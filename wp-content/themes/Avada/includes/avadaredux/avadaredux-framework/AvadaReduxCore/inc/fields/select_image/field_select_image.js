/*global avadaredux_change, avadaredux*/

(function( $ ) {
    "use strict";

    avadaredux.field_objects = avadaredux.field_objects || {};
    avadaredux.field_objects.select_image = avadaredux.field_objects.select_image || {};

    $( document ).ready(
        function() {
            //avadaredux.field_objects.select_image.init();
        }
    );

    avadaredux.field_objects.select_image.init = function( selector ) {

        if ( !selector ) {
            selector = $( document ).find( ".avadaredux-group-tab:visible" ).find( '.avadaredux-container-select_image:visible' );
        }

        $( selector ).each(
            function() {
                var el = $( this );
                var parent = el;
                if ( !el.hasClass( 'avadaredux-field-container' ) ) {
                    parent = el.parents( '.avadaredux-field-container:first' );
                }
                if ( parent.is( ":hidden" ) ) { // Skip hidden fields
                    return;
                }
                if ( parent.hasClass( 'avadaredux-field-init' ) ) {
                    parent.removeClass( 'avadaredux-field-init' );
                } else {
                    return;
                }
                var default_params = {
                    width: 'resolve',
                    triggerChange: true,
                    allowClear: true
                };

                var select2_handle = el.find( '.avadaredux-container-select_image' ).find( '.select2_params' );

                if ( select2_handle.size() > 0 ) {
                    var select2_params = select2_handle.val();

                    select2_params = JSON.parse( select2_params );
                    default_params = $.extend( {}, default_params, select2_params );
                }

                el.find( 'select.avadaredux-select-images' ).select2( default_params );

                el.find( '.avadaredux-select-images' ).on(
                    'change', function() {
                        var preview = $( this ).parents( '.avadaredux-field:first' ).find( '.avadaredux-preview-image' );

                        if ( $( this ).val() === "" ) {
                            preview.fadeOut(
                                'medium', function() {
                                    preview.attr( 'src', '' );
                                }
                            );
                        } else {
                            preview.attr( 'src', $( this ).val() );
                            preview.fadeIn().css( 'visibility', 'visible' );
                        }
                    }
                );
            }
        );
    };
})( jQuery );
