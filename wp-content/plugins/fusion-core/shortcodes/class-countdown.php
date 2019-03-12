<?php
class FusionSC_Countdown {

	public static $args;

	private $countdown_counter = 1;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_countdown-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_countdown-shortcode-counter-wrapper', array( $this, 'counter_wrapper_attr' ) );
		add_filter( 'fusion_attr_countdown-shortcode-link', array( $this, 'link_attr' ) );

		add_shortcode( 'fusion_countdown', array( $this, 'render' ) );

	}

	/**
	 * Render the shortcode
	 *
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 *
	 * @return string		  HTML output
	 */
	public function render( $args, $content = '' ) {

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'			   		=> '',
				'id'				 	=> '',
				'background_color'		=> Avada()->settings->get( 'countdown_background_color' ),
				'background_image'		=> Avada()->settings->get( 'countdown_background_image', 'url' ),
				'background_position' 	=> Avada()->settings->get( 'countdown_background_position' ),
				'background_repeat' 	=> Avada()->settings->get( 'countdown_background_repeat' ),
				'border_radius'			=> Avada()->settings->get( 'countdown_border_radius' ),
				'counter_box_color'		=> Avada()->settings->get( 'countdown_counter_box_color' ),
				'counter_text_color'	=> Avada()->settings->get( 'countdown_counter_text_color' ),
				'countdown_end'			=> '2000-01-01 00:00:00',
				'dash_titles'			=> 'short',
				'heading_text'			=> '',
				'heading_text_color'	=> Avada()->settings->get( 'countdown_heading_text_color' ),
				'link_text'				=> '',
				'link_text_color'		=> Avada()->settings->get( 'countdown_link_text_color' ),
				'link_target'			=> Avada()->settings->get( 'countdown_link_target' ),
				'link_url'				=> '',
				'show_weeks'			=> Avada()->settings->get( 'countdown_show_weeks' ),
				'subheading_text'		=> '',
				'subheading_text_color'	=> Avada()->settings->get( 'countdown_subheading_text_color' ),
				'timezone'				=> Avada()->settings->get( 'countdown_timezone' ),
			), $args
		);

		$defaults['border_radius'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['border_radius'], 'px' );

		extract( $defaults );

		self::$args = $defaults;

		$html = sprintf( '<div %s>', FusionCore_Plugin::attributes( 'countdown-shortcode' ) );
			$html .= self::get_styles();
			$html .= sprintf( '<div %s>', FusionCore_Plugin::attributes( 'fusion-countdown-heading-wrapper' ) );
				$html .= sprintf( '<div %s>%s</div>', FusionCore_Plugin::attributes( 'fusion-countdown-subheading' ), $subheading_text );
				$html .= sprintf( '<div %s>%s</div>', FusionCore_Plugin::attributes( 'fusion-countdown-heading' ), $heading_text );
			$html .= '</div>';

			$html .= sprintf( '<div %s>', FusionCore_Plugin::attributes( 'countdown-shortcode-counter-wrapper' ) );

				$dashes = array (
					array( 'show' => $show_weeks, 'class' => 'weeks', 'shortname' => __( 'Weeks', 'fusion-core' ), 'longname' => __( 'Weeks', 'fusion-core' ) ),
					array( 'show' => 'yes', 'class' => 'days', 'shortname' => __( 'Days', 'fusion-core' ), 'longname' => __( 'Days', 'fusion-core' ) ),
					array( 'show' => 'yes', 'class' => 'hours', 'shortname' => __( 'Hrs', 'fusion-core' ), 'longname' => __( 'Hours', 'fusion-core' ) ),
					array( 'show' => 'yes', 'class' => 'minutes', 'shortname' => __( 'Min', 'fusion-core' ), 'longname' => __( 'Minutes', 'fusion-core' ) ),
					array( 'show' => 'yes', 'class' => 'seconds', 'shortname' => __( 'Sec', 'fusion-core' ), 'longname' => __( 'Seconds', 'fusion-core' ) )
				);

				$dash_class = '';
				if ( ! self::$args['counter_box_color'] || 'transparent' == self::$args['counter_box_color'] || '0' == Avada_Color::get_alpha_from_rgba( self::$args['counter_box_color'] ) ) {
					$dash_class = ' fusion-no-bg';
				}

				for ( $i = 0; $i < count( $dashes ); $i++ ) {
					if ( $dashes[$i]['show'] == 'yes' ) {
						$html .= sprintf( '<div class="fusion-dash-wrapper %s"><div class="fusion-dash fusion-dash-%s">%s<div class="fusion-digit">0</div><div class="fusion-digit">0</div><div class="fusion-dash-title">%s</div></div></div>', $dash_class, $dashes[$i]['class'], ( $dashes[$i]['class'] == 'days' ) ? '<div class="fusion-first-digit fusion-digit">0</div>' : '', $dashes[$i][$dash_titles . 'name'] );
					}
				}

			$html .= '</div>';

			$html .= sprintf( '<div %s>', FusionCore_Plugin::attributes( 'fusion-countdown-link-wrapper' ) );
				$html .= sprintf( '<a %s>%s</a>', FusionCore_Plugin::attributes( 'countdown-shortcode-link' ), $link_text );
			$html .= '</div>';


			$html .= do_shortcode( $content );
		$html .= '</div>';

		$this->countdown_counter++;

		return $html;

	}

	function attr() {

		$attr = array();

		$attr['class'] = sprintf( 'fusion-countdown fusion-countdown-%s', $this->countdown_counter );

		if ( ! self::$args['background_image'] && ( ! self::$args['background_color'] || 'transparent' == self::$args['background_color'] || '0' == Avada_Color::get_alpha_from_rgba( self::$args['background_color'] ) ) ) {
			$attr['class'] .= ' fusion-no-bg';
		}

		if ( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if ( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		return $attr;
	}

	function counter_wrapper_attr() {

		$attr = array();

		$attr['class'] = 'fusion-countdown-counter-wrapper';

		$attr['id'] = sprintf( 'fusion-countdown-%s', $this->countdown_counter );

		if ( self::$args['timezone'] == 'site_time' ) {
			$attr['data-gmt-offset'] = get_option( 'gmt_offset' );
		}

		if ( self::$args['countdown_end'] ) {
			$attr['data-timer'] = date( 'Y-m-d-H-i-s', strtotime( self::$args['countdown_end'] ) );
		}

		if ( self::$args['show_weeks'] == 'yes' ) {
			$attr['data-omit-weeks'] = '0';
		} else {
			$attr['data-omit-weeks'] = '1';
		}

		return $attr;
	}

	function link_attr() {

		$attr = array();

		$attr['class'] = 'fusion-countdown-link';

		$attr['target'] = self::$args['link_target'];
		$attr['href'] = self::$args['link_url'];

		return $attr;
	}

	function get_styles() {
		$styles = '';

		// Set custom background styles
		if ( self::$args['background_image'] ) {
			$styles .= sprintf( '.fusion-countdown-%s {', $this->countdown_counter );
			$styles .= sprintf( 'background:url(%s) %s %s %s;', self::$args['background_image'], self::$args['background_position'], self::$args['background_repeat'], self::$args['background_color']  );

			if ( self::$args['background_repeat'] == 'no-repeat') {
				$styles .= '-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover;';
			}
			$styles .= '}';

		} elseif ( self::$args['background_color'] ) {
			$styles .= sprintf( '.fusion-countdown-%s {background-color:%s;}', $this->countdown_counter, self::$args['background_color'] );
		}

		if ( self::$args['border_radius'] ) {
			$styles .= sprintf( '.fusion-countdown-%s, .fusion-countdown-%s .fusion-dash {border-radius:%s;}', $this->countdown_counter, $this->countdown_counter, self::$args['border_radius'] );
		}

		if ( self::$args['heading_text_color'] ) {
			$styles .= sprintf( '.fusion-countdown-%s .fusion-countdown-heading {color:%s;}', $this->countdown_counter, self::$args['heading_text_color'] );
		}

		if ( self::$args['subheading_text_color'] ) {
			$styles .= sprintf( '.fusion-countdown-%s .fusion-countdown-subheading {color:%s;}', $this->countdown_counter, self::$args['subheading_text_color'] );
		}

		if ( self::$args['counter_text_color'] ) {
			$styles .= sprintf( '.fusion-countdown-%s .fusion-countdown-counter-wrapper {color:%s;}', $this->countdown_counter, self::$args['counter_text_color'] );
		}

		if ( self::$args['counter_box_color'] ) {
			$styles .= sprintf( '.fusion-countdown-%s .fusion-dash {background-color:%s;}', $this->countdown_counter, self::$args['counter_box_color'] );
		}

		if ( self::$args['link_text_color'] ) {
			$styles .= sprintf( '.fusion-countdown-%s .fusion-countdown-link {color:%s;}', $this->countdown_counter, self::$args['link_text_color'] );
		}

		if ( $styles ) {
			$styles = sprintf( '<style type="text/css" scoped="scoped">%s</style>', $styles );
		}

		return $styles;
	}
}

new FusionSC_Countdown();
