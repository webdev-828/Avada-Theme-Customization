<?php
$avada_theme = wp_get_theme();
if ($avada_theme->parent_theme) {
	$template_dir =  basename(get_template_directory());
	$avada_theme = wp_get_theme($template_dir);
}
$avada_version = Avada()->get_version();
$theme_fusion_url = 'https://theme-fusion.com/';
?>
<div class="wrap about-wrap avada-wrap">
	<h1><?php _e( "Welcome to Avada!", "Avada" ); ?></h1>
	<div class="about-text"><?php printf( esc_html__( 'Avada is now installed and ready to use! Get ready to build something beautiful. Please register your purchase to get support and automatic theme updates. Read below for additional information. We hope you enjoy it! %s', 'Avada' ), '<a href="//www.youtube.com/embed/dn6g_gJDAIk?rel=0&TB_iframe=true&height=540&width=960" class="thickbox" title="' . esc_attr__( 'Guided Tour of Avada', 'Avada' ) . '">' . esc_attr__( 'Watch Our Quick Guided Tour!', 'Avada' ) . '</a>' ); ?></div>
	<div class="avada-logo"><span class="avada-version"><?php _e( "Version", "Avada" ); ?> <?php echo $avada_version; ?></span></div>
	<h2 class="nav-tab-wrapper">
		<?php
		printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=avada' ), __( "Product Registration", "Avada" ) );
		printf( '<a href="#" class="nav-tab nav-tab-active">%s</a>', __( "Support", "Avada" ) );
		printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=avada-demos' ), __( "Install Demos", "Avada" ) );
		printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=avada-plugins' ), __( "Plugins", "Avada" ) );
		printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=avada-system-status' ), __( "System Status", "Avada" ) );
		?>
	</h2>
	<div class="avada-important-notice">
		<p class="about-description"><?php esc_html_e( 'To access our support forum and resources, you first must register your purchase.', 'Avada' ); ?></p>
		<p class="about-description"><?php printf( __( 'See the <a href="%s">Product Registration</a> tab for instructions on how to complete registration.', 'Avada' ), admin_url( 'admin.php?page=avada' ) ); ?></p>
	</div>
	<div class="avada-registration-steps">
		<div class="feature-section col three-col">
			<div class="col">
				<h3><span class="dashicons dashicons-sos"></span><?php _e( "Submit A Ticket", "Avada" ); ?></h3>
				<p><?php _e( "We offer excellent support through our advanced ticket system. Make sure to register your purchase first to access our support services and other resources.", "Avada" ); ?></p>
				<a href="<?php echo $theme_fusion_url . 'support-ticket/'; ?>" class="button button-large button-primary avada-large-button" target="_blank"><?php esc_html_e( 'Submit a ticket', 'Avada' ); ?></a>
			</div>
			<div class="col">
				<h3><span class="dashicons dashicons-book"></span><?php _e( "Documentation", "Avada" ); ?></h3>
				<p><?php _e( "This is the place to go to reference different aspects of the theme. Our online documentaiton is an incredible resource for learning the ins and outs of using Avada.", "Avada" ); ?></p>
				<a href="<?php echo $theme_fusion_url . 'support/documentation/avada-documentation/'; ?>" class="button button-large button-primary avada-large-button" target="_blank"><?php esc_html_e( 'Documentation', 'Avada' ); ?></a>
			</div>
			<div class="col last-feature">
				<h3><span class="dashicons dashicons-portfolio"></span><?php _e( "Knowledgebase", "Avada" ); ?></h3>
				<p><?php _e( "Our knowledgebase contains additional content that is not inside of our documentation. This information is more specific and unique to various versions or aspects of Avada.", "Avada" ); ?></p>
				<a href="<?php echo $theme_fusion_url . 'support/knowledgebase/'; ?>" class="button button-large button-primary avada-large-button" target="_blank"><?php esc_html_e( 'Knowledgebase', 'Avada' ); ?></a>
			</div>
			<div class="col">
				<h3><span class="dashicons dashicons-format-video"></span><?php _e( "Video Tutorials", "Avada" ); ?></h3>
				<p><?php _e( "Nothing is better than watching a video to learn. We have a growing library of high-definititon, narrated video tutorials to help teach you the different aspects of using Avada.", "Avada" ); ?></p>
				<a href="<?php echo $theme_fusion_url . 'support/video-tutorials/avada-videos/'; ?>" class="button button-large button-primary avada-large-button" target="_blank"><?php esc_html_e( 'Watch Videos', 'Avada' ); ?></a>
			</div>
			<div class="col">
				<h3><span class="dashicons dashicons-groups"></span><?php _e( "Community Forum", "Avada" ); ?></h3>
				<p><?php _e( "We also have a community forum for user to user interactions. Ask another Avada user! Please note that ThemeFusion does not provide product support here.", "Avada" ); ?></p>
				<a href="<?php echo $theme_fusion_url . 'support/forum/'; ?>" class="button button-large button-primary avada-large-button" target="_blank"><?php esc_html_e( 'Community Forum', 'Avada' ); ?></a>
			</div>
			<div class="col last-feature">
				<h3><span class="dashicons dashicons-facebook"></span><?php _e( "Facebook Group", "Avada" ); ?></h3>
				<p><?php _e( "We have an amazing Facebook Group! Come and share with other Avada users and help grow our community. Please note, ThemeFusion does not provide support here.", "Avada" ); ?></p>
				<a href="https://www.facebook.com/groups/AvadaUsers/" class="button button-large button-primary avada-large-button" target="_blank"><?php esc_html_e( 'Facebook Group', 'Avada' ); ?></a>
			</div>
		</div>
		<?php do_action( 'avada/admin_pages/support/after_list' ); ?>
	</div>
	<div class="avada-thanks">
		<p class="description"><?php _e( "Thank you for choosing Avada. We are honored and are fully dedicated to making your experience perfect.", "Avada" ); ?></p>
	</div>
</div>
