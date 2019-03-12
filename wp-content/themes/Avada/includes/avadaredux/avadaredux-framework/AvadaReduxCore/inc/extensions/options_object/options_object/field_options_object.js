/*global avadaredux_change, avadaredux*/

(function( $ ) {
    "use strict";

    avadaredux.field_objects                 = avadaredux.field_objects || {};
    avadaredux.field_objects.options_object  = avadaredux.field_objects.options_object || {};

//    $( document ).ready(
//        function() {
//            avadaredux.field_objects.import_export.init();
//        }
//    );

    avadaredux.field_objects.options_object.init = function( selector ) {

        if ( !selector ) {
            selector = $( document ).find( '.avadaredux-container-options_object' );
        }

        var parent = selector;

        if ( !selector.hasClass( 'avadaredux-field-container' ) ) {
            parent = selector.parents( '.avadaredux-field-container:first' );
        }

        if ( parent.hasClass( 'avadaredux-field-init' ) ) {
            parent.removeClass( 'avadaredux-field-init' );
        } else {
            return;
        }

        $( '#consolePrintObject' ).on(
            'click', function( e ) {
                e.preventDefault();
                console.log( $.parseJSON( $( "#avadaredux-object-json" ).html() ) );
            }
        );

        if ( typeof jsonView === 'function' ) {
            jsonView( '#avadaredux-object-json', '#avadaredux-object-browser' );
        }
    };
})( jQuery );
