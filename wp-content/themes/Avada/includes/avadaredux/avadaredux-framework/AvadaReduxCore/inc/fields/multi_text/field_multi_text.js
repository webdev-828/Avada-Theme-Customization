/*global avadaredux_change, avadaredux*/

(function( $ ) {
    "use strict";

    avadaredux.field_objects = avadaredux.field_objects || {};
    avadaredux.field_objects.multi_text = avadaredux.field_objects.multi_text || {};

    $( document ).ready(
        function() {
            //avadaredux.field_objects.multi_text.init();
        }
    );

    avadaredux.field_objects.multi_text.init = function( selector ) {

        if ( !selector ) {
            selector = $( document ).find( '.avadaredux-container-multi_text:visible' );
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
                el.find( '.avadaredux-multi-text-remove' ).live(
                    'click', function() {
                        avadaredux_change( $( this ) );
                        $( this ).prev( 'input[type="text"]' ).val( '' );
                        $( this ).parent().slideUp(
                            'medium', function() {
                                $( this ).remove();
                            }
                        );
                    }
                );

                el.find( '.avadaredux-multi-text-add' ).click(
                    function() {
                        var number = parseInt( $( this ).attr( 'data-add_number' ) );
                        var id = $( this ).attr( 'data-id' );
                        var name = $( this ).attr( 'data-name' );
                        for ( var i = 0; i < number; i++ ) {
                            var new_input = $( '#' + id + ' li:last-child' ).clone();
                            el.find( '#' + id ).append( new_input );
                            el.find( '#' + id + ' li:last-child' ).removeAttr( 'style' );
                            el.find( '#' + id + ' li:last-child input[type="text"]' ).val( '' );
                            el.find( '#' + id + ' li:last-child input[type="text"]' ).attr( 'name', name );
                        }
                    }
                );
            }
        );
    };
})( jQuery );
