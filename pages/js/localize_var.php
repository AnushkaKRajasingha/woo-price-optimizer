<?php
header('application/javascript');
require_once "../../../../../wp-blog-header.php";
$args = array(
		'TextDomain' =>Plugin_Core::$current_plugin_data['TextDomain'],
		'admin_ajaxurl' =>  admin_url( 'admin-ajax.php' ),
		'imageDirUrl' => WPHT_PLUGIN_IMGDIR_URL,
		'tempImgDirUrl' => WPHT_PLUGIN_SPLASHTEMDIR_URL.'/images/'
);
?>
var localize_var =  <?php echo json_encode($args);?>;
 		

/* var localize_var = {"TextDomain":"mymq","admin_ajaxurl":"http:\/\/quoteplugin.anushkar.com\/wp-admin\/admin-ajax.php","imageDirUrl":"http:\/\/quoteplugin.anushkar.com\/wp-content\/plugins\/wp_quoteplugin\/pages\/images","tempImgDirUrl":"http:\/\/quoteplugin.anushkar.com\/wp-content\/plugins\/wp_quoteplugin\/pages\/inc\/splashTemplates\/images\/"}; */ 