/**
 *
 * Group of helper functions which will convert object data to form and elements.
 */
( function($) {

	var DdElementParser 	= {};
	window.DdElementParser 	= DdElementParser;
	//types of elements
	var INPUT 				= "input";
	var COLOR 				= "color";
	var HIDDEN 				= "hidden";
	var SELECT 				= "select";
	var RADIO 				= "radio";
	var CHECKBOX 			= "checkbox";
	var TEXTAREA 			= "textarea";
	var HTML_EDITOR 		= "html_editor";
	var ICON_BOX 			= "icon_box";
	var MULTI				= "multiselect";
	var PARAGRAPH			= "paragraph";
	var UPLOAD				= "upload";
	var GALLERY				= "gallery";
	var ADDMORE				= "addmore";

	DdElementParser.generateHtml = function (model, settings_lvl) {
		var output 				= "";
		output					+= '<div id="child-element-data" style="display:none"></div>';
		output 					+= "<form action='#' id='element-edit-form' class='"+model.get('php_class')+"'>";

        var $hasGroups = false
        jQuery.each ( model.get('subElements') ,function( key, element ){
            if (element['group'] && element['group'] != "") {
                $hasGroups = true;
            }
        });

        if ($hasGroups) {
            var $group_html = [];
            $group_html['Default'] = "";
            var $orig_output = output;
        }

        var lvl_exists = false;
        var lvl_value;

        if( model.get('subElements')[0].id == 'fusion_settings_lvl' ) {
        	lvl_exists = true;
        	lvl_value = model.get('subElements')[0].value;

        	if( settings_lvl ) {
        		lvl_value = settings_lvl;
        	}
        }

		jQuery.each ( model.get('subElements') ,function( key, element ){
            if ($hasGroups) {
                output = "";
            }
			// if element is of add more type where user can create child elmenets
			if( element['type'] == ADDMORE ) {

				var elementsCount = element['elements'].length; // get how many elements we have

				elementsCount = (elementsCount > 0 ? elementsCount : 1);

				output 			+= "<table class='clearfix has-children'>";

				for (var i = 0; i < elementsCount; i++) {

					output 		+= "<tr class='child-clone-row'><td><a href='#' class='fusion-expand-child'>" + model.get('name') + " Item " + (i + 1) + "<i class='fusiona-plus2'></i></a><div class='child-options' style='display: none;'>";

					jQuery.each (element['elements'][i],function( innerKey, dynamic_element ) {
						var ChildElement = dynamic_element;

						if( ChildElement['settings_lvl'] == 'child' && lvl_value == 'parent' ) {
							output 	+= "<div class='clearfix form-element-container funsion-element-child form-element-container-" + ChildElement['type'] + "' style='display: none;'><div class='name-description'>";
						} else {
							output 	+= "<div class='clearfix form-element-container funsion-element-child form-element-container-" + ChildElement['type'] + "'><div class='name-description'>";
						}

						if( ChildElement['name'] != "" ) { output += "<strong>" + ChildElement['name'] + "</strong>"; }
						if( ChildElement['desc'] != "" ) { output += "<span>" + ChildElement['desc'] + "</span>"; }
						//for image in child elements
						if (typeof ChildElement['upid'] !== 'undefined') { ChildElement['upid'] = i; }

						output 	+= "</div>";
						output 	+= "<div class='element-type'>";
						output 	+= DdElementParser.parseElementType(ChildElement);
                        if( ChildElement['note'] != undefined ) { output += "<em>" + ChildElement['note'] + "</em>"; }
						output 	+= "</div>";
						output 	+= "</div>";
					});
					output 		+="<a class='child-clone-row-remove fusion-shortcodes-button' href='JavaScript:void(0)'>Remove</a>";
					output 		+= "</div></td></tr>";
				}

				output 			+= "<tr><td><a id='fusion-child-add' href='JavaScript:void(0)'>" + element['buttonText'] + "</a></td></tr>";
				output 			+= "</table>";

			} else {
				if ( model.get('subElements')[0].value != 'fusion_login' && ( element['id'] == 'fusion_register' || element['id'] == 'fusion_lost_password' ) ) {
					return;
				}

				output 			+= "<div class='clearfix form-element-container form-element-container-" + element['type'] + "'>";
				output 			+= "<div class='name-description'>";
				if( element['name'] != "" ) { output += "<strong>" + element['name'] + "</strong>"; }
				if( element['desc'] != "" ) { output += "<span>" + element['desc'] + "</span>"; }
				output 			+= "</div>";
				output 			+= "<div class='element-type'>";
				output 			+= DdElementParser.parseElementType(element);
                if( element['note'] ) { output += '<div class="note">' + element['note'] + "</div>"; }
				output 			+= "</div>";
				output 			+= "</div>";
			}
            if ($hasGroups) {
                if (element['group']) {
                    if (!$group_html[element['group']]) {
                        $group_html[element['group']] = "";
                    }
                    $group_html[element['group']] += output;
                } else {
                    $group_html['Default'] += output;
                }
            }
		});
        var $menu = "";

        if ($hasGroups) {
            output = $orig_output;
            var $tabID = 1;
            $menu = '<ul class="fusion-tabs-menu">';
            var $tabs = '<div class="fusion-tabs">';

            if ($group_html['Default'] != "") {
                $menu += '<li class="current"><a href="#tab-'+$tabID+'">Default</a></li>';
                $tabs += '<div id="tab-'+$tabID+'" class="fusion-tab-content" style="display:block;">'+$group_html['Default']+'</div>';
                $tabID++;
            }

            for ( var key in $group_html ) {
                if (key != "Default") {
                    var $class = "";
                    if ($tabID == 1) {
                        $class = ' class="current"';
                    }
                    $menu += '<li'+$class+'><a href="#tab-'+$tabID+'">'+key+'</a></li>';
                    $class = '';
                    if ($tabID == 1) {
                        $class = ' style="display:block;"';
                    }
                    $tabs += '<div id="tab-'+$tabID+'" class="fusion-tab-content"'+$class+'>'+$group_html[key]+'</div>';
                    $tabID++;
                }
            }
            $menu += "</ul>";
            $tabs += "</div>";
            output += $tabs;
        }
		output 					+= "</form>";
		//update model
        model.attributes.editPanel_appendtoTitle = $menu;
		model.attributes.editPanel_innerHtml = output;



	}

	DdElementParser.parseElementType = function (element) {

		var output 			= "";

		if(element['value'] == null) {
			element['value'] = '';
		}

		switch (element['type']) {

			case COLOR:

				output 		+= '<input type="text" class="text-field fusion-color-field" value="' + nl2br(element['value'],false) + '" id="' + element['id'] + '" name="' + element['id'] + '" size="50"/>';
			break;

			case GALLERY:
				output 		+= '<a href="'+element['id']+'" class="fusion-gallery-button fusion-shortcodes-button">Attach Images to Gallery</a>';
			break;

			case UPLOAD:
				//for empty string
				if ( element['value'] == '' ) { element['value'] = ''; }

				//for default settings
				button_class	= ( element['value'] == '' ? '' 			: 'remove-image' );
				button_text		= ( element['value'] == '' ? 'Upload' 		: 'Remove' );
				edit_style		= ( element['value'] == '' ? 'display:none' : '' );

				output 		+= '<div class="fusion-upload-container">';
				output 		+= '<img src="'+element['value'] +'" alt="Image" class="uploaded-image" /><div style=" clear:both;"></div>';
				output 		+= '<input type="hidden" class="fusion-form-text fusion-form-upload fusion-input" name="'+element['id'] +'" id="'+element['id'] +'" value="'+element['value'] +'" />' + "\n";
				output 		+= '<a href="'+element['id'] +'" class="fusionb-upload-button '+button_class+'" data-upid="'+element['upid'] +'">'+button_text+'</a>';
				output		+= '<a href="'+element['id'] +'" style="'+edit_style+'" class="fusionb-edit-button" data-upid="'+element['upid'] +'">Edit</a>';
				output 		+= '</div>';

			break;

			  case INPUT:

			  	if ( nl2br(element['value'],false ).indexOf('\'') >= 0 ) {
					output 		+= '<input type="text" class="text-field" value="' + nl2br(element['value'],false)+'" id="'+element['id']+'" name="'+element['id']+'" size="50"/>';
				} else {	output 		+= '<input type="text" class="text-field" value=\'' + nl2br(element['value'],false)+'\' id="'+element['id']+'" name="'+element['id']+'" size="50"/>';
				}

			break;

			case HIDDEN:
				output  	+= '<input type="hidden" value=\''+element['value']+'\' id="'+element['id']+'" name="'+element['id']+'"/>';
			break;
			case MULTI:

			output 			+= '<div class="select_arrow"></div><select id="'+element['id']+'" name="'+element['id']+'" class="select-field chosen-select" multiple>';
				jQuery.each (element['allowedValues'] ,function( key, value ){
					selected = "";
					key = key.replace('fusion_', '');
					if( $.inArray( key, element['value'] ) >= 0) {
						selected = "selected";
					}
					output += '<option value="'+key+'" '+selected+'>'+value+'</option>';
				});
				output 	   += '</select></div>';
			break;
			case SELECT:
				output 	   += '<div class="select_arrow"></div><select id="'+element['id']+'" name="'+element['id']+'" class="select-field">';
				jQuery.each (element['allowedValues'] ,function( key ,value) {
					selected = "";
					key = key.replace('fusion_', '');
					if( element['value'] == key ) {

						selected = "selected";
					}
					output += '<option value="'+key+'" '+selected+'>'+value+'</option>';
				});
				output 	   += '</select>';
			break;

			case RADIO:
				var counter = 1;
				jQuery.each( element['allowedValues'],function( key,radiobutton ) {
					var checked = "";
					key = key.replace('fusion_', '');
					if( element['value'] == key ) { checked = 'checked = "checked"'; }
					output  += '<span class="radio-field">';
					output  += '<input '+checked+' type="radio" value="'+key+'" id="'+element['id'] + counter+'" name="'+element['id']+'"/>';
					output  += '<label for="'+element['id'].counter+'"><span class="labeltext">'+radiobutton+'</span>';
					output  += '</label>';
					output  += '</span>';
					counter++;
				});
			break;
			case CHECKBOX:
				var counter = 1;
				jQuery.each( element['allowedValues'],function( key ,checkbox ) {
					var checked = "";
					key = key.replace('fusion_', '');
					if( element['value'] == key ) { checked = 'checked = "checked"'; }
					output  += '<span class="checkbox-field">';
					output  += '<input '+checked+' type="checkbox" value="'+key+'" id="'+element['id'].counter+'" name="'+element['id']+'"/>';
					output  += '<label for="'+element['id'].counter+'"><span class="labeltext">'+checkbox+'</span>';
					output  += '</label>';
					output  += '</span>';
					counter++;
				});
			break;
			case TEXTAREA:
				output  	+= '<textarea rows="5" cols="55" class="textarea-field" id="'+element['id']+'" name="'+element['id']+'">'+element['value']+'</textarea>';
			break;
			case HTML_EDITOR:
				// wpautop is required https://github.com/Theme-Fusion/Avada/issues/2222
				output  	+= '<textarea rows="5" cols="55" class="html-field" id="'+element['id']+'" name="'+element['id']+'">'+window.switchEditors.wpautop(String(element['value']))+'</textarea>';
			break;
			case ICON_BOX:
				output 		+= "<div class='icon_select_container'>";
				output += element['list'];
				/*jQuery.each (iconsArray,function( iconKey,iconValue) {
					selectedClass = "";
					if(element['value'] == iconValue)
					{
						selectedClass = "selected-element";
					}

					output += '<span class="icon_preview '+selectedClass+'"><i class="fa '+iconValue+'" data-name="'+iconValue+'"></i></span>';
				});*/
				output 	   += '</div>';
				output	 += '<input type="hidden" value="'+element['value']+'" id="'+element['id']+'" name="'+element['id']+'"/>';
			break;
			default :

			break;
		}

		return output;

}

function nl2br (str, is_xhtml) {
	var breakTag 	= (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
	var rt			= (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
	rt				= rt.replace('null','');
	return rt;
}

DdElementParser.DeepCopyElement = function (from, to) {
	if (from == null || typeof from != "object") return from;
	if (from.constructor != Object && from.constructor != Array) return from;
	if (from.constructor == Date || from.constructor == RegExp || from.constructor == Function ||
		from.constructor == String || from.constructor == Number || from.constructor == Boolean)
		return new from.constructor(from);

	to = to || new from.constructor();

	for (var name in from)
	{
		to[name] = typeof to[name] == "undefined" ? DdElementParser.DeepCopyElement(from[name], null) : to[name];
	}

	return to;
}
})(jQuery);