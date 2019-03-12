<?php

/**
 * Class for working with ZIP archives to import
 * sliders with images and other attachments.
 *
 * @package LS_ImportUtil
 * @since 5.0.3
 * @author John Gera
 * @copyright Copyright (c) 2013  John Gera, George Krupa, and Kreatura Media Kft.
 * @license http://codecanyon.net/licenses/faq Envato marketplace licenses
 */

class LS_ImportUtil {

	/**
	 * The managed ZipArchieve instance.
	 */
	private $zip;

	/**
	 * Target folders
	 */
	private $targetDir, $targetURL, $tmpDir;

	// Imported images
	private $imported = array();


	// Accepts $_FILES
	public function __construct($archive, $name = null) {

		if(empty($name)) {
			$name = $archive;
		}

		// TODO: check file extension to support old import method
		$type = wp_check_filetype(basename($name), array(
			'zip' => 'application/zip',
			'json' => 'application/json'
		));

		// Check for ZIP
		if(!empty($type['ext']) && $type['ext'] == 'zip') {
			if(class_exists('ZipArchive')) {

				// Remove previous uploads (if any)
				$this->cleanup();

				// Extract ZIP
				$this->zip = new ZipArchive;
				if($this->zip->open($archive)) {
					if($this->unpack($archive)) {

						// Uploaded folders
						foreach(glob($this->tmpDir.'/*', GLOB_ONLYDIR) as $key => $dir) {

							$this->imported = array();

							if(!isset($_POST['skip_images'])) {
								$this->uploadMedia($dir);
							}

							if(file_exists($dir.'/settings.json')) {
								$this->addSlider($dir.'/settings.json');
							}
						}

						// Finishing up
						$this->cleanup();
						return true;
					}

					// Close ZIP
					$this->zip->close();
				}
			} else {
				header('Location: admin.php?page=layerslider&error=1&message=exportZipError');
				die();
			}


		// Check for JSON
		} elseif(!empty($type['ext']) && $type['ext'] == 'json') {

			// Get decoded file data
			$data = file_get_contents($archive);
			if($decoded = base64_decode($data, true)) {
				if(!$parsed = json_decode($decoded, true)) {
					$parsed = unserialize($decoded);
				}

			// Since v5.1.1
			} else {
				$parsed = array(json_decode($data, true));
			}

			// Iterate over imported sliders
			if(is_array($parsed)) {

				// Import sliders
				foreach($parsed as $item) {

					// Fix for export issue in v4.6.4
					if(is_string($item)) { $item = json_decode($item, true); }

					LS_Sliders::add($item['properties']['title'], $item);
				}
			}
		}

		// Return false otherwise
		return false;
	}



	public function unpack($archive) {

		// Get uploads folder
		$uploads = wp_upload_dir();

		// Check if /uploads dir is writable
		if(is_writable($uploads['basedir'])) {

			// Get target folders
			$this->targetDir = $targetDir = $uploads['basedir'].'/layerslider';
			$this->targetURL = $uploads['baseurl'].'/layerslider';
			$this->tmpDir = $tmpDir = $uploads['basedir'].'/layerslider/tmp';

			// Create necessary folders under /uploads
			if(!file_exists($targetDir)) { mkdir($targetDir, 0755); }
			if(!file_exists($targetDir)) { mkdir($targetDir, 0755); }

			// Unpack archive
			if($this->zip->extractTo($tmpDir)) {
				return true;
			}
		}

		return false;
	}




	public function uploadMedia($dir = null) {

		// Check provided data
		if(empty($dir) || !is_string($dir) || !file_exists($dir.'/uploads')) {
			return false;
		}

		// Create folder if it isn't exists already
		$targetDir = $this->targetDir . '/' . basename($dir);
		if(!file_exists($targetDir)) { mkdir($targetDir, 0755); }

		// Include image.php for media library upload
		require_once(ABSPATH.'wp-admin/includes/image.php');

		// Iterate through directory
		foreach(glob($dir.'/uploads/*') as $filePath) {

			$fileName = sanitize_file_name(basename($filePath));
			$targetFile = $targetDir.'/'.$fileName;

			// Validate media
			$filetype = wp_check_filetype($fileName, null);
			if(!empty($filetype['ext']) && $filetype['ext'] != 'php') {

				// Move item to place
				rename($filePath, $targetFile);

				// Upload to media library
				$attachment = array(
					'guid' => $targetFile,
					'post_mime_type' => $filetype['type'],
					'post_title' => preg_replace( '/\.[^.]+$/', '', $fileName),
					'post_content' => '',
					'post_status' => 'inherit'
				);

				$attach_id = wp_insert_attachment($attachment, $targetFile, 37);
				if($attach_data = wp_generate_attachment_metadata($attach_id, $targetFile)) {
					wp_update_attachment_metadata($attach_id, $attach_data);
				}

				$this->imported[$fileName] = array(
					'id' => $attach_id,
					'url' => $this->targetURL.'/'.basename($dir).'/'.$fileName
				);
			}
		}

		return true;
	}



	public function deleteDir($dir) {
		if(!file_exists($dir)) return true;
		if(!is_dir($dir)) return unlink($dir);
		foreach(scandir($dir) as $item) {
			if($item == '.' || $item == '..') continue;
			if(!$this->deleteDir($dir.DIRECTORY_SEPARATOR.$item)) return false;
		}
		return rmdir($dir);
	}




	public function addSlider($file) {

		// Get slider data and title
		$data = json_decode(file_get_contents($file), true);
		$title = $data['properties']['title'];
		$slug = !empty($data['properties']['slug']) ? $data['properties']['slug'] : '';

		// Slider settings
		if(!empty($data['properties']['backgroundimage'])) {
			$data['properties']['backgroundimage'] = $this->attachURLForImage(
				$data['properties']['backgroundimage']
			);
		}

		if(!empty($data['properties']['yourlogo'])) {
			$data['properties']['yourlogoId'] = $this->attachIDForImage($data['properties']['yourlogo']);
			$data['properties']['yourlogo'] = $this->attachURLForImage($data['properties']['yourlogo']);
		}


		// Slides
		if(!empty($data['layers']) && is_array($data['layers'])) {
		foreach($data['layers'] as &$slide) {

			if(!empty($slide['properties']['background'])) {
				$slide['properties']['backgroundId'] = $this->attachIDForImage($slide['properties']['background']);
				$slide['properties']['background'] = $this->attachURLForImage($slide['properties']['background']);
			}

			if(!empty($slide['properties']['thumbnail'])) {
				$slide['properties']['thumbnailId'] = $this->attachIDForImage($slide['properties']['thumbnail']);
				$slide['properties']['thumbnail'] = $this->attachURLForImage($slide['properties']['thumbnail']);
			}

			// Layers
			if(!empty($slide['sublayers']) && is_array($slide['sublayers'])) {
			foreach($slide['sublayers'] as &$layer) {

				if(!empty($layer['image'])) {
					$layer['imageId'] = $this->attachIDForImage($layer['image']);
					$layer['image'] = $this->attachURLForImage($layer['image']);
				}
			}}
		}}

		// Add slider
		LS_Sliders::add($title, $data, $slug);
	}



	public function attachURLForImage($file = '#') {

		if(isset($this->imported[basename($file)])) {
			return $this->imported[basename($file)]['url'];
		}

		return $file;
	}


	public function attachIDForImage($file = '') {

		if(isset($this->imported[basename($file)])) {
			return $this->imported[basename($file)]['id'];
		}

		return $file;
	}



	public function cleanup() {
		$this->deleteDir($this->tmpDir);
	}
}
?>