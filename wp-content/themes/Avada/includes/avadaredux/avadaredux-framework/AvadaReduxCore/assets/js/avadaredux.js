/* global avadaredux, confirm, relid:true, jsonView */

(function( $ ) {
	'use strict';

	$.avadaredux = $.avadaredux || {};

	$( document ).ready(
		function() {
			$.fn.isOnScreen = function() {
				if ( !window ) {
					return;
				}

				var win = $( window );
				var viewport = {
					top: win.scrollTop(),
				};

				viewport.right = viewport.left + win.width();
				viewport.bottom = viewport.top + win.height();

				var bounds = this.offset();

				bounds.right = bounds.left + this.outerWidth();
				bounds.bottom = bounds.top + this.outerHeight();

				return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
			};

			$.avadaredux.hideFields();
			$.avadaredux.checkRequired();
			$.avadaredux.initEvents();
			$.avadaredux.initQtip();
			$.avadaredux.tabCheck();
			$.avadaredux.notices();
			$.avadaredux.tabControl();
		}
	);

	$.avadaredux.ajax_save = function( button ) {

		var overlay = $( document.getElementById( 'avadaredux_ajax_overlay' ) );
		overlay.fadeIn();

		// Add the loading mechanism
		jQuery( '.avadaredux-action_bar .spinner' ).addClass( 'is-active' );

		jQuery( '.avadaredux-action_bar input' ).attr( 'disabled', 'disabled' );
		var $notification_bar = jQuery( document.getElementById( 'avadaredux_notification_bar' ) );
		$notification_bar.slideUp();
		jQuery( '.avadaredux-save-warn' ).slideUp();
		jQuery( '.avadaredux_ajax_save_error' ).slideUp(
			'medium', function() {
				jQuery( this ).remove();
			}
		);

		var $parent = jQuery( document.getElementById( "avadaredux-form-wrapper" ) );

		// Editor field doesn't auto save. Have to call it. Boo.
		if ( avadaredux.fields.hasOwnProperty( "editor" ) ) {
			$.each(
				avadaredux.fields.editor, function( $key, $index ) {
					if (typeof(tinyMCE) !== 'undefined') {
						var editor = tinyMCE.get( $key );
						if ( editor ) {
							editor.save();
						}
					}
				}
			);
		}

		var $data = $parent.serialize();
		// add values for checked and unchecked checkboxes fields
		$parent.find( 'input[type=checkbox]' ).each(
			function() {
				if ( typeof $( this ).attr( 'name' ) !== "undefined" ) {
					var chkVal = $( this ).is( ':checked' ) ? $( this ).val() : "0";
					$data += "&" + $( this ).attr( 'name' ) + "=" + chkVal;
				}
			}
		);


		if ( button.attr( 'name' ) != "avadaredux_save" ) {
			$data += "&" + button.attr( 'name' ) + "=" + button.val();
		}

		var $nonce = $parent.attr( "data-nonce" );

		jQuery.ajax(
			{
				type: "post",
				dataType: "json",
				url: ajaxurl,
				data: {
					action: avadaredux.args.opt_name + "_ajax_save",
					nonce: $nonce,
					'opt_name': avadaredux.args.opt_name,
					data: $data
				},
				error: function( response ) {
					if ( !window.console ) console = {};
					console.log = console.log || function( name, data ) {
					};
					console.log( avadaredux.ajax.console );
					console.log( response.responseText );
					jQuery( '.avadaredux-action_bar input' ).removeAttr( 'disabled' );
					overlay.fadeOut( 'fast' );
					jQuery( '.avadaredux-action_bar .spinner' ).removeClass( 'is-active' );
					alert( avadaredux.ajax.alert );
				},
				success: function( response ) {
					if ( response.action && response.action == "reload" ) {
						location.reload( true );
					} else if ( response.status == "success" ) {
						jQuery( '.avadaredux-action_bar input' ).removeAttr( 'disabled' );
						overlay.fadeOut( 'fast' );
						jQuery( '.avadaredux-action_bar .spinner' ).removeClass( 'is-active' );
						avadaredux.options = response.options;
						//avadaredux.defaults = response.defaults;
						avadaredux.errors = response.errors;
						avadaredux.warnings = response.warnings;

						$notification_bar.html( response.notification_bar ).slideDown( 'fast' );
						if ( response.errors !== null || response.warnings !== null ) {
							$.avadaredux.notices();
						}
						var $save_notice = $( document.getElementById( 'avadaredux_notification_bar' ) ).find( '.saved_notice' );
						$save_notice.slideDown();
						$save_notice.delay( 4000 ).slideUp();
					} else {
						jQuery( '.avadaredux-action_bar input' ).removeAttr( 'disabled' );
						jQuery( '.avadaredux-action_bar .spinner' ).removeClass( 'is-active' );
						overlay.fadeOut( 'fast' );
						jQuery( '.wrap h2:first' ).parent().append( '<div class="error avadaredux_ajax_save_error" style="display:none;"><p>' + response.status + '</p></div>' );
						jQuery( '.avadaredux_ajax_save_error' ).slideDown();
						jQuery( "html, body" ).animate( {scrollTop: 0}, "slow" );
					}
				}
			}
		);
		return false;
	};

	$.avadaredux.initEvents = function() {
		$( '.avadaredux-presets-bar' ).on(
			'click', function() {
				window.onbeforeunload = null;
			}
		);


		$( '#toplevel_page_' + avadaredux.args.slug + ' .wp-submenu a, #wp-admin-bar-' + avadaredux.args.slug + ' a.ab-item' ).click(
			function( e ) {

				if ( ( $( '#toplevel_page_' + avadaredux.args.slug ).hasClass( 'wp-menu-open' ) || $( this ).hasClass( 'ab-item' ) ) && !$( this ).parents( 'ul.ab-submenu:first' ).hasClass( 'ab-sub-secondary' ) && $( this ).attr( 'href' ).toLowerCase().indexOf( avadaredux.args.slug + "&tab=" ) >= 0 ) {
					e.preventDefault();
					var url = $( this ).attr( 'href' ).split( '&tab=' );
					$( '#' + url[1] + '_section_group_li_a' ).click();
					$( this ).parents( 'ul:first' ).find( '.current' ).removeClass( 'current' );
					$( this ).addClass( 'current' );
					$( this ).parent().addClass( 'current' );
					return false;
				}
			}
		);

		// Save button clicked
		$( '.avadaredux-action_bar input' ).on(
			'click', function( e ) {
				if ( $( this ).attr( 'name' ) == avadaredux.args.opt_name + '[defaults]' ) {
					// Defaults button clicked
					if ( !confirm( avadaredux.args.reset_confirm ) ) {
						return false;
					}
				} else if ( $( this ).attr( 'name' ) == avadaredux.args.opt_name + '[defaults-section]' ) {
					// Default section clicked
					if ( !confirm( avadaredux.args.reset_section_confirm ) ) {
						return false;
					}
				}

				window.onbeforeunload = null;

				if ( avadaredux.args.ajax_save === true ) {
					$.avadaredux.ajax_save( $( this ) );
					e.preventDefault();
				}
			}
		);
		//
		//// Default button clicked
		//$( 'input[name="' + avadaredux.args.opt_name + '[defaults]"]' ).click(
		//    function() {
		//        if ( !confirm( avadaredux.args.reset_confirm ) ) {
		//            return false;
		//        }
		//        window.onbeforeunload = null;
		//    }
		//);


		//$( 'input[name="' + avadaredux.args.opt_name + '[defaults-section]"]' ).click(
		//    function() {
		//        if ( !confirm( avadaredux.args.reset_section_confirm ) ) {
		//            return false;
		//        }
		//
		//        window.onbeforeunload = null;
		//    }
		//);
		//$( '.avadaredux-save' ).click(
		//    function() {
		//        window.onbeforeunload = null;
		//    }
		//);

		$( '.expand_options' ).click(
			function( e ) {

				e.preventDefault();

				var container = $( '.avadaredux-container' );
				if ( $( container ).hasClass( 'fully-expanded' ) ) {
					$( container ).removeClass( 'fully-expanded' );

					var tab = $.cookie( "avadaredux_current_tab" );

					$( '.avadaredux-container:first' ).find( '#' + tab + '_section_group' ).fadeIn(
						200, function() {
							if ( $( '.avadaredux-container:first' ).find( '#avadaredux-footer' ).length !== 0 ) {
								$.avadaredux.stickyInfo(); // race condition fix
							}
							$.avadaredux.initFields();
						}
					);
				}

				$.avadaredux.expandOptions( $( this ).parents( '.avadaredux-container:first' ) );

				return false;
			}
		);

		if ( $( '.saved_notice' ).is( ':visible' ) ) {
			$( '.saved_notice' ).slideDown();
		}

		var stickyHeight = $( '#avadaredux-footer' ).height();

		$( '#avadaredux-sticky-padder' ).css(
			{
				height: stickyHeight
			}
		);
		$( '#avadaredux-footer-sticky' ).removeClass( 'hide' );

		if ( $( '#avadaredux-footer' ).length !== 0 ) {
			$( window ).scroll(
				function() {
					$.avadaredux.stickyInfo();
				}
			);

			$( window ).resize(
				function() {
					$.avadaredux.stickyInfo();
				}
			);
		}

		$( '.saved_notice' ).delay( 4000 ).slideUp();


	};

	$.avadaredux.hideFields = function() {
		$( "label[for='avadaredux_hide_field']" ).each(
			function( idx, val ) {
				var tr = $( this ).parent().parent();
				$( tr ).addClass( 'hidden' );
			}
		);
	};

	$.avadaredux.checkRequired = function() {
		$.avadaredux.required();

		// todo: fix for Avada-Beta issue : 155. WIP
		var $parent = $( "#avadaredux-form-wrapper" );
		$parent.serialize();

		$.avadaredux.check_active_tab_dependencies();

		$( "body" ).on( 'change',
			'.avadaredux-field input, .avadaredux-field textarea, .avadaredux-field select, .avadaredux-main select, .avadaredux-main radio, .avadaredux-main input[type=checkbox], .avadaredux-main input[type=hidden]',
			function( e ) {
				if ( ! $( this ).hasClass( 'noUpdate' ) ) {
					avadaredux_change( $( this ) );
				}
			}
		);

		$( 'td > fieldset:empty,td > div:empty' ).parent().parent().hide();
	};

	$.avadaredux.initQtip = function() {
		if ( $().qtip ) {
			// Shadow
			var shadow = '';
			var tip_shadow = avadaredux.args.hints.tip_style.shadow;

			if ( tip_shadow === true ) {
				shadow = 'qtip-shadow';
			}

			// Color
			var color = '';
			var tip_color = avadaredux.args.hints.tip_style.color;

			if ( tip_color !== '' ) {
				color = 'qtip-' + tip_color;
			}

			// Rounded
			var rounded = '';
			var tip_rounded = avadaredux.args.hints.tip_style.rounded;

			if ( tip_rounded === true ) {
				rounded = 'qtip-rounded';
			}

			// Tip style
			var style = '';
			var tip_style = avadaredux.args.hints.tip_style.style;

			if ( tip_style !== '' ) {
				style = 'qtip-' + tip_style;
			}

			var classes = shadow + ',' + color + ',' + rounded + ',' + style + ',avadaredux-qtip';
			classes = classes.replace( /,/g, ' ' );

			// Get position data
			var myPos = avadaredux.args.hints.tip_position.my;
			var atPos = avadaredux.args.hints.tip_position.at;

			// Gotta be lowercase, and in proper format
			myPos = $.avadaredux.verifyPos( myPos.toLowerCase(), true );
			atPos = $.avadaredux.verifyPos( atPos.toLowerCase(), false );

			// Tooltip trigger action
			var showEvent = avadaredux.args.hints.tip_effect.show.event;
			var hideEvent = avadaredux.args.hints.tip_effect.hide.event;

			// Tip show effect
			var tipShowEffect = avadaredux.args.hints.tip_effect.show.effect;
			var tipShowDuration = avadaredux.args.hints.tip_effect.show.duration;

			// Tip hide effect
			var tipHideEffect = avadaredux.args.hints.tip_effect.hide.effect;
			var tipHideDuration = avadaredux.args.hints.tip_effect.hide.duration;

			$( 'div.avadaredux-dev-qtip' ).each(
				function() {
					$( this ).qtip(
						{
							content: {
								text: $( this ).attr( 'qtip-content' ),
								title: $( this ).attr( 'qtip-title' )
							},
							show: {
								effect: function() {
									$( this ).slideDown( 500 );
								},
								event: 'mouseover',
							},
							hide: {
								effect: function() {
									$( this ).slideUp( 500 );
								},
								event: 'mouseleave',
							},
							style: {
								classes: 'qtip-shadow qtip-light',
							},
							position: {
								my: 'top center',
								at: 'bottom center',
							},
						}
					);
				}
			);

			$( 'div.avadaredux-hint-qtip' ).each(
				function() {
					$( this ).qtip(
						{
							content: {
								text: $( this ).attr( 'qtip-content' ),
								title: $( this ).attr( 'qtip-title' )
							},
							show: {
								effect: function() {
									switch ( tipShowEffect ) {
										case 'slide':
											$( this ).slideDown( tipShowDuration );
											break;
										case 'fade':
											$( this ).fadeIn( tipShowDuration );
											break;
										default:
											$( this ).show();
											break;
									}
								},
								event: showEvent,
							},
							hide: {
								effect: function() {
									switch ( tipHideEffect ) {
										case 'slide':
											$( this ).slideUp( tipHideDuration );
											break;
										case 'fade':
											$( this ).fadeOut( tipHideDuration );
											break;
										default:
											$( this ).hide( tipHideDuration );
											break;
									}
								},
								event: hideEvent,
							},
							style: {
								classes: classes,
							},
							position: {
								my: myPos,
								at: atPos,
							},
						}
					);
				}
			);
			// });

			$( 'input[qtip-content]' ).each(
				function() {
					$( this ).qtip(
						{
							content: {
								text: $( this ).attr( 'qtip-content' ),
								title: $( this ).attr( 'qtip-title' )
							},
							show: 'focus',
							hide: 'blur',
							style: classes,
							position: {
								my: myPos,
								at: atPos,
							},
						}
					);
				}
			);
		}
	};

	$.avadaredux.tabCheck = function() {
		$( '.avadaredux-group-tab-link-a' ).click( function() {

				var link = $( this );

				if ( link.parent().hasClass( 'empty_section' ) && link.parent().hasClass( 'hasSubSections' ) ) {

					var elements = $( this ).closest( 'ul' ).find( '.avadaredux-group-tab-link-a' );
					var index = elements.index( this );
					link = elements.slice( index + 1, index + 2 );
				}

				var el = link.parents( '.avadaredux-container:first' );
				var relid = link.data( 'rel' ); // The group ID of interest
				var oldid = el.find( '.avadaredux-group-tab-link-li.active:first .avadaredux-group-tab-link-a' ).data( 'rel' );

				//console.log('id: '+relid+' oldid: '+oldid);

				if ( oldid === relid ) {
					return;
				}

				$( '#currentSection' ).val( relid );

				if ( !link.parents( '.postbox-container:first' ).length ) {
					// Set the proper page cookie
					$.cookie(
						'avadaredux_current_tab', relid, {
							expires: 7,
							path: '/'
						}
					);
				}

				if ( el.find( '#' + relid + '_section_group_li' ).parents( '.avadaredux-group-tab-link-li' ).length ) {
					var parentID = el.find( '#' + relid + '_section_group_li' ).parents( '.avadaredux-group-tab-link-li' ).attr( 'id' ).split( '_' );
					parentID = parentID[0];
				}

				el.find( '#toplevel_page_' + avadaredux.args.slug + ' .wp-submenu a.current' ).removeClass( 'current' );
				el.find( '#toplevel_page_' + avadaredux.args.slug + ' .wp-submenu li.current' ).removeClass( 'current' );

				el.find( '#toplevel_page_' + avadaredux.args.slug + ' .wp-submenu a' ).each(
					function() {
						var url = $( this ).attr( 'href' ).split( '&tab=' );
						if ( url[1] == relid || url[1] == parentID ) {
							$( this ).addClass( 'current' );
							$( this ).parent().addClass( 'current' );
						}
					}
				);

				if ( el.find( '#' + oldid + '_section_group_li' ).find( '#' + oldid + '_section_group_li' ).length ) {
					//console.log('RELID is child of oldid');
					el.find( '#' + oldid + '_section_group_li' ).addClass( 'activeChild' );
					el.find( '#' + relid + '_section_group_li' ).addClass( 'active' ).removeClass( 'activeChild' );
				} else if ( el.find( '#' + relid + '_section_group_li' ).parents( '#' + oldid + '_section_group_li' ).length || el.find( '#' + oldid + '_section_group_li' ).parents( 'ul.subsection' ).find( '#' + relid + '_section_group_li' ).length ) {
					//console.log('RELID is sibling or child of OLDID');
					if ( el.find( '#' + relid + '_section_group_li' ).parents( '#' + oldid + '_section_group_li' ).length ) {
						//console.log('child of oldid');
						el.find( '#' + oldid + '_section_group_li' ).addClass( 'activeChild' ).removeClass( 'active' );
					} else {
						//console.log('sibling');
						el.find( '#' + relid + '_section_group_li' ).addClass( 'active' );
						el.find( '#' + oldid + '_section_group_li' ).removeClass( 'active' );
					}
					el.find( '#' + relid + '_section_group_li' ).removeClass( 'activeChild' ).addClass( 'active' );
				} else {
					el.find( '#' + relid + '_section_group_li' ).addClass( 'active' ).removeClass( 'activeChild' ).find( 'ul.subsection' ).slideDown();

					if ( el.find( '#' + oldid + '_section_group_li' ).find( 'ul.subsection' ).length ) {
						//console.log('oldid is parent');
						//console.log('#' + relid + '_section_group_li');

						el.find( '#' + oldid + '_section_group_li' ).find( 'ul.subsection' ).slideUp(
							'fast', function() {
								el.find( '#' + oldid + '_section_group_li' ).removeClass( 'active' ).removeClass( 'activeChild' );
							}
						);
						var newParent = el.find( '#' + relid + '_section_group_li' ).parents( '.hasSubSections:first' );

						if ( newParent.length > 0 ) {
							el.find( '#' + relid + '_section_group_li' ).removeClass( 'active' );
							relid = newParent.find( '.avadaredux-group-tab-link-a:first' ).data( 'rel' );
							//console.log(relid);
							if ( newParent.hasClass( 'empty_section' ) ) {
								newParent.find( '.subsection li:first' ).addClass( 'active' );
								el.find( '#' + relid + '_section_group_li' ).removeClass( 'active' ).addClass( 'activeChild' ).find( 'ul.subsection' ).slideDown();
								newParent = newParent.find( '.subsection li:first' );
								relid = newParent.find( '.avadaredux-group-tab-link-a:first' ).data( 'rel' );
								//console.log('Empty section, do the next one?');
							} else {
								el.find( '#' + relid + '_section_group_li' ).addClass( 'active' ).removeClass( 'activeChild' ).find( 'ul.subsection' ).slideDown();
							}
						}
					} else if ( el.find( '#' + oldid + '_section_group_li' ).parents( 'ul.subsection' ).length ) {
						//console.log('oldid is a child');
						if ( !el.find( '#' + oldid + '_section_group_li' ).parents( '#' + relid + '_section_group_li' ).length ) {
							//console.log('oldid is child, but not of relid');
							el.find( '#' + oldid + '_section_group_li' ).parents( 'ul.subsection' ).slideUp(
								'fast', function() {
									el.find( '#' + oldid + '_section_group_li' ).removeClass( 'active' );
									el.find( '#' + oldid + '_section_group_li' ).parents( '.avadaredux-group-tab-link-li' ).removeClass( 'active' ).removeClass( 'activeChild' );
									el.find( '#' + relid + '_section_group_li' ).parents( '.avadaredux-group-tab-link-li' ).addClass( 'activeChild' ).find( 'ul.subsection' ).slideDown();
									el.find( '#' + relid + '_section_group_li' ).addClass( 'active' );
								}
							);
						} else {
							//console.log('oldid is child, but not of relid2');
							el.find( '#' + oldid + '_section_group_li' ).removeClass( 'active' );
						}
					} else {
						//console.log('Normal remove active from child');
						el.find( '#' + oldid + '_section_group_li' ).removeClass( 'active' );
						if ( el.find( '#' + relid + '_section_group_li' ).parents( '.avadaredux-group-tab-link-li' ).length ) {
							//console.log('here');
							// ThemeFusion edit: added the timeout to make the slidedown work correctly
							setTimeout( function() {
								el.find( '#' + relid + '_section_group_li' ).parents( '.avadaredux-group-tab-link-li' ).addClass( 'activeChild' ).find( 'ul.subsection' ).slideDown();
							}, 50 );
							el.find( '#' + relid + '_section_group_li' ).addClass( 'active' );
						}
					}
				}

				// Show the group
				el.find( '#' + oldid + '_section_group' ).hide();

				el.find( '#' + relid + '_section_group' ).fadeIn(
					200, function() {
						if ( el.find( '#avadaredux-footer' ).length !== 0 ) {
							$.avadaredux.stickyInfo(); // race condition fix
						}
						$.avadaredux.initFields();
					}
				);
				$( '#toplevel_page_' + avadaredux.args.slug ).find( '.current' ).removeClass( 'current' );

			}
		);

		if (avadaredux.last_tab !== undefined) {
			$( '#' + avadaredux.last_tab + '_section_group_li_a' ).click();
			return;
		}

		var tab = decodeURI( (new RegExp( 'tab' + '=' + '(.+?)(&|$)' ).exec( location.search ) || [, ''])[1] );

		if ( tab !== "" ) {
			if ( $.cookie( "avadaredux_current_tab_get" ) !== tab ) {
				$.cookie(
					'avadaredux_current_tab', tab, {
						expires: 7,
						path: '/'
					}
				);
				$.cookie(
					'avadaredux_current_tab_get', tab, {
						expires: 7,
						path: '/'
					}
				);

				$( '#' + tab + '_section_group_li' ).click();
			}
		} else if ( $.cookie( 'avadaredux_current_tab_get' ) !== "" ) {
			$.removeCookie( 'avadaredux_current_tab_get' );
		}

		var sTab = $( '#' + $.cookie( "avadaredux_current_tab" ) + '_section_group_li_a' );

		// Tab the first item or the saved one
		if ( $.cookie( "avadaredux_current_tab" ) === null || typeof ($.cookie( "avadaredux_current_tab" )) === "undefined" || sTab.length === 0 ) {
			$( '.avadaredux-container' ).find( '.avadaredux-group-tab-link-a:first' ).click();
		} else {
			sTab.click();
		}

	};

	$.avadaredux.initFields = function() {
		$( ".avadaredux-group-tab:visible" ).find( ".avadaredux-field-init:visible" ).each(
			function() {

				// Initialize color picker
				$( this ).find( ".color-picker" ).each( function() {
					$(this).wpColorPicker();
				});

				var type = $( this ).attr( 'data-type' );
				if ( typeof avadaredux.field_objects != 'undefined' && avadaredux.field_objects[type] && avadaredux.field_objects[type] ) {
					avadaredux.field_objects[type].init();
				}
				if ( !avadaredux.customizer && $( this ).hasClass( 'avadaredux_remove_th' )  ) {

					var tr = $( this ).parents( 'tr:first' );
					var th = tr.find( 'th:first' );
					if ( th.html() && th.html().length > 0 ) {
						$( this ).prepend( th.html() );
						$( this ).find( '.avadaredux_field_th' ).css( 'padding', '0 0 10px 0' );
					}
					$( this ).parent().attr( 'colspan', '2' );
					th.remove();
				}
			}
		);
	};

	$.avadaredux.notices = function() {
		if ( avadaredux.errors && avadaredux.errors.errors ) {
			$.each(
				avadaredux.errors.errors, function( sectionID, sectionArray ) {
					$.each(
						sectionArray.errors, function( key, value ) {
							$( "#" + avadaredux.args.opt_name + '-' + value.id ).addClass( "avadaredux-field-error" );
							if ( $( "#" + avadaredux.args.opt_name + '-' + value.id ).parent().find( '.avadaredux-th-error' ).length === 0 ) {
								$( "#" + avadaredux.args.opt_name + '-' + value.id ).append( '<div class="avadaredux-th-error">' + value.msg + '</div>' );
							} else {
								$( "#" + avadaredux.args.opt_name + '-' + value.id ).parent().find( '.avadaredux-th-error' ).html( value.msg ).css(
									'display', 'block'
								);
							}
						}
					);
				}
			);
			$( '.avadaredux-container' ).each(
				function() {
					var container = $( this );
					// Ajax cleanup
					container.find( '.avadaredux-menu-error' ).remove();
					var totalErrors = container.find( '.avadaredux-field-error' ).length;
					if ( totalErrors > 0 ) {
						container.find( ".avadaredux-field-errors span" ).text( totalErrors );
						container.find( ".avadaredux-field-errors" ).slideDown();
						container.find( '.avadaredux-group-tab' ).each(
							function() {
								var total = $( this ).find( '.avadaredux-field-error' ).length;
								if ( total > 0 ) {
									var sectionID = $( this ).attr( 'id' ).split( '_' );
									sectionID = sectionID[0];
									container.find( '.avadaredux-group-tab-link-a[data-key="' + sectionID + '"]' ).prepend( '<span class="avadaredux-menu-error">' + total + '</span>' );
									container.find( '.avadaredux-group-tab-link-a[data-key="' + sectionID + '"]' ).addClass( "hasError" );
									var subParent = container.find( '.avadaredux-group-tab-link-a[data-key="' + sectionID + '"]' ).parents( '.hasSubSections:first' );
									if ( subParent ) {
										subParent.find( '.avadaredux-group-tab-link-a:first' ).addClass( 'hasError' );
									}
								}
							}
						);
					}
				}
			);
		}
		if ( avadaredux.warnings && avadaredux.warnings.warnings ) {
			$.each(
				avadaredux.warnings.warnings, function( sectionID, sectionArray ) {
					$.each(
						sectionArray.warnings, function( key, value ) {
							$( "#" + avadaredux.args.opt_name + '-' + value.id ).addClass( "avadaredux-field-warning" );
							if ( $( "#" + avadaredux.args.opt_name + '-' + value.id ).parent().find( '.avadaredux-th-warning' ).length === 0 ) {
								$( "#" + avadaredux.args.opt_name + '-' + value.id ).append( '<div class="avadaredux-th-warning">' + value.msg + '</div>' );
							} else {
								$( "#" + avadaredux.args.opt_name + '-' + value.id ).parent().find( '.avadaredux-th-warning' ).html( value.msg ).css(
									'display', 'block'
								);
							}
						}
					);
				}
			);
			$( '.avadaredux-container' ).each(
				function() {
					var container = $( this );
					// Ajax cleanup
					container.find( '.avadaredux-menu-warning' ).remove();
					var totalWarnings = container.find( '.avadaredux-field-warning' ).length;
					if ( totalWarnings > 0 ) {
						container.find( ".avadaredux-field-warnings span" ).text( totalWarnings );
						container.find( ".avadaredux-field-warnings" ).slideDown();
						container.find( '.avadaredux-group-tab' ).each(
							function() {
								var total = $( this ).find( '.avadaredux-field-warning' ).length;
								if ( total > 0 ) {
									var sectionID = $( this ).attr( 'id' ).split( '_' );
									sectionID = sectionID[0];
									container.find( '.avadaredux-group-tab-link-a[data-key="' + sectionID + '"]' ).prepend( '<span class="avadaredux-menu-warning">' + total + '</span>' );
									container.find( '.avadaredux-group-tab-link-a[data-key="' + sectionID + '"]' ).addClass( "hasWarning" );
									var subParent = container.find( '.avadaredux-group-tab-link-a[data-key="' + sectionID + '"]' ).parents( '.hasSubSections:first' );
									if ( subParent ) {
										subParent.find( '.avadaredux-group-tab-link-a:first' ).addClass( 'hasWarning' );
									}
								}
							}
						);
					}
				}
			);
		}
	};

	$.avadaredux.tabControl = function() {
		$( '.avadaredux-section-tabs div' ).hide();
		$( '.avadaredux-section-tabs div:first' ).show();
		$( '.avadaredux-section-tabs ul li:first' ).addClass( 'active' );

		$( '.avadaredux-section-tabs ul li a' ).click(
			function() {
				$( '.avadaredux-section-tabs ul li' ).removeClass( 'active' );
				$( this ).parent().addClass( 'active' );

				var currentTab = $( this ).attr( 'href' );

				$( '.avadaredux-section-tabs div' ).hide();
				$( currentTab ).fadeIn(
					'medium', function() {
						$.avadaredux.initFields();
					}
				);

				return false;
			}
		);
	};

	$.avadaredux.required = function() {

		// Hide the fold elements on load ,
		// It's better to do this by PHP but there is no filter in tr tag , so is not possible
		// we going to move each attributes we may need for folding to tr tag

		// ThemeFusion edit: make sure that only the currently clicked tab is parsed through
		var $current_tab_name = $.cookie( "avadaredux_current_tab" ),
			$current_tab = jQuery( '#' + $current_tab_name + '_section_group' ),
			$current_folds = {};

		$current_tab.find( 'fieldset' ).each( function() {
			var $option_name = $( this ).data( 'id' ),
				$fold_value = avadaredux.folds[$( this ).data( 'id' )];

			if ( $fold_value !== undefined ) {
				$current_folds[$( this ).data( 'id' )] = $fold_value;
			}
		});

		$.each(
			$current_folds, function( i, v ) {

				var fieldset = $( '#' + avadaredux.args.opt_name + '-' + i );

				fieldset.parents( 'tr:first' ).addClass( 'fold' );

				if ( v == "hide" ) {
					fieldset.parents( 'tr:first' ).addClass( 'hide' );

					if ( fieldset.hasClass( 'avadaredux-container-section' ) ) {
						var div = $( '#section-' + i );

						if ( div.hasClass( 'avadaredux-section-indent-start' ) ) {
							$( '#section-table-' + i ).hide().addClass( 'hide' );
							div.hide().addClass( 'hide' );
						}
					}

					if ( fieldset.hasClass( 'avadaredux-container-info' ) ) {
						$( '#info-' + i ).hide().addClass( 'hide' );
					}

					if ( fieldset.hasClass( 'avadaredux-container-divide' ) ) {
						$( '#divide-' + i ).hide().addClass( 'hide' );
					}

					if ( fieldset.hasClass( 'avadaredux-container-raw' ) ) {
						var rawTable = fieldset.parents().find( 'table#' + avadaredux.args.opt_name + '-' + i );
						rawTable.hide().addClass( 'hide' );
					}
				}
			}
		);
	};

	$.avadaredux.get_container_value = function( id ) {
		var value = $( '#' + avadaredux.args.opt_name + '-' + id ).serializeForm();

		if ( value !== null && typeof value === 'object' && value.hasOwnProperty( avadaredux.args.opt_name ) ) {
			value = value[avadaredux.args.opt_name][id];
		}
		if ( $( '#' + avadaredux.args.opt_name + '-' + id ).hasClass( 'avadaredux-container-media' ) ) {
			value = value.url;
		}
		return value;
	};

	$.avadaredux.check_active_tab_dependencies = function() {
		var
		tab = $.cookie( "avadaredux_current_tab" ),
		tab_name = $( '#' + tab + '_section_group_li_a' ).data('css-id');

		if ( avadaredux.optionSections.hasOwnProperty(tab_name) ) {
			var current_tab_options = avadaredux.optionSections[tab_name];

			$.each( current_tab_options, function( id ) {
				$.avadaredux.check_dependencies(id);
			});
		}
	};

	$.avadaredux.check_dependencies = function( variable ) {
		if ( avadaredux.required === null ) {
			return;
		}

		var
		id = '',
		current = '',
		container = '',
		is_hidden = '',
		show = false;

		// If option ID is passed to $variable.
		if ( jQuery.type(variable) === 'string' ) {
			id = variable;
			container = $( '#' + avadaredux.args.opt_name + '-' + id );

		// If option DOM element is passed to $variable.
		} else {
			current = $( variable );
			id = current.parents( '.avadaredux-field:first' ).data( 'id' );
			container = current.parents( '.avadaredux-field-container:first' );
		}

		var
		tab = $.cookie( "avadaredux_current_tab" ),
		tab_name = $( '#' + tab + '_section_group_li_a' ).data('css-id');

		// Get active tab options
		if ( avadaredux.optionSections.hasOwnProperty(tab_name) ) {
			var current_tab_options = avadaredux.optionSections[tab_name];

			// Process options that belong to active options tab only
			if ( id in current_tab_options ) {

				if ( ! avadaredux.required.hasOwnProperty( id ) ) {
					return;
				}

				is_hidden = container.parents( 'tr:first' ).hasClass( '.hide' );

				if ( ! is_hidden ) {
					show = $.avadaredux.check_parents_dependencies( id );
				}

				// Show/Hide option that is being processed.
				if ( show === true ) {
					$( '#' + avadaredux.args.opt_name + '-' + id ).parents( 'tr:first' ).fadeIn( 300, function() {
						$( this ).removeClass( 'hide' );
						//$.avadaredux.initFields();
					});
				} else if ( show === false ) {
					$( '#' + avadaredux.args.opt_name + '-' + id ).parents( 'tr:first' ).fadeOut( 100, function() {
						$( this ).addClass( 'hide' );
					});
				}

				// Check if option is a parent and process it's children
				$.each( avadaredux.required[id], function( child, dependents ) {
					if ( child in current_tab_options ) {

						show = false;
						is_hidden = '';

						var
						current = $( this ),
						childFieldset = $( '#' + avadaredux.args.opt_name + '-' + child ),
						tr = childFieldset.parents( 'tr:first' );

						if ( ! is_hidden ) {
							show = $.avadaredux.check_parents_dependencies( child );
						}

						if ( show === true ) {
							// ThemeFusion edit: wrapped below code in timeout
							//setTimeout( function() {

								// Shim for sections
								if ( childFieldset.hasClass( 'avadaredux-container-section' ) ) {
									var div = $( '#section-' + child );

									if ( div.hasClass( 'avadaredux-section-indent-start' ) && div.hasClass( 'hide' ) ) {
										$( '#section-table-' + child ).fadeIn( 300 ).removeClass( 'hide' );
										div.fadeIn( 300 ).removeClass( 'hide' );
									}
								}

								if ( childFieldset.hasClass( 'avadaredux-container-info' ) ) {
									$( '#info-' + child ).fadeIn( 300 ).removeClass( 'hide' );
								}

								if ( childFieldset.hasClass( 'avadaredux-container-divide' ) ) {
									$( '#divide-' + child ).fadeIn( 300 ).removeClass( 'hide' );
								}

								if ( childFieldset.hasClass( 'avadaredux-container-raw' ) ) {
									var rawTable = childFieldset.parents().find( 'table#' + avadaredux.args.opt_name + '-' + child );
									rawTable.fadeIn( 300 ).removeClass( 'hide' );
								}

								// Show option
								tr.fadeIn( 300 );
								tr.removeClass( 'hide' );

								if ( avadaredux.required.hasOwnProperty( child ) ) {
									$.avadaredux.check_dependencies( child );
								}

								if ( childFieldset.hasClass( 'avadaredux-container-section' ) || childFieldset.hasClass( 'avadaredux-container-info' ) ) {
									tr.css( {display: 'none'} );
								}

							//}, 120 );
						} else if ( show === false ) {
							tr.fadeOut( 100, function() {
								$( this ).addClass( 'hide' );
							});
						}
						//todo: check this trigger
						//current.find( 'select, radio, input[type=checkbox]' ).trigger( 'change' );
					}
				});
			}
		}
	};

	$.avadaredux.required_recursive_hide = function( id ) {
		var toFade = $( '#' + avadaredux.args.opt_name + '-' + id ).parents( 'tr:first' );

		toFade.fadeOut(
			50, function() {
				$( this ).addClass( 'hide' );

				if ( $( '#' + avadaredux.args.opt_name + '-' + id ).hasClass( 'avadaredux-container-section' ) ) {
					var div = $( '#section-' + id );
					if ( div.hasClass( 'avadaredux-section-indent-start' ) ) {
						$( '#section-table-' + id ).fadeOut( 50 ).addClass( 'hide' );
						div.fadeOut( 50 ).addClass( 'hide' );
					}
				}

				if ( $( '#' + avadaredux.args.opt_name + '-' + id ).hasClass( 'avadaredux-container-info' ) ) {
					$( '#info-' + id ).fadeOut( 50 ).addClass( 'hide' );
				}

				if ( $( '#' + avadaredux.args.opt_name + '-' + id ).hasClass( 'avadaredux-container-divide' ) ) {
					$( '#divide-' + id ).fadeOut( 50 ).addClass( 'hide' );
				}

				if ( $( '#' + avadaredux.args.opt_name + '-' + id ).hasClass( 'avadaredux-container-raw' ) ) {
					var rawTable = $( '#' + avadaredux.args.opt_name + '-' + id ).parents().find( 'table#' + avadaredux.args.opt_name + '-' + id );
					rawTable.fadeOut( 50 ).addClass( 'hide' );
				}

				if ( avadaredux.required.hasOwnProperty( id ) ) {
					$.each(
						avadaredux.required[id], function( child ) {
							//$.avadaredux.required_recursive_hide( child );
						}
					);
				}
			}
		);
	};

	// ThemeFusion edit: all parts in this function with result variable have been added
	$.avadaredux.check_parents_dependencies = function( id ) {
		var
		show = "",
		or_gutter = false,
		result = 0,
		tab = $.cookie( "avadaredux_current_tab" ),
		tab_name = $( '#' + tab + '_section_group_li_a' ).data('css-id');

		// Get active tab options
		if ( avadaredux.optionSections.hasOwnProperty(tab_name) ) {
			var current_tab_options = avadaredux.optionSections[tab_name];

			// Process options that belong to active options tab only
			if ( id in current_tab_options ) {

				if ( avadaredux.required_child.hasOwnProperty( id ) ) {

					$.each( avadaredux.required_child[id], function( i, parentData ) {

							or_gutter = $( '#' + avadaredux.args.opt_name + '-' + id ).parents( 'tr:first' ).hasClass( 'avada-or-gutter' );

							if ( $( '#' + avadaredux.args.opt_name + '-' + parentData.parent ).parents( 'tr:first' ).hasClass( '.hide' ) ) {
								show = false;

							} else {

								if ( show !== false || or_gutter ) {
									var parentValue = $.avadaredux.get_container_value( parentData.parent );
									show = $.avadaredux.check_dependencies_visibility( parentValue, parentData );
								}
							}

							if ( show ) {
								result += 1;
							}
						}
					);

				} else {
					show = true;
					result = 1;
				}

				if ( or_gutter ) {
					if ( result >= 1 ) {
						show = true;
					} else {
						show = false;
					}
				}

				return show;

			}
		}
	};

	$.avadaredux.check_dependencies_visibility = function( parentValue, data ) {
		var show = false,
			checkValue_array,
			checkValue = data.checkValue,
			operation = data.operation,
			arr;

		switch ( operation ) {
			case '=':
			case 'equals':
//                if ($.isPlainObject(parentValue)) {
//                    var arr = Object.keys(parentValue).map(function (key) {return parentValue[key]});
//                    parentValue = arr;
//                }

				if ( $.isArray( parentValue ) ) {
					$( parentValue[0] ).each(
						function( idx, val ) {
							if ( $.isArray( checkValue ) ) {
								$( checkValue ).each(
									function( i, v ) {
										if ( val == v ) {
											show = true;
											return true;
										}
									}
								);
							} else {
								if ( val == checkValue ) {
									show = true;
									return true;
								}
							}
						}
					);
				} else {
					if ( $.isArray( checkValue ) ) {
						$( checkValue ).each(
							function( i, v ) {
								if ( parentValue == v ) {
									show = true;
								}
							}
						);
					} else {
						if ( parentValue == checkValue ) {
							show = true;
						}
					}
				}
				break;

			case '!=':
			case 'not':
				if ( $.isArray( parentValue ) ) {
					$( parentValue ).each(
						function( idx, val ) {
							if ( $.isArray( checkValue ) ) {
								$( checkValue ).each(
									function( i, v ) {
										if ( val != v ) {
											show = true;
											return true;
										}
									}
								);
							} else {
								if ( val != checkValue ) {
									show = true;
									return true;
								}
							}
						}
					);
				} else {
					if ( $.isArray( checkValue ) ) {
						$( checkValue ).each(
							function( i, v ) {
								if ( parentValue != v ) {
									show = true;
								}
							}
						);
					} else {
						if ( parentValue != checkValue ) {
							show = true;
						}
					}
				}
			break;

			case '>':
			case 'greater':
			case 'is_larger':
				if ( parseFloat( parentValue ) > parseFloat( checkValue ) ) {
					show = true;
				}
			break;

			case '>=':
			case 'greater_equal':
			case 'is_larger_equal':
				if ( parseFloat( parentValue ) >= parseFloat( checkValue ) ) {
					show = true;
				}
			break;

			case '<':
			case 'less':
			case 'is_smaller':
				if ( parseFloat( parentValue ) < parseFloat( checkValue ) ) {
					show = true;
				}
			break;

			case '<=':
			case 'less_equal':
			case 'is_smaller_equal':
				if ( parseFloat( parentValue ) <= parseFloat( checkValue ) ) {
					show = true;
				}
			break;

			case 'contains':
				if ($.isPlainObject(parentValue)) {
					checkValue = Object.keys(checkValue).map(function (key) {
						return [key, checkValue[key]];
					});
					parentValue = arr;
				}

				if ($.isPlainObject(checkValue)) {
					arr = Object.keys(checkValue).map(function (key) {
						return checkValue[key];
					});
					checkValue = arr;
				}

				if ( $.isArray( checkValue ) ) {
					$( checkValue ).each(
						function( idx, val ) {

							var breakMe = false;
							var toFind  = val[0];
							var findVal = val[1];

							$(parentValue).each(
								function (i, v) {
									var toMatch = v[0];
									var matchVal = v[1];

									if (toFind === toMatch) {
										if (findVal == matchVal) {
											show = true;
											breakMe = true;

											return false;
										}
									}
								}
							);

							if (breakMe === true) {
								return false;
							}
						}
					);
				} else {
					if ( parentValue.toString().indexOf( checkValue ) !== -1 ) {
						show = true;
					}
				}
			break;

			case 'doesnt_contain':
			case 'not_contain':
				if ($.isPlainObject(parentValue)) {
					arr = Object.keys(parentValue).map(function (key) {
						return parentValue[key];
					});
					parentValue = arr;
				}

				if ($.isPlainObject(checkValue)) {
					arr = Object.keys(checkValue).map(function (key) {
						return checkValue[key];
					});
					checkValue = arr;
				}

				if ( $.isArray( checkValue ) ) {
					$( checkValue ).each(
						function( idx, val ) {
							if ( parentValue.toString().indexOf( val ) === -1 ) {
								show = true;
							}
						}
					);
				} else {
					if ( parentValue.toString().indexOf( checkValue ) === -1 ) {
						show = true;
					}
				}
			break;

			case 'is_empty_or':
				if ( parentValue === "" || parentValue == checkValue ) {
					show = true;
				}
			break;

			case 'not_empty_and':
				if ( parentValue !== "" && parentValue != checkValue ) {
					show = true;
				}
			break;

			case 'is_empty':
			case 'empty':
			case '!isset':
				if ( !parentValue || parentValue === "" || parentValue === null ) {
					show = true;
				}
			break;

			case 'not_empty':
			case '!empty':
			case 'isset':
				if ( parentValue && parentValue !== "" && parentValue !== null ) {
					show = true;
				}
			break;
		}

		return show;

	};

	$.avadaredux.verifyPos = function( s, b ) {

		// trim off spaces
		s = s.replace( /^\s+|\s+$/gm, '' );

		// position value is blank, set the default
		if ( s === '' || s.search( ' ' ) == -1 ) {
			if ( b === true ) {
				return 'top left';
			} else {
				return 'bottom right';
			}
		}

		// split string into array
		var split = s.split( ' ' );

		// Evaluate first string.  Must be top, center, or bottom
		var paramOne = b ? 'top' : 'bottom';
		if ( split[0] == 'top' || split[0] == 'center' || split[0] == 'bottom' ) {
			paramOne = split[0];
		}

		// Evaluate second string.  Must be left, center, or right.
		var paramTwo = b ? 'left' : 'right';
		if ( split[1] == 'left' || split[1] == 'center' || split[1] == 'right' ) {
			paramTwo = split[1];
		}

		return paramOne + ' ' + paramTwo;
	};

	$.avadaredux.stickyInfo = function() {
		var stickyWidth = $( '.avadaredux-main' ).innerWidth() - 20;

		if ( !$( '#info_bar' ).isOnScreen() && !$( '#avadaredux-footer-sticky' ).isOnScreen() ) {
			$( '#avadaredux-footer' ).css(
				{
					position: 'fixed',
					bottom: '0',
					width: stickyWidth,
					right: 21
				}
			);
			$( '#avadaredux-footer' ).addClass( 'sticky-footer-fixed' );
			$( '.avadaredux-save-warn' ).css( 'left', $( '#avadaredux-sticky' ).offset().left );
			$( '#avadaredux-sticky-padder' ).show();
		} else {
			$( '#avadaredux-footer' ).css(
				{
					background: '#eee',
					position: 'inherit',
					bottom: 'inherit',
					width: 'inherit'
				}
			);
			$( '#avadaredux-sticky-padder' ).hide();
			$( '#avadaredux-footer' ).removeClass( 'sticky-footer-fixed' );
		}
		if ( !$( '#info_bar' ).isOnScreen() ) {
			$( '#avadaredux-sticky' ).addClass( 'sticky-save-warn' );
		} else {
			$( '#avadaredux-sticky' ).removeClass( 'sticky-save-warn' );
		}
	};

	$.avadaredux.expandOptions = function( parent ) {
		var trigger = parent.find( '.expand_options' );
		var width = parent.find( '.avadaredux-sidebar' ).width() - 1;
		var id = $( '.avadaredux-group-menu .active a' ).data( 'rel' ) + '_section_group';

		if ( trigger.hasClass( 'expanded' ) ) {
			trigger.removeClass( 'expanded' );
			parent.find( '.avadaredux-main' ).removeClass( 'expand' );

			parent.find( '.avadaredux-sidebar' ).stop().animate(
				{
					'margin-left': '0px'
				}, 500
			);

			parent.find( '.avadaredux-main' ).stop().animate(
				{
					'margin-left': width
				}, 500, function() {
					parent.find( '.avadaredux-main' ).attr( 'style', '' );
				}
			);

			parent.find( '.avadaredux-group-tab' ).each(
				function() {
					if ( $( this ).attr( 'id' ) !== id ) {
						$( this ).fadeOut( 'fast' );
					}
				}
			);
			// Show the only active one
		} else {
			trigger.addClass( 'expanded' );
			parent.find( '.avadaredux-main' ).addClass( 'expand' );

			parent.find( '.avadaredux-sidebar' ).stop().animate(
				{
					'margin-left': -width - 113
				}, 500
			);

			parent.find( '.avadaredux-main' ).stop().animate(
				{
					'margin-left': '-1px'
				}, 500
			);

			parent.find( '.avadaredux-group-tab' ).fadeIn(
				'medium', function() {
					$.avadaredux.initFields();
				}
			);
		}
		return false;
	};


	$.avadaredux.scaleToRatio = function( el, maxHeight, maxWidth ) {
		var ratio = 0;  // Used for aspect ratio

		var width = el.attr( 'data-width' );
		if ( !width ) {
			width = el.width();
			el.attr( 'data-width', width );
		}
		var height = el.attr( 'data-height' );
		var eHeight = el.height();
		if ( !height || eHeight > height ) {
			height = eHeight;
			el.attr( 'data-height', height );
			el.css( "width", 'auto' );
			el.attr( 'data-width', el.width() );
			width = el.width();
		}


		// Check if the current width is larger than the max
		if ( width > maxWidth ) {
			ratio = maxWidth / width;   // get ratio for scaling image
			el.css( "width", maxWidth ); // Set new width
			el.css( "height", height * ratio );  // Scale height based on ratio
			height = height * ratio;    // Reset height to match scaled image
			width = width * ratio;    // Reset width to match scaled image

		} else {
			el.css( "width", 'auto' );   // Set new height

		}

		// Check if current height is larger than max
		if ( height > maxHeight ) {
			ratio = maxHeight / height; // get ratio for scaling image
			el.css( "height", maxHeight );   // Set new height
			el.css( "width", width * ratio );    // Scale width based on ratio
			width = width * ratio;    // Reset width to match scaled image
			height = height * ratio;    // Reset height to match scaled image


		} else {
			el.css( "height", 'auto' );   // Set new height

		}

		var test = ($( document.getElementById( 'avadaredux-header' ) ).height() - el.height()) / 2;
		if ( test > 0 ) {
			el.css( "margin-top", test );
		} else {
			el.css( "margin-top", 0 );
		}

		if ( $( '#avadaredux-header .avadaredux_field_search' ) ) {
			$( '#avadaredux-header .avadaredux_field_search' ).css( 'right', ($( el ).width() + 20) );
		}


	};
	$.avadaredux.resizeAds = function() {
		var el = $( '#avadaredux-header' );
		var maxWidth;
		if ( el.length ) {
			maxWidth = el.width() - el.find( '.display_header' ).width() - 30;
		} else {
			el = $( '#customize-info' );
			maxWidth = el.width();
		}

		var maxHeight = el.height();
		var rAds = el.find( '.rAds' );

		$( rAds ).find( 'video' ).each(
			function() {
				$.avadaredux.scaleToRatio( $( this ), maxHeight, maxWidth );
			}
		);
		$( rAds ).find( 'img' ).each(
			function() {
				$.avadaredux.scaleToRatio( $( this ), maxHeight, maxWidth );
			}
		);
		$( rAds ).find( 'div' ).each(
			function() {
				$.avadaredux.scaleToRatio( $( this ), maxHeight, maxWidth );
			}
		);

		if ( rAds.css( 'left' ) == "-99999px" ) {
			rAds.css( 'display', 'none' ).css( 'left', 'auto' );
		}
		rAds.fadeIn( 'slow' );
	};
	$( document ).ready(
		function() {
			if ( avadaredux.rAds ) {
				setTimeout(
					function() {
						var el;
						if ( $( '#avadaredux-header' ).length > 0 ) {
							$( '#avadaredux-header' ).append( '<div class="rAds"></div>' );
							el = $( '#avadaredux-header' );
						} else {
							$('#customize-theme-controls ul').first().prepend('<li id="avadaredux_rAds" class="accordion-section rAdsContainer" style="position: relative;"><div class="rAds"></div></li>');
							el = $( '#avadaredux_rAds' );
						}

						el.css( 'position', 'relative' );

						el.find( '.rAds' ).attr(
							'style',
							'position:absolute; top: 6px; right: 6px; display:block !important;overflow:hidden;'
						).css( 'left', '-99999px' );
						el.find( '.rAds' ).html( avadaredux.rAds.replace( /<br\s?\/?>/, '' ) );
						var rAds = el.find( '.rAds' );

						var maxHeight = el.height();
						var maxWidth = el.width() - el.find( '.display_header' ).width() - 30;

						rAds.find( 'a' ).css( 'float', 'right' ).css( 'line-height', el.height() + 'px' ).css(
							'margin-left', '5px'
						);

						$( document ).ajaxComplete(
							function() {
								rAds.find( 'a' ).hide();
								setTimeout(
									function() {
										$.avadaredux.resizeAds();
										rAds.find( 'a' ).fadeIn();
									}, 1400
								);
								setTimeout(
									function() {
										$.avadaredux.resizeAds();

									}, 1500
								);
								$( document ).unbind( 'ajaxComplete' );
							}
						);

						$( window ).resize(
							function() {
								$.avadaredux.resizeAds();
							}
						);
					}, 400
				);

			}
		}
	);
})( jQuery );

jQuery.noConflict();

var confirmOnPageExit = function( e ) {
	//return; // ONLY FOR DEBUGGING
	// If we haven't been passed the event get the window.event
	e = e || window.event;

	var message = avadaredux.args.save_pending;

	// For IE6-8 and Firefox prior to version 4
	if ( e ) {
		e.returnValue = message;
	}

	window.onbeforeunload = null;

	// For Chrome, Safari, IE8+ and Opera 12+
	return message;
};

function avadaredux_change( variable ) {
	jQuery.avadaredux.check_dependencies( variable );
	jQuery.avadaredux.initFields();

	if ( variable.hasClass( 'compiler' ) ) {
		jQuery( '#avadaredux-compiler-hook' ).val( 1 );
	}

	var rContainer = jQuery( variable ).parents( '.avadaredux-container:first' );
	var parentID = jQuery( variable ).closest( '.avadaredux-group-tab' ).attr( 'id' );
	// Let's count down the errors now. Fancy.  ;)
	var id = parentID.split( '_' );
	id = id[0];

	var th = rContainer.find( '.avadaredux-group-tab-link-a[data-key="' + id + '"]' ).parents( '.avadaredux-group-tab-link-li:first' );
	var subParent = jQuery( '#' + parentID + '_li' ).parents( '.hasSubSections:first' );

	if ( jQuery( variable ).parents( 'fieldset.avadaredux-field:first' ).hasClass( 'avadaredux-field-error' ) ) {
		jQuery( variable ).parents( 'fieldset.avadaredux-field:first' ).removeClass( 'avadaredux-field-error' );
		jQuery( variable ).parent().find( '.avadaredux-th-error' ).slideUp();

		var errorCount = (parseInt( rContainer.find( '.avadaredux-field-errors span' ).text() ) - 1);

		if ( errorCount <= 0 ) {
			//console.log('HERE');
			jQuery( '#' + parentID + '_li .avadaredux-menu-error' ).fadeOut( 'fast' ).remove();
			jQuery( '#' + parentID + '_li .avadaredux-group-tab-link-a' ).removeClass( 'hasError' );

			jQuery( '#' + parentID + '_li' ).parents( '.inside:first' ).find( '.avadaredux-field-errors' ).slideUp();
			jQuery( variable ).parents( '.avadaredux-container:first' ).find( '.avadaredux-field-errors' ).slideUp();
			jQuery( '#avadaredux_metaboxes_errors' ).slideUp();
		} else {

			var errorsLeft = (parseInt( th.find( '.avadaredux-menu-error:first' ).text() ) - 1);
			if ( errorsLeft <= 0 ) {
				th.find( '.avadaredux-menu-error:first' ).fadeOut().remove();
			} else {
				th.find( '.avadaredux-menu-error:first' ).text( errorsLeft );
			}

			rContainer.find( '.avadaredux-field-errors span' ).text( errorCount );
		}

		if ( subParent.length !== 0 ) {
			if ( subParent.find( '.avadaredux-menu-error' ).length === 0 ) {
				subParent.find( '.hasError' ).removeClass( 'hasError' );
			}
		}
	}
	if ( jQuery( variable ).parents( 'fieldset.avadaredux-field:first' ).hasClass( 'avadaredux-field-warning' ) ) {
		jQuery( variable ).parents( 'fieldset.avadaredux-field:first' ).removeClass( 'avadaredux-field-warning' );
		jQuery( variable ).parents( 'fieldset.avadaredux-field:first' ).find( '.avadaredux-th-warning' ).slideUp();

		var warningCount = (parseInt( rContainer.find( '.avadaredux-field-warnings span' ).text() ) - 1);

		if ( warningCount <= 0 ) {
			//console.log('HERE');
			jQuery( '#' + parentID + '_li .avadaredux-menu-warning' ).fadeOut( 'fast' ).remove();
			jQuery( '#' + parentID + '_li .avadaredux-group-tab-link-a' ).removeClass( 'hasWarning' );

			jQuery( '#' + parentID + '_li' ).parents( '.inside:first' ).find( '.avadaredux-field-warnings' ).slideUp();
			jQuery( variable ).parents( '.avadaredux-container:first' ).find( '.avadaredux-field-warnings' ).slideUp();
			jQuery( '#avadaredux_metaboxes_warnings' ).slideUp();
		} else {
			// Let's count down the warnings now. Fancy.  ;)

			var warningsLeft = (parseInt( th.find( '.avadaredux-menu-warning:first' ).text() ) - 1);
			if ( warningsLeft <= 0 ) {
				th.find( '.avadaredux-menu-warning:first' ).fadeOut().remove();
			} else {
				th.find( '.avadaredux-menu-warning:first' ).text( warningsLeft );
			}

			rContainer.find( '.avadaredux-field-warning span' ).text( warningCount );

		}
		if ( subParent.length !== 0 ) {
			if ( subParent.find( '.avadaredux-menu-warning' ).length === 0 ) {
				subParent.find( '.hasWarning' ).removeClass( 'hasWarning' );
			}
		}
	}
	// Don't show the changed value notice while save_notice is visible.
	if ( rContainer.find( '.saved_notice:visible' ).length > 0 ) {
		return;
	}


	if ( avadaredux.customizer ) {
		avadaredux.customizer.save( variable, rContainer, parentID );
		return;
	}

	if ( !avadaredux.args.disable_save_warn ) {
		rContainer.find( '.avadaredux-save-warn' ).slideDown();
		window.onbeforeunload = confirmOnPageExit;
	}
}

function colorValidate( field ) {
	var value = jQuery( field ).val();

	var hex = colorNameToHex( value );
	if ( hex !== value.replace( '#', '' ) ) {
		return hex;
	}

	return value;
}

function colorNameToHex( colour ) {
	var tcolour = colour.replace( /^\s\s*/, '' ).replace( /\s\s*$/, '' ).replace( "#", "" );

	var colours = {
		"aliceblue": "#f0f8ff",
		"antiquewhite": "#faebd7",
		"aqua": "#00ffff",
		"aquamarine": "#7fffd4",
		"azure": "#f0ffff",
		"beige": "#f5f5dc",
		"bisque": "#ffe4c4",
		"black": "#000000",
		"blanchedalmond": "#ffebcd",
		"blue": "#0000ff",
		"blueviolet": "#8a2be2",
		"brown": "#a52a2a",
		"burlywood": "#deb887",
		"cadetblue": "#5f9ea0",
		"chartreuse": "#7fff00",
		"chocolate": "#d2691e",
		"coral": "#ff7f50",
		"cornflowerblue": "#6495ed",
		"cornsilk": "#fff8dc",
		"crimson": "#dc143c",
		"cyan": "#00ffff",
		"darkblue": "#00008b",
		"darkcyan": "#008b8b",
		"darkgoldenrod": "#b8860b",
		"darkgray": "#a9a9a9",
		"darkgreen": "#006400",
		"darkkhaki": "#bdb76b",
		"darkmagenta": "#8b008b",
		"darkolivegreen": "#556b2f",
		"darkorange": "#ff8c00",
		"darkorchid": "#9932cc",
		"darkred": "#8b0000",
		"darksalmon": "#e9967a",
		"darkseagreen": "#8fbc8f",
		"darkslateblue": "#483d8b",
		"darkslategray": "#2f4f4f",
		"darkturquoise": "#00ced1",
		"darkviolet": "#9400d3",
		"deeppink": "#ff1493",
		"deepskyblue": "#00bfff",
		"dimgray": "#696969",
		"dodgerblue": "#1e90ff",
		"firebrick": "#b22222",
		"floralwhite": "#fffaf0",
		"forestgreen": "#228b22",
		"fuchsia": "#ff00ff",
		"gainsboro": "#dcdcdc",
		"ghostwhite": "#f8f8ff",
		"gold": "#ffd700",
		"goldenrod": "#daa520",
		"gray": "#808080",
		"green": "#008000",
		"greenyellow": "#adff2f",
		"honeydew": "#f0fff0",
		"hotpink": "#ff69b4",
		"indianred ": "#cd5c5c",
		"indigo ": "#4b0082",
		"ivory": "#fffff0",
		"khaki": "#f0e68c",
		"lavender": "#e6e6fa",
		"lavenderblush": "#fff0f5",
		"lawngreen": "#7cfc00",
		"lemonchiffon": "#fffacd",
		"lightblue": "#add8e6",
		"lightcoral": "#f08080",
		"lightcyan": "#e0ffff",
		"lightgoldenrodyellow": "#fafad2",
		"lightgrey": "#d3d3d3",
		"lightgreen": "#90ee90",
		"lightpink": "#ffb6c1",
		"lightsalmon": "#ffa07a",
		"lightseagreen": "#20b2aa",
		"lightskyblue": "#87cefa",
		"lightslategray": "#778899",
		"lightsteelblue": "#b0c4de",
		"lightyellow": "#ffffe0",
		"lime": "#00ff00",
		"limegreen": "#32cd32",
		"linen": "#faf0e6",
		"magenta": "#ff00ff",
		"maroon": "#800000",
		"mediumaquamarine": "#66cdaa",
		"mediumblue": "#0000cd",
		"mediumorchid": "#ba55d3",
		"mediumpurple": "#9370d8",
		"mediumseagreen": "#3cb371",
		"mediumslateblue": "#7b68ee",
		"mediumspringgreen": "#00fa9a",
		"mediumturquoise": "#48d1cc",
		"mediumvioletred": "#c71585",
		"midnightblue": "#191970",
		"mintcream": "#f5fffa",
		"mistyrose": "#ffe4e1",
		"moccasin": "#ffe4b5",
		"navajowhite": "#ffdead",
		"navy": "#000080",
		"oldlace": "#fdf5e6",
		"olive": "#808000",
		"olivedrab": "#6b8e23",
		"orange": "#ffa500",
		"orangered": "#ff4500",
		"orchid": "#da70d6",
		"palegoldenrod": "#eee8aa",
		"palegreen": "#98fb98",
		"paleturquoise": "#afeeee",
		"palevioletred": "#d87093",
		"papayawhip": "#ffefd5",
		"peachpuff": "#ffdab9",
		"peru": "#cd853f",
		"pink": "#ffc0cb",
		"plum": "#dda0dd",
		"powderblue": "#b0e0e6",
		"purple": "#800080",
		"red": "#ff0000",
		"avadaredux": "#01a3e3",
		"rosybrown": "#bc8f8f",
		"royalblue": "#4169e1",
		"saddlebrown": "#8b4513",
		"salmon": "#fa8072",
		"sandybrown": "#f4a460",
		"seagreen": "#2e8b57",
		"seashell": "#fff5ee",
		"sienna": "#a0522d",
		"silver": "#c0c0c0",
		"skyblue": "#87ceeb",
		"slateblue": "#6a5acd",
		"slategray": "#708090",
		"snow": "#fffafa",
		"springgreen": "#00ff7f",
		"steelblue": "#4682b4",
		"tan": "#d2b48c",
		"teal": "#008080",
		"thistle": "#d8bfd8",
		"tomato": "#ff6347",
		"turquoise": "#40e0d0",
		"violet": "#ee82ee",
		"wheat": "#f5deb3",
		"white": "#ffffff",
		"whitesmoke": "#f5f5f5",
		"yellow": "#ffff00",
		"yellowgreen": "#9acd32"
	};

	if ( colours[tcolour.toLowerCase()] !== 'undefined' ) {
		return colours[tcolour.toLowerCase()];
	}

	return colour;
}
