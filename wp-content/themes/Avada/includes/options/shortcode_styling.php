<?php

/**
 * Shortcode-Styling settings
 *
 * @var array any existing settings
 * @return array existing sections + shortcode_styling
 *
 */
function avada_options_section_shortcode_styling( $sections ) {

	$settings = get_option( Avada::get_option_name(), array() );
	if ( isset( $settings['h2_typography']['color'] ) ) {
		$h2_or_primary_default = $settings['h2_typography']['color'];
	} elseif ( isset( $settings['h2_color'] ) ) {
		$h2_or_primary_default = $settings['h2_color'];
	} else {
		$h2_or_primary_default = '#a0ce4e';
	}

	$sections['shortcode_styling'] = array(
		'label'    => esc_html__( 'Shortcodes Styling', 'Avada' ),
		'id'       => 'heading_shortcode_styling',
		'is_panel' => true,
		'priority' => 14,
		'icon'     => 'el-icon-check',
		'fields'   => array(
			'shortcode_animations_accordion' => array(
				'label'       => esc_html__( 'Animations', 'Avada' ),
				'description' => '',
				'id'          => 'shortcode_animations_accordion',
				'default'     => '',
				'type'        => 'accordion',
				'fields'      => array(
					'animation_offset' => array(
						'label'       => esc_html__( 'Animation Offset', 'Avada' ),
						'description' => esc_html__( 'Controls when the animation should start.', 'Avada' ),
						'id'          => 'animation_offset',
						'default'     => 'top-into-view',
						'type'        => 'select',
						'choices'     => array(
							'top-into-view'   => esc_html__( 'Top of element hits bottom of viewport', 'Avada' ),
							'top-mid-of-view' => esc_html__( 'Top of element hits middle of viewport', 'Avada' ),
							'bottom-in-view'  => esc_html__( 'Bottom of element enters viewport', 'Avada' ),
						),
					),
				),
			),
			'blog_shortcode_section' => array(
				'label'       => esc_html__( 'Blog Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'blog_shortcode_section',
				'default'     => '',
				'type'        => 'accordion',
				'fields'      => array(
					'dates_box_color' => array(
						'label'       => esc_html__( 'Blog Date Box Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the date box in blog alternate and recent posts layouts.', 'Avada' ),
						'id'          => 'dates_box_color',
						'default'     => '#eef0f2',
						'type'        => 'color-alpha',
					),
				),
			),
			'button_shortcode_section' => array(
				'label'       => esc_html__( 'Button Shortcode', 'Avada' ),
				'id'          => 'button_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'button_size' => array(
						'label'       => esc_html__( 'Button Size', 'Avada' ),
						'description' => esc_html__( 'Controls the default button size.', 'Avada' ),
						'id'          => 'button_size',
						'default'     => 'Large',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Small'  => esc_html__( 'Small', 'Avada' ),
							'Medium' => esc_html__( 'Medium', 'Avada' ),
							'Large'  => esc_html__( 'Large', 'Avada' ),
							'XLarge' => esc_html__( 'X-Large', 'Avada' ),
						),
					),
					'button_span' => array(
						'label'       => esc_html__( 'Button Span', 'Avada' ),
						'description' => esc_html__( 'Controls if the button spans the full width of its container.', 'Avada' ),
						'id'          => 'button_span',
						'default'     => 'no',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'yes' => esc_html__( 'Yes', 'Avada' ),
							'no'  => esc_html__( 'No', 'Avada' ),
						),
					),
					'button_shape' => array(
						'label'       => esc_html__( 'Button Shape', 'Avada' ),
						'description' => esc_html__( 'Controls the default button shape.', 'Avada' ),
						'id'          => 'button_shape',
						'default'     => 'Round',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Square' => esc_html__( 'Square', 'Avada' ),
							'Round'  => esc_html__( 'Round', 'Avada' ),
							'Pill'   => esc_html__( 'Pill', 'Avada' ),
						),
					),
					'button_type' => array(
						'label'       => esc_html__( 'Button Type', 'Avada' ),
						'description' => esc_html__( 'Controls the default button type.', 'Avada' ),
						'id'          => 'button_type',
						'default'     => 'Flat',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Flat' => esc_html__( 'Flat', 'Avada' ),
							'3d'   => esc_html__( '3D', 'Avada' ),
						),
					),
					'button_typography' => array(
						'id'          => 'button_typography',
						'label'       => esc_html__( 'Button Typography', 'Avada' ),
						'description' => esc_html__( 'These settings control the typography for all button text.', 'Avada' ),
						'type'        => 'typography',
						'choices'     => array(
							'font-family'    => true,
							'font-weight'    => true,
							'letter-spacing' => true,
						),
						'default'     => array(
							'font-family'    => 'PT Sans',
							'font-weight'    => '400',
							'letter-spacing' => '0',
						),
					),
					'button_gradient_top_color' => array(
						'label'       => esc_html__( 'Button Gradient Top Color', 'Avada' ),
						'description' => esc_html__( 'Controls the top color of the button background.', 'Avada' ),
						'id'          => 'button_gradient_top_color',
						'default'     => '#a0ce4e',
						'type'        => 'color-alpha'
					),
					'button_gradient_bottom_color' => array(
						'label'       => esc_html__( 'Button Gradient Bottom Color', 'Avada' ),
						'description' => esc_html__( 'Controls the bottom color of the button background.', 'Avada' ),
						'id'          => 'button_gradient_bottom_color',
						'default'     => '#a0ce4e',
						'type'        => 'color-alpha'
					),
					'button_gradient_top_color_hover' => array(
						'label'       => esc_html__( 'Button Gradient Top Hover Color', 'Avada' ),
						'description' => esc_html__( 'Controls the top hover color of the button background.', 'Avada' ),
						'id'          => 'button_gradient_top_color_hover',
						'default'     => '#96c346',
						'type'        => 'color-alpha'
					),
					'button_gradient_bottom_color_hover' => array(
						'label'       => esc_html__( 'Button Gradient Bottom Hover Color', 'Avada' ),
						'description' => esc_html__( 'Controls the bottom hover color of the button background.', 'Avada' ),
						'id'          => 'button_gradient_bottom_color_hover',
						'default'     => '#96c346',
						'type'        => 'color-alpha'
					),
					'button_accent_color' => array(
						'label'       => esc_html__( 'Button Accent Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the button border, divider, text and icon.', 'Avada' ),
						'id'          => 'button_accent_color',
						'default'     => '#ffffff',
						'type'        => 'color-alpha'
					),
					'button_accent_hover_color' => array(
						'label'       => esc_html__( 'Button Accent Hover Color', 'Avada' ),
						'description' => esc_html__( 'Controls the hover color of the button border, divider, text and icon.', 'Avada' ),
						'id'          => 'button_accent_hover_color',
						'default'     => '#ffffff',
						'type'        => 'color-alpha'
					),
					'button_bevel_color' => array(
						'label'       => esc_html__( 'Button Bevel Color For 3D Mode', 'Avada' ),
						'description' => esc_html__( 'Controls the bevel color of the buttons when using 3D button type.', 'Avada' ),
						'id'          => 'button_bevel_color',
						'default'     => '#54770F',
						'type'        => 'color-alpha'
					),
					'button_border_width' => array(
						'label'       => esc_html__( 'Button Border Width', 'Avada' ),
						'description' => esc_html__( 'Controls the border width for buttons.', 'Avada' ),
						'id'          => 'button_border_width',
						'default'     => '0',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '20',
							'step' => '1',
						),
					),
				),
			),
			'carousel_shortcode_section' => array(
				'label'       => esc_html__( 'Carousel Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'carousel_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'carousel_nav_color' => array(
						'label'       => esc_html__( 'Carousel Navigation Box Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the navigation box for carousel sliders.', 'Avada' ),
						'id'          => 'carousel_nav_color',
						'default'     => 'rgba(0,0,0,0.6)',
						'type'        => 'color-alpha',
					),
					'carousel_hover_color' => array(
						'label'       => esc_html__( 'Carousel Hover Navigation Box Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the hover navigation box for carousel sliders.', 'Avada' ),
						'id'          => 'carousel_hover_color',
						'default'     => 'rgba(0,0,0,0.7)',
						'type'        => 'color-alpha',
					),
					'carousel_speed' => array(
						'label'       => esc_html__( 'Carousel Speed', 'Avada' ),
						'description' => esc_html__( 'Controls the speed of all carousel elements. ex: 1000 = 1 second.', 'Avada' ),
						'id'          => 'carousel_speed',
						'default'     => '2500',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '1000',
							'max'  => '20000',
							'step' => '250',
						),
					),
				),
			),
			'checklist_shortcode_section' => array(
				'label'       => esc_html__( 'Checklist Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'checklist_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'checklist_icons_color' => array(
						'label'       => esc_html__( 'Checklist Icon Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the checklist icon.', 'Avada' ),
						'id'          => 'checklist_icons_color',
						'default'     => '#ffffff',
						'type'        => 'color'
					),
					'checklist_circle' => array(
						'label'       => esc_html__( 'Checklist Circle', 'Avada' ),
						'description' => esc_html__( 'Turn on if you want to display a circle background for checklists.', 'Avada' ),
						'id'          => 'checklist_circle',
						'default'     => '1',
						'type'        => 'switch'
					),
					'checklist_circle_color' => array(
						'label'       => esc_html__( 'Checklist Circle Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the checklist circle background.', 'Avada' ),
						'id'          => 'checklist_circle_color',
						'default'     => '#a0ce4e',
						'type'        => 'color-alpha'
					),
				),
			),
			'content_box_shortcode_section' => array(
				'label'       => esc_html__( 'Content Box Shortcode', 'Avada' ),
				'id'          => 'content_box_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'content_box_bg_color' => array(
						'label'       => esc_html__( 'Content Box Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color for content boxes.', 'Avada' ),
						'id'          => 'content_box_bg_color',
						'default'     => 'rgba(255,255,255,0)',
						'type'        => 'color-alpha',
					),
					'content_box_title_size' => array(
						'label'       => esc_html__( 'Content Box Title Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the size of the title text.', 'Avada' ),
						'id'          => 'content_box_title_size',
						'default'     => '18px',
						'type'        => 'dimension',
					),
					'content_box_title_color' => array(
						'label'       => esc_html__( 'Content Box Title Font Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the title font.', 'Avada' ),
						'id'          => 'content_box_title_color',
						'default'     => $h2_or_primary_default,
						'type'        => 'color',
					),
					'content_box_body_color' => array(
						'label'       => esc_html__( 'Content Box Body Font Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the body font.', 'Avada' ),
						'id'          => 'content_box_body_color',
						'default'     => '#747474',
						'type'        => 'color',
					),
					'content_box_icon_size' => array(
						'label'       => esc_html__( 'Content Box Icon Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the size of the icon.', 'Avada' ),
						'id'          => 'content_box_icon_size',
						'default'     => '21px',
						'type'        => 'dimension',
					),
					'content_box_icon_color' => array(
						'label'       => esc_html__( 'Content Box Icon Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the content box icon.', 'Avada' ),
						'id'          => 'content_box_icon_color',
						'default'     => '#ffffff',
						'type'        => 'color'
					),
					'content_box_icon_circle' => array(
						'label'       => esc_html__( 'Content Box Icon Background', 'Avada' ),
						'description' => esc_html__( 'Turn on to display a background behind the icon.', 'Avada' ),
						'id'          => 'content_box_icon_circle',
						'default'     => 'yes',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'yes' => esc_html__( 'On', 'Avada' ),
							'no'  => esc_html__( 'Off', 'Avada' ),
						),
					),
					'content_box_icon_circle_radius' => array(
						'label'       => esc_html__( 'Content Box Icon Background Radius', 'Avada' ),
						'description' => esc_html__( 'Controls the border radius of the icon background.', 'Avada' ),
						'id'          => 'content_box_icon_circle_radius',
						'default'     => '50%',
						'type'        => 'dimension',
					),
					'content_box_icon_bg_color' => array(
						'label'       => esc_html__( 'Content Box Icon Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the icon background.', 'Avada' ),
						'id'          => 'content_box_icon_bg_color',
						'default'     => '#333333',
						'type'        => 'color-alpha'
					),
					'content_box_icon_bg_inner_border_color' => array(
						'label'       => esc_html__( 'Content Box Icon Background Inner Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the inner border color of the icon background.', 'Avada' ),
						'id'          => 'content_box_icon_bg_inner_border_color',
						'default'     => '#333333',
						'type'        => 'color-alpha'
					),
					'content_box_icon_bg_inner_border_size' => array(
						'label'       => esc_html__( 'Content Box Icon Background Inner Border Size', 'Avada' ),
						'description' => esc_html__( 'Controls the inner border size of the icon background.', 'Avada' ),
						'id'          => 'content_box_icon_bg_inner_border_size',
						'default'     => '1',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '20',
							'step' => '1',
						),
					),
					'content_box_icon_bg_outer_border_color' => array(
						'label'       => esc_html__( 'Content Box Icon Background Outer Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the outer border color of the icon background.', 'Avada' ),
						'id'          => 'content_box_icon_bg_outer_border_color',
						'default'     => 'rgba(255,255,255,0)',
						'type'        => 'color-alpha'
					),
					'content_box_icon_bg_outer_border_size' => array(
						'label'       => esc_html__( 'Content Box Icon Background Outer Border Size', 'Avada' ),
						'description' => esc_html__( 'Controls the outer border size of the icon background', 'Avada' ),
						'id'          => 'content_box_icon_bg_outer_border_size',
						'default'     => '0',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '20',
							'step' => '1',
						),
					),
					'content_box_icon_hover_type' => array(
						'label'       => esc_html__( 'Content Box Hover Animation Type', 'Avada' ),
						'description' => esc_html__( 'Controls the hover effect of the icon.', 'Avada' ),
						'id'          => 'content_box_icon_hover_type',
						'default'     => 'fade',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'none'    => esc_html__( 'None', 'Avada' ),
							'fade'    => esc_html__( 'Fade', 'Avada' ),
							'slide'   => esc_html__( 'Slide', 'Avada' ),
							'pulsate' => esc_html__( 'Pulsate', 'Avada' )
						),
					),
					'content_box_hover_animation_accent_color' => array(
						'label'       => esc_html__( 'Content Box Hover Animation Accent Color', 'Avada' ),
						'description' => esc_html__( 'Controls the accent color for the hover animation.', 'Avada' ),
						'id'          => 'content_box_hover_animation_accent_color',
						'default'     => ( isset( $settings['primary_color'] ) ) ? $settings['primary_color'] : '#a0ce4e',
						'type'        => 'color-alpha'
					),
					'content_box_link_type' => array(
						'label'       => esc_html__( 'Content Box Link Type', 'Avada' ),
						'description' => esc_html__( 'Controls the type of link that displays in the content box.', 'Avada' ),
						'id'          => 'content_box_link_type',
						'default'     => 'text',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'text'       => esc_html__( 'Text', 'Avada' ),
							'button-bar' => esc_html__( 'Button Bar', 'Avada' ),
							'button'     => esc_html__( 'Button', 'Avada' ),
						),
					),
					'content_box_link_area' => array(
						'label'       => esc_html__( 'Content Box Link Area', 'Avada' ),
						'description' => esc_html__( 'Controls which area the link will be assigned to.', 'Avada' ),
						'id'          => 'content_box_link_area',
						'default'     => 'link-icon',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'link-icon' => esc_html__( 'Link + Icon', 'Avada' ),
							'box'       => esc_html__( 'Entire Content Box', 'Avada' ),
						),
					),
					'content_box_link_target' => array(
						'label'       => esc_html__( 'Content Box Link Target', 'Avada' ),
						'description' => esc_html__( 'Controls how the link will open.', 'Avada' ),
						'id'          => 'content_box_link_target',
						'default'     => '_self',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'_self'  => esc_html__( 'Same Window', 'Avada' ),
							'_blank' => esc_html__( 'New Window/Tab', 'Avada' ),
						),
					),
					'content_box_margin' => array(
						'label'       => esc_html__( 'Content Box Top/Bottom Margins', 'Avada' ),
						'description' => esc_html__( 'Controls the top/bottom margin for content boxes.', 'Avada' ),
						'id'          => 'content_box_margin',
						'type'        => 'spacing',
						'choices'     => array(
							'top'     => true,
							'bottom'  => true,
							'units'   => array( 'px', '%' ),
						),
						'default'     => array(
							'top'     => '0px',
							'bottom'  => '60px',
						),
					),
				),
			),
			'countdown_shortcode_section' => array(
				'label'  => esc_html__( 'Countdown Shortcode', 'Avada' ),
				'id'     => 'countdown_shortcode_section',
				'type'   => 'accordion',
				'fields' => array(
					'countdown_timezone' => array(
						'label'       => esc_html__( 'Countdown Timezone', 'Avada' ),
						'description' => esc_html__( 'Controls the timezone that is used for the countdown calculation.', 'Avada' ),
						'id'          => 'countdown_timezone',
						'default'     => 'site_time',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'site_time' => esc_html__( 'Site Timezone', 'Avada' ),
							'user_time' => esc_html__( 'User Timezone', 'Avada' ),
						),
					),
					'countdown_show_weeks' => array(
						'label'       => esc_html__( 'Countdown Show Weeks', 'Avada' ),
						'description' => esc_html__( 'Turn on to display the number of weeks in the countdown.', 'Avada' ),
						'id'          => 'countdown_show_weeks',
						'default'     => 'no',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'yes'     => esc_html__( 'On', 'Avada' ),
							'no'      => esc_html__( 'Off', 'Avada' ),
						),
					),
					'countdown_background_color' => array(
						'label'       => esc_html__( 'Countdown Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color for the countdown box.', 'Avada' ),
						'id'          => 'countdown_background_color',
						'default'     => '#a0ce4e',
						'type'        => 'color-alpha',
					),
					'countdown_background_image' => array(
						'label'       => esc_html__( 'Countdown Background Image', 'Avada' ),
						'description' => esc_html__( 'Select an image for the countdown box background.', 'Avada' ),
						'id'          => 'countdown_background_image',
						'default'     => '',
						'mod'         => '',
						'type'        => 'media',
					),
					'countdown_background_repeat' => array(
						'label'       => esc_html__( 'Countdown Background Repeat', 'Avada' ),
						'description' => esc_html__( 'Controls how the background image repeats.', 'Avada' ),
						'id'          => 'countdown_background_repeat',
						'default'     => 'no-repeat',
						'type'        => 'select',
						'choices'     => array(
							'repeat'    => esc_html__( 'Repeat All', 'Avada' ),
							'repeat-x'  => esc_html__( 'Repeat Horizontal', 'Avada' ),
							'repeat-y'  => esc_html__( 'Repeat Vertical', 'Avada' ),
							'no-repeat' => esc_html__( 'Repeat None', 'Avada' ),
						),
						'required'    => array(
							array(
								'setting'  => 'countdown_background_image',
								'operator' => '!=',
								'value'    => '',
							),
							array(
								'setting'  => 'countdown_background_image',
								'operator' => '!=',
								'value'    => array(
									'url'  => '',
								),
							),
							array(
								'setting'  => 'countdown_background_image',
								'operator' => '!=',
								'value'    => array(
									'url'       => '',
									'id'        => '',
									'height'    => '',
									'width'     => '',
									'thumbnail' => '',
								),
							),
						),
					),
					'countdown_background_position' => array(
						'label'       => esc_html__( 'Countdown Background Position', 'Avada' ),
						'description' => esc_html__( 'Controls how the background image is positioned.', 'Avada' ),
						'id'          => 'countdown_background_position',
						'default'     => 'center center',
						'type'        => 'select',
						'choices'     => array(
							'top left'      => esc_html__( 'top left', 'Avada' ),
							'top center'    => esc_html__( 'top center', 'Avada' ),
							'top right'     => esc_html__( 'top right', 'Avada' ),
							'center left'   => esc_html__( 'center left', 'Avada' ),
							'center center' => esc_html__( 'center center', 'Avada' ),
							'center right'  => esc_html__( 'center right', 'Avada' ),
							'bottom left'   => esc_html__( 'bottom left', 'Avada' ),
							'bottom center' => esc_html__( 'bottom center', 'Avada' ),
							'bottom right'  => esc_html__( 'bottom right', 'Avada' )
						),
						'required'    => array(
							array(
								'setting'  => 'countdown_background_image',
								'operator' => '!=',
								'value'    => '',
							),
							array(
								'setting'  => 'countdown_background_image',
								'operator' => '!=',
								'value'    => array(
									'url'  => '',
								),
							),
							array(
								'setting'  => 'countdown_background_image',
								'operator' => '!=',
								'value'    => array(
									'url'       => '',
									'id'        => '',
									'height'    => '',
									'width'     => '',
									'thumbnail' => '',
								),
							),
						),
					),
					'countdown_counter_box_color' => array(
						'label'       => esc_html__( 'Countdown Counter Box Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color for the counter boxes.', 'Avada' ),
						'id'          => 'countdown_counter_box_color',
						'default'     => '#333333',
						'type'        => 'color-alpha',
					),
					'countdown_counter_text_color' => array(
						'label'       => esc_html__( 'Countdown Counter Text Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color for the countdown timer text.', 'Avada' ),
						'id'          => 'countdown_counter_text_color',
						'default'     => '#ffffff',
						'type'        => 'color',
					),
					'countdown_heading_text_color' => array(
						'label'       => esc_html__( 'Countdown Heading Text Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color for the countdown headings.', 'Avada' ),
						'id'          => 'countdown_heading_text_color',
						'default'     => '#ffffff',
						'type'        => 'color',
					),
					'countdown_subheading_text_color' => array(
						'label'       => esc_html__( 'Countdown Subheading Text Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color for the countdown subheadings.', 'Avada' ),
						'id'          => 'countdown_subheading_text_color',
						'default'     => '#ffffff',
						'type'        => 'color',
					),
					'countdown_link_text_color' => array(
						'label'       => esc_html__( 'Countdown Link Text Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color for the countdown link text.', 'Avada' ),
						'id'          => 'countdown_link_text_color',
						'default'     => '#ffffff',
						'type'        => 'color'
					),
					'countdown_link_target' => array(
						'label'       => esc_html__( 'Countdown Link Target', 'Avada' ),
						'description' => esc_html__( 'Controls how the link will open.', 'Avada' ),
						'id'          => 'countdown_link_target',
						'default'     => '_self',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'_self'  => esc_html__( 'Same Window', 'Avada' ),
							'_blank' => esc_html__( 'New Window', 'Avada' ),
						),
					),
				),
			),
			'counterb_shortcode_section' => array(
				'label'       => esc_html__( 'Counter Boxes Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'counterb_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'counter_box_speed' => array(
						'label'       => esc_html__( 'Counter Box Speed', 'Avada' ),
						'description' => esc_html__( 'Controls the speed of all counter box elements. ex: 1000 = 1 second.', 'Avada' ),
						'id'          => 'counter_box_speed',
						'default'     => '1000',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '500',
							'max'  => '20000',
							'step' => '250',
						),
					),
					'counter_box_color' => array(
						'label'       => esc_html__( 'Counter Box Title Font Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the counter value and icon.', 'Avada' ),
						'id'          => 'counter_box_color',
						'default'     => '#a0ce4e',
						'type'        => 'color-alpha'
					),
					'counter_box_title_size' => array(
						'label'       => esc_html__( 'Counter Box Title Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the size of the counter value and icon.', 'Avada' ),
						'id'          => 'counter_box_title_size',
						'default'     => '50px',
						'type'        => 'dimension',
					),
					'counter_box_icon_size' => array(
						'label'       => esc_html__( 'Counter Box Icon Size', 'Avada' ),
						'description' => esc_html__( 'Controls the size of the icon.', 'Avada' ),
						'id'          => 'counter_box_icon_size',
						'default'     => '50px',
						'type'        => 'dimension',
					),
					'counter_box_body_color' => array(
						'label'       => esc_html__( 'Counter Box Body Font Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the counter box body text.', 'Avada' ),
						'id'          => 'counter_box_body_color',
						'default'     => '#747474',
						'type'        => 'color'
					),
					'counter_box_body_size' => array(
						'label'       => esc_html__( 'Counter Box Body Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the size of the counter box body text.', 'Avada' ),
						'id'          => 'counter_box_body_size',
						'default'     => '13px',
						'type'        => 'dimension',
					),
					'counter_box_border_color' => array(
						'label'       => esc_html__( 'Counter Box Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the counter box border.', 'Avada' ),
						'id'          => 'counter_box_border_color',
						'default'     => '#e0dede',
						'type'        => 'color-alpha'
					),
					'counter_box_icon_top' => array(
						'label'       => esc_html__( 'Counter Box Icon On Top', 'Avada' ),
						'description' => esc_html__( 'Turn on to display the icon on top of the counter value.', 'Avada' ),
						'id'          => 'counter_box_icon_top',
						'default'     => 'no',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'yes' => esc_html__( 'On', 'Avada' ),
							'no'  => esc_html__( 'Off', 'Avada' ),
						),
					),
				),
			),
			'cc_shortcode_section' => array(
				'label'       => esc_html__( 'Counter Circle Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'cc_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'counter_filled_color' => array(
						'label'       => esc_html__( 'Counter Circle Filled Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the filled circle.', 'Avada' ),
						'id'          => 'counter_filled_color',
						'default'     => '#a0ce4e',
						'type'        => 'color-alpha',
					),
					'counter_unfilled_color' => array(
						'label'       => esc_html__( 'Counter Circle Unfilled Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the unfilled circle.', 'Avada' ),
						'id'          => 'counter_unfilled_color',
						'default'     => '#f6f6f6',
						'type'        => 'color-alpha',
					),
				),
			),
			'dropcap_shortcode_section' => array(
				'label'       => esc_html__( 'Dropcap Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'dropcap_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'dropcap_color' => array(
						'label'       => esc_html__( 'Dropcap Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the dropcap text, or the dropcap box if a box is used.', 'Avada' ),
						'id'          => 'dropcap_color',
						'default'     => '#a0ce4e',
						'type'        => 'color-alpha'
					),
				),
			),
			'flipb_shortcode_section' => array(
				'label'       => esc_html__( 'Flip Boxes Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'flipb_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'flip_boxes_front_bg' => array(
						'label'       => esc_html__( 'Flip Box Background Color Frontside', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the frontside background.', 'Avada' ),
						'id'          => 'flip_boxes_front_bg',
						'default'     => '#f6f6f6',
						'type'        => 'color-alpha'
					),
					'flip_boxes_front_heading' => array(
						'label'       => esc_html__( 'Flip Box Heading Color Frontside', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the frontside heading.', 'Avada' ),
						'id'          => 'flip_boxes_front_heading',
						'default'     => '#333333',
						'type'        => 'color'
					),
					'flip_boxes_front_text' => array(
						'label'       => esc_html__( 'Flip Box Text Color Frontside', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the frontside text.', 'Avada' ),
						'id'          => 'flip_boxes_front_text',
						'default'     => '#747474',
						'type'        => 'color'
					),
					'flip_boxes_back_bg' => array(
						'label'       => esc_html__( 'Flip Box Background Color Backside', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the backside background.', 'Avada' ),
						'id'          => 'flip_boxes_back_bg',
						'default'     => '#a0ce4e',
						'type'        => 'color-alpha'
					),
					'flip_boxes_back_heading' => array(
						'label'       => esc_html__( 'Flip Box Heading Color Backside', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the backside heading.', 'Avada' ),
						'id'          => 'flip_boxes_back_heading',
						'default'     => '#eeeded',
						'type'        => 'color'
					),
					'flip_boxes_back_text' => array(
						'label'       => esc_html__( 'Flip Box Text Color Backside', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the backside text.', 'Avada' ),
						'id'          => 'flip_boxes_back_text',
						'default'     => '#ffffff',
						'type'        => 'color'
					),
					'flip_boxes_border_size' => array(
						'label'       => esc_html__( 'Flip Box Border Size', 'Avada' ),
						'description' => esc_html__( 'Controls the border size of the flip box background.', 'Avada' ),
						'id'          => 'flip_boxes_border_size',
						'default'     => '1',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '50',
							'step' => '1',
						)
					),
					'flip_boxes_border_color' => array(
						'label'       => esc_html__( 'Flip Box Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the border color of flip box background.', 'Avada' ),
						'id'          => 'flip_boxes_border_color',
						'default'     => 'rgba(0,0,0,0)',
						'type'        => 'color-alpha'
					),
					'flip_boxes_border_radius' => array(
						'label'       => esc_html__( 'Flip Box Border Radius', 'Avada' ),
						'description' => esc_html__( 'Controls the border radius of the flip box background.', 'Avada' ),
						'id'          => 'flip_boxes_border_radius',
						'default'     => '4px',
						'type'        => 'dimension',
						'choices'     => array( 'px', '%', 'em' )
					),
				),
			),
			'fullwidth_shortcode_section' => array(
				'label'       => esc_html__( 'Full Width Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'fullwidth_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'full_width_bg_color' => array(
						'label'       => esc_html__( 'Full Width Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color of the full width section.', 'Avada' ),
						'id'          => 'full_width_bg_color',
						'default'     => 'rgba(255,255,255,0)',
						'type'        => 'color-alpha'
					),
					'full_width_border_size' => array(
						'label'       => esc_html__( 'Full Width Border Size', 'Avada' ),
						'description' => esc_html__( 'Controls the border size of the full width section.', 'Avada' ),
						'id'          => 'full_width_border_size',
						'default'     => '0',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '50',
							'step' => '1',
						),
					),
					'full_width_border_color' => array(
						'label'       => esc_html__( 'Full Width Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the border color of the full width section.', 'Avada' ),
						'id'          => 'full_width_border_color',
						'default'     => '#eae9e9',
						'type'        => 'color-alpha'
					),
				),
			),
			'icon_shortcode_section' => array(
				'label'       => esc_html__( 'Icon Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'icon_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'icon_circle_color' => array(
						'label'       => esc_html__( 'Icon Circle Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the circle background.', 'Avada' ),
						'id'          => 'icon_circle_color',
						'default'     => '#333333',
						'type'        => 'color-alpha'
					),
					'icon_border_color' => array(
						'label'       => esc_html__( 'Icon Circle Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the border color of the circle background.', 'Avada' ),
						'id'          => 'icon_border_color',
						'default'     => '#333333',
						'type'        => 'color-alpha',
					),
					'icon_color' => array(
						'label'       => esc_html__( 'Icon Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the icon.', 'Avada' ),
						'id'          => 'icon_color',
						'default'     => '#ffffff',
						'type'        => 'color-alpha'
					),
				),
			),
			'imgf_shortcode_section' => array(
				'label'       => esc_html__( 'Image Frame Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'imgf_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'imgframe_border_color' => array(
						'label'       => esc_html__( 'Image Frame Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the border color of the image frame.', 'Avada' ),
						'id'          => 'imgframe_border_color',
						'default'     => '#f6f6f6',
						'type'        => 'color-alpha'
					),
					'imageframe_border_size' => array(
						'label'       => esc_html__( 'Image Frame Border Size', 'Avada' ),
						'description' => esc_html__( 'Controls the border size of the image frame.', 'Avada' ),
						'id'          => 'imageframe_border_size',
						'default'     => '0',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '50',
							'step' => '1',
						),
					),
					'imageframe_border_radius' => array(
						'label'       => esc_html__( 'Image Frame Border Radius', 'Avada' ),
						'description' => esc_html__( 'Controls the border radius of the image frame.', 'Avada' ),
						'id'          => 'imageframe_border_radius',
						'default'     => '0px',
						'type'        => 'dimension',
						'choices'     => array( 'px', '%' ),
					),
					'imgframe_style_color' => array(
						'label'       => esc_html__( 'Image Frame Style Color', 'Avada' ),
						'description' => esc_html__( 'Controls the style color of the image frame. Only works for glow and drop shadow style.', 'Avada' ),
						'id'          => 'imgframe_style_color',
						'default'     => '#000000',
						'type'        => 'color-alpha'
					),
				),
			),
			'modal_shortcode_section' => array(
				'label'       => esc_html__( 'Modal Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'modal_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'modal_bg_color' => array(
						'label'       => esc_html__( 'Modal Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color of the modal popup box.', 'Avada' ),
						'id'          => 'modal_bg_color',
						'default'     => '#f6f6f6',
						'type'        => 'color-alpha'
					),
					'modal_border_color' => array(
						'label'       => esc_html__( 'Modal Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the border color of the modal popup box.', 'Avada' ),
						'id'          => 'modal_border_color',
						'default'     => '#ebebeb',
						'type'        => 'color-alpha'
					),
				),
			),
			'person_shortcode_section' => array(
				'label'       => esc_html__( 'Person Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'person_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'person_shortcode_important_note_info' => array(
						'label'       => '',
						'description' => '<div class="avada-avadaredux-important-notice">' . __( '<strong>IMPORTANT NOTE:</strong> The styling options for the social icons used in the person shortcode are controlled through the options under the "Social Icon Shortcodes" section on this tab.', 'Avada' ) . '</div>',
						'id'          => 'person_shortcode_important_note_info',
						'type'        => 'custom',
					),
					'person_background_color' => array(
						'label'       => esc_html__( 'Person Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color of the person area.', 'Avada' ),
						'id'          => 'person_background_color',
						'default'     => 'rgba(0,0,0,0)',
						'type'        => 'color-alpha',
					),
					'person_border_color' => array(
						'label'       => esc_html__( 'Person Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the border color of the person image.', 'Avada' ),
						'id'          => 'person_border_color',
						'default'     => '#f6f6f6',
						'type'        => 'color-alpha'
					),
					'person_border_size' => array(
						'label'       => esc_html__( 'Person Border Size', 'Avada' ),
						'description' => esc_html__( 'Controls the border size of the person image.', 'Avada' ),
						'id'          => 'person_border_size',
						'default'     => '0',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '50',
							'step' => '1',
						),
					),
					'person_border_radius' => array(
						'label'       => esc_html__( 'Person Border Radius', 'Avada' ),
						'description' => esc_html__( 'Controls the border radius of the person image.', 'Avada' ),
						'id'          => 'person_border_radius',
						'default'     => '0px',
						'type'        => 'dimension',
						'choices'     => array( 'px', '%' ),
					),
					'person_style_color' => array(
						'label'       => esc_html__( 'Person Style Color', 'Avada' ),
						'description' => esc_html__( 'Controls the style color for all style types except border.', 'Avada' ),
						'id'          => 'person_style_color',
						'default'     => '#000000',
						'type'        => 'color-alpha'
					),
					'person_alignment' => array(
						'label'       => esc_html__( 'Person Content Alignment', 'Avada' ),
						'description' => esc_html__( 'Controls the alignment of the person content.', 'Avada' ),
						'id'          => 'person_alignment',
						'default'     => 'Left',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Left'   => esc_html__( 'Left', 'Avada' ),
							'Center' => esc_html__( 'Center', 'Avada' ),
							'Right'  => esc_html__( 'Right', 'Avada' ),
						)
					),
					'person_icon_position' => array(
						'label'       => esc_html__( 'Person Social Icon Position', 'Avada' ),
						'description' => esc_html__( 'Controls the position of the social icons.', 'Avada' ),
						'id'          => 'person_icon_position',
						'default'     => 'Top',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Top'    => esc_html__( 'Top', 'Avada' ),
							'Bottom' => esc_html__( 'Bottom', 'Avada' ),
						),
					),
				),
			),
			'popover_shortcode_section' => array(
				'label'       => esc_html__( 'Popover Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'popover_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'popover_heading_bg_color' => array(
						'label'       => esc_html__( 'Popover Heading Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the popover heading background.', 'Avada' ),
						'id'          => 'popover_heading_bg_color',
						'default'     => '#f6f6f6',
						'type'        => 'color-alpha'
					),
					'popover_content_bg_color' => array(
						'label'       => esc_html__( 'Popover Content Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of popover content background.', 'Avada' ),
						'id'          => 'popover_content_bg_color',
						'default'     => '#ffffff',
						'type'        => 'color-alpha'
					),
					'popover_border_color' => array(
						'label'       => esc_html__( 'Popover Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the border color of popover box.', 'Avada' ),
						'id'          => 'popover_border_color',
						'default'     => '#ebebeb',
						'type'        => 'color-alpha'
					),
					'popover_text_color' => array(
						'label'       => esc_html__( 'Popover Text Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the popover text.', 'Avada' ),
						'id'          => 'popover_text_color',
						'default'     => '#747474',
						'type'        => 'color'
					),
					'popover_placement' => array(
						'label'       => esc_html__( 'Popover Position', 'Avada' ),
						'description' => esc_html__( 'Controls the position of the popover in reference to the triggering element.', 'Avada' ),
						'id'          => 'popover_placement',
						'default'     => 'Top',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Top'    => esc_html__( 'Top', 'Avada' ),
							'Right'  => esc_html__( 'Right', 'Avada' ),
							'Bottom' => esc_html__( 'Bottom', 'Avada' ),
							'Left'   => esc_html__( 'Left', 'Avada' ),
						),
					),
				),
			),
			'pricingtable_shortcode_section' => array(
				'label'       => esc_html__( 'Pricing Table Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'pricingtable_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'full_boxed_pricing_box_heading_color' => array(
						'label'       => esc_html__( 'Pricing Box Style 1 Heading Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of style 1 pricing table headings.', 'Avada' ),
						'id'          => 'full_boxed_pricing_box_heading_color',
						'default'     => '#333333',
						'type'        => 'color',
					),
					'sep_pricing_box_heading_color' => array(
						'label'       => esc_html__( 'Pricing Box Style 2 Heading Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of style 2 pricing table headings.', 'Avada' ),
						'id'          => 'sep_pricing_box_heading_color',
						'default'     => '#333333',
						'type'        => 'color',
					),
					'pricing_box_color' => array(
						'label'       => esc_html__( 'Pricing Box Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color portions of pricing boxes.', 'Avada' ),
						'id'          => 'pricing_box_color',
						'default'     => '#a0ce4e',
						'type'        => 'color-alpha',
					),
					'pricing_bg_color' => array(
						'label'       => esc_html__( 'Pricing Box Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the main background and title background.', 'Avada' ),
						'id'          => 'pricing_bg_color',
						'default'     => '#ffffff',
						'type'        => 'color-alpha'
					),
					'pricing_border_color' => array(
						'label'       => esc_html__( 'Pricing Box Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the outer border, pricing row and footer row backgrounds.', 'Avada' ),
						'id'          => 'pricing_border_color',
						'default'     => '#f8f8f8',
						'type'        => 'color-alpha'
					),
					'pricing_divider_color' => array(
						'label'       => esc_html__( 'Pricing Box Divider Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the dividers in-between pricing rows.', 'Avada' ),
						'id'          => 'pricing_divider_color',
						'default'     => '#ededed',
						'type'        => 'color-alpha'
					),
				),
			),
			'progressbar_shortcode_section' => array(
				'label'       => esc_html__( 'Progress Bar Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'progressbar_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'progressbar_height' => array(
						'label'       => esc_html__( 'Progress Bar Height', 'Avada' ),
						'description' => esc_html__( 'Insert a height for the progress bar.', 'Avada' ),
						'id'          => 'progressbar_height',
						'default'     => '37px',
						'type'        => 'dimension',
					),
					'progressbar_text_position' => array(
						'label'       => esc_html__( 'Text Position', 'Avada' ),
						'description' => esc_html__( 'Select the position of the progress bar text. Choose "Default" for theme option selection.', 'Avada' ),
						'id'          => 'progressbar_text_position',
						'default'     => 'on_bar',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'on_bar'    => esc_html__( 'On Bar', 'Avada' ),
							'above_bar'  => esc_html__( 'Above Bar', 'Avada' ),
							'below_bar'  => esc_html__( 'Below Bar', 'Avada' ),
						),
					),
					'progressbar_filled_color' => array(
						'label'       => esc_html__( 'Progress Bar Filled Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the progress bar filled area.', 'Avada' ),
						'id'          => 'progressbar_filled_color',
						'default'     => '#a0ce4e',
						'type'        => 'color-alpha'
					),
					'progressbar_filled_border_color' => array(
						'label'       => esc_html__( 'Progress Bar Filled Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the border color of the progress bar filled area.', 'Avada' ),
						'id'          => 'progressbar_filled_border_color',
						'default'     => '#ffffff',
						'type'        => 'color-alpha'
					),
					'progressbar_filled_border_size' => array(
						'label'       => esc_html__( 'Progress Bar Filled Border Size', 'Avada' ),
						'description' => esc_html__( 'Controls the border size of the progress bar filled area.', 'Avada' ),
						'id'          => 'progressbar_filled_border_size',
						'default'     => '0',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '20',
							'step' => '1',
						),
					),
					'progressbar_unfilled_color' => array(
						'label'       => esc_html__( 'Progress Bar Unfilled Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the progress bar unfilled area.', 'Avada' ),
						'id'          => 'progressbar_unfilled_color',
						'default'     => '#f6f6f6',
						'type'        => 'color-alpha'
					),
					'progressbar_text_color' => array(
						'label'       => esc_html__( 'Progress Bar Text Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the progress bar text.', 'Avada' ),
						'id'          => 'progressbar_text_color',
						'default'     => '#ffffff',
						'type'        => 'color-alpha'
					),
				),
			),
			'sectionseparator_shortcode_section' => array(
				'label'       => esc_html__( 'Section Separator Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'sectionseparator_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'section_sep_border_size' => array(
						'label'       => esc_html__( 'Section Separator Border Size', 'Avada' ),
						'description' => esc_html__( 'Controls the border size of the section separator.', 'Avada' ),
						'id'          => 'section_sep_border_size',
						'default'     => '1',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '50',
							'step' => '1',
						),
					),
					'section_sep_bg' => array(
						'label'       => esc_html__( 'Section Separator Divider Candy Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the background color of the divider candy.', 'Avada' ),
						'id'          => 'section_sep_bg',
						'default'     => '#f6f6f6',
						'type'        => 'color-alpha'
					),
					'section_sep_border_color' => array(
						'label'       => esc_html__( 'Section Separator Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the border color of the separator.', 'Avada' ),
						'id'          => 'section_sep_border_color',
						'default'     => '#f6f6f6',
						'type'        => 'color-alpha'
					),
				),
			),
			'separator_shortcode_section' => array(
				'label'       => esc_html__( 'Separator Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'separator_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'sep_color' => array(
						'label'       => esc_html__( 'Separator Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of all separators, divider lines and borders for meta, previous & next, filters, archive pages, boxes around number pagination, sidebar widgets, accordion divider lines, counter boxes and more.', 'Avada' ),
						'id'          => 'sep_color',
						'default'     => '#e0dede',
						'type'        => 'color-alpha',
					),
					'separator_circle' => array(
						'label'       => esc_html__( 'Separator Circle', 'Avada' ),
						'description' => esc_html__( 'Turn on if you want to display a circle around the separator icon.', 'Avada' ),
						'id'          => 'separator_circle',
						'default'     => '1',
						'type'        => 'switch'
					),
					'separator_border_size' => array(
						'label'       => esc_html__( 'Border Size', 'Avada' ),
						'description' => esc_html__( 'Controls the border size of the separator.', 'Avada' ),
						'id'          => 'separator_border_size',
						'default'     => '1',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '50',
							'step' => '1',
						),
					),
				),
			),
			'sociallinks_shortcode_section' => array(
				'label'       => esc_html__( 'Social Icon Shortcodes', 'Avada' ),
				'description' => '',
				'id'          => 'sociallinks_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'social_links_info' => array(
						'id'    => 'social_links_info',
						'type'  => 'raw',
						'content'  => '<div class="avada-avadaredux-important-notice">' . __( '<strong>IMPORTANT NOTE:</strong> These social icon global options control both the social link shortcode and person shortcode.', 'Avada' ) . '</div>',
					),
					'social_links_font_size' => array(
						'label'       => esc_html__( 'Social Links Icons Font Size', 'Avada' ),
						'description' => esc_html__( 'Controls the font size for the social link icons.', 'Avada' ),
						'id'          => 'social_links_font_size',
						'default'     => '16px',
						'type'        => 'dimension',
					),
					'social_links_color_type' => array(
						'label'       => esc_html__( 'Social Links Icon Color Type', 'Avada' ),
						'description' => esc_html__( 'Custom colors allow you to choose a color for icons and boxes. Brand colors will use the exact brand color of each network for the icons or boxes.', 'Avada' ),
						'id'          => 'social_links_color_type',
						'default'     => 'custom',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'custom' => esc_html__( 'Custom Colors', 'Avada' ),
							'brand'  => esc_html__( 'Brand Colors', 'Avada' ),
						),
					),
					'social_links_icon_color' => array(
						'label'       => esc_html__( 'Social Links Custom Icons Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the custom icons.', 'Avada' ),
						'id'          => 'social_links_icon_color',
						'default'     => '#bebdbd',
						'type'        => 'color-alpha',
						'required'    => array(
							array(
								'setting'  => 'social_links_color_type',
								'operator' => '==',
								'value'    => 'custom',
							),
						),
					),
					'social_links_boxed' => array(
						'label'       => esc_html__( 'Social Links Icons Boxed', 'Avada' ),
						'description' => esc_html__( 'Turn on to have the icon displayed in a small box. Turn off to have the icon displayed with no box.', 'Avada' ),
						'id'          => 'social_links_boxed',
						'default'     => '0',
						'type'        => 'switch',
					),
					'social_links_box_color' => array(
						'label'       => esc_html__( 'Social Links Icons Custom Box Color', 'Avada' ),
						'description' => esc_html__( 'Select a custom social icon box color.', 'Avada' ),
						'id'          => 'social_links_box_color',
						'default'     => '#e8e8e8',
						'type'        => 'color-alpha',
						'required'    => array(
							array(
								'setting'  => 'social_links_boxed',
								'operator' => '==',
								'value'    => '1',
							),
							array(
								'setting'  => 'social_links_color_type',
								'operator' => '==',
								'value'    => 'custom',
							),
						),
					),
					'social_links_boxed_radius' => array(
						'label'       => esc_html__( 'Social Links Icons Boxed Radius', 'Avada' ),
						'description' => esc_html__( 'Box radius for the social icons.', 'Avada' ),
						'id'          => 'social_links_boxed_radius',
						'default'     => '4px',
						'type'        => 'dimension',
						'choices'     => array( 'px', 'em' ),
						'required'    => array(
							array(
								'setting'  => 'social_links_boxed',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'social_links_boxed_padding' => array(
						'label'       => esc_html__( 'Social Links Icons Boxed Padding', 'Avada' ),
						'id'          => 'social_links_boxed_padding',
						'default'     => '8px',
						'type'        => 'dimension',
						'required'    => array(
							array(
								'setting'  => 'social_links_boxed',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
					'social_links_tooltip_placement' => array(
						'label'       => esc_html__( 'Social Links Icons Tooltip Position', 'Avada' ),
						'description' => esc_html__( 'Controls the tooltip position of the social links icons.', 'Avada' ),
						'id'          => 'social_links_tooltip_placement',
						'default'     => 'Top',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'Top'    => esc_html__( 'Top', 'Avada' ),
							'Right'  => esc_html__( 'Right', 'Avada' ),
							'Bottom' => esc_html__( 'Bottom', 'Avada' ),
							'Left'   => esc_html__( 'Left', 'Avada' ),
							'None'   => esc_html__( 'None', 'Avada' ),
						),
					),
				),
			),
			'tabs_shortcode_section' => array(
				'label'       => esc_html__( 'Tabs Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'tabs_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'tabs_bg_color' => array(
						'label'       => esc_html__( 'Tabs Background Color + Hover Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the active tab, tab hover and content background.', 'Avada' ),
						'id'          => 'tabs_bg_color',
						'default'     => '#ffffff',
						'type'        => 'color-alpha',
					),
					'tabs_inactive_color' => array(
						'label'       => esc_html__( 'Tabs Inactive Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the inactive tabs.', 'Avada' ),
						'id'          => 'tabs_inactive_color',
						'default'     => '#ebeaea',
						'type'        => 'color-alpha',
					),
					'tabs_border_color' => array(
						'label'       => esc_html__( 'Tabs Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the tab border.', 'Avada' ),
						'id'          => 'tabs_border_color',
						'default'     => '#ebeaea',
						'type'        => 'color-alpha',
					),
				),
			),
			'tagline_shortcode_section' => array(
				'label'       => esc_html__( 'Tagline Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'tagline_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'tagline_bg' => array(
						'label'       => esc_html__( 'Tagline Box Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the tagline box background.', 'Avada' ),
						'id'          => 'tagline_bg',
						'default'     => '#f6f6f6',
						'type'        => 'color-alpha',
					),
					'tagline_border_color' => array(
						'label'       => esc_html__( 'Tagline Box Border Color', 'Avada' ),
						'description' => esc_html__( 'Controls the border color of the tagline box.', 'Avada' ),
						'id'          => 'tagline_border_color',
						'default'     => '#f6f6f6',
						'type'        => 'color-alpha'
					),
					'tagline_margin' => array(
						'label'       => esc_html__( 'Tagline Top/Bottom Margins', 'Avada' ),
						'description' => esc_html__( 'Controls the top/bottom margin of the tagline box.', 'Avada' ),
						'id'          => 'tagline_margin',
						'default'     => array(
							'top'     => '0px',
							'bottom'  => '84px',
						),
						'type'        => 'spacing',
						'choices'     => array(
							'top'     => true,
							'bottom'  => true,
						),
					),
				),
			),
			'testimonials_shortcode_section' => array(
				'label'       => esc_html__( 'Testimonials Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'testimonials_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'testimonial_bg_color' => array(
						'label'       => esc_html__( 'Testimonial Background Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the testimonial background.', 'Avada' ),
						'id'          => 'testimonial_bg_color',
						'default'     => '#f6f6f6',
						'type'        => 'color-alpha',
					),
					'testimonial_text_color' => array(
						'label'       => esc_html__( 'Testimonial Text Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the testimonial text.', 'Avada' ),
						'id'          => 'testimonial_text_color',
						'default'     => '#747474',
						'type'        => 'color',
					),
					'testimonials_speed' => array(
						'label'       => esc_html__( 'Testimonials Speed', 'Avada' ),
						'description' => esc_html__( 'Controls the speed of the testimonial slider. ex: 1000 = 1 second.', 'Avada' ),
						'id'          => 'testimonials_speed',
						'default'     => '4000',
						'type'        => 'slider',
						'choices'     => array(
							'min'  => '0',
							'max'  => '20000',
							'step' => '250',
						),
					),
					'testimonials_random' => array(
						'label'       => esc_html__( 'Random Order', 'Avada' ),
						'description' => esc_html__( 'Turn on to display testimonials in a random order.', 'Avada' ),
						'id'          => 'testimonials_random',
						'default'     => '0',
						'type'        => 'switch'
					),
				),
			),
			'title_shortcode_section' => array(
				'label'       => esc_html__( 'Title Shortcode', 'Avada' ),
				'description' => '',
				'id'          => 'title_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'title_style_type' => array(
						'label'       => esc_html__( 'Title Separator', 'Avada' ),
						'description' => esc_html__( 'Controls the type of title separator that will display.', 'Avada' ),
						'id'          => 'title_style_type',
						'default'     => 'double',
						'type'        => 'select',
						'choices'     => array(
							'single'           => esc_html__( 'Single', 'Avada' ),
							'single solid'     => esc_html__( 'Single Solid', 'Avada' ),
							'single dashed'    => esc_html__( 'Single Dashed', 'Avada' ),
							'single dotted'    => esc_html__( 'Single Dotted', 'Avada' ),
							'double'           => esc_html__( 'Double', 'Avada' ),
							'double solid'     => esc_html__( 'Double Solid', 'Avada' ),
							'double dashed'    => esc_html__( 'Double Dashed', 'Avada' ),
							'double dotted'    => esc_html__( 'Double Dotted', 'Avada' ),
							'underline'        => esc_html__( 'Underline', 'Avada' ),
							'underline solid'  => esc_html__( 'Underline Solid', 'Avada' ),
							'underline dashed' => esc_html__( 'Underline Dashed', 'Avada' ),
							'underline dotted' => esc_html__( 'Underline Dotted', 'Avada' ),
							'none'             => esc_html__( 'None', 'Avada' )
						),
					),
					'title_border_color' => array(
						'label'       => esc_html__( 'Title Separator Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the title separators.', 'Avada' ),
						'id'          => 'title_border_color',
						'default'     => '#e0dede',
						'type'        => 'color-alpha',
					),
					'title_margin' => array(
						'label'       => esc_html__( 'Title Top/Bottom Margins', 'Avada' ),
						'description' => esc_html__( 'Controls the top/bottom margin of the title.', 'Avada' ),
						'id'          => 'title_margin',
						'default'     => array(
							'top'     => '0px',
							'bottom'  => '31px',
						),
						'type'        => 'spacing',
						'choices'     => array(
							'top'     => true,
							'bottom'  => true,
						),
					),
				),
			),
			'accordion_shortcode_section' => array(
				'label'       => esc_html__( 'Toggles Shortcode', 'Avada' ),
				'id'          => 'accordion_shortcode_section',
				'type'        => 'accordion',
				'fields'      => array(
					'accordion_divider_line' => array(
						'label'       => esc_html__( 'Toggle Divider Line', 'Avada' ),
						'description' => esc_html__( 'Turn on to display a divider line between each item.', 'Avada' ),
						'id'          => 'accordion_divider_line',
						'default'     => '1',
						'type'        => 'switch'
					),
					'accordian_inactive_color' => array(
						'label'       => esc_html__( 'Toggles Inactive Box Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the inactive toggle box.', 'Avada' ),
						'id'          => 'accordian_inactive_color',
						'default'     => '#333333',
						'type'        => 'color-alpha',
					),
				),
			),
			'user_login_shortcode_section' => array(
				'label'       => esc_html__( 'User Login Shortcode', 'Avada' ),
				'id'          => 'user_login_shortcode_section',
				'description' => '',
				'type'        => 'accordion',
				'fields'      => array(
					'user_login_text_align' => array(
						'label'       => esc_html__( 'User Login Text Align', 'Avada' ),
						'description' => esc_html__( 'Controls the alignment of all user login content. "Text Flow" follows the default text align of the site. "Center" will center all elements.', 'Avada' ),
						'id'          => 'user_login_text_align',
						'default'     => 'center',
						'type'        => 'radio-buttonset',
						'choices'     => array(
							'textflow' => esc_html__( 'Text Flow', 'Avada' ),
							'center'   => esc_html__( 'Center', 'Avada' ),
						),
					),
					'user_login_form_background_color' => array(
						'label'       => esc_html__( 'User Login Form Backgound Color', 'Avada' ),
						'description' => esc_html__( 'Controls the color of the form background.', 'Avada'),
						'id'          => 'user_login_form_background_color',
						'default'     => '#f6f6f6',
						'type'        => 'color-alpha',
					),
				),
			),
		),
	);

	return $sections;

}
