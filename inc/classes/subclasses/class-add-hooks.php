<?php
class Add_Hooks extends Plugin_Core{
	public function __construct(){
	//	if(self::$current_plugin_data['Environment'] == 'Dev'){set_error_handler('var_dump');}
		$this->_addHook();
		register_activation_hook( WPHT_PLUGINDIR.'/'.WPHT_PLUGIN_FILE,array($this,'_activate'));
		register_deactivation_hook( WPHT_PLUGINDIR.'/'.WPHT_PLUGIN_FILE,array($this,'_deactivate'));
		$this->_regAjaxMethosd();
		$this->_registerHelperFuncs();
		$this->_regPluginShortCodes();		
	}
	
	public function _addHook(){
		add_filter('_isLicenseActive',array( &$this, '_isLicenseActive' ));
		
		if(apply_filters('_isLicenseActive',self::$current_plugin_data['TextDomain'])){
			add_action('wp_head',array(&$this,'_customPluginHeader'));
			add_action('wp_footer',array(&$this,'_customPluginFooter'));
			add_action( 'admin_menu', array( &$this, 'menuInit' ) );
			/* Plugin Loded Hook */
			add_action( 'plugins_loaded',array(&$this, 'mq_plugins_loaded' ));
			//add_action( 'template_redirect', array(&$this,'_loadTemplate'), 1 );
		}
		else{
			add_action( 'admin_menu', array( &$this, 'inactiveMenuInit' ) );
		}
		add_action(self::$current_plugin_data['TextDomain'].'_script_localiztion', array(&$this,'_scriptLocalization'));
		add_action('admin_enqueue_scripts', array( &$this, 'scriptInit' ));		
	}		
	
	public function _activate(){
		//update_option('WOOPO-settings', 'sadasdasdasd');
		add_option(self::$current_plugin_data['TextDomain'].'-settings', maybe_serialize(self::$current_plugin_data) );
		global $wpdb;
		foreach (init_var::_getDbTables() as $key => $value) {
			$table_name = $wpdb->prefix .self::$current_plugin_data['TextDomain'].$key;
			$value = str_replace("@table_name",$table_name,$value);
			if($wpdb->get_var("SHOW TABLES LIKE '".$table_name."'") != $table_name){
				$wpdb->query($value);
			}
		}
		
		
		foreach (init_var::_getSchedules() as $schedualkey => $schedualvalue) {
			if (! wp_next_scheduled ( $schedualvalue['func'] )) {
				wp_schedule_event(time(),  $schedualvalue['frequent'],  $schedualvalue['func']);
			}
		}
		
	}
	
	public function mq_plugins_loaded(){
		try {
			$plugin_settings = get_option(self::$current_plugin_data['TextDomain'].'-settings');
			if (!empty($plugin_settings)) {
				$plugin_settings = unserialize($plugin_settings);
			}		
			
			if (self::$current_plugin_data['DBVersion'] > $plugin_settings['DBVersion']) {				
				/* DB changes ececution by AKR on 06122015 */
				global $wpdb;
				$_dbupdateScript = init_var::_getDbUpdates();
				if($_dbupdateScript != null){
					if(self::$current_plugin_data['Environment'] != 'Dev'){ $wpdb->show_errors = false;}
					foreach ($_dbupdateScript as $_query) {
						$_query = str_replace('@wpprefix', $wpdb->prefix, $_query);
						$_query = str_replace('@textdomain', self::$current_plugin_data['TextDomain'], $_query);
						$wpdb->query($_query);
					}
					if(self::$current_plugin_data['Environment'] != 'Dev'){ /*var_dump($wpdb->last_query);*/ $wpdb->show_errors = true;  add_action( 'admin_notices', array(&$this,'mq_db_lastexecutedquery_notice') ); }
				}
				/* DB changes ececution by AKR on 06122015 */
				$plugin_settings['DBVersion'] = self::$current_plugin_data['DBVersion'];
				update_option(self::$current_plugin_data['TextDomain'].'-settings', maybe_serialize($plugin_settings) );
				
				add_action( 'admin_notices', array(&$this,'mq_db_update_admin_notice') );
				
			}
			
			
			if (self::$current_plugin_data['Version'] > $plugin_settings['Version']) {				
				$plugin_settings = self::$current_plugin_data;
				update_option(self::$current_plugin_data['TextDomain'].'-settings', maybe_serialize($plugin_settings) );
				add_action( 'admin_notices', array(&$this,'mq_plugin_update_admin_notice') );
			}
			
			
			
		} catch (Exception $e) {
		}
	}
	
	public function mq_db_lastexecutedquery_notice(){
		if(self::$current_plugin_data['Environment'] == 'Dev'){
			global $wpdb;
			?>
					<div class="notice notice-success updated notice-large is-dismissible" >
					<p>
					<?php _e( 'Last executed query : '.$wpdb->last_query . PHP_EOL . 'Last eoor : '.$wpdb->last_error, self::$current_plugin_data['TextDomain'] ); ?>
					</p>
					</div>
					<?php 
			}
	}
	
	public function mq_db_update_admin_notice(){
		?>
			<div class="notice notice-success updated notice-large is-dismissible" >
		        <p><?php _e( 'The plugin '.self::$current_plugin_data['Name'].' database changes has been applied.', self::$current_plugin_data['TextDomain'] ); ?></p>
		    </div>
		<?php		
	}
	
	public function mq_plugin_update_admin_notice(){
		?>
					<div class="notice notice-success updated notice-large is-dismissible" >
				        <p><?php _e( 'The plugin '.self::$current_plugin_data['Name'].' has been updated, excellent!', self::$current_plugin_data['TextDomain'] ); ?></p>
				    </div>
				<?php
	}
	
	public function mq_plugin_admin_notice(){
		global $msg_class, $msg_note;
		?>
						<div class="notice <?php echo $msg_class; ?> notice-large is-dismissible" >
					        <p><?php _e( $msg_note, self::$current_plugin_data['TextDomain'] ); ?></p>
					    </div>
					<?php
		}
	
	
	
	public function _deactivate(){
		delete_option(self::$current_plugin_data['TextDomain'].'-settings');
		if (self::$current_plugin_data['DbRemove']=='Yes') {
			global $wpdb;
			foreach (init_var::_getDbTables() as $key => $value) {
				$table_name = $wpdb->prefix .self::$current_plugin_data['TextDomain'].$key;
				$results = $wpdb->query("drop table ".$table_name." ;");
			}
		}
		
		foreach (init_var::_getSchedules() as $schedualkey => $schedualvalue) {			
			wp_clear_scheduled_hook($schedualvalue['func']);
		}
	}
	
	private function _regAjaxMethosd(){
		foreach (init_var::_getMethodAjax() as $key => $value) {
			$single_action = $value['func'];
			add_action( 'wp_ajax_'.self::$current_plugin_data['TextDomain'].$key, array( &$this, $single_action ) );
			if($value['user'])
				add_action( 'wp_ajax_nopriv_'.self::$current_plugin_data['TextDomain'].$key, array( &$this, $single_action ) );
		}
	}
	
	private function _regPluginShortCodes(){
		foreach (init_var::_getShortCodeSettings() as $key => $value) {
			add_shortcode($key, array( &$this,$value));
		}
	}
	
	/* added new private hook req#0006 */
	private function _registerHelperFuncs(){
		add_filter(self::$current_plugin_data['TextDomain'].'_setRadioBtn',array( &$this, '_setRadioBtn' ),10,4);
	}
	/* added new private hook req#0006 */
}