<?php
/**
 * Class for Custom templates
 *
 * @package   FusionCore
 * @author	ThemeFusion
 * @link	  http://theme-fusion.com
 * @copyright ThemeFusion
 */

if( ! class_exists( 'Fusion_Core_Custom_Templates' ) ) {

	class Fusion_Core_Custom_Templates {

		/**
		 * Instance of this class.
		 *
		 * @since	2.0.0
		 *
		 * @var	  object
		 */
		protected static $instance = null;

		
		function __construct() {
			
			//constructor
		}

		/**
		 * Return an instance of this class.
		 *
		 * @since	 2.0.0
		 *
		 * @return	object	A single instance of this class.
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;

		}
		/**
		 * Function to get tab content for custom templates
		 *
		 * @since	 2.0.0
		 *
		 * @return	String	String having tab content
		 */
		public function get_custom_templates () {
			
			$columns		= 6; //number of columns
			$content 		= '<div id="custom_templates_wrapper">
								<div id="custom_templates_left">
								<h1 class="templates_heading">'.__('Save Template', 'fusion-core').'</h1>
								<div class="save_templates_here">
								<a class="templates_selection" id="fusion_save_custom_tpl" 
								href="JavaScript:void(0)">'.__('Save Layout As Template', 'fusion-core').'</a>
								</div>
								</div>
								<div id="custom_templates_right">
								<h1 class="templates_heading">'.__('Load Template', 'fusion-core').'</h1>';
			//get templates data
			$templates 		= get_option( 'fusion_custom_templates' );
			
			//if value exists and there are more than 1 number of templates
			if ( $templates != false && count( $templates ) > 0 ) {
				//generate column combinations
				$combinations 	= FusionHelper::generate_column_combinations( count( $templates ), $columns );
				
				//add data in each column
				for( $i = 0; $i < $columns; $i++) {
					//if no data available for this column then break
					if( $combinations[$i] == 0 ) { break; }
					$counter = 0 ;
					$content.= ' <div class="custom_templates_sections">';
					
					foreach( $templates as $key => $value ) {
						$content.= ' <div style="position:relative;" class="template_selection_wrapper"> ';
						$content.= ' <div class="hidden_overlay">
										<a href="JavaScript:void(0)" data-id="'.$key.'"  class="fuiosn_load_template">'.__('LOAD', 'fusion-core').'</a>
										<a href="JavaScript:void(0)" data-id="'.$key.'"  class="fusion_delete_template">'.__('DELETE', 'fusion-core').'</a>
									 </div>';
						$content.= ' <a class="fusion_custom_template templates_selection" href="JavaScript:void(0)">'.$key.'</a>';
						$content.= ' </div>';
						//remove current element from array for next iteration
						unset( $templates[$key] );
						$counter++;
						//if reached combination value then break loop
						if ( $counter == $combinations[$i] ) { break; }
					}
					$content.= '</div>';
				}
			}
			
			$content.=	'</div>';
			
			return $content;

		}
		/**
		 * Function to get get single template content
		 *
		 * @since	 2.0.0
		 *
		 * @return	Array	Array having template data
		 */
		public function get_single_template () {
			
			$custom_templates = get_option( 'fusion_custom_templates' );
			
			//if value exists
			if ( $custom_templates != false ) {
				
				//print_r($custom_templates);
				return $custom_templates[ stripslashes($_POST['name']) ];
			} else {
				return false;
			}
			
			
		}
		
		/**
		 * Function to save single template data
		 *
		 * @since	 2.0.0
		 *
		 * @return	Boolean	True for success and FALSE for failure
		 */
		public function save_single_template () {
			
			
			$response			= false;
			$custom_templates 	= array();
			//strip slashes and remove newline characters
			$model 				= stripslashes( $_POST['model'] );
			
			$custom_templates = get_option( 'fusion_custom_templates' );
			//if value exists
			if ( $custom_templates === false ) {
				$custom_templates 					= array();
				$custom_templates[ stripslashes($_POST['name']) ] = $model;
				$response 							= add_option( 'fusion_custom_templates', $custom_templates, '', 'no' );
			} else {
				
				$custom_templates[ stripslashes($_POST['name']) ] = $model;
				
				$response 							= update_option( 'fusion_custom_templates',  $custom_templates );
				
			}
			
			return $response;
			
		}
		
		public function delete_single_template () {
			$response			= false;
			$custom_templates 	= array();
			$custom_templates 	= get_option( 'fusion_custom_templates' );
			//if templates exists
			if ( $custom_templates != false ) {
				//remove template
				unset( $custom_templates[ stripslashes($_POST['name']) ]  );
				$response 							= update_option( 'fusion_custom_templates', $custom_templates );
				
			} 
			
			return $response;
		}

	}
	
}