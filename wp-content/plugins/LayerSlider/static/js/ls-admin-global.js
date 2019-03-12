if (!Array.prototype.indexOf) {
	Array.prototype.indexOf = function (searchElement /*, fromIndex */ ) {
		"use strict";
		if (this === null) {
			throw new TypeError();
		}
		var t = Object(this);
		var len = t.length >>> 0;
		if (len === 0) {
			return -1;
		}
		var n = 0;
		if (arguments.length > 1) {
			n = Number(arguments[1]);
			if (n != n) { // shortcut for verifying if it's NaN
				n = 0;
			} else if (n != 0 && n != Infinity && n != -Infinity) {
				n = (n > 0 || -1) * Math.floor(Math.abs(n));
			}
		}
		if (n >= len) {
			return -1;
		}
		var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);
		for (; k < len; k++) {
			if (k in t && t[k] === searchElement) {
				return k;
			}
		}
		return -1;
	};
}

Storage.prototype.setObject = function(key, value) {
    this.setItem(key, JSON.stringify(value));
};

Storage.prototype.getObject = function(key) {
    var value = this.getItem(key);
    return value && JSON.parse(value);
};


function moveArrayItem(array, from, to) {
	if( to === from ) return;

	var target = array[from];
	var increment = to < from ? -1 : 1;

	for(var k = from; k != to; k += increment){
		array[k] = array[k + increment];
	}
	array[to] = target;
}


function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function ucFirst(string) {
	return string.charAt(0).toUpperCase() + string.slice(1);
}


(function( $ ) {
	$.fn.appendToWithIndex = function(to, index) {

		if(!to instanceof jQuery) { to = $(to); }

		if(index == 0) {
			this.prependTo(to);
		} else {
			this.insertAfter( to.children(':eq('+(index-1)+')') );
		}

		return this;
	};
})( jQuery );



(function( $ ) {

	$.fn.customCheckbox = function() {
		return this.each(function() {

			// Get element & hide it
			var $el = $(this).hide();

			// Create replacement element
			var $rep = $('<a href="#"><span></span></a>').addClass('ls-checkbox').insertAfter(this);

			// Check help attr
			if($el.attr('data-help') !== "undefined") {
				$rep.attr('data-help', $el.attr('data-help'));
			}

			// Set default state
			if($el.prop('checked')) {
				$rep.addClass('on');
			} else {
				$rep.addClass('off');
			}
		});
	};
})( jQuery );


(function( $ ) {

	$.fn.kmTabs = function(p) {

		var properties = $.extend({}, p);

		return this.each(function(){

			var $tabs = $(this);
			var $content =  properties.content ? $(properties.content) : $(this).next('.km-tabs-content');

			$tabs.on('click', 'a:not(".active")', function(event){

				event.preventDefault();

				$tabs.children().removeClass('active');
				$(this).addClass('active');

				var index = $(this).index();
				var $iContent = $content.children().eq(index);

				$iContent.find('.km-tabs-inner').css({
					display : 'block'
				});
				var targetedHeight = $iContent.outerHeight();
				$iContent.find('.km-tabs-inner').css({
					display : 'none'
				});

				$content.find('> .active .km-tabs-inner').fadeOut(200,function(){
					$iContent.find('.km-tabs-inner').fadeIn(200);
					$content.children().removeClass('active').eq(index).addClass('active');
				});

				$content.animate({
					height: targetedHeight
				},400, function(){
					$content.css('height','auto');
				});

			});
		});
	};

}( jQuery ));



var LS_CodeMirror = {

	init : function(settings) {

		var defaults = {
			mode: 'css',
			theme: 'solarized',
			lineNumbers: true,
			autofocus: true,
			indentUnit: 4,
			indentWithTabs: true,
			foldGutter: true,
			gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
			styleActiveLine: true,
			extraKeys: {
				"Ctrl-Q": function(cm) {
					cm.foldCode(cm.getCursor());
				}
			}
		}

		if(typeof settings !== "undefined") {
			jQuery.extend(defaults, settings);
		}

		jQuery('.ls-codemirror').each(function() {
			var cm = CodeMirror.fromTextArea(this, defaults);
			cm.on("change", function(instance) {
				instance.save();
				jQuery(instance.getTextArea()).trigger('updated.ls');
			});
		});
	}
};




jQuery(function($) {




	var lsScreenOptionsActions = {

		init : function() {

			// Form submit
			$(document).on('submit', '#ls-screen-options-form', function(e) {
				e.preventDefault(); lsScreenOptionsActions.saveSettings(this, true);
			});

			// Checkboxes
			$(document).on('click', '#ls-screen-options-form input:checkbox', function() {
				var reload = false;
				if(typeof lsScreenOptionsActions[ $(this).attr('name')] != "undefined") {
					lsScreenOptionsActions[ $(this).attr('name')](this); }

				if($(this).hasClass('reload')) { reload = true; }

				lsScreenOptionsActions.saveSettings( $(this).closest('form'), reload );
			});
		},

		saveSettings : function(form, reload) {

			var options = {};
			$(form).find('input').each(function() {
				if( $(this).is(':checkbox')) {
					options[$(this).attr('name')] = $(this).prop('checked');
				} else {
					options[$(this).attr('name')] = $(this).val();
				}
			});

			// Save settings
			$.post(ajaxurl, $.param({ action : 'ls_save_screen_options', options : options }), function() {
				if(typeof reload != "undefined" && reload === true) {
					document.location.href = 'admin.php?page=layerslider';
				}
			});
		},

		showTooltips : function(el) {

			if( $(el).prop('checked') === true ) {
				lsTooltip.init();
			} else {
				lsTooltip.destroy();
			}
		}
	};





	var lsTooltip = {
		timeout : 0,

		init : function() {

			$(document).on('mouseover', '[data-help]', function() {
				var el = this;
				lsTooltip.timeout = setTimeout(function() {
					lsTooltip.open(el);
				}, 400);
			});

			$(document).on('mouseout', '[data-help]', function() {
				clearTimeout(lsTooltip.timeout);
				lsTooltip.close();
			});
		},

		destroy : function() {

			$(document).off('mouseover', '[data-help]');
			$(document).off('mouseout', '[data-help]');
		},

		open : function(el) {

			// Create tooltip
			$('body').prepend( $('<div>', { 'class' : 'ls-tooltip' })
				.append( $('<div>', { 'class' : 'inner' }))
				.append( $('<span>') )
			);

			// Get tooltip
			var tooltip = $('.ls-tooltip');

			// Set tooltip text
			tooltip.find('.inner').html( $(el).data('help') );

			// Get viewport dimensions
			var v_w = $(window).width();

			// Get element dimensions
			var e_w = $(el).width();

			// Get element position
			var e_l = $(el).offset().left;
			var e_t = $(el).offset().top;

			// Get toolip dimensions
			var t_w = tooltip.outerWidth();
			var t_h = tooltip.outerHeight();

			// Position tooltip
			tooltip.css({ top : e_t - t_h - 10, left : e_l - (t_w - e_w) / 2  });
			// Fix right position
			if(tooltip.offset().left + t_w > v_w) {
				tooltip.css({ 'left' : 'auto', 'right' : 10 });
				tooltip.find('span').css({ left : 'auto', right : v_w - $(el).offset().left - $(el).outerWidth() / 2 - 17, marginLeft : 'auto' });
			}

		},

		close : function() {
			$('.ls-tooltip').remove();
		}
	};



	// Tooltips
	if(typeof lsScreenOptions != 'undefined' && lsScreenOptions['showTooltips'] == 'true') {
		lsTooltip.init();
	}


	// Screen options
	$('#ls-screen-options').children().first().appendTo('#screen-meta');
	$('#ls-screen-options').children().last().appendTo('#screen-meta-links');
	lsScreenOptionsActions.init();


	// CodeMirror
	if(document.location.href.indexOf('&action=edit') === -1) {
		LS_CodeMirror.init();
	}


	// Skin/CSS Editor
	if(document.location.href.indexOf('ls-skin-editor') != -1 ||
		document.location.href.indexOf('ls-style-editor') != -1) {
		$('select[name="skin"]').change(function() {
			document.location.href = 'admin.php?page=ls-skin-editor&skin=' + $(this).children(':selected').val();
		});
	}


	// Checkbox event
	$(document).on('click', '.ls-checkbox', function(e){
		e.preventDefault();

		// Get checkbox
		var el = $(this).prev()[0];

		if( $(el).is(':checked') ) {
			$(el).prop('checked', false);
			$(this).removeClass('on').addClass('off');
		} else {
			$(el).prop('checked', true);
			$(this).removeClass('off').addClass('on');
		}

		// Trigger events
		$('#ls-layers').trigger( $.Event('click', { target : el } ) );
		$(document).trigger( $.Event('click', { target : el } ) );
	});


	// Share sheet
	$('#ls-share-template .inner a').click(function(e) {
		e.preventDefault();

		var newWindow = window.open('', '_blank', 'width=700,height=400');
			newWindow.location.href = $(this).attr('href');
			newWindow.focus();
	});


	$('#ls-share-template h3 a').click(function(e) {
		e.preventDefault();
		$('#ls-share-template, .ls-overlay').remove();
	});
});