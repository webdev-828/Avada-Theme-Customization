<div class="wrap about-wrap">
	<h1><?php esc_html_e( 'AvadaRedux Framework - A Community Effort', 'avadaredux-framework' ); ?></h1>

	<div class="about-text">
		<?php esc_html_e( 'We recognize we are nothing without our community. We would like to thank all of those who help AvadaRedux to be what it is. Thank you for your involvement.', 'avadaredux-framework' ); ?>
	</div>
	<div class="avadaredux-badge">
		<i class="el el-avadaredux"></i>
		<span>
			<?php printf( __( 'Version %s', 'avadaredux-framework' ), esc_html(AvadaReduxFramework::$_version )); ?>
		</span>
	</div>

	<?php $this->actions(); ?>
	<?php $this->tabs(); ?>

	<p class="about-description">
		<?php echo sprintf( __( 'AvadaRedux is created by a community of developers world wide. Want to have your name listed too? <a href="%d" target="_blank">Contribute to AvadaRedux</a>.', 'avadaredux-framework' ), 'https://github.com/avadareduxframework/avadaredux-framework/blob/master/CONTRIBUTING.md' );?>
	</p>

	<?php echo wp_kses_post($this->contributors()); ?>
</div>
