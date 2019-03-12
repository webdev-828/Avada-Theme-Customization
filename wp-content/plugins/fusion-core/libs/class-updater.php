<?php
if( ! class_exists( 'FusionCoreUpdater' ) ) {
	class FusionCoreUpdater {
		var $api_url;
		var $id;
		var $slug;
	
		function __construct( $api_url, $id, $slug ) {
			$this->api_url = $api_url;
			$this->id = $id;
			$this->slug = $slug;

			add_filter( 'pre_set_site_transient_update_plugins', array( &$this, 'check_for_update' ) );			
			// This is for testing only!
			//set_site_transient('update_plugins', null);
			
			// Add notice that an update is available
			/*if( get_option( 'fusion_'. $this->slug .'_update_available' ) ){
				add_action( 'admin_notices', array(&$this, 'update_notice') );
			}*/
		}
		
		function check_for_update( $transient ) {
			global $wp_filesystem, $smof_data;

			if( empty( $transient->checked ) )  {
				return $transient;
			}

			$request_args = array(
				'id' => $this->id,
				'slug' => $this->slug,
				'version' => $transient->checked[$this->id]
			);

			$request_args['item_code'] = '2833226';
			$request_args['envato_username'] = $smof_data['tf_username'];
			$request_args['api_key'] = $smof_data['tf_api'];

			$filename = trailingslashit( get_template_directory() ) . 'log.txt';

			$request_string = $this->prepare_request( 'plugin_update', $request_args );
			$raw_response = wp_remote_post( $this->api_url, $request_string );
			
			$response = null;
			if( ! is_wp_error( $raw_response ) && ( $raw_response['response']['code'] == 200 ) ) {
				$response = json_decode( $raw_response['body'], true );
			}
			
			if( ! empty( $response ) ) { // Feed the update data into WP updater
				$transient->response[$this->id] = (object) $response;
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
	}
}