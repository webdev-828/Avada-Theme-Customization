<?php
/**
 * User interface class for Page Builder
 *
 * @package   FusionCore
 * @author	ThemeFusion
 * @link	  http://theme-fusion.com
 * @copyright ThemeFusion
 */

if( ! class_exists( 'Fusion_Core_PageBuilder_UI' ) ) {

	class Fusion_Core_PageBuilder_UI {

		/**
		 * Instance of this class.
		 *
		 * @since	1.0.0
		 *
		 * @var	  object
		 */
		protected static $instance = null;

		var $settings = array();

		/**
		 * Initialize the hooks and filters for the page builder UI
		 *
		 * @since  1.0.0
		 */
		private function __construct( $settings = array() ) {

			$this->settings = $settings;
			add_action( 'init', array( $this, 'init' ) );

		}

		/**
		 * Return an instance of this class.
		 *
		 * @since	 1.0.0
		 *
		 * @return	object	A single instance of this class.
		 */
		public static function get_instance( $settings = array() ) {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self( $settings );
			}

			return self::$instance;

		}

		public function init() {

			add_action( 'edit_form_after_title', array( $this, 'wrap_default_editor' ) ); 
			add_action( 'edit_form_after_editor', array( $this, 'close_default_editor_wrap' ) );

		}
		public function get_page_builder() {
			
		}

		public function wrap_default_editor() {
			$screen = get_current_screen();
			
			$allowed_screens 	= $this->settings['allowed_post_types'];
			if ( in_array( $screen->id, $allowed_screens) ) {
				global $post_ID;
	
				$pagebuilder_label 	= __( 'Fusion Page Builder', 'fusion-core' );
				$default_label  	= __( 'Default Editor', 'fusion-core' );
				$status		 	= get_post_meta($post_ID, '_fusionPageBuilderStatus', true);
				echo '<a id="fusion-pb-switch-button" href="javascript:void(0);" class="button-primary" data-active-button="' . $default_label . '" data-inactive-button="' . $pagebuilder_label . '">' . $pagebuilder_label . '</a>';
				echo '<div id="postdivrich_wrap">';
			}
		
		}
		
		public function close_default_editor_wrap() {
			$screen = get_current_screen();

			$allowed_screens 	= $this->settings['allowed_post_types'];
			if ( in_array( $screen->id, $allowed_screens) ) {
				echo '</div>';
				$post = get_post( get_queried_object_id() ); 
				$content = htmlentities( $post->post_content, ENT_COMPAT, 'UTF-8' );
				echo '<textarea class="fusion-builder-pagecontent">' . $content . '</textarea>';
				$this->get_page_builder();
			}
		}

	}
	
}