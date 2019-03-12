<?php
$avada_theme = wp_get_theme();
if ( $avada_theme->parent_theme ) {
	$template_dir = basename( get_template_directory() );
	$avada_theme  = wp_get_theme( $template_dir );
}
$avada_version     = Avada()->get_version();
$plugins           = TGM_Plugin_Activation::$instance->plugins;
$installed_plugins = get_plugins();
?>
<div class="wrap about-wrap avada-wrap">
	<h1><?php _e( 'Welcome to Avada!', 'Avada' ); ?></h1>
	<?php add_thickbox(); ?>
	<div class="about-text"><?php printf( esc_html__( 'Avada is now installed and ready to use! Get ready to build something beautiful. Please register your purchase to get support and automatic theme updates. Read below for additional information. We hope you enjoy it! %s', 'Avada' ), '<a href="//www.youtube.com/embed/dn6g_gJDAIk?rel=0&TB_iframe=true&height=540&width=960" class="thickbox" title="' . esc_attr__( 'Guided Tour of Avada', 'Avada' ) . '">' . esc_attr__( 'Watch Our Quick Guided Tour!', 'Avada' ) . '</a>' ); ?></div>
	<div class="avada-logo"><span class="avada-version"><?php printf( __( 'Version %s', 'Avada'), $avada_version ); ?></span></div>
	<h2 class="nav-tab-wrapper">
		<a href="<?php echo admin_url( 'admin.php?page=avada' ); ?>" class="nav-tab"><?php _e( 'Product Registration', 'Avada' ); ?></a>
		<a href="<?php echo admin_url( 'admin.php?page=avada-support' ); ?>" class="nav-tab"><?php  _e( 'Support', 'Avada' ); ?></a>
		<a href="<?php echo admin_url( 'admin.php?page=avada-demos' ); ?>" class="nav-tab"><?php _e( 'Install Demos', 'Avada' ); ?></a>
		<a href="#" class="nav-tab nav-tab-active"><?php _e( 'Plugins', 'Avada' ); ?></a>
		<a href="<?php echo admin_url( 'admin.php?page=avada-system-status' ); ?>" class="nav-tab"><?php _e( 'System Status', 'Avada' ); ?></a>
	</h2>
	 <div class="avada-important-notice">
		<p class="about-description"><?php printf( __( 'These are plugins we include or offer design integration for with Avada. Fusion Core is the only required plugin needed to use Avada. You can activate, deactivate or update the plugins from this tab. <a href="%s" target="_blank">Subscribe to our newsletter</a> to be notified about new products being released in the future!', 'Avada' ), 'http://theme-fusion.us2.list-manage2.com/subscribe?u=4345c7e8c4f2826cc52bb84cd&id=af30829ace' ); ?></p>
	</div>
	<div class="avada-demo-themes avada-install-plugins">
		<div class="feature-section theme-browser rendered">
			<?php foreach ( $plugins as $plugin ) : ?>
				<?php
				$class = '';
				$plugin_status = '';
				$file_path = $plugin['file_path'];
				$plugin_action = $this->plugin_link( $plugin );

				if ( is_plugin_active( $file_path ) ) {
					$plugin_status = 'active';
					$class = 'active';
				}
				?>
				<div class="theme <?php echo $class; ?>">
					<div class="theme-wrapper">
						<div class="theme-screenshot">
							<img src="<?php echo $plugin['image_url']; ?>" alt="" />
							<div class="plugin-info">
								<?php if ( isset( $installed_plugins[ $plugin['file_path'] ] ) ) : ?>
									<?php printf( __( 'Version: %1s | <a href="%2s" target="_blank">%3s</a>', 'Avada' ), $installed_plugins[ $plugin['file_path'] ]['Version'], $installed_plugins[ $plugin['file_path'] ]['AuthorURI'], $installed_plugins[ $plugin['file_path'] ]['Author'] ); ?>
								<?php elseif ( 'bundled' == $plugin['source_type'] ) : ?>
									<?php printf( esc_attr__( 'Available Version: %s', 'Avada' ), $plugin['version'] ); ?>
								<?php endif; ?>
							</div>
						</div>
						<h3 class="theme-name">
							<?php if ( 'active' == $plugin_status ) : ?>
								<span><?php printf( __( 'Active: %s', 'Avada' ), $plugin['name'] ); ?></span>
							<?php else : ?>
								<?php echo $plugin['name']; ?>
							<?php endif; ?>
						</h3>
						<div class="theme-actions">
							<?php foreach ( $plugin_action as $action ) { echo $action; } ?>
						</div>
						<?php if ( isset( $plugin_action['update'] ) && $plugin_action['update'] ) : ?>
							<div class="theme-update">
								<?php printf( __( 'Update Available: Version %s', 'Avada' ), $plugin['version'] ); ?>
							</div>
						<?php endif; ?>
						<?php if ( isset( $plugin['required'] ) && $plugin['required'] ) : ?>
							<div class="plugin-required">
								<?php esc_html_e( 'Required', 'Avada' ); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="avada-thanks">
		<p class="description"><?php esc_html_e( 'Thank you for choosing Avada. We are honored and are fully dedicated to making your experience perfect.', 'Avada' ); ?></p>
	</div>
</div>
<div class="fusion-clearfix" style="clear: both;"></div>
