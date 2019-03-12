<?php if(!defined('LS_ROOT_FILE')) {  header('HTTP/1.0 403 Forbidden'); exit; }

$demoSliders = LS_Sources::getDemoSliders(); ?>
<script type="text/html" id="tmpl-demo-sliders">
	<div id="ls-import-samples-template" class="ls-pointer ls-box">
		<span class="ls-mce-arrow"></span>
		<h3 class="header"><?php _e('Choose a demo slider to import', 'LayerSlider') ?></h3>
		<ul class="inner">
			<?php foreach($demoSliders as $item) : ?>
			<li>
				<a href="<?php echo wp_nonce_url('?page=layerslider&action=import_sample&slider='.$item['handle'].'', 'import-sample-sliders') ?>">
					<div class="preview"><img src="<?php echo $item['preview'] ?>"></div>
					<div class="title"><?php echo $item['name'] ?></div>
				</a>
			</li>
			<?php endforeach ?>
		</ul>
		<ul class="inner sep">
			<li>
				<a href="<?php echo wp_nonce_url('?page=layerslider&action=import_sample&slider=all', 'import-sample-sliders') ?>">
					<?php _e('Import all demo sliders (might be slow)', 'LayerSlider') ?>
				</a>
			</li>
		</ul>
		<div class="inner">
			<?php _e('More demo content can be found in the downloaded package from CodeCanyon. You can import them in the usual way in the "Import and Export Sliders" section below.', 'LayerSlider') ?>
		</div>
	</div>
</script>