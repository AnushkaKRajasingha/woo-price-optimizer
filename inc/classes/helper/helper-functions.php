<?php
function page_key(){
	global $p_set,$p_data;
	if(array_key_exists('_key', $p_set))
		return $p_set['_key'];
}

function page_title(){
	global $p_set,$p_data;
	return $p_set['Title'];
}

function page_css_class(){
	global $p_set,$p_data;	
	if(array_key_exists('cls', $p_set))return $p_set['cls'];
	return '';	
}

function getPluginShortName(){
	global $p_set,$p_data;
	if (array_key_exists('TextDomain',$p_data )) {
		return $p_data['TextDomain'];
	}
	return '';
}

function getDocumentationRef(){
	global $p_set,$p_data;
	if (array_key_exists('UserDocumentation',$p_data )) {
		return $p_data['UserDocumentation'];
	}
	return '';
}
function getHelpSupportRefRef(){
	global $p_set,$p_data;
	if (array_key_exists('HelpAndSupport',$p_data )) {
		return $p_data['HelpAndSupport'];
	}
	return '';
}
function setUnderConstruction(){
	?>
	Will be back soon..
		<img src="<?php echo WPHT_PLUGIN_PGDIR_URL; ?>/images/under-construction.gif" alt="under Construction" />
	<?php
}

function getFileName(){
	global $p_set,$p_data,$f_name;
	return $f_name; 
}

function setRadioBtnClass($obj,$property,$value,$default = 0){
	if(isset($obj) &&  $obj->$property == $value){echo 'active';}elseif($value = $default && !isset($obj)){echo 'active';}
}

function setRadioBtnClassInv($obj,$property,$invvalue,$default = 0){
	if(isset($obj) &&  $obj->$property != $invvalue){echo 'active';}elseif($invvalue != $default && !isset($obj)){echo 'active';}
}

function setRadioBtnValue($obj,$property,$value,$default = 0){
	if(isset($obj) &&  $obj->$property == $value){echo 'checked';}elseif($value = $default && !isset($obj)){echo 'checked';}
}

function setRadioBtnValueInv($obj,$property,$invvalue,$default = 0){
	if(isset($obj) &&  $obj->$property != $invvalue){echo 'checked';}elseif($invvalue != $default && !isset($obj)){echo 'checked';}
}




function setRadioBtn($obj,$property,$elemId,$lables){
	?>
	<label class="btn btn-default btn-yes <?php setRadioBtnClass($obj, $property,1);?>"> <input <?php setRadioBtnValue($obj, $property,1);?> type="radio" name="<?php echo $elemId;?>"  data-field-value="yes" data-field-name="<?php echo $property; ?>" value="1"><?php echo $lables[0];?></label> 
	<label class="btn btn-default btn-no <?php setRadioBtnClass($obj, $property,0);?>"> <input <?php setRadioBtnValue($obj, $property,0);?> type="radio" name="<?php echo $elemId;?>"  data-field-value="yes" data-field-name="<?php echo $property; ?>" value="0"><?php echo $lables[1];?></label>
	<?php 
}


function setRadioBtnWithValue($obj,$property,$elemId,$lables){
	?>
	<label class="btn btn-default btn-yes <?php setRadioBtnClass($obj, $property,-1);?>"> <input <?php setRadioBtnValue($obj, $property,-1);?> type="radio" name="<?php echo $elemId;?>" data-field-value="yes" data-field-name="<?php echo $property; ?>" value="-1"><?php echo $lables[0];?></label>
	<label class="btn btn-default btn-nutral <?php setRadioBtnClassInv($obj, $property,-1,-1);?>"> <input <?php setRadioBtnValueInv($obj, $property,-1,-1);?> type="radio" name="<?php echo $elemId;?>"  data-field-value="yes" data-field-name="<?php echo $property; ?>" value="<?php if(isset($obj) && $obj->$property != -1 ) echo $obj->$property;?>"><input type="number" placeholder="00" class="custom-time"  min="0" value="<?php if(isset($obj) && $obj->$property != -1 ) echo $obj->$property;?>" /><?php echo $lables[1];?></label>
	<?php 
}

/*

function get_zip_info($zip)
{
	$zipCity="";
	$zipState="";
	$zipCountryCode="";
	// Replace "demo" in below url with registered username
	$url = file_get_contents('http://api.geonames.org/postalCodeLookupJSON?postalcode='.$zip.'&username=anushkakrajasingha&maxRows=1');
	$array = json_decode($url,TRUE);
	foreach($array as $row)
	{
		//print_r($row);
		$zipCity = $row[0]['adminName2'];
		$zipState = $row[0]['adminName1'];
		$zipCountryCode = $row[0]['countryCode'];
	}
	$zipinfo = array($zipCity,$zipState,$zipCountryCode);
	return $zipinfo;
}*/