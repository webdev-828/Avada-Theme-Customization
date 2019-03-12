/*global jQuery, document, avadaredux_change, avadaredux*/

(function( $ ) {
    "use strict";

    avadaredux.field_objects = avadaredux.field_objects || {};
    avadaredux.field_objects.sortable = avadaredux.field_objects.sortable || {};

    var scroll = '';

    avadaredux.field_objects.sortable.init = function( selector ) {

        if ( !selector ) {
            selector = $( document ).find( ".avadaredux-group-tab:visible" ).find( '.avadaredux-container-sortable:visible' );
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
                el.find( ".avadaredux-sortable" ).sortable(
                    {
                        handle: ".drag",
                        placeholder: "placeholder",
                        opacity: 0.7,
                        scroll: false,
                        out: function( event, ui ) {
                            if ( !ui.helper ) return;
                            if ( ui.offset.top > 0 ) {
                                scroll = 'down';
                            } else {
                                scroll = 'up';
                            }
                            avadaredux.field_objects.sortable.scrolling( $( this ).parents( '.avadaredux-field-container:first' ) );
                        },

                        over: function( event, ui ) {
                            scroll = '';
                        },

                        deactivate: function( event, ui ) {
                            scroll = '';
                        },

                        update: function() {
                            avadaredux_change( $( this ) );
                        }
                    }
                );

                el.find( '.checkbox_sortable' ).on(
                    'click', function() {
                        if ( $( this ).is( ":checked" ) ) {
                            el.find( '#' + $( this ).attr( 'rel' ) ).val( 1 );
                        } else {
                            el.find( '#' + $( this ).attr( 'rel' ) ).val( '' );
                        }
                    }
                );
            }
        );
    };

    avadaredux.field_objects.sortable.scrolling = function( selector ) {
        if (selector === undefined) {
            return;
        }

        var $scrollable = selector.find( ".avadaredux-sorter" );

        if ( scroll == 'up' ) {
            $scrollable.scrollTop( $scrollable.scrollTop() - 20 );
            setTimeout( avadaredux.field_objects.sortable.scrolling, 50 );
        } else if ( scroll == 'down' ) {
            $scrollable.scrollTop( $scrollable.scrollTop() + 20 );
            setTimeout( avadaredux.field_objects.sortable.scrolling, 50 );
        }
    };

})( jQuery );
