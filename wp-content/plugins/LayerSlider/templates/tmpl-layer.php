<?php if(!defined('LS_ROOT_FILE')) { header('HTTP/1.0 403 Forbidden'); exit; } ?>
<script type="text/html" id="ls-layer-template">
	<div class="ls-sublayer-page ls-sublayer-basic active">

		<input type="hidden" name="media" value="img">
		<div class="ls-layer-kind">
			<ul>
				<li data-section="img" class="active"><span class="dashicons dashicons-format-image"></span><?php _e('Image', 'LayerSlider') ?></li>
				<li data-section="text"><span class="dashicons dashicons-text"></span><?php _e('Text', 'LayerSlider') ?></li>
				<li data-section="html"><span class="dashicons dashicons-video-alt3"></span><?php _e('HTML / Video / Audio', 'LayerSlider') ?></li>
				<li data-section="post"><span class="dashicons dashicons-admin-post"></span><?php _e('Dynamic content from posts', 'LayerSlider') ?></li>
			</ul>
		</div>
		<!-- End of Layer Media Type -->

		<!-- Layer Element Type -->
		<input type="hidden" name="type" value="p">
		<ul class="ls-sublayer-element ls-hidden">
			<li class="ls-type active" data-element="p"><?php _e('Paragraph', 'LayerSlider') ?></li>
			<li class="ls-type" data-element="h1"><?php _e('H1', 'LayerSlider') ?></li>
			<li class="ls-type" data-element="h2"><?php _e('H2', 'LayerSlider') ?></li>
			<li class="ls-type" data-element="h3"><?php _e('H3', 'LayerSlider') ?></li>
			<li class="ls-type" data-element="h4"><?php _e('H4', 'LayerSlider') ?></li>
			<li class="ls-type" data-element="h5"><?php _e('H5', 'LayerSlider') ?></li>
			<li class="ls-type" data-element="h6"><?php _e('H6', 'LayerSlider') ?></li>
		</ul>
		<!-- End of Layer Element Type -->

		<div class="ls-layer-sections">

			<!-- Image Layer -->
			<div class="ls-image-uploader slide-image clearfix">
				<input type="hidden" name="imageId">
				<input type="hidden" name="image">
				<div class="ls-image ls-upload ls-bulk-upload ls-layer-image">
					<div><img src="<?php echo LS_ROOT_URL.'/static/img/not_set.png' ?>" alt=""></div>
					<a href="#" class="dashicons dashicons-dismiss"></a>
				</div>
				<p>
					<?php _e('Click on the image preview to open WordPress Media Library or', 'LayerSlider') ?>
					<a href="#" class="ls-url-prompt"><?php _e('insert from URL', 'LayerSlider') ?></a> or
					<a href="#" class="ls-post-image"><?php _e('use post image', 'LayerSlider') ?></a>.
				</p>
			</div>

			<!-- Text/HTML Layer -->
			<div class="ls-html-code ls-hidden">
				<textarea name="html" cols="50" rows="5" placeholder="Enter layer content here" data-help="<?php _e('Type here the contents of your layer. You can use any HTML codes in this field to insert content others then text. This field is also shortcode-aware, so you can insert content from other plugins as well as video embed codes.', 'LayerSlider') ?>"></textarea>
				<p class="ls-hidden">
					<button type="button" class="button ls-upload ls-bulk-upload ls-insert-media">
						<span class="dashicons dashicons-admin-media"></span>
						<?php _e('Add Media', 'LayerSlider') ?>
					</button>
					<?php _e('Insert self-hosted video or audio', 'LayerSlider') ?>
				</p>
			</div>

			<!-- Dynamic Layer -->
			<div class="ls-post-section ls-hidden">
				<div class="ls-posts-configured">
					<ul class="ls-post-placeholders clearfix">
						<li><span>[post-id]</span></li>
						<li><span>[post-slug]</span></li>
						<li><span>[post-url]</span></li>
						<li><span>[date-published]</span></li>
						<li><span>[date-modified]</span></li>
						<li><span>[image]</span></li>
						<li><span>[image-url]</span></li>
						<li><span>[title]</span></li>
						<li><span>[content]</span></li>
						<li><span>[excerpt]</span></li>
						<li data-placeholder="<a href=&quot;[post-url]&quot;>Read more</a>"><span>[link]</span></li>
						<li><span>[author]</span></li>
						<li><span>[author-id]</span></li>
						<li><span>[categories]</span></li>
						<li><span>[tags]</span></li>
						<li><span>[comments]</span></li>
						<li><span>[meta:&lt;fieldname&gt;]</span></li>
					</ul>
					<p>
						<?php _e("Click on one or more post placeholders to insert them into your layer's content. Post placeholders act like shortcodes in WP, and they will be filled with the actual content from your posts.", "LayerSlider") ?><br>
						<?php _e('Limit text length (if any)', 'LayerSlider') ?>
						<input type="number" name="post_text_length">
						<button type="button" class="button ls-configure-posts"><span class="dashicons dashicons-admin-post"></span><?php _e('Configure post options', 'LayerSlider') ?></button>
					</p>
				</div>
			</div>
		</div>
	</div>
	<div class="ls-sublayer-page ls-sublayer-options">
		<table>
			<tbody>
				<tr>
					<td rowspan="3"><?php _e('Transition in', 'LayerSlider') ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionInOffsetX']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionInOffsetX'], null, array('class' => 'sublayerprop')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionInOffsetY']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionInOffsetY'], null, array('class' => 'sublayerprop')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionInDuration']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionInDuration'], null, array('class' => 'sublayerprop')) ?> ms</td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionInDelay']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionInDelay'], null, array('class' => 'sublayerprop')) ?> ms</td>
					<td class="right"><a href="http://easings.net/" target="_blank"><?php echo $lsDefaults['layers']['transitionInEasing']['name'] ?></a></td>
					<td><?php lsGetSelect($lsDefaults['layers']['transitionInEasing'], null, array('class' => 'sublayerprop', 'options' => $lsDefaults['easings'])) ?></td>
				</tr>
				<tr>
					<td class="right"><?php echo $lsDefaults['layers']['transitionInFade']['name'] ?></td>
					<td><?php lsGetCheckbox($lsDefaults['layers']['transitionInFade'], null, array('class' => 'sublayerprop')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionInRotate']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionInRotate'], null, array('class' => 'sublayerprop')) ?> &deg;</td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionInRotateX']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionInRotateX'], null, array('class' => 'sublayerprop')) ?> &deg;</td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionInRotateY']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionInRotateY'], null, array('class' => 'sublayerprop')) ?> &deg;</td>
					<td colspan="2" rowspan="2" class="center">
						<?php echo $lsDefaults['layers']['transitionInTransformOrigin']['name'] ?><br>
						<?php lsGetInput($lsDefaults['layers']['transitionInTransformOrigin'], null, array('class' => 'sublayerprop')) ?>
					</td>
				</tr>

				<tr>
					<td class="right"><?php echo $lsDefaults['layers']['transitionInSkewX']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionInSkewX'], null, array('class' => 'sublayerprop')) ?> &deg;</td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionInSkewY']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionInSkewY'], null, array('class' => 'sublayerprop')) ?> &deg;</td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionInScaleX']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionInScaleX'], null, array('class' => 'sublayerprop')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionInScaleY']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionInScaleY'], null, array('class' => 'sublayerprop')) ?></td>
				</tr>
				<tr class="ls-separator"><td colspan="11"></td></tr>
				<tr>
					<td rowspan="3"><?php _e('Transition out', 'LayerSlider') ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionOutOffsetX']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionOutOffsetX'], null, array('class' => 'sublayerprop')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionOutOffsetY']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionOutOffsetY'], null, array('class' => 'sublayerprop')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionOutDuration']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionOutDuration'], null, array('class' => 'sublayerprop')) ?> ms</td>
					<td class="right"><?php echo $lsDefaults['layers']['showUntil']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['showUntil'], null, array('class' => 'sublayerprop')) ?> ms</td>
					<td class="right"><a href="http://easings.net/" target="_blank"><?php echo $lsDefaults['layers']['transitionOutEasing']['name'] ?></a></td>
					<td><?php lsGetSelect($lsDefaults['layers']['transitionOutEasing'], null, array('class' => 'sublayerprop', 'options' => $lsDefaults['easings'])) ?></td>
				</tr>
				<tr>
					<td class="right"><?php echo $lsDefaults['layers']['transitionOutFade']['name'] ?></td>
					<td><?php lsGetCheckbox($lsDefaults['layers']['transitionOutFade'], null, array('class' => 'sublayerprop')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionOutRotate']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionOutRotate'], null, array('class' => 'sublayerprop')) ?> &deg;</td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionOutRotateX']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionOutRotateX'], null, array('class' => 'sublayerprop')) ?> &deg;</td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionOutRotateY']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionOutRotateY'], null, array('class' => 'sublayerprop')) ?> &deg;</td>
					<td colspan="2" rowspan="2" class="center">
						<?php echo $lsDefaults['layers']['transitionOutTransformOrigin']['name'] ?><br>
						<?php lsGetInput($lsDefaults['layers']['transitionOutTransformOrigin'], null, array('class' => 'sublayerprop')) ?>
					</td>
				</tr>

				<tr>
					<td class="right"><?php echo $lsDefaults['layers']['transitionOutSkewX']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionOutSkewX'], null, array('class' => 'sublayerprop')) ?> &deg;</td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionOutSkewY']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionOutSkewY'], null, array('class' => 'sublayerprop')) ?> &deg;</td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionOutScaleX']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionOutScaleX'], null, array('class' => 'sublayerprop')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionOutScaleY']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['transitionOutScaleY'], null, array('class' => 'sublayerprop')) ?></td>
				</tr>
				<tr class="ls-separator"><td colspan="11"></td></tr>
				<tr>
					<td rowspan="3"><?php _e('Other options', 'LayerSlider') ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['transitionParallaxLevel']['name'] ?></td>
					<td colspan="9"><?php lsGetInput($lsDefaults['layers']['transitionParallaxLevel'], null, array('class' => 'sublayerprop')) ?></td>
				</tr>
		</table>
	</div>
	<div class="ls-sublayer-page ls-sublayer-link">
		<h3 class="subheader">Linking</h3>
		<div class="ls-slide-link">
			<?php lsGetInput($lsDefaults['layers']['linkURL'], null, array('placeholder' => $lsDefaults['layers']['linkURL']['name'] )) ?>
			<br> <?php lsGetSelect($lsDefaults['layers']['linkTarget'], null) ?>
			<span> or <a href="#"><?php _e('use post URL', 'LayerSlider') ?></a></span>
		</div>

		<h3 class="subheader">Attributes</h3>
		<table class="ls-sublayer-attributes">
			<tbody>
				<tr>
					<td class="right"><?php echo $lsDefaults['layers']['ID']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['ID'], null) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['class']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['class'], null) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['title']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['title'], null) ?></td>
				</tr>
				<tr>
					<td class="right"><?php echo $lsDefaults['layers']['alt']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['alt'], null) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['rel']['name'] ?></td>
					<td colspan="3"><?php lsGetInput($lsDefaults['layers']['rel'], null) ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="ls-sublayer-page ls-sublayer-style">
		<table>
			<tbody>
				<tr>
					<td><?php _e('Layout & Positions', 'LayerSlider') ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['width']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['width'], null, array('class' => 'auto')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['height']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['height'], null, array('class' => 'auto')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['top']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['top'], null) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['left']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['left'], null) ?></td>
				</tr>
				<tr>
					<td><?php _e('Padding', 'LayerSlider') ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['paddingTop']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['paddingTop'], null, array('class' => 'auto')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['paddingRight']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['paddingRight'], null, array('class' => 'auto')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['paddingBottom']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['paddingBottom'], null, array('class' => 'auto')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['paddingLeft']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['paddingLeft'], null, array('class' => 'auto')) ?></td>
				</tr>
				<tr>
					<td><?php _e('Border', 'LayerSlider') ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['borderTop']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['borderTop'], null, array('class' => 'auto')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['borderRight']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['borderRight'], null, array('class' => 'auto')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['borderBottom']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['borderBottom'], null, array('class' => 'auto')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['borderLeft']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['borderLeft'], null, array('class' => 'auto')) ?></td>
				</tr>
				<tr>
					<td><?php _e('Font', 'LayerSlider') ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['fontFamily']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['fontFamily'], null, array('class' => 'auto')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['fontSize']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['fontSize'], null, array('class' => 'auto')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['lineHeight']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['lineHeight'], null, array('class' => 'auto')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['color']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['color'], null, array('class' => 'auto ls-colorpicker')) ?></td>
				</tr>
				<tr>
					<td><?php _e('Misc', 'LayerSlider') ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['background']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['background'], null, array('class' => 'auto ls-colorpicker')) ?></td>
					<td class="right"><?php echo $lsDefaults['layers']['borderRadius']['name'] ?></td>
					<td><?php lsGetInput($lsDefaults['layers']['borderRadius'], null, array('class' => 'auto')) ?></td>
					<td class="right"><?php _e('Word-wrap', 'LayerSlider') ?></td>
					<td colspan="3"><input type="checkbox" name="wordwrap" data-help="<?php _e('If you use custom sized layers, you have to enable this setting to wrap your text.', 'LayerSlider') ?>" class="checkbox"></td>
				</tr>
				<tr class="ls-separator"><td colspan="11"></td></tr>
				<tr>
					<td><?php _e('Custom CSS', 'LayerSlider') ?></td>
					<td colspan="8"><textarea rows="5" cols="50" name="style" class="style" data-help="<?php _e('If you want to set style settings other then above, you can use here any CSS codes. Please make sure to write valid markup.', 'LayerSlider') ?>"></textarea></td>
				</tr>
			</tbody>
		</table>
	</div>
</script>