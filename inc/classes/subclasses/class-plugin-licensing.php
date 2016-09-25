<?php
class Plugin_lisense extends Plugin_Core{
	public  function __construct(){	

		try{
		//add_action( 'wp', 'prefix_setup_schedule' );
		add_action( 'wp', array(&$this , '_lisensing_setup_schedule') );
		add_action('lisensing_daily_event', array(&$this , '_lisensing_this_daily'));
		// Clear the event schedule on plugin deactivation...
		register_deactivation_hook(__FILE__, array(&$this , '_lisensing_deactivation'));
	} catch (Exception $e) {
		echo json_encode(array('error'=>$e->getMessage()));
		$errorlogger = new ErrorLogger();
		$errorlogger->add_message($e->getMessage());
		exit;
	}
	}
	
	public function _isLicenseActive(){
		$secret_key = self::$current_plugin_data['LICENSE_SECRET'];
		if ($secret_key == 'RGVtbyBMaWNlbnNl') {
			return $secret_key;	
		}		
		try {		
			$plugin_settings = new Plugin_Settings();
			if (!empty($plugin_settings->licensekey)) {
				return $plugin_settings->licensekey ;
			}
			return false;
		} catch (Exception $e) {
			echo json_encode(array('error'=>$e->getMessage()));
			$errorlogger = new ErrorLogger();
			$errorlogger->add_message($e->getMessage());
			exit;
		}
	}
	
	public function _deactivateLicense(){
		try {
			self::__call('_updateLicenseKey',array(''));
			exit;
		} catch (Exception $e) {
			echo json_encode(array('error'=>$e->getMessage()));
			$errorlogger = new ErrorLogger();
			$errorlogger->add_message($e->getMessage());
			exit;
		}
	}
	

	public function _activation_page(){
		global $p_set,$p_data;
		$page_param = array(
				'p_data' => self::$current_plugin_data,
				'p_set' => array('_key'=>'_licensing','Title'=>'Plugin Licensing','MenuText'=>'Activate','capability'=> 'manage_options','callback' => '_option_page_sub','page-style'=>true,'page-script'=>true ,'scriptDep' => array('jquery','common-data','plugin_utilities'))
		);
		extract($page_param);
		
		$page_stylefile = 'style_licensing.css';
		if(file_exists(WPHT_PLUGIN_PAGES.'/css/'.$page_stylefile))
			wp_enqueue_style( self::$current_plugin_data['TextDomain'].'-licensing-style',WPHT_PLUGIN_CSSDIR_URL.'/'.$page_stylefile,array(),self::$current_plugin_data['Version']);
		$page_scriptfile = 'script_licensing.js';
		if(file_exists(WPHT_PLUGIN_PAGES.'/js/'.$page_scriptfile))
			wp_enqueue_script( self::$current_plugin_data['TextDomain'].'-licensing-script',WPHT_PLUGIN_JSDIR_URL.'/'.$page_scriptfile,array('jquery','common-data','bootbox.min','plugin_utilities'),self::$current_plugin_data['Version'],true);
		
		include_once WPHT_PLUGIN_PAGES.'/page_licensing.php';
	}
	
	public function _expire_page(){
		global $p_set,$p_data;
		$page_param = array(
				'p_data' => self::$current_plugin_data,
				'p_set' => array('_key'=>'_expired','Title'=>'Plugin expired','MenuText'=>'Expired','capability'=> 'manage_options','callback' => '_option_page_sub','page-style'=>true,'page-script'=>true ,'scriptDep' => array())
		);
		extract($page_param);
	
		$page_stylefile = 'style_expired.css';
		if(file_exists(WPHT_PLUGIN_PAGES.'/css/'.$page_stylefile))
			wp_enqueue_style( self::$current_plugin_data['TextDomain'].'-expired-style',WPHT_PLUGIN_CSSDIR_URL.'/'.$page_stylefile,array(),self::$current_plugin_data['Version']);
		$page_scriptfile = 'script_expired.js';
		if(file_exists(WPHT_PLUGIN_PAGES.'/js/'.$page_scriptfile))
			wp_enqueue_script( self::$current_plugin_data['TextDomain'].'-expired-script',WPHT_PLUGIN_JSDIR_URL.'/'.$page_scriptfile,array('jquery','common-data','bootbox.min','plugin_utilities'),self::$current_plugin_data['Version'],true);
	
		include_once WPHT_PLUGIN_PAGES.'/page_expired.php';
	}

	public function _activateLicense(){
		try {
			//error_reporting(0);
				$post_data = $_POST['data'];
                $license_key = $post_data['licensekey'];
                $wpnonce = $post_data[self::$current_plugin_data['TextDomain'].'_licensekey_nonce'];

			
			$secret_key = self::$current_plugin_data['LICENSE_SECRET'];
			$siteurl = get_site_url(); 
			$requParameters = "/?edd_action=activate_license&item_name={$secret_key}&license={$license_key}&url={$siteurl}";
			
			$api_url = self::$current_plugin_data['LICENSE_SERVER_URL'] . "{$requParameters}";
			$md5_hash = md5($license_key . $secret_key);
			
			
			
			if ( !wp_verify_nonce($wpnonce, self::$current_plugin_data['TextDomain'].'_nonce') ) {  
				echo json_encode(array('error'=>'Security check : This nonce is not valid'));
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message('Security check : This nonce is not valid');
				wp_die( 'Security check' ); exit;}// This nonce is not valid
			
			
			// Grab the user info and submit the data to the license server...
			$hostname = @gethostbyaddr($_SERVER['SERVER_ADDR']);
			$data = array(
					'domain' => $_SERVER['HTTP_HOST'],
					'userip' => $_SERVER['REMOTE_ADDR'],
					'servip' => ( $hostname ) ? $hostname : $_SERVER['SERVER_ADDR']
			);
			
			$call = wp_remote_post(
					$api_url,
					array(
							'method' => 'POST',
							'timeout' => 45,
							'redirection' => 5,
							'httpversion' => '1.1',
							'blocking' => true,
							'headers' => array(
									'Authorization' => 'Basic ' . base64_encode( $secret_key . ':' . $md5_hash )
							),
							'body' => $data
					)
			);
			
			//var_dump($call);
			
			if ( is_wp_error($call) ){
				$error_msg = $call->get_error_message();
				echo json_encode(array('error'=>$error_msg,'api_url'=>$api_url));
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($error_msg);
				//wp_die('ERROR: ' . $error_msg);
				exit;
			}
			
			$response = json_decode($call['body']);
			//echo '<pre>'; print_r($response); echo '</pre>';
			if ( $response->success == true ) {				
				self::__call('_updateLicenseKey',array(base64_encode($license_key)));
				exit;
			} else {
				// code to handle activation error here...
				$error_msg = $response->error == 'expired' ? 'Licens key is expired' : 'Invalid licens key';
				echo json_encode(array('error'=>$error_msg));
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($error_msg);
				//wp_die('<p style="color:#cc0000">ERROR: ' . $error_msg . '</p>');
				exit;
			}
			exit;
		} catch (Exception $e) {
			echo json_encode(array('error'=>$e->getMessage()));
			$errorlogger = new ErrorLogger();
			$errorlogger->add_message($e->getMessage());
			exit;
		}
	}
	
	
	/**
	 * Function to check the status of the license key
	 *
	 */
	function _lisensing_key_status( $license_key ) {
		try {
			$license_key = base64_decode($license_key);
			$secret_key = self::$current_plugin_data['LICENSE_SECRET'];
			
			$siteurl = get_site_url();
			$requParameters = "/?edd_action=check_license&item_name={$secret_key}&license={$license_key}&url={$siteurl}";
			
			$api_url = self::$current_plugin_data['LICENSE_SERVER_URL'] . "{$requParameters}";
			$md5_hash = md5($license_key . $secret_key);
			
			// Grab the user info and submit the data to the license server...
			$hostname = @gethostbyaddr($_SERVER['SERVER_ADDR']);
			$data = array(
					'domain' => $_SERVER['HTTP_HOST'],
					'userip' => $_SERVER['REMOTE_ADDR'],
					'servip' => ( $hostname ) ? $hostname : $_SERVER['SERVER_ADDR']
			);
			
			$call = wp_remote_post(
					$api_url,
					array(
							'method' => 'POST',
							'timeout' => 45,
							'redirection' => 5,
							'httpversion' => '1.1',
							'blocking' => true,
							'headers' => array(
									'Authorization' => 'Basic ' . base64_encode( $secret_key . ':' . $md5_hash )
							),
							'body' => $data
					)
			);
			
			// if the call is error, then we can simply return it as a false
			// more info about the 'is_wp_error' function: http://codex.wordpress.org/Function_Reference/is_wp_error
			if ( is_wp_error($call) ) return false;
			//echo '<pre>'; print_r($call); echo '</pre>';
			$response = json_decode($call['body']);
			return $response;
		} catch (Exception $e) {
			$errorlogger = new ErrorLogger();
			$errorlogger->add_message($e->getMessage());
		}
	}
	
	
	/**
	 * On an early action hook, check if the hook is scheduled - if not, schedule it.
	*/
	public function _lisensing_setup_schedule() {
		if ( !wp_next_scheduled('lisensing_daily_event') ) {
			wp_schedule_event( time(), 'hourly', 'lisensing_daily_event');
		}
	}
	

	public function _lisensing_this_daily() {
		try {
			$errorlogger = new ErrorLogger();
			// First, let's check if this plugin has been activated or not...
			if ( !$license_key = $this->_isLicenseActive() ){ $errorlogger->add_message('Plugin Not active'); return false; }// The license hasn't been activated yet, then simply return this...						
			
			// if the license key is exists on the db, we must validate it...
			$response = $this->_lisensing_key_status($license_key);
			
			if  ( $response->success == true && $response->license =='valid') {
				// The license key is active and it's still valid...
				// So we simply don't do anything...
				//$errorlogger->add_message('Plugin hourly license check and it is valid');
				return true;
			} else if ( $response->success == true && ($response->license =='inactive' || $response->license =='invalid') ) {
				self::__call('_updateLicenseKey',array(''));
			} else {
				// the license key is not valid...
				// let's reset this plugin state to before 'activation'
				//$errorlogger->add_message($response->status);
				//$errorlogger->add_message('License key : '.base64_decode($license_key));
				self::__call('_updateLicenseKey',array(''));
			}
		} catch (Exception $e) {			
			$errorlogger = new ErrorLogger();
			$errorlogger->add_message($e->getMessage());
			exit;			
		}
	}
	
	
	public function _lisensing_deactivation() {
		wp_clear_scheduled_hook('lisensing_daily_event');
	}
	
}