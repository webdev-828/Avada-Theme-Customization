<?php
	/**
	 * The template for the panel header area.
	 * Override this template by specifying the path where it is stored (templates_path) in your AvadaRedux config.
	 *
	 * @author      AvadaRedux Framework
	 * @package     AvadaReduxFramework/Templates
	 * @version:    3.5.4.18
	 */

	$tip_title = __( 'Developer Mode Enabled', 'avadaredux-framework' );

	if ( $this->parent->dev_mode_forced ) {
		$is_debug     = false;
		$is_localhost = false;

		$debug_bit = '';
		if ( AvadaRedux_Helpers::isWpDebug() ) {
			$is_debug  = true;
			$debug_bit = __( 'WP_DEBUG is enabled', 'avadaredux-framework' );
		}

		$localhost_bit = '';
		if ( AvadaRedux_Helpers::isLocalHost() ) {
			$is_localhost  = true;
			$localhost_bit = __( 'you are working in a localhost environment', 'avadaredux-framework' );
		}

		$conjunction_bit = '';
		if ( $is_localhost && $is_debug ) {
			$conjunction_bit = ' ' . __( 'and', 'avadaredux-framework' ) . ' ';
		}

		$tip_msg = __( 'This has been automatically enabled because', 'avadaredux-framework' ) . ' ' . $debug_bit . $conjunction_bit . $localhost_bit . '.';
	} else {
		$tip_msg = __( 'If you are not a developer, your theme/plugin author shipped with developer mode enabled. Contact them directly to fix it.', 'avadaredux-framework' );
	}

?>
<div id="avadaredux-header">
	<?php if ( ! empty( $this->parent->args['display_name'] ) ) { ?>
		<div class="display_header">

			<?php if ( isset( $this->parent->args['dev_mode'] ) && $this->parent->args['dev_mode'] ) { ?>
				<div class="avadaredux-dev-mode-notice-container avadaredux-dev-qtip"
					 qtip-title="<?php echo esc_attr( $tip_title ); ?>"
					 qtip-content="<?php echo esc_attr( $tip_msg ); ?>">
					<span
						class="avadaredux-dev-mode-notice"><?php _e( 'Developer Mode Enabled', 'avadaredux-framework' ); ?></span>
				</div>
			<?php } elseif (isset($this->parent->args['forced_dev_mode_off']) && $this->parent->args['forced_dev_mode_off'] == true ) { ?>
				<?php $tip_title    = 'The "forced_dev_mode_off" argument has been set to true.'; ?>
				<?php $tip_msg      = 'Support options are not available while this argument is enabled.  You will also need to switch this argument to false before deploying your project.  If you are a user of this product and you are seeing this message, please contact the author of this theme/plugin.'; ?>
				<div class="avadaredux-dev-mode-notice-container avadaredux-dev-qtip"
					 qtip-title="<?php echo esc_attr( $tip_title ); ?>"
					 qtip-content="<?php echo esc_attr( $tip_msg ); ?>">
					<span
						class="avadaredux-dev-mode-notice" style="background-color: #FF001D;"><?php _e( 'FORCED DEV MODE OFF ENABLED', 'avadaredux-framework' ); ?></span>
				</div>

			<?php } ?>

			<h2><?php echo wp_kses_post( $this->parent->args['display_name'] ); ?></h2>

			<?php if ( ! empty( $this->parent->args['display_version'] ) ) { ?>
				<span><?php echo wp_kses_post( $this->parent->args['display_version'] ); ?></span>
			<?php } ?>

		</div>
	<?php } ?>

	<div class="clear"></div>
</div>
