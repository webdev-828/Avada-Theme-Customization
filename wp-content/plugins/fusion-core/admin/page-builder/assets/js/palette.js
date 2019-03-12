/*
 * This is the Palette placeholder which contains all its categories
 */  
( function($) { 
	var Palette = {};
	window.Palette = Palette;
	// emulateHTTP = true is a workaround to prevent backbone from using HTTP PUT and HTTP DELETE requests, only POST is used
	Backbone.emulateHTTP = true;
	Backbone.emulateJSON = true;
	
	var oldBackboneSync = Backbone.sync;
	// Override Backbone.Sync
	Backbone.sync = function( method, model, options ) {
		
		var call_model = null;
		//if delete or update requests, do not continue
		if( method == 'delete' || method == 'update' ) { return; }
		//if read method then modify method name and setup model
		if( method == 'read' ) {
			method 		= 'fusion_pallete_elements';
			call_model 	= options.model;	
		}
		//last request, data is populated. load custom tabs content now
		if (options.category != "Palette" && window.loadCustom == null && window.preBuiltTemplateID == null) {
			DdHelper.loadCustomTabs();
		}
		//setup content for AJAX request
		instance 		= jQuery('#fusion-page-builder').attr('instance');
		instance 		= ( window.preBuiltTemplateID != null ? window.preBuiltTemplateID : instance );
		options.action 	= method;
		options.aysnc	= false;
		options.data 	= { action : method, model : call_model, category : options.category ,instance : instance };
		
		//send request
		return oldBackboneSync.apply(this, ['create', model, options]);
		
		
	}

	// Palette Element model, it's a placeholder for Element Cateogires, each category have set of elements, it's presented by the subelement elements
	Palette.Element = Backbone.Model.extend({
		  initialize : function(){
			  
			  this.elements = new Category.Elements(this.get('elements'));
		  }
	});

	// Palette Elements collection, it will have all categories elements, it act as an array for all cateogies elements
	Palette.Elements = Backbone.Collection.extend({
		model: Palette.Element,
		url  : ajaxurl,
	});

	
	// Main view object for the palette
	Palette.Display = Backbone.View.extend({
		initialize: function() {
			this.elements = new Palette.Elements();
			this.bind( 'error', this.errorHandler );
		},
		render: function() {
			var tabsPlaceholder = this.$el;
			var tabsTitle = $("<ul></ul>");
			// iterate over each category and append the category to the ul element.
			this.elements.each(function( category ){tabsTitle.append(this.displayCategory(category));}, this);
			// append the categories output to the main tab placeholder
			tabsPlaceholder.append(tabsTitle);
			// iterate over each category and diplay it's elements
			this.elements.each(function( category ){this.displayElement(category, tabsPlaceholder);}, this);
			// initialize jquery tabs
			tabsPlaceholder.tabs();
			// initialize jquery tooltip

			// error check
			if( this.elements.length == 0 ) {
				this.errorHandler();
			}

			return this;
		},
		displayCategory: function(category) {  
			var displayCategoryTab = new Category.DisplayTab({model:category}); 
			return displayCategoryTab.render().$el.html();
		},
		displayElement: function(category, parent) {  
			//create tab content view object using the current category
			var tabContent 		= new Category.DisplayTabContent({model:category});
			var elementValues 	= $().add(tabContent.render().$el.html());
			// get the current category elements
			var elements 		= category.get('elements');
			for(var i=0; i < elements.length; i++)
			{
				// create view object for each element object
				var elementView = new Category.DisplayElement({model:new Category.ElementEntry(elements[i])});
				// add the view object to the elementValues object
				elementValues.append(elementView.render().$el);
			}
			// append the elementValues to the parent DOM object
			parent.append(elementValues);
			return tabContent;
		},
		//Create new element using it's palette id
		createElement: function(uiElementId){
			var elementObject 		= app.getElementById(uiElementId);
			elementObject 			= new Editor.ElementEntry(elementObject);
			var generatedElementId 	= Math.guid();
			elementObject.set("id", generatedElementId);
			return elementObject;
		},
		// error Handler
		errorHandler: function() {
			jQuery( '.fusion-builder-settings-error' ).show();
		}
	  });
	  
  })(jQuery);