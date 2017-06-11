<?php
/*
Plugin Name: Watch Tower
Plugin URI: http://www.atomicsmash.co.uk
Description: Provide data for watchtower
Version: 0.0.1
Author: David Darke
Author URI: http://www.atomicsmash.co.uk
*/

global $action, $wpdb;

class Watchtower {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'my_admin_menu') );
		add_action( 'admin_init', array( $this, 'my_admin_init') );
	}

	public function my_admin_menu() {
		add_options_page( __('WatchTower', 'textdomain' ), __('WatchTower', 'textdomain' ), 'manage_options', 'watchtower', array($this,'watchtower_options_page') );
	}

	function my_admin_init() {

	  	register_setting( 'my-settings-group', 'watchtower-settings' );

	  	add_settings_section( 'section-1', __( 'WatchTower API details', 'textdomain' ),  array( $this,'section_1_callback' ), 'watchtower' );


	  	add_settings_field( 'field-api-user-key', __( 'WatchTower API User Key', 'textdomain' ), array( $this, 'field_api_user_key_callback'), 'watchtower', 'section-1', array('field-name' => '') );

		add_settings_field( 'field-api-auth-key', __( 'WatchTower API Auth Key', 'textdomain' ), array( $this, 'field_api_auth_key_callback'), 'watchtower', 'section-1', array('field-name' => '') );

		add_settings_section( 'section-2', __( 'Your Website API Key', 'textdomain' ),  array( $this,'section_2_callback' ), 'watchtower' );

	}

	function watchtower_options_page() {
	?>
	  <div class="wrap">
	      <h2><?php _e('WatchTower', 'textdomain'); ?></h2>
	      <form action="options.php" method="POST">
	        <?php settings_fields('my-settings-group'); ?>
	        <?php do_settings_sections('watchtower'); ?>
	        <?php submit_button(); ?>
	      </form>

		  <!-- This is for post testing -->

		  <form action='http://watchtower.dev/post_website'>
			  <input type="submit" value="Test Fire" />
		  </form>


	  </div>
	<?php }


	function section_1_callback() {
		_e( 'Get these details from your WatchTower Account', 'textdomain' );
	}

	function section_2_callback() {

		echo $this->generate_serialkey();

	}


	private function random($length, $chars = '') {
		if (!$chars) {
			$chars = implode(range('a','f'));
			$chars .= implode(range('0','9'));
		}
		$shuffled = str_shuffle($chars);
		return substr($shuffled, 0, $length);
	}

	private function generate_serialkey() {
		return $this->random(5).'-'.$this->random(5).'-'.$this->random(5).'-'.$this->random(5).'-'.$this->random(5);
	}

	//ASTODO: These functions need to be merged

	function field_api_user_key_callback($args= array()) {

		$settings = (array) get_option( 'watchtower-settings' );
		$field = "field_api_user_key";
		if(isset($settings[$field])){
			$value = esc_attr( $settings[$field] );
		}else{
			$value = "";
		}

		echo "<input type='text' name='watchtower-settings[$field]' value='$value' />";
	}


	function field_api_auth_key_callback($args= array()) {

		$settings = (array) get_option( 'watchtower-settings' );
		$field = "field_api_auth_key";
		if(isset($settings[$field])){
			$value = esc_attr( $settings[$field] );
		}else{
			$value = "";
		}

		echo "<input type='text' name='watchtower-settings[$field]' value='$value' />";
	}

}

new Watchtower;

add_action( 'rest_api_init', function () {
	register_rest_route( 'watchtower/v1', '/details', array(
		'methods' => 'GET',
		'callback' => 'get_details',
		// 'permission_callback' => function () {
		// 	return current_user_can( 'edit_others_posts' );
		// }

	) );
} );


function get_details( WP_REST_Request $request ) {

	//ASTODO: Add Auth here

	// wp_get_active_and_valid_plugins()
    $pluginsUpdates = get_site_transient('update_plugins');

    return $pluginsUpdates;

}


// Validation for future use
// function my_settings_validate_and_sanitize( $input ) {
//
// 	$settings = (array) get_option( 'watchtower-settings' );
//
// 	if ( $some_condition == $input['field_api_user_key'] ) {
// 		$output['field_api_user_key'] = $input['field_api_user_key'];
// 	} else {
// 		add_settings_error( 'watchtower-settings', 'invalid-field_api_user_key', 'You have entered an invalid value into Field One.' );
// 	}
//
// 	if ( $some_condition == $input['field_api_auth_key'] ) {
// 		$output['field_api_auth_key'] = $input['field_api_auth_key'];
// 	} else {
// 		add_settings_error( 'watchtower-settings', 'invalid-field_api_auth_key', 'You have entered an invalid value into Field One.' );
// 	}
//
// 	// and so on for each field
//
// 	return $output;
// }
