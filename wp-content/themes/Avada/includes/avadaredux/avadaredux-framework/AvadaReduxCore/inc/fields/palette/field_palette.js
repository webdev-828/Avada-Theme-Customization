/*
 Field Palette (color)
 */

/*global jQuery, document, avadaredux_change, avadaredux*/

(function( $ ) {
    'use strict';

    avadaredux.field_objects         = avadaredux.field_objects || {};
    avadaredux.field_objects.palette = avadaredux.field_objects.palette || {};

    avadaredux.field_objects.palette.init = function( selector ) {

        if ( !selector ) {
            selector = $( document ).find( ".avadaredux-group-tab:visible" ).find( '.avadaredux-container-palette:visible' );
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

                el.find( '.buttonset' ).each(
                    function() {
                        $( this ).buttonset();
                    }
                );

//                el.find('.avadaredux-palette-set').click(
//                    function(){
//                        console.log($(this).val());
//                    }
//                )
            }
        );
    };
})( jQuery );
