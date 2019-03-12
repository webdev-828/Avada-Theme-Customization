<?php

/**
 * Require the installation of any required and/or recommended third-party plugins here.
 * See http://tgmpluginactivation.com/ for more details
 */
function avada_register_required_plugins() {
	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		array(
			'name'               => 'Fusion Core',
			'slug'               => 'fusion-core',
			'source'             => get_template_directory() . '/includes/plugins/fusion-core.zip',
			'required'           => true,
			'version'            => '2.0.2',
			'force_activation'   => false,
			'force_deactivation' => false,
			'external_url'       => '',
			'image_url'          => get_template_directory_uri() . '/assets/admin/images/plugin-thumbnails/fusion_core.png',
		),
		array(
			'name'               => 'LayerSlider WP',
			'slug'               => 'LayerSlider',
			'source'             => get_template_directory() . '/includes/plugins/LayerSlider.zip',
			'required'           => false,
			'version'            => '5.6.6',
			'force_activation'   => false,
			'force_deactivation' => false,
			'external_url'       => '',
			'image_url'          => get_template_directory_uri() . '/assets/admin/images/plugin-thumbnails/layer_slider.png',
		),
		array(
			'name'               => 'Revolution Slider',
			'slug'               => 'revslider',
			'source'             => get_template_directory() . '/includes/plugins/revslider.zip',
			'required'           => false,
			'version'            => '5.2.5',
			'force_activation'   => false,
			'force_deactivation' => false,
			'external_url'       => '',
			'image_url'          => get_template_directory_uri() . '/assets/admin/images/plugin-thumbnails/rev_slider.png',
		),
		array(
			'name'      => 'WooCommerce',
			'slug'      => 'woocommerce',
			'required'  => false,
			'image_url' => get_template_directory_uri() . '/assets/admin/images/plugin-thumbnails/woocommerce.png',
		),
		array(
			'name'      => 'bbPress',
			'slug'      => 'bbpress',
			'required'  => false,
			'image_url' => get_template_directory_uri() . '/assets/admin/images/plugin-thumbnails/bbpress.png',
		),
		array(
			'name'      => 'The Events Calendar',
			'slug'      => 'the-events-calendar',
			'required'  => false,
			'image_url' => get_template_directory_uri() . '/assets/admin/images/plugin-thumbnails/the_events_calendar.png',
		),
		array(
			'name'      => 'Contact Form 7',
			'slug'      => 'contact-form-7',
			'required'  => false,
			'image_url' => get_template_directory_uri() . '/assets/admin/images/plugin-thumbnails/contact_form_7.jpg',
		),
	);

	// Change this to your theme text domain, used for internationalising strings
	$theme_text_domain = 'Avada';

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(

		'domain'        	=> $theme_text_domain,
		'default_path'  	=> '',
		'parent_slug' 		=> 'themes.php',
		'menu'            	=> 'install-required-plugins',
		'has_notices'     	=> true,
		'is_automatic'    	=> true,
		'message'         	=> '',
		'strings'         	=> array(
			'page_title'                      => __( 'Install Required Plugins', 'Avada' ),
			'menu_title'                      => __( 'Install Plugins', 'Avada' ),
			'installing'                      => __( 'Installing Plugin: %s', 'Avada' ), // %1$s = plugin name
			'oops'                            => __( 'Something went wrong with the plugin API.', 'Avada' ),
			'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin installed or updated: %1$s.', 'This theme requires the following plugins installed or updated: %1$s.', 'Avada' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'  => _n_noop( str_replace( '{{system-status}}', admin_url( 'admin.php?page=avada-system-status' ), 'This theme recommends the following plugin installed or updated: %1$s.<br />IMPORTANT: If your hosting plan has low resources, activating additional plugins can lead to fatal "out of memory" errors. We recommend at least 128MB of memory. Check your resources on the <a href="{{system-status}}" target="_self">System Status</a> tab.' ), str_replace( '{{system-status}}', admin_url( 'admin.php?page=avada-system-status' ), 'This theme recommends the following plugins installed or updated: %1$s.<br />IMPORTANT: If your hosting plan has low resources, activating additional plugins can lead to fatal "out of memory" errors. We recommend at least 128MB of memory. Check your resources on the <a href="{{system-status}}" target="_self">System Status</a> tab.' ), 'Avada' ), // %1$s = plugin name(s)
			'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'Avada' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'Avada' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended' => _n_noop( str_replace( '{{system-status}}', admin_url( 'admin.php?page=avada-system-status' ), 'The following recommended plugin is currently inactive: %1$s.<br />IMPORTANT: If your hosting plan has low resources, activating additional plugins can lead to fatal "out of memory" errors. We recommend at least 128MB of memory. Check your resources on the <a href="{{system-status}}" target="_self">System Status</a> tab.' ), str_replace( '{{system-status}}', admin_url( 'admin.php?page=avada-system-status' ), 'The following recommended plugins are currently inactive: %1$s.<br />IMPORTANT: If your hosting plan has low resources, activating additional plugins can lead to fatal "out of memory" errors. We recommend at least 128MB of memory. Check your resources on the <a href="{{system-status}}" target="_self">System Status</a> tab.' ), 'Avada' ), // %1$s = plugin name(s)
			'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'Avada' ), // %1$s = plugin name(s)
			'notice_ask_to_update'            => _n_noop( '<span class="fusion-update-heading" style="margin-top:-0.4em">%1$s Update Required</span>The plugin needs to be updated to its latest version to ensure maximum compatibility with Avada.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'Avada' ), // %1$s = plugin name(s)
			'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'Avada' ), // %1$s = plugin name(s)
			'install_link'                    => _n_noop( 'Go Install Plugin', 'Go Install Plugins', 'Avada' ),
			'activate_link'                   => _n_noop( 'Go Activate Plugin', 'Go Activate Plugins', 'Avada' ),
			'return'                          => __( 'Return to Required Plugins Installer', 'Avada' ),
			'plugin_activated'                => __( 'Plugin activated successfully.', 'Avada' ),
			'complete'                        => __( 'All plugins installed and activated successfully. %s', 'Avada' ), // %1$s = dashboard link
			'nag_type'                        => 'error' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'avada_register_required_plugins' );

// Omit closing PHP tag to avoid "Headers already sent" issues.
