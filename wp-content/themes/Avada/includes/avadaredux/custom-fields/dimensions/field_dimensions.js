
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

                el.find( '.avadaredux-dimensions-input' ).on(
                    'change', function() {
                        el.find( '#' + $( this ).attr( 'rel' ) ).val( $( this ).val() );
                    }
                );

            }
        );


    };
})( jQuery );
