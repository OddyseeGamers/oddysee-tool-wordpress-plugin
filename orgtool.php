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

include 'rsi_user.php';
function ot_activation() {
	error_log("activate orgtool");
	$res = fetchFromRSI(1);
	error_log("rest " . $res);

}
register_activation_hook(__FILE__, 'ot_activation');

function ot_deactivation() {
}
register_deactivation_hook(__FILE__, 'ot_deactivation');

?>
