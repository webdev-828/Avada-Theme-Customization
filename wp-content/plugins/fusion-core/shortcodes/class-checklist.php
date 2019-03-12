<?php
class FusionSC_Checklist {


	private $checklist_counter = 1;
	private $circle_class = 'circle-no';

	public static $parent_args;
	public static $child_args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_checklist-shortcode', array( $this, 'attr' ) );
		add_shortcode( 'checklist', array( $this, 'render_parent' ) );

		add_filter( 'fusion_attr_checklist-shortcode-li-item', array( $this, 'li_attr' ) );
		add_filter( 'fusion_attr_checklist-shortcode-span', array( $this, 'span_attr' ) );
		add_filter( 'fusion_attr_checklist-shortcode-icon', array( $this, 'icon_attr' ) );
		add_filter( 'fusion_attr_checklist-shortcode-item-content', array( $this, 'item_content_attr' ) );

		add_shortcode( 'li_item', array( $this, 'render_child' ) );

	}

	/**
	 * Render the parent shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render_parent( $args, $content = '') {
		global $smof_data;

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'			=> '',
				'id'			=> '',
				'circle'		=> strtolower( $smof_data['checklist_circle'] ),
				'circlecolor'	=> $smof_data['checklist_circle_color'],
				'icon'			=> 'fa-check',
				'iconcolor'		=> $smof_data['checklist_icons_color'],
				'size'			=> '13px',
			), $args
		);

		$defaults['size'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['size'], 'px' );

		( $defaults['circle'] == 1 ) ? $defaults['circle'] = 'yes' : $defaults['circle'] = $defaults['circle'];

		// Fallbacks for old size parameter and 'px' check
		if ( $defaults['size'] == 'small' ) {
			$defaults['size'] = '13px';
		} elseif ( $defaults['size'] == 'medium' ) {
			$defaults['size'] = '18px';
		} elseif ( $defaults['size'] == 'large' ) {
			$defaults['size'] = '40px';
		} elseif ( ! strpos( $defaults['size'], 'px' ) ) {
			$defaults['size'] = $defaults['size'] . 'px';
		}

		// Dertmine line-height and margin from font size
		$font_size = str_replace( 'px', '', $defaults['size'] );
		$defaults['circle_yes_font_size'] = $font_size * 0.88;
		$defaults['line_height'] = $font_size * 1.7;
		$defaults['icon_margin'] = $font_size * 0.7;
		$defaults['icon_margin_position'] = ( is_rtl() ) ? 'left' : 'right';
		$defaults['content_margin'] = $defaults['line_height'] + $defaults['icon_margin'];
		$defaults['content_margin_position'] = ( is_rtl() ) ? 'right' : 'left';

		extract( $defaults );

		self::$parent_args = $defaults;

		// legacy checklist integration
		if( strpos( $content, '<li>' ) &&
			strpos( $content, '[list_item' ) === false
		) {
			$content = str_replace( '<ul>', '', $content );
			$content = str_replace( '</ul>', '', $content );
			$content = str_replace( '<li>', '[li_item]', $content );
			$content = str_replace( '</li>', '[/li_item]', $content );
		}

		$html = sprintf( '<ul %s>%s</ul>', FusionCore_Plugin::attributes( 'checklist-shortcode' ), do_shortcode( $content ) );

		$html = str_replace( '</li><br />', '</li>', $html );

		$this->checklist_counter++;

		return $html;

	}

	function attr() {

		$attr = array();

		$attr['class'] = sprintf( 'fusion-checklist fusion-checklist-%s', $this->checklist_counter );

		$font_size = str_replace( 'px', '', self::$parent_args['size'] );
		$line_height = $font_size * 1.7;
		$attr['style'] = sprintf( 'font-size:%s;line-height:%spx;', self::$parent_args['size'], $line_height );

		if( self::$parent_args['class'] ) {
			$attr['class'] .= ' ' . self::$parent_args['class'];
		}

		if( self::$parent_args['id'] ) {
			$attr['id'] = self::$parent_args['id'];
		}

		return $attr;

	}

	/**
	 * Render the child shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render_child( $args, $content = '') {

		$defaults = shortcode_atts(
			array(
				'circle'		=> '',
				'circlecolor'	=> '',
				'icon'			=> '',
				'iconcolor'		=> '',
			), $args
		);

		extract( $defaults );

		self::$child_args = $defaults;

		$html =  sprintf( '<li %s><span %s><i %s></i></span><div %s>%s</div></li>', FusionCore_Plugin::attributes( 'checklist-shortcode-li-item' ), FusionCore_Plugin::attributes( 'checklist-shortcode-span' ),
						  FusionCore_Plugin::attributes( 'checklist-shortcode-icon' ), FusionCore_Plugin::attributes( 'checklist-shortcode-item-content' ), do_shortcode( $content ) );

		$this->circle_class = 'circle-no';

		return $html;

	}

	function li_attr() {

		$attr = array();

		$attr['class'] = 'fusion-li-item';

		return $attr;

	}


	function item_content_attr() {

		$attr = array();

		$attr['class'] = 'fusion-li-item-content';

		$attr['style'] = sprintf( 'margin-%s:%spx;', self::$parent_args['content_margin_position'], self::$parent_args['content_margin'] );

		return $attr;

	}

	function span_attr() {

		$attr = array();

		$attr['style'] = '';

		if( self::$child_args['circle'] == 'yes' ||
			self::$parent_args['circle'] == 'yes' && ( self::$child_args['circle']  != 'no' )
		) {
			$this->circle_class = 'circle-yes';

			if( ! self::$child_args['circlecolor'] ) {
				$circlecolor = self::$parent_args['circlecolor'];
			} else {
				$circlecolor = self::$child_args['circlecolor'];
			}
			$attr['style'] = sprintf( 'background-color:%s;', $circlecolor );

			$attr['style'] .= sprintf( 'font-size:%spx;', self::$parent_args['circle_yes_font_size'] );
		}

		$attr['class'] = sprintf( 'icon-wrapper %s', $this->circle_class );

		$attr['style'] .= sprintf( 'height:%spx;width:%spx;margin-%s:%spx;', self::$parent_args['line_height'], self::$parent_args['line_height'],
								  self::$parent_args['icon_margin_position'], self::$parent_args['icon_margin'] );

		return $attr;

	}

	function icon_attr() {

		$attr = array();

		if( ! self::$child_args['icon'] ) {
			$icon = FusionCore_Plugin::font_awesome_name_handler( self::$parent_args['icon'] );
		} else {
			$icon = FusionCore_Plugin::font_awesome_name_handler( self::$child_args['icon'] );
		}

		if( ! self::$child_args['iconcolor'] ) {
			$iconcolor = self::$parent_args['iconcolor'];
		} else {
			$iconcolor = self::$child_args['iconcolor'];
		}

		$attr['class'] = sprintf( 'fusion-li-icon fa %s', $icon );

		$attr['style'] = sprintf( 'color:%s;', $iconcolor );

		return $attr;

	}
}

new FusionSC_Checklist();