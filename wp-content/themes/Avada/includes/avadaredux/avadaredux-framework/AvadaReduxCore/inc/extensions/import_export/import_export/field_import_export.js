/*global jQuery, document, avadaredux*/

(function( $ ) {
    "use strict";

    avadaredux.field_objects = avadaredux.field_objects || {};
    avadaredux.field_objects.import_export = avadaredux.field_objects.import_export || {};

    avadaredux.field_objects.import_export.init = function( selector ) {
        if ( !selector ) {
            selector = $( document ).find( ".avadaredux-group-tab:visible" ).find( '.avadaredux-container-import_export:visible' );
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
                el.each(
                    function() {
                        $( '#avadaredux-import' ).click(
                            function( e ) {
                                if ( $( '#import-code-value' ).val() === "" && $( '#import-link-value' ).val() === "" ) {
                                    e.preventDefault();
                                    return false;
                                }
                                window.onbeforeunload = null;
                                avadaredux.args.ajax_save = false;
                            }
                        );

                        $( this ).find( '#avadaredux-import-code-button' ).click(
                            function() {
                                var $el = $( '#avadaredux-import-code-wrapper' );
                                if ( $( '#avadaredux-import-link-wrapper' ).is( ':visible' ) ) {
                                    $( '#import-link-value' ).text( '' );
                                    $( '#avadaredux-import-link-wrapper' ).slideUp(
                                        'fast', function() {
                                            $el.slideDown(
                                                'fast', function() {
                                                    $( '#import-code-value' ).focus();
                                                }
                                            );
                                        }
                                    );
                                } else {
                                    if ( $el.is( ':visible' ) ) {
                                        $el.slideUp();
                                    } else {
                                        $el.slideDown(
                                            'medium', function() {
                                                $( '#import-code-value' ).focus();
                                            }
                                        );
                                    }
                                }
                            }
                        );

                        $( this ).find( '#avadaredux-import-link-button' ).click(
                            function() {
                                var $el = $( '#avadaredux-import-link-wrapper' );
                                if ( $( '#avadaredux-import-code-wrapper' ).is( ':visible' ) ) {
                                    $( '#import-code-value' ).text( '' );
                                    $( '#avadaredux-import-code-wrapper' ).slideUp(
                                        'fast', function() {
                                            $el.slideDown(
                                                'fast', function() {
                                                    $( '#import-link-value' ).focus();
                                                }
                                            );
                                        }
                                    );
                                } else {
                                    if ( $el.is( ':visible' ) ) {
                                        $el.slideUp();
                                    } else {
                                        $el.slideDown(
                                            'medium', function() {
                                                $( '#import-link-value' ).focus();
                                            }
                                        );
                                    }
                                }
                            }
                        );

                        $( this ).find( '#avadaredux-export-code-copy' ).click(
                            function() {
                                var $el = $( '#avadaredux-export-code' );
                                if ( $( '#avadaredux-export-link-value' ).is( ':visible' ) ) {
                                    $( '#avadaredux-export-link-value' ).slideUp(
                                        'fast', function() {
                                            $el.slideDown(
                                                'medium', function() {
                                                    var options = avadaredux.options;
                                                    options['avadaredux-backup'] = 1;
                                                    $( this ).text( JSON.stringify( options ) ).focus().select();
                                                }
                                            );
                                        }
                                    );
                                } else {
                                    if ( $el.is( ':visible' ) ) {
                                        $el.slideUp().text( '' );
                                    } else {
                                        $el.slideDown(
                                            'medium', function() {
                                                var options = avadaredux.options;
                                                options['avadaredux-backup'] = 1;
                                                $( this ).text( JSON.stringify( options ) ).focus().select();
                                            }
                                        );
                                    }
                                }
                            }
                        );

                        $( this ).find( 'textarea' ).focusout(
                            function() {
                                var $id = $( this ).attr( 'id' );
                                var $el = $( this );
                                var $container = $el;
                                if ( $id == "import-link-value" || $id == "import-code-value" ) {
                                    $container = $( this ).parent();
                                }
                                $container.slideUp(
                                    'medium', function() {
                                        if ( $id != "avadaredux-export-link-value" ) {
                                            $el.text( '' );
                                        }
                                    }
                                );
                            }
                        );


                        $( this ).find( '#avadaredux-export-link' ).click(
                            function() {
                                var $el = $( '#avadaredux-export-link-value' );
                                if ( $( '#avadaredux-export-code' ).is( ':visible' ) ) {
                                    $( '#avadaredux-export-code' ).slideUp(
                                        'fast', function() {
                                            $el.slideDown().focus().select();
                                        }
                                    );
                                } else {
                                    if ( $el.is( ':visible' ) ) {
                                        $el.slideUp();
                                    } else {
                                        $el.slideDown(
                                            'medium', function() {
                                                $( this ).focus().select();
                                            }
                                        );
                                    }

                                }
                            }
                        );

                        var textBox1 = document.getElementById( "avadaredux-export-code" );
                        textBox1.onfocus = function() {
                            textBox1.select();
                            // Work around Chrome's little problem
                            textBox1.onmouseup = function() {
                                // Prevent further mouseup intervention
                                textBox1.onmouseup = null;
                                return false;
                            };
                        };
                        var textBox2 = document.getElementById( "import-code-value" );
                        textBox2.onfocus = function() {
                            textBox2.select();
                            // Work around Chrome's little problem
                            textBox2.onmouseup = function() {
                                // Prevent further mouseup intervention
                                textBox2.onmouseup = null;
                                return false;
                            };
                        };

                    }
                );
            }
        );
    };
})( jQuery );


