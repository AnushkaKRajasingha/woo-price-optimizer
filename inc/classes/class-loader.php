<?php
class Loader{
	public function __construct(){}
	public function classloader(){
		if (is_admin()) {
			$classes = array( 
					'ErrorLogger' => WOOPO_CLS_DIR.'/subclasses/class-plugin-errorlogger.php',
					'Plugin_Utilities' 	=> WOOPO_CLS_DIR.'/subclasses/class-plugin-utilities.php',
					'Plugin_Settings' =>  WOOPO_CLS_DIR.'/subclasses/class-plugin-settings.php',
					'Plugin_lisense' =>  WOOPO_CLS_DIR.'/subclasses/class-plugin-licensing.php',
					'Plugin_Header' => WOOPO_CLS_DIR.'/subclasses/class-plugin-wpheader.php',					
					'Add_Hooks' 		 => WOOPO_CLS_DIR.'/subclasses/class-add-hooks.php',
					'Plugin_Menu' 		 => WOOPO_CLS_DIR.'/subclasses/class-plugin-menu.php',
					'Plugin_Scripts' 	=> WOOPO_CLS_DIR.'/subclasses/class-plugin-script.php',
					'Plugin_Functions' 	=> WOOPO_CLS_DIR.'/subclasses/class-plugin-functions.php',
					'Plugin_page' 	=> WOOPO_CLS_DIR.'/subclasses/class-plugin-pages.php',
					
					'clsDbBase' => WOOPO_CLS_DIR.'/subclasses/classes/class-plugin-dbbase.php',
					'IclsStatusBase'	=> 	WOOPO_CLS_DIR.'/subclasses/classes/class-plugin-statusbase.php',
					'clsPluginBase' => WOOPO_CLS_DIR.'/subclasses/classes/class-plugin-baseclass.php',
					'clsVisitorHandler' => WOOPO_CLS_DIR.'/subclasses/class-plugin-visitors.php',
					'clsWooPriceOptimizer' => WOOPO_CLS_DIR.'/subclasses/class-plugin-woopo.php',
					
			);
			$this->register_classes( $classes ); //var_dump(Plugin_Core::$class_array);
		}elseif (!is_admin()){
			$classes = array(
					'ErrorLogger' => WOOPO_CLS_DIR.'/subclasses/class-plugin-errorlogger.php',					
					'Plugin_Utilities' 	=> WOOPO_CLS_DIR.'/subclasses/class-plugin-utilities.php',
					'Plugin_Settings' =>  WOOPO_CLS_DIR.'/subclasses/class-plugin-settings.php',
					'Plugin_lisense' =>  WOOPO_CLS_DIR.'/subclasses/class-plugin-licensing.php',
					'Plugin_Header' => WOOPO_CLS_DIR.'/subclasses/class-plugin-wpheader.php',											
					'Add_Hooks' 		 => WOOPO_CLS_DIR.'/subclasses/class-add-hooks.php',
					
					'Plugin_Scripts' 	=> WOOPO_CLS_DIR.'/subclasses/class-plugin-script.php',
					'Plugin_Functions' 	=> WOOPO_CLS_DIR.'/subclasses/class-plugin-functions.php',
					'clsDbBase' => WOOPO_CLS_DIR.'/subclasses/classes/class-plugin-dbbase.php',					
					'IclsStatusBase'	=> 	WOOPO_CLS_DIR.'/subclasses/classes/class-plugin-statusbase.php',
					'clsPluginBase' => WOOPO_CLS_DIR.'/subclasses/classes/class-plugin-baseclass.php',
					'clsVisitorHandler' => WOOPO_CLS_DIR.'/subclasses/class-plugin-visitors.php',
								
			);
			$this->register_classes( $classes );//var_dump(Plugin_Core::$class_array);
			
		}else{
			
		}
	}
}