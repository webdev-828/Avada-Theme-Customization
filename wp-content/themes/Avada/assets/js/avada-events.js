jQuery( document ).ready( function() {

	// Disable the navigation top and bottom lines, when there is no prev and next nav
	if( ! jQuery.trim( jQuery( '.tribe-events-nav-previous' ).html() ).length && ! jQuery.trim( jQuery( '.tribe-events-nav-next' ).html() ).length ) {
		jQuery( '.tribe-events-sub-nav' ).parent().hide();
	}

	jQuery( '.fusion-tribe-has-featured-image' ).each(function() {
		var height = jQuery(this).parent().height();
		jQuery(this).find('.tribe-events-event-image').css('height', height);
	});

	jQuery( window ).on( 'resize', function() {
		jQuery( '.fusion-tribe-has-featured-image' ).each(function() {
			jQuery(this).find('.tribe-events-event-image').css('height', 'auto');
			var height = jQuery(this).parent().height();
			jQuery(this).find('.tribe-events-event-image').css('height', height);
		});
	});
});

jQuery( window ).load(function() {
	// Equal Heights Elements
	jQuery( '.fusion-events-shortcode' ).each( function() {
		jQuery( this ).find('.fusion-events-meta' ).equalHeights();
	});

	jQuery( window ).on( 'resize', function() {
		jQuery( '.fusion-events-shortcode' ).each( function() {
			jQuery( this ).find( '.fusion-events-meta' ).equalHeights();
		});
	});
});

jQuery( document ).ajaxComplete( function( event, request, settings ) {
	jQuery( '.fusion-tribe-has-featured-image' ).each(function() {
		var height = jQuery(this).parent().height();
		jQuery(this).find('.tribe-events-event-image').css('height', height);
	});

	jQuery( this ).find( '.post' ).each(function() {
		jQuery( this ).find( '.fusion-post-slideshow' ).flexslider();
		jQuery( this ).find( '.full-video, .video-shortcode, .wooslider .slide-content' ).fitVids();
	});

	// Fade in new posts when all images are loaded, then relayout isotope
	var $posts_container = jQuery( '#tribe-events .fusion-blog-layout-grid' );
	var $posts = $posts_container.find( '.post' );
	$posts_container.css( 'height', $posts_container.height() );
	$posts.hide();
	imagesLoaded( $posts, function() {

		$posts_container.css( 'height', '' );
		$posts.fadeIn();

		// Relayout isotope
		$posts_container.isotope();
		jQuery( window ).trigger( 'resize' );

		// Refresh the scrollspy script for one page layouts
		jQuery( '[data-spy="scroll"]' ).each( function () {
			  var $spy = jQuery( this ).scrollspy( 'refresh' );
		});
	});

});
