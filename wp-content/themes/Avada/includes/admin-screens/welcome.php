<?php
$avada_theme = wp_get_theme();
if ($avada_theme->parent_theme) {
	$template_dir =  basename(get_template_directory());
	$avada_theme = wp_get_theme($template_dir);
}
$avada_version = Avada()->get_version();
$avada_options = get_option( 'Avada_Key' );
$registration_complete = false;
$tf_username = isset( $avada_options[ 'tf_username' ] ) ? $avada_options[ 'tf_username' ] : '';
$tf_api = isset( $avada_options[ 'tf_api' ] ) ? $avada_options[ 'tf_api' ] : '';
$tf_purchase_code = isset( $avada_options[ 'tf_purchase_code' ] ) ? $avada_options[ 'tf_purchase_code' ] : '';
if ( $tf_username !== "" && $tf_api !== "" && $tf_purchase_code !== "" ) {
	$registration_complete = true;
}
$theme_fusion_url = 'https://theme-fusion.com/';
?>
<div class="wrap about-wrap avada-wrap">
	<h1><?php _e( "Welcome to Avada!", "Avada" ); ?></h1>

	<div class="updated registration-notice-1" style="display: none;"><p><strong><?php _e( "Thanks for registering your purchase. You will now receive the automatic updates.", "Avada" ); ?> </strong></p></div>

	<div class="updated error registration-notice-2" style="display: none;"><p><strong><?php _e( "Please provide all the three details for registering your copy of Avada.", "Avada" ); ?>.</strong></p></div>

	<div class="updated error registration-notice-3" style="display: none;"><p><strong><?php _e( "Something went wrong. Please verify your details and try again.", "Avada" ); ?></strong></p></div>

	<div class="about-text"><?php printf( esc_html__( 'Avada is now installed and ready to use! Get ready to build something beautiful. Please register your purchase to get support and automatic theme updates. Read below for additional information. We hope you enjoy it! %s', 'Avada' ), '<a href="//www.youtube.com/embed/dn6g_gJDAIk?rel=0&TB_iframe=true&height=540&width=960" class="thickbox" title="' . esc_attr__( 'Guided Tour of Avada', 'Avada' ) . '">' . esc_attr__( 'Watch Our Quick Guided Tour!', 'Avada' ) . '</a>' ); ?></div>
	<div class="avada-logo"><span class="avada-version"><?php _e( "Version", "Avada" ); ?> <?php echo $avada_version; ?></span></div>
	<h2 class="nav-tab-wrapper">
		<?php
		printf( '<a href="#" class="nav-tab nav-tab-active">%s</a>', __( "Product Registration", "Avada" ) );
		printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=avada-support' ), __( "Support", "Avada" ) );
		printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=avada-demos' ), __( "Install Demos", "Avada" ) );
		printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=avada-plugins' ), __( "Plugins", "Avada" ) );
		printf( '<a href="%s" class="nav-tab">%s</a>', admin_url( 'admin.php?page=avada-system-status' ), __( "System Status", "Avada" ) );
		?>
	</h2>
<!--    <p class="about-description"><span class="dashicons dashicons-admin-network avada-icon-key"></span><?php _e( "Your Purchase Must Be Registered To Receive Theme Support & Auto Updates", "Avada" ); ?></p> -->
	<div class="avada-registration-steps">
		<div class="feature-section col three-col">
			<div class="col">
				<h3><?php _e( "Step 1 - Signup for Support", "Avada" ); ?></h3>
				<p><?php printf( __( '<a href="%1s" target="_blank">Click here</a> to signup at our support center. View a tutorial <a href="%2s" target="_blank">here</a>. This gives you access to our documentation, knowledgebase, video tutorials and ticket system.', 'Avada' ), $theme_fusion_url . 'support/?from_theme=1', $theme_fusion_url . 'avada-doc/getting-started/free-forum-support/' ); ?></p>
			</div>
			<div class="col">
				<h3><?php _e( "Step 2 - Generate an API Key", "Avada" ); ?></h3>
				<p><?php _e( 'Once you registered at our support center, you need to generate a product API key under the "Licenses" section of your Themeforest account. View a tutorial&nbsp;', 'Avada' );
				printf( '<a href="%s" target="_blank">%s</a>.',$theme_fusion_url . 'avada-doc/install-update/generate-themeforest-api/',  __('here', "Avada" ) ); ?></p>
			</div>
			<div class="col last-feature">
				<h3><?php _e( "Step 3 - Purchase Validation", "Avada" ); ?></h3>
				<p><?php _e( "Enter your ThemeForest username, purchase code and generated API key into the fields below. This will give you access to automatic theme updates.", "Avada" ); ?></p>
			</div>
		</div>
		<!--<div class="start_registration_button">
			 <a href="<?php echo $theme_fusion_url; ?>support/" class="button button-large button-primary avada-large-button" target="_blank"><?php esc_html_e( 'Start Registration Now!', 'Avada' ); ?></a>
		</div>-->
	</div>
	<div class="feature-section">
		<div class="avada-important-notice registration-form-container">
			<?php
			if ( $registration_complete ) {
				echo '<p class="about-description"><span class="dashicons dashicons-yes avada-icon-key"></span>' . __("Registration Complete! You can now receive automatic updates, theme support and future goodies.", "Avada") . '</p>';
			} else {
			?>
			<p class="about-description"><?php _e( "After Steps 1-2 are complete, enter your credentials below to complete product registration.", "Avada" ); ?></p>
			<?php } ?>
			<div class="avada-registration-form">
				<form id="avada_product_registration">
					<input type="hidden" name="action" value="avada_update_registration" />
					<input type="text" name="tf_username" id="tf_username" placeholder="<?php _e( "Themeforest Username", "Avada" ); ?>" value="<?php echo $tf_username; ?>" />
					<input type="text" name="tf_purchase_code" id="tf_purchase_code" placeholder="<?php _e( "Enter Themeforest Purchase Code", "Avada" ); ?>" value="<?php echo $tf_purchase_code; ?>" />
					<input type="text" name="tf_api" id="tf_api" placeholder="<?php _e( "Enter API Key", "Avada" ); ?>" value="<?php echo $tf_api; ?>" />
				</form>
			</div>
			<button class="button button-large button-primary avada-large-button avada-register"><?php _e( "Submit", "Avada" ); ?></button>
			<span class="avada-loader"><i class="dashicons dashicons-update loader-icon"></i><span></span></span>
		</div>
	</div>
	<div class="avada-thanks">
		<p class="description"><?php _e( "Thank you for choosing Avada. We are honored and are fully dedicated to making your experience perfect.", "Avada" ); ?></p>
	</div>
</div>
