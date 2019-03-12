<div class="fusion-logo" data-margin-top="<?php echo Avada()->settings->get( 'logo_margin', 'top' ); ?>" data-margin-bottom="<?php echo Avada()->settings->get( 'logo_margin', 'bottom' ); ?>" data-margin-left="<?php echo Avada()->settings->get( 'logo_margin', 'left' ); ?>" data-margin-right="<?php echo Avada()->settings->get( 'logo_margin', 'right' ); ?>">
	<?php
	/**
	 * avada_logo_prepend hook
	 */
	do_action( 'avada_logo_prepend' );
	?>
	<?php if ( ( Avada()->settings->get( 'logo', 'url' ) && '' != Avada()->settings->get( 'logo', 'url' ) ) || ( Avada()->settings->get( 'logo_retina', 'url' ) && '' != Avada()->settings->get( 'logo_retina', 'url' ) ) ) : ?>
		<a class="fusion-logo-link" href="<?php echo home_url(); ?>">
			<?php
			$base_logo = 'default';
			$logo_url = Avada_Sanitize::get_url_with_correct_scheme( Avada()->settings->get( 'logo', 'url' ) );
			$matches = array();

			if ( '' == $logo_url ) {
				$base_logo = 'retina';
				$logo_url = Avada_Sanitize::get_url_with_correct_scheme( Avada()->settings->get( 'logo_retina', 'url' ) );
			}

			preg_match( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif|tiff|svg)$)/i', $logo_url, $matches );
			$logo_attachment_id = Avada()->images->get_attachment_id_from_url( $logo_url );

			if ( isset( $matches[0] ) && $logo_attachment_id ) {
				$thumbnail_dimensions = explode( 'x', $matches[0] );
				$logo_size['width']  = trim( $thumbnail_dimensions[0], '-' );
				$logo_size['height'] = $thumbnail_dimensions[1];				
				
			} else if ( $logo_attachment_id ) {
				$logo_data = wp_get_attachment_image_src( $logo_attachment_id, 'full' );
				if ( 'retina' == $base_logo ) {
					$logo_size['width'] = $logo_data[1] / 2;
					$logo_size['height'] = $logo_data[2] / 2;
				} else {
					$logo_size['width'] = $logo_data[1];
					$logo_size['height'] = $logo_data[2];
				}
			} else {
				$logo_size['width']  = '';
				$logo_size['height'] = '';
			}
			?>
			<img src="<?php echo $logo_url; ?>" width="<?php echo $logo_size['width']; ?>" height="<?php echo $logo_size['height']; ?>" alt="<?php bloginfo( 'name' ); ?>" class="fusion-logo-1x fusion-standard-logo" />

			<?php if ( Avada()->settings->get( 'logo_retina', 'url' ) && '' != Avada()->settings->get( 'logo_retina', 'url' ) ) : ?>
				<?php $retina_logo = Avada_Sanitize::get_url_with_correct_scheme( Avada()->settings->get( 'logo_retina', 'url' ) ); ?>
				<?php $style = 'style="max-height: ' . $logo_size['height'] . 'px; height: auto;"'; ?>
				<img src="<?php echo $retina_logo; ?>" width="<?php echo $logo_size['width']; ?>" height="<?php echo $logo_size['height']; ?>" alt="<?php bloginfo('name'); ?>" <?php echo $style; ?> class="fusion-standard-logo fusion-logo-2x" />
			<?php else: ?>
				<img src="<?php echo $logo_url; ?>" width="<?php echo $logo_size['width']; ?>" height="<?php echo $logo_size['height']; ?>" alt="<?php bloginfo('name'); ?>" class="fusion-standard-logo fusion-logo-2x" />
			<?php endif; ?>

			<!-- mobile logo -->
			<?php if ( Avada()->settings->get( 'mobile_logo', 'url' ) && '' != Avada()->settings->get( 'mobile_logo', 'url' ) ) : ?>
				<?php $mobile_logo_data = Avada()->images->get_logo_data( 'mobile_logo' ); ?>
				<img src="<?php echo $mobile_logo_data['url']; ?>" width="<?php echo $mobile_logo_data['width']; ?>" height="<?php echo $mobile_logo_data['height']; ?>" alt="<?php bloginfo( 'name' ); ?>" class="fusion-logo-1x fusion-mobile-logo-1x" />

				<?php if ( Avada()->settings->get( 'mobile_logo_retina', 'url' ) && '' != Avada()->settings->get( 'mobile_logo_retina', 'url' ) ) : ?>
					<?php
					$retina_mobile_logo_data = Avada()->images->get_logo_data( 'mobile_logo_retina' );
					$style = 'style="max-height: ' . $retina_mobile_logo_data['height'] . 'px; height: auto;"';
					?>
					<img src="<?php echo $retina_mobile_logo_data['url']; ?>" width="<?php echo $retina_mobile_logo_data['width']; ?>" height="<?php echo $retina_mobile_logo_data['height']; ?>" alt="<?php bloginfo('name'); ?>" <?php echo $style; ?> class="fusion-logo-2x fusion-mobile-logo-2x" />
				<?php else: ?>
					<img src="<?php echo $mobile_logo_data['url']; ?>" width="<?php echo $mobile_logo_data['width']; ?>" height="<?php echo $mobile_logo_data['height']; ?>" alt="<?php bloginfo( 'name' ); ?>" class="fusion-logo-2x fusion-mobile-logo-2x" />
				<?php endif; ?>
			<?php endif; ?>

			<!-- sticky header logo -->
			<?php if ( Avada()->settings->get( 'sticky_header_logo', 'url' ) && '' != Avada()->settings->get( 'sticky_header_logo', 'url' ) && ( in_array( Avada()->settings->get( 'header_layout' ), array( 'v1', 'v2', 'v3', 'v6' ) ) || ( ( in_array( Avada()->settings->get( 'header_layout' ), array( 'v4', 'v5' ) ) && Avada()->settings->get( 'header_sticky_type2_layout' ) == 'menu_and_logo' ) ) ) ) : ?>
				<?php $sticky_logo_data = Avada()->images->get_logo_data( 'sticky_header_logo' ); ?>
				<img src="<?php echo $sticky_logo_data['url']; ?>" width="<?php echo $sticky_logo_data['width']; ?>" height="<?php echo $sticky_logo_data['height']; ?>" alt="<?php bloginfo( 'name' ); ?>" class="fusion-logo-1x fusion-sticky-logo-1x" />

				<?php if ( Avada()->settings->get( 'sticky_header_logo_retina', 'url' ) && '' != Avada()->settings->get( 'sticky_header_logo_retina', 'url' ) ) : ?>
					<?php
					$retina_sticky_logo_data = Avada()->images->get_logo_data( 'sticky_header_logo_retina' );
					$style = 'style="max-height: ' . $retina_sticky_logo_data['height'] . 'px; height: auto;"';
					?>
					<img src="<?php echo $retina_sticky_logo_data['url']; ?>" width="<?php echo $retina_sticky_logo_data['width']; ?>" height="<?php echo $retina_sticky_logo_data['height']; ?>" alt="<?php bloginfo('name'); ?>" <?php echo $style; ?> class="fusion-logo-2x fusion-sticky-logo-2x" />
				<?php else: ?>
					<img src="<?php echo $sticky_logo_data['url']; ?>" width="<?php echo $sticky_logo_data['width']; ?>" height="<?php echo $sticky_logo_data['height']; ?>" alt="<?php bloginfo( 'name' ); ?>" class="fusion-logo-2x fusion-sticky-logo-2x" />
				<?php endif; ?>
			<?php endif; ?>
		</a>
	<?php endif; ?>
	<?php
	/**
	 * avada_logo_append hook
	 * @hooked avada_header_content_3 - 10
	 */
	do_action( 'avada_logo_append' );
	?>
</div>
