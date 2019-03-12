<?php
class PyreThemeFrameworkMetaboxes {

	public $data;

	public function __construct() {

		$this->data = Avada()->settings->get_all();

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_script_loader' ) );

	}

	/**
	 * Load backend scripts
	 */
	function admin_script_loader() {

		global $pagenow;

		if ( is_admin() && ( in_array( $pagenow, array( 'post-new.php', 'post.php' ) ) ) ) {

			$theme_info = wp_get_theme();

			wp_enqueue_script( 'jquery.biscuit', get_template_directory_uri() . '/assets/admin/js/jquery.biscuit.js', array( 'jquery' ), $theme_info->get( 'Version' ) );
			wp_register_script( 'avada_upload', get_template_directory_uri() . '/assets/admin/js/upload.js', array( 'jquery' ), $theme_info->get( 'Version' ) );
			wp_enqueue_script( 'avada_upload' );
			wp_enqueue_script( 'media-upload' );
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_style( 'thickbox' );

		}

	}

	public function add_meta_boxes() {

		$post_types = get_post_types( array( 'public' => true ) );

		$disallowed = array( 'page', 'post', 'attachment', 'avada_portfolio', 'themefusion_elastic', 'product', 'wpsc-product', 'slide', 'tribe_events' );

		foreach ( $post_types as $post_type ) {
			if ( in_array( $post_type, $disallowed ) ) {
				continue;
			}
			$this->add_meta_box('post_options', 'Avada Options', $post_type);
		}

		$this->add_meta_box( 'post_options', 'Fusion Page Options', 'post' );
		$this->add_meta_box( 'page_options', 'Fusion Page Options', 'page' );
		$this->add_meta_box( 'portfolio_options', 'Fusion Page Options', 'avada_portfolio' );
		$this->add_meta_box( 'es_options', 'Elastic Slide Options', 'themefusion_elastic' );
		$this->add_meta_box( 'woocommerce_options', 'Fusion Page Options', 'product' );
		$this->add_meta_box( 'slide_options', 'Slide Options', 'slide' );
		$this->add_meta_box( 'events_calendar_options', 'Events Calendar Options', 'tribe_events' );

	}

	public function add_meta_box( $id, $label, $post_type ) {
		add_meta_box( 'pyre_' . $id, $label, array( $this, $id ), $post_type );
	}

	public function save_meta_boxes( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		foreach ( $_POST as $key => $value ) {
			if ( strstr( $key, 'pyre_') ) {
				update_post_meta( $post_id, $key, $value );
			}
		}

	}

	public function page_options() {
		$this->render_option_tabs( array( 'sliders', 'page', 'header', 'footer', 'sidebars', 'background', 'portfolio_page', 'pagetitlebar' ) );
	}

	public function post_options() {
		$this->render_option_tabs( array( 'post', 'page', 'sliders', 'header', 'footer', 'sidebars', 'background', 'pagetitlebar' ) );
	}

	public function portfolio_options() {
		$this->render_option_tabs( array( 'portfolio_post', 'page', 'sliders', 'header', 'footer', 'sidebars', 'background', 'pagetitlebar' ) );
	}

	public function woocommerce_options() {
		$this->render_option_tabs( array( 'page', 'header', 'footer', 'sidebars', 'sliders', 'background', 'pagetitlebar' ), 'product' );
	}

	public function es_options() {
		include 'options/options_es.php';
	}

	public function slide_options() {
		include 'options/options_slide.php';
	}

	public function events_calendar_options() {
		$this->render_option_tabs( array( 'page', 'sliders', 'header', 'footer', 'sidebars', 'background', 'pagetitlebar' ) );
	}

	public function render_option_tabs( $requested_tabs, $post_type = 'default' ) {

		$tabs_names = array(
			'sliders'        => esc_html__( 'Sliders', 'Avada' ),
			'page'           => esc_html__( 'Page', 'Avada' ),
			'post'           => esc_html__( 'Post', 'Avada' ),
			'header'         => esc_html__( 'Header', 'Avada' ),
			'footer'         => esc_html__( 'Footer', 'Avada' ),
			'sidebars'       => esc_html__( 'Sidebars', 'Avada' ),
			'background'     => esc_html__( 'Background', 'Avada' ),
			'portfolio'      => esc_html__( 'Portfolio', 'Avada' ),
			'pagetitlebar'   => esc_html__( 'Page Title Bar', 'Avada' ),
			'portfolio_page' => esc_html__( 'Portfolio', 'Avada' ),
			'portfolio_post' => esc_html__( 'Portfolio', 'Avada' ),
			'product'        => esc_html__( 'Product', 'Avada' )
		);
		?>

		<ul class="pyre_metabox_tabs">

			<?php foreach( $requested_tabs as $key => $tab_name ) : ?>
				<?php $class_active = ( $key === 0 ) ? ' class="active"' : ''; ?>
				<?php if ( 'page' == $tab_name && 'product' == $post_type ) : ?>
					<li<?php echo $class_active; ?>><a href="<?php echo $tab_name; ?>"><?php echo $tabs_names[$post_type]; ?></a></li>
				<?php else : ?>
					<li<?php echo $class_active; ?>><a href="<?php echo $tab_name; ?>"><?php echo $tabs_names[$tab_name]; ?></a></li>
				<?php endif; ?>
			<?php endforeach; ?>

		</ul>

		<div class="pyre_metabox">

			<?php foreach ( $requested_tabs as $key => $tab_name ) : ?>
				<div class="pyre_metabox_tab" id="pyre_tab_<?php echo $tab_name; ?>">
					<?php require_once( 'tabs/tab_' . $tab_name . '.php' ); ?>
				</div>
			<?php endforeach; ?>

		</div>
		<div class="clear"></div>
		<?php

	}

	public function text( $id, $label, $desc = '' ) {

		global $post;
		?>

		<div class="pyre_metabox_field">
			<div class="pyre_desc">
				<label for="pyre_<?php echo $id; ?>"><?php echo $label; ?></label>
				<?php if ( $desc ) : ?>
					<p><?php echo $desc; ?></p>
				<?php endif; ?>
			</div>
			<div class="pyre_field">
				<input type="text" id="pyre_<?php echo $id; ?>" name="pyre_<?php echo $id; ?>" value="<?php echo get_post_meta( $post->ID, 'pyre_' . $id, true ); ?>" />
			</div>
		</div>
		<?php

	}

	public function select( $id, $label, $options, $desc = '' ) {
		global $post;
		?>

		<div class="pyre_metabox_field">
			<div class="pyre_desc">
				<label for="pyre_<?php echo $id; ?>"><?php echo $label; ?></label>
				<?php if ( $desc ) : ?>
					<p><?php echo $desc; ?></p>
				<?php endif; ?>
			</div>
			<div class="pyre_field">
				<div class="fusion-shortcodes-arrow">&#xf107;</div>
				<select id="pyre_<?php echo $id; ?>" name="pyre_<?php echo $id; ?>">
					<?php foreach( $options as $key => $option ) : ?>
						<?php $selected = ( $key == get_post_meta( $post->ID, 'pyre_' . $id, true ) ) ? 'selected="selected"' : ''; ?>
						<option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $option; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<?php

	}

	public function multiple( $id, $label, $options, $desc = '' ) {
		global $post;
		?>

		<div class="pyre_metabox_field">
			<div class="pyre_desc">
				<label for="pyre_<?php echo $id; ?>"><?php echo $label; ?></label>
				<?php if ( $desc ) : ?>
					<p><?php echo $desc; ?></p>
				<?php endif; ?>
			</div>
			<div class="pyre_field">
				<select multiple="multiple" id="pyre_<?php echo $id; ?>" name="pyre_<?php echo $id; ?>[]">
					<?php foreach ( $options as $key => $option ) : ?>
						<?php $selected = ( is_array( get_post_meta( $post->ID, 'pyre_' . $id, true ) ) && in_array( $key, get_post_meta( $post->ID, 'pyre_' . $id, true ) ) ) ? 'selected="selected"' : ''; ?>
						<option <?php echo $selected; ?> value="<?php echo $key; ?>"><?php echo $option; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<?php

	}

	public function textarea( $id, $label, $desc = '', $default = '' ) {
		global $post;
		$db_value = get_post_meta( $post->ID, 'pyre_' . $id, true );
		$value = ( metadata_exists( 'post', $post->ID, 'pyre_'. $id ) ) ? $db_value : $default;
		$rows = 10;
		if ( $id == 'heading' || $id == 'caption' ) {
			$rows = 5;
		} else if ( 'page_title_custom_text' == $id || 'page_title_custom_subheader' == $id ) {
			$rows = 1;
		}
		?>

		<div class="pyre_metabox_field">
			<div class="pyre_desc">
				<label for="pyre_<?php echo $id; ?>"><?php echo $label; ?></label>
				<?php if ( $desc ) : ?>
					<p><?php echo $desc; ?></p>
				<?php endif; ?>
			</div>
			<div class="pyre_field">
				<textarea cols="120" rows="<?php echo $rows; ?>" id="pyre_<?php echo $id; ?>" name="pyre_<?php echo $id; ?>"><?php echo $value; ?></textarea>
			</div>
		</div>
		<?php

	}

	public function upload( $id, $label, $desc = '' ) {
		global $post;
		?>

		<div class="pyre_metabox_field">
			<div class="pyre_desc">
				<label for="pyre_<?php echo $id; ?>"><?php echo $label; ?></label>
				<?php if ( $desc ) : ?>
					<p><?php echo $desc; ?></p>
				<?php endif; ?>
			</div>
			<div class="pyre_field">
				<div class="pyre_upload">
					<div><input name="pyre_<?php echo $id; ?>" class="upload_field" id="pyre_<?php echo $id; ?>" type="text" value="<?php echo get_post_meta( $post->ID, 'pyre_' . $id, true ); ?>" /></div>
					<div class="fusion_upload_button_container"><input class="fusion_upload_button" type="button" value="<?php _e( 'Browse', 'Avada' ); ?>" /></div>
				</div>
			</div>
		</div>
		<?php

	}

}

$metaboxes = new PyreThemeFrameworkMetaboxes;

// Omit closing PHP tag to avoid "Headers already sent" issues.
