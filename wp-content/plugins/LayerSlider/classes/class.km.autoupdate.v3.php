<?php

/**
 * Automatic Updater Class
 *
 * Receive updates for plugins and themes on-the-fly from
 * self-hosted repositories using the WordPress Updates API.
 *
 * @package KM_Updates
 * @since 4.6.3
 * @author John Gera
 * @copyright Copyright (c) 2013  John Gera, George Krupa, and Kreatura Media Kft.
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 */


class KM_UpdatesV3 {


	/**
	 * Repository API version
	 */
	const API_VERSION = 3;


	/**
	 * Adds additional one minute caching between WP API calls
	 * to prevent parallel requests of self-hosted repository.
	 */
	const TIMEOUT = 60;


	/**
	 * @var array $config Stores API an plugin details
	 * @access protected
	 */
	protected $config;


	/**
	 * @var object $data Received update data.
	 * @access protected
	 */
	protected $data;




	/**
	 * Init class and set up config for update checking
	 *
	 * @since 4.6.3
	 * @access public
	 * @param array $config Config for setting up auto updates
	 * @return void
	 */
	public function __construct($config = array()) {

		// Get and check params
		extract($config, EXTR_SKIP);
		if(!isset($repoUrl, $root, $version, $itemID, $codeKey, $authKey, $channelKey)) {
			wp_die('Missing params in $config for KM_Updates constructor');
		}

		// Bug fix in v5.3.0: WPLANG is not always defined
		if(!defined('WPLANG')) { define('WPLANG', ''); }

		// Build config
		$this->config = array_merge($config, array(
			'slug' => basename(dirname($config['root'])),
			'base' => plugin_basename($config['root']),
			'channel' => get_option($config['channelKey'], 'stable'),
			'license' => get_option($config['codeKey'], ''),
			'domain' => $_SERVER['SERVER_NAME'],
			'option' => strtolower(basename(dirname($config['root']))) . '_update_info',
			'locale' => WPLANG
		));
	}




	/**
	 * Adds self-hosted updates for site transients.
	 *
	 * @since 4.6.3
	 * @access public
	 * @param object $transient WP plugin updates site transient
	 * @return object $transient WP plugin updates site transient
	 */
	public function set_update_transient($transient) {

		$this->_check_updates();

		if(!isset($transient)) {
			$transient = new stdClass;
		}

		if(!isset($transient->response)) {
			$transient->response = array();
		}


		if(!empty($this->data->basic) && is_object($this->data->basic)) {
			if(version_compare($this->config['version'], $this->data->basic->version, '<')) {

				$this->data->basic->new_version = $this->data->basic->version;
				$transient->response[$this->config['base']] = $this->data->basic;
			}
		} else {
			unset($transient->response[$this->config['base']]);
		}

		return $transient;
	}




	/**
	 * Adds self-hosted updates for WP Updates API.
	 *
	 * @since 4.6.3
	 * @access public
	 * @param object $result Result object containing update info
	 * @param string $action WP Updates API action
	 * @param object $args Object containing additional information
	 * @return object $result Result object containing update info
	 */
	public function set_updates_api_results($result, $action, $args) {

		$this->_check_updates();

		if(isset($args->slug) && $args->slug == $this->config['slug'] && $action == 'plugin_information') {
			if(is_object($this->data->full) && !empty($this->data->full)) {
				$result = $this->data->full;
			}
		}

		return $result;
	}




	/**
	 * Check for update info.
	 *
	 * @since 4.6.3
	 * @access protected
	 * @return void
	 */
	protected function _check_updates() {

		// Get data
		if(empty($this->data)) {
			$data = get_option($this->config['option'], false);
			$data = $data ? $data : new stdClass;
			$this->data = is_object($data) ? $data : maybe_unserialize($data);
		}

		// Just installed
		if(!isset($this->data->checked)) {
			$this->data->checked = time();
		}

		// Check for updates
		if($this->data->checked < time() - self::TIMEOUT) {

			$response = $this->sendApiRequest($this->config['repoUrl'].'updates/');

			if(!empty($response) && $newData = maybe_unserialize($response)) {
				if(is_object($newData)) {
					$this->data = $newData;
					$this->data->checked = time();
				}
			}


			// Store version number of the latest release
			// to notify unauthorized sites owners
			if(!empty($this->data->_latest_version)) {
				update_option('ls-latest-version', $data->_latest_version);
			}
		}

		// Save results
		update_option($this->config['option'], $this->data);
	}



	/**
	 * Retrieves API method info from self-hosted repository.
	 *
	 * @since 4.6.3
	 * @access protected
	 * @param string $url API URL to be called
	 * @return string API response
	 */
	protected function sendApiRequest($url) {

		if(empty($url)) { return false; }

		// Build request
		$request = wp_remote_post($url, array(
			'user-agent' => 'WordPress/'.$GLOBALS['wp_version'].'; '.get_bloginfo('url'),
			'body' => array(
				'slug' => $this->config['slug'],
				'version' => $this->config['version'],
				'channel' => $this->config['channel'],
				'license' => $this->config['license'],
				'item_id' => $this->config['itemID'],
				'domain' => $this->config['domain'],
				'locale' => $this->config['locale'],
				'api_version' => self::API_VERSION
			)
		));

		return wp_remote_retrieve_body($request);
	}




	/**
	 * Parses JSON API responses
	 *
	 * @since 4.6.3
	 * @access protected
	 * @param string $response JSON string to be parsed
	 * @return array Array of the raw and parsed JSON
	 */
	public function parseApiResponse($response) {

		// Get response
		$json = !empty($response) ? json_decode($response) : false;

		// ERR: Unexpected error
		if(empty($json)) {
			die(json_encode(array(
				'message' => 'An unexpected error occurred. Please try again later.',
				'errCode' => 'ERR_UNEXPECTED_ERROR')
			));
		}

		return array($response, $json);
	}



	/**
	 * Handles repository authorization and saving auto-update settings
	 *
	 * @since 4.6.3
	 * @access public
	 * @return string JSON string of authorization status data
	 */
	public function handleActivation() {

		// Required informations
		if(empty($_POST['purchase_code']) || empty($_POST['channel'])) {
			die(json_encode(array(
				'status' => 'Please enter your purchase code.',
				'errCode' => 'ERR_INVALID_DATA_RECEIVED')
			));
		}

		// Re-validation
		if(get_option('layerslider-validated', null) === '1' && !empty($this->config['license']) && get_option('layerslider-authorized-site', null) === null) {
			$_POST['purchase_code'] = $this->config['license'];
		}

		// Save release channel
		update_option($this->config['channelKey'], $_POST['channel']);

		// Only update release channel?
		if(get_option($this->config['authKey'], false)) {
			if( strpos($_POST['purchase_code'], '●') === 0 || $this->config['license'] == $_POST['purchase_code']) {
				die(json_encode(array('status' => __('Your settings were successfully saved.', 'LayerSlider'))));
			}
		}

		// Validate purchase
		$this->config['license'] = $_POST['purchase_code'];
		$data = $this->sendApiRequest($this->config['repoUrl'].'authorize/');
		list($response, $json) = $this->parseApiResponse($data);

		// Failed authorization
		if(!empty($json->errCode)) {
			update_option($this->config['authKey'], 0);
			update_option($this->config['codeKey'], '');

		// Successful authorization
		} else {
			update_option($this->config['authKey'], 1);
			update_option($this->config['codeKey'], $_POST['purchase_code']);
		}

		die($response);
	}



	/**
	 * Handles repository deauthorization
	 *
	 * @since 4.6.3
	 * @access public
	 * @return string JSON string of deauthorization status data
	 */
	public function handleDeactivation() {

		// Get response
		$data = $this->sendApiRequest($this->config['repoUrl'].'deauthorize/');
		list($response, $json) = $this->parseApiResponse($data);

		// Deauthorize
		delete_option($this->config['codeKey']);
		delete_option($this->config['authKey']);

		die($response);
	}
}
