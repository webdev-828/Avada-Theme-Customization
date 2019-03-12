jQuery( document ).ready(function() {

	'use strict';

	// position dropdown menu correctly
	jQuery.fn.fusion_position_menu_dropdown = function( variables ) {

			if( ( js_local_vars.header_position === 'Top' && ! jQuery( 'body.rtl' ).length ) || js_local_vars.header_position === 'Left'  ) {
				return 	jQuery( this ).children( '.sub-menu' ).each( function() {

					// reset attributes
					jQuery( this ).removeAttr( 'style' );
					jQuery( this ).show();
					jQuery( this ).removeData( 'shifted' );

					var submenu = jQuery( this );

					if( submenu.length ) {
						var submenu_position = submenu.offset(),
							submenu_left = submenu_position.left,
							submenu_top = submenu_position.top,
							submenu_height = submenu.height(),
							submenu_width = submenu.outerWidth(),
							submenu_bottom_edge = submenu_top + submenu_height,
							submenu_right_edge = submenu_left + submenu_width,
							browser_bottom_edge = jQuery( window ).height(),
							browser_right_edge = jQuery( window ).width(),
							admin_bar_height,
							submenu_new_top_pos;

						if(	jQuery( '#wpadminbar' ).length ) {
							admin_bar_height = jQuery( '#wpadminbar' ).height();
						} else {
							admin_bar_height = 0;
						}

						if( jQuery( '#side-header' ).length ) {
							var side_header_top = jQuery( '#side-header' ).offset().top - admin_bar_height;
						}

						// current submenu goes beyond browser's right edge
						if( submenu_right_edge > browser_right_edge ) {

							//if there are 2 or more submenu parents position this submenu below last one
							if( submenu.parent().parent( '.sub-menu' ).parent().parent( '.sub-menu' ).length ) {
								submenu.css({
									'left': '0',
									'top': submenu.parent().parent( '.sub-menu' ).height()
								});

							// first or second level submenu
							} else {
								// first level submenu
								if( ! submenu.parent().parent( '.sub-menu' ).length ) {
									submenu.css( 'left', ( -1 ) * submenu_width + submenu.parent().width() );

								// second level submenu
								} else {
									submenu.css({
										'left': ( -1 ) * submenu_width
									});
								}
							}

							submenu.data( 'shifted', 1 );
						// parent submenu had to be shifted
						} else if( submenu.parent().parent( '.sub-menu' ).length ) {
							if( submenu.parent().parent( '.sub-menu' ).data( 'shifted' ) ) {
								submenu.css( 'left', ( -1 ) * submenu_width );
								submenu.data( 'shifted', 1 );
							}
						}

						// Calculate dropdown vertical position on side header.
						if( js_local_vars.header_position !== 'Top' && submenu_bottom_edge > side_header_top + browser_bottom_edge && jQuery( window ).height() >= jQuery( '.side-header-wrapper' ).height() ) {
							if( submenu_height < browser_bottom_edge  ) {
								submenu_new_top_pos = ( -1 ) * ( submenu_bottom_edge - side_header_top - browser_bottom_edge + 20 );
							} else {
								submenu_new_top_pos = ( -1 ) * ( submenu_top - admin_bar_height );
							}
							submenu.css( 'top', submenu_new_top_pos );
						}
					}
				});
			} else {
				return 	jQuery( this ).children( '.sub-menu' ).each( function() {

					// reset attributes
					jQuery( this ).removeAttr( 'style' );
					jQuery( this ).removeData( 'shifted' );

					var submenu = jQuery( this );

					if( submenu.length ) {
						var submenu_position = submenu.offset(),
							submenu_left_edge = submenu_position.left,
							submenu_top = submenu_position.top,
							submenu_height = submenu.height(),
							submenu_width = submenu.outerWidth(),
							submenu_bottom_edge = submenu_top + submenu_height,
							browser_bottom_edge = jQuery( window ).height();

						if(	jQuery( '#wpadminbar' ).length ) {
							var admin_bar_height = jQuery( '#wpadminbar' ).height();
						} else {
							var admin_bar_height = 0;
						}

						if( jQuery( '#side-header' ).length ) {
							var side_header_top = jQuery( '#side-header' ).offset().top - admin_bar_height;
						}

						// current submenu goes beyond browser's left edge
						if( submenu_left_edge < 0 ) {
							//if there are 2 or more submenu parents position this submenu below last one
							if( submenu.parent().parent( '.sub-menu' ).parent().parent( '.sub-menu' ).length ) {
								if( js_local_vars.header_position == 'Right' ) {
									submenu.css({
										'left': '0',
										'top': submenu.parent().parent( '.sub-menu' ).height()
									});
								} else {
									submenu.css({
										'right': '0',
										'top': submenu.parent().parent( '.sub-menu' ).height()
									});
								}
							// first or second level submenu
							} else {
								// first level submenu
								if( ! submenu.parent().parent( '.sub-menu' ).length ) {
									submenu.css( 'right', ( -1 ) * submenu_width + submenu.parent().width() );

								// second level submenu
								} else {
									submenu.css({
										'right': ( -1 ) * submenu_width
									});
								}
							}

							submenu.data( 'shifted', 1 );
						// parent submenu had to be shifted
						} else if( submenu.parent().parent( '.sub-menu' ).length ) {
							if( submenu.parent().parent( '.sub-menu' ).data( 'shifted' ) ) {
								submenu.css( 'right', ( -1 ) * submenu_width );
							}
						}

						// Calculate dropdown vertical position on side header
						if( js_local_vars.header_position != 'Top' && submenu_bottom_edge > side_header_top + browser_bottom_edge && jQuery( window ).height() >= jQuery( '.side-header-wrapper' ).height() ) {
							if( submenu_height < browser_bottom_edge  ) {
								var submenu_new_top_pos = ( -1 ) * ( submenu_bottom_edge - side_header_top - browser_bottom_edge + 20 );
							} else {
								var submenu_new_top_pos = ( -1 ) * ( submenu_top - admin_bar_height );
							}
							submenu.css( 'top', submenu_new_top_pos );
						}
					}
				});
			}
	};

	// Recursive function for positioning menu items correctly on load
	jQuery.fn.walk_through_menu_items = function() {
		jQuery( this ).fusion_position_menu_dropdown();

		if( jQuery( this ).find( '.sub-menu' ).length ) {
			jQuery( this ).find( '.sub-menu li' ).walk_through_menu_items();
		} else {
			return;
		}
	};

	// Position the cart dropdown vertically on side-header layouts
	jQuery.fn.position_cart_dropdown = function() {
		if( js_local_vars.header_position != 'Top' ) {
			jQuery( this ).each( function() {
				jQuery( this ).css( 'top', '' );

				var cart_dropdown = jQuery( this ),
					cart_dropdown_top = cart_dropdown.offset().top,
					cart_dropdown_height = cart_dropdown.height(),
					cart_dropdown_bottom_edge = cart_dropdown_top + cart_dropdown_height,
					admin_bar_height = ( jQuery( '#wpadminbar' ).length ) ? jQuery( '#wpadminbar' ).height() : 0,
					side_header_top = jQuery( '#side-header' ).offset().top - admin_bar_height,
					browser_bottom_edge = jQuery( window ).height();

				if( cart_dropdown_bottom_edge > side_header_top + browser_bottom_edge && jQuery( window ).height() >= jQuery( '.side-header-wrapper' ).height() ) {
					if( cart_dropdown_height < browser_bottom_edge ) {
						var cart_dropdown_new_top_pos = ( -1 ) * ( cart_dropdown_bottom_edge - side_header_top - browser_bottom_edge + 20 );
					} else {
						var cart_dropdown_new_top_pos = ( -1 ) * ( cart_dropdown_top - admin_bar_height );
					}

					cart_dropdown.css( 'top', cart_dropdown_new_top_pos );
				}
			});
		}
	};

	// Position the search form vertically on side-header layouts
	jQuery.fn.position_menu_search_form = function() {
		if( js_local_vars.header_position != 'Top' ) {
			jQuery( this ).each( function() {
				jQuery( this ).css( 'top', '' );

				var search_form = jQuery( this ),
					search_form_top = search_form.offset().top,
					search_form_height = search_form.outerHeight(),
					search_form_bottom_edge = search_form_top + search_form_height,
					admin_bar_height = ( jQuery( '#wpadminbar' ).length ) ? jQuery( '#wpadminbar' ).height() : 0,
					side_header_top = jQuery( '#side-header' ).offset().top - admin_bar_height,
					browser_bottom_edge = jQuery( window ).height();

				if( search_form_bottom_edge > side_header_top + browser_bottom_edge && jQuery( window ).height() >= jQuery( '.side-header-wrapper' ).height() ) {
					var search_form_new_top_pos = ( -1 ) * ( search_form_bottom_edge - side_header_top - browser_bottom_edge + 20 );

					search_form.css( 'top', search_form_new_top_pos );
				}
			});
		}
	};

	// position mega menu correctly
	jQuery.fn.fusion_position_megamenu = function( variables ) {
		// side header left handling
		if( jQuery( '.side-header-left' ).length ) {
			return this.each( function() {
				jQuery( this ).children( 'li' ).each( function() {
					var li_item = jQuery( this ),
						megamenu_wrapper = li_item.find( '.fusion-megamenu-wrapper' );

					if( megamenu_wrapper.length ) {
						megamenu_wrapper.removeAttr( 'style' );

						var megamenu_wrapper_left = jQuery( '#side-header' ).outerWidth() - 1,
							megamenu_wrapper_top = megamenu_wrapper.offset().top,
							megamenu_wrapper_height = megamenu_wrapper.height(),
							megamenu_bottom_edge = megamenu_wrapper_top + megamenu_wrapper_height,
							admin_bar_height = ( jQuery( '#wpadminbar' ).length ) ? jQuery( '#wpadminbar' ).height() : 0,
							side_header_top = jQuery( '#side-header' ).offset().top - admin_bar_height,
							browser_bottom_edge = jQuery( window ).height();

						if( ! jQuery( 'body.rtl' ).length ) {
							megamenu_wrapper.css( 'left', megamenu_wrapper_left );
						} else {
							megamenu_wrapper.css({ 'left': megamenu_wrapper_left, 'right': 'auto' });
						}

						if( megamenu_bottom_edge > side_header_top + browser_bottom_edge && jQuery( window ).height() >= jQuery( '.side-header-wrapper' ).height() ) {
							if( megamenu_wrapper_height < browser_bottom_edge ) {
								var megamenu_wrapper_new_top_pos = ( -1 ) * ( megamenu_bottom_edge - side_header_top - browser_bottom_edge + 20 );
							} else {
								var megamenu_wrapper_new_top_pos = ( -1 ) * ( megamenu_wrapper_top - admin_bar_height );
							}

							megamenu_wrapper.css( 'top', megamenu_wrapper_new_top_pos );
						}
					}
				});
			});
		}

		// side header right handling
		if( jQuery( '.side-header-right' ).length ) {
			return this.each( function() {
				jQuery( this ).children( 'li' ).each( function() {
					var li_item = jQuery( this ),
						megamenu_wrapper = li_item.find( '.fusion-megamenu-wrapper' );

					if( megamenu_wrapper.length ) {
						megamenu_wrapper.removeAttr( 'style' );

						var megamenu_wrapper_left = ( -1 ) * megamenu_wrapper.outerWidth(),
							megamenu_wrapper_top = megamenu_wrapper.offset().top,
							megamenu_wrapper_height = megamenu_wrapper.height(),
							megamenu_bottom_edge = megamenu_wrapper_top + megamenu_wrapper_height,
							admin_bar_height = ( jQuery( '#wpadminbar' ).length ) ? jQuery( '#wpadminbar' ).height() : 0,
							side_header_top = jQuery( '#side-header' ).offset().top - admin_bar_height,
							browser_bottom_edge = jQuery( window ).height();

						if( ! jQuery( 'body.rtl' ).length ) {
							megamenu_wrapper.css( 'left', megamenu_wrapper_left );
						} else {
							megamenu_wrapper.css({ 'left': megamenu_wrapper_left, 'right': 'auto' });
						}

						if( megamenu_bottom_edge > side_header_top + browser_bottom_edge && jQuery( window ).height() >= jQuery( '.side-header-wrapper' ).height()) {
							if( megamenu_wrapper_height < browser_bottom_edge ) {
								var megamenu_wrapper_new_top_pos = ( -1 ) * ( megamenu_bottom_edge - side_header_top - browser_bottom_edge + 20 );
							} else {
								var megamenu_wrapper_new_top_pos = ( -1 ) * ( megamenu_wrapper_top - admin_bar_height );
							}

							megamenu_wrapper.css( 'top', megamenu_wrapper_new_top_pos );
						}
					}
				});
			});
		}

		// top header handling
		var reference_elem = '';
		if( jQuery( '.fusion-header-v4' ).length ) {
			reference_elem = jQuery( this ).parent( '.fusion-main-menu' ).parent();
		} else {
			reference_elem = jQuery( this ).parent( '.fusion-main-menu' );
		}

		if( jQuery( this ).parent( '.fusion-main-menu' ).length ) {

			var main_nav_container = reference_elem,
				main_nav_container_position = main_nav_container.offset(),
				main_nav_container_width = main_nav_container.width(),
				main_nav_container_left_edge = main_nav_container_position.left,
				main_nav_container_right_edge = main_nav_container_left_edge + main_nav_container_width;

			if( ! jQuery( 'body.rtl' ).length ) {
				return this.each( function() {

					jQuery( this ).children( 'li' ).each( function() {
						var li_item = jQuery( this ),
							li_item_position = li_item.offset(),
							megamenu_wrapper = li_item.find( '.fusion-megamenu-wrapper' ),
							megamenu_wrapper_width = megamenu_wrapper.outerWidth(),
							megamenu_wrapper_position = 0;

						// check if there is a megamenu
						if( megamenu_wrapper.length ) {
							megamenu_wrapper.removeAttr( 'style' );

							// set megamenu max width
							var reference_avada_row;

							if( jQuery( '.fusion-secondary-main-menu' ).length ) {
								reference_avada_row = jQuery( '.fusion-header-wrapper .fusion-secondary-main-menu .fusion-row' );
							} else {
								reference_avada_row = jQuery( '.fusion-header-wrapper .fusion-row' );
							}

							if( megamenu_wrapper.hasClass( 'col-span-12' ) && ( reference_avada_row.width() < megamenu_wrapper.data( 'maxwidth' ) ) ) {
								megamenu_wrapper.css( 'width', reference_avada_row.width() );
							} else {
								megamenu_wrapper.removeAttr( 'style' );
							}

							// reset the megmenu width after resizing the menu
							megamenu_wrapper_width = megamenu_wrapper.outerWidth();

							if( li_item_position.left + megamenu_wrapper_width > main_nav_container_right_edge ) {
								megamenu_wrapper_position = -1 * ( li_item_position.left - ( main_nav_container_right_edge - megamenu_wrapper_width ) );

								if( js_local_vars.logo_alignment.toLowerCase() == 'right' ) {
									if( li_item_position.left + megamenu_wrapper_position < main_nav_container_left_edge ) {
										megamenu_wrapper_position = -1 * ( li_item_position.left - main_nav_container_left_edge );
									}
								}

								megamenu_wrapper.css( 'left', megamenu_wrapper_position );
							}
						}
					});
				});

			} else {
				return this.each( function() {
					jQuery( this ).children( 'li' ).each( function() {
						var li_item = jQuery( this ),
							li_item_position = li_item.offset(),
							li_item_right_edge = li_item_position.left + li_item.outerWidth(),
							megamenu_wrapper = li_item.find( '.fusion-megamenu-wrapper' ),
							megamenu_wrapper_width = megamenu_wrapper.outerWidth(),
							megamenu_wrapper_position = 0;

						// check if there is a megamenu
						if( megamenu_wrapper.length ) {
							megamenu_wrapper.removeAttr( 'style' );

							// set megamenu max width
							var reference_avada_row;

							if( jQuery( '.fusion-secondary-main-menu' ).length ) {
								reference_avada_row = jQuery( '.fusion-header-wrapper .fusion-secondary-main-menu .fusion-row' );
							} else {
								reference_avada_row = jQuery( '.fusion-header-wrapper .fusion-row' );
							}

							if( megamenu_wrapper.hasClass( 'col-span-12' ) && ( reference_avada_row.width() < megamenu_wrapper.data( 'maxwidth' ) ) ) {
								megamenu_wrapper.css( 'width', reference_avada_row.width() );
							} else {
								megamenu_wrapper.removeAttr( 'style' );
							}

							if( li_item_right_edge - megamenu_wrapper_width < main_nav_container_left_edge ) {

								megamenu_wrapper_position = -1 * ( megamenu_wrapper_width - ( li_item_right_edge - main_nav_container_left_edge ) );

								if( js_local_vars.logo_alignment.toLowerCase() == 'left' || ( js_local_vars.logo_alignment.toLowerCase() == 'center' && ! jQuery( '.header-v5' ).length ) || jQuery( this ).parents( '.sticky-header' ).length ) {
									if( li_item_right_edge - megamenu_wrapper_position > main_nav_container_right_edge ) {
										megamenu_wrapper_position = -1 * ( main_nav_container_right_edge - li_item_right_edge );
									}
								}

								megamenu_wrapper.css( 'right', megamenu_wrapper_position );
							}
						}
					});
				});
			}
		}
	};

	jQuery.fn.calc_megamenu_responsive_column_widths = function( variables ) {
		jQuery( this ).find( '.fusion-megamenu-menu' ).each( function() {
			var megamenu_holder = jQuery( this ).find( '.fusion-megamenu-holder' ),
				megamenu_holder_full_width = megamenu_holder.data( 'width' ),
				reference_fusion_row = ( jQuery( '.fusion-secondary-main-menu' ).length ) ? jQuery( '.fusion-header-wrapper .fusion-secondary-main-menu .fusion-row' ) : jQuery( '.fusion-header-wrapper .fusion-row' ),
				available_space = reference_fusion_row.width();

			if( js_local_vars.header_position != 'Top' ) {
				var main_padding_left = jQuery( '#main' ).css( 'padding-left' ).replace( 'px', '' );
				available_space = jQuery( window ).width() - main_padding_left - jQuery( '#side-header' ).outerWidth();
			}

			if( available_space < megamenu_holder_full_width ) {
				megamenu_holder.css( 'width', available_space );

				if( ! megamenu_holder.parents( '.fusion-megamenu-wrapper' ).hasClass( 'fusion-megamenu-fullwidth' ) ) {
					megamenu_holder.find( '.fusion-megamenu-submenu' ).each( function() {
						var submenu = jQuery( this );
						var submenu_width = submenu.data( 'width' ) * available_space / megamenu_holder_full_width;
						submenu.css( 'width', submenu_width );
					});
				}
			} else {
				megamenu_holder.css( 'width', megamenu_holder_full_width );

				if( ! megamenu_holder.parents( '.fusion-megamenu-wrapper' ).hasClass( 'fusion-megamenu-fullwidth' ) ) {
					megamenu_holder.find( '.fusion-megamenu-submenu' ).each( function() {
						jQuery( this ).css( 'width', jQuery( this ).data( 'width' ) );
					});
				}
			}
		});
	};

	jQuery.fn.position_last_top_menu_item = function( variables ) {
		if( jQuery( this ).children( 'ul' ).length || jQuery( this).children( 'div' ).length ) {
			var last_item = jQuery( this ),
				last_item_left_pos = last_item.position().left,
				last_item_width = last_item.outerWidth(),
				last_item_child,
				parent_container = jQuery( '.fusion-secondary-header .fusion-row' ),
				parent_container_left_pos = parent_container.position().left,
				parent_container_width = parent_container.outerWidth();

			if( last_item.children( 'ul' ).length ) {
				last_item_child =  last_item.children( 'ul' );
			} else if( last_item.children('div').length ) {
				last_item_child =  last_item.children( 'div' );
			}

			if( ! jQuery( 'body.rtl' ).length ) {
				if( last_item_left_pos + last_item_child.outerWidth() > parent_container_left_pos + parent_container_width ) {
					last_item_child.css( 'right', '-1px' ).css( 'left', 'auto' );

					last_item_child.find( '.sub-menu' ).each( function() {
						jQuery( this ).css( 'right', '100px' ).css( 'left', 'auto' );
					});
				}
			} else {
				if( last_item_child.position().left < last_item_left_pos ) {
					last_item_child.css( 'left', '-1px' ).css( 'right', 'auto' );

					last_item_child.find( '.sub-menu' ).each( function() {
						jQuery( this ).css( 'left', '100px' ).css( 'right', 'auto' );
					});
				}
			}
		}
	};

	// IE8 fixes
	jQuery( '.fusion-main-menu > ul > li:last-child' ).addClass( 'fusion-last-menu-item' );
	if( cssua.ua.ie && cssua.ua.ie.substr(0, 1) == '8' ) {
		jQuery( '.fusion-header-shadow' ).removeClass( 'fusion-header-shadow' );
	}

	// Calculate main menu dropdown submenu position
	if( jQuery.fn.fusion_position_menu_dropdown ) {
		jQuery( '.fusion-dropdown-menu, .fusion-dropdown-menu li' ).mouseenter( function() {
			jQuery( this ).fusion_position_menu_dropdown();
		});

		jQuery( '.fusion-dropdown-menu > ul > li' ).each( function() {
			jQuery( this ).walk_through_menu_items();
		});

		jQuery( window ).on( 'resize', function() {
			jQuery( '.fusion-dropdown-menu > ul > li' ).each( function() {
				jQuery( this ).walk_through_menu_items();
			});
		});
	}

	// Set overflow state of main nav items; done to get rid of menu overflow
	jQuery( '.fusion-dropdown-menu ' ).mouseenter( function() {
		jQuery( this ).css( 'overflow', 'visible' );
	});
	jQuery( '.fusion-dropdown-menu' ).mouseleave( function() {
		jQuery( this ).css( 'overflow', '' );
	});

	// Search icon show/hide
	jQuery( document ).click( function() {
		jQuery( '.fusion-main-menu-search .fusion-custom-menu-item-contents' ).hide();
		jQuery( '.fusion-main-menu-search' ).removeClass( 'fusion-main-menu-search-open' );
		jQuery( '.fusion-main-menu-search' ).find( 'style' ).remove();
	});

	jQuery( '.fusion-main-menu-search' ).click(function(e) {
		e.stopPropagation();
	});

	jQuery( '.fusion-main-menu-search .fusion-main-menu-icon' ).click(function(e) {
		e.stopPropagation();

		if( jQuery( this ).parent().find( '.fusion-custom-menu-item-contents' ).css( 'display' ) == 'block' ) {
			jQuery( this ).parent().find( '.fusion-custom-menu-item-contents' ).hide();
			jQuery( this ).parent().removeClass( 'fusion-main-menu-search-open' );

			jQuery( this ).parent().find( 'style' ).remove();
		} else {
			jQuery( this ).parent().find( '.fusion-custom-menu-item-contents' ).removeAttr( 'style' );
			jQuery( this ).parent().find( '.fusion-custom-menu-item-contents' ).show();
			jQuery( this ).parent().addClass( 'fusion-main-menu-search-open' );

			jQuery( this ).parent().append( '<style>.fusion-main-menu{overflow:visible!important;</style>' );
			jQuery( this ).parent().find( '.fusion-custom-menu-item-contents .s' ).focus();

			// position main menu search box on click positioning
			if( js_local_vars.header_position == 'Top' ) {
				if( ! jQuery( 'body.rtl' ).length && jQuery( this ).parent().find( '.fusion-custom-menu-item-contents' ).offset().left < 0 ) {
					jQuery( this ).parent().find( '.fusion-custom-menu-item-contents' ).css({
						'left': '0',
						'right': 'auto'
					});
				}

				if( jQuery( 'body.rtl' ).length && jQuery( this ).parent().find( '.fusion-custom-menu-item-contents' ).offset().left + jQuery( this ).parent().find( '.fusion-custom-menu-item-contents' ).width()  > jQuery( window ).width() ) {
					jQuery( this ).parent().find( '.fusion-custom-menu-item-contents' ).css({
						'left': 'auto',
						'right': '0'
					});
				}
			}
		}
	});

	// Calculate megamenu position
	if( jQuery.fn.fusion_position_megamenu ) {
		jQuery( '.fusion-main-menu > ul' ).fusion_position_megamenu();

		jQuery( '.fusion-main-menu .fusion-megamenu-menu' ).mouseenter( function() {
			jQuery( this ).parent().fusion_position_megamenu();
		});

		jQuery(window).resize(function() {
			jQuery( '.fusion-main-menu > ul' ).fusion_position_megamenu();
		});
	}

	// Calculate megamenu column widths
	if( jQuery.fn.calc_megamenu_responsive_column_widths ) {
		jQuery( '.fusion-main-menu > ul' ).calc_megamenu_responsive_column_widths();

		jQuery(window).resize(function() {
			jQuery( '.fusion-main-menu > ul' ).calc_megamenu_responsive_column_widths();
		});
	}

	// Top Menu last item positioning
	jQuery('.fusion-header-wrapper .fusion-secondary-menu > ul > li:last-child').position_last_top_menu_item();

	fusion_reposition_menu_item( '.fusion-main-menu .fusion-main-menu-cart' );
	fusion_reposition_menu_item( '.fusion-secondary-menu .fusion-menu-login-box' );

	function fusion_reposition_menu_item( $menu_item ) {
		// Position main menu cart dropdown correctly
		if( js_local_vars.header_position == 'Top' ) {
			jQuery( $menu_item ).mouseenter( function(e) {

				if( jQuery(this).find( '> div' ).length && jQuery(this).find( '> div' ).offset().left < 0 ) {
					jQuery (this ).find( '> div' ).css({
						'left': '0',
						'right': 'auto'
					});
				}

				if( jQuery(this).find( '> div' ).length && jQuery( this ).find( '> div' ).offset().left + jQuery( this ).find( '> div' ).width()  > jQuery( window ).width() ) {
					jQuery( this ).find( '> div' ).css({
						'left': 'auto',
						'right': '0'
					});
				}
			});

			jQuery( window ).on( 'resize', function() {
				jQuery( $menu_item ).find( '> div' ).each( function() {
					var $menu_item_dropdown = jQuery( this ),
						$menu_item_dropdown_width = $menu_item_dropdown.outerWidth(),
						$menu_item_dropdown_left_edge = $menu_item_dropdown.offset().left,
						$menu_item_dropdown_right_edge = $menu_item_dropdown_left_edge + $menu_item_dropdown_width,
						$menu_item_left_edge = $menu_item_dropdown.parent().offset().left,
						window_right_edge = jQuery( window ).width();


					if( ! jQuery( 'body.rtl' ).length ) {
						if( ( $menu_item_dropdown_left_edge < $menu_item_left_edge && $menu_item_dropdown_left_edge < 0 ) || ( $menu_item_dropdown_left_edge == $menu_item_left_edge && $menu_item_dropdown_left_edge - $menu_item_dropdown_width < 0 ) ) {
							$menu_item_dropdown.css({
								'left': '0',
								'right': 'auto'
							});
						} else {
							$menu_item_dropdown.css({
								'left': 'auto',
								'right': '0'
							});
						}
					} else {
						if( ( $menu_item_dropdown_left_edge == $menu_item_left_edge && $menu_item_dropdown_right_edge > window_right_edge ) || ( $menu_item_dropdown_left_edge < $menu_item_left_edge && $menu_item_dropdown_right_edge + $menu_item_dropdown_width > window_right_edge )  ) {
							$menu_item_dropdown.css({
								'left': 'auto',
								'right': '0'
							});
						} else {
							$menu_item_dropdown.css({
								'left': '0',
								'right': 'auto'
							});
						}
					}
				});
			});
		}
	}

	// Reinitialize google map on megamenu
	jQuery('.fusion-megamenu-menu').mouseenter(function() {
		if(jQuery(this).find('.shortcode-map').length ) {
			jQuery(this).find('.shortcode-map').each(function() {
				jQuery( this ).reinitialize_google_map();
			});
		}
	});

	// Make iframes in megamenu widget area load correctly in Safari and IE
	// Safari part - load the iframe correctly
    var iframe_loaded = false;
    jQuery( '.fusion-megamenu-menu' ).mouseover(
		function() {
			jQuery( this ).find( '.fusion-megamenu-widgets-container iframe' ).each(
				function() {
					if( ! iframe_loaded ) {
						jQuery( this ).attr('src', jQuery( this ).attr( 'src' ) );
					}
					iframe_loaded = true;
				}
			);
    	}
    );

    // IE part - making the megamenu stay on hover
    jQuery( '.fusion-megamenu-wrapper iframe' ).mouseover(
		function() {
			jQuery( this ).parents( '.fusion-megamenu-widgets-container').css( 'display', 'block' );
			jQuery( this ).parents( '.fusion-megamenu-wrapper' ).css({ 'opacity': '1', 'visibility': 'visible' });
    	}
    );

    jQuery( '.fusion-megamenu-wrapper iframe' ).mouseout(
		function() {
			jQuery( this ).parents( '.fusion-megamenu-widgets-container').css( 'display', '' );
			jQuery( this ).parents( '.fusion-megamenu-wrapper' ).css({ 'opacity': '', 'visibility': '' });
    	}
    );

	// Position main menu cart dropdown correctly on side-header
	jQuery( '.fusion-navbar-nav .cart' ).find( '.cart-contents' ).position_cart_dropdown();

	jQuery(window).on( 'resize', function() {
		jQuery( '.fusion-navbar-nav .cart' ).find( '.cart-contents' ).position_cart_dropdown();
	});

	// Position main menu search form correctly on side-header
	jQuery( '.fusion-navbar-nav .search-link' ).click( function() {
		setTimeout( function() {
			jQuery( '.fusion-navbar-nav .search-link' ).parent().find( '.main-nav-search-form' ).position_menu_search_form();
		}, 5 );
	});

	jQuery(window).on( 'resize', function() {
		jQuery( '.fusion-navbar-nav .main-nav-search' ).find( '.main-nav-search-form' ).position_menu_search_form();
	});

	// Set overflow on the main menu correcty to show dropdowns when needed
	if ( ! jQuery( '.fusion-header-v6' ).length ) {
		jQuery( '.fusion-main-menu' ).mouseover(function() {
			jQuery( this ).css( 'overflow', 'visible' );
		});

		jQuery( '.fusion-main-menu' ).mouseout(function() {
			jQuery( this ).css( 'overflow', '' );
		});
	}

	/**
	 * Mobile Navigation
	 */

	jQuery( '.fusion-mobile-nav-holder' ).not( '.fusion-mobile-sticky-nav-holder' ).each(function() {
		var $mobile_nav_holder = jQuery( this );
		var $mobile_nav = '';
		var $menu = jQuery( this ).parent().find( '.fusion-main-menu, .fusion-secondary-menu' ).not( '.fusion-sticky-menu' );

		if ( $menu.length ) {
			if ( js_local_vars.mobile_menu_design == 'classic' ) {
				$mobile_nav_holder.append( '<div class="fusion-mobile-selector"><span>' + js_local_vars.dropdown_goto + '</span></div>' );
				jQuery( this ).find( '.fusion-mobile-selector' ).append( '<div class="fusion-selector-down"></div>' );
			}

			jQuery( $mobile_nav_holder ).append( jQuery( $menu ).find( '> ul' ).clone() );

			$mobile_nav = jQuery( $mobile_nav_holder ).find( '> ul' );

			$mobile_nav.find( '.fusion-caret, .fusion-menu-login-box .fusion-custom-menu-item-contents, .fusion-menu-cart .fusion-custom-menu-item-contents, .fusion-main-menu-search, li> a > span > .button-icon-divider-left, li > a > span > .button-icon-divider-right' ).remove();

			if ( js_local_vars.mobile_menu_design == 'classic' ) {
				$mobile_nav.find( '.fusion-menu-cart > a' ).html( js_local_vars.mobile_nav_cart );
			} else {
				$mobile_nav.find( '.fusion-main-menu-cart' ).remove();
			}

			$mobile_nav.find( 'li' ).each(function () {
				jQuery( this ).find( '> a > .menu-text' ).removeAttr( 'class' ).addClass( 'menu-text' );

				var classes = 'fusion-mobile-nav-item';

				if( jQuery( this ).hasClass( 'current-menu-item' ) || jQuery( this ).hasClass( 'current-menu-parent' ) || jQuery( this ).hasClass( 'current-menu-ancestor' ) ) {
					classes += ' fusion-mobile-current-nav-item';
				}

				jQuery( this ).attr( 'class', classes );

				if( jQuery( this ).attr( 'id' ) ) {
					jQuery( this ).attr( 'id', jQuery( this ).attr( 'id' ).replace( 'menu-item', 'mobile-menu-item' ) );
				}

				jQuery( this ).attr( 'style', '' );
			});

			jQuery( this ).find( '.fusion-mobile-selector' ).click(function() {
				if( $mobile_nav.hasClass( 'mobile-menu-expanded' ) ) {
					$mobile_nav.removeClass( 'mobile-menu-expanded' );
				} else {
					$mobile_nav.addClass( 'mobile-menu-expanded' );
				}

				$mobile_nav.slideToggle( 200, 'easeOutQuad' );
			});
		}
	});

	jQuery( '.fusion-mobile-sticky-nav-holder' ).each(function() {
		var $mobile_nav_holder = jQuery( this );
		var $mobile_nav = '';
		var $menu = jQuery( this ).parent().find( '.fusion-sticky-menu' );

		if( js_local_vars.mobile_menu_design == 'classic' ) {
			$mobile_nav_holder.append( '<div class="fusion-mobile-selector"><span>' + js_local_vars.dropdown_goto + '</span></div>' );
			jQuery( this ).find( '.fusion-mobile-selector' ).append( '<div class="fusion-selector-down"></div>' );
		}

		jQuery( $mobile_nav_holder ).append( jQuery( $menu ).find( '> ul' ).clone() );

		$mobile_nav = jQuery( $mobile_nav_holder ).find( '> ul' );

		$mobile_nav.find( '.fusion-menu-cart, .fusion-menu-login-box, .fusion-main-menu-search' ).remove();

		$mobile_nav.find( 'li' ).each(function () {
			var classes = 'fusion-mobile-nav-item';

			if( jQuery( this ).hasClass( 'current-menu-item' ) || jQuery( this ).hasClass( 'current-menu-parent' ) || jQuery( this ).hasClass( 'current-menu-ancestor' ) ) {
				classes += ' fusion-mobile-current-nav-item';
			}

			jQuery( this ).attr( 'class', classes );

			if( jQuery( this ).attr( 'id' ) ) {
				jQuery( this ).attr( 'id', jQuery( this ).attr( 'id' ).replace( 'menu-item', 'mobile-menu-item' ) );
			}

			jQuery( this ).attr( 'style', '' );
		});

		jQuery( this ).find( '.fusion-mobile-selector' ).click(function() {
			if( $mobile_nav.hasClass( 'mobile-menu-expanded' ) ) {
				$mobile_nav.removeClass( 'mobile-menu-expanded' );
			} else {
				$mobile_nav.addClass( 'mobile-menu-expanded' );
			}

			$mobile_nav.slideToggle( 200, 'easeOutQuad' );
		});
	});

	// Make megamenu items mobile ready
	jQuery( '.fusion-mobile-nav-holder > ul > li' ).each(function() {
		jQuery( this ).find( '.fusion-megamenu-widgets-container' ).remove();

		jQuery( this ).find( '.fusion-megamenu-holder > ul' ).each( function() {
			jQuery( this ).attr( 'class', 'sub-menu' );
			jQuery( this ).attr( 'style', '' );
			jQuery( this ).find( '> li' ).each( function() {
				// add menu needed menu classes to li elements
				var classes = 'fusion-mobile-nav-item';

				if( jQuery( this ).hasClass( 'current-menu-item' ) || jQuery( this ).hasClass( 'current-menu-parent' ) || jQuery( this ).hasClass( 'current-menu-ancestor' ) || jQuery( this ).hasClass( 'fusion-mobile-current-nav-item' ) ) {
					classes += ' fusion-mobile-current-nav-item';
				}
				jQuery( this ).attr( 'class', classes );

				// Append column titles and title links correctly
				if( ! jQuery( this ).find( '.fusion-megamenu-title a, > a' ).length ) {
					jQuery( this ).find( '.fusion-megamenu-title' ).each( function() {
						if( ! jQuery( this ).children( 'a' ).length ) {
							jQuery( this ).append( '<a href="#">' + jQuery( this ).text() + '</a>' );
						}
					});

					if( ! jQuery( this ).find( '.fusion-megamenu-title' ).length ) {

						var parent_li = jQuery( this );

						jQuery( this ).find( '.sub-menu').each( function() {
							parent_li.after( jQuery( this ) );

						});
						jQuery( this ).remove();
					}
				}
				jQuery( this ).prepend( jQuery( this ).find( '.fusion-megamenu-title a, > a' ) );

				jQuery( this ).find( '.fusion-megamenu-title' ).remove();
			});
			jQuery( this ).closest( '.fusion-mobile-nav-item' ).append( jQuery( this ) );
		});

		jQuery( this ).find( '.fusion-megamenu-wrapper, .caret, .fusion-megamenu-bullet' ).remove();
	});

	// Mobile Modern Menu
	jQuery( '.fusion-mobile-menu-icons .fusion-icon-bars' ).click(function( e ) {
		e.preventDefault();

		var $wrapper;

		if( jQuery( '.fusion-header-v4').length >= 1 || jQuery( '.fusion-header-v5' ).length >= 1 ) {
			$wrapper = '.fusion-secondary-main-menu';
		} else if ( jQuery( '#side-header').length >= 1 ) {
			$wrapper = '#side-header';
		} else {
			$wrapper = '.fusion-header';
		}

		if( jQuery( '.fusion-is-sticky' ).length >= 1 && jQuery( '.fusion-mobile-sticky-nav-holder' ).length >= 1 ) {
			jQuery( $wrapper ).find( '.fusion-mobile-sticky-nav-holder' ).slideToggle( 200, 'easeOutQuad' );
		} else {
			jQuery( $wrapper ).find( '.fusion-mobile-nav-holder' ).not( '.fusion-mobile-sticky-nav-holder' ).slideToggle( 200, 'easeOutQuad' );
		}
	});

	jQuery( '.fusion-mobile-menu-icons .fusion-icon-search' ).click(function( e ) {
		e.preventDefault();

		jQuery( '.fusion-secondary-main-menu .fusion-secondary-menu-search, .side-header-wrapper .fusion-secondary-menu-search' ).slideToggle( 200, 'easeOutQuad' );
	});

	// Collapse mobile menus when on page anchors are clicked
	jQuery( '.fusion-mobile-nav-holder .fusion-mobile-nav-item a:not([href="#"])' ).click( function() {
		var $target = jQuery( this.hash );
		if ( $target.length && this.hash.slice( 1 ) !== '' ) {
			if ( jQuery( this ).parents( '.fusion-mobile-menu-design-classic' ).length ) {
				jQuery( this ).parents( '.fusion-menu, .menu' )
					.hide()
					.removeClass( 'mobile-menu-expanded' );
			} else {
				jQuery( this ).parents( '.fusion-mobile-nav-holder' ).hide();
			}
		}
	});

	// Make mobile menu sub-menu toggles
	if( js_local_vars.submenu_slideout == 1 ) {
		jQuery( '.fusion-mobile-nav-holder > ul li' ).each(function() {
			var classes = 'fusion-mobile-nav-item';

			if( jQuery( this ).hasClass( 'current-menu-item' ) || jQuery( this ).hasClass( 'current-menu-parent' ) || jQuery( this ).hasClass( 'current-menu-ancestor' ) || jQuery( this ).hasClass( 'fusion-mobile-current-nav-item' ) ) {
				classes += ' fusion-mobile-current-nav-item';
			}

			jQuery( this ).attr( 'class', classes );

			if( jQuery( this ).find( ' > ul' ).length ) {
				jQuery( this ).prepend( '<span href="#" aria-haspopup="true" class="fusion-open-submenu"></span>' );

				jQuery( this ).find( ' > ul' ).hide();
			}
		});

		jQuery( '.fusion-mobile-nav-holder .fusion-open-submenu' ).click( function(e) {
			e.stopPropagation();

			jQuery( this ).parent().children( '.sub-menu' ).slideToggle( 200, 'easeOutQuad' );
		});
	}

	// Flyout Menu
	function set_flyout_active_css() {
		jQuery( 'body' ).bind( 'touchmove', function(e){
			if ( ! jQuery( e.target ).parents( '.fusion-flyout-menu' ).length ) {
				e.preventDefault();
			}
		});

		var $wp_adminbar_height = ( jQuery( '#wpadminbar' ).length ) ? jQuery( '#wpadminbar' ).height() : 0,
			$flyout_menu_top_height = jQuery( '.fusion-header-v6-content' ).height() + $wp_adminbar_height;

		// Make usre the menu is opened in a way, that menu items do not collide with the header
		if ( jQuery( '.fusion-header-v6' ).hasClass( 'fusion-flyout-menu-active' ) ) {
			jQuery( '.fusion-header-v6 .fusion-flyout-menu' ).css({
				'height': 'calc(100% - ' + $flyout_menu_top_height + 'px)',
				'margin-top': $flyout_menu_top_height
			});

			if ( jQuery( '.fusion-header-v6 .fusion-flyout-menu .fusion-menu' ).height() > jQuery( '.fusion-header-v6 .fusion-flyout-menu' ).height() ) {
				jQuery( '.fusion-header-v6 .fusion-flyout-menu' ).css( 'display', 'block' );
			}
		}

		// Make sure logo and menu stay sticky on flyout opened, even if sticky header is disabled
		if ( js_local_vars.header_sticky == '0' ) {
			jQuery( '.fusion-header-v6 .fusion-header' ).css({
				'position': 'fixed',
				'width': '100%',
				'max-width': '100%',
				'top': $wp_adminbar_height,
				'z-index': '210'
			});

			jQuery( '.fusion-header-sticky-height' ).css({
				'display': 'block',
				'height': jQuery( '.fusion-header-v6 .fusion-header' ).height()
			});
		}
	}

	function reset_flyout_active_css() {
		setTimeout( function() {
			jQuery( '.fusion-header-v6 .fusion-flyout-menu' ).css( 'display', '' );

			if ( js_local_vars.header_sticky == '0' ) {
				jQuery( '.fusion-header-v6 .fusion-header' ).attr( 'style', '' );
				jQuery( '.fusion-header-sticky-height' ).attr( 'style', '' );
			}
			jQuery( 'body' ).unbind( 'touchmove' );
		}, 250 );
	}

	jQuery( '.fusion-flyout-menu-icons .fusion-flyout-menu-toggle' ).on( 'click', function() {
		var $flyout_content = jQuery( this ).parents( '.fusion-header-v6' );

		if ( $flyout_content.hasClass( 'fusion-flyout-active' ) ) {
			if ( $flyout_content.hasClass( 'fusion-flyout-search-active' ) ) {
				$flyout_content.addClass( 'fusion-flyout-menu-active' );

				set_flyout_active_css();
			} else {
				$flyout_content.removeClass( 'fusion-flyout-active' );
				$flyout_content.removeClass( 'fusion-flyout-menu-active' );

				reset_flyout_active_css();
			}
			$flyout_content.removeClass( 'fusion-flyout-search-active' );
		} else {
			$flyout_content.addClass( 'fusion-flyout-active' );
			$flyout_content.addClass( 'fusion-flyout-menu-active' );

			set_flyout_active_css();
		}
	});

	jQuery( '.fusion-flyout-menu-icons .fusion-flyout-search-toggle' ).on( 'click', function() {
		var $flyout_content = jQuery( this ).parents( '.fusion-header-v6' );

		if ( $flyout_content.hasClass( 'fusion-flyout-active' ) ) {
			if ( $flyout_content.hasClass( 'fusion-flyout-menu-active' ) ) {
				$flyout_content.addClass( 'fusion-flyout-search-active' );

				// Set focus on search field if not on mobiles
				if ( Modernizr.mq( 'only screen and (min-width:'  + parseInt( js_local_vars.side_header_break_point ) +  'px)' ) ) {
					$flyout_content.find( '.fusion-flyout-search .s' ).focus();
				}
			} else {
				$flyout_content.removeClass( 'fusion-flyout-active' );
				$flyout_content.removeClass( 'fusion-flyout-search-active' );

				reset_flyout_active_css();
			}
			$flyout_content.removeClass( 'fusion-flyout-menu-active' );
		} else {
			$flyout_content.addClass( 'fusion-flyout-active' );
			$flyout_content.addClass( 'fusion-flyout-search-active' );

			// Set focus on search field if not on mobiles
			if ( Modernizr.mq( 'only screen and (min-width:'  + parseInt( js_local_vars.side_header_break_point ) +  'px)' ) ) {
				$flyout_content.find( '.fusion-flyout-search .s' ).focus();
			}

			set_flyout_active_css();
		}
	});
});

jQuery( window ).load(function() {
	// Sticky Header
	if( js_local_vars.header_sticky == '1' && ( jQuery( '.fusion-header-wrapper' ).length >= 1 || jQuery( '#side-header' ).length >= 1 )  ) {
		var $animation_duration = 300;
		if( js_local_vars.sticky_header_shrinkage == '0' ) {
			$animation_duration = 0;
		}
		var $header_parent = jQuery( '.fusion-header' ).parent();
		window.$header_parent_height = $header_parent.outerHeight();
		window.$header_height = jQuery( '.fusion-header' ).outerHeight();
		var $menu_height = parseInt( js_local_vars.nav_height );
		var $menu_border_height = parseInt( js_local_vars.nav_highlight_border );
		window.$scrolled_header_height = 65;
		var $logo = ( jQuery( '.fusion-logo img:visible' ).length ) ? jQuery( '.fusion-logo img:visible' ) : '';
		var $sticky_header_scrolled = false;
		window.$sticky_trigger = jQuery( '.fusion-header' );
		window.$sticky_trigger_position = ( window.$sticky_trigger.length ) ? Math.round( window.$sticky_trigger.offset().top ) - window.$wp_adminbar_height - window.$woo_store_notice : 0;
		window.$wp_adminbar_height = ( jQuery( '#wpadminbar' ).length ) ? jQuery( '#wpadminbar' ).height() : 0;
		window.$woo_store_notice = ( jQuery( '.demo_store' ).length ) ? jQuery( '.demo_store' ).outerHeight() : 0;
		window.$sticky_header_type = 1;
		window.$logo_height, window.$main_menu_height;
		window.$slider_offset = 0;
		window.$site_width = jQuery( '#wrapper' ).outerWidth();
		window.$media_query_test_1 = Modernizr.mq( 'only screen and (min-device-width: 768px) and (max-device-width: 1366px) and (orientation: portrait)' ) ||  Modernizr.mq( 'only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape)' );
		window.$media_query_test_2 = Modernizr.mq( 'screen and (max-width: ' + js_local_vars.side_header_break_point + 'px)' );
		window.$media_query_test_3 = Modernizr.mq( 'screen and (max-width: ' + js_local_vars.side_header_break_point + 'px)' );
		window.$media_query_test_4 = Modernizr.mq( 'only screen and (max-width: ' + js_local_vars.side_header_break_point + 'px)' );

		var $standard_logo_height = jQuery( '.fusion-standard-logo' ).height() + parseInt( jQuery( '.fusion-logo' ).data( 'margin-top' ) ) + parseInt( jQuery( '.fusion-logo' ).data( 'margin-bottom' ) );
		window.$initial_desktop_header_height = Math.max( window.$header_height, Math.max( $menu_height + $menu_border_height, $standard_logo_height ) + parseInt( jQuery( '.fusion-header' ).find( '.fusion-row' ).css( 'padding-top' ) ) + parseInt( jQuery( '.fusion-header' ).find( '.fusion-row' ).css( 'padding-bottom' ) ) );
		window.$initial_sticky_header_shrinkage = js_local_vars.sticky_header_shrinkage;
		window.$sticky_can_be_shrinked = true;

		if( js_local_vars.sticky_header_shrinkage == '0' ) {
			$animation_duration = 0;
			window.$scrolled_header_height = window.$header_height;
		}
		if ( $logo ) {
			// Getting the correct natural height of the visible logo
			if ( $logo.hasClass( 'fusion-logo-2x' ) ) {
				var $logo_image = new Image();
				$logo_image.src = $logo.attr( 'src' );
				window.original_logo_height = parseInt( $logo.height() ) + parseInt( js_local_vars.logo_margin_top ) + parseInt( js_local_vars.logo_margin_bottom );
			} else {
				// For normal logo we need to setup the image object to get the natural heights
				var $logo_image = new Image();
				$logo_image.src = $logo.attr( 'src' );
				window.original_logo_height = parseInt( $logo_image.naturalHeight ) + parseInt( js_local_vars.logo_margin_top ) + parseInt( js_local_vars.logo_margin_bottom );

				// IE8, Opera fallback
				$logo_image.onload = function() {
					window.original_logo_height = parseInt( this.height ) + parseInt( js_local_vars.logo_margin_top ) + parseInt( js_local_vars.logo_margin_bottom );
				};
			}
		}

		// Different sticky header behavior for header v4/v5
		// Instead of header with logo, secondary menu is made sticky
		if( jQuery( '.fusion-header-v4' ).length >= 1 || jQuery( '.fusion-header-v5' ).length >= 1 ) {
			window.$sticky_header_type = 2;
			if ( js_local_vars.header_sticky_type2_layout == 'menu_and_logo' || ( Modernizr.mq( 'only screen and (max-width: ' + js_local_vars.side_header_break_point + 'px)' ) && js_local_vars.mobile_menu_design == 'modern' ) ) {
				window.$sticky_trigger = jQuery( '.fusion-sticky-header-wrapper' );
			} else {
				window.$sticky_trigger = jQuery( '.fusion-secondary-main-menu' );
			}
			window.$sticky_trigger_position = Math.round( window.$sticky_trigger.offset().top ) - window.$wp_adminbar_height - window.$woo_store_notice;
		}

		if( window.$sticky_header_type == 1 ) {
			if( Modernizr.mq( 'only screen and (max-width: ' + js_local_vars.side_header_break_point + 'px)' ) ) {
				window.$scrolled_header_height = window.$header_height;
			} else {
				window.$original_sticky_trigger_height = jQuery ( window.$sticky_trigger ).outerHeight();
			}
		}

		if ( window.$sticky_header_type == 2 ) {
			if ( js_local_vars.mobile_menu_design == 'classic' ) {
				jQuery( $header_parent ).height( window.$header_parent_height );
			}

			if( ! Modernizr.mq( 'only screen and (max-width: ' + js_local_vars.side_header_break_point + 'px)' ) ) {
				jQuery( $header_parent ).height( window.$header_parent_height );
			} else {
				window.$scrolled_header_height = window.$header_parent_height;
			}
		}

		// Side Header
		if( jQuery( '#side-header' ).length >= 1 ) {
			window.$sticky_header_type = 3;
		}

		if ( jQuery( document ).height() - ( window.$initial_desktop_header_height - window.$scrolled_header_height ) < jQuery( window ).height() && js_local_vars.sticky_header_shrinkage == 1 ) {
			window.$sticky_can_be_shrinked = false;
			jQuery( '.fusion-header-wrapper' ).removeClass( 'fusion-is-sticky' );
		} else {
			window.$sticky_can_be_shrinked = true;
		}

		var resize_width = jQuery(window).width();
		var resize_height = jQuery(window).height();

		jQuery( window ).resize(function() {
			window.$media_query_test_1 = Modernizr.mq( 'only screen and (min-device-width: 768px) and (max-device-width: 1366px) and (orientation: portrait)' ) ||  Modernizr.mq( 'only screen and (min-device-width: 768px) and (max-device-width: 1024px) and (orientation: landscape)' );
			window.$media_query_test_2 = Modernizr.mq( 'screen and (max-width: ' + js_local_vars.side_header_break_point + 'px)' );
			window.$media_query_test_3 = Modernizr.mq( 'screen and (max-width: ' + js_local_vars.side_header_break_point + 'px)' );
			window.$media_query_test_4 = Modernizr.mq( 'only screen and (max-width: ' + js_local_vars.side_header_break_point + 'px)' );

			if( js_local_vars.header_sticky_tablet != '1' && ( window.$media_query_test_1 ) ) {
				jQuery( '.fusion-header-wrapper, .fusion-header-sticky-height, .fusion-header, .fusion-logo, .fusion-header-wrapper .fusion-main-menu > li a, .fusion-header-wrapper .fusion-secondary-main-menu' ).attr( 'style', '' );
				jQuery( '.fusion-header-wrapper' ).removeClass( 'fusion-is-sticky' );
			} else if( js_local_vars.header_sticky_tablet == '1' && ( window.$media_query_test_1 ) ) {
				$animation_duration = 0;
			}

			if( js_local_vars.header_sticky_mobile != '1' && window.$media_query_test_2 ) {
				jQuery( '.fusion-header-wrapper, .fusion-header-sticky-height, .fusion-header, .fusion-logo, .fusion-header-wrapper .fusion-main-menu > li a, .fusion-header-wrapper .fusion-secondary-main-menu' ).attr( 'style', '' );
				jQuery( '.fusion-header-wrapper' ).removeClass( 'fusion-is-sticky' );
			} else if( js_local_vars.header_sticky_mobile == '1' && window.$media_query_test_2 ) {
				$animation_duration = 0;
			}

			if( jQuery(window).width() != resize_width || jQuery(window).height() != resize_height ) { // check for actual resize
				var $menu_height = parseInt( js_local_vars.nav_height );
				var $menu_border_height = parseInt( js_local_vars.nav_highlight_border );

				if( jQuery( '#wpadminbar' ).length ) {
					window.$wp_adminbar_height = jQuery( '#wpadminbar' ).height();
				} else {
					window.$wp_adminbar_height = 0;
				}

				window.$woo_store_notice = ( jQuery( '.demo_store' ).length ) ? jQuery( '.demo_store' ).outerHeight() : 0;

				if( jQuery( '#wpadminbar' ).length >= 1 && jQuery( '.fusion-is-sticky' ).length >= 1 ) {
					var $sticky_trigger = jQuery( '.fusion-header' );

					if ( window.$sticky_header_type == 2 ) {
						if ( js_local_vars.header_sticky_type2_layout == 'menu_only' && ( js_local_vars.mobile_menu_design == 'classic' || ! window.$media_query_test_4 ) ) {
							$sticky_trigger = jQuery( '.fusion-secondary-main-menu' );
						} else {
							$sticky_trigger = jQuery( '.fusion-sticky-header-wrapper' );
						}
					}

					// Unset the top value for all candidates
					jQuery( '.fusion-header, .fusion-sticky-header-wrapper, .fusion-secondary-main-menu' ).css( 'top', '' );

					// Set top value for coreect selector
					jQuery( $sticky_trigger ).css( 'top', window.$wp_adminbar_height + window.$woo_store_notice );
				}

				// Refresh header v1, v2, v3 and v6
				if ( window.$sticky_header_type == 1 ) {
					js_local_vars.sticky_header_shrinkage = window.$initial_sticky_header_shrinkage;

					if ( jQuery( '.fusion-secondary-header' ).length ) {
						window.$sticky_trigger_position = Math.round( jQuery( '.fusion-secondary-header' ).offset().top )  - window.$wp_adminbar_height - window.$woo_store_notice + jQuery( '.fusion-secondary-header' ).outerHeight();
					// If there is no secondary header, trigger position is 0
					} else {
						window.$sticky_trigger_position = Math.round( jQuery( '.fusion-header' ).offset().top )  - window.$wp_adminbar_height - window.$woo_store_notice;
					}

					// Desktop mode
					if ( ! Modernizr.mq( 'only screen and (max-width: ' + js_local_vars.side_header_break_point + 'px)' ) ) {
						var $logo_height_with_margin = jQuery( '.fusion-logo img:visible' ).outerHeight() + parseInt( js_local_vars.logo_margin_top ) + parseInt( js_local_vars.logo_margin_bottom );
							$main_menu_width = 0;

						// Calculate actual menu width
						jQuery( '.fusion-main-menu > ul > li' ).each( function() {
							$main_menu_width += jQuery( this ).outerWidth();
						});

						if ( jQuery( '.fusion-header-v6' ).length ) {
							$main_menu_width = 0;
						}

						// Sticky desktop header
						if ( jQuery( '.fusion-is-sticky' ).length ) {
							if ( $main_menu_width > ( jQuery( '.fusion-header .fusion-row' ).width() - jQuery( '.fusion-logo img:visible' ).outerWidth() ) ) {
								window.$header_height = jQuery( '.fusion-main-menu' ).outerHeight() + $logo_height_with_margin;

								// Headers v2 and v3 have a 1px bottom border
								if ( jQuery( '.fusion-header-v2' ).length || jQuery( '.fusion-header-v3' ).length ) {
									window.$header_height += 1;
								}
							} else {
								if ( js_local_vars.sticky_header_shrinkage == '0' ) {
									if ( window.original_logo_height > $menu_height + $menu_border_height ) {
										window.$header_height = window.original_logo_height;
									} else {
										window.$header_height = $menu_height + $menu_border_height;
									}

									window.$header_height += parseInt( js_local_vars.header_padding_top ) + parseInt( js_local_vars.header_padding_bottom );

									// Headers v2 and v3 have a 1px bottom border
									if ( jQuery( '.fusion-header-v2' ).length || jQuery( '.fusion-header-v3' ).length ) {
										window.$header_height += 1;
									}
								} else {
									window.$header_height = 65;
								}
							}

							window.$scrolled_header_height = window.$header_height;

							jQuery( '.fusion-header-sticky-height' ).css( 'height', window.$header_height );
							jQuery( '.fusion-header' ).css( 'height', window.$header_height );
						// Non sticky desktop header
						} else {
							if ( $main_menu_width > ( jQuery( '.fusion-header .fusion-row' ).width() - jQuery( '.fusion-logo img:visible' ).outerWidth() ) ) {
								window.$header_height = jQuery( '.fusion-main-menu' ).outerHeight() + $logo_height_with_margin;
								js_local_vars.sticky_header_shrinkage = '0';
							} else {
								if ( window.original_logo_height > $menu_height + $menu_border_height ) {
									window.$header_height = window.original_logo_height;
								} else {
									window.$header_height = $menu_height + $menu_border_height;
								}
							}

							window.$header_height += parseInt( js_local_vars.header_padding_top ) + parseInt( js_local_vars.header_padding_bottom );

							// Headers v2 and v3 have a 1px bottom border
							if ( jQuery( '.fusion-header-v2' ).length || jQuery( '.fusion-header-v3' ).length ) {
								window.$header_height += 1;
							}

							window.$scrolled_header_height = 65;

							if( js_local_vars.sticky_header_shrinkage == '0' ) {
								window.$scrolled_header_height = window.$header_height;
							}

							jQuery( '.fusion-header-sticky-height' ).css( 'height', window.$header_height );
							jQuery( '.fusion-header' ).css( 'height', window.$header_height );
						}
					}

					// Mobile mode
					if ( Modernizr.mq( 'only screen and (max-width: ' + js_local_vars.side_header_break_point + 'px)' ) ) {
						jQuery( '.fusion-header' ).css( 'height', '' );

						window.$header_height = jQuery( '.fusion-header' ).outerHeight();
						window.$scrolled_header_height = window.$header_height;

						jQuery( '.fusion-header-sticky-height' ).css( 'height', window.$scrolled_header_height );
					}
				}

				// Refresh header v4 and v5
				if ( window.$sticky_header_type == 2 ) {
					if ( js_local_vars.mobile_menu_design == 'modern' ) {
						// Desktop mode and sticky active
						if ( ! Modernizr.mq( 'only screen and (max-width: ' + js_local_vars.side_header_break_point + 'px)' ) &&
							 jQuery( '.fusion-is-sticky' ).length &&
							 js_local_vars.header_sticky_type2_layout == 'menu_only'
						) {
							window.$header_parent_height = jQuery( '.fusion-header' ).parent().outerHeight() + jQuery( '.fusion-secondary-main-menu' ).outerHeight();
						} else {
							window.$header_parent_height = jQuery( '.fusion-header' ).parent().outerHeight();
						}
						window.$scrolled_header_height = window.header_parent_height;

						// Desktop Mode
						if ( ! Modernizr.mq( 'only screen and (max-width: ' + js_local_vars.side_header_break_point + 'px)' ) ) {
							window.$header_parent_height = jQuery( '.fusion-header' ).outerHeight() + jQuery( '.fusion-secondary-main-menu' ).outerHeight();
							window.$sticky_trigger_position = Math.round( jQuery( '.fusion-header' ).offset().top )  - window.$wp_adminbar_height - window.$woo_store_notice + jQuery( '.fusion-header' ).outerHeight();

							jQuery( $header_parent ).height( window.$header_parent_height );
							jQuery( '.fusion-header-sticky-height' ).css( 'height', '' );
						}

						// Mobile Mode
						if ( Modernizr.mq( 'only screen and (max-width: ' + js_local_vars.side_header_break_point + 'px)' ) ) {
							// Trigger position basis is fusion-secondary-header, if there is a secondary header
							if ( jQuery( '.fusion-secondary-header' ).length ) {
								window.$sticky_trigger_position = Math.round( jQuery( '.fusion-secondary-header' ).offset().top )  - window.$wp_adminbar_height - window.$woo_store_notice + jQuery( '.fusion-secondary-header' ).outerHeight();
							// If there is no secondary header, trigger position is 0
							} else {
								window.$sticky_trigger_position = Math.round( jQuery( '.fusion-header' ).offset().top )  - window.$wp_adminbar_height - window.$woo_store_notice;
							}

							jQuery( $header_parent ).height( '' );
							jQuery( '.fusion-header-sticky-height' ).css( 'height', jQuery( '.fusion-sticky-header-wrapper' ).outerHeight() ).hide();
						}
					}

					if ( js_local_vars.mobile_menu_design == 'classic' ) {
						window.$header_parent_height = jQuery( '.fusion-header' ).outerHeight() + jQuery( '.fusion-secondary-main-menu' ).outerHeight();
						window.$sticky_trigger_position = Math.round( jQuery( '.fusion-header' ).offset().top ) - window.$wp_adminbar_height - window.$woo_store_notice + jQuery( '.fusion-header' ).outerHeight();

						jQuery( $header_parent ).height( window.$header_parent_height );
					}
				}

				// Refresh header v3
				if ( window.$sticky_header_type == 3 ) {
					var $position_top = '';
					// Desktop mode
					if ( ! Modernizr.mq( 'only screen and (max-width:' + js_local_vars.side_header_break_point + 'px)' ) ) {
						jQuery( '#side-header-sticky' ).css({
							height: '',
							top: ''
						});

						if ( jQuery( '#side-header' ).hasClass( 'fusion-is-sticky' ) ) {
							jQuery( '#side-header' ).css({
								top: ''
							});

							jQuery( '#side-header' ).removeClass( 'fusion-is-sticky' );
						}
					}
				}

				if ( jQuery( document ).height() - ( window.$initial_desktop_header_height - window.$scrolled_header_height ) < jQuery( window ).height() && js_local_vars.sticky_header_shrinkage == 1 ) {
					window.$sticky_can_be_shrinked = false;
					jQuery( '.fusion-header-wrapper' ).removeClass( 'fusion-is-sticky' );
					jQuery( '.fusion-header-sticky-height' ).hide();
					jQuery( '.fusion-header' ).css( 'height', '' );

					jQuery( '.fusion-logo' ).css({
						'margin-top': '',
						'margin-bottom': ''
					});

					jQuery( '.fusion-main-menu > ul > li > a' ).css({
						'height': '',
						'line-height': ''
					});

					jQuery( '.fusion-logo img' ).css( 'height', '' );
				} else {
					window.$sticky_can_be_shrinked = true;

					// Resizing sticky header
					if( jQuery( '.fusion-is-sticky' ).length >= 1 ) {
						if( window.$sticky_header_type == 1 && ! Modernizr.mq( 'only screen and (max-width: ' + js_local_vars.side_header_break_point + 'px)' ) ) {
							// Animate Header Height
							if( ! Modernizr.mq( 'only screen and (max-width: ' + js_local_vars.side_header_break_point + 'px)' ) ) {
								if ( window.$header_height == window.$initial_desktop_header_height ) {
									jQuery( window.$sticky_trigger ).stop(true, true).animate({
										height: window.$scrolled_header_height
									}, { queue: false, duration: $animation_duration, easing: 'easeOutCubic', complete: function() {
										jQuery(this).css( 'overflow', 'visible' );
									} });
									jQuery( '.fusion-header-sticky-height' ).show();
									jQuery( '.fusion-header-sticky-height' ).stop(true, true).animate({
										height: window.$scrolled_header_height
									}, { queue: false, duration: $animation_duration, easing: 'easeOutCubic', complete: function() {
										jQuery(this).css( 'overflow', 'visible' );
									} });
								} else {
									jQuery( '.fusion-header-sticky-height' ).show();
								}
							} else {
								jQuery( '.fusion-header-sticky-height' ).css( 'height', window.$scrolled_header_height ).show();
							}

							// Animate Logo
							if( js_local_vars.sticky_header_shrinkage == '1' && window.$header_height == window.$initial_desktop_header_height ) {
								if( $logo ) {
									var $scrolled_logo_height = $logo.height();

									if(  $scrolled_logo_height < window.$scrolled_header_height - 10 ) {
										var $scrolled_logo_container_margin = ( window.$scrolled_header_height - $scrolled_logo_height ) / 2;
									} else {
										$scrolled_logo_height = window.$scrolled_header_height - 10;
										var $scrolled_logo_container_margin = 5;
									}

									$logo.stop(true, true).animate({
										'height': $scrolled_logo_height
									}, { queue: false, duration: $animation_duration, easing: 'easeOutCubic', complete: function() {
										jQuery(this).css( 'display', '' );
									}, step: function() {
										jQuery(this).css( 'display', '' );
									} });
								}

								jQuery( '.fusion-logo' ).stop(true, true).animate({
									'margin-top': $scrolled_logo_container_margin,
									'margin-bottom': $scrolled_logo_container_margin
								}, { queue: false, duration: $animation_duration, easing: 'easeOutCubic' });

								// Animate Menu Height
								if ( ! jQuery( '.fusion-header-v6' ).length ) {
									jQuery( '.fusion-main-menu > ul > li > a' ).stop(true, true).animate({
										height: window.$scrolled_header_height - $menu_border_height,
										'line-height': window.$scrolled_header_height - $menu_border_height
									}, { queue: false, duration: $animation_duration, easing: 'easeOutCubic' });
								}
							}
						}
					}
				}

				resize_width = jQuery(window).width();
				resize_height = jQuery(window).height();
			}
		}); // end resize event


		jQuery( window ).scroll(function() {

			if ( window.$sticky_can_be_shrinked ) {
				if( js_local_vars.header_sticky_tablet != '1' && ( window.$media_query_test_1 ) ) {
					return;
				} else if( js_local_vars.header_sticky_tablet == '1' && ( window.$media_query_test_1 ) ) {
					$animation_duration = 0;
				}

				if( js_local_vars.header_sticky_mobile != '1' && window.$media_query_test_2 ) {
					return;
				} else if( js_local_vars.header_sticky_mobile == '1' && window.$media_query_test_2 ) {
					$animation_duration = 0;
				}

				if( window.$sticky_header_type == 3 && js_local_vars.header_sticky_mobile != '1' ) {
					return;
				}

				if( window.$sticky_header_type == 3 && js_local_vars.header_sticky_mobile == '1' && ! window.$media_query_test_3 ) {
					return;
				}

				// Change the sticky trigger position to the bottom of the mobile menu
				if( jQuery( '.fusion-is-sticky' ).length == 0 && jQuery( '.fusion-header, .fusion-secondary-main-menu' ).find( '.fusion-mobile-nav-holder > ul' ).is( ':visible' ) ) {
					window.$sticky_trigger_position = Math.round( jQuery( '.fusion-header, .fusion-sticky-header-wrapper' ).find( '.fusion-mobile-nav-holder:visible' ).offset().top ) - window.$wp_adminbar_height - window.$woo_store_notice + jQuery( '.fusion-header, .fusion-sticky-header-wrapper' ).find( '.fusion-mobile-nav-holder:visible' ).height();
				}

				// If sticky header is not active, reassign the triggers
				if( window.$sticky_header_type != 3 && jQuery( '.fusion-is-sticky' ).length == 0 && ! jQuery( '.fusion-header, .fusion-secondary-main-menu' ).find( '.fusion-mobile-nav-holder > ul' ).is( ':visible' ) ) {
					window.$sticky_trigger = jQuery( '.fusion-header' );
					window.$sticky_trigger_position = Math.round( window.$sticky_trigger.offset().top )  - window.$wp_adminbar_height - window.$woo_store_notice;

					if( window.$sticky_header_type == 2 ) {
						if ( js_local_vars.header_sticky_type2_layout == 'menu_and_logo' || ( window.$media_query_test_4 && js_local_vars.mobile_menu_design == 'modern' ) ) {
							window.$sticky_trigger = jQuery( '.fusion-sticky-header-wrapper' );
						} else {
							window.$sticky_trigger = jQuery( '.fusion-secondary-main-menu' );
						}
						window.$sticky_trigger_position = Math.round( window.$sticky_trigger.offset().top )  - window.$wp_adminbar_height - window.$woo_store_notice;
					}

					// set sticky header height for header v4 and v5
					if( js_local_vars.mobile_menu_design == 'modern' && window.$sticky_header_type == 2 && ( window.$media_query_test_4 || js_local_vars.header_sticky_type2_layout == 'menu_and_logo' ) ) {
						// Refresh header height on scroll
						window.$header_height = jQuery( window.$sticky_trigger ).outerHeight();
						window.$scrolled_header_height = window.$header_height;
						jQuery( '.fusion-header-sticky-height' ).css( 'height', window.$scrolled_header_height ).show();
					}
				}

				if( jQuery( window ).scrollTop() > window.$sticky_trigger_position ) { // sticky header mode
					if( $sticky_header_scrolled == false ) {
						var $wp_adminbar_height = 0;
						var $woo_store_notice = ( jQuery( '.demo_store' ).length ) ? jQuery( '.demo_store' ).outerHeight() : 0;

						if ( jQuery( '#wpadminbar' ).length ) {
							$wp_adminbar_height = jQuery( '#wpadminbar' ).height();
						}



						jQuery( '.fusion-header-wrapper' ).addClass( 'fusion-is-sticky' );
						jQuery( window.$sticky_trigger ).css( 'top', $wp_adminbar_height + $woo_store_notice );
						$logo = jQuery( '.fusion-logo img:visible' );

						// Hide all mobile menus
						if( js_local_vars.mobile_menu_design == 'modern' ) {
							jQuery( '.fusion-header, .fusion-secondary-main-menu' ).find( '.fusion-mobile-nav-holder' ).hide();
							jQuery( '.fusion-secondary-main-menu .fusion-main-menu-search .fusion-custom-menu-item-contents' ).hide();
						} else {
							jQuery( '.fusion-header, .fusion-secondary-main-menu' ).find( '.fusion-mobile-nav-holder > ul' ).hide();
						}

						if( js_local_vars.mobile_menu_design == 'modern' ) {
							// hide normal mobile menu if sticky menu is set in sticky header
							if( jQuery( '.fusion-is-sticky' ).length >= 1 && jQuery( '.fusion-mobile-sticky-nav-holder' ).length >= 1 && jQuery( '.fusion-mobile-nav-holder' ).is( ':visible' ) ) {
								jQuery( '.fusion-mobile-nav-holder' ).not( '.fusion-mobile-sticky-nav-holder' ).hide();
							}
						}

						if( js_local_vars.layout_mode == 'boxed' ) {
							jQuery( window.$sticky_trigger ).css( 'max-width', window.$site_width );
						}

						if( window.$sticky_header_type == 1 ) {
							// Animate Header Height

							if( ! Modernizr.mq( 'only screen and (max-width: ' + js_local_vars.side_header_break_point + 'px)' ) ) {
								if ( window.$header_height == window.$initial_desktop_header_height ) {
									jQuery( window.$sticky_trigger ).stop( true, true ).animate({
										height: window.$scrolled_header_height
									}, { queue: false, duration: $animation_duration, easing: 'easeOutCubic', complete: function() {
										jQuery( this ).css( 'overflow', 'visible' );
									} });

									jQuery( '.fusion-header-sticky-height' ).show();
									jQuery( '.fusion-header-sticky-height' ).stop( true, true ).animate({
										height: window.$scrolled_header_height
									}, { queue: false, duration: $animation_duration, easing: 'easeOutCubic', complete: function() {
										jQuery( this ).css( 'overflow', 'visible' );
									} });
								} else {
									jQuery( '.fusion-header-sticky-height' ).show();
								}
							} else {
								jQuery( '.fusion-header-sticky-height' ).css( 'height', window.$scrolled_header_height ).show();
							}

							// Add sticky shadow
							setTimeout( function() {
								jQuery( '.fusion-header' ).addClass( 'fusion-sticky-shadow' );
							}, 150 );

							if( js_local_vars.sticky_header_shrinkage == '1' && window.$header_height == window.$initial_desktop_header_height ) {
								// Animate header padding
								jQuery( window.$sticky_trigger ).find( '.fusion-row' ).stop( true, true ).animate({
									'padding-top': 0,
									'padding-bottom': 0
								}, { queue: false, duration: $animation_duration, easing: 'easeOutCubic' });

								// Animate Logo
								if( $logo ) {
									var $scrolled_logo_height = $logo.height();

									$logo.attr( 'data-logo-height', $logo.height() );
									$logo.attr( 'data-logo-width', $logo.width() );

									if(  $scrolled_logo_height < window.$scrolled_header_height - 10 ) {
										var $scrolled_logo_container_margin = ( window.$scrolled_header_height - $scrolled_logo_height ) / 2;
									} else {
										$scrolled_logo_height = window.$scrolled_header_height - 10;
										var $scrolled_logo_container_margin = 5;
									}

									$logo.stop( true, true ).animate({
										'height': $scrolled_logo_height
									}, { queue: false, duration: $animation_duration, easing: 'easeOutCubic', complete: function() {
										jQuery( this ).css( 'display', '' );
									}, step: function() {
										jQuery( this ).css( 'display', '' );
									} });
								}

								jQuery( '.fusion-logo' ).stop( true, true ).animate({
									'margin-top': $scrolled_logo_container_margin,
									'margin-bottom': $scrolled_logo_container_margin
								}, { queue: false, duration: $animation_duration, easing: 'easeOutCubic' });

								// Animate Menu Height
								if ( ! jQuery( '.fusion-header-v6' ).length ) {
									jQuery( '.fusion-main-menu > ul > li > a' ).stop( true, true ).animate({
										height: window.$scrolled_header_height - $menu_border_height,
										'line-height': window.$scrolled_header_height - $menu_border_height
									}, { queue: false, duration: $animation_duration, easing: 'easeOutCubic' });
								}
							}

						}

						if ( window.$sticky_header_type == 2 ) {
							if ( js_local_vars.header_sticky_type2_layout == 'menu_and_logo' ) {
								jQuery( window.$sticky_trigger ).css( 'height', '' );

								// Refresh header height on scroll
								window.$header_height = jQuery( window.$sticky_trigger ).outerHeight();
								window.$scrolled_header_height = window.$header_height;
								jQuery( window.$sticky_trigger ).css( 'height', window.$scrolled_header_height );
								jQuery( '.fusion-header-sticky-height' ).css( 'height', window.$scrolled_header_height );
							}

							jQuery( '.fusion-header-sticky-height' ).show();
						}

						if( window.$sticky_header_type == 3 && Modernizr.mq( 'only screen and (max-width:' + js_local_vars.side_header_break_point + 'px)' ) ) {
							jQuery( '#side-header-sticky' ).css({
								height: jQuery( '#side-header' ).outerHeight()
							});

							jQuery( '#side-header' ).css({
								position: 'fixed',
								top: $wp_adminbar_height + $woo_store_notice
							}).addClass( 'fusion-is-sticky' );
						}

						$sticky_header_scrolled = true;
					}
				} else if( jQuery( window ).scrollTop() <= window.$sticky_trigger_position ) {
					jQuery( '.fusion-header-wrapper' ).removeClass( 'fusion-is-sticky' );
					jQuery( '.fusion-header' ).removeClass( 'fusion-sticky-shadow' );
					$logo = jQuery( '.fusion-logo img:visible' );

					if( js_local_vars.mobile_menu_design == 'modern' ) {
						// hide sticky menu if sticky menu is set in normal header
						if( jQuery( '.fusion-is-sticky' ).length == 0 && jQuery( '.fusion-mobile-sticky-nav-holder' ).length >= 1 && jQuery( '.fusion-mobile-nav-holder' ).is( ':visible' ) ) {
							jQuery( '.fusion-mobile-sticky-nav-holder' ).hide();
						}
					}

					if( window.$sticky_header_type == 1 ) {
						// Animate Header Height to Original Size
						if( ! Modernizr.mq( 'only screen and (max-width: ' + js_local_vars.side_header_break_point + 'px)' ) ) {
							// Done to make sure that resize event while sticky is active doesn't lead to no animation on scroll up
							if ( window.$sticky_header_type == 1 && window.$header_height == 65 ) {
								window.$header_height = window.$initial_desktop_header_height;
							}

							if ( window.$header_height == window.$initial_desktop_header_height ) {
								jQuery( window.$sticky_trigger ).stop( true, true ).animate({
									height: window.$header_height
								}, { queue: false, duration: $animation_duration, easing: 'easeOutCubic', complete: function() {
									jQuery( this ).css( 'overflow', 'visible' );
								}, step: function() {
									jQuery( this ).css( 'overflow', 'visible' );
								} });

								jQuery( '.fusion-header-sticky-height' ).stop( true, true ).animate({
									height: window.$header_height
								}, { queue: false, duration: $animation_duration, easing: 'easeOutCubic', complete: function() {
									jQuery( this ).css( 'overflow', 'visible' );
								}, step: function() {
									jQuery( this ).css( 'overflow', 'visible' );
								} });
							}
							jQuery( '.fusion-header-sticky-height' ).hide();
						} else {
							jQuery( '.fusion-header-sticky-height' ).hide().css( 'height', window.$header_height + $menu_border_height );
						}

						if( js_local_vars.sticky_header_shrinkage == '1' && window.$header_height == window.$initial_desktop_header_height ) {
							// Animate header padding to Original Size
							jQuery( window.$sticky_trigger ).find( '.fusion-row' ).stop( true, true ).animate({
								'padding-top': js_local_vars.header_padding_top,
								'padding-bottom': js_local_vars.header_padding_bottom
							}, { queue: false, duration: $animation_duration, easing: 'easeOutCubic' });

							// Animate Logo to Original Size
							if( $logo ) {
								$logo.stop( true, true ).animate({
									'height': $logo.data( 'logo-height' )
								}, { queue: false, duration: $animation_duration, easing: 'easeOutCubic', complete: function() {
									jQuery( this ).css( 'display', '' );
									jQuery( '.fusion-sticky-logo-1x, .fusion-sticky-logo-2x' ).css( 'height', '' );
								} });
							}

							jQuery( '.fusion-logo' ).stop( true, true ).animate({
								'margin-top': jQuery('.fusion-logo').data('margin-top'),
								'margin-bottom': jQuery('.fusion-logo').data('margin-bottom')
							}, { queue: false, duration: $animation_duration, easing: 'easeOutCubic' });

							// Animate Menu Height to Original Size
							if ( ! jQuery( '.fusion-header-v6' ).length ) {
								jQuery( '.fusion-main-menu > ul > li > a' ).stop( true, true ).animate({
									height: $menu_height,
									'line-height': $menu_height
								}, { queue: false, duration: $animation_duration, easing: 'easeOutCubic' });
							}
						}
					}

					if( window.$sticky_header_type == 2 ) {
						jQuery( '.fusion-header-sticky-height' ).hide();

						if ( js_local_vars.header_sticky_type2_layout == 'menu_and_logo' ) {
							jQuery( window.$sticky_trigger ).css( 'height', '' );

							// Refresh header height on scroll
							window.$header_height = jQuery( window.$sticky_trigger ).outerHeight();
							window.$scrolled_header_height = window.$header_height;
							jQuery( window.$sticky_trigger ).css( 'height', window.$scrolled_header_height );
							jQuery( '.fusion-header-sticky-height' ).css( 'height', window.$scrolled_header_height );
						}


					}

					if( window.$sticky_header_type == 3 && Modernizr.mq( 'only screen and (max-width:' + js_local_vars.side_header_break_point + 'px)' ) ) {
						jQuery( '#side-header-sticky' ).css({
							height: ''
						});

						jQuery( '#side-header' ).css({
							'position': ''
						}).removeClass( 'fusion-is-sticky' );
					}

					$sticky_header_scrolled = false;
				}

			}
		});

		jQuery( window ).trigger( 'scroll' ); // trigger scroll for page load


	}

	// adjust mobile menu when it falls to 2 rows
	var mobile_menu_sep_added = false;

	function adjust_mobile_menu_settings() {
		var menu_width = 0;

		if( Modernizr.mq( 'only screen and (max-width: ' + js_local_vars.side_header_break_point + 'px)' ) ) {
			jQuery( '.fusion-secondary-menu > ul' ).children( 'li' ).each( function() {
				menu_width += jQuery( this ).outerWidth( true ) + 2;
			});

			if( menu_width > jQuery( window ).width() && jQuery( window ).width() > 318 ) {
				if( ! mobile_menu_sep_added ) {
					jQuery( '.fusion-secondary-menu > ul' ).append( '<div class="fusion-mobile-menu-sep"></div>' );
					jQuery( '.fusion-secondary-menu > ul' ).css( 'position', 'relative' );
					jQuery( '.fusion-mobile-menu-sep' ).css( {
						'position': 'absolute',
						'top': jQuery( '.fusion-secondary-menu > ul > li' ).height() - 1 + 'px',
						'width': '100%',
						'border-bottom-width': '1px',
						'border-bottom-style': 'solid'
					});
					mobile_menu_sep_added = true;
				}
			} else {
				jQuery( '.fusion-secondary-menu > ul' ).css( 'position', '' );
				jQuery( '.fusion-secondary-menu > ul' ).find( '.fusion-mobile-menu-sep' ).remove();
				mobile_menu_sep_added = false;
			}
		} else {
			jQuery( '.fusion-secondary-menu > ul' ).css( 'position', '' );
			jQuery( '.fusion-secondary-menu > ul' ).find( '.fusion-mobile-menu-sep' ).remove();
			mobile_menu_sep_added = false;
		}
	}

	adjust_mobile_menu_settings();

	jQuery( window ).on( 'resize', function() {
		adjust_mobile_menu_settings();
	});
});

// Reintalize scripts after ajax
jQuery( document ).ajaxComplete( function() {
	jQuery( window ).trigger( 'scroll' ); // trigger scroll for page load

	if( jQuery( '.fusion-is-sticky' ).length >= 1 && window.$sticky_trigger && window.$sticky_header_type != 3 ) {
		var $sticky_trigger = jQuery( window.$sticky_trigger ),
			$menu_border_height = parseInt( js_local_vars.nav_highlight_border ),
			$menu_height = $sticky_trigger.height() - $menu_border_height;

		if ( window.$sticky_header_type == 2 ) {
			$sticky_trigger = jQuery( '.fusion-secondary-main-menu' );
			$menu_height = $sticky_trigger.find( '.fusion-main-menu > ul > li > a' ).height();
		}

		jQuery( '.fusion-main-menu > ul > li > a' ).css({
			height: $menu_height + 'px',
			'line-height': $menu_height + 'px'
		});
	}
});
