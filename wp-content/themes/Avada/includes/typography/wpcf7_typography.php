<?php
/**
 * This file contains typography styles for WPCF plugin
 *
 */

// WPCF7 css classes that inherit Avada's body typography settings
function avada_wpcf7_body_typography( $typography_elements ){
    if ( defined( 'WPCF7_PLUGIN' ) ) {
        $typography_elements['family'][] = '.wpcf7-form input[type="submit"]';
    }

    return $typography_elements;
}
add_filter( 'avada_body_typography_elements', 'avada_wpcf7_body_typography' );


// WPCF7 css classes that inherit Avada's button typography settings
function avada_wpcf7_button_typography( $typography_elements ){
    if ( defined( 'WPCF7_PLUGIN' ) ) {
        $typography_elements['family'][] = '.wpcf7-form input[type="submit"]';
    }

    return $typography_elements;
}
add_filter( 'avada_button_typography_elements', 'avada_wpcf7_button_typography' );

// Omit closing PHP tag to avoid "Headers already sent" issues.
