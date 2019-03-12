// Stores the database ID of
// currently editing slider.
var LS_sliderID = 0;


// Store the indexes of currently
// selected items on the interface.
var LS_activeSlideIndex = 0;
var LS_activeLayerIndex = 0;
var LS_activeLayerPageIndex = 0;


// Object references, pointing to the currently selected
// slide/layer data. These are not working copies, any
// change made will affect the main data object. This makes
// possible to avoid issues caused by inconsistent data.
var LS_activeSlideData = null;
var LS_activeLayerData = null;


// These objects will be filled with the default slide/layer
// properties when needed. They purpose as a caching mechanism
// for bulk slide/layer creation.
var LS_defaultSlideData = {};
var LS_defaultLayerData = {};


// Stores all previous editing sessions
// to cache results and speed up operations
var LS_editorSessions = {};




var LayerSlider = {

	uploadInput : null,
	dragIndex : 0,
	timeout : 0,


	getEditingSession: function() {

		// Get all sessions
		var sessions = localStorage.getObject('ls-editor-sessions');

		// Select the item we need
		if(sessions && sessions.hasOwnProperty('slider-'+LS_sliderID)) {
			return sessions['slider-'+LS_sliderID];
		}

		return {
			slideIndex: LS_activeSlideIndex,
			layerIndex: LS_activeLayerIndex,
			layerPageIndex: LS_activeLayerPageIndex
		};
	},


	saveEditingSession: function() {

		LS_editorSessions['slider-'+LS_sliderID] = {
			slideIndex: LS_activeSlideIndex,
			layerIndex: LS_activeLayerIndex,
			layerPageIndex: LS_activeLayerPageIndex
		};

		localStorage.setObject('ls-editor-sessions', LS_editorSessions);
	},


	selectMainTab: function(el) {

		// Select new tab
		jQuery(el).addClass('active').siblings().removeClass('active');

		// Show new tab contents
		jQuery('#ls-pages .ls-page').removeClass('active');
		jQuery('#ls-pages .ls-page').eq( jQuery(el).index() ).addClass('active');

		// Init CodeMirror
		if(jQuery(el).hasClass('callbacks')) {
			if(jQuery('.ls-callback-page .CodeMirror-code').length === 0) {
				LS_CodeMirror.init({ mode: 'javascript', autofocus : false, styleActiveLine : false });
				jQuery(window).scrollTop(0);
			}
		}
	},


	selectSettingsTab: function(li) {
		var index = jQuery(li).index();
		jQuery(li).addClass('active').siblings().removeClass('active');
		jQuery('div.ls-settings-contents tbody.active').removeClass('active');
		jQuery('div.ls-settings-contents tbody').eq(index).addClass('active');
	},


	addSlide: function() {

		// Add new slide tab
		var newIndex = window.lsSliderData.layers.length + 1;
		var tab = jQuery('<a href="#">Slide #'+newIndex+'<span class="dashicons dashicons-dismiss"></span>').insertBefore('#ls-add-layer');

		// Name new slide properly
		LayerSlider.reindexSlides();
		LayerSlider.addSlideSortables();

		// Get default data objects for slides and layers
		var newSlideData = jQuery.extend(true, {}, LS_DataSource.getDefaultSlideData());
		var newLayerData = jQuery.extend(true, {}, LS_DataSource.getDefaultLayerData());
			newLayerData.subtitle = 'Layer #1';

		// Add new slide data to data source
		window.lsSliderData.layers.push({
			properties: newSlideData,
			sublayers: [newLayerData]
		});

		// Show new slide, re-initialize
		// interactive features
		tab.click();
		LayerSlider.addLayerSortables();
	},


	removeSlide: function(el) {

		if(confirm('Are you sure you want to remove this slide?')) {

			// Get tab and menu item index
			var index = LS_activeSlideIndex;
			var $tab = jQuery(el).parent();
			var $newTab = null;

			// Open next or prev layer
			if($tab.next(':not(.unsortable)').length > 0) {
				$newTab = $tab.next();

			} else if($tab.prev().length > 0) {
				$newTab = $tab.prev();
			}

			// Remove tab and slide data
			window.lsSliderData.layers.splice(index, 1);
			$tab.remove();

			// Create a new slide if the last one
			// was removed
			if(window.lsSliderData.layers < 1) {
				LayerSlider.addSlide();
				return true;
			}

			// Select new slide. The .click() event will
			// maintain the active slide index and data.
			LayerSlider.reindexSlides();
			$newTab.click();
		}
	},


	selectSlide: function(el) {

		// Bail out early if it's the currently active layer
		if(jQuery(el).hasClass('active') ) { return false; }

		// Set active slide, highlight new tab
		jQuery(el).addClass('active').siblings().removeClass('active');

		// Stop live preview (if any)
		LayerSlider.stop();

		// Maintain active slide/layer data
		LS_activeSlideIndex = jQuery(el).index();
		LS_activeSlideData = window.lsSliderData.layers[ jQuery(el).index() ];
		LS_activeLayerIndex = 0;
		LS_activeLayerData = LS_activeSlideData.sublayers[0];

		// Generate new slide markup
		// and update the Preview
		LS_DataSource.buildSlide();
		LayerSlider.generatePreview();

		// Hide Timeline
		lsTimeLine.hide( jQuery('.ls-tl') );

		// Store selection
		LayerSlider.saveEditingSession();
	},


	duplicateSlide: function(el) {

		// Append new tab and re-index slides
		jQuery('<a href="#">Slide #0<span class="dashicons dashicons-dismiss"></span></a>').insertBefore('#ls-layer-tabs a:last');
		LayerSlider.reindexSlides();

		// Duplicate slide by using jQuery.extend()
		// to make sure it's a copy instead of an
		// object reference.
		window.lsSliderData.layers.push(
			jQuery.extend(true, {}, LS_activeSlideData)
		);
	},


	addPreviewSlider: function(target) {

		jQuery(target).slider({
			value: 1, min: 0.3, max: 2, step: 0.1,
			range: 'min', orientation: "horizontal",
      		slide: function(event, ui) {

      			// Set value
      			jQuery(ui.handle).parent().next().text(''+parseInt(ui.value*100)+'%');

      			// Get vars
      			var $slide = jQuery(ui.handle).closest('.ls-layer-box');
      			var $preview = $slide.find('.ls-preview-wrapper').css('zoom', ui.value);
      		}
		});
	},


	addLayer: function(el) {

		// Get sample markup and append it
		var $template = jQuery( jQuery('#ls-layer-item-template').text() );
			$template.appendTo( jQuery('.ls-sublayers') );

		// Name the new layer
		$template.find('.ls-sublayer-title').val('Layer #' + ($template.index() + 1) );


		// Get default layer data. Using jQuery.extend() to
		// make sure it's a copy instead of an object reference.
		var layerData = jQuery.extend(true, {}, LS_DataSource.getDefaultLayerData());
			layerData.subtitle = 'Layer #' + ($template.index() + 1);

		// Add new layer data
		LS_activeSlideData.sublayers.push( layerData );

		// Open it. The .click() event will maintain
		// the new layer index and data.
		$template.click();
		LayerSlider.generatePreview();
		LayerSlider.addColorPicker( $template.find('.ls-colorpicker') );
	},


	selectLayer: function(el) {

		// Bail out early if it's the currently active layer
		if(jQuery(el).hasClass('active') ) { return false; }

		// Hide Timeline
//		lsTimeLine.hide( jQuery(el).closest('table').find('.ls-tl') );

		// Select & build new layer
		LS_activeLayerIndex = jQuery(el).index();
		LS_activeLayerData = LS_activeSlideData.sublayers[ jQuery(el).index() ];
		jQuery(el).addClass('active').siblings().removeClass('active');
		LS_DataSource.buildLayer();

		// Set focus to input
		jQuery(el).find('input')[0].focus();

		// Store selection
		LayerSlider.saveEditingSession();
	},


	selectLayerPage: function(el) {

		// Select new tab
		jQuery(el).addClass('active').siblings().removeClass('active');

		// Show the corresponding page
		jQuery('#ls-layers .ls-sublayer-page')
			.eq( jQuery(el).index() ).addClass('active')
			.siblings().removeClass('active');

		// Store lastly selected layer page
		LS_activeLayerPageIndex = jQuery(el).index();

		// Store selection
		LayerSlider.saveEditingSession();
	},


	removeLayer: function(el) {

		if(confirm('Are you sure you want to remove this layer?')) {

			// Get the layer and its index
			var index = LS_activeLayerIndex;
			var $layer = jQuery(el).closest('li');
			var $newLayer = null;

			// Open the next or prev layer
			if($layer.next().length > 0) {
				$newLayer = $layer.next();

			} else if($layer.prev().length > 0) {
				$newLayer = $layer.prev();
			}

			// Remove layer from data source and UI
			LS_activeSlideData.sublayers.splice(index, 1);
			$layer.remove();

			// Select new layer. The .click() event will
			// maintain the active layer index and data.
			if($newLayer) {
				$newLayer.click();
				LayerSlider.reindexLayers();
			}

			// Update preview
			LayerSlider.generatePreview();
		}
	},


	hideLayer: function(el) {

		// Get layer index
		var layerIndex = jQuery(el).closest('li').index();

		// Hide layer
		if( !jQuery(el).hasClass('disabled') ){
			LS_activeSlideData.sublayers[layerIndex].skip = true;
			jQuery(el).addClass('disabled');

		// Show layer
		} else {
			LS_activeSlideData.sublayers[layerIndex].skip = false;
			jQuery(el).removeClass('disabled');
		}

		// Update preview
		LayerSlider.generatePreviewItem(layerIndex);
	},


	lockLayer: function(el) {

		// Get layer index
		var layerIndex = jQuery(el).closest('li').index();

		// Unlock layer
		if( !jQuery(el).hasClass('disabled') ){
			LS_activeSlideData.sublayers[layerIndex].locked = false;
			LayerSlider.previewItemAtIndex(layerIndex).removeClass('disabled');
			jQuery(el).addClass('disabled');

		// Lock layer
		} else {
			LS_activeSlideData.sublayers[layerIndex].locked = true;
			LayerSlider.previewItemAtIndex(layerIndex).addClass('disabled');
			jQuery(el).removeClass('disabled');
		}
	},


	addColorPicker: function(el) {
		jQuery(el).minicolors({
			opacity: true,
			changeDelay : 100,
			position: 'bottom right',
			change: function(hex, opacity) {
				LayerSlider.willGeneratePreview();
			}
		});
	},


	duplicateLayer: function(el) {

		// Duplicate layer by using jQuery.extend()
		// to make sure it's a copy instead of an
		// object reference.
		var newLayerData = jQuery.extend(true, {}, LS_activeLayerData);
			newLayerData.subtitle += ' copy';

		// Add dupe layer to data source
		LS_activeSlideData.sublayers.push( newLayerData );

		// Append new layer and name is properly
		var clone = jQuery(el).closest('li').clone().removeClass('active');
			clone.find('.ls-sublayer-title').val( newLayerData.subtitle );
			clone.appendTo('#ls-layers .ls-sublayers');

		// Update preview
		LayerSlider.generatePreview();
	},


	selectMediaType: function(el) {

		// Bail out early if the active menu item was selected again
		if( jQuery(el).hasClass('active') ) { return false; }

		// Layer and sections
		var layer = jQuery(el).closest('.ls-sublayer-page');
		var $layerItem = jQuery('.ls-sublayers li.active');
		var section = jQuery(el).data('section');
		var sections = jQuery('.ls-layer-sections', layer).children();

		// Set active class
		jQuery(el).attr('class', 'active').siblings().removeAttr('class');

		// Store selection
		LS_activeLayerData.media = section;

		// Show the corresponding sections
		sections.hide().removeClass('ls-hidden');
		jQuery('.ls-sublayer-element', layer).hide().removeClass('ls-hidden');
		jQuery('.ls-html-code p', layer).hide().removeClass('ls-hidden');
		switch(section) {
			case 'img':
				sections.eq(0).show();
				jQuery('.ls-sublayer-thumb', $layerItem).attr('class', 'ls-sublayer-thumb').html('<img src="'+LS_activeLayerData.image+'">');
				break;

			case 'text':
				sections.eq(1).show();
				layer.find('.ls-sublayer-element').show();
				jQuery('.ls-sublayer-thumb', $layerItem).attr('class', 'ls-sublayer-thumb dashicons dashicons-editor-textcolor');
				break;

			case 'html':
				sections.eq(1).show();
				jQuery('.ls-html-code p', layer).show();
				jQuery('.ls-sublayer-thumb', $layerItem).attr('class', 'ls-sublayer-thumb dashicons dashicons-editor-code');
				break;

			case 'post':
				sections.eq(1).show();
				sections.eq(2).show();
				jQuery('.ls-sublayer-thumb', $layerItem).attr('class', 'ls-sublayer-thumb dashicons dashicons-admin-post');
				break;
		}

		LayerSlider.generatePreviewItem(LS_activeLayerIndex);
	},


	selectElementType: function(el) {

		// Layer and properties
		var layer = jQuery(el).closest('.ls-sublayer-page');
		var element = jQuery(el).data('element');

		// Set active class
		jQuery(el).siblings().removeClass('active');
		jQuery(el).addClass('active');

		// Store selection
		LS_activeLayerData.type = element;
	},


	willGeneratePreview: function() {
		clearTimeout(LayerSlider.timeout);
		LayerSlider.timeout = setTimeout(function() {
				LayerSlider.generatePreview();
		}, 1000);
	},


	generatePreview: function() {

		// Get slide data
		var sliderProps = window.lsSliderData.properties;
		var slideIndex = LS_activeSlideIndex;
		var slideData = LS_activeSlideData;
		var layers = slideData.sublayers;

		// Get main elements
		var preview = jQuery('.ls-preview');
		var draggable = preview.find('.draggable');
		var $settings = jQuery('.ls-settings');

		// Get sizes
		var width = sliderProps.width;
		var height = sliderProps.height;
			height = (height.indexOf('%') != -1) ? 400 : height;
		var sub_container = sliderProps.sublayercontainer;

		// Which width?
		if(sub_container != '' && sub_container != 0) {
			width = sub_container;
		}

		// Set sizes
		preview.add(draggable).css({ width : width, height : height });
		preview.parent().css({ width : width });

		// Get post content if any
		var posts = window.lsPostsJSON;
		var postOffset = slideData.properties.post_offset;

		if(postOffset == -1) { postOffset = slideIndex; }
		var post = posts[postOffset];

		// Get backgrounds
		var bgColor = sliderProps.backgroundcolor;
			bgColor = (bgColor !== '') ? bgColor : 'transparent';

		var bgImage = sliderProps.backgroundimage;
			bgImage = (bgImage !== '') ? 'url('+bgImage+')' : 'none';

		// Set backgrounds
		preview.css({ backgroundColor : bgColor });
		preview.css({ backgroundImage : bgImage });


		// Get yourLogo
		var yourLogo = sliderProps.yourlogo;
		var yourLogoStyle = sliderProps.yourlogostyle;

		// Remove previous yourLogo
		preview.parent().find('.yourlogo').remove();

		// Set yourLogo
		if(yourLogo && yourLogo !== '') {
			var logo = jQuery('<img src="'+yourLogo+'" class="yourlogo">').prependTo( jQuery(preview).parent() );
			logo.attr('style', yourLogoStyle);

			var oL = oR = oT = oB = 'auto';

			if( logo.css('left') != 'auto' ){
				var logoLeft = logo[0].style.left;
			}
			if( logo.css('right') != 'auto' ){
				var logoRight = logo[0].style.right;
			}
			if( logo.css('top') != 'auto' ){
				var logoTop = logo[0].style.top;
			}
			if( logo.css('bottom') != 'auto' ){
				var logoBottom = logo[0].style.bottom;
			}

			if( logoLeft && logoLeft.indexOf('%') != -1 ){
				oL = width / 100 * parseInt( logoLeft ) - logo.width() / 2;
			}else{
				oL = parseInt( logoLeft );
			}

			if( logoRight && logoRight.indexOf('%') != -1 ){
				oR = width / 100 * parseInt( logoRight ) - logo.width() / 2;
			}else{
				oR = parseInt( logoRight );
			}

			if( logoTop && logoTop.indexOf('%') != -1 ){
				oT = height / 100 * parseInt( logoTop ) - logo.height() / 2;
			}else{
				oT = parseInt( logoTop );
			}

			if( logoBottom && logoBottom.indexOf('%') != -1 ){
				oB = height / 100 * parseInt( logoBottom ) - logo.height() / 2;
			}else{
				oB = parseInt( logoBottom );
			}

			logo.css({
				left : oL,
				right : oR,
				top : oT,
				bottom : oB
			});
		}

		// Get slide background
		var background = slideData.properties.background;
		if(background == '[image-url]') {
			background = post['image-url'];
			jQuery('.slide-image:eq(0) img').attr('src', post['image-url']);
		}

		// Set slide background
		if(background != '') {
			draggable.css({
				backgroundImage : 'url('+background+')',
				backgroundPosition : 'center center'
			});
		} else {
			draggable.css({
				backgroundImage : 'none'
			});
		}

		// Empty draggable
		draggable.children().remove();

		// Iterate over slides
		jQuery.each(layers, function(layerIndex, layerData) {
			LayerSlider.generatePreviewItem(layerIndex, post);
		});
	},


	willGeneratePreviewItem: function(layerIndex) {
		clearTimeout(LayerSlider.timeout);
		LayerSlider.timeout = setTimeout(function() {
				LayerSlider.generatePreviewItem(layerIndex);
		}, 300);
	},


	generatePreviewItem: function(layerIndex, post) {

		// Get post content if not passed
		if(typeof post == 'undefined') {
			var posts = window.lsPostsJSON;
			var postOffset = LS_activeSlideData.properties.post_offset;

			if(postOffset == -1) { postOffset = LS_activeSlideIndex; }
			post = posts[postOffset];
		}

		// Get main elements
		var preview = jQuery('.ls-preview');
		var draggable = preview.find('.draggable');
		var layerItem = draggable.children(':eq('+layerIndex+')');
		var layerData = LS_activeSlideData.sublayers[layerIndex];

		// Remove existing item when updating a specific layer
		if(layerItem.length) { layerItem.remove(); }

		// Hidden layer
		if(layerData.skip) {
			jQuery('<div>').appendToWithIndex(draggable, layerIndex).hide();
			return true;
		}

		var item;
		var type = layerData.type;
		var html = layerData.html;
		switch( layerData.media ) {
			case 'img': type = 'img'; break;
			case 'html': type = 'div'; break;
			case 'post': type = 'post'; break;
		}
		var id = layerData.id;
		var classes = layerData['class'];

		// Append element
		if(type == 'img') {
			var url = layerData.image;

			if(url == '[image-url]') {
				url = post['image-url'];
				jQuery('.slide-image:eq(0) img').attr('src', post['image-url']);
			}

			var tmpContent = (url !== '') ? '<img src="'+url+'">' : '<div>';
			item = jQuery(tmpContent).hide().appendToWithIndex(draggable, layerIndex);

		} else if(type == 'post') {

			var textlength = layerData.post_text_length;
			for(var key in post) {
				if(html.indexOf('['+key+']') !== -1) {
					if( (key == 'title' || key == 'content' || key == 'excerpt') && textlength > 0) {
						post[key] = post[key].substr(0, textlength);
					}
					html = html.replace('['+key+']', post[key]);
				}
			}

			// Test for html wrapper
			html = jQuery.trim(html);

			var first = html.substr(0, 1);
			var last = html.substr(html.length-1, 1);
			if(first == '<' && last == '>') {
				html = html.replace(/(\r\n|\n|\r)/gm,"");
				item = jQuery(html).appendToWithIndex(draggable, layerIndex);
			} else {
				item = jQuery('<div>').html(html).appendToWithIndex(draggable, layerIndex);
			}

		} else {
			item = jQuery('<'+type+'>').appendToWithIndex(draggable, layerIndex);
			if(html !== '') { item.html(html); }
		}


		// Locked layer
		if(layerData.locked) { item.addClass('disabled'); }

		// Get style settings
		var top = layerData.top;
		var left = layerData.left;
		var custom = layerData.style;

		// Styles
		var styles = {};
		for(var sKey in layerData.styles) {

			var cssVal = layerData.styles[sKey];

			if(cssVal === '') { continue; }
			if(cssVal.slice(-1) == ';' ) { cssVal = cssVal.substring(0, cssVal.length - 1); }

			styles[sKey] = isNumber(cssVal) ? cssVal + 'px' : cssVal;
		}

		// Apply style settings and attributes
		item.attr('style', custom).css(styles);
		item.attr('id', id).addClass(classes);
		item.css('white-space', !layerData.wordwrap ? 'nowrap' : 'normal');

		var pt = isNaN( parseInt( item.css('padding-top') ) ) ? 0 : parseInt( item.css('padding-top') );
		var pl = isNaN( parseInt( item.css('padding-left') ) ) ? 0 : parseInt( item.css('padding-left') );
		var bt = isNaN( parseInt( item.css('border-top-width') ) ) ? 0 : parseInt( item.css('border-top-width') );
		var bl = isNaN( parseInt( item.css('border-left-width') ) ) ? 0 : parseInt( item.css('border-left-width') );

		var setPositions = function(){

			// Position the element
			if(top.indexOf('%') !== -1) {
				item.css({ top : draggable.height() / 100 * parseInt( top ) - item.height() / 2 - pt - bt });
			} else {
				item.css({ top : parseInt(top) });
			}

			if(left.indexOf('%') !== -1) {
				item.css({ left : draggable.width() / 100 * parseInt( left ) - item.width() / 2 - pl - bl });
			} else {
				item.css({ left : parseInt(left) });
			}
		};

		if( item.is('img') ){

			item.load(function(){
				setPositions();
			}).attr('src',item.attr('src') );
		}else{
			setPositions();
		}

		// Z-index
		item.css({ zIndex : 10 + item.index() });

		// Add draggable
		LayerSlider.addDraggable();
	},



	previewItemAtIndex: function(index) {
		return jQuery('#ls-preview-layers').children().eq(index);
	},



	openMediaLibrary: function() {

		jQuery(document).on('click', '.ls-upload', function(e) {
			e.preventDefault();

			uploadInput = this;

			// Get library type
			var type = jQuery(this).hasClass('ls-insert-media') ? 'video,audio' : 'image';
			var multiple = jQuery(this).hasClass('ls-bulk-upload');

			// Media Library params
			var frame = wp.media({
				title : 'Pick an image to use it in LayerSlider WP',
				multiple : multiple,
				library : { type : type },
				button : { text : 'Insert' }
			});

			// Runs on select
			frame.on('select',function() {

				// Get attachment(s) data
				var attachment = frame.state().get('selection').first().toJSON();
				var attachments = frame.state().get('selection').toJSON();


				// Slide image upload
				// -------------------------------------
				if(jQuery(uploadInput).hasClass('ls-slide-image') ) {

					// Set image chooser preview
					var previewImg = !typeof attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.sizes.full.url;
					jQuery(uploadInput).find('img').attr('src', previewImg);

					// Set current layer image
					LS_activeSlideData.properties.background = attachment.url;
					LS_activeSlideData.properties.backgroundId = attachment.id;
					LS_activeSlideData.properties.backgroundThumb = previewImg;

					// Set other images
					for(c = 1; c < attachments.length; c++) {

						// Add new slide tab
						var newIndex = window.lsSliderData.layers.length + 1;
						var tab = jQuery('<a href="#">Slide #'+newIndex+'<span class="dashicons dashicons-dismiss"></span>').insertBefore('#ls-add-layer');

						// Name new slide properly
						LayerSlider.reindexSlides();

						// Get preview image url
						var previewImg = !typeof attachments[c].sizes.thumbnail ? attachments[c].sizes.thumbnail.url : attachments[c].sizes.full.url;

						// Build new slide
						var newSlideData = jQuery.extend(true, {}, LS_DataSource.getDefaultSlideData());
							newSlideData.background = attachments[c].url;
							newSlideData.backgroundId = attachments[c].id;
							newSlideData.backgroundThumb = previewImg;

						// Add a layer
						var newLayerData = jQuery.extend(true, {}, LS_DataSource.getDefaultLayerData());
							newLayerData.subtitle = 'Layer #1';

						// Add new layer
						window.lsSliderData.layers.push({
							properties: newSlideData,
							sublayers: [newLayerData]
						});
					}


				// Slide thumbnail upload
				// -------------------------------------
				} else if(jQuery(uploadInput).hasClass('ls-slide-thumbnail') ) {

					// Set image chooser preview
					var previewImg = !typeof attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.sizes.full.url;
					jQuery(uploadInput).find('img').attr('src', previewImg);

					// Set current layer image
					LS_activeSlideData.properties.thumbnail = attachment.url;
					LS_activeSlideData.properties.thumbnailId = attachment.id;
					LS_activeSlideData.properties.thumbnailThumb = previewImg;


				// Layer image upload
				// -------------------------------------
				} else if(jQuery(uploadInput).hasClass('ls-layer-image') ) {

					// Set image chooser preview
					var previewImg = !typeof attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.sizes.full.url;
					jQuery(uploadInput).find('img').attr('src', previewImg);

					// Set current layer image
					LS_activeLayerData.image = attachment.url;
					LS_activeLayerData.imageId = attachment.id;
					LS_activeLayerData.imageThumb = previewImg;

					// Set other images
					for(c = 1; c < attachments.length; c++) {

						// Get preview image url
						var previewImg = !typeof attachments[c].sizes.thumbnail ? attachments[c].sizes.thumbnail.url : attachments[c].sizes.full.url;

						// Build new layer
						var newLayerData = jQuery.extend(true, {}, LS_DataSource.getDefaultLayerData());
							newLayerData.image = attachments[c].url;
							newLayerData.imageId = attachments[c].id;
							newLayerData.imageThumb = previewImg;
							newLayerData.subtitle = 'Layer #' + (LS_activeSlideData.sublayers.length + 1);

						// Add new layer
						LS_activeSlideData.sublayers.push(newLayerData);
					}

					LS_DataSource.buildLayersList();


				// Global slider background
				// -------------------------------------
				} else if( jQuery(uploadInput).hasClass('ls-global-background') ) {

					// Set image chooser preview
					var previewImg = !typeof attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.sizes.full.url;
					jQuery(uploadInput).find('img').attr('src', previewImg);

					// Store changes and update the preview
					window.lsSliderData.properties.backgroundimage = attachment.url;
					window.lsSliderData.properties.backgroundimageId = attachment.id;


				// YourLogo
				// -------------------------------------
				} else if( jQuery(uploadInput).hasClass('ls-yourlogo-upload') ) {

					// Set image chooser preview
					var previewImg = !typeof attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.sizes.full.url;
					jQuery(uploadInput).find('img').attr('src', previewImg);

					// Store changes and update the preview
					window.lsSliderData.properties.yourlogo = attachment.url;
					window.lsSliderData.properties.yourlogoId = attachment.id;


				// Multimedia HTML
				} else if( jQuery(uploadInput).hasClass('ls-insert-media')) {

					var hasVideo = false;
					var hasAudio = false;

					var videos = [];
					var audios = [];

					var mediaHTML = '';

					// Iterate over selected items
					for(c = 0; c < attachments.length; c++) {
						var url = '/' + attachments[c].url.split('/').slice(3).join('/');
						if(attachments[c].type === 'video') {
							hasVideo = true;
							videos.push({ url: url, mime: attachment.mime });

						} else if(attachments[c].type === 'audio') {
							hasAudio = true;
							audios.push({ url: url, mime: attachment.mime });
						}
					}

					// Insert multimedia
					if(hasVideo) {
						mediaHTML += '<video width="320" height="240" preload="metadata" controls>\r\n';
						for(c = 0; c < videos.length; c++) {
							mediaHTML += '\t<source src="'+videos[c].url+'" type="'+videos[c].mime+'">\r\n';
						}
						mediaHTML += '</video>';
					}

					if(hasAudio) {

						if(hasVideo) { mediaHTML += '\r\n\r\n'; }

						mediaHTML += '<audio preload="metadata" nocontrols>\r\n';
						for(c = 0; c < audios.length; c++) {
							mediaHTML += '\t<source src="'+audios[c].url+'" type="'+audios[c].mime+'">\r\n';
						}
						mediaHTML += '</audio>';
					}

					jQuery(uploadInput).parent().prev().val(mediaHTML);

				// Image with input field
				} else {
					jQuery(uploadInput).val( attachment['url'] );
					if(jQuery(uploadInput).is('input[name="image"]')) {
						jQuery(uploadInput).prev().attr('src', attachment['url']);
					}
				}

				// Generate preview
				LayerSlider.generatePreview();
			});

			// Open ML
			frame.open();
		});
	},


	addLayerSortables: function() {

		// Bind sortable function
		jQuery('.ls-sublayer-sortable').sortable({

			handle : 'span.ls-sublayer-sortable-handle',
			containment : 'parent',
			tolerance : 'pointer',
			delay: 150,
			axis : 'y',

			start: function() {
				LayerSlider.dragIndex = jQuery('.ui-sortable-placeholder').index() - 1;
			},

			change: function() {
				jQuery('.ui-sortable-helper').addClass('moving');
			},

			stop: function(event, ui) {

				// Get indexes
				var oldIndex = LayerSlider.dragIndex;
				var index = jQuery('.moving').removeClass('moving').index();

				if( index > -1 ){
					moveArrayItem(LS_activeSlideData.sublayers, oldIndex, index);
				}

				// Reindex layers
				LayerSlider.reindexLayers();
				LayerSlider.generatePreview();
			}
		});
	},


	addSlideSortables: function() {

		jQuery('#ls-layer-tabs').sortable({

			containment: 'parent',
			tolerance: 'pointer',
			distance: 10,
			items: 'a:not(.unsortable)',

			start: function() {
				LayerSlider.dragIndex = jQuery('.ui-sortable-placeholder').index() - 1;
			},

			change: function() {
				jQuery('.ui-sortable-helper').addClass('moving');
			},

			stop: function(event, ui) {

				// Get indexes
				var oldIndex = LayerSlider.dragIndex;
				var index = jQuery('.moving').removeClass('moving').index();

				if( index > -1 ){
					moveArrayItem(window.lsSliderData.layers, oldIndex, index);
				}

				if(oldIndex == LS_activeSlideIndex) {
					LS_activeSlideIndex = index;
				}

				// Reindex slides
				LayerSlider.reindexSlides();
			}
		});
	},


	addDraggable: function() {

		// Add dragables and update settings
		// while and after dragging
		jQuery('.draggable').children().draggable({
			drag: function() { LayerSlider.dragging(); },
			stop: function() { LayerSlider.dragging(); },
			cancel: '.disabled'
		});
	},


	dragging: function() {

		// Get positions
		var top = parseInt(jQuery('.ui-draggable-dragging').position().top);
		var left = parseInt(jQuery('.ui-draggable-dragging').position().left);

		// Get index
		var wrapper = jQuery('.ui-draggable-dragging').closest('.ls-layer-box');
		var index = jQuery('.ui-draggable-dragging').index();

		// If it's the active layer, update input fields
		if(index == LS_activeLayerIndex) {
			wrapper.find('input[name="top"]').val(top + 'px');
			wrapper.find('input[name="left"]').val(left + 'px');
		}

		// Maintain changes in data source
		LS_activeSlideData.sublayers[index].top = top+'px';
		LS_activeSlideData.sublayers[index].left = left+'px';
	},


	selectDragElement: function(el) {
		jQuery('.ls-sublayers > li').eq( jQuery(el).index() ).click();
	},


	listPreviewItems: function(e) {

		// Get preview area
		var $preview = jQuery('#ls-preview-layers');

		// Var to hold intersecting elements
		var items = [];

		// Get mouse position
		var mt = e.pageY;
		var ml = e.pageX;

		// Loop through layers list
		$preview.children().each(function(layerIndex) {

			// Get layer item and data
			var $layer = jQuery(this);
			var layerData = LS_activeSlideData.sublayers[ $layer.index() ];

			// Get layer positions and dimensions
			var t = $layer.offset().top;
			var l = $layer.offset().left;
			var w = $layer.outerWidth();
			var h = $layer.outerHeight();

			if( (mt > t && mt < t+h) && (ml > l && ml < l+w) ) {
				items.push({ index: layerIndex, data: layerData });
			}
		});

		// Create list holder
		if(items.length > 1) {

			// Remove previous list (if any)
			jQuery('.ls-preview-item-list').remove();

			// Create list
			var $list = jQuery('<ul class="ls-preview-item-list">').prependTo('body');
				$list.hide().css({ top: mt, left: ml }).fadeIn(100);

			// Close event
			jQuery('body').one('click', function() {
				jQuery('.ls-preview-item-list').animate({ opacity: 0 }, 200, function() {
					jQuery(this).remove();
				})
			});

			// Loop through intersecting elements (if any)
			jQuery.each(items, function(idx, data) {

				var layerIndex = data.index;
				var layerData = data.data;

				var $li = jQuery('<li><div></div><span>'+layerData.subtitle+'</span></li>').appendTo($list);
					$li.data('layerIndex', layerIndex);

				switch(layerData.media) {
					case 'img':
						jQuery('div', $li).html('<img src="'+layerData.imageThumb+'">');
						break;

					case 'html':
						jQuery('div', $li).addClass('dashicons dashicons-editor-code');
						break;

					case 'post':
						jQuery('div', $li).addClass('dashicons dashicons-admin-post');
						break;

					default:
						jQuery('div', $li).addClass('dashicons dashicons-editor-textcolor');
						break;
				}
			});
		}
	},


	highlightPreviewItem: function(el) {

		// Get layer related data
		var layerIndex = jQuery(el).data('layerIndex');
		var $previewItem = jQuery('#ls-preview-layers').children().eq(layerIndex);


		// Highlight item
		$previewItem.addClass('highlighted').siblings().css('opacity', 0.5);

	},


	selectPreviewItem: function(el) {

		// Select layer
		var layerIndex = jQuery(el).data('layerIndex');
		jQuery('.ls-sublayers li').eq(layerIndex).click();

		// Remove layer highlights (if any)
		jQuery('#ls-preview-layers').children().removeClass('highlighted').css('opacity', 1);
	},


	reindexLayers: function(el) {

		// Reindex default layers' title
		jQuery('#ls-layers .ls-sublayers > li').each(function(index) {
			var layerTitle = jQuery(this).find('.ls-sublayer-title').val();
			if( layerTitle.indexOf('Layer') != -1 && layerTitle.indexOf('copy') == -1) {
				jQuery(this).find('.ls-sublayer-title').val('Layer #' + (index + 1) );
			}
		});
	},


	reindexSlides: function() {

		jQuery('#ls-layer-tabs a:not(.unsortable)').each(function(index) {
			jQuery(this).html('Slide #' + (index + 1) + '<span class="dashicons dashicons-dismiss"></span>');
		});
	},


	play: function( ) {

		// Get slider settings and preview container
		var sliderProps = window.lsSliderData.properties;
		var layerslider = jQuery('#ls-layers .ls-real-time-preview');

		// Stop
		if(layerslider.children().length > 0) {
			LayerSlider.stop();
			return true;
		}

		// Slider settings
		var width = sliderProps.width;
		var height = sliderProps.height;
		var posts = window.lsPostsJSON;

		// Switch between preview and editor
		layerslider.show();
		layerslider = jQuery('<div class="layerslider">').appendTo(layerslider);
		jQuery('#ls-layers .ls-preview').hide();
		jQuery('#ls-layers .ls-preview-button').html('Exit Preview').addClass('playing');

		// Apply global settings
		layerslider.css({ width: width, height : height });

		// Add backgrounds
		var backgroundColor = sliderProps.backgroundcolor;
		var backgroundImage = sliderProps.backgroundimage;
		if(backgroundColor != '') {
			layerslider.css({ backgroundColor : backgroundColor }); }

		if(backgroundImage != '') {
			 layerslider.css({ backgroundImage : 'url('+backgroundImage+')' }); }



		// Iterate over the slides
		jQuery.each(window.lsSliderData.layers, function(slideIndex, slideData) {

			// Slide data
			var slideProps = slideData.properties;
			var layers = slideData.sublayers;

			// Get post content if any
			var postOffset = slideProps.post_offset;
			if(postOffset == -1) { postOffset = slideIndex; }
			var post = posts[postOffset];

			// Slide properties
			var layerprops = '';
				layerprops += 'slidedelay:'+slideProps.slidedelay+';';
				layerprops += 'timeshift:'+slideProps.timeshift+';';

			// Build the Slide
			var layer = jQuery('<div class="ls-layer">').appendTo(layerslider);
				layer.attr('data-ls', layerprops);

			// Get background
			var background = slideProps.background;
			if(background === '[image-url]') {
				background = post['image-url'];
			}

			// Add background
			if(background != '') {
				jQuery('<img src="'+background+'" class="ls-bg">').appendTo(layer);
			}

			// Get selected transitions
			var tr2d = slideProps['2d_transitions'];
			var tr3d = slideProps['3d_transitions'];
			var tr2dcustom = slideProps.custom_2d_transitions;
			var tr3dcustom = slideProps.custom_3d_transitions;

			// Apply transitions
			if( tr2d == '' && tr3d == '' && tr2dcustom == '' && tr3dcustom == '' ) {
				layer.attr('data-ls', layer.attr('data-ls') + ' transition2d: all; ');
				layer.attr('data-ls', layer.attr('data-ls') + ' transition3d: all; ');
			} else {
				if(tr2d != '') layer.attr('data-ls', layer.attr('data-ls') + ' transition2d: '+tr2d+'; ');
				if(tr3d != '') layer.attr('data-ls', layer.attr('data-ls') + ' transition3d: '+tr3d+'; ');
				if(tr2dcustom != '') layer.attr('data-ls', layer.attr('data-ls') + ' customtransition2d: '+tr2dcustom+'; ');
				if(tr3dcustom != '') layer.attr('data-ls', layer.attr('data-ls') + ' customtransition3d: '+tr3dcustom+'; ');
			}


			// Iterate over layers
			jQuery.each(layers, function(layerKey, layerData) {

				// Skip sublayer?
				if( !!layerData.skip ) {
					jQuery('<div>').appendTo(layer)
					return true;
				}

				// Gather sublayer data
				var type = layerData.type;
				switch( layerData.media ) {
					case 'img': type = 'img'; break;
					case 'html': type = 'div'; break;
					case 'post': type = 'post'; break;
				}

				var image = layerData.image;
				var html = layerData.html;
				var style = layerData.style;
				var top = layerData.top;
				var left = layerData.left;
				var url = layerData.url;
				var id = layerData.id;
				var classes = layerData['class'];

				// Sublayer properties
				var sublayerprops = '';
				jQuery.each(layerData.transition, function(trKey, trVal) {
					sublayerprops += trKey+':'+trVal+';';
				});

				// Styles
				var styles = {};
				jQuery.each(layerData.styles, function(cssProp, cssVal) {
					if(cssVal.slice(-1) == ';' ) {
						cssVal = cssVal.substring(0, cssVal.length - 1);
					}

					styles[cssProp] = isNumber(cssVal) ? cssVal + 'px' : cssVal;
				});

				// Build the sublayer
				var sublayer;
				if(type == 'img') {
					if(image == '') { return true; }
					if(image == '[image-url]') { image = post['image-url']; }

					sublayer = jQuery('<img src="'+image+'" class="ls-s">').appendTo(layer);

				} else if(type == 'post') {

					// Parse post placeholders
					var textlength = layerData.post_text_length;
					for(var key in post) {
						if(html.indexOf('['+key+']') !== -1) {
							if( (key == 'title' || key == 'content' || key == 'excerpt') && textlength > 0) {
								post[key] = post[key].substr(0, textlength);
							}
							html = html.replace('['+key+']', post[key]);
						}
					}

					// Test html
					html = jQuery.trim(html);
					var first = html.substr(0, 1);
					var last = html.substr(html.length-1, 1);
					if(first == '<' && last == '>') {
						html = html.replace(/(\r\n|\n|\r)/gm,"");
						sublayer = jQuery(html).appendTo(layer).addClass('ls-s');
					} else {
						sublayer = jQuery('<div>').appendTo(layer).html(html).addClass('ls-s');
					}

				} else {
					sublayer = jQuery('<'+type+'>').appendTo(layer).html(html).addClass('ls-s');
				}

				// Apply styles and attributes
				sublayer.attr('id', id).attr('style', style).addClass(classes)
				sublayer.css(styles);
				sublayer.css('white-space', !layerData.wordwrap ? 'nowrap' : 'normal');

				// Position the element
				if(top.indexOf('%') != -1) { sublayer.css({ top : top });
					} else { sublayer.css({ top : parseInt(top) }); }

				if(left.indexOf('%') != -1) { sublayer.css({ left : left });
					} else { sublayer.css({ left : parseInt(left) }); }

				if(url != '' && url.match(/^\#[0-9]/)) {
					sublayer.addClass('ls-linkto-' + url.substr(1));
				}

				sublayer.attr('data-ls', sublayerprops);
			});
		});

		// Get slider settings
		var autoPlayVideos = sliderProps.autoplayvideos;
			autoPlayVideos = autoPlayVideos ? true : false;

		// Init layerslider
		jQuery(layerslider).layerSlider({
			width: width,
			height: height,
			responsive: false,
			skin: 'preview',
			skinsPath: pluginPath + 'skins/',
			animateFirstLayer: true,
			firstLayer: LS_activeSlideIndex + 1,
			autoStart: false,
			pauseOnHover: false,
			autoPlayVideos: autoPlayVideos,
			cbInit: function(){
				lsTimeLine.create();
			},
			cbTimeLineStart: function(g,d){
				if( g.nextLayerIndex == jQuery('#ls-layer-tabs .active').index() + 1 ){
					lsTimeLine.start(d);
				}
			}

		});

		jQuery(layerslider).layerSlider('start');
	},


	stop: function() {

		// Get layerslider contaier
		var layersliders = jQuery('#ls-layers .ls-real-time-preview');

		// Stop the preview if any
		if(layersliders.children().length > 0) {

			// Show the editor
			jQuery('#ls-layers .ls-preview').show();

			// Stop LayerSlider and empty the preview contents
			layersliders.find('.ls-container').layerSlider('stop');
			layersliders.empty().hide();

			// Rewrote the Preview button text
			jQuery('#ls-layers .ls-preview-button').text('Enter Preview').removeClass('playing');
		}

		// Remove Timeline slider
		lsTimeLine.remove();
	},


	openTransitionGallery: function() {

		// Create overlay
		jQuery('body').prepend( jQuery('<div>', { 'class' : 'ls-overlay'}));

		// Load transition selector modal window
		jQuery(jQuery('#tmpl-ls-transition-modal').html()).prependTo('body');

		// Append transitions
		LayerSlider.appendTransition('', '2d_transitions', layerSliderTransitions['t2d']);
		LayerSlider.appendTransition('', '3d_transitions', layerSliderTransitions['t3d']);

		// Append custom transitions
		if(typeof layerSliderCustomTransitions != "undefined") {
			if(layerSliderCustomTransitions['t2d'].length) {
				LayerSlider.appendTransition('Custom 2D transitions', 'custom_2d_transitions', layerSliderCustomTransitions['t2d']);
			}
			if(layerSliderCustomTransitions['t3d'].length) {
				LayerSlider.appendTransition('Custom 3D transitions', 'custom_3d_transitions', layerSliderCustomTransitions['t3d']);
			}
		}

		// Select proper tab
		jQuery('#ls-transition-window .filters li.active').click();

		// Close event
		jQuery(document).one('click', '.ls-overlay', function() {
			LayerSlider.closeTransitionGallery();
		});


		jQuery('#ls-transition-window').show();
	},


	closeTransitionGallery: function() {

		jQuery('#ls-transition-window').remove();
		jQuery('.ls-overlay').remove();
	},


	appendTransition: function(title, tbodyclass, transitions) {

		// Append new tbody
		var tbody = jQuery('<tbody>').data('tr-type', tbodyclass).appendTo('#ls-transition-window table');

		// Get checked transitions
		var checked = LS_activeSlideData.properties[tbodyclass];
			checked = (checked != '') ? checked.split(',') : [];

		if(title != '') {
			jQuery('<tr>').appendTo(tbody).append('<th colspan="4">'+title+'</th>');
		}

		for(c = 0; c < transitions.length; c+=2) {

			// Append new table row
			var tr = jQuery('<tr>').appendTo(tbody)
				.append( jQuery('<td class="c"></td>') )
				.append( jQuery('<td></td>') )
				.append( jQuery('<td class="c"></td>') )
				.append( jQuery('<td></td>')
			);

			// Append transition col 1 & 2
			tr.children().eq(0).append('<i>'+(c+1)+'</i><i class="dashicons dashicons-yes"></i>');
			tr.children().eq(1).append( jQuery('<a>', { 'href' : '#', 'html' : transitions[c]['name']+'', 'data-key' : (c+1) } ) )
			if(transitions.length > (c+1)) {
				tr.children().eq(2).append('<i>'+(c+2)+'</i><i class="dashicons dashicons-yes"></i>');
				tr.children().eq(3).append( jQuery('<a>', { 'href' : '#', 'html' : transitions[(c+1)]['name']+'', 'data-key' : (c+2) } ) );
			}

			// Check transitions
			if(checked.indexOf(''+(c+1)+'') != -1 || checked == 'all') {
				tr.children().eq(0).addClass('added');
				tr.children().eq(1).addClass('added');
			}

			if((checked.indexOf(''+(c+2)+'') != -1 || checked == 'all') ) {
				tr.children().eq(2).addClass('added');
				tr.children().eq(3).addClass('added');
			}
		}
	},


	selectAllTransition: function(index, check) {

		// Get checkbox and transition type
		var checkbox = jQuery('#ls-transition-window header i:last');
		var cat = jQuery('#ls-transition-window tbody').eq(index).data('tr-type');

		if(typeof check != undefined && check == true) {

			jQuery('#ls-transition-window tbody').eq(index).find('td').addClass('added');
			checkbox.attr('class', 'on').text('Deselect all');
			LS_activeSlideData.properties[cat] = 'all';

		} else {

			jQuery('#ls-transition-window tbody').eq(index).find('td').removeClass('added');
			checkbox.attr('class', 'off').text('Select all');
			LS_activeSlideData.properties[cat] = '';
		}
	},

	toggleTransition: function(el) {

		// Toggle addded class
		if(jQuery(el).parent().hasClass('added')) {
			jQuery(el).parent().removeClass('added').prev().removeClass('added');

		} else {
			jQuery(el).parent().addClass('added').prev().addClass('added');
		}

		// Get transitions
		var trs = jQuery(el).closest('tbody').find('td');

		// All selected
		if(trs.filter('.c.added').length == trs.filter('.c').length) {

			LayerSlider.selectAllTransition( jQuery(el).closest('tbody').index(), true );
			return;

		// Uncheck select all
		} else {

			// Check the checkbox
			jQuery('#ls-transition-window header i:last').attr('class', 'off').text('Select all');
		}

		// Get category
		var cat = jQuery(el).closest('tbody').data('tr-type');

		// Gather checked selected transitions
		var checked = [];
		trs.filter('.added').find('a').each(function() {
			checked.push( jQuery(this).data('key') );
		});

		// Set data
		LS_activeSlideData.properties[cat] = checked.join(',');
	},


	showTransition: function(el) {

		// Get transition index
		var index = parseInt(jQuery(el).data('key')) - 1;

		// Create popup
		jQuery('body').prepend( jQuery('<div>', { 'class' : 'ls-popup' })
			.append( jQuery('<div>', { 'class' : 'inner ls-transition-preview' }))
		);

		// Get popup
		var popup = jQuery('.ls-popup');

		// Get viewport dimensions
		var v_w = jQuery(window).width();

		// Get element dimensions
		var e_w = jQuery(el).width();

		// Get element position
		var e_l = jQuery(el).offset().left;
		var e_t = jQuery(el).offset().top;

		// Get toolip dimensions
		var t_w = popup.outerWidth();
		var t_h = popup.outerHeight();

		// Position tooltip
		popup.css({ top : e_t - t_h - 14, left : e_l - (t_w - e_w) / 2  });

		// Fix top
		if(popup.offset().top < 20) {
			popup.css('top', e_t + 26);
		}

		// Fix left
		if(popup.offset().left < 20) {
			popup.css('left', 20);
		}

		// Get transition class
		var trclass = jQuery(el).closest('tbody').data('tr-type');

		// Built-in 3D
		if(trclass == '3d_transitions') {
			var trtype = '3d';
			var trObj = layerSliderTransitions['t'+trtype+''][index];

		// Built-in 2D
		} else if(trclass == '2d_transitions') {
			var trtype = '2d';
			var trObj = layerSliderTransitions['t'+trtype+''][index];

		// Custom 3D
		} else if(trclass == 'custom_3d_transitions') {
			var trtype = '3d';
			var trObj = layerSliderCustomTransitions['t'+trtype+''][index];

		// Custom 3D
		} else if(trclass == 'custom_2d_transitions') {
			var trtype = '2d';
			var trObj = layerSliderCustomTransitions['t'+trtype+''][index];
		}

		// Init transition
		popup.find('.inner').lsTransitionPreview({
			transitionType : trtype,
			transitionObject : trObj,
			imgPath : lsTrImgPath,
			skinsPath: lsTrImgPath+'../skins/',
			delay : 100
		});
	},


	hideTransition: function(el) {

		// Stop transition
		jQuery('.ls-popup').find('.inner').lsTransitionPreview('stop');

		// Remove transition
		jQuery('.ls-popup').remove();
	},


	save: function(el) {

		// Get the slider data
		var sliderData = jQuery.extend(true, {}, window.lsSliderData);

		// Temporary disable submit button
		jQuery('.ls-publish').addClass('saving').find('button').text('Saving ...').attr('disabled', true);

		// Serialize slider settings to prevent jQuery form converting form data
		sliderData.properties = JSON.stringify(sliderData.properties);

		// Iterate over the slides and encode them
		// to workaround PHP's array size limitation
		jQuery.each(sliderData.layers, function(slideIndex, slideData) {
			jQuery.each(slideData.sublayers, function(layerIndex, layerData) {
				slideData.sublayers[layerIndex].transition = JSON.stringify(layerData.transition);
				slideData.sublayers[layerIndex].styles = JSON.stringify(layerData.styles);
			});

			sliderData.layers[slideIndex] = JSON.stringify(slideData);
		});

		// Save slider
		jQuery.ajax({
			type: 'post', url: ajaxurl, dataType: 'text',
			data: {
				action: 'ls_save_slider',
				id: LS_sliderID,
				sliderData: sliderData
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('It seems there is a server issue that prevented LayerSlider from saving your work. Please, try to temporary disable themes/plugins, or contact with your hosting provider. Your HTTP server thrown the following error: \n\n' + errorThrown);
			},
			complete: function(data) {

				// Button feedback
				jQuery('.ls-publish').removeClass('saving').addClass('saved').find('button').text('Saved');
				setTimeout(function() {
					jQuery('.ls-publish').removeClass('saved').find('button').text('Save changes').attr('disabled', false);
				}, 2000);
			}
		});
	},
};



var lsTimeLine = {

	opened : false,

	init: function(){

		jQuery(document).on('click', '.ls-timeline-switch li', function(e) {
			e.preventDefault();

			// Bail out early if it's the active menu item
			if(jQuery(this).hasClass('active')) { return false; }

			var $item = jQuery(this);
			var t = jQuery('.ls-sublayers');
			var tl = jQuery('.ls-sublayers').find('.ls-tl');

			if( $item.index() == 1 ){
				lsTimeLine.show(t,tl);
			} else {
				lsTimeLine.hide(tl);
			}
		});
	},

	show: function(t,tl){

		jQuery('.ls-timeline-switch li:first-child').removeClass('active');
		jQuery('.ls-timeline-switch li:last-child').addClass('active');
		jQuery('.ls-sublayer-pages-wrapper, .ls-add-sublayer').hide();
		jQuery('.ls-sublayers').addClass('ls-timeline');

		if( t.find('li').length != -1 ){

			if( t.find('li.active').length != -1 ){
				this.opened = t.find('li.active');
			} else {
				this.opened = false;
			}

			// Adjust the width of layer's title field
			jQuery('.ls-sublayer-title').outerWidth(138);

			var osd = LS_activeSlideData.properties.slidedelay;

			tl.addClass('ls-tl-active');
			tl.each(function(layerIndex){

				var layerData = LS_activeSlideData.sublayers[layerIndex].transition;
				var slidedelay = osd;

				var percent = slidedelay / 100;
				var tableWidth = '100%';

				var tlVal = [];
				var tlName = ['delayin','durationin','showuntil','durationout'];
				var tlTTName = ['Delay in','Duration in','Show until','Duration out'];

				tlVal.push( parseInt( layerData.delayin ) );
				tlVal.push( parseInt( layerData.durationin ) );
				tlVal.push( parseInt( layerData.showuntil ) );
				tlVal.push( parseInt( layerData.durationout ) );

				var osu = tlVal[2];
				if( tlVal[2] === 0 ){
					tlVal[3] = 0;
					tlVal[2] = slidedelay - ( tlVal[0] + tlVal[1] ) > 0 ? slidedelay - ( tlVal[0] + tlVal[1] ) : 0;
				}

				if( slidedelay > tlVal[0] + tlVal[1] + tlVal[2] + tlVal[3] ){
					tableWidth = ( tlVal[0] + tlVal[1] + tlVal[2] + tlVal[3] ) / percent + '%';
				}

				var allPercent = 0;

				for(var x = 0; x<tlVal.length; x++ ){
					slidedelay -= tlVal[x];
					var el = jQuery(this).find('.ls-tl-'+tlName[x]);
					var w = tlVal[x] / percent + '%';
					if( slidedelay < 0 ){
						w = ( tlVal[x] + slidedelay ) / percent + '%';
						el.css('width',w);
						el.attr('data-help', tlTTName[x] + ': ' + (tlVal[x] + slidedelay) + ' ms (original: ' + tlVal[x] + ' ms but the current Slide delay is ' + osd + ' ms, so this slide will change before)');
						break;
					}else{
						el.css('width',w);
						if( x == 2 && osu == 0 ){
							el.attr('data-help', 'This layer will be shown until the slide change and it will be animate out with the other layers.');
						}else{
							el.attr('data-help', tlTTName[x] + ': ' + tlVal[x] + ' ms');
						}
					}
					allPercent += parseFloat(w);
				}

				jQuery(this).find('.ls-tl-helper').css( 'width', 100-allPercent + '%' );

			});

			// create ruler

			if( !t.find('.ls-tl-rulers').length ){
				var h = t.outerHeight();
				jQuery('<table class="ls-tl-rulers"><tr><td></td></tr></table>').prependTo( t.find('.ls-tl:eq(0)') );
				var tr = jQuery('<div>').addClass('ls-tl-ruler').appendTo( t.find('.ls-tl-rulers td')  );
				var rn = osd%1000 === 0 ? osd/1000 + 1 : parseInt( osd/1000 ) + 2;
				var l, d, ms;

				for( var r=0; r<rn;r++ ){

					l = r === rn-1 ? 100 + '%' : ( r * 1000 ) / ( osd / 100 ) + '%';
					ms = r === rn-1 ? osd : r*1000;

					d = jQuery('<div>').css({
						top: -5,
						left: l,
						height: h - 14
					}).appendTo( tr );
					jQuery('<p>').text( ms+' ms' ).appendTo( d );
				}
			}
		}
	},

	hide: function(tl){

		jQuery('.ls-timeline-switch li:first-child').addClass('active');
		jQuery('.ls-timeline-switch li:last-child').removeClass('active');
		jQuery('.ls-sublayer-pages-wrapper, .ls-add-sublayer').show();
		jQuery('.ls-sublayers').removeClass('ls-timeline');

		tl.removeClass('ls-tl-active');

		// Adjust the width of layer's title field
		jQuery('.ls-sublayer-title').width(250);

		jQuery('.ls-tl-rulers').remove();

		this.opened = false;
	},

	create: function(){
		var tls = jQuery('<div>').addClass('ls-tl-slider').appendTo('.ls-tl-active');
//		var timer = jQuery('<div>').addClass('ls-tl-timer').appendTo('.ls-tl-slider:eq(0)');
	},

	start: function(d){
		var slidedelay = parseInt( jQuery('#ls-layers .active').find('input[name="slidedelay"]').val() );
		var tls = jQuery('.ls-tl-slider');
		var w = jQuery('.ls-tl-active:eq(0)').width();
		var d = d ? d : 0;

		tls.css({
			width: 0
		}).delay(d).animate({
			width : w
		}, slidedelay, 'linear' );
/*
		var timer;

		var t = function(){
			timer = parseInt( (tls.eq(0).width() / w * slidedelay)/50 ) * 50 + 50;
			setTimeout(function(){
				console.log('s')
				jQuery('.ls-tl-timer').text( timer + ' ms');
				if( timer < slidedelay ){
					t();
				}else{
					jQuery('.ls-tl-timer').text( slidedelay + ' ms');
				}
			},50);
		};

		t();
*/
	},

	remove: function(){
		jQuery('.ls-tl-slider').stop().remove();
	}
};


var LS_PostOptions = {

	init: function() {

		jQuery('#ls-layers').on('click', '.ls-configure-posts', function(e) {
			e.preventDefault(); LS_PostOptions.open(this);
		});

		jQuery('.ls-configure-posts-modal .header a').click(function(e) {
			e.preventDefault(); LS_PostOptions.close();
		});

		jQuery('#ls-post-options select:not(.ls-post-taxonomy, .post_offset)').change(function() {
			window.lsSliderData.properties[ jQuery(this).attr('name') ] = jQuery(this).val();
			LS_PostOptions.change(this);
		});

		jQuery('#ls-post-options select.offset').change(function() {
			LS_activeSlideData.properties.post_offset = jQuery(this).val();
			LayerSlider.willGeneratePreview();
		});

		jQuery('#ls-post-options select.ls-post-taxonomy').change(function() {
			window.lsSliderData.properties.post_taxonomy = jQuery(this).val();
			LS_PostOptions.getTaxonoies(this);
		});

		jQuery('#ls-layers').on('click', '.ls-post-placeholders li', function() {
			LS_PostOptions.insertPlaceholder(this);
		});
	},


	open: function(el) {

		// Create overlay
		jQuery('body').prepend(jQuery('<div>', { 'class' : 'ls-overlay'}));

		// Get slide's post offset
		var offset = parseInt(LS_activeSlideData.properties.post_offset) + 1;

		// Show modal window
		var modal = jQuery('#ls-post-options').show();
			modal.find('select.offset option').prop('selected', false).eq(offset).prop('selected', true);

		// Close event
		jQuery(document).one('click', '.ls-overlay', function() {
			LS_PostOptions.close();
		});

		// First open?
		if(modal.find('.ls-post-previews ul').children().length === 0) {
			LS_PostOptions.change( modal.find('select')[0] );
		}
	},


	getTaxonoies: function(select) {

		var target = jQuery(select).next().empty();

		if(jQuery(select).val() == 0) {
			LS_PostOptions.change(select);

		} else {

			jQuery.post(ajaxurl, jQuery.param({ action : 'ls_get_taxonomies', taxonomy : jQuery(select).val() }), function(data) {
				var data = jQuery.parseJSON(data);
				for(c = 0; c < data.length; c++) {
					target.append( jQuery('<option>', { 'value' : data[c]['term_id'], 'text' : data[c]['name'] }));
				}
			});
		}
	},


	change: function(el) {

		// Get options
		var items = {};
		jQuery('#ls-post-options').find('select').each(function() {
			items[ jQuery(this).data('param') ] = jQuery(this).val();
		});

		jQuery.post(ajaxurl, jQuery.param({ action: 'ls_get_post_details', params : items }), function(data) {

			// Handle data
			var parsed = jQuery.parseJSON(data);
			window.lsPostsJSON = parsed;

			// Update preview
			LayerSlider.willGeneratePreview();
			LS_PostOptions.update(el, parsed );
		});
	},


	update: function(el, data) {

		var preview = jQuery('#ls-post-options').find('.ls-post-previews ul').empty();

		if(data.length === 0) {
			preview.append( jQuery('<li>')
				.append( jQuery('<h4>', { 'text' : 'No posts were found with the current filters.' }) )
			);

		} else {
			for(c = 0; c < data.length; c++) {
				preview.append( jQuery('<li>')
					.append( jQuery('<span>', { 'class' : 'counter', 'text' : ''+(c+1)+'. ' }))
					.append( jQuery('<img>', { 'src' : data[c]['thumbnail'] } ))
					.append( jQuery('<h3>', { 'html' : data[c]['title'] } ))
					.append( jQuery('<p>', { 'html' : data[c]['content'] } ))
					.append( jQuery('<span>', { 'class' : 'author', 'text' : data[c]['date-published']+' by '+data[c]['author'] } ))
				);
			}
		}
	},


	close: function() {
		jQuery('#ls-post-options').hide();
		jQuery('.ls-overlay').remove();
	},


	insertPlaceholder: function(el) {

		var element = jQuery(el).closest('.ls-sublayer-page').find('textarea[name="html"]')[0];
		var text = (typeof jQuery(el).data('placeholder') != "undefined") ? jQuery(el).data('placeholder') : jQuery(el).children().text();

		if (document.selection) {
			element.focus();
			var sel = document.selection.createRange();
			sel.text = text;
			element.focus();
		} else if (element.selectionStart || element.selectionStart === 0) {
			var startPos = element.selectionStart;
			var endPos = element.selectionEnd;
			var scrollTop = element.scrollTop;
			element.value = element.value.substring(0, startPos) + text + element.value.substring(endPos, element.value.length);
			element.focus();
			element.selectionStart = startPos + text.length;
			element.selectionEnd = startPos + text.length;
			element.scrollTop = scrollTop;
		} else {
			element.value += text;
			element.focus();
		}

		jQuery(element).keyup();
	}
};



var LS_DataSource = {

	buildSlide: function() {

		var $slide = jQuery('#ls-layers .ls-layer-box');
		var $slideOptions = $slide.find('.ls-slide-options');

		// Reset checkboxes
		$slideOptions.find('.ls-checkbox').remove();
		$slideOptions.find('input:checkbox').prop('checked', false);

		// Loop through slide option form items
		var $formItems = jQuery($slideOptions.find('input,textarea,select'));
		LS_DataSource.setFormItemValues($formItems, LS_activeSlideData.properties);

		// Set checboxes
		$slideOptions.find('input:checkbox').customCheckbox();

		// Set image placeholders
		var background = LS_activeSlideData.properties.backgroundThumb;
			background = background ? background : lsTrImgPath+'/not_set.png';

		var thumbnail = LS_activeSlideData.properties.thumbnailThumb;
			thumbnail = thumbnail ? thumbnail : lsTrImgPath+'/not_set.png';

		$slideOptions.find('input[name="background"]').next().find('img').attr('src', background);
		$slideOptions.find('input[name="thumbnail"]').next().find('img').attr('src', thumbnail);

		this.buildLayersList();
	},


	buildLayersList: function() {

		// Get the layer list and empty it (if any)
		var $layersList = jQuery('#ls-layers .ls-sublayers').empty();

		// Build layers
		var numOfLayers = !LS_activeSlideData.sublayers ? 0 : LS_activeSlideData.sublayers.length;
		var $template = jQuery(jQuery('#ls-layer-item-template').html());

		for(var c = 0; c < numOfLayers; c++) {

			var layerData = LS_activeSlideData.sublayers[c];
			var $layer = $template.clone();
			$layer.find('.ls-sublayer-number').text(c+1);
			$layer.find('.ls-sublayer-title').val(layerData.subtitle);

			// Hidden layer
			if(layerData.skip) { $layer.find('.ls-icon-eye').addClass('disabled'); }

			// Locked layer
			if(layerData.locked) { $layer.find('.ls-icon-lock').removeClass('disabled'); }

			switch(layerData.media) {
				case 'img':
					if(layerData.imageThumb) {
						jQuery('.ls-sublayer-thumb', $layer).html('<img src="'+layerData.imageThumb+'">');
					}
					break;

				case 'html':
					jQuery('.ls-sublayer-thumb', $layer).addClass('dashicons dashicons-editor-code');
					break;

				case 'post':
					jQuery('.ls-sublayer-thumb', $layer).addClass('dashicons dashicons-admin-post');
					break;

				default:
					jQuery('.ls-sublayer-thumb', $layer).addClass('dashicons dashicons-editor-textcolor');
					break;
			}

			$layersList.append($layer);
		}

		// Select first layer
		$layersList.children().eq(LS_activeLayerIndex).addClass('active');
		this.buildLayer();
	},


	buildLayer: function() {

		// Bail out early if there's no layers on slide
		if(!LS_activeLayerData) { return false; }

		// Find active layer
		var $layerItem = jQuery('#ls-layers .ls-sublayers li.active');
		var $layer = jQuery('.ls-sublayer-pages');

		// Empty earlier layers and add new
		jQuery('.ls-sublayer-pages').empty();
		jQuery('.ls-sublayer-pages').html( jQuery('#ls-layer-template').html() );

		// Select layer and media type
		if(typeof LS_activeLayerData.media == 'undefined') {
			switch(LS_activeLayerData.type) {
				case 'img': LS_activeLayerData.media = 'img'; break;
				case 'div': LS_activeLayerData.media = 'html'; break;
				default: LS_activeLayerData.media = 'text';
			}
		}

		LayerSlider.selectMediaType( $layer.find('.ls-layer-kind li[data-section="'+LS_activeLayerData.media+'"]') );
		LayerSlider.selectElementType( $layer.find('.ls-sublayer-element > li[data-element="'+LS_activeLayerData.type+'"]') )

		// Reset checkboxes
		$layer.find('.ls-checkbox').remove();
		$layer.find('input:checkbox').prop('checked', false);

		var $formItems = jQuery($layer.find('input,textarea,select').filter(':not(.auto,.sublayerprop)'));
		var $styleItems = jQuery($layer.find('input,textarea,select').filter('.auto'));
		var $transitionItems = jQuery($layer.find('input,textarea,select').filter('.sublayerprop'));
		LS_DataSource.setFormItemValues($formItems, LS_activeLayerData);
		LS_DataSource.setFormItemValues($styleItems, LS_activeLayerData.styles);
		LS_DataSource.setFormItemValues($transitionItems, LS_activeLayerData.transition);

		// Set image placeholder
		var imageURL = !LS_activeLayerData.imageThumb ? lsTrImgPath+'/not_set.png' : LS_activeLayerData.imageThumb;
		$layer.find('input[name="image"]').next().find('img').attr('src', imageURL);

		// Apply custom checkboxes and color picker
		$layer.find(':checkbox:not(.noreplace)').customCheckbox();
		LayerSlider.addColorPicker( $layer.find('.ls-colorpicker') );

		// Select lastly viewed subpage
		jQuery('.ls-sublayer-nav a').removeClass('active').eq(LS_activeLayerPageIndex).addClass('active');
		var $target = jQuery('#ls-layers .ls-sublayer-page').eq(LS_activeLayerPageIndex);
			$target.addClass('active').siblings().removeClass('active');
	},

	setFormItemValues: function($items, values) {

		// Bail out early if no value was specified
		if(typeof $items == "undefined" || typeof values == "undefined") {
			return false;
		}

		// Iterate over items
		for(var itemIndex = 0; itemIndex < $items.length; itemIndex++) {

			var $item = jQuery($items[itemIndex]);
			var value = values[ $item.attr('name') ];

			if(typeof value == "undefined" || !value) {
				$item.val('');
				continue;
			}

			// Checkboxes
			if($item.is(':checkbox')) {
				$item.prop('checked', Boolean(value));

			// Input, textarea
			} else if($item.is('input,textarea')) {
				$item.val(value);

			// Select
			} else if($item.is('select')) {
				$item.children().prop('selected', false);
				$item.children('[value="'+value+'"]').prop('selected', true);
			}
		}
	},


	readSliderSettings: function() {

		var settings = {};

		jQuery('.ls-slider-settings').find('input,textarea,select').each(function() {
			var item = jQuery(this);
			var prop = item.attr('name');
			var  val = item.is(':checkbox') ? item.prop('checked') : item.val();

			if(prop && val !== false) { settings[ prop ] = val; }
		});

		return settings;
	},


	getDefaultSlideData: function() {

		// Return previously stored data whenever it's possible
		if(!jQuery.isEmptyObject(LS_defaultSlideData)) {
			return LS_defaultSlideData;
		}

		// Get slide template
		var $template = jQuery( jQuery('#ls-slide-template').text() );

		// Iterate over form items and add their values to LS_defaultSlideData
		jQuery('.ls-slide-options', $template).find('input, textarea, select').each(function() {

			var item = jQuery(this);
			var prop = item.attr('name');
			var  val = item.is(':checkbox') ? item.prop('checked') : item.val();

			if(prop) { LS_defaultSlideData[ prop ] = val; }
		});

		return LS_defaultSlideData;
	},


	getDefaultLayerData: function() {

		// Return previously stored data whenever it's possible
		if(!jQuery.isEmptyObject(LS_defaultLayerData)) {
			return LS_defaultLayerData;
		}

		// Transition and style options will be stored in a sub-object
		LS_defaultLayerData.subtitle = 'Layer #1';
		LS_defaultLayerData.transition = {};
		LS_defaultLayerData.styles = {};

		// Get layer template
		var $template = jQuery( jQuery('#ls-layer-template').text() );

		// Iterate over form items and add their values to LS_defaultLayerData
		jQuery('input, textarea, select', $template).each(function() {

			var item = jQuery(this);
			var prop = item.attr('name');
			var  val = item.is(':checkbox') ? item.prop('checked') : item.val();

			if(prop) {
				if(item.hasClass('sublayerprop')) { LS_defaultLayerData.transition[prop] = val; return true; }
				if(item.hasClass('auto')) { LS_defaultLayerData.styles[prop] = val; return true; }
				LS_defaultLayerData[prop] = val;
			}
		});

		return LS_defaultLayerData;
	}
};


jQuery(document).ready(function() {

		// Set the DB ID of currently editing slider
		LS_sliderID = jQuery('#ls-slider-form input[name="slider_id"]').val();


		// Add default slide data to data source if it's a new slider
		if(typeof window.lsSliderData.layers[0].sublayers == "undefined") {
			window.lsSliderData.properties = LS_DataSource.readSliderSettings();
			window.lsSliderData.layers = [{
				properties: jQuery.extend(true, {}, LS_DataSource.getDefaultSlideData()),
				sublayers: [jQuery.extend(true, {}, LS_DataSource.getDefaultLayerData())]
			}];
		}

		// Restore interface selection from previous editing session (if any)
		var session = LayerSlider.getEditingSession();
		if(window.lsSliderData.layers.length > parseInt(session.slideIndex)) { LS_activeSlideIndex = parseInt(session.slideIndex); }
		LS_activeSlideData = window.lsSliderData.layers[LS_activeSlideIndex];
		jQuery('#ls-layer-tabs a').removeClass('active').eq(LS_activeSlideIndex).addClass('active');

		if(LS_activeSlideData.sublayers.length > parseInt(session.layerIndex)) { LS_activeLayerIndex = parseInt(session.layerIndex); }
		LS_activeLayerData = LS_activeSlideData.sublayers[LS_activeLayerIndex];
		LS_activeLayerPageIndex = parseInt(session.layerPageIndex);


		// URL rewrite after creating slider
		if( history.replaceState ) {
			if(document.location.href.indexOf('&showsettings=1') != -1) {
				var url = document.location.href.replace('&showsettings=1', '');
				history.replaceState(null, document.title, url);
			}
		}

		// Main tab bar page select
		jQuery('#ls-main-nav-bar a:not(.unselectable)').click(function(e) {
			e.preventDefault(); LayerSlider.selectMainTab( this );
		});

		// Settings: checkboxes
		jQuery('.ls-settings :checkbox, .ls-layer-box :checkbox:not(.noreplace)').customCheckbox();

		// Uploads
		LayerSlider.openMediaLibrary();

		// Clear uploaded image button
		jQuery(document).on({
			mouseenter: function() {
				if(jQuery(this).find('img').attr('src').indexOf('not_set.png') == -1) {
					jQuery(this).addClass('hover');
				}
			},
			mouseleave: function() {
				jQuery(this).removeClass('hover');
			}
		}, '.ls-image');

		// Clear uploads
		jQuery(document).on('click', '.ls-image a', function(e) {
			e.preventDefault();
			e.stopPropagation();

			var $parent = jQuery(this).parent();

			$parent.removeClass('hover');
			$parent.prev().val('').prev().val('');
			$parent.find('img').attr('src', lsTrImgPath+'/not_set.png');

			// Global background
			if($parent.hasClass('ls-global-background')) {
				window.lsSliderData.properties.backgroundimage = '';
				window.lsSliderData.properties.backgroundimageId = '';
				window.lsSliderData.properties.backgroundimageThumb = '';

			} else if($parent.hasClass('ls-yourlogo-upload')) {
				window.lsSliderData.properties.yourlogo = '';
				window.lsSliderData.properties.yourlogoId = '';
				window.lsSliderData.properties.yourlogoThumb = '';

			} else if($parent.hasClass('ls-slide-image')) {
				LS_activeSlideData.properties.background = '';
				LS_activeSlideData.properties.backgroundId = '';
				LS_activeSlideData.properties.backgroundThumb = '';

			} else if($parent.hasClass('ls-slide-thumbnail')) {
				LS_activeSlideData.properties.thumbnail = '';
				LS_activeSlideData.properties.thumbnailId = '';
				LS_activeSlideData.properties.thumbnailThumb = '';

			} else if($parent.hasClass('ls-layer-image')) {
				LS_activeLayerData.image = '';
				LS_activeLayerData.imageId = '';
				LS_activeLayerData.imageThumb = '';
				jQuery('.ls-sublayers li').eq(LS_activeLayerIndex).find('.ls-sublayer-thumb img').remove();
			}


			LayerSlider.generatePreview();
		});


		// Settings: store any form element change in  data source
		jQuery(document).on('click keyup change', '.ls-slider-settings input, .ls-slider-settings textarea, .ls-slider-settings select', function(event) {

			// Bail out early if there was a click event
			// fired on a non-checkbox form item
			if(event.type === 'click') {
				if( !jQuery(this).is(':checkbox') ) {
					return false;
				}
			}

			// Get option data
			var item = jQuery(this);
			var prop = item.attr('name');
			var val  = item.is(':checkbox') ? item.prop('checked') : item.val();

			// Set new setting
			window.lsSliderData.properties[ prop ] = val;

			// Update preview
			if(item.is('select, :checkbox')) {
				LayerSlider.generatePreview();
			} else {
				LayerSlider.willGeneratePreview();
			}
		});

		// Settings: reset button
		jQuery(document).on('click', '.ls-reset', function() {

			// Empty field
			jQuery(this).prev().val('');

			// Generate preview
			LayerSlider.generatePreview();
		});


		// Callbacks: store any form element change in  data source
		jQuery('.ls-callback-page').on('updated.ls', 'textarea', function() {
			window.lsSliderData.properties[ jQuery(this).attr('name') ] = jQuery(this).val();
		});


		// Add layer
		jQuery('#ls-add-layer').click(function(e) {
			e.preventDefault();
			LayerSlider.addSlide();
		});

		// Select layer
		jQuery('#ls-layer-tabs').on('click', 'a:not(.unsortable)', function(e) {
			e.preventDefault();
			LayerSlider.selectSlide(this);
		});

		// Duplicate layer
		jQuery('#ls-layers').on('click', 'button.ls-layer-duplicate', function(e){
			e.preventDefault();
			LayerSlider.duplicateSlide(this);
		});

		// Enter URL
		jQuery('#ls-layers').on('click', '.ls-url-prompt', function(e){
			e.preventDefault();
			var $el = jQuery(this);
			var url = prompt('Enter an image URL');
			if(!url || url == '') { return false; }

			// Slide image
			if($el.prev().hasClass('ls-slide-image')) {
				LS_activeSlideData.properties.background = url;
				LS_activeSlideData.properties.backgroundId = '';
				LS_activeSlideData.properties.backgroundThumb = url;

			// Slide thumbnail
			} else if($el.prev().hasClass('ls-slide-thumbnail')) {
				LS_activeSlideData.properties.thumbnail = url;
				LS_activeSlideData.properties.thumbnailId = '';
				LS_activeSlideData.properties.thumbnailThumb = url;

			// Image layer
			} else if($el.parent().prev().hasClass('ls-layer-image')) {
				LS_activeLayerData.image = url;
				LS_activeLayerData.imageId = '';
				LS_activeLayerData.imageThumb = url;
			}

			$el.closest('.slide-image').find('.ls-image img').attr('src', url);
			LayerSlider.generatePreview();
		});

		// Slide options: input, textarea, select
		jQuery('#ls-layers').on('keyup change click', '.ls-slide-options input, .ls-slide-options textarea, .ls-slide-options select', function(event) {

			// Bail out early if there was a click event
			// fired on a non-checkbox form item
			if(event.type === 'click') {
				if( !jQuery(this).is(':checkbox') ) {
					return false;
				}
			}

			var item = jQuery(this);
			var prop = item.attr('name');
			var val  = item.is(':checkbox') ? item.prop('checked') : item.val();

			LS_activeSlideData.properties[prop] = val;
		});

		// Open Transition gallery
		jQuery('#ls-layers').on('click', '.ls-select-transitions', function(e) {
			e.preventDefault();
			LayerSlider.openTransitionGallery();
		});

		// Close transition gallery
		jQuery(document).on('click', '#ls-transition-window header b', function(e) {
			e.preventDefault();
			LayerSlider.closeTransitionGallery();
		});

		// Add/Remove layer transitions
		jQuery(document).on('click', '#ls-transition-window tbody a:not(.ls-checkbox)', function(e) {
			e.preventDefault();
			LayerSlider.toggleTransition(this);
		});

		// Add/Remove layer transitions
		jQuery(document).on('click', '#ls-transition-window header i:last', function(e) {
			var check = jQuery(this).hasClass('off') ? true : false;
			jQuery('#ls-transition-window tbody.active').each(function() {
				LayerSlider.selectAllTransition( jQuery(this).index(), check );
			});
		});

		// Apply on others
		jQuery(document).on('click', '#ls-transition-window header i:not(:last)', function(e) {

			// Confirmation
			if(!confirm('Are you sure you want to apply the currently selected transitions on the other slides?')) {
				return false;
			}

			// Dim color briefly
			var button = jQuery(this);
			button.css('color', '#bbb');
			setTimeout(function() {
				button.css('color', '#444');
			}, 2000);

			// Apply to other slides
			jQuery.each(window.lsSliderData.layers, function(slideIndex, slideData) {
				slideData.properties['3d_transitions'] = LS_activeSlideData.properties['3d_transitions'];
				slideData.properties['2d_transitions'] = LS_activeSlideData.properties['2d_transitions'];
				slideData.properties['custom_3d_transitions'] = LS_activeSlideData.properties['custom_3d_transitions'];
				slideData.properties['custom_2d_transitions'] = LS_activeSlideData.properties['custom_2d_transitions'];
			});
		});


		// Show/Hide transition
		jQuery(document).on('mouseenter', '#ls-transition-window table a', function() {
			LayerSlider.showTransition(this);
		}).on('mouseleave', '#ls-transition-window table a', function() {
			LayerSlider.hideTransition(this);
		});



		// Remove layer
		jQuery('#ls-layer-tabs').on('click', 'a span', function(e) {
			e.preventDefault();
			e.stopPropagation();
			LayerSlider.removeSlide(this);
		});

		// Add layer
		jQuery('#ls-layers').on('click', '.ls-add-sublayer', function(e) {
			e.preventDefault(); LayerSlider.addLayer(this);

		// Select layer
		}).on('click', '.ls-sublayers li', function() {
			LayerSlider.selectLayer(this);

		}).on('keyup', 'input[name="subtitle"]', function() {
			var index = jQuery(this).closest('li').index();
			LS_activeSlideData.sublayers[index].subtitle = jQuery(this).val()

		// Layer pages
		}).on('click', '.ls-sublayer-nav a', function(e) {
			e.preventDefault(); LayerSlider.selectLayerPage(this);

		// Remove layer
		}).on('click', '.ls-sublayers a.remove', function(e) {
			e.preventDefault(); LayerSlider.removeLayer(this);

		// Duplicate layer
		}).on('click', '.ls-sublayers a.duplicate', function(e) {
			e.preventDefault(); LayerSlider.duplicateLayer(this);


		// Layer media type
		}).on('click', '.ls-layer-kind li', function(e) {
			e.preventDefault(); LayerSlider.selectMediaType(this);

		// Layer element type
		}).on('click', '.ls-sublayer-element > li', function(e) {
			e.preventDefault(); LayerSlider.selectElementType(this);
			LayerSlider.generatePreviewItem(LS_activeLayerIndex);

		// Layer options: input, textarea, select
		}).on('keyup change click', '.ls-sublayer-pages input, .ls-sublayer-pages textarea, .ls-sublayer-pages select', function(event) {

			// Bail out early if there was a click event
			// fired on a non-checkbox form item
			if(event.type === 'click') {
				if( !jQuery(this).is(':checkbox') ) {
					return false;
				}
			}

			var item = jQuery(this);
			var prop = item.attr('name');
			var val  = item.is(':checkbox') ? item.prop('checked') : item.val();

			if( jQuery(this).hasClass('sublayerprop') ) { LS_activeLayerData.transition[prop] = val; }
				else if( jQuery(this).hasClass('auto') ) { LS_activeLayerData.styles[prop] = val; }
					else { LS_activeLayerData[prop] = val; }

			LayerSlider.willGeneratePreviewItem(LS_activeLayerIndex);
		});


		// Sublayer: sortables, draggable, etc
		LayerSlider.addSlideSortables();
		LayerSlider.addLayerSortables();
		LayerSlider.addDraggable();


		// Preview
		jQuery('#ls-layers').on('click', '.ls-preview-button', function(e) {
			e.preventDefault();
			LayerSlider.play();
		});

		// Preview drag element select
		jQuery('#ls-layers').on('click', '.draggable > *', function(e) {
			e.preventDefault();
			LayerSlider.selectDragElement(this);
		});

		// List intersecting preview items when right clicking on them
		jQuery('#ls-preview-layers').bind('contextmenu',function(e) {
			e.preventDefault(); LayerSlider.listPreviewItems(e);
		});

		// Highlight preview item when hovering the intersecting layers list
		jQuery(document).on({
			mouseenter: function() { LayerSlider.highlightPreviewItem(this); },
			mouseleave: function() { jQuery('#ls-preview-layers').children().removeClass('highlighted').css('opacity', 1); },
			}, '.ls-preview-item-list li'
		);

		// Select layer from intersecting layers list
		jQuery(document).on('click', '.ls-preview-item-list li', function() {
			LayerSlider.selectPreviewItem(this);
		});

		// Save changes
		jQuery('#ls-slider-form').submit(function(e) {
			e.preventDefault();
			LayerSlider.save(this);
		});

		// Add color picker
		LayerSlider.addColorPicker( jQuery('#ls-slider-form input.ls-colorpicker') );


		// Show color picker on focus
		jQuery('.color').focus(function() {
			jQuery(this).next().slideDown();
		});

		// Hide color picker on blur
		jQuery('.color').blur(function() {
			jQuery(this).next().slideUp();
		});

		// Eye icon for layers
		jQuery('.ls-sublayers').on('click', '.ls-icon-eye', function(e) {
			e.stopPropagation();
			LayerSlider.hideLayer(this);
		});

		// Lock icon for layers
		jQuery('#ls-layers').on('click', '.ls-icon-lock', function(e) {
			e.stopPropagation();
			LayerSlider.lockLayer(this);
		});

		jQuery('ul.ls-settings-sidebar > li').click(function() {
			LayerSlider.selectSettingsTab(this);
		});

		// Collapse layer before sorting
		jQuery('#ls-layers').on('mousedown', '.ls-sublayer-sortable-handle', function(){
			jQuery(this).closest('.ls-sublayers').addClass('dragging');
		});


		// Expand layer after sorting
		jQuery('#ls-layers').on('mouseup', '.ls-sublayer-sortable-handle', function(){
			jQuery('#ls-layers .ls-layer-box.active .ls-sublayer-sortable').removeClass('dragging');
		});

		// Timeline
		lsTimeLine.init();
		LS_PostOptions.init();
		LayerSlider.addPreviewSlider( jQuery('#ls-layers .ls-editor-slider') );

		// Transitions gallery
		jQuery(document).on('click', '#ls-transition-window .filters li', function() {

			// Update navigation
			jQuery(this).siblings().removeClass('active');
			jQuery(this).addClass('active');

			// Update view
			jQuery('#ls-transition-window tbody').removeClass('active');
			jQuery('#ls-transition-window tbody').eq( jQuery(this).index() ).addClass('active');

			// Custom transitions
			if(jQuery(this).index() == 2) {
				jQuery('#ls-transition-window tbody').eq(3).addClass('active');
			}

			// Update 'Select all' button
			var trs = jQuery('#ls-transition-window tbody.active td');
			if(trs.filter('.c.added').length == trs.filter('.c').length) {
				jQuery('#ls-transition-window header i:last').attr('class', 'on').text('Deselect all');
			} else {
				jQuery('#ls-transition-window header i:last').attr('class', 'off').text('Select all');
			}
		});

		// Link slide to post url
		jQuery('#ls-layers').on('click', '.ls-slide-link a', function(e) {
			e.preventDefault();
			jQuery(this).closest('.ls-slide-link').children('input').val('[post-url]');
		});


		// Use post image as slide background
		jQuery('#ls-layers').on('click', '.slide-image .ls-post-image', function(e) {
			e.preventDefault();
			jQuery(this).closest('.slide-image').children('input[name="backgroundId"]').val('');
			jQuery(this).closest('.slide-image').children('input[name="background"]').val('[image-url]');

			jQuery(this).closest('.slide-image').children('input[name="imageId"]').val('');
			jQuery(this).closest('.slide-image').children('input[name="image"]').val('[image-url]');

			LayerSlider.generatePreview();
		});

		// Hide zoom slider if not supported by browser
		if(typeof document.body.style.zoom === 'undefined') {
			jQuery('.ls-editor-zoom').addClass('ls-hidden');
		}

		LS_DataSource.buildSlide();
		LayerSlider.generatePreview();
});
