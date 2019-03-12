jQuery( document ).ready(
    function() {
        function nice_scroll_init() {
            jQuery( 'html' ).niceScroll(
                {
                    background: '#555',
                    scrollspeed: 60,
                    mousescrollstep: 40,
                    cursorwidth: 9,
                    cursorborder: '0px',
                    cursorcolor: '#303030',
                    cursorborderradius: 8,
                    preservenativescrolling: true,
                    cursoropacitymax: 1,
                    cursoropacitymin: 1,
                    autohidemode: false,
                    zindex: 999999,
                    horizrailenabled: false
                }
            );

            if( jQuery( 'html' ).getNiceScroll().length ) {
                jQuery( 'html' ).addClass( 'no-overflow-y' );
            } else {
                jQuery( 'html' ).removeClass( 'no-overflow-y' );   
            }
        }

        var $smooth_active = js_local_vars.smooth_scrolling,
        	$smooth_cache = ( $smooth_active == 1 ) ? true : false;

		setTimeout ( function() {
	        if ( $smooth_active == 1 && ! Modernizr.mq( 'screen and (max-width: ' + ( 800 + parseInt( js_local_vars.side_header_width ) ) +  'px)' ) && jQuery( 'body' ).outerHeight( true ) > jQuery( window ).height() && ! navigator.userAgent.match( /(Android|iPod|iPhone|iPad|IEMobile|Opera Mini)/ ) ) {
	            nice_scroll_init();
			} else {
                jQuery( 'html' ).removeClass( 'no-overflow-y' );   
            }
		}, 50 );


        jQuery( window ).resize( function() {
            var $smooth_active = js_local_vars.smooth_scrolling,
            	$smooth_cache = ( $smooth_active == 1 ) ? true : false;

            if ( $smooth_active == 1 && ! Modernizr.mq( 'screen and (max-width: ' + ( 800 + parseInt( js_local_vars.side_header_width ) ) +  'px)' ) && jQuery( 'body' ).outerHeight( true ) > jQuery( window ).height() && ! navigator.userAgent.match( /(Android|iPod|iPhone|iPad|IEMobile|Opera Mini)/ ) ) {
                nice_scroll_init();
            } else {
                jQuery( 'html' ).getNiceScroll().remove();
                jQuery( 'html' ).removeClass( 'no-overflow-y' );
                jQuery( 'html' ).css( 'overflow-y', 'auto' );
                jQuery( '#ascrail2000' ).css( 'opacity', '1' );
            }
        });
    }
);
