<?php


add_action('wp_enqueue_scripts', 'layerslider_enqueue_content_res');
add_action('wp_footer', 'layerslider_footer_scripts', 21);
add_action('admin_enqueue_scripts', 'layerslider_enqueue_admin_res');
add_action('admin_enqueue_scripts', 'ls_load_google_fonts');
add_action('wp_enqueue_scripts', 'ls_load_google_fonts');



function layerslider_enqueue_content_res() {

	// Include in the footer?
	$condsc = get_option('ls_conditional_script_loading', false) ? true : false;
	$footer = get_option('ls_include_at_footer', false) ? true : false;
	$footer = $condsc ? true : $footer;

	// Use Gogole CDN version of jQuery
	if(get_option('ls_use_custom_jquery', false)) {
		wp_deregister_script('jquery');
		wp_enqueue_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js', array(), '1.8.3');
	}

	// Register LayerSlider resources
	wp_register_script('greensock', LS_ROOT_URL.'/static/js/greensock.js', false, '1.11.8', $footer );
	wp_register_script('layerslider', LS_ROOT_URL.'/static/js/layerslider.kreaturamedia.jquery.js', array('jquery'), LS_PLUGIN_VERSION, $footer );
	wp_register_script('layerslider-transitions', LS_ROOT_URL.'/static/js/layerslider.transitions.js', false, LS_PLUGIN_VERSION, $footer );
	wp_enqueue_style('layerslider', LS_ROOT_URL.'/static/css/layerslider.css', false, LS_PLUGIN_VERSION );

	// User resources
	$uploads = wp_upload_dir();
	if(file_exists($uploads['basedir'].'/layerslider.custom.transitions.js')) {
		wp_register_script('ls-user-transitions', $uploads['baseurl'].'/layerslider.custom.transitions.js', false, LS_PLUGIN_VERSION, $footer );
	}

	if(file_exists($uploads['basedir'].'/layerslider.custom.css')) {
		wp_enqueue_style('ls-user', $uploads['baseurl'].'/layerslider.custom.css', false, LS_PLUGIN_VERSION );
	}

	if(!$footer) {
		wp_enqueue_script('greensock');
		wp_enqueue_script('layerslider');
		wp_enqueue_script('layerslider-transitions');
		wp_enqueue_script('ls-user-transitions');
	}
}



function layerslider_footer_scripts() {

	if(!empty($GLOBALS['lsSliderInit'])) {

		// Enqueue scripts
		wp_print_scripts('greensock');
		wp_print_scripts('layerslider');
		wp_print_scripts('layerslider-transitions');

		if(wp_script_is('ls-user-transitions', 'registered')) {
			wp_print_scripts('ls-user-transitions');
		}


		echo implode('', $GLOBALS['lsSliderInit']);
	}
}



function layerslider_enqueue_admin_res() {

	// Load global LayerSlider CSS
	wp_enqueue_style('layerslider-global', LS_ROOT_URL.'/static/css/global.css', false, LS_PLUGIN_VERSION );

	// Use Gogole CDN version of jQuery
	if(get_option('ls_use_custom_jquery', false)) {
		wp_deregister_script('jquery');
		wp_enqueue_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js', array(), '1.8.3');
	}

	// Load LayerSlider-only resources
	$screen = get_current_screen();
	if(strpos($screen->base, 'layerslider') !== false) {

		// New Media Library
		if(function_exists( 'wp_enqueue_media' )){ wp_enqueue_media(); }

		// Load some bundled WP resources
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
		wp_enqueue_script('wp-pointer');
		wp_enqueue_style('wp-pointer');

		// Dashicons
		if(version_compare(get_bloginfo('version'), '3.8', '<')) {
			wp_enqueue_style('dashicons', LS_ROOT_URL.'/static/css/dashicons.css', false, LS_PLUGIN_VERSION );
		}

		// Global scripts & stylesheets
		wp_enqueue_script('ls-admin-global', LS_ROOT_URL.'/static/js/ls-admin-global.js', array('jquery'), LS_PLUGIN_VERSION );
		wp_enqueue_style('layerslider-admin', LS_ROOT_URL.'/static/css/admin.css', false, LS_PLUGIN_VERSION );
		wp_enqueue_style('layerslider-admin-new', LS_ROOT_URL.'/static/css/admin_new.css', false, LS_PLUGIN_VERSION );

		// 3rd-party: CodeMirror
		wp_enqueue_style('codemirror', LS_ROOT_URL.'/static/codemirror/lib/codemirror.css', false, LS_PLUGIN_VERSION );
		wp_enqueue_script('codemirror', LS_ROOT_URL.'/static/ codemirror/lib/codemirror.js', array('jquery'), LS_PLUGIN_VERSION );
		wp_enqueue_style('codemirror-solarized', LS_ROOT_URL.'/static/codemirror/theme/solarized.mod.css', false, LS_PLUGIN_VERSION );
		wp_enqueue_script('codemirror-syntax-css', LS_ROOT_URL.'/static/codemirror/mode/css/css.js', array('jquery'), LS_PLUGIN_VERSION );
		wp_enqueue_script('codemirror-syntax-javascript', LS_ROOT_URL.'/static/codemirror/mode/javascript/javascript.js', array('jquery'), LS_PLUGIN_VERSION );
		wp_enqueue_script('codemirror-foldcode', LS_ROOT_URL.'/static/codemirror/addon/fold/foldcode.js', array('jquery'), LS_PLUGIN_VERSION );
		wp_enqueue_script('codemirror-foldgutter', LS_ROOT_URL.'/static/codemirror/addon/fold/foldgutter.js', array('jquery'), LS_PLUGIN_VERSION );
		wp_enqueue_script('codemirror-brace-fold', LS_ROOT_URL.'/static/codemirror/addon/fold/brace-fold.js', array('jquery'), LS_PLUGIN_VERSION );
		wp_enqueue_script('codemirror-active-line', LS_ROOT_URL.'/static/codemirror/addon/selection/active-line.js', array('jquery'), LS_PLUGIN_VERSION );

		// 3rd-party: Google Fonts
		wp_enqueue_style('google-fonts-indie-flower', 'http://fonts.googleapis.com/css?family=Indie+Flower', false, LS_PLUGIN_VERSION );

		// Sliders list page
		if(empty($_GET['action'])) {
			wp_enqueue_script('ls-admin-sliders', LS_ROOT_URL.'/static/js/ls-admin-sliders.js', array('jquery'), LS_PLUGIN_VERSION );

		// Slider builder
		} else {

			// Load some bundled WP resources
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('jquery-ui-draggable');
			wp_enqueue_script('jquery-ui-slider');

			// LayerSlider admin includes
			wp_enqueue_script('layerslider-admin', LS_ROOT_URL.'/static/js/ls-admin-slider-builder.js', array('jquery', 'json2'), LS_PLUGIN_VERSION );

			// LayerSlider includes for preview
			wp_enqueue_script('greensock', LS_ROOT_URL.'/static/js/greensock.js', false, '1.11.8' );
			wp_enqueue_script('layerslider', LS_ROOT_URL.'/static/js/layerslider.kreaturamedia.jquery.js', array('jquery'), LS_PLUGIN_VERSION );
			wp_enqueue_script('layerslider-transitions', LS_ROOT_URL.'/static/js/layerslider.transitions.js', false, LS_PLUGIN_VERSION );
			wp_enqueue_script('layerslider-tr-gallery', LS_ROOT_URL.'/static/js/layerslider.transition.gallery.js', array('jquery'), LS_PLUGIN_VERSION );
			wp_enqueue_style('layerslider', LS_ROOT_URL.'/static/css/layerslider.css', false, LS_PLUGIN_VERSION );
			wp_enqueue_style('layerslider-tr-gallery', LS_ROOT_URL.'/static/css/layerslider.transitiongallery.css', false, LS_PLUGIN_VERSION );

			// 3rd-party: MiniColor
			wp_enqueue_script('minicolor', LS_ROOT_URL.'/static/js/minicolors/jquery.minicolors.js', array('jquery'), LS_PLUGIN_VERSION );
			wp_enqueue_style('minicolor', LS_ROOT_URL.'/static/js/minicolors/jquery.minicolors.css', false, LS_PLUGIN_VERSION );

			// User CSS
			$uploads = wp_upload_dir();
			if(file_exists($uploads['basedir'].'/layerslider.custom.transitions.js')) {
				wp_enqueue_script('ls-user-transitions', $uploads['baseurl'].'/layerslider.custom.transitions.js', false, LS_PLUGIN_VERSION );
			}

			// User transitions
			if(file_exists($uploads['basedir'].'/layerslider.custom.css')) {
				wp_enqueue_style('ls-user', $uploads['baseurl'].'/layerslider.custom.css', false, LS_PLUGIN_VERSION );
			}
		}
	}

	// Transition builder
	if(strpos($screen->base, 'ls-transition-builder') !== false) {
		wp_enqueue_script('layerslider_tr_builder', LS_ROOT_URL.'/static/js/ls-admin-transition-builder.js', array('jquery'), LS_PLUGIN_VERSION );
	}

	// Skin editor
	if(strpos($screen->base, 'ls-skin-editor') !== false || strpos($screen->base, 'ls-style-editor') !== false) {
		wp_enqueue_style('ls-skin-editor', LS_ROOT_URL.'/static/css/skin.editor.css', false, LS_PLUGIN_VERSION );
	}
}



function ls_load_google_fonts() {

	// Get font list
	$fonts = get_option('ls-google-fonts', array());
	$scripts = get_option('ls-google-font-scripts', array('latin', 'latin-ext'));

	// Check fonts if any
	if(!empty($fonts) && is_array($fonts)) {
		$lsFonts = array();
		foreach($fonts as $item) {
			if(!is_admin() && !$item['admin']) {
				$lsFonts[] = $item['param'];
			} else {
				$lsFonts[] = $item['param'];
			}
		}
		$lsFonts = implode('%7C', $lsFonts);
		$protocol = is_ssl() ? 'https' : 'http';
		$query_args = array(
			'family' => $lsFonts,
			'subset' => implode('%2C', $scripts),
		);

		wp_enqueue_style('ls-google-fonts',
			add_query_arg($query_args, "$protocol://fonts.googleapis.com/css" ),
			array(), null
		);
	}
}
