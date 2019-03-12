<?php

/*
Plugin Name: LayerSlider WP
Plugin URI: http://codecanyon.net/user/kreatura/
Description: LayerSlider is the most advanced responsive WordPress slider plugin with the famous Parallax Effect and over 200 2D & 3D transitions.
Version: 5.6.2
Author: Kreatura Media
Author URI: http://kreaturamedia.com/
Text Domain: LayerSlider
*/

if(defined('LS_PLUGIN_VERSION') || isset($GLOBALS['lsPluginPath'])) {
	die('ERROR: It looks like you already have one instance of LayerSlider installed. WordPress cannot activate and handle two instanced at the same time, you need to remove the old version first.');
}

if(!defined('ABSPATH')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

/********************************************************/
/*                        Actions                       */
/********************************************************/

	// Action to redirect to Layerslider's admin page after activation
	add_action('admin_init', 'layerslider_activation_redirect');

	// Legacy, will be dropped
	$GLOBALS['lsAutoUpdateBox'] = true;

	// Basic configuration
	define('LS_DB_TABLE', 'layerslider');
	define('LS_PLUGIN_VERSION', '5.6.2');

	// Path info
	define('LS_ROOT_FILE', __FILE__);
	define('LS_ROOT_PATH', dirname(__FILE__));
	define('LS_ROOT_URL', plugins_url('', __FILE__));

	// Other constants
	define('LS_PLUGIN_SLUG', basename(dirname(__FILE__)));
	define('LS_PLUGIN_BASE', plugin_basename(__FILE__));
	define('LS_MARKETPLACE_ID', '1362246');
	define('LS_TEXTDOMAIN', 'LayerSlider');
	define('LS_REPO_BASE_URL', 'http://repository.kreaturamedia.com/v3/');

	if(!defined('NL')) { define("NL", "\r\n"); }
	if(!defined('TAB')) { define("TAB", "\t"); }

	// Shared
	include LS_ROOT_PATH.'/wp/scripts.php';
	include LS_ROOT_PATH.'/wp/menus.php';
	include LS_ROOT_PATH.'/wp/hooks.php';
	include LS_ROOT_PATH.'/wp/widgets.php';
	include LS_ROOT_PATH.'/wp/compatibility.php';

	include LS_ROOT_PATH.'/classes/class.ls.posts.php';
	include LS_ROOT_PATH.'/classes/class.ls.sliders.php';
	include LS_ROOT_PATH.'/classes/class.ls.sources.php';


	// Register WP shortcode
	include LS_ROOT_PATH.'/wp/shortcodes.php';
	LS_Shortcode::registerShortcode();

	// Add demo sliders and skins
	LS_Sources::addDemoSlider(LS_ROOT_PATH.'/demos/');
	LS_Sources::addSkins(LS_ROOT_PATH.'/static/skins/');
	LS_Sources::removeSkin('preview');


	// Back-end only
	if(is_admin()) {
		include LS_ROOT_PATH.'/wp/activation.php';
		include LS_ROOT_PATH.'/wp/tinymce.php';
		include LS_ROOT_PATH.'/wp/notices.php';
		include LS_ROOT_PATH.'/wp/actions.php';

	// Front-end only
	} else {

	}


	// Auto update
	if(!class_exists('KM_PluginUpdatesV3')) {
		require_once LS_ROOT_PATH.'/classes/class.km.autoupdate.plugins.v3.php';
	}

		new KM_PluginUpdatesV3(array(
			'repoUrl' => LS_REPO_BASE_URL,
			'root' => LS_ROOT_FILE,
			'version' => LS_PLUGIN_VERSION,
			'itemID' => LS_MARKETPLACE_ID,
			'codeKey' => 'layerslider-purchase-code',
			'authKey' => 'layerslider-authorized-site',
			'channelKey' => 'layerslider-release-channel'
		));


	// Hook to trigger plugin override functions
	add_action('after_setup_theme', 'layerslider_loaded');
	add_action('plugins_loaded', 'layerslider_load_lang');


// Redirect to LayerSlider's main admin page after plugin activation.
// Should not trigger on multisite bulk activation or after upgrading
// the plugin to a newer versions.
function layerslider_activation_redirect() {
	if(get_option('layerslider_do_activation_redirect', false)) {
		delete_option('layerslider_do_activation_redirect');
		if(isset($_GET['activate']) && !isset($_GET['activate-multi'])) {
			wp_redirect(admin_url('admin.php?page=layerslider'));
		}
	}
}

function layerslider_load_lang() {
	load_plugin_textdomain('LayerSlider', false, LS_PLUGIN_SLUG . '/locales/' );
}


/********************************************************/
/*          WPML Layer's String Translation             */
/********************************************************/
function layerslider_register_wpml_strings($slider_id, $data) {


	global $wpdb;
	$table_name = $wpdb->prefix . "layerslider";

	$slider = $wpdb->get_row("SELECT * FROM $table_name WHERE id = ".(int)$slider_id." ORDER BY date_c DESC LIMIT 1" , ARRAY_A);
	$slider = json_decode($slider['data'], true);

	foreach($data['layers'] as $layerkey => $layer) {
		foreach($layer['sublayers'] as $sublayerkey => $sublayer) {
			if($sublayer['type'] != 'img') {
				icl_register_string('LayerSlider WP', '<'.$sublayer['type'].':'.substr(sha1($sublayer['html']), 0, 10).'> layer on slide #'.($layerkey+1).' in slider #'.$slider_id.'', $sublayer['html']);
			}
		}
	}
}



/********************************************************/
/*                        MISC                          */
/********************************************************/

function layerslider_builder_convert_numbers(&$item, $key) {
	if(is_numeric($item)) {
		$item = (float) $item;
	}
}

function ls_ordinal_number($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    $mod100 = $number % 100;
    return $number . ($mod100 >= 11 && $mod100 <= 13 ? 'th' :  $ends[$number % 10]);
}



function layerslider_check_unit($str) {

	if(strstr($str, 'px') == false && strstr($str, '%') == false) {
		return $str.'px';
	} else {
		return $str;
	}
}

function layerslider_convert_urls($arr) {

	// Global BG
	if(!empty($arr['properties']['backgroundimage']) && strpos($arr['properties']['backgroundimage'], 'http://') !== false) {
		$arr['properties']['backgroundimage'] = parse_url($arr['properties']['backgroundimage'], PHP_URL_PATH);
	}

	// YourLogo img
	if(!empty($arr['properties']['yourlogo']) && strpos($arr['properties']['yourlogo'], 'http://') !== false) {
		$arr['properties']['yourlogo'] = parse_url($arr['properties']['yourlogo'], PHP_URL_PATH);
	}

	if(!empty($arr['layers'])) {
		foreach($arr['layers'] as $key => $slide) {

			// Layer BG
			if(strpos($slide['properties']['background'], 'http://') !== false) {
				$arr['layers'][$key]['properties']['background'] = parse_url($slide['properties']['background'], PHP_URL_PATH);
			}

			// Layer Thumb
			if(strpos($slide['properties']['thumbnail'], 'http://') !== false) {
				$arr['layers'][$key]['properties']['thumbnail'] = parse_url($slide['properties']['thumbnail'], PHP_URL_PATH);
			}

			// Image sublayers
			if(!empty($slide['sublayers'])) {
				foreach($slide['sublayers'] as $subkey => $layer) {
					if($layer['media'] == 'img' && strpos($layer['image'], 'http://') !== false) {
						$arr['layers'][$key]['sublayers'][$subkey]['image'] = parse_url($layer['image'], PHP_URL_PATH);
					}
				}
			}
		}
	}

	return $arr;
}
