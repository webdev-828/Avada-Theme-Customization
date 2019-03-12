<?php
/**
 * Fusion Framework
 *
 * WARNING: This file is part of the Fusion Core Framework.
 * Do not edit the core files.
 * Add any modifications necessary under a child theme.
 *
 * @version: 1.0.0
 * @package  Fusion/Template
 * @author   ThemeFusion
 * @link     http://theme-fusion.com
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

// Add the first level menu style dropdown to the menu fields
add_action( 'wp_nav_menu_item_custom_fields', 'avada_add_menu_button_fields', 10, 4 );
function avada_add_menu_button_fields( $item_id, $item, $depth, $args ) {
	$name  = 'menu-item-fusion-menu-style';
	?>
	<p class="description description-wide fusion-menu-style">
		<label for="<?php echo $name . '-' . $item_id; ?>>">
			<?php esc_attr_e( 'Menu First Level Style', 'Avada' ); ?><br />
			<select id="<?php echo $name . '-' . $item_id; ?>" class="widefat edit-menu-item-target" name="<?php echo $name . '[' . $item_id . ']'; ?>">
				<option value="" <?php selected( $item->fusion_menu_style, '' ); ?>><?php esc_attr_e( 'Default Style', 'Avada' ); ?></option>
				<option value="fusion-button-small" <?php selected( $item->fusion_menu_style, 'fusion-button-small' ); ?>><?php esc_attr_e( 'Button Small', 'Avada' ); ?></option>
				<option value="fusion-button-medium" <?php selected( $item->fusion_menu_style, 'fusion-button-medium' ); ?>><?php esc_attr_e( 'Button Medium', 'Avada' ); ?></option>
				<option value="fusion-button-large" <?php selected( $item->fusion_menu_style, 'fusion-button-large' ); ?>><?php esc_attr_e( 'Button Large', 'Avada' ); ?></option>
				<option value="fusion-button-xlarge" <?php selected( $item->fusion_menu_style, 'fusion-button-xlarge' ); ?>><?php esc_attr_e( 'Button xLarge', 'Avada' ); ?></option>
			</select>
		</label>
	</p>
	<p class="field-megamenu-icon description description-wide">
		<label for="edit-menu-item-megamenu-icon-<?php echo $item_id; ?>">
			<?php esc_attr_e( 'Menu Icon (use full font awesome name)', 'Avada' ); ?>
			<input type="text" id="edit-menu-item-megamenu-icon-<?php echo $item_id; ?>" class="widefat code edit-menu-item-megamenu-icon" name="menu-item-fusion-megamenu-icon[<?php echo $item_id; ?>]" value="<?php echo $item->fusion_megamenu_icon; ?>" />
		</label>
	</p>
<?php }

// Add the mega menu custom fields to the menu fields
if ( Avada()->settings->get( 'disable_megamenu' ) ) {
	add_action( 'wp_nav_menu_item_custom_fields', 'avada_add_megamenu_fields', 20, 4 );
}

function avada_add_megamenu_fields( $item_id, $item, $depth, $args ) { ?>
	<div class="clear"></div>
	<div class="fusion-mega-menu-options">
		<p class="field-megamenu-status description description-wide">
			<label for="edit-menu-item-megamenu-status-<?php echo $item_id; ?>">
				<input type="checkbox" id="edit-menu-item-megamenu-status-<?php echo $item_id; ?>" class="widefat code edit-menu-item-megamenu-status" name="menu-item-fusion-megamenu-status[<?php echo $item_id; ?>]" value="enabled" <?php checked( $item->fusion_megamenu_status, 'enabled' ); ?> />
				<strong><?php esc_attr_e( 'Enable Fusion Mega Menu (only for main menu)', 'Avada' ); ?></strong>
			</label>
		</p>
		<p class="field-megamenu-width description description-wide">
			<label for="edit-menu-item-megamenu-width-<?php echo $item_id; ?>">
				<input type="checkbox" id="edit-menu-item-megamenu-width-<?php echo $item_id; ?>" class="widefat code edit-menu-item-megamenu-width" name="menu-item-fusion-megamenu-width[<?php echo $item_id; ?>]" value="fullwidth" <?php checked( $item->fusion_megamenu_width, 'fullwidth' ); ?> />
				<?php esc_attr_e( 'Full Width Mega Menu (overrides column width)', 'Avada' ); ?>
			</label>
		</p>
		<p class="field-megamenu-columns description description-wide">
			<label for="edit-menu-item-megamenu-columns-<?php echo $item_id; ?>">
				<?php esc_attr_e( 'Mega Menu Number of Columns', 'Avada' ); ?>
				<select id="edit-menu-item-megamenu-columns-<?php echo $item_id; ?>" class="widefat code edit-menu-item-megamenu-columns" name="menu-item-fusion-megamenu-columns[<?php echo $item_id; ?>]">
					<option value="auto" <?php selected( $item->fusion_megamenu_columns, 'auto' ); ?>><?php esc_attr_e( 'Auto', 'Avada' ); ?></option>
					<option value="1" <?php selected( $item->fusion_megamenu_columns, '1' ); ?>>1</option>
					<option value="2" <?php selected( $item->fusion_megamenu_columns, '2' ); ?>>2</option>
					<option value="3" <?php selected( $item->fusion_megamenu_columns, '3' ); ?>>3</option>
					<option value="4" <?php selected( $item->fusion_megamenu_columns, '4' ); ?>>4</option>
					<option value="5" <?php selected( $item->fusion_megamenu_columns, '5' ); ?>>5</option>
					<option value="6" <?php selected( $item->fusion_megamenu_columns, '6' ); ?>>6</option>
				</select>
			</label>
		</p>
		<p class="field-megamenu-columnwidth description description-wide">
			<label for="edit-menu-item-megamenu-columnwidth-<?php echo $item_id; ?>">
				<?php esc_attr_e( 'Mega Menu Column Width (in percentage, ex: 30%)', 'Avada' ); ?>
				<input type="text" id="edit-menu-item-megamenu-columnwidth-<?php echo $item_id; ?>" class="widefat code edit-menu-item-megamenu-columnwidth" name="menu-item-fusion-megamenu-columnwidth[<?php echo $item_id; ?>]" value="<?php echo $item->fusion_megamenu_columnwidth; ?>" />
			</label>
		</p>
		<p class="field-megamenu-title description description-wide">
			<label for="edit-menu-item-megamenu-title-<?php echo $item_id; ?>">
				<input type="checkbox" id="edit-menu-item-megamenu-title-<?php echo $item_id; ?>" class="widefat code edit-menu-item-megamenu-title" name="menu-item-fusion-megamenu-title[<?php echo $item_id; ?>]" value="disabled" <?php checked( $item->fusion_megamenu_title, 'disabled' ); ?> />
				<?php esc_attr_e( 'Disable Mega Menu Column Title', 'Avada' ); ?>
			</label>
		</p>
		<p class="field-megamenu-widgetarea description description-wide">
			<label for="edit-menu-item-megamenu-widgetarea-<?php echo $item_id; ?>">
				<?php esc_attr_e( 'Mega Menu Widget Area', 'Avada' ); ?>
				<select id="edit-menu-item-megamenu-widgetarea-<?php echo $item_id; ?>" class="widefat code edit-menu-item-megamenu-widgetarea" name="menu-item-fusion-megamenu-widgetarea[<?php echo $item_id; ?>]">
					<option value="0"><?php esc_attr_e( 'Select Widget Area', 'Avada' ); ?></option>
					<?php global $wp_registered_sidebars; ?>
					<?php if ( ! empty( $wp_registered_sidebars ) && is_array( $wp_registered_sidebars ) ) : ?>
						<?php foreach ( $wp_registered_sidebars as $sidebar ) : ?>
							<option value="<?php echo $sidebar['id']; ?>" <?php selected( $item->fusion_megamenu_widgetarea, $sidebar['id'] ); ?>><?php echo $sidebar['name']; ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</label>
		</p>
		<a href="#" id="fusion-media-upload-<?php echo $item_id; ?>" class="fusion-open-media button button-primary fusion-megamenu-upload-thumbnail"><?php esc_attr_e( 'Set Thumbnail', 'Avada' ); ?></a>
		<p class="field-megamenu-thumbnail description description-wide">
			<label for="edit-menu-item-megamenu-thumbnail-<?php echo $item_id; ?>">
				<input type="hidden" id="edit-menu-item-megamenu-thumbnail-<?php echo $item_id; ?>" class="fusion-new-media-image widefat code edit-menu-item-megamenu-thumbnail" name="menu-item-fusion-megamenu-thumbnail[<?php echo $item_id; ?>]" value="<?php echo $item->fusion_megamenu_thumbnail; ?>" />
				<img src="<?php echo $item->fusion_megamenu_thumbnail; ?>" id="fusion-media-img-<?php echo $item_id; ?>" class="fusion-megamenu-thumbnail-image" style="<?php echo ( trim( $item->fusion_megamenu_thumbnail ) ) ? 'display:inline;' : ''; ?>" />
				<a href="#" id="fusion-media-remove-<?php echo $item_id; ?>" class="remove-fusion-megamenu-thumbnail" style="<?php echo ( trim( $item->fusion_megamenu_thumbnail ) ) ? 'display:inline;' : ''; ?>"><?php esc_attr_e( 'Remove Image', 'Avada' ); ?></a>
			</label>
		</p>
	</div><!-- .fusion-mega-menu-options-->
	<?php
}

// Dont duplicate me!
if ( ! class_exists( 'FusionCoreFrontendWalker' ) ) {
	class FusionCoreFrontendWalker extends Walker_Nav_Menu {

		/**
		 * @var string $menu_style do we use default styling or a button
		 */
		private $menu_style = '';

		/**
		 * @var string $menu_megamenu_status are we currently rendering a mega menu?
		 */
		private $menu_megamenu_status = '';

		/**
		 * @var string $menu_megamenu_width use full width mega menu?
		 */
		private $menu_megamenu_width = '';

		/**
		 * @var int $num_of_columns how many columns should the mega menu have?
		 */
		private $num_of_columns = 0;

		/**
		 * @var int $max_num_of_columns mega menu allow for 6 columns at max
		 */
		private $max_num_of_columns = 6;

		/**
		 * @var int $total_num_of_columns total number of columns for a single megamenu?
		 */
		private $total_num_of_columns = 0;

		/**
		 * @var int $num_of_rows number of rows in the mega menu
		 */
		private $num_of_rows = 1;

		/**
		 * @var array $submenu_matrix holds number of columns per row
		 */
		private $submenu_matrix = array();

		/**
		 * @var int|string $menu_megamenu_columnwidth how large is the width of a column?
		 */
		private $menu_megamenu_columnwidth = 0;

		/**
		 * @var array $menu_megamenu_rowwidth_matrix how large is the width of each row?
		 */
		private $menu_megamenu_rowwidth_matrix = array();

		/**
		 * @var int $menu_megamenu_maxwidth how large is the overall width of a column?
		 */
		private $menu_megamenu_maxwidth = 0;

		/**
		 * @var string $menu_megamenu_title should a colum title be displayed?
		 */
		private $menu_megamenu_title = '';

		/**
		 * @var string $menu_megamenu_widget_area should one column be a widget area?
		 */
		private $menu_megamenu_widget_area = '';

		/**
		 * @var string $menu_megamenu_icon does the item have an icon?
		 */
		private $menu_megamenu_icon = '';

		/**
		 * @var string $menu_megamenu_thumbnail does the item have a thumbnail?
		 */
		private $menu_megamenu_thumbnail = '';

		/**
		 * Sets the overall width of the megamenu wrappers
		 */
		private function set_megamenu_max_width() {

			// Set overall width of megamenu
			$site_width         = (int) str_replace( 'px', '', Avada()->settings->get( 'site_width' ) );
			$megamenu_max_width = (int) Avada()->settings->get( 'megamenu_max_width' );
			$megmanu_width      = 0;

			$megamenu_width = $megamenu_max_width;
			// Site width in px
			if ( false !== strpos( Avada()->settings->get( 'site_width' ), 'px' ) ) {
				$megamenu_width = $site_width;
				if ( $site_width > $megamenu_max_width && 0 < $megamenu_max_width ) {
					$megamenu_width = $megamenu_max_width;
				}
			}
			$this->menu_megamenu_maxwidth = $megamenu_width;
		}

		/**
		 * @see Walker::start_lvl()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int $depth Depth of page. Used for padding.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {

			if ( 0 === $depth && 'enabled' == $this->menu_megamenu_status ) {
				// set overall width of megamenu
				if ( ! $this->menu_megamenu_maxwidth ) {
					$this->set_megamenu_max_width();
				}
				$output .= '{first_level}';
				$output .= '<div class="fusion-megamenu-holder" {megamenu_final_width}><ul class="fusion-megamenu {megamenu_border}">';
			} elseif ( 2 <= $depth && 'enabled' == $this->menu_megamenu_status ) {
				$output .= '<ul class="sub-menu deep-level">';
			} else {
				$output .= '<ul class="sub-menu">';
			}

		}

		/**
		 * @see Walker::end_lvl()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int $depth Depth of page. Used for padding.
		 */
		public function end_lvl( &$output, $depth = 0, $args = array() ) {

			$row_width = '';

			if ( 0 === $depth && 'enabled' == $this->menu_megamenu_status ) {

				$output .= '</ul></div><div style="clear:both;"></div></div></div>';

				if ( $this->total_num_of_columns < $this->max_num_of_columns ) {
					$col_span = ' col-span-' . $this->total_num_of_columns * 2;
				} else {
					$col_span = ' col-span-' . $this->max_num_of_columns * 2;
				}

				if ( 'fullwidth' == $this->menu_megamenu_width ) {
					$col_span = ' col-span-12 fusion-megamenu-fullwidth';
					// overall megamenu wrapper width in px is max width for fullwidth megamenu
					$wrapper_width = $this->menu_megamenu_maxwidth;
				} else {
					// calc overall megamenu wrapper width in px
					$wrapper_width = max( $this->menu_megamenu_rowwidth_matrix ) * $this->menu_megamenu_maxwidth;
				}

				$output = str_replace( '{first_level}', '<div class="fusion-megamenu-wrapper {fusion_columns} columns-' . $this->total_num_of_columns . $col_span . '" data-maxwidth="' . $this->menu_megamenu_maxwidth . '"><div class="row">', $output );

				$output = str_replace( '{megamenu_final_width}', 'style="width:' . $wrapper_width . 'px;" data-width="' . $wrapper_width . '"', $output );

				if ( $this->total_num_of_columns > $this->max_num_of_columns ) {
					$output = str_replace( '{megamenu_border}', 'fusion-megamenu-border', $output );
				} else {
					$output = str_replace( '{megamenu_border}', '', $output );
				}

				foreach ( $this->submenu_matrix as $row => $columns ) {
					$layout_columns = 12 / $columns;
					$layout_columns = ( '5' == $columns ) ? 2 : $layout_columns;


					if ( $columns < $this->max_num_of_columns ) {
						$row_width = 'style="width:' . $columns / $this->max_num_of_columns * 100 . '% !important;"';
					}

					$output = str_replace( '{row_width_' . $row . '}', $row_width, $output );

					if ( ( $row - 1 ) * $this->max_num_of_columns + $columns < $this->total_num_of_columns ) {
						$output = str_replace( '{row_number_' . $row . '}', 'fusion-megamenu-row-columns-' . $columns . ' fusion-megamenu-border', $output );
					} else {
						$output = str_replace( '{row_number_' . $row . '}', 'fusion-megamenu-row-columns-' . $columns, $output );
					}
					$output = str_replace( '{current_row_' . $row . '}', 'fusion-megamenu-columns-' . $columns . ' col-lg-' . $layout_columns . ' col-md-' . $layout_columns . ' col-sm-' . $layout_columns, $output );

					$output = str_replace( '{fusion_columns}', 'fusion-columns-' . $columns . ' columns-per-row-' . $columns, $output );
				}
			} else {
				$output .= '</ul>';
			}
		}

		/**
		 * @see Walker::start_el()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param array $args
		 * @param int $id Menu item ID.
		 * @param object $args
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

			$item_output = $class_columns = '';

			// Set some vars
			// Megamenu is enabled
			if ( Avada()->settings->get( 'disable_megamenu' ) ) {
				if ( 0 === $depth ) {
					$this->menu_megamenu_status = get_post_meta( $item->ID, '_menu_item_fusion_megamenu_status', true );
					$this->menu_megamenu_width  = get_post_meta( $item->ID, '_menu_item_fusion_megamenu_width', true );
					$allowed_columns = get_post_meta( $item->ID, '_menu_item_fusion_megamenu_columns', true );
					if ( 'auto' != $allowed_columns ) {
						$this->max_num_of_columns = $allowed_columns;
					}
					$this->num_of_columns                                      = $this->total_num_of_columns = 0;
					$this->num_of_rows                                         = 1;
					$this->menu_megamenu_rowwidth_matrix                       = array();
					$this->menu_megamenu_rowwidth_matrix[ $this->num_of_rows ] = 0;
				}

				$this->menu_style               = get_post_meta( $item->ID, '_menu_item_fusion_menu_style', true );
				$this->menu_megamenu_title      = get_post_meta( $item->ID, '_menu_item_fusion_megamenu_title', true );
				$this->menu_megamenu_widgetarea = get_post_meta( $item->ID, '_menu_item_fusion_megamenu_widgetarea', true );
				$this->menu_megamenu_icon       = get_post_meta( $item->ID, '_menu_item_fusion_megamenu_icon', true );
				$this->menu_megamenu_thumbnail  = get_post_meta( $item->ID, '_menu_item_fusion_megamenu_thumbnail', true );
			// Megamenu is disabled
			} else {
				$this->menu_megamenu_status = 'disabled';
				$this->menu_style           = get_post_meta( $item->ID, '_menu_item_fusion_menu_style', true );
				$this->menu_megamenu_icon   = get_post_meta( $item->ID, '_menu_item_fusion_megamenu_icon', true );
			}
			// We are inside a megamenu
			if ( 1 === $depth && 'enabled' == $this->menu_megamenu_status ) {

				if ( get_post_meta( $item->ID, '_menu_item_fusion_megamenu_columnwidth', true ) ) {
					$this->menu_megamenu_columnwidth = get_post_meta( $item->ID, '_menu_item_fusion_megamenu_columnwidth', true );
				} else {
					if ( 'fullwidth' == $this->menu_megamenu_width ) {
						$this->menu_megamenu_columnwidth = 100 / $this->max_num_of_columns . '%';
					} else if ( $this->max_num_of_columns == '1' ) {
						$this->menu_megamenu_columnwidth = '100%';
					} else {
						$this->menu_megamenu_columnwidth = '16.6666%';
					}
					//$this->menu_megamenu_columnwidth = 100 / $this->max_num_of_columns . '%';
				}

				$this->num_of_columns++;
				$this->total_num_of_columns++;

				// check if we need to start a new row
				if ( $this->num_of_columns > $this->max_num_of_columns ) {
					$this->num_of_columns = 1;
					$this->num_of_rows++;

					// start new row width calculation
					$this->menu_megamenu_rowwidth_matrix[ $this->num_of_rows ] =  floatval( $this->menu_megamenu_columnwidth ) / 100;

					$output .= '</ul><ul class="fusion-megamenu fusion-megamenu-row-' . $this->num_of_rows . ' {row_number_' . $this->num_of_rows . '}" {row_width_' . $this->num_of_rows . '}>';
				} else {
					$this->menu_megamenu_rowwidth_matrix[ $this->num_of_rows ] +=  floatval( $this->menu_megamenu_columnwidth ) / 100;
				}

				$this->submenu_matrix[ $this->num_of_rows ] = $this->num_of_columns;

				if ( $this->max_num_of_columns < $this->num_of_columns ) {
					$this->max_num_of_columns = $this->num_of_columns;
				}

				$title = apply_filters( 'the_title', $item->title, $item->ID );

				if ( ! ( ( empty( $item->url ) || '#' == $item->url || 'http://' == $item->url )  && 'disabled' == $this->menu_megamenu_title ) ) {
					$heading      = do_shortcode($title);
					$link         = '';
					$link_closing = '';
					$target = '';

					if ( ! empty( $item->url ) && '#' != $item->url && 'http://' != $item->url ) {
						
						if ( ! empty( $item->target ) ) {
							$target = ' target="' . $item->target . '"';
						}
						
						$link         = '<a href="' . $item->url . '"' . $target . '>';
						$link_closing = '</a>';
					}

					/* check if we need to set an image */
					$title_enhance = '';
					if ( ! empty( $this->menu_megamenu_thumbnail ) ) {
						$title_enhance = '<span class="fusion-megamenu-icon"><img src="' . $this->menu_megamenu_thumbnail . '"></span>';
					} elseif ( ! empty( $this->menu_megamenu_icon ) ) {
						$title_enhance = '<span class="fusion-megamenu-icon"><i class="fa glyphicon ' . avada_font_awesome_name_handler( $this->menu_megamenu_icon) . '"></i></span>';
					} elseif ( 'disabled' == $this->menu_megamenu_title ) {
						$title_enhance = '<span class="fusion-megamenu-bullet"></span>';
					}

					$heading = $link . $title_enhance . $title . $link_closing;

					if ( 'disabled' != $this->menu_megamenu_title ) {
						$item_output .= "<div class='fusion-megamenu-title'>" . $heading . "</div>";
					} else {
						$item_output .= $heading;
					}
				}

				if ( $this->menu_megamenu_widgetarea && is_active_sidebar( $this->menu_megamenu_widgetarea ) ) {
					ob_start();
					dynamic_sidebar( $this->menu_megamenu_widgetarea );
					$item_output .= '<div class="fusion-megamenu-widgets-container second-level-widget">' . ob_get_clean() . '</div>';
				}

				$class_columns  = ' {current_row_' . $this->num_of_rows . '}';

			} else if ( 2 === $depth && 'enabled' == $this->menu_megamenu_status && $this->menu_megamenu_widgetarea ) {

				if ( is_active_sidebar( $this->menu_megamenu_widgetarea ) ) {
					ob_start();
					dynamic_sidebar( $this->menu_megamenu_widgetarea );
					$item_output .= '<div class="fusion-megamenu-widgets-container third-level-widget">' . ob_get_clean() . '</div>';
				}

			} else {

				$atts = array();
				$atts['title']  = ! empty( $item->attr_title ) ? esc_attr( $item->attr_title ) : '';
				$atts['target'] = ! empty( $item->target )     ? esc_attr( $item->target )     : '';
				$atts['rel']    = ! empty( $item->xfn )        ? esc_attr( $item->xfn )        : '';
				$atts['href']   = ! empty( $item->url )        ? esc_attr( $item->url )        : '';

				$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

				$attributes = '';
				foreach ( $atts as $attr => $value ) {
					if ( ! empty( $value ) ) {
						$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
						$attributes .= ' ' . $attr . '="' . $value . '"';
					}
				}

				$item_output .= $args->before . '<a ' . $attributes . '>';

				// For right side header add the caret icon at the beginning
				if ( 0 === $depth && $args->has_children && Avada()->settings->get( 'menu_display_dropdown_indicator' ) && 'v6' != Avada()->settings->get( 'header_layout' ) && 'Right' == Avada()->settings->get( 'header_position' ) ) {
					$item_output .= ' <span class="fusion-caret"><i class="fusion-dropdown-indicator"></i></span>';
				}

				// Check if we need to set an image
				$icon_wrapper_class = 'fusion-megamenu-icon';
				if ( 0 === $depth && $this->menu_style ) {
					$icon_wrapper_class = ( is_rtl() ) ? 'button-icon-divider-right' : 'button-icon-divider-left';
				}

				$icon = '';
				if ( ! empty( $this->menu_megamenu_thumbnail ) && 'enabled' == $this->menu_megamenu_status ) {
					$icon = '<span class="' . $icon_wrapper_class . ' fusion-megamenu-image"><img src="' . $this->menu_megamenu_thumbnail . '"></span>';
				} elseif ( ! empty( $this->menu_megamenu_icon ) ) {
					$icon = '<span class="' . $icon_wrapper_class . '"><i class="fa glyphicon ' . $this->menu_megamenu_icon . '"></i></span>';
				} elseif ( 0 !== $depth && 'enabled' == $this->menu_megamenu_status ) {
					$icon = '<span class="fusion-megamenu-bullet"></span>';
				}

				$classes = '';
				// Check if we have a menu button
				if ( 0 === $depth ) {
					$classes = 'menu-text';
					if ( $this->menu_style ) {
						$classes .= ' fusion-button button-default ' . str_replace( 'fusion-', '', $this->menu_style );
						// Button should have 3D effect
						if ( '3d' == Avada()->settings->get( 'button_type' ) ) {
							$classes .= ' button-3d';
						}
					}

					$item_output .=  '<span class="' . $classes . '">' . $icon;
				// Normal menu item
				} else {
					$item_output .=  $icon . '<span class="' . $classes . '">';
				}

				$title = $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;

				if ( false !== strpos( $icon, 'button-icon-divider-left' ) ) {
					$title = '<span class="fusion-button-text-left">' . $title . '</span>';
				} elseif ( false !== strpos( $icon, 'button-icon-divider-right' ) ) {
					$title = '<span class="fusion-button-text-right">' . $title . '</span>';
				}

				$item_output .= $title;
				$item_output .=  '</span>';

				// For top header and left side header add the caret icon at the end
				if ( 0 === $depth && $args->has_children && Avada()->settings->get( 'menu_display_dropdown_indicator' ) && 'v6' != Avada()->settings->get( 'header_layout' ) && 'Right' != Avada()->settings->get( 'header_position' ) ) {
					$item_output .= ' <span class="fusion-caret"><i class="fusion-dropdown-indicator"></i></span>';
				}

				$item_output .= '</a>' . $args->after;

			}

			// check if we need to apply a divider
			if ( 'enabled' != $this->menu_megamenu_status && ( ( 0 == strcasecmp( $item->attr_title, 'divider' ) ) || ( 0 == strcasecmp( $item->title, 'divider' ) ) ) ) {

				$output .= '<li role="presentation" class="divider">';

			} else {

				$class_names  = '';
				$column_width = '';
				$classes      = empty( $item->classes ) ? array() : (array) $item->classes;
				$classes[]    = 'menu-item-' . $item->ID;

				$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );

				if ( 0 === $depth && $args->has_children ) {
					$class_names .= ( 'enabled' == $this->menu_megamenu_status ) ? ' fusion-megamenu-menu' : ' fusion-dropdown-menu';
				}

				if ( 0 === $depth && $this->menu_style ) {
					$class_names .= ' fusion-menu-item-button';
				}

				if ( 1 === $depth ) {
					if ( 'enabled' == $this->menu_megamenu_status ) {
						$class_names .= ' fusion-megamenu-submenu';

						if ( 'disabled' == $this->menu_megamenu_title ) {
							$class_names .= ' fusion-megamenu-submenu-notitle';
						}

						if ( 'fullwidth' != $this->menu_megamenu_width ) {
							$width        = $this->menu_megamenu_maxwidth * floatval( $this->menu_megamenu_columnwidth ) / 100;
							$column_width = 'style="width:' . $width . 'px;max-width:' . $width . 'px;" data-width="' . $width . '"';
						}
					} else {
						$class_names .= ' fusion-dropdown-submenu';
					}
				}

				$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . $class_columns . '"' : '';

				$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
				$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

				$output .= '<li ' . $id . ' ' . $class_names . ' ' . $column_width . ' >';

				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
			}
		}

		/**
		 * @see Walker::end_el()
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item Page data object. Not used.
		 * @param int $depth Depth of page. Not Used.
		 */
		function end_el( &$output, $item, $depth = 0, $args = array() ) {
			$output .= '</li>';
		}

		/**
		 * Traverse elements to create list from elements.
		 *
		 * Display one element if the element doesn't have any children otherwise,
		 * display the element and its children. Will only traverse up to the max
		 * depth and no ignore elements under that depth.
		 *
		 * This method shouldn't be called directly, use the walk() method instead.
		 *
		 * @see Walker::start_el()
		 * @since 2.5.0
		 *
		 * @param object $element Data object
		 * @param array $children_elements List of elements to continue traversing.
		 * @param int $max_depth Max depth to traverse.
		 * @param int $depth Depth of current element.
		 * @param array $args
		 * @param string $output Passed by reference. Used to append additional content.
		 * @return null Null on failure with no changes to parameters.
		 */
		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
			if ( ! $element ) {
				return;
			}

			$id_field = $this->db_fields['id'];

			// Display this element.
			if ( is_object( $args[0] ) )
			   $args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );

			parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}

		/**
		 * Menu Fallback
		 * =============
		 * If this function is assigned to the wp_nav_menu's fallback_cb variable
		 * and a manu has not been assigned to the theme location in the WordPress
		 * menu manager the function with display nothing to a non-logged in user,
		 * and will add a link to the WordPress menu manager if logged in as an admin.
		 *
		 * @param array $args passed from the wp_nav_menu function.
		 *
		 */
		public static function fallback( $args ) {
			if ( current_user_can( 'manage_options' ) ) {
				return null;
			}
		}
	}  // end FusionCoreFrontendWalker() class
}

// Don't duplicate me!
if ( ! class_exists( 'FusionCoreMegaMenus' ) ) {

	class FusionCoreMegaMenus extends Walker_Nav_Menu {

		/**
		 * Starts the list before the elements are added.
		 *
		 * @see Walker_Nav_Menu::start_lvl()
		 *
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args   Not used.
		 */
		function start_lvl( &$output, $depth = 0, $args = array() ) {}

		/**
		 * Ends the list of after the elements are added.
		 *
		 * @see Walker_Nav_Menu::end_lvl()
		 *
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args   Not used.
		 */
		function end_lvl( &$output, $depth = 0, $args = array() ) {}

		/**
		 * Start the element output.
		 *
		 * @see Walker_Nav_Menu::start_el()
		 * @since 3.0.0
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param object $item   Menu item data object.
		 * @param int    $depth  Depth of menu item. Used for padding.
		 * @param array  $args   Not used.
		 * @param int    $id     Not used.
		 */
		function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			global $_wp_nav_menu_max_depth, $wp_registered_sidebars;
			$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

			ob_start();
			$item_id = esc_attr( $item->ID );
			$removed_args = array(
				'action',
				'customlink-tab',
				'edit-menu-item',
				'menu-item',
				'page-tab',
				'_wpnonce',
			);

			$original_title = '';
			if ( 'taxonomy' == $item->type ) {
				$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
				if ( is_wp_error( $original_title ) )
					$original_title = false;
			} elseif ( 'post_type' == $item->type ) {
				$original_object = get_post( $item->object_id );
				$original_title  = get_the_title( $original_object->ID );
			}

			$classes = array(
				'menu-item menu-item-depth-' . $depth,
				'menu-item-' . esc_attr( $item->object ),
				'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive' ),
			);

			$title = $item->title;

			if ( ! empty( $item->_invalid ) ) {
				$classes[] = 'menu-item-invalid';
				/* translators: %s: title of menu item which is invalid */
				$title = sprintf( esc_html__( '%s (Invalid)', 'Avada' ), $item->title );
			} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
				$classes[] = 'pending';
				/* translators: %s: title of menu item in draft status */
				$title = sprintf( esc_html__( '%s (Pending)', 'Avada' ), $item->title );
			}

			$title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

			$submenu_text = '';
			if ( 0 == $depth ) {
				$submenu_text = 'style="display:none;"';
			}

			?>
			<li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode( ' ', $classes ); ?>">
				<dl class="menu-item-bar">
					<dt class="menu-item-handle">
						<span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span class="is-submenu" <?php echo $submenu_text; ?>><?php esc_attr_e( 'sub item' , 'Avada' ); ?></span></span>
						<span class="item-controls">
							<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
							<span class="item-order hide-if-js">
								<a href="<?php echo esc_url( wp_nonce_url(
									add_query_arg(
										array( 'action' => 'move-up-menu-item', 'menu-item' => $item_id, ),
										remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								) ); ?>" class="item-move-up"><abbr title="<?php esc_attr_e( 'Move up', 'Avada' ); ?>">&#8593;</abbr></a>
								|
								<a href="<?php echo esc_url( wp_nonce_url(
									add_query_arg(
										array( 'action' => 'move-down-menu-item', 'menu-item' => $item_id, ),
										remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								) ); ?>" class="item-move-down"><abbr title="<?php esc_attr_e( 'Move down', 'Avada' ); ?>">&#8595;</abbr></a>
							</span>
							<a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e( 'Edit Menu Item', 'Avada' ); ?>" href="<?php echo esc_url( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ?
								admin_url( 'nav-menus.php' ) :
								add_query_arg(
									'edit-menu-item', $item_id, remove_query_arg(
										$removed_args,
										admin_url( 'nav-menus.php#menu-item-settings-' . $item_id )
									)
								)
							); ?>"><?php esc_attr_e( 'Edit Menu Item', 'Avada' ); ?></a>
						</span>
					</dt>
				</dl>

				<div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
					<?php if ( 'custom' == $item->type ) : ?>
						<p class="field-url description description-wide">
							<label for="edit-menu-item-url-<?php echo $item_id; ?>">
								<?php esc_attr_e( 'URL', 'Avada' ); ?><br />
								<input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
							</label>
						</p>
					<?php endif; ?>
					<p class="description description-thin">
						<label for="edit-menu-item-title-<?php echo $item_id; ?>">
							<?php esc_attr_e( 'Navigation Label', 'Avada' ); ?><br />
							<input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
						</label>
					</p>
					<p class="description description-thin">
						<label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
							<?php esc_attr_e( 'Title Attribute', 'Avada' ); ?><br />
							<input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
						</label>
					</p>
					<p class="field-link-target description">
						<label for="edit-menu-item-target-<?php echo $item_id; ?>">
							<input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
							<?php esc_attr_e( 'Open link in a new window/tab', 'Avada' ); ?>
						</label>
					</p>
					<p class="field-css-classes description description-thin">
						<label for="edit-menu-item-classes-<?php echo $item_id; ?>">
							<?php esc_attr_e( 'CSS Classes (optional)', 'Avada' ); ?><br />
							<input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode( ' ', $item->classes ) ); ?>" />
						</label>
					</p>
					<p class="field-xfn description description-thin">
						<label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
							<?php esc_attr_e( 'Link Relationship (XFN)', 'Avada' ); ?><br />
							<input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
						</label>
					</p>
					<p class="field-description description description-wide">
						<label for="edit-menu-item-description-<?php echo $item_id; ?>">
							<?php esc_attr_e( 'Description', 'Avada' ); ?><br />
							<textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
							<span class="description"><?php esc_attr_e( 'The description will be displayed in the menu if the current theme supports it.', 'Avada' ); ?></span>
						</label>
					</p>

					<?php do_action( 'wp_nav_menu_item_custom_fields', $item_id, $item, $depth, $args ); ?>

					<p class="field-move hide-if-no-js description description-wide">
						<label>
							<span><?php esc_attr_e( 'Move', 'Avada' ); ?></span>
							<a href="#" class="menus-move menus-move-up" data-dir="up"><?php esc_attr_e( 'Up one', 'Avada' ); ?></a>
							<a href="#" class="menus-move menus-move-down" data-dir="down"><?php esc_attr_e( 'Down one', 'Avada' ); ?></a>
							<a href="#" class="menus-move menus-move-left" data-dir="left"></a>
							<a href="#" class="menus-move menus-move-right" data-dir="right"></a>
							<a href="#" class="menus-move menus-move-top" data-dir="top"><?php esc_attr_e( 'To the top', 'Avada' ); ?></a>
						</label>
					</p>

					<div class="menu-item-actions description-wide submitbox">
						<?php if ( 'custom' != $item->type && false !== $original_title ) : ?>
							<p class="link-to-original">
								<?php printf( esc_html__( 'Original: %s', 'Avada' ), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
							</p>
						<?php endif; ?>
						<a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php echo esc_url( wp_nonce_url(
							add_query_arg(
								array( 'action' => 'delete-menu-item', 'menu-item' => $item_id, ),
								admin_url( 'nav-menus.php' )
							),
							'delete-menu_item_' . $item_id
						) ); ?>"><?php esc_attr_e( 'Remove', 'Avada' ); ?></a> <span class="meta-sep hide-if-no-js"> | </span>
						<a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url(
							add_query_arg(
								array( 'edit-menu-item' => $item_id, 'cancel' => time() ),
								admin_url( 'nav-menus.php' )
							) ); ?>#menu-item-settings-<?php echo $item_id; ?>"><?php esc_attr_e( 'Cancel', 'Avada' ); ?></a>
					</div>

					<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
					<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
					<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
					<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
					<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
					<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
				</div><!-- .menu-item-settings-->
				<ul class="menu-item-transport"></ul>
			<?php
			$output .= ob_get_clean();
		}

	} // end FusionCoreMegaMenus() class

}


// Don't duplicate me!
if ( ! class_exists( 'FusionMegaMenu' ) ) {

	/**
	 * Class to manipulate menus
	 *
	 * @since 3.4
	 */
	class FusionMegaMenu extends FusionMegaMenuFramework {

		function __construct() {
			add_action( 'wp_update_nav_menu_item', array( $this, 'save_custom_menu_style_fields' ), 10, 3 );
			add_filter( 'wp_setup_nav_menu_item', array( $this, 'add_menu_style_data_to_menu' ) );
			if ( Avada()->settings->get( 'disable_megamenu' ) ) {
				add_filter( 'wp_setup_nav_menu_item', array( $this, 'add_megamenu_data_to_menu' ) );
				add_action( 'wp_update_nav_menu_item', array( $this, 'save_custom_megamenu_fields' ), 20, 3 );
			}
			add_filter( 'wp_edit_nav_menu_walker', array( $this, 'add_custom_fields' ) );
		}


		/**
		 * Function to replace normal edit nav walker for fusion core mega menus
		 *
		 * @return string Class name of new navwalker
		 */
		function add_custom_fields() {
			return 'FusionCoreMegaMenus';
		}

		/**
		 * Add the custom menu style fields menu item data to fields in database
		 *
		 * @return void
		 */
		function save_custom_menu_style_fields( $menu_id, $menu_item_db_id, $args ) {
			$field_names = array( 'menu-item-fusion-megamenu-icon' );
			if ( ! $args['menu-item-parent-id'] ) {
				$field_names = array( 'menu-item-fusion-menu-style', 'menu-item-fusion-megamenu-icon' );
			}

			foreach ( $field_names as $name ) {
				if ( ! isset( $_REQUEST[ $name ][ $menu_item_db_id ] ) ) {
					$_REQUEST[ $name ][ $menu_item_db_id ] = '';
				}
				$value = $_REQUEST[ $name ][ $menu_item_db_id ];
				update_post_meta( $menu_item_db_id, '_' . str_replace( '-', '_', $name ), $value );
			}
		}

		/**
		 * Add custom menu style fields data to the menu
		 *
		 * @return object the menu item
		 */
		function add_menu_style_data_to_menu( $menu_item ) {
			if ( ! $menu_item->menu_item_parent ) {
				$menu_item->fusion_menu_style = get_post_meta( $menu_item->ID, '_menu_item_fusion_menu_style', true );
			}

			$menu_item->fusion_megamenu_icon = get_post_meta( $menu_item->ID, '_menu_item_fusion_megamenu_icon', true );

			return $menu_item;
		}


		/**
		 * Add the custom megamenu fields menu item data to fields in database
		 *
		 * @return void
		 */
		function save_custom_megamenu_fields( $menu_id, $menu_item_db_id, $args ) {

			$field_name_suffix = array( 'title', 'widgetarea', 'columnwidth', 'icon', 'thumbnail' );
			if ( ! $args['menu-item-parent-id'] ) {
				$field_name_suffix = array( 'status', 'width', 'columns', 'columnwidth', 'icon', 'thumbnail' );
			}

			foreach ( $field_name_suffix as $key ) {
				if ( ! isset( $_REQUEST[ 'menu-item-fusion-megamenu-' . $key ][ $menu_item_db_id ] ) ) {
					$_REQUEST[ 'menu-item-fusion-megamenu-' . $key ][ $menu_item_db_id ] = '';
				}
				$value = $_REQUEST[ 'menu-item-fusion-megamenu-' . $key ][ $menu_item_db_id ];
				update_post_meta( $menu_item_db_id, '_menu_item_fusion_megamenu_' . $key, $value );
			}
		}

		/**
		 * Add custom megamenu fields data to the menu
		 *
		 * @return object the menu item
		 */
		function add_megamenu_data_to_menu( $menu_item ) {

			if ( ! $menu_item->menu_item_parent ) {

				$menu_item->fusion_megamenu_status  = get_post_meta( $menu_item->ID, '_menu_item_fusion_megamenu_status', true );
				$menu_item->fusion_megamenu_width   = get_post_meta( $menu_item->ID, '_menu_item_fusion_megamenu_width', true );
				$menu_item->fusion_megamenu_columns = get_post_meta( $menu_item->ID, '_menu_item_fusion_megamenu_columns', true );

			} else {

				$menu_item->fusion_megamenu_title      = get_post_meta( $menu_item->ID, '_menu_item_fusion_megamenu_title', true );
				$menu_item->fusion_megamenu_widgetarea = get_post_meta( $menu_item->ID, '_menu_item_fusion_megamenu_widgetarea', true );

			}

			$menu_item->fusion_megamenu_columnwidth = get_post_meta( $menu_item->ID, '_menu_item_fusion_megamenu_columnwidth', true );
			$menu_item->fusion_megamenu_icon        = get_post_meta( $menu_item->ID, '_menu_item_fusion_megamenu_icon', true );
			$menu_item->fusion_megamenu_thumbnail   = get_post_meta( $menu_item->ID, '_menu_item_fusion_megamenu_thumbnail', true );

			return $menu_item;

		}

	} // end FusionMegaMenu() class

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
