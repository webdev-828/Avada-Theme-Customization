<?php

// loads the shortcodes class, wordpress is loaded with it
require_once( 'shortcodes.class.php' );

// get popup type
$popup = trim( $_GET['popup'] );
$shortcode = new fusion_shortcodes( $popup );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<body>
<div id="fusion-popup">

	<div id="fusion-shortcode-wrap">

		<div id="fusion-sc-form-wrap">

			<?php
			$select_shortcode = array(
					'select' => 'Choose a Shortcode',
					'alert' => 'Alert',
					'blog' => 'Blog',
					'button' => 'Button',
					'checklist' => 'Checklist',
					'columns' => 'Columns',
					'contentboxes' => 'Content Boxes',
					'fusion_countdown' => 'Countdown',	
					'countersbox' => 'Counters Box',					
					'counterscircle' => 'Counters Circle',
					'dropcap' => 'Dropcap',
					'events' => 'Events',
					'flipboxes' => 'Flip Boxes',
					'fontawesome' => 'Font Awesome',
					'fusionslider' => 'Fusion Slider',
					'fullwidth' => 'Full Width Container',
					'googlemap' => 'Google Map',
					'highlight' => 'Highlight',
					'imagecarousel' => 'Image Carousel',
					'imageframe' => 'Image Frame',
					'lightbox' => 'Lightbox',
					'fusion_login'	=> 'Login',
					'fusion_register'	=> 'Register',
					'fusion_lost_password' => 'Lost Password',
					'menuanchor' => 'Menu Anchor',
					'modal' => 'Modal',
					'modaltextlink' => 'Modal Text Link',
					'onepagetextlink' => 'One Page Text Link',
					'person' => 'Person',
					'popover' => 'Popover',
					'postslider' => 'Post Slider',
					'pricingtable' => 'Pricing Table',
					'progressbar' => 'Progress Bar',
					'recentposts' => 'Recent Posts',
					'recentworks' => 'Recent Works',
					'sectionseparator' => 'Section Separator',
					'separator' => 'Separator',
					'sharingbox' => 'Sharing Box',
					'slider' => 'Slider',
					'sociallinks' => 'Social Links',
					'soundcloud' => 'SoundCloud',
					'table' => 'Table',
					'tabs' => 'Tabs',
					'taglinebox' => 'Tagline Box',
					'testimonials' => 'Testimonials',
					'title' => 'Title',
					'toggles' => 'Toggles',
					'tooltip' => 'Tooltip',
					'vimeo' => 'Vimeo',
					'fusion_widget_area' => 'Widget Area',
					'woofeatured' => 'Woocommerce Featured Products Slider',
					'wooproducts' => 'Woocommerce Products Slider',
					'youtube' => 'Youtube'
			);
			?>
			<table id="fusion-sc-form-table" class="fusion-shortcode-selector">
				<tbody>
					<tr class="form-row">
						<td class="label">Choose Shortcode</td>
						<td class="field">
							<div class="fusion-form-select-field">
							<div class="fusion-shortcodes-arrow">&#xf107;</div>
								<select name="fusion_select_shortcode" id="fusion_select_shortcode" class="fusion-form-select fusion-input">
									<?php foreach($select_shortcode as $shortcode_key => $shortcode_value): ?>
									<?php if($shortcode_key == $popup): $selected = 'selected="selected"'; else: $selected = ''; endif; ?>
									<option value="<?php echo $shortcode_key; ?>" <?php echo $selected; ?>><?php echo $shortcode_value; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			<form method="post" id="fusion-sc-form">

				<table id="fusion-sc-form-table">

					<?php echo $shortcode->output; ?>

					<tbody class="fusion-sc-form-button">
						<tr class="form-row">
							<td class="field"><a href="#" class="fusion-insert">Insert Shortcode</a></td>
						</tr>
					</tbody>

				</table>
				<!-- /#fusion-sc-form-table -->

			</form>
			<!-- /#fusion-sc-form -->

		</div>
		<!-- /#fusion-sc-form-wrap -->

		<div class="clear"></div>

	</div>
	<!-- /#fusion-shortcode-wrap -->

</div>
<!-- /#fusion-popup -->

</body>
</html>