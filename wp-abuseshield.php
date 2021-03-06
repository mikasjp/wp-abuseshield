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

define("WP_ABUSESHIELD_VERSION", "1.0.0");

require_once plugin_dir_path( __FILE__ ) . "include/class-wp-abuseshield.php";

$WPAbuseShield = new Wp_Abuseshield();

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
	global $WPAbuseShield;
	$WPAbuseShield->Run();
}

function EnqueueAdminStyle()
{
	wp_register_style( 'wp_abuseshield_admin_css', plugins_url("admin/style.css", __FILE__ ), false, WP_ABUSESHIELD_VERSION );
	wp_enqueue_style("wp_abuseshield_admin_css");
}

function wp_abuseshield_add_verification_tag()
{
	if(!empty($WPAbuseShield->config->config["DVC"]))
		echo '<meta name="abuseipdb-verification" content="'.$WPAbuseShield->config->config["DVC"].'">';
}

function wp_abuseshield_admin_panel()
{
	DEFINE("WP_ABUSESHIELD_ADMIN", true);
	require_once plugin_dir_path( __FILE__ ) . "admin/admin.php";
?>

<?php
}

function wp_abuseshield_setup_menu()
{
	add_menu_page("WP AbuseShield Configuration", "WP AbuseShield", "manage_options", "wp-abuseshield", "wp_abuseshield_admin_panel");
}

add_action("wp", "run_wp_abuseshield");
register_activation_hook( __FILE__, "activate_wp_abuseshield");
register_deactivation_hook( __FILE__, "deactivate_wp_abuseshield");

add_action('admin_menu', 'wp_abuseshield_setup_menu');
add_action('admin_enqueue_scripts', 'EnqueueAdminStyle');
add_action("wp_head", "wp_abuseshield_add_verification_tag");