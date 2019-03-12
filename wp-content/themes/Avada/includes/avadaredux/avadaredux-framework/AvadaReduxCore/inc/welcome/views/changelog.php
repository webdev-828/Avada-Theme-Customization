<div class="wrap about-wrap">
	<h1><?php esc_html_e( 'AvadaRedux Framework - Changelog', 'avadaredux-framework' ); ?></h1>

	<div class="about-text">
		<?php esc_html_e( 'Our core mantra at AvadaRedux is backwards compatibility. With hundreds of thousands of instances worldwide, you can be assured that we will take care of you and your clients.', 'avadaredux-framework' ); ?>
	</div>
	<div class="avadaredux-badge">
		<i class="el el-avadaredux"></i>
		<span>
			<?php printf( __( 'Version %s', 'avadaredux-framework' ), esc_html(AvadaReduxFramework::$_version) ); ?>
		</span>
	</div>

	<?php $this->actions(); ?>
	<?php $this->tabs(); ?>

	<div class="changelog">
		<div class="feature-section">
			<?php echo wp_kses_post($this->parse_readme()); ?>
		</div>
	</div>

</div>
