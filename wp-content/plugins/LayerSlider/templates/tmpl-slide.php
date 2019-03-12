<?php if(!defined('LS_ROOT_FILE')) { header('HTTP/1.0 403 Forbidden'); exit; } ?>
<div class="ls-box ls-layer-box active">
	<input type="hidden" name="layerkey" value="0">
	<table>
		<thead class="ls-layer-options-thead">
			<tr>
				<td colspan="3">
					<i class="dashicons dashicons-welcome-write-blog"></i>
					<h4><?php _e('Slide Options', 'LayerSlider') ?></h4>
				</td>
			</tr>
		</thead>
		<tbody class="ls-slide-options">
			<input type="hidden" name="post_offset" value="-1">
			<input type="hidden" name="3d_transitions">
			<input type="hidden" name="2d_transitions">
			<input type="hidden" name="custom_3d_transitions">
			<input type="hidden" name="custom_2d_transitions">
			<tr>
				<td valign="top">
					<h3 class="subheader">Slide image &amp; thumbnail</h3>
					<div class="inner slide-image">
						<input type="hidden" name="backgroundId">
						<input type="hidden" name="background">
						<div class="ls-image ls-upload ls-bulk-upload ls-slide-image" data-help="<?php echo $lsDefaults['slides']['image']['tooltip'] ?>">
							<div><img src="<?php echo LS_ROOT_URL.'/static/img/not_set.png' ?>" alt=""></div>
							<a href="#" class="dashicons dashicons-dismiss"></a>
						</div>
						<?php _e('or', 'LayerSlider') ?> <a href="#" class="ls-url-prompt"><?php _e('enter URL', 'LayerSlider') ?></a> <br>
						<?php _e('or', 'LayerSlider') ?> <a href="#" class="ls-post-image"><?php _e('use post image', 'LayerSlider') ?></a>
					</div>
					<div class="hsep"></div>
					<div class="inner slide-image">
						<input type="hidden" name="thumbnailId">
						<input type="hidden" name="thumbnail">
						<div class="ls-image ls-upload ls-slide-thumbnail" data-help="<?php echo $lsDefaults['slides']['thumbnail']['tooltip'] ?>">
							<div><img src="<?php echo LS_ROOT_URL.'/static/img/not_set.png' ?>" alt=""></div>
							<a href="#" class="dashicons dashicons-dismiss"></a>
						</div>
						<?php _e('or', 'LayerSlider') ?> <a href="#" class="ls-url-prompt"><?php _e('enter URL', 'LayerSlider') ?></a>
					</div>
				</td>
				<td valign="top" class="second">
					<h3 class="subheader">Duration</h3>
					<?php lsGetInput($lsDefaults['slides']['delay'], null, array('class' => 'layerprop')) ?> ms
					<h3 class="subheader">
						Transition
					</h3>
					<button type="button" class="button ls-select-transitions new" data-help="<?php _e('You can select your desired slide transitions by clicking on this button.', 'LayerSlider') ?>">Select transitions</button><br><br>
					<?php echo $lsDefaults['slides']['timeshift']['name'] ?><br>
					<?php lsGetInput($lsDefaults['slides']['timeshift'], null, array('class' => 'layerprop')) ?> ms
				</td>
				<td valign="top">
					<h3 class="subheader">Linking</h3>
					<div class="ls-slide-link">
						<?php lsGetInput($lsDefaults['slides']['linkUrl'], null, array('placeholder' => $lsDefaults['slides']['linkUrl']['name'] )) ?>
						<br> <?php lsGetSelect($lsDefaults['slides']['linkTarget'], null) ?>
						<span> or <a href="#"><?php _e('use post URL', 'LayerSlider') ?></a></span>
					</div>
					<h3 class="subheader">Misc</h3>
					<table class="noborder">
						<tr>
							<td>
								<?php echo $lsDefaults['slides']['ID']['name'] ?>
								<?php lsGetInput($lsDefaults['slides']['ID'], null) ?>
							</td>
							<td>
								<?php echo $lsDefaults['slides']['deeplink']['name'] ?>
								<?php lsGetInput($lsDefaults['slides']['deeplink'], null) ?>
							</td>
							<td>
								<?php _e('Hidden', 'LayerSlider') ?>
								<input type="checkbox" name="skip" class="checkbox" data-help="<?php _e("If you don't want to use this slide in your front-page, but you want to keep it, you can hide it with this switch.", "LayerSlider") ?>">
							</td>
						</tr>
						<tr>
							<td colspan="3">
								<button type="button" class="button ls-configure-posts"><span class="dashicons dashicons-admin-post"></span><?php _e('Configure post options', 'LayerSlider') ?></button>
								<button type="button" class="button ls-layer-duplicate"><span class="dashicons dashicons-admin-page"></span><?php _e('Duplicate this slide', 'LayerSlider') ?></button>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	<table>
		<thead>
			<tr>
				<td>
					<i class="dashicons dashicons-editor-video ls-preview-icon"></i>
					<h4>
						<span><?php _e('Preview', 'LayerSlider') ?></span>
						<div class="ls-editor-zoom">
							<span class="ls-editor-slider-text">Size:</span>
							<div class="ls-editor-slider"></div>
							<span class="ls-editor-slider-val">100%</span>
						</div>
					</h4>
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="ls-preview-td">
					<div class="ls-preview-wrapper">
						<div class="ls-preview">
							<div id="ls-preview-layers" class="draggable ls-layer"></div>
						</div>
						<div class="ls-real-time-preview"></div>
					</div>
					<button type="button" class="button ls-preview-button"><?php _e('Enter Preview', 'LayerSlider') ?></button>
				</td>
			</tr>
		</tbody>
	</table>

	<div class="ls-sublayer-wrapper">
		<h4>
			<span class="dashicons dashicons-images-alt2 ls-layers-icon"></span>
			<span class="ls-layers-text"><?php _e('Layers', 'LayerSlider') ?></span>
			<a href="#" class="ls-add-sublayer">
				<span class="dashicons dashicons-plus"></span><?php _e('Add New', 'LayerSlider') ?>
			</a>
			<div class="ls-timeline-switch filters">
				<ul>
					<li class="active"><?php _e('Layer options', 'LayerSlider') ?></li>
					<li><?php _e('Timeline', 'LayerSlider') ?></li>
				</ul>
			</div>
		</h4>
		<ul class="ls-sublayers ls-sublayer-sortable"></ul>
		<div class="ls-sublayer-pages-wrapper">
			<div class="ls-sublayer-nav">
				<a href="#" class="active"><?php _e('Content', 'LayerSlider') ?></a>
				<a href="#"><?php _e('Transition', 'LayerSlider') ?></a>
				<a href="#"><?php _e('Link & Attributes', 'LayerSlider') ?></a>
				<a href="#"><?php _e('Styles', 'LayerSlider') ?></a>
			</div>
			<div class="ls-sublayer-pages">
			</div>
		</div>
	</div>
</div>