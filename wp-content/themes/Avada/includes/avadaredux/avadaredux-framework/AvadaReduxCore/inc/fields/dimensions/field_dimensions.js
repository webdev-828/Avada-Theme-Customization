
/*global jQuery, document, avadaredux*/

(function( $ ) {
    "use strict";

    avadaredux.field_objects = avadaredux.field_objects || {};
    avadaredux.field_objects.dimensions = avadaredux.field_objects.dimensions || {};

    $( document ).ready(
        function() {
            //avadaredux.field_objects.dimensions.init();
        }
    );

    avadaredux.field_objects.dimensions.init = function( selector ) {

        if ( !selector ) {
            selector = $( document ).find( '.avadaredux-container-dimensions:visible' );
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

                var select2_handle = el.find( '.select2_params' );
                if ( select2_handle.size() > 0 ) {
                    var select2_params = select2_handle.val();

                    select2_params = JSON.parse( select2_params );
                    default_params = $.extend( {}, default_params, select2_params );
                }

                el.find( ".avadaredux-dimensions-units" ).select2( default_params );

                el.find( '.avadaredux-dimensions-input' ).on(
                    'change', function() {
                        var units = $( this ).parents( '.avadaredux-field:first' ).find( '.field-units' ).val();
                        if ( $( this ).parents( '.avadaredux-field:first' ).find( '.avadaredux-dimensions-units' ).length !== 0 ) {
                            units = $( this ).parents( '.avadaredux-field:first' ).find( '.avadaredux-dimensions-units option:selected' ).val();
                        }
                        if ( typeof units !== 'undefined' ) {
                            el.find( '#' + $( this ).attr( 'rel' ) ).val( $( this ).val() + units );
                        } else {
                            el.find( '#' + $( this ).attr( 'rel' ) ).val( $( this ).val() );
                        }
                    }
                );

                el.find( '.avadaredux-dimensions-units' ).on(
                    'change', function() {
                        $( this ).parents( '.avadaredux-field:first' ).find( '.avadaredux-dimensions-input' ).change();
                    }
                );
            }
        );


    };
})( jQuery );
