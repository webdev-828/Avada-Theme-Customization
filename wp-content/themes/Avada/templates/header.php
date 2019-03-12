<?php

if ( ! function_exists( 'avada_header_template' ) ) {
	/**
	 * Avada Header Template Function
	 * @param  string $slider_position Show header below or above slider
	 * @return void
	 */
	function avada_header_template( $slider_position = 'Below' ) {

		$page_id = get_queried_object_id();

		$reverse_position = ( 'Below' == $slider_position ) ? 'Above' : 'Below';

		$menu_text_align = '';

		$theme_option_slider_position = Avada()->settings->get( 'slider_position' );
		$page_option_slider_position  = fusion_get_page_option( 'slider_position', $page_id );

		if ( ( ! $theme_option_slider_position || ( $theme_option_slider_position == $slider_position && $page_option_slider_position != strtolower( $reverse_position ) ) || ( $theme_option_slider_position != $slider_position && $page_option_slider_position == strtolower( $slider_position ) ) ) && ! is_page_template( 'blank.php' ) && fusion_get_page_option( 'display_header', $page_id ) != 'no' && Avada()->settings->get( 'header_position' ) == 'Top' ) {
			$header_wrapper_class  = 'fusion-header-wrapper';
			$header_wrapper_class .= ( Avada()->settings->get( 'header_shadow' ) ) ? ' fusion-header-shadow' : '';
			$header_wrapper_class  = 'class="' . $header_wrapper_class . '"';

			/**
			 * avada_before_header_wrapper hook
			 */
			do_action( 'avada_before_header_wrapper' );

			$sticky_header_logo = Avada()->settings->get( 'sticky_header_logo' );
			$sticky_header_logo = ( is_array( $sticky_header_logo ) && isset( $sticky_header_logo['url'] ) && $sticky_header_logo['url'] ) ? true : false;
			$mobile_logo        = Avada()->settings->get( 'mobile_logo' );
			$mobile_logo        = ( is_array( $mobile_logo ) && isset( $mobile_logo['url'] ) && $mobile_logo['url'] ) ? true : false;

			$sticky_header_type2_layout = '';

			if ( in_array( Avada()->settings->get( 'header_layout' ), array( 'v4', 'v5' ) ) ) {
				$sticky_header_type2_layout = ( 'menu_and_logo' == Avada()->settings->get( 'header_sticky_type2_layout' ) ) ? ' fusion-sticky-menu-and-logo' : ' fusion-sticky-menu-only';
				$menu_text_align = 'fusion-header-menu-align-' . Avada()->settings->get( 'menu_text_align' );
			}
			?>

			<div <?php echo $header_wrapper_class; ?>>
				<div class="fusion-header-<?php echo Avada()->settings->get( 'header_layout' ); ?> fusion-logo-<?php echo strtolower( Avada()->settings->get( 'logo_alignment' ) ); ?> fusion-sticky-menu-<?php echo has_nav_menu( 'sticky_navigation' ); ?> fusion-sticky-logo-<?php echo $sticky_header_logo; ?> fusion-mobile-logo-<?php echo $mobile_logo; ?> fusion-mobile-menu-design-<?php echo strtolower( Avada()->settings->get( 'mobile_menu_design' ) ); ?><?php echo $sticky_header_type2_layout; ?> <?php echo $menu_text_align; ?>">
					<?php
					/**
					 * avada_header hook
					 * @hooked avada_secondary_header - 10
					 * @hooked avada_header_1 - 20 (adds header content for header v1-v3)
					 * @hooked avada_header_2 - 20 (adds header content for header v4-v5)
					 */
					do_action( 'avada_header' );
					?>
				</div>
				<div class="fusion-clearfix"></div>
			</div>
			<?php
			/**
			 * avada_after_header_wrapper hook
			 */
			do_action( 'avada_after_header_wrapper' );
		}
	}
}

if ( ! function_exists( 'avada_side_header' ) ) {
	/**
	 * Avada Side Header Template Function
	 * @return void
	 */
	function avada_side_header() {
		$queried_object_id = get_queried_object_id();

		if ( ! is_page_template( 'blank.php' ) && 'no' != get_post_meta( $queried_object_id, 'pyre_display_header', true ) ) {
			get_template_part( 'templates/side-header' );
		}
	}
}

if ( ! function_exists( 'avada_secondary_header' ) ) {
	function avada_secondary_header() {
		if ( ! in_array( Avada()->settings->get( 'header_layout' ), array( 'v2', 'v3', 'v4', 'v5' ) ) ) {
			return;
		}
		if ( 'Leave Empty' != Avada()->settings->get( 'header_left_content' ) || 'Leave Empty' != Avada()->settings->get( 'header_right_content' ) ) {
			get_template_part( 'templates/header-secondary' );
		}
	}
}
add_action( 'avada_header', 'avada_secondary_header', 10 );

if ( ! function_exists( 'avada_header_1' ) ) {
	function avada_header_1() {
		if ( ! in_array( Avada()->settings->get( 'header_layout' ), array( 'v1', 'v2', 'v3' ) ) ) {
			return;
		}
		get_template_part( 'templates/header-1' );
	}
}
add_action( 'avada_header', 'avada_header_1', 20 );

if ( ! function_exists( 'avada_header_2' ) ) {
	function avada_header_2() {
		if ( ! in_array( Avada()->settings->get( 'header_layout' ), array( 'v4', 'v5' ) ) ) {
			return;
		}
		get_template_part( 'templates/header-2' );
	}
}
add_action( 'avada_header', 'avada_header_2', 20 );


add_action( 'avada_header', 'avada_header_3', 10 );
if ( ! function_exists( 'avada_header_3' ) ) {
	function avada_header_3() {
		if ( Avada()->settings->get( 'header_layout' ) != 'v6' ) {
			return;
		}
		get_template_part( 'templates/header-3' );
	}
}

if ( ! function_exists( 'avada_secondary_main_menu' ) ) {
	function avada_secondary_main_menu() {
		if ( ! in_array( Avada()->settings->get( 'header_layout' ), array( 'v4', 'v5' ) ) ) {
			return;
		}
		get_template_part( 'templates/header-secondary-main-menu' );
	}
}
add_action( 'avada_header', 'avada_secondary_main_menu', 30 );

if ( ! function_exists( 'avada_logo' ) ) {
	function avada_logo() {
		// No need to proceed any further if no logo is set
		if ( '' == Avada()->settings->get( 'logo' ) && '' == Avada()->settings->get( 'logo_retina' ) ) {
			return;
		}
		get_template_part( 'templates/logo' );
	}
}

if ( ! function_exists( 'avada_main_menu' ) ) {
	function avada_main_menu( $flyout_menu = false ) {
		$sticky_menu = '';

		$main_menu_args = array(
			'theme_location'  => 'main_navigation',
			'depth'           => 5,
			'menu_class'      => 'fusion-menu',
			'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'fallback_cb'     => 'FusionCoreFrontendWalker::fallback',
			'walker'          => new FusionCoreFrontendWalker(),
			'container_class' => 'fusion-main-menu',
			'container' 	  => 'div'
		);

		if ( $flyout_menu ) {
			$flyout_menu_args = array(
				'depth'     => 1,
				'container' => false,
				'echo'      => false,
			);

			$main_menu_args = wp_parse_args( $flyout_menu_args, $main_menu_args );

			$main_menu = wp_nav_menu( $main_menu_args );

			return $main_menu;

		} else {

			wp_nav_menu( $main_menu_args );

			if ( has_nav_menu( 'sticky_navigation' ) && ( ! function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) || ( function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) && ! ubermenu_get_menu_instance_by_theme_location( 'sticky_navigation' ) ) ) ) {

				$sticky_menu_args = array(
					'theme_location'  => 'sticky_navigation',
					'container_class' => 'fusion-main-menu fusion-sticky-menu',
					'menu_id'		  => 'menu-main-menu-1',
				);

				$sticky_menu_args = wp_parse_args( $sticky_menu_args, $main_menu_args );

				wp_nav_menu( $sticky_menu_args );
			}

			// Make sure mobile menu is not loaded when we use slideout menu or ubermenu
			if ( ! function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) || ( function_exists( 'ubermenu_get_menu_instance_by_theme_location' ) && ! ubermenu_get_menu_instance_by_theme_location( 'main_navigation' ) ) ) {
				avada_mobile_main_menu();
			}
		}
	}
}

if ( ! function_exists( 'avada_default_menu_fallback' ) ) {
	function avada_default_menu_fallback( $args ) {
		return null;
	}
}

if ( ! function_exists( 'avada_contact_info' ) ) {
	function avada_contact_info() {
		$phone_number    = do_shortcode( Avada()->settings->get( 'header_number' ) );
		$email           = Avada()->settings->get( 'header_email' );
		$header_position = Avada()->settings->get( 'header_position' );

		$html = '';

		if ( $phone_number || $email ) {
			$html .= '<div class="fusion-contact-info">';
				$html .= $phone_number;
				if ( $phone_number && $email ) {
					if ( 'Top' == $header_position ) {
						$html .= '<span class="fusion-header-separator">' . apply_filters( 'avada_header_separator', '|' ) .'</span>';
					} else {
						$html .= '<br />';
					}
				}
				$html .= sprintf( apply_filters( 'avada_header_contact_info_email', '<a href="mailto:%s">%s</a>' ), $email, $email );
			$html .= '</div>';
		}
		return $html;
	}
}

if ( ! function_exists( 'avada_secondary_nav' ) ) {
	function avada_secondary_nav() {
		if ( has_nav_menu( 'top_navigation' ) ) {
			return wp_nav_menu( array(
				'theme_location'  => 'top_navigation',
				'depth'           => 5,
				'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				'container_class' => 'fusion-secondary-menu',
				'fallback_cb'     => 'FusionCoreFrontendWalker::fallback',
				'walker'          => new FusionCoreFrontendWalker(),
				'echo'            => false
			) );
		}
	}
}

if ( ! function_exists( 'avada_header_social_links' ) ) {
	function avada_header_social_links() {
		global $social_icons;

		$options = array(
			'position'          => 'header',
			'icon_colors'       => Avada()->settings->get( 'header_social_links_icon_color'),
			'box_colors'        => Avada()->settings->get( 'header_social_links_box_color', 'rgba' ),
			'icon_boxed'        => Avada()->settings->get( 'header_social_links_boxed' ),
			'icon_boxed_radius' => Avada_Sanitize::size( Avada()->settings->get( 'header_social_links_boxed_radius' ) ),
			'tooltip_placement' => Avada()->settings->get( 'header_social_links_tooltip_placement' ),
			'linktarget'        => Avada()->settings->get( 'social_icons_new' )
		);

		$render_social_icons = $social_icons->render_social_icons( $options );
		$html = ( $render_social_icons ) ? '<div class="fusion-social-links-header">' . $render_social_icons . '</div>' : '';

		return $html;
	}
}

if ( ! function_exists( 'avada_secondary_header_content' ) ) {
	/**
	 * Get the secondary header content based on the content area
	 * @param  string $content_area Secondary header content area from theme optins
	 * @return string               Html for the content
	 */
	function avada_secondary_header_content( $content_area ) {
		if ( Avada()->settings->get( $content_area ) == 'Contact Info' ) {
			return avada_contact_info();
		} elseif ( Avada()->settings->get( $content_area ) == 'Social Links' ) {
			return avada_header_social_links();
		} elseif ( Avada()->settings->get( $content_area ) == 'Navigation' ) {
			$mobile_menu_wrapper = '';
			if ( has_nav_menu( 'top_navigation' ) ) {
				$mobile_menu_wrapper = '<div class="fusion-mobile-nav-holder"></div>';
			}
			return avada_secondary_nav() . $mobile_menu_wrapper;
		}
	}
}

if ( ! function_exists( 'avada_header_content_3' ) ) {
	function avada_header_content_3() {
		if ( 'v4' != Avada()->settings->get( 'header_layout' ) && Avada()->settings->get( 'header_position' ) == 'Top' ) {
			return;
		}

		$header_content_3 = Avada()->settings->get( 'header_v4_content' );
		$html = '';

		if ( 'Tagline' == $header_content_3 ) {
			$html .= avada_header_tagline();
		} elseif ( 'Tagline And Search' == $header_content_3 ) {
			if ( 'Top' == Avada()->settings->get( 'header_position' ) ) {
				if ( 'Right' == Avada()->settings->get( 'logo_alignment' ) ) {
					$html .= avada_header_tagline();
					$html .= '<div class="fusion-secondary-menu-search">' . get_search_form( false ) . '</div>';
				} else {
					$html .= '<div class="fusion-secondary-menu-search">' . get_search_form( false ) . '</div>';
					$html .= avada_header_tagline();
				}
			} else {
				$html .= avada_header_tagline();
				$html .= '<div class="fusion-secondary-menu-search">' . get_search_form( false ) . '</div>';
			}
		} elseif ( 'Search' == $header_content_3 ) {
			$html .= '<div class="fusion-secondary-menu-search">' . get_search_form( false ) . '</div>';
		} elseif ( 'Banner' == $header_content_3 ) {
			$html .= avada_header_banner();
		}

		echo '<div class="fusion-header-content-3-wrapper">' . $html . '</div>';
	}
}
if ( Avada()->settings->get( 'header_position' ) == 'Top' ) {
	add_action( 'avada_logo_append', 'avada_header_content_3', 10 );
}


if ( ! function_exists( 'avada_header_banner' ) ) {
	function avada_header_banner() {
		return '<div class="fusion-header-banner">' . do_shortcode( Avada()->settings->get( 'header_banner_code' ) ) . '</div>';
	}
}

if ( ! function_exists( 'avada_header_tagline' ) ) {
	function avada_header_tagline() {
		return '<h3 class="fusion-header-tagline">' . do_shortcode( Avada()->settings->get( 'header_tagline' ) ) . '</h3>';
	}
}

if ( ! function_exists( 'avada_modern_menu' ) ) {
	function avada_modern_menu() {
		ob_start();
		get_template_part( 'templates/menu-mobile-modern' );
		return ob_get_contents();
	}
}

if ( ! function_exists( 'avada_mobile_main_menu' ) ) {
	function avada_mobile_main_menu() {
		get_template_part( 'templates/menu-mobile-main' );
	}
}

// Omit closing PHP tag to avoid "Headers already sent" issues.
