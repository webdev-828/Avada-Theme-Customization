<?php
class FusionSC_Alert {

	private $alert_class;
	private $icon_class;

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_alert-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_alert-shortcode-icon', array( $this, 'icon_attr' ) );
		add_filter( 'fusion_attr_alert-shortcode-button', array( $this, 'button_attr' ) );

		add_shortcode( 'alert', array( $this, 'render' ) );

	}

	/**
	 * Render the shortcode
	 * @param  array $args     Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string          HTML output
	 */
	function render( $args, $content = '') {

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'               => '',
				'id'                  => '',
				'accent_color'        => '',
				'background_color'    => '',
				'border_size'         => '',
				'box_shadow'          => 'no',
				'icon'                => '',
				'type'                => 'general',
				'animation_type'      => '',
				'animation_direction' => 'left',
				'animation_speed'     => '',
				'animation_offset'    => Avada()->settings->get( 'animation_offset' ),
			), $args
		);

		$defaults['border_size'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['border_size'], 'px' );

		extract( $defaults );

		self::$args = $defaults;

		switch( $args['type'] ) {

			case 'general':
				$this->alert_class = 'info';
				if ( ! $icon || 'none' != $icon ) {
					self::$args['icon'] = $icon = 'fa-info-circle';
				}
				break;
			case 'error':
				$this->alert_class = 'danger';
				if( ! $icon || 'none' != $icon ) {
					self::$args['icon'] = $icon = 'fa-exclamation-triangle';
				}
				break;
			case 'success':
				$this->alert_class = 'success';
				if( ! $icon || 'none' != $icon ) {
					self::$args['icon'] = $icon = 'fa-check-circle';
				}
				break;
			case 'notice':
				$this->alert_class = 'warning';
				if( ! $icon || 'none' != $icon ) {
					self::$args['icon'] = $icon = 'fa-lg fa-cog';
				}
				break;
			case 'blank':
				$this->alert_class = 'blank';
				break;
			case 'custom':
				$this->alert_class = 'custom';
				break;
		}

		$html = '<div ' . FusionCore_Plugin::attributes( 'alert-shortcode' ) . '>';
			$html .= '  <button ' . FusionCore_Plugin::attributes( 'alert-shortcode-button' ) . '>&times;</button>';
			if ( $icon && 'none' != $icon ) {
				$html .= '<span ' . FusionCore_Plugin::attributes( 'alert-icon' ) . '>';
					$html .= '<i ' . FusionCore_Plugin::attributes( 'alert-shortcode-icon' ) . '></i>';
				$html .= '</span>';
			}
			$html .= do_shortcode( $content );
		$html .= '</div>';

		return $html;

	}

	function attr() {

		$attr = array();

		$attr['class'] = 'fusion-alert alert ' . self::$args['type'] . ' alert-dismissable alert-' . $this->alert_class;

		if ( 'yes' == self::$args['box_shadow'] ) {
			$attr['class'] .= ' alert-shadow';
		}

		if ( 'custom' == $this->alert_class ) {
			$attr['style']  = 'background-color:' . self::$args['background_color'] . ';';
			$attr['style'] .= 'color:' . self::$args['accent_color'] . ';';
			$attr['style'] .= 'border-color:' . self::$args['accent_color'] . ';';
			$attr['style'] .= 'border-width:' . self::$args['border_size'] . ';';
		}

		if ( self::$args['animation_type'] ) {
			$animations = FusionCore_Plugin::animations( array(
				'type'      => self::$args['animation_type'],
				'direction' => self::$args['animation_direction'],
				'speed'     => self::$args['animation_speed'],
				'offset'    => self::$args['animation_offset'],
			) );

			$attr = array_merge( $attr, $animations );

			$attr['class'] .= ' ' . $attr['animation_class'];
			unset( $attr['animation_class'] );
		}

		if ( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if ( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		return $attr;

	}

	function icon_attr() {
		return array(
			'class' => 'fa fa-lg ' . FusionCore_Plugin::font_awesome_name_handler( self::$args['icon'] )
		);
	}


	function button_attr() {

		$attr = array();

		if ( 'custom' == $this->alert_class ) {
			$attr['style'] = 'color:' . self::$args['accent_color'] . ';border-color:' . self::$args['accent_color'] . ';';
		}

		$attr['type']         = 'button';
		$attr['class']        = 'close toggle-alert';
		$attr['data-dismiss'] = 'alert';
		$attr['aria-hidden']  = 'true';

		return $attr;

	}

}

new FusionSC_Alert();
