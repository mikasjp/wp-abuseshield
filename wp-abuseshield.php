<?php
/*
 * @wordpress-plugin
 * Plugin Name:       WP AbuseShield
 * Plugin URI:        https://github.com/mikasjp/wp-abuseshield
 * Description:       A simple and lightweight plugin that protects your WordPress against abuse.
 * Version:           1.0.0
 * Author:            Mikołaj Kamiński
 * Author URI:        https://becomeapro.pl/
 * License:           MIT
 */

if ( ! defined( "WPINC" ) ) {
	die;
}

require_once plugin_dir_path( __FILE__ ) . "include/class-wp-abuseshield.php";

function activate_wp_abuseshield()
{
	global $wpdb;
	$query = "CREATE TABLE ".$wpdb->prefix."abuseshield ( id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, ip VARCHAR(40) NOT NULL, expiry TIMESTAMP );";
	$wpdb->query($query);
}

function deactivate_wp_abuseshield()
{
	global $wpdb;
	$query = "DROP TABLE ".$wpdb->prefix."abuseshield;";
	$wpdb->query($query);
}

function run_wp_abuseshield()
{
	$WPAbuseShield = new Wp_Abuseshield();
	$WPAbuseShield->Run();
}

add_action("wp", "run_wp_abuseshield");
register_activation_hook( __FILE__, "activate_wp_abuseshield");
register_deactivation_hook( __FILE__, "deactivate_wp_abuseshield");