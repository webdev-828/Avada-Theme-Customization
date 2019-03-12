/*
 Field Border (border)
 */

/*global avadaredux_change, wp, avadaredux*/

(function( $ ) {
    "use strict";

    avadaredux.field_objects = avadaredux.field_objects || {};
    avadaredux.field_objects.border = avadaredux.field_objects.border || {};

    avadaredux.field_objects.border.init = function( selector ) {
        if ( !selector ) {
            selector = $( document ).find( ".avadaredux-group-tab:visible" ).find( '.avadaredux-container-border:visible' );
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
                el.find( ".avadaredux-border-top, .avadaredux-border-right, .avadaredux-border-bottom, .avadaredux-border-left, .avadaredux-border-all" ).numeric(
                    {
                        allowMinus: false
                    }
                );

                var default_params = {
                    triggerChange: true,
                    allowClear: true
                };

                var select2_handle = el.find( '.avadaredux-container-border' ).find( '.select2_params' );

                if ( select2_handle.size() > 0 ) {
                    var select2_params = select2_handle.val();

                    select2_params = JSON.parse( select2_params );
                    default_params = $.extend( {}, default_params, select2_params );
                }

                el.find( ".avadaredux-border-style" ).select2( default_params );

                el.find( '.avadaredux-border-input' ).on(
                    'change', function() {
                        var units = $( this ).parents( '.avadaredux-field:first' ).find( '.field-units' ).val();
                        if ( $( this ).parents( '.avadaredux-field:first' ).find( '.avadaredux-border-units' ).length !== 0 ) {
                            units = $( this ).parents( '.avadaredux-field:first' ).find( '.avadaredux-border-units option:selected' ).val();
                        }
                        var value = $( this ).val();
                        if ( typeof units !== 'undefined' && value ) {
                            value += units;
                        }
                        if ( $( this ).hasClass( 'avadaredux-border-all' ) ) {
                            $( this ).parents( '.avadaredux-field:first' ).find( '.avadaredux-border-value' ).each(
                                function() {
                                    $( this ).val( value );
                                }
                            );
                        } else {
                            $( '#' + $( this ).attr( 'rel' ) ).val( value );
                        }
                    }
                );

                el.find( '.avadaredux-border-units' ).on(
                    'change', function() {
                        $( this ).parents( '.avadaredux-field:first' ).find( '.avadaredux-border-input' ).change();
                    }
                );

                el.find( '.avadaredux-color-init' ).wpColorPicker(
                    {
                        change: function( e, ui ) {
                            $( this ).val( ui.color.toString() );
                            avadaredux_change( $( this ) );
                            el.find( '#' + e.target.getAttribute( 'data-id' ) + '-transparency' ).removeAttr( 'checked' );
                        },

                        clear: function( e, ui ) {
                            $( this ).val( ui.color.toString() );
                            avadaredux_change( $( this ).parent().find( '.avadaredux-color-init' ) );
                        }
                    }
                );

                el.find( '.avadaredux-color' ).on(
                    'keyup', function() {
                        var color = colorValidate( this );

                        if ( color && color !== $( this ).val() ) {
                            $( this ).val( color );
                        }
                    }
                );

                // Replace and validate field on blur
                el.find( '.avadaredux-color' ).on(
                    'blur', function() {
                        var value = $( this ).val();

                        if ( colorValidate( this ) === value ) {
                            if ( value.indexOf( "#" ) !== 0 ) {
                                $( this ).val( $( this ).data( 'oldcolor' ) );
                            }
                        }
                    }
                );

                // Store the old valid color on keydown
                el.find( '.avadaredux-color' ).on(
                    'keydown', function() {
                        $( this ).data( 'oldkeypress', $( this ).val() );
                    }
                );
            }
        );
    };
})( jQuery );
