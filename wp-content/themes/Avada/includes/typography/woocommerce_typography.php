<?php
/**
 * This file contains typography styles for WooCommerce plugin
 *
 */

// WooCommerce css classes that inherit Avada's body typography settings
function avada_woocommerce_body_typography( $typography_elements ){
    if ( class_exists( 'WooCommerce' ) ) {
        $typography_elements['color'][]  = '.quantity';
        $typography_elements['color'][]  = '.quantity .qty';
        $typography_elements['color'][]  = '.quantity .minus';
        $typography_elements['color'][]  = '.quantity .plus';
        $typography_elements['family'][] = '.woocommerce-success-message .button';
        $typography_elements['family'][] = '.woocommerce .shipping-calculator-form .button';
        $typography_elements['family'][] = '.widget.woocommerce .product-title';
    }

    return $typography_elements;
}
add_filter( 'avada_body_typography_elements', 'avada_woocommerce_body_typography' );

// WooCommerce css classes that inherit Avada's H1 typography settings
function avada_woocommerce_h1_typography( $typography_elements ){
    if ( class_exists( 'WooCommerce' ) ) {
        $typography_elements['color'][]  = '.woocommerce-success-message .msg';
        $typography_elements['color'][]  = '.woocommerce-message';
        $typography_elements['family'][] = '.woocommerce-success-message .msg';
    }

    return $typography_elements;
}
add_filter( 'avada_h1_typography_elements', 'avada_woocommerce_h1_typography' );

// WooCommerce css classes that inherit Avada's H2 typography settings
function avada_woocommerce_h2_typography( $typography_elements ){
    if ( class_exists( 'WooCommerce' ) ) {
        $typography_elements['size'][]   = '#wrapper .woocommerce .checkout h3';
        $typography_elements['size'][]   = '.woocommerce .checkout h3';
        $typography_elements['color'][]  = '.cart-empty';
        $typography_elements['color'][]  = '.woocommerce-tabs h2';
        $typography_elements['color'][]  = '.woocommerce h2';
        $typography_elements['color'][]  = '.woocommerce .checkout h3';
        $typography_elements['family'][] = '.cart-empty';
    }

    return $typography_elements;
}
add_filter( 'avada_h2_typography_elements', 'avada_woocommerce_h2_typography' );

// WooCommerce css classes that inherit Avada's H3 typography settings
function avada_woocommerce_h3_typography( $typography_elements ){
    if ( class_exists( 'WooCommerce' ) ) {
        $typography_elements['color'][]  = '.product-title';
        $typography_elements['family'][] = '.product-title';
        $typography_elements['size'][]   = '.product-title';
        $typography_elements['size'][]   = 'p.demo_store';
        
        $typography_elements['color'][]  = '.woocommerce-tabs .entry-content h3';
        $typography_elements['family'][] = '.woocommerce-tabs .entry-content h3';
        $typography_elements['weight'][] = '.woocommerce-tabs .entry-content h3';
        
        $typography_elements['color'][]  = '.upsells.products h3';
        $typography_elements['family'][] = '.upsells.products h3';
        $typography_elements['weight'][] = '.upsells.products h3';
        
        $typography_elements['color'][]  = '.related.products h3';
        $typography_elements['family'][] = '.related.products h3';
        $typography_elements['weight'][] = '.related.products h3';          
    }

    return $typography_elements;
}
add_filter( 'avada_h3_typography_elements', 'avada_woocommerce_h3_typography' );

// WooCommerce css classes that inherit Avada's button typography settings
function avada_woocommerce_button_typography( $typography_elements ){
    if ( class_exists( 'WooCommerce' ) ) {
        $typography_elements['family'][] = '.woocommerce .single_add_to_cart_button';
        $typography_elements['family'][] = '.woocommerce button.button';
        $typography_elements['family'][] = '.woocommerce .avada-shipping-calculator-form .button';
        $typography_elements['family'][] = '.woocommerce .checkout #place_order';  //todo 11px
        $typography_elements['family'][] = '.woocommerce .checkout_coupon .button';   //todo 11px
        $typography_elements['family'][] = '.woocommerce .login .button';
        $typography_elements['family'][] = '.woocommerce .register .button';
        $typography_elements['family'][] = '.woocommerce .avada-order-details .order-again .button';
    }

    return $typography_elements;
}
add_filter( 'avada_button_typography_elements', 'avada_woocommerce_button_typography' );

// Omit closing PHP tag to avoid "Headers already sent" issues.
