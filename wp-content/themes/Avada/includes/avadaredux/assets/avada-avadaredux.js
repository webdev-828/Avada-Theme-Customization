jQuery( document ).ready( function() {

	// Activate the Avada admin menu theme option entry when theme options are active
	if ( jQuery( 'a[href="themes.php?page=avada_options"]' ).hasClass( 'current' ) ) {
		var $avada_menu = jQuery( '#toplevel_page_avada' );

		$avada_menu.addClass( 'wp-has-current-submenu wp-menu-open' );
		$avada_menu.children( 'a' ).addClass( 'wp-has-current-submenu wp-menu-open' );
		$avada_menu.children( '.wp-submenu' ).find( 'li' ).last().addClass( 'current' );
		$avada_menu.children( '.wp-submenu' ).find( 'li' ).last().children().addClass( 'current' );

		// do not show the appearance menu as active
		jQuery( '#menu-appearance a[href="themes.php"]' ).removeClass( 'wp-has-current-submenu wp-menu-open' );
		jQuery( '#menu-appearance' ).removeClass( 'wp-has-current-submenu wp-menu-open' );
		jQuery( '#menu-appearance' ).addClass( 'wp-not-current-submenu' );
		jQuery( '#menu-appearance a[href="themes.php"]' ).addClass( 'wp-not-current-submenu' );
		jQuery( '#menu-appearance' ).children( '.wp-submenu' ).find( 'li' ).removeClass( 'current' );
	}

	var $parent_element = jQuery( '#' + avada_avadaredux_vars.option_name + '-social_media_icons .avadaredux-repeater-accordion' );

	// Initialize avadaredux color fields, even when they are insivible
	avadaredux.field_objects.color.init( $parent_element.find( '.avadaredux-container-color ' ) );

	$parent_element.set_social_media_repeater_custom_field_logic();

	jQuery( '.avadaredux-repeaters-add' ).click( function() {
		setTimeout( function() {
			$parent_element = jQuery( '#' + avada_avadaredux_vars.option_name + '-social_media_icons .avadaredux-repeater-accordion' );
			$parent_element.set_social_media_repeater_custom_field_logic();
		}, 50 );
	});

	// Make sure the sub menu flyouts are closed, when a new menu item is activated
	jQuery( '.avadaredux-group-tab-link-li a' ).click( function() {
		jQuery( '.avadaredux-group-tab-link-li' ).removeClass( 'avada-section-hover' );
		jQuery.avadaredux.required();
		jQuery.avadaredux.check_active_tab_dependencies();
	});

	// Make submenus flyout when a main menu item is hovered
	jQuery( '.avadaredux-group-tab-link-li.hasSubSections' ).each( function() {
		jQuery( this ).mouseenter( function() {
			if ( ! jQuery( this ).hasClass( 'activeChild' ) ) {
				jQuery( this ).addClass( 'avada-section-hover' );
			}
		});

		jQuery( this ).mouseleave( function() {
			jQuery( this ).removeClass( 'avada-section-hover' );
		});
	});

	// Add a pattern preview container to show off the background patterns
	jQuery( '#avada_theme_options-bg_pattern' ).append( '<div class="avada-pattern-preview"></div>' );

	// On pattern image click update the preview
	jQuery( '#avada_theme_options-bg_pattern' ).find( 'ul li img' ).click( function() {
		$background = 'url("' + jQuery( this ).attr( 'src' ) + '") repeat';
		jQuery( '.avada-pattern-preview' ).css( 'background', $background );
	});

	// Setup tooltips on color presets
	jQuery( '#avada_theme_options-scheme_type, #avada_theme_options-color_scheme' ).find( ' ul li img' ).qtip({
		content: {
			//title: avada_avadaredux_vars.color_scheme,
			attr: 'alt'
		},
		position: {
			my: 'bottom center',
			at: 'top center'
		},
		style: {
			classes: 'avada-tooltip qtip-light qtip-rounded qtip-shadow'
		}
	});

	// Color picker fallback for pre WP 4.4 versions
	jQuery( '.wp-color-result' ).on( 'click', function() {
		jQuery( this ).parent().addClass( 'wp-picker-active' );
	});

	jQuery( '#avada_theme_options-header_layout img' ).on( 'click', function() {
		// Auto adjust main menu height
		var $header_version = jQuery( this ).attr( 'alt' ),
			$main_menu_height = '0';

		if ( $header_version == 'v1' || $header_version == 'v2' || $header_version == 'v3' ) {
			$main_menu_height = '83';
		} else {
			$main_menu_height = '40';
		}

		jQuery( 'input#nav_height' ).val( $main_menu_height );

		// Auto adjust logo margin
		if ( $header_version == 'v4' ) {
			jQuery( '#avada_theme_options-logo_margin .avadaredux-spacing-bottom, #avada_theme_options-logo_margin #logo_margin-bottom' ).val( '0px' );
		} else {
			jQuery( '#avada_theme_options-logo_margin .avadaredux-spacing-bottom, #avada_theme_options-logo_margin #logo_margin-bottom' ).val( '31px' );
		}
		jQuery( '#avada_theme_options-logo_margin .avadaredux-spacing-top, #avada_theme_options-logo_margin #logo_margin-top' ).val( '31px' );

		// Auto adjust header v2 topbar color
		if ( $header_version == 'v2' ) {
			jQuery( '#avada_theme_options-header_top_bg_color #header_top_bg_color-color' ).val( '#fff' );
		} else {
			jQuery( '#avada_theme_options-header_top_bg_color #header_top_bg_color-color' ).val( jQuery( '#primary_color-color' ).val() );
		}
	});

	jQuery( '#avada_theme_options-header_position label' ).on ('click', function() {
		var $header_position = jQuery( this ).find( 'span' ).text(),
			$header_version = jQuery( '#avada_theme_options-header_layout' ).find( '.avadaredux-image-select-selected img' ).attr( 'alt' );

		// Auto adjust main menu height
		if ( $header_position == 'Top' ) {
			if ( $header_version == 'v1' || $header_version == 'v2' || $header_version == 'v3' ) {
				$main_menu_height = '83';
			} else {
				$main_menu_height = '40';
			}
		} else {
			$main_menu_height = '40';
		}
		jQuery( 'input#nav_height' ).val( $main_menu_height );

		// Auto set header padding
		jQuery( '#avada_theme_options-header_padding input' ).val( '0px' );
		if ( $header_position != 'Top' ) {
			jQuery( '#avada_theme_options-header_padding input.avadaredux-spacing-left, #avada_theme_options-header_padding #header_padding-left, #avada_theme_options-header_padding input.avadaredux-spacing-right, #avada_theme_options-header_padding #header_padding-right' ).val( '60px' );
		}

		// Auto adjust logo margin
		jQuery( '#avada_theme_options-logo_margin .avadaredux-spacing-top, #avada_theme_options-logo_margin #logo_margin-top, #avada_theme_options-logo_margin .avadaredux-spacing-bottom, #avada_theme_options-logo_margin #logo_margin-bottom' ).val( '31px' );
		if ( $header_position == 'Top' && $header_version == 'v4' ) {
			jQuery( '#avada_theme_options-logo_margin .avadaredux-spacing-bottom, #avada_theme_options-logo_margin #logo_margin-bottom' ).val( '0px' );
		}
	});

});

jQuery( window ).load(function() {
	// If search field is not empty, make sidebar accessible again when an item is clicked and clear the search field
	jQuery( '.avadaredux-sidebar a' ).click(function() {
		if ( jQuery( '.avadaredux_field_search' ).val() != '' ) {
			if ( jQuery( this ).parent().hasClass( 'hasSubSections' ) ) {
				var $tab_to_activate_id  = jQuery( this ).data( 'rel' ) + 1;
			} else {
				$tab_to_activate_id = jQuery( this ).data( 'rel' );
			}

			var $tab_to_activate = '#' + $tab_to_activate_id + '_section_group',
				$avadaredux_option_tab_extras = jQuery( '.avadaredux-container' ).find('.avadaredux-section-field, .avadaredux-info-field, .avadaredux-notice-field, .avadaredux-container-group, .avadaredux-section-desc, .avadaredux-group-tab h3, .avadaredux-accordion-field');

			// Show the correct tab

			jQuery( '.avadaredux-main' ).find( '.avadaredux-group-tab' ).not( $tab_to_activate ).hide();
			jQuery('.avadaredux-accordian-wrap').hide();
			$avadaredux_option_tab_extras.show();
			jQuery('.form-table tr').show();
			jQuery('.form-table tr.hide').hide();
			jQuery('.avadaredux-notice-field.hide').hide();

			jQuery( '.avadaredux-container' ).removeClass( 'avada-avadaredux-search' );
			jQuery( '.avadaredux_field_search' ).val( '' );
			jQuery( '.avadaredux_field_search' ).trigger( 'change' );
		}
	});

	jQuery( '.avadaredux_field_search' ).typeWatch({

		callback:function( $search_string ){
			$search_string = $search_string.toLowerCase();

			if ( $search_string !== '' && $search_string !== null && typeof $search_string !== 'undefined' && $search_string.length > 2 ) {
				jQuery( '.avadaredux-sidebar .avadaredux-group-menu' ).find( 'li' ).removeClass( 'activeChild' ).removeClass( 'active' );
				jQuery( '.avadaredux-sidebar .avadaredux-group-menu' ).find( '.submenu' ).hide();

			} else {
				var $tab = jQuery.cookie( 'avadaredux_current_tab' );

				if ( jQuery( '#' + $tab + '_section_group_li' ).parents( '.hasSubSections' ).length ) {
					jQuery( '#' + $tab + '_section_group_li' ).parents( '.hasSubSections' ).addClass( 'activeChild' );
					jQuery( '#' + $tab + '_section_group_li' ).parents( '.hasSubSections' ).find( '.submenu' ).show();
				}
				jQuery( '#' + $tab + '_section_group_li' ).addClass( 'active' );
			}
		},

		wait: 500,
		highlight: false,
		captureLength: 0,

	} );
});

jQuery.fn.set_social_media_repeater_custom_field_logic = function() {
	jQuery( this ).each( function( i, obj ) {

		var $icon_select    = jQuery( '#icon-' + i + '-select' );
		var $custom_fields  = jQuery( '#' + avada_avadaredux_vars.option_name + '-custom_title-' + i + ', #' + avada_avadaredux_vars.option_name + '-custom_source-' + i );

		// Get the initial value of the select input and depending on its value
		// show or hide the custom icon input elements
		if ( 'custom' == $icon_select.val() ) {
			// show input fields & headers
			$custom_fields.show();
			$custom_fields.prev().show();
		} else {
			// hide input fields & headers
			$custom_fields.hide();
			$custom_fields.prev().hide();
		}

		if ( ! $icon_select.val() ) {
			$icon_select.parents( '.ui-accordion-content' ).css( 'height', '' );
		}

		// check if the value of the select has changed and show/hide the elements conditionally.
		$icon_select.change( function() {
			$icon_select.parents( '.ui-accordion-content' ).css( 'height', '' );

			if ( 'custom' == jQuery( this ).val() ) {
				// show input fields & headers
				$custom_fields.show();
				$custom_fields.prev().show();
			} else {
				// hide input fields & headers
				$custom_fields.hide();
				$custom_fields.prev().hide();
			}
		});
	});
};
