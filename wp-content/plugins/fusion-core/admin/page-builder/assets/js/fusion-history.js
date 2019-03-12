/*
* adds undo and redo functionality to the Fusion Page Builder
*/
( function($) { 
	var fusionHistoryManager 		= {};
	window.fusionHistoryManager 	= fusionHistoryManager;
	var fusionCommands 				= new Array('[]');
	//is tracking on or off?
	window.tracking					= 'on'
	//maximum steps allowed/saved
	var maxSteps					= 40;
	//current Index of step
	var currStep					= 0;
	
	/**
	 * get editor data and add to array
	 * @param 	NULL
	 * @return 	NULL
	 */
	fusionHistoryManager.captureEditor = function( ) {
		
		//if tracking is on
		if( fusionHistoryManager.isTrackingOn() ) {
			//get elements
			allElements = fusionHistoryManager.getAllElementsData();
			
			if ( currStep ==  maxSteps) { //if reached limit
				fusionCommands.shift(); //remove first index
			} else {
				currStep += 1; //else increment index
			}
			
			//add editor data to Array
			fusionCommands[currStep] = allElements;
			//update buttons
			fusionHistoryManager.updateButtons();
		}
	}
	/**
	 * get models of all elements visible in editor
	 * @param 	NULL
	 * @return 	{String}	JSON String of editor elements
	 */
	fusionHistoryManager.getAllElementsData = function() {
		
		var editorElements 		= document.querySelectorAll('#editor .item-wrapper');	
		var allElements 		= new Array();
		var uniqueEls			= new Array();


		for ( var i=0; i < editorElements.length; i++ )
		{
			var elementId 		= editorElements[i].id;
			
			if( elementId ) //if element exists
			{
				//get element model
				var element 		= app.editor.selectedElements.get(elementId);
				// get element order
				var elementIndex 	= i;
				//set element order
				element.attributes.index = elementIndex;
				//add element to stack
				allElements.push( element );
				
			}
		}
		//remove duplicates
		$.each( allElements, function( i, el ){
			if( $.inArray( el, uniqueEls ) === -1) uniqueEls.push( el );
		});
		//return JSON String of elements
		return JSON.stringify(uniqueEls);
	}
	/**
	 * set tracking flag ON.
	 * @param 	NULL
	 * @return 	NULL
	 */
	fusionHistoryManager.turnOnTracking = function( ) {
		window.tracking = 'on';
	}
	/**
	 * set tracking flag OFF.
	 * @param 	NULL
	 * @return 	NULL
	 */
	fusionHistoryManager.turnOffTracking = function( ) {
		window.tracking = 'off';
	}
	/**
	 * Get editor elements of current index for UNDO. Remove all elements currenlty visible in eidor and then reset models
	 * @param 	NULL
	 * @return 	NULL
	 */
	fusionHistoryManager.doUndo = function( ){
		
		if ( fusionHistoryManager.hasUndo() ) { //if no data or end of stack and nothing to undo
			//turn off tracking first, so these actions are not captured
			fusionHistoryManager.turnOffTracking();
			currStep 		-= 1;
			
			//data to undo
			var undoData 	= fusionCommands[currStep];
			if( undoData != '[]' ) { //if not empty state
				//remove all current editor elements first
				Editor.deleteAllElements();
				//reset models with new elements
				app.editor.selectedElements.reset( JSON.parse(undoData) );
				//turn on tracking
				fusionHistoryManager.turnOnTracking();
				//update buttons
				fusionHistoryManager.updateButtons();
			}
		}
		
	}
	/**
	 * Get editor elements of current index for REDO. Remove all elements currenlty visible in eidor and then reset models
	 * @param 	NULL
	 * @return 	NULL
	 */
	fusionHistoryManager.doRedo = function( ) {
		
		if ( fusionHistoryManager.hasRedo() ) { //if not at end and nothing to redo
			//turn off tracking, so these actions are not tracked
			fusionHistoryManager.turnOffTracking();
			//move index
			currStep	+= 1;;
			//get data to redo
			var RedoData = fusionCommands[currStep];
			//remove currently visible elements in editor
			Editor.deleteAllElements();
			//reset models with new elements
			app.editor.selectedElements.reset( JSON.parse(RedoData) );
			//turn on tracking, so future actions are tracked
			fusionHistoryManager.turnOnTracking();
			//update buttons
			fusionHistoryManager.updateButtons();
		}
		
	}
	/**
	 * check whether tracking is on or off
	 * @param 	NULL
	 * @return 	NULL
	 */
	fusionHistoryManager.isTrackingOn = function( ) {
		if ( window.tracking == 'on' ) {
			return true;
		} else {
			return false;
		}
	}
	/**
	 * log current data
	 * @param 	NULL
	 * @return 	NULL
	 */
	fusionHistoryManager.logStacks = function() {
		console.log( JSON.parse(fusionCommands) );
	}
	/**
	 * clear all commands and reset manager
	 * @param 	NULL
	 * @return 	NULL
	 */
	fusionHistoryManager.clear = function() {
		fusionCommands 	= new Array('[]');
		currStep 		= -1;
	}
	/**
	 * check if undo commands exist
	 * @param 	NULL
	 * @return 	NULL
	 */
	fusionHistoryManager.hasUndo = function () {
		return currStep !== 1;
	}
	/**
	 * check if redo commands exist
	 * @param 	NULL
	 * @return 	NULL
	 */
	fusionHistoryManager.hasRedo = function () {
		return currStep < ( fusionCommands.length - 1 );
	}
	/**
	 * get existing commands
	 * @param 	NULL
	 * @return 	{string}	actions
	 */
	fusionHistoryManager.getCommands = function () {
		return fusionCommands;
	}
	/**
	 * update buttons colors accordingly
	 * @param 	NULL
	 * @return 	NULL
	 */
	fusionHistoryManager.updateButtons = function () {
		//for undo button
		$( '#both_icon .fusiona-reply' ).css( 'color',fusionHistoryManager.hasUndo() ? "#008EC5" : "" );
		//for redo button
		$( '#both_icon .fusiona-forward' ).css( 'color', fusionHistoryManager.hasRedo() ? "#008EC5" : "" );
		
	}
	 
  })(jQuery);

