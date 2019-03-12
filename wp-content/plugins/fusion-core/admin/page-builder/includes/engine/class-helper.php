<?php
/**
 * FusionHelper class with static methods
 */
class FusionHelper  {
	/**
	 * Convert object sent using Ajax into array.
	 * @param type $object data object
	 * @return type data array
	 */
	public static function OBJECT_TO_ARRAY( $object ) {
		if ( is_object($object) ) {
			// Gets the properties of the given object
			// with get_object_vars function
			$object = get_object_vars($object);
		}
		if ( is_array($object) ) {
			/*
			* Return array converted to object
			* Using recursive call
			*/
			return array_map("FusionHelper::OBJECT_TO_ARRAY", $object);
		}
		else {
				// Return array
				return $object;
		}
	}
	/**
	 * Create drop down data of wordpress existing categories
	 * @params: null
	 * @return: data array
	 */
	public static function get_wp_categories_list () {
		
		$args = array(
			'type'					 => 'post', 		// tye of categor
			'child_of'				 => 0, 			// child of some specific
			'parent'				 => '', 			// should get parents?
			'orderby'				 => 'name', 		// sorty by name
			'order'					 => 'ASC', 		// in ascending order
			'hide_empty'			 => 0, 			// hide empty categories
			'hierarchical'			 => 1, 			// get in hirearchical order
			'exclude'				 => '', 			// no categories to exclude
			'include'				 => '', 			// include all categories
			'number'				 => '', 			// number of categories to retun. Empty for ALL.
			'taxonomy'				 => 'category', 	// taxonomy to return
			'pad_counts'			 => true  		// get number of posts for each category
		
		); 
		
		$categories 		= get_categories( $args );
		$categories_list 	= array();
		
		foreach ( $categories as $category ) {
			$data = array (
					$category->slug => $category->name." (".$category->category_count.")" //category name and post count
					);
			$categories_list += $data;
		}
		
		return $categories_list;
		
	}
	/**
	 * Get taxonomy categories
	 * @params: $taxonomy, $empty_choice
	 * @return: data array
	 */
	public static function fusion_shortcodes_categories ( $taxonomy, $empty_choice = false, $empty_choice_label = 'Default' ) {
		if( taxonomy_exists( $taxonomy ) ) {
			if( $empty_choice == true ) {
				$post_categories[''] = $empty_choice_label;
			}

			$get_categories = get_categories('hide_empty=0&taxonomy=' . $taxonomy);

			if( ! array_key_exists('errors', $get_categories) ) {
				if( $get_categories && is_array($get_categories) ) {
					foreach ( $get_categories as $cat ) {
						$post_categories[$cat->slug] = $cat->name;
					}
				}

				if( isset( $post_categories ) && count( $post_categories ) > 0 ) {
					return $post_categories;
				} else {
					return array();
				}
			}
		} else {
			return array();
		}
	}
	/**
	 * Create drop down data in form of array
	 * @params: $array, $start, $end
	 * @return: data array
	 */
	 public static function fusion_create_dropdown_data ( $start, $end, $dataArray = array() ) {
		 
		 for ($i = $start; $i <= $end; $i++ ) {
			 $array['fusion_' . $i] = $i;
		 }

		 $dataArray = $dataArray + $array;
		 
		 return $dataArray;
	 }
	/**
	 * Generate icons list
	 * you can get more icons names from http://fontawesome.io/icons/
	 * @return array icons array
	 */
	public static function GET_ICONS_LIST() {
		
		$icons = new FAIterator(FUSION_BUILDER_FA_PATH);
		$iconssArray = array();
		$output = '';
		foreach ($icons as $icon) {
			$output .= '<span class="icon_preview"><i class="fa ' . $icon->class . '" data-name="' . $icon->class . '"></i></span>';
		}
		
		return $output;
	}
	/**
	 * Returns array of layerslider slide groups
	 * @param NULL
	 * @return array slide keys array
	 */
	public static function get_layerslider_slides () {
		global $wpdb;
		$slides_array['fusion_0'] 	= 'Select a slider';
		// Table name
		$table_name 		= $wpdb->prefix . "layerslider";
		//check if table exists
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			//table not exists
		} else {
			// Get sliders
			$sliders 			= $wpdb->get_results( "SELECT * FROM $table_name
											WHERE flag_hidden = '0' AND flag_deleted = '0'
											ORDER BY date_c ASC" );
											
			if(!empty($sliders)) {
				foreach($sliders as $key => $item) {
					$slides[$item->id] = '';
				}
			}
			
			if(isset($slides) && $slides){
				foreach($slides as $key => $val){
					$slides_array['fusion_'.$key] = 'LayerSlider #'.($key);
				}
			}
		}
		
		return $slides_array;
	}
	/**
	 * Returns array of rev slider slide groups
	 * @param NULL
	 * @return array slide keys array
	 */
	public static function get_revslider_slides () {
		
		global $wpdb;
		$revsliders['fusion_0'] 		= 'Select a slider';
		$table_name			= $wpdb->prefix.'revslider_sliders';
		//check if table exists
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			//table not exists
		} else {
			
			if(function_exists('rev_slider_shortcode')) {
				$get_sliders = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'revslider_sliders');
				if($get_sliders) {
					foreach($get_sliders as $slider) {
						$revsliders['fusion_'.$slider->alias] = $slider->title;
					}
				}
			}
		}
		
		return $revsliders;
	}
	/**
	 * Returns array of elastic slider slide groups
	 * @param NULL
	 * @return array slide keys array
	 */
	public static function get_elasticslider_slides () {
		$slides_array 		= array();
		$slides_array[0] 	= 'Select a slider';
		$slides 			= get_terms('themefusion_es_groups');
		
		if($slides && !isset($slides->errors)){
			$slides = is_array($slides) ? $slides : unserialize($slides);
			foreach($slides as $key => $val){
				$slides_array[$val->slug] = $val->name;
			}
		}
		
		return $slides_array;
	}
	/**
	 * Returns array of theme fusion slider slide groups
	 * @param NULL
	 * @return array slide keys array
	 */
	public static function get_tfslider_slides () {
		global $data;
		$slides_array 		= array();
		$slides_array[0] 	= 'Select a slider';
		$counter 			= 1;
		if ( isset ( $data['flexsliders_number'] ) ) {
			while($counter <= $data['flexsliders_number']){
				$slides_array['flexslider_'.$counter] = 'TFSlider'.$counter;
				$counter ++;
			}
		}
		return $slides_array;
	}
	/**
	 * Returns array of animation speed data
	 * @param NULL
	 * @return array
	 */
	public static function get_animation_speed_data ( $defaults = false ) {

		if( $defaults ) {
			$dec_numbers = array(
								''	  => 'Default',
								'0.1' => '0.1',
								'0.2' => '0.2',
								'0.3' => '0.3',
								'0.4' => '0.4',
								'0.5' => '0.5',
								'0.6' => '0.6',
								'0.7' => '0.7',
								'0.8' => '0.8',
								'0.9' => '0.9',
								'1'   => '1' 
							);
		} else {
			$dec_numbers = array( 
								'0.1' => '0.1',
								'0.2' => '0.2',
								'0.3' => '0.3',
								'0.4' => '0.4',
								'0.5' => '0.5',
								'0.6' => '0.6',
								'0.7' => '0.7',
								'0.8' => '0.8',
								'0.9' => '0.9',
								'1'   => '1' 
							);
		}
		
		return $dec_numbers;
	}
	/**
	 * Returns array of choices data
	 * @param NULL
	 * @return array
	 */
	public static function get_shortcode_choices () {
		$choices = array(
							'yes' => 'Yes',
							'no' => 'No'
						);
						
		return $choices;
	}
	/**
	 * Returns array of choices data with default
	 * @param NULL
	 * @return array
	 */
	public static function get_shortcode_choices_with_default () {
		$choices = array(
							'' => 'Default',
							'yes' => 'Yes',
							'no' => 'No'
						);
						
		return $choices;
	}
	/**
	 * Returns array of animation direction data
	 * @param NULL
	 * @return array
	 */
	public static function get_animation_direction_data ( $defaults = false ) {
		
		if( $defaults ) {
			$directions = array(
								''			=> 'Default',
								'down' 		=> 'Top',
								'left' 		=> 'Left',
								'right' 	=> 'Right',
								'up' 		=> 'Bottom',
								'static'	=> 'Static'
							);
		} else {
			$directions = array(
								'down' 		=> 'Top',
								'left' 		=> 'Left',
								'right' 	=> 'Right',
								'up' 		=> 'Bottom',
								'static'	=> 'Static'
							);
		}

		return $directions;
	}
	/**
	 * Returns array of animation type data
	 * @param NULL
	 * @return array
	 */
	public static function get_animation_type_data ( $defaults = false ) {
		if( $defaults ) {
			$types = 	array(
								''			=> 'Default',
								'0' 		=> 'None',
							   'bounce' 	=> 'Bounce',
							   'fade' 		=> 'Fade',
							   'flash'		=> 'Flash',
							   'rubberBand'	=> 'Rubberband',						   
							   'shake' 		=> 'Shake',
							   'slide'		=> 'Slide',
							   'zoom'		=> 'Zoom'
						);			
		} else {
			$types = 	array(
								'0' 		=> 'None',
							   'bounce' 	=> 'Bounce',
							   'fade' 		=> 'Fade',
							   'flash'		=> 'Flash',
							   'rubberBand'	=> 'Rubberband',						   
							   'shake' 		=> 'Shake',
							   'slide'		=> 'Slide',
							   'zoom'		=> 'Zoom'
						);
		}
					
		return $types; 
	}
	/**
	 * Returns array of left-right  data
	 * @param NULL
	 * @return array
	 */
	public static function get_left_right_data () {
		$leftright = array( 
							'left' 		=> 'Left',
							'right' 	=> 'Right' 
					);
					
		return $leftright;
	}
	/**
	 * Returns array of no-yes  data
	 * @param NULL
	 * @return array
	 */
	public static function get_reversed_choice_data () {
		$reverse_choices = array( 
									'no' => 'No', 
									'yes' => 'Yes' 
								);
		return $reverse_choices;
	}
	/**
	 * Function to generate combinations for number of elements in each column
	 *
	 * @since	 2.0.0
	 *
	 * @return	Array	Array of $columns index where each index has a number value
	 */
	public static function generate_column_combinations( $total, $columns ) {
		
		$combinations 		= array();
		$per_col			= floor( $total / $columns );
		$per_col_increment	= $total % $columns;
		//if not divided equally
		if( $per_col_increment > 0 ) {
			$per_col++;
		}
		
		for( $i = 0; $i < $columns; $i++ ) {
			
			if( $total > $per_col ) {
				
				$combinations[$i]		= $per_col;
				$total					= $total-$per_col;
				
				} else {
					
					$combinations[$i] 	= $total;
					$total				= 0;
				}
		}
		
		return $combinations;
	}
	
	public static function get_sidebars() {
		global $wp_registered_sidebars;
		
		$sidebars = array();
		
		foreach( $wp_registered_sidebars as $sidebar_id => $sidebar ) {
			$sidebars[$sidebar_id] = $sidebar['name'];
		}
		
		return $sidebars;
	}
	
}

