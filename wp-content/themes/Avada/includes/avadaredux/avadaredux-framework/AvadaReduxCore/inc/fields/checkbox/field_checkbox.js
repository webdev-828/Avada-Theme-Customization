/**
 * AvadaRedux Checkbox
 * Dependencies        : jquery
 * Feature added by    : Dovy Paukstys
 * Date                : 17 June 2014
 */

/*global avadaredux_change, wp, avadaredux*/

(function( $ ) {
	"use strict";

	avadaredux.field_objects = avadaredux.field_objects || {};
	avadaredux.field_objects.checkbox = avadaredux.field_objects.checkbox || {};

	$( document ).ready(
		function() {
			//avadaredux.field_objects.checkbox.init();
		}
	);

	avadaredux.field_objects.checkbox.init = function( selector ) {
		if ( !selector ) {
			selector = $( document ).find( ".avadaredux-group-tab:visible" ).find( '.avadaredux-container-checkbox:visible' );
		}

		$( selector ).each(
			function() {
				var el = $( this );
				var parent = el;
				if ( !el.hasClass( 'avadaredux-field-container' ) ) {
					parent = el.parents( '.avadaredux-field-container:first' );
				}
				if ( parent.is( ":hidden" ) ) { // Skip hidden fields
					return;
				}
				if ( parent.hasClass( 'avadaredux-field-init' ) ) {
					parent.removeClass( 'avadaredux-field-init' );
				} else {
					return;
				}
				el.find( '.checkbox' ).on(
					'click', function( e ) {
						var val = 0;
						if ( $( this ).is( ':checked' ) ) {
							val = $( this ).parent().find( '.checkbox-check' ).attr( 'data-val' );
						}
						$( this ).parent().find( '.checkbox-check' ).val( val );
						avadaredux_change( $( this ) );
					}
				);
			}
		);
	};
})( jQuery );
