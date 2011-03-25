<?php

class SettingsForm extends Zend_Form {

	private $_inputText       = '';

	private $_errorText       = '';

	private $_imagePath       = '';

	private $_imageExt        = '';

	private $_imageWidth      = '';

	private $_imageHeight     = '';

	private $_redirectSuccess = '';

	private $_redirectFail    = '';

	public function init() {
		$this->setMethod('post');
		$this->setAttrib('id', 'frm_settings');

		$this->addElement('text', 'inputText', array(
			'id'       => 'input_text',
			'label'    => 'Text will be shown near the captcha input field',
			'value'    => $this->_inputText,
			'filters'  => array('StringTrim')
		));

		$this->addElement('text', 'errorText', array(
			'id'       => 'error_text',
			'label'    => 'Captcha error text',
			'value'    => $this->_errorText,
			'filters'  => array('StringTrim')
		));

		$this->addElement('text', 'imagePath', array(
			'id'       => 'image_path',
			'label'    => 'Path to store captcha images',
			'value'    => $this->_imagePath,
			'filters'  => array('StringTrim')
		));

		$this->addElement('text', 'imageExt', array(
			'id'       => 'image_ext',
			'label'    => 'Extension for captcha image',
			'value'    => $this->_imageExt,
			'filters'  => array('StringTrim')
		));

		$this->addElement('text', 'imageWidth', array(
			'id'       => 'image_width',
			'label'    => 'Captcha width',
			'value'    => $this->_imageWidth,
			'filters'  => array('StringTrim')
		));

		$this->addElement('text', 'imageHeight', array(
			'id'       => 'image_height',
			'label'    => 'Captcha height',
			'value'    => $this->_imageHeight,
			'filters'  => array('StringTrim')
		));

		$this->addElement('text', 'redirectSuccess', array(
			'id'       => 'redirect_success',
			'label'    => 'Success redirect url',
			'value'    => $this->_redirectSuccess,
			'filters'  => array('StringTrim')
		));

		$this->addElement('text', 'redirectFail', array(
			'id'       => 'redirect_fail',
			'label'    => 'Fail redirect url',
			'value'    => $this->_redirectFail,
			'filters'  => array('StringTrim')
		));

		$this->addElement('submit', 'submit', array(
			'label'  => 'Apply setings',
			'id'     => 'settings_submit',
			'ignore' => true
		));
	}

	public function getInputText() {
		return $this->_inputText;
	}

	public function setInputText($inputText) {
		$this->_inputText = $inputText;
		return $this;
	}

	public function getImagePath() {
		return $this->_imagePath;
	}

	public function setImagePath($imagePath) {
		$this->_imagePath = $imagePath;
		return $this;
	}

	public function getImageExt() {
		return $this->_imageExt;
	}

	public function setImageExt($imageExt) {
		$this->_imageExt = $imageExt;
		return $this;
	}

	public function getImageWidth() {
		return $this->_imageWidth;
	}

	public function setImageWidth($imageWidth) {
		$this->_imageWidth = $imageWidth;
		return $this;
	}

	public function getImageHeight() {
		return $this->_imageHeight;
	}

	public function setImageHeight($imageHeight) {
		$this->_imageHeight = $imageHeight;
		return $this;
	}

	public function getErrorText() {
		return $this->_errorText;
	}

	public function setErrorText($errorText) {
		$this->_errorText = $errorText;
		return $this;
	}

	public function getRedirectSuccess() {
		return $this->_redirectSuccess;
	}

	public function setRedirectSuccess($redirectSuccess) {
		$this->_redirectSuccess = $redirectSuccess;
		return $this;
	}

	public function getRedirectFail() {
		return $this->_redirectFail;
	}

	public function setRedirectFail($redirectFail) {
		$this->_redirectFail = $redirectFail;
		return $this;
	}

}

