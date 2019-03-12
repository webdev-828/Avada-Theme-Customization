<?php
/**
 * This file contains typography styles for Fusion Core plugin
 *
 */

// CSS classes that inherit Avada's body typography settings
function avada_fusion_core_body_typography( $typography_elements ) {

    $typography_elements['size'][] = '.counter-box-content';
    $typography_elements['size'][] = '.fusion-alert';
    $typography_elements['size'][] = '.fusion-progressbar .progress-title';
    $typography_elements['family'][] = '.fusion-blog-shortcode .fusion-timeline-date';

    return $typography_elements;
}
add_filter( 'avada_body_typography_elements', 'avada_fusion_core_body_typography' );


// CSS classes that inherit Avada's H3 typography settings
function avada_fusion_core_h3_typography( $typography_elements ) {

    $typography_elements['size'][] = '.fusion-modal .modal-title';
    $typography_elements['color'][] = '.person-author-wrapper span';
    $typography_elements['family'][] = '.fusion-modal .modal-title';
    $typography_elements['family'][] = '.fusion-pricing-table .title-row';
    $typography_elements['family'][] = '.fusion-pricing-table .pricing-row';

    return $typography_elements;
}
add_filter( 'avada_h3_typography_elements', 'avada_fusion_core_h3_typography' );


// CSS classes that inherit Avada's H4 typography settings
function avada_fusion_core_h4_typography( $typography_elements ) {

    $typography_elements['size'][] = '.fusion-person .person-author-wrapper .person-name';
    $typography_elements['size'][] = '.fusion-person .person-author-wrapper .person-title';
    $typography_elements['size'][] = '.person-author-wrapper';
    $typography_elements['size'][] = '.popover .popover-title';
    $typography_elements['size'][] = '.fusion-flip-box .flip-box-heading-back';
    $typography_elements['family'][] = '.popover .popover-title';
    $typography_elements['family'][] = '.fusion-flip-box .flip-box-heading-back';
    $typography_elements['family'][] = '.fusion-tabs .nav-tabs  li .fusion-tab-heading';
    $typography_elements['family'][] = '.fusion-accordian .panel-heading a';
    $typography_elements['family'][] = '.fusion-person .person-desc .person-author .person-author-wrapper';

    return $typography_elements;
}
add_filter( 'avada_h4_typography_elements', 'avada_fusion_core_h4_typography' );

// Omit closing PHP tag to avoid "Headers already sent" issues.
