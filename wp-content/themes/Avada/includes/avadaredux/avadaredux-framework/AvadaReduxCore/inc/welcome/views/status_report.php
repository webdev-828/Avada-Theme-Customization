<?php
	/**
	 * Admin View: Page - Status Report
	 */

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	global $wpdb;

	function avadaredux_get_support_object() {
		$obj = array();

	}

	function avadaredux_clean( $var ) {
		return sanitize_text_field( $var );
	}

	$sysinfo = AvadaRedux_Helpers::compileSystemStatus( false, true );

?>
<div class="wrap about-wrap avadaredux-status">
	<h1>
		<?php esc_html_e( 'AvadaRedux Framework - System Status', 'avadaredux-framework' ); ?>
	</h1>

	<div class="about-text">
		<?php esc_html_e( 'Our core mantra at AvadaRedux is backwards compatibility. With hundreds of thousands of instances worldwide, you can be assured that we will take care of you and your clients.', 'avadaredux-framework' ); ?></div>
	<div class="avadaredux-badge">
		<i class="el el-avadaredux"></i>
		<span>
			<?php printf( __( 'Version %s', 'avadaredux-framework' ), esc_html(AvadaReduxFramework::$_version )); ?>
		</span>
	</div>

	<?php $this->actions(); ?>
	<?php $this->tabs(); ?>

	<div class="updated avadaredux-message">
		<p>
			<?php esc_html_e( 'Please copy and paste this information in your ticket when contacting support:', 'avadaredux-framework' ); ?>
		</p>

		<p class="submit">
			<a href="#" class="button-primary debug-report">
				<?php esc_html_e( 'Get System Report', 'avadaredux-framework' ); ?>
			</a>
			<a class="skip button-primary"
			   href="http://docs.avadareduxframework.com/core/support/understanding-the-avadaredux-framework-system-status-report/"
			   target="_blank">
				   <?php esc_html_e( 'Understanding the Status Report', 'avadaredux-framework' ); ?>
			</a>
		</p>

		<div id="debug-report">
			<textarea readonly="readonly"></textarea>
			<p class="submit">
				<button id="copy-for-support"
						class="button-primary avadaredux-hint-qtip"
						href="#" qtip-content="<?php esc_html_e( 'Copied!', 'avadaredux-framework' ); ?>">
					<?php esc_html_e( 'Copy for Support', 'avadaredux-framework' ); ?>
				</button>
			</p>
		</div>
	</div>
	<br/>
	<table class="avadaredux_status_table widefat" cellspacing="0" id="status">
		<thead>
		<tr>
			<th colspan="3" data-export-label="WordPress Environment">
				<?php esc_html_e( 'WordPress Environment', 'avadaredux-framework' ); ?>
			</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td data-export-label="Home URL">
				<?php esc_html_e( 'Home URL', 'avadaredux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The URL of your site\'s homepage.', 'avadaredux-framework' ) . '">[?]</a>'; ?>
			</td>
			<td><?php echo esc_url($sysinfo['home_url']); ?></td>
		</tr>
		<tr>
			<td data-export-label="Site URL">
				<?php esc_html_e( 'Site URL', 'avadaredux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The root URL of your site.', 'avadaredux-framework' ) . '">[?]</a>'; ?>
			</td>
			<td>
				<?php echo esc_url($sysinfo['site_url']); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="AvadaRedux Version">
				<?php esc_html_e( 'AvadaRedux Version', 'avadaredux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The version of AvadaRedux Framework installed on your site.', 'avadaredux-framework' ) . '">[?]</a>'; ?>
			</td>
			<td>
				<?php echo esc_html($sysinfo['avadaredux_ver']); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="AvadaRedux Data Directory Writable">
				<?php esc_html_e( 'AvadaRedux Data Directory Writable', 'avadaredux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'AvadaRedux and its extensions write data to the <code>uploads</code> directory. This directory must be writable.', 'avadaredux-framework' ) . '">[?]</a>'; ?>
			</td>
			<td><?php
					if ( $sysinfo['avadaredux_data_writeable'] == 'true' ) {
						echo '<mark class="yes">' . '&#10004; <code>' . esc_html($sysinfo['avadaredux_data_dir']) . '</code></mark> ';
					} else {
						printf( '<mark class="error">' . '&#10005; ' . __( 'To allow data saving, make <code>%s</code> writable.', 'avadaredux-framework' ) . '</mark>', esc_html($sysinfo['avadaredux_data_dir']) );
					}
				?></td>
		</tr>
		<tr>
			<td data-export-label="WP Content URL">
				<?php esc_html_e( 'WP Content URL', 'avadaredux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The location of Wordpress\'s content URL.', 'avadaredux-framework' ) . '">[?]</a>'; ?>
			</td>
			<td>
				<?php echo '<code>' . esc_url($sysinfo['wp_content_url']) . '</code> '; ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="WP Version">
				<?php esc_html_e( 'WP Version', 'avadaredux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The version of WordPress installed on your site.', 'avadaredux-framework' ) . '">[?]</a>'; ?>
			</td>
			<td>
				<?php bloginfo( 'version' ); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="WP Multisite">
				<?php esc_html_e( 'WP Multisite', 'avadaredux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'Whether or not you have WordPress Multisite enabled.', 'avadaredux-framework' ) . '">[?]</a>'; ?>
			</td>
			<td><?php if ( $sysinfo['wp_multisite'] == true ) {
					echo '&#10004;';
				} else {
					echo '&ndash;';
				} ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Permalink Structure">
				<?php esc_html_e( 'Permalink Structure', 'avadaredux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The current permalink structure as defined in Wordpress Settings->Permalinks.', 'avadaredux-framework' ) . '">[?]</a>'; ?>
			</td>
			<td>
				<?php echo esc_html($sysinfo['permalink_structure']); ?>
			</td>
		</tr>
		<?php $sof = $sysinfo['front_page_display']; ?>
		<tr>
			<td data-export-label="Front Page Display">
				<?php esc_html_e( 'Front Page Display', 'avadaredux-framework' ); ?>:
			</td>
			<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The current Reading mode of Wordpress.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
			<td><?php echo esc_html($sof); ?></td>
		</tr>

		<?php
			if ( $sof == 'page' ) {
?>
				<tr>
					<td data-export-label="Front Page">
						<?php esc_html_e( 'Front Page', 'avadaredux-framework' ); ?>:
					</td>
					<td class="help">
						<?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The currently selected page which acts as the site\'s Front Page.', 'avadaredux-framework' ) . '">[?]</a>'; ?>
					</td>
					<td>
						<?php echo esc_html($sysinfo['front_page']); ?>
					</td>
				</tr>
				<tr>
					<td data-export-label="Posts Page">
						<?php esc_html_e( 'Posts Page', 'avadaredux-framework' ); ?>:
					</td>
					<td class="help">
						<?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The currently selected page in where blog posts are displayed.', 'avadaredux-framework' ) . '">[?]</a>'; ?>
					</td>
					<td>
						<?php echo esc_html($sysinfo['posts_page']); ?>
					</td>
				</tr>
<?php
			}
?>
		<tr>
			<td data-export-label="WP Memory Limit">
				<?php esc_html_e( 'WP Memory Limit', 'avadaredux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The maximum amount of memory (RAM) that your site can use at one time.', 'avadaredux-framework' ) . '">[?]</a>'; ?>
			</td>
			<td>
<?php
					$memory = $sysinfo['wp_mem_limit']['raw'];

					if ( $memory < 40000000 ) {
						echo '<mark class="error">' . sprintf( __( '%s - We recommend setting memory to at least 40MB. See: <a href="%s" target="_blank">Increasing memory allocated to PHP</a>', 'avadaredux-framework' ), esc_html($sysinfo['wp_mem_limit']['size']), 'http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP' ) . '</mark>';
					} else {
						echo '<mark class="yes">' . esc_html($sysinfo['wp_mem_limit']['size']) . '</mark>';
					}
?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Database Table Prefix">
				<?php esc_html_e( 'Database Table Prefix', 'avadaredux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The prefix structure of the current Wordpress database.', 'avadaredux-framework' ) . '">[?]</a>'; ?>
			</td>
			<td>
				<?php echo esc_html($sysinfo['db_table_prefix']); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="WP Debug Mode">
				<?php esc_html_e( 'WP Debug Mode', 'avadaredux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'Displays whether or not WordPress is in Debug Mode.', 'avadaredux-framework' ) . '">[?]</a>'; ?>
			</td>
			<td>
				<?php if ( $sysinfo['wp_debug'] === 'true' ) {
					echo '<mark class="yes">' . '&#10004;' . '</mark>';
				} else {
					echo '<mark class="no">' . '&ndash;' . '</mark>';
				} ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Language">
				<?php esc_html_e( 'Language', 'avadaredux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The current language used by WordPress. Default = English', 'avadaredux-framework' ) . '">[?]</a>'; ?>
			</td>
			<td>
				<?php echo esc_html($sysinfo['wp_lang']); ?>
			</td>
		</tr>
		</tbody>
	</table>
	<table class="avadaredux_status_table widefat" cellspacing="0" id="status">
		<thead>
		<tr>
			<th colspan="3" data-export-label="Browser">
				<?php esc_html_e( 'Browser', 'avadaredux-framework' ); ?>
			</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td data-export-label="Browser Info">
				<?php esc_html_e( 'Browser Info', 'avadaredux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'Information about web browser current in use.', 'avadaredux-framework' ) . '">[?]</a>'; ?>
			</td>
			<td>
<?php
				foreach ( $sysinfo['browser'] as $key => $value ) {
					echo '<strong>' . esc_html(ucfirst( $key )) . '</strong>: ' . esc_html($value) . '<br/>';
				}
?>
			</td>
		</tr>
		</tbody>
	</table>

	<table class="avadaredux_status_table widefat" cellspacing="0" id="status">
		<thead>
		<tr>
			<th colspan="3" data-export-label="Server Environment">
				<?php esc_html_e( 'Server Environment', 'avadaredux-framework' ); ?>
			</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td data-export-label="Server Info">
				<?php esc_html_e( 'Server Info', 'avadaredux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'Information about the web server that is currently hosting your site.', 'avadaredux-framework' ) . '">[?]</a>'; ?>
			</td>
			<td>
				<?php echo esc_html($sysinfo['server_info']); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Localhost Environment">
				<?php esc_html_e( 'Localhost Environment', 'avadaredux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'Is the server running in a localhost environment.', 'avadaredux-framework' ) . '">[?]</a>'; ?>
			</td>
			<td>
<?php
				if ( $sysinfo['localhost'] === 'true' ) {
					echo '<mark class="yes">' . '&#10004;' . '</mark>';
				} else {
					echo '<mark class="no">' . '&ndash;' . '</mark>';
				}
?>
			</td>
		</tr>
		<tr>
			<td data-export-label="PHP Version">
				<?php esc_html_e( 'PHP Version', 'avadaredux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The version of PHP installed on your hosting server.', 'avadaredux-framework' ) . '">[?]</a>'; ?>
			</td>
			<td>
				<?php echo esc_html($sysinfo['php_ver']); ?>
			</td>
		</tr>
		<tr>
			<td data-export-label="ABSPATH">
				<?php esc_html_e( 'ABSPATH', 'avadaredux-framework' ); ?>:
			</td>
			<td class="help">
				<?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The ABSPATH variable on the server.', 'avadaredux-framework' ) . '">[?]</a>'; ?>
			</td>
			<td>
				<?php echo '<code>' . esc_html($sysinfo['abspath']) . '</code>'; ?>
			</td>
		</tr>

		<?php if ( function_exists( 'ini_get' ) ) { ?>
			<tr>
				<td data-export-label="PHP Memory Limit"><?php esc_html_e( 'PHP Memory Limit', 'avadaredux-framework' ); ?>:</td>
				<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The largest filesize that can be contained in one post.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
				<td><?php echo esc_html($sysinfo['php_mem_limit']); ?></td>
			</tr>
			<tr>
				<td data-export-label="PHP Post Max Size"><?php esc_html_e( 'PHP Post Max Size', 'avadaredux-framework' ); ?>:</td>
				<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The largest filesize that can be contained in one post.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
				<td><?php echo esc_html($sysinfo['php_post_max_size']); ?></td>
			</tr>
			<tr>
				<td data-export-label="PHP Time Limit"><?php esc_html_e( 'PHP Time Limit', 'avadaredux-framework' ); ?>:</td>
				<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The amount of time (in seconds) that your site will spend on a single operation before timing out (to avoid server lockups)', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
				<td><?php echo esc_html($sysinfo['php_time_limit']); ?></td>
			</tr>
			<tr>
				<td data-export-label="PHP Max Input Vars"><?php esc_html_e( 'PHP Max Input Vars', 'avadaredux-framework' ); ?>:</td>
				<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The maximum number of variables your server can use for a single function to avoid overloads.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
				<td><?php echo esc_html($sysinfo['php_max_input_var']); ?></td>
			</tr>
			<tr>
				<td data-export-label="PHP Display Errors"><?php esc_html_e( 'PHP Display Errors', 'avadaredux-framework' ); ?>:</td>
				<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'Determines if PHP will display errors within the browser.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
				<td><?php
						if ( 'true' === $sysinfo['php_display_errors'] ) {
							echo '<mark class="yes">' . '&#10004;' . '</mark>';
						} else {
							echo '<mark class="no">' . '&ndash;' . '</mark>';
						}
					?></td>
			</tr>
		<?php } ?>
		<tr>
			<td data-export-label="SUHOSIN Installed"><?php esc_html_e( 'SUHOSIN Installed', 'avadaredux-framework' ); ?>:</td>
			<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'Suhosin is an advanced protection system for PHP installations. It was designed to protect your servers on the one hand against a number of well known problems in PHP applications and on the other hand against potential unknown vulnerabilities within these applications or the PHP core itself.  If enabled on your server, Suhosin may need to be configured to increase its data submission limits.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
			<td>
				<?php if ( $sysinfo['suhosin_installed'] == true ) {
					echo '<mark class="yes">' . '&#10004;' . '</mark>';
				} else {
					echo '<mark class="no">' . '&ndash;' . '</mark>';
				} ?>
			</td>
		</tr>

		<tr>
			<td data-export-label="MySQL Version"><?php esc_html_e( 'MySQL Version', 'avadaredux-framework' ); ?>:</td>
			<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The version of MySQL installed on your hosting server.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
			<td><?php echo esc_html($sysinfo['mysql_ver']); ?></td>
		</tr>
		<tr>
			<td data-export-label="Max Upload Size"><?php esc_html_e( 'Max Upload Size', 'avadaredux-framework' ); ?>:</td>
			<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The largest filesize that can be uploaded to your WordPress installation.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
			<td><?php echo esc_html($sysinfo['max_upload_size']); ?></td>
		</tr>
		<tr>
			<td data-export-label="Default Timezone is UTC">
				<?php esc_html_e( 'Default Timezone is UTC', 'avadaredux-framework' ); ?>:
			</td>
			<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The default timezone for your server.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
			<td>
<?php
				if ( $sysinfo['def_tz_is_utc'] === 'false' ) {
					echo '<mark class="error">' . '&#10005; ' . sprintf( __( 'Default timezone is %s - it should be UTC', 'avadaredux-framework' ), esc_html(date_default_timezone_get()) ) . '</mark>';
				} else {
					echo '<mark class="yes">' . '&#10004;' . '</mark>';
				}
?>
			</td>
		</tr>
		<?php
			$posting = array();

			// fsockopen/cURL
			$posting['fsockopen_curl']['name'] = 'fsockopen/cURL';
			$posting['fsockopen_curl']['help'] = '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'Used when communicating with remote services with PHP.', 'avadaredux-framework' ) . '">[?]</a>';

			if ( $sysinfo['fsockopen_curl'] === 'true' ) {
				$posting['fsockopen_curl']['success'] = true;
			} else {
				$posting['fsockopen_curl']['success'] = false;
				$posting['fsockopen_curl']['note']    = esc_html__( 'Your server does not have fsockopen or cURL enabled - cURL is used to communicate with other servers. Please contact your hosting provider.', 'avadaredux-framework' ) . '</mark>';
			}

			/*
			// SOAP
			$posting['soap_client']['name'] = 'SoapClient';
			$posting['soap_client']['help'] = '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'Some webservices like shipping use SOAP to get information from remote servers, for example, live shipping quotes from FedEx require SOAP to be installed.', 'avadaredux-framework' ) . '">[?]</a>';

			if ( $sysinfo['soap_client'] == true ) {
				$posting['soap_client']['success'] = true;
			} else {
				$posting['soap_client']['success'] = false;
				$posting['soap_client']['note']    = sprintf( __( 'Your server does not have the <a href="%s">SOAP Client</a> class enabled - some gateway plugins which use SOAP may not work as expected.', 'avadaredux-framework' ), 'http://php.net/manual/en/class.soapclient.php' ) . '</mark>';
			}

			// DOMDocument
			$posting['dom_document']['name'] = 'DOMDocument';
			$posting['dom_document']['help'] = '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'HTML/Multipart emails use DOMDocument to generate inline CSS in templates.', 'avadaredux-framework' ) . '">[?]</a>';

			if ( $sysinfo['dom_document'] == true ) {
				$posting['dom_document']['success'] = true;
			} else {
				$posting['dom_document']['success'] = false;
				$posting['dom_document']['note']    = sprintf( __( 'Your server does not have the <a href="%s">DOMDocument</a> class enabled - HTML/Multipart emails, and also some extensions, will not work without DOMDocument.', 'avadaredux-framework' ), 'http://php.net/manual/en/class.domdocument.php' ) . '</mark>';
			}
			*/

			//// GZIP
			//$posting['gzip']['name'] = 'GZip';
			//$posting['gzip']['help'] = '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'GZip (gzopen) is used to open the GEOIP database from MaxMind.', 'avadaredux-framework' ) . '">[?]</a>';
			//
			//if ( $sysinfo['gzip'] == true ) {
			//    $posting['gzip']['success'] = true;
			//} else {
			//    $posting['gzip']['success'] = false;
			//    $posting['gzip']['note']    = sprintf( __( 'Your server does not support the <a href="%s">gzopen</a> function - this is required to use the GeoIP database from MaxMind. The API fallback will be used instead for geolocation.', 'avadaredux-framework' ), 'http://php.net/manual/en/zlib.installation.php' ) . '</mark>';
			//}

			// WP Remote Post Check
			$posting['wp_remote_post']['name'] = esc_html__( 'Remote Post', 'avadaredux-framework' );
			$posting['wp_remote_post']['help'] = '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'Used to send data to remote servers.', 'avadaredux-framework' ) . '">[?]</a>';

			if ( $sysinfo['wp_remote_post'] === 'true' ) {
				$posting['wp_remote_post']['success'] = true;
			} else {
				$posting['wp_remote_post']['note'] = esc_html__( 'wp_remote_post() failed. Many advanced features may not function. Contact your hosting provider.', 'avadaredux-framework' );

				if ( $sysinfo['wp_remote_post_error'] ) {
					$posting['wp_remote_post']['note'] .= ' ' . sprintf( __( 'Error: %s', 'avadaredux-framework' ), avadaredux_clean( $sysinfo['wp_remote_post_error'] ) );
				}

				$posting['wp_remote_post']['success'] = false;
			}

			// WP Remote Get Check
			$posting['wp_remote_get']['name'] = esc_html__( 'Remote Get', 'avadaredux-framework' );
			$posting['wp_remote_get']['help'] = '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'Used to grab information from remote servers for updates updates.', 'avadaredux-framework' ) . '">[?]</a>';

			if ( $sysinfo['wp_remote_get'] === 'true' ) {
				$posting['wp_remote_get']['success'] = true;
			} else {
				$posting['wp_remote_get']['note'] = esc_html__( 'wp_remote_get() failed. This is needed to get information from remote servers. Contact your hosting provider.', 'avadaredux-framework' );
				if ( $sysinfo['wp_remote_get_error'] ) {
					$posting['wp_remote_get']['note'] .= ' ' . sprintf( __( 'Error: %s', 'avadaredux-framework' ), avadaredux_clean( $sysinfo['wp_remote_get_error'] ) );
				}

				$posting['wp_remote_get']['success'] = false;
			}

			$posting = apply_filters( 'avadaredux_debug_posting', $posting );

			foreach ( $posting as $post ) {
				$mark = ! empty( $post['success'] ) ? 'yes' : 'error';
				?>
				<tr>
					<td data-export-label="<?php echo esc_html( $post['name'] ); ?>">
						<?php echo esc_html( $post['name'] ); ?>:
					</td>
					<td>
						<?php echo isset( $post['help'] ) ? $post['help'] : ''; ?>
					</td>
					<td class="help">
						<mark class="<?php echo esc_attr($mark); ?>">
							<?php echo ! empty( $post['success'] ) ? '&#10004' : '&#10005'; ?>
							<?php echo ! empty( $post['note'] ) ? wp_kses_data( $post['note'] ) : ''; ?>
						</mark>
					</td>
				</tr>
			<?php
			}
		?>
		</tbody>
	</table>
	<table class="avadaredux_status_table widefat" cellspacing="0" id="status">
		<thead>
		<tr>
			<th colspan="3" data-export-label="Active Plugins (<?php echo esc_html(count( (array) get_option( 'active_plugins' ) ) ); ?>)">
				<?php esc_html_e( 'Active Plugins', 'avadaredux-framework' ); ?> (<?php echo esc_html(count( (array) get_option( 'active_plugins' ) ) ); ?>)
			</th>
		</tr>
		</thead>
		<tbody>
		<?php
			foreach ( $sysinfo['plugins'] as $name => $plugin_data ) {
				$version_string = '';
				$network_string = '';

				if ( ! empty( $plugin_data['Name'] ) ) {
					// link the plugin name to the plugin url if available
					$plugin_name = esc_html( $plugin_data['Name'] );

					if ( ! empty( $plugin_data['PluginURI'] ) ) {
						$plugin_name = '<a href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="' . esc_attr__( 'Visit plugin homepage', 'avadaredux-framework' ) . '">' . esc_html($plugin_name) . '</a>';
					}
?>
					<tr>
						<td><?php echo $plugin_name; ?></td>
						<td class="help">&nbsp;</td>
						<td>
							<?php echo sprintf( _x( 'by %s', 'by author', 'avadaredux-framework' ), $plugin_data['Author'] ) . ' &ndash; ' . esc_html( $plugin_data['Version'] ) . $version_string . $network_string; ?>
						</td>
					</tr>
<?php
				}
			}
		?>
		</tbody>
	</table>
	<?php
		if ( ! empty( $sysinfo['avadaredux_instances'] ) && is_array( $sysinfo['avadaredux_instances'] ) ) {
			foreach ( $sysinfo['avadaredux_instances'] as $inst => $data ) {
				$inst_name = ucwords( str_replace( array( '_', '-' ), ' ', $inst ) );
				$args      = $data['args'];
				?>
				<table class="avadaredux_status_table widefat" cellspacing="0" id="status">
					<thead>
					<tr>
						<th colspan="3" data-export-label="AvadaRedux Instance: <?php echo esc_html($inst_name); ?>">
							<?php esc_html_e( 'AvadaRedux Instance: ', 'avadaredux-framework' );
							echo esc_html($inst_name); ?></th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td data-export-label="opt_name">opt_name:</td>
						<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The opt_name argument for this instance of AvadaRedux.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
						<td><?php echo esc_html($args['opt_name']); ?></td>
					</tr>
					<?php
						if ( isset( $args['global_variable'] ) && $args['global_variable'] != '' ) {
							?>
							<tr>
								<td data-export-label="global_variable">global_variable:</td>
								<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The global_variable argument for this instance of AvadaRedux.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
								<td><?php echo esc_html($args['global_variable']); ?></td>
							</tr>
						<?php
						}
					?>
					<tr>
						<td data-export-label="dev_mode">dev_mode:</td>
						<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'Indicates if developer mode is enabled for this instance of AvadaRedux.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
						<td><?php echo true == $args['dev_mode'] ? '<mark class="yes">' . '&#10004;' . '</mark>' : '<mark class="no">' . '&ndash;' . '</mark>'; ?></td>
					</tr>
					<tr>
						<td data-export-label="ajax_save">ajax_save:</td>
						<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'Indicates if ajax based saving is enabled for this instance of AvadaRedux.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
						<td><?php echo true == $args['ajax_save'] ? '<mark class="yes">' . '&#10004;' . '</mark>' : '<mark class="no">' . '&ndash;' . '</mark>'; ?></td>
					</tr>
					<tr>
						<td data-export-label="page_slug">page_slug:</td>
						<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The page slug denotes the string used for the options panel page for this instance of AvadaRedux.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
						<td><?php echo esc_html($args['page_slug']); ?></td>
					</tr>
					<tr>
						<td data-export-label="page_permissions">page_permissions:</td>
						<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The page permissions variable sets the permission level required to access the options panel for this instance of AvadaRedux.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
						<td><?php echo esc_html($args['page_permissions']); ?></td>
					</tr>
					<tr>
						<td data-export-label="menu_type">menu_type:</td>
						<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'This variable set whether or not the menu is displayed as an admin menu item for this instance of AvadaRedux.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
						<td><?php echo esc_html($args['menu_type']); ?></td>
					</tr>
					<tr>
						<td data-export-label="page_parent">page_parent:</td>
						<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The page parent variable sets where the options menu will be placed on the WordPress admin sidebar for this instance of AvadaRedux.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
						<td><?php echo esc_html($args['page_parent']); ?></td>
					</tr>

					<tr>
						<td data-export-label="compiler">compiler:</td>
						<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'Indicates if the compiler flag is enabled for this instance of AvadaRedux.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
						<td><?php echo true == $args['compiler'] ? '<mark class="yes">' . '&#10004;' . '</mark>' : '<mark class="no">' . '&ndash;' . '</mark>'; ?></td>
					</tr>
					<tr>
						<td data-export-label="output">output:</td>
						<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'Indicates if output flag for globally shutting off all CSS output is enabled for this instance of AvadaRedux.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
						<td><?php echo true == $args['output'] ? '<mark class="yes">' . '&#10004;' . '</mark>' : '<mark class="no">' . '&ndash;' . '</mark>'; ?></td>
					</tr>
					<tr>
						<td data-export-label="output_tag">output_tag:</td>
						<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The output_tag variable sets whether or not dynamic CSS will be generated for the customizer and Google fonts for this instance of AvadaRedux.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
						<td><?php echo true == $args['output_tag'] ? '<mark class="yes">' . '&#10004;' . '</mark>' : '<mark class="no">' . '&ndash;' . '</mark>'; ?></td>
					</tr>

					<?php
						if ( isset( $args['templates_path'] ) && $args['templates_path'] != '' ) {
							?>
							<tr>
								<td data-export-label="template_path">template_path:</td>
								<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The specified template path containing custom template files for this instance of AvadaRedux.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
								<td><?php echo '<code>' . esc_html($args['templates_path']) . '</code>'; ?></td>
							</tr>
							<tr>
								<td data-export-label="Templates">Templates:</td>
								<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'List of template files overriding the default AvadaRedux template files.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
<?php
									$found_files = $data['templates'];
									if ( $found_files ) {
										foreach ( $found_files as $plugin_name => $found_plugin_files ) {
?>
											<td>
												<?php echo implode( ', <br/>', $found_plugin_files ); ?>
											</td>
										<?php
										}
									} else {
?>
										<td>&ndash;</td>
<?php
									}
?>
							</tr>
<?php
						}

						$ext = $data['extensions'];
						if ( ! empty( $ext ) && is_array( $ext ) ) {
?>
							<tr>
								<td data-export-label="Extensions">Extensions</td>
								<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'Indicates the installed AvadaRedux extensions and their version numbers.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
								<td>
<?php
									foreach ( $ext as $name => $arr ) {
										$ver = $arr['version'];

										echo '<a href="http://avadareduxframework.com/extensions/' . str_replace( array(
												'_',
											), '-', $name ) . '" target="blank">' . ucwords( str_replace( array(
												'_',
												'-'
											), ' ', $name ) ) . '</a> - ' . esc_html($ver); ?><br/>
<?php
									}
?>
								</td>
							</tr>
<?php
						}
?>
					</tbody>
				</table>
<?php
			}
		}
?>
	<table class="avadaredux_status_table widefat" cellspacing="0" id="status">
		<thead>
		<tr>
			<th colspan="3" data-export-label="Theme"><?php esc_html_e( 'Theme', 'avadaredux-framework' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td data-export-label="Name"><?php esc_html_e( 'Name', 'avadaredux-framework' ); ?>:</td>
			<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The name of the current active theme.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
			<td><?php echo esc_html($sysinfo['theme']['name']); ?></td>
		</tr>
		<tr>
			<td data-export-label="Version"><?php esc_html_e( 'Version', 'avadaredux-framework' ); ?>:</td>
			<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The installed version of the current active theme.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
			<td>
<?php
				echo esc_html($sysinfo['theme']['version']);

				if ( ! empty( $theme_version_data['version'] ) && version_compare( $theme_version_data['version'], $active_theme->Version, '!=' ) ) {
					echo ' &ndash; <strong style="color:red;">' . esc_html($theme_version_data['version']) . ' ' . esc_html__( 'is available', 'avadaredux-framework' ) . '</strong>';
				}
?>
			</td>
		</tr>
		<tr>
			<td data-export-label="Author URL"><?php esc_html_e( 'Author URL', 'avadaredux-framework' ); ?>:</td>
			<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The theme developers URL.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
			<td><?php echo esc_url($sysinfo['theme']['author_uri']); ?></td>
		</tr>
		<tr>
			<td data-export-label="Child Theme"><?php esc_html_e( 'Child Theme', 'avadaredux-framework' ); ?>:</td>
			<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'Displays whether or not the current theme is a child theme.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
			<td>
<?php
				echo is_child_theme() ? '<mark class="yes">' . '&#10004;' . '</mark>' : '&#10005; <br /><em>' . sprintf( __( 'If you\'re modifying AvadaRedux Framework or a parent theme you didn\'t build personally, we recommend using a child theme. See: <a href="%s" target="_blank">How to create a child theme</a>', 'avadaredux-framework' ), 'http://codex.wordpress.org/Child_Themes' ) . '</em>';
?>
			</td>
		</tr>
<?php
			if ( is_child_theme() ) {
?>
				<tr>
					<td data-export-label="Parent Theme Name"><?php esc_html_e( 'Parent Theme Name', 'avadaredux-framework' ); ?>:
					</td>
					<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The name of the parent theme.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
					<td><?php echo esc_html($sysinfo['theme']['parent_name']); ?></td>
				</tr>
				<tr>
					<td data-export-label="Parent Theme Version">
						<?php esc_html_e( 'Parent Theme Version', 'avadaredux-framework' ); ?>:
					</td>
					<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The installed version of the parent theme.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
					<td><?php echo esc_html($sysinfo['theme']['parent_version']); ?></td>
				</tr>
				<tr>
					<td data-export-label="Parent Theme Author URL">
						<?php esc_html_e( 'Parent Theme Author URL', 'avadaredux-framework' ); ?>:
					</td>
					<td class="help"><?php echo '<a href="#" class="avadaredux-hint-qtip" qtip-content="' . esc_attr__( 'The parent theme developers URL.', 'avadaredux-framework' ) . '">[?]</a>'; ?></td>
					<td><?php echo esc_url($sysinfo['theme']['parent_author_uri']); ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<script type="text/javascript">
		jQuery( 'a.avadaredux-hint-qtip' ).click(
			function() {
				return false;
			}
		);

		jQuery( 'a.debug-report' ).click(
			function() {
				var report = '';

				jQuery( '#status thead, #status tbody' ).each(
					function() {
						if ( jQuery( this ).is( 'thead' ) ) {
							var label = jQuery( this ).find( 'th:eq(0)' ).data( 'export-label' ) || jQuery( this ).text();
							report = report + "\n### " + jQuery.trim( label ) + " ###\n\n";
						} else {
							jQuery( 'tr', jQuery( this ) ).each(
								function() {
									var label = jQuery( this ).find( 'td:eq(0)' ).data( 'export-label' ) || jQuery( this ).find( 'td:eq(0)' ).text();
									var the_name = jQuery.trim( label ).replace( /(<([^>]+)>)/ig, '' ); // Remove HTML
									var the_value = jQuery.trim( jQuery( this ).find( 'td:eq(2)' ).text() );
									var value_array = the_value.split( ', ' );

									if ( value_array.length > 1 ) {
										// If value have a list of plugins ','
										// Split to add new line
										var output = '';
										var temp_line = '';
										jQuery.each(
											value_array, function( key, line ) {
												temp_line = temp_line + line + '\n';
											}
										);

										the_value = temp_line;
									}

									report = report + '' + the_name + ': ' + the_value + "\n";
								}
							);
						}
					}
				);

				try {
					jQuery( "#debug-report" ).slideDown();
					jQuery( "#debug-report textarea" ).val( report ).focus().select();
					jQuery( this ).fadeOut();

					return false;
				} catch ( e ) {
					console.log( e );
				}

				return false;
			}
		);

		jQuery( document ).ready(
			function( $ ) {
				$( 'body' ).on(
					'copy', '#copy-for-support', function( e ) {
						e.clipboardData.clearData();
						e.clipboardData.setData( 'text/plain', $( '#debug-report textarea' ).val() );
						e.preventDefault();
					}
				);
			}
		);
	</script>
</div>
