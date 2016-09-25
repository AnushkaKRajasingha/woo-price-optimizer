<?php
require_once 'class-loader.php';

class Plugin_Core extends Loader{
	public static $instances_array 	= array();	
	public static $class_array 		= array();	
	public static $current_plugin_data = array();
	public static $default_headers = array(
	                'Name' => 'Plugin Name',
	                'PluginURI' => 'Plugin URI',
	                'Version' => 'Version',
	                'Description' => 'Description',
	                'Author' => 'Author',
	                'AuthorURI' => 'Author URI',
                	'TextDomain' => 'Text Domain',
	                'DomainPath' => 'Domain Path',
	                'Network' => 'Network',
	                // Site Wide Only is deprecated in favor of Network.
	                '_sitewide' => 'Site Wide Only',
					'PluginIcon' => 'Icon URL',
					'DBVersion' => 'Db Version',
					'DbRemove' => 'Db Remove',
					'LICENSE_SERVER_URL' => 'License Srv Url',
					'LICENSE_SECRET' => 'License Secert',
					'UserDoc'	=> 'UserDocumentation',
					'DevDoc' => 'DevDocumentation',
					'HelpSup' => 'HelpAndSupport',
					'Environment' => 'Environment'
	        );
	
	
	
	public  function __construct(){	
		self::$current_plugin_data = get_file_data(WPHT_PLUGINDIR.'/henrytrack-plugin.php', self::$default_headers);
		$this->classloader();
	}
	/* ----- magic methods --------------------------------- */
	/**
	 *
	 * magic call. invoke method calls to included classes
	 *
	 * @since 0.1.0
	 * @access public
	 * @param string $method
	 * @param mixed $args
	 */
	public function __call( $method, $args )
	{
		//var_dump($method);
		foreach( self::$class_array as $class )
		{
			$methods = get_class_methods( self::$instances_array[$class] );
			if( in_array( $method, $methods ) )
			{
				$function_call = array( self::$instances_array[$class], $method );
				if( !empty( $args ) )
				{
					return call_user_func_array( $function_call, $args  );
				}
				else
				{
					return call_user_func( $function_call );
				}
			}
		}
		die( $method.' not found :(' );
	}
	/* ----- public functions --- for initialise the class & other purposes ----- */
	/**
	 *
	 * register classes for magic __call
	 * register multiple classes via array
	 *
	 * @since 0.1.0
	 * @access public
	 * @param array $classes assoziatives array $class => $filename
	 */
	public function register_classes( array $classes )
	{
		$error = false;
		// double the moppel (array)
		if( empty( $classes ) || !is_array( $classes ) )
		{
			return false;
		}
		foreach( $classes as $class_name => $filename )
		{
			$error = $this->register_class( $class_name, $filename );
		}
		return $error;
	}
	/**
	 *
	 * register single class
	 *
	 * @since 0.1.0
	 * @access public
	 * @param string $class class-name to register
	 * @param string $filename
	 */
	public function register_class( $class_name = false, $filename = false )
	{
		try {
			if( $class_name && $filename )
			{
				//echo $class_name.'<br/>'.$filename;
				// if this class is already registered return false
				if( key_exists( $class_name, self::$instances_array ) ){
					// for debugging only
					// $this->logger('class already registered ==> class: '.$class_name);
					return false;
				}
				if( file_exists( $filename ) )
				{
					require_once $filename;
				}
				else
				{
					// for debugging only
					// $this->logger('file not found ==> file: '.$filename);
					if(self::$current_plugin_data['Environment'] == 'Dev'){ 
						throw new Exception('File not found - '.$filename); 
					}
					else
					{
						return false;
					}
				}
				if( class_exists( $class_name ) )
				{
					$classRef = new ReflectionClass($class_name);
					if(!$classRef->isAbstract()){
						self::$instances_array[$class_name] = new $class_name;
						self::$class_array[] = $class_name;
					}
					return true;
				}
				else
				{
					// for debugging only
					// $this->logger('registering failed ==> file: '.$filename.' | class: '.$class_name);
					if(self::$current_plugin_data['Environment'] == 'Dev'){ 
						throw new Exception('Class already exists - '.$class_name); 
					}
					else
					{
						return false;
					}
				}
			}
			
			// for debugging only
			// $this->logger('registering class failed with unknown reason');
			if(self::$current_plugin_data['Environment'] == 'Dev'){ 
				throw new Exception('Invalid File name or Class name  - class name : '.$class_name .' / file name :'.$filename); 
			}
			else
			{
				return false;
			}
		} catch (Exception $e) {			
			$errorlogger = new ErrorLogger();
			$errorlogger->add_message($e->getMessage());
			return false;
		}
	}
	
/* Log any errors */ 
	public function logger($data){
		$f = @fopen(WPHT_PLUGINDIR.'/_errorlog.txt', 'a+');
		if(!$f) die("Fehlgeschlagen");
		$out = var_export($data, true);
		fputs($f, date('d.m.Y - H:i:s').' => ');
		fputs($f,$out.PHP_EOL);
		fputs($f, "--------------------".PHP_EOL);
		fclose($f);
		return 'ok';
	}	
}