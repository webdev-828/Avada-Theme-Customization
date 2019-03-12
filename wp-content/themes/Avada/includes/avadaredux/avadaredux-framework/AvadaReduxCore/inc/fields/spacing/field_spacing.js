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

                el.find( ".avadaredux-spacing-units" ).select2( default_params );

                el.find( '.avadaredux-spacing-input' ).on(
                    'change', function() {
                        var units = $( this ).parents( '.avadaredux-field:first' ).find( '.field-units' ).val();

                        if ( $( this ).parents( '.avadaredux-field:first' ).find( '.avadaredux-spacing-units' ).length !== 0 ) {
                            units = $( this ).parents( '.avadaredux-field:first' ).find( '.avadaredux-spacing-units option:selected' ).val();
                        }

                        var value = $( this ).val();

                        if ( typeof units !== 'undefined' && value ) {
                            value += units;
                        }

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

                el.find( '.avadaredux-spacing-units' ).on(
                    'change', function() {
                        $( this ).parents( '.avadaredux-field:first' ).find( '.avadaredux-spacing-input' ).change();
                    }
                );
            }
        );
    };
})( jQuery );
