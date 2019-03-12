<?php
	/**
	 * The template for the header sticky bar.
	 * Override this template by specifying the path where it is stored (templates_path) in your AvadaRedux config.
	 *
	 * @author        AvadaRedux Framework
	 * @package       AvadaReduxFramework/Templates
	 * @version:      3.5.7.8
	 */
?>
<div id="avadaredux-sticky">
	<div id="info_bar">

		<a href="javascript:void(0);" class="expand_options<?php echo esc_attr(( $this->parent->args['open_expanded'] ) ? ' expanded' : ''); ?>"<?php echo $this->parent->args['hide_expand'] ? ' style="display: none;"' : '' ?>>
			<span class="dashicons dashicons-editor-ul"></span><?php esc_attr_e( 'Expand Options', 'Avada' ); ?>
		</a>

		<div class="avada-support-links">
			<a href="https://theme-fusion.com/support" target="_blank"><span class="dashicons dashicons-thumbs-up"></span><?php esc_attr_e( 'Support Center', 'Avada' ); ?></a>
		</div>

		<div class="avadaredux-action_bar">
			<span class="spinner"></span>
			<?php
			if ( false === $this->parent->args['hide_save'] ) {
				submit_button( esc_attr__( 'Save Changes', 'avadaredux-framework' ), 'primary', 'avadaredux_save', false );
			}

			if ( false === $this->parent->args['hide_reset'] ) {
				submit_button( esc_attr__( 'Reset Section', 'avadaredux-framework' ), 'secondary', $this->parent->args['opt_name'] . '[defaults-section]', false, array( 'id' => 'avadaredux-defaults-section' ) );
				submit_button( esc_attr__( 'Reset All', 'avadaredux-framework' ), 'secondary', $this->parent->args['opt_name'] . '[defaults]', false, array( 'id' => 'avadaredux-defaults' ) );
			}
			?>
		</div>
		<div class="avadaredux-ajax-loading" alt="<?php esc_attr_e( 'Working...', 'avadaredux-framework' ) ?>">&nbsp;</div>
		<div class="clear"></div>
	</div>

	<!-- Notification bar -->
	<div id="avadaredux_notification_bar">
		<?php $this->notification_bar(); ?>
	</div>


</div>
