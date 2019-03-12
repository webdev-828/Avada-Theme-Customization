
( function ( $ ) {
	"use strict";
	$.fusionCustomTemplates = ( function () {
		
		var SaveTemplateHandler 	= $( '#fusion_save_custom_tpl' );
		var loadTemplateHanlder 	= $( '.fuiosn_load_template' );
		var deleteTemplateHanlder 	= $( '.fusion_delete_template' );
		window.customTemplate   	= null;
		window.loadCustom			= null;
		
		$( document ).ready( function () {
			//function to get template name from user
			function getTemplateNameInput () {
				var tpl_name = prompt("Please enter template name.");
				if ( tpl_name ) {
					if ( checkIfTemplateExists( tpl_name ) == false ) {
						return tpl_name;
					}
					return null;
				} else  {
					return null;
				}
			}
			//function to check if a template with this name already exists
			function checkIfTemplateExists ( tpl_name ) {
				var is_duplicate = false;
				//loop through all templates and compare names
				$('.fusion_custom_template').each(function(i, obj) {
					var name = $(obj).text();
					if ( name.toLowerCase() == tpl_name.toLowerCase() ) {
						if ( confirm ( "Template with this name already exists. Click OK to overwrite." ) ) {
							//do nothing
						} else {
							is_duplicate = true;
						}
					}
				});
				return is_duplicate;
			}
			//function to load custom template
			loadTemplateHanlder.live('click', function ( e ) {
				if ( confirm( "Do you want to replace the current page content with the custom template content? This action cannot be reversed." ) ) {
					window.customTemplate = null;
					getCustomTemplateContent( $(this).attr( "data-id" ) );
					//var elementObject = app.palette.createElement(elementId);
				}
			});
			//function to save custom template
			SaveTemplateHandler.live('click', function ( e ) {
				var editorElements 		= $("#editor").find('.item-wrapper').length;
				if ( editorElements > 0 ) {
					window.customTemplate = getTemplateNameInput();
					if ( window.customTemplate != null ) {
						// save data to server
						SaveCustomTemplate( );
					}
				} else {
					alert ( "Error: You can not save empty template." );
				}
			});
			//function to delete custom template
			deleteTemplateHanlder.live('click', function ( e ) {
				if ( confirm( "Are you sure you want to delete this template? This action cannot be reversed." ) ) {
					DdHelper.showHideLoader( 'show', 'Deleting...' );
					var data = {
							action		: 'fusion_custom_tabs',
							post_action : 'delete_custom_template',
							name		: $(this).attr( "data-id" )
						};
						
					$.post(ajaxurl, data ,function( response ) {
						
						if ( response.hasOwnProperty ( 'message' ) ) { //if data updated successfully
						
							DdHelper.showHideLoader( 'hide' );
							if ( response.hasOwnProperty ( 'custom_templates' ) ) { //if we got data
								$('#custom_templates_div').html(response.custom_templates);
							}
						
						} else {
							DdHelper.showHideLoader( 'hide' );
							alert ( response.error.text ); // i feel bad.
						}
					});
				}
			});
			/**
			 * Save custom template to server
			 * @param content content to be saved
			 * @returns null. 
			 */
			function SaveCustomTemplate( ) {
				//get short-codes for current elements
				var content = fusionParser.parseColumnOptions();
				//prepare data
				var data = {
						action	  : 'fusion_custom_tabs',
						post_action : 'save_custom_template',
						model	   : content,
						name		: window.customTemplate
					};
				
				//way to go
				$.post(ajaxurl, data ,function( response ) {
					
					if ( response.hasOwnProperty ( 'message' ) ) { //if data updated successfully
						DdHelper.showHideLoader( 'hide' );
						if ( response.hasOwnProperty ( 'custom_templates' ) ) { //if we got data
							$('#custom_templates_div').html(response.custom_templates);
						}
						
					} else {
						DdHelper.showHideLoader( 'hide' );
						alert ( response.error.text ); // i feel bad.
					}
					
				});
			}
			
			/* function to get template data from server
			* @param template name
			* @returns null. 
			*/
			function getCustomTemplateContent ( templateName ) {
			
				//show loader
				DdHelper.showHideLoader('show','');
				//setup data
				var data = {
								action		: 'fusion_custom_tabs',
								post_action : 'load_custom_template',
								name		: templateName
				};
				
				
				$.post(ajaxurl, data ,function( response ) {
					
					var data = {
									action		  : 'fusion_content_to_elements',
									content		 : response
					};
					
					$.post(ajaxurl, data ,function( response ) {
						
						//turn off tracking first, so these actions are not captured
						fusionHistoryManager.turnOffTracking();
						//remove all current editor elements first
						Editor.deleteAllElements();
						//reset models with new elements
						app.editor.selectedElements.reset( response );
						//turn on tracking
						fusionHistoryManager.turnOnTracking();
						//capture editor
						fusionHistoryManager.captureEditor();
						//hide loads
						DdHelper.showHideLoader('hide');
					});
				});
			}
			
		});
	});
	
	$(document).ready(function () {
		
		$.fusionCustomTemplatesObj = new $.fusionCustomTemplates();
	});

}(jQuery));
