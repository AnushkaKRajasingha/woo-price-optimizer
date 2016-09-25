<?php
class init_var{
	public  function  __construct() {}
	
	public function _initVar(){
		require_once 'plugin-config.php';
		foreach ($WPHT_var_array as $key => $value) {
			if(!defined($key)){
				define($key, $value);
			}
		}
	}
	
	public static function _getMainMenuItems($path = null) {
		if($path == null){ $path = 'plugin-config.php';}
		include $path;
		return $plugin_main_page;
	}
	
	public static function _getMenuItems($path = null) {
		if($path == null){ $path = 'plugin-config.php';}
		include $path;
		return $plugin_menu_items;
	}
	
	public static function _addMenuItems($menuitem = null,$path = null) {
		if($path == null){ $path = 'plugin-config.php';}
		include $path;
		$plugin_menu_items[$menuitem['_key']] = $menuitem;
		//var_dump($plugin_menu_items);
		return $plugin_menu_items;
	}
		
	public static function _getDbTables($path = null) {
		if($path == null){ $path = 'plugin-config.php';}
		include $path;
		return $db_tables;
	}
	
	public static function _getSchedules($path = null) {
		if($path == null){ $path = 'plugin-config.php';}
		include $path;
		return $plugin_Schedules;
	}
	
	public static function getSysMsg($id,$path = null){
		if($path == null){ $path = 'plugin-config.php';}
		include $path;
		if(!array_key_exists($id, $sysMsg)) return 'Undefined message';
		return $sysMsg[$id];
	}
	
	public static function _getMethodAjax($path = null) {
		if($path == null){ $path = 'plugin-config.php';}
		include $path;
		return $methods_ajax;
	}
	
	public static function _getDefaultCssSettings($path = null) {
		if($path == null){ $path = 'plugin-config.php';}
		include $path;
		return $default_css_settings;
	}	
	
	public static function _getShortCodeSettings($path = null) {
		if($path == null){ $path = 'plugin-config.php';}
		include $path;
		return $ShortCodeSettings;
	}
	
	public static function _getUSStates($path = null) {
		if($path == null){ $path = 'plugin-config.php';}
		include $path;
		return $states;
	}
	/* DB changes ececution by AKR on 06122015 */
	public static function _getDbUpdates(){
		$_path = WPHT_CLS_DIR.'/db/update.sql';
		$_file = fopen($_path, 'r');
		$_scripts = array();
		while (!feof($_file)) {
			$_scripts[] = fgets($_file);
		}
		fclose($_file);
		return $_scripts;
	}	
	/* DB changes ececution by AKR on 06122015 - ends */
}