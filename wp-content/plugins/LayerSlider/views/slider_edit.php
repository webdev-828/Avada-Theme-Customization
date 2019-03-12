<?php

	if(!defined('LS_ROOT_FILE')) {
		header('HTTP/1.0 403 Forbidden');
		exit;
	}

	// Get the IF of the slider
	$id = (int) $_GET['id'];

	// Get slider
	$slider = LS_Sliders::find($id);
	$slider = $slider['data'];


	// Get screen options
	$lsScreenOptions = get_option('ls-screen-options', '0');
	$lsScreenOptions = ($lsScreenOptions == 0) ? array() : $lsScreenOptions;
	$lsScreenOptions = is_array($lsScreenOptions) ? $lsScreenOptions : unserialize($lsScreenOptions);

	// Defaults
	if(!isset($lsScreenOptions['showTooltips'])) {
		$lsScreenOptions['showTooltips'] = 'true';
	}

	// Get phpQuery
	if(!class_exists('phpQuery')) {
		libxml_use_internal_errors(true);
		include LS_ROOT_PATH.'/helpers/phpQuery.php';
	}

	// Get defaults
	include LS_ROOT_PATH . '/config/defaults.php';
	include LS_ROOT_PATH . '/helpers/admin.ui.tools.php';


	// Run filters
	if(has_filter('layerslider_override_defaults')) {
		$newDefaults = apply_filters('layerslider_override_defaults', $lsDefaults);
		if(!empty($newDefaults) && is_array($newDefaults)) {
			$lsDefaults = $newDefaults;
			unset($newDefaults);
		}
	}

	// Show tab
	$settingsTabClass = isset($_GET['showsettings']) ? 'active' : '';
	$slidesTabClass = !isset($_GET['showsettings']) ? 'active' : '';

	// Fixes
	if(!isset($slider['layers'][0]['properties'])) {
		$slider['layers'][0]['properties'] = array();
	}

	// Get post types
	$postTypes = LS_Posts::getPostTypes();
	$postCategories = get_categories();
	$postTags = get_tags();
	$postTaxonomies = get_taxonomies(array('_builtin' => false), 'objects');
?>
<div id="ls-screen-options" class="metabox-prefs hidden">
	<div id="screen-options-wrap" class="hidden">
		<form id="ls-screen-options-form" action="<?php echo $_SERVER['REQUEST_URI']?>" method="post">
			<h5><?php _e('Show on screen', 'LayerSlider') ?></h5>
			<label>
				<input type="checkbox" name="showTooltips"<?php echo $lsScreenOptions['showTooltips'] == 'true' ? ' checked="checked"' : ''?>> Tooltips
			</label>
		</form>
	</div>
	<div id="screen-options-link-wrap" class="hide-if-no-js screen-meta-toggle">
		<button type="button" id="show-settings-link" class="button show-settings" aria-controls="screen-options-wrap" aria-expanded="false"><?php _e('Screen Options', 'LayerSlider') ?></button>
	</div>
</div>

<!-- Load templates -->
<?php
include LS_ROOT_PATH . '/templates/tmpl-share-sheet.php';
include LS_ROOT_PATH . '/templates/tmpl-layer-item.php';
include LS_ROOT_PATH . '/templates/tmpl-layer.php';
include LS_ROOT_PATH . '/templates/tmpl-transition-window.php';
?>

<!-- Load slide template -->
<script type="text/html" id="ls-slide-template">
	<?php include LS_ROOT_PATH . '/templates/tmpl-slide.php'; ?>
</script>

<!-- Slider JSON data source -->
<?php
	foreach($slider['layers'] as $slideKey => $slideVal) {

		if(!empty($slideVal['properties']['backgroundId'])) { $slideVal['properties']['backgroundThumb'] = apply_filters('ls_get_thumbnail', $slideVal['properties']['backgroundId'], $slideVal['properties']['background']); }
		if(!empty($slideVal['properties']['thumbnailId'])) { $slideVal['properties']['thumbnailThumb'] = apply_filters('ls_get_thumbnail', $slideVal['properties']['thumbnailId'], $slideVal['properties']['thumbnail']); }
		$slider['layers'][$slideKey] = $slideVal;

		if(!empty($slideVal['sublayers'])) {
			foreach($slideVal['sublayers'] as $layerKey => $layerVal) {
				if(!empty($layerVal['imageId'])) { $layerVal['imageThumb'] = apply_filters('ls_get_thumbnail', $layerVal['imageId'], $layerVal['image']); }

				// Ensure that magic quotes will not mess with JSON data
				if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
					$layerVal['styles'] = stripslashes($layerVal['styles']);
					$layerVal['transition'] = stripslashes($layerVal['transition']);
				}

				// Parse embedded JSON data
				$layerVal['styles'] = !empty($layerVal['styles']) ? json_decode(stripslashes($layerVal['styles']), true) : new stdClass;
				$layerVal['transition'] = !empty($layerVal['transition']) ? json_decode(stripslashes($layerVal['transition']), true) : new stdClass;
				$layerVal['html'] = stripslashes($layerVal['html']);

				$slider['layers'][$slideKey]['sublayers'][$layerKey] = $layerVal;
			}
		}
	}
?>

<!-- Get slider data from DB -->
<script type="text/javascript">
	window.lsSliderData = <?php echo json_encode($slider) ?>;
</script>



<form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post" class="wrap" id="ls-slider-form" novalidate="novalidate">

	<input type="hidden" name="slider_id" value="<?php echo $id ?>">
	<input type="hidden" name="action" value="ls_save_slider">

	<!-- Title -->
	<h2>
		<?php _e('Editing slider:', 'LayerSlider') ?>
		<?php $sliderName = !empty($slider['properties']['title']) ? $slider['properties']['title'] : 'Unnamed'; ?>
		<?php echo apply_filters('ls_slider_title', $sliderName, 35) ?>
		<a href="?page=layerslider" class="add-new-h2"><?php _e('Back to the list', 'LayerSlider') ?></a>
	</h2>

	<!-- Version number -->
	<?php include LS_ROOT_PATH . '/templates/tmpl-beta-feedback.php'; ?>

	<!-- Main menu bar -->
	<div id="ls-main-nav-bar">
		<a href="#" class="settings <?php echo $settingsTabClass ?>">
			<i class="dashicons dashicons-admin-tools"></i>
			<?php _e('Slider Settings', 'LayerSlider') ?>
		</a>
		<a href="#" class="layers <?php echo $slidesTabClass ?>">
			<i class="dashicons dashicons-images-alt"></i>
			<?php _e('Slides', 'LayerSlider') ?>
		</a>
		<a href="#" class="callbacks">
			<i class="dashicons dashicons-redo"></i>
			<?php _e('Event Callbacks', 'LayerSlider') ?>
		</a>
		<a href="http://support.kreaturamedia.com/faq/4/layerslider-for-wordpress/" target="_blank" class="faq right unselectable">
			<i class="dashicons dashicons-sos"></i>
			<?php _e('FAQ', 'LayerSlider') ?>
		</a>
		<a href="http://support.kreaturamedia.com/docs/layersliderwp/documentation.html" target="_blank" class="support right unselectable">
			<i class="dashicons dashicons-editor-help"></i>
			<?php _e('Documentation', 'LayerSlider') ?>
		</a>
		<span class="right help"><?php _e('Need help? Try these: ', 'LayerSlider') ?></span>
		<a href="#" class="clear unselectable"></a>
	</div>

	<!-- Post options -->
	<?php include LS_ROOT_PATH . '/templates/tmpl-post-options.php'; ?>

	<!-- Pages -->
	<div id="ls-pages">

		<!-- Slider Settings -->
		<div class="ls-page ls-settings ls-slider-settings <?php echo $settingsTabClass ?>">
			<?php include LS_ROOT_PATH . '/templates/tmpl-slider-settings.php'; ?>
		</div>

		<!-- Slides -->
		<div class="ls-page <?php echo $slidesTabClass ?>">

			<!-- Slide tabs -->
			<div id="ls-layer-tabs">
				<?php foreach($slider['layers'] as $key => $layer) : ?>
				<?php $active = empty($key) ? 'active' : '' ?>
				<a href="#" class="<?php echo $active ?>">Slide #<?php echo ($key+1) ?><span class="dashicons dashicons-dismiss"></span></a>
				<?php endforeach; ?>
				<a href="#"  title="<?php _e('Add new slide', 'LayerSlider') ?>" class="unsortable" id="ls-add-layer"><i class="dashicons dashicons-plus"></i></a>
				<div class="unsortable clear"></div>
			</div>

			<!-- Slides -->
			<div id="ls-layers">
				<?php include LS_ROOT_PATH . '/templates/tmpl-slide.php'; ?>
			</div>
		</div>

		<!-- Event Callbacks -->
		<div class="ls-page ls-callback-page">
			<div class="ls-box ls-callback-box">
				<h3 class="header">
					cbInit
					<figure><span>|</span> <?php _e('Fires when LayerSlider has loaded', 'LayerSlider') ?></figure>
				</h3>
				<div>
					<textarea name="cbinit" cols="20" rows="5" class="ls-codemirror"><?php echo !empty($slider['properties']['cbinit']) ? stripslashes($slider['properties']['cbinit']) : $lsDefaults['slider']['cbInit']['value'] ?></textarea>
				</div>
			</div>

			<div class="ls-box ls-callback-box">
				<h3 class="header">
					cbStart
					<figure><span>|</span> <?php _e('Calling when the slideshow has started.', 'LayerSlider') ?></figure>
				</h3>
				<div>
					<textarea name="cbstart" cols="20" rows="5" class="ls-codemirror"><?php echo !empty($slider['properties']['cbstart']) ? stripslashes($slider['properties']['cbstart']) : $lsDefaults['slider']['cbStart']['value'] ?></textarea>
				</div>
			</div>

			<div class="ls-box ls-callback-box side">
				<h3 class="header">
					cbStop
					<figure><span>|</span> <?php _e('Calling when the slideshow is stopped by the user.', 'LayerSlider') ?></figure>
				</h3>
				<div>
					<textarea name="cbstop" cols="20" rows="5" class="ls-codemirror"><?php echo !empty($slider['properties']['cbstop']) ? stripslashes($slider['properties']['cbstop']) : $lsDefaults['slider']['cbStop']['value'] ?></textarea>
				</div>
			</div>

			<div class="ls-box ls-callback-box">
				<h3 class="header">
					cbPause
					<figure><span>|</span> <?php _e('Fireing when the slideshow is temporary on hold (e.g.: "Pause on hover" feature).', 'LayerSlider') ?></figure>
				</h3>
				<div>
					<textarea name="cbpause" cols="20" rows="5" class="ls-codemirror"><?php echo !empty($slider['properties']['cbpause']) ? stripslashes($slider['properties']['cbpause']) : $lsDefaults['slider']['cbPause']['value'] ?></textarea>
				</div>
			</div>

			<div class="ls-box ls-callback-box">
				<h3 class="header">
					cbAnimStart
					<figure><span>|</span> <?php _e('Calling when the slider commencing slide change (animation start).', 'LayerSlider') ?></figure>
				</h3>
				<div>
					<textarea name="cbanimstart" cols="20" rows="5" class="ls-codemirror"><?php echo !empty($slider['properties']['cbanimstart']) ? stripslashes($slider['properties']['cbanimstart']) : $lsDefaults['slider']['cbAnimStart']['value'] ?></textarea>
				</div>
			</div>

			<div class="ls-box ls-callback-box side">
				<h3 class="header">
					cbAnimStop
					<figure><span>|</span> <?php _e('Fireing when the slider finished a slide change (animation end).', 'LayerSlider') ?></figure>
				</h3>
				<div>
					<textarea name="cbanimstop" cols="20" rows="5" class="ls-codemirror"><?php echo !empty($slider['properties']['cbanimstop']) ? stripslashes($slider['properties']['cbanimstop']) : $lsDefaults['slider']['cbAnimStop']['value'] ?></textarea>
				</div>
			</div>

			<div class="ls-box ls-callback-box">
				<h3 class="header">
					cbPrev
					<figure><span>|</span> <?php _e('Calling when the slider will change to the previous slide by the user.', 'LayerSlider') ?></figure>
				</h3>
				<div>
					<textarea name="cbprev" cols="20" rows="5" class="ls-codemirror"><?php echo !empty($slider['properties']['cbprev']) ? stripslashes($slider['properties']['cbprev']) : $lsDefaults['slider']['cbPrev']['value'] ?></textarea>
				</div>
			</div>

			<div class="ls-box ls-callback-box">
				<h3 class="header">
					cbNext
					<figure><span>|</span> <?php _e('Calling when the slider will change to the next slide by the user.', 'LayerSlider') ?></figure>
				</h3>
				<div>
					<textarea name="cbnext" cols="20" rows="5" class="ls-codemirror"><?php echo !empty($slider['properties']['cbnext']) ? stripslashes($slider['properties']['cbnext']) : $lsDefaults['slider']['cbNext']['value'] ?></textarea>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>

	<div class="ls-publish">
		<button type="submit" class="button button-primary button-hero"><?php _e('Save changes', 'LayerSlider') ?></button>
		<div class="ls-save-shortcode">
			<p><span>Use shortcode:</span><br><span>[layerslider id="<?php echo !empty($slider['properties']['slug']) ? $slider['properties']['slug'] : $id ?>"]</span></p>
			<p><span>Use PHP function:</span><br><span>&lt;?php layerslider(<?php echo !empty($slider['properties']['slug']) ? "'{$slider['properties']['slug']}'" : $id ?>) ?&gt;</span></p>
		</div>
	</div>
</form>


<script type="text/javascript">

	// Plugin path
	var pluginPath = '<?php echo LS_ROOT_URL ?>/static/';

	// Transition images
	var lsTrImgPath = '<?php echo LS_ROOT_URL ?>/static/img/';

	// New Media Library
	<?php if(function_exists( 'wp_enqueue_media' )) { ?>
	var newMediaUploader = true;
	<?php } else { ?>
	var newMediaUploader = false;
	<?php } ?>

	// Screen options
	var lsScreenOptions = <?php echo json_encode($lsScreenOptions) ?>;
</script>
