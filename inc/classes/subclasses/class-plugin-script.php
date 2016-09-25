<?php
class Plugin_Scripts extends Plugin_Core{
	public function __construct() {
	}
	
	public function scriptInit(){
		$this->_jquery();	
		$this->_media();
		$this->_adminStyleEnque();
		$this->adminScriptEnque();
		if (is_admin() && isset($_REQUEST['page'])) {
			if(strpos($_REQUEST['page'], self::$current_plugin_data['TextDomain']) === false) return false;
			$page_slug = str_replace(self::$current_plugin_data['TextDomain'], '', $_REQUEST['page']);			
			if(!empty($page_slug)){
				$page_settings =  init_var::_getMenuItems();//var_dump($page_settings);
				$this->_registerPageScript($page_slug, $page_settings);
			}
			else{				
				/* Nothing */
				$page_stylefile = 'option-page.css';
				if(file_exists(WPHT_PLUGIN_PAGES.'/css/'.$page_stylefile))
					wp_enqueue_style( self::$current_plugin_data['TextDomain'].'-optionpage-style',WPHT_PLUGIN_CSSDIR_URL.'/'.$page_stylefile,array(),self::$current_plugin_data['Version']);
				$page_scriptfile = 'option-page.js';
				if(file_exists(WPHT_PLUGIN_PAGES.'/js/'.$page_scriptfile))
					wp_enqueue_script( self::$current_plugin_data['TextDomain'].'-option-page-script',WPHT_PLUGIN_JSDIR_URL.'/'.$page_scriptfile,array('jquery','common-data','bootbox.min','plugin_utilities'),self::$current_plugin_data['Version'],true);
			}
			
			$this->enqueCommonScripts();
			wp_enqueue_style( self::$current_plugin_data['TextDomain'].'-style',WPHT_PLUGINDIR_URL.'style.css',array(),self::$current_plugin_data['Version']);
			wp_enqueue_script( self::$current_plugin_data['TextDomain'].'-script',WPHT_PLUGIN_JSDIR_URL.'/script.js',array('jquery'),self::$current_plugin_data['Version'],true);
			do_action(self::$current_plugin_data['TextDomain'].'_script_localiztion');	
				
		}
	}
	
	public function _adminStyleEnque(){
		if(is_admin())
		wp_enqueue_style( self::$current_plugin_data['TextDomain'].'-admin-css', WPHT_PLUGIN_CSSDIR_URL.'/admin/style.css', array(), null, 'all' );
	}
	
	private function adminScriptEnque(){
		if(is_admin())
		wp_enqueue_script( self::$current_plugin_data['TextDomain'].'-admin-js', WPHT_PLUGIN_JSDIR_URL.'/admin/script.js',array(), self::$current_plugin_data['Version'],true);
	}
	
	
	
	private function _registerPageScript($page_slug, $page_settings){
		if (array_key_exists($page_slug, $page_settings) ) {
			$current_p_set = $page_settings[$page_slug];
			//WPHT_PLUGIN_CSSDIR_URL
		
			if(array_key_exists('bootstrap', $current_p_set)){
				$this->_bootstrap();
			}
			if(array_key_exists('jQuery-ui', $current_p_set)){
				$this->_jqueryUI();
			}
		
			if(array_key_exists('page-style',$current_p_set)){
				$page_stylefile = $current_p_set['page-style'];
				if(!file_exists(WPHT_PLUGIN_PAGES.'/css/'.$page_stylefile)){
					$page_stylefile = 'style'.$page_slug.'.css';
				}
				//echo WPHT_PLUGIN_PAGES.'/css/'.$page_stylefile;
				if(file_exists(WPHT_PLUGIN_PAGES.'/css/'.$page_stylefile))
					wp_enqueue_style( self::$current_plugin_data['TextDomain'].'-'.$page_slug.'-style',WPHT_PLUGIN_CSSDIR_URL.'/'.$page_stylefile,array(),self::$current_plugin_data['Version']);
			}
		
			if (array_key_exists('page-script',$current_p_set)) {
				$page_scriptfile = $current_p_set['page-script'];
				if(!file_exists(WPHT_PLUGIN_PAGES.'/js/'.$page_scriptfile)){
					$page_scriptfile = 'script'.$page_slug.'.js';
				}
				
				if(file_exists(WPHT_PLUGIN_PAGES.'/js/'.$page_scriptfile)) //var_dump(WPHT_PLUGIN_PAGES.'/js/'.$page_scriptfile); var_dump(self::$current_plugin_data['TextDomain'].'-'.$page_slug.'-script');
					wp_enqueue_script( self::$current_plugin_data['TextDomain'].'-'.$page_slug.'-script',WPHT_PLUGIN_JSDIR_URL.'/'.$page_scriptfile,$current_p_set['scriptDep'],self::$current_plugin_data['Version'],true);
			}
		}
		else{
			foreach ($page_settings as $key => $value) {
				if(is_array($value) && array_key_exists('subpages', $value)){
					$this->_registerPageScript($page_slug, $value['subpages']);//echo $page_slug ;  print_r($value);
				}
			}
		}
	} 

	private function enqueCommonScripts(){
		
		$this->_bootstrap();
		//$this->_jqueryUI();
		$this->_fonts();
		$this->_animation();
		$this->_wpReset();
		$this->_dummyData();
		//$this->_chance();
		$this->_dataTable();
		$this->_dateFormat();
		$this->_fontAwsome();
		$this->_pluginUtilities();
		$this->_bootastrapChekBox();
	//	$this->_bootstrapDateTimePicker();
		$this->_bootstrapDatePicker();
		$this->_bootstrapBootbox();
	//	$this->_bootstrapColorPicker();
	//	$this->_canvg();
		$this->_jQuerySortable();
	//	$this->_canvas2image();
		$this->_msdropdown();
		$this->_wysihtml5();
		$this->_stepsmaster();
		$this->_stripPaymentValidation();
		//$this->_iChekMaster();
		//$this->bootstrap_select();
	}
	
	public function bootstrap_select($autorized = 0){
		if($autorized == 1 || is_admin()){
		$version = 'v1.7.2';
		wp_enqueue_script( 'bootstrap-select-script',WPHT_PLUGIN_PGDIR_URL.'/assets/bootstrap-select/js/bootstrap-select.min.js',array('jquery','Bootstrap-Scripts'),$version,true);
		wp_enqueue_style( 'bootstrap-select-style',WPHT_PLUGIN_PGDIR_URL.'/assets/bootstrap-select/css/bootstrap-select.css',array('bootstrap'),$version,'all');
		}
	}
	
	public function _iChekMaster(){
		$version = '0.9.1';
		wp_enqueue_script( 'icheckmaster',WPHT_PLUGIN_PGDIR_URL.'/assets/iCheck-master/jquery.icheck.js',array('jquery','Bootstrap-Scripts'),$version,true);
		wp_enqueue_script( 'icheckmaster-init',WPHT_PLUGIN_PGDIR_URL.'/assets/iCheck-master/icheck-init.js',array('icheckmaster'),$version,true);
	}
	
	private function _canvg(){
		$version = '1.3';
		wp_enqueue_script( 'convg-rgbcolor',WPHT_PLUGIN_PGDIR_URL.'/js/canvg/rgbcolor.js',array('jquery'),$version,true);
		wp_enqueue_script( 'convg-stackBlur',WPHT_PLUGIN_PGDIR_URL.'/js/canvg/StackBlur.js',array('convg-rgbcolor'),$version,true);
		wp_enqueue_script( 'convg',WPHT_PLUGIN_PGDIR_URL.'/js/canvg/canvg.js',array('convg-stackBlur'),$version,true);
	}
	
	private function _canvas2image(){
		$version = '0.1';
		wp_enqueue_script( 'base64',WPHT_PLUGIN_PGDIR_URL.'/assets/canvas2image/base64.js',array(),$version,true);
		wp_enqueue_script( 'canvas2Image',WPHT_PLUGIN_PGDIR_URL.'/assets/canvas2image/canvas2image.js',array('base64'),$version,true);
	}

	
	private function _jQuerySortable(){
		$version = '0.9.12';
		wp_enqueue_style( 'jquerySortable-style',WPHT_PLUGIN_PGDIR_URL.'/js/jQuery-sortable/style.css',array(),$version);
		wp_enqueue_script( 'jquerySortable-scripts',WPHT_PLUGIN_PGDIR_URL.'/js/jQuery-sortable/jquery-sortable.js',array('jquery'),$version,true);
	}

	public function _bootstrap(){
		$version = '3.0.3';
		wp_enqueue_style( 'Bootstrap-Style',WPHT_PLUGIN_PGDIR_URL.'/bs3/css/bootstrap.min.css',array(),$version);
		wp_enqueue_style( 'Bootstrap-Reset-Style',WPHT_PLUGIN_PGDIR_URL.'/bs3/css/bootstrap.reset.css',array('Bootstrap-Style'),$version);
		wp_enqueue_style( 'Bootstrap-Reset-Style1',WPHT_PLUGIN_PGDIR_URL.'/bs3/css/bootstrap-reset.css',array('Bootstrap-Style'),$version);
		wp_enqueue_script( 'Bootstrap-Scripts',WPHT_PLUGIN_PGDIR_URL.'/bs3/js/bootstrap.min.js',array('jquery'),$version,true);
	}
	
	private function _jquery(){
		//wp_enqueue_script( 'jquery',WPHT_PLUGIN_JSDIR_URL.'/jquery.min.js',array(),'1.11.0',true);
		wp_enqueue_script('jquery');
		
	}
	
	private function _media(){
		//if(is_admin() && isset($_REQUEST['page']) &&  $_REQUEST['page'] == self::$current_plugin_data['TextDomain'].'_splashCreator'){
			wp_enqueue_media();
		//}
	}
	
	private function _chance(){
		wp_enqueue_script( 'chance','http://chancejs.com/chance.min.js',array('jquery'),'',true);
	}

	private function _jqueryUI(){
		$version = '1.10.1';
		wp_enqueue_style( 'jquery-ui',WPHT_PLUGIN_PGDIR_URL.'/assets/jquery-ui/jquery-ui-1.10.1.custom.min.css',array(),$version);
		wp_enqueue_script( 'jquery-ui',WPHT_PLUGIN_PGDIR_URL.'/assets/jquery-ui/jquery-ui-1.10.1.custom.min.js',array('jquery'),$version,true);
	}

	private function _fonts(){
		wp_enqueue_style( self::$current_plugin_data['TextDomain'].'-fonts',WPHT_PLUGIN_CSSDIR_URL.'/fonts.css',array(),self::$current_plugin_data['Version']);
	}

	private function _animation(){
		wp_enqueue_style( self::$current_plugin_data['TextDomain'].'-animation',WPHT_PLUGIN_CSSDIR_URL.'/animation.css',array(),self::$current_plugin_data['Version']);
	}

	private function _wpReset(){
		wp_enqueue_style( self::$current_plugin_data['TextDomain'].'-wp-reset',WPHT_PLUGIN_CSSDIR_URL.'/wp-reset.css',array(),self::$current_plugin_data['Version']);
	}
	
	private function _dummyData(){
		$version = '1.0';
		wp_enqueue_script( 'demo_data',WPHT_PLUGIN_JSDIR_URL.'/dummy-data/demo_data.js',array('jquery','date-format'),$version,true);
		wp_enqueue_script( 'common_page_ini',WPHT_PLUGIN_JSDIR_URL.'/dummy-data/common_page_ini.js',array('jquery','demo_data'),$version,true);
		wp_enqueue_script( 'common-data',WPHT_PLUGIN_JSDIR_URL.'/dummy-data/common-data.js',array('jquery','demo_data','common_page_ini'),$version,true);		
	}
	
	private function  _dataTable(){
		$version = self::$current_plugin_data['Version'];
		wp_enqueue_style( 'data-table',WPHT_PLUGIN_PGDIR_URL.'/assets/data-tables/DT_bootstrap.css',array('Bootstrap-Style'),$version);
		wp_enqueue_style( 'data-table-custom',WPHT_PLUGIN_PGDIR_URL.'/assets/data-tables/dataTableCustom.css',array('data-table'),$version);
		//wp_enqueue_script( 'data-table',WPHT_PLUGIN_PGDIR_URL.'/assets/data-tables/jquery.dataTables.js',array('jquery'),$version,true);
		wp_enqueue_script( 'data-table','//cdn.datatables.net/1.10.8/js/jquery.dataTables.min.js',array('jquery'),$version,true);
		wp_enqueue_script( 'jeditable',WPHT_PLUGIN_PGDIR_URL.'/assets/data-tables/jquery.jeditable.mini.js',array('jquery','data-table'),$version,true);
		wp_enqueue_script( 'DT-bootstrap',WPHT_PLUGIN_PGDIR_URL.'/assets/data-tables/DT_bootstrap.js',array('jquery'),$version,true);
	}
	
	private function _dateFormat(){
		$version = '1.0';
		wp_enqueue_script( 'date-format',WPHT_PLUGIN_PGDIR_URL.'/assets/date-format/date.format.js',array('jquery'),$version,true);
	}
	
	public function _fontAwsome(){
		wp_enqueue_style( 'font-awesome',WPHT_PLUGIN_PGDIR_URL.'/assets/font-awesome/css/font-awesome.css',array(),self::$current_plugin_data['Version']);
	}
	
	private function _pluginUtilities(){
		$version = '1.0';
		wp_enqueue_script( 'plugin_utilities',WPHT_PLUGIN_JSDIR_URL.'/plugin_utilities.js',array('jquery','bootstrap-switch-master'),$version,true);
	}

	private function _bootastrapChekBox(){
		$version = '2.0.0';
		wp_enqueue_style( 'bootstrap-switch-master',WPHT_PLUGIN_PGDIR_URL.'/assets/bootstrap-switch-master/build/css/bootstrap3/bootstrap-switch.css',array('Bootstrap-Style'),$version);
		wp_enqueue_script( 'bootstrap-switch-master',WPHT_PLUGIN_PGDIR_URL.'/assets/bootstrap-switch-master/build/js/bootstrap-switch.js',array('jquery','Bootstrap-Scripts'),$version,true);
	}
	
	private function _bootstrapDateTimePicker(){
		$version = '2.0.0';
		wp_enqueue_style( 'bootstrap-datetimepicker',WPHT_PLUGIN_PGDIR_URL.'/assets/bootstrap-datetimepicker/css/datetimepicker.css',array('Bootstrap-Style'),$version);
		wp_enqueue_script( 'bootstrap-datetimepicker',WPHT_PLUGIN_PGDIR_URL.'/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js',array('jquery','Bootstrap-Scripts'),$version,true);
	}
	
	private function _bootstrapDatePicker(){
		$version = '2.0.0';
		//wp_enqueue_style( 'bootstrap-datepicker',WPHT_PLUGIN_PGDIR_URL.'/assets/bootstrap-datepicker/css/datepicker.css',array('Bootstrap-Style'),$version);
		//wp_enqueue_script( 'bootstrap-datepicker',WPHT_PLUGIN_PGDIR_URL.'/assets/bootstrap-datepicker/js/bootstrap-datepicker.js',array('jquery','Bootstrap-Scripts'),$version,true);
		
		wp_enqueue_style( 'bootstrap-datepicker','//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css',array('Bootstrap-Style'),$version);
		wp_enqueue_script( 'bootstrap-datepicker','//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js',array('jquery','Bootstrap-Scripts'),$version,true);
	}
	
	private function _bootstrapBootbox(){
		$version = '3.3.0';
		wp_enqueue_script( 'bootbox.min',WPHT_PLUGIN_PGDIR_URL.'/assets/bootbox/bootbox.min.js',array('jquery','Bootstrap-Scripts'),$version,true);
	}
	
	private function _bootstrapColorPicker(){
		$version = '2.0.0';
		wp_enqueue_style( 'bootstrap-colorpicker',WPHT_PLUGIN_PGDIR_URL.'/assets/bootstrap-colorpicker/css/colorpicker.css',array('Bootstrap-Style'),$version);
		wp_enqueue_script( 'bootstrap-colorpicker',WPHT_PLUGIN_PGDIR_URL.'/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js',array('jquery','Bootstrap-Scripts'),$version,true);
	}
	
	private function _msdropdown(){
		if(is_admin() && isset($_REQUEST['page']) &&  ( $_REQUEST['page'] == self::$current_plugin_data['TextDomain'].'_splashCreator' ||  $_REQUEST['page'] == self::$current_plugin_data['TextDomain'].'_addNewVideo')){
			$version = '3.0';
			wp_enqueue_style( 'msdropdown-style',WPHT_PLUGIN_PGDIR_URL.'/assets/msdropdown/dd.css',array(),$version);
			wp_enqueue_script( 'msdropdown-script',WPHT_PLUGIN_PGDIR_URL.'/assets/msdropdown/jquery.dd.min.js',array('jquery'),$version,true);
		}
	}
	
	private function _wysihtml5(){
		if(is_admin() && isset($_REQUEST['page']) &&  ( $_REQUEST['page'] == self::$current_plugin_data['TextDomain'].'_mngSysSettings' ||  $_REQUEST['page'] == self::$current_plugin_data['TextDomain'].'_addNewVideo')){
			$version = '0.3.0';
			wp_enqueue_style( 'wysihtml5-style',WPHT_PLUGIN_PGDIR_URL.'/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css',array(),$version);
			wp_enqueue_script( 'wysihtml5-script',WPHT_PLUGIN_PGDIR_URL.'/assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js',array('jquery','Bootstrap-Scripts'),$version,true);
			wp_enqueue_script( 'bootstrap-wysihtml5-script',WPHT_PLUGIN_PGDIR_URL.'/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js',array('jquery','Bootstrap-Scripts','wysihtml5-script'),$version,true);
		}
	}
	
	public function _stepsmaster($autorized = 0){
		if($autorized == 1 || (is_admin() && isset($_REQUEST['page']) &&  ( $_REQUEST['page'] == self::$current_plugin_data['TextDomain'].'_frontQuotePageView'))){
			$version = '1.0.4';
			wp_enqueue_style( 'jquery-steps-master',WPHT_PLUGIN_PGDIR_URL.'/assets/jquery-steps-master/demo/css/jquery.steps.css',array(),$version);
			//wp_enqueue_script( 'accordion-menu',WPHT_PLUGIN_PGDIR_URL.'/assets/accordion-menu/jquery.dcjqaccordion.2.7.js',array('jquery'),$version,true);
			wp_enqueue_script( 'jquery-steps-master',WPHT_PLUGIN_PGDIR_URL.'/assets/jquery-steps-master/build/jquery.steps.js',array('jquery','Bootstrap-Scripts'),$version,true);
		}
	}
	
	public function _stripPaymentValidation($autorized = 0){
		if($autorized == 1 || (is_admin() && isset($_REQUEST['page']) &&  ( $_REQUEST['page'] == self::$current_plugin_data['TextDomain'].'_frontQuotePageView'))){
			$version = '1.0.4';
			wp_enqueue_script( 'jquery.payment',WPHT_PLUGIN_PGDIR_URL.'/js/jquery.payment.js',array('jquery'),$version,true);
		}
	}

	public function _scriptLocalization(){
		$args = array(
				'TextDomain' =>self::$current_plugin_data['TextDomain'],
				'admin_ajaxurl' =>  admin_url( 'admin-ajax.php' ),				
				'imageDirUrl' => WPHT_PLUGIN_IMGDIR_URL,
				'tempImgDirUrl' => WPHT_PLUGIN_SPLASHTEMDIR_URL.'/imagesasd/'
		);
		wp_localize_script(self::$current_plugin_data['TextDomain'].'-script', 'localize_var',$args );
	}
	
	public function _localizeAjaxScript(){
		header('Content-Type: application/javascript');  //header('application/javascript');
		$args = array(
				'TextDomain' =>self::$current_plugin_data['TextDomain'],
				'admin_ajaxurl' =>  admin_url( 'admin-ajax.php' ),
				'imageDirUrl' => WPHT_PLUGIN_IMGDIR_URL,
				'tempImgDirUrl' => WPHT_PLUGIN_SPLASHTEMDIR_URL.'/images/'
		);
		$sysSettings = new MMSystemSettings(); $sysSettings->GetSystemSetting();
		?>
		var localize_var =  <?php echo json_encode($args);?>;
		<?php
		if($sysSettings->gasettings && is_array($sysSettings->gasettings)){
			if(!empty($sysSettings->gasettings['googleanalyticid'])){
				$_gasettings = new MMGoogleAnalytics();
				$gacode =  $_gasettings->_setGaCode($sysSettings->gasettings['googleanalyticid']); echo $gacode ; 
			}
			?>
			function _gaTracking(index){
			switch(index) {
			    case 1:
			        <?php echo stripslashes($sysSettings->gasettings['trackingcode1']);  ?>
			        break;
			    case 2:
			        <?php echo stripslashes($sysSettings->gasettings['trackingcode2']); ?>
			        break;
			    case 3:
			        <?php echo stripslashes($sysSettings->gasettings['trackingcode3']); ?>
			        break;
			    case 4:
			        <?php echo stripslashes($sysSettings->gasettings['trackingcode4']); ?>
			        break;
			    default:
			        console.log('undefined event id');
			}
		}
			<?php 
		}	
		exit;
	}
	
	public function _customAjaxStyle(){
		header('Content-Type: text/css');
		$__sysset = new MMSystemSettings(); $__sysset->GetSystemSetting();
		echo $__sysset->customstyle;
		exit;
	}
}