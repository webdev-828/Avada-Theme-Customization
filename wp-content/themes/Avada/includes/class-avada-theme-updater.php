<?php

class Avada_Theme_Updater {

	var $api_url;
	var $theme_id;
	var $theme_slug;

	function __construct( $api_url, $theme_id, $theme_slug ) {
		$this->api_url = $api_url;
		$this->theme_id = $theme_id;
		$this->theme_slug = $theme_slug;

		add_filter( 'pre_set_site_transient_update_themes', array( &$this, 'check_for_update' ) );

		// This is for testing only!
		//set_site_transient('update_themes', null);

		// Add notice that theme update is available
		if ( get_option( 'fusion_'. $this->theme_slug .'_update_available' ) ){
			add_action( 'admin_notices', array(&$this, 'update_notice') );
		}
	}

	function check_for_update( $transient ) {
		global $wp_filesystem;
		$avada_options = get_option( 'Avada_Key' );

		if ( empty( $transient->checked ) )  {
			return $transient;
		}

		$request_args = array(
			'id' => $this->theme_id,
			'slug' => $this->theme_slug,
			'version' => $transient->checked[$this->theme_slug]
		);

		if ( $this->api_url == 'http://updates.theme-fusion.com/avada-theme.php' ) {
			$request_args['item_code'] = '2833226';
			$request_args['envato_username'] = $avada_options['tf_username'];
			$request_args['api_key'] = $avada_options['tf_api'];
		}

		$filename = trailingslashit( get_template_directory() ) . 'log.txt';
		$request_string = $this->prepare_request( 'theme_update', $request_args );
		$raw_response = wp_remote_post( $this->api_url, $request_string );

		$response = null;
		if ( ! is_wp_error( $raw_response ) && ( $raw_response['response']['code'] == 200 ) ) {
			$response = json_decode( $raw_response['body'], true );
		}

		if ( ! empty( $response ) ) { // Feed the update data into WP updater
			$transient->response[$this->theme_slug] = $response;
		}

		/*$handle = fopen($filename, 'a');
		fwrite($handle, json_encode($request_string));
		fwrite($handle, json_encode($raw_response));*/

		return $transient;
	}

	function prepare_request( $action, $args ) {
		global $wp_version;

		return array(
			'body' => array(
				'action' => $action,
				'request' => json_encode($args),
				'api-key' => md5(home_url())
			),
			'user-agent' => 'WordPress/'. $wp_version .'; '. home_url()
		);
	}

	function update_notice() {
		global $pagenow;

		$theme_settings_link = admin_url( 'themes.php' );

		if ( $pagenow == 'themes.php' || $pagenow == 'update-core.php' ) {
			$theme_name = 'Avada';
			if ( function_exists('wp_get_theme') ) {
				$theme_name = '<strong>'. wp_get_theme() .'</strong>';
			}
			echo '<div class="updated">
			<p>' . sprintf( __( 'There is an update available for the %s theme.', 'Avada' ), $theme_name ) . '</p>
			</div>';
		}
	}
}

// Omit closing PHP tag to avoid "Headers already sent" issues.
