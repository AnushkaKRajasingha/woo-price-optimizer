<?php
/**
 * @author Anushka Rajasingha
 * @url http://www.anushkar.com
 * @date 09/02/2016
 * @uses Visitor Handler
 *
 */
if (!class_exists('clsVisitorHandler')) {
	class clsVisitorHandler extends clsPluginBase{
		use clsDbBase;
		/**
		 * Visitor Ip Address
		 * @name Visitor Ip Address
		 * @since 1.0.0
		 * @access public
		 * @var varchar
		 * @category variable
		 * @param ISLevel0 string directmap
		 * @uses infusionsoft_api
		 */
		public $ipaddress;
		/**
		 * User Login Name
		 * @name User Login Name
		 * @since 1.0.0
		 * @access public
		 * @var varchar
		 * @category variable
		 * @param ISLevel0 string directmap
		 * @uses infusionsoft_api
		 */
		public $userlogin;
		
		/**
		 * Track By IP
		 * @name Track By IP
		 * @since 1.0.0
		 * @access public
		 * @var int
		 * @category variable
		 * @param ISLevel0 string directmap
		 * @uses infusionsoft_api
		 */
		public $byip;
		/**
		 * Track By Cookie
		 * @name Track By Cookie
		 * @since 1.0.0
		 * @access public
		 * @var int
		 * @category variable
		 * @param ISLevel0 string directmap
		 * @uses infusionsoft_api
		 */
		public $bycookie;
		/**
		 * Track By Login
		 * @name Track By Login
		 * @since 1.0.0
		 * @access public
		 * @var int
		 * @category variable
		 * @param ISLevel0 string directmap
		 * @uses infusionsoft_api
		 */
		public $bylogin;
		
		
		/**
		 * Record Updated Date
		 * @name Record Updated Date
		 * @since 1.0.0
		 * @access public
		 * @var String
		 * @category variable
		 * @param ISLevel0 string directmap
		 * @uses infusionsoft_api
		 */
		public $updatedate;
		
		/**
		 * Visitor Sessin id
		 * @name Visitor Sessin id
		 * @since 1.0.0
		 * @access public
		 * @var string
		 * @category variable
		 * @param ISLevel0 string directmap
		 * @uses infusionsoft_api
		 */
		public $sessionid; 
		
		/**
		 * Last SessionDate
		 * @name Last SessionDate
		 * @since 1.0.0
		 * @access public
		 * @var String
		 * @category variable
		 * @param ISLevel0 string directmap
		 * @uses infusionsoft_api
		 */
		public $lastsessiondate;
		
		/**
		 * Cookie Value
		 * @name Cookie Value
		 * @since 1.0.0
		 * @access public
		 * @var string
		 * @category variable
		 * @param ISLevel0 string directmap
		 * @uses infusionsoft_api
		 */
		public $cookievalue;
		
		/**
		 * Last Cookie Date
		 * @name Cookie Value
		 * @since 1.0.0
		 * @access public
		 * @var String
		 * @category variable
		 * @param ISLevel0 string directmap
		 * @uses infusionsoft_api
		 */
		public $cookiedate;
		
		/**
		 * Request Page
		 * @name Request Page
		 * @since 1.0.0
		 * @access public
		 * @var String
		 * @category variable
		 * @param ISLevel0 string directmap
		 * @uses infusionsoft_api
		 */
		public $requestpage;
		
		public function __construct(){ parent::___init(); $this->__init(); }
		
		/* Implement clsPluginBase */  
		public function __init(){
			try {	
				
				$var_henrytrack = get_option('var_henrytrack');
				$trackingoption = $var_henrytrack['opt-enable-tracking-methods'];
				
				if(!isset($var_henrytrack['opt-enable-tracking']) || $var_henrytrack['opt-enable-tracking'] != 1 ) return ;
				
				$var_admin_tracking = !$var_henrytrack['opt-enable-admin-tracking'];
				
				include_once ABSPATH . WPINC . '/pluggable.php';
				
				$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,strpos( $_SERVER["SERVER_PROTOCOL"],'/'))).'://';

				
				$req_url = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				
				$plugin_option_page = (isset($_REQUEST['page']) && ($_REQUEST['page'] = 'henrytrack'  || $_REQUEST['page'] = 'var_henrytrack_options'));
				
				//if($plugin_option_page || strpos($_SERVER['REQUEST_URI'],'admin-ajax.php') > -1 || strpos($_SERVER['REQUEST_URI'],'wp-cron.php') > -1 ){
				
				$isimagefile = (strpos($_SERVER['REQUEST_URI'],'.png') > -1 || strpos($_SERVER['REQUEST_URI'],'.jpg') > -1 || strpos($_SERVER['REQUEST_URI'],'.gif') > -1) ? true : false;
				
				$blacklist = 'wordfence,robots';
				
				$url_toremove = explode(",",$blacklist);
				
				foreach($url_toremove as $valueitem){
					if(strpos(strtolower($_SERVER['REQUEST_URI']),strtolower($valueitem)) > -1) return;
				}
				
				
				if($plugin_option_page || $isimagefile || (strpos($_SERVER['REQUEST_URI'],'wp-admin') > -1 &&  $var_admin_tracking ) || strpos($_SERVER['REQUEST_URI'],'wp-cron.php') > -1 || strpos($_SERVER['REQUEST_URI'],'wp-content') > -1){					
					return;	
				}
				
				$current_user = wp_get_current_user();				
				if(is_user_logged_in()){
					$current_user = wp_get_current_user();
					$this->userlogin = $current_user->ID;
				}     
				session_start();
				$this->_setTablename('_visitorlog');
				
				$this->bylogin = empty($trackingoption[1]) ? 0 : $trackingoption[1];
				$this->byip = empty($trackingoption[2]) ? 0 : $trackingoption[2];
				$this->bycookie = empty($trackingoption[3]) ? 0 : $trackingoption[2];
				
				$this->requestpage = $_SERVER['REQUEST_URI'];
				
				
				$this->logVisitor();
			} catch (Exception $e) {
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($e->getMessage());
				exit;
			}
		}
		
		public function get_current_visitor_id(){
			try {
				global $current_visitor_id;
				$current_visitor_id = get_current_user_id();				
			} catch (Exception $e) {
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($e->getMessage());
			}
		}
		
		
		
		private function logVisitor(){	
			try {
				global $current_user;
					
				$this->uniqueid = Plugin_Utilities::getUniqueKey ( 10 );
				$this->ipaddress = $this->getUserIP();
				$this->getVisitorCookie();
				
				$this->getSessionId();
				$this->updatedate = date('Y-m-d H:i:s');
				/* login fix */ 
				//if(empty($this->userlogin) || $this->userlogin == '' || $this->userlogin <= 0 ){$this->userlogin = 0 ;}
				//var_dump($this->userlogin);
				$this->__setDbData();
				$this->_save();				
				$this->repetevisitorNotification();
			} catch (Exception $e) {
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($e->getMessage());
			}
		}
		
		
		private function repetevisitorNotification(){
			try {
				//var_dump('Notofication');
				$var_henrytrack = get_option('var_henrytrack');
				$var_henrytrack_oetn = $var_henrytrack['opt-enable-tracking-notify'];
				$triggerpageurl = $var_henrytrack['trigger-page-url'];
				$var_henrytrack_ipd = $var_henrytrack['information_period_days'];
				$var_henrytrack_email = $var_henrytrack['reciever-email'];
				$user_login = empty($this->userlogin) ? 'Anonymous' : $this->userlogin;
				
				$headers[] = 'From : webadmin@dependance-affective.ws';
				$headers[] = 'Bcc: anudevscs@gmail.com';
				
				if($var_henrytrack_oetn){
						if(strtolower($_SERVER['REQUEST_URI']) == strtolower($triggerpageurl)){
							$var_userRecHistory = $this->getUseRecHistory($var_henrytrack_ipd,$triggerpageurl);
							
							$std_userRecHistory = <<<TableResult
							<table cellspacing='0' cellpadding='5px' border='1'>
									<thead>
									<tr><th>IP Address</th><th>Login Name</th><th>Session ID</th><th>Page Visit</th><th>Last Access Date</th><th>Visit Count</th></tr>
									</thead>
									<tbody>

TableResult;
							
							if(count($var_userRecHistory) <= 0) return;
							
							foreach ($var_userRecHistory as $value) {
								$std_userRecHistory .= "<tr><td>{$value['ipaddress']}</td><td>{$value['userlogin']}</td><td>{$value['sessionid']}</td><td>{$value['requestpage']}</td><td>{$value['lastsessiondate']}</td><td>{$value['visitcount']}</td></tr>";
								$user_login = $value['userlogin'];
							}
							
							
							$std_userRecHistory .= '</tbody></table>';
							$emailbody = <<<Emailbody
							<h1>You have new visitor to page <i>'{$triggerpageurl}'</i>
							<hr/>
									<h2>Ip Address : {$this->ipaddress}</h2>
									<h2>Login Name : {$user_login}	</h2>	
									<h2>Visitor Tracking ID  : {$this->cookievalue}	</h2>
							<hr/>
									<h3>Visitor's Log</h3>
									<hr/>
									{$std_userRecHistory}
Emailbody;
						
									add_filter( 'wp_mail_content_type', 'set_html_content_type' );
									function set_html_content_type() {
										return 'text/html';
									}
									$mailsend = wp_mail($var_henrytrack_email, 'New Visitor for the Page '.$triggerpageurl, $emailbody,$headers);
									remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
									//var_dump( $mailsend);
						}
				}
			} catch (Exception $e) {
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($e->getMessage());
			}
		}
		
		private function getUseRecHistory($noofdays = 5,$targeturl = '/page-not-fund'){
			try {
				global  $wpdb;
//				$cust_query = "select * from {$this->tablename} where  `cookievalue` = '{$this->cookievalue}' and lastsessiondate > DATE_SUB(NOW(), INTERVAL {$noofdays} DAY) and  lastsessiondate < now()";
				$cust_query = "select vl.id, vl.ipaddress,IFNULL(wu.user_nicename,'Anonymous') 'userlogin',vl.cookievalue 'visitorid',vl.sessionid, vl.requestpage,vl.lastsessiondate,vl.updatedate ,count(vl.requestpage) as 'visitcount' 
from {$wpdb->prefix}".Plugin_Core::$current_plugin_data['TextDomain']."_visitorlog vl left join   {$wpdb->prefix}users wu on wu.ID = vl.userlogin 
where  vl.cookievalue = '{$this->cookievalue}' and 
vl.lastsessiondate > DATE_SUB(NOW(), INTERVAL {$noofdays} DAY) and  
vl.lastsessiondate < DATE_ADD(NOW(), INTERVAL 1 DAY) 
group by vl.requestpage,vl.cookievalue,vl.userlogin
order by  vl.lastsessiondate desc,vl.sessionid
				";
				/*and
vl.cookievalue not in (select v2.cookievalue from {$wpdb->prefix}".Plugin_Core::$current_plugin_data['TextDomain']."_visitorlog v2 where v2.lastsessiondate > DATE_SUB(NOW(), INTERVAL {$noofdays} DAY) and  v2.lastsessiondate < DATE_ADD(NOW(), INTERVAL 1 DAY) and v2.`requestpage` = '{$targeturl}'  and v2.cookievalue = '{$this->cookievalue}' group by  v2.cookievalue having count( v2.cookievalue) <= 2)*/
				$__result = $this->_getCustomResults($cust_query);
				return $__result;
			} catch (Exception $e) {
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($e->getMessage());
			}
		}
		
		private function getVisitorRecByCookie(){
			try {
				$cust_query = "select * from {$this->tablename} where `cookievalue` = '{$this->cookievalue}'";
				$__result = $this->_getCustomResults($cust_query);
				
				$__visitor_records = new ArrayIterator();
				foreach ($__result as $value) {
					$__visitorrecord = new clsVisitorHandler();
					Plugin_Utilities::injectObjectData($value, $__visitorrecord);
					$__visitor_records->append($__visitorrecord);
				}
				
				return $__visitor_records;
			} catch (Exception $e) {
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($e->getMessage());
				exit;
			}
		}
		
		private function getVisitorRecByIp(){
			try {
				$temp_ip = $this->getUserIP();
				$cust_query = "select * from {$this->tablename} where `ipaddress` = '{$temp_ip}'";
				$__result = $this->_getCustomResults($cust_query);
				
				$__visitor_records = new ArrayIterator();
				foreach ($__result as $value) {
					$__visitorrecord = new clsVisitorHandler();
					Plugin_Utilities::injectObjectData($value, $__visitorrecord);
					$__visitor_records->append($__visitorrecord);
				}
				
				//var_dump($__visitor_records);
				
			} catch (Exception $e) {
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($e->getMessage());
				exit;
			}
		}
		
		public function identifyVisitor(){
			try {
				$this->getVisitorRecByIp();
				$this->getVisitorCookie();
				//var_dump($this);
			} catch (Exception $e) {
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($e->getMessage());
				exit;
			}
		}
		
		private function _getVisitorLogs($noofdays = 10){
			try {	
				global $wpdb;
				$__query = "select ipaddress,IFNULL(wu.user_nicename,'Anonymous') 'userlogin',cookievalue 'visitorid', requestpage,lastsessiondate,updatedate ,count(requestpage) as 'visitcount' from {$wpdb->prefix}".Plugin_Core::$current_plugin_data['TextDomain']."_visitorlog vl left join  {$wpdb->prefix}users wu on wu.ID = vl.userlogin where lastsessiondate > DATE_SUB(NOW(), INTERVAL {$noofdays} DAY) and  lastsessiondate < DATE_ADD(NOW(), INTERVAL 1 DAY) and ( requestpage NOT LIKE '%wp-admin%' and requestpage NOT LIKE '%wp-content%' and requestpage NOT LIKE '%wp-cron%' ) and ipaddress regexp '^([0-1]?[0-9]{1,2}|2[0-4][0-9]|25[0-5])\\.([0-1]?[0-9]{1,2}|2[0-4][0-9]|25[0-5])\\.([0-1]?[0-9]{1,2}|2[0-4][0-9]|25[0-5])\\.([0-1]?[0-9]{1,2}|2[0-4][0-9]|25[0-5])$'  group by cookievalue,userlogin,requestpage order by   vl.lastsessiondate desc,vl.sessionid";
				//Plugin_Utilities::custom_var_dump($__query);
				$__result = $this->_getCustomResults($__query);
				return $__result;
				
			} catch (Exception $e) {
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($e->getMessage());
				exit;
			}			
		}
		
		public function getVisitorsLogs(){
			try {				
				$var_henrytrack = get_option('var_henrytrack');
				$__result = $this->_getVisitorLogs($var_henrytrack['tracking_period_days']);				
				echo json_encode($__result);
				exit;
			} catch (Exception $e) {
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($e->getMessage());
				exit;
			}
		}
		
		private function getUserIP()
		{
		    try {
		    	$client  = @$_SERVER['HTTP_CLIENT_IP'];
		    	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		    	$remote  = $_SERVER['REMOTE_ADDR'];
		    	
		    	if(filter_var($client, FILTER_VALIDATE_IP))
		    	{
		    		$ip = $client;
		    	}
		    	elseif(filter_var($forward, FILTER_VALIDATE_IP))
		    	{
		    		$ip = $forward;
		    	}
		    	else
		    	{
		    		$ip = $remote;
		    	}
		    	
		    	return $ip;
		    } catch (Exception $e) {
		    	$errorlogger = new ErrorLogger();
		    	$errorlogger->add_message($e->getMessage());
		    	exit;
		    }
		}
		
		private function removeVisitorCookie(){
			try {
				/*$cookie_name = self::$current_plugin_data ['TextDomain'].'_visitorck';				
				setcookie($cookie_name,"", time()-3600, "/");
				unset ($_COOKIE[$cookie_name]);	*/
				return true;
			} catch (Exception $e) {
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($e->getMessage());
				exit;
			}
		}
		
		private function setVisitorCookie(){
			try {
				$cookie_name = self::$current_plugin_data ['TextDomain'].'_visitorck';
				$this->cookievalue = Plugin_Utilities::getUniqueKey ( 40 );
				$this->cookiedate = date('Y-m-d H:i:s');
				setcookie($cookie_name, $this->cookievalue, time() + (86400 * 10), "/"); // 86400 = 1 day				
				return $this->cookievalue;
			} catch (Exception $e) {
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($e->getMessage());
				exit;
			}
		}
		
		private function getVisitorCookie(){
			try {
				$cookie_name = self::$current_plugin_data ['TextDomain'].'_visitorck';
				
				if(!isset($_COOKIE[$cookie_name])) {
					return $this->setVisitorCookie();
				} else {
					$this->cookievalue = $_COOKIE[$cookie_name];
					$this->bycookie = 1;
					$this->cookiedate = date('Y-m-d H:i:s');
					return $this->cookievalue;	
				}
				
			} catch (Exception $e) {
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($e->getMessage());
				exit;
			}
		}
		
		private function getSessionId(){
			try {
				//session_regenerate_id();
				$this->sessionid = session_id();
				$this->lastsessiondate = date('Y-m-d H:i:s');
				return $this->sessionid;
			} catch (Exception $e) {
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($e->getMessage());
				exit;
			}
		}
		
		/* Grabadge Colloection */ 
		public function _visitordataclear(){
			try {
				global $wpdb;
				$results = $wpdb->query("DELETE FROM {$wpdb->prefix}".Plugin_Core::$current_plugin_data['TextDomain']."_visitorlog WHERE lastsessiondate > DATE_SUB(NOW(), INTERVAL 1 YEAR) and  lastsessiondate < DATE_SUB(NOW(), INTERVAL 20 DAY)");
			} catch (Exception $e) {
				$errorlogger = new ErrorLogger();
				$errorlogger->add_message($e->getMessage());
				exit;
			}
		}
		
		
		
	}
}