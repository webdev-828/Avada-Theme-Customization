<?php
class FusionSC_FlipBoxes {

	private $flipbox_counter = 1;

	public static $parent_args;
	public static $child_args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_flip-boxes-shortcode', array( $this, 'parent_attr' ) );
		add_shortcode( 'flip_boxes', array( $this, 'render_parent' ) );

		add_filter( 'fusion_attr_flip-box-shortcode', array( $this, 'child_attr' ) );
		add_filter( 'fusion_attr_flip-box-shortcode-front-box', array( $this, 'front_box_attr' ) );
		add_filter( 'fusion_attr_flip-box-shortcode-back-box', array( $this, 'back_box_attr' ) );
		add_filter( 'fusion_attr_flip-box-shortcode-heading-front', array( $this, 'heading_front_attr' ) );
		add_filter( 'fusion_attr_flip-box-shortcode-heading-back', array( $this, 'heading_back_attr' ) );
		add_filter( 'fusion_attr_flip-box-shortcode-grafix', array( $this, 'grafix_attr' ) );
		add_filter( 'fusion_attr_flip-box-shortcode-icon', array( $this, 'icon_attr' ) );
		add_shortcode( 'flip_box', array( $this, 'render_child' ) );

	}

	/**
	 * Render the shortcode
	 *
	 * @param  array $args     Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string          HTML output
	 */
	function render_parent( $args, $content = '') {

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'   => '',
				'id'      => '',
				'columns' => '1',
			), $args
		);

		extract( $defaults );

		self::$parent_args = $defaults;

		if( self::$parent_args['columns'] > 6 ) {
			self::$parent_args['columns'] = 6;
		}

		$html = sprintf( '<div %s>%s</div><div %s></div>', FusionCore_Plugin::attributes( 'flip-boxes-shortcode' ), do_shortcode( $content ), FusionCore_Plugin::attributes( 'fusion-clearfix' ) );

		return $html;

	}

	function parent_attr() {

		$attr['class'] = sprintf( 'fusion-flip-boxes flip-boxes row fusion-columns-%s', self::$parent_args['columns'] );

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
	 *
	 * @param  array $args     Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string          HTML output
	 */
	function render_child( $args, $content = '') {
		global $smof_data;

		$defaults =    FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'                  => '',
				'id'                     => '',
				'background_color_front' => $smof_data['flip_boxes_front_bg'],
				'background_color_back'  => $smof_data['flip_boxes_back_bg'],
				'border_color'           => $smof_data['flip_boxes_border_color'],
				'border_radius'          => $smof_data['flip_boxes_border_radius'],
				'border_size'            => intval( $smof_data['flip_boxes_border_size'] ) . 'px',
				'circle'                 => '',
				'circle_color'           => $smof_data['icon_circle_color'],
				'circle_border_color'    => $smof_data['icon_border_color'],
				'icon'                   => '',
				'icon_color'             => $smof_data['icon_color'],
				'icon_flip'              => '',
				'icon_rotate'            => '',
				'icon_spin'              => '',
				'image'                  => '',
				'image_width'            => '35',
				'image_height'           => '35',
				'text_back_color'        => $smof_data['flip_boxes_back_text'],
				'text_front'             => '',
				'text_front_color'       => $smof_data['flip_boxes_front_text'],
				'title_front'            => '',
				'title_front_color'      => $smof_data['flip_boxes_front_heading'],
				'title_back'             => '',
				'title_back_color'       => $smof_data['flip_boxes_back_heading'],
				'animation_type'         => '',
				'animation_direction'    => 'left',
				'animation_speed'        => '0.1',
				'animation_offset'       => Avada()->settings->get( 'animation_offset' ),
			), $args
		);

		$defaults['border_size'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['border_size'], 'px' );
		$defaults['border_radius'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['border_radius'], 'px' );
		$defaults['image_width'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['image_width'], '' );
		$defaults['image_height'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['image_height'], '' );

		if( $defaults['border_radius'] == 'round' ) {
			$defaults['border_radius'] = '50%';
		}

		extract( $defaults );

		self::$child_args = $defaults;

		$style = $icon_output = $title_output = $title_front_output = $title_back_output = $alt = '';

		if( $image &&
			$image_width &&
			$image_height
		) {

			$image_id = FusionCore_Plugin::get_attachment_id_from_url( $image );

			if( $image_id ) {
				$alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
			}

			$icon_output = sprintf( '<img src="%s" width="%s" height="%s" alt="%s" />', $image, $image_width, $image_height, $alt );
		} else if( $icon ) {
			$icon_output = sprintf( '<i %s></i>', FusionCore_Plugin::attributes( 'flip-box-shortcode-icon' ) );
		}

		if( $icon_output ) {
			$icon_output = sprintf( '<div %s>%s</div>', FusionCore_Plugin::attributes( 'flip-box-shortcode-grafix' ), $icon_output );
		} else {
			$icon_output = '';
		}

		if( $title_front ) {
			$title_front_output = sprintf( '<h2 %s>%s</h2>', FusionCore_Plugin::attributes( 'flip-box-shortcode-heading-front' ), $title_front );
		}

		if( $title_back ) {
			$title_back_output = sprintf( '<h3 %s>%s</h3>', FusionCore_Plugin::attributes( 'flip-box-shortcode-heading-back' ), $title_back );
		}

		$front_inner = sprintf( '<div %s>%s</div>', FusionCore_Plugin::attributes( 'flip-box-front-inner' ), $icon_output . $title_front_output . $text_front );
		$back_inner = sprintf( '<div %s>%s</div>', FusionCore_Plugin::attributes( 'flip-box-back-inner' ), $title_back_output . do_shortcode( $content ) );

		$front = sprintf( '<div %s>%s</div>', FusionCore_Plugin::attributes( 'flip-box-shortcode-front-box' ), $front_inner );
		$back = sprintf( '<div %s>%s</div>', FusionCore_Plugin::attributes( 'flip-box-shortcode-back-box' ), $back_inner );

		$html = sprintf( '<div %s><div class="fusion-flip-box"><div %s>%s%s</div></div></div>', FusionCore_Plugin::attributes( 'flip-box-shortcode' ), FusionCore_Plugin::attributes( 'flip-box-inner-wrapper' ), $front, $back );

		$this->flipbox_counter++;

		return $html;

	}

	function child_attr() {

		if( self::$parent_args['columns'] &&
			! empty( self::$parent_args['columns'] )
		) {
			$columns = 12 / self::$parent_args['columns'];
		} else {
			$columns = 1;
		}

		$attr['class'] = sprintf('fusion-flip-box-wrapper fusion-column col-lg-%s col-md-%s col-sm-%s', $columns, $columns, $columns );

		if( self::$parent_args['columns'] == '5'  ) {
			$attr['class'] = 'fusion-flip-box-wrapper col-lg-2 col-md-2 col-sm-2';
		}

		if( self::$child_args['class'] ) {
			$attr['class'] .= ' ' . self::$child_args['class'];
		}

		if( self::$child_args['id'] ) {
			$attr['id'] = self::$child_args['id'];
		}

		return $attr;

	}

	function front_box_attr() {

		$attr['class'] = 'flip-box-front';

		if( self::$child_args['background_color_front'] ) {
			$attr['style'] = sprintf( 'background-color:%s;', self::$child_args['background_color_front'] );
		}

		if( self::$child_args['border_color'] ) {
			$attr['style'] .= sprintf( 'border-color:%s;', self::$child_args['border_color'] );
		}

		if( self::$child_args['border_radius'] ) {
			$attr['style'] .= sprintf( 'border-radius:%s;', self::$child_args['border_radius'] );
		}

		if( self::$child_args['border_size'] ) {
			$attr['style'] .= sprintf( 'border-style:solid;border-width:%s;', self::$child_args['border_size'] );
		}

		if( self::$child_args['text_front_color'] ) {
			$attr['style'] .= sprintf( 'color:%s;', self::$child_args['text_front_color'] );
		}

		return $attr;

	}

	function back_box_attr() {

		$attr['class'] = 'flip-box-back';

		if( self::$child_args['background_color_back'] ) {
			$attr['style'] = sprintf( 'background-color:%s;', self::$child_args['background_color_back'] );
		}

		if( self::$child_args['border_color'] ) {
			$attr['style'] .= sprintf( 'border-color:%s;', self::$child_args['border_color'] );
		}

		if( self::$child_args['border_radius'] ) {
			$attr['style'] .= sprintf( 'border-radius:%s;', self::$child_args['border_radius'] );
		}

		if( self::$child_args['border_size'] ) {
			$attr['style'] .= sprintf( 'border-style:solid;border-width:%s;', self::$child_args['border_size'] );
		}

		if( self::$child_args['text_back_color'] ) {
			$attr['style'] .= sprintf( 'color:%s;', self::$child_args['text_back_color'] );
		}

		return $attr;

	}

	function grafix_attr() {

		$attr = array();

		$attr['class'] = 'flip-box-grafix';

		if( ! self::$child_args['image'] ) {

			if( self::$child_args['circle'] == 'yes' ) {
				$attr['class'] .= ' flip-box-circle';

				if( self::$child_args['circle_color'] ) {
					$attr['style'] = sprintf( 'background-color:%s;', self::$child_args['circle_color'] );
				}

				if( self::$child_args['circle_border_color'] ) {
					$attr['style'] .= sprintf( 'border-color:%s;', self::$child_args['circle_border_color'] );
				}

			} else {
				$attr['class'] .= ' flip-box-no-circle';
			}
		} else {
			$attr['class'] .= ' flip-box-image';
		}

		return $attr;

	}

	function icon_attr() {

		$attr = array();

		if( self::$child_args['image'] ) {
			$attr['class'] = 'image';
		} else if( self::$child_args['icon'] ) {
			$attr['class'] = sprintf( 'fa %s', FusionCore_Plugin::font_awesome_name_handler( self::$child_args['icon'] ) );
		}

		if( self::$child_args['icon_color'] ) {
			$attr['style'] = sprintf( 'color:%s;', self::$child_args['icon_color'] );
		}

		if( self::$child_args['icon_flip'] ) {
			$attr['class'] .= ' fa-flip-' . self::$child_args['icon_flip'];
		}

		if( self::$child_args['icon_rotate'] ) {
			$attr['class'] .= ' fa-rotate-' . self::$child_args['icon_rotate'];
		}

		if( self::$child_args['icon_spin'] == 'yes' ) {
			$attr['class'] .= ' fa-spin';
		}

		if ( self::$child_args['animation_type'] && self::$child_args['icon_spin'] != 'yes' ) {
			$animations = FusionCore_Plugin::animations( array(
				'type'      => self::$child_args['animation_type'],
				'direction' => self::$child_args['animation_direction'],
				'speed'     => self::$child_args['animation_speed'],
				'offset'    => self::$child_args['animation_offset'],
			) );

			$attr = array_merge( $attr, $animations );

			$attr['class'] .= ' ' . $attr['animation_class'];
			unset( $attr['animation_class'] );
		}

		return $attr;

	}

	function heading_front_attr() {

		$attr['class'] = 'flip-box-heading';

		if( ! self::$child_args['text_front'] ) {
			$attr['class'] .= ' without-text';
		}

		if( self::$child_args['title_front_color'] ) {
			$attr['style'] = sprintf( 'color:%s;', self::$child_args['title_front_color'] );
		}

		return $attr;

	}

	function heading_back_attr() {

		$attr['class'] = 'flip-box-heading-back';

		if( self::$child_args['title_back_color'] ) {
			$attr['style'] = sprintf( 'color:%s;', self::$child_args['title_back_color'] );
		}

		return $attr;

	}

}

new FusionSC_FlipBoxes();
