<?php if ( Avada()->settings->get( 'disable_date_rich_snippet_pages' ) ) : ?>

	<?php if ( $title_tag ) : ?>
		<span class="entry-title" style="display: none;">
			<?php echo get_the_title(); ?>
		</span>
	<?php endif; ?>

	<?php if ( $author_tag ) : ?>
		<span class="vcard" style="display: none;">
			<span class="fn">
				<?php the_author_posts_link(); ?>
			</span>
		</span>
	<?php endif; ?>

	<?php if ( $updated_tag ) : ?>
		<span class="updated" style="display:none;">
			<?php echo get_the_modified_time( 'c' ); ?>
		</span>
	<?php endif; ?>

<?php endif;

// Omit closing PHP tag to avoid "Headers already sent" issues.
