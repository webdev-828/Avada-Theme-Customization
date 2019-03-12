<?php

// Activation and de-activation hooks
register_activation_hook(LS_ROOT_FILE, 'layerslider_activation_scripts');
register_deactivation_hook(LS_ROOT_FILE, 'layerslider_deactivation_scripts');
register_uninstall_hook(LS_ROOT_FILE, 'layerslider_uninstall_scripts');

// Run activation scripts when adding new sites to a multisite installation
add_action('wpmu_new_blog', 'layerslider_new_site');

// Update handler
if(get_option('ls-plugin-version', '1.0.0') !== LS_PLUGIN_VERSION) {
	update_option('ls-plugin-version', LS_PLUGIN_VERSION);
	layerslider_update_scripts();
}

function layerslider_activation_scripts() {

	// Multi-site
	if(is_multisite()) {

		// Get WPDB Object
		global $wpdb;

		// Get current site
		$old_site = $wpdb->blogid;

		// Get all sites
		$sites = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");

		// Iterate over the sites
		foreach($sites as $site) {
			switch_to_blog($site);
			layerslider_create_db_table();
		}

		// Switch back the old site
		switch_to_blog($old_site);

	// Single-site
	} else {
		layerslider_create_db_table();
	}

	// Call "activated" hook
	if(has_action('layerslider_activated')) {
		do_action('layerslider_activated');
	}

	// Check new install
	layerslider_install_scripts();

	// Redirect to LS's admin page after activation
	update_option('layerslider_do_activation_redirect', 1);
}

function layerslider_install_scripts() {
	
	// Check new install
	if(!get_option('ls-installed')) {
		update_option('ls-installed', 1);

		// Google Fonts
		$fonts = array();
		$fonts[] = array( 'param' => 'Lato:100,300,regular,700,900', 'admin' => false );
		$fonts[] = array( 'param' => 'Open+Sans:300', 'admin' => false );
		$fonts[] = array( 'param' => 'Indie+Flower:regular', 'admin' => false );
		$fonts[] = array( 'param' => 'Oswald:300,regular,700', 'admin' => false );

		update_option('ls-google-fonts', $fonts);

		// Call "installed" hook
		if(has_action('layerslider_installed')) {
			do_action('layerslider_installed');
		}
	}

	// Install date
	if(!get_option('ls-date-installed', 0)) {
		update_option('ls-date-installed', time());
	}
}

function layerslider_update_scripts() {

	// Check new install
	layerslider_activation_scripts();

	if(has_action('layerslider_updated')) {
		do_action('layerslider_updated');
	}
}


function layerslider_new_site($blog_id) {


    // Get current site
    global $wpdb;
	$old_site = $wpdb->blogid;

	// Switch to new site
	switch_to_blog($blog_id);
	layerslider_create_db_table();
	switch_to_blog($old_site);
}

function layerslider_create_db_table() {

	global $wpdb;
	$charset_collate = '';
	$table_name = $wpdb->prefix . "layerslider";

	// Get DB collate
	if(!empty($wpdb->charset)) {
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
	}

	if(!empty($wpdb->collate)) {
		$charset_collate .= " COLLATE $wpdb->collate";
	}

	// Building the query
	$sql = "CREATE TABLE $table_name (
			  id int(10) NOT NULL AUTO_INCREMENT,
			  author int(10) NOT NULL DEFAULT 0,
			  name varchar(100) NOT NULL,
			  slug varchar(100) NOT NULL,
			  data mediumtext NOT NULL,
			  date_c int(10) NOT NULL,
			  date_m int(11) NOT NULL,
			  flag_hidden tinyint(1) NOT NULL DEFAULT 0,
			  flag_deleted tinyint(1) NOT NULL DEFAULT 0,
			  PRIMARY KEY  (id)
			) $charset_collate;";

	// Executing the query
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	// Execute the query
	dbDelta($sql);
	update_option('ls-db-version', '5.0.0');
}


function layerslider_deactivation_scripts() {

	// Stuff we need
	global $current_user;
	get_currentuserinfo();

	// Remove capability option, so a user can restore
	// his access to the plugin if set the wrong capability
	// delete_option('layerslider_custom_capability');

	// Remove the help pointer entry to remind a user for the
	// help menu when start to use the plugin again
	delete_user_meta($current_user->ID, 'layerslider_help_wp_pointer');

	// Call user hooks
	if(has_action('layerslider_deactivated')) {
		do_action('layerslider_deactivated');
	}
}

function layerslider_uninstall_scripts() {

	// Call user hooks
	update_option('ls-installed', 0);
	if(has_action('layerslider_uninstalled')) {
		do_action('layerslider_uninstalled');
	}
}

?>
