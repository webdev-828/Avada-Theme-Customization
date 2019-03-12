<?php
$avada_theme = wp_get_theme();
if ( $avada_theme->parent_theme ) {
	$template_dir = basename( get_template_directory() );
	$avada_theme  = wp_get_theme( $template_dir );
}
$avada_version = Avada()->get_version();

$theme_fusion_url = 'http://theme-fusion.com/';
$avada_url = 'http://avada.theme-fusion.com/';
$demos = array(
	'classic' 			=> array(),
	'photography'		=> array( 'new' => true ),
	'gym'				=> array(),
	'modern_shop'		=> array(),
	'classic_shop'		=> array(),
	'landing_product'	=> array(),
	'forum'				=> array(),
	'church' 			=> array(),
	'cafe' 				=> array(),
	'travel' 			=> array(),
	'hotel' 			=> array(),
	'architecture' 		=> array(),
	'hosting' 			=> array(),
	'law' 				=> array(),
	'lifestyle' 		=> array(),
	'fashion' 			=> array(),
	'app'				=> array(),
	'agency' 			=> array(),
);
?>
<div class="wrap about-wrap avada-wrap">
	<h1><?php esc_attr_e( 'Welcome to Avada!', 'Avada' ); ?></h1>

	<div class="updated error importer-notice importer-notice-1" style="display: none;">
		<p><strong><?php esc_attr_e( "We're sorry but the demo data could not be imported. It is most likely due to low PHP configurations on your server. There are two possible solutions.", 'Avada' ); ?></strong></p>

		<p><strong><?php esc_attr_e( 'Solution 1:', 'Avada' ); ?></strong> <?php esc_attr_e( 'Import the demo using an alternate method.', 'Avada' ); ?><a href="https://theme-fusion.com/avada-doc/demo-content-info/alternate-demo-method/" class="button-primary" target="_blank" style="margin-left: 10px;"><?php esc_attr_e( 'Alternate Method', 'Avada' ); ?></a></p>
		<p><strong><?php esc_attr_e( 'Solution 2:', 'Avada' ); ?></strong> <?php printf( __( 'Fix the PHP configurations in the System Status that are reported in <strong style="color: red;">RED</strong>, then use the %s, then reimport.', 'Avada' ), '<a href="' . admin_url() . 'plugin-install.php?tab=plugin-information&amp;plugin=wordpress-reset&amp;TB_iframe=true&amp;width=830&amp;height=472' . '">Reset WordPress Plugin</a>' ); ?><a href="<?php echo admin_url( 'admin.php?page=avada-system-status' ); ?>" class="button-primary" target="_blank" style="margin-left: 10px;"><?php esc_attr_e( 'System Status', 'Avada' ); ?></a></p>
	</div>

	<div class="updated importer-notice importer-notice-2" style="display: none;"><p><?php printf( esc_html__( 'Demo data successfully imported. Now, please install and run %s plugin once.', 'Avada' ), '<a href="' . admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=regenerate-thumbnails&amp;TB_iframe=true&amp;width=830&amp;height=472' ) . ' class="thickbox" title="' . esc_attr__( 'Regenerate Thumbnails', 'Avada' ) . '">' . esc_attr__( 'Regenerate Thumbnails', 'Avada' ) . '</a>' ); ?></p></div>

	<div class="updated error importer-notice importer-notice-3" style="display: none;">
		<p><strong><?php esc_attr_e( "We're sorry but the demo data could not be imported. It is most likely due to low PHP configurations on your server. There are two possible solutions.", 'Avada' ); ?></strong></p>

		<p><strong><?php esc_attr_e( 'Solution 1:', 'Avada' ); ?></strong> <?php esc_attr_e( 'Import the demo using an alternate method.', 'Avada' ); ?><a href="https://theme-fusion.com/avada-doc/demo-content-info/alternate-demo-method/" class="button-primary" target="_blank" style="margin-left: 10px;"><?php esc_attr_e( 'Alternate Method', 'Avada' ); ?></a></p>
		<p><strong><?php esc_attr_e( 'Solution 2:', 'Avada' ); ?></strong> <?php printf( __( 'Fix the PHP configurations in the System Status that are reported in <strong style="color: red;">RED</strong>, then use the %s, then reimport.', 'Avada' ), '<a href="' . admin_url() . 'plugin-install.php?tab=plugin-information&amp;plugin=wordpress-reset&amp;TB_iframe=true&amp;width=830&amp;height=472' . '">Reset WordPress Plugin</a>' ); ?><a href="<?php echo admin_url( 'admin.php?page=avada-system-status' ); ?>" class="button-primary" target="_blank" style="margin-left: 10px;"><?php esc_attr_e( 'System Status', 'Avada' ); ?></a></p>
	</div>

	<div class="about-text"><?php printf( esc_html__( 'Avada is now installed and ready to use! Get ready to build something beautiful. Please register your purchase to get support and automatic theme updates. Read below for additional information. We hope you enjoy it! %s', 'Avada' ), '<a href="//www.youtube.com/embed/dn6g_gJDAIk?rel=0&TB_iframe=true&height=540&width=960" class="thickbox" title="' . esc_attr__( 'Guided Tour of Avada', 'Avada' ) . '">' . esc_attr__( 'Watch Our Quick Guided Tour!', 'Avada' ) . '</a>' ); ?></div>
	<div class="avada-logo"><span class="avada-version"><?php printf( esc_attr__( 'Version %s', 'Avada' ), $avada_version ); ?></span></div>
	<h2 class="nav-tab-wrapper">
		<?php
		printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=avada' ),  esc_attr__( 'Product Registration', 'Avada' ) );
		printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=avada-support' ), esc_attr__( 'Support', 'Avada' ) );
		printf( '<a href="#" class="nav-tab nav-tab-active">%s</a>', esc_attr__( 'Install Demos', 'Avada' ) );
		printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=avada-plugins' ), esc_attr__( 'Plugins', 'Avada' ) );
		printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=avada-system-status' ), esc_attr__( 'System Status', 'Avada' ) );
		?>
	</h2>
	 <div class="avada-important-notice">
		<p class="about-description"><?php printf( __( 'Installing a demo provides pages, posts, images, theme options, widgets, sliders and more. IMPORTANT: The included plugins need to be installed and activated before you install a demo. Please check the "System Status" tab to ensure your server meets all requirements for a successful import. Settings that need attention will be listed in red. <a href="%s" target="_blank">View more info here</a>.', 'Avada' ), $theme_fusion_url . 'avada-doc/demo-content-info/import-xml-file/' ); ?></p>
	</div>
	<div class="avada-demo-themes">
		<div class="feature-section theme-browser rendered">
			<?php
			// Loop through all demos
			foreach ( $demos as $demo => $demo_details ) { ?>
				<div class="theme">
					<div class="theme-wrapper">
						<div class="theme-screenshot">
							<img src="<?php echo get_template_directory_uri() . '/assets/admin/images/demo-previews/' . $demo . '.jpg'; ?>" />
						</div>
						<h3 class="theme-name" id="<?php echo $demo; ?>"><?php echo ucwords( str_replace( '_', ' ', $demo ) ); ?></h3>
						<div class="theme-actions">
							<?php printf( '<a class="button button-primary button-install-demo" data-demo-id="%s" href="#">%s</a>', strtolower( $demo ), __( 'Install', 'Avada' ) ); ?>
							<?php printf( '<a class="button button-primary" target="_blank" href="%1s">%2s</a>', ( $demo != 'classic' ) ? $avada_url .  str_replace( '_', '-', $demo ) : $avada_url, __( 'Preview', 'Avada' ) ); ?>
						</div>
						<div id="demo-preview-classic" class="screenshot-hover fusion-animated fadeInUp">
							<a href="<?php echo ( $demo != 'classic' ) ? $avada_url . $demo : $avada_url; ?>" target="_blank"><img src="<?php echo get_template_directory_uri() . '/assets/admin/images/demo-popovers/' . $demo . '.jpg'; ?>" /></a>
						</div>
						<div class="demo-import-loader preview-all"></div>
						<div class="demo-import-loader preview-<?php echo strtolower( $demo ); ?>"><i class="dashicons dashicons-admin-generic"></i></div>
						<div class="demo-import-loader success-icon success-<?php echo strtolower( $demo ); ?>"><i class="dashicons dashicons-yes"></i></div>
						<div class="demo-import-loader warning-icon warning-<?php echo strtolower( $demo ); ?>"><i class="dashicons dashicons-warning"></i></div>
						
						<?php if ( isset( $demo_details['new'] ) && true == $demo_details['new'] ) : ?>
							<div class="plugin-required"><?php esc_attr_e( 'New', 'Avada' ); ?></div>
						<?php endif; ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="avada-thanks">
		<p class="description"><?php esc_attr_e( 'Thank you for choosing Avada. We are honored and are fully dedicated to making your experience perfect.', 'Avada' ); ?></p>
	</div>
</div>
