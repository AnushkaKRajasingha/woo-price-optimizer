<?php
class Plugin_Utilities extends Plugin_Core {
	public function __construct() {
	}
	public static function flattenArray($input, $maxdepth = NULL, $depth = 0) {
		if (! is_array ( $input )) {
			return $input;
		}
		
		$depth ++;
		$array = array ();
		foreach ( $input as $key => $value ) {
			if (($depth <= $maxdepth or is_null ( $maxdepth )) && is_array ( $value )) {
				$array = array_merge ( $array, self::flattenArray ( $value, $maxdepth, $depth ) );
			} else {
				array_push ( $array, $value );
				// or $array[$key] = $value;
			}
		}
		return $array;
	}
	
	/*
	 * public static function array_flatten($array) { $return = array(); array_walk_recursive($array, function($x) use (&$return) { $return[] = $x; }); return $return; }
	 */
	public static function getUniqueKey($lenght) {
		$unique_key = substr ( md5 ( rand ( 0, 1000000 ) ), 0, $lenght );
		return $unique_key;
	}
	
	
	public static function extractDataToOjbect(&$_object) {
		if (isset ( $_POST ['data'] ) && ! empty ( $_POST ['data'] )) {
			$data = $_POST ['data'];
			foreach ( $_object as $key => &$value ) {
				if (array_key_exists ( $key, $data ))
					$value = $data [$key];
			}
			return $_object;
		} else {
			return null;
		}
	}
	
	public static function extractstdClassToOjbect($stdClass,$className){
		try {
			$r = new ReflectionClass($className);			
			$objInstance = $r->newInstanceArgs();
			foreach ($objInstance as $key => $value) {
				if($key == 'tablename') continue;
				$objInstance->$key = $stdClass->$key;
				if($className == 'MMSystemSettings' && $key == 'quotedisplayedtstagid' ){ $objInstance->$key = (array)$stdClass->$key; }
				if($className == 'MMFirstTimePricing' && $key == 'prices' ){ $objInstance->$key = (array)$stdClass->$key; }
			}
			return $objInstance; 
		} catch (Exception $e) {
		}
	}
	
	/*
	 * public static function injectObjectData($data,&$_object) { foreach($data as $key => $value) { if($value != null && ($value == '0' || !empty($value))) if(is_array($value) && is_object($_object->$key)){ $class = get_class($_object->$key); $obj = new $class(); foreach ($value as $key2 => $value2) { if($value2 != null && !empty($value2)) $obj->$key2 = $value2; } //$_object->$key = $obj; } else { $_object->$key = $value;} // $value = $optin_settings[$key]; } }
	 */
	public static function injectArrayData($data, &$_object, $key) {
		if (array_key_exists ( $key, $data ) && $data [$key] != null) {
			$__resultarr = unserialize ( $data [$key] );
			if($__resultarr){
			foreach ( $__resultarr as $value ) {
				array_push ( $_object->$key, $value );
			}
			}
		}
	}
	public static function injectObjectData($data, &$_object) {
		try {
			// error_reporting(0);			
			if($data == null){ echo json_encode(array('msgError' => 'Invalid data object','data' => $data));exit;}
			foreach ( $_object as $key => $value ) {
				if ($data!= null && is_object ( $_object->$key ) && array_key_exists ( $key, $data )) {
					if(is_object($data [$key])){
						$__result = (array)$data [$key];
					}
					else if(is_array($data [$key])){
						$__result = $data [$key];
					}
						else
						$__result = unserialize ( $data [$key] );
					if ($__result && is_array ( $__result )) {
						foreach ( $_object->$key as $key2 => $value2 ) {
							if (is_object ( $_object->$key->$key2 )) {
								self::injectObjectData ( $__result, $_object->$key->$key2 ); // var_dump($__result);
							} elseif (is_array ( $_object->$key->$key2 )) {
								self::injectArrayData ( $__result, $_object->$key, $key2 );
							} else {
								if (array_key_exists ( $key2, $__result ) && $__result [$key2] != null)
									$_object->$key->$key2 = stripslashes ( $__result [$key2] );
							}
						}
					} else {
						/*
						 * echo 'Is not array'; var_dump($__result);
						 */
					}
				} elseif (is_array ( $_object->$key )) {
					self::injectArrayData ( $data, $_object, $key );
				} else {					
					if ($data != null && is_array($data) && array_key_exists ( $key, $data ) && $data [$key] != null)
						if(is_object($data [$key])){
							//var_dump($data [$key]); //$_object->$key = stripslashes($data->$key);							
							//var_dump($_object->$key);
							$_object->$key = call_user_func(array($data [$key],"toString"));
							//var_dump($_object->$key);
						}
						else if(is_string($data)){
							$_var =  unserialize($data) ;// var_dump($_var); exit;
							self::injectArrayData ( unserialize($data), $_object, $key );
						}
						else
							$_object->$key = stripslashes ( $data [$key] );
				}
			}
		} catch ( Exception $e ) {		
		}
	}
	public static function prepairUpdateArray(&$_object) {
		var_dump ( $_object );
		if (is_object ( $_object )) {
			$updateArray = array ();
			foreach ( $_object as $key => &$value ) {
				if (is_object ( $value )) {
					echo 'Object'; // $updateArray[$key] = maybe_serialize($value);
				} else {
					echo 'Not object'; // $updateArray[$key] = $value;
				}
			}
			return $updateArray;
		} else {
			return null;
		}
	}
	
	public static function custom_var_dump($args){		
		ob_start();		
		var_dump($args);		
		$_obj =  ob_get_clean();
		echo "<div class='custom-var-dump'>".$_obj."</div>";
		return "<div class='custom-var-dump'>".$_obj."</div>";
	}
	
	public static function getClassPropMeta($className){
		$metaData = array();
		try {
			require_once WPHT_CLS_DIR.'/subclasses/plugins/class-plugin-docblock.php';
			$rc = new ReflectionClass($className);
			$properties = $rc->getProperties(ReflectionProperty::IS_PUBLIC); 
			foreach ($properties as $key => $value) {
				$return = DocBlock::ofProperty($value->class, $value->name);
				if($return->tags != null /*&& ( array_key_exists('uses', $return->tags) && $return->tags['uses'][0] == 'infusionsoft_api')*/){				
					$metaData[$value->name] = array($value->class,$return );
				}
			}
		} catch (Exception $e) {
			$errorlogger = new ErrorLogger();
			$errorlogger->add_message($e->getMessage());
		}		
		return $metaData;
	}
	
	private static function genarateSelectOption($list,$uses){
		try {
			if($list){
				foreach ($list as $key => $value) {// var_dump($value);
					if(array_key_exists('uses', $value[1]->tags) && $value[1]->tags['uses'][0] == $uses){
						$type = array_key_exists('var', $value[1]->tags) ? $value[1]->tags['var'][0] : '';
						$name = array_key_exists('name', $value[1]->tags) ? $value[1]->tags['name'][0] : '';
						echo '<option data-class="'.$value[0].'" data-type="'.$type.'" value="'.$key.'" title="'.$value[1]->desc.'">'.$name.'</option>';
					}
				}				
			}
		} catch (Exception $e) {
			$errorlogger = new ErrorLogger();
			$errorlogger->add_message($e->getMessage());
		}		
	}
	
	public static function drawClassPropList($elementType,$uses,$classes = '',$csscls = '',$elemId = ''){
		try {
			$elemId = $elemId =='' ?$uses : $elemId;
			if ($classes == '') { echo 'Invalid parameters'; return ;} 
			$returnlist = array();
			switch ($elementType) {
				case 'select':
					if (!is_array($classes)) {
						$list = Plugin_Utilities::getClassPropMeta($classes); $returnlist = array_merge($returnlist,$list);
						echo '<select id="'.$elemId.'" class="'.$csscls.'">';								
						Plugin_Utilities::genarateSelectOption($list, $uses);		
						echo '</select>';
					}
					else{
						echo '<select id="'.$elemId.'" class="'.$csscls.'">';
						foreach ($classes as $value) {
							$list = Plugin_Utilities::getClassPropMeta($value); $returnlist = array_merge($returnlist,$list);
							$classDet = DocBlock::ofClass($value);							
							echo '<optgroup label="'.$classDet->tags['uses'][0].'">';
							Plugin_Utilities::genarateSelectOption($list, $uses);
							echo '</optgroup>';
						}
						echo '</select>';
					}
				break;
				
				default:
					echo 'Not yet implemented';
				break;
			}
			return $returnlist;
		} catch (Exception $e) {
			$errorlogger = new ErrorLogger();
			$errorlogger->add_message($e->getMessage());
			echo 'Error in code block';
		}
	}
}
class Plugin_Collection {
	private $items = array ();
	public function addItem($obj, $key = null) {
		if ($key == null) {
			$this->items [] = $obj;
		} else {
			if (isset ( $this->items [$key] )) {
				throw new KeyHasUseException ( "Key $key already in use." );
			} else {
				$this->items [$key] = $obj;
			}
		}
	}
	public function deleteItem($key) {
		if (isset ( $this->items [$key] )) {
			unset ( $this->items [$key] );
		} else {
			throw new KeyInvalidException ( "Invalid key $key." );
		}
	}
	public function getItem($key) {
		if (isset ( $this->items [$key] )) {
			return $this->items [$key];
		} else {
			throw new KeyInvalidException ( "Invalid key $key." );
		}
	}
	
	public function getItems(){
		return $this->items;
	}
	
	public function count(){return count($this->items);}
}