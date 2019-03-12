<?php
class FusionSC_Modal {

	private $modal_counter = 1;
	private $label;

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_modal-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_modal-shortcode-dialog', array( $this, 'dialog_attr' ) );
		add_filter( 'fusion_attr_modal-shortcode-content', array( $this, 'content_attr' ) );
		add_filter( 'fusion_attr_modal-shortcode-heading', array( $this, 'heading_attr' ) );
		add_filter( 'fusion_attr_modal-shortcode-button', array( $this, 'button_attr' ) );
		add_filter( 'fusion_attr_modal-shortcode-button-footer', array( $this, 'button_footer_attr' ) );

		add_shortcode( 'modal', array( $this, 'render' ) );

	}

	/**
	 * Render the shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render( $args, $content = '') {
		global $smof_data;

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'			   		=> '',
				'id'				 	=> '',
				'background'			=> $smof_data['modal_bg_color'],
				'border_color'			=> $smof_data['modal_border_color'],
				'name'					=> '',
				'size' 					=> 'small',
				'title'					=> '',
				'show_footer'		 	=> 'yes'
			), $args
		);

		extract( $defaults );

		self::$args = $defaults;

		$style = '';
		if( $border_color ) {
			$style = sprintf( '<style type="text/css">.modal-%s .modal-header, .modal-%s .modal-footer{border-color:%s;}</style>', $this->modal_counter, $this->modal_counter, $border_color );
		}

		$html = sprintf( '<div %s>%s<div %s><div %s><div %s><button %s>&times;</button><h3 %s>%s</h3></div><div %s>%s</div>',
						 FusionCore_Plugin::attributes( 'modal-shortcode' ), $style, FusionCore_Plugin::attributes( 'modal-shortcode-dialog' ),
						 FusionCore_Plugin::attributes( 'modal-shortcode-content' ), FusionCore_Plugin::attributes( 'modal-header' ),
						 FusionCore_Plugin::attributes( 'modal-shortcode-button' ), FusionCore_Plugin::attributes( 'modal-shortcode-heading' ),
						 $title, FusionCore_Plugin::attributes( 'modal-body' ), do_shortcode( $content ) );

		if( $show_footer == 'yes' ) {
			$html .= sprintf( '<div %s><a %s>%s</a></div>', FusionCore_Plugin::attributes( 'modal-footer' ),
							  FusionCore_Plugin::attributes( 'modal-shortcode-button-footer' ), __( 'Close', 'fusion-core' ) );
		}

		$html .= '</div></div></div>';

		$this->modal_counter++;

		return $html;

	}

	function attr() {

		$attr = array();

		$attr['class'] = 'fusion-modal modal fade modal-' . $this->modal_counter;

		$attr['tabindex'] = '-1';
		$attr['role'] = 'dialog';
		$attr['aria-labelledby'] = sprintf( 'modal-heading-%s', $this->modal_counter );
		$attr['aria-hidden'] = 'true';

		if( self::$args['name'] ) {
			$attr['class'] .= ' ' . self::$args['name'];
		}

		if( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		return $attr;

	}

	function dialog_attr() {

		$attr = array();

		$attr['class'] = 'modal-dialog';

		if( self::$args['size'] == 'small' ) {
			$attr['class'] .= ' modal-sm';
		} else {
			$attr['class'] .= ' modal-lg';
		}

		return $attr;

	}

	function content_attr() {

		$attr = array();

		$attr['class'] = 'modal-content fusion-modal-content';

		if( self::$args['background'] ) {
			$attr['style'] = sprintf( 'background-color:%s', self::$args['background'] );
		}

		return $attr;

	}

	function button_attr() {

		$attr = array();

		$attr['class'] = 'close';
		$attr['type'] = 'button';
		$attr['data-dismiss'] = 'modal';
		$attr['aria-hidden'] = 'true';

		return $attr;

	}

	function heading_attr() {

		$attr = array();

		$attr['class'] = 'modal-title';
		$attr['id'] = sprintf( 'modal-heading-%s', $this->modal_counter );
		$attr['data-dismiss'] = 'modal';
		$attr['aria-hidden'] = 'true';

		return $attr;

	}

	function button_footer_attr() {

		$attr = array();

		$attr['class'] = 'fusion-button button-default button-medium button default medium';
		$attr['data-dismiss'] = 'modal';

		return $attr;

	}

}

class FusionSC_ModalTextLink {

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_modal-text-link-shortcode', array( $this, 'attr' ) );

		add_shortcode( 'modal_text_link', array( $this, 'render' ) );

	}

	/**
	 * Render the shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render( $args, $content = '') {

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'			   		=> '',
				'id'				 	=> '',
				'name'					=> '',
			), $args
		);

		extract( $defaults );

		self::$args = $defaults;

		$html = sprintf( '<a %s>%s</a>',
						 FusionCore_Plugin::attributes( 'modal-text-link-shortcode' ), do_shortcode( $content ) );

		return $html;

	}

	function attr() {

		$attr = array();

		$attr['class'] = 'fusion-modal-text-link';

		if( self::$args['name'] ) {
			$attr['data-toggle'] = 'modal';
			$attr['data-target'] = '.' . self::$args['name'];
		}

		if( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		$attr['href'] = '#';

		return $attr;

	}

}

new FusionSC_Modal();
new FusionSC_ModalTextLink();