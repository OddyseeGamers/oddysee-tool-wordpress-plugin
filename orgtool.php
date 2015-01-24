<?php
/*
Plugin Name: Org Tool
Plugin URI: http://oddysee.org
Description: Org Tool
Version: 0.1
Author: 
Author URI: 
License: GPLv2 or later
*/

@include_once "$dir/user/rsi_user.php";

function ot_activation() {
	error_log("activate orgtool");
	$res = fetchFromRSI(1);
	error_log("rest " . $res);
}

function ot_deactivation() {
	error_log("dectivate orgtool");
}

register_activation_hook(__FILE__, 'ot_activation');
register_deactivation_hook(__FILE__, 'ot_deactivation');

?>
