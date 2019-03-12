<?php

class Avada_AvadaRedux {

	public $key;
	public $ver;

	/**
	 * The class constructor
	 */
	public function __construct() {

		/**
		 * Initialization of the framework needs to be hooked, due to globals not being set earlier etc.
		 * Priority 2 loads he options framework directly after widgets are initialized
		 */
		add_action( 'init', array( $this, 'init_avadaredux' ), 2 );

	}

	public function init_avadaredux() {

		$this->key = Avada::get_option_name();

		if ( ! class_exists( 'AvadaRedux' ) ) {
			require_once( dirname( __FILE__ ) . '/avadaredux-framework/avadaredux-framework.php' );
		}

		require_once( dirname( __FILE__ ) . '/validation-functions.php' );
		if ( ! class_exists( 'Avada_AvadaRedux_Custom_Fields' ) ) {
			require_once( dirname( __FILE__ ) . '/class-avada-avadaredux-addons.php' );
			new Avada_AvadaRedux_Addons();
		}

		$this->ver = Avada::$version;
		$this->add_config();
		$this->parse();

		add_action( 'avadaredux/page/' . Avada::get_option_name() . '/enqueue', array( $this, 'enqueue' ) );
		add_action( 'admin_head', array( $this, 'dynamic_css' ) );

		add_action( 'admin_init', array( $this, 'remove_avadaredux_notices' ) );
		add_action( 'admin_notices', array( $this, 'remove_avadaredux_notices' ), 999 );
		add_action( 'admin_menu', array( $this, 'deprecated_adminpage_hook' ) );

		//  Update option for fusion builder and code block encoding
		add_action( 'avadaredux/options/' . Avada::get_option_name() . '/saved', array( $this, 'save_as_option' ), 10, 2 );

		// reset caches when loading avadaredux. This is a hack for the preset options.
		add_action( 'avada_avadaredux_header', array( $this, 'reset_cache' ) );
		// Make sure caches are reset when saving/resetting options
		add_action( 'avadaredux/options/' . Avada::get_option_name() . '/reset', array( $this, 'reset_cache' ) );
		add_action( 'avadaredux/options/' . Avada::get_option_name() . '/section/reset', array( $this, 'reset_cache' ) );
		add_action( 'avadaredux/options/' . Avada::get_option_name() . '/saved', array( $this, 'reset_cache' ) );

		// Save all languages
		add_action( 'avadaredux/options/' . Avada::get_option_name() . '/reset', array( $this, 'save_all_languages' ) );
		add_action( 'avadaredux/options/' . Avada::get_option_name() . '/section/reset', array( $this, 'save_all_languages' ) );
		add_action( 'avadaredux/options/' . Avada::get_option_name() . '/saved', array( $this, 'save_all_languages' ) );

		add_filter( 'avadaredux/' . Avada::get_option_name() . '/localize/reset', array( $this, 'reset_message_l10n' ) );
		add_filter( 'avadaredux/' . Avada::get_option_name() . '/localize/reset_section', array( $this, 'reset_section_message_l10n' ) );
		add_filter( 'avadaredux-import-file-description', array( $this, 'avadaredux_import_file_description_l10n' ) );
	}

	/**
	 * Triggers the cache reset
	 */
	public function reset_cache() {
		Avada()->dynamic_css->reset_all_caches();
	}

	/**
	 * Register the page and then unregister it.
	 * This allows the user to access the URL of the page,
	 * but without an actual menu for the page.
	 */
	public function deprecated_adminpage_hook() {
		add_submenu_page( 'themes.php', __( 'Avada Options have moved!', 'Avada' ), __( 'Avada Options', 'Avada' ), 'edit_theme_options', 'optionsframework', array( $this, 'deprecated_adminpage' ) );
		remove_submenu_page( 'themes.php', 'optionsframework' );
	}

	/**
	 * Creates a countdown counter and then redirects the user to the new admin page.
	 * We're using this to accomodate users that perhaps have the page bookmarked.
	 * This way they won't get an error page but we'll gracefully migrate them to the new page.
	 */
	public function deprecated_adminpage() { ?>
		<script type="text/javascript">
			var count = 6;
			var redirect = "<?php echo admin_url( 'themes.php?page=avada_options' ); ?>";

			function countDown(){
				var timer = document.getElementById("timer");
				if (count > 0){
					count--;
					timer.innerHTML = "<?php printf( esc_html__( 'Theme options have changed, redirecting you to the new page in %s seconds.', 'Avada' ), '" + count + "' ); ?>";
					setTimeout("countDown()", 1000);
				}else{
					window.location.href = redirect;
				}
			}
		</script>
		<span id="timer" style="font-size: 1.7em; padding: 100px; text-align: center; line-height: 10em;"><script type="text/javascript">countDown();</script></span>
		<?php
	}

	/**
	 * Removes avadaredux admin notices & nag messages
	 * as well as the avadaredux demo mode.
	 */
	public function remove_avadaredux_notices() {
		if ( class_exists( 'AvadaReduxFrameworkPlugin' ) ) {
			remove_filter( 'plugin_row_meta', array( AvadaReduxFrameworkPlugin::get_instance(), 'plugin_metalinks' ), null, 2 );
			remove_action( 'admin_notices', array( AvadaReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );
			remove_action( 'admin_notices', array( AvadaReduxFrameworkInstances::get_instance( Avada::get_option_name() ), '_admin_notices' ), 99 );
			// Remove the admin metabox
			remove_meta_box( 'avadaredux_dashboard_widget', 'dashboard', 'side' );
		}
	}

	/**
	 * The main parser
	 */
	public function parse() {
		/**
		 * Instantiate the Avada_Options object
		 */
		$avada_sections = new Avada_Options();
		/**
		 * Start looping through the sections from the $avada_sections object
		 */
		foreach ( $avada_sections->sections as $section ) {
			/**
			 * Create the section
			 */
			$this->create_section( $section );
			/**
			 * Start looping through the section's fields.
			 * Make sure we have fields defined before proceeding.
			 */
			if ( isset( $section['fields'] ) ) {
				foreach ( $section['fields'] as $field ) {
					if ( isset( $field['type'] ) ) {
						if ( 'sub-section' == $field['type'] ) {
							if ( ! isset( $field['id'] ) ) {
								continue;
							}
							/**
							 * This is a subsection so first we need to add the section.
							 */
							$this->create_subsection( $field );
							/**
							 * Make sure we have fields defined before proceeding.
							 * We'll need to add these fields to the subsection.
							 */
							if ( isset( $field['fields'] ) && is_array( $field['fields'] ) ) {
								foreach ( $field['fields'] as $subfield ) {
									$this->create_field( $subfield, $field['id'] );
								}
							}
						} elseif ( 'accordion' == $field['type'] ) {
							/**
							 * Make sure we have fields defined before proceeding.
							 * We'll need to add these fields to the subsection.
							 */
							if ( isset( $field['fields'] ) && is_array( $field['fields'] ) ) {
								// Open the accordion
								$accordion_start             = $field;
								$accordion_start['position'] = 'start';
								$accordion_start['id']       = $field['id'] . '_start_accordion';
								$this->create_field( $accordion_start, $section['id'] );
								// Add the fields inside the accordion
								foreach ( $field['fields'] as $subfield ) {
									$this->create_field( $subfield, $section['id'] );
								}
								// Close the accordion
								$accordion_end             = $field;
								$accordion_end['position'] = 'end';
								$accordion_end['id']       = $field['id'] . '_end_accordion';
								$this->create_field( $accordion_end, $section['id'] );
							}
						} else {
							$this->create_field( $field, $section['id'] );
						}
					}
				}
			}
		}
	}

	/**
	 * Create a section
	 */
	public function create_section( $section ) {

		if ( ! isset( $section['id'] ) ) {
			return;
		}

		if ( ! class_exists( 'AvadaRedux' ) ) {
			return;
		}

		AvadaRedux::setSection( $this->key, array(
			'title' => ( isset( $section['label'] ) ) ? $section['label'] : '',
			'id'    => $section['id'],
			'desc'  => ( isset( $section['description'] ) ) ? $section['description'] : '',
			'icon'  => ( isset( $section['icon'] ) ) ? $section['icon'] : 'el el-home',
			'class' => ( isset( $section['class'] ) ) ? $section['class'] : '',
		) );
	}

	/**
	 * Creates a subsection
	 */
	public function create_subsection( $subsection ) {

		$args = array(
			'title'      => ( isset( $subsection['label'] ) ) ? $subsection['label'] : '',
			'id'         => $subsection['id'],
			'subsection' => true,
			'desc'       => ( isset( $subsection['description'] ) ) ? $subsection['description'] : '',
		);

		if ( class_exists( 'AvadaRedux' ) ) {
			AvadaRedux::setSection( $this->key, $args );
		}

	}

	/**
	 * Convert a field
	 */
	public function create_field( $field, $section_id = null ) {

		$args = array();
		$args['section_id']  = $section_id;
		$args['title']       = ( isset( $field['label'] ) ) ? $field['label'] : '';
		$args['subtitle']    = ( isset( $field['description'] ) ) ? $field['description'] : '';
		$args['description'] = ( isset( $field['help'] ) ) ? $field['help'] : '';
		$args['class']       = ( isset( $field['class'] ) ) ? $field['class'] . ' avada_options' : 'avada_options';
		$args['options']     = ( isset( $field['choices'] ) ) ? $field['choices'] : array();
		$args['required']    = array();

		if ( isset( $field['required'] ) && is_array( $field['required'] ) && ! empty( $field['required'] ) ) {
			foreach ( $field['required'] as $requirement ) {
				$requirement['operator'] = ( '==' == $requirement['operator'] ) ? '=' : $requirement['operator'];
				$args['required'][] = array(
					$requirement['setting'],
					$requirement['operator'],
					$requirement['value'],
				);
			}
		} elseif ( isset( $args['required'] ) ) {
			unset( $args['required'] );
		}

		// This will allow us to have an 'options_mode' setting.
		// We can have 'simple', 'advanced' etc there, and options will be shown depending on our selection.
		if ( isset( $field['option_mode'] ) ) {
			if ( ! isset( $args['required'] ) ) {
				$args['required'] = array();
			}
			$args['required'][] = array( 'options_mode', '=', $field['option_mode'] );
		}

		if ( ! isset( $field['type'] ) ) {
			return;
		}

		$font_size_dimension_fields = array(
			'meta_font_size',
			'es_title_font_size',
			'es_caption_font_size',
			'ec_sidew_font_size',
			'image_rollover_icon_size',
			'pagination_font_size',
			'form_input_height',
			'copyright_font_size',
			'tagline_font_size',
			'header_sticky_nav_font_size',
			'page_title_font_size',
			'page_title_subheader_font_size',
			'breadcrumbs_font_size',
			'content_box_title_size',
			'content_box_icon_size',
			'counter_box_title_size',
			'counter_box_icon_size',
			'counter_box_body_size',
			'social_links_font_size',
			'sidew_font_size',
			'slider_arrow_size',
			'slidingbar_font_size',
			'header_social_links_font_size',
			'footer_social_links_font_size',
			'sharing_social_links_font_size',
			'post_titles_font_size',
			'post_titles_font_lh',
			'post_titles_extras_font_size',
			'woo_icon_font_size',
		);

		switch ( $field['type'] ) {
			case 'color':
				if ( ! isset( $field['transparent'] ) ) {
					$args['transparent'] = false;
				}
				$args['validate_callback'] = 'avada_avadaredux_validate_color_hex';
				break;
			case 'code':
				$args['type']    = 'ace_editor';
				$args['mode']    = ( isset( $args['options'] ) && isset( $args['options']['language'] ) ) ? $args['options']['language'] : 'css';
				$args['theme']   = ( isset( $args['choices'] ) && isset( $args['choices']['theme'] ) ) ? $args['choices']['theme'] : 'chrome';
				$args['options']['minLines'] = ( ! isset( $args['options']['minLines'] ) ) ? 18 : $args['options']['minLines'];
				$args['options']['maxLines'] = ( ! isset( $args['options']['maxLines'] ) ) ? 30 : $args['options']['maxLines'];
				if ( 'custom_css' == $field['id'] ) {
					$args['full_width'] = true;
				}
				break;
			case 'radio-buttonset':
				$args['type'] = 'button_set';
				break;
			case 'dimension':
				$args['type']     = 'text';
				$args['class']   .= ' dimension';
				$args['options']  = '';
				$args['validate_callback'] = 'avada_avadaredux_validate_dimension';

				if ( in_array( $field['id'], $font_size_dimension_fields ) ) {
					// $args['subtitle'] = sprintf( esc_html__( '%s Enter value including a CSS unit, ex: %s. Valid CSS units for this field are px, em, rem.', 'Avada' ), $args['subtitle'], $field['default'] );
					$args['validate_callback'] = 'avada_avadaredux_validate_font_size';
					$args['subtitle'] = sprintf( esc_html__( '%s Enter value including CSS unit (px, em, rem), ex: %s.', 'Avada' ), $args['subtitle'], $field['default'] );
				} else {
					$args['subtitle'] = sprintf( esc_html__( '%s Enter value including any valid CSS unit, ex: %s.', 'Avada' ), $args['subtitle'], $field['default'] );
				}
				break;
			case 'dimensions':
				if ( 'lightbox_video_dimensions' == $field['id'] ) {
					$args['subtitle'] = sprintf( esc_html__( '%s In pixels, ex: %s.', 'Avada' ), $args['subtitle'], implode( ', ', $field['default'] ) );
				} else {
					$args['subtitle'] = sprintf( esc_html__( '%s Enter values including any valid CSS unit, ex: %s.', 'Avada' ), $args['subtitle'], implode( ', ', $field['default'] ) );
				}
				$args['validate_callback'] = 'avada_avadaredux_validate_dimensions';
				break;
			case 'spacing':
				$args['top']    = ( isset( $field['choices'] ) && isset( $field['choices']['top'] ) ) ? true : false;
				$args['bottom'] = ( isset( $field['choices'] ) && isset( $field['choices']['bottom'] ) ) ? true : false;
				$args['left']   = ( isset( $field['choices'] ) && isset( $field['choices']['left'] ) ) ? true : false;
				$args['right']  = ( isset( $field['choices'] ) && isset( $field['choices']['right'] ) ) ? true : false;
				$args['validate_callback'] = 'avada_avadaredux_validate_dimensions';
				$args['subtitle'] = sprintf( esc_html__( '%s Enter values including any valid CSS unit, ex: %s.', 'Avada' ), $args['subtitle'], implode( ', ', $field['default'] ) );
				break;
			case 'number':
				$args['type'] = 'spinner';
				if ( isset( $field['choices'] ) && isset( $field['choices']['min'] ) ) {
					$args['min'] = $field['choices']['min'];
				}
				if ( isset( $field['choices'] ) && isset( $field['choices']['max'] ) ) {
					$args['max'] = $field['choices']['max'];
				}
				if ( isset( $field['choices'] ) && isset( $field['choices']['step'] ) ) {
					$args['step'] = $field['choices']['step'];
				}
				break;
			case 'select':
				$args['width'] = 'width:100%;';
				$args['select2'] = array(
					'minimumResultsForSearch' => '-1',
					'allowClear'              => false,
				);
				break;
			case 'slider':
				$not_in_pixels = array(
					'carousel_speed',
					'counter_box_speed',
					'testimonials_speed',
					'slidingbar_widgets_columns',
					'footer_widgets_columns',
					'blog_grid_columns',
					'excerpt_length_blog',
					'excerpt_length_portfolio',
					'posts_slideshow_number',
					'slideshow_speed',
					'tfes_interval',
					'tfes_speed',
					'lightbox_slideshow_speed',
					'lightbox_opacity',
					'map_zoom_level',
					'search_results_per_page',
					'number_related_posts',
					'related_posts_columns',
					'related_posts_speed',
					'related_posts_swipe_items',
					'pw_jpeg_quality',
					'portfolio_items',
					'woo_items',
					'woocommerce_shop_page_columns',
					'woocommerce_related_columns',
					'woocommerce_archive_page_columns',
					'typography_sensitivity',
					'typography_factor',
				);

				if ( ! in_array( $field['id'], $not_in_pixels ) ) {
					$args['subtitle'] = $args['subtitle'] . ' ' . esc_html__( 'In pixels.', 'Avada' );
				}

				if ( isset( $field['choices'] ) && isset( $field['choices']['min'] ) ) {
					$args['min'] = $field['choices']['min'];
				}
				if ( isset( $field['choices'] ) && isset( $field['choices']['max'] ) ) {
					$args['max'] = $field['choices']['max'];
				}
				if ( isset( $field['choices'] ) && isset( $field['choices']['step'] ) ) {
					$args['step'] = $field['choices']['step'];
				}
				if ( isset( $field['choices']['step'] ) && 1 > $field['choices']['step'] ) {
					$args['resolution'] = 0.1;
					if ( .1 > $field['choices']['step'] ) {
						$args['resolution'] = 0.01;
					} elseif ( .01 > $field['choices']['step'] ) {
						$args['resolution'] = 0.001;
					}
				}
				break;
			case 'switch':
			case 'toggle':
				$args['type'] = 'switch';
				if ( isset( $field['choices'] ) && isset( $field['choices']['on'] ) ) {
					$args['on'] = $field['choices']['on'];
				}
				if ( isset( $field['choices'] ) && isset( $field['choices']['off'] ) ) {
					$args['off'] = $field['choices']['off'];
				}
				break;
			case 'color-alpha':
				$args['type'] = 'color_alpha';
				$args['transparent'] = false;
				$args['validate_callback'] = 'avada_avadaredux_validate_color_rgba';
				break;
			case 'preset':
				$args['type'] = 'image_select';
				$args['presets'] = true;
				$args['options'] = array();
				foreach ( $field['choices'] as $choice => $choice_args ) {
					if ( is_array( $choice_args ) ) {
						$args['options'][ $choice ] = array(
							'alt'     => $choice_args['label'],
							'img'     => $choice_args['image'],
							'presets' => $choice_args['settings'],
						);
					}
				}
				break;
			case 'radio-image':
				$args['type'] = 'image_select';
				$args['options'] = array();
				foreach ( $field['choices'] as $id => $url ) {
					$args['options'][ $id ] = array(
						'alt' => $id,
						'img' => $url,
					);
				}
				if ( 'header_layout' == $field['id'] ) {
					$args['full_width'] = true;
				}
				break;
			case 'upload':
			case 'media':
				$args['type'] = 'media';
				if ( isset( $field['default'] ) && ! is_array( $field['default'] ) ) {
					$args['default'] = ( '' == $field['default'] ) ? array() : $args['default'] = array( 'url' => $field['default'] );
				}
				break;
			case 'radio':
				$args['options'] = array();
				foreach ( $field['choices'] as $choice => $label ) {
					if ( is_array( $label ) ) {
						$args['options'][ $choice ] = '<span style="font-weight: bold; font-size: 1.1em; line-height: 2.2em;">' . $label[0] . '</span><p>' . $label[1] . '<p>';
					} else {
						$args['options'][ $choice ] = $label;
					}
				}
				break;
			case 'multicheck':
				$args['type'] = 'checkbox';
				break;
			case 'typography':
				$args['default'] = array();
				if ( isset( $field['default'] ) ) {
					if ( isset( $field['default']['font-weight'] ) ) {
						$args['default']['font-weight'] = $field['default']['font-weight'];
					}
					if ( isset( $field['default']['font-size'] ) ) {
						$args['default']['font-size'] = $field['default']['font-size'];
					}
					if ( isset( $field['default']['font-family'] ) ) {
						$args['default']['font-family'] = $field['default']['font-family'];
						$args['default']['font-backup'] = true;
						$args['default']['google']      = true;
					}
					if ( isset( $field['default']['line-height'] ) ) {
						$args['default']['line-height'] = $field['default']['line-height'];
					}
					if ( isset( $field['default']['word-spacing'] ) ) {
						$args['default']['word-spacing'] = $field['default']['word-spacing'];
					}
					if ( isset( $field['default']['letter-spacing'] ) ) {
						$args['default']['letter-spacing'] = $field['default']['letter-spacing'];
					}
					if ( isset( $field['default']['color'] ) ) {
						$args['default']['color'] = $field['default']['color'];
					}
					if ( isset( $field['default']['text-align'] ) ) {
						$args['default']['text-align'] = $field['default']['text-align'];
					}
					if ( isset( $field['default']['text-transform'] ) ) {
						$args['default']['text-transform'] = $field['default']['text-transform'];
					}
					if ( isset( $field['default']['margin-top'] ) ) {
						$args['default']['margin-top'] = $field['default']['margin-top'];
					}
					if ( isset( $field['default']['margin-bottom'] ) ) {
						$args['default']['margin-bottom'] = $field['default']['margin-bottom'];
					}
				}
				$args['fonts'] = Avada_Data::standard_fonts();
				$args['font-backup']    = true;
				$args['font-style']     = ( isset( $args['default']['font-style'] ) || ( isset( $field['choices']['font-style'] ) && $field['choices']['font-style'] ) ) ? true : false;
				$args['font-weight']    = ( isset( $args['default']['font-weight'] ) || ( isset( $field['choices']['font-weight'] ) && $field['choices']['font-weight'] ) ) ? true : false;
				$args['font-size']      = ( isset( $args['default']['font-size'] ) || ( isset( $field['choices']['font-size'] ) && $field['choices']['font-size'] ) ) ? true : false;
				$args['font-family']    = ( isset( $args['default']['font-family'] ) || ( isset( $field['choices']['font-family'] ) && $field['choices']['font-family'] ) ) ? true : false;
				$args['subsets']        = ( isset( $args['default']['font-family'] ) || ( isset( $field['choices']['font-family'] ) && $field['choices']['font-family'] ) ) ? true : false;
				$args['line-height']    = ( isset( $args['default']['line-height'] ) || ( isset( $field['choices']['line-height'] ) && $field['choices']['line-height'] ) ) ? true : false;
				$args['word-spacing']   = ( isset( $args['default']['word-spacing'] ) || ( isset( $field['choices']['word-spacing'] ) && $field['choices']['word-spacing'] ) ) ? true : false;
				$args['letter-spacing'] = ( isset( $args['default']['word-spacing'] ) || ( isset( $field['choices']['letter-spacing'] ) && $field['choices']['letter-spacing'] ) ) ? true : false;
				$args['text-align']     = ( isset( $args['default']['text-align'] ) || ( isset( $field['choices']['text-align'] ) && $field['choices']['text-align'] ) ) ? true : false;
				$args['text-transform'] = ( isset( $args['default']['text-transform'] ) || ( isset( $field['choices']['text-transform'] ) && $field['choices']['text-transform'] ) ) ? true : false;
				$args['color']          = ( isset( $args['default']['color'] ) || ( isset( $field['choices']['color'] ) && $field['choices']['color'] ) ) ? true : false;
				$args['margin-top']     = ( isset( $args['default']['margin-top'] ) || ( isset( $field['choices']['margin-top'] ) && $field['choices']['margin-top'] ) ) ? true : false;
				$args['margin-bottom']  = ( isset( $args['default']['margin-bottom'] ) || ( isset( $field['choices']['margin-bottom'] ) && $field['choices']['margin-bottom'] ) ) ? true : false;

				$args['select2'] = array( 'allowClear' => false );
				$args['validate_callback'] = 'avada_avadaredux_validate_typography';

				break;
			case 'repeater':
				$args['fields']       = array();
				$args['group_values'] = true;
				$args['sortable']     = true;
				$i = 0;
				foreach ( $field['fields'] as $repeater_field_id => $repeater_field_args ) {
					$repeater_field_args['label'] = ( isset( $repeater_field_args['label'] ) ) ? $repeater_field_args['label'] : '';
					$args['fields'][ $i ] = array(
						'id'          => $repeater_field_id,
						'type'        => isset( $repeater_field_args['type'] ) ? $repeater_field_args['type'] : 'text',
						'title'       => $repeater_field_args['label'],
						'placeholder' => ( isset( $repeater_field_args['default'] ) ) ? $repeater_field_args['default'] : $repeater_field_args['label'],
					);
					if ( isset( $repeater_field_args['choices'] ) ) {
						$args['fields'][ $i ]['options'] = $repeater_field_args['choices'];
					}
					if ( isset( $repeater_field_args['type'] ) && 'select' == $repeater_field_args['type'] ) {
						$args['fields'][ $i ]['width'] = 'width:100%;';
						$args['fields'][ $i ]['select2'] = array(
							'minimumResultsForSearch' => '-1',
						);
					}
					if ( isset( $repeater_field_args['type'] ) && 'color' == $repeater_field_args['type'] ) {
						$args['fields'][ $i ]['transparent'] = false;
					}
					if ( isset( $repeater_field_args['type'] ) && 'upload' == $repeater_field_args['type'] ) {
						$args['fields'][ $i ]['type'] = 'media';
						if ( isset( $repeater_field_args['mode'] ) ) {
							$args['fields'][ $i ]['mode'] = $repeater_field_args['mode'];
						}
						if ( isset( $repeater_field_args['preview'] ) ) {
							$args['fields'][ $i ]['preview'] = $repeater_field_args['preview'];
						}
					}

					$i++;
				}
				unset( $args['options'] );
				if ( 'custom_fonts' == $field['id'] ) {
					$args['validate_callback'] = 'avada_avadaredux_validate_custom_fonts';
				}
				break;
			case 'accordion':
				$args['type']     = 'accordion';
				$args['title']    = $field['label'];
				$args['subtitle'] = ( isset( $field['description'] ) ) ? $field['description'] : '';
				unset( $field['fields'] );
				unset( $field['label'] );
				unset( $field['default'] );
				unset( $field['options'] );
				break;
			case 'custom':
				$args['type']        = 'raw';
				$args['full_width']  = true;
				if ( isset( $field['style'] ) && $field['style'] == 'heading' ) {
					$args['content'] = '<div class="avadaredux-field-info"><p class="avadaredux-info-desc" style="font-size:13px;"><b>' .$field['description'] . '</b></p></div>';
					$args['class'] .= ' custom-heading';
				} else {
					$args['content'] = $field['description'];
					$args['class'] .= ' custom-info';
				}
				$args['description'] = '';
				$args['subtitle']    = '';
				$args['raw_html']    = true;
				break;
		}

		// Add validation to the email field
		if ( isset( $field['id'] ) && 'email_address' == $field['id'] ) {
			$args['validate'] = 'email';
		}

		$args = wp_parse_args( $args, $field );

		if ( class_exists( 'AvadaRedux' ) ) {
			AvadaRedux::setField( $this->key, $args );
		}

	}

	public function enqueue() {
		$vars = array(
			'option_name' => Avada::get_option_name(),
			'theme_skin' => esc_html__( 'Theme Skin', 'Avada' ),
			'color_scheme' => esc_html__( 'Color Scheme', 'Avada' ),
		);
		wp_register_script( 'avada-avadaredux-custom-js', trailingslashit( get_template_directory_uri() ) . 'includes/avadaredux/assets/avada-avadaredux.js', array( 'jquery' ), time(), true );
		wp_localize_script( 'avada-avadaredux-custom-js', 'avada_avadaredux_vars', $vars );
		wp_enqueue_script( 'avada-avadaredux-custom-js' );
	}

	public function dynamic_css() {
		$screen = get_current_screen();

		// Early exit if we're not in the avadaredux panel.
		if ( is_null( $screen ) || 'appearance_page_avada_options' != $screen->id ) {
			return;
		}

		// Get the user's admin colors
		$color_scheme = get_user_option( 'admin_color' );

		// If no theme is active set it to 'fresh'
		if ( empty( $color_scheme ) ) {
			$color_scheme = 'fresh';
		}

		$main_colors = $this->get_main_colors( $color_scheme );
		$text_colors = $this->get_text_colors( $color_scheme );

		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		$styles = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/assets/style.css' );

		if ( ! $styles || empty( $styles ) ) {
			ob_start();
			include( dirname( __FILE__ ) . '/assets/style.css' );
			$styles = ob_get_clean();
		}

		if ( $styles && ! empty( $styles ) ) {

			$themefusion_logo = trailingslashit( get_template_directory_uri() ) . 'includes/avadaredux/assets/themefusion_logo_white.png';

			$styles = str_replace( '$color_back_1', $main_colors['color_back_1'], $styles );
			$styles = str_replace( '$color_back_2', $main_colors['color_back_2'], $styles );
			$styles = str_replace( '$color_back_top_level_hover', $main_colors['color_back_top_level_hover'], $styles );
			$styles = str_replace( '$color_back_top_level_active', $main_colors['color_back_top_level_active'], $styles );
			$styles = str_replace( '$color_accent_1', $main_colors['color_accent_1'], $styles );
			$styles = str_replace( '$color_accent_2', $main_colors['color_accent_2'], $styles );

			$styles = str_replace( '$color_text_menu_top_level_hover', $text_colors['menu_top_level_hover'], $styles );
			$styles = str_replace( '$color_text_menu_sub_level_hover', $text_colors['menu_sub_level_hover'], $styles );
			$styles = str_replace( '$color_text_menu_top_level_active', $text_colors['menu_top_level_active'], $styles );
			$styles = str_replace( '$color_text_menu_sub_level_active', $text_colors['menu_sub_level_active'], $styles );
			$styles = str_replace( '$color_text_menu_top_level', $text_colors['menu_top_level'], $styles );
			$styles = str_replace( '$color_text_menu_sub_level', $text_colors['menu_sub_level'], $styles );

			$styles = str_replace( '$themefusion_logo', $themefusion_logo, $styles );

			// Add custom fonts
			$styles .= avada_custom_fonts_font_faces();

			echo '<style id="avada-avadaredux-custom-styles" type="text/css">' . $styles . '</style>';

		}
	}

	public function get_main_colors( $scheme ) {
		$main_colors = array(
			'color_back_1' => '',
			'color_back_2' => '',
			'color_back_top_level_hover' => '',
			'color_back_top_level_active' => '',
			'color_accent_1' => '',
			'color_accent_2' => '',
		);

		// Get the active admin theme
		global $_wp_admin_css_colors;

		if ( ! isset( $_wp_admin_css_colors[ $scheme ] ) ) {
			$scheme = 'fresh';
		}

		$colors = (array) $_wp_admin_css_colors[ $scheme ];

		if ( isset( $colors['colors'] ) ) {
			$main_colors['color_accent_1'] = ( isset( $colors['colors'][2] ) ) ? $colors['colors'][2] : $main_colors['color_accent_1'];
			$main_colors['color_accent_2'] = ( isset( $colors['colors'][3] ) ) ? $colors['colors'][3] : $main_colors['color_accent_2'];
		}

		switch ( $scheme ) {
			case 'fresh':
				$main_colors['color_back_1']                = '#32373c';
				$main_colors['color_back_2']                = '#23282d';
				$main_colors['color_back_top_level_hover']  = '#191e23';
				$main_colors['color_back_top_level_active'] = '#0073aa';
				break;
			case 'light':
				$main_colors['color_back_1']                = '#fff';
				$main_colors['color_back_2']                = '#e5e5e5';
				$main_colors['color_back_top_level_hover']  = '#888';
				$main_colors['color_back_top_level_active'] = '#888';
				break;
			case 'blue':
				$main_colors['color_back_1']                = '#4796b3';
				$main_colors['color_back_2']                = '#52accc';
				$main_colors['color_back_top_level_hover']  = '#096484';
				$main_colors['color_back_top_level_active'] = '#096484';
				$main_colors['color_accent_1']              = '#e1a948';
				break;
			case 'coffee':
				$main_colors['color_back_1']                = '#46403c';
				$main_colors['color_back_2']                = '#59524c';
				$main_colors['color_back_top_level_hover']  = '#c7a589';
				$main_colors['color_back_top_level_active'] = '#c7a589';
				break;
			case 'ectoplasm':
				$main_colors['color_back_1']                = '#413256';
				$main_colors['color_back_2']                = '#523f6d';
				$main_colors['color_back_top_level_hover']  = '#a3b745';
				$main_colors['color_back_top_level_active'] = '#a3b745';
				break;
			case 'midnight':
				$main_colors['color_back_1']                = '#26292c';
				$main_colors['color_back_2']                = '#363b3f';
				$main_colors['color_back_top_level_hover']  = '#e14d43';
				$main_colors['color_back_top_level_active'] = '#e14d43';
				break;
			case 'ocean':
				$main_colors['color_back_1']                = '#627c83';
				$main_colors['color_back_2']                = '#738e96';
				$main_colors['color_back_top_level_hover']  = '#9ebaa0';
				$main_colors['color_back_top_level_active'] = '#9ebaa0';
				break;
			case 'sunrise':
				$main_colors['color_back_1']                = '#be3631';
				$main_colors['color_back_2']                = '#cf4944';
				$main_colors['color_back_top_level_hover']  = '#dd823b';
				$main_colors['color_back_top_level_active'] = '#dd823b';
				break;
			default:
				if ( isset( $colors['colors'] ) ) {
					$main_colors['color_back_1']   = ( isset( $colors['colors'][0] ) ) ? $colors['colors'][0] : $main_colors['color_back_1'];
					$main_colors['color_back_2']   = ( isset( $colors['colors'][1] ) ) ? $colors['colors'][1] : $main_colors['color_back_2'];
					$main_colors['color_back_top_level_hover'] = ( isset( $colors['colors'][2] ) ) ? $colors['colors'][2] : $main_colors['color_accent_1'];
					$main_colors['color_back_top_level_active'] = ( isset( $colors['colors'][2] ) ) ? $colors['colors'][2] : $main_colors['color_accent_1'];
				}
		}
		return $main_colors;
	}

	public function get_text_colors( $scheme ) {
		$text_colors = array();

		switch ( $scheme ) {
			case 'fresh':
				$text_colors['menu_top_level']        = '#eee';
				$text_colors['menu_sub_level']        = 'rgba(240, 245, 250, 0.7)';
				$text_colors['menu_top_level_hover']  = '#00b9eb';
				$text_colors['menu_sub_level_hover']  = '#00b9eb';
				$text_colors['menu_top_level_active'] = '#fff';
				$text_colors['menu_sub_level_active'] = '#fff';
				break;
			case 'light':
				$text_colors['menu_top_level']        = '#333';
				$text_colors['menu_sub_level']        = '#686868';
				$text_colors['menu_top_level_hover']  = '#fff';
				$text_colors['menu_sub_level_hover']  = '#00b9eb';
				$text_colors['menu_top_level_active'] = '#fff';
				$text_colors['menu_sub_level_active'] = '#333';
				break;
			case 'blue':
				$text_colors['menu_top_level']        = '#fff';
				$text_colors['menu_sub_level']        = '#e2ecf1';
				$text_colors['menu_top_level_hover']  = '#fff';
				$text_colors['menu_sub_level_hover']  = '#fff';
				$text_colors['menu_top_level_active'] = '#fff';
				$text_colors['menu_sub_level_active'] = '#fff';
				break;
			case 'coffee':
				$text_colors['menu_top_level']        = '#fff';
				$text_colors['menu_sub_level']        = '#cdcbc9';
				$text_colors['menu_top_level_hover']  = '#fff';
				$text_colors['menu_sub_level_hover']  = '#c7a589';
				$text_colors['menu_top_level_active'] = '#fff';
				$text_colors['menu_sub_level_active'] = '#fff';
				break;
			case 'ectoplasm':
				$text_colors['menu_top_level']        = '#fff';
				$text_colors['menu_sub_level']        = '#cbc5d3';
				$text_colors['menu_top_level_hover']  = '#fff';
				$text_colors['menu_sub_level_hover']  = '#a3b745';
				$text_colors['menu_top_level_active'] = '#fff';
				$text_colors['menu_sub_level_active'] = '#fff';
				break;
			case 'midnight':
				$text_colors['menu_top_level']        = '#fff';
				$text_colors['menu_sub_level']        = '#c3c4c5';
				$text_colors['menu_top_level_hover']  = '#fff';
				$text_colors['menu_sub_level_hover']  = '#e14d43';
				$text_colors['menu_top_level_active'] = '#fff';
				$text_colors['menu_sub_level_active'] = '#fff';
				break;
			case 'ocean':
				$text_colors['menu_top_level']        = '#fff';
				$text_colors['menu_sub_level']        = '#d5dde0';
				$text_colors['menu_top_level_hover']  = '#fff';
				$text_colors['menu_sub_level_hover']  = '#9ebaa0';
				$text_colors['menu_top_level_active'] = '#fff';
				$text_colors['menu_sub_level_active'] = '#fff';
				break;
			case 'sunrise':
				$text_colors['menu_top_level']        = '#fff';
				$text_colors['menu_sub_level']        = '#f1c8c7';
				$text_colors['menu_top_level_hover']  = '#fff';
				$text_colors['menu_sub_level_hover']  = '#f7e3d3';
				$text_colors['menu_top_level_active'] = '#fff';
				$text_colors['menu_sub_level_active'] = '#fff';
				break;
			default:
				$text_colors['menu_top_level']        = '#eee';
				$text_colors['menu_sub_level']        = 'rgba(240, 245, 250, 0.7)';
				$text_colors['menu_top_level_hover']  = '#00b9eb';
				$text_colors['menu_sub_level_hover']  = '#00b9eb';
				$text_colors['menu_top_level_active'] = '#fff';
				$text_colors['menu_sub_level_active'] = '#fff';
		}

		return $text_colors;
	}

	/**
	 * Create the AvadaRedux Config.
	 */
	public function add_config() {

		$args = array(
			'opt_name'             => $this->key,
			'display_name'         => 'Avada',
			'display_version'      => $this->ver,
			'allow_sub_menu'       => true,
			'menu_title'           => __( 'Theme Options', 'Avada' ),
			'page_title'           => __( 'Theme Options', 'Avada' ),
			'async_typography'     => true,
			'admin_bar'            => false,
			'admin_bar_icon'       => 'dashicons-portfolio',
			'admin_bar_priority'   => 50,
			'global_variable'      => 'avada_avadaredux_options',
			'update_notice'        => true,
			'page_parent'          => 'themes.php',
			'page_slug'            => 'avada_options',
			'menu_type'            => 'submenu',
			'page_permissions'     => 'manage_options',
			'dev_mode'             => false,
			'customizer'           => false,
			'default_show'         => false,
			'templates_path'       => dirname( __FILE__ ) . '/panel_templates/',
			'show_options_object'  => false,
			'forced_dev_mode_off'  => true,
		);
		if ( class_exists( 'AvadaRedux' ) ) {
			AvadaRedux::setArgs( $this->key, $args );
		}

	}

	/**
	* Save buider and code block encoding as option
	*
	* @since 4.0
	* @return void
	*/
	public function save_as_option( $data, $changed_values ) {
		update_option( 'avada_disable_builder', $data['disable_builder'] );
		update_option( 'avada_disable_encoding', $data['disable_code_block_encoding'] );
	}

	/**
	 * When in Polylang on WPML we're using "all" languages,
	 * saved options should be copied to ALL languages.
	 *
	 * @access public
	 * @since 4.0.2
	 */
	public function save_all_languages() {

		// Get the current language.
		$is_all = Avada::get_language_is_all();

		// If not "all", then early exit.
		if ( ! $is_all ) {
			return;
		}

		// Get the options.
		$option_name          = Avada::get_option_name();
		$original_option_name = Avada::get_original_option_name();
		$options              = get_option( $option_name );

		// Get available languages.
		$all_languages = Avada_Multilingual::get_available_languages();

		// Get default language
		$default_language = Avada_Multilingual::get_default_language();

		if ( 'en' !== $default_language ) {
			update_option( $original_option_name . '_' . $default_language, $options );
			update_option( $original_option_name, $options );
		}

		foreach ( $all_languages as $language ) {

			// Skip English.
			if ( '' === $language || 'en' === $language ) {
				continue;
			}

			// Skip the main language if something other than English.
			// We've already handled that above.
			if ( 'en' !== $default_language && $default_language === $language ) {
				continue;
			}

			// Copy options to the new language.
			update_option( $original_option_name . '_' . $language, $options );

		}

	}

	/**
	 * Modify the AvadaRedux reset message (global)
	 *
	 * @return string
	 */
	public function reset_message_l10n() {
		return esc_html__( 'Are you sure? This will reset all saved theme options to their default values.', 'Avada' );
	}

	/**
	 * Modify the AvadaRedux reset message (section)
	 *
	 * @return string
	 */
	public function reset_section_message_l10n() {
		return esc_html__( 'Are you sure? This will reset all saved options to their default values for this section.', 'Avada' );
	}

	/**
	 * Modify the import file description
	 *
	 * @return string
	 */
	public function avadaredux_import_file_description_l10n() {
		return esc_html__( 'Copy the contents of the json file and paste it below. Then click "Import" to restore your setings.', 'Avada' );
	}

}
