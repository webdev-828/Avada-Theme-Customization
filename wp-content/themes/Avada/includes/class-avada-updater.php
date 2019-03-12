<?php

class Avada_Updater {

	public function __construct() {
		add_action( 'admin_init', array( $this, 'auto_updater' ) );
	}

	/**
	 * Auto Updater
	 */
	public function auto_updater() {

		$avada_options = get_option( 'Avada_Key' );

		if ( isset( $avada_options['tf_username'] ) && ! empty( $avada_options['tf_username'] ) && isset( $avada_options['tf_api'] ) && ! empty( $avada_options['tf_api'] ) && isset( $avada_options['tf_purchase_code'] ) && ! empty( $avada_options['tf_purchase_code'] ) ) {

			$theme_info = wp_get_theme();

			if ( $theme_info->parent_theme ) {

				$template_dir =  basename( get_template_directory() );
				$theme_info = wp_get_theme( $template_dir );

			}

			$name = $theme_info->get( 'Name' );
			$slug = $theme_info->get_template();

			$theme_update = new Avada_Theme_Updater( 'http://updates.theme-fusion.com/avada-theme.php', $name, $slug );

		}

	}

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
