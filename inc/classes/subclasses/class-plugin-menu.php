<?php
class Plugin_menu extends Plugin_Core{
	
	private $key;
	private $key_sub;
	
	public function __construct(){}
	
	public function menuInit(){
		if (is_admin()) {						
			$admin_page = add_menu_page(  
					self::$current_plugin_data['Name'] . ' Options',
					self::$current_plugin_data['Name'],
					'manage_options',
					self::$current_plugin_data['TextDomain'],
					array( &$this, '_options_page'),
					WPHT_PLUGIN_IMGDIR_URL.'/'.self::$current_plugin_data['PluginIcon']
					);
			foreach (init_var::_getMenuItems() as $key => $value) {
				add_submenu_page(self::$current_plugin_data['TextDomain'], self::$current_plugin_data['Name'].' - '.$value['Title'], $value['MenuText'], $value['capability'], self::$current_plugin_data['TextDomain'].$key,array(&$this,$value['callback']));
				if(array_key_exists('subpages', $value)){
					foreach ($value['subpages'] as $key_sub => $value_sub) {						
						$_this = new Plugin_menu();
						$_this->key = $key;
						$this->key_sub = $key_sub;
						add_submenu_page(self::$current_plugin_data['TextDomain'].$key, self::$current_plugin_data['Name'].' - '.$value_sub['Title'], $value_sub['MenuText'], $value_sub['capability'], self::$current_plugin_data['TextDomain'].$key_sub,array($_this,'_option_page_sub_v1'));
					}
				}
			}					
		}		
	}
	
	public function inactiveMenuInit(){
		if (is_admin()) {	
			$admin_page = add_menu_page(
					self::$current_plugin_data['Name'] . ' Options',
					self::$current_plugin_data['Name'],
					'manage_options',
					self::$current_plugin_data['TextDomain'].'_licensing',
					array( &$this, '_activation_page')/*,
					WPHT_PLUGIN_IMGDIR_URL.'/'.self::$current_plugin_data['PluginIcon']*/
			);
			
		}
	}
	
	public function _options_page(){
		$page_settings = init_var::_getMainMenuItems();
		$page_param = array(
				'p_data' => self::$current_plugin_data,
				'p_set' => $page_settings,
		);
		global $p_set,$p_data;
		extract($page_param);
		include_once WPHT_PLUGIN_PAGES.'/page-main.php';
	}
	
	public function _option_page_sub(){
		if(is_admin() && isset($_REQUEST['page'])){
			$page_slug = str_replace(self::$current_plugin_data['TextDomain'], '', $_REQUEST['page']);
			$page_settings =  init_var::_getMenuItems();
			$filename = WPHT_PLUGIN_PAGES.'/page'.$page_slug.'.php';
 			$page_param = array(
					'p_data' => self::$current_plugin_data,
					'p_set' => $page_settings[$page_slug],
 					'f_name' => $filename
			);			
			global $p_set,$p_data,$f_name;
			extract($page_param);
			if(file_exists($filename))
				include_once $filename;
			else
				include_once WPHT_PLUGIN_PAGES.'/page-404.php';
		}
	}
	
	function _option_page_sub_v1(){
		if(is_admin() && isset($_REQUEST['page'])){
			echo '<h1>Test '.$this->key.' -- '.$this->key_sub.'</h1>';
			$page_settings =  init_var::_getMenuItems();
			$page_settings = $page_settings[$this->key]['subpages'];
			$filename = WPHT_PLUGIN_PAGES.'/page'.$this->key_sub.'.php';
			$page_param = array(
					'p_data' => Plugin_Core::$current_plugin_data,
					'p_set' => $page_settings[$this->key_sub],
					'f_name' => $filename
			);
			global $p_set,$p_data,$f_name;
			extract($page_param);
			if(file_exists($filename))
				include_once $filename;
			else
				include_once WPHT_PLUGIN_PAGES.'/page-404.php';
		}
	}
}