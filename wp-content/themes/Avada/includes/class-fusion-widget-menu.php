<?php

class Fusion_Widget_Menu extends WP_Widget {

	function __construct() {

		$widget_ops  = array('classname' => 'menu', 'description' => '');
		$control_ops = array('id_base' => 'menu-widget');
		parent::__construct('menu-widget', 'Avada: Horizontal Menu', $widget_ops, $control_ops);

	}

	function widget( $args, $instance ) {

		extract( $args );

		echo $before_widget;

		// Get menu
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

		if ( !$nav_menu )
			return;

		?>
		<style type="text/css">
		#<?php echo $this->id; ?> > .fusion-widget-menu ul {
			<?php if ( strtolower( $instance['alignment'] ) != 'center' ): ?>
			float: <?php echo strtolower( $instance['alignment'] ); ?>;
			<?php else: ?>
			text-align: <?php echo strtolower( $instance['alignment'] ); ?>;
			<?php endif; ?>
		}
		#<?php echo $this->id; ?> > .fusion-widget-menu li {
			display: inline-block;
		}
		#<?php echo $this->id; ?> ul li a {
			display: inline-block;
			padding: 0;
			border: 0;
			color: <?php echo Avada_Sanitize::color( $instance['menu_link_color'] ); ?>;
			font-size: <?php echo Avada_Sanitize::size( $instance['font_size'] ); ?>;
		}
		#<?php echo $this->id; ?> ul li a:after {
			content: '<?php echo $instance['sep_text']; ?>';
			color: <?php echo Avada_Sanitize::color( $instance['menu_link_color'] ); ?>;
			padding-right: <?php echo Avada_Sanitize::size( $instance['menu_padding'] ); ?>;
			padding-left: <?php echo Avada_Sanitize::size( $instance['menu_padding'] ); ?>;
			font-size: <?php echo Avada_Sanitize::size( $instance['font_size'] ); ?>;
		}
		#<?php echo $this->id; ?> ul li a:hover {
			color: <?php echo Avada_Sanitize::color( $instance['menu_link_hover_color'] ); ?>;
		}
		#<?php echo $this->id; ?> ul li:last-child a:after {
			display: none;
		}
		#<?php echo $this->id; ?> ul li .fusion-widget-cart-number {
			margin: 0 7px;
			background-color: <?php echo Avada_Sanitize::color( $instance['menu_link_hover_color'] ); ?>;
			color: <?php echo Avada_Sanitize::color( $instance['menu_link_color'] ); ?>;
		}
		#<?php echo $this->id; ?> ul li.fusion-active-cart-icon .fusion-widget-cart-icon:after {
			color: <?php echo Avada_Sanitize::color( $instance['menu_link_hover_color'] ); ?>;
		}
		</style>
		<?php

		$nav_menu_args = array(
			'fallback_cb' 	  => '',
			'menu'        	  => $nav_menu,
			'depth'		  	  => -1,
			'container_class' => 'fusion-widget-menu'
		);

		wp_nav_menu( $nav_menu_args );


		echo $after_widget;

	}

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['nav_menu']				= $new_instance['nav_menu'];
		$instance['alignment']				= $new_instance['alignment'];
		$instance['menu_padding']  			= $new_instance['menu_padding'];
		$instance['menu_link_color']    	= $new_instance['menu_link_color'];
		$instance['menu_link_hover_color']  = $new_instance['menu_link_hover_color'];
		$instance['sep_text']      			= $new_instance['sep_text'];
		$instance['font_size']      		= $new_instance['font_size'];

		return $instance;

	}

	function form( $instance ) {

		$defaults = array(
			'nav_menu' 				=> '',
			'alignment'				=> 'Left',
			'menu_padding'  		=> '25px',
			'menu_link_color'    	=> '#ccc',
			'menu_link_hover_color' => '#fff',
			'sep_text'      		=> '|',
			'font_size'				=> '14px'
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		// Get menus
		$menus = wp_get_nav_menus();
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'nav_menu' ); ?>"><?php _e( 'Select Menu:', 'Avada' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'nav_menu' ); ?>" name="<?php echo $this->get_field_name( 'nav_menu' ); ?>" class="widefat" style="width:100%;">
				<option value="0"><?php _e( '&mdash; Select &mdash;' ); ?></option>
				<?php foreach ( $menus as $menu ) : ?>
					<option value="<?php echo esc_attr( $menu->slug ); ?>" <?php selected( $nav_menu, $menu->slug ); ?>>
						<?php echo esc_html( $menu->name ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'alignment' ); ?>"><?php _e( 'Alignment:', 'Avada' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'alignment' ); ?>" name="<?php echo $this->get_field_name( 'alignment' ); ?>" class="widefat" style="width:100%;">
				<option value="Left" <?php if ( 'Left' == $instance['alignment']) echo 'selected="selected"'; ?>><?php _e( 'Left', 'Avada' ); ?></option>
				<option value="Center" <?php if ( 'Center' == $instance['alignment']) echo 'selected="selected"'; ?>><?php _e( 'Center', 'Avada' ); ?></option>
				<option value="Right" <?php if ( 'Right' == $instance['alignment']) echo 'selected="selected"'; ?>><?php _e( 'Right', 'Avada' ); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'menu_padding' ); ?>"><?php _e( 'Menu Padding:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'menu_padding' ); ?>" name="<?php echo $this->get_field_name( 'menu_padding' ); ?>" value="<?php echo $instance['menu_padding']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'menu_link_color' ); ?>"><?php _e( 'Menu Link Color:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'menu_Link_color' ); ?>" name="<?php echo $this->get_field_name( 'menu_link_color' ); ?>" value="<?php echo $instance['menu_link_color']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('menu_link_hover_color'); ?>"><?php _e( 'Menu Link Hover Color:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'menu_link_hover_color' ); ?>" name="<?php echo $this->get_field_name( 'menu_link_hover_color' ); ?>" value="<?php echo $instance['menu_link_hover_color']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'sep_text' ); ?>"><?php _e( 'Separator Text:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'sep_text' ); ?>" name="<?php echo $this->get_field_name( 'sep_text' ); ?>" value="<?php echo $instance['sep_text']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'font_size' ); ?>"><?php _e( 'Font Size:', 'Avada' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'font_size' ); ?>" name="<?php echo $this->get_field_name( 'font_size' ); ?>" value="<?php echo $instance['font_size']; ?>" />
		</p>
		<?php

	}

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
