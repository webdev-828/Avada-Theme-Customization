<?php

/**
 * Footer settings
 *
 * @var  array  	any existing settings
 * @return array 	existing sections + footer
 *
 */
function avada_options_section_events_calendar( $sections ) {

	if ( ! Avada::$is_updating && ! class_exists( 'Tribe__Events__Main' ) ) {
		return $sections;
	}

	$sections['ec'] = array(
		'label'    => esc_html__( 'Events Calendar', 'Avada' ),
		'id'       => 'heading_events_calendar',
		'is_panel' => true,
		'priority' => 30,
		'icon'     => 'el-icon-calendar',
		'fields'   => array(
			'ec_general_tab' => array(
				'label'       => esc_html__( 'General Events Calendar', 'Avada' ),
				'description' => '',
				'id'          => 'ec_general_tab',
				'default'     => '',
				'icon'        => true,
				'type'        => 'sub-section',
				'fields'      => array(
					'primary_overlay_text_color' => array(
						'label'       => esc_html__( 'Events Primary Color Overlay Text Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of text when primary color is the background.', 'Avada' ),
						'id'          => 'primary_overlay_text_color',
						'default'     => '#ffffff',
						'type'        => 'color',
					),
					'ec_bar_bg_color' => array(
						'label'       => esc_html__( 'Events Filter Bar Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color for the events calendar filter bar.', 'Avada' ),
						'id'          => 'ec_bar_bg_color',
						'default'     => '#efeded',
						'type'        => 'color-alpha',
					),
					'ec_bar_text_color' => array(
						'label'       => esc_html__( 'Event Filter Bar Text Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the event filter bar text.', 'Avada'),
						'id'          => 'ec_bar_text_color',
						'default'     => '#747474',
						'type'        => 'color',
					),
					'ec_calendar_heading_bg_color' => array(
						'label'       => esc_html__( 'Events Monthly Calendar Heading Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color of the numbered heading in the calendar.', 'Avada' ),
						'id'          => 'ec_calendar_heading_bg_color',
						'default'     => '#b2b2b2',
						'type'        => 'color-alpha',
					),
					'ec_calendar_bg_color' => array(
						'label'       => esc_html__( 'Events Monthly Calendar Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color of each day in the calendar.', 'Avada' ),
						'id'          => 'ec_calendar_bg_color',
						'default'     => '#b2b2b2',
						'type'        => 'color-alpha',
					),
					'ec_tooltip_bg_color' => array(
						'label'       => esc_html__( 'Events Tooltip Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color for the event tooltip background.', 'Avada' ),
						'id'          => 'ec_tooltip_bg_color',
						'default'     => '#ffffff',
						'type'        => 'color-alpha',
					),
					'ec_tooltip_body_color' => array(
						'label'       => esc_html__( 'Events Tooltip Body Text Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the tooltip text.', 'Avada' ),
						'id'          => 'ec_tooltip_body_color',
						'default'     => '#747474',
						'type'        => 'color',
					),
					'ec_border_color' => array(
						'label'       => esc_html__( 'Events Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the various border colors around the calendar.', 'Avada' ),
						'id'          => 'ec_border_color',
						'default'     => '#e0dede',
						'type'        => 'color-alpha',
					),
					'ec_hover_type' => array(
						'label'       => esc_html__( 'Events Featured Image Hover Type', 'Avada' ),
						'description' => esc_html__( 'Controls the hover type for event featured images.', 'Avada' ),
						'id'          => 'ec_hover_type',
						'default'     => 'none',
						'type'        => 'select',
						'choices'     => array(
							'none'    => 'none',
							'zoomin'  => esc_html__( 'Zoom In', 'Avada' ),
							'zoomout' => esc_html__( 'Zoom Out', 'Avada' ),
							'liftup'  => esc_html__( 'Lift Up', 'Avada' )
						),
					),
					'ec_bg_list_view' => array(
						'label'       => esc_html__( 'Events Image Background Size For List View', 'Avada' ),
						'description' => esc_html__( 'Controls if the image is set to auto or covered for list view layout. All other layouts use auto.', 'Avada' ),
						'id'          => 'ec_bg_list_view',
						'default'     => 'cover',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'cover' => 'Cover',
							'auto'  => 'Auto',
						),
					),
				),
			),
			'ec_single_event_detail_section_heading' => array(
				'label'  => esc_html__( 'Events Single Posts', 'Avada' ),
				'id'     => 'ec_single_event_detail_section_heading',
				'type'   => 'sub-section',
				'fields' => array(
					'events_social_sharing_box' => array(
						'label'       => esc_html__( 'Events Social Sharing Box', 'Avada' ),
						'description' => esc_html__( 'Turn on to display the social sharing box on single event posts.', 'Avada' ),
						'id'          => 'events_social_sharing_box',
						'default'     => 1,
						'type'        => 'switch',
					),
					'ec_sidebar_bg_color' => array(
						'label'       => esc_html__( 'Events Sidebar Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color of the single event post sidebars.', 'Avada' ),
						'id'          => 'ec_sidebar_bg_color',
						'default'     => '#f6f6f6',
						'type'        => 'color-alpha',
					),
					'ec_sidebar_padding' => array(
						'label'       => esc_html__( 'Events Sidebar Padding', 'Avada' ),
						'description' => esc_html__( 'Controls the sidebar padding for single event post sidebars.', 'Avada' ),
						'id'          => 'ec_sidebar_padding',
						'default'     => '4%',
						'type'        => 'dimension',
						'choices'     => array( 'px', '%' ),
					),
					'ec_sidew_font_size' => array(
						'label'       => esc_html__( 'Events Sidebar Widget Heading Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the size of the sidebar widget heading for single event posts.', 'Avada' ),
						'id'          => 'ec_sidew_font_size',
						'default'     => '17px',
						'type'        => 'dimension',
					),
					'ec_sidebar_widget_bg_color' => array(
						'label'       => esc_html__( 'Events Sidebar Widget Title Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color of the sidebar widget title for single event posts.', 'Avada' ),
						'id'          => 'ec_sidebar_widget_bg_color',
						'default'     => '#aace4e',
						'type'        => 'color-alpha',
					),
					'ec_sidebar_heading_color' => array(
						'label'       => esc_html__( 'Events Sidebar Widget Headings Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the sidebar widget heading for single event posts.', 'Avada' ),
						'id'          => 'ec_sidebar_heading_color',
						'default'     => '#333333',
						'type'        => 'color',
					),
					'ec_text_font_size' => array(
						'label'       => esc_html__( 'Events Sidebar Text Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the size of the text in the single event post sidebar.', 'Avada' ),
						'id'          => 'ec_text_font_size',
						'default'     => '14',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
					),
					'ec_sidebar_text_color' => array(
						'label'       => esc_html__( 'Events Sidebar Text Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the text in the single event post sidebar.', 'Avada' ),
						'id'          => 'ec_sidebar_text_color',
						'default'     => '#747474',
						'type'        => 'color',
					),
					'ec_sidebar_link_color' => array(
						'label'       => esc_html__( 'Events Sidebar Link Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the link text in the single event post sidebar.', 'Avada' ),
						'id'          => 'ec_sidebar_link_color',
						'default'     => '#333333',
						'type'        => 'color',
					),
					'ec_sidebar_divider_color' => array(
						'label'       => esc_html__( 'Events Sidebar Divider Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the dividers in the single event post sidebar.', 'Avada' ),
						'id'          => 'ec_sidebar_divider_color',
						'default'     => '#e8e8e8',
						'type'        => 'color-alpha',
					),
				),
			),
		),
	);

	return $sections;

}
