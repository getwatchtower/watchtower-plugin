<?php
/*
Plugin Name: Watch Tower
Plugin URI: http://www.atomicsmash.co.uk
Description: Provide data for watchtower
Version: 0.0.1
Author: David Darke
Author URI: http://www.atomicsmash.co.uk
*/

class Watchtower {

	public function __construct() {


	}

	public function random($length, $chars = '') {
		if (!$chars) {
			$chars = implode(range('a','f'));
			$chars .= implode(range('0','9'));
		}
		$shuffled = str_shuffle($chars);
		return substr($shuffled, 0, $length);
	}


	public function serialkey() {
		return random(5).'-'.random(5).'-'.random(5).'-'.random(5).'-'.random(5);
	}


}



// echo serialkey();

// die();

global $action, $wpdb;

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

	//Add Auth

    $pluginsUpdates = get_site_transient('update_plugins');

    // wp_get_active_and_valid_plugins()
    //
    // get_plugins()
    //
    // and get_option('active_plugins')

    // return get_plugins();


    return $pluginsUpdates;

}




# http://kovshenin.com/2012/the-wordpress-settings-api/
# http://codex.wordpress.org/Settings_API

add_action( 'admin_menu', 'my_admin_menu' );
function my_admin_menu() {
    add_options_page( __('My Plugin Options', 'textdomain' ), __('My Plugin Options', 'textdomain' ), 'manage_options', 'my-plugin', 'my_options_page' );
}
add_action( 'admin_init', 'my_admin_init' );

function my_admin_init() {

  /*
	 * http://codex.wordpress.org/Function_Reference/register_setting
	 * register_setting( $option_group, $option_name, $sanitize_callback );
	 * The second argument ($option_name) is the option name. It’s the one we use with functions like get_option() and update_option()
	 * */
  	# With input validation:
  	# register_setting( 'my-settings-group', 'my-plugin-settings', 'my_settings_validate_and_sanitize' );
  	register_setting( 'my-settings-group', 'my-plugin-settings' );

  	/*
	 * http://codex.wordpress.org/Function_Reference/add_settings_section
	 * add_settings_section( $id, $title, $callback, $page );
	 * */
  	add_settings_section( 'section-1', __( 'Section One', 'textdomain' ), 'section_1_callback', 'my-plugin' );
	add_settings_section( 'section-2', __( 'Section Two', 'textdomain' ), 'section_2_callback', 'my-plugin' );

	/*
	 * http://codex.wordpress.org/Function_Reference/add_settings_field
	 * add_settings_field( $id, $title, $callback, $page, $section, $args );
	 * */
  	add_settings_field( 'field-1-1', __( 'Field One', 'textdomain' ), 'field_1_1_callback', 'my-plugin', 'section-1' );
	add_settings_field( 'field-1-2', __( 'Field Two', 'textdomain' ), 'field_1_2_callback', 'my-plugin', 'section-1' );

	add_settings_field( 'field-2-1', __( 'Field One', 'textdomain' ), 'field_2_1_callback', 'my-plugin', 'section-2' );
	add_settings_field( 'field-2-2', __( 'Field Two', 'textdomain' ), 'field_2_2_callback', 'my-plugin', 'section-2' );

}
/*
 * THE ACTUAL PAGE
 * */
function my_options_page() {
?>
  <div class="wrap">
      <h2><?php _e('My Plugin Options', 'textdomain'); ?></h2>
      <form action="options.php" method="POST">
        <?php settings_fields('my-settings-group'); ?>
        <?php do_settings_sections('my-plugin'); ?>
        <?php submit_button(); ?>
      </form>
  </div>
<?php }
/*
* THE SECTIONS
* Hint: You can omit using add_settings_field() and instead
* directly put the input fields into the sections.
* */
function section_1_callback() {
	_e( 'Some help text regarding Section One goes here.', 'textdomain' );
}
function section_2_callback() {
	_e( 'Some help text regarding Section Two goes here.', 'textdomain' );
}
/*
* THE FIELDS
* */
function field_1_1_callback() {

	$settings = (array) get_option( 'my-plugin-settings' );
	$field = "field_1_1";
	if(isset($settings[$field])){
		$value = esc_attr( $settings[$field] );
	}else{
		$value = "";
	}

	echo "<input type='text' name='my-plugin-settings[$field]' value='$value' />";
}
function field_1_2_callback() {

	$settings = (array) get_option( 'my-plugin-settings' );
	$field = "field_1_2";
	if(isset($settings[$field])){
		$value = esc_attr( $settings[$field] );
	}else{
		$value = "";
	}

	echo "<input type='text' name='my-plugin-settings[$field]' value='$value' />";
}
function field_2_1_callback() {

	$settings = (array) get_option( 'my-plugin-settings' );
	$field = "field_2_1";
	if(isset($settings[$field])){
		$value = esc_attr( $settings[$field] );
	}else{
		$value = "";
	}

	echo "<input type='text' name='my-plugin-settings[$field]' value='$value' />";
}
function field_2_2_callback() {

	$settings = (array) get_option( 'my-plugin-settings' );
	$field = "field_2_2";
	if(isset($settings[$field])){
		$value = esc_attr( $settings[$field] );
	}else{
		$value = "";
	}

	echo "<input type='text' name='my-plugin-settings[$field]' value='$value' />";
}
/*
* INPUT VALIDATION:
* */
function my_settings_validate_and_sanitize( $input ) {

	$settings = (array) get_option( 'my-plugin-settings' );

	if ( $some_condition == $input['field_1_1'] ) {
		$output['field_1_1'] = $input['field_1_1'];
	} else {
		add_settings_error( 'my-plugin-settings', 'invalid-field_1_1', 'You have entered an invalid value into Field One.' );
	}

	if ( $some_condition == $input['field_1_2'] ) {
		$output['field_1_2'] = $input['field_1_2'];
	} else {
		add_settings_error( 'my-plugin-settings', 'invalid-field_1_2', 'You have entered an invalid value into Field One.' );
	}

	// and so on for each field

	return $output;
}
