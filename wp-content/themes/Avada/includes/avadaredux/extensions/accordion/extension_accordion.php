<?php

	/**
	 * AvadaRedux Framework is free software: you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published by
	 * the Free Software Foundation, either version 2 of the License, or
	 * any later version.
	 * AvadaRedux Framework is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	 * GNU General Public License for more details.
	 * You should have received a copy of the GNU General Public License
	 * along with AvadaRedux Framework. If not, see <http://www.gnu.org/licenses/>.
	 *
	 * @package     AvadaRedux Framework
	 * @subpackage  Accordion Section
	 * @subpackage  Wordpress
	 * @author      Kevin Provance (kprovance)
	 * @version     1.0.1
	 */

// Exit if accessed directly
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

// Don't duplicate me!
	if ( ! class_exists( 'AvadaReduxFramework_extension_accordion' ) ) {


		/**
		 * Main AvadaReduxFramework_extension_accordion extension class
		 *
		 * @since       1.0.0
		 */
		class AvadaReduxFramework_extension_accordion {

			public static $version = '1.0.1';

			// Protected vars
			protected $parent;
			public $extension_url;
			public $extension_dir;
			public static $theInstance;
			public static $ext_url;
			public $field_id = '';
			private $class_css = '';
			public $field_name = 'accordion';

			/**
			 * Class Constructor. Defines the args for the extions class
			 *
			 * @since       1.0.0
			 * @access      public
			 *
			 * @param       array $parent Parent settings.
			 *
			 * @return      void
			 */
			public function __construct( $parent ) {

				$avadaredux_ver = AvadaReduxFramework::$_version;

				// Set parent object
				$this->parent = $parent;

				// Set extension dir
				if ( empty( $this->extension_dir ) ) {
					$this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
					$this->extension_url = trailingslashit( get_template_directory_uri() ) . 'includes/avadaredux/extensions/accordion/';
					// $this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
					self::$ext_url       = $this->extension_url;
				}

				// Set instance
				self::$theInstance = $this;

				// Uncomment when customizer works - kp
				//include_once($this->extension_dir . 'multi-media/inc/class.customizer.php');
				//new AvadaReduxColorSchemeCustomizer($parent, $this->extension_dir);

				// Adds the local field
				add_filter( 'avadaredux/' . $this->parent->args['opt_name'] . '/field/class/' . $this->field_name, array(
					&$this,
					'overload_field_path'
				) );
			}

			static public function getInstance() {
				return self::$theInstance;
			}

			static public function getExtURL() {
				return self::$ext_url;
			}

			// Forces the use of the embeded field path vs what the core typically would use
			public function overload_field_path( $field ) {
				return dirname( __FILE__ ) . '/' . $this->field_name . '/field_' . $this->field_name . '.php';
			}
		}
	}
