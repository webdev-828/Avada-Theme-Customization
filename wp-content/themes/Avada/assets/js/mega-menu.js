/**
 * Fusion Framework
 *
 * WARNING: This file is part of the Fusion Core Framework.
 * Do not edit the core files.
 * Add any modifications necessary under a child theme.
 *
 * @version: 1.0.0
 * @package  Fusion/Admin Interface
 * @author   ThemeFusion
 * @link	 http://theme-fusion.com
 */

( function( $ ) {

	"use strict";

	$( document ).ready( function() {

		// show or hide megamenu fields on parent and child list items
		fusion_megamenu.menu_item_mouseup();
		fusion_megamenu.megamenu_status_update();
		fusion_megamenu.megamenu_fullwidth_update();
		fusion_megamenu.update_megamenu_fields();

		// setup automatic thumbnail handling
		$( '.remove-fusion-megamenu-thumbnail' ).manage_thumbnail_display();
		$( '.fusion-megamenu-thumbnail-image' ).css( 'display', 'block' );
		$( ".fusion-megamenu-thumbnail-image[src='']" ).css( 'display', 'none' );

		// setup new media uploader frame
		fusion_media_frame_setup();
	});

	// "extending" wpNavMenu
	var fusion_megamenu = {

		menu_item_mouseup: function() {
			$( document ).on( 'mouseup', '.menu-item-bar', function( event, ui ) {
				if( ! $( event.target ).is( 'a' )) {
					setTimeout( fusion_megamenu.update_megamenu_fields, 300 );
				}
			});
		},

		megamenu_status_update: function() {

			$( document ).on( 'click', '.edit-menu-item-megamenu-status', function() {
				var parent_li_item = $( this ).parents( '.menu-item:eq( 0 )' );

				if( $( this ).is( ':checked' ) ) {
					parent_li_item.addClass( 'fusion-megamenu' );
				} else 	{
					parent_li_item.removeClass( 'fusion-megamenu' );
				}

				fusion_megamenu.update_megamenu_fields();
			});
		},

		megamenu_fullwidth_update: function() {

			$( document ).on( 'click', '.edit-menu-item-megamenu-width', function() {
				var parent_li_item = $( this ).parents( '.menu-item:eq( 0 )' );

				if( $( this ).is( ':checked' ) ) {
					parent_li_item.addClass( 'fusion-megamenu-fullwidth' );
				} else 	{
					parent_li_item.removeClass( 'fusion-megamenu-fullwidth' );
				}

				fusion_megamenu.update_megamenu_fields();
			});
		},

		update_megamenu_fields: function() {
			var menu_li_items = $( '.menu-item');

			menu_li_items.each( function( i ) 	{

				var megamenu_status = $( '.edit-menu-item-megamenu-status', this );
				var megamenu_fullwidth = $( '.edit-menu-item-megamenu-width', this );

				if( ! $( this ).is( '.menu-item-depth-0' ) ) {
					var check_against = menu_li_items.filter( ':eq(' + (i-1) + ')' );

					if( check_against.is( '.fusion-megamenu' ) ) {
						megamenu_status.attr( 'checked', 'checked' );
						$( this ).addClass( 'fusion-megamenu' );
					} else {
						megamenu_status.attr( 'checked', '' );
						$( this ).removeClass( 'fusion-megamenu' );
					}

					if( check_against.is( '.fusion-megamenu-fullwidth' ) ) {
						megamenu_fullwidth.attr( 'checked', 'checked' );
						$( this ).addClass( 'fusion-megamenu-fullwidth' );
					} else {
						megamenu_fullwidth.attr( 'checked', '' );
						$( this ).removeClass( 'fusion-megamenu-fullwidth' );
					}
				} else {
					if( megamenu_status.attr( 'checked' ) ) {
						$( this ).addClass( 'fusion-megamenu' );
					}

					if( megamenu_fullwidth.attr( 'checked' ) ) {
						$( this ).addClass( 'fusion-megamenu-fullwidth' );
					}
				}
			});
		}

	};

	$.fn.manage_thumbnail_display = function( variables ) {
		var button_id;

		return this.click( function( e ){
			e.preventDefault();

			button_id = this.id.replace( 'fusion-media-remove-', '' );
			$( '#edit-menu-item-megamenu-thumbnail-'+button_id ).val( '' );
			$( '#fusion-media-img-'+button_id ).attr( 'src', '' ).css( 'display', 'none' );
		});
	}

	function fusion_media_frame_setup() {
		var fusion_media_frame;
		var item_id;

		$( document.body ).on( 'click.fusionOpenMediaManager', '.fusion-open-media', function(e){

			e.preventDefault();

			item_id = this.id.replace('fusion-media-upload-', '');

			if ( fusion_media_frame ) {
				fusion_media_frame.open();
				return;
			}

			fusion_media_frame = wp.media.frames.fusion_media_frame = wp.media({

				className: 'media-frame fusion-media-frame',
				frame: 'select',
				multiple: false,
				library: {
					type: 'image'
				}
			});

			fusion_media_frame.on('select', function(){

				var media_attachment = fusion_media_frame.state().get('selection').first().toJSON();

				$( '#edit-menu-item-megamenu-thumbnail-'+item_id ).val( media_attachment.url );
				$( '#fusion-media-img-'+item_id ).attr( 'src', media_attachment.url ).css( 'display', 'block' );

			});

			fusion_media_frame.open();
		});

	}
})( jQuery );