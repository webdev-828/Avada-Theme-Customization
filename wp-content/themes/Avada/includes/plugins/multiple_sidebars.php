<?php
/*
Plugin Name: Sidebar Generator
Plugin URI: http://www.getson.info
Description: This plugin generates as many sidebars as you need. Then allows you to place them on any page you wish. Version 1.1 now supports themes with multiple sidebars.
Version: 1.1.0
Author: Kyle Getson
Author URI: http://www.kylegetson.com
Copyright (C) 2009 Kyle Robert Getson
*/

/*
Copyright (C) 2009 Kyle Robert Getson, kylegetson.com and getson.info

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class sidebar_generator {

	public function __construct(){

		add_action( 'init', array( 'sidebar_generator', 'init' ) );
		// add_action( 'admin_menu', array( 'sidebar_generator', 'admin_menu' ) );
		add_action( 'widgets_admin_page', array( 'sidebar_generator', 'admin_page' ) );
		add_action( 'admin_enqueue_scripts', array( 'sidebar_generator', 'admin_enqueue_scripts' ) );
		add_action( 'admin_print_scripts', array( 'sidebar_generator', 'admin_print_scripts' ) );
		add_action( 'wp_ajax_add_sidebar', array( 'sidebar_generator', 'add_sidebar' ) );
		add_action( 'wp_ajax_remove_sidebar', array( 'sidebar_generator', 'remove_sidebar' ) );

		//save posts/pages
		add_action( 'edit_post', array( 'sidebar_generator', 'save_form' ) );
		add_action( 'publish_post', array( 'sidebar_generator', 'save_form' ) );
		add_action( 'save_post', array( 'sidebar_generator', 'save_form' ) );
		add_action( 'edit_page_form', array( 'sidebar_generator', 'save_form' ) );

	}

	public static function init(){
		//go through each sidebar and register it
		$sidebars = sidebar_generator::get_sidebars();

		if ( is_array( $sidebars ) ) {
			foreach ( $sidebars as $sidebar ) {
				$sidebar_class = sidebar_generator::name_to_class( $sidebar );
				register_sidebar( array(
					'name'          => $sidebar,
					'id'            => 'avada-custom-sidebar-' . strtolower( $sidebar_class ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<div class="heading"><h4 class="widget-title">',
					'after_title'   => '</h4></div>',
				) );
			}
		}
	}

	public static function add_meta_boxes() {
		$post_types = get_post_types( array( 'public' => true ) );

		foreach ( $post_types as $post_type ) {
			add_meta_box( 'sbg_box', esc_html__( 'Sidebar', 'Avada' ), array( 'sidebar_generator', 'edit_form' ), $post_type, 'side' );
		}
	}

	public static function admin_enqueue_scripts() {
		wp_enqueue_script( array( 'sack' ) );
	}

	public static function admin_print_scripts() { ?>
		<script>
			function add_sidebar( sidebar_name ) {
				var mysack = new sack("<?php echo admin_url( 'admin-ajax.php' ); ?>" );

			  	mysack.execute = 1;
			  	mysack.method  = 'POST';
			  	mysack.setVar( "action", "add_sidebar" );
			  	mysack.setVar( "sidebar_name", sidebar_name );
			  	//mysack.encVar( "cookie", document.cookie, false );
			  	mysack.onError = function() { alert('Ajax error. Cannot add sidebar' )};
			  	mysack.runAJAX();
				return true;
			}

			function remove_sidebar( sidebar_name,num ) {
				var mysack = new sack("<?php echo admin_url( 'admin-ajax.php' ); ?>" );

			  	mysack.execute = 1;
			  	mysack.method = 'POST';
			  	mysack.setVar( "action", "remove_sidebar" );
			  	mysack.setVar( "sidebar_name", sidebar_name );
			  	mysack.setVar( "row_number", num );
			  	//mysack.encVar( "cookie", document.cookie, false );
			  	mysack.onError = function() { alert('Ajax error. Cannot remove sidebar' )};
			  	mysack.runAJAX();
				//alert('hi!:::'+sidebar_name);
				return true;
			}
		</script>
		<?php
	}

	public static function add_sidebar(){
		$sidebars = sidebar_generator::get_sidebars();
		$name     = str_replace( array( "\n", "\r", "\t" ), '', $_POST['sidebar_name'] );
		$counter  = ( is_array( $sidebars ) && ! empty( $sidebars ) ) ? count( $sidebars ) + 1 : 1;
		$id       = sidebar_generator::name_to_class( $name );

		if ( isset( $sidebars[ $id ] ) ) {
			die("alert('" . esc_html__( 'Widget Section already exists, please use a different name.', 'Avada' ) . "')" );
		}

		$sidebars[ $id ] = $name;
		sidebar_generator::update_sidebars( $sidebars );

		$js = "
		var tbl = document.getElementById('sbg_table');
		var lastRow = tbl.rows.length;
		// if there's no header row in the table, then iteration = lastRow + 1
		var iteration = lastRow;
		var row = tbl.insertRow(lastRow);

		// left cell
		var cellLeft = row.insertCell(0);
		var textNode = document.createTextNode('$name');
		cellLeft.appendChild(textNode);

		//middle cell
		var cellLeft = row.insertCell(1);
		var textNode = document.createTextNode('$id');
		cellLeft.appendChild(textNode);

		//var cellLeft = row.insertCell(2);
		//var textNode = document.createTextNode('[<a href=\'javascript:void(0);\' onclick=\'return remove_sidebar_link($name);\'>Remove</a>]');
		//cellLeft.appendChild(textNode)

		var cellLeft = row.insertCell(2);
		removeLink = document.createElement('a');
		linkText = document.createTextNode('remove');
		removeLink.setAttribute('onclick', 'remove_sidebar_link(\'$name\', $counter)');
		removeLink.setAttribute('href', 'javascript:void(0)');

		removeLink.appendChild(linkText);
		cellLeft.appendChild(removeLink);

		var tbl = document.getElementById( 'no-widget-sections' );
		if ( tbl !== null ) {
			tbl.remove();
		}
		location.reload();
		";

		die( "$js" );
	}

	public static function remove_sidebar(){
		$sidebars = sidebar_generator::get_sidebars();
		$name     = str_replace( array( "\n", "\r", "\t" ), '', $_POST['sidebar_name'] );
		$counter  = '1';

		if ( is_array( $sidebars ) && ! empty( $sidebars ) ) {
			$counter = count( $sidebars );
		}
		$no_widget_text = esc_html__( 'No Widget Sections defined.', 'Avada' );

		$id = sidebar_generator::name_to_class( $name );
		if ( ! isset( $sidebars[ $id ] ) ) {
			die( 'alert("' . esc_html__( 'Sidebar does not exist.', 'Avada' ) . '")' );
		}
		$row_number = $_POST['row_number'];
		unset( $sidebars[ $id ] );
		sidebar_generator::update_sidebars( $sidebars );
		$js = "
			var tbl = document.getElementById('sbg_table');

			if ( $counter - 1  == '0' ) {
				var last_row = tbl.rows.length;
				var row = tbl.insertRow( last_row );
				var cell = row.insertCell( 0 );
				var text_node = document.createTextNode( '$no_widget_text' );
				row.setAttribute( 'id', 'no-widget-sections' );
				cell.appendChild( text_node );
				cell.colSpan = 3;
			}
			tbl.deleteRow($row_number);
			location.reload();
		";
		die( $js );
	}

	public static function admin_menu() {
		add_theme_page( esc_html__( 'Widget Sections', 'Avada' ), esc_html__( 'Widget Sections' ), 'manage_options', 'multiple_sidebars', array( 'sidebar_generator', 'admin_page' ) );
	}

	public static function admin_page() { ?>
		<script>
		function remove_sidebar_link(name,num){
			answer = confirm( "<?php esc_html_e( 'Are you sure you want to remove', 'Avada' ); ?> " + name + "?\n<?php esc_html_e( 'This will remove any widgets you have assigned to this widget section.', 'Avada' ); ?>" );
				if ( answer ) {
					//alert('AJAX REMOVE');
					remove_sidebar( name, num );
				} else {
					return false;
				}
			}
			function add_sidebar_link(){
				var sidebar_name = prompt( "<?php esc_html_e( 'Widget Section Name:', 'Avada' ); ?>","" );
				//alert(sidebar_name);
				if ( sidebar_name === null || sidebar_name == '' ) {
					return;
				}

				add_sidebar( sidebar_name );
			}
		</script>
		<div class="postbox" style="max-width:1719px;">
			<h2 class="hndle ui-sortable-handle" style="padding: 15px 12px; margin: 0;">
				<span><?php esc_html_e( 'Widget Sections', 'Avada' ); ?></span>
			</h2>
			<div class="inside" style="margin-bottom: 0;">
				<table class="widefat page" id="sbg_table">
					<tr>
						<th><?php esc_html_e( 'Widget Section Name', 'Avada' ); ?></th>
						<th><?php esc_html_e( 'CSS Class', 'Avada' ); ?></th>
						<th><?php esc_html_e( 'Remove', 'Avada' ); ?></th>
					</tr>
					<?php
					$sidebars = sidebar_generator::get_sidebars();
					?>
					<?php if ( is_array( $sidebars ) && ! empty( $sidebars ) ) : ?>
						<?php $cnt = 0; ?>
						<?php foreach ( $sidebars as $sidebar ) : ?>
							<?php $alt = ( 0 == $cnt % 2 ) ? 'alternate' : ''; ?>
							<tr class="<?php echo $alt; ?>">
								<td><?php echo $sidebar; ?></td>
								<td><?php echo sidebar_generator::name_to_class( $sidebar ); ?></td>
								<td><a href="javascript:void(0);" onclick="return remove_sidebar_link('<?php echo $sidebar; ?>',<?php echo $cnt + 1; ?>);" title="<?php esc_html_e( 'Remove this Widget Section', 'Avada' ); ?>"><?php esc_attr_e( 'remove', 'Avada' ); ?></a></td>
							</tr>
							<?php $cnt++; ?>
						<?php endforeach; ?>
					<?php else : ?>
						<tr id="no-widget-sections">
							<td colspan="3"><?php esc_html_e( 'No Widget Sections defined.', 'Avada' ); ?></td>
						</tr>
					<?php endif; ?>
				</table>
				<p class="add_sidebar"><a href="javascript:void(0);" onclick="return add_sidebar_link()" title="<?php _e( 'Add New Widget Section', 'Avada' ); ?>" class="button button-primary"><?php _e( 'Add New Widget Section', 'Avada' ); ?></a></p>
			</div>
		</div>
		<?php
	}

	/**
	 * for saving the pages/post
	*/
	public static function save_form( $post_id ) {
		if ( isset( $_POST['sbg_edit'] ) ) {
			$is_saving = $_POST['sbg_edit'];
			if ( ! empty( $is_saving ) ) {
				delete_post_meta( $post_id, 'sbg_selected_sidebar' );
				delete_post_meta( $post_id, 'sbg_selected_sidebar_replacement' );
				add_post_meta( $post_id, 'sbg_selected_sidebar', $_POST['sidebar_generator'] );
				add_post_meta( $post_id, 'sbg_selected_sidebar_replacement', $_POST['sidebar_generator_replacement'] );

				delete_post_meta( $post_id, 'sbg_selected_sidebar_2' );
				delete_post_meta( $post_id, 'sbg_selected_sidebar_2_replacement' );
				add_post_meta( $post_id, 'sbg_selected_sidebar_2', $_POST['sidebar_2_generator'] );
				add_post_meta( $post_id, 'sbg_selected_sidebar_2_replacement', $_POST['sidebar_2_generator_replacement'] );
			}
		}
	}

	public static function edit_form() {
		global $post;
		$screen  = get_current_screen();
		$post_id = $post;
		if ( is_object( $post_id ) ) {
			$post_id = $post_id->ID;
		}

		$selected_sidebar = get_post_meta( $post_id, 'sbg_selected_sidebar', true );
		if ( ! is_array( $selected_sidebar ) ) {
			$selected_sidebar    = array();
			$selected_sidebar[0] = $selected_sidebar;
		}
		$selected_sidebar_replacement = get_post_meta( $post_id, 'sbg_selected_sidebar_replacement', true );
		if ( ! is_array( $selected_sidebar_replacement ) ) {
			$selected_sidebar_replacement    = array();
			$selected_sidebar_replacement[0] = $selected_sidebar_replacement;
		}
		$selected_sidebar_2 = get_post_meta( $post_id, 'sbg_selected_sidebar_2', true );
		if ( ! is_array( $selected_sidebar_2 ) ) {
			$selected_sidebar_2    = array();
			$selected_sidebar_2[0] = $selected_sidebar_2;
		}
		$selected_sidebar_2_replacement = get_post_meta( $post_id, 'sbg_selected_sidebar_2_replacement', true );
		if ( ! is_array( $selected_sidebar_2_replacement ) ) {
			$selected_sidebar_2_replacement    = array();
			$selected_sidebar_2_replacement[0] = $selected_sidebar_2_replacement;
		}
		?>
		<div class="pyre_metabox_field">
			<input name="sbg_edit" type="hidden" value="sbg_edit" />
			<div class="pyre_desc">
				<label><?php esc_html_e( 'Select Sidebar 1:', 'Avada' ); ?></label>
				<p><?php esc_html_e( 'Select sidebar 1 that will display on this page. Choose "No Sidebar" for full width.', 'Avada' ); ?></p>
			</div>
			<div class="pyre_field">
				<?php global $wp_registered_sidebars; ?>
				<?php //var_dump($wp_registered_sidebars); ?>
				<?php for ( $i = 0; $i < 1; $i++ ) : ?>
					<div class="fusion-shortcodes-arrow">&#xf107;</div>
					<select name="sidebar_generator[<?php echo $i; ?>]" style="display: none;">
						<option value="0"<?php echo ( '' == $selected_sidebar[ $i ] ) ? ' selected' : ''; ?>><?php esc_html__( 'WP Default Sidebar', 'Avada' ); ?></option>
						<?php $sidebars = $wp_registered_sidebars; // sidebar_generator::get_sidebars(); ?>
						<?php if ( is_array( $sidebars ) && ! empty( $sidebars ) ) : ?>
							<?php foreach ( $sidebars as $sidebar ) : ?>
								<?php if ( $selected_sidebar[ $i ] == $sidebar['name'] ) : ?>
									<?php if ( 'Blog Sidebar' == $sidebar['name'] || esc_html__( 'Blog Sidebar', 'Avada' ) == $sidebar['name'] ) : ?>
										<option value="<?php echo esc_attr( $sidebar['name'] ); ?>" selected><?php esc_html_e( 'Default Sidebar', 'Avada' ); ?></option>
									<?php else : ?>
										<option value="<?php echo esc_attr( $sidebar['name'] ); ?>" selected><?php esc_html_e( $sidebar['name'] ); ?></option>
									<?php endif; ?>
								<?php else : ?>
									<?php if ( 'Blog Sidebar' == $sidebar['name'] || esc_html__( 'Blog Sidebar', 'Avada' ) == $sidebar['name'] ) : ?>
										<option value="<?php echo esc_attr( $sidebar['name'] ); ?>"><?php esc_html_e( 'Default Sidebar', 'Avada' ); ?></option>
									<?php else : ?>
										<option value="<?php echo esc_attr( $sidebar['name'] ); ?>"><?php esc_html_e( $sidebar['name'] ); ?></option>
									<?php endif; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
					<select name="sidebar_generator_replacement[<?php echo $i; ?>]">
						<option value="" <?php echo ( '' == $selected_sidebar_replacement[ $i ] && 'post' != $screen->post_type ) ? ' selected' : ''; ?>><?php esc_html_e( 'No Sidebar', 'Avada' ); ?></option>
						<?php $sidebar_replacements = $wp_registered_sidebars; //sidebar_generator::get_sidebars(); ?>
						<?php if ( is_array( $sidebar_replacements ) && ! empty( $sidebar_replacements ) ) : ?>
							<?php foreach ( $sidebar_replacements as $sidebar ) : ?>
								<?php if ( '0' == $selected_sidebar_replacement[ $i ] ) : ?>
									<?php $selected_sidebar_replacement[ $i ] = esc_html__( 'Blog Sidebar', 'Avada' ); ?>
								<?php endif; ?>
								<?php if ( 'post' == $screen->post_type && 'add' != $screen->action && is_array( $selected_sidebar_replacement[ $i ] ) && empty( $selected_sidebar_replacement[ $i ] ) ) : ?>
									<?php $selected_sidebar_replacement[ $i ] = ''; ?>
								<?php endif; ?>
								<?php if ( $selected_sidebar_replacement[ $i ] == $sidebar['name'] ) : ?>
									<?php if ( 'Blog Sidebar' == $sidebar['name'] || esc_html__( 'Blog Sidebar', 'Avada' ) == $sidebar['name'] ) : ?>
										<option value="<?php echo esc_attr( $sidebar['name'] ); ?>" selected><?php esc_html_e( 'Default Sidebar', 'Avada' ); ?></option>
									<?php else : ?>
										<option value="<?php echo esc_attr( $sidebar['name'] ); ?>" selected><?php esc_html_e( $sidebar['name'] ); ?></option>
									<?php endif; ?>
								<?php else : ?>
									<?php if ( 'Blog Sidebar' == $sidebar['name'] || esc_html__( 'Blog Sidebar', 'Avada' ) == $sidebar['name'] ) : ?>
										<?php $selected = ( '' != $selected_sidebar_replacement[ $i ] && 'post' == $screen->post_type ) ? ' selected' : ''; ?>
										<option value="<?php echo esc_attr( $sidebar['name'] ); ?>"<?php echo $selected; ?>><?php esc_html_e( 'Default Sidebar', 'Avada' ); ?></option>
									<?php else : ?>
										<option value="<?php echo esc_attr( $sidebar['name'] ); ?>"/><?php esc_html_e( $sidebar['name'] ); ?></option>
									<?php endif; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				<?php endfor; ?>
			</div>
		</div>
		<div class="pyre_metabox_field">
			<input name="sbg_edit" type="hidden" value="sbg_edit" />
			<div class="pyre_desc">
				<label><?php esc_html_e( 'Select Sidebar 2:', 'Avada' ); ?></label>
				<p><?php esc_html_e( 'Select sidebar 2 that will display on this page. Sidebar 2 can only be used if sidebar 1 is selected.', 'Avada' ); ?></p>
			</div>
			<div class="pyre_field">
				<?php global $wp_registered_sidebars; //var_dump($wp_registered_sidebars); ?>
				<?php for ( $i = 0; $i < 1; $i++ ) : ?>
					<div class="fusion-shortcodes-arrow">&#xf107;</div>
					<select name="sidebar_2_generator[<?php echo $i; ?>]" style="display: none;">
						<option value="0"<?php echo ( '' == $selected_sidebar_2[ $i ] ) ? ' selected' : ''; ?>><?php esc_html_e( 'WP Default Sidebar', 'Avada' ); ?></option>
						<?php $sidebars = $wp_registered_sidebars; // sidebar_generator::get_sidebars(); ?>
						<?php if ( is_array( $sidebars ) && ! empty( $sidebars ) ) : ?>
							<?php foreach ( $sidebars as $sidebar ) : ?>
								<?php if ( $selected_sidebar_2[ $i ] == $sidebar['name'] ) : ?>
									<?php if ( 'Blog Sidebar' == $sidebar['name'] || esc_html__( 'Blog Sidebar', 'Avada' ) == $sidebar['name'] ) : ?>
										<option value="<?php echo esc_attr( $sidebar['name'] ); ?>" selected><?php esc_html_e( 'Default Sidebar', 'Avada' ); ?></option>
									<?php else : ?>
										<option value="<?php echo esc_attr( $sidebar['name'] ); ?>" selected><?php echo esc_html( $sidebar['name'] ); ?></option>
									<?php endif; ?>
								<?php else : ?>
									<?php if ( 'Blog Sidebar' == $sidebar['name'] || esc_html__( 'Blog Sidebar', 'Avada' ) == $sidebar['name'] ) : ?>
										<option value="<?php echo esc_attr( $sidebar['name'] ); ?>"><?php esc_html_e( 'Default Sidebar', 'Avada' ); ?></option>
									<?php else : ?>
										<option value="<?php echo esc_attr( $sidebar['name'] ); ?>"><?php echo esc_html( $sidebar['name'] ); ?></option>
									<?php endif; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
					<select name="sidebar_2_generator_replacement[<?php echo $i; ?>]">
						<option value=""<?php echo ( '' == $selected_sidebar_replacement[ $i ] ) ? ' selected' : ''; ?>><?php esc_html_e( 'No Sidebar', 'Avada' ); ?></option>
						<?php $sidebar_replacements = $wp_registered_sidebars; //sidebar_generator::get_sidebars(); ?>
						<?php if ( is_array( $sidebar_replacements ) && ! empty( $sidebar_replacements ) ) : ?>
							<?php foreach ( $sidebar_replacements as $sidebar ) : ?>
								<?php if ( $selected_sidebar_2_replacement[ $i ] == $sidebar['name'] ) : ?>
									<?php if ( 'Blog Sidebar' == $sidebar['name'] || esc_html__( 'Blog Sidebar', 'Avada' ) == $sidebar['name'] ) : ?>
										<option value="<?php echo esc_attr( $sidebar['name'] ); ?>" selected><?php esc_html_e( 'Default Sidebar', 'Avada' ); ?></option>
									<?php else : ?>
										<option value="<?php echo esc_attr( $sidebar['name'] ); ?>" selected><?php echo esc_html( $sidebar['name'] ); ?></option>
									<?php endif; ?>
								<?php else : ?>
									<?php if ( 'Blog Sidebar' == $sidebar['name'] || esc_html__( 'Blog Sidebar', 'Avada' ) == $sidebar['name'] ) : ?>
										<option value="<?php echo esc_attr( $sidebar['name'] ); ?>"><?php esc_html_e( 'Default Sidebar', 'Avada' ); ?></option>
									<?php else : ?>
										<option value="<?php echo esc_attr( $sidebar['name'] ); ?>"><?php echo esc_html( $sidebar['name'] ); ?></option>
									<?php endif; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
				<?php endfor; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * called by the action get_sidebar. this is what places this into the theme
	 */
	public static function get_sidebar( $name = '0' ){
		if ( ! is_singular() ) {
			$sidebar = ( '0' != $name ) ? $name : 'avada-blog-sidebar';
			dynamic_sidebar( $sidebar );
			return; // dont do anything
		}
		wp_reset_query();
		global $wp_query;
		$post = $wp_query->get_queried_object();
		$selected_sidebar = get_post_meta( $post->ID, 'sbg_selected_sidebar', true );
		$selected_sidebar_replacement = get_post_meta( $post->ID, 'sbg_selected_sidebar_replacement', true );
		$did_sidebar = false;

		// this page uses a generated sidebar
		if ( ! $name && '' != $selected_sidebar && '0' != $selected_sidebar ) {
			if ( is_array( $selected_sidebar ) && ! empty( $selected_sidebar ) ) {
				$sizeof_selected_sidebar = sizeof( $selected_sidebar );
				for ( $i = 0; $i < $sizeof_selected_sidebar; $i++ ) {
					if ( '0' == $name && '0' == $selected_sidebar[ $i ] && '0' == $selected_sidebar_replacement[ $i ] ) {
						dynamic_sidebar( 'avada-blog-sidebar' ); //default behavior
						$did_sidebar = true;
						break;
					} elseif ( '0' == $name && '0' == $selected_sidebar[ $i ] || 'Blog Sidebar' == $selected_sidebar[ $i ] || esc_html__( 'Blog Sidebar', 'Avada' ) == $selected_sidebar[ $i ] ) {
						// we are replacing the default sidebar with something
						dynamic_sidebar( $selected_sidebar_replacement[ $i ] ); //default behavior
						$did_sidebar = true;
						break;
					} elseif ( $name == $selected_sidebar[ $i ] ) {
						//we are replacing this $name
						$did_sidebar = true;
						dynamic_sidebar( $selected_sidebar_replacement[ $i ] ); //default behavior
						break;
					}
				}
			}
			if ( true == $did_sidebar ) {
				return;
			}
			//go through without finding any replacements, lets just send them what they asked for
			$sidebar = ( '0' != $name ) ? $name : 'avada-blog-sidebar';
			dynamic_sidebar( $sidebar );
			return;
		} else {
			$sidebar = ( '0' != $name ) ? $name : 'avada-blog-sidebar';
			dynamic_sidebar( $sidebar );
		}
	}

	/**
	 * called by the action get_sidebar. this is what places this into the theme
	 */
	public static function get_sidebar_2( $name = '0' ) {
		if ( ! is_singular() ) {
			$sidebar = ( '0' != $name ) ? $name : 'avada-blog-sidebar';
			dynamic_sidebar( $sidebar );
			return; //dont do anything
		}

		wp_reset_query();
		global $wp_query;
		$post = $wp_query->get_queried_object();
		$selected_sidebar = get_post_meta( $post->ID, 'sbg_selected_sidebar_2', true );
		$selected_sidebar_replacement = get_post_meta( $post->ID, 'sbg_selected_sidebar_2_replacement', true );
		$did_sidebar = false;

		// this page uses a generated sidebar
		if ( ! $name && '' != $selected_sidebar && '0' != $selected_sidebar ) {
			if ( is_array( $selected_sidebar ) && ! empty( $selected_sidebar ) ) {
				$sizeof_selected_sidebar = sizeof( $selected_sidebar );
				for ( $i = 0; $i < $sizeof_selected_sidebar; $i++ ) {
					if ( '0' == $name && '0' == $selected_sidebar[ $i ] && '0' == $selected_sidebar_replacement[ $i ] ) {
						dynamic_sidebar( 'avada-blog-sidebar' ); // default behavior
						$did_sidebar = true;
						break;
					} elseif ( '0' == $name && '0' == $selected_sidebar[ $i ] || 'Blog Sidebar' == $selected_sidebar[ $i ] || esc_html__( 'Blog Sidebar', 'Avada' ) == $selected_sidebar[ $i ] ) {
						//we are replacing the default sidebar with something
						dynamic_sidebar( $selected_sidebar_replacement[ $i ] ); // default behavior
						$did_sidebar = true;
						break;
					} elseif ( $name == $selected_sidebar[ $i ] ) {
						// we are replacing this $name
						$did_sidebar = true;
						dynamic_sidebar( $selected_sidebar_replacement[ $i ] ); // default behavior
						break;
					}
				}
			}
			if ( true == $did_sidebar ) {
				return;
			}
			//go through without finding any replacements, lets just send them what they asked for
			$sidebar = ( '0' != $name ) ? $name : 'avada-blog-sidebar';
			dynamic_sidebar( $sidebar );
			return;
		} else {
			$sidebar = ( '0' != $name ) ? $name : 'avada-blog-sidebar';
			dynamic_sidebar( $sidebar );
		}
	}

	/**
	 * replaces array of sidebar names
	 */
	public static function update_sidebars( $sidebar_array ) {
		update_option( 'sbg_sidebars', $sidebar_array );
	}

	/**
	 * gets the generated sidebars
	 */
	public static function get_sidebars() {
		return get_option( 'sbg_sidebars' );
	}

	public static function name_to_class($name){
		$class = str_replace( array( ' ', ',', '.', '"', "'", '/', "\\", '+', '=', ')', '(', '*', '&', '^', '%', '$', '#', '@', '!', '~', '`', '<', '>', '?', '[', ']', '{', '}', '|', ':', ), '', $name );
		return sanitize_html_class( $class );
	}

}
$sbg = new sidebar_generator;

function generated_dynamic_sidebar( $name = '0' ) {
	sidebar_generator::get_sidebar( $name );
	return true;
}
function generated_dynamic_sidebar_2( $name = '0' ) {
	sidebar_generator::get_sidebar_2( $name );
	return true;
}

// Omit closing PHP tag to avoid "Headers already sent" issues.
