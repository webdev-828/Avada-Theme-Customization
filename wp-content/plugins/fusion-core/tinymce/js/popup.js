// start the popup specefic scripts
// safe to use $
var old_tb_remove = window.tb_remove;
var using_text_editor = false;
var text_editor_toggle;
var html_editor_toggle;
var editor_area;
var cursor_position = 0;

fusion_popup = function ( a, params ) {
	var popup = 'shortcode-generator';

	if(typeof params != 'undefined' && params.identifier) {
		popup = params.identifier;
	}

	// load thickbox
	tb_show("Fusion Shortcodes", ajaxurl + "?action=fusion_shortcodes_popup&popup=" + popup);

	jQuery('#TB_window').hide();
};

jQuery(document).ready(function($) {
	var tb_remove = function() {
		// check if text editor shortcode button was used; if so return to it
		if ( using_text_editor ) {
			using_text_editor = false;
			window.switchEditors.go( 'content' );
		}

		old_tb_remove();
	};

	window.avada_tb_height = (92 / 100) * jQuery(window).height();
	window.avada_fusion_shortcodes_height = (71 / 100) * jQuery(window).height();
	if(window.avada_fusion_shortcodes_height > 550) {
		window.avada_fusion_shortcodes_height = (74 / 100) * jQuery(window).height();
	}

	jQuery(window).resize(function() {
		window.avada_tb_height = (92 / 100) * jQuery(window).height();
		window.avada_fusion_shortcodes_height = (71 / 100) * jQuery(window).height();

		if(window.avada_fusion_shortcodes_height > 550) {
			window.avada_fusion_shortcodes_height = (74 / 100) * jQuery(window).height();
		}
	});

	themefusion_shortcodes = {
		loadVals: function()
		{
			var shortcode = $('#_fusion_shortcode').text(),
				uShortcode = shortcode;

			// fill in the gaps eg {{param}}
			$('.fusion-input').each(function() {
				var input = $(this),
					id = input.attr('id'),
					id = id.replace('fusion_', ''),		// gets rid of the fusion_ prefix
					re = new RegExp("{{"+id+"}}","g");
					var value = input.val();
					if(value == null) {
					  value = '';
					}
				uShortcode = uShortcode.replace(re, value);
			});

			// adds the filled-in shortcode as hidden input
			$('#_fusion_ushortcode').remove();
			$('#fusion-sc-form-table').prepend('<div id="_fusion_ushortcode" class="hidden">' + uShortcode + '</div>');
		},
		cLoadVals: function()
		{
			var shortcode = $('#_fusion_cshortcode').text(),
				pShortcode = '';

				if(shortcode.indexOf("<li>") < 0) {
					shortcodes = '<br />';
				} else {
					shortcodes = '';
				}

			// fill in the gaps eg {{param}}
			$('.fusion-shortcodes-popup .child-clone-row').each(function() {
				var row = $(this),
					rShortcode = shortcode;

				if($(this).find('#fusion_slider_type').length >= 1) {
					if($(this).find('#fusion_slider_type').val() == 'image') {
						rShortcode = '[slide type="{{slider_type}}" link="{{image_url}}" linktarget="{{image_target}}" lightbox="{{image_lightbox}}"]{{image_content}}[/slide]';
					} else if($(this).find('#fusion_slider_type').val() == 'video') {
						rShortcode = '[slide type="{{slider_type}}"]{{video_content}}[/slide]';
					}
				}
				$('.fusion-cinput', this).each(function() {
					var input = $(this),
						id = input.attr('id'),
						id = id.replace('fusion_', '')		// gets rid of the fusion_ prefix
						re = new RegExp("{{"+id+"}}","g");
						var value = input.val();
						if(value == null) {
						  value = '';
						}
					rShortcode = rShortcode.replace(re, input.val());
				});

				if(shortcode.indexOf("<li>") < 0) {
					shortcodes = shortcodes + rShortcode + '<br />';
				} else {
					shortcodes = shortcodes + rShortcode;
				}
			});

			// adds the filled-in shortcode as hidden input
			$('#_fusion_cshortcodes').remove();
			$('.fusion-shortcodes-popup .child-clone-rows').prepend('<div id="_fusion_cshortcodes" class="hidden">' + shortcodes + '</div>');

			// add to parent shortcode
			this.loadVals();
			pShortcode = $('#_fusion_ushortcode').html().replace('{{child_shortcode}}', shortcodes);

			// add updated parent shortcode
			$('#_fusion_ushortcode').remove();
			$('#fusion-sc-form-table').prepend('<div id="_fusion_ushortcode" class="hidden">' + pShortcode + '</div>');
		},
		children: function()
		{
			// assign the cloning plugin
			$('.fusion-shortcodes-popup .child-clone-rows').appendo({
				subSelect: '> div.child-clone-row:last-child',
				allowDelete: false,
				focusFirst: false,
				onAdd: function(row) {
					// Get Upload ID
					var prev_upload_id = jQuery(row).prev().find('.fusion-upload-button').data('upid');
					var new_upload_id = prev_upload_id + 1;
					jQuery(row).find('.fusion-upload-button').attr('data-upid', new_upload_id);

					// activate chosen
					jQuery('.fusion-form-multiple-select').chosen({
						width: '100%',
						placeholder_text_multiple: 'Select Options or Leave Blank for All'
					});

					// activate color picker
					jQuery('.wp-color-picker-field').wpColorPicker({
						change: function(event, ui) {
							themefusion_shortcodes.loadVals();
							themefusion_shortcodes.cLoadVals();
						}
					});

					// changing slide type
					var type = $(row).find('#fusion_slider_type').val();

					if(type == 'video') {
						$(row).find('#fusion_image_content, #fusion_image_url, #fusion_image_target, #fusion_image_lightbox').closest('li').hide();
						$(row).find('#fusion_video_content').closest('li').show();

						$(row).find('#_fusion_cshortcode').text('[slide type="{{slider_type}}"]{{video_content}}[/slide]');
					}

					if(type == 'image') {
						$(row).find('#fusion_image_content, #fusion_image_url, #fusion_image_target, #fusion_image_lightbox').closest('li').show();
						$(row).find('#fusion_video_content').closest('li').hide();

						$(row).find('#_fusion_cshortcode').text('[slide type="{{slider_type}}" link="{{image_url}}" linktarget="{{image_target}}" lightbox="{{image_lightbox}}"]{{image_content}}[/slide]');
					}

					themefusion_shortcodes.loadVals();
					themefusion_shortcodes.cLoadVals();
				}
			});

			// remove button
			$('.fusion-shortcodes-popup .child-clone-row-remove').live('click', function() {
				var	btn = $(this),
					row = btn.parent();

				if( $('.fusion-shortcodes-popup .child-clone-row').size() > 1 )
				{
					row.remove();
				}
				else
				{
					alert('You need a minimum of one row');
				}

				return false;
			});

			// assign jUI sortable
			$( ".fusion-shortcodes-popup .child-clone-rows" ).sortable({
				placeholder: "sortable-placeholder",
				items: '.child-clone-row',
				cancel: 'div.iconpicker, input, select, textarea, a'
			});
		},
		resizeTB: function()
		{
			var	ajaxCont = $('#TB_ajaxContent'),
				tbWindow = $('#TB_window'),
				fusionPopup = $('#fusion-popup');

			tbWindow.css({
				height: window.avada_tb_height,
				width: fusionPopup.outerWidth(),
				marginLeft: -(fusionPopup.outerWidth()/2)
			});

			ajaxCont.css({
				paddingTop: 0,
				paddingLeft: 0,
				paddingRight: 0,
				height: window.avada_tb_height,
				overflow: 'auto', // IMPORTANT
				width: fusionPopup.outerWidth()
			});

			tbWindow.show();

			$('#fusion-popup').addClass('no_preview');
			$('#fusion-sc-form-wrap #fusion-sc-form').height(window.avada_fusion_shortcodes_height);
		},
		load: function()
		{

			var	fusion = this,
				popup = $('#fusion-popup'),
				form = $('#fusion-sc-form', popup),
				shortcode = $('#_fusion_shortcode', form).text(),
				popupType = $('#_fusion_popup', form).text(),
				uShortcode = '';

			// if its the shortcode selection popup
			if($('#_fusion_popup').text() == 'shortcode-generator') {
				$('.fusion-sc-form-button').hide();
			}

			// resize TB
			themefusion_shortcodes.resizeTB();
			$(window).resize(function() { themefusion_shortcodes.resizeTB() });

			// initialise
			themefusion_shortcodes.loadVals();
			themefusion_shortcodes.children();
			themefusion_shortcodes.cLoadVals();

			// update on children value change
			$('.fusion-cinput', form).live('change', function() {
				themefusion_shortcodes.cLoadVals();
			});

			// update on value change
			$('.fusion-input', form).live('change', function() {
				themefusion_shortcodes.loadVals();
			});

			// change shortcode when a user selects a shortcode from choose a dropdown field
			$('#fusion_select_shortcode').change(function() {
				var name = $(this).val();
				var label = $(this).text();

				if(name != 'select') {
					fusion_popup(false, {
						title: label,
						identifier: name
					});

					$('#TB_window').hide();
				}
			});

			// activate chosen
			$('.fusion-form-multiple-select').chosen({
				width: '100%',
				placeholder_text_multiple: 'Select Options'
			});

			// update upload button ID
			jQuery('.fusion-upload-button:not(:first)').each(function() {
				var prev_upload_id = jQuery(this).data('upid');
				var new_upload_id = prev_upload_id + 1;
				jQuery(this).attr('data-upid', new_upload_id);
			});
		}
	}

	// run
	$('#fusion-popup').livequery(function() {
		themefusion_shortcodes.load();

		$('#fusion-popup').closest('#TB_window').addClass('fusion-shortcodes-popup');

		$('#fusion_video_content').closest('li').hide();

			// activate color picker
			$('.wp-color-picker-field').wpColorPicker({
				change: function(event, ui) {
					setTimeout(function() {
						themefusion_shortcodes.loadVals();
						themefusion_shortcodes.cLoadVals();
					},
					1);
				}
			});
	});

	// when insert is clicked
	$('.fusion-insert').live('click', function() {

		if( using_text_editor ) {
			if( $('#fusion_select_shortcode').val() != 'table' ) {
				using_text_editor = false;

				// switch back to text editor mode
				window.switchEditors.go( 'content', html_editor_toggle );

				var html = $('#_fusion_ushortcode').html().replace( /<br>/g, '' );

				// inserting the new shortcode at the correct position in the text editor content field
				editor_area.val( [ editor_area.val().slice(0, cursor_position), html, editor_area.val().slice(cursor_position)].join( '' ) );

				tb_remove();
			}

		} else if(window.tinyMCE)
		{
			window.tinyMCE.activeEditor.execCommand('mceInsertContent', false, $('#_fusion_ushortcode').html());
			tb_remove();
		}
	});

	//tinymce.init(tinyMCEPreInit.mceInit['fusion_content']);
	//tinymce.execCommand('mceAddControl', true, 'fusion_content');
	//quicktags({id: 'fusion_content'});

	// activate upload button
	$('.fusion-upload-button').live('click', function(e) {
		e.preventDefault();

		upid = $(this).attr('data-upid');

		if($(this).hasClass('remove-image')) {
			$('.fusion-upload-button[data-upid="' + upid + '"]').parent().find('img').attr('src', '').hide();
			$('.fusion-upload-button[data-upid="' + upid + '"]').parent().find('input').attr('value', '');
			$('.fusion-upload-button[data-upid="' + upid + '"]').text('Upload').removeClass('remove-image');

			return;
		}

		var file_frame = wp.media.frames.file_frame = wp.media({
			title: 'Select Image',
			button: {
				text: 'Select Image',
			},
			frame: 'post',
			multiple: false  // Set to true to allow multiple files to be selected
		});

		file_frame.open();

		$('.media-menu a:contains(Insert from URL)').remove();

		file_frame.on( 'select', function() {
			var selection = file_frame.state().get('selection');
			selection.map( function( attachment ) {
				attachment = attachment.toJSON();

				$('.fusion-upload-button[data-upid="' + upid + '"]').parent().find('img').attr('src', attachment.url).show();
				$('.fusion-upload-button[data-upid="' + upid + '"]').parent().find('input').attr('value', attachment.url);

				themefusion_shortcodes.loadVals();
				themefusion_shortcodes.cLoadVals();
			});

			$('.fusion-upload-button[data-upid="' + upid + '"]').text('Remove').addClass('remove-image');
			$('.media-modal-close').trigger('click');
		});

		file_frame.on( 'insert', function() {
			var selection = file_frame.state().get('selection');
			var size = jQuery('.attachment-display-settings .size').val();

			selection.map( function( attachment ) {
				attachment = attachment.toJSON();

				if(!size) {
					attachment.url = attachment.url;
				} else {
					attachment.url = attachment.sizes[size].url;
				}

				$('.fusion-upload-button[data-upid="' + upid + '"]').parent().find('img').attr('src', attachment.url).show();
				$('.fusion-upload-button[data-upid="' + upid + '"]').parent().find('input').attr('value', attachment.url);

				themefusion_shortcodes.loadVals();
				themefusion_shortcodes.cLoadVals();
			});

			$('.fusion-upload-button[data-upid="' + upid + '"]').text('Remove').addClass('remove-image');
			$('.media-modal-close').trigger('click');
		});
	});

	// activate iconpicker
	$('.iconpicker i').live('click', function(e) {
		e.preventDefault();

		var iconWithPrefix = $(this).attr('class');
		var fontName = $(this).attr('data-name');

		if($(this).hasClass('active')) {
			$(this).parent().find('.active').removeClass('active');

			$(this).parent().parent().find('input').attr('value', '');
		} else {
			$(this).parent().find('.active').removeClass('active');
			$(this).addClass('active');

			$(this).parent().parent().find('input').attr('value', fontName);
		}

		themefusion_shortcodes.loadVals();
		themefusion_shortcodes.cLoadVals();
	});

	// table shortcode
	$('#fusion-sc-form-table .fusion-insert').live('click', function(e) {
		e.stopPropagation();

		var shortcodeType = $('#fusion_select_shortcode').val();

		if(shortcodeType == 'table') {
			var type = $('#fusion-sc-form-table #fusion_type').val();
			var columns = $('#fusion-sc-form-table #fusion_columns').val();

			var text = '<div class="fusion-table table-' + type + '"><table width="100%"><thead><tr>';

			for(var i=0;i<columns;i++) {
				text += '<th>Column ' + (i + 1) + '</th>';
			}

			text += '</tr></thead><tbody>';

			for(var i=0;i<columns;i++) {
				text += '<tr>';
				if(columns >= 1) {
					text += '<td>Item #' + (i + 1) + '</td>';
				}
				if(columns >= 2) {
					text += '<td>Description</td>';
				}
				if(columns >= 3) {
					text += '<td>Discount:</td>';
				}
				if(columns >= 4) {
					text += '<td>$' + (i + 1) + '.00</td>';
				}
				if(columns >= 5) {
					text += '<td>$ 0.' + (i + 1) + '0</td>';
				}
				if(columns >= 6) {
					text += '<td>$ 0.' + (i + 1) + '0</td>';
				}
				text += '</tr>';
			}

			text += '<tr>';

			if(columns >= 1) {
				text += '<td><strong>All Items</strong></td>';
			}
			if(columns >= 2) {
				text += '<td><strong>Description</strong></td>';
			}
			if(columns >= 3) {
				text += '<td><strong>Your Total:</strong></td>';
			}
			if(columns >= 4) {
				text += '<td><strong>$10.00</strong></td>';
			}
			if(columns >= 5) {
				text += '<td><strong>Tax: $10.00</strong></td>';
			}
			if(columns >= 6) {
				text += '<td><strong>Gross: $10.00</strong></td>';
			}
			text += '</tr>';
			text += '</tbody></table></div>';

			if( using_text_editor ) {
				using_text_editor = false;

				// switch back to text editor mode
				window.switchEditors.go( 'content', html_editor_toggle );

				// inserting the new shortcode at the correct position in the text editor content field
				editor_area.val( [ editor_area.val().slice(0, cursor_position), text, editor_area.val().slice(cursor_position)].join( '' ) );

				tb_remove();
			} else if(window.tinyMCE)
			{
				window.tinyMCE.activeEditor.execCommand('mceInsertContent', false, text);
				tb_remove();
			}
		}
	});

	// slider shortcode
	$('#fusion_slider_type').live('change', function(e) {
		e.preventDefault();

		var type = $(this).val();
		if(type == 'video') {
			$(this).parents('ul').find('#fusion_image_content, #fusion_image_url, #fusion_image_target, #fusion_image_lightbox').closest('li').hide();
			$(this).parents('ul').find('#fusion_video_content').closest('li').show();

			$('#_fusion_cshortcode').text('[slide type="{{slider_type}}"]{{video_content}}[/slide]');
		}

		if(type == 'image') {
			$(this).parents('ul').find('#fusion_image_content, #fusion_image_url, #fusion_image_target, #fusion_image_lightbox').closest('li').show();
			$(this).parents('ul').find('#fusion_video_content').closest('li').hide();

			$('#_fusion_cshortcode').text('[slide type="{{slider_type}}" link="{{image_url}}" linktarget="{{image_target}}" lightbox="{{image_lightbox}}"]{{image_content}}[/slide]');
		}
	});

	$('.fusion-add-video-shortcode').live('click', function(e) {
		e.preventDefault();

		var shortcode = $(this).attr('href');
		var content = $(this).parents('ul').find('#fusion_video_content');

		content.val(content.val() + shortcode);
		themefusion_shortcodes.cLoadVals();
	});

	$('#fusion-popup textarea').live('focus', function() {
		var text = $(this).val();

		if(text == 'Your Content Goes Here') {
			$(this).val('');
		}
	});

	$('.fusion-gallery-button').live('click', function(e) {
		var gallery_file_frame;

		e.preventDefault();

		alert('To add images to this post or page for attachments layout, navigate to "Upload Files" tab in media manager and upload new images.');

		gallery_file_frame = wp.media.frames.gallery_file_frame = wp.media({
			title: 'Attach Images to Post/Page',
			button: {
				text: 'Go Back to Shortcode',
			},
			frame: 'post',
			multiple: true  // Set to true to allow multiple files to be selected
		});

		gallery_file_frame.open();

		$('.media-menu a:contains(Insert from URL)').remove();

		$('.media-menu-item:contains("Upload Files")').trigger('click');

		gallery_file_frame.on( 'select', function() {
			$('.media-modal-close').trigger('click');

			themefusion_shortcodes.loadVals();
			themefusion_shortcodes.cLoadVals();
		});
	});

	// text editor shortcode button was used
	jQuery(window).resize(function() {
		$('.quicktags-toolbar input[id*=fusion_shortcodes_text_mode]').addClass( 'fusion-shortcode-generator-button' );
	});
	$( '.switch-html, .fusion-expand-child' ).live('click', function(e) {
		$('.quicktags-toolbar input[id*=fusion_shortcodes_text_mode]').addClass( 'fusion-shortcode-generator-button' );
	});

	$('.quicktags-toolbar input[id*="fusion_shortcodes_text_mode"]').each(function() {
		$(this).addClass( 'fusion-shortcode-generator-button' );
	})

	$('.quicktags-toolbar input[id*=fusion_shortcodes_text_mode]').live('click', function(e) {

		var popup = 'shortcode-generator';

		// set the flag for text editor, change to visual editor
		using_text_editor = true;
		text_editor_toggle = 'tmce';
		html_editor_toggle = 'html';
		editor_area = $( this ).parents( '.wp-editor-container' ).find( '.wp-editor-area' );

		cursor_position = editor_area.getCursorPosition();

		window.switchEditors.go( 'content' );

		// load thickbox
		tb_show("Fusion Shortcodes", ajaxurl + "?action=fusion_shortcodes_popup&popup=" + popup);

		jQuery('#TB_window').hide();
	});
});


// Helper function to check the cursor position of text editor content field before the shortcode generator is opened
(function($, undefined) {
    $.fn.getCursorPosition = function() {
        var el = $(this).get(0);
        var pos = 0;
        if ('selectionStart' in el) {
            pos = el.selectionStart;
        } else if ('selection' in document) {
            el.focus();
            var Sel = document.selection.createRange();
            var SelLength = document.selection.createRange().text.length;
            Sel.moveStart('character', -el.value.length);
            pos = Sel.text.length - SelLength;
        }
        return pos;
    }
})(jQuery);