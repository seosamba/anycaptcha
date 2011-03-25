<?php

set_include_path(implode(PATH_SEPARATOR, array(
	dirname(__FILE__) . '/system/classes/forms',
	dirname(__FILE__) . '/system/classes/dbtables',
    get_include_path(),
)));


/**
 * Anycaptcha plugin
 *
 */
class Anycaptcha implements RCMS_Core_PluginInterface {

	const ACTION_POSTFIX     = 'Action';
	
	private $_options        = array();

	private $_seotoasterData = array();

	private $_session        = null;

	private $_view           = null;

	private $_websiteUrl     = null;

	private $_request        = null;

	private $_redirector     = null;

	private $_dbTalbeSettings = null;

	public function  __construct($options, $seotoasterData) {
		$this->_options         = $options;
		$this->_seotoasterData  = $seotoasterData;
		$this->_websiteUrl      = $this->_seotoasterData['websiteUrl'];
		$this->_request         = new Zend_Controller_Request_Http();
		$this->_redirector      = new Zend_Controller_Action_Helper_Redirector();
		$this->_session         = new Zend_Session_Namespace($this->_websiteUrl);
		$this->_dbTalbeSettings = new CaptchaSettings();
		$this->_view            = new Zend_View(array(
			'scriptPath' => dirname(__FILE__) . '/views'
		));
		if($this->_seotoasterData['url']) {
			$this->_session->pluginPageUrl  = $this->_websiteUrl . $this->_seotoasterData['url'] . '.html';
		}
		$this->_view->websiteUrl        = $this->_websiteUrl;
	}

	public function  run($requestedParams = array()) {
		if(!isset($this->_session->captchaError)) {
			$this->_session->captchaError = array();
		}
		$optionResult = $this->_dispatchOptions();
		if($optionResult !== null) {
			return $optionResult;
		}
		$this->_dispatch($requestedParams);
		$captchaId                     = RCMS_Tools_Tools::generateCaptcha();
		$this->_session->captchaId     = $captchaId;
		$this->_view->captchaId        = $captchaId;
		$this->_view->captchaSettings  = $this->_dbTalbeSettings->fetchAllConfig();
		$this->_view->savedParams      = $this->_session->savedParams;
		$this->_session->savedParams   = array();
		return $this->_view->render('captcha.phtml');
	}

	private function _dispatchOptions() {
		if(!empty($this->_options)) {
			foreach ($this->_options as $option) {
				$optionMakerName = '_makeOption' . ucfirst($option);
				if(in_array($optionMakerName, get_class_methods(__CLASS__))) {
					return $this->$optionMakerName();
				}
				return '<strong>Invalid option</strong>';
			}
		}
	}

	private function _makeOptionProxy() {
		return $this->_websiteUrl . 'plugin/anycaptcha/run/handler';
	}

	private function _makeOptionMessage() {
		$this->_view->messages = $this->_session->captchaError;
		$this->_session->captchaError = array();
		return $this->_view->render('messages.phtml');
	}

	private function _dispatch($params) {
		$action = ($params['run']) ? $params['run'] . self::ACTION_POSTFIX : null;
		if($action !== null) {
			if(in_array($action, get_class_methods(__CLASS__))) {
				$this->$action();
				exit;
			}
		}
	}

	/**
	 * 
	 * @todo modify this meth to use zend + altered post params
	 */
	public function handlerAction() {
		$params          = $this->_request->getParams();
		$captchaSettings = $this->_dbTalbeSettings->fetchAllConfig();
		$captchaWord     = $params['captcha'];
		$realAction      = $params['realAction'];
		$captcha         = new Zend_Session_Namespace('Zend_Form_Captcha_' . $this->_session->captchaId);
		$captchaIterator = $captcha->getIterator();
		if($captchaWord == $captchaIterator['word']) {
			unset($params['captcha']);
			unset ($params['realAction']);

			$httpClient = new Zend_Http_Client($realAction);
			$httpClient->setRawData(http_build_query($params));
			$response = $httpClient->request(Zend_Http_Client::POST);
			if($response->getStatus() != 200) {
				$this->_session->captchaError[] = 'Faild to send form';
				if(isset($captchaSettings['redirectFail'])) {
					$this->_redirector->gotoUrlAndExit($captchaSettings['redirectFail']);
				}
				$this->_session->savedParams    = $params;
				$this->_redirector->gotoUrlAndExit(($this->_session->pluginPageUrl) ? $this->_session->pluginPageUrl : $this->_websiteUrl);
			}
			else {
				if(isset($captchaSettings['redirectSuccess'])) {
					$this->_redirector->gotoUrlAndExit($captchaSettings['redirectSuccess']);
				}
				$this->_session->captchaError[] = 'Form sent.';
				$this->_redirector->gotoUrlAndExit(($this->_session->pluginPageUrl) ? $this->_session->pluginPageUrl : $this->_websiteUrl);
			}
		}
		$this->_session->captchaError[] = $captchaSettings['errorText'];
		$this->_session->savedParams    = $params;
		$this->_redirector->gotoUrlAndExit(($this->_session->pluginPageUrl) ? $this->_session->pluginPageUrl : $this->_websiteUrl);
	}

	public function settingsAction() {
		if(!$this->_request->isPost()) {
			$settingsForm    = new SettingsForm($this->_dbTalbeSettings->fetchAllConfig());
			$this->_view->settingsForm = $settingsForm;
		}
		elseif ($this->_request->isXmlHttpRequest()) {
			$data = $this->_request->getParams();
			$result = $this->_dbTalbeSettings->updateSettings($data);
			exit;
		}
		echo $this->_view->render('settings.phtml');
	}
}
