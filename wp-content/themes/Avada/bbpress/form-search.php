<?php

/**
 * Search
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<form role="search" method="get" class="searchform bbp-search-form search-form" action="<?php bbp_search_url(); ?>">
	<div class="search-table">
		<label class="screen-reader-text hidden" for="bbp_search"><?php _e( 'Search for:', 'bbpress' ); ?></label>
		<input type="hidden" name="action" value="bbp-search-request" />
		<div class="search-field">
			<input tabindex="<?php bbp_tab_index(); ?>" type="text" value="<?php echo esc_attr( bbp_get_search_terms() ); ?>" placeholder="<?php _e( 'Search the Forum...', 'Avada' ); ?>" name="bbp_search" id="bbp_search" />
		</div>
		<div class="search-button">
			<input tabindex="<?php bbp_tab_index(); ?>" class="fusion-button button submit" type="submit" id="bbp_search_submit" value="&#xf002;" />
		</div>
		<div class="clearfix"></div>
	</div>
</form>
