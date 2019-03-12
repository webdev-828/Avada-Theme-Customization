<?php
/**
 * This file contains typography styles for GFForms plugin
 *
 */

// GFForms css classes that inherit Avada's body typography settings
function avada_gform_body_typography( $typography_elements ){
    if ( class_exists( 'GFForms' ) ) {
        $typography_elements['family'][] = '.gform_wrapper .gform_button';
        $typography_elements['family'][] = '.gform_wrapper .button';
        $typography_elements['family'][] = '.gform_page_footer input[type="button"]';
        $typography_elements['family'][] = '.gform_wrapper label';
        $typography_elements['family'][] = '.gform_wrapper .gfield_description';
        $typography_elements['size'][]   = '.gform_wrapper label';
        $typography_elements['size'][]   = '.gform_wrapper .gfield_description';
    }

    return $typography_elements;
}
add_filter( 'avada_body_typography_elements', 'avada_gform_body_typography' );

// GFForms css classes that inherit Avada's button typography settings
function avada_gform_button_typography( $typography_elements ){
    if ( class_exists( 'GFForms' ) ) {
        $typography_elements['family'][] = '.gform_wrapper .gform_button';
        $typography_elements['family'][] = '.gform_wrapper .button';
        $typography_elements['family'][] = '.gform_page_footer input[type="button"]';
    }

    return $typography_elements;
}
add_filter( 'avada_button_typography_elements', 'avada_gform_button_typography' );

// Omit closing PHP tag to avoid "Headers already sent" issues.
