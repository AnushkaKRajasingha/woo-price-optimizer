<?php
$WPHT_var_array = array(
		WOOPO.'_PLUGIN_FILE' => 'henrytrack-plugin.php',
		WOOPO.'_PLUGINDIR' =>  dirname(dirname(plugin_dir_path(__FILE__))),
		WOOPO.'_PLUGIN_PAGES' =>  dirname(dirname(plugin_dir_path(__FILE__))).'/pages',
		WOOPO.'_PLUGIN_TEMPLATES' =>  dirname(dirname(plugin_dir_path(__FILE__))).'/pages/templates',
		WOOPO.'_PLUGINDIR_URL' => plugin_dir_url(dirname(dirname(__FILE__))),
		WOOPO.'_PLUGIN_PGDIR_URL' => plugin_dir_url(dirname(dirname(__FILE__))).'pages',
		WOOPO.'_PLUGIN_TMDIR_URL' => plugin_dir_url(dirname(dirname(__FILE__))).'pages/templates',
		WOOPO.'_PLUGIN_IMGDIR_URL' => plugin_dir_url(dirname(dirname(__FILE__))).'pages/images',
		WOOPO.'_PLUGIN_CSSDIR_URL' => plugin_dir_url(dirname(dirname(__FILE__))).'pages/css',
		WOOPO.'_PLUGIN_JSDIR_URL' => plugin_dir_url(dirname(dirname(__FILE__))).'pages/js',
		WOOPO.'_PLUGIN_SPLASHTEMDIR_PATH' =>  dirname(dirname(plugin_dir_path(__FILE__))).'/pages/inc/splashTemplates',
		WOOPO.'_PLUGIN_SPLASHTEMDIR_URL' => plugin_dir_url(dirname(dirname(__FILE__))).'pages/inc/splashTemplates',
		WOOPO.'_PLUGIN_VIDSKINSDIR_PATH' =>  dirname(dirname(plugin_dir_path(__FILE__))).'/pages/inc/skins',
		WOOPO.'_PLUGIN_VIDSKINSDIR_URL' => plugin_dir_url(dirname(dirname(__FILE__))).'pages/inc/skins',
		WOOPO.'_INC_DIR' => dirname(plugin_dir_path(__FILE__)),
		WOOPO.'_CLS_DIR' => dirname(plugin_dir_path(__FILE__)).'/classes'
);

$plugin_main_page =  array('_key'=>'_htrecdetails','Title'=>__( 'Henry Track Records', $_plugindata['Text Domain']),'MenuText'=>__( 'Track Records', $_plugindata['Text Domain']) ,'capability'=> 'manage_options','callback' => '_option_page_sub','page-style'=>true,'page-script'=>true ,'scriptDep' => array('jquery','common-data','plugin_utilities','bootstrap-switch-master'),'cls'=>'page-main');

$plugin_menu_items = array(		
		/*'_mngSysSettings' => array('_key'=>'_mngSysSettings','Title'=>'System Settings','MenuText'=>'System Settings','capability'=> 'manage_options','callback' => '_option_page_sub','page-style'=>true,'page-script'=>true ,'scriptDep' => array('jquery','common-data','plugin_utilities')),
		'_basePricingOptions' => array('_key'=>'_basePricingOptions','Title'=>'Pricing Settings','MenuText'=>'Pricing Settings','capability'=> 'manage_options','callback' => '_option_page_sub','page-style'=>true,'page-script'=>true ,'scriptDep' => array('jquery','common-data','plugin_utilities')),
		'_serviceAvailability' => array('_key'=>'_serviceAvailability','Title'=>'Service Availability','MenuText'=>'Service Availability','capability'=> 'manage_options','callback' => '_option_page_sub','page-style'=>true,'page-script'=>true ,'scriptDep' => array('jquery','plugin_utilities','bootstrap-datepicker')),
		'_frontQuotePageView' => array('_key'=>'_frontQuotePageView','Title'=>'Quote Page View (Example)','MenuText'=>'Quote Page View','capability'=> 'manage_options','callback' => '_option_page_sub','page-style'=>true,'page-script'=>true ,'scriptDep' => array('jquery','common-data','plugin_utilities','jquery-steps-master')),
		*/'_licenseSettings' => array('_key'=>'_licenseSettings','Title'=>'License Settings','MenuText'=>'License','capability'=> 'manage_options','callback' => '_option_page_sub','page-style'=>true,'page-script'=>true ,'scriptDep' => array('jquery')),
);


$db_tables = array(
		"_visitorlog" => "CREATE TABLE IF NOT EXISTS @table_name (
		 			`id` mediumint(9) NOT NULL AUTO_INCREMENT,
					`uniqueid` varchar(10) NOT NULL,
					`ipaddress` varchar(50) NOT NULL,
					`userlogin` varchar(50) NULL,
					`byip`  int(11) NOT NULL DEFAULT '0',
					`bycookie`  int(11) NOT NULL DEFAULT '0',
					`bylogin` int(11) NOT NULL DEFAULT '0',
					`sessionid` varchar(50) NULL,
					`lastsessiondate` timestamp NULL,
					`cookievalue` varchar(50) NULL,
					`cookiedate` timestamp NULL,
					`requestpage` varchar(200) NOT NULL DEFAULT ' ',
					`description` varchar(200) NOT NULL DEFAULT ' ',
					`sortOrder` int(11) NOT NULL DEFAULT '0',
					`copyof` varchar(10) NOT NULL DEFAULT '0',
					`isActive` int(11) NOT NULL DEFAULT '1',
					`isDelete` int(11) NOT NULL DEFAULT '0',
					`createdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`updatedate` timestamp NULL,
					 UNIQUE KEY `id` (`id`),KEY `uniqueid` (`uniqueid`)) ;"
				,
);

$methods_ajax = array(
		"_activateLicense" => array('func'=>'_activateLicense','user'=>false),
		"_getvisitorslogs" => array('func'=>'getVisitorsLogs','user'=>false),
);

$ShortCodeSettings = array(
	'htrecdata' => '_getHtRecData'
);

$default_css_settings = array(
	"fontfamily" => "plugin-default",
	"fontsize" => "12px",
	"fontsizeadjust" => "none",
	"fontstretch" => "normal",
	"fontstyle" => "normal",
	"fontvariant" => "normal",
	"fontweight" => "normal",
	"lineheight" => "12px"
);

$sysMsg = array(
	0 => "Successfully Saved",
	1 => "Successfully Updated",
	2 => "Successfully Deleted",
	3 => "Successfully Copied"
);

$states = array (
		'AL'=>'Alabama',
		'AK'=>'Alaska',
		'AZ'=>'Arizona',
		'AR'=>'Arkansas',
		'CA'=>'California',
		'CO'=>'Colorado',
		'CT'=>'Connecticut',
		'DE'=>'Delaware',
		'DC'=>'District Of Columbia',
		'FL'=>'Florida',
		'GA'=>'Georgia',
		'HI'=>'Hawaii',
		'ID'=>'Idaho',
		'IL'=>'Illinois',
		'IN'=>'Indiana',
		'IA'=>'Iowa',
		'KS'=>'Kansas',
		'KY'=>'Kentucky',
		'LA'=>'Louisiana',
		'ME'=>'Maine',
		'MD'=>'Maryland',
		'MA'=>'Massachusetts',
		'MI'=>'Michigan',
		'MN'=>'Minnesota',
		'MS'=>'Mississippi',
		'MO'=>'Missouri',
		'MT'=>'Montana',
		'NE'=>'Nebraska',
		'NV'=>'Nevada',
		'NH'=>'New Hampshire',
		'NJ'=>'New Jersey',
		'NM'=>'New Mexico',
		'NY'=>'New York',
		'NC'=>'North Carolina',
		'ND'=>'North Dakota',
		'OH'=>'Ohio',
		'OK'=>'Oklahoma',
		'OR'=>'Oregon',
		'PA'=>'Pennsylvania',
		'RI'=>'Rhode Island',
		'SC'=>'South Carolina',
		'SD'=>'South Dakota',
		'TN'=>'Tennessee',
		'TX'=>'Texas',
		'UT'=>'Utah',
		'VT'=>'Vermont',
		'VA'=>'Virginia',
		'WA'=>'Washington',
		'WV'=>'West Virginia',
		'WI'=>'Wisconsin',
		'WY'=>'Wyoming',
);


$plugin_Schedules =  array(
		"_visitordataclear" => array('func'=>'_visitordataclear','user'=>false,'frequent'=>'hourly'),
);
