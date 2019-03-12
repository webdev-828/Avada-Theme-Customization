<?php
class FusionSC_WidgetArea {

	public static $args;

	private $widget_counter = 1;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_widget-shortcode', array( $this, 'attr' ) );


		add_shortcode( 'fusion_widget_area', array( $this, 'render' ) );

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
				'class'			 	=> '',
				'id'				=> '',
				'background_color'	=> '',
				'name'				=> '',
				'padding'			=> ''
			), $args
		);

		$defaults['padding'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['padding'], 'px' );

		extract( $defaults );

		self::$args = $defaults;

		$html = sprintf( '<div %s>', FusionCore_Plugin::attributes( 'widget-shortcode' ) );
			$html .= self::get_styles();

			ob_start();
			if ( function_exists( 'dynamic_sidebar' ) &&
				 dynamic_sidebar( $name )
			) {
				// All is good, dynamic_sidebar() already called the rendering
			}
			$html .= ob_get_clean();

			$html .= sprintf( '<div %s>', FusionCore_Plugin::attributes( 'fusion-additional-widget-content' ) );
				$html .= do_shortcode( $content );
			$html .= '</div>';
		$html .= '</div>';

		$this->widget_counter++;

		return $html;

	}

	function attr() {

		$attr = array();

		$attr['class'] = sprintf( 'fusion-widget-area fusion-widget-area-%s fusion-content-widget-area', $this->widget_counter );

		if ( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if ( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		return $attr;
	}

	function get_styles() {
		$styles = '';

		if ( self::$args['background_color'] ) {
			$styles .= sprintf( '.fusion-widget-area-%s {background-color:%s;}', $this->widget_counter, self::$args['background_color'] );
		}

		if ( self::$args['padding'] ) {
			if ( strpos( self::$args['padding'], '%' ) === false && strpos( self::$args['padding'], 'px' ) === false ) {
				self::$args['padding'] = self::$args['padding'] . 'px';
			}

			$styles .= sprintf( '.fusion-widget-area-%s {padding:%s;}', $this->widget_counter, Avada_Sanitize::get_value_with_unit( self::$args['padding'] ) );
		}

		if ( $styles ) {
			$styles = sprintf( '<style type="text/css" scoped="scoped">%s</style>', $styles );
		}

		return $styles;
	}
}

new FusionSC_WidgetArea();
