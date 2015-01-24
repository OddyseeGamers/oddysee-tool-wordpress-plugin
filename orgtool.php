<?php
/*
Plugin Name: Org Tool
Plugin URI: http://oddysee.org
Description: Org Tool
Version: 0.2
Author: 
Author URI: 
License: GPLv2 or later
*/

$dir = tool_dir();
@include_once "$dir/user/rsi_user.php";


function initRSIUsers() {
	global $wpdb;
	$table_name = $wpdb->prefix . "rsi_users";
	error_log("init rsi, check table ". $table_name);

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  handle tinytext NOT NULL,
	  name tinytext NOT NULL,
	  img varchar(55) DEFAULT '' NOT NULL,
	  role text,
	  roles text,
	  rank text,
	  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	  UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	error_log("init rsi users: " . $dir);
	$res = fetchFromRSI(1);
	error_log("rest " . $res);
}

function ot_activation() {
	error_log("activate orgtool");
}

function ot_deactivation() {
	error_log("dectivate orgtool");
}

function tool_dir() {
error_log("get tool dir ");
  if (defined('TOOL_DIR') && file_exists(TOOL_DIR)) {
error_log("    from env ");
    return TOOL_DIR;
  } else {
error_log("    from FILE " . __FILE__ . "  -  " . dirname(__FILE__));
    return dirname(__FILE__);
  }
}

add_action('init', 'initRSIUsers');
register_activation_hook("$dir/orgtool.php", 'ot_activation');
register_deactivation_hook("$dir/orgtool.php", 'ot_deactivation');

?>
