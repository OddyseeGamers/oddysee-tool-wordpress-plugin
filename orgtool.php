<?php
/*
Plugin Name: Org Tool
Plugin URI: http://oddysee.org
Description: Org Tool
Version: 0.4
Author: 
Author URI: 
License: GPLv2 or later
*/

@include_once "user/rsi_user.php";

register_activation_hook( __FILE__, array( 'OrgPlugin', 'ot_activation' ) );

class OrgPlugin {
	function initRSIUsers() {
		global $wpdb;
		$table_name = $wpdb->prefix . "rsi_users";
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
	}

	function fetchAll() {
		$res = true;
		$page = 1;
		while($res) {
			$res = fetchFromRSI($page++);
		}
	}

	function ot_activation() {
		self::initRSIUsers();
		self::fetchAll();
	}

	function ot_deactivation() {
	}
}

?>
