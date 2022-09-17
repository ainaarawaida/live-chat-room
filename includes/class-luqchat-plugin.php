<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://devinvinson.com
 * @since      1.0.0
 *
 * @package    luqchat_Plugin
 * @subpackage luqchat_Plugin/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    luqchat_Plugin
 * @subpackage luqchat_Plugin/includes
 * @author     Devin Vinson <devinvinson@gmail.com>
 */
class luqchat_Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      luqchat_Plugin_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'luqchat-plugin';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - luqchat_Plugin_Loader. Orchestrates the hooks of the plugin.
	 * - luqchat_Plugin_i18n. Defines internationalization functionality.
	 * - luqchat_Plugin_Admin. Defines all hooks for the admin area.
	 * - luqchat_Plugin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-luqchat-plugin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-luqchat-plugin-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-luqchat-plugin-admin.php';
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-luqchat-plugin-admin-settings.php';


		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-luqchat-plugin-public.php';

		$this->loader = new luqchat_Plugin_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the luqchat_Plugin_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new luqchat_Plugin_i18n();
		$plugin_i18n->set_domain( 'luqchat' );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new luqchat_Plugin_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_settings = new luqchat_Admin_Settings( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_settings, 'setup_plugin_options_menu' );
		$this->loader->add_action( 'admin_init', $plugin_settings, 'initialize_display_options' );
		$this->loader->add_action( 'admin_init', $plugin_settings, 'initialize_social_options' );
		$this->loader->add_action( 'admin_init', $plugin_settings, 'initialize_input_examples' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new luqchat_Plugin_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'luqchat_wp_head' );
		$this->loader->add_filter('script_loader_tag', $plugin_public, 'add_type_attribute' , 10, 3); 
		$this->loader->add_action( 'rest_api_init', $plugin_public, 'luqchat_rest_api_init' );
		$this->loader->add_action( 'rest_api_init', $plugin_public, 'luqchat_rest_api_init_post' );
		$this->loader->add_action( 'template_redirect', $plugin_public, 'luqchat_template_redirect', 99 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    luqchat_Plugin_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}


//////////////////////////////////
//Custom route Rest API
// add_action( 'rest_api_init', function () {
//     register_rest_route( 'myplugin/v1', '/author/(?P<id>\d+)', array(
//     'methods' => 'GET',
//     'callback' => 'my_awesome_func',
//     'permission_callback' => '__return_true'
//     ) );
// } );

// function my_awesome_func( $data ) {
//     $posts = get_posts( array(
//       'author' => $data['id'],
//     ) );
    
//     if ( empty( $posts ) ) {
//       return null;
//     }
    
//     return $posts[0]->post_title;
//   }
 
 
//Custom route Rest API
//////////////////////////////////