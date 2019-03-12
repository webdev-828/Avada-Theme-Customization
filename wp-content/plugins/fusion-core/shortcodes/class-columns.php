<?php
/**
 * Class rendering all layout column shortcodes.
 * This class is different from other shortcode classes,
 * as it does not use class variables for the $args array
 * and other params, but sends the variables directly to
 * the filter functions.
 *
 * @since 1.8
 */
class FusionSC_Columns {

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_columns-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_columns-shortcode-wrapper', array( $this, 'wrapper_attr' ) );
		add_filter( 'fusion_attr_columns-shortcode-inner-bg', array( $this, 'inner_bg_attr' ) );

		add_shortcode( 'one_half', array( $this, 'render' ) );
		add_shortcode( 'five_sixth', array( $this, 'render' ) );
		add_shortcode( 'four_fifth', array( $this, 'render' ) );
		add_shortcode( 'one_fifth', array( $this, 'render' ) );
		add_shortcode( 'one_fourth', array( $this, 'render' ) );
		add_shortcode( 'one_full', array( $this, 'render' ) );
		add_shortcode( 'one_sixth', array( $this, 'render' ) );
		add_shortcode( 'one_third', array( $this, 'render' ) );
		add_shortcode( 'three_fifth', array( $this, 'render' ) );
		add_shortcode( 'three_fourth', array( $this, 'render' ) );
		add_shortcode( 'two_fifth', array( $this, 'render' ) );
		add_shortcode( 'two_third', array( $this, 'render' ) );

	}

	/**
	 * Render the shortcode
	 *
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @param  string $tag The actual shortcode tag, e.g. one_half.
	 * @return string		  HTML output
	 */
	function render( $args, $content = '', $tag ) {
		global $smof_data;


		$defaults =	FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'					=> '',
				'id'					=> '',
				'background_color'		=> '',
				'background_image'		=> '',
				'background_position' 	=> 'left top',
				'background_repeat' 	=> 'no-repeat',
				'border_color'			=> '',
				'border_position'		=> 'all',
				'border_size'			=> '',
				'border_style'			=> 'solid',
				'center_content'		=> 'no',
				'hide_on_mobile'		=> 'no',
				'last'  				=> 'no',
				'margin_top'			=> $smof_data['col_margin']['top'],
				'margin_bottom'			=> $smof_data['col_margin']['bottom'],
				'padding'				=> '',
				'spacing'				=> 'yes',
				'animation_type' 		=> '',
				'animation_direction' 	=> 'left',
				'animation_speed' 		=> '0.1',
				'animation_offset'		=> Avada()->settings->get( 'animation_offset' ),
				'link'					=> '',
				'hover_type'			=> 'none'
			), $args
		);

	
		$defaults['margin_top'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['margin_top'], 'px' );
		$defaults['margin_bottom'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['margin_bottom'], 'px' );
		$defaults['border_size'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['border_size'], 'px' );
		$defaults['padding'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['padding'], 'px' );
	
		$defaults['tag'] = $tag;

		extract( $defaults );

		if( $defaults['margin_top'] == '' ) {
			$defaults['margin_top'] = $smof_data['col_margin']['top'];
		}

		if( $defaults['margin_bottom'] == '' ) {
			$defaults['margin_bottom'] = $smof_data['col_margin']['bottom'];
		}



		// After the last column we need a clearing div
		$clearfix = '';
		if ( $last == 'yes' ) {
			$clearfix = sprintf( '<div %s></div>', FusionCore_Plugin::attributes( 'fusion-clearfix' ) );
		}

		$inner_content = do_shortcode( $content );

		$inner_content .= '<div class="fusion-clearfix"></div>';

		// If content should be centered, add needed markup
		if ( $center_content == 'yes' ) {
			$inner_content = sprintf( '<div class="fusion-column-table"><div class="fusion-column-tablecell">%s</div></div>', $inner_content );
		}

		// Link + Hover Background
		$inner_bg = '';
		if( $link || ( $hover_type && $hover_type != 'none' ) ) {
			$bg_link = '';
			if( $link ) {
				$bg_link = 'href="' . $link . '"';
			}

			$inner_bg = sprintf( '<span class="fusion-column-inner-bg hover-type-%s"><a %s><span %s></span></a></span>', $hover_type, $bg_link, FusionCore_Plugin::attributes( 'columns-shortcode-inner-bg', $defaults ) );
		}

		// Setup the main markup
		$html = sprintf( '<div %s><div %s>%s</div>%s</div>%s', FusionCore_Plugin::attributes( 'columns-shortcode', $defaults ), FusionCore_Plugin::attributes( 'columns-shortcode-wrapper', $defaults ), $inner_content, $inner_bg, $clearfix );

		return $html;

	}

	function attr( $args ) {
		$class = str_replace( '_', '-', $args['tag'] );

		$attr['class'] = 'fusion-' . $class . ' fusion-layout-column';

		if( $args['link'] || ( $args['hover_type'] && $args['hover_type'] != 'none' ) ) {
			$attr['class'] .= ' fusion-column-inner-bg-wrapper';
		}

		// Set the last class on the rightmost column to supress margin
		if ( $args['last'] == 'yes' ) {
			$attr['class'] .= ' fusion-column-last';
		}

		// Set spacing class for correct widths
		$attr['class'] .= ' fusion-spacing-' . $args['spacing'];

		$attr['style'] = sprintf( 'margin-top:%s;margin-bottom:%s;', Avada_Sanitize::get_value_with_unit( $args['margin_top'] ), Avada_Sanitize::get_value_with_unit( $args['margin_bottom'] ) );

		if( $args['hide_on_mobile'] == 'yes' ) {
			$attr['class'] .= ' fusion-hide-on-mobile';
		}

		// User specific class and id
		if( $args['class'] ) {
			$attr['class'] .= ' ' . $args['class'];
		}

		if( $args['id'] ) {
			$attr['id'] = $args['id'];
		}

		return $attr;

	}

	function wrapper_attr( $args ) {
		$attr = array();

		$attr['class'] = 'fusion-column-wrapper';

		$attr['style'] = '';

		// Set custom background styles
		if( ! $args['link'] && ( ! $args['hover_type'] || $args['hover_type'] == 'none' ) ) {
			if ( $args['background_image'] ) {
				$attr['style'] .= sprintf( 'background:url(%s) %s %s %s;', $args['background_image'], $args['background_position'], $args['background_repeat'], $args['background_color']  );

				if ( $args['background_repeat'] == 'no-repeat') {
					$attr['style'] .= '-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover;';
				}

			} elseif ( $args['background_color'] ) {
				$attr['style'] .= sprintf( 'background-color:%s;', $args['background_color'] );
			}
		}

		if ( $args['background_image'] ) {
			$attr['data-bg-url'] = $args['background_image'];
		}

		// Set custom border styles
		if ( $args['border_color'] &&
			 $args['border_size'] &&
			 $args['border_style']
		) {
		 	if ( $args['border_position'] != 'all' ) {
		 		$border_position = '-' . $args['border_position'];
		 	} else {
		 		$border_position = '';
		 	}

			$attr['style'] .= sprintf( 'border%s:%s %s %s;', $border_position, $args['border_size'], $args['border_style'], $args['border_color'] );
		}

		// Set custom padding
		if ( $args['padding'] ) {
			$attr['style'] .= sprintf( 'padding:%s;', Avada_Sanitize::get_value_with_unit( $args['padding'] ) );
		}

		// Animations
		if ( $args['animation_type'] ) {
			$animations = FusionCore_Plugin::animations( array(
				'type'	  	=> $args['animation_type'],
				'direction' => $args['animation_direction'],
				'speed'	 	=> $args['animation_speed'],
				'offset' 	=>$args['animation_offset'],
			) );

			$attr = array_merge( $attr, $animations );

			$attr['class'] .= ' ' . $attr['animation_class'];
			unset( $attr['animation_class'] );
		}

		return $attr;
	}

	function inner_bg_attr( $args ) {
		$attr = array();

		$attr['class'] = 'fusion-column-inner-bg-image';
		$attr['style'] = '';

		// Set custom background styles
		if ( $args['background_image'] ) {
			$attr['style'] .= sprintf( 'background:url(%s) %s %s %s;', $args['background_image'], $args['background_position'], $args['background_repeat'], $args['background_color']  );

			if ( $args['background_repeat'] == 'no-repeat') {
				$attr['style'] .= '-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover;';
			}

		} elseif ( $args['background_color'] ) {
			$attr['style'] .= sprintf( 'background-color:%s;', $args['background_color'] );
		}

		return $attr;
	}

}

new FusionSC_Columns();