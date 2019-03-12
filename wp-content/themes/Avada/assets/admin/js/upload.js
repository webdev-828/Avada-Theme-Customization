jQuery(document).ready(function($){
	if($('.fusion_upload_button').length ) {
		window.avada_uploadfield = '';

		$('.fusion_upload_button').live('click', function() {
			window.avada_uploadfield = $('.upload_field', $(this).parents( '.pyre_upload' ));
			tb_show('Upload', 'media-upload.php?type=image&TB_iframe=true', false);

			return false;
		});

		window.avada_send_to_editor_backup = window.send_to_editor;
		window.send_to_editor = function( $html ) {
			if ( window.avada_uploadfield ) {
				if ( $( $html ).is( 'img' ) ) {
					var $image_url = $( $html ).attr( 'src' );
				} else if ( $( 'img', $html ).length ) {
					var $image_url = $( 'img', $html ).attr( 'src' );
				} else {
					var $image_url = $ ( $( $html )[0] ).attr( 'href' );
				}
				$( window.avada_uploadfield ).val( $image_url );
				window.avada_uploadfield = '';

				tb_remove();
			} else {
				window.avada_send_to_editor_backup( $html );
			}
		}
	}

	if($.cookie('fusion_metabox_tab_' + jQuery('#post_ID').val())) {
		var id = $.cookie('fusion_metabox_tab_' + jQuery('#post_ID').val());

		jQuery('.pyre_metabox_tabs li').removeClass('active');
		jQuery('.pyre_metabox_tabs li a[href=' + id + ']').parent().addClass('active');

		jQuery('.pyre_metabox_tabs li a[href=' + id + ']').parents('.inside').find('.pyre_metabox_tab').removeClass('active').hide();
		jQuery('.pyre_metabox_tabs li a[href=' + id + ']').parents('.inside').find('#pyre_tab_' + id).addClass('active').fadeIn();

		calc_element_heights();
	} else {
		jQuery('.pyre_metabox_tabs li:first-child').addClass('active');
		jQuery('.pyre_metabox .pyre_metabox_tab:first-child').addClass('active').fadeIn();
	}
	jQuery('.pyre_metabox_tabs li a').click(function(e) {
		e.preventDefault();

		var id = jQuery(this).attr('href');

		$.cookie('fusion_metabox_tab_' + jQuery('#post_ID').val(), id, { expires: 7 });

		jQuery(this).parents('ul').find('li').removeClass('active');
		jQuery(this).parent().addClass('active');

		jQuery(this).parents('.inside').find('.pyre_metabox_tab').removeClass('active').hide();
		jQuery(this).parents('.inside').find('#pyre_tab_' + id).addClass('active').fadeIn();

		calc_element_heights();
	});

	// calc height if the whole panel toggle is closed on load and opened later
	jQuery( '#post-body #advanced-sortables #pyre_page_options .handlediv, #post-body #advanced-sortables #pyre_page_options .hndle' ).click(function(e) {
		setTimeout( function() {
			calc_element_heights();
		}, 250 );

	});

	// initialize heights on load
	calc_element_heights();

	// set the page options to same width as other boxes (this is builder specific)
	/*if( jQuery( '#post-body #normal-sortables' ).length && jQuery( '#post-body #normal-sortables' ).css( 'width' ) != '0px' ) {
		jQuery( '#post-body #advanced-sortables' ).css( 'width', jQuery( '#post-body #normal-sortables' ).css( 'width' ) );
	}

	jQuery( window ).on( 'resize', function() {
		if( jQuery( '#post-body #normal-sortables' ).length && jQuery( '#post-body #normal-sortables' ).css( 'width' ) != '0px' ) {
			jQuery( '#post-body #advanced-sortables' ).css( 'width', jQuery( '#post-body #normal-sortables' ).css( 'width' ) );
		}
	});*/
});

function calc_element_heights() {

	// set tabs pane height same as the tab content height
	jQuery( '.pyre_metabox_tabs' ).removeAttr( 'style' );
	var tab_content_height = jQuery( '.pyre_metabox' ).outerHeight();
	var tabs_height = jQuery( '.pyre_metabox_tabs' ).height();
	if( tab_content_height > tabs_height ) {
		jQuery( '.pyre_metabox_tabs' ).css( 'height', tab_content_height );
	}


	// set heights of select arrows correctly
	jQuery( '.pyre_field .fusion-shortcodes-arrow' ).each( function() {
		if( jQuery( this ).next().innerHeight() > 0 ) {
			jQuery( this ).css( {
				height: jQuery( this ).next().innerHeight(),
				width: jQuery( this ).next().innerHeight(),
				'line-height': jQuery( this ).next().innerHeight() + 'px'
			});
		}
	});

	// set height of upload buttons to correspond with text field height
	jQuery( '.pyre_field .fusion_upload_button' ).each( function() {
		var field_height = jQuery( this ).parents( '.pyre_upload' ).find( 'input' ).outerHeight();
		if( field_height > 0 ) {
			jQuery( this ).css( 'height', field_height );
		}
	});
}