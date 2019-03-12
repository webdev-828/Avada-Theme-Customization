<?php

if( ! class_exists( 'Fusion_Slider' ) ) {
	class Fusion_Slider {

		function __construct() {
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			// Add settings
			add_action( 'slide-page_add_form_fields', array( $this, 'slider_add_new_meta_fields' ), 10, 2 );
			add_action( 'slide-page_edit_form_fields', array( $this, 'slider_edit_meta_fields' ), 10, 2 );
			add_action( 'edited_slide-page', array( $this, 'slider_save_taxonomy_custom_meta' ), 10, 2 );
			add_action( 'create_slide-page', array( $this, 'slider_save_taxonomy_custom_meta' ), 10, 2 );
			// Clone slide
			add_action( 'admin_action_save_as_new_slide', array( $this, 'save_as_new_slide' ) );
			add_filter( 'post_row_actions',  array( $this, 'admin_clone_slide_button' ), 10, 2 );
			add_action( 'edit_form_after_title', array( $this, 'admin_clone_slide_button_after_title' ) );
			// Clone slider
			add_filter( 'slide-page_row_actions', array( $this, 'admin_clone_slider_button' ), 10, 2 );
			add_action( 'slide-page_edit_form_fields', array( $this, 'admin_clone_slider_button_edit_form' ) );
			add_action( 'admin_action_clone_fusion_slider', array( $this, 'save_as_new_slider' ) );
		}

		function init() {
			global $smof_data;

			if( $smof_data['status_fusion_slider'] ) {
				register_post_type(
					'slide',
					array(
						'public' => true,
						'has_archive' => false,
						'rewrite' => array('slug' => 'slide'),
						'supports' => array('title', 'thumbnail'),
						'can_export' => true,
						'menu_position' => 100,
						'hierarchical' => false,
						'labels' => array(
							'name'				 => _x( 'Fusion Slides', 'Post Type General Name', 'fusion-core' ),
							'singular_name'	     => _x( 'Fusion Slide', 'Post Type Singular Name', 'fusion-core' ),
							'menu_name'		     => __( 'Fusion Slider', 'fusion-core' ),
							'parent_item_colon'  => __( 'Parent Slide:', 'fusion-core' ),
							'all_items'		     => __( 'Add or Edit Slides', 'fusion-core' ),
							'view_item'		     => __( 'View Slide', 'fusion-core' ),
							'add_new_item'		 => __( 'Add New Slide', 'fusion-core' ),
							'add_new'			 => __( 'Add New Slide', 'fusion-core' ),
							'edit_item'		     => __( 'Edit Slide', 'fusion-core' ),
							'update_item'		 => __( 'Update Slide', 'fusion-core' ),
							'search_items'		 => __( 'Search Slide', 'fusion-core' ),
							'not_found'		     => __( 'Not found', 'fusion-core' ),
							'not_found_in_trash' => __( 'Not found in Trash', 'fusion-core' ),
						)
					)
				);

				register_taxonomy('slide-page', 'slide',
					array(
						'hierarchical' => true,
						'label' => 'Slider',
						'query_var' => true,
						'rewrite' => true,
						'hierarchical' => true,
						'show_in_nav_menus' => false,
						'show_tagcloud' => false,
						'labels' => array(
							'name'					     => __( 'Fusion Sliders', 'fusion-core' ),
							'singular_name'			     => __( 'Fusion Slider', 'fusion-core' ),
							'menu_name'				     => __( 'Add or Edit Sliders', 'fusion-core' ),
							'all_items'				     => __( 'All Sliders', 'fusion-core' ),
							'parent_item_colon'		     => __( 'Parent Slider:', 'fusion-core' ),
							'new_item_name'			     => __( 'New Slider Name', 'fusion-core' ),
							'add_new_item'			     => __( 'Add Slider', 'fusion-core' ),
							'edit_item'				     => __( 'Edit Slider', 'fusion-core' ),
							'update_item'				 => __( 'Update Slider', 'fusion-core' ),
							'separate_items_with_commas' => __( 'Separate sliders with commas', 'fusion-core' ),
							'search_items'			     => __( 'Search Sliders', 'fusion-core' ),
							'add_or_remove_items'		 => __( 'Add or remove sliders', 'fusion-core' ),
							'choose_from_most_used'	     => __( 'Choose from the most used sliders', 'fusion-core' ),
							'not_found'				     => __( 'Not Found', 'fusion-core' ),
						),
					)
				);
			}
		}

		/**
		 * Enqueue Scripts and Styles
		 *
		 * @return	void
		 */
		function admin_init() {
			global $pagenow;

			$post_type = '';

			if( isset( $_GET['post'] ) && $_GET['post'] ) {
				$post_type = get_post_type( $_GET['post'] );
			}

			if( ( isset( $_GET['taxonomy'] ) && $_GET['taxonomy'] == 'slide-page' ) || ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'slide' ) || $post_type == 'slide' ) {
				wp_enqueue_script( 'fusion-slider', plugin_dir_url( __FILE__ ) . 'js/fusion-slider.js', false, '1.0', true );
			}

			if( isset( $_GET['page'] ) && $_GET['page'] == 'fs_export_import' ) {
				$this->export_sliders();
			}
		}

		function admin_menu() {
			global $submenu;
			unset( $submenu['edit.php?post_type=slide'][10] );

			add_submenu_page( 'edit.php?post_type=slide', __( 'Export / Import', 'fusion-core' ), __( 'Export / Import', 'fusion-core' ), 'manage_options', 'fs_export_import', array( $this, 'fs_export_import_settings' ) );
		}

		// Add term page
		function slider_add_new_meta_fields() {
			// this will add the custom meta field to the add new term page
			?>
			<div class="form-field fusion-double-fields">
				<label for="term_meta[slider_width]"><?php _e( 'Slider Size', 'fusion-core' ); ?></label>
				<p class="description"><?php _e( 'Enter a pixel value for width and height, ex: 1000px', 'fusion-core' ); ?></p>

				<div class="fusion-field">
					<input type="text" name="term_meta[slider_width]" id="term_meta[slider_width]" value="100%">
					<label for="term_meta[slider_width]"><?php _e( 'Width', 'fusion-core' ); ?></label>
				</div>
				<div class="fusion-field">
					<input type="text" name="term_meta[slider_height]" id="term_meta[slider_height]" value="400px">
					<label for="term_meta[slider_height]"><?php _e( 'Height', 'fusion-core' ); ?></label>
				</div>
			</div>
			<div class="form-field">
				<label for="term_meta[slider_content_width]"><?php _e( 'Slider Content Max Width', 'fusion-core' ); ?></label>
				<input type="text" name="term_meta[slider_content_width]" id="term_meta[slider_content_width]" value="">
				<p class="description"><?php _e( 'Controls the width of content, In pixels, ex: 850px. Leave blank for site width.', 'fusion-core' ); ?></p>
			</div>
			<div class="form-field form-field-checkbox">
				<label for="term_meta[full_screen]"><?php _e( 'Full Screen Slider', 'fusion-core' ); ?></label>
				<input type="hidden" name="term_meta[full_screen]" id="term_meta[full_screen]" value="0">
				<input type="checkbox" name="term_meta[full_screen]" id="term_meta[full_screen]" value="1">
				<p class="description"><?php _e( 'Check this option if you want full width and height of the screen.', 'fusion-core' ); ?></p>
			</div>
			<div class="form-field form-field-checkbox">
				<label for="term_meta[parallax]"><?php _e( 'Parallax Scrolling Effect', 'fusion-core' ); ?></label>
				<input type="hidden" name="term_meta[parallax]" id="term_meta[parallax]" value="0">
				<input type="checkbox" name="term_meta[parallax]" id="term_meta[parallax]" value="1">
				<p class="description"><?php _e( 'Check this box to have a parallax scrolling effect, this ONLY works when assigning the slider in page options. It does not work when using a slider shortcode. With this option enabled, the slider height you input will not be exact due to negative margin which is based off the overall header size. ex: 500px will show as 415px. Please adjust accordingly.', 'fusion-core' ); ?></p>
			</div>
			<div class="form-field form-field-checkbox">
				<label for="term_meta[nav_arrows]"><?php _e( 'Display Navigation Arrows', 'fusion-core' ); ?></label>
				<input type="hidden" name="term_meta[nav_arrows]" id="term_meta[nav_arrows]" value="0">
				<input type="checkbox" name="term_meta[nav_arrows]" id="term_meta[nav_arrows]" value="1" checked="checked">
				<p class="description"><?php _e( 'Check this box to display the navigation arrows.', 'fusion-core' ); ?></p>
			</div>
			<div class="form-field fusion-double-fields">
				<label for="term_meta[nav_box_width]"><?php _e( 'Navigation Box Size', 'fusion-core' ); ?></label>
				<p class="description"><?php _e( 'Enter a pixel value for width and height, ex: 40px', 'fusion-core' ); ?></p>
				<div class="fusion-field">
					<input type="text" name="term_meta[nav_box_width]" id="term_meta[nav_box_width]" value="63px">
					<label for="term_meta[nav_box_width]"><?php _e( 'Width', 'fusion-core' ); ?></label>
				</div>
				<div class="fusion-field">
					<input type="text" name="term_meta[nav_box_height]" id="term_meta[nav_box_height]" value="63px">
					<label for="term_meta[nav_box_height]"><?php _e( 'Height', 'fusion-core' ); ?></label>
				</div>
			</div>
			<div class="form-field">
				<label for="term_meta[nav_arrow_size]"><?php _e( 'Navigation Arrow Size', 'fusion-core' ); ?></label>
				<input type="text" name="term_meta[nav_arrow_size]" id="term_meta[nav_arrow_size]" value="25px">
				<p class="description"><?php _e( 'Enter a pixel value for the arrow size, ex: 14px', 'fusion-core' ); ?></p>
			</div>
			<div class="form-field form-field-checkbox">
				<label for="term_meta[pagination_circles]"><?php _e( 'Display Pagination Circles', 'fusion-core' ); ?></label>
				<input type="hidden" name="term_meta[pagination_circles]" id="term_meta[pagination_circles]" value="0">
				<input type="checkbox" name="term_meta[pagination_circles]" id="term_meta[pagination_circles]" value="1">
				<p class="description"><?php _e( 'Check this box to display the pagination circles.', 'fusion-core' ); ?></p>
			</div>
			<div class="form-field form-field-checkbox">
				<label for="term_meta[autoplay]"><?php _e( 'Autoplay', 'fusion-core' ); ?></label>
				<input type="hidden" name="term_meta[autoplay]" id="term_meta[autoplay]" value="0">
				<input type="checkbox" name="term_meta[autoplay]" id="term_meta[autoplay]" value="1" checked="checked">
				<p class="description"><?php _e( 'Check this box to autoplay the slides.', 'fusion-core' ); ?></p>
			</div>
			<div class="form-field form-field-checkbox">
				<label for="term_meta[loop]"><?php _e( 'Slide Loop', 'fusion-core' ); ?></label>
				<input type="hidden" name="term_meta[loop]" id="term_meta[loop]" value="0">
				<input type="checkbox" name="term_meta[loop]" id="term_meta[loop]" value="1">
				<p class="description"><?php _e( 'Check this box to have the slider loop infinitely.', 'fusion-core' ); ?></p>
			</div>
			<div class="form-field">
				<label for="term_meta[animation]"><?php _e( 'Animation', 'fusion-core' ); ?></label>
				<select name="term_meta[animation]" id="term_meta[animation]">
					<option value="fade">Fade</option>
					<option value="slide">Slide</option>
				</select>
				<p class="description"><?php _e( 'The type of animation when slides rotate.', 'fusion-core' ); ?></p>
			</div>
			<div class="form-field">
				<label for="term_meta[slideshow_speed]"><?php _e( 'Slideshow Speed', 'fusion-core' ); ?></label>
				<input type="text" name="term_meta[slideshow_speed]" id="term_meta[slideshow_speed]" value="7000">
				<p class="description"><?php _e( 'Controls the speed of the slideshow. 1000 = 1 second.', 'fusion-core' ); ?></p>
			</div>
			<div class="form-field">
				<label for="term_meta[animation_speed]"><?php _e( 'Animation Speed', 'fusion-core' ); ?></label>
				<input type="text" name="term_meta[animation_speed]" id="term_meta[animation_speed]" value="600">
				<p class="description"><?php _e( 'Controls the speed of the slide transition from slide to slide. 1000 = 1 second.', 'fusion-core' ); ?></p>
			</div>
			<div class="form-field">
				<label for="term_meta[typo_sensitivity]"><?php _e( 'Responsive Typography Sensitivity', 'fusion-core' ); ?></label>
				<input type="text" name="term_meta[typo_sensitivity]" id="term_meta[typo_sensitivity]" value="1">
				<p class="description"><?php _e( 'Values below 1 decrease resizing, values above 1 increase sizing. ex: .6', 'fusion-core' ); ?></p>
			</div>
			<div class="form-field">
				<label for="term_meta[typo_factor]"><?php _e( 'Mininum Font Size Factor', 'fusion-core' ); ?></label>
				<input type="text" name="term_meta[typo_factor]" id="term_meta[typo_factor]" value="1.5">
				<p class="description"><?php _e( 'Minimum font factor is used to determine minimum distance between headings and body type by a multiplying value. ex: 1.5', 'fusion-core' ); ?></p>
			</div>
		<?php
		}

		// Edit term page
		function slider_edit_meta_fields( $term ) {
			// put the term ID into a variable
			$t_id = $term->term_id;

			// retrieve the existing value(s) for this meta field. This returns an array
			$term_meta = get_option( "taxonomy_$t_id" );

			if ( ! array_key_exists( 'typo_sensitivity', $term_meta ) ) {
				$term_meta['typo_sensitivity'] = '1';
			}

			if ( ! array_key_exists( 'typo_factor', $term_meta ) ) {
				$term_meta['typo_factor'] = '1.5';
			}

			if ( ! array_key_exists( 'nav_box_width', $term_meta ) ) {
				$term_meta['nav_box_width'] = '63px';
			}

			if ( ! array_key_exists( 'nav_box_height', $term_meta ) ) {
				$term_meta['nav_box_height'] = '63px';
			}

			if ( ! array_key_exists( 'nav_arrow_size', $term_meta ) ) {
				$term_meta['nav_arrow_size'] = '25px';
			}
			?>
			<tr class="form-field fusion-double-fields">
				<th scope="row" valign="top"><label for="term_meta[slider_width]"><?php _e( 'Slider Size', 'fusion-core' ); ?></label></th>
				<td>
					<p class="description"><?php _e( 'Enter a pixel value for width and height, ex: 1000px', 'fusion-core' ); ?></p>
					<div class="fusion-field">
						<input type="text" name="term_meta[slider_width]" id="term_meta[slider_width]" value="<?php echo esc_attr( $term_meta['slider_width'] ) ? esc_attr( $term_meta['slider_width'] ) : ''; ?>">
						<label for="term_meta[slider_width]"><?php _e( 'Width', 'fusion-core' ); ?></label>
					</div>
					<div class="fusion-field">
						<input type="text" name="term_meta[slider_height]" id="term_meta[slider_height]" value="<?php echo esc_attr( $term_meta['slider_height'] ) ? esc_attr( $term_meta['slider_height'] ) : ''; ?>">
						<label for="term_meta[slider_height]"><?php _e( 'Height', 'fusion-core' ); ?></label>
					</div>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="term_meta[slider_content_width]"><?php _e( 'Slider Content Max Width', 'fusion-core' ); ?></label></th>
				<td>
					<input type="text" name="term_meta[slider_content_width]" id="term_meta[slider_content_width]" value="<?php echo ( isset( $term_meta['slider_content_width'] ) && esc_attr( $term_meta['slider_content_width'] ) ) ? esc_attr( $term_meta['slider_content_width'] ) : ''; ?>">
					<p class="description"><?php _e( 'Controls the width of content,  In pixels, ex: 850px. Leave blank for site width.', 'fusion-core' ); ?></p>
				</td>
			</tr>
			<tr class="form-field form-field-checkbox">
				<th scope="row" valign="top"><label for="term_meta[full_screen]"><?php _e( 'Full Screen Slider', 'fusion-core' ); ?></label></th>
				<td>
					<input type="hidden" name="term_meta[full_screen]" id="term_meta[full_screen]" value="0">
					<input type="checkbox" name="term_meta[full_screen]" id="term_meta[full_screen]" value="1" <?php echo esc_attr( $term_meta['full_screen'] ) ? 'checked="checked"' : ''; ?>>
					<p class="description"><?php _e( 'Check this option if you want full width and height of the screen.', 'fusion-core' ); ?></p>
				</td>
			</tr>
			<tr class="form-field form-field-checkbox">
				<th scope="row" valign="top"><label for="term_meta[parallax]"><?php _e( 'Parallax Scrolling Effect', 'fusion-core' ); ?></label></th>
				<td>
					<input type="hidden" name="term_meta[parallax]" id="term_meta[parallax]" value="0">
					<input type="checkbox" name="term_meta[parallax]" id="term_meta[parallax]" value="1" <?php echo esc_attr( $term_meta['parallax'] ) ? 'checked="checked"' : ''; ?>>
					<p class="description"><?php _e( 'Check this box to have a parallax scrolling effect, this ONLY works when assigning the slider in page options. It does not work when using a slider shortcode. With this option enabled, the slider height you input will not be exact due to negative margin which is based off the overall header size. ex: 500px will show as 415px. Please adjust accordingly.', 'fusion-core' ); ?></p>
				</td>
			</tr>
			<tr class="form-field form-field-checkbox">
				<th scope="row" valign="top"><label for="term_meta[nav_arrows]"><?php _e( 'Display Navigation Arrows', 'fusion-core' ); ?></label></th>
				<td>
					<input type="hidden" name="term_meta[nav_arrows]" id="term_meta[nav_arrows]" value="0">
					<input type="checkbox" name="term_meta[nav_arrows]" id="term_meta[nav_arrows]" value="1" <?php echo esc_attr( $term_meta['nav_arrows'] ) ? 'checked="checked"' : ''; ?>>
					<p class="description"><?php _e( 'Check this box to display the navigation arrows.', 'fusion-core' ); ?></p>
				</td>
			</tr>
			<tr class="form-field fusion-double-fields">
				<th scope="row" valign="top"><label for="term_meta[nav_box_width]"><?php _e( 'Navigation Box Size', 'fusion-core' ); ?></label></th>
				<td>
					<p class="description"><?php _e( 'Enter a pixel value for width and height, ex: 40px', 'fusion-core' ); ?></p>
					<div class="fusion-field">
						<input type="text" name="term_meta[nav_box_width]" id="term_meta[nav_box_width]" value="<?php echo esc_attr( $term_meta['nav_box_width'] ) ? esc_attr( $term_meta['nav_box_width'] ) : ''; ?>">
						<label for="term_meta[nav_box_width]"><?php _e( 'Width', 'fusion-core' ); ?></label>
					</div>
					<div class="fusion-field">
						<input type="text" name="term_meta[nav_box_height]" id="term_meta[nav_box_height]" value="<?php echo esc_attr( $term_meta['nav_box_height'] ) ? esc_attr( $term_meta['nav_box_height'] ) : ''; ?>">
						<label for="term_meta[nav_box_height]"><?php _e( 'Height', 'fusion-core' ); ?></label>
					</div>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="term_meta[nav_arrow_size]"><?php _e( 'Navigation Arrow Size', 'fusion-core' ); ?></label></th>
				<td>
					<input type="text" name="term_meta[nav_arrow_size]" id="term_meta[nav_arrow_size]" value="<?php echo ( isset( $term_meta['nav_arrow_size'] ) && esc_attr( $term_meta['nav_arrow_size'] ) ) ? esc_attr( $term_meta['nav_arrow_size'] ) : ''; ?>">
					<p class="description"><?php _e( 'Enter a pixel value for the arrow size, ex: 14px', 'fusion-core' ); ?></p>
				</td>
			</tr>
			<tr class="form-field form-field-checkbox">
				<th scope="row" valign="top"><label for="term_meta[pagination_circles]"><?php _e( 'Display Pagination Circles', 'fusion-core' ); ?></label></th>
				<td>
					<input type="hidden" name="term_meta[pagination_circles]" id="term_meta[pagination_circles]" value="0">
					<input type="checkbox" name="term_meta[pagination_circles]" id="term_meta[pagination_circles]" value="1" <?php echo esc_attr( $term_meta['pagination_circles'] ) ? 'checked="checked"' : ''; ?>>
					<p class="description"><?php _e( 'Check this box to display the pagination circles.', 'fusion-core' ); ?></p>
				</td>
			</tr>
			<tr class="form-field form-field-checkbox">
				<th scope="row" valign="top"><label for="term_meta[autoplay]"><?php _e( 'Autoplay', 'fusion-core' ); ?></label></th>
				<td>
					<input type="hidden" name="term_meta[autoplay]" id="term_meta[autoplay]" value="0">
					<input type="checkbox" name="term_meta[autoplay]" id="term_meta[autoplay]" value="1" <?php echo esc_attr( $term_meta['autoplay'] ) ? 'checked="checked"' : ''; ?>>
					<p class="description"><?php _e( 'Check this box to autoplay the slides.', 'fusion-core' ); ?></p>
				</td>
			</tr>
			<tr class="form-field form-field-checkbox">
				<th scope="row" valign="top"><label for="term_meta[loop]"><?php _e( 'Slide Loop', 'fusion-core' ); ?></label></th>
				<td>
					<input type="hidden" name="term_meta[loop]" id="term_meta[loop]" value="0">
					<input type="checkbox" name="term_meta[loop]" id="term_meta[loop]" value="1" <?php echo esc_attr( $term_meta['loop'] ) ? 'checked="checked"' : ''; ?>>
					<p class="description"><?php _e( 'Check this box to have the slider loop infinitely.', 'fusion-core' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="term_meta[animation]"><?php _e( 'Animation', 'fusion-core' ); ?></label></th>
				<td>
					<select name="term_meta[animation]" id="term_meta[animation]">
					<option value="fade" <?php echo ( esc_attr( $term_meta['animation'] ) == 'fade' ) ? 'selected="selected"' : ''; ?>>Fade</option>
					<option value="slide" <?php echo ( esc_attr( $term_meta['animation'] ) == 'slide' ) ? 'selected="selected"' : ''; ?>>Slide</option>
					</select>
					<p class="description"><?php _e( 'The type of animation when slides rotate.', 'fusion-core' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="term_meta[slideshow_speed]"><?php _e( 'Slideshow Speed', 'fusion-core' ); ?></label></th>
				<td>
					<input type="text" name="term_meta[slideshow_speed]" id="term_meta[slideshow_speed]" value="<?php echo esc_attr( $term_meta['slideshow_speed'] ) ? esc_attr( $term_meta['slideshow_speed'] ) : ''; ?>">
					<p class="description"><?php _e( 'Controls the speed of the slideshow. 1000 = 1 second.', 'fusion-core' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="term_meta[animation_speed]"><?php _e( 'Animation Speed', 'fusion-core' ); ?></label></th>
				<td>
					<input type="text" name="term_meta[animation_speed]" id="term_meta[animation_speed]" value="<?php echo esc_attr( $term_meta['animation_speed'] ) ? esc_attr( $term_meta['animation_speed'] ) : ''; ?>">
					<p class="description"><?php _e( 'Controls the speed of the slide transition from slide to slide. 1000 = 1 second.', 'fusion-core' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="term_meta[typo_sensitivity]"><?php _e( 'Responsive Typography Sensitivity', 'fusion-core' ); ?></label></th>
				<td>
					<input type="text" name="term_meta[typo_sensitivity]" id="term_meta[typo_sensitivity]" value="<?php echo esc_attr( $term_meta['typo_sensitivity'] ) ? esc_attr( $term_meta['typo_sensitivity'] ) : ''; ?>">
					<p class="description"><?php _e( 'Values below 1 decrease resizing, values above 1 increase sizing. ex: .6', 'fusion-core' ); ?></p>
				</td>
			</tr>
			<tr class="form-field">
				<th scope="row" valign="top"><label for="term_meta[typo_factor]"><?php _e( 'Mininum Font Size Factor', 'fusion-core' ); ?></label></th>
				<td>
					<input type="text" name="term_meta[typo_factor]" id="term_meta[typo_factor]" value="<?php echo esc_attr( $term_meta['typo_factor'] ) ? esc_attr( $term_meta['typo_factor'] ) : ''; ?>">
					<p class="description"><?php _e( 'Minimum font factor is used to determine minimum distance between headings and body type by a multiplying value. ex: 1.5', 'fusion-core' ); ?></p>
				</td>
			</tr>
		<?php
		}

		// Save extra taxonomy fields callback function.
		function slider_save_taxonomy_custom_meta( $term_id ) {
			if ( isset( $_POST['term_meta'] ) ) {
				$t_id = $term_id;
				$term_meta = get_option( "taxonomy_$t_id" );
				$cat_keys = array_keys( $_POST['term_meta'] );
				foreach ( $cat_keys as $key ) {
					if ( isset ( $_POST['term_meta'][$key] ) ) {
						$term_meta[$key] = $_POST['term_meta'][$key];
					}
				}
				// Save the option array.
				update_option( "taxonomy_$t_id", $term_meta );
			}
		}

		// Export / Import Settings Page
		function fs_export_import_settings() {
			if( $_FILES ) {
				$this->import_sliders( $_FILES['import']['tmp_name'] );
			}
		?>
		<div class="wrap">
			<h2><?php _e( 'Export and Import Fusion Sliders', 'fusion-core' ); ?></h2>
			<form enctype="multipart/form-data" method="post" action="">
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php _e( 'Export', 'fusion-core' ); ?></th>
						<td><input type="submit" class="button button-primary" name="export_button" value="<?php _e( 'Export All Sliders', 'fusion-core' ); ?>" /></td>
					</tr>
					<tr valign="top">
						<th>
							<label for="upload"><?php _e( 'Choose a file from your computer:', 'fusion-core'); ?></label>
						</th>
						<td>
							<input type="file" id="upload" name="import" size="25" />
							<input type="hidden" name="action" value="save" />
							<input type="hidden" name="max_file_size" value="33554432" />
							<p class="submit"><input type="submit" name="upload" id="submit" class="button" value="Upload file and import"  /></p>
						</td>
					</tr>
				</table>
			</form>
		</div>
		<?php
		}

		function export_sliders() {
			if( isset($_POST['export_button']) && $_POST['export_button'] ) {
				// Load Importer API
				require_once ABSPATH . 'wp-admin/includes/export.php';

				ob_start();
				export_wp( array(
					'content' => 'slide',
				) );
				$export = ob_get_contents();
				ob_get_clean();

				$terms = get_terms( 'slide-page', array(
					'hide_empty' => 1
				) );

				foreach( $terms as $term ) {
					$term_meta = get_option( 'taxonomy_' . $term->term_id );
					$export_terms[$term->slug] = $term_meta;
				}

				$json_export_terms = json_encode($export_terms);

				$upload_dir = wp_upload_dir();
				$base_dir = trailingslashit( $upload_dir['basedir'] );
				$fs_dir = $base_dir . 'fusion_slider/';

				$loop = new WP_Query( array( 'post_type' => 'slide', 'posts_per_page' => -1, 'meta_key' => '_thumbnail_id' ) );

				while( $loop->have_posts() ) { $loop->the_post();
					$post_image_id = get_post_thumbnail_id( get_the_ID() );
					$image_path = get_attached_file($post_image_id);
					if( isset( $image_path ) && $image_path ) {
						$ext = pathinfo( $image_path, PATHINFO_EXTENSION );
						@copy( $image_path, $fs_dir . $post_image_id . '.' . $ext );
					}
				}

				wp_reset_query();

				$url = wp_nonce_url( 'edit.php?post_type=slide&page=fs_export_import' );
				if (false === ($creds = request_filesystem_credentials($url, '', false, false, null) ) ) {
					return; // stop processing here
				}

				wp_mkdir_p( $fs_dir  );

				if( WP_Filesystem( $creds ) ) {
					global $wp_filesystem;

					if ( ! $wp_filesystem->put_contents( $fs_dir . 'sliders.xml', $export, FS_CHMOD_FILE ) || ! $wp_filesystem->put_contents( $fs_dir . 'settings.json', $json_export_terms, FS_CHMOD_FILE ) ) {
						echo 'Couldn\'t export sliders, make sure wp-content/uploads is writeable.';
					} else {
						// Initialize archive object
						$zip = new ZipArchive;
						$zip->open( 'fusion_slider.zip', ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE );

						foreach( new DirectoryIterator( $fs_dir ) as $file ) {
							if( $file->isDot() ) {
								continue;
							}

							$zip->addFile( $fs_dir . $file->getFilename(), $file->getFilename() );
						}

						$zip_file = $zip->filename;

						// Zip archive will be created only after closing object
						$zip->close();

						header('X-Accel-Buffering: no');
						header("Pragma: public");
						header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
						header("Content-Length: " . filesize( $zip_file ) );
						header("Content-Type: application/octet-stream");
						header('Content-Disposition: attachment; filename="fusion_slider.zip"');
						ob_clean();
						flush();
						readfile($zip_file);

						foreach( new DirectoryIterator( $fs_dir ) as $file ) {
							if( $file->isDot() ) {
								continue;
							}

							@unlink ( $fs_dir . $file->getFilename() );
						}
					}
				}
			}
		}

		function import_sliders( $zip_file ) {
			if ( isset ( $zip_file ) && '' != $zip_file ) {
				$upload_dir = wp_upload_dir();
				$base_dir = trailingslashit( $upload_dir['basedir'] );
				$fs_dir = $base_dir . 'fusion_slider_exports/';

				@unlink ( $fs_dir . 'sliders.xml' );
				@unlink ( $fs_dir . 'settings.json' );

				$zip = new ZipArchive();
				$zip->open( $zip_file );
				$zip->extractTo( $fs_dir );
				$zip->close();

				if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
					define( 'WP_LOAD_IMPORTERS', true );
				}

				// If WP importer doesn't exist
				if ( ! class_exists( 'WP_Import' ) ) {
					$wp_import = plugin_dir_path( __FILE__ ) . 'libs/wordpress-importer.php';
					require_once $wp_import;
				}

				if ( class_exists( 'WP_Importer' ) && class_exists( 'WP_Import' ) ) {
					$importer = new WP_Import();
					$xml = $fs_dir . 'sliders.xml';
					$importer->fetch_attachments = true;
					ob_start();
					$importer->import($xml);
					ob_end_clean();

					$loop = new WP_Query( array( 'post_type' => 'slide', 'posts_per_page' => -1, 'meta_key' => '_thumbnail_id' ) );

					if ( $loop->have_posts() ) {

						while( $loop->have_posts() ) { $loop->the_post();
							$post_thumb_meta = get_post_meta( get_the_ID(), '_thumbnail_id', true );

							if ( isset( $post_thumb_meta ) && '' != $post_thumb_meta ) {
								$thumbnail_ids[$post_thumb_meta] = get_the_ID();
							}
						}
					}
					wp_reset_postdata();

					foreach( new DirectoryIterator( $fs_dir ) as $file ) {
						if( $file->isDot() || $file->getFilename() == '.DS_Store' ) {
							continue;
						}

						$image_path = pathinfo( $fs_dir . $file->getFilename() );

						if ( $image_path['extension'] != 'xml' && $image_path['extension'] != 'json' ) {
							$filename = $image_path['filename'];
							$new_image_path = $upload_dir['path'] . '/' . $image_path['basename'];
							$new_image_url = $upload_dir['url'] . '/' . $image_path['basename'];
							@copy( $fs_dir . $file->getFilename(), $new_image_path );

							// Check the type of tile. We'll use this as the 'post_mime_type'.
							$filetype = wp_check_filetype( basename( $new_image_path ), null );

							// Prepare an array of post data for the attachment.
							$attachment = array(
								'guid'		     => $new_image_url,
								'post_mime_type' => $filetype['type'],
								'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $new_image_path ) ),
								'post_content'   => '',
								'post_status'    => 'inherit'
							);

							// Insert the attachment.
							if ( isset( $thumbnail_ids[$filename] ) && '' != $thumbnail_ids[$filename] ) {
								$attach_id = wp_insert_attachment( $attachment, $new_image_path, $thumbnail_ids[$filename] );

								// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
								require_once( ABSPATH . 'wp-admin/includes/image.php' );

								// Generate the metadata for the attachment, and update the database record.
								$attach_data = wp_generate_attachment_metadata( $attach_id, $new_image_path );
								wp_update_attachment_metadata( $attach_id, $attach_data );

								set_post_thumbnail( $thumbnail_ids[$filename], $attach_id );
							}
						}
					}

					$url = wp_nonce_url( 'edit.php?post_type=slide&page=fs_export_import' );
					if (false === ($creds = request_filesystem_credentials($url, '', false, false, null) ) ) {
						return; // stop processing here
					}

					if( WP_Filesystem( $creds ) ) {
						global $wp_filesystem;

						$settings = $wp_filesystem->get_contents( $fs_dir . 'settings.json' );

						$decode = json_decode( $settings, TRUE );

						foreach( $decode as $slug => $settings ) {
							$get_term = get_term_by( 'slug', $slug, 'slide-page' );

							if( $get_term ) {
								update_option( 'taxonomy_' . $get_term->term_id, $settings );
							}
						}
					}
				}
			} else {
				echo '<p>' . __( 'No file to import.', 'fusion-core' ) . '</p>';
			}
		}

		function admin_clone_slide_button( $actions, $post ) {
			if ( current_user_can( 'manage_options' ) && 'slide' == $post->post_type ) {
				$actions['clone_slide'] = '<a href="' . $this->get_slide_clone_link( $post->ID ) . '" title="' . esc_attr(__( 'Clone this slide', 'fusion-core' ) ) . '">' . __( 'Clone', 'fusion-core' ) . '</a>';
			}
			return $actions;
		}

		function admin_clone_slider_button( $actions, $term ) {
			$args = array(
				'slider_id'                  => $term->term_id,
				'_fusion_slider_clone_nonce' => wp_create_nonce( 'clone_slider' ),
				'action'                     => 'clone_fusion_slider',
			);

			$url = add_query_arg( $args, admin_url( 'edit-tags.php' ) );
			$actions['clone_slider'] = "<a href='{$url}' title='" . __( 'Clone this slider', 'fusion-core' ) . "'>" . __( 'Clone', 'fusion-core' ) . "</a>";

			return $actions;
		}

		function admin_clone_slider_button_edit_form( $term ) {
			if ( isset( $_GET['taxonomy'] ) && $_GET['taxonomy'] == 'slide-page' && current_user_can( 'manage_options' ) ) {

				$args = array(
					'slider_id'                  => $term->term_id,
					'_fusion_slider_clone_nonce' => wp_create_nonce( 'clone_slider' ),
					'action'                     => 'clone_fusion_slider',
				);

				$url = add_query_arg( $args, admin_url( 'edit-tags.php' ) );
				$html  = '<div id="fusion-slider-clone">';
				$html .= "<a href='{$url}' title='" . __( 'Clone this slider', 'fusion-core' ) . "' class='button'>" . __( 'Clone this slider', 'fusion-core' ) . "</a>";
				$html .= '</div>';

				echo $html;
			}
		}

		function admin_clone_slide_button_after_title( $post ) {
			if ( isset( $_GET['post'] ) && current_user_can( 'manage_options' ) && 'slide' == $post->post_type ) {

				$html  = '<div id="fusion-slide-clone">';
				$html .= '<a href="' . $this->get_slide_clone_link( $_GET["post"] ) . '" class="button">' . __( "Clone this slide", "fusion-core" ) . '</a>';
				$html .= '</div>';

				echo $html;
			}
		}

		function save_as_new_slider() {
			if ( isset( $_REQUEST['_fusion_slider_clone_nonce'] ) && check_admin_referer( 'clone_slider', '_fusion_slider_clone_nonce' ) && current_user_can( 'manage_options' ) ) {

				$term_id  = $_REQUEST['slider_id'];
				$term_tax = 'slide-page';
				$original_term = get_term( $term_id, $term_tax );
				$original_term_meta = get_option( "taxonomy_$term_id" );
				$suffix = " " . __( '( Cloned )', 'fusion-core' );

				$term_details = array(
					'description' => $original_term->description,
					'slug'        => wp_unique_term_slug( $original_term->slug, $original_term ),
					'parent'      => $original_term->parent,
				);

				$new_term = wp_insert_term( $original_term->name . $suffix, $term_tax, $term_details );

				if ( ! is_wp_error( $new_term ) ) {

					// add slides (posts) to new slider (term)
					$posts = get_objects_in_term( $term_id, $term_tax );

					if ( ! is_wp_error( $posts ) ) {
						foreach( $posts as $post_id ) {
							$result = wp_set_post_terms( $post_id, $new_term['term_id'], $term_tax, true );
						}
					}

					// Clone slider (term) meta
					if ( isset( $original_term_meta ) ) {
						$t_id = $new_term['term_id'];
						update_option( "taxonomy_$t_id", $original_term_meta );
					}

					// Redirect to the all sliders screen
					wp_redirect( admin_url( 'edit-tags.php?taxonomy=slide-page&post_type=slide' ) );
				}
			}
		}

		function get_slide_clone_link( $id = 0 ) {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			if ( ! $post = get_post( $id ) ) {
				return;
			}

			$args = array(
				'_fusion_slide_clone_nonce' => wp_create_nonce( 'clone_slide' ),
				'post'                      => $post->ID,
				'action'                    => 'save_as_new_slide',
			);

			$url = add_query_arg( $args, admin_url( 'admin.php' ) );

			return $url;
		}

		function save_as_new_slide(){

			if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset( $_REQUEST['action'] ) && 'save_as_new_slide' == $_REQUEST['action'] ) ) ) {
				wp_die( __( 'No slide to clone.', 'fusion-core' ) );
			}

			if ( isset( $_REQUEST['_fusion_slide_clone_nonce'] ) && check_admin_referer( 'clone_slide', '_fusion_slide_clone_nonce' ) && current_user_can( 'manage_options' ) ) {

				// Get the post being copied
				$id = ( isset( $_GET['post'] ) ? $_GET['post'] : $_POST['post'] );
				$post = get_post( $id );

				// Copy the post and insert it
				if ( isset( $post ) && $post != null ) {
					$new_id = $this->clone_slide( $post );

					// Redirect to the all slides screen
					wp_redirect( admin_url( 'edit.php?post_type=' . $post->post_type ) );

					exit;

				} else {
					wp_die( esc_attr( __( 'Cloninig failed. Post not found. ID: ', 'fusion-core' ) ) . ' ' . htmlspecialchars( $id ) );
				}
			}
		}

		function clone_slide( $post ) {
			// Ignore revisions
			if ( 'revision' == $post->post_type ) {
				return;
			}

			$status = 'publish';
			$suffix = " " . __( '( Cloned )', 'fusion-core' );
			$post_meta_keys = get_post_custom_keys( $post->ID );

			$new_post = array(
				'menu_order'     => $post->menu_order,
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_author'    => $post->post_author,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_mime_type' => $post->post_mime_type,
				'post_parent'    => $new_post_parent = $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => $status,
				'post_title'     => $post->post_title . $suffix,
				'post_type'      => $post->post_type,
			);

			// Add new slide post
			$new_post_id = wp_insert_post( $new_post );

			// Set a proper slug
			$post_name = wp_unique_post_slug( $post->post_name, $new_post_id, $status, $post->post_type, $new_post_parent );
			$new_post = array();
			$new_post['ID'] = $new_post_id;
			$new_post['post_name'] = $post_name;

			wp_update_post( $new_post );

			// Clone post meta
			if ( ! empty ( $post_meta_keys ) ) {

				foreach ( $post_meta_keys as $meta_key ) {
					$meta_values = get_post_custom_values( $meta_key, $post->ID );

					foreach ( $meta_values as $meta_value ) {
						$meta_value = maybe_unserialize( $meta_value );
						add_post_meta( $new_post_id, $meta_key, $meta_value );
					}
				}
			}

			return $new_post_id;
		}
	}

	$fusion_slider = new Fusion_Slider();
}
