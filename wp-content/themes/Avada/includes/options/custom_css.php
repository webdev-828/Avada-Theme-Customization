<?php

/**
 * Custom CSS settings
 *
 * @var  array  	any existing settings
 * @return array 	existing sections + custom_css
 *
 */
function avada_options_section_custom_css( $sections ) {

	$sections['custom_css'] = array(
		'label'    => esc_html__( 'Custom CSS', 'Avada' ),
		'id'       => 'custom_css_section',
		'priority' => 27,
		'icon'     => 'el-icon-css',
		'fields'   => array(
			'custom_css' => array(
				'label'       => esc_html__( 'CSS Code', 'Avada' ),
				'description' => sprintf( esc_html__( 'Enter your CSS code in the field below. Do not include any tags or HTML in the field. Custom CSS entered here will override the theme CSS. In some cases, the !important tag may be needed. Don\'t URL encode image or svg paths. Contents of this field will be auto encoded.', 'Avada' ), '<code>!important</code>' ),
				'id'          => 'custom_css',
				'default'     => '',
				'type'        => 'code',
				'choices'     => array(
					'language' => 'css',
					'height'   => 450,
					'theme'    => 'chrome',
					'minLines' => 40,
					'maxLines' => 50
				),
			),
		),
	);

	return $sections;

}
