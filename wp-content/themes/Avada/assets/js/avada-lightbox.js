/*******************************************
 Avada Lightbox
 *
 * @package		Avada
 * @author		ThemeFusion
 * @link		http://theme-fusion.com
 * @copyright	ThemeFusion
********************************************/

"use strict";

var $avada_lightbox = {};
var $il_instances	= [];
/**
* [initialize_lightbox manipulate pretty photo content]
*/
$avada_lightbox.initialize_lightbox = function() {

	if( Number( js_local_vars.status_lightbox ) == 1 ) {

		// For old prettyPhoto instances initialize caption and titles
		$avada_lightbox.set_title_and_caption();

		//activate lightbox now
		$avada_lightbox.activate_lightbox();
	};
};
/**
* [activate_lightbox activate lightbox]
*/
$avada_lightbox.activate_lightbox = function( $wrapper ) {

	// Default value for optional $gallery variable
	if ( typeof $wrapper === 'undefined' ) {
    	$wrapper = jQuery( 'body' );
  	}

	//create galleries group
	var $groups_arr = [];

	$wrapper.find( '[data-rel^="prettyPhoto["], [rel^="prettyPhoto["], [data-rel^="iLightbox["], [rel^="iLightbox["]' ).each( function () {
		var $image_formats = ['bmp', 'gif', 'jpeg', 'jpg', 'png', 'tiff', 'tif', 'jfif', 'jpe', 'svg', 'mp4', 'ogg', 'webm' ],
			$image_formats_mask = 0,
			$href = jQuery( this ).attr( 'href' );

		// Fix for #1738
		if( typeof $href == 'undefined' ) {
			$href = '';
		}

		// Loop through the image extensions array to see if we have an image link
		for ( var $i = 0; $i < $image_formats.length; $i++ ) {
			$image_formats_mask += String( $href ).toLowerCase().indexOf( '.' + $image_formats[$i] );
		}

		// Check for Vimeo URL
		var $reg_exp = /http(s?):\/\/(www\.)?vimeo.com\/(\d+)/;
		var $match = $href.match( $reg_exp );
		if ( $match ) {
			$image_formats_mask = 1;
		}

		// Check for Youtube URL
		$reg_exp =  /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
		$match = $href.match( $reg_exp );
		if ( $match ) {
			$image_formats_mask = 1;
		}

		// If no image extension was found add the no lightbox class
		if ( $image_formats_mask == -13 ) {
			jQuery( this ).addClass( 'fusion-no-lightbox' )
		}

		if ( ! jQuery( this ).hasClass( 'fusion-no-lightbox' ) ) {
			var $data_rel = this.getAttribute("data-rel");
			if( $data_rel != null ) {
				jQuery.inArray( $data_rel, $groups_arr ) === -1 && $groups_arr.push( $data_rel );
			}

			var $rel = this.getAttribute("data-rel");
			if( $rel != null ) {

				// For WP galleries make sure each has its own lightbox gallery
				if ( jQuery ( this ).parents( '.gallery' ).length ) {
					$rel = $rel.replace( 'postimages', jQuery ( this ).parents( '.gallery' ).attr( 'id' ) );
					jQuery( this ).attr( 'data-rel', $rel );
				}

				jQuery.inArray( $rel, $groups_arr ) === -1 && $groups_arr.push( $rel );
			}
		}
	});

	// Special setup for jetpack tiled gallery
	var $tiled_gallery_counter = 1;
	$wrapper.find( '.tiled-gallery' ).each( function() {
		jQuery( this ).find( '.tiled-gallery-item > a' ).each( function() {
			var $data_rel = this.getAttribute( 'data-rel' );
			if ( $data_rel == null ) {
				$data_rel = 'iLightbox[tiled-gallery-' + $tiled_gallery_counter + ']';
				jQuery( this ).attr( 'data-rel', $data_rel );
			}

			jQuery.inArray( $data_rel, $groups_arr ) === -1 && $groups_arr.push( $data_rel );
		});

		$tiled_gallery_counter++;
	});


	// Activate lightbox for galleries
	jQuery.each( $groups_arr, function ( $i, $group_name ) {
		// For groups with only one single image, disable the slideshow play button
		if ( jQuery( '[data-rel="' + $group_name + '"], [rel="' + $group_name + '"]' ).length == 1 ) {
			$il_instances.push( jQuery( '[data-rel="' + $group_name + '"], [rel="' + $group_name + '"]' ).iLightBox( $avada_lightbox.prepare_options( $group_name, false ) ) );
		} else {
			$il_instances.push( jQuery( '[data-rel="' + $group_name + '"], [rel="' + $group_name + '"]' ).iLightBox( $avada_lightbox.prepare_options( $group_name ) ) );
		}
	});

	// Activate lightbox for single instances
	$wrapper.find( "a[rel='prettyPhoto'], a[data-rel='prettyPhoto'], a[rel='iLightbox'], a[data-rel='iLightbox']" ).each( function() {
		$il_instances.push( jQuery( this ).iLightBox( $avada_lightbox.prepare_options( 'single' ) ) );
	});

	// Activate lightbox for single lightbox links
	$wrapper.find( '#lightbox-link, .lightbox-link, .fusion-lightbox-link' ).each( function() {
		$il_instances.push( jQuery( this ).iLightBox( $avada_lightbox.prepare_options( 'single' ) ) );
	});

	//activate lightbox for images within the post content
	if( Boolean( Number( js_local_vars.lightbox_post_images ) ) ) {
		$wrapper.find( '.type-post .post-content a, #posts-container .post .post-content a, .fusion-blog-shortcode .post .post-content a' ).has( 'img' ).each(
			function() {
				// Make sure the lightbox is only used for image links and not for links to external pages
				var $image_formats = ['bmp', 'gif', 'jpeg', 'jpg', 'png', 'tiff', 'tif', 'jfif', 'jpe', 'svg', 'mp4', 'ogg', 'webm' ],
					$image_formats_mask = 0;

				// Loop through the image extensions array to see if we have an image link
				for ( var $i = 0; $i < $image_formats.length; $i++ ) {
					$image_formats_mask += String( jQuery( this ).attr( 'href' ) ).toLowerCase().indexOf( '.' + $image_formats[$i] );
				}

				// If no image extension was found add the no lightbox class
				if ( $image_formats_mask == -13 ) {
					jQuery( this ).addClass( 'fusion-no-lightbox' )
				}

				if( String( jQuery( this ).attr( 'rel' ) ).indexOf( 'prettyPhoto' ) === -1 && String( jQuery( this ).attr( 'data-rel' ) ).indexOf( 'prettyPhoto' ) === -1 && String( jQuery( this ).attr( 'rel' ) ).indexOf( 'iLightbox' ) === -1 && String( jQuery( this ).attr( 'data-rel' ) ).indexOf( 'iLightbox' ) === -1 && ! jQuery( this ).hasClass( 'fusion-no-lightbox' ) ) {
					jQuery( this ).attr( 'data-caption', jQuery( this ).parent().find('p.wp-caption-text').text() );
					$il_instances.push( jQuery( this ).iLightBox( $avada_lightbox.prepare_options( 'post' ) ) );
				}
			}
		);
	}
};

/**
* [set_title_and_caption For old prettyPhoto instances initialize caption and titles]
*/
$avada_lightbox.set_title_and_caption = function(){
	jQuery( "a[rel^='prettyPhoto'], a[data-rel^='prettyPhoto']" ).each(function( index ) {
		if ( ! jQuery( this ).attr( 'data-caption' ) ) {

			if ( ! jQuery( this ).attr( 'title' ) ) {
				jQuery( this ).attr( 'data-caption', jQuery( this ).parents( '.gallery-item' ).find( '.gallery-caption' ).text() );
			} else {
				jQuery( this ).attr( 'data-caption', jQuery( this ).attr( 'title' ) );
			}
		}

		if ( ! jQuery( this ).attr( 'data-title' ) ) {
			jQuery( this ).attr( 'data-title', jQuery( this ).find( 'img' ).attr( 'alt' ) );
		}
	});

	jQuery( "a[rel^='iLightbox'], a[data-rel^='iLightbox']" ).each(function( index ) {
		if ( ! jQuery( this ).attr( 'data-caption' ) ) {
			jQuery( this ).attr( 'data-caption', jQuery( this ).parents( '.gallery-item' ).find( '.gallery-caption' ).text() );
		}
	});
};

/**
* [prepare_options set data for page options]
*/
$avada_lightbox.prepare_options = function( $link_id, $gallery ){

	// Default value for optional $gallery variable
	if ( typeof $gallery === 'undefined' ) {
    	$gallery = Boolean( Number( js_local_vars.lightbox_gallery  ) );
  	}

	var $show_speed = {Fast:100, Slow:800, Normal:400};
	var $autoplay	= {1:false, 0:true};

	var $ilightbox_args = {
		skin: js_local_vars.lightbox_skin,
		smartRecognition: false,
		minScale: 0.075,
		show: {
			title: Boolean( Number( js_local_vars.lightbox_title ) ),
			speed: $show_speed[js_local_vars.lightbox_animation_speed],
		},
		path: js_local_vars.lightbox_path,
		controls: {
			slideshow: $gallery,
			arrows: Boolean( Number( js_local_vars.lightbox_arrows ) )
		},
		slideshow: {
			pauseTime: js_local_vars.lightbox_slideshow_speed,
			pauseOnHover: false,
			startPaused: $autoplay[Number( js_local_vars.lightbox_autoplay )]
		},
		overlay: {
			opacity: js_local_vars.lightbox_opacity
		},
		caption: {
			start: Boolean( Number( js_local_vars.lightbox_desc ) ),
			show: '',
			hide: ''
		},
		isMobile: true
	};

	//for social sharing
	if( Boolean( Number( js_local_vars.lightbox_social ) ) ) {

		$ilightbox_args.social = {
			buttons: {
				facebook: true,
				twitter: true,
				googleplus: true,
				reddit: true,
				digg: true,
				delicious: true
			}
		};
	}

	//for deep linking
	if( Boolean( Number( js_local_vars.lightbox_deeplinking ) ) ) {
		$ilightbox_args.linkId = $link_id;
	}

	return $ilightbox_args;
};
/**
* [refresh_instance A function to refresh all items and rebind all elements.]
*/
$avada_lightbox.refresh_lightbox = function( ) {

	$avada_lightbox.set_title_and_caption();

	jQuery.each( $il_instances, function( $key, $value ) {
		if( $value.hasOwnProperty( 'refresh' ) ) {
			$value.refresh();
		};
	});
};

// lightbox initialization for dynamically loaded content
jQuery( document ).ajaxComplete( function() {
	$avada_lightbox.refresh_lightbox();
});

jQuery(window).load(function() {
	//initialize lightbox
	$avada_lightbox.initialize_lightbox();
});
