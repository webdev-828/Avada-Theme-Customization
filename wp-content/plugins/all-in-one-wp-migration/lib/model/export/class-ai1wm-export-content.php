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

class Ai1wm_Export_Content {

	public static function execute( $params ) {

		// Set content offset
		if ( isset( $params['content_offset'] ) ) {
			$content_offset = (int) $params['content_offset'];
		} else {
			$content_offset = 0;
		}
		// Set filemap offset
		if ( isset( $params['filemap_offset'] ) ) {
			$filemap_offset = (int) $params['filemap_offset'];
		} else {
			$filemap_offset = 0;
		}

		// Get total files
		if ( isset( $params['total_files'] ) ) {
			$total_files = (int) $params['total_files'];
		} else {
			$total_files = 1;
		}

		// Get total size
		if ( isset( $params['total_size'] ) ) {
			$total_size = (int) $params['total_size'];
		} else {
			$total_size = 1;
		}

		// Get processed files
		if ( isset( $params['processed'] ) ) {
			$processed = (int) $params['processed'];
		} else {
			$processed = 0;
		}

		// What percent of files have we processed?
		$progress = (int) ( ( $processed / $total_size ) * 100 );

		// Set progress
		if ( empty( $content_offset ) ) {
			Ai1wm_Status::info( sprintf( __( 'Archiving %d files...<br />%d%% complete', AI1WM_PLUGIN_NAME ), $total_files, $progress ) );
		}

		// Get map file
		$filemap = fopen( ai1wm_filemap_path( $params ), 'r' );

		// Start time
		$start = microtime( true );

		// Flag to hold if all files have been processed
		$completed = true;

		// Set filemap pointer at the current index
		if ( fseek( $filemap, $filemap_offset ) !== -1 ) {

			// Get archive
			$archive = new Ai1wm_Compressor( ai1wm_archive_path( $params ) );

			while ( $path = trim( fgets( $filemap ) ) ) {
				try {

					// Add file to archive
					if ( ( $content_offset = $archive->add_file( WP_CONTENT_DIR . DIRECTORY_SEPARATOR . $path, $path, $content_offset, 10 ) ) ) {

						// Set progress
						if ( ( $processed += $content_offset ) ) {
							$progress = (int) ( ( $processed / $total_size ) * 100 );
						}

						// Set progress
						Ai1wm_Status::info( sprintf( __( 'Archiving %d files...<br />%d%% complete', AI1WM_PLUGIN_NAME ), $total_files, $progress ) );

						// Set content offset
						$params['content_offset'] = $content_offset;

						// Set filemap offset
						$params['filemap_offset'] = $filemap_offset;

						// Set completed flag
						$params['completed'] = false;

						// Close the filemap file
						fclose( $filemap );

						return $params;
					}

					// Set content offset
					$content_offset = 0;

					// Set filemap offset
					$filemap_offset = ftell( $filemap );

				} catch ( Exception $e ) {
					// Skip bad file permissions
				}

				// Increment processed files
				$processed += $archive->get_current_filesize();

				// More than 10 seconds have passed, break and do another request
				if ( ( microtime( true ) - $start ) > 10 ) {
					$completed = false;
					break;
				}
			}

			$archive->close();
		}

		// Set content offset
		$params['content_offset'] = $content_offset;

		// Set filemap offset
		$params['filemap_offset'] = $filemap_offset;

		// Set processed files
		$params['processed'] = $processed;

		// Set completed flag
		$params['completed'] = $completed;

		// Close the filemap file
		fclose( $filemap );

		return $params;
	}
}
