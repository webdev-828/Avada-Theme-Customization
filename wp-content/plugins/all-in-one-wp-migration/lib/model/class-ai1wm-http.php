<?php
/**
 * Copyright (C) 2014-2016 ServMask Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * ███████╗███████╗██████╗ ██╗   ██╗███╗   ███╗ █████╗ ███████╗██╗  ██╗
 * ██╔════╝██╔════╝██╔══██╗██║   ██║████╗ ████║██╔══██╗██╔════╝██║ ██╔╝
 * ███████╗█████╗  ██████╔╝██║   ██║██╔████╔██║███████║███████╗█████╔╝
 * ╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║╚██╔╝██║██╔══██║╚════██║██╔═██╗
 * ███████║███████╗██║  ██║ ╚████╔╝ ██║ ╚═╝ ██║██║  ██║███████║██║  ██╗
 * ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
 */

class Ai1wm_Http {

	public static $transports = array( 'ai1wm', 'curl' );

	public static function post( $url, $params = array() ) {

		// Check the status, maybe we need to stop it
		if ( ! is_file( ai1wm_export_path( $params ) ) && ! is_file( ai1wm_import_path( $params ) ) ) {
			exit;
		}

		// Get IP address
		$ip = get_option( AI1WM_URL_IP );

		// HTTP request
		Ai1wm_Http::request( $url, $ip, $params );
	}

	public static function resolve( $url ) {

		// Reset IP address and transport layer
		delete_option( AI1WM_URL_IP );
		delete_option( AI1WM_URL_TRANSPORT );

		// Set secret
		$secret_key = get_option( AI1WM_SECRET_KEY );

		// Set scheme
		$scheme = parse_url( $url, PHP_URL_SCHEME );

		// Set host name
		$host = parse_url( $url, PHP_URL_HOST );

		// Set server IP address
		if ( ! empty( $_SERVER['SERVER_ADDR'] ) ) {
			$ip = $_SERVER['SERVER_ADDR'];
		} else if ( ! empty( $_SERVER['LOCAL_ADDR'] ) ) {
			$ip = $_SERVER['LOCAL_ADDR'];
		} else {
			$ip = '127.0.0.1';
		}

		// Set domain IP address
		$domain = gethostbyname( $host );

		// HTTP resolve
		foreach ( array( 'ai1wm', 'curl' ) as $transport ) {
			foreach ( array( $ip, $domain, $host ) as $ip ) {

			    // Set transport
			    Ai1wm_Http::$transports = array( $transport );

				// HTTP request
				Ai1wm_Http::request( $url, $ip, array(
					'secret_key' => $secret_key,
					'url_ip' => $ip,
					'url_transport' => $transport
				) );

				// HTTP response
				for ( $i = 0; $i < 5; $i++, sleep( 1 ) ) {

					// Initialize empty WP cache
					wp_cache_init();

					// Is valid transport layer?
					if ( get_option( AI1WM_URL_IP ) && get_option( AI1WM_URL_TRANSPORT ) ) {
						return;
					}
				}
			}
		}

		// No connection
		throw new Ai1wm_Http_Exception( __(
			'There was a problem while reaching your server.<br />' .
			'Contact <a href="mailto:support@servmask.com">support@servmask.com</a> for assistance.',
			AI1WM_PLUGIN_NAME
		) );
	}

	public static function request( $url, $ip, $params = array() ) {

		// Set request order
		add_filter( 'http_api_transports', 'Ai1wm_Http::transports', 100 );

		// Set host name
		$host = parse_url( $url, PHP_URL_HOST );

		// Set accept header
		$headers = array( 'Accept' => '*/*' );

		// Add authorization header
		if ( ( $user = get_option( AI1WM_AUTH_USER ) ) && ( $password = get_option( AI1WM_AUTH_PASSWORD ) ) ) {
			$headers['Authorization'] = sprintf( 'Basic %s', base64_encode( "{$user}:{$password}" ) );
		}

		// Add host header
		if ( ( $port = parse_url( $url, PHP_URL_PORT ) ) ) {
			$headers['Host'] = sprintf( '%s:%s', $host, $port );
		} else {
			$headers['Host'] = sprintf( '%s', $host );
		}

		// Add IPv6 support
		if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6 ) ) {
			$ip = "[$ip]";
		}

		// Replace IP address
		if ( ! empty( $ip ) ) {
			$url = str_replace( "//{$host}", "//{$ip}", $url );
		}

		// HTTP request
		remove_all_filters( 'http_request_args' );
		wp_remote_post(
			$url,
			array(
				'timeout'   => apply_filters( 'ai1wm_http_timeout', 5 ),
				'blocking'  => false,
				'sslverify' => false,
				'headers'   => $headers,
				'body'      => $params,
			)
		);
	}

	public static function transports( $transports ) {
		return get_option( AI1WM_URL_TRANSPORT, Ai1wm_Http::$transports );
	}
}
