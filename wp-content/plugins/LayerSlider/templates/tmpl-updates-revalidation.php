<?php if(!defined('LS_ROOT_FILE')) {  header('HTTP/1.0 403 Forbidden'); exit; }

if(
	get_option('layerslider-validated', null) === '1' &&
	get_option('layerslider-authorized-site', null) === null &&
	get_option('ls-show-revalidation-notice', 1)
) : ?>
<div class="ls-overlay" data-manualclose="true"></div>
<div id="ls-autoupdate-popup" class="ls-box">
	<h3 class="header">Auto-update revalidation required</h3>
	<div class="inner">
		<h4>What's changed?</h4>
		<p>Due to changes in our auto-update solution you need to re-validate the plugin to receive automatic updates again. From now on a purchase code can be used to enable automatic updates on 2 websites only. The domain name of your site will be recorded on re-activation.</p>
		<h4>What do I have to do?</h4>
		<p>Since you've already entered your purchase code you just need to click on the "Update" button in the auto-update box. Please keep in mind that now you can only activate 2 websites with a key at the same time.</p>
		<h4>Why is it necessary?</h4>
		<p>Item purchase codes have many applications besides the auto-update feature. We feel it's important to minimize the impact of leaked/shared codes on the web in order to improve user experience and our products and support services.</p>
		<h4>Should I be worried?</h4>
		<p>The plugin will remain functioning without any restriction, but you need to re-validate your purchase to receive updates again. If you've got LayerSlider by a theme purchase you can receive the latest versions with theme updates, unless you purchase a license directly for LayerSlider on <a href="http://codecanyon.net/item/layerslider-responsive-wordpress-slider-plugin-/1362246" target="_blank">CodeCanyon</a>.</p>
		<p>For more details about licensing, please read our <a href="http://support.kreaturamedia.com/faq/4/layerslider-for-wordpress/#group-3" target="_blank">FAQ entries</a> or check the relevant <a href="http://codecanyon.net/licenses/standard" target="_blank">marketplace pages<a>.</p>
		<p class="signature">Please accept our sincerest apologies for any inconvenience may caused. <br> Kreatura Media Team</p>
		<a href="<?php echo wp_nonce_url('?page=layerslider&action=hide-revalidation-notice', 'hide-revalidation-notice') ?>" class="button button-primary">I understand</a>
	</div>
</div>
<?php endif ?>