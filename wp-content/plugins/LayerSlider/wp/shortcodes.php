<?php


function layerslider($id = 0, $filters = '') {
	echo LS_Shortcode::handleShortcode(array('id' => $id, 'filters' => $filters));
}

class LS_Shortcode {

	// List of already included sliders on page.
	// Using to identify duplicates and give them
	// a unique slider ID to avoid issues with caching.
	public static $slidersOnPage = array();

	private function __contruct() {}


	/**
	 * Registers the LayerSlider shortcode.
	 *
	 * @since 5.3.3
	 * @access public
	 * @return void
	 */

	public static function registerShortcode() {
		if(!shortcode_exists('layerslider')) {
			add_shortcode('layerslider', array(__CLASS__, 'handleShortcode'));
		}
	}




	/**
	 * Handles the shortcode workflow to display the
	 * appropriate content.
	 *
	 * @since 5.3.3
	 * @access public
	 * @param array $atts Shortcode attributes
	 * @return bool True on successful validation, false otherwise
	 */

	public static function handleShortcode($atts = array()) {

		if(self::validateFilters($atts)) {
			if($slider = self::validateShortcode($atts)) {
				return self::processShortcode($slider);
			} else {

				$data = '<div style="margin: 10px auto; padding: 10px; border: 2px solid red; border-radius: 5px;">';
				$data.= '<strong style="display: block; font-size: 18px;">'.__('LayerSlider encountered a problem while it tried to show your slider.', 'LayerSlider').'</strong>';
				$data.= __("Please make sure that you've used the right shortcode or method to insert the slider, and check if the corresponding slider exists and it wasn't deleted previously.", "LayerSlider");
				$data.= '</div>';

				return $data;
			}
		}
	}




	/**
	 * Validates the provided shortcode filters (if any).
	 *
	 * @since 5.3.3
	 * @access public
	 * @param array $atts Shortcode attributes
	 * @return bool True on successful validation, false otherwise
	 */

	public static function validateFilters($atts = array()) {

		// Bail out early and pass the validation
		// if there aren't filters provided
		if(empty($atts['filters'])) {
			return true;
		}

		// Gather data needed for filters
		$pages = explode(',', $atts['filters']);
		$currSlug = basename(get_permalink());
		$currPageID = (string) get_the_ID();

		foreach($pages as $page) {

			if(($page == 'homepage' && is_front_page())
				|| $currPageID == $page
				|| $currSlug == $page
				|| in_category($page)
			) {
				return true;
			}
		}

		// No filters matched,
		// return false
		return false;
	}



	/**
	 * Validates the shortcode parameters and checks
	 * the references slider.
	 *
	 * @since 5.3.3
	 * @access public
	 * @param array $atts Shortcode attributes
	 * @return bool True on successful validation, false otherwise
	 */

	public static function validateShortcode($atts = array()) {

		// Has ID attribute
		if(!empty($atts['id'])) {

			// Attempt to retrieve the pre-generated markup
			// set via the Transients API
			if(get_option('ls_use_cache', true)) {
				if($markup = get_transient('ls-slider-data-'.intval($atts['id']))) {
					$markup['id'] = intval($atts['id']);
					$markup['_cached'] = true;
					return $markup;
				}
			}

			// Slider exists and isn't deleted
			$slider = LS_Sliders::find($atts['id']);
			if(!empty($slider) || $slider['flag_deleted'] != '1') {
				return $slider;
			}
		}

		return false;
	}





	public static function processShortcode($slider) {

		// Slider ID
		$sID = 'layerslider_'.$slider['id'];

		// Include init code in the footer?
		$condsc = get_option('ls_conditional_script_loading', false) ? true : false;
		$footer = get_option('ls_include_at_footer', false) ? true : false;
		$footer = $condsc ? true : $footer;

		// Check if the returned data is a string,
		// indicating that it's a pre-generated
		// slider markup retrieved via Transients
		if(!empty($slider['_cached'])) { $output = $slider;}
		else {
			$output = self::generateSliderMarkup($slider);
			set_transient('ls-slider-data-'.$slider['id'], $output, HOUR_IN_SECONDS * 6);
		}

		// Replace slider ID to avoid issues with enabled caching when
		// adding the same slider to a page in multiple times
		if(array_key_exists($slider['id'], self::$slidersOnPage)) {
			$sliderCount = ++self::$slidersOnPage[ $slider['id'] ];
			$output['init'] = str_replace($sID, $sID.'_'.$sliderCount, $output['init']);
			$output['container'] = str_replace($sID, $sID.'_'.$sliderCount, $output['container']);

		} else {

			// Add current slider ID to identify duplicates later on
			// and give them a unique slider ID to avoid issues with caching.
			self::$slidersOnPage[ $slider['id'] ] = 1;
		}

		// Unify the whole markup after any potential string replacement
		$output['markup'] = $output['container'].$output['markup'];

		// Filter to override the printed HTML markup
		if(has_filter('layerslider_slider_markup')) {
			$lsMarkup = apply_filters('layerslider_slider_markup', $lsMarkup);
		}

		if($footer) {
			$GLOBALS['lsSliderInit'][] = $output['init'];
			return $output['markup'];
		} else {
			return $output['init'].$output['markup'];
		}
	}



	public static function generateSliderMarkup($slider = null) {

		// Bail out early if no params received
		if(!$slider) { return array('init' => '', 'container' => '', 'markup' => ''); }

		// Slider and markup data
		$id = $slider['id'];
		$sliderID = 'layerslider_'.$id;
		$slides = $slider['data'];

		// Store generated output
		$lsInit = ''; $lsContainer = ''; $lsMarkup = '';

		// Include slider file
		if(is_array($slides)) {

			// Get phpQuery
			if(!class_exists('phpQuery')) {
				libxml_use_internal_errors(true);
				include LS_ROOT_PATH.'/helpers/phpQuery.php';
			}

			include LS_ROOT_PATH.'/config/defaults.php';
			include LS_ROOT_PATH.'/includes/slider_markup_init.php';
			include LS_ROOT_PATH.'/includes/slider_markup_html.php';
			$lsInit = implode('', $lsInit);
			$lsContainer = implode('', $lsContainer);
			$lsMarkup = implode('', $lsMarkup);
		}

		// Concatenate output
		if(get_option('ls_concatenate_output', false)) {
			$lsInit = trim(preg_replace('/\s+/u', ' ', $lsInit));
			$lsContainer = trim(preg_replace('/\s+/u', ' ', $lsContainer));
			$lsMarkup = trim(preg_replace('/\s+/u', ' ', $lsMarkup));
		}

		// Bug fix in v5.4.0: Use self closing tag for <source>
		$lsMarkup = str_replace('></source>', ' />', $lsMarkup);

		// Return formatted data
		return array(
			'init' => $lsInit,
			'container' => $lsContainer,
			'markup' => $lsMarkup
		);
	}
}