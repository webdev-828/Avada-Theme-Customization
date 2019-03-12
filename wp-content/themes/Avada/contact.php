<?php
// Template Name: Contact
?>

<?php get_header(); ?>

<?php
/**
 * Instantiate the Avada_Contact class
 */
$avada_contact = new Avada_Contact();
?>
<div id="content" <?php Avada()->layout->add_style( 'content_style' ); ?>>
	<?php while ( have_posts() ) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php echo avada_render_rich_snippets_for_pages(); ?>
			<?php echo avada_featured_images_for_pages(); ?>
			<div class="post-content">
				<?php the_content(); ?>

				<?php if ( ! Avada()->settings->get( 'email_address' ) ) : // Email address not set ?>
					<?php if ( shortcode_exists( 'alert' ) ) : ?>
						<?php echo do_shortcode( '[alert type="error" accent_color="" background_color="" border_size="1px" icon="" box_shadow="yes" animation_type="0" animation_direction="down" animation_speed="0.1" class="" id=""]' . esc_html__( 'Form email address is not set in Theme Options. Please fill in a valid address to make contact form work.', 'Avada' ) . '[/alert]' ); ?>
					<?php else : ?>
						<h2 style="color:#b94a48;"><?php esc_html_e( 'Form email address is not set in Theme Options. Please fill in a valid address to make contact form work.', 'Avada' ); ?></h2>
					<?php endif; ?>
					<br />
				<?php endif; ?>

				<?php if ( $avada_contact->has_error ) : //If errors are found ?>
					<?php if ( shortcode_exists( 'alert' ) ) : ?>
						<?php echo do_shortcode( '[alert type="error" accent_color="" background_color="" border_size="1px" icon="" box_shadow="yes" animation_type="0" animation_direction="down" animation_speed="0.1" class="" id=""]' . esc_html__( 'Please check if you\'ve filled all the fields with valid information. Thank you.', 'Avada' ) . '[/alert]' ); ?>
					<?php else : ?>
						<h3 style="color:#b94a48;"><?php esc_html_e( 'Please check if you\'ve filled all the fields with valid information. Thank you.', 'Avada' ); ?></h3>
					<?php endif; ?>
					<br />
				<?php endif; ?>

				<?php if ( $avada_contact->email_sent ) : //If email is sent ?>
					<?php if ( shortcode_exists( 'alert' ) ) : ?>
						<?php echo do_shortcode( '[alert type="success" accent_color="" background_color="" border_size="1px" icon="" box_shadow="yes" animation_type="0" animation_direction="down" animation_speed="0.1" class="" id=""]' . sprintf( __( 'Thank you %s for using our contact form! Your email was successfully sent!', 'Avada' ), '<strong>' . $name . '</strong>' ) . '[/alert]' ); ?>
					<?php else : ?>
						<h3 style="color:#468847;"><?php printf( __( 'Thank you %s for using our contact form! Your email was successfully sent!', 'Avada' ), '<strong>' . $name . '</strong>' ); ?></h3>
					<?php endif; ?>
					<br />
				<?php endif; ?>
			</div>

			<form action="" method="post" class="avada-contact-form">
				<?php if ( 'above' == Avada()->settings->get( 'contact_comment_position' ) ) : ?>
					<div id="comment-textarea">
						<textarea name="msg" id="comment" cols="39" rows="4" tabindex="4" class="textarea-comment" placeholder="<?php esc_html_e( 'Message', 'Avada' ); ?>"><?php echo ( isset( $_POST['msg'] ) && ! empty( $_POST['msg'] ) ) ? esc_html( $_POST['msg'] ) : ''; ?></textarea>
					</div>
				<?php endif; ?>

				<div id="comment-input">
					<input type="text" name="contact_name" id="author" value="<?php echo esc_html( $avada_contact->name ); ?>" placeholder="<?php esc_html_e( 'Name (required)', 'Avada' ); ?>" size="22" tabindex="1" aria-required="true" class="input-name">
					<input type="text" name="email" id="email" value="<?php echo esc_html( $avada_contact->email ); ?>" placeholder="<?php esc_html_e( 'Email (required)', 'Avada' ); ?>" size="22" tabindex="2" aria-required="true" class="input-email">
					<input type="text" name="url" id="url" value="<?php echo esc_html( $avada_contact->subject ); ?>" placeholder="<?php esc_html_e( 'Subject', 'Avada' ); ?>" size="22" tabindex="3" class="input-website">
				</div>

				<?php if ( 'above' != Avada()->settings->get( 'contact_comment_position' ) ) : ?>
					<div id="comment-textarea" class="fusion-contact-comment-below">
						<textarea name="msg" id="comment" cols="39" rows="4" tabindex="4" class="textarea-comment" placeholder="<?php esc_html_e( 'Message', 'Avada' ); ?>"><?php echo ( isset( $_POST['msg'] ) && ! empty( $_POST['msg'] ) ) ? esc_html( $_POST['msg'] ) : ''; ?></textarea>
					</div>
				<?php endif; ?>

				<?php if ( Avada()->settings->get( 'recaptcha_public' ) && Avada()->settings->get( 'recaptcha_private' ) ) : ?>

					<div id="comment-recaptcha">
						<div class="g-recaptcha" data-type="audio" data-theme="<?php echo Avada()->settings->get( 'recaptcha_color_scheme' ); ?>" data-sitekey="<?php echo Avada()->settings->get( 'recaptcha_public' ); ?>"></div>
						<script type="text/javascript" src="https://www.google.com/recaptcha/api.js?hl=<?php echo get_locale(); ?>"></script>
					</div>

				<?php endif; ?>

				<div id="comment-submit-container">
					<input name="submit" type="submit" id="submit" tabindex="5" value="<?php esc_html_e( 'Submit Form', 'Avada' ); ?>" class="comment-submit fusion-button fusion-button-default fusion-button-<?php echo strtolower( Avada()->settings->get( 'button_size' ) ); ?> fusion-button-<?php echo strtolower( Avada()->settings->get( 'button_shape' ) ); ?> fusion-button-<?php echo strtolower( Avada()->settings->get( 'button_type' ) ); ?>">
				</div>
			</form>
		</div>
	<?php endwhile; ?>
</div>
<?php do_action( 'avada_after_content' ); ?>
<?php get_footer();

// Omit closing PHP tag to avoid "Headers already sent" issues.
