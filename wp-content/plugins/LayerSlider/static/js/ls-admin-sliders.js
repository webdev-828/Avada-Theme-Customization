jQuery(function($) {

	var LS_GoogleFontsAPI = {

		results : 0,
		fontName : null,
		fontIndex : null,

		init : function() {

			// Prefetch fonts
			$('.ls-font-search input').focus(function() {
				LS_GoogleFontsAPI.getFonts();
			});

			// Search
			$('.ls-font-search button').click(function(e) {
				e.preventDefault();
				var input = $(this).prev()[0];
				LS_GoogleFontsAPI.timeout = setTimeout(function() {
					LS_GoogleFontsAPI.search(input);
				}, 500);
			});

			$('.ls-font-search input').keydown(function(e) {
				if(e.which === 13) {
					e.preventDefault();
					var input = this;
					LS_GoogleFontsAPI.timeout = setTimeout(function() {
						LS_GoogleFontsAPI.search(input);
					}, 500);
				}
			});

			// Select font
			$('.ls-google-fonts .fonts').on('click', 'li:not(.unselectable)', function() {
				LS_GoogleFontsAPI.showVariants(this);
			});

			// Add font event
			$('.ls-font-search').on('click', 'button.add-font', function(e) {
				e.preventDefault();
				LS_GoogleFontsAPI.addFonts(this);
			});

			// Back to results event
			$('.ls-google-fonts .variants').on('click', 'button:last', function(e) {
				e.preventDefault();
				LS_GoogleFontsAPI.showFonts(this);
			});

			// Close event
			$(document).on('click', '.ls-overlay', function() {

				if($(this).data('manualclose')) {
					return false;
				}

				if($('.ls-pointer').length) {
					$(this).remove();
					$('.ls-pointer').children('div.fonts').show().next().hide();
					$('.ls-pointer').animate({ marginTop : 40, opacity : 0 }, 150, function() {
						this.style.display = 'none';
					});
				}
			});

			// Remove font
			$('.ls-font-list').on('click', 'a.remove', function(e) {
				e.preventDefault();
				$(this).parent().animate({ height : 0, opacity : 0 }, 300, function() {

					// Add notice if needed
					if($(this).siblings().length < 2) {
						$(this).parent().append(
							$('<li>', { 'class' : 'ls-notice', 'text' : 'You didn\'t add any Google font to your library yet.'})
						);
					}

					$(this).remove();
				});
			});

			// Add script
			$('.ls-google-fonts .footer select').change(function() {

				// Prevent adding the placeholder option tag
				if($('option:selected', this).index() !== 0) {

					// Selected item
					var item = $('option:selected', this);
					var hasDuplicate = false;

					// Prevent adding duplicates
					$('.ls-google-font-scripts input').each(function() {
						if($(this).val() === item.val()) {
							hasDuplicate = true;
							return false;
						}
					});

					// Add item
					if(!hasDuplicate) {
						var clone = $('.ls-google-font-scripts li:first').clone();
							clone.find('span').text( item.text() );
							clone.find('input').val( item.val() );
							clone.removeClass('ls-hidden').appendTo('.ls-google-font-scripts');
					}

					// Show the placeholder option tag
					$('option:first', this).prop('selected', true);
				}
			});

			// Remove script
			$('.ls-google-font-scripts').on('click', 'li a', function(event) {
				event.preventDefault();

				if($('.ls-google-font-scripts li').length > 2) {
					$(this).closest('li').remove();
				} else {
					alert('You need to have at least one character set added. Please select another item before removing this one.');
				}
			});
		},

		getFonts : function() {

			if(LS_GoogleFontsAPI.results == 0) {
				var API_KEY = 'AIzaSyC_iL-1h1jz_StV_vMbVtVfh3h2QjVUZ8c';
				$.getJSON('https://www.googleapis.com/webfonts/v1/webfonts?key=' + API_KEY, function(data) {
					LS_GoogleFontsAPI.results = data;
				});
			}
		},

		search : function(input) {

			// Hide overlay if any
			$('.ls-overlay').remove();

			// Get search field
			var searchValue = $(input).val().toLowerCase();

			// Wait until fonts being fetched
			if(LS_GoogleFontsAPI.results != 0 && searchValue.length > 2 ) {

				// Search
				var indexes = [];
				var found = $.grep(LS_GoogleFontsAPI.results.items, function(obj, index) {
					if(obj.family.toLowerCase().indexOf(searchValue) !== -1) {
						indexes.push(index);
						return true;
					}
				});

				// Get list
				var list = $('.ls-font-search .ls-pointer .fonts ul');

				// Remove previous contents and append new ones
				list.empty();
				if(found.length) {
					for(c = 0; c < found.length; c++) {
						list.append( $('<li>', { 'data-key' : indexes[c], 'text' : found[c]['family'] }));
					}
				} else {
					list.append($('<li>', { 'class' : 'unselectable' })
						.append( $('<h4>', { 'text' : 'No results were found' }))
					);
				}

				// Show pointer and append overlay
				$('.ls-font-search .ls-pointer').show().animate({ marginTop : 15, opacity : 1 }, 150);
				$('<div>', { 'class' : 'ls-overlay ls-add-slider-overlay'}).prependTo('body');
			}
		},

		showVariants : function(li) {

			// Get selected font
			var fontName = $(li).text();
			var fontIndex = $(li).data('key');
			var fontObject = LS_GoogleFontsAPI.results.items[fontIndex]['variants'];
			LS_GoogleFontsAPI.fontName = fontName;
			LS_GoogleFontsAPI.fontIndex = fontIndex;

			// Get and empty list
			var list = $(li).closest('div').next().children('ul');
				list.empty();


			// Change header
			$(li).closest('.ls-box').children('.header').text('Select "'+fontName+'" variants');

			// Append variants
			for(c = 0; c < fontObject.length; c++) {
				list.append( $('<li>', { 'class' : 'unselectable' })
					.append( $('<input>', { 'type' : 'checkbox'} ))
					.append( $('<span>', { 'text' : ucFirst(fontObject[c]) }))
				);
			}

			// Init checkboxes
			list.find(':checkbox').customCheckbox();

			// Show variants
			$(li).closest('.fonts').hide().next().show();
		},

		showFonts : function(button) {
			$(button).closest('.ls-box').children('.header').text('Choose a font family');
			$(button).closest('.variants').hide().prev().show();
		},

		addFonts: function(button) {

			// Get variants
			var variants = $(button).parent().prev().find('input:checked');

			var apiUrl = [];
			var urlVariants = [];
			apiUrl.push(LS_GoogleFontsAPI.fontName.replace(/ /g, '+'));

			if(variants.length) {
				apiUrl.push(':');
				variants.each(function() {
					urlVariants.push( $(this).siblings('span').text().toLowerCase() );
				});
				apiUrl.push(urlVariants.join(','));
			}

			LS_GoogleFontsAPI.appendToFontList( apiUrl.join('') );
		},

		appendToFontList : function(url) {

			// Empty notice if any
			$('ul.ls-font-list li.ls-notice').remove();

			var index = $('ul.ls-font-list li').length - 1;

			// Append list item
			var item = $('ul.ls-font-list li.ls-hidden').clone();
				item.children('input:text').val(url).attr('name', 'urlParams[]');
				item.children('input:checkbox').attr('name', 'onlyOnAdmin[]');
				item.appendTo('ul.ls-font-list').attr('class', '');

			// Reset search field
			$('.ls-font-search input').val('');

			// Close pointer
			$('.ls-overlay').click();
		}
	};


	// Checkboxes
	$('.ls-global-settings :checkbox').customCheckbox();
	$('.ls-google-fonts :checkbox').customCheckbox();

	// Tabs
	$('.km-tabs').kmTabs();

	// Google Fonts API
	LS_GoogleFontsAPI.init();

	// Slider remove
	$('.ls-sliders-list a.remove').click(function(e) {
		e.preventDefault();
		if(confirm('Are you sure you want to remove this slider?')){
			document.location.href = $(this).attr('href');
		}
	});


	// Add slider
	$('#ls-add-slider-button').click(function(e) {

		e.preventDefault();
		var offsets = $(this).offset();
		var popup = $('#ls-add-slider-template').length ?
					$('#ls-add-slider-template') :
					$($('#tmpl-ls-add-slider').html()).prependTo('body');

		popup.css({
			top : offsets.top + 35,
			left : offsets.left - popup.outerWidth() / 2 + $(this).width() / 2 + 7
		}).show().animate({ marginTop : 0, opacity : 1 }, 150, function() {
			$(this).find('.inner input').focus();
		});

		$('<div>', { 'class' : 'ls-overlay ls-add-slider-overlay'}).prependTo('body');
	});


	// Import sample slider
	$('#ls-import-samples-button').click(function(e) {
		e.preventDefault();
		var offsets = $(this).offset();
		var popup = $('#ls-import-samples-template').length ?
			$('#ls-import-samples-template') :
			$($('#tmpl-demo-sliders').html()).prependTo('body');

		popup.css({
			top : offsets.top + 35,
			left : offsets.left - popup.outerWidth() / 2 + $(this).width() / 2 + 7
		}).show().animate({ marginTop : 0, opacity : 1 }, 150);

		$('<div>', { 'class' : 'ls-overlay ls-add-slider-overlay'}).prependTo('body');
	});


	// Close add slider window
	$(document).on('click', '.ls-overlay', function() {

		if($(this).data('manualclose')) {
			return false;
		}

		if($('.ls-pointer').length) {
			$('.ls-overlay').remove();
			$('.ls-pointer').animate({ marginTop : 40, opacity : 0 }, 150);
		}
	});


	// Auto-update authorization
	$('.ls-auto-update form').submit(function(e) {

		// Prevent browser default submission
		e.preventDefault();

		// Send request and provide feedback message
		$('.ls-auto-update span.status').text('Validating ...').css('color', '#333');

		// Post it
		$.post( ajaxurl, $(this).serialize(), function(data) {

			// Parse response and set message
			var data = $.parseJSON(data);
			var success = (typeof data.errCode === "undefined") ? true : false;
			var color = success ? '#76b546' : 'red';
			var status = success ? 'Successfully set up automatic updates.' : 'Failed to set up automatic updates.';
				status = (typeof data.status === "undefined") ? status : data.status;

			// Status message
			$('.ls-auto-update span.status').html(status).css('color', color);

			// Show or hide 'Check for updates' button
			if(success) {
				$('.ls-auto-update .footer a').removeClass('ls-hidden');
			} else {
				$('.ls-auto-update .footer a').addClass('ls-hidden');
			}

			// Alert message (if any)
			if(typeof data.message !== "undefined") {
				alert(data.message);
			}
		});
	});


	// Auto-update deauthorization
	$('.ls-auto-update a.ls-deauthorize').click(function(event) {
		event.preventDefault();
		$.get( ajaxurl, $.param({ action: 'layerslider_deauthorize_site'}), function(data) {

			// Parse response and set message
			var data = $.parseJSON(data);

			if(typeof data.errCode === "undefined") {
				$('.ls-auto-update span.status').html(data.status).css('color', 'red');
				$('.ls-auto-update .footer a').addClass('ls-hidden');
				$('.ls-auto-update input[name="purchase_code"]').val('');
			}

			// Alert message (if any)
			if(typeof data.message !== "undefined") {
				alert(data.message);
			}
		});
	});


	// Permission form
	$('#ls-permission-form').submit(function(e) {
		e.preventDefault();
		if(confirm('WARNING: This option controls who can access to this plugin, you can easily lock out yourself by accident. Please, make sure that you have entered a valid capability without whitespaces or other invalid characters. Do you want to proceed?')) {
			this.submit();
		}
	});


	// News filters
	$('.ls-news .filters li').click(function() {

		// Highlight
		$(this).siblings().attr('class', '');
		$(this).attr('class', 'active');

		// Get stuff
		var page = $(this).data('page');
		var frame = $(this).closest('.ls-box').find('iframe');
		var baseUrl = frame.attr('src').split('#')[0];

		// Set filter
		frame.attr('src', baseUrl+'#'+page);

	});


	// Shortcode
	$('input.ls-shortcode').click(function() {
		this.focus();
		this.select();
	});


	// Import
	$('form.ls-import-box button').click(function() {
		$(this).addClass('saving').text('Importing');
	});

	$('#ls-import-samples-template li').click(function() {
		$('#ls-import-samples-button').addClass('saving').text('Importing, please wait');
	});

});