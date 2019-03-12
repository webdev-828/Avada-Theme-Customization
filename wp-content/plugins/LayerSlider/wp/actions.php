<?php

add_action('init', 'ls_register_form_actions');
function ls_register_form_actions() {
	if(current_user_can(get_option('layerslider_custom_capability', 'manage_options'))) {

		// Remove slider
		if(isset($_GET['page']) && $_GET['page'] == 'layerslider' && isset($_GET['action']) && $_GET['action'] == 'remove') {
			if(check_admin_referer('remove_'.$_GET['id'])) {
				add_action('admin_init', 'layerslider_removeslider');
			}
		}

		// Restore slider
		if(isset($_GET['page']) && $_GET['page'] == 'layerslider' && isset($_GET['action']) && $_GET['action'] == 'restore') {
			if(check_admin_referer('restore_'.$_GET['id'])) {
				LS_Sliders::restore( (int) $_GET['id'] );
				header('Location: admin.php?page=layerslider'); die();
			}
		}

		// Duplicate slider
		if(isset($_GET['page']) && $_GET['page'] == 'layerslider' && isset($_GET['action']) && $_GET['action'] == 'duplicate') {
			if(check_admin_referer('duplicate_'.$_GET['id'])) {
				add_action('admin_init', 'layerslider_duplicateslider');
			}
		}

		// Import sample sliders
		if(isset($_GET['page']) && $_GET['page'] == 'layerslider' && isset($_GET['action']) && $_GET['action'] == 'import_sample') {
			if(check_admin_referer('import-sample-sliders')) {
				add_action('admin_init', 'layerslider_import_sample_slider');
			}
		}

		// Slider list bulk actions
		if(isset($_POST['ls-bulk-action'])) {
			if(check_admin_referer('bulk-action')) {
				add_action('admin_init', 'ls_sliders_bulk_action');
			}
		}

		// Add new slider
		if(isset($_POST['ls-add-new-slider'])) {
			if(check_admin_referer('add-slider')) {
				add_action('admin_init', 'ls_add_new_slider');
			}
		}

		// Google Fonts
		if(isset($_POST['ls-save-google-fonts'])) {
			if(check_admin_referer('save-google-fonts')) {
				add_action('admin_init', 'ls_save_google_fonts');
			}
		}

		// Advanced settings
		if(isset($_POST['ls-save-advanced-settings'])) {
			if(check_admin_referer('save-advanced-settings')) {
				add_action('admin_init', 'ls_save_advanced_settings');
			}
		}

		// Access permission
		if(isset($_POST['ls-access-permission'])) {
			if(check_admin_referer('save-access-permissions')) {
				add_action('admin_init', 'ls_save_access_permissions');
			}
		}

		// Import sliders
		if(isset($_POST['ls-import'])) {
			if(check_admin_referer('import-sliders')) {
				add_action('admin_init', 'ls_import_sliders');
			}
		}

		// Export sliders
		if(isset($_POST['ls-export'])) {
			if(check_admin_referer('export-sliders')) {
				add_action('admin_init', 'ls_export_sliders');
			}
		}

		// Custom CSS editor
		if(isset($_POST['ls-user-css'])) {
			if(check_admin_referer('save-user-css')) {
				add_action('admin_init', 'ls_save_user_css');
			}
		}

		// Skin editor
		if(isset($_POST['ls-user-skins'])) {
			if(check_admin_referer('save-user-skin')) {
				add_action('admin_init', 'ls_save_user_skin');
			}
		}

		// Transition builder
		if(isset($_POST['ls-user-transitions'])) {
			if(check_admin_referer('save-user-transitions')) {
				add_action('admin_init', 'ls_save_user_transitions');
			}
		}

		// Compatibility: convert old sliders to new data storage since 3.6
		if(isset($_GET['page']) && $_GET['page'] == 'layerslider' && isset($_GET['action']) && $_GET['action'] == 'convert') {
			if(check_admin_referer('convertoldsliders')) {
				add_action('admin_init', 'layerslider_convert');
			}
		}

		if(isset($_GET['page']) && $_GET['page'] == 'layerslider' && isset($_GET['action']) && $_GET['action'] == 'hide-support-notice') {
			if(check_admin_referer('hide-support-notice')) {
				update_option('ls-show-support-notice', 0);
				header('Location: admin.php?page=layerslider');
				die();
			}
		}

		if(isset($_GET['page']) && $_GET['page'] == 'layerslider' && isset($_GET['action']) && $_GET['action'] == 'hide-update-notice') {
			if(check_admin_referer('hide-update-notice')) {
				$latest = get_option('ls-latest-version', LS_PLUGIN_VERSION);
				update_option('ls-last-update-notification', $latest);
				header('Location: admin.php?page=layerslider');
				die();
			}
		}

		if(isset($_GET['page']) && $_GET['page'] == 'layerslider' && isset($_GET['action']) && $_GET['action'] == 'hide-revalidation-notice') {
			if(check_admin_referer('hide-revalidation-notice')) {
				update_option('ls-show-revalidation-notice', 0);
				header('Location: admin.php?page=layerslider');
				die();
			}
		}

		// AJAX functions
		add_action('wp_ajax_ls_save_slider', 'ls_save_slider');
		add_action('wp_ajax_ls_save_screen_options', 'ls_save_screen_options');
		add_action('wp_ajax_ls_get_mce_sliders', 'ls_get_mce_sliders');
		add_action('wp_ajax_ls_get_post_details', 'ls_get_post_details');
		add_action('wp_ajax_ls_get_taxonomies', 'ls_get_taxonomies');
	}
}


function ls_add_new_slider() {
	$id = LS_Sliders::add($_POST['title']);
	header('Location: admin.php?page=layerslider&action=edit&id='.$id.'&showsettings=1');
	die();
}

function ls_sliders_bulk_action() {

	// Remove
	if($_POST['action'] === 'remove') {
		if(!empty($_POST['sliders']) && is_array($_POST['sliders'])) {
			foreach($_POST['sliders'] as $item) {
				LS_Sliders::remove( intval($item) );
				delete_transient('ls-slider-data-'.intval($item));
			}
			header('Location: admin.php?page=layerslider&message=removeSuccess'); die();
		} else {
			header('Location: admin.php?page=layerslider&message=removeSelectError&error=1'); die();
		}
	}

	// Delete
	if($_POST['action'] === 'delete') {
		if(!empty($_POST['sliders']) && is_array($_POST['sliders'])) {
			foreach($_POST['sliders'] as $item) {
				LS_Sliders::delete( intval($item));
				delete_transient('ls-slider-data-'.intval($item));
			}
			header('Location: admin.php?page=layerslider&message=deleteSuccess'); die();
		} else {
			header('Location: admin.php?page=layerslider&message=deleteSelectError&error=1'); die();
		}
	}


	// Restore
	if($_POST['action'] === 'restore') {
		if(!empty($_POST['sliders']) && is_array($_POST['sliders'])) {
			foreach($_POST['sliders'] as $item) { LS_Sliders::restore( intval($item)); }
			header('Location: admin.php?page=layerslider&message=restoreSuccess'); die();
		} else {
			header('Location: admin.php?page=layerslider&message=restoreSelectError&error=1'); die();
		}
	}



	// Merge
	if($_POST['action'] === 'merge') {

		// Error check
		if(!isset($_POST['sliders'][1]) || !is_array($_POST['sliders'])) {
			header('Location: admin.php?page=layerslider&error=1&message=mergeSelectError');
			die();
		}

		if($sliders = LS_Sliders::find($_POST['sliders'])) {
			foreach($sliders as $key => $item) {

				// Get IDs
				$ids[] = '#' . $item['id'];

				// Merge slides
				if($key === 0) { $data = $item['data']; }
				else { $data['layers'] = array_merge($data['layers'], $item['data']['layers']); }
			}

			// Save as new
			$name = 'Merged sliders of ' . implode(', ', $ids);
			$data['properties']['title'] = $name;
			LS_Sliders::add($name, $data);
		}

		header('Location: admin.php?page=layerslider&message=mergeSuccess');
		die();
	}
}

function ls_save_google_fonts() {

	// Build object to save
	$fonts = array();
	if(isset($_POST['urlParams'])) {
		foreach($_POST['urlParams'] as $key => $val) {
			if(!empty($val)) {
				$fonts[] = array(
					'param' => $val,
					'admin' => isset($_POST['onlyOnAdmin'][$key]) ? true : false
				);
			}
		}
	}

	// Google Fonts character sets
	array_shift($_POST['scripts']);
	update_option('ls-google-font-scripts', $_POST['scripts']);

	// Save & redirect back
	update_option('ls-google-fonts', $fonts);
	header('Location: admin.php?page=layerslider&message=googleFontsUpdated');
	die();
}


function ls_save_advanced_settings() {

	$options = array('use_cache', 'include_at_footer', 'conditional_script_loading', 'concatenate_output', 'use_custom_jquery',  'put_js_to_body');
	foreach($options as $item) {
		update_option('ls_'.$item, array_key_exists($item, $_POST));
	}

	header('Location: admin.php?page=layerslider&message=generalUpdated');
	die();
}


function ls_save_screen_options() {
	$_POST['options'] = !empty($_POST['options']) ? $_POST['options'] : array();
	update_option('ls-screen-options', $_POST['options']);
	die();
}

function ls_get_mce_sliders() {

	$sliders = LS_Sliders::find(array('limit' => 50));
	foreach($sliders as $key => $item) {
		$sliders[$key]['preview'] = apply_filters('ls_get_preview_for_slider', $item );
	}

	die(json_encode($sliders));
}

function ls_save_slider() {

	// Vars
	$id = (int) $_POST['id'];
	$data = $_POST['sliderData'];

	// Parse slider settings
	$data['properties'] = json_decode(stripslashes(html_entity_decode($data['properties'])), true);

	// Parse slide data
	if(!empty($data['layers']) && is_array($data['layers'])) {
		foreach($data['layers'] as $slideKey => $slideData) {
			$data['layers'][$slideKey] = json_decode(stripslashes(html_entity_decode($slideData)), true);
		}
	}

	$title = esc_sql($data['properties']['title']);
	$slug = !empty($data['properties']['slug']) ? esc_sql($data['properties']['slug']) : '';


	// Relative URL
	if(isset($data['properties']['relativeurls'])) {
		$data = layerslider_convert_urls($data);
	}

	// WPML
	if(function_exists('icl_register_string')) {
		layerslider_register_wpml_strings($id, $data);
	}

	// Delete transient (if any) to
	// invalidate outdated data
	delete_transient('ls-slider-data-'.$id);

	// Update the slider
	if(empty($id)) {
		LS_Sliders::add($title, $data);
	} else {
		LS_Sliders::update($id, $title, $data, $slug);
	}

	die(json_encode(array('status' => 'ok')));
}


/********************************************************/
/*               Action to duplicate slider             */
/********************************************************/
function layerslider_duplicateslider() {

	// Check and get the ID
	$id = (int) $_GET['id'];
	if(!isset($_GET['id'])) {
		return;
	}

	// Get the original slider
	$slider = LS_Sliders::find( (int)$_GET['id'] );
	$data = $slider['data'];

	// Name check
	if(empty($data['properties']['title'])) {
		$data['properties']['title'] = 'Unnamed';
	}

	// Insert the duplicate
	$data['properties']['title'] .= ' copy';
	LS_Sliders::add($data['properties']['title'], $data);

	// Success
	header('Location: admin.php?page=layerslider');
	die();
}


/********************************************************/
/*                Action to remove slider               */
/********************************************************/
function layerslider_removeslider() {

	// Check received data
	if(empty($_GET['id'])) { return false; }

	// Remove the slider
	LS_Sliders::remove( intval($_GET['id']) );

	// Delete transient cache
	delete_transient('ls-slider-data-'.intval($_GET['id']));

	// Reload page
	header('Location: admin.php?page=layerslider');
	die();
}

/********************************************************/
/*            Action to import sample slider            */
/********************************************************/
function layerslider_import_sample_slider() {

	// Get samples and importUtil
	$sliders = LS_Sources::getDemoSliders();
	include LS_ROOT_PATH.'/classes/class.ls.importutil.php';

	// Check reference
	if(!empty($_GET['slider']) && $_GET['slider'] == 'all') {
		foreach($sliders as $item) {
			if(file_exists($item['file'])) {
				$import = new LS_ImportUtil($item['file']);
			}
		}
	} elseif(!empty($_GET['slider']) && is_string($_GET['slider'])) {
		if($item = LS_Sources::getDemoSlider($_GET['slider'])) {
			if(file_exists($item['file'])) {
				$import = new LS_ImportUtil($item['file']);
			}
		}
	}

	header('Location: '.menu_page_url('layerslider', 0));
	die();
}




// PLUGIN USER PERMISSIONS
//-------------------------------------------------------
function ls_save_access_permissions() {

	// Get capability
	$capability = ($_POST['custom_role'] == 'custom') ? $_POST['custom_capability'] : $_POST['custom_role'];

	// Test value
	if(empty($capability) || !current_user_can($capability)) {
		header('Location: admin.php?page=layerslider&error=1&message=permissionError');
		die();
	} else {
		update_option('layerslider_custom_capability', $capability);
		header('Location: admin.php?page=layerslider&message=permissionSuccess');
		die();
	}
}




// IMPORT SLIDERS
//-------------------------------------------------------
function ls_import_sliders() {

	// Check export file if any
	if(!is_uploaded_file($_FILES['import_file']['tmp_name'])) {
		header('Location: '.$_SERVER['REQUEST_URI'].'&error=1&message=importSelectError');
		die('No data received.');
	}

	include LS_ROOT_PATH.'/classes/class.ls.importutil.php';
	$import = new LS_ImportUtil($_FILES['import_file']['tmp_name'], $_FILES['import_file']['name']);

	header('Location: '.menu_page_url('layerslider', 0));
	die();
}




// EXPORT SLIDERS
//-------------------------------------------------------
function ls_export_sliders() {

	// Get sliders
	if(isset($_POST['sliders'][0]) && $_POST['sliders'][0] == -1) {
		$sliders = LS_Sliders::find(array('limit' => 500));
	} elseif(!empty($_POST['sliders'])) {
		$sliders = LS_Sliders::find($_POST['sliders']);
	} else {
		header('Location: admin.php?page=layerslider&error=1&message=exportSelectError');
		die('Invalid data received.');
	}

	// Check results
	if(empty($sliders)) {
		header('Location: admin.php?page=layerslider&error=1&message=exportNotFound');
		die('Invalid data received.');
	}

	if(class_exists('ZipArchive')) {
		include LS_ROOT_PATH.'/classes/class.ls.exportutil.php';
		$zip = new LS_ExportUtil;
	}

	// Gather slider data
	foreach($sliders as $item) {

		// Slider settings array for fallback mode
		$data[] = $item['data'];

		// If ZipArchive is available
		if(class_exists('ZipArchive')) {

			// Add slider folder and settings.json
			$name = empty($item['name']) ? 'slider_' . $item['id'] : $item['name'];
			$name = sanitize_file_name($name);
			$zip->addSettings(json_encode($item['data']), $name);

			// Add images?
			if(!isset($_POST['skip_images'])) {
				$images = $zip->getImagesForSlider($item['data']);
				$images = $zip->getFSPaths($images);
				$zip->addImage($images, $name);
			}
		}
	}

	if(class_exists('ZipArchive')) {
		$zip->download();
	} else {
		$name = 'LayerSlider Export '.date('Y-m-d').' at '.date('H.i.s').'.json';
		header('Content-type: application/force-download');
		header('Content-Disposition: attachment; filename="'.str_replace(' ', '_', $name).'"');
		die(base64_encode(json_encode($data)));
	}
}




// TRANSITION BUILDER
//-------------------------------------------------------
function ls_save_user_css() {

	// Get target file and content
	$upload_dir = wp_upload_dir();
	$file = $upload_dir['basedir'].'/layerslider.custom.css';

	// Attempt to save changes
	if(is_writable($upload_dir['basedir'])) {
		file_put_contents($file, stripslashes($_POST['contents']));
		header('Location: admin.php?page=ls-style-editor&edited=1');
		die();

	// File isn't writable
	} else {
		wp_die(__("It looks like your files isn't writable, so PHP couldn't make any changes (CHMOD).", "LayerSlider"), __('Cannot write to file', 'LayerSlider'), array('back_link' => true) );
	}
}





// SKIN EDITOR
//-------------------------------------------------------
function ls_save_user_skin() {

	// Error checking
	if(empty($_POST['skin']) || strpos($_POST['skin'], '..') !== false) {
		wp_die(__("It looks like you haven't selected any skin to edit.", "LayerSlider"), __('No skin selected.', 'LayerSlider'), array('back_link' => true) );
	}

	// Get skin file and contents
	$skin = LS_Sources::getSkin($_POST['skin']);
	$file = $skin['file'];

	// Attempt to write the file
	if(is_writable($file)) {
		file_put_contents($file, stripslashes($_POST['contents']));
		header('Location: admin.php?page=ls-skin-editor&skin='.$skin['handle'].'&edited=1');
	} else {
		wp_die(__("It looks like your files isn't writable, so PHP couldn't make any changes (CHMOD).", "LayerSlider"), __('Cannot write to file', 'LayerSlider'), array('back_link' => true) );
	}
}




// TRANSITION BUILDER
//-------------------------------------------------------
function ls_save_user_transitions() {

	// Array to hold transitions
	$transitions = array();

	// Get transitions
	$transitions['t2d'] = isset($_POST['t2d']) ? $_POST['t2d'] : array();
	$transitions['t3d'] = isset($_POST['t3d']) ? $_POST['t3d'] : array();

	array_walk_recursive($transitions['t2d'], 'layerslider_builder_convert_numbers');
	array_walk_recursive($transitions['t3d'], 'layerslider_builder_convert_numbers');

	// Iterate over the sections
	foreach($transitions['t3d'] as $key => $val) {

		// Rows
		if(strstr($val['rows'], ',')) { $tmp = explode(',', $val['rows']); $tmp[0] = (int) trim($tmp[0]); $tmp[1] = (int) trim($tmp[1]); $transitions['t3d'][$key]['rows'] = $tmp; }
			else { $transitions['t3d'][$key]['rows'] = (int) $val['rows']; }

		// Cols
		if(strstr($val['cols'], ',')) { $tmp = explode(',', $val['cols']); $tmp[0] = (int) trim($tmp[0]); $tmp[1] = (int) trim($tmp[1]); $transitions['t3d'][$key]['cols'] = $tmp; }
			else { $transitions['t3d'][$key]['cols'] = (int) $val['cols']; }

		// Depth
		if(isset($val['tile']['depth'])) {
			$transitions['t3d'][$key]['tile']['depth'] = 'large'; }

		// Before
		if(!isset($val['before']['enabled'])) {
			unset($transitions['t3d'][$key]['before']['transition']); }

		// After
		if(!isset($val['after']['enabled'])) {
			unset($transitions['t3d'][$key]['after']['transition']); }
	}

	// Iterate over the sections
	foreach($transitions['t2d'] as $key => $val) {

		if(strstr($val['rows'], ',')) { $tmp = explode(',', $val['rows']); $tmp[0] = (int) trim($tmp[0]); $tmp[1] = (int) trim($tmp[1]); $transitions['t2d'][$key]['rows'] = $tmp; }
			else { $transitions['t2d'][$key]['rows'] = (int) $val['rows']; }

		if(strstr($val['cols'], ',')) { $tmp = explode(',', $val['cols']); $tmp[0] = (int) trim($tmp[0]); $tmp[1] = (int) trim($tmp[1]); $transitions['t2d'][$key]['cols'] = $tmp; }
			else { $transitions['t2d'][$key]['cols'] = (int) $val['cols']; }

		if(empty($val['transition']['rotateX'])) {
			unset($transitions['t2d'][$key]['transition']['rotateX']); }

		if(empty($val['transition']['rotateY'])) {
			unset($transitions['t2d'][$key]['transition']['rotateY']); }

		if(empty($val['transition']['rotate'])) {
			unset($transitions['t2d'][$key]['transition']['rotate']); }

		if(empty($val['transition']['scale']) || $val['transition']['scale'] == '1.0' || $val['transition']['scale'] == '1') {
			unset($transitions['t2d'][$key]['transition']['scale']); }

	}

	// Save transitions
	$upload_dir = wp_upload_dir();
	$custom_trs = $upload_dir['basedir'] . '/layerslider.custom.transitions.js';
	$data = 'var layerSliderCustomTransitions = '.json_encode($transitions).';';
	file_put_contents($custom_trs, $data);
	die('SUCCESS');
}


// --
function ls_get_post_details() {

	$params = $_POST['params'];

	$queryArgs = array(
		'post_status' => 'publish',
		'limit' => 30,
		'posts_per_page' => 30,
		'post_type' => $params['post_type']
	);

	if(!empty($params['post_orderby'])) {
		$queryArgs['orderby'] = $params['post_orderby']; }

	if(!empty($params['post_order'])) {
		$queryArgs['order'] = $params['post_order']; }

	if(!empty($params['post_categories'][0])) {
		$queryArgs['category__in'] = $params['post_categories']; }

	if(!empty($params['post_tags'][0])) {
		$queryArgs['tag__in'] = $params['post_tags']; }

	if(!empty($params['post_taxonomy']) && !empty($params['post_tax_terms'])) {
		$queryArgs['tax_query'][] = array(
			'taxonomy' => $params['post_taxonomy'],
			'field' => 'id',
			'terms' => $params['post_tax_terms']
		);
	}

	$posts = LS_Posts::find($queryArgs)->getParsedObject();

	die(json_encode($posts));
}


function ls_get_taxonomies() {
	die(json_encode(array_values(get_terms($_POST['taxonomy']))));
}
