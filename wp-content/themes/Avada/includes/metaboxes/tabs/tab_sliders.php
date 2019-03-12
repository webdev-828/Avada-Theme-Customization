<?php

$this->select(
	'slider_position',
	esc_html__( 'Slider Position', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'below'   => esc_html__( 'Below', 'Avada' ),
		'above'   => esc_html__( 'Above', 'Avada' )
	),
	esc_html__( 'Select if the slider shows below or above the header. Only works for top header position.', 'Avada' )
);

$this->select(
	'slider_type',
	esc_html__( 'Slider Type', 'Avada' ),
	array(
		'no'      => esc_html__( 'No Slider', 'Avada' ),
		'layer'   => 'LayerSlider',
		'flex'    => 'Fusion Slider',
		'rev'     => 'Revolution Slider',
		'elastic' => 'Elastic Slider'
	),
	esc_html__( 'Select the type of slider that displays.', 'Avada' )
);

global $wpdb;
$slides_array[0] = esc_html__( 'Select a slider', 'Avada' );
if ( class_exists( 'LS_Sliders' ) ) {

	// Table name
	$table_name = $wpdb->prefix . 'layerslider';

	// Get sliders
	$sliders = $wpdb->get_results( "SELECT * FROM $table_name WHERE flag_hidden = '0' AND flag_deleted = '0' ORDER BY date_c ASC" );

	if ( ! empty( $sliders ) ) {
		foreach ( $sliders as $key => $item ) {
			$slides[$item->id] = $item->name;
		}
	}

	if ( isset( $slides ) && ! empty( $slides ) ) {
		foreach ( $slides as $key => $val ) {
			$slides_array[$key] = $val;
		}
	}
}

$this->select(
	'slider',
	esc_html__( 'Select LayerSlider', 'Avada' ),
	$slides_array,
	esc_html__( 'Select the unique name of the slider.', 'Avada' )
);

$slides_array    = array();
$slides          = array();
$slides_array[0] = esc_html__( 'Select a slider', 'Avada' );
$slides          = get_terms( 'slide-page' );
if ( $slides && ! isset( $slides->errors ) ) {
	$slides = maybe_unserialize( $slides );
	foreach( $slides as $key => $val ) {
		$slides_array[$val->slug] = $val->name;
	}
}
$this->select(
	'wooslider',
	esc_html__( 'Select Fusion Slider', 'Avada' ) ,
	$slides_array,
	esc_html__( 'Select the unique name of the slider.', 'Avada' )
);

global $wpdb;
$revsliders[0] = esc_html__( 'Select a slider', 'Avada' );

if ( function_exists( 'rev_slider_shortcode' ) ) {
	$get_sliders = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'revslider_sliders' );
	if ( $get_sliders ) {
		foreach ( $get_sliders as $slider ) {
			if ( $slider->type != 'template' ) {
				$revsliders[$slider->alias] = $slider->title;
			}
		}
	}
}

$this->select(
	'revslider',
	esc_html__( 'Select Revolution Slider', 'Avada' ),
	$revsliders,
	esc_html__( 'Select the unique name of the slider.', 'Avada' )
);

$slides_array    = array();
$slides_array[0] = esc_html__( 'Select a slider', 'Avada' );
$slides          = get_terms( 'themefusion_es_groups' );
if ( $slides && ! isset( $slides->errors ) ) {
	$slides = maybe_unserialize( $slides );
	foreach ( $slides as $key => $val ) {
		$slides_array[$val->slug] = $val->name;
	}
}
$this->select(
	'elasticslider',
	esc_html__( 'Select Elastic Slider', 'Avada' ),
	$slides_array,
	esc_html__( 'Select the unique name of the slider.', 'Avada' )
);

$this->upload(
	'fallback',
	esc_html__( 'Slider Fallback Image', 'Avada' ),
	esc_html__( 'This image will override the slider on mobile devices.', 'Avada' )
);

$this->select(
	'avada_rev_styles',
	esc_html__( 'Disable Avada Styles For Revolution Slider', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'yes'     => esc_html__( 'Yes', 'Avada' ),
		'no'      => esc_html__( 'No', 'Avada' )
	),
	esc_html__( 'Choose to enable or disable disable Avada styles for Revolution Slider.', 'Avada' )
);

// Omit closing PHP tag to avoid "Headers already sent" issues.
