/*global avadaredux_change, wp, avadaredux*/

(function( $ ) {
    "use strict";

    avadaredux.field_objects = avadaredux.field_objects || {};
    avadaredux.field_objects.slides = avadaredux.field_objects.slides || {};

    $( document ).ready(
        function() {
            //avadaredux.field_objects.slides.init();
        }
    );

    avadaredux.field_objects.slides.init = function( selector ) {

        if ( !selector ) {
            selector = $( document ).find( ".avadaredux-group-tab:visible" ).find( '.avadaredux-container-slides:visible' );
        }

        $( selector ).each(
            function() {
                var el = $( this );

                avadaredux.field_objects.media.init(el);

                var parent = el;
                if ( !el.hasClass( 'avadaredux-field-container' ) ) {
                    parent = el.parents( '.avadaredux-field-container:first' );
                }
                if ( parent.is( ":hidden" ) ) { // Skip hidden fields
                    return;
                }

                if ( parent.hasClass( 'avadaredux-container-slides' ) ) {
                    parent.addClass( 'avadaredux-field-init' );
                }

                if ( parent.hasClass( 'avadaredux-field-init' ) ) {
                    parent.removeClass( 'avadaredux-field-init' );
                } else {
                    return;
                }

                el.find( '.avadaredux-slides-remove' ).live(
                    'click', function() {
                        avadaredux_change( $( this ) );

                        $( this ).parent().siblings().find( 'input[type="text"]' ).val( '' );
                        $( this ).parent().siblings().find( 'textarea' ).val( '' );
                        $( this ).parent().siblings().find( 'input[type="hidden"]' ).val( '' );

                        var slideCount = $( this ).parents( '.avadaredux-container-slides:first' ).find( '.avadaredux-slides-accordion-group' ).length;

                        if ( slideCount > 1 ) {
                            $( this ).parents( '.avadaredux-slides-accordion-group:first' ).slideUp(
                                'medium', function() {
                                    $( this ).remove();
                                }
                            );
                        } else {
                            var content_new_title = $( this ).parent( '.avadaredux-slides-accordion' ).data( 'new-content-title' );

                            $( this ).parents( '.avadaredux-slides-accordion-group:first' ).find( '.remove-image' ).click();
                            $( this ).parents( '.avadaredux-container-slides:first' ).find( '.avadaredux-slides-accordion-group:last' ).find( '.avadaredux-slides-header' ).text( content_new_title );
                        }
                    }
                );

                //el.find( '.avadaredux-slides-add' ).click(
                el.find( '.avadaredux-slides-add' ).off('click').click(
                    function() {
                        var newSlide = $( this ).prev().find( '.avadaredux-slides-accordion-group:last' ).clone( true );

                        var slideCount = $( newSlide ).find( '.slide-title' ).attr( "name" ).match( /[0-9]+(?!.*[0-9])/ );
                        var slideCount1 = slideCount * 1 + 1;

                        $( newSlide ).find( 'input[type="text"], input[type="hidden"], textarea' ).each(
                            function() {

                                $( this ).attr(
                                    "name", jQuery( this ).attr( "name" ).replace( /[0-9]+(?!.*[0-9])/, slideCount1 )
                                ).attr( "id", $( this ).attr( "id" ).replace( /[0-9]+(?!.*[0-9])/, slideCount1 ) );
                                $( this ).val( '' );
                                if ( $( this ).hasClass( 'slide-sort' ) ) {
                                    $( this ).val( slideCount1 );
                                }
                            }
                        );

                        var content_new_title = $( this ).prev().data( 'new-content-title' );

                        $( newSlide ).find( '.screenshot' ).removeAttr( 'style' );
                        $( newSlide ).find( '.screenshot' ).addClass( 'hide' );
                        $( newSlide ).find( '.screenshot a' ).attr( 'href', '' );
                        $( newSlide ).find( '.remove-image' ).addClass( 'hide' );
                        $( newSlide ).find( '.avadaredux-slides-image' ).attr( 'src', '' ).removeAttr( 'id' );
                        $( newSlide ).find( 'h3' ).text( '' ).append( '<span class="avadaredux-slides-header">' + content_new_title + '</span><span class="ui-accordion-header-icon ui-icon ui-icon-plus"></span>' );
                        $( this ).prev().append( newSlide );
                    }
                );

                el.find( '.slide-title' ).keyup(
                    function( event ) {
                        var newTitle = event.target.value;
                        $( this ).parents().eq( 3 ).find( '.avadaredux-slides-header' ).text( newTitle );
                    }
                );


                el.find( ".avadaredux-slides-accordion" )
                    .accordion(
                    {
                        header: "> div > fieldset > h3",
                        collapsible: true,
                        active: false,
                        heightStyle: "content",
                        icons: {
                            "header": "ui-icon-plus",
                            "activeHeader": "ui-icon-minus"
                        }
                    }
                )
                    .sortable(
                    {
                        axis: "y",
                        handle: "h3",
                        connectWith: ".avadaredux-slides-accordion",
                        start: function( e, ui ) {
                            ui.placeholder.height( ui.item.height() );
                            ui.placeholder.width( ui.item.width() );
                        },
                        placeholder: "ui-state-highlight",
                        stop: function( event, ui ) {
                            // IE doesn't register the blur when sorting
                            // so trigger focusout handlers to remove .ui-state-focus
                            ui.item.children( "h3" ).triggerHandler( "focusout" );
                            var inputs = $( 'input.slide-sort' );
                            inputs.each(
                                function( idx ) {
                                    $( this ).val( idx );
                                }
                            );
                        }
                    }
                );
            }
        );
    };
})( jQuery );
