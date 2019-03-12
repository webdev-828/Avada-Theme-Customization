/*
 * This is the Category element placeholder
 */  
( function($) { 
	var Category = {};
	window.Category = Category;

	// Category Element Model
	Category.ElementEntry = Backbone.Model.extend();

	// Category collection, it act as array for elements
	Category.Elements = Backbone.Collection.extend({
		model: Category.ElementEntry
	});


	// Category view object
	Category.DisplayElement = Backbone.View.extend({
		// tag name for the element is div, it means each element will be surrounding by <div></div>
		tagName: 'div',
		// classes for the element div
		className: 'item-wrapper pre_element_block',
		
		initialize: function() {
			// use template that has name "element-template"
			this.template = window.HandlebarsLoadTemplate('element');
		},
		events: function(){
			var _events = {};
			// add click event to the element hyperlink, which will execute addElementByClicking on click
			_events["click " + ".element_block"] = "addElementByClicking";
			return _events;
		},
		render: function() {
			this.$el.html(this.template(this.model.toJSON()));
			// Get model "data" array and loop over it and add it to the DOM element with prefix "data-"
			var dataArray = this.model.get('data');
			if(dataArray)
			{
				for(var index in dataArray) 
				{
					$(this.el).attr("data-"+index, dataArray[index] );
				}
			}
			// activate dragging for the current element
			DdHelper.activateDragging($(this.el));
			return this;
		},
		addElementByClicking: function(){
			
			var elementObject = app.palette.createElement(this.model.get('id'));
			
			// get last element index
			var elements 		= $( '#editor .item-wrapper' );
			var lastItem 		= $( '.item-wrapper:last' );
			var elementIndex 	= elements.index(lastItem);
			//var elementIndex = $("#editor").children().closest('.item-wrapper').index(); //:: Not working
			// add 1 to the last element index which is used as element order. i.e. element index is the element order in it's parent
			elementObject.attributes.index = elementIndex+1;
			// add the element to the selectedElements
			app.editor.selectedElements.add(elementObject);
			// capture editor
			fusionHistoryManager.captureEditor();
		},
	  });
	  
	  
	  Category.DisplayTab = Backbone.View.extend({
		initialize: function() {
		  this.template = window.HandlebarsLoadTemplate('tab');
		},
		render: function() {	 
		  this.$el.html(this.template(this.model.toJSON()));
		  return this;
		}
	  });
	  
	  
	  Category.DisplayTabContent = Backbone.View.extend({
		initialize: function() {
		  this.template = window.HandlebarsLoadTemplate('tabContent');
		},
		render: function() {	 
		  this.$el.html(this.template(this.model.toJSON()));
		  return this;
		}
	  });
	  
  })(jQuery);