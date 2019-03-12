<?php
$avada_theme = wp_get_theme();
if ($avada_theme->parent_theme) {
	$template_dir =  basename(get_template_directory());
	$avada_theme = wp_get_theme($template_dir);
}
$avada_version = Avada()->get_version();
$theme_fusion_url = 'https://theme-fusion.com/';
?>
<div class="wrap about-wrap avada-wrap">
	<h1><?php _e( "Welcome to Avada!", "Avada" ); ?></h1>
	<div class="about-text"><?php printf( esc_html__( 'Avada is now installed and ready to use! Get ready to build something beautiful. Please register your purchase to get support and automatic theme updates. Read below for additional information. We hope you enjoy it! %s', 'Avada' ), '<a href="//www.youtube.com/embed/dn6g_gJDAIk?rel=0&TB_iframe=true&height=540&width=960" class="thickbox" title="' . esc_attr__( 'Guided Tour of Avada', 'Avada' ) . '">' . esc_attr__( 'Watch Our Quick Guided Tour!', 'Avada' ) . '</a>' ); ?></div>
	<div class="avada-logo"><span class="avada-version"><?php _e( "Version", "Avada" ); ?> <?php echo $avada_version; ?></span></div>
	<h2 class="nav-tab-wrapper">
		<a href="<?php echo admin_url( 'admin.php?page=avada' ); ?>" class="nav-tab"><?php esc_attr_e( 'Product Registration', 'Avada' ); ?></a>
		<a href="<?php echo admin_url( 'admin.php?page=avada-support' ); ?>" class="nav-tab"><?php esc_attr_e( 'Support', 'Avada' ); ?></a>
		<a href="<?php echo admin_url( 'admin.php?page=avada-demos' ); ?>" class="nav-tab"><?php esc_attr_e( 'Install Demos', 'Avada' ); ?></a>
		<a href="<?php echo admin_url( 'admin.php?page=avada-plugins' ); ?>" class="nav-tab"><?php esc_attr_e( 'Plugins', 'Avada' ); ?></a>
		<a href="#" class="nav-tab nav-tab-active"><?php esc_attr_e( 'System Status', 'Avada' ); ?></a>
	</h2>
	<div class="avada-system-status">
		<table class="widefat fusion-system-status-debug" cellspacing="0">
			<tbody>
				<tr>
					<td colspan="3" data-export-label="Avada Versions">
						<span class="get-system-status"><a href="#" class="button-primary debug-report"><?php _e( 'Get System Report', 'Avada' ); ?></a><span class="system-report-msg"><?php _e( 'Click the button to produce a report, then copy and paste into your support ticket.', 'Avada' ); ?></span></span>

						<div id="debug-report">
							<textarea readonly="readonly"></textarea>
							<p class="submit"><button id="copy-for-support" class="button-primary" href="#" data-tip="<?php _e( 'Copied!', 'Avada' ); ?>"><?php _e( 'Copy for Support', 'Avada' ); ?></button></p>
						</div>
					</td>
				</tr>
			</tbody>
		</div>
		<h3 class="screen-reader-text"><?php _e( 'Avada Versions', 'Avada' ); ?></h3>
		<table class="widefat" cellspacing="0">
			<thead>
				<tr>
					<th colspan="3" data-export-label="Avada Versions"><?php _e( 'Avada Versions', 'Avada' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td data-export-label="Current Version"><?php _e( 'Current Version:', 'Avada' ); ?></td>
					<td class="help">&nbsp;</td>
					<td><?php echo Avada()->get_theme_version(); ?></td>
				</tr>
				<tr>
					<td data-export-label="Previous Version"><?php _e( 'Update History:', 'Avada' ); ?></td>
					<td class="help">&nbsp;</td>
					<?php $previous_version = get_option( 'avada_previous_version', false );
					if ( ! $previous_version ) {
						$previous_version = __( 'No previous versions could be detected', 'Avada' );
					} else {
						if ( is_array( $previous_version ) ) {
							$previous_version = implode( ' <span style="font-size:1em;line-height:inherit;" class="dashicons dashicons-arrow-right-alt"></span> ', $previous_version );
						}
					}
					?>
					<td><?php echo $previous_version; ?></td>
				</tr>
				<?php
				$show_400_migration = false;
				$previous_version = get_option( 'avada_previous_version', false );
				if ( is_array( $previous_version ) ) {
					foreach ( $previous_version as $ver ) {
						if ( version_compare( $ver, '4.0.0', '<' ) ) {
							$show_400_migration = true;
						}
						$last_version = $ver;
					}
				} else {
					if ( version_compare( $previous_version, '4.0.0', '<' ) ) {
						$show_400_migration = true;
					}
					$last_version = $previous_version;
				}
				?>
				<?php if ( $show_400_migration ) : ?>
					<?php $latest_version = ( empty( $last_version ) || ! $last_version ) ? esc_attr__( 'Previous Version', 'Avada' ) : sprintf( esc_attr__( 'Version %s', 'Avada' ), $last_version ); ?>
					<tr>
						<td></td>
						<td class="help">&nbsp;</td>
						<td>
							<a id="avada-manual-migration-trigger" href="#">
								<?php printf( esc_attr__( 'Run Manual Migration From %s to Version 4.0', 'Avada' ), $latest_version ); ?>
							</a>
						</td>
						<script type="text/javascript">
						jQuery( '#avada-manual-migration-trigger' ).on( 'click', function() {
							var migration_response = confirm( "<?php printf( esc_attr__( 'Warning: By clicking OK, all changes made to your theme options after installing Avada 4.0 will be lost. Your Theme Options will be reset to the values from %s and then migrated again to 4.0.', 'Avada' ), $latest_version ); ?>" );
							if ( migration_response == true ) {
								window.location= "<?php echo admin_url( 'index.php?avada_update=1&ver=400&new=1' ); ?>";
							}
						});
						</script>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>

		<h3 class="screen-reader-text"><?php _e( 'WordPress Environment', 'Avada' ); ?></h3>
		<table class="widefat" cellspacing="0">
			<thead>
				<tr>
					<th colspan="3" data-export-label="WordPress Environment"><?php _e( 'WordPress Environment', 'Avada' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td data-export-label="Home URL"><?php _e( 'Home URL:', 'Avada' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The URL of your site\'s homepage.', 'Avada'  ) . '">[?]</a>'; ?></td>
					<td><?php echo home_url(); ?></td>
				</tr>
				<tr>
					<td data-export-label="Site URL"><?php _e( 'Site URL:', 'Avada' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The root URL of your site.', 'Avada'  ) . '">[?]</a>'; ?></td>
					<td><?php echo site_url(); ?></td>
				</tr>
				<tr>
					<td data-export-label="WP Version"><?php _e( 'WP Version:', 'Avada' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The version of WordPress installed on your site.', 'Avada'  ) . '">[?]</a>'; ?></td>
					<td><?php bloginfo('version'); ?></td>
				</tr>
				<tr>
					<td data-export-label="WP Multisite"><?php _e( 'WP Multisite:', 'Avada' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Whether or not you have WordPress Multisite enabled.', 'Avada'  ) . '">[?]</a>'; ?></td>
					<td><?php if ( is_multisite() ) echo '&#10004;'; else echo '&ndash;'; ?></td>
				</tr>
				<tr>
					<td data-export-label="WP Memory Limit"><?php _e( 'WP Memory Limit:', 'Avada' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The maximum amount of memory (RAM) that your site can use at one time.', 'Avada'  ) . '">[?]</a>'; ?></td>
					<td><?php
						$memory = $this->let_to_num( WP_MEMORY_LIMIT );
						if ( $memory < 128000000 ) {
							echo '<mark class="error">' . sprintf( __( '%s - We recommend setting memory to at least <strong>128MB</strong>. <br /> To import classic demo data, <strong>256MB</strong> of memory limit is required. <br /> Please define memory limit in <strong>wp-config.php</strong> file. To learn how, see: <a href="%s" target="_blank">Increasing memory allocated to PHP.</a>', 'Avada' ), size_format( $memory ), 'http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' ) . '</mark>';
						} else {
							echo '<mark class="yes">' . size_format( $memory ) . '</mark>';
							if ( $memory < 256000000 ) {
								echo '<br /><mark class="error">' . __( 'Your current memory limit is sufficient, but if you need to import classic demo content, the required memory limit is <strong>256MB.</strong>', 'Avada' ) . '</mark>';
							}
						}
					?></td>
				</tr>
				<tr>
					<td data-export-label="WP Debug Mode"><?php _e( 'WP Debug Mode:', 'Avada' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Displays whether or not WordPress is in Debug Mode.', 'Avada'  ) . '">[?]</a>'; ?></td>
					<td><?php if ( defined('WP_DEBUG') && WP_DEBUG ) echo '<mark class="yes">' . '&#10004;' . '</mark>'; else echo '<mark class="no">' . '&ndash;' . '</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="Language"><?php _e( 'Language:', 'Avada' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The current language used by WordPress. Default = English', 'Avada'  ) . '">[?]</a>'; ?></td>
					<td><?php echo get_locale() ?></td>
				</tr>
			</tbody>
		</table>

		<h3 class="screen-reader-text"><?php _e( 'Server Environment', 'Avada' ); ?></h3>
		<table class="widefat" cellspacing="0">
			<thead>
				<tr>
					<th colspan="3" data-export-label="Server Environment"><?php _e( 'Server Environment', 'Avada' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td data-export-label="Server Info"><?php _e( 'Server Info:', 'Avada' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Information about the web server that is currently hosting your site.', 'Avada'  ) . '">[?]</a>'; ?></td>
					<td><?php echo esc_html( $_SERVER['SERVER_SOFTWARE'] ); ?></td>
				</tr>
				<tr>
					<td data-export-label="PHP Version"><?php _e( 'PHP Version:', 'Avada' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The version of PHP installed on your hosting server.', 'Avada'  ) . '">[?]</a>'; ?></td>
					<td><?php if ( function_exists( 'phpversion' ) ) echo esc_html( phpversion() ); ?></td>
				</tr>
				<?php if ( function_exists( 'ini_get' ) ) : ?>
					<tr>
						<td data-export-label="PHP Post Max Size"><?php _e( 'PHP Post Max Size:', 'Avada' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The largest file size that can be contained in one post.', 'Avada'  ) . '">[?]</a>'; ?></td>
						<td><?php echo size_format( $this->let_to_num( ini_get('post_max_size') ) ); ?></td>
					</tr>
					<tr>
						<td data-export-label="PHP Time Limit"><?php _e( 'PHP Time Limit:', 'Avada' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The amount of time (in seconds) that your site will spend on a single operation before timing out (to avoid server lockups)', 'Avada'  ) . '">[?]</a>'; ?></td>
						<td><?php
							$time_limit = ini_get('max_execution_time');

							if ( $time_limit < 180 && $time_limit != 0 ) {
								echo '<mark class="error">' . sprintf( __( '%s - We recommend setting max execution time to at least 180. <br /> To import classic demo content, <strong>300</strong> seconds of max execution time is required.<br />See: <a href="%s" target="_blank">Increasing max execution to PHP</a>', 'Avada' ), $time_limit, 'http://codex.wordpress.org/Common_WordPress_Errors#Maximum_execution_time_exceeded' ) . '</mark>';
							} else {
								echo '<mark class="yes">' . $time_limit . '</mark>';
								if ( $time_limit < 300 && $time_limit != 0 ) {
									echo '<br /><mark class="error">' . __( 'Current time limit is sufficient, but if you need import classic demo content, the required time is <strong>300</strong>.', 'Avada' ) . '</mark>';
								}
							}
						?></td>
					</tr>
					<tr>
						<td data-export-label="PHP Max Input Vars"><?php _e( 'PHP Max Input Vars:', 'Avada' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'Avada'  ) . '">[?]</a>'; ?></td>
						<?php
						$registered_navs = get_nav_menu_locations();
						$menu_items_count = array( "0" => "0" );
						foreach( $registered_navs as $handle => $registered_nav ) {
							$menu = wp_get_nav_menu_object( $registered_nav );
							if ( $menu ) {
								$menu_items_count[] = $menu->count;
							}
						}

						$max_items = max( $menu_items_count );
						if ( Avada()->settings->get( 'disable_megamenu' ) ) {
							$required_input_vars = $max_items * 20;
						} else {
							$required_input_vars = $max_items * 12;
						}
						?>
						<td><?php
							$max_input_vars = ini_get('max_input_vars');
							$required_input_vars = $required_input_vars + ( 500 + 1000 );
							// 1000 = theme options

							if ( $max_input_vars < $required_input_vars ) {
								echo '<mark class="error">' . sprintf( __( '%s - Recommended Value: %s.<br />Max input vars limitation will truncate POST data such as menus. See: <a href="%s" target="_blank">Increasing max input vars limit.</a>', 'Avada' ), $max_input_vars, '<strong>' . $required_input_vars . '</strong>', 'http://sevenspark.com/docs/ubermenu-3/faqs/menu-item-limit' ) . '</mark>';
							} else {
								echo '<mark class="yes">' . $max_input_vars . '</mark>';
							}
						?></td>
					</tr>
					<tr>
						<td data-export-label="SUHOSIN Installed"><?php _e( 'SUHOSIN Installed:', 'Avada' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Suhosin is an advanced protection system for PHP installations. It was designed to protect your servers on the one hand against a number of well known problems in PHP applications and on the other hand against potential unknown vulnerabilities within these applications or the PHP core itself.
		If enabled on your server, Suhosin may need to be configured to increase its data submission limits.', 'Avada'  ) . '">[?]</a>'; ?></td>
						<td><?php echo extension_loaded( 'suhosin' ) ? '&#10004;' : '&ndash;'; ?></td>
					</tr>
					<?php if ( extension_loaded( 'suhosin' ) ): ?>
					<tr>
						<td data-export-label="Suhosin Post Max Vars"><?php _e( 'Suhosin Post Max Vars:', 'Avada' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'Avada'  ) . '">[?]</a>'; ?></td>
						<?php
						$registered_navs = get_nav_menu_locations();
						$menu_items_count = array( "0" => "0" );
						foreach( $registered_navs as $handle => $registered_nav ) {
							$menu = wp_get_nav_menu_object( $registered_nav );
							if ( $menu ) {
								$menu_items_count[] = $menu->count;
							}
						}

						$max_items = max( $menu_items_count );
						if ( Avada()->settings->get( 'disable_megamenu' ) ) {
							$required_input_vars = $max_items * 20;
						} else {
							$required_input_vars = ini_get( 'suhosin.post.max_vars' );
						}
						?>
						<td><?php
							$max_input_vars = ini_get( 'suhosin.post.max_vars' );
							$required_input_vars = $required_input_vars + ( 500 + 1000 );

							if ( $max_input_vars < $required_input_vars ) {
								echo '<mark class="error">' . sprintf( __( '%s - Recommended Value: %s.<br />Max input vars limitation will truncate POST data such as menus. See: <a href="%s" target="_blank">Increasing max input vars limit.</a>', 'Avada' ), $max_input_vars, '<strong>' . ( $required_input_vars + ( 500 + 1000 ) ) . '</strong>', 'http://sevenspark.com/docs/ubermenu-3/faqs/menu-item-limit' ) . '</mark>';
							} else {
								echo '<mark class="yes">' . $max_input_vars . '</mark>';
							}
						?></td>
					</tr>
					<tr>
						<td data-export-label="Suhosin Request Max Vars"><?php _e( 'Suhosin Request Max Vars:', 'Avada' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'Avada'  ) . '">[?]</a>'; ?></td>
						<?php
						$registered_navs = get_nav_menu_locations();
						$menu_items_count = array( "0" => "0" );
						foreach( $registered_navs as $handle => $registered_nav ) {
							$menu = wp_get_nav_menu_object( $registered_nav );
							if ( $menu ) {
								$menu_items_count[] = $menu->count;
							}
						}

						$max_items = max( $menu_items_count );
						if ( Avada()->settings->get( 'disable_megamenu' ) ) {
							$required_input_vars = $max_items * 20;
						} else {
							$required_input_vars = ini_get( 'suhosin.request.max_vars' );
						}
						?>
						<td><?php
							$max_input_vars = ini_get( 'suhosin.request.max_vars' );
							$required_input_vars = $required_input_vars + ( 500 + 1000 );

							if ( $max_input_vars < $required_input_vars ) {
								echo '<mark class="error">' . sprintf( __( '%s - Recommended Value: %s.<br />Max input vars limitation will truncate POST data such as menus. See: <a href="%s" target="_blank">Increasing max input vars limit.</a>', 'Avada' ), $max_input_vars, '<strong>' . ( $required_input_vars + ( 500 + 1000 ) ) . '</strong>', 'http://sevenspark.com/docs/ubermenu-3/faqs/menu-item-limit' ) . '</mark>';
							} else {
								echo '<mark class="yes">' . $max_input_vars . '</mark>';
							}
						?></td>
					</tr>
					<tr>
						<td data-export-label="Suhosin Post Max Value Length"><?php _e( 'Suhosin Post Max Value Length:', 'Avada' ); ?></td>
						<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Defines the maximum length of a variable that is registered through a POST request.', 'Avada'  ) . '">[?]</a>'; ?></td>
						<td><?php
							$suhosin_max_value_length = ini_get( "suhosin.post.max_value_length" );
							$recommended_max_value_length = 2000000;

							if ( $suhosin_max_value_length < $recommended_max_value_length ) {
								echo '<mark class="error">' . sprintf( __( '%s - Recommended Value: %s.<br />Post Max Value Length limitation may prohibit the Theme Options data from being saved to your database. See: <a href="%s" target="_blank">Suhosin Configuration Info</a>.', 'Avada' ), $suhosin_max_value_length, '<strong>' . $recommended_max_value_length . '</strong>', 'http://suhosin.org/stories/configuration.html' ) . '</mark>';
							} else {
								echo '<mark class="yes">' . $suhosin_max_value_length . '</mark>';
							}
						?></td>
					</tr>
					<?php endif; ?>
				<?php endif; ?>
				<tr>
					<td data-export-label="ZipArchive"><?php _e( 'ZipArchive:', 'Avada' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'ZipArchive is required for importing demos. They are used to import and export zip files specifically for sliders.', 'Avada'  ) . '">[?]</a>'; ?></td>
					<td><?php echo class_exists( 'ZipArchive' ) ? '<mark class="yes">&#10004;</mark>' : '<mark class="error">ZipArchive is not installed on your server, but is required if you need to import demo content.</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="MySQL Version"><?php _e( 'MySQL Version:', 'Avada' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The version of MySQL installed on your hosting server.', 'Avada'  ) . '">[?]</a>'; ?></td>
					<td>
						<?php
						/** @global wpdb $wpdb */
						global $wpdb;
						echo $wpdb->db_version();
						?>
					</td>
				</tr>
				<tr>
					<td data-export-label="Max Upload Size"><?php _e( 'Max Upload Size:', 'Avada' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'The largest file size that can be uploaded to your WordPress installation.', 'Avada'  ) . '">[?]</a>'; ?></td>
					<td><?php echo size_format( wp_max_upload_size() ); ?></td>
				</tr>
				<tr>
					<td data-export-label="DOMDocument"><?php _e( 'DOMDocument:', 'Avada' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'DOMDocument is required for the Fusion Builder plugin to properly function.', 'Avada'  ) . '">[?]</a>'; ?></td>
					<td><?php echo class_exists( 'DOMDocument' ) ? '<mark class="yes">&#10004;</mark>' : '<mark class="error">DOMDocument is not installed on your server, but is required if you need to use the Fusion Page Builder.</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="WP Remote Get"><?php _e( 'WP Remote Get:', 'Avada' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Avada uses this method to communicate with different APIs, e.g. Google, Twitter, Facebook.', 'Avada'  ) . '">[?]</a>'; ?></td>
					<?php $response = wp_remote_get( 'http://avada.theme-fusion.com/test.php', array( 'decompress' => false ) ); ?>
					<td><?php echo ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) ? '<mark class="yes">&#10004;</mark>' : '<mark class="error">wp_remote_get() failed. Some theme features may not work. Please contact your hosting provider and make sure that http://avada.theme-fusion.com/test.php is not blocked.</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="WP Remote Post"><?php _e( 'WP Remote Post:', 'Avada' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Avada uses this method to communicate with different APIs, e.g. Google, Twitter, Facebook.', 'Avada'  ) . '">[?]</a>'; ?></td>
					<?php $response = wp_remote_post( 'http://avada.theme-fusion.com/test.php', array( 'decompress' => false ) ); ?>
					<td><?php echo ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) ? '<mark class="yes">&#10004;</mark>' : '<mark class="error">wp_remote_post() failed. Some theme features may not work. Please contact your hosting provider and make sure that http://avada.theme-fusion.com/test.php is not blocked.</mark>'; ?></td>
				</tr>
				<tr>
					<td data-export-label="GD Library"><?php _e( 'GD Library:', 'Avada' ); ?></td>
					<td class="help"><?php echo '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Avada uses this library to resize images and speed up your site\'s loading time', 'Avada'  ) . '">[?]</a>'; ?></td>
					<td>
						<?php
						$info = esc_attr__( 'Not Installed', 'Avada' );
						if ( extension_loaded( 'gd' ) && function_exists( 'gd_info' ) ) {
							$info = esc_attr__( 'Installed', 'Avada' );
							$gd_info = gd_info();
							if ( isset( $gd_info['GD Version'] ) ) {
								$info = $gd_info['GD Version'];
							}
						}
						echo $info;
						?>
					</td>
				</tr>
			</tbody>
		</table>

		<h3 class="screen-reader-text"><?php _e( 'Active Plugins', 'Avada' ); ?></h3>
		<table class="widefat" cellspacing="0" id="status">
			<thead>
				<tr>
					<th colspan="3" data-export-label="Active Plugins (<?php echo count( (array) get_option( 'active_plugins' ) ); ?>)"><?php _e( 'Active Plugins', 'Avada' ); ?> (<?php echo count( (array) get_option( 'active_plugins' ) ); ?>)</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$active_plugins = (array) get_option( 'active_plugins', array() );

				if ( is_multisite() ) {
					$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
				}

				foreach ( $active_plugins as $plugin ) {

					$plugin_data    = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
					$dirname        = dirname( $plugin );
					$version_string = '';
					$network_string = '';

					if ( ! empty( $plugin_data['Name'] ) ) {

						// link the plugin name to the plugin url if available
						$plugin_name = esc_html( $plugin_data['Name'] );

						if ( ! empty( $plugin_data['PluginURI'] ) ) {
							$plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . __( 'Visit plugin homepage' , 'Avada' ) . '">' . $plugin_name . '</a>';
						}
						?>
						<tr>
							<td><?php echo $plugin_name; ?></td>
							<td class="help">&nbsp;</td>
							<td><?php printf( _x( 'by %s', 'by author', 'Avada' ), $plugin_data['Author'] ) . ' &ndash; ' . esc_html( $plugin_data['Version'] ) . $version_string . $network_string; ?></td>
						</tr>
						<?php
					}
				}
				?>
			</tbody>
		</table>
	</div>
	<div class="avada-thanks">
		<hr />
		<p class="description"><?php _e( "Thank you for choosing Avada. We are honored and are fully dedicated to making your experience perfect.", "Avada" ); ?></p>
	</div>
</div>
