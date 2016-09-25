<?php
class Loader{
	public function __construct(){}
	public function classloader(){
		if (is_admin()) {
			$classes = array( 
					'ErrorLogger' => WPHT_CLS_DIR.'/subclasses/class-plugin-errorlogger.php',
					'Plugin_Utilities' 	=> WPHT_CLS_DIR.'/subclasses/class-plugin-utilities.php',
					'Plugin_Settings' =>  WPHT_CLS_DIR.'/subclasses/class-plugin-settings.php',
					'Plugin_lisense' =>  WPHT_CLS_DIR.'/subclasses/class-plugin-licensing.php',
					'Plugin_Header' => WPHT_CLS_DIR.'/subclasses/class-plugin-wpheader.php',					
					'Add_Hooks' 		 => WPHT_CLS_DIR.'/subclasses/class-add-hooks.php',
					'Plugin_Menu' 		 => WPHT_CLS_DIR.'/subclasses/class-plugin-menu.php',
					'Plugin_Scripts' 	=> WPHT_CLS_DIR.'/subclasses/class-plugin-script.php',
					'Plugin_Functions' 	=> WPHT_CLS_DIR.'/subclasses/class-plugin-functions.php',
					'Plugin_page' 	=> WPHT_CLS_DIR.'/subclasses/class-plugin-pages.php',
					
					'clsDbBase' => WPHT_CLS_DIR.'/subclasses/classes/class-plugin-dbbase.php',
					'IclsStatusBase'	=> 	WPHT_CLS_DIR.'/subclasses/classes/class-plugin-statusbase.php',
					'clsPluginBase' => WPHT_CLS_DIR.'/subclasses/classes/class-plugin-baseclass.php',
					'clsVisitorHandler' => WPHT_CLS_DIR.'/subclasses/class-plugin-visitors.php',
					
			);
			$this->register_classes( $classes ); //var_dump(Plugin_Core::$class_array);
		}elseif (!is_admin()){
			$classes = array(
					'ErrorLogger' => WPHT_CLS_DIR.'/subclasses/class-plugin-errorlogger.php',					
					'Plugin_Utilities' 	=> WPHT_CLS_DIR.'/subclasses/class-plugin-utilities.php',
					'Plugin_Settings' =>  WPHT_CLS_DIR.'/subclasses/class-plugin-settings.php',
					'Plugin_lisense' =>  WPHT_CLS_DIR.'/subclasses/class-plugin-licensing.php',
					'Plugin_Header' => WPHT_CLS_DIR.'/subclasses/class-plugin-wpheader.php',											
					'Add_Hooks' 		 => WPHT_CLS_DIR.'/subclasses/class-add-hooks.php',
					
					'Plugin_Scripts' 	=> WPHT_CLS_DIR.'/subclasses/class-plugin-script.php',
					'Plugin_Functions' 	=> WPHT_CLS_DIR.'/subclasses/class-plugin-functions.php',
					'clsDbBase' => WPHT_CLS_DIR.'/subclasses/classes/class-plugin-dbbase.php',					
					'IclsStatusBase'	=> 	WPHT_CLS_DIR.'/subclasses/classes/class-plugin-statusbase.php',
					'clsPluginBase' => WPHT_CLS_DIR.'/subclasses/classes/class-plugin-baseclass.php',
					'clsVisitorHandler' => WPHT_CLS_DIR.'/subclasses/class-plugin-visitors.php',
								
			);
			$this->register_classes( $classes );//var_dump(Plugin_Core::$class_array);
			
		}else{
			
		}
	}
}