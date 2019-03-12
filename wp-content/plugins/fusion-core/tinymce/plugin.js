tinymce.PluginManager.add('fusion_button', function(ed, url) {
	ed.addCommand("fusionPopup", function ( a, params )
	{
		var popup = 'shortcode-generator';

		if(typeof params != 'undefined' && params.identifier) {
			popup = params.identifier;
		}

		// load thickbox
		tb_show("Fusion Shortcodes", ajaxurl + "?action=fusion_shortcodes_popup&popup=" + popup);

		jQuery('#TB_window').hide();
	});

	// Add a button that opens a window
	ed.addButton('fusion_button', {
		text: '',
		icon: true,
		image: FusionShortcodes.plugin_folder + '/tinymce/images/icon.png',
		cmd: 'fusionPopup'
	});
});