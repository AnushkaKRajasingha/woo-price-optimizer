<?php
class Plugin_Header extends Plugin_Core{
	public function __construct(){
	}
	
	public function _customPluginHeader(){
		try {
			$plugin_settings = new Plugin_Settings();
			$plugin_settings->_init();
			if(!empty($plugin_settings->settings->headerScript))
				echo $plugin_settings->settings->headerScript;
		} catch (Exception $e) {
			echo json_encode(array('error'=>$e->getMessage()));
			$errorlogger = new ErrorLogger();
			$errorlogger->add_message($e->getMessage());
			exit;
		}
	}
	
	public function _customPluginFooter(){
		try {
			$plugin_settings = new Plugin_Settings();
			$plugin_settings->_init();
			if(!empty($plugin_settings->settings->footerScript))
				echo $plugin_settings->settings->footerScript;
		} catch (Exception $e) {
			echo json_encode(array('error'=>$e->getMessage()));
			$errorlogger = new ErrorLogger();
			$errorlogger->add_message($e->getMessage());
			exit;
		}
	}
}