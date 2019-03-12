<?php

class Avada_Fonts {

	public function __construct() {
		add_filter( 'upload_mimes', array( $this, 'mime_types' ) );
	}

	/**
	 * Allow uploading font file types.
	 */
	public function mime_types( $mimes ) {

		$mimes['ttf']  = 'font/ttf';
		$mimes['woff'] = 'font/woff';
		$mimes['svg']  = 'font/svg';
		$mimes['eot']  = 'font/eot';

		return $mimes;

	}

}

// Omit closing PHP tag to avoid "Headers already sent" issues.
