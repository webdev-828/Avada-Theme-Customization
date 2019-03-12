<?php

/**
 * Contact
 *
 * @var  array  	any existing settings
 * @return array 	existing sections + contact
 *
 */
function avada_options_section_contact( $sections ) {

	$settings = get_option( Avada::get_option_name(), array() );
	if ( ! isset( $settings['map_overlay_color'] ) ) {
		$settings['map_overlay_color'] = '#a0ce4e';
	}

	$sections['contact'] = array(
		'label'    => esc_html__( 'Contact Form', 'Avada' ),
		'id'       => 'heading_contact',
		'priority' => 22,
		'is_panel' => true,
		'icon'     => 'el-icon-envelope',
		'fields'   => array(
			'contact_form_options_subsection' => array(
				'label'       => esc_html__( 'Contact Form', 'Avada' ),
				'description' => '',
				'id'          => 'contact_form_options_subsection',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'contact_form_important_note_info' => array(
						'label'       => '',
						'description' => '<div class="avada-avadaredux-important-notice">' . __( '<strong>IMPORTANT NOTE:</strong> The options on this tab are only for the contact form that displays on the "Contact" page template.', 'Avada' ) . '</div>',
						'id'          => 'contact_form_important_note_info',
						'type'        => 'custom',
					),
					'email_address' => array(
						'label'           => esc_html__( 'Email Address', 'Avada' ),
						'description'     => esc_html__( 'Enter the email address the form should be sent to. This only works for the form on the contact page template.', 'Avada' ),
						'id'              => 'email_address',
						'default'         => '',
						'type'            => 'text',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
					),
					'contact_comment_position' => array(
						'label'           => esc_html__( 'Contact Form Comment Area Position', 'Avada' ),
						'description'     => esc_html__( 'Controls the position of the comment field with respect to the other fields.', 'Avada' ),
						'id'              => 'contact_comment_position',
						'default'         => 'below',
						'type'            => 'radio-buttonset',
						'choices'         => array(
							'above' => esc_html__( 'Above', 'Avada' ),
							'below' => esc_html__( 'Below', 'Avada' ),
						),
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
					),
					'contact_form_options_info_2' => array(
						'label'           => esc_html__( 'ReCaptcha', 'Avada' ),
						'description'     => '',
						'id'              => 'contact_form_options_info_2',
						'type'            => 'info',
					),
					'recaptcha_php_version_warning' => ( version_compare( PHP_VERSION, '5.3' ) >= 0 ) ? array() : array(
						'label'       => '',
						'description' => '<div class="avada-avadaredux-important-notice">' . esc_html__( 'ReCaptcha is not compatible with the PHP version you\'re using. Please update your server to at least PHP 5.3', 'Avada' ) . '</div>',
						'id'          => 'recaptcha_php_version_warning',
						'type'        => 'custom',
					),
					'recaptcha_public' => ( Avada::$is_updating || version_compare( PHP_VERSION, '5.3' ) >= 0 ) ? array(
						'label'           => esc_html__( 'ReCaptcha Site Key', 'Avada' ),
						'description'     => sprintf( esc_html__( 'Follow the steps in %s to get the site key.', 'Avada' ), '<a href="http://theme-fusion.com/avada-doc/pages/setting-up-contact-page/" target="_blank">' . esc_html__( 'our docs', 'Avada' ) . '</a>' ),
						'id'              => 'recaptcha_public',
						'default'         => '',
						'type'            => 'text',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
					) : array(),
					'recaptcha_private' => ( Avada::$is_updating || version_compare( PHP_VERSION, '5.3' ) >= 0 ) ? array(
						'label'           => esc_html__( 'ReCaptcha Secret Key', 'Avada' ),
						'description'     => sprintf( esc_html__( 'Follow the steps in %s to get the secret key.', 'Avada' ), '<a href="http://theme-fusion.com/avada-doc/pages/setting-up-contact-page/" target="_blank">' . esc_html__( 'our docs', 'Avada' ) . '</a>' ),
						'id'              => 'recaptcha_private',
						'default'         => '',
						'type'            => 'text',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
					) : array(),
					'recaptcha_color_scheme' => ( Avada::$is_updating || version_compare( PHP_VERSION, '5.3' ) >= 0 ) ? array(
						'label'           => esc_html__( 'ReCaptcha Color Scheme', 'Avada' ),
						'description'     => esc_html__( 'Controls the recaptcha color scheme.', 'Avada' ),
						'id'              => 'recaptcha_color_scheme',
						'default'         => 'Clean',
						'type'            => 'select',
						'choices'         => array(
							'light' => esc_html__( 'Light', 'Avada' ),
							'dark'  => esc_html__( 'Dark', 'Avada' ),
						),
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
					) : array(),
				),
			),
			'google_map_section' => array(
				'label'       => esc_html__( 'Google Map', 'Avada' ),
				'description' => '',
				'id'          => 'google_map_section',
				'default'     => esc_html__( 'Google Map', 'Avada' ),
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'google_map_disabled_note' => array(
						'label'       => '',
						'description' => '<div class="avada-avadaredux-important-notice">' . __( '<strong>IMPORTANT NOTE:</strong> Google Maps Script is disabled in Advanced > Theme Features section. Please enable it to see the options.', 'Avada' ) . '</div>',
						'id'          => 'google_map_disabled_note',
						'type'        => 'custom',
						'required'    => array(
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '0',
							),
						),
					),
					'google_map_important_note_info' => array(
						'label'       => '',
						'description' => '<div class="avada-avadaredux-important-notice">' . __( '<strong>IMPORTANT NOTE:</strong> The options on this tab are only for the google map that displays on the "Contact" page template, they do not control the google map shortcode.', 'Avada' ) . '</div>',
						'id'          => 'google_map_important_note_info',
						'type'        => 'custom',
						'required'    => array(
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
					'gmap_type' => array(
						'label'           => esc_html__( 'Google Map Type', 'Avada' ),
						'description'     => esc_html__( 'Controls the type of google map that displays.', 'Avada' ),
						'id'              => 'gmap_type',
						'default'         => 'roadmap',
						'type'            => 'select',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
						'choices'     => array(
							'roadmap'   => esc_html__( 'Roadmap', 'Avada' ),
							'satellite' => esc_html__( 'Satellite', 'Avada' ),
							'hybrid'    => esc_html__( 'Hybrid', 'Avada' ),
							'terrain'   => esc_html__( 'Terrain', 'Avada' ),
						),
						'required'    => array(
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
					'gmap_dimensions' => array(
						'label'       => esc_html__( 'Google Map Dimensions', 'Avada' ),
						'description' => esc_html__( 'Controls the width and height of the google map.', 'Avada' ),
						'id'          => 'gmap_dimensions',
						'units'		  => false,
						'default'     => array(
							'width'   => '100%',
							'height'  => '415px',
						),
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
						'type'            => 'dimensions',
						'required'    => array(
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
					'gmap_topmargin' => array(
						'label'           => esc_html__( 'Google Map Top Margin', 'Avada' ),
						'description'     => esc_html__( 'This is only applied to google maps that are not 100% width. It controls the distance to menu/page title.', 'Avada' ),
						'id'              => 'gmap_topmargin',
						'default'         => '55px',
						'type'            => 'dimension',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
						'required'    => array(
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
					'gmap_address' => array(
						'label'           => esc_html__( 'Google Map Address', 'Avada' ),
						'description'     => esc_html__( 'Add the address to the location you wish to display. Single address example: 775 New York Ave, Brooklyn, Kings, New York 11203. If the location is off, please try to use long/lat coordinates with latlng=. ex: latlng=12.381068,-1.492711. For multiple addresses, separate addresses by using the | symbol. ex: Address 1|Address 2|Address 3.', 'Avada' ),
						'id'              => 'gmap_address',
						'default'         => '775 New York Ave, Brooklyn, Kings, New York 11203',
						'type'            => 'textarea',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
						'required'    => array(
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
					'map_zoom_level' => array(
						'label'           => esc_html__( 'Map Zoom Level', 'Avada' ),
						'description'     => esc_html__( 'Controls the zoom level of the google map. Higher number is more zoomed in.', 'Avada' ),
						'id'              => 'map_zoom_level',
						'default'         => 8,
						'type'            => 'slider',
						'choices'         => array(
							'min'  => 0,
							'max'  => 22,
							'step' => 1,
						),
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
						'required'    => array(
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
					'map_pin' => array(
						'label'           => esc_html__( 'Address Pin', 'Avada' ),
						'description'     => esc_html__( 'Turn on to display the google map address pin.', 'Avada' ),
						'id'              => 'map_pin',
						'default'         => '1',
						'type'            => 'switch',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
						'required'    => array(
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
					'gmap_pin_animation' => array(
						'label'           => esc_html__( 'Address Pin Animation', 'Avada' ),
						'description'     => esc_html__( 'Turn on to enable address pin animation.', 'Avada' ),
						'id'              => 'gmap_pin_animation',
						'default'         => '1',
						'type'            => 'switch',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
						'required'    => array(
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
					'map_popup' => array(
						'label'           => esc_html__( 'Map Popup On Click', 'Avada' ),
						'description'     => esc_html__( 'Turn on to require a click to display the popup graphic with address info for the pin on the map.', 'Avada' ),
						'id'              => 'map_popup',
						'default'         => '0',
						'type'            => 'switch',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
						'required'    => array(
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
					'map_scrollwheel' => array(
						'label'           => esc_html__( 'Map Zoom With Scrollwheel', 'Avada' ),
						'description'     => esc_html__( 'Turn on to use the mouse scrollwheel to zoom the google map.', 'Avada' ),
						'id'              => 'map_scrollwheel',
						'default'         => '1',
						'type'            => 'switch',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
						'required'    => array(
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
					'map_scale' => array(
						'label'           => esc_html__( 'Map Scale', 'Avada' ),
						'description'     => esc_html__( 'Turn on to display the google map scale.', 'Avada' ),
						'id'              => 'map_scale',
						'default'         => '1',
						'type'            => 'switch',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
						'required'    => array(
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
					'map_zoomcontrol' => array(
						'label'           => esc_html__( 'Map Zoom & Pan Control Icons', 'Avada' ),
						'description'     => esc_html__( 'Turn on to display the google map zoom control icon and pan control icon.', 'Avada' ),
						'id'              => 'map_zoomcontrol',
						'default'         => '1',
						'type'            => 'switch',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
						'required'    => array(
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
				),
			),
			'google_map_design_styling_section' => array(
				'label'       => esc_html__( 'Google Map Styling', 'Avada' ),
				'description' => '',
				'id'          => 'google_map_design_styling_section',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'google_map_disabled_note_1' => array(
						'label'       => '',
						'description' => '<div class="avada-avadaredux-important-notice">' . __( '<strong>IMPORTANT NOTE:</strong> Google Maps Script is disabled in Advanced > Theme Features section. Please enable it to see the options.', 'Avada' ) . '</div>',
						'id'          => 'google_map_disabled_note_1',
						'type'        => 'custom',
						'required'    => array(
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '0',
							),
						),
					),
					'google_map_styling_important_note_info' => array(
						'label'       => '',
						'description' => '<div class="avada-avadaredux-important-notice">' . __( '<strong>IMPORTANT NOTE:</strong> The options on this tab are only for the google map that displays on the "Contact" page template, they do not control the google map shortcode.', 'Avada' ) . '</div>',
						'id'          => 'google_map_styling_important_note_info',
						'type'        => 'custom',
						'required'    => array(
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
					'map_styling' => array(
						'label'           => esc_html__( 'Select the Map Styling', 'Avada' ),
						'description'     => esc_html__( 'Controls the google map styles. Default is google style, Theme is our style, or choose Custom to select your own style options below.', 'Avada' ),
						'id'              => 'map_styling',
						'default'         => 'default',
						'type'            => 'select',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
						'choices'         => array(
							'default' => esc_html__( 'Default Styling', 'Avada' ),
							'theme'   => esc_html__( 'Theme Styling', 'Avada' ),
							'custom'  => esc_html__( 'Custom Styling', 'Avada' ),
						),
						'required'    => array(
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
					'map_overlay_color' => array(
						'label'           => esc_html__( 'Map Overlay Color', 'Avada' ),
						'description'     => esc_html__( 'Custom styling setting only. Controls the overlay color for the map.', 'Avada' ),
						'id'              => 'map_overlay_color',
						'default'         => ( isset( $settings['primary_color'] ) ) ? $settings['primary_color'] : '#a0ce4e',
						'type'            => 'color-alpha',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
						'required'    => array(
							array(
								'setting'  => 'map_styling',
								'operator' => '==',
								'value'    => 'custom',
							),
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
					'map_infobox_styling' => array(
						'label'           => esc_html__( 'Info Box Styling', 'Avada' ),
						'description'     => esc_html__( 'Custom styling setting only. Controls the styling of the info box.', 'Avada' ),
						'id'              => 'map_infobox_styling',
						'default'         => 'default',
						'type'            => 'select',
						'choices'         => array(
							'default' => esc_html__( 'Default Infobox', 'Avada' ),
							'custom'  => esc_html__( 'Custom Infobox', 'Avada' ),
						),
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
						'required'    => array(
							array(
								'setting'  => 'map_styling',
								'operator' => '==',
								'value'    => 'custom',
							),
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
					'map_infobox_content' => array(
						'label'           => esc_html__( 'Info Box Content', 'Avada' ),
						'description'     => esc_html__( 'Custom styling setting only. Type in custom info box content to replace the default address string. For multiple addresses, separate info box contents by using the | symbol. ex: InfoBox 1|InfoBox 2|InfoBox 3', 'Avada' ),
						'id'              => 'map_infobox_content',
						'default'         => '',
						'type'            => 'textarea',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
						'required'    => array(
							array(
								'setting'  => 'map_infobox_styling',
								'operator' => '==',
								'value'    => 'custom',
							),
							array(
								'setting'  => 'map_styling',
								'operator' => '==',
								'value'    => 'custom',
							),
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
					'map_infobox_bg_color' => array(
						'label'           => esc_html__( 'Info Box Background Color', 'Avada' ),
						'description'     => esc_html__( 'Custom styling setting only. Controls the info box background color.', 'Avada' ),
						'id'              => 'map_infobox_bg_color',
						'default'         => 'rgba(255,255,255,0)',
						'type'            => 'color-alpha',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
						'required'    => array(
							array(
								'setting'  => 'map_infobox_styling',
								'operator' => '==',
								'value'    => 'custom',
							),
							array(
								'setting'  => 'map_styling',
								'operator' => '==',
								'value'    => 'custom',
							),
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
					'map_infobox_text_color' => array(
						'label'           => esc_html__( 'Info Box Text Color', 'Avada' ),
						'description'     => esc_html__( 'Custom styling setting only. Controls the info box text color.', 'Avada' ),
						'id'              => 'map_infobox_text_color',
						'default'         => ( 140 < Avada_Color::get_brightness( $settings['map_overlay_color'] ) ) ? '#ffffff' : '#747474',
						'type'            => 'color',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
						'required'    => array(
							array(
								'setting'  => 'map_infobox_styling',
								'operator' => '==',
								'value'    => 'custom',
							),
							array(
								'setting'  => 'map_styling',
								'operator' => '==',
								'value'    => 'custom',
							),
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
					'map_custom_marker_icon' => array(
						'label'           => esc_html__( 'Custom Marker Icon', 'Avada' ),
						'description'     => esc_html__( 'Custom styling setting only. Use full image urls for custom marker icons or input "theme" for our custom marker. For multiple addresses, separate icons by using the | symbol or use one for all. ex: Icon 1|Icon 2|Icon 3', 'Avada' ),
						'id'              => 'map_custom_marker_icon',
						'default'         => '',
						'type'            => 'textarea',
						'active_callback' => array( 'Avada_Options_Conditionals', 'is_contact' ),
						'required'    => array(
							array(
								'setting'  => 'map_infobox_styling',
								'operator' => '==',
								'value'    => 'custom',
							),
							array(
								'setting'  => 'map_styling',
								'operator' => '==',
								'value'    => 'custom',
							),
							array(
								'setting'  => 'status_gmap',
								'operator' => '=',
								'value'    => '1',
							),
						),
					),
				),
			),
		),
	);

	return $sections;

}
