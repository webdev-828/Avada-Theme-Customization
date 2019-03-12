<?php
/**
 * The AvadaRedux Framework Plugin
 *
 * A simple, truly extensible and fully responsive options framework
 * for WordPress themes and plugins. Developed with WordPress coding
 * standards and PHP best practices in mind.
 *
 * Plugin Name:     AvadaRedux Framework
 * Plugin URI:      http://wordpress.org/plugins/avadaredux-framework
 * Github URI:      https://github.com/AvadaReduxFramework/avadaredux-framework
 * Description:     AvadaRedux is a simple, truly extensible options framework for WordPress themes and plugins.
 * Author:          Team AvadaRedux
 * Author URI:      http://avadareduxframework.com
 * Version:         3.5.9.8
 * Text Domain:     avadaredux-framework
 * License:         GPL3+
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:     AvadaReduxCore/languages
 * Provides:        AvadaReduxFramework
 *
 * @package         AvadaReduxFramework
 * @author          Dovy Paukstys <dovy@avadareduxframework.com>
 * @author          Kevin Provance <kevin@avadareduxframework.com>
 * @license         GNU General Public License, version 3
 * @copyright       2012-2016 AvadaRedux.io
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    die;
}

// Require the main plugin class
require_once plugin_dir_path( __FILE__ ) . 'class.avadaredux-plugin.php';

// Register hooks that are fired when the plugin is activated and deactivated, respectively.
register_activation_hook( __FILE__, array( 'AvadaReduxFrameworkPlugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'AvadaReduxFrameworkPlugin', 'deactivate' ) );

// Get plugin instance
//add_action( 'plugins_loaded', array( 'AvadaReduxFrameworkPlugin', 'instance' ) );

// The above line prevents AvadaReduxFramework from instancing until all plugins have loaded.
// While this does not matter for themes, any plugin using AvadaRedux will not load properly.
// Waiting until all plugins have been loaded prevents the AvadaReduxFramework class from
// being created, and fails the !class_exists('AvadaReduxFramework') check in the sample_config.php,
// and thus prevents any plugin using AvadaRedux from loading their config file.
AvadaReduxFrameworkPlugin::instance();
