/*
 Field Accordion (accordion)
 */

/*global jQuery, document, avadaredux*/

(function( $ ) {
    'use strict';

    avadaredux.field_objects = avadaredux.field_objects || {};
    avadaredux.field_objects.accordion = avadaredux.field_objects.accordion || {};

    avadaredux.field_objects.accordion.init = function( selector ) {
        if ( !selector ) {
            selector = $( document ).find( ".avadaredux-group-tab:visible" ).find( '.avadaredux-container-accordion:visible' );
        }

        $( selector ).each(
            function() {
                var el      = $( this );
                var parent  = el;

                if ( !el.hasClass( 'avadaredux-field-container' ) ) {
                    parent = el.parents( '.avadaredux-field-container:first' );
                }
                if ( parent.hasClass( 'avadaredux-field-init' ) ) {
                    parent.removeClass( 'avadaredux-field-init' );
                } else {
                    return;
                }

                var $id = el.attr( 'data-id' );
                $( '#accordion-' + $id + '-marker' ).parents( 'tr:first' ).css( {display: 'none'} ).prev( 'tr' ).css(
                    'border-bottom', 'none'
                );

                var group = $( '#accordion-' + $id + '-marker' ).parents( '.avadaredux-group-tab:first' );

                if ( !group.hasClass( 'accordionsChecked' ) ) {
                    group.addClass( 'accordionsChecked' );
                    var test = group.find( '.avadaredux-accordion-indent-start h3' );

                    $.each( test, function( key, value ) {
                            $( value ).css( 'margin-top', '20px' )
                    });

                    if ( group.find( 'h3:first' ).css( 'margin-top' ) == "20px" ) {
                        group.find( 'h3:first' ).css( 'margin-top', '0' );
                    }

                    var accordionMarker = $('#accordion-' + $id + '-marker');
                    var openIcon        = accordionMarker.data('open-icon');  // plus
                    var closeIcon       = accordionMarker.data('close-icon'); // minus

                    group.find( '.avadaredux-accordion-field' ).click(
                        function( e ) {
                            e.preventDefault();
                            var id = $( this ).attr( 'id' );
                            if ( $( '#accordion-table-' + id ).closest( 'div' ).is( ':visible' ) ) {
                                $( this ).find( '.el' ).removeClass( closeIcon ).addClass( openIcon );
                                $( '#accordion-table-' + id ).closest( 'div' ).slideUp();
                            } else {
                                $( '#accordion-table-' + id ).closest( 'div' ).slideDown();
                                $.avadaredux.initFields();
                                $( this ).find( '.el' ).removeClass( openIcon ).addClass( closeIcon );
                            }
                        }
                    );

                    group.find( '.avadaredux-accordion-field' ).each(
                        function() {
                            var position = $( this ).data( 'position' );

                            if ( position === 'start' ) {
                                var devMode = Boolean($( this ).data( 'dev-mode' ));

                                if (devMode === true) {
                                    var ver         = $( this ).data( 'version' );
                                    var dev_html    = $('div.avadaredux-timer').html();

                                    if (dev_html !== undefined) {
                                        var pos = dev_html.indexOf('Accordion Field');

                                        if (pos === -1) {
                                            $('div.avadaredux-timer').html(dev_html + '<br/>Accordion Field extension v.' + ver);
                                        }
                                    }
                                }

                                var id      = $( this ).attr( 'id' );
                                var state   = Boolean($( this ).data( 'state' ));

                                $( '#accordion-table-' + id ).wrapAll( '<div class="avadaredux-accordian-wrap"/>' );

                                if ( state === false ) {
                                    $( '#accordion-table-' + id ).closest( 'div' ).hide();
                                } else {
                                    $( this ).find( '.el' ).removeClass( openIcon ).addClass( closeIcon );
                                }
                            }
                        }
                    );
                }
            }
        );
    };
})( jQuery );
