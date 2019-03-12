jQuery(document).ready(function() {
	jQuery('.wp-list-table #slug span:first,label[for=tag-slug],label[for=slug]').text('Shortcode');
	jQuery('label[for=tag-slug],label[for=slug]').parents('.form-field').find('p').text('This is the shortcode name that can be used in the post content area. It is usually all lowercase and contains only letters, numbers, and hyphens.');
	jQuery('#parent, #tag-description, #description').parents('.form-field').hide();
	jQuery('label[for=tag-name],label[for=name]').parents('.form-field').find('p').text('The name of your slider.');
	jQuery('.revslider_settings').parents('.postbox').hide();

	jQuery('.metabox-prefs #slugdiv-hide, .metabox-prefs #mymetabox_revslider_0-hide, .metabox-prefs #slug-hide').parents('label').hide();
	jQuery('#normal-sortables').hide();

	var type = jQuery('#pyre_type').val();
	var container = jQuery('#pyre_type').parents('.pyre_metabox');
	jQuery(container).find('.video_settings .pyre_metabox_field').hide();

	if(type == 'self-hosted-video' || type == 'youtube' || type == 'vimeo') {
		jQuery(container).find('.video_settings').slideDown();

		jQuery(container).find('.video_settings').find('#pyre_video_bg_color, #pyre_mute_video, #pyre_autoplay_video, #pyre_loop_video, #pyre_hide_video_controls').parents('.pyre_metabox_field').show();

		if ( type == 'youtube' || type == 'vimeo' ) {
			jQuery( container ).find( '.video_settings' ).find( '#pyre_aspect_ratio' ).parents('.pyre_metabox_field').show();
		}

		if(type == 'youtube') {
			jQuery(container).find('.video_settings #pyre_youtube_id').parents('.pyre_metabox_field').show();
		} else if(type == 'vimeo') {
			jQuery(container).find('.video_settings #pyre_vimeo_id').parents('.pyre_metabox_field').show();
			jQuery(container).find('.video_settings #pyre_hide_video_controls').parents('.pyre_metabox_field').hide();
		} else if(type == 'self-hosted-video') {
			jQuery(container).find('.video_settings #pyre_webm, .video_settings #pyre_mp4, .video_settings #pyre_ogv, .video_settings #pyre_preview_image').parents('.pyre_metabox_field').show();
		}
	} else {
		jQuery(container).find('.video_settings').hide();
	}

	if ( jQuery( '#pyre_content_alignment' ).val() == 'center' ) {
		jQuery( container ).find( '#pyre_heading_separator, #pyre_caption_separator' ).parents( '.pyre_metabox_field' ).show();
	} else {
		jQuery( container ).find( '#pyre_heading_separator, #pyre_caption_separator' ).parents( '.pyre_metabox_field' ).hide();
	}

	jQuery( '#pyre_content_alignment' ).change( function() {
		if ( jQuery( this ).val() == 'center' ) {
			jQuery( container ).find( '#pyre_heading_separator, #pyre_caption_separator' ).parents( '.pyre_metabox_field' ).show();
		} else {
			jQuery( container ).find( '#pyre_heading_separator, #pyre_caption_separator' ).parents( '.pyre_metabox_field' ).hide();
		}
	});

	jQuery('#pyre_type').change(function() {
		var type = jQuery(this).val();
		var container = jQuery(this).parents('.pyre_metabox');
		jQuery(container).find('.video_settings .pyre_metabox_field').hide();

		if(type == 'self-hosted-video' || type == 'youtube' || type == 'vimeo') {
			jQuery(container).find('.video_settings').slideDown();

			jQuery(container).find('.video_settings').find('#pyre_video_bg_color, #pyre_mute_video, #pyre_autoplay_video, #pyre_loop_video, #pyre_hide_video_controls').parents('.pyre_metabox_field').show();

			if ( type == 'youtube' || type == 'vimeo' ) {
				jQuery( container ).find( '.video_settings' ).find( '#pyre_aspect_ratio' ).parents('.pyre_metabox_field').show();
			}

			if(type == 'youtube') {
				jQuery(container).find('.video_settings #pyre_youtube_id').parents('.pyre_metabox_field').show();
			} else if(type == 'vimeo') {
				jQuery(container).find('.video_settings #pyre_vimeo_id').parents('.pyre_metabox_field').show();
				jQuery(container).find('.video_settings #pyre_hide_video_controls').parents('.pyre_metabox_field').hide();
			} else if(type == 'self-hosted-video') {
				jQuery(container).find('.video_settings #pyre_webm, .video_settings #pyre_mp4, .video_settings #pyre_ogv, .video_settings #pyre_preview_image').parents('.pyre_metabox_field').show();
			}
		} else {
			jQuery(container).find('.video_settings').hide();
		}
	});

	var type = jQuery('#pyre_link_type').val();
	var container = jQuery('#pyre_link_type').parents('.pyre_metabox');
	jQuery(container).find('#pyre_slide_link, #pyre_button_1, #pyre_button_2, #pyre_slide_target').parents('.pyre_metabox_field').hide();

	if(type == 'button') {
		jQuery(container).find('#pyre_button_1, #pyre_button_2').parents('.pyre_metabox_field').show();
	} else {
		jQuery(container).find('#pyre_slide_link, #pyre_slide_target').parents('.pyre_metabox_field').show();
	}

	jQuery('#pyre_link_type').change(function() {
		var type = jQuery(this).val();
		var container = jQuery(this).parents('.pyre_metabox');
		jQuery(container).find('#pyre_slide_link, #pyre_button_1, #pyre_button_2, #pyre_slide_target').parents('.pyre_metabox_field').hide();

		if(type == 'button') {
			jQuery(container).find('#pyre_button_1, #pyre_button_2').parents('.pyre_metabox_field').show();
		} else {
			jQuery(container).find('#pyre_slide_link, #pyre_slide_target').parents('.pyre_metabox_field').show();
		}
	});
});