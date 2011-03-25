<?php

class CaptchaSettings extends Zend_Db_Table_Abstract {

	protected $_name = 'captcha_settings';


	public function fetchAllConfig() {
		$config  = array();
		$rows = $this->fetchAll()->toArray();
		foreach ($rows as $kye => $data) {
			$config[$data['name']] = $data['value'];
		}
		return $config;
	}

	public function updateSettings($data) {
		foreach ($data as $name => $value) {
			$updData = array(
				'value' => $value
			);
			$this->update($updData, $this->getAdapter()->quoteInto('name=?', $name));
		}
	}
}

