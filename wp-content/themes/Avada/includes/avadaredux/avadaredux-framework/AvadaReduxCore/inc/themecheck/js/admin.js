(function( $ ) {
	"use strict";

	$(function() {

		$('#theme-check > h2').html( $('#theme-check > h2').html() + ' with AvadaRedux Theme-Check' );

		if ( typeof avadaredux_check_intro !== 'undefined' ) {
			$('#theme-check .theme-check').append( avadaredux_check_intro.text );
		}
		$('#theme-check form' ).append('&nbsp;&nbsp;<input name="avadaredux_wporg" type="checkbox">  Extra WP.org Requirements.');
	});

}(jQuery));
