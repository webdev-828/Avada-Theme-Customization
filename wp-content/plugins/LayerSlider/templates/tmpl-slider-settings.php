<?php if(!defined('LS_ROOT_FILE')) { header('HTTP/1.0 403 Forbidden'); exit; } ?>

<!-- Slider title -->
<div class="ls-slider-titlewrap">
	<?php $sliderName = !empty($slider['properties']['title']) ? $slider['properties']['title'] : ''; ?>
	<input type="text" name="title" value="<?php echo $sliderName ?>" id="title" autocomplete="off" placeholder="<?php _e('Type your slider name here', 'LayerSlider') ?>">
	<div class="ls-slider-slug">
		Slider slug:<input type="text" name="slug" value="<?php echo !empty($slider['properties']['slug']) ? $slider['properties']['slug'] : '' ?>" autocomplete="off" placeholder="<?php _e('e.g. homepageslider', 'LayerSlider') ?>" data-help="Set a custom slider identifier to use in shortcodes instead of the database ID. Needs to be unique, and can contain only alphanumeric characters. This setting is optional.">
	</div>
</div>

<!-- Slider settings -->
<div class="ls-box ls-settings">
	<h3 class="header medium"><?php _e('Slider Settings', 'LayerSlider') ?></h3>
	<div class="inner">
		<ul class="ls-settings-sidebar">
			<li class="active"><i class="dashicons dashicons-editor-distractionfree"></i><?php _e('Layout', 'LayerSlider') ?></li>
			<li><i class="dashicons dashicons-editor-video"></i><?php _e('Slideshow', 'LayerSlider') ?></li>
			<li><i class="dashicons dashicons-admin-appearance"></i><?php _e('Appearance', 'LayerSlider') ?></li>
			<li><i class="dashicons dashicons-image-flip-horizontal"></i><?php _e('Navigation Area', 'LayerSlider') ?></li>
			<li><i class="dashicons dashicons-screenoptions"></i><?php _e('Thumbnail Navigation', 'LayerSlider') ?></li>
			<li><i class="dashicons dashicons-video-alt3"></i><?php _e('Videos', 'LayerSlider') ?></li>
			<li><i class="dashicons dashicons-admin-generic"></i><?php _e('Misc', 'LayerSlider') ?></li>
			<li><i class="dashicons dashicons-admin-post"></i><?php _e('YourLogo', 'LayerSlider') ?></li>
		</ul>
		<div class="ls-settings-contents">
			<table>

				<!-- Layout -->
				<tbody class="active">
					<tr><th colspan="3"><?php _e('Slider dimensions', 'LayerSlider') ?></th></tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['width']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetInput($lsDefaults['slider']['width'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['width']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['height']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetInput($lsDefaults['slider']['height'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['height']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr><th colspan="3"><?php _e('Responsive mode', 'LayerSlider') ?></th></tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['responsiveness']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['responsiveness'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['responsiveness']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['maxWidth']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetInput($lsDefaults['slider']['maxWidth'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['maxWidth']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr><th colspan="3"><?php _e('Full-width slider settings', 'LayerSlider') ?></th></tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['fullWidth']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['fullWidth'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['fullWidth']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['responsiveUnder']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetInput($lsDefaults['slider']['responsiveUnder'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['responsiveUnder']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['layersContainer']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetInput($lsDefaults['slider']['layersContainer'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['layersContainer']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr><th colspan="3"><?php _e('Other settings', 'LayerSlider') ?></th></tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['hideOnMobile']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['hideOnMobile'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['hideOnMobile']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['hideUnder']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetInput($lsDefaults['slider']['hideUnder'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['hideUnder']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['hideOver']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetInput($lsDefaults['slider']['hideOver'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['hideOver']['desc'], 'LayerSlider') ?></td>
					</tr>
				</tbody>

				<!-- Slideshow -->
				<tbody>
					<tr><th colspan="3"><?php _e('Slideshow behavior', 'LayerSlider') ?></th></tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['autoStart']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['autoStart'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['autoStart']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['startInViewport']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['startInViewport'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['startInViewport']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['pauseOnHover']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['pauseOnHover'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['pauseOnHover']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr><th colspan="3"><?php _e('Starting slide options', 'LayerSlider') ?></th></tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['firstSlide']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetInput($lsDefaults['slider']['firstSlide'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['firstSlide']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['animateFirstSlide']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['animateFirstSlide'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['animateFirstSlide']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr><th colspan="3"><?php _e('Slideshow navigation', 'LayerSlider') ?></th></tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['keybNavigation']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['keybNavigation'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['keybNavigation']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['touchNavigation']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['touchNavigation'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['touchNavigation']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr><th colspan="3"><?php _e('Looping', 'LayerSlider') ?></th></tr>
					<tr>
						<td><?php _e('Loops', 'LayerSlider') ?></td>
						<td><?php lsGetInput($lsDefaults['slider']['loops'], $slider['properties']) ?></td>
						<td class="desc"><?php _e('Number of loops if automatically start slideshow is enabled (0 means infinite!)', 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['forceLoopNumber']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['forceLoopNumber'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['forceLoopNumber']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr><th colspan="3"><?php _e('Other settings', 'LayerSlider') ?></th></tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['twoWaySlideshow']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['twoWaySlideshow'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['twoWaySlideshow']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['shuffle']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['shuffle'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['shuffle']['desc'], 'LayerSlider') ?></td>
					</tr>
				</tbody>

				<!-- Appearance -->
				<tbody>
					<tr>
						<td><?php _e('Skin', 'LayerSlider') ?></td>
						<td>
							<select name="skin">
								<?php $slider['properties']['skin'] = empty($slider['properties']['skin']) ? $lsDefaults['slider']['skin']['value'] : $slider['properties']['skin'] ?>
								<?php $skins = LS_Sources::getSkins(); ?>
								<?php foreach($skins as $skin) : ?>
								<?php $selected = ($skin['handle'] == $slider['properties']['skin']) ? ' selected="selected"' : '' ?>
								<option value="<?php echo $skin['handle'] ?>"<?php echo $selected ?>>
									<?php
									echo $skin['name'];
									if(!empty($skin['info']['note'])) { echo ' - ' . $skin['info']['note']; }
									?>
								</option>
								<?php endforeach; ?>
							</select>
						</td>
						<td class="desc"><?php _e("You can change the skin of the slider. The 'noskin' skin is a border- and buttonless skin. Your custom skins will appear in the list when you create their folders as well.", "LayerSlider") ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['globalBGColor']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetInput($lsDefaults['slider']['globalBGColor'], $slider['properties'], array('class' => 'input ls-colorpicker minicolors-input')) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['globalBGColor']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e('Background image', 'LayerSlider') ?></td>
						<td>
							<?php $bgImage = !empty($slider['properties']['backgroundimage']) ? $slider['properties']['backgroundimage'] : null; ?>
							<?php $bgImageId = !empty($slider['properties']['backgroundimageId']) ? $slider['properties']['backgroundimageId'] : null; ?>
							<input type="hidden" name="backgroundimageId" value="<?php echo !empty($slider['properties']['backgroundimageId']) ? $slider['properties']['backgroundimageId'] : '' ?>">
							<input type="hidden" name="backgroundimage" value="<?php echo !empty($slider['properties']['backgroundimage']) ? $slider['properties']['backgroundimage'] : '' ?>">
							<div class="ls-image ls-global-background ls-upload">
								<div><img src="<?php echo apply_filters('ls_get_thumbnail', $bgImageId, $bgImage) ?>" alt=""></div>
								<a href="#" class="dashicons dashicons-dismiss"></a>
							</div>
						</td>
						<td class="desc"><?php _e($lsDefaults['slider']['globalBGImage']['desc'], 'LayerSlider') ?></td>
					</tr>

					<tr>
						<td><?php _e($lsDefaults['slider']['sliderFadeInDuration']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetInput($lsDefaults['slider']['sliderFadeInDuration'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['sliderFadeInDuration']['desc'], 'LayerSlider') ?></td>
					</tr>

					<tr>
						<td>
							<?php _e('Custom slider CSS', 'LayerSlider') ?> <br>
						</td>
						<td colspan="2">
							<textarea name="sliderstyle" cols="30" rows="10"><?php echo !empty($slider['properties']['sliderstyle']) ? $slider['properties']['sliderstyle'] : $lsDefaults['slider']['sliderStyle']['value'] ?></textarea>
						</td>
					</tr>
				</tbody>

				<!-- Navigation Area -->
				<tbody>
					<tr><th colspan="3"><?php _e('Show navigation buttons', 'LayerSlider') ?></th></tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['navPrevNextButtons']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['navPrevNextButtons'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['navPrevNextButtons']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['navStartStopButtons']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['navStartStopButtons'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['navStartStopButtons']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['navSlideButtons']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['navSlideButtons'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['navSlideButtons']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr><th colspan="3"><?php _e('Navigation buttons on hover', 'LayerSlider') ?></th></tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['hoverPrevNextButtons']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['hoverPrevNextButtons'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['hoverPrevNextButtons']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['hoverSlideButtons']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['hoverSlideButtons'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['hoverSlideButtons']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr><th colspan="3"><?php _e('Slideshow timers', 'LayerSlider') ?></th></tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['barTimer']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['barTimer'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['barTimer']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['circleTimer']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['circleTimer'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['circleTimer']['desc'], 'LayerSlider') ?></td>
					</tr>
				</tbody>

				<!-- Thumbnail navigation -->
				<tbody>
					<tr><th colspan="3"><?php _e('Appearance', 'LayerSlider') ?></th></tr>
					<tr>
						<td><?php _e('Thumbnail navigation', 'LayerSlider') ?></td>
						<td><?php lsGetSelect($lsDefaults['slider']['thumbnailNavigation'], $slider['properties']) ?></td>
						<td class="desc"></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['thumbnailAreaWidth']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetInput($lsDefaults['slider']['thumbnailAreaWidth'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['thumbnailAreaWidth']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr><th colspan="3"><?php _e('Thumbnail dimensions', 'LayerSlider') ?></th></tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['thumbnailWidth']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetInput($lsDefaults['slider']['thumbnailWidth'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['thumbnailWidth']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['thumbnailHeight']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetInput($lsDefaults['slider']['thumbnailHeight'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['thumbnailHeight']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr><th colspan="3"><?php _e('Thumbnail appearance', 'LayerSlider') ?></th></tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['thumbnailActiveOpacity']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetInput($lsDefaults['slider']['thumbnailActiveOpacity'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['thumbnailActiveOpacity']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['thumbnailInactiveOpacity']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetInput($lsDefaults['slider']['thumbnailInactiveOpacity'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['thumbnailInactiveOpacity']['desc'], 'LayerSlider') ?></td>
					</tr>
				</tbody>

				<!-- Videos -->
				<tbody>
					<tr>
						<td><?php _e($lsDefaults['slider']['autoPlayVideos']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['autoPlayVideos'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['autoPlayVideos']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['autoPauseSlideshow']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetSelect($lsDefaults['slider']['autoPauseSlideshow'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['autoPauseSlideshow']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['youtubePreviewQuality']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetSelect($lsDefaults['slider']['youtubePreviewQuality'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['youtubePreviewQuality']['desc'], 'LayerSlider') ?></td>
					</tr>
				</tbody>

				<!-- Misc -->
				<tbody>
					<tr>
						<td><?php _e($lsDefaults['slider']['imagePreload']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['imagePreload'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['imagePreload']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['lazyLoad']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['lazyLoad'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['lazyLoad']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['relativeURLs']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetCheckbox($lsDefaults['slider']['relativeURLs'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['relativeURLs']['desc'], 'LayerSlider') ?></td>
					</tr>
				</tbody>

				<!-- YourLogo -->
				<tbody>
					<tr>
						<td><?php _e($lsDefaults['slider']['yourLogoImage']['name'], 'LayerSlider') ?></td>
						<td>
							<?php $slider['properties']['yourlogo'] = !empty($slider['properties']['yourlogo']) ? $slider['properties']['yourlogo'] : null; ?>
							<?php $slider['properties']['yourlogoId'] = !empty($slider['properties']['yourlogoId']) ? $slider['properties']['yourlogoId'] : null; ?>
							<input type="hidden" name="yourlogoId" value="<?php echo !empty($slider['properties']['yourlogoId']) ? $slider['properties']['yourlogoId'] : '' ?>">
							<input type="hidden" name="yourlogo" value="<?php echo !empty($slider['properties']['yourlogo']) ? $slider['properties']['yourlogo'] : '' ?>">
							<div class="ls-image ls-upload ls-yourlogo-upload">
								<div><img src="<?php echo apply_filters('ls_get_thumbnail', $slider['properties']['yourlogoId'], $slider['properties']['yourlogo']) ?>" alt=""></div>
								<a href="#" class="dashicons dashicons-dismiss"></a>
							</div>
						</td>
						<td class="desc"><?php _e($lsDefaults['slider']['yourLogoImage']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['yourLogoStyle']['name'], 'LayerSlider') ?></td>
						<td colspan="2">
							<textarea name="yourlogostyle" cols="30" rows="10"><?php echo !empty($slider['properties']['yourlogostyle']) ? $slider['properties']['yourlogostyle'] : $lsDefaults['slider']['yourLogoStyle']['value'] ?></textarea>
						</td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['yourLogoLink']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetInput($lsDefaults['slider']['yourLogoLink'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['yourLogoLink']['desc'], 'LayerSlider') ?></td>
					</tr>
					<tr>
						<td><?php _e($lsDefaults['slider']['yourLogoTarget']['name'], 'LayerSlider') ?></td>
						<td><?php lsGetSelect($lsDefaults['slider']['yourLogoTarget'], $slider['properties']) ?></td>
						<td class="desc"><?php _e($lsDefaults['slider']['yourLogoTarget']['desc'], 'LayerSlider') ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="clear"></div>
	</div>
</div>
