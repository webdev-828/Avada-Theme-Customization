<?php
/**
 * Copyright (C) 2014-2016 ServMask Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * ███████╗███████╗██████╗ ██╗   ██╗███╗   ███╗ █████╗ ███████╗██╗  ██╗
 * ██╔════╝██╔════╝██╔══██╗██║   ██║████╗ ████║██╔══██╗██╔════╝██║ ██╔╝
 * ███████╗█████╗  ██████╔╝██║   ██║██╔████╔██║███████║███████╗█████╔╝
 * ╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║╚██╔╝██║██╔══██║╚════██║██╔═██╗
 * ███████║███████╗██║  ██║ ╚████╔╝ ██║ ╚═╝ ██║██║  ██║███████║██║  ██╗
 * ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
 */

class Ai1wm_Import_Upload {

	public static function execute( $params ) {

		// Get upload file
		if ( ! isset( $_FILES['upload-file'] ) ) {
			return $params;
		}

		// Set chunk
		if ( isset( $params['chunk'] ) ) {
			$chunk = (int) $params['chunk'];
		} else {
			$chunk = 0;
		}

		// Set chunks
		if ( isset( $params['chunks'] ) ) {
			$chunks = (int) $params['chunks'];
		} else {
			$chunks = 1;
		}

		// Has any upload error?
		if ( empty( $_FILES['upload-file']['error'] ) ) {

			// Open partial file
			$out = fopen( ai1wm_archive_path( $params ), $chunk === 0 ? 'wb' : 'ab' );
			if ( $out ) {

				// Read binary input stream and append it to temp file
				$in = fopen( $_FILES['upload-file']['tmp_name'], 'rb' );
				if ( $in ) {
					while ( $buff = fread( $in, 4096 ) ) {
						fwrite( $out, $buff );
					}
				}

				fclose( $in );
				fclose( $out );

				// Remove temporary uploaded file
				unlink( $_FILES['upload-file']['tmp_name'] );
			} else {
				status_header( 500 );
			}
		} else {
			status_header( 500 );
		}

		exit;
	}
}
