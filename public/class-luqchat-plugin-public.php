<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://devinvinson.com
 * @since      1.0.0
 *
 * @package    luqchat_Plugin
 * @subpackage luqchat_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    luqchat_Plugin
 * @subpackage luqchat_Plugin/public
 * @author     Devin Vinson <devinvinson@gmail.com>
 */
class luqchat_Plugin_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in luqchat_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The luqchat_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/luqchat-plugin-public.css', array(), $this->version, 'all' );
		$files = glob(SVELTEBOILERPLATE_PATH . '/myapp/dist/assets/*.css');
		
		foreach ($files AS $key => $val){
			wp_enqueue_style( 'svelte_my-app#'.$key , SVELTEBOILERPLATE_URL.'/myapp/dist/assets/' . basename($val) , array(), null, 'all' );
		}

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in luqchat_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The luqchat_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/luqchat-plugin-public.js', array( 'jquery' ), $this->version, false );
		$files = glob(SVELTEBOILERPLATE_PATH . '/myapp/dist/assets/*.js');
		
		foreach ($files AS $key => $val){
			wp_enqueue_script( 'svelte_my-app#'.$key , SVELTEBOILERPLATE_URL.'/myapp/dist/assets/' . basename($val) , array(), null, true );
		}
		wp_localize_script( 'svelte_my-app#'.$key, 'frontend_ajax_object',
			array( 
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'statuslogin' => wp_get_current_user()
			)
		);
	}


	public function add_type_attribute($tag, $handle, $src) {
		// if not your script, do nothing and return original $tag
		if (strpos($handle, 'svelte_my-app') !== false) {
			$tag = '<script type="module" crossorigin src="' . esc_url( $src ) . '"></script>';
			return $tag;
		}
		
		return $tag;
	}

	public function luqchat_wp_head(){
		global $wp ; 

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/chatbox.php';
		
	}

	public function luqchat_template_redirect(){
	

	
	}


	public function luqchat_rest_api_init() {
			register_rest_route( 'myplugin/v1', '/author/(?P<id>\d+)', array(
			'methods' => 'GET',
			'callback' => 'my_awesome_func',
			'permission_callback' => '__return_true'
			) );
	} //http://test.test/wp-json/myplugin/v1/author/1



	public function luqchat_rest_api_init_post() {
        register_rest_route( 'api/v1', '/data', array(
            'methods' => 'POST',
            'callback' => 'create_luqchat_rest_api_init_post'
        ));
    }

	//http://test.test/wp-json/api/v1/data?name=ali&population=abu

	
	

}

function my_awesome_func( $data ) {
	global $wpdb;

	date_default_timezone_set(wp_timezone_string());

	if($_GET['sendMessage']){

		$anonymousName = '' ; 
		if(!($_GET['currentuserid'] === 'undefined' || $_GET['currentuserid'] === '0')){
			$result = $wpdb->get_results ( "
			SELECT * 
			FROM  {$wpdb->prefix}users WHERE  ID = '".$_GET['currentuserid']."	'
				
		" );
		$anonymousName = $result[0]->user_login ; 

		}
		
		

		  $wpdb->insert('wp_luqchat', array(
			'text' => base64_decode($_GET['sendMessage']),
			'user_id' => $_GET['currentuserid'],
			'device_id' => $_GET['device_id'],
			'anonymousName' => $anonymousName !== '' ? $anonymousName : base64_decode($_GET['anonymousName']),
		));

		$wpdb->update($wpdb->prefix.'luqchatuser', array('lastactive'=>$date = date('Y-m-d H:i:s')), array('device_id'=>$_GET['device_id']));

		return $_GET['sendMessage'] ;


	}else if($_GET['getMessage']){

		$result = $wpdb->get_results ( "
			SELECT a.* , b.guid, c.user_email
			FROM  {$wpdb->prefix}luqchat a LEFT JOIN {$wpdb->prefix}posts b ON a.attachment_id = b.ID
			LEFT JOIN {$wpdb->prefix}users c ON a.user_id = c.ID 
				
		" );

		
		

		return $result;
		
	}else if($_GET['getactiveuser']){

		$result = $wpdb->get_results ( "
			SELECT device_id 
			FROM  {$wpdb->prefix}luqchatuser WHERE TIMESTAMPDIFF(minute, lastactive , NOW()) < 10 
				
		" );
		
		
		return $result;


	}else if($_GET['storeIdUser']){

		

		  $wpdb->insert($wpdb->prefix.'luqchatuser', array(
			'device_id' => $_GET['storeIdUser'],
			'user_id' => $_GET['currentuserid'] 
		));
		
		
		return null;

		
	}else if($_GET['updateIdUser']){

		$wpdb->update($wpdb->prefix.'luqchatuser', array('lastactive'=>$date = date('Y-m-d H:i:s')), array('device_id'=>$_GET['updateIdUser']));
	  
	  
	  return null;

	  
  }else{

		$user = get_users( array(
			'id' => get_current_user_id(),
		  ) );
	
	
		
		  
		  if ( empty( $user ) ) {
			return null;
		  }
	
		  return $user[0]->data->user_nicename;



	}

	

	// $posts = get_posts( array(
	//   'author' => get_current_user_id(),
	// ) );
	
	// if ( empty( $posts ) ) {
	//   return null;
	// }
	
	// return $posts[0]->post_title;
  }






  function create_luqchat_rest_api_init_post($req) {

	global $wpdb;
	date_default_timezone_set(wp_timezone_string());

	$res = new WP_REST_Response($response);
	$res->set_status(200);

	

	if($_POST['actionname'] && $_POST['actionname'] === "sendMessage" ){


		$attachment_id = null ; 
		if($_FILES){

			
			// it allows us to use wp_handle_upload() function
			require_once( ABSPATH . 'wp-admin/includes/file.php' );

			$upload = wp_handle_upload( 
				$_FILES[ 'uploadFile' ], 
				array( 'test_form' => false ) 
			);

			if( ! empty( $upload[ 'error' ] ) ) {
				wp_die( $upload[ 'error' ] );
			}

			// it is time to add our uploaded image into WordPress media library
			$attachment_id = wp_insert_attachment(
				array(
					'guid'           => $upload[ 'url' ],
					'post_mime_type' => $upload[ 'type' ],
					'post_title'     => basename( $upload[ 'file' ] ),
					'post_content'   => '',
					'post_status'    => 'inherit',
				),
				$upload[ 'file' ]
			);

			if( is_wp_error( $attachment_id ) || ! $attachment_id ) {
				wp_die( 'Upload error.' );
			}

			// update medatata, regenerate image sizes
			require_once( ABSPATH . 'wp-admin/includes/image.php' );

			wp_update_attachment_metadata(
				$attachment_id,
				wp_generate_attachment_metadata( $attachment_id, $upload[ 'file' ] )
			);

			
		}

		$anonymousName = '' ; 
		if(!($_POST['currentuserid'] === 'undefined' || $_POST['currentuserid'] === '0')){
			$result = $wpdb->get_results ( "
			SELECT * 
			FROM  {$wpdb->prefix}users WHERE  ID = '".$_POST['currentuserid']."	'
				
		" );
		$anonymousName = $result[0]->user_login ; 

		}
		
		

		  $wpdb->insert('wp_luqchat', array(
			'text' => $_POST['sendmessage'],
			'user_id' => $_POST['currentuserid'],
			'device_id' => $_POST['device_id'],
			'attachment_id' => $attachment_id ,
			'anonymousName' => $anonymousName !== '' ? $anonymousName : $_POST['anonymousName'],
		));

		$wpdb->update($wpdb->prefix.'luqchatuser', array('lastactive'=>$date = date('Y-m-d H:i:s')), array('device_id'=>$_POST['device_id']));

		return json_encode($_POST['sendmessage']) ;


	}else{

		return '';
	}

	
}


