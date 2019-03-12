/*global avadaredux*/

(function( $ ) {
    "use strict";

    avadaredux.field_objects = avadaredux.field_objects || {};
    avadaredux.field_objects.spacing = avadaredux.field_objects.spacing || {};

    $( document ).ready(
        function() {
            //avadaredux.field_objects.spacing.init();
        }
    );

    avadaredux.field_objects.spacing.init = function( selector ) {

        if ( !selector ) {
            selector = $( document ).find( ".avadaredux-group-tab:visible" ).find( '.avadaredux-container-spacing:visible' );
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

                el.find( '.avadaredux-spacing-input' ).on(
                    'change', function() {

                        var value = $( this ).val();

                        if ( $( this ).hasClass( 'avadaredux-spacing-all' ) ) {
                            $( this ).parents( '.avadaredux-field:first' ).find( '.avadaredux-spacing-value' ).each(
                                function() {
                                    $( this ).val( value );
                                }
                            );
                        } else {
                            $( '#' + $( this ).attr( 'rel' ) ).val( value );
                        }
                    }
                );
            }
        );
    };
})( jQuery );
