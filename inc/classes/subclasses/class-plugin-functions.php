<?php
class Plugin_Functions extends Plugin_Core{
	public function __construct(){
		include_once WPHT_CLS_DIR.'/helper/helper-functions.php';
	}
	
	/* added new functions in to the plugin core upone req#0006 */
	private function _setRadioBtnClass($obj,$property,$value,$default = 0){
		if(isset($obj) &&  $obj->$property == $value){echo 'active';}elseif($value = $default && !isset($obj)){echo 'active';}
	}
	
	private function _setRadioBtnClassInv($obj,$property,$invvalue,$default = 0){
		if(isset($obj) &&  $obj->$property != $invvalue){echo 'active';}elseif($invvalue != $default && !isset($obj)){echo 'active';}
	}
	
	private function _setRadioBtnValue($obj,$property,$value,$default = 0){
		if(isset($obj) &&  $obj->$property == $value){echo 'checked';}elseif($value = $default && !isset($obj)){echo 'checked';}
	}
	
	private function _setRadioBtnValueInv($obj,$property,$invvalue,$default = 0){
		if(isset($obj) &&  $obj->$property != $invvalue){echo 'checked';}elseif($invvalue != $default && !isset($obj)){echo 'checked';}
	}
	
	Public function _setRadioBtn($obj,$property,$elemId,$lables){
		?>
		<label class="btn btn-default btn-yes <?php $this->_setRadioBtnClass($obj, $property,1);?>"> <input <?php $this->_setRadioBtnValue($obj, $property,1);?> type="radio" name="<?php echo $elemId;?>"  data-field-value="yes" data-field-name="<?php echo $property; ?>" value="1"><?php echo $lables[0];?></label> 
		<label class="btn btn-default btn-no <?php $this->_setRadioBtnClass($obj, $property,0);?>"> <input <?php $this->_setRadioBtnValue($obj, $property,0);?> type="radio" name="<?php echo $elemId;?>"  data-field-value="yes" data-field-name="<?php echo $property; ?>" value="0"><?php echo $lables[1];?></label>
		<?php 
	}
	
	
	public function _setRadioBtnWithValue($obj,$property,$elemId,$lables){
		?>
		<label class="btn btn-default btn-yes <?php $this->_setRadioBtnClass($obj, $property,-1);?>"> <input <?php $this->_setRadioBtnValue($obj, $property,-1);?> type="radio" name="<?php echo $elemId;?>" data-field-value="yes" data-field-name="<?php echo $property; ?>" value="-1"><?php echo $lables[0];?></label>
		<label class="btn btn-default btn-nutral <?php $this->_setRadioBtnClassInv($obj, $property,-1,-1);?>"> <input <?php $this->_setRadioBtnValueInv($obj, $property,-1,-1);?> type="radio" name="<?php echo $elemId;?>"  data-field-value="yes" data-field-name="<?php echo $property; ?>" value="<?php if(isset($obj) && $obj->$property != -1 ) echo $obj->$property;?>"><input type="number" placeholder="00" class="custom-time"  min="0" value="<?php if(isset($obj) && $obj->$property != -1 ) echo $obj->$property;?>" /><?php echo $lables[1];?></label>
		<?php 
	}
	
	/* added new functions in to the plugin core upone req#0006 */
}