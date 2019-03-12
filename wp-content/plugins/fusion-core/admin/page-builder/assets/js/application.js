(function ($) {
	// Generate random unique ID
	if (Math && !Math.s4 && !Math.guid) {
				var s4 = function () {
					return Math.floor((1 + Math.random()) * 0x10000)
						.toString(16)
						.substring(1);
				};
				Math.s4 = s4;
				Math.guid = function () {
					return 'fusionb_' + s4() + s4() + '-' + s4() + '-' + s4() + '-' +
						s4() + '-' + s4() + s4() + s4();
				}
	}
			
	// Extend Handlebars to load [and cache] a template from within the DOM.
	if (Handlebars && !Handlebars.loadTemplate) {
		var loadTemplate = function (name) {
			if (this[name]) {
				return this[name];
			}
			var temp 		= $("#" + name + "-template").html();
			var compiled 	= Handlebars.compile(temp);
			this[name] 		= compiled;
			return compiled;
		};
		window.HandlebarsLoadTemplate = loadTemplate;
	}

	var Application = {};
	window.Application = Application;

	// The main view of the application
	Application.DdBuilder = Backbone.View.extend({
		initialize:function (options) {
			// set tabsContainer with contain passed during initialization
			this.tabsContainer 		= options.tabsContainer;
			// set editorContainer with contain passed during initialization
			this.editorContainer 	= options.editorContainer;
			// load palette elements
			this.loadPalette();
		},
		
		loadPalette: function()
		{
			// create new palette view object
			this.palette = new Palette.Display({el:this.tabsContainer});
			// fetch all palette elements
			this.palette.elements.fetch({
				success: function(){
					// on success render the palette layout
					app.palette.render();
					// load the editor
					app.loadEditor();
				},
				error: function (errorResponse, errorDescription) {
					 // on success render the palette layout
					app.palette.render();
					// load the editor
					app.loadEditor();
				},
				category: 'Palette'
			});
			
		},
		
		loadEditor : function(){
			// activate dropping for the main editor element
			DdHelper.activateDropping(this.editorContainer);
			// create new editor view object
			this.editor = new Editor.Display({el:this.editorContainer});
			if( window.fusionBuilderState == 'active' ) { //check if builder is active
				//load elements :: convert current Wp editor content to builder elements
				DdHelper.shortCodestoBuilderElements();
			}
			//losf custom tabs
			DdHelper.loadCustomTabs();
		},
		// this function iterate over palette categories and element and return the element with the given Id
		getElementById : function(elementId){
			var currentElement = null;
			this.palette.elements.each(function( category ){
				var categoryElements = category.get('elements');
				if(categoryElements)
				{
					var tempCurrentElement = null;
					if(!currentElement)
					{
						for(var i=0 ; i < categoryElements.length ; i++)
						{
							if(categoryElements[i].id===elementId)
							{
								
								tempCurrentElement = DdElementParser.DeepCopyElement(categoryElements[i]);
								
								currentElement = tempCurrentElement;
								break;
							}
						}  
					}
				}
			});
			
			return currentElement;
		}
	});

	// the startup function
	Application.startup = function (tabsContainer, editorContainer) {
		return new Application.DdBuilder({tabsContainer:$(tabsContainer), editorContainer:$(editorContainer)});
	};

}) (jQuery);

var app;
jQuery(function ($) {
	// initialize empty jquery modal lighbox, which will be used later for showing the editing panel.
	DdHelper.initializeModalLightbox();
	
	// initialize the application by sending the DOM elements for the tabs and for the editor.
	// the created object is added to var app which is defined in application.js, as it's used everywhere later on.
	app = Application.startup($("#tabs"),$("#editor"));
	

});