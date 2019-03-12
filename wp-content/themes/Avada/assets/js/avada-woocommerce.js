/**
 * WooCommerce Quanity buttons add-back
 */
jQuery(
    function( $ ) {
        if ( typeof js_local_vars.woocommerce_23 !== 'undefined' ) {
            var $testProp = $( 'div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)' ).find( 'qty' );
            if ( $testProp && $testProp.prop( 'type' ) != 'date' ) {
                // Quantity buttons
                $( 'div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)' ).addClass( 'buttons_added' ).append( '<input type="button" value="+" class="plus" />' ).prepend( '<input type="button" value="-" class="minus" />' );

                // Target quantity inputs on product pages
                $( 'input.qty:not(.product-quantity input.qty)' ).each(
                    function() {

                        var min = parseFloat( $( this ).attr( 'min' ) );

                        if ( min && min > 0 && parseFloat( $( this ).val() ) < min ) {
                            $( this ).val( min );
                        }
                    }
                );

                $( document ).on(
                    'click', '.plus, .minus', function() {

                        // Get values
                        var $qty = $( this ).closest( '.quantity' ).find( '.qty' ),
                            currentVal = parseFloat( $qty.val() ),
                            max = parseFloat( $qty.attr( 'max' ) ),
                            min = parseFloat( $qty.attr( 'min' ) ),
                            step = $qty.attr( 'step' );

                        // Format values
                        if ( !currentVal || currentVal === '' || currentVal === 'NaN' ) currentVal = 0;
                        if ( max === '' || max === 'NaN' ) max = '';
                        if ( min === '' || min === 'NaN' ) min = 0;
                        if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN' ) step = 1;

                        // Change the value
                        if ( $( this ).is( '.plus' ) ) {

                            if ( max && ( max == currentVal || currentVal > max ) ) {
                                $qty.val( max );
                            } else {
                                $qty.val( currentVal + parseFloat( step ) );
                            }

                        } else {

                            if ( min && ( min == currentVal || currentVal < min ) ) {
                                $qty.val( min );
                            } else if ( currentVal > 0 ) {
                                $qty.val( currentVal - parseFloat( step ) );
                            }

                        }

                        // Trigger change event
                        $qty.trigger( 'change' );
                    }
                );
            }
        }
    }
);

function fusionResizeCrossfadeImages( $parent ) {
	var $parent_height = $parent.height();

	$parent.find( 'img' ).each(
		function() {
			$img_height = jQuery( this ).height();

			if ( $img_height < $parent_height ) {
				jQuery( this ).css( 'margin-top', ( ( $parent_height - $img_height ) / 2 )  + "px" );
			}
		}
	);
}

function fusion_resize_crossfade_images_container( $container ) {
	var $biggest_height = 0;

	$container.find( 'img' ).each(
		function() {
			$img_height = jQuery( this ).height();

			if ( $img_height > $biggest_height ) {
				$biggest_height = $img_height;
			}
		}
	);

	$container.css( 'height', $biggest_height );
}

function fusion_calc_woocommerce_tabs_layout( $tab_selector ) {
	jQuery( $tab_selector ).each( function() {
		var $menu_width = jQuery( this ).parent().width();
		var $menu_items = jQuery( this ).find( 'li' ).length;
		var $mod = $menu_width % $menu_items;
		var $item_width = ( $menu_width - $mod ) / $menu_items;
		var $last_item_width = $menu_width - $item_width * ( $menu_items - 1 );

		jQuery( this ).css( 'width', $menu_width + 'px' );
		jQuery( this ).find( 'li' ).css( 'width', $item_width + 'px' );
		jQuery( this ).find( 'li:last' ).css( 'width', $last_item_width + 'px' ).addClass( 'no-border-right' );
	});
};

// Resize crossfade images and square to be the largest image and also vertically centered
jQuery( window ).load(
    function() {
		jQuery( '.variations_form' ).find( '.variations .single_variation_wrap .woocommerce-variation-description' ).remove();

        jQuery( window ).resize(
            function() {
				jQuery( '.crossfade-images' ).each(
					function() {
						fusion_resize_crossfade_images_container( jQuery( this ) );
						fusionResizeCrossfadeImages( jQuery( this ) );
					}
				);
            }
        );

        jQuery( '.crossfade-images' ).each(
            function() {
                fusion_resize_crossfade_images_container( jQuery( this ) );
                fusionResizeCrossfadeImages( jQuery( this ) );
            }
        );

		// Make the onsale badge also work on products without image
        jQuery( '.product-images' ).each(
            function() {
                if ( ! jQuery( this ).find( 'img' ).length && jQuery( this ).find( '.onsale' ).length ) {
					jQuery( this ).css( 'min-height', '45px' );
				}
            }
        );

		jQuery( '.woocommerce .images #carousel a' ).click( function(e) {
			e.preventDefault();
		});

        // Make sure the variation image is also changed in the thumbs carousel and for lightbox
		jQuery( '.variations_form' ).on( 'change', '.variations select', function( event ) {
			var $variations_form = jQuery( this ).parents( '.variations_form' );

			// Timeout needed to get updated image src attribute
			setTimeout( function() {
				var $slider_first_image = jQuery( '.images' ).find( '#slider img:eq(0)' ),
					$slider_first_image_parent_link = $slider_first_image.parent(),
					$slider_first_image_src = $slider_first_image.attr( 'src' ),
					$thumbs_first_image = jQuery( '.images' ).find( '#carousel img:eq(0)' );

				if ( $slider_first_image_parent_link && $slider_first_image_parent_link.attr( 'href' ) ) {
					$slider_first_image_src = $slider_first_image_parent_link.attr( 'href' );
				}

				$slider_first_image.parent().attr( 'href', $slider_first_image_src );
				$slider_first_image.removeAttr( 'sizes' );
				$slider_first_image.removeAttr( 'srcset' );

				// Refresh the lightbox
				$avada_lightbox.refresh_lightbox();

				$thumbs_first_image.attr( 'src', $slider_first_image_src );
				$thumbs_first_image.removeAttr( 'sizes' );
				$thumbs_first_image.removeAttr( 'srcset' );

				var $slider = jQuery( '.images #slider' ).data( 'flexslider' );
				if ( $slider ) {
					$slider.resize();
				}

				var $slider = jQuery( '.images #carousel' ).data( 'flexslider' );
				if ( $slider ) {
					$slider.resize();
				}

				//$variations_form.find( '.variations .single_variation_wrap .woocommerce-variation-description' ).remove();

			}, 1 );

			setTimeout( function() {
				$avada_lightbox.refresh_lightbox();

				var $slider = jQuery( '.images #slider' ).data( 'flexslider' );
				if ( $slider ) {
					$slider.resize();
				}
			}, 500 );

			setTimeout( function() {
				$avada_lightbox.refresh_lightbox();
			}, 1500 );
		});
    }
);

jQuery( document ).ready( function() {
	jQuery( '.product-type-variable .variations_form > .single_variation_wrap .woocommerce-variation-price' ).remove();
	jQuery( '.product-type-variable .variations_form > .single_variation_wrap .woocommerce-variation-availability' ).remove();

	jQuery( 'body' ).on( 'click', '.add_to_cart_button', function(e) {
		var $add_to_cart_button = jQuery( this );

		$add_to_cart_button.closest( '.product, li' ).find( '.cart-loading' ).find( 'i' ).removeClass( 'fusion-icon-check-square-o' ).addClass( 'fusion-icon-spinner' );
		$add_to_cart_button.closest( '.product, li' ).find( '.cart-loading' ).fadeIn();
		setTimeout( function(){
			$add_to_cart_button.closest( '.product, li' ).find( '.cart-loading' ).find( 'i' ).hide().removeClass( 'fusion-icon-spinner' ).addClass( 'fusion-icon-check-square-o' ).fadeIn();
			jQuery( $add_to_cart_button ).parents( '.fusion-clean-product-image-wrapper, li' ).addClass( 'fusion-item-in-cart' );
		}, 2000 );
	});

	jQuery('li').mouseenter(function() {
		if(jQuery(this).find('.cart-loading').find('i').hasClass('fusion-icon-check-square-o')) {
			jQuery(this).find('.cart-loading').fadeIn();
		}
	}).mouseleave(function() {
		if(jQuery(this).find('.cart-loading').find('i').hasClass('fusion-icon-check-square-o')) {
			jQuery(this).find('.cart-loading').fadeOut();
		}
	});

	jQuery('.catalog-ordering .orderby .current-li a').html(jQuery('.catalog-ordering .orderby ul li.current a').html());
	jQuery('.catalog-ordering .sort-count .current-li a').html(jQuery('.catalog-ordering .sort-count ul li.current a').html());
	jQuery('.woocommerce .shop_table .variation dd').after('<br />');
	jQuery('.woocommerce .avada-myaccount-data th.order-actions').text(js_local_vars.order_actions);

	// My account page error check
	if ( jQuery( '.avada_myaccount_user' ).length && jQuery( '.woocommerce-error' ).length && ! jQuery( '.avada-myaccount-nav' ).find( '.active' ).children().hasClass( 'address' ) ) {
		jQuery( '.avada-myaccount-nav' ).find( '.active' ).removeClass( 'active' );
		jQuery( '.avada-myaccount-nav' ).find( '.account' ).parent().addClass( 'active' );
	}

	var avada_myaccount_active = jQuery('.avada-myaccount-nav').find('.active a');

	if(avada_myaccount_active.hasClass('address') ) {
		jQuery('.avada-myaccount-data .edit_address_heading').fadeIn();
	} else {
		jQuery('.avada-myaccount-data h2:nth-of-type(1)').fadeIn();
	}

	if(avada_myaccount_active.hasClass('downloads') ) {
		jQuery('.avada-myaccount-data .digital-downloads').fadeIn();
	} else if(avada_myaccount_active.hasClass('orders') ) {
		jQuery('.avada-myaccount-data .my_account_orders').fadeIn();
	} else if(avada_myaccount_active.hasClass('address') ) {
		jQuery('.avada-myaccount-data .myaccount_address, .avada-myaccount-data .address').fadeIn();
	} else if(avada_myaccount_active ) {
		jQuery('.avada-myaccount-data .edit-account-form, .avada-myaccount-data .edit-account-heading').fadeIn();
		jQuery('.avada-myaccount-data h2:nth-of-type(1)').hide();
	}

	jQuery('body.rtl .avada-myaccount-data .my_account_orders .order-status').each( function() {
		jQuery( this ).css( 'text-align', 'right' );
	});

	jQuery('.woocommerce input').each(function() {
		if(!jQuery(this).has('#coupon_code')) {
			var name = jQuery(this).attr('id');
			jQuery(this).attr('placeholder', jQuery(this).parent().find('label[for='+name+']').text());
		}
	});

	if(jQuery('.woocommerce #reviews #comments .comment_container .comment-text').length ) {
		jQuery('.woocommerce #reviews #comments .comment_container').append('<div class="clear"></div>');
	}

	var $title_sep = js_local_vars.title_style_type.split( ' ' ),
		$title_sep_class_string = '',
		$title_main_sep_class_string = '',
		$headinging_orientation = 'title-heading-left';

	for ( var i = 0; i < $title_sep.length; i++ ) {
		$title_sep_class_string += ' sep-' + $title_sep[i];
	}

	if ( $title_sep_class_string.indexOf( 'underline' ) > -1 ) {
		$title_main_sep_class_string = $title_sep_class_string;
	}

	if ( jQuery( 'body' ).hasClass( 'rtl' ) ) {
		$headinging_orientation = 'title-heading-right';
	}

	jQuery('.woocommerce.single-product .related.products > h2' ).each( function() {
		var $related_heading = jQuery( this ).replaceWith( function () {
		    return '<div class="fusion-title title' + $title_sep_class_string + '"><h3 class="' + $headinging_orientation + '">' + jQuery( this ).html() + '</h3><div class="title-sep-container"><div class="title-sep' + $title_sep_class_string + ' "></div></div></div>';
		});
	});

	jQuery('.woocommerce.single-product .upsells.products > h2').each( function() {
		var $related_heading = jQuery( this ).replaceWith( function () {
		    return '<div class="fusion-title title' + $title_sep_class_string + '"><h3 class="' + $headinging_orientation + '">' + jQuery( this ).html() + '</h3><div class="title-sep-container"><div class="title-sep' + $title_sep_class_string + ' "></div></div></div>';
		});
	});

	if ( jQuery( 'body .sidebar' ).css( 'display' ) == 'block' ) {
		fusion_calc_woocommerce_tabs_layout( '.woocommerce-tabs .tabs-horizontal' );
	}

	jQuery('.sidebar .products,.fusion-footer-widget-area .products,#slidingbar-area .products').each(function() {
		jQuery(this).removeClass('products-4');
		jQuery(this).removeClass('products-3');
		jQuery(this).removeClass('products-2');
		jQuery(this).addClass('products-1');
	});

	jQuery('.products-6 li, .products-5 li, .products-4 li, .products-3 li, .products-3 li').removeClass('last');

	// Woocommerce nested products plugin support
	jQuery( '.subcategory-products' ).each( function() {
		jQuery( this ).addClass( 'products-' + js_local_vars.woocommerce_shop_page_columns );
	});

	jQuery('.woocommerce-tabs ul.tabs li a').unbind( 'click' );
	jQuery('body').on( 'click', '.woocommerce-tabs > ul.tabs li a', function(){

		var tab = jQuery( this );
		var tabs_wrapper = tab.closest( '.woocommerce-tabs' );

		jQuery( 'ul.tabs li', tabs_wrapper ).removeClass( 'active' );
		jQuery( '> div.panel', tabs_wrapper ).hide();
		jQuery( 'div' + tab.attr( 'href' ), tabs_wrapper ).show();
		tab.parent().addClass( 'active' );

		return false;
	});

	jQuery( 'body' ).on( 'click', '.woocommerce-checkout-nav a,.continue-checkout', function(e) {
		var $admin_bar_height = ( jQuery( '#wpadminbar' ).length ) ? jQuery( '#wpadminbar' ).height() : 0,
			$header_div_children = jQuery( '.fusion-header-wrapper').find( 'div' ),
			$sticky_header_height = 0;

		$header_div_children.each( function() {
			if ( jQuery( this ).css( 'position' ) == 'fixed' ) {
				$sticky_header_height = jQuery( this ).height();
			}
		});

		e.preventDefault();

		if ( ! jQuery( '.woocommerce .avada-checkout' ).find( '.woocommerce-invalid' ).is( ':visible' ) ) {
			var $data_name = jQuery( this ).attr( 'data-name' ),
				$name = $data_name;

			if ( $data_name == 'order_review' ) {
				$name = '#' + $data_name;
			} else {
				$name = '.' + $data_name;
			}

			jQuery( 'form.checkout .col-1, form.checkout .col-2, form.checkout #order_review_heading, form.checkout #order_review' ).hide();

			jQuery( 'form.checkout' ).find( $name ).fadeIn();
			if( $name == 'order_review' ) {
				jQuery( 'form.checkout' ).find( '#order_review_heading ').fadeIn();
			}

			jQuery( '.woocommerce-checkout-nav li' ).removeClass( 'active' );
			jQuery( '.woocommerce-checkout-nav' ).find( '[data-name=' + $data_name + ']' ).parent().addClass( 'active' );

			if ( jQuery( this ).hasClass( 'continue-checkout' ) && jQuery( window ).scrollTop() > 0 ) {
				jQuery( 'html, body' ).animate( {scrollTop: jQuery( '.woocommerce-content-box.avada-checkout' ).offset().top - $admin_bar_height - $sticky_header_height }, 500 );
			}
		}

		// set heights of select arrows correctly
		calc_select_arrow_dimensions();
	});

	// Ship to a different address toggle
	jQuery( 'body' ).on( 'click', 'input[name=ship_to_different_address]',
		function() {
			if ( jQuery ( this ).is( ':checked' ) ) {
				setTimeout( function() {
					// set heights of select arrows correctly
					calc_select_arrow_dimensions();
				}, 1 );
			}
		}
	);

	jQuery( 'body' ).on( 'click', '.avada-myaccount-nav a', function(e) {
		e.preventDefault();

		jQuery('.avada-myaccount-data h2, .avada-myaccount-data .digital-downloads, .avada-myaccount-data .my_account_orders, .avada-myaccount-data .myaccount_address, .avada-myaccount-data .address, .avada-myaccount-data .edit-account-heading, .avada-myaccount-data .edit-account-form').hide();

		if(jQuery(this).hasClass('downloads') ) {
			jQuery('.avada-myaccount-data h2:nth-of-type(1), .avada-myaccount-data .digital-downloads').fadeIn();
		} else if(jQuery(this).hasClass('orders') ) {

			if( jQuery(this).parents('.avada-myaccount-nav').find('.downloads').length ) {
				heading = jQuery('.avada-myaccount-data h2:nth-of-type(2)');
			} else {
				heading = jQuery('.avada-myaccount-data h2:nth-of-type(1)');
			}

			heading.fadeIn();
			jQuery('.avada-myaccount-data .my_account_orders').fadeIn();
		} else if(jQuery(this).hasClass('address') ) {

			if( jQuery(this).parents('.avada-myaccount-nav').find('.downloads').length && jQuery(this).parents('.avada-myaccount-nav').find('.orders').length ) {
				heading = jQuery('.avada-myaccount-data h2:nth-of-type(3)');
			} else if( jQuery(this).parents('.avada-myaccount-nav').find('.downloads').length || jQuery(this).parents('.avada-myaccount-nav').find('.orders').length ) {
				heading = jQuery('.avada-myaccount-data h2:nth-of-type(2)');
			} else {
				heading = jQuery('.avada-myaccount-data h2:nth-of-type(1)');
			}

			heading.fadeIn();
			jQuery('.avada-myaccount-data .myaccount_address, .avada-myaccount-data .address').fadeIn();
		} else if(jQuery(this).hasClass('account') ) {
			jQuery('.avada-myaccount-data .edit-account-heading, .avada-myaccount-data .edit-account-form').fadeIn();
		}

		jQuery('.avada-myaccount-nav li').removeClass('active');
		jQuery(this).parent().addClass('active');
	});
});