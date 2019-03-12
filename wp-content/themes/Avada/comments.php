<?php
do_action( 'avada_before_comments' );

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
?>

<?php if ( post_password_required() ) : ?>
	<?php return; ?>
<?php endif; ?>

<?php if ( have_comments() ) : ?>

	<div id="comments" class="comments-container">
		<?php ob_start(); ?>
		<?php comments_number( esc_html__( 'No Comments', 'Avada' ), esc_html__( 'One Comment', 'Avada' ), '% ' . esc_html__( 'Comments', 'Avada' ) ); ?>
		<?php echo Avada()->template->title_template( ob_get_clean(), '3' ); ?>

		<?php if ( function_exists( 'the_comments_navigation' ) ) : ?>
			<?php the_comments_navigation(); ?>
		<?php endif; ?>

		<ol class="comment-list commentlist">
			<?php wp_list_comments( 'callback=avada_comment' ); ?>
		</ol><!-- .comment-list -->

		<?php if ( function_exists( 'the_comments_navigation' ) ) : ?>
			<?php the_comments_navigation(); ?>
		<?php endif; ?>
	</div>

<?php endif; ?>

<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
	<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'Avada' ); ?></p>
<?php endif; ?>

<?php if ( comments_open() ) : ?>
	<?php
	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ) ? " aria-required='true'" : '';
	$html_req  = ( $req ) ? " required='required'" : '';
	$html5     = ( 'html5' === current_theme_supports( 'html5', 'comment-form' ) ) ? 'html5' : 'xhtml';

	$fields = array();

	$fields['author'] = '<div id="comment-input"><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" placeholder="' . esc_html__( 'Name (required)', 'Avada' ) . '" size="30"' . $aria_req . $html_req . ' />';
	$fields['email']  = '<input id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" placeholder="' . esc_html__( 'Email (required)', 'Avada' ) . '" size="30" aria-describedby="email-notes"' . $aria_req . $html_req  . ' />';
	$fields['url']    = '<input id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="' . esc_html__( 'Website', 'Avada' ) . '" size="30" /></div>';

	$comments_args = array(
		'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
		'comment_field'        => '<div id="comment-textarea"><label class="screen-reader-text" for="comment">' . esc_attr__( 'Comment', 'Avada' ) . '</label><textarea name="comment" id="comment" cols="45" rows="8" aria-required="true" required="required" tabindex="0" class="textarea-comment" placeholder="' . esc_html__( 'Comment...', 'Avada' ) . '"></textarea></div>',
		'title_reply'          => esc_html__( 'Leave A Comment', 'Avada' ),
		'title_reply_to'       => esc_html__( 'Leave A Comment', 'Avada' ),
		'must_log_in'          => '<p class="must-log-in">' .  sprintf( esc_html__( 'You must be %slogged in%s to post a comment.', 'Avada' ), '<a href="' . wp_login_url( apply_filters( 'the_permalink', get_permalink() ) ) . '">', '</a>' ) . '</p>',
		'logged_in_as'         => '<p class="logged-in-as">' . sprintf( esc_html__( 'Logged in as %s. %sLog out &raquo;%s', 'Avada' ), '<a href="' . admin_url( 'profile.php' ) . '">' . $user_identity . '</a>', '<a href="' . wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) ) . '" title="' . esc_html__( 'Log out of this account', 'Avada' ) . '">', '</a>' ) . '</p>',
		'comment_notes_before' => '',
		'id_submit'            => 'comment-submit',
		'class_submit'         => 'fusion-button fusion-button-default',
		'label_submit'         => esc_html__( 'Post Comment', 'Avada' ),
	);
	?>

	<?php comment_form( $comments_args ); ?>

<?php endif;

// Omit closing PHP tag to avoid "Headers already sent" issues.
