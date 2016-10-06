<?php
if (!defined('WOOPO')) {
	define('WOOPO', 'WOOPO');
}
require_once 'classes/plugin-init_var.php';
$plugin_var = new init_var();
$plugin_var->_initVar();
require_once WOOPO_CLS_DIR.'/plugin-core.php';
$_pluginCore = new Plugin_Core();
