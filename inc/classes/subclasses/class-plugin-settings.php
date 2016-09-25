<?php
class Plugin_Settings extends  Plugin_Core{
	public $settingdomaintext;
	public $version;
	public $licensekey;
	public $settings;	
	public function __construct(){
		$this->settingdomaintext = Plugin_Core::$current_plugin_data['TextDomain'].'_settings';
		$this->version = Plugin_Core::$current_plugin_data['Version'];
		$this->settings = new SettingsKey();
		//$this->licensekey = "Anushka";
		$this->GetSettings();
	}
	
	private function SaveSetiings(){
		try{
			$_obj = Plugin_Utilities::extractDataToOjbect($this);
			if ($_obj == null) {
				echo json_encode(array('error'=>'Unable to extract object data'));
				exit;
			}
			update_option($this->settingdomaintext, maybe_serialize($this));
			echo json_encode($this);
			exit;
		}catch(Exception $e){
			echo json_encode(array('error'=>$e->getMessage()));
			$errorlogger = new ErrorLogger();
			$errorlogger->add_message($e->getMessage());
			exit;
		}		
	}
	
	public function _updateLicenseKey($license_key){
		try {
			$this->GetSettings();
			$this->licensekey = $license_key ;
			$this->UpdateSettings();
		} catch (Exception $e) {
			echo json_encode(array('error'=>$e->getMessage()));
			$errorlogger = new ErrorLogger();
			$errorlogger->add_message($e->getMessage());
			exit;
		}
	}
	
	private function UpdateSettings(){
		try {
			update_option($this->settingdomaintext, maybe_serialize($this));
			echo json_encode($this);
		} catch (Exception $e) {
			echo json_encode(array('error'=>$e->getMessage()));
			$errorlogger = new ErrorLogger();
			$errorlogger->add_message($e->getMessage());
			exit;
		}
	}
	
	
	private function GetSettings(){
		try {
			$results =  get_option($this->settingdomaintext); 			
			if($results){
				$results = (array)unserialize($results);				
				//Plugin_Utilities::injectObjectData($results, $this);
				$this->licensekey = $results['licensekey'];
				Plugin_Utilities::injectObjectData((array)$results['settings'], $this->settings);				
			}
		} catch (Exception $e) {
			echo json_encode(array('error'=>$e->getMessage()));
			$errorlogger = new ErrorLogger();
			$errorlogger->add_message($e->getMessage());
			exit;
		}
	}
	
	public function _saveSettings(){
		try {
			$this->SaveSetiings();			
		} catch (Exception $e) {
			echo json_encode(array('error'=>$e->getMessage()));
			$errorlogger = new ErrorLogger();
			$errorlogger->add_message($e->getMessage());
			exit;
		}
	}
	
	public function _getSettings(){
		try {
			$this->GetSettings();
			echo json_encode($this);
			exit;
		} catch (Exception $e) {
			echo json_encode(array('error'=>$e->getMessage()));
			$errorlogger = new ErrorLogger();
			$errorlogger->add_message($e->getMessage());
			exit;
		}
	}
	
	public function _init(){
		try {
			$this->GetSettings();
		} catch (Exception $e) {
			echo json_encode(array('error'=>$e->getMessage()));
			$errorlogger = new ErrorLogger();
			$errorlogger->add_message($e->getMessage());
			exit;
		}
	}
}

class SettingsKey{
	public $headerScript;
	public $footerScript;	
	public function __construct(){
		$this->headerScript = '';
		$this->footerScript = '';
	}
}