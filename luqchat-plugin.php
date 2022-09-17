<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://luqmannordin.com
 * @since             1.0.0
 * @package           luqchat_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       luqchat Plugin
 * Plugin URI:        http://wppb.io/luqchat
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Luqman Nordin
 * Author URI:        http://luqmannordin.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       luqchat-plugin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


define( 'SVELTEBOILERPLATE_PATH', plugin_dir_path( __FILE__ ) );
define( 'SVELTEBOILERPLATE_URL', plugins_url('luqchat') );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-luqchat-plugin-activator.php
 */
function activate_luqchat_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-luqchat-plugin-activator.php';
	luqchat_Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-luqchat-plugin-deactivator.php
 */
function deactivate_luqchat_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-luqchat-plugin-deactivator.php';
	luqchat_Plugin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_luqchat_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_luqchat_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-luqchat-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_luqchat_plugin() {

	$plugin = new luqchat_Plugin();
	$plugin->run();

}
run_luqchat_plugin();


function deb($data){
	print_r("<pre>");
	print_r($data);
}
