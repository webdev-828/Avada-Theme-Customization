<?php

	if(!defined('LS_ROOT_FILE')) {
		header('HTTP/1.0 403 Forbidden');
		exit;
	}

	// Get screen options
	$lsScreenOptions = get_option('ls-screen-options', '0');
	$lsScreenOptions = ($lsScreenOptions == 0) ? array() : $lsScreenOptions;
	$lsScreenOptions = is_array($lsScreenOptions) ? $lsScreenOptions : unserialize($lsScreenOptions);

	// Defaults
	if(!isset($lsScreenOptions['showTooltips'])) { $lsScreenOptions['showTooltips'] = 'true'; }
	if(!isset($lsScreenOptions['showRemovedSliders'])) { $lsScreenOptions['showRemovedSliders'] = 'false'; }
	if(!isset($lsScreenOptions['numberOfSliders'])) { $lsScreenOptions['numberOfSliders'] = '20'; }

	// Get current page
	$curPage = (!empty($_GET['paged']) && is_numeric($_GET['paged'])) ? (int) $_GET['paged'] : 1;
	// $curPage = ($curPage >= $maxPage) ? $maxPage : $curPage;

	// Set filters
	$filters = array('page' => $curPage, 'limit' => (int) $lsScreenOptions['numberOfSliders']);
	if($lsScreenOptions['showRemovedSliders'] == 'true') {
		$filters['exclude'] = array('hidden'); }

	// Find sliders
	$sliders = LS_Sliders::find($filters);

	// Pager
	$maxItem = LS_Sliders::$count;
	$maxPage = ceil($maxItem / (int) $lsScreenOptions['numberOfSliders']);
	$maxPage = $maxPage ? $maxPage : 1;

	// Custom capability
	$custom_capability = $custom_role = get_option('layerslider_custom_capability', 'manage_options');
	$default_capabilities = array('manage_network', 'manage_options', 'publish_pages', 'publish_posts', 'edit_posts');

	if(in_array($custom_capability, $default_capabilities)) {
		$custom_capability = '';
	} else {
		$custom_role = 'custom';
	}

	// Auto-updates
	$code = get_option('layerslider-purchase-code', '');
	$codeFormatted = '';
	if(!empty($code)) {
		$start = substr($code, 0, -6);
		$end = substr($code, -6);
		$codeFormatted = preg_replace("/[a-zA-Z0-9]/", '●', $start) . $end;
		$codeFormatted = str_replace('-', ' ', $codeFormatted);
	}

	$validity = get_option('layerslider-authorized-site', '0');
	$channel = get_option('layerslider-release-channel', 'stable');

	// Google Fonts
	$googleFonts = get_option('ls-google-fonts', array());
	$googleFontScripts = get_option('ls-google-font-scripts', array('latin', 'latin-ext'));


	// Notification messages
	$notifications = array(
		'removeSelectError' => __('No sliders were selected to remove.', 'LayerSlider'),
		'removeSuccess' => __('The selected sliders were removed.', 'LayerSlider'),
		'deleteSelectError' => __('No sliders were selected.', 'LayerSlider'),
		'deleteSuccess' => __('The selected sliders were permanently deleted.', 'LayerSlider'),
		'mergeSelectError' => __('You need to select at least 2 sliders to merge them.', 'LayerSlider'),
		'mergeSuccess' => __('The selected items were merged together as a new slider.', 'LayerSlider'),
		'restoreSelectError' => __('No sliders were selected.', 'LayerSlider'),
		'restoreSuccess' => __('The selected sliders were restored.', 'LayerSlider'),

		'exportNotFound' => __('No sliders were found to export.', 'LayerSlider'),
		'exportSelectError' => __('No sliders were selected to export.', 'LayerSlider'),
		'exportZipError' => __('The PHP ZipArchive extension is required to import .zip files.', 'LayerSlider'),

		'importSelectError' => __('Choose a file to import sliders.', 'LayerSlider'),
		'importFailed' => __('The import file seems to be invalid or corrupted.', 'LayerSlider'),
		'importSuccess' => __('Your slider has been imported.', 'LayerSlider'),
		'permissionError' => __('Your account does not have the necessary permission you have chosen, and your settings have not been saved in order to prevent locking yourself out of the plugin.', 'LayerSlider'),
		'permissionSuccess' => __('Permission changes has been updated.', 'LayerSlider'),
		'googleFontsUpdated' => __('Your Google Fonts library has been updated.', 'LayerSlider'),
		'generalUpdated' => __('Your settings has been updated.', 'LayerSlider')
	);
?>
<div id="ls-screen-options" class="metabox-prefs hidden">
	<div id="screen-options-wrap" class="hidden">
		<form id="ls-screen-options-form" action="<?php echo $_SERVER['REQUEST_URI']?>" method="post">
			<h5><?php _e('Show on screen', 'LayerSlider') ?></h5>
			<label><input type="checkbox" name="showTooltips"<?php echo $lsScreenOptions['showTooltips'] == 'true' ? ' checked="checked"' : ''?>> <?php _e('Tooltips', 'LayerSlider') ?></label>
			<label><input type="checkbox" name="showRemovedSliders" class="reload"<?php echo $lsScreenOptions['showRemovedSliders'] == 'true' ? ' checked="checked"' : ''?>> <?php _e('Removed sliders', 'LayerSlider') ?></label><br><br>

			<?php _e('Show me', 'LayerSlider') ?> <input type="number" name="numberOfSliders" min="3" step="1" value="<?php echo $lsScreenOptions['numberOfSliders'] ?>"> <?php _e('sliders per page', 'LayerSlider') ?>
			<button class="button"><?php _e('Apply', 'LayerSlider') ?></button>
		</form>
	</div>
	<div id="screen-options-link-wrap" class="hide-if-no-js screen-meta-toggle">
		<button type="button" id="show-settings-link" class="button show-settings" aria-controls="screen-options-wrap" aria-expanded="false"><?php _e('Screen Options', 'LayerSlider') ?></button>
	</div>
</div>

<!-- WP hack to place notification at the top of page -->
<div class="wrap ls-wp-hack">
	<h2></h2>

	<!-- Error messages -->
	<?php if(isset($_GET['message'])) : ?>
	<div class="ls-notification <?php echo isset($_GET['error']) ? 'error' : 'updated' ?>">
		<div><?php echo $notifications[ $_GET['message'] ] ?></div>
	</div>
	<?php endif; ?>
	<!-- End of error messages -->
</div>

<div class="wrap" id="ls-list-page">
	<h2>
		<?php _e('LayerSlider sliders', 'LayerSlider') ?>
		<a href="#" id="ls-add-slider-button" class="add-new-h2"><?php _e('Add New', 'LayerSlider') ?></a>
		<a href="#" id="ls-import-samples-button" class="add-new-h2"><?php _e('Import sample sliders', 'LayerSlider') ?></a>
	</h2>

	<!-- Version number -->
	<?php include LS_ROOT_PATH . '/templates/tmpl-beta-feedback.php'; ?>

	<!-- Add slider template -->
	<?php include LS_ROOT_PATH . '/templates/tmpl-add-slider.php'; ?>


	<!-- Import sample sliders template -->
	<?php include LS_ROOT_PATH . '/templates/tmpl-demo-sliders.php'; ?>


	<!-- Share sheet template -->
	<?php include LS_ROOT_PATH . '/templates/tmpl-share-sheet.php'; ?>


	<!-- Auto-update revalidation -->
	<?php include LS_ROOT_PATH . '/templates/tmpl-updates-revalidation.php'; ?>



	<?php if(empty($sliders)) : ?>
	<div id="ls-no-sliders">
		<span><?php _e('You haven\'t created any slider yet.', 'LayerSlider') ?></span><br>
		<span><?php _e('Click those buttons to add one or import our demo content.', 'LayerSlider') ?></span>
	</div>
	<?php endif; ?>

	<?php if(!empty($sliders)) : ?>
	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" class="ls-slider-list-form">
		<input type="hidden" name="ls-bulk-action" value="1">
		<?php wp_nonce_field('bulk-action'); ?>
		<div class="ls-box ls-sliders-list">
			<table>
				<thead class="header">
					<tr>
						<td></td>
						<td><?php _e('ID', 'LayerSlider') ?></td>
						<td class="preview"><?php _e('Slider preview', 'LayerSlider') ?></td>
						<td><?php _e('Name', 'LayerSlider') ?></td>
						<td><?php _e('Shortcode', 'LayerSlider') ?></td>
						<td><?php _e('Slides', 'LayerSlider') ?></td>
						<td><?php _e('Created', 'LayerSlider') ?></td>
						<td><?php _e('Modified', 'LayerSlider') ?></td>
						<td></td>
					</tr>
				</thead>
				<tbody>
					<?php foreach($sliders as $key => $item) : ?>
					<?php $class = ($item['flag_deleted'] == '1') ? ' class="faded"' : '' ?>
					<tr<?php echo $class ?>>
						<td><input type="checkbox" name="sliders[]" value="<?php echo $item['id'] ?>"></td>
						<td><?php echo $item['id'] ?></td>
						<td class="preview">
							<div>
								<a href="?page=layerslider&action=edit&id=<?php echo $item['id'] ?>">
									<img src="<?php echo apply_filters('ls_get_preview_for_slider', $item ) ?>" alt="Slider preview">
								</a>
							</div>
						</td>
						<td class="name">
							<a href="?page=layerslider&action=edit&id=<?php echo $item['id'] ?>">
								<?php echo apply_filters('ls_slider_title', $item['name'], 40) ?>
							</a>
						</td>
						<td><input type="text" class="ls-shortcode" value="[layerslider id=&quot;<?php echo !empty($item['slug']) ? $item['slug'] : $item['id'] ?>&quot;]" readonly></td>
						<td><?php echo isset($item['data']['layers']) ? count($item['data']['layers']) : 0 ?></td>
						<td><?php echo date('d/m/y', $item['date_c']) ?></td>
						<td><?php echo human_time_diff($item['date_m']) ?> <?php _e('ago', 'LayerSlider') ?></td>
						<td>
							<?php if(!$item['flag_deleted']) : ?>
							<a href="<?php echo wp_nonce_url('?page=layerslider&action=duplicate&id='.$item['id'], 'duplicate_'.$item['id']) ?>">
								<span class="dashicons dashicons-admin-page" data-help="<?php _e('Duplicate this slider', 'LayerSlider') ?>"></span>
							</a>
							<a href="<?php echo wp_nonce_url('?page=layerslider&action=remove&id='.$item['id'], 'remove_'.$item['id']) ?>" class="remove">
								<span class="dashicons dashicons-trash" data-help="<?php _e('Remove this slider', 'LayerSlider') ?>"></span>
							</a>
							<?php else : ?>
							<a href="<?php echo wp_nonce_url('?page=layerslider&action=restore&id='.$item['id'], 'restore_'.$item['id']) ?>">
								<span class="dashicons dashicons-backup" data-help="<?php _e('Restore removed slider', 'LayerSlider') ?>"></span>
							</a>
							<?php endif; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<div class="ls-bulk-actions">
				<select name="action">
					<option value="0"><?php _e('Bulk Actions', 'LayerSlider') ?></option>
					<option value="remove"><?php _e('Remove selected', 'LayerSlider') ?></option>
					<option value="delete"><?php _e('Delete permanently', 'LayerSlider') ?></option>
					<?php if($lsScreenOptions['showRemovedSliders'] == 'true') : ?>
					<option value="restore"><?php _e('Restore removed', 'LayerSlider') ?></option>
					<?php endif; ?>
					<option value="merge"><?php _e('Merge selected as new', 'LayerSlider') ?></option>
				</select>
				<button class="button"><?php _e('Apply', 'LayerSlider') ?></button>
			</div>
			<div class="ls-pagination tablenav bottom">
				<div class="tablenav-pages">
					<span class="displaying-num"><?php echo $maxItem ?> <?php _e('items', 'LayerSlider') ?></span>
					<span class="pagination-links">
						<a class="first-page<?php echo ($curPage <= 1) ? ' disabled' : ''; ?>" title="Go to the first page" href="admin.php?page=layerslider">«</a>
						<a class="prev-page <?php echo ($curPage <= 1) ? ' disabled' : ''; ?>" title="Go to the previous page" href="admin.php?page=layerslider&amp;paged=<?php echo ($curPage-1) ?>">‹</a>
						<form action="admin.php" method="get" class="paging-input">
							<input type="hidden" name="page" value="layerslider">
							<input class="current-page" title="Current page" type="text" name="paged" value="<?php echo $curPage ?>" size="1"> of
							<span class="total-pages"><?php echo $maxPage ?></span>
						</form>
						<a class="next-page <?php echo ($curPage >= $maxPage) ? ' disabled' : ''; ?>" title="Go to the next page" href="admin.php?page=layerslider&amp;paged=<?php echo ($curPage+1) ?>">›</a>
						<a class="last-page <?php echo ($curPage >= $maxPage) ? ' disabled' : ''; ?>" title="Go to the last page" href="admin.php?page=layerslider&amp;paged=<?php echo $maxPage ?>">»</a>
					</span>
				</div>
			</div>
		</div>
	</form>
	<?php endif ?>


	<div class="km-tabs">
		<a href="#" class="active"><?php _e('Auto-Updates', 'LayerSlider') ?></a>
		<a href="#"><?php _e('Import / Export', 'LayerSlider') ?></a>
		<a href="#"><?php _e('Permissions', 'LayerSlider') ?></a>
		<a href="#"><?php _e('Google Fonts', 'LayerSlider') ?></a>
		<a href="#"><?php _e('Advanced', 'LayerSlider') ?></a>
	</div>
	<div class="km-tabs-content ls-plugin-settings">

		<!-- Auto-Updates -->
		<div class="ls-auto-update active">
			<figure>
				<?php _e('Receive update notifications and install new versions with 1-Click.', 'LayerSlider') ?>
				<a href="http://support.kreaturamedia.com/docs/layersliderwp/documentation.html#updating" target="_blank"><? _e('Read more', 'LayerSlider') ?></a>
				<span class="status" style="<?php echo ($validity == '1') ? 'color: #76b546;' : 'color: red'?>">
				<?php
					if($validity == '1') {
						_e('This site is authorized to receive automatic updates.', 'LayerSlider');
					} else {
						_e("This site is not yet authorized to receive plugin updates.", "LayerSlider");
					}
				?>
				</span>
			</figure>
			<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" class="ls-box km-tabs-inner ls-settings">
				<input type="hidden" name="action" value="layerslider_authorize_site">

				<div class="inner">
					<?php _e('Enter your purchase code', 'LayerSlider') ?>
					<input type="text" name="purchase_code" value="<?php echo $codeFormatted ?>"  class="key" placeholder="e.g. bc8e2b24-3f8c-4b21-8b4b-90d57a38e3c7" data-help="<?php _e('To receive automatic updates, you need to enter your item purchase code. Click on the Download button next to LayerSlider WP on your CodeCanyon downloads page and choose the &quot;License certificate & purchase code&quot; option. This will download a text file that contains your purchase code.', 'LayerSlider') ?>">
					<i><?php _e('and', 'LayerSlider') ?></i>
					<?php _e('choose release channel', 'LayerSlider') ?>
					<label><input type="radio" name="channel" value="stable" <?php echo ($channel === 'stable') ? 'checked="checked"' : ''?>> <?php _e('Stable', 'LayerSlider') ?></label>
					<label data-help="<?php _e('Although pre-release versions meant to work properly, they might contain unknown issues, and are not recommended for sites in production.', 'LayerSlider') ?>">
						<input type="radio" name="channel" value="beta" <?php echo ($channel === 'beta') ? 'checked="checked"' : ''?>> <?php _e('Beta', 'LayerSlider') ?>
					</label>
					<p>
						<?php _e("You can find your purchase code by selecting the License Certificate option under LayerSlider's Download button on your", "LayerSlider"); ?>
						<a href="http://codecanyon.net/downloads?filter_by=codecanyon.net" target="_blank"><?php _e('CodeCanyon Downloads', 'LayerSlider') ?></a>
						<?php _e('page.', 'LayerSlider') ?>
					</p>
					<?php if($GLOBALS['lsAutoUpdateBox'] == false) : ?>
					<p>
						<?php _e("It seems you've received LayerSlider by a theme. Please note that the auto-update feature only works if you've purchased the plugin directly from us on <a href=\"http://codecanyon.net/item/layerslider-responsive-wordpress-slider-plugin-/1362246\" target=\"_blank\">CodeCanyon</a>.", "LayerSlider"); ?>
					</p>
					<?php endif ?>
				</div>

				<div class="footer">
					<button class="button"><?php _e('Update', 'LayerSlider') ?></button>
					<a href="#" class="ls-deauthorize<?php echo ($validity == '1') ? '' : ' ls-hidden' ?>"><?php _e('Deauthorize this site', 'LayerSlider') ?></a>
					<a href="<?php echo LS_REPO_BASE_URL.'download?domain='.base64_encode($_SERVER['SERVER_NAME']).'&channel='.$channel.'&code='.base64_encode($code) ?>" class="<?php echo ($validity == '1') ? '' : ' ls-hidden' ?>"><?php _e('Download latest version manually', 'LayerSlider') ?></a>
					<a href="update-core.php" class="<?php echo ($validity == '1') ? '' : 'ls-hidden' ?>"><?php _e('Check for updates', 'LayerSlider') ?></a>
				</div>
			</form>
		</div>


		<!-- Import / Export -->
		<div class="ls-export-wrapper">
			<figure>
				<?php _e('Move sliders between sites, make backups, import demo content', 'LayerSlider') ?>
				<span class="status <?php echo class_exists('ZipArchive') ? 'available' : 'notavailable' ?>" data-help="<?php _e("The PHP ZipArchive extension is needed for exporting/importing images. The plugin will only copy your slider settings if it's not available. In that case please contact with your hosting provider.", "LayerSlider") ?>">
					<?php echo class_exists('ZipArchive') ?
						'ZipArchive is available to import/export images' :
						'ZipArchive isn\'t avilable'
					?>
				</span>
			</figure>
			<div class="km-tabs-inner columns clearfix">
				<div class="half">
					<div class="ls-import-export-box ls-box">
						<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" enctype="multipart/form-data" class="ls-import-box">
							<?php wp_nonce_field('import-sliders'); ?>
							<input type="hidden" name="ls-import" value="1">
							<input type="file" name="import_file" class="ls-import-file">
							<button class="button"><?php _e('Import', 'LayerSlider') ?></button><br>
							<label><input type="checkbox" name="skip_images" class="checkbox"> <?php _e('Do not import images', 'LayerSlider') ?></label>
							<p class="desc">
								<?php _e('Choose a LayerSlider export file downloaded previously to import your sliders. In order to import from outdated versions, you need to create a file and paste the export code into it. The file needs to have a .json extension.', 'LayerSlider') ?>
							</p>
						</form>
					</div>
				</div>

				<div class="half">
					<div class="ls-import-export-box ls-box">
						<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" class="ls-export-form">
							<?php wp_nonce_field('export-sliders'); ?>
							<input type="hidden" name="ls-export" value="1">
							<select name="sliders[]" multiple="multiple" data-help="<?php _e('Downloads an export file that contains your selected sliders to import on your new site. You can select multiple sliders by holding the Ctrl/Cmd button while clicking.', 'LayerSlider') ?>">
								<option value="-1" selected> <?php _e('All Sliders', 'LayerSlider') ?></option>
								<?php foreach($sliders as $slider) : ?>
								<option value="<?php echo $slider['id'] ?>">
									#<?php echo str_replace(' ', '&nbsp;', str_pad($slider['id'], 3, " ")) ?> -
									<?php echo apply_filters('ls_slider_title', $slider['name'], 30) ?>
								</option>
								<?php endforeach; ?>
							</select>

							<label>
								<input type="checkbox"  class="checkbox" name="skip_images"> Do not export images
							</label>
							<button class="button"><?php _e('Export', 'LayerSlider') ?></button>
						</form>
					</div>
				</div>
			</div>
		</div>

		<!-- Permissions -->
		<div>
			<figure><?php _e('Allow non-admin users to change plugin settings and manage your sliders', 'LayerSlider') ?></figure>
			<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" class="ls-box km-tabs-inner" id="ls-permission-form">
				<?php wp_nonce_field('save-access-permissions'); ?>
				<input type="hidden" name="ls-access-permission" value="1">
				<div class="inner">
					<?php _e('Choose a role', 'LayerSlider') ?>
					<select name="custom_role">
						<?php if(is_multisite()) : ?>
						<option value="manage_network" <?php echo ($custom_role == 'manage_network') ? 'selected="selected"' : '' ?>> <?php _e('Super Admin', 'LayerSlider') ?></option>
						<?php endif; ?>
						<option value="manage_options" <?php echo ($custom_role == 'manage_options') ? 'selected="selected"' : '' ?>> <?php _e('Admin', 'LayerSlider') ?></option>
						<option value="publish_pages" <?php echo ($custom_role == 'publish_pages') ? 'selected="selected"' : '' ?>> <?php _e('Editor, Admin', 'LayerSlider') ?></option>
						<option value="publish_posts" <?php echo ($custom_role == 'publish_posts') ? 'selected="selected"' : '' ?>> <?php _e('Author, Editor, Admin', 'LayerSlider') ?></option>
						<option value="edit_posts" <?php echo ($custom_role == 'edit_posts') ? 'selected="selected"' : '' ?>> <?php _e('Contributor, Author, Editor, Admin', 'LayerSlider') ?></option>
						<option value="custom" <?php echo ($custom_role == 'custom') ? 'selected="selected"' : '' ?>> <?php _e('Custom', 'LayerSlider') ?></option>
					</select>

					<i><?php _e('or', 'LayerSlider') ?></i> <?php _e('enter a custom capability', 'LayerSlider') ?>
					<input type="text" name="custom_capability" value="<?php echo $custom_capability ?>" placeholder="Enter custom capability">

					<p><?php _e('You can specify a custom capability if none of the pre-defined roles match your needs. You can find all the available capabilities on', 'LayerSlider') ?> <a href="http://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table" target="_blank"><?php _e('this page', 'LayerSlider') ?></a>.</p>
				</div>
				<div class="footer">
					<button class="button"><?php _e('Update', 'LayerSlider') ?></button>
				</div>
			</form>
		</div>


		<!-- Google Fonts -->
		<div>
			<figure><?php _e('Choose from hundreds of custom fonts faces provided by Google Fonts', 'LayerSlider') ?></figure>
			<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" class="ls-box km-tabs-inner ls-google-fonts">
				<?php wp_nonce_field('save-google-fonts'); ?>
				<input type="hidden" name="ls-save-google-fonts" value="1">

				<!-- Google Fonts list -->
				<div class="inner">
					<ul class="ls-font-list">
						<li class="ls-hidden">
							<a href="#" class="remove dashicons dashicons-dismiss" title="Remove this font"></a>
							<input type="text" name="urlParams[]" readonly="readonly">
							<input type="checkbox" name="onlyOnAdmin[]">
							<?php _e('Load only on admin interface', 'LayerSlider') ?>
						</li>
						<?php if(is_array($googleFonts) && !empty($googleFonts)) : ?>
						<?php foreach($googleFonts as $item) : ?>
						<li>
							<a href="#" class="remove dashicons dashicons-dismiss" title="Remove this font"></a>
							<input type="text" name="urlParams[]" value="<?php echo $item['param'] ?>" readonly="readonly">
							<input type="checkbox" name="onlyOnAdmin[]" <?php echo $item['admin'] ? ' checked="checked"' : '' ?>>
							<?php _e('Load only on admin interface', 'LayerSlider') ?>
						</li>
						<?php endforeach ?>
						<?php else : ?>
						<li class="ls-notice"><?php _e("You didn't add any Google font to your library yet.", "LayerSlider") ?></li>
						<?php endif ?>
					</ul>
				</div>
				<div class="inner ls-font-search">

					<input type="text" placeholder="<?php _e('Enter a font name to add to your collection', 'LayerSlider') ?>">
					<button class="button"><?php _e('Search', 'LayerSlider') ?></button>

					<!-- Google Fonts search pointer -->
					<div class="ls-box ls-pointer">
						<h3 class="header"><?php _e('Choose a font family', 'LayerSlider') ?></h3>
						<div class="fonts">
							<ul class="inner"></ul>
						</div>
						<div class="variants">
							<ul class="inner"></ul>
							<div class="inner">
								<button class="button add-font"><?php _e('Add font', 'LayerSlider') ?></button>
								<button class="button right"><?php _e('Back to results', 'LayerSlider') ?></button>
							</div>
						</div>
					</div>
				</div>

				<!-- Google Fonts search bar -->
				<div class="inner footer">
					<button type="submit" class="button"><?php _e('Save changes', 'LayerSlider') ?></button>
					<?php
						$scripts = array(
							'cyrillic' => __('Cyrillic', 'LayerSlider'),
							'cyrillic-ext' => __('Cyrillic Extended', 'LayerSlider'),
							'devanagari' => __('Devanagari', 'LayerSlider'),
							'greek' => __('Greek', 'LayerSlider'),
							'greek-ext' => __('Greek Extended', 'LayerSlider'),
							'khmer' => __('Khmer', 'LayerSlider'),
							'latin' => __('Latin', 'LayerSlider'),
							'latin-ext' => __('Latin Extended', 'LayerSlider'),
							'vietnamese' => __('Vietnamese', 'LayerSlider')
						);
					?>
					<div class="right">
						<div>
							<select>
								<option><?php _e('Select new', 'LayerSlider') ?></option>
								<?php foreach($scripts as $key => $val) : ?>
								<option value="<?php echo $key ?>"><?php echo $val ?></option>
								<?php endforeach ?>
							</select>
						</div>
						<ul class="ls-google-font-scripts">
							<li class="ls-hidden">
								<span></span>
								<a href="#" class="dashicons dashicons-dismiss" title="<?php _e('Remove character set', 'LayerSlider') ?>"></a>
								<input type="hidden" name="scripts[]" value="">
							</li>
							<?php if(!empty($googleFontScripts) && is_array($googleFontScripts)) : ?>
							<?php foreach($googleFontScripts as $item) : ?>
							<li>
								<span><?php echo $scripts[$item] ?></span>
								<a href="#" class="dashicons dashicons-dismiss" title="<?php _e('Remove character set', 'LayerSlider') ?>"></a>
								<input type="hidden" name="scripts[]" value="<?php echo $item ?>">
							</li>
							<?php endforeach ?>
							<?php else : ?>
							<li>
								<span>Latin</span>
								<a href="#" class="dashicons dashicons-dismiss" title="<?php _e('Remove character set', 'LayerSlider') ?>"></a>
								<input type="hidden" name="scripts[]" value="latin">
							</li>
							<?php endif ?>
						</ul>
						<div><?php _e('Use character sets:', 'LayerSlider') ?></div>
					</div>
				</div>

			</form>
		</div>

		<!-- Advanced -->
		<div class="ls-global-settings">
			<figure>
				<?php _e('Troubleshooting &amp; Advanced Settings', 'LayerSlider') ?>
				<span class="warning"><?php _e("Don't change these options without experience, incorrect settings might break your site.", "LayerSlider") ?></span>
			</figure>
			<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post" class="ls-box km-tabs-inner">
				<?php wp_nonce_field('save-advanced-settings'); ?>
				<input type="hidden" name="ls-save-advanced-settings">

				<table>
					<tr>
						<td><?php _e('Use slider markup caching', 'LayerSlider') ?></td>
						<td><input type="checkbox" name="use_cache" <?php echo get_option('ls_use_cache', true) ? 'checked="checked"' : '' ?>></td>
						<td class="desc"><?php _e('Enabled caching can drastically increase the plugin performance and spare your server from unnecessary load.', 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e("Include scripts in the footer", "LayerSlider") ?></td>
						<td><input type="checkbox" name="include_at_footer" <?php echo get_option('ls_include_at_footer', false) ? 'checked="checked"' : '' ?>></td>
						<td class="desc"><?php _e("Including resources in the footer can improve load times and solve other type of issues. Outdated themes might not support this method.", "LayerSlider") ?></td>
					</tr>
					<tr>
						<td><?php _e("Conditional script loading", "LayerSlider") ?></td>
						<td><input type="checkbox" name="conditional_script_loading" <?php echo get_option('ls_conditional_script_loading', false) ? 'checked="checked"' : '' ?>></td>
						<td class="desc"><?php _e("Increase your site's performance by loading resources only when necessary. Outdated themes might not support this method.", "LayerSlider") ?></td>
					</tr>
					<tr>
						<td><?php _e('Concatenate output', 'LayerSlider') ?></td>
						<td><input type="checkbox" name="concatenate_output" <?php echo get_option('ls_concatenate_output', false) ? 'checked="checked"' : '' ?>></td>
						<td class="desc"><?php _e("Concatenating the plugin's output could solve issues caused by custom filters your theme might use.", "LayerSlider") ?></td>
					</tr>
					<tr>
						<td><?php _e('Use Google CDN version of jQuery', 'LayerSlider') ?></td>
						<td><input type="checkbox" name="use_custom_jquery" <?php echo get_option('ls_use_custom_jquery', false) ? 'checked="checked"' : '' ?>></td>
						<td class="desc"><?php _e('This option will likely solve "Old jQuery" issues.', 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e('Put JS includes to body', 'LayerSlider') ?></td>
						<td><input type="checkbox" name="put_js_to_body" <?php echo get_option('ls_put_js_to_body', false) ? 'checked="checked"' : '' ?>></td>
						<td class="desc"><?php _e('This is the most common workaround for jQuery related issues, and is recommended when you experience problems with jQuery.', 'LayerSlider') ?></td>
					</tr>
				</table>
				<div class="footer">
					<button type="submit" class="button"><?php _e('Save changes', 'LayerSlider') ?></button>
				</div>
			</form>
		</div>
	</div>


	<div class="ls-box ls-news">
		<div class="header medium">
			<h2><?php _e('LayerSlider News', 'LayerSlider') ?></h2>
			<div class="filters">
				<span><?php _e('Filter:', 'LayerSlider') ?></span>
				<ul>
					<li class="active" data-page="all"><?php _e('All', 'LayerSlider') ?></li>
					<li data-page="announcements"><?php _e('Announcements', 'LayerSlider') ?></li>
					<li data-page="changes"><?php _e('Release log', 'LayerSlider') ?></li>
					<li data-page="betas"><?php _e('Beta versions', 'LayerSlider') ?></li>
				</ul>
			</div>
			<div class="ls-version"<?php _e('>You have version', 'LayerSlider') ?> <?php echo LS_PLUGIN_VERSION ?> <?php _e('installed', 'LayerSlider') ?></div>
		</div>
		<div>
			<iframe src="http://news.kreaturamedia.com/layerslider/"></iframe>
		</div>
	</div>
</div>

<!-- Help menu WP Pointer -->
<?php
if(get_user_meta(get_current_user_id(), 'layerslider_help_wp_pointer', true) != '1') {
add_user_meta(get_current_user_id(), 'layerslider_help_wp_pointer', '1'); ?>
<script type="text/javascript">

	// Help
	jQuery(document).ready(function() {
		jQuery('#contextual-help-link-wrap').pointer({
			pointerClass : 'ls-help-pointer',
			pointerWidth : 320,
			content: '<h3><?php _e('The documentation is here', 'LayerSlider') ?></h3><div class="inner"><?php _e('Open this help menu to quickly access to our online documentation.', 'LayerSlider') ?></div>',
			position: {
				edge : 'top',
				align : 'right'
			}
		}).pointer('open');
	});
</script>
<?php } ?>
<script type="text/javascript">
	var lsScreenOptions = <?php echo json_encode($lsScreenOptions) ?>;
</script>
