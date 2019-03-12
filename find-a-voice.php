<?php
	/*
		Plugin Name: Find A Voice
		Author: Milan Lukic
		Version: 1.0
	*/
	
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

register_activation_hook( __FILE__, 'install_find_a_voice' );
register_activation_hook( __FILE__, 'install_find_a_voice_data' );

//add_rewrite_rule('ˆfav/?$', '/plugins/find_a_voice/public/fav_json_feed.php', 'top');

add_action('admin_menu', 'lfm_admin_dashboard');
//add_shortcode( 'lfm_find_a_voice', 'show_find_a_voice_table' );

add_filter( 'init', function( $template ) {
    if ( isset( $_GET['fav'] ) ) {
        $invoice_id = $_GET['fav'];
        include plugin_dir_path( __FILE__ ) . 'public/fav_json_feed.php';
        die;
    }
} );


// 	Creates admin dasboard menu
function lfm_admin_dashboard()
{
    if (!current_user_can('manage_options')) {
	    return;
    }    
    
    include(plugin_dir_path(__FILE__) . 'admin/admin_menu.php');
}

// 	Create database tables if it doesnt exist
function install_find_a_voice() {	
	if (!current_user_can('manage_options')) {
		return;
	}
	
	include(plugin_dir_path(__FILE__) . 'admin/db_create.php');
}

// 	Install default data
function install_find_a_voice_data()
{
	if (!current_user_can('manage_options')) {
		return;
	}
	
	include(plugin_dir_path(__FILE__) . 'admin/db_populate.php');
}

// 	Loads admin dashboard
function show_lfm_dashboard()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    include(plugin_dir_path(__FILE__) . 'admin/dashboard.php');
}

// 	Load voice talents page
function show_lfm_voice_talents()
{
	// Check user capabilities
	if (!current_user_can('manage_options')) {
		return;
	}
	
	include(plugin_dir_path(__FILE__) . 'admin/talents.php');
}

// 	Loads media files list
function show_lfm_media()
{
	if (!current_user_can( 'manage_options' )) {
		return ;
	}
	include(plugin_dir_path(__FILE__) . 'admin/media.php');
}

// 	Loads tones list
function show_lfm_tones()
{
	if (!current_user_can( 'manage_options' )) {
		return ;
	}
	include(plugin_dir_path(__FILE__) . 'admin/tones.php');
}

// 	Loads accents list
function show_lfm_accents()
{
	if (!current_user_can( 'manage_options' )) {
		return ;
	}
	include(plugin_dir_path(__FILE__) . 'admin/accents.php');
}

// 	Loads styles list
function show_lfm_styles()
{
	if (!current_user_can( 'manage_options' )) {
		return ;
	}
	include(plugin_dir_path(__FILE__) . 'admin/styles.php');
}

// 	Loads ages list
function show_lfm_ages()
{
	if (!current_user_can( 'manage_options' )) {
		return ;
	}
	include(plugin_dir_path(__FILE__) . 'admin/ages.php');
}

// 	Loads gender list
function show_lfm_genders()
{
	if (!current_user_can( 'manage_options' )) {
		return ;
	}
	include(plugin_dir_path(__FILE__) . 'admin/genders.php');
}

// 	Loads languages list
function show_lfm_languages()
{
	if (!current_user_can( 'manage_options' )) {
		return ;
	}
	include(plugin_dir_path(__FILE__) . 'admin/languages.php');
}

// 	Loads platforms list
function show_lfm_platforms()
{
	if (!current_user_can( 'manage_options' )) {
		return ;
	}
	include(plugin_dir_path(__FILE__) . 'admin/platforms.php');
}

// 	Add accent screen options
function add_accent_options() {
	$option = 'per_page';
	$args = array(
		'label' => "Accents per page",
		'default' => 10,
		'option' => "accents_per_page"
	);
	add_screen_option( $option, $args );
}

function accent_table_set_option($status, $option, $value){
	echo 'test';
	if ('accents_per_page' == $option) return $value;
	return $status;
}

function show_find_a_voice_table( $atts ) {
	wp_register_style('boot_css', '/wp-content/plugins/find-a-voice/public/css/simpleGridTemplate.css');
	wp_enqueue_style( 'boot_css' );
	wp_register_style('font_css', 'https://use.fontawesome.com/releases/v5.3.1/css/all.css');
	wp_enqueue_style( 'font_css' );
	
	// normalize attribute keys, lowercase
    $atts = array_change_key_case((array)$atts, CASE_LOWER);
	echo $atts['platform'];

//	include(plugin_dir_path(__FILE__) . 'public/showtable.php');
//	include(plugin_dir_path(__FILE__) . 'public/showcards.php');
//	include(plugin_dir_path(__FILE__) . 'public/showcardstable.php');
	include(plugin_dir_path(__FILE__) . 'public/grid.php');
}

function find_a_voice_json() {
	
}

?>