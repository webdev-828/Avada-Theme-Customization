<?php

class Avada_Multiple_Featured_Images {

	public function __construct() {
		if ( is_admin() ) {
			add_action( 'after_setup_theme', array( $this, 'generate' ) );
		}
	}

	public function generate() {
		$post_types = array(
			'post',
			'page',
			'avada_portfolio',
		);

		if ( ! class_exists( 'kdMultipleFeaturedImages' ) ) {
			return;
		}

		$i = 2;

		while ( $i <= Avada()->settings->get( 'posts_slideshow_number' ) ) {

			foreach ( $post_types as $post_type ) {
				new kdMultipleFeaturedImages( array(
					'id'         => 'featured-image-' . $i,
					'post_type'  => $post_type,
					'labels'     => array(
						'name'   => sprintf( __( 'Featured image %s', 'Avada' ), $i ),
						'set'	 => sprintf( __( 'Set featured image %s', 'Avada' ), $i ),
						'remove' => sprintf( __( 'Remove featured image %s', 'Avada' ), $i ),
						'use'    => sprintf( __( 'Use as featured image %s', 'Avada' ), $i ),
					)
				) );
			}

			$i++;

		}

	}

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
