<?php

$lsDefaults = array(

	'slider' => array(

		// ============= //
		// |   Layout  | //
		// ============= //

		// The width of a new slider.
		'width' => array(
			'value' => 600,
			'name' => __('Slider width', 'LayerSlider'),
			'keys' => 'width',
			'desc' => __('The width of the slider in pixels. Accepts percents, but is only recommended for full-width layout.', 'LayerSlider'),
			'attrs' => array(
				'type' => 'text'
			),
			'props' => array(
				'meta' => true
			)
		),

		// The height of a new slider.
		'height' => array(
			'value' => 300,
			'name' => __('Slider height', 'LayerSlider'),
			'keys' => 'height',
			'desc' => __('The height of the slider in pixels.', 'LayerSlider'),
			'attrs' => array(
				'type' => 'text'
			),
			'props' => array(
				'meta' => true
			)
		),

		// Whether use or not responsiveness.
		'responsiveness' => array(
			'value' => true,
			'name' => __('Responsive', 'LayerSlider'),
			'keys' => 'responsive',
			'desc' => __('Responsive mode provides optimal viewing experience across a wide range of devices (from desktop to mobile) by adapting and scaling your sliders for the viewing environment.', 'LayerSlider')
		),


		// The maximum width that the slider can get in responsive mode.
		'maxWidth' => array(
			'value' => '',
			'name' => __('Max-width', 'LayerSlider'),
			'keys' => 'maxwidth',
			'desc' => __('The maximum width your slider can take in pixels when responsive mode is enabled.', 'LayerSlider'),
			'attrs' => array(
				'type' => 'number',
				'min' => 0
			),
			'props' => array(
				'meta' => true
			)
		),

		// Force the slider to stretch to full-width,
		// even when the theme doesn't designed that way.
		'fullWidth' => array(
			'value' => false,
			'name' => __('Full-width', 'LayerSlider'),
			'keys' => 'forceresponsive',
			'desc' => __('Enable this option to force the slider to become full-width, even if your theme does not support such layout.', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		// Turn on responsiveness under a given width of the slider.
		// Depends on: enabled fullWidth option. Defaults to: 0
		'responsiveUnder' => array(
			'value' => 0,
			'name' => __('Responsive under', 'LayerSlider'),
			'keys' => array('responsiveunder', 'responsiveUnder'),
			'desc' => __('Turns on responsive mode in a full-width slider under the specified value in pixels. Can only be used with full-width mode.', 'LayerSlider'),
			'attrs' => array(
				'min' => 0
			)
		),

		// Creates an inner area for sublayers that will be centered
		// regardless the size of the slider.
		// Depends on: enabled fullWidth option. Defaults to: 0
		'layersContainer' => array(
			'value' => 0,
			'name' => __('Layers container', 'LayerSlider'),
			'keys' => array('sublayercontainer', 'layersContainer'),
			'desc' => __('Creates an invisible inner container with the given dimension in pixels to hold and center your layers.', 'LayerSlider'),
			'attrs' => array(
				'min' => 0
			)
		),


		// Hides the slider on mobile devices.
		// Defaults to: false
		'hideOnMobile' => array(
			'value' => false,
			'name' => __('Hide on mobile', 'LayerSlider'),
			'keys' => array('hideonmobile', 'hideOnMobile'),
			'desc' => __('Hides the slider on mobile devices.', 'LayerSlider')
		),


		// Hides the slider under the given value of browser width in pixels.
		// Defaults to: 0
		'hideUnder' => array(
			'value' => 0,
			'name' => __('Hide under', 'LayerSlider'),
			'keys' => array('hideunder', 'hideUnder'),
			'desc' => __('Hides the slider under the given value of browser width in pixels.', 'LayerSlider'),
			'attrs' => array(
				'min' => 0
			)
		),

		// Hides the slider over the given value of browser width in pixel.
		// Defaults to: 100000
		'hideOver' => array(
			'value' => 100000,
			'name' => __('Hide over', 'LayerSlider'),
			'keys' => array('hideover', 'hideOver'),
			'desc' => __('Hides the slider over the given value of browser width in pixel.', 'LayerSlider'),
			'attrs' => array(
				'min' => 0
			)
		),

		// ================ //
		// |   Slideshow  | //
		// ================ //

		// Automatically start slideshow.
		'autoStart' => array(
			'value' => true,
			'name' => __('Start slideshow', 'LayerSlider'),
			'keys' => array('autostart', 'autoStart'),
			'desc' => __('Slideshow will automatically start after pages have loaded.', 'LayerSlider')
		),

		// The slider will start only if it enters in the viewport.
		'startInViewport' => array(
			'value' => true,
			'name' => __('Start in viewport', 'LayerSlider'),
			'keys' => array('startinviewport', 'startInViewport'),
			'desc' => __('The slider will start only if it enters into the viewport.', 'LayerSlider')
		),

		// Temporarily pauses the slideshow while you are hovering over the slider.
		'pauseOnHover' => array(
			'value' => true,
			'name' => __('Pause on hover', 'LayerSlider'),
			'keys' => array('pauseonhover', 'pauseOnHover'),
			'desc' => __('Slideshow will temporally pause when someone moves the mouse cursor over the slider.', 'LayerSlider')
		),

		// The starting slide of a slider. Non-index value, starts with 1.
		'firstSlide' => array(
			'value' => 1,
			'name' => __('Start with slide', 'LayerSlider'),
			'keys' => array('firstlayer', 'firstSlide'),
			'desc' => __('The slider will start with the specified slide. You can use the value "random".', 'LayerSlider'),
			'attrs' => array(
				'type' => 'text'
			)
		),

		// Whether animate or show the ending position of the first slide.
		'animateFirstSlide' => array(
			'value' => true,
			'name' => __('Animate starting slide', 'LayerSlider'),
			'keys' => array('animatefirstlayer', 'animateFirstSlide'),
			'desc' => __('Disabling this option will result a static starting slide for the fisrt time on page load.', 'LayerSlider')
		),

		// The slideshow will change slides in random order.
		'shuffle' => array(
			'value' => false,
			'name' => __('Shuffle mode', 'LayerSlider'),
			'keys' => array('randomslideshow', 'randomSlideshow'),
			'desc' => __('Slideshow will proceed in random order. This feature does not work with looping.', 'LayerSlider')
		),

		// Whether slideshow should goind backwards or not
		// when you switch to a previous slide.
		'twoWaySlideshow' => array(
			'value' => false,
			'name' => __('Two way slideshow', 'LayerSlider'),
			'keys' => array('twowayslideshow', 'twoWaySlideshow'),
			'desc' => __('Slideshow can go backwards if someone switches to a previous slide.', 'LayerSlider')
		),

		// Number of loops taking by the slideshow.
		// Depends on: shuffle. Defaults to: 0 => infinite
		'loops' => array(
			'value' => 0,
			'name' => __('Loops', 'LayerSlider'),
			'keys' => 'loops',
			'desc' => __('Number of loops if slideshow is enabled. Zero means infinite loops.', 'LayerSlider'),
		),

		// The slideshow will always stop at the given number of
		// loops, even when the user restarts slideshow.
		// Depends on: loop. Defaults to: true
		'forceLoopNumber' => array(
			'value' => true,
			'name' => __('Force the number of loops', 'LayerSlider'),
			'keys' => array('forceloopnum', 'forceLoopNum'),
			'desc' => __('The slider will always stop at the given number of loops, even if someone restarts slideshow.', 'LayerSlider')
		),

		// Use global shortcuts to control the slider.
		'keybNavigation' => array(
			'value' => true,
			'name' => __('Keyboard navigation', 'LayerSlider'),
			'keys' => array('keybnav', 'keybNav'),
			'desc' => __('You can navigate through slides with the left and right arrow keys.', 'LayerSlider')
		),

		// Accepts touch gestures if enabled.
		'touchNavigation' => array(
			'value' => true,
			'name' => __('Touch navigation', 'LayerSlider'),
			'keys' => array('touchnav', 'touchNav'),
			'desc' => __('Gesture-based navigation when swiping on touch-enabled devices.', 'LayerSlider')
		),


		// ================= //
		// |   Appearance  | //
		// ================= //

		// The default skin.
		'skin' => array(
			'value' => 'v5',
			'name' => __('Skin', 'LayerSlider'),
			'keys' => 'skin',
			'desc' => __("The skin used for this slider. The 'noskin' skin is a border- and buttonless skin. Your custom skins will appear in the list when you create their folders.", "LayerSlider")
		),


		// Global background color on all slides.
		'globalBGColor' => array(
			'value' => '',
			'name' => __('Background color', 'LayerSlider'),
			'keys' => array('backgroundcolor', 'globalBGColor'),
			'desc' => __('Global background color of the slider. Slides with non-transparent background will cover this one. You can use all CSS methods such as HEX or RGB(A) values.', 'LayerSlider')
		),

		// Global background image on all slides.
		'globalBGImage' => array(
			'value' => '',
			'name' => __('Background image', 'LayerSlider'),
			'keys' => array('backgroundimage', 'globalBGImage'),
			'desc' => __('Global background image of the slider. Slides with non-transparent backgrounds will cover it. This image will not scale in responsive mode.', 'LayerSlider')
		),

		'sliderFadeInDuration' => array(
			'value' => 350,
			'name' => __('Initial fade duration', 'LayerSlider'),
			'keys' => array('sliderfadeinduration', 'sliderFadeInDuration'),
			'desc' => __('Change the duration of the initial fade animation when the page loads. Enter 0 to disable fading.', 'LayerSlider'),
			'attrs' => array(
				'min' => 0
			)
		),

		// Some CSS values you can append on each slide individually
		// to make some adjustments if needed.
		'sliderStyle' => array(
			'value' => 'margin-bottom: 0px;',
			'name' => __('Slider CSS', 'LayerSlider'),
			'keys' => array('sliderstyle', 'sliderStyle'),
			'desc' => __('You can enter custom CSS to change some style properties on the slider wrapper element. More complex CSS should be applied with the Custom Styles Editor.', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),


		// ================= //
		// |   Navigation  | //
		// ================= //

		// Show the next and previous buttons.
		'navPrevNextButtons' => array(
			'value' => true,
			'name' => __('Show Prev & Next buttons', 'LayerSlider'),
			'keys' => array('navprevnext', 'navPrevNext'),
			'desc' => __('Disabling this option will hide the Prev and Next buttons.', 'LayerSlider')
		),

		// Show the next and previous buttons
		// only when hovering over the slider.
		'hoverPrevNextButtons' => array(
			'value' => true,
			'name' => __('Show Prev & Next buttons on hover', 'LayerSlider'),
			'keys' => array('hoverprevnext', 'hoverPrevNext'),
			'desc' => __('Show the buttons only when someone moves the mouse cursor over the slider. This option depends on the previous setting.', 'LayerSlider')
		),

		// Show the start and stop buttons
		'navStartStopButtons' => array(
			'value' => true,
			'name' => __('Show Start & Stop buttons', 'LayerSlider'),
			'keys' => array('navstartstop', 'navStartStop'),
			'desc' => __('Disabling this option will hide the Start & Stop buttons.', 'LayerSlider')
		),

		// Show the slide buttons or thumbnails.
		'navSlideButtons' => array(
			'value' => true,
			'name' => __('Show slide navigation buttons', 'LayerSlider'),
			'keys' => array('navbuttons', 'navButtons'),
			'desc' => __('Disabling this option will hide slide navigation buttons or thumbnails.', 'LayerSlider')
		),

		// Show the slider buttons or thumbnails
		// ony when hovering over the slider.
		'hoverSlideButtons' => array(
			'value' => false,
			'name' => __('Slide navigation on hover', 'LayerSlider'),
			'keys' => array('hoverbottomnav', 'hoverBottomNav'),
			'desc' => __('Slide navigation buttons (including thumbnails) will be shown on mouse hover only.', 'LayerSlider')
		),

		// Show bar timer
		'barTimer' => array(
			'value' => false,
			'name' => __('Show bar timer', 'LayerSlider'),
			'keys' => array('bartimer', 'showBarTimer'),
			'desc' => __('Show the bar timer to indicate slideshow progression.', 'LayerSlider')
		),

		// Show circle timer. Requires CSS3 capable browser.
		// This setting will overrule the 'barTimer' option.
		'circleTimer' => array(
			'value' => true,
			'name' => __('Show circle timer', 'LayerSlider'),
			'keys' => array('circletimer', 'showCircleTimer'),
			'desc' => __('Use circle timer to indicate slideshow progression.', 'LayerSlider')
		),

		// ========================== //
		// |  Thumbnail navigation  | //
		// ========================== //

		// Use thumbnails for slide buttons
		// Depends on: navSlideButtons.
		// Possible values: 'disabled', 'hover', 'always'
		'thumbnailNavigation' => array(
			'value' => 'hover',
			'name' => __('Thumbnail navigation', 'LayerSlider'),
			'keys' => array('thumb_nav', 'thumbnailNavigation'),
			'desc' => __('Use thumbnail navigation instead of slide bullet buttons.', 'LayerSlider'),
			'options' => array(
				'disabled' => __('Disabled', 'LayerSlider'),
				'hover' => __('Hover', 'LayerSlider'),
				'always' => __('Always', 'LayerSlider')
			)
		),

		// The width of the thumbnail area in percents.
		'thumbnailAreaWidth' => array(
			'value' => '60%',
			'name' => __('Thumbnail container width', 'LayerSlider'),
			'keys' => array('thumb_container_width', 'tnContainerWidth'),
			'desc' => __('The width of the thumbnail area.', 'LayerSlider')
		),

		// Thumbnails' width in pixels.
		'thumbnailWidth' => array(
			'value' => 100,
			'name' => __('Thumbnail width', 'LayerSlider'),
			'keys' => array('thumb_width', 'tnWidth'),
			'desc' => __('The width of thumbnails in the navigation area.', 'LayerSlider'),
			'attrs' => array(
				'min' => 0
			)
		),

		// Thumbnails' height in pixels.
		'thumbnailHeight' => array(
			'value' => 60,
			'name' => __('Thumbnail height', 'LayerSlider'),
			'keys' => array('thumb_height', 'tnHeight'),
			'desc' => __('The height of thumbnails in the navigation area.', 'LayerSlider'),
			'attrs' => array(
				'min' => 0
			)
		),


		// The opacity of the active thumbnail in percents.
		'thumbnailActiveOpacity' => array(
			'value' => 35,
			'name' => __('Active thumbnail opacity', 'LayerSlider'),
			'keys' => array('thumb_active_opacity', 'tnActiveOpacity'),
			'desc' => __("Opacity in percentage of the active slide's thumbnail.", "LayerSlider"),
			'attrs' => array(
				'min' => 0,
				'max' => 100
			)
		),

		// The opacity of inactive thumbnails in percents.
		'thumbnailInactiveOpacity' => array(
			'value' => 100,
			'name' => __('Inactive thumbnail opacity', 'LayerSlider'),
			'keys' => array('thumb_inactive_opacity', 'tnInactiveOpacity'),
			'desc' => __('Opacity in percentage of inactive slide thumbnails.', 'LayerSlider'),
			'attrs' => array(
				'min' => 0,
				'max' => 100
			)
		),

		// ============ //
		// |  Videos  | //
		// ============ //

		// Automatically starts vidoes on the given slide.
		'autoPlayVideos' => array(
			'value' => true,
			'name' => __('Automatically play videos', 'LayerSlider'),
			'keys' => array('autoplayvideos', 'autoPlayVideos'),
			'desc' => __('Videos will be automatically started on the active slide.', 'LayerSlider')
		),

		// Automatically pauses the slideshow when a video is playing.
		// Auto means it only pauses the slideshow while the video is playing.
		// Possible values: 'auto', 'enabled', 'disabled'
		'autoPauseSlideshow' => array(
			'value' => 'auto',
			'name' => __('Pause slideshow', 'LayerSlider'),
			'keys' => array('autopauseslideshow', 'autoPauseSlideshow'),
			'desc' => __('The slideshow can temporally be paused while videos are playing. You can choose to permanently stop the pause until manual restarting.', 'LayerSlider'),
			'options' => array(
				'auto' => __('While playing', 'LayerSlider'),
				'enabled' => __('Permanently', 'LayerSlider'),
				'disabled' => __('No action', 'LayerSlider')
			)
		),

		// The preview image quality of a YouTube video.
		// Some videos doesn't have HD preview images and
		// you may have to lower the quality settings.
		// Possible values:
			// 'maxresdefault.jpg',
			// 'hqdefault.jpg',
			// 'mqdefault.jpg',
			// 'default.jpg'
		'youtubePreviewQuality' => array(
			'value' => 'maxresdefault.jpg',
			'name' => __('Youtube preview', 'LayerSlider'),
			'keys' => array('youtubepreview', 'youtubePreview'),
			'desc' => __('The preview image quaility for YouTube videos. Please note, some videos do not have HD previews, and you may need to choose a lower quaility.', 'LayerSlider'),
			'options' => array(
				'maxresdefault.jpg' => __('Maximum quality', 'LayerSlider'),
				'hqdefault.jpg' => __('High quality', 'LayerSlider'),
				'mqdefault.jpg' => __('Medium quality', 'LayerSlider'),
				'default.jpg' => __('Default quality', 'LayerSlider')
			)
		),

		// ========== //
		// |  Misc  | //
		// ========== //

		// Preloads images from the first slide before displaying the slider.
		'imagePreload' => array(
			'value' => true,
			'name' => __('Image preload', 'LayerSlider'),
			'keys' => array('imgpreload', 'imgPreload'),
			'desc' => __('Preloads images used in the next slides for seamless animations.', 'LayerSlider')
		),

		'lazyLoad' => array(
			'value' => true,
			'name' => __('Lazy load images', 'LayerSlider'),
			'keys' => array('lazyload', 'lazyLoad'),
			'desc' => __('Loads images only when needed to save bandwidth and server resources. Relies on the preload feature.', 'LayerSlider')
		),

		// Ignores the host/domain names in URLS by converting the to
		// relative format. Useful when you move your site.
		// Prevents linking content from 3rd party servers.
		'relativeURLs' => array(
			'value' => false,
			'name' => __('Use relative URLs', 'LayerSlider'),
			'keys' => 'relativeurls',
			'desc' => __('Use relative URLs for local images. This setting could be important when moving your WP installation.', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),


		// ============== //
		// |  YourLogo  | //
		// ============== //

		// Places a fixed image on the top of the slider.
		'yourLogoImage' => array(
			'value' => '',
			'name' => __('YourLogo', 'LayerSlider'),
			'keys' => array('yourlogo', 'yourLogo'),
			'desc' => __('A fixed image layer can be shown above the slider that remains still during slide progression. Can be used to display logos or watermarks.', 'LayerSlider')
		),

		// Custom CSS style settings for the YourLogo image.
		// Depends on: yourLogoImage
		'yourLogoStyle' => array(
			'value' => 'left: -10px; top: -10px;',
			'name' => __('YourLogo style', 'LayerSlider'),
			'keys' => array('yourlogostyle', 'yourLogoStyle'),
			'desc' => __('CSS properties to control the image placement and appearance.', 'LayerSlider')
		),

		// Linking the YourLogo image to a given URL.
		// Depends on: yourLogoImage
		'yourLogoLink' => array(
			'value' => '',
			'name' => __('YourLogo link', 'LayerSlider'),
			'keys' => array('yourlogolink', 'yourLogoLink'),
			'desc' => __('Enter an URL to link the YourLogo image.', 'LayerSlider')
		),

		// Link target for yourLogoLink.
		// Depends on: yourLogoLink
		'yourLogoTarget' => array(
			'value' => '_self',
			'name' => __('Link target', 'LayerSlider'),
			'keys' => array('yourlogotarget', 'yourLogoTarget'),
			'desc' => '',
			'options' => array(
				'_self' => 'Open on the same page',
				'_blank' => 'Open on new page',
				'_parent' => 'Open in parent frame',
				'_top' => 'Open in main frame'
			),
		),

		// Post options
		'postType' => array(
			'value' => '',
			'keys' => 'post_type',
			'props' => array(
				'meta' => true
			)
		),

		'postOrderBy' => array(
			'value' => 'date',
			'keys' => 'post_orderby',
			'options' => array(
				'date' => 'Date Created',
				'modified' => 'Last Modified',
				'ID' => 'Post ID',
				'title' => 'Post Title',
				'comment_count' => 'Number of Comments',
				'rand' => 'Random'
			),
			'props' => array(
				'meta' => true
			)
		),

		'postOrder' => array(
			'value' => 'DESC',
			'keys' => 'post_order',
			'options' => array(
				'ASC' => 'Ascending',
				'DESC' => 'Descending'
			),
			'props' => array(
				'meta' => true
			)
		),

		'postCategories' => array(
			'value' => '',
			'keys' => 'post_categories',
			'props' => array(
				'meta' => true
			)
		),

		'postTags' => array(
			'value' => '',
			'keys' => 'post_tags',
			'props' => array(
				'meta' => true
			)
		),

		'postTaxonomy' => array(
			'value' => '',
			'keys' => 'post_taxonomy',
			'props' => array(
				'meta' => true
			)
		),

		'postTaxTerms' => array(
			'value' => '',
			'keys' => 'post_tax_terms',
			'props' => array(
				'meta' => true
			)
		),


		'cbInit' => array(
			'value' => "function(element) {\r\n\r\n}",
			'keys' => array('cbinit','cbInit')
		),

		'cbStart' => array(
			'value' => "function(data) {\r\n\r\n}",
			'keys' => array('cbstart','cbStart')
		),

		'cbStop' => array(
			'value' => "function(data) {\r\n\r\n}",
			'keys' => array('cbstop','cbStop')
		),

		'cbPause' => array(
			'value' => "function(data) {\r\n\r\n}",
			'keys' => array('cbpause','cbPause')
		),

		'cbAnimStart' => array(
			'value' => "function(data) {\r\n\r\n}",
			'keys' => array('cbanimstart','cbAnimStart')
		),

		'cbAnimStop' => array(
			'value' => "function(data) {\r\n\r\n}",
			'keys' => array('cbanimstop','cbAnimStop')
		),

		'cbPrev' => array(
			'value' => "function(data) {\r\n\r\n}",
			'keys' => array('cbprev','cbPrev')
		),

		'cbNext' => array(
			'value' => "function(data) {\r\n\r\n}",
			'keys' => array('cbnext','cbNext')
		),
	),

	'slides' => array(

		// The background image of slides
		// Defaults to: void
		'image' => array (
			'value' => '',
			'name' => __('Set a slide image', 'LayerSlider'),
			'keys' => 'background',
			'tooltip' => __('The slide image/background. Click on the image to open the WordPress Media Library to choose or upload an image.', 'LayerSlider'),
			'props' => array( 'meta' => true )
		),

		'imageId' => array (
			'value' => '',
			'keys' => 'backgroundId',
			'props' => array( 'meta' => true )
		),

		'thumbnail' => array (
			'value' => '',
			'name' => __('Set a slide thumbnail', 'LayerSlider'),
			'keys' => 'thumbnail',
			'tooltip' => __('The thumbnail image of this slide. Click on the image to open the WordPress Media Library to choose or upload an image. If you leave this field empty, the slide image will be used.', 'LayerSlider'),
			'props' => array( 'meta' => true )
		),

		'thumbnailId' => array (
			'value' => '',
			'keys' => 'thumbnailId',
			'props' => array( 'meta' => true )
		),

		// Default slide delay in millisecs.
		// Defaults to: 4000 (ms) => 4secs
		'delay' => array(
			'value' => 4000,
			'name' => __('Slide delay', 'LayerSlider'),
			'keys' => 'slidedelay',
			'tooltip' => __("Here you can set the time interval between slide changes, this slide will stay visible for the time specified here. This value is in millisecs, so the value 1000 means 1 second. Please don't use 0 or very low values.", "LayerSlider"),
			'attrs' => array(
				'min' => 0,
				'step' => 500
			)
		),

		'2dTransitions' => array(
			'value' => '',
			'keys' => array('2d_transitions', 'transition2d')
		),

		'3dTransitions' => array(
			'value' => '',
			'keys' => array('3d_transitions', 'transition3d')
		),

		'custom2dTransitions' => array(
			'value' => '',
			'keys' => array('custom_2d_transitions', 'customtransition2d')
		),

		'custom3dTransitions' => array(
			'value' => '',
			'keys' => array('custom_3d_transitions', 'customtransition3d')
		),

		'timeshift' => array (
			'value' => 0,
			'name' => __('Time Shift', 'LayerSlider'),
			'keys' => 'timeshift',
			'tooltip' => __('You can control here the timing of the layer animations when the slider changes to this slide with a 3D/2D transition. Zero means that the layers of this slide will animate in when the slide transition ends. You can time-shift the starting time of the layer animations with positive or negative values.', 'LayerSlider'),
			'attrs' => array(
				'step' => 50
			)
		),

		'linkUrl' => array (
			'value' => '',
			'name' => __('Enter URL', 'LayerSlider'),
			'keys' => array('layer_link', 'linkUrl'),
			'tooltip' => __('If you want to link the whole slide, enter the URL of your link here.', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)

		),

		'linkTarget' => array (
			'value' => '_self',
			'name' => __('Link Target', 'LayerSlider'),
			'keys' => array('layer_link_target', 'linkTarget'),
			'options' => array(
				'_self' => 'Open on the same page',
				'_blank' => 'Open on new page',
				'_parent' => 'Open in parent frame',
				'_top' => 'Open in main frame'
			),
			'props' => array(
				'meta' => true
			)

		),

		'ID' => array (
			'value' => '',
			'name' => __('#ID', 'LayerSlider'),
			'keys' => 'id',
			'tooltip' => __('You can apply an ID attribute on the HTML element of this slide to work with it in your custom CSS or Javascript code.', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		'deeplink' => array (
			'value' => '',
			'name' => __('Deeplink', 'LayerSlider'),
			'keys' => 'deeplink',
			'tooltip' => __('You can specify a slide alias name which you can use in your URLs with a hash mark, so LayerSlider will start with the correspondig slide.', 'LayerSlider')
		),

		'postOffset' => array(
			'value' => '',
			'keys' => 'post_offset',
			'props' => array(
				'meta' => true
			)
		),

		'skipSlide' => array(
			'value' => false,
			'name' => __('Hidden', 'LayerSlider'),
			'keys' => 'skip',
			'tooltip' => __("If you don't want to use this slide in your front-page, but you want to keep it, you can hide it with this switch.", 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		//  DEPRECATED OLD TRANSITIONS
		'slidedirection' => array( 'value' => 'right', 'keys' => 'slidedirection'),
		'durationin' => array( 'value' => 1500, 'keys' => 'durationin'),
		'durationout' => array( 'value' => 1500, 'keys' => 'durationout'),
		'easingin' => array( 'value' => 'easeInOutQuint', 'keys' => 'easingin'),
		'easingout' => array( 'value' => 'easeInOutQuint', 'keys' => 'easingout'),
		'delayin' => array( 'value' => 0, 'keys' => 'delayin'),
		'delayout' => array( 'value' => 0, 'keys' => 'slidedidelayoutrection')
	),

	'layers' => array(

		// ======================= //
		// |  Content  | //
		// ======================= //

		'type' => array(
			'value' => '',
			'keys' => 'type',
			'props' => array(
				'meta' => true
			)
		),

		'media' => array(
			'value' => '',
			'keys' => 'media',
			'props' => array(
				'meta' => true
			)
		),

		'image' => array(
			'value' => '',
			'keys' => 'image',
			'props' => array(
				'meta' => true
			)
		),

		'imageId' => array(
			'value' => '',
			'keys' => 'imageId',
			'props' => array( 'meta' => true )
		),

		'html' => array(
			'value' => '',
			'keys' => 'html',
			'props' => array(
				'meta' => true
			)
		),

		'postTextLength' => array(
			'value' => '',
			'keys' => 'post_text_length',
			'props' => array(
				'meta' => true
			)
		),


		// ======================= //
		// |  Animation options  | //
		// ======================= //
		'transition' => array( 'value' => '', 'keys' => 'transition', 'props' => array( 'meta' => true )),

		'transitionInOffsetX' => array(
			'value' => '80',
			'name' => __('OffsetX', 'LayerSlider'),
			'keys' => 'offsetxin',
			'tooltip' => __("The horizontal offset to align the starting position of layers. Positive and negative numbers are allowed or enter left / right to position the layer out of the frame.", "LayerSlider")
		),

		'transitionInOffsetY' => array(
			'value' => '0',
			'name' => __('OffsetY', 'LayerSlider'),
			'keys' => 'offsetyin',
			'tooltip' => __("The vertical offset to align the starting position of layers. Positive and negative numbers are allowed or enter top / bottom to position the layer out of the frame.", "LayerSlider")
		),

		// Duration of the transition in millisecs when a layer animates in.
		// Original: durationin
		// Defaults to: 1000 (ms) => 1sec
		'transitionInDuration' => array(
			'value' => 1000,
			'name' => __('Duration', 'LayerSlider'),
			'keys' => 'durationin',
			'tooltip' => __('The transition duration in milliseconds when the layer enters into the slide. A second equals to 1000 milliseconds.', 'LayerSlider'),
			'attrs' => array( 'min' => 0, 'step' => 50 )
		),

		// Delay before the transition in millisecs when a layer animates in.
		// Original: delayin
		// Defaults to: 0 (ms)
		'transitionInDelay' => array(
			'value' => 0,
			'name' => __('Delay', 'LayerSlider'),
			'keys' => 'delayin',
			'tooltip' => __('Delays the transition with the given amount of milliseconds before the layer enters into the slide. A second equals to 1000 milliseconds.', 'LayerSlider'),
			'attrs' => array( 'min' => 0, 'step' => 50 )
		),

		// Easing of the transition when a layer animates in.
		// Original: easingin
		// Defaults to: 'easeInOutQuint'
		'transitionInEasing' => array(
			'value' => 'easeInOutQuint',
			'name' => __('Easing', 'LayerSlider'),
			'keys' => 'easingin',
			'tooltip' => __("The timing function of the animation. With this function you can manipulate the movement of the animated object. Please click on the link next to this select field to open easings.net for more information and real-time examples.", "LayerSlider")
		),

		'transitionInFade' => array(
			'value' => true,
			'name' => __('Fade', 'LayerSlider'),
			'keys' => 'fadein',
			'tooltip' => __('Fade the layer during the transition.', 'LayerSlider'),
		),

		// Initial rotation degrees when a layer animates in.
		// Original: rotatein
		// Defaults to: 0 (deg)
		'transitionInRotate' => array(
			'value' => 0,
			'name' => __('Rotate', 'LayerSlider'),
			'keys' => 'rotatein',
			'tooltip' => __('Rotates the layer clockwise from the given angle to zero degree. Negative values are allowed for counterclockwise rotation.', 'LayerSlider')
		),

		'transitionInRotateX' => array(
			'value' => 0,
			'name' => __('RotateX', 'LayerSlider'),
			'keys' => 'rotatexin',
			'tooltip' => __('Rotates the layer along the X (horizontal) axis from the given angle to zero degree. Negative values are allowed for reverse direction.', 'LayerSlider')
		),

		'transitionInRotateY' => array(
			'value' => 0,
			'name' => __('RotateY', 'LayerSlider'),
			'keys' => 'rotateyin',
			'tooltip' => __('Rotates the layer along the Y (vertical) axis from the given angle to zero degree. Negative values are allowed for reverse direction.', 'LayerSlider')
		),

		'transitionInSkewX' => array(
			'value' => 0,
			'name' => __('SkewX', 'LayerSlider'),
			'keys' => 'skewxin',
			'tooltip' => __('Skews the layer along the X (horizontal) axis from the given angle to 0 degree. Negative values are allowed for reverse direction.', 'LayerSlider')
		),

		'transitionInSkewY' => array(
			'value' => 0,
			'name' => __('SkewY', 'LayerSlider'),
			'keys' => 'skewyin',
			'tooltip' => __('Skews the layer along the Y (vertical) axis from the given angle to 0 degree. Negative values are allowed for reverse direction.', 'LayerSlider'),
		),

		'transitionInScaleX' => array(
			'value' => 1,
			'name' => __('ScaleX', 'LayerSlider'),
			'keys' => 'scalexin',
			'tooltip' => __("Scales the layer's width from the given value to its original size.", "LayerSlider"),
			'attrs' => array( 'step' => 0.1 )
		),

		'transitionInScaleY' => array(
			'value' => 1,
			'name' => __('ScaleY', 'LayerSlider'),
			'keys' => 'scaleyin',
			'tooltip' => __("Scales the layer's height from the given value to its original size.", "LayerSlider"),
			'attrs' => array( 'step' => 0.1 )
		),

		'transitionInTransformOrigin' => array(
			'value' => '50% 50% 0',
			'name' => __('TransformOrigin', 'LayerSlider'),
			'keys' => 'transformoriginin',
			'tooltip' => __('This option allows you to modify the origin for transformations of the layer according to its position. The three values represent the X, Y and Z axis in 3D space. OriginX can be left, center, right, a number or a percentage value. OriginY can be top, center, bottom, a number or a percentage value. OriginZ can be a number and corresponds the depth in 3D space.', 'LayerSlider'),
		),

		// ======

		'transitionOutOffsetX' => array(
			'value' => '-80',
			'name' => __('OffsetX', 'LayerSlider'),
			'keys' => 'offsetxout',
			'tooltip' => __("The horizontal offset to align the ending position of layers. Positive and negative numbers are allowed or write left / right to position the layer out of the frame.", "LayerSlider")
		),

		'transitionOutOffsetY' => array(
			'value' => '0',
			'name' => __('OffsetY', 'LayerSlider'),
			'keys' => 'offsetyout',
			'tooltip' => __("The vertical offset to align the starting position of layers. Positive and negative numbers are allowed or write top / bottom to position the layer out of the frame.", "LayerSlider")
		),

		// Duration of the transition in millisecs when a layer animates out.
		// Original: durationout
		// Defaults to: 1000 (ms) => 1sec
		'transitionOutDuration' => array(
			'value' => 400,
			'name' => __('Duration', 'LayerSlider'),
			'keys' => 'durationout',
			'tooltip' => __('The transition duration in milliseconds when the layer leaves the slide. A second equals to 1000 milliseconds.', 'LayerSlider'),
			'attrs' => array( 'min' => 0, 'step' => 50 )
		),

		// You can create timed layers by specifing their time they can take on a slide in millisecs.
		// Original: showuntil
		// Defaults to: 0 (ms)
		'showUntil' => array(
			'value' => 0,
			'name' => __('Show until', 'LayerSlider'),
			'keys' => 'showuntil',
			'tooltip' => __('The layer will be visible for the time you specify here, then it will slide out. You can use this setting for layers to leave the slide before the slide itself animates out, or for example before other layers will slide in. This value in millisecs, so the value 1000 means 1 second.', 'LayerSlider'),
			'attrs' => array( 'min' => 0, 'step' => 50 )
		),

		// Easing of the transition when a layer animates out.
		// Original: easingout
		// Defaults to: 'easeInOutQuint'
		'transitionOutEasing' => array(
			'value' => 'easeInOutQuint',
			'name' => __('Easing', 'LayerSlider'),
			'keys' => 'easingout',
			'tooltip' => __("The timing function of the animation. With this function you can manipulate the movement of the animated object. Please click on the link next to this select field to open easings.net for more information and real-time examples.", "LayerSlider")
		),

		'transitionOutFade' => array(
			'value' => true,
			'name' => __('Fade', 'LayerSlider'),
			'keys' => 'fadeout',
			'tooltip' => __('Fade the layer during the transition.', 'LayerSlider'),
		),


		// Initial rotation degrees when a layer animates out.
		// Original: rotateout
		// Defaults to: 0 (deg)
		'transitionOutRotate' => array(
			'value' => 0,
			'name' => __('Rotate', 'LayerSlider'),
			'keys' => 'rotateout',
			'tooltip' => __('Rotates the layer clockwise by the given angle from its original position. Negative values are allowed for counterclockwise rotation.', 'LayerSlider')
		),

		'transitionOutRotateX' => array(
			'value' => 0,
			'name' => __('RotateX', 'LayerSlider'),
			'keys' => 'rotatexout',
			'tooltip' => __('Rotates the layer along the X (horizontal) axis by the given angle from its original state. Negative values are allowed for reverse direction.', 'LayerSlider')
		),

		'transitionOutRotateY' => array(
			'value' => 0,
			'name' => __('RotateY', 'LayerSlider'),
			'keys' => 'rotateyout',
			'tooltip' => __('Rotates the layer along the Y (vertical) axis by the given angle from its orignal state. Negative values are allowed for reverse direction.', 'LayerSlider')
		),

		'transitionOutSkewX' => array(
			'value' => 0,
			'name' => __('SkewX', 'LayerSlider'),
			'keys' => 'skewxout',
			'tooltip' => __('Skews the layer along the X (horizontal) axis by the given angle from its orignal state. Negative values are allowed for reverse direction.', 'LayerSlider')
		),

		'transitionOutSkewY' => array(
			'value' => 0,
			'name' => __('SkewY', 'LayerSlider'),
			'keys' => 'skewyout',
			'tooltip' => __('Skews the layer along the Y (vertical) axis by the given angle from its original state. Negative values are allowed for reverse direction.', 'LayerSlider'),
		),

		'transitionOutScaleX' => array(
			'value' => 1,
			'name' => __('ScaleX', 'LayerSlider'),
			'keys' => 'scalexout',
			'tooltip' => __("Scales the layer's width by the given value from its original size.", "LayerSlider"),
			'attrs' => array( 'step' => 0.1 )
		),

		'transitionOutScaleY' => array(
			'value' => 1,
			'name' => __('ScaleY', 'LayerSlider'),
			'keys' => 'scaleyout',
			'tooltip' => __("Scales the layer's height by the given value from its original size.", "LayerSlider"),
			'attrs' => array( 'step' => 0.1 )
		),

		'transitionOutTransformOrigin' => array(
			'value' => '50% 50% 0',
			'name' => __('TransformOrigin', 'LayerSlider'),
			'keys' => 'transformoriginout',
			'tooltip' => __('This option allows you to modify the origin for transformations of the layer according to its position. The three values represent the X, Y and Z axis in 3D space. OriginX can be left, center, right, a number or a percentage value. OriginY can be top, center, bottom, a number or a percentage value. OriginZ can be a number and corresponds the depth in 3D space.', 'LayerSlider'),
		),

		'transitionParallaxLevel' => array(
			'value' => 0,
			'name' => __('Parallax Level', 'LayerSlider'),
			'keys' => 'parallaxlevel',
			'tooltip' => __('Applies a parallax effect on layers when you move your mouse over the slider. Higher values make the layer more sensitive to mouse move. Negative values are allowed.', 'LayerSlider')
		),


		// == Compatibility ==
		'transitionInType' => array(
			'value' => 'auto',
			'name' => __('Type', 'LayerSlider'),
			'keys' => 'slidedirection'
		),
		'transitionOutType' => array(
			'value' => 'auto',
			'name' => __('Type', 'LayerSlider'),
			'keys' => 'slideoutdirection'
		),

		'transitionOutDelay' => array(
			'value' => 0,
			'name' => __('Delay', 'LayerSlider'),
			'keys' => 'delayout',
			'tooltip' => __('Delay before the animation start when the layer slides out. This value is in millisecs, so the value 1000 means 1 second.', 'LayerSlider'),
			'attrs' => array(
				'min' => 0,
				'step' => 50
			)
		),

		'transitionInScale' => array(
			'value' => '1.0',
			'name' => __('Scale', 'LayerSlider'),
			'keys' => 'scalein',
			'tooltip' => __('You can set the initial scale of this layer here which will be animated to the default (1.0) value.', 'LayerSlider'),
			'attrs' => array(
				'step' => 0.1
			)
		),

		'transitionOutScale' => array(
			'value' => '1.0',
			'name' => __('Scale', 'LayerSlider'),
			'keys' => 'scaleout',
			'tooltip' => __('You can set the ending scale value here, this sublayer will be animated from the default (1.0) value to yours.', 'LayerSlider'),
			'attrs' => array(
				'step' => 0.1
			)
		),

		'skipLayer' => array(
			'value' => false,
			'name' => __('Hidden', 'LayerSlider'),
			'keys' => 'skip',
			'tooltip' => __("If you don't want to use this layer, but you want to keep it, you can hide it with this switch.", "LayerSlider"),
			'props' => array(
				'meta' => true
			)
		),

		// ======

		// Distance level determines the starting and ending position of a layer out of the frame.
		// The value of -1 means automatic positioning outside of the frame. In some cases you might
		// have to use a manual value greater than 3.
		// Original: level
		// Defaults to: -1
		'distanceLevel' => array(
			'value' => -1,
			'name' => __('Distance', 'LayerSlider'),
			'keys' => 'level',
			'tooltip' => __('The default value is -1 which means that the layer will be positioned exactly outside of the slide container. You can use the default setting in most of the cases. If you need to set the start or end position of the layer from further of the edges of the slide container, you can use 2, 3 or higher values.', 'LayerSlider'),
			'attrs' => array(
				'min' => -1
			),
			'props' => array(
				'meta' => true
			)
		),


		'linkURL' => array(
			'value' => '',
			'name' => __('Enter URL', 'LayerSlider'),
			'keys' => 'url',
			'tooltip' => __('If you want to link your layer, type here the URL. You can use a hash mark followed by a number to link this layer to another slide. Example: #3 - this will switch to the third slide.', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		'linkTarget' => array(
			'value' => '_self',
			'name' => __('URL target', 'LayerSlider'),
			'keys' => 'target',
			'options' => array(
				'_self' => 'Open on the same page',
				'_blank' => 'Open on new page',
				'_parent' => 'Open in parent frame',
				'_top' => 'Open in main frame'
			),
			'props' => array(
				'meta' => true
			)
		),

		// Styles

		'width' => array(
			'value' => '',
			'name' => __('Width', 'LayerSlider'),
			'keys' => 'width',
			'tooltip' => __("You can set the width of your layer. You can use pixels, percentage, or the default value 'auto'. Examples: 100px, 50% or auto.", "LayerSlider"),
			'props' => array(
				'meta' => true
			)
		),

		'height' => array(
			'value' => '',
			'name' => __('Height', 'LayerSlider'),
			'keys' => 'height',
			'tooltip' => __("You can set the height of your layer. You can use pixels, percentage, or the default value 'auto'. Examples: 100px, 50% or auto", "LayerSlider"),
			'props' => array(
				'meta' => true
			)
		),

		'top' => array(
			'value' => '0px',
			'name' => __('Top', 'LayerSlider'),
			'keys' => 'top',
			'tooltip' => __("The layer position from the top of the slide. You can use pixels and percentage. Examples: 100px or 50%. You can move your layers in the preview above with a drag n' drop, or set the exact values here.", "LayerSlider"),
			'props' => array(
				'meta' => true
			)
		),

		'left' => array(
			'value' => '0px',
			'name' => __('Left', 'LayerSlider'),
			'keys' => 'left',
			'tooltip' => __("The layer position from the left side of the slide. You can use pixels and percentage. Examples: 100px or 50%. You can move your layers in the preview above with a drag n' drop, or set the exact values here.", "LayerSlider"),
			'props' => array(
				'meta' => true
			)
		),

		'paddingTop' => array(
			'value' => '',
			'name' => __('Top', 'LayerSlider'),
			'keys' => 'padding-top',
			'tooltip' => __('Padding on the top of the layer. Example: 10px', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		'paddingRight' => array(
			'value' => '',
			'name' => __('Right', 'LayerSlider'),
			'keys' => 'padding-right',
			'tooltip' => __('Padding on the right side of the layer. Example: 10px', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		'paddingBottom' => array(
			'value' => '',
			'name' => __('Bottom', 'LayerSlider'),
			'keys' => 'padding-bottom',
			'tooltip' => __('Padding on the bottom of the layer. Example: 10px', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		'paddingLeft' => array(
			'value' => '',
			'name' => __('Left', 'LayerSlider'),
			'keys' => 'padding-left',
			'tooltip' => __('Padding on the left side of the layer. Example: 10px', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		'borderTop' => array(
			'value' => '',
			'name' => __('Top', 'LayerSlider'),
			'keys' => 'border-top',
			'tooltip' => __('Border on the top of the layer. Example: 5px solid #000', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		'borderRight' => array(
			'value' => '',
			'name' => __('Right', 'LayerSlider'),
			'keys' => 'border-right',
			'tooltip' => __('Border on the right side of the layer. Example: 5px solid #000', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		'borderBottom' => array(
			'value' => '',
			'name' => __('Bottom', 'LayerSlider'),
			'keys' => 'border-bottom',
			'tooltip' => __('Border on the bottom of the layer. Example: 5px solid #000', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		'borderLeft' => array(
			'value' => '',
			'name' => __('Left', 'LayerSlider'),
			'keys' => 'border-left',
			'tooltip' => __('Border on the left side of the layer. Example: 5px solid #000', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		'fontFamily' => array(
			'value' => '',
			'name' => __('Family', 'LayerSlider'),
			'keys' => 'font-family',
			'tooltip' => __('List of your chosen fonts separated with a comma. Please use apostrophes if your font names contains white spaces. Example: Helvetica, Arial, sans-serif', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		'fontSize' => array(
			'value' => '',
			'name' => __('Size', 'LayerSlider'),
			'keys' => 'font-size',
			'tooltip' => __('The font size in pixels. Example: 16px.', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		'lineHeight' => array(
			'value' => '',
			'name' => __('Line-height', 'LayerSlider'),
			'keys' => 'line-height',
			'tooltip' => __("The line height of your text. The default setting is 'normal'. Example: 22px", "LayerSlider"),
			'props' => array(
				'meta' => true
			)
		),

		'color' => array(
			'value' => '',
			'name' => __('Color', 'LayerSlider'),
			'keys' => 'color',
			'tooltip' => __('The color of your text. You can use color names, hexadecimal, RGB or RGBA values. Example: #333', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		'background' => array(
			'value' => '',
			'name' => __('Background', 'LayerSlider'),
			'keys' => 'background',
			'tooltip' => __("The background color of your layer. You can use color names, hexadecimal, RGB or RGBA values as well as the 'transparent' keyword. Example: #FFF", "LayerSlider"),
			'props' => array(
				'meta' => true
			)
		),

		'borderRadius' => array(
			'value' => '',
			'name' => __('Rounded corners', 'LayerSlider'),
			'keys' => 'border-radius',
			'tooltip' => __('If you want rounded corners, you can set its radius here. Example: 5px', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		'wordWrap' => array(
			'value' => false,
			'name' => 'Word-wrap',
			'keys' => 'wordwrap',
			'tooltip' => 'If you use custom sized layers, you have to enable this setting to wrap your text.',
			'props' => array(
				'meta' => true
			)
		),

		'style' => array(
			'value' => '',
			'name' => __('Custom styles', 'LayerSlider'),
			'keys' => 'style',
			'tooltip' => __('If you want to set style settings other than above, you can use here any CSS codes. Please make sure to write valid markup.', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		'styles' => array(
			'value' => '',
			'keys' => 'styles',
			'props' => array(
				'meta' => true,
				'raw' => true
			)
		),

		// Attributes

		'ID' => array(
			'value' => '',
			'name' => __('ID', 'LayerSlider'),
			'keys' => 'id',
			'tooltip' => __('You can apply an ID attribute on the HTML element of this layer to work with it in your custom CSS or Javascript code.', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		'class' => array(
			'value' => '',
			'name' => __('Classes', 'LayerSlider'),
			'keys' => 'class',
			'tooltip' => __('You can apply classes on the HTML element of this layer to work with it in your custom CSS or Javascript code.', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		'title' => array(
			'value' => '',
			'name' => __('Title', 'LayerSlider'),
			'keys' => 'title',
			'tooltip' => __('You can add a title to this layer which will display as a tooltip if someone holds his mouse cursor over the layer.', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		'alt' => array(
			'value' => '',
			'name' => __('Alt', 'LayerSlider'),
			'keys' => 'alt',
			'tooltip' => __('You can add an alternative text to your layer which is indexed by search engine robots and it helps people with certain disabilities.', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		),

		'rel' => array(
			'value' => '',
			'name' => __('Rel', 'LayerSlider'),
			'keys' => 'rel',
			'tooltip' => __('Some plugin may use the rel attribute of a linked content, here you can specify it to make interaction with these plugins.', 'LayerSlider'),
			'props' => array(
				'meta' => true
			)
		)

	),

	'easings' => array(
		'linear',
		'swing',
		'easeInQuad',
		'easeOutQuad',
		'easeInOutQuad',
		'easeInCubic',
		'easeOutCubic',
		'easeInOutCubic',
		'easeInQuart',
		'easeOutQuart',
		'easeInOutQuart',
		'easeInQuint',
		'easeOutQuint',
		'easeInOutQuint',
		'easeInSine',
		'easeOutSine',
		'easeInOutSine',
		'easeInExpo',
		'easeOutExpo',
		'easeInOutExpo',
		'easeInCirc',
		'easeOutCirc',
		'easeInOutCirc',
		'easeInElastic',
		'easeOutElastic',
		'easeInOutElastic',
		'easeInBack',
		'easeOutBack',
		'easeInOutBack',
		'easeInBounce',
		'easeOutBounce',
		'easeInOutBounce'
	)
);
