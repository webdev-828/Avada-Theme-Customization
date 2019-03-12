/* global avadaredux_change */

/*global avadaredux_change, avadaredux*/

(function( $ ) {
    "use strict";

    avadaredux.field_objects = avadaredux.field_objects || {};
    avadaredux.field_objects.repeater = avadaredux.field_objects.repeater || {};

    $( document ).ready(
        function() {

        }
    );

    avadaredux.field_objects.repeater.sort_repeaters = function( selector ) {
        if ( !selector.hasClass( 'avadaredux-container-repeater' ) ) {
            selector = selector.parents( '.avadaredux-container-repeater:first' );
        }

        selector.find( '.avadaredux-repeater-accordion-repeater' ).each(
            function( idx ) {

                var id = $( this ).attr( 'data-sortid' );
                var input = $( this ).find( "input[name*='[" + id + "]']" );
                input.each(
                    function() {
                        $( this ).attr( 'name', $( this ).attr( 'name' ).replace( '[' + id + ']', '[' + idx + ']' ) );
                    }
                );

                var select = $( this ).find( "select[name*='[" + id + "]']" );
                select.each(
                    function() {
                        $( this ).attr( 'name', $( this ).attr( 'name' ).replace( '[' + id + ']', '[' + idx + ']' ) );
                    }
                );
                $( this ).attr( 'data-sortid', idx );

                // Fix the accordian header
                var header = $( this ).find( '.ui-accordion-header' );
                var split = header.attr( 'id' ).split( '-header-' );
                header.attr( 'id', split[0] + '-header-' + idx );
                split = header.attr( 'aria-controls' ).split( '-panel-' );
                header.attr( 'aria-controls', split[0] + '-panel-' + idx );

                // Fix the accordian content
                var content = $( this ).find( '.ui-accordion-content' );
                var split = content.attr( 'id' ).split( '-panel-' );
                content.attr( 'id', split[0] + '-panel-' + idx );
                split = content.attr( 'aria-labelledby' ).split( '-header-' );
                content.attr( 'aria-labelledby', split[0] + '-header-' + idx );

            }
        );
    };


    avadaredux.field_objects.repeater.init = function( selector ) {

        if ( !selector ) {
            selector = $( document ).find( ".avadaredux-group-tab:visible" ).find( '.avadaredux-container-repeater:visible' );
        }

        $( selector ).each(
            function( idx ) {

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

                var parent = el;

                if ( !el.hasClass( 'avadaredux-field-container' ) ) {
                    parent = el.parents( '.avadaredux-field-container:first' );
                }

                var gid = parent.attr( 'data-id' );

                var blank = el.find( '.avadaredux-repeater-accordion-repeater:last-child' );
                avadaredux.repeater[gid].blank = blank.clone().wrap( '<p>' ).parent().html();


                if ( parent.hasClass( 'avadaredux-container-repeater' ) ) {
                    parent.addClass( 'avadaredux-field-init' );
                }

                if ( parent.hasClass( 'avadaredux-field-init' ) ) {
                    parent.removeClass( 'avadaredux-field-init' );
                } else {
                    return;
                }

                var active = false;

                //if ( el.find( '.slide-title' ).length < 2 ) {
                //    active = 0;
                //}

                var accordian = el.find( ".avadaredux-repeater-accordion" ).accordion(
                    {
                        header: "> div > fieldset > h3",
                        collapsible: true,
                        //active: active,
                        activate: function( event, ui ) {
                            $.avadaredux.initFields();
                        },
                        heightStyle: "content",
                        icons: {
                            "header": "ui-icon-plus",
                            "activeHeader": "ui-icon-minus"
                        }
                    }
                );
                if ( avadaredux.repeater[gid].sortable == 1 ) {
                    accordian.sortable(
                        {
                            axis: "y",
                            handle: "h3",
                            connectWith: ".avadaredux-repeater-accordion",
                            placeholder: "ui-state-highlight",
                            start: function( e, ui ) {
                                ui.placeholder.height( ui.item.height() );
                                ui.placeholder.width( ui.item.width() );
                            },
                            stop: function( event, ui ) {
                                // IE doesn't register the blur when sorting
                                // so trigger focusout handlers to remove .ui-state-focus
                                ui.item.children( "h3" ).triggerHandler( "focusout" );

                                avadaredux.field_objects.repeater.sort_repeaters( $( this ) );

                            }
                        }
                    );
                } else {
                    accordian.find( 'h3.ui-accordion-header' ).css( 'cursor', 'pointer' );
                }

                el.find( '.avadaredux-repeater-accordion-repeater .bind_title' ).on(
                    'change keyup', function( event ) {
                        if ( $( event.target ).find( ':selected' ).text().length > 0 ) {
                            var value = $( event.target ).find( ':selected' ).text();
                        } else {
                            var value = $( event.target ).val()
                        }
                        $( this ).closest( '.avadaredux-repeater-accordion-repeater' ).find( '.avadaredux-repeater-header' ).text( value );
                    }
                );

                // Handler to remove the given repeater
                el.find( '.avadaredux-repeaters-remove' ).live(
                    'click', function() {
                        avadaredux_change( $( this ) );
                        var parent = $( this ).parents( '.avadaredux-container-repeater:first' );
                        var gid = parent.attr( 'data-id' );
                        avadaredux.repeater[gid].blank = $( this ).parents( '.avadaredux-repeater-accordion-repeater:first' ).clone(
                            true, true
                        );
                        $( this ).parents( '.avadaredux-repeater-accordion-repeater:first' ).slideUp(
                            'medium', function() {
                                $( this ).remove();
                                avadaredux.field_objects.repeater.sort_repeaters( el );
                                if ( avadaredux.repeater[gid].limit != '' ) {
                                    var count = parent.find( '.avadaredux-repeater-accordion-repeater' ).length;
                                    if ( count < avadaredux.repeater[gid].limit ) {
                                        parent.find( '.avadaredux-repeaters-add' ).removeClass( 'button-disabled' );
                                    }
                                }
                                parent.find( '.avadaredux-repeater-accordion-repeater:last .ui-accordion-header' ).click();
                            }
                        );

                    }
                );

                String.prototype.avadareduxReplaceAll = function( s1, s2 ) {
                    return this.replace(
                        new RegExp( s1.replace( /[.^$*+?()[{\|]/g, '\\$&' ), 'g' ),
                        s2
                    );
                };


                el.find( '.avadaredux-repeaters-add' ).click(
                    function() {

                        if ( $( this ).hasClass( 'button-disabled' ) ) {
                            return;
                        }

                        var parent = $( this ).parent().find( '.avadaredux-repeater-accordion:first' );
                        var count = parent.find( '.avadaredux-repeater-accordion-repeater' ).length;

                        var gid = parent.attr( 'data-id' ); // Group id
                        if ( avadaredux.repeater[gid].limit != '' ) {
                            if ( count >= avadaredux.repeater[gid].limit ) {
                                $( this ).addClass( 'button-disabled' );
                                return;
                            }
                        }
                        count++;

                        var id = parent.find( '.avadaredux-repeater-accordion-repeater' ).size(); // Index number


                        if ( parent.find( '.avadaredux-repeater-accordion-repeater:last' ).find( '.ui-accordion-header' ).hasClass( 'ui-state-active' ) ) {
                            parent.find( '.avadaredux-repeater-accordion-repeater:last' ).find( '.ui-accordion-header' ).click();
                        }

                        var newSlide = parent.find( '.avadaredux-repeater-accordion-repeater:last' ).clone( true, true );

                        if ( newSlide.length == 0 ) {
                            newSlide = avadaredux.repeater[gid].blank;
                        }

                        if ( avadaredux.repeater[gid] ) {
                            avadaredux.repeater[gid].count = el.find( '.avadaredux-repeater-header' ).length;
                            var html = avadaredux.repeater[gid].html.avadareduxReplaceAll( '99999', id );
                            $( newSlide ).find( '.avadaredux-repeater-header' ).text( '' );
                        }

                        newSlide.find( '.ui-accordion-content' ).html( html );
                        // Append to the accordian
                        $( parent ).append( newSlide );
                        // Reorder
                        avadaredux.field_objects.repeater.sort_repeaters( newSlide );
                        // Refresh the JS object
                        var newSlide = $( this ).parent().find( '.avadaredux-repeater-accordion:first' );
                        newSlide.find( '.avadaredux-repeater-accordion-repeater:last .ui-accordion-header' ).click();
                        newSlide.find( '.avadaredux-repeater-accordion-repeater:last .bind_title' ).on(
                            'change keyup', function( event ) {
                                if ( $( event.target ).find( ':selected' ).text().length > 0 ) {
                                    var value = $( event.target ).find( ':selected' ).text();
                                } else {
                                    var value = $( event.target ).val()
                                }
                                $( this ).closest( '.avadaredux-repeater-accordion-repeater' ).find( '.avadaredux-repeater-header' ).text( value );
                            }
                        );
                        if ( avadaredux.repeater[gid].limit > 0 && count >= avadaredux.repeater[gid].limit ) {
                            $( this ).addClass( 'button-disabled' );
                        }

                    }
                );
            }
        );
    };
})( jQuery );
