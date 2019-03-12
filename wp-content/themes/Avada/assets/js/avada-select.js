jQuery(window).load(function() {
    // Avada dropdown styles
    if ( Number( js_local_vars.avada_styles_dropdowns ) == 1 ) {

		jQuery( '.woocommerce-billing-fields, .woocommerce-shipping-fields' ).addClass( 'avada-select' );

        if ( jQuery( '.woocommerce.widget_product_categories select' ).length ) {
            jQuery( '.woocommerce.widget_product_categories select' ).wrap( '<p class="avada-select-parent"></p>' ).after( '<div class="select-arrow">&#xe61f;</div>' );
        }

        jQuery( '.cart-collaterals select#calc_shipping_country, .widget_layered_nav select' ).wrap( '<p class="avada-select-parent"></p>' ).after( '<div class="select-arrow">&#xe61f;</div>' );
        jQuery( '.cart-collaterals select#calc_shipping_state' ).after( '<div class="select-arrow">&#xe61f;</div>' );

        setTimeout(
            function() {
                // Billing address - Only add styling if woocommerce enhanced country selects are disabled
                if ( ! jQuery( '#billing_country_field .chosen-container' ).length && ! jQuery( '#billing_country_field .select2-container' ).length ) {
                    // Wrap the country select
                    jQuery( '#billing_country_field select.country_select' ).wrap( '<p class="avada-select-parent"></p>' ).after( '<span class="select-arrow">&#xe61f;</span>' );

                    // If there is a state select for the initially selected country, wrap it
                    if ( jQuery( '#billing_state_field select.state_select' ).length && ! jQuery( '#billing_state_field .chosen-container' ).length && ! jQuery( '#billing_state_field .select2-container' ).length ) {
                        jQuery( '#billing_state_field' ).addClass( 'avada-select-parent' ).append( '<div class="select-arrow">&#xe61f;</div>' );
                    }

                    // When the country is changed
                    jQuery( "#billing_country" ).change(
                        function() {
                            // Timeout is needed that woocommerce js can kick in first
                            setTimeout(
                                function() {
                                    // If the new country has no state field at all or if it is just an input, undo custom styles
                                    if ( jQuery( '#billing_state_field input#billing_state' ).length || jQuery( '#billing_state_field' ).is( ':hidden' ) ) {
                                        jQuery( '#billing_state_field .select-arrow' ).remove();
                                        jQuery( '#billing_state_field' ).removeClass( 'avada-select-parent' );
                                    }

                                    // If the new country has a state select
                                    if ( jQuery( '#billing_state_field select.state_select' ).length ) {
                                        // Add the correct wrapper class (always needed due to woocommerce classes reset)
                                        jQuery( '#billing_state_field' ).addClass( 'avada-select-parent' );

                                        // If the last country wasn't already having a state select, add the arrow container and calculate dimensions
                                        if ( !jQuery( '#billing_state_field .select-arrow' ).length ) {
                                            jQuery( '#billing_state_field' ).append( '<div class="select-arrow">&#xe61f;</div>' );

                                            calc_select_arrow_dimensions();
                                        }
                                    }
                                }, 1
                            );
                        }
                    );
                }

                // Shipping address - Only add styling if woocommerce enhanced country selects are disabled
                if ( !jQuery( '#shipping_country_field .chosen-container' ).length && !jQuery( '#shipping_country_field .select2-container' ).length ) {
                    jQuery( '#shipping_country_field select.country_select' ).wrap( '<p class="avada-select-parent"></p>' ).after( '<span class="select-arrow">&#xe61f;</span>' );

                    // If there is a state select for the initially selected country, wrap it
                    if ( jQuery( '#shipping_state_field select.state_select' ).length ) {
                        jQuery( '#shipping_state_field' ).addClass( 'avada-select-parent' ).append( '<div class="select-arrow">&#xe61f;</div>' );
                    }

                    jQuery( "#shipping_country" ).change(
                        function() {
                            // Timeout is needed that woocommerce js can kick in first
                            setTimeout(
                                function() {
                                    // If the new country has no state field at all or if it is just an input, undo custom styles
                                    if ( jQuery( '#shipping_state_field input#shipping_state' ).length || jQuery( '#shipping_state_field' ).is( ':hidden' ) ) {
                                        jQuery( '#shipping_state_field .select-arrow' ).remove();
                                        jQuery( '#shipping_state_field' ).removeClass( 'avada-select-parent' );
                                    }

                                    // If the new country has a state select
                                    if ( jQuery( '#shipping_state_field select.state_select' ).length ) {
                                        // Add the correct wrapper class (always needed due to woocommerce classes reset)
                                        jQuery( '#shipping_state_field' ).addClass( 'avada-select-parent' );

                                        // If the last country wasn't already having a state select, add the arrow container and calculate dimensions
                                        if ( !jQuery( '#shipping_state_field .select-arrow' ).length ) {
                                            jQuery( '#shipping_state_field' ).append( '<div class="select-arrow">&#xe61f;</div>' );

                                            calc_select_arrow_dimensions();
                                        }
                                    }
                                }, 1
                            );
                        }
                    );
                }
            }, 1
        );

		jQuery( '#calc_shipping_country' ).change(
			function() {
				// Timeout is needed that woocommerce js can kick in first
				setTimeout(
					function() {
						if ( jQuery( '.avada-shipping-calculator-form select#calc_shipping_state').length && ! jQuery( '.avada-shipping-calculator-form #calc_shipping_state' ).parent().find( '.select-arrow' ).length ) {
							jQuery( '.avada-shipping-calculator-form select#calc_shipping_state').after( '<div class="select-arrow">&#xe61f;</div>' );
						}

						if ( jQuery( '.avada-shipping-calculator-form input#calc_shipping_state' ).length ) {
							jQuery( '.avada-shipping-calculator-form #calc_shipping_state' ).parent().children( '.select-arrow' ).remove();
						}

						calc_select_arrow_dimensions();
					}, 1
				);
			}
		);

        // wrap variation forms select and add arrow
        jQuery( 'table.variations select, .variations-table select, .product-addon select' ).wrap( '<div class="avada-select-parent"></div>' );
        jQuery( '<div class="select-arrow">&#xe61f;</div>' ).appendTo( 'table.variations .avada-select-parent, .variations-table .avada-select-parent, .product-addon .avada-select-parent' );

        // wrap cf7 select and add arrow
        jQuery( '.wpcf7-select:not([multiple])' ).wrap( '<div class="wpcf7-select-parent"></div>' );
        jQuery( '<div class="select-arrow">&#xe61f;</div>' ).appendTo( '.wpcf7-select-parent' );

        // wrap gravity forms select and add arrow
		wrap_gravity_selects();

        // wrap woo and bbpress select and add arrow
        jQuery( '#bbp_stick_topic_select, #bbp_topic_status_select, #bbp_forum_id, #bbp_destination_topic, #wpfc_sermon_sorting select' ).wrap( '<div class="avada-select-parent"></div>' ).after( '<div class="select-arrow">&#xe61f;</div>' );

        jQuery( '.variations_form select' ).change(
            function() {
                if ( jQuery( '.product #slider' ).length ) {
                    jQuery( '.product #slider' ).flexslider( 0 );
                }
            }
        );

        // wrap category and archive widget dropdowns
        jQuery( '.widget_categories select, .widget_archive select ' ).css( 'width', '100%' );
        jQuery( '.widget_categories select, .widget_archive select ' ).wrap( '<div class="avada-select-parent"></div>' ).after( '<div class="select-arrow">&#xe61f;</div>' );
    }

    // set heights of select arrows correctly
   	calc_select_arrow_dimensions();

   	setTimeout( function() {
		calc_select_arrow_dimensions();
	}, 100 );
});

function wrap_gravity_selects() {
	jQuery( '.gform_wrapper select:not([multiple])' ).each(
		function() {
			var current_select = jQuery( this );

			setTimeout(
				function() {
					if ( !current_select.siblings( '.chosen-container' ).length ) {
						var select_width = current_select.css( 'width' );
						current_select.wrap( '<div class="gravity-select-parent"></div>' );
						current_select.parent().width( select_width );
						current_select.css( 'cssText', "width: 100% !important;" );
						jQuery( '<div class="select-arrow">&#xe61f;</div>' ).appendTo( current_select.parent( '.gravity-select-parent' ) );

						calc_select_arrow_dimensions();
					}
				}, 150
			);
		}
	);
}

// Wrap gravity forms select and add arrow
function calc_select_arrow_dimensions() {
	jQuery( '.avada-select-parent .select-arrow, .gravity-select-parent .select-arrow, .wpcf7-select-parent .select-arrow' ).filter( ':visible' ).each( function() {
		if( jQuery( this ).prev().innerHeight() > 0 ) {
			jQuery( this ).css( {
				height: jQuery( this ).prev().innerHeight(),
				width: jQuery( this ).prev().innerHeight(),
				'line-height': jQuery( this ).prev().innerHeight() + 'px'
			});
		}
	});
}

// Unwrap gravity selects that get a chzn field appended on the fly
jQuery(document).bind('gform_post_conditional_logic', function(){
	var select = jQuery('.gform_wrapper select');
	jQuery(select).each(function() {
		if(jQuery(this).hasClass('chzn-done') && jQuery(this).parent().hasClass('gravity-select-parent')) {
			jQuery(this).parent().find('.select-arrow').remove();
			jQuery(this).unwrap('<div class="gravity-select-parent"></div>');
		}
	});
});

// Setup a recursive function to handle gform multipart form selects
function recursive_gform_submission_handler() {
	if ( jQuery( '.gform_wrapper' ).find( 'form' ).attr( 'target' ) && jQuery( '.gform_wrapper' ).find( 'form' ).attr( 'target' ).indexOf( 'gform_ajax_frame' ) > -1 ) {
		jQuery( '.gform_wrapper' ).find( 'form' ).submit( function( event ) {
			setTimeout(
				function() {
					wrap_gravity_selects();
					calc_select_arrow_dimensions();
					recursive_gform_submission_handler();
				},
			800 );
		});
	}
}
recursive_gform_submission_handler();


