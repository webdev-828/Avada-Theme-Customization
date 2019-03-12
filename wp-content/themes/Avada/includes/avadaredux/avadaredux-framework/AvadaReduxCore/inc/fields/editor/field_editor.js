/**
 * AvadaRedux Editor on change callback
 * Dependencies        : jquery
 * Feature added by    : Dovy Paukstys
 *                     : Kevin Provance (who helped)  :P
 * Date                : 07 June 2014
 */

/*global avadaredux_change, wp, tinymce, avadaredux*/
(function( $ ) {
	"use strict";

	avadaredux.field_objects = avadaredux.field_objects || {};
	avadaredux.field_objects.editor = avadaredux.field_objects.editor || {};

	$( document ).ready(
		function() {
			//avadaredux.field_objects.editor.init();
		}
	);

	avadaredux.field_objects.editor.init = function( selector ) {
		setTimeout(
			function() {
				if (typeof(tinymce) !== 'undefined') {
					for ( var i = 0; i < tinymce.editors.length; i++ ) {
						avadaredux.field_objects.editor.onChange( i );
					}
				}
			}, 1000
		);
	};

	avadaredux.field_objects.editor.onChange = function( i ) {
		tinymce.editors[i].on(
			'change', function( e ) {
				var el = jQuery( e.target.contentAreaContainer );
				if ( el.parents( '.avadaredux-container-editor:first' ).length !== 0 ) {
					avadaredux_change( $( '.wp-editor-area' ) );
				}
			}
		);
	};
})( jQuery );
