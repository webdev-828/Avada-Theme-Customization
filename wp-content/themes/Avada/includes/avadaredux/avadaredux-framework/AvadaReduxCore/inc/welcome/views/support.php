<div class="wrap about-wrap" xmlns="http://www.w3.org/1999/html">
	<h1><?php esc_html_e( 'AvadaRedux Framework - Support', 'avadaredux-framework' ); ?></h1>
	<div class="about-text">
		<?php esc_html_e( 'We are an open source project used by developers to make powerful control panels.', 'avadaredux-framework' ); ?>
	</div>
	<div class="avadaredux-badge">
		<i class="el el-avadaredux"></i>
		<span><?php printf( __( 'Version %s', 'avadaredux-framework' ), esc_html(AvadaReduxFramework::$_version) ); ?></span>
	</div>

	<?php $this->actions(); ?>
	<?php $this->tabs(); ?>

	<div id="support_div" class="support">

		<!-- multistep form -->
		<form id="supportform">
			<ul id="progressbar" class=" breadcrumb">
				<li class="active"><?php esc_html_e( 'Generate a Support URL', 'avadaredux-framework' ); ?></li>
				<li href="#"><?php esc_html_e( 'Select Support Type', 'avadaredux-framework' ); ?></li>
				<li href="#"><?php esc_html_e( 'How to Get Support', 'avadaredux-framework' ); ?></li>
			</ul>

			<!-- fieldsets -->
			<fieldset>
				<h2 class="fs-title">
					<?php esc_html_e( 'Submit a Support Request', 'avadaredux-framework' ); ?>
				</h2>

				<h3 class="fs-title" style="margin-top:0;">
					<?php esc_html_e( 'To get started, we will need to generate a support hash.', 'avadaredux-framework' ); ?>
				</h3>

				<p>
					<?php echo sprintf( wp_kses( __( 'This will provide to your developer all the information they may need to remedy your issue. This action WILL send information securely to a remote server. To see the type of information sent, please look at the  <a href="%s">Status tab</a>.', 'avadaredux-framework' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'tools.php?page=avadaredux-status' ) ) ); ?>
				</p>

				<p>
					<a href="#" class="docs button button-primary button-large avadaredux_support_hash">
						<?php esc_html_e( 'Generate a Support URL', 'avadaredux-framework' ); ?>
					</a>
				</p>
				<input type="button"
					   name="next"
					   class="next hide action-button"
					   value="Next"
				/>
			</fieldset>

			<fieldset>
				<h2 class="fs-title">
					<?php esc_html_e( 'Select Your Support Type', 'avadaredux-framework' ); ?>
				</h2>

				<h3 class="fs-subtitle" style="text-align: center;">
					<?php esc_html_e( 'What type of user are you?', 'avadaredux-framework' ); ?>
				</h3>

				<table id="user_type">
					<tr>
						<td id="is_user">
							<i class="el el-user"></i><br/>
							<?php esc_html_e( 'User', 'avadaredux-framework' ); ?><br/>
							<small>
								<?php esc_html_e( 'I am a user, using a pre-built product.', 'avadaredux-framework' ); ?>
							</small>
						</td>
						<td id="is_developer">
							<i class="el el-github"></i><br/>
							<?php esc_html_e( 'Developer', 'avadaredux-framework' ); ?><br/>
							<small>
								<?php esc_html_e( 'I am a developer, building a product using AvadaRedux.', 'avadaredux-framework' ); ?>
							</small>
						</td>
					</tr>
				</table>

				<input type="button"
					   name="next"
					   class="next action-button hide"
					   value="Next"
				/>
			</fieldset>

			<fieldset id="final_support">
				<h2 class="fs-title">
					<?php esc_html_e( 'How to Get Support', 'avadaredux-framework' ); ?>
				</h2>

				<div class="is_developer">
					<p>
						<?php esc_html_e( 'Please proceed to the AvadaRedux Framework issue tracker and supply us with your support URL below. Please also provide any information that will help us to reproduce your issue.', 'avadaredux-framework' ); ?>
					</p>
					<a href="<?php echo esc_url('https://github.com/avadareduxframework/avadaredux-framework/issues') ?>" target="_blank">
						<h4>https://github.com/avadareduxframework/avadaredux-framework/issues</h4>
					</a>
				</div>

				<div class="is_user">
					<p align="left">
						<?php esc_html_e( 'Listed below are the Wordpress plugins and/or theme installed on your site that utilize AvadaRedux Framework. We do not directly support products created with our framework.  It is the responsibility of the plugin or theme developer to support their work. You will need to contact the author(s) of the products listed below with your support questions.', 'avadaredux-framework' ); ?>
					</p>
					<p>
						<strong>
							<?php esc_html_e( 'Please be sure to include for your developer - via cut and paste - the Support URL in the box below.', 'avadaredux-framework' ); ?>
						</strong>
					</p>
<?php
						$avadaredux = AvadaReduxFrameworkInstances::get_all_instances();

						if ( ! empty( $avadaredux ) ) {
							echo '<code style="line-height: 30px;">';
							foreach ( $avadaredux as $panel ) {
								echo '&nbsp;' . esc_html($panel->args['display_name']) . '';
								if ( ! empty( $panel->args['display_version'] ) ) {
									echo ' v' . esc_html($panel->args['display_version']);
								}
								echo '&nbsp;<br />';
							}
							echo '</code><br />';
						}
?>
				</div>
				<textarea type="text"
						  id="support_hash"
						  name="hash"
						  placeholder="Support Hash"
						  readonly="readonly"
						  class="hash"
						  value="http://support.avadaredux.io/"></textarea>

				<p>
					<em>
						<?php echo sprintf( wp_kses( __( 'Should the developer not be responsive, read the <a href="%s" target="_blank">following article</a> before asking for support from us directly.', 'avadaredux-framework' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( 'http://docs.avadareduxframework.com/core/support-defined/' ) );?>
					</em>
				</p>
				<input type="button"
					   name="previous"
					   class="previous action-button"
					   value="Go Back"
				/>
			</fieldset>
		</form>
		<div class="clear" style="clear:both;"></div>
	</div>
</div>
