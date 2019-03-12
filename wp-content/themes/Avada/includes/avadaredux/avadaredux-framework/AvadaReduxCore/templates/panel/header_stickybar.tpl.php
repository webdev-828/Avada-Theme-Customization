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
			<?php esc_attr_e( 'Expand', 'avadaredux-framework' ); ?>
		</a>

		<div class="avadaredux-action_bar">
			<span class="spinner"></span>
			<?php if ( false === $this->parent->args['hide_save'] ) { ?>
				<?php submit_button( esc_attr__( 'Save Changes', 'avadaredux-framework' ), 'primary', 'avadaredux_save', false ); ?>
			<?php } ?>

			<?php if ( false === $this->parent->args['hide_reset'] ) { ?>
				<?php submit_button( esc_attr__( 'Reset Section', 'avadaredux-framework' ), 'secondary', $this->parent->args['opt_name'] . '[defaults-section]', false, array( 'id' => 'avadaredux-defaults-section' ) ); ?>
				<?php submit_button( esc_attr__( 'Reset All', 'avadaredux-framework' ), 'secondary', $this->parent->args['opt_name'] . '[defaults]', false, array( 'id' => 'avadaredux-defaults' ) ); ?>
			<?php } ?>
		</div>
		<div class="avadaredux-ajax-loading" alt="<?php esc_attr_e( 'Working...', 'avadaredux-framework' ) ?>">&nbsp;</div>
		<div class="clear"></div>
	</div>

	<!-- Notification bar -->
	<div id="avadaredux_notification_bar">
		<?php $this->notification_bar(); ?>
	</div>


</div>
