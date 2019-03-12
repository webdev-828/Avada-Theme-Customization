jQuery(function($) {
    $(document).ready(function() {

        $('.avadaredux-container').each( function() {
            if ( ! $(this).hasClass('avadaredux-no-sections') ) {
                $(this).find('.avadaredux-main').prepend('<span class="dashicons dashicons-search"></span><input class="avadaredux_field_search" name="" type="text" placeholder="' + avadareduxsearch + '"/>');
            }
        } );

        $( '.avadaredux_field_search' ).keypress( function (evt) {
            //Deterime where our character code is coming from within the event
            var charCode = evt.charCode || evt.keyCode;
            if (charCode  == 13) { //Enter key's keycode
                return false;
            }
        } );

        var
        avadaredux_container = $('.avadaredux-container'),
        avadaredux_option_tab_extras = avadaredux_container.find('.avadaredux-section-field, .avadaredux-info-field, .avadaredux-notice-field, .avadaredux-container-group, .avadaredux-section-desc, .avadaredux-group-tab h3, .avadaredux-accordion-field'),
        search_targets = avadaredux_container.find( '.form-table tr, .avadaredux-group-tab'),
        avadaredux_menu_items = $('.avadaredux-group-menu .avadaredux-group-tab-link-li');

        jQuery('.avadaredux_field_search').typeWatch({

            callback:function( searchString ){
                searchString = searchString.toLowerCase();
                var searchArray = searchString.split(',');

                if ( searchString !== '' && searchString !== null && typeof searchString !== 'undefined' && searchString.length > 2 ) {
                    // Add a class to the avadaredux container
                    $('.avadaredux-container').addClass('avada-avadaredux-search');
                    // Show accordion content / options
                    $('.avadaredux-accordian-wrap').show();

                    // Hide option fields and tabs
                    avadaredux_option_tab_extras.hide();
                    search_targets.hide();

                    // Show matching results
                    search_targets.filter( function () {
                        var
                        item = $(this),
                        matchFound = true,
                        text = item.find('.avadaredux_field_th').text().toLowerCase();

                        if ( ! text || text == '' ) {
                            return false;
                        }

                        $.each( searchArray, function ( i, searchStr ) {
                            if ( text.indexOf( searchStr ) == -1 ) {
                                matchFound = false;
                            }
                        });

                        if ( matchFound ) {
                            item.show();
                        }

                        return matchFound;

                    } ).show();

                    // Initialize option fields
                    $.avadaredux.initFields();

                } else {
                    // remove the search class from .avadaredux-container if it exists
                    $('.avadaredux-container').removeClass('avada-avadaredux-search');

                    // Get active options tab id
                    var tab = $.cookie( 'avadaredux_current_tab' );

                    // Show the last tab that was active before the search
                    $('.avadaredux-group-tab').hide();
                    $('.avadaredux-accordian-wrap').hide();
                    avadaredux_option_tab_extras.show();
                    $('.form-table tr').show();
                    $('.form-table tr.hide').hide();
                    $('.avadaredux-notice-field.hide').hide();
                    $( '#' + tab + '_section_group' ).show();

                }

            },

            wait: 800,
            highlight: false,
            captureLength: 0,

        } );

    } );

} );
