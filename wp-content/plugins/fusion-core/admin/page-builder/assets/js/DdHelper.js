/**
 *
 * Group of helper Javascript functions, added to one object, DdHelper.
 */
(function( $ ) {
    var DdHelper = {};
    window.DdHelper = DdHelper;
    var start_top, stop_top, start_left, stop_left, movement_horiz, movement_verti;
    window.oldParent = null;


    /**
     * Activate dragging for the given DOM element.
     * @param {type} element DOM element that will be dragged.
     */
    DdHelper.activateDragging = function( element ) {
        element.draggable(
            {
                cursor: 'move',
                appendTo: '#fusion-page-builder',
                helper: 'clone',
                zIndex: 1000,
                revert: false,
                scroll: true,
                cursorAt: {left: 20},
                start: function( event, ui ) {
                    var current = $( event.target );
                    //reduce elements opacity so user got a visual feedback on what he is editing
                    current.css( {opacity: 0.4} );
                    //remove all previous fusion-hover-active classes
                    $( '.fusion-hover-active' ).removeClass( 'fusion-hover-active' );
                    //add a class based on element's drop_level to the editor container that highlights all possible drop targets
                    $( "#editor" ).addClass( 'select-target-' + element.data( 'drop_level' ) );
                    //get start position of UI element
                    start_top = ui.position.top;
                    start_left = ui.position.left;

                    //limit height and hide extra

                    //if element dragged from tab
                    if ( ui.helper.hasClass( 'pre_element_block' ) ) {
                        ui.helper.css( 'height', '56' );
                        ui.helper.css( 'width', '66' );

                    } else { //if inside editor movement
                        ui.helper.css( 'height', '100' );
                    }
                    //hide extra
                    ui.helper.css( 'overflow', 'hidden' );

                    $( ui.helper ).addClass( 'fusion-dragging-element' );
                    //get element parent ID
                    window.oldParent = $( this ).parent().parent().attr( "id" );
                },
                stop: function( event, ui ) {
                    //return opacity of element to normal
                    $( event.target ).css( {opacity: 1} );
                    //remove fusion-hover-active class from all elements
                    $( '.fusion-hover-active' ).removeClass( 'fusion-hover-active' );
                    //reset highlight on container class, currently application.css have setting for 4 nested level of element.
                    //if you have more levels, just add it to the application.css like the other select-target
                    $( "#editor" ).removeClass( 'select-target-1 select-target-2 select-target-3 select-target-4' );
                    //patch for full widht to full widht
                    if ( window.oldParent.length > 9 ) {
                        var pElement = app.editor.selectedElements.get( window.oldParent );
                        phpClass = pElement.get( 'php_class' );
                        var cElement = app.editor.selectedElements.get( $( this ).attr( 'id' ) );
                        if ( phpClass == 'TF_FullWidthContainer' && cElement.get( 'childrenId' ).length > 0 ) {
                            $( "#clone-element-" + $( this ).attr( 'id' ) ).trigger( "click" );
                            $( "#delete-element-" + $( this ).attr( 'id' ) ).trigger( "click" );
                        }
                    }


                }
            }
        ).disableSelection();
    }

    /**
     * Activate dropping for DOM element.
     * @param {type} element DOM element that will be dropped.
     */
    DdHelper.activateDropping = function( element ) {
        // If the element contain innerElemener div, i.e. it's already existing element, use the innerElement div as drop placeholder.
        if ( element.find( '.innerElement' ).length ) {
            element = element.find( '.innerElement' );
        }

        // initialize droppable plugin
        element.droppable(
            {
                tolerance: 'pointer',
                greedy: true,

                // if there's a draggable element and it's over the current element, this function will be executed.
                over: function( event, ui ) {
                    var dropable = $( this );
                    // check if the current element can accept the droppable element
                    if ( DdHelper.isDropingAllowed( ui.helper, dropable ) ) {
                        // add active class that will highlight the element with gree, i.e. drop is allowed.
                        dropable.parent().parent().removeClass( 'fusion-hover-active' );
                        dropable.addClass( 'fusion-hover-active' );
                    }
                },

                // if there's a draggable element and it was over the current element, when it moves out this function will be executed.
                out: function( event, ui ) {
                    // remove the highlighted class. i.e. drop is not allowed
                    $( this ).removeClass( 'fusion-hover-active' );
                },

                // when an element is droped in the current element, the following function executed.
                drop: function( event, ui ) {

                    //get end position of UI element
                    stop_top = ui.position.top;
                    stop_left = ui.position.left;
                    //check if movement is right to left or left to right
                    movement_horiz = ( ( start_left < stop_left ) ? 'right' : 'left' );
                    //check if it is bottom to top movement
                    var col_tabs_offset = $( '#Column_options_div .pre_element_block:last' ).offset();
                    var bld_tabs_offset = $( '#Builder_elements_div .pre_element_block:last' ).offset();
                    var active_tab = $( "#tabs" ).tabs( 'option', 'active' );
                    var phpClass = null;
                    var cElement = null;


                    if ( $( "#" + ui.draggable.attr( "id" ) ).length == 0 ) { //if it is new element
                        movement_verti = 'tabs';
                    } else if ( start_top > ( stop_top + 100 ) ) { //if movement is from bottom to top
                        movement_verti = 'top';
                    } else if ( ( start_top + 100 ) < stop_top ) { //if movement is from top to bottom
                        movement_verti = 'bottom';
                    } else {
                        movement_verti = null; //else it is right to left or left to right movement
                    }


                    // indicate if this element is a new element added or existing element just moved.
                    var newElement = false;

                    // the target that we dropped the draggable on
                    var dropable = $( this );

                    //check if the previous check for isDropingAllowed returend true, otherwise do nothing
                    if ( !dropable.is( '.fusion-hover-active' ) ) return false;

                    // get all elements on the droppable area
                    var elements = dropable.find( '>.drag-element' ), offset = {}, method = 'after', toEl = false, position_array = [], last_pos, max_height;

                    var currentKey;
                    //iterate over all elements and check their positions
                    for ( var i = 0; i < elements.length; i++ ) {

                        var current = elements.eq( i );
                        var elID = current.attr( 'id' );
                        var uiID = ui.draggable.attr( 'id' );
                        var offset = current.offset();
                        var second_condition = false;
                        //patch for right to left movement
                        if ( movement_horiz == "left" ) {
                            offset.left += current.outerWidth();
                        }
                        //patch for bottom to bottom movemet
                        if ( movement_verti == null ) {
                            second_condition = ( ( offset.left < ui.offset.left  ) ? true : false );
                        }

                        if ( offset.top < ui.offset.top || second_condition ) {

                            toEl = current;

                            last_pos = offset;
                            //save all items before the draggable to a position array so we can check if the right positioning is important
                            if ( !position_array["top_" + offset.top] ) {
                                max_height = 0;
                                position_array["top_" + offset.top] = [];
                            }
                            max_height = max_height > current.outerHeight() + offset.top ? max_height : current.outerHeight() + offset.top;
                            position_array["top_" + offset.top].push(
                                {
                                    left: offset.left,
                                    top: offset.top,
                                    index: i,
                                    height: current.outerHeight(),
                                    maxheight: current.outerHeight() + offset.top
                                }
                            );

                            if ( elID == uiID ) {
                                currentKey = "top_" + offset.top;
                            }

                        }

                        else {
                            break;
                        }

                    }


                    //if we got multiple matches that all got the same top position we also need to check for the left position
                    if ( last_pos && position_array["top_" + last_pos.top].length > 0 && max_height - 40 > ui.offset.top ) {
                        var real_element = false;
                        var flag = false;

                        var keys = Object.keys( position_array )
                        for ( var i = 0; i < keys.length; i++ ) {
                            for ( var j = 0; j < position_array[keys[i]].length; j++ ) {
                                if ( position_array[keys[i]][j].left < ui.offset.left ) {
                                    real_element = position_array[keys[i]][j].index;
                                    if ( movement_horiz == 'right' && movement_verti == null && currentKey == keys[i] ) {
                                        flag = true;
                                    }
                                }
                                else {
                                    break;
                                }
                            }
                            if ( flag ) {
                                break;
                            }
                        }


                        //if we got an index get that element from the list, else delete the toEL var because we need to append the draggable to the start and the next check will do that for us
                        if ( real_element === false ) {
                            real_element = position_array["top_" + last_pos.top][0].index;
                            method = 'before';
                        }

                        toEl = elements.eq( real_element );


                    }

                    //if no element with higher offset were found there either are no at all or the new position is at the top so we change the params accordingly

                    if ( toEl === false ) {
                        toEl = dropable;

                        method = 'prepend';
                    }

                    //if the draggable and the new el are the same do nothing
                    if ( toEl[0] == ui.draggable[0] ) {
                        return;
                    }

                    // get parent element id
                    var parentId = $( this ).parent().attr( "id" );

                    //get element that needs to be added
                    // if class element_block exist, then it's a new element not existing one.
                    if ( ui.helper.find( '.element_block' ).length ) {
                        // get element id
                        var elementId = ui.helper.find( '.element_block' ).attr( 'id' );
                        // get element object from palette collection
                        var elementObject = app.palette.createElement( elementId );
                        //set index of element
                        elementObject.set( 'index', $( '#' + toEl.attr( "id" ) ).index() + 1 );
                        // get the new element id as the id was changed in method createElement
                        elementId = elementObject.get( 'id' );

                        // if the parent id = "ddbuilder" it means there's no parent, otherwise there's a parent.
                        if ( parentId !== "ddbuilder" ) {
                            // add reference to the parent element in the current elemenet
                            elementObject.set( 'parentId', parentId );
                            // add the current element to the parent element
                            DdHelper.addElementToParent( elementId, parentId );
                        }

                        // add the current element to the editor collection
                        app.editor.selectedElements.add( elementObject );
                        // change draggable object to be the element we just added, not the one that was draged from the palette
                        ui.draggable = $( $( "#" + elementId )[0].outerHTML );
                        // set it's a new element
                        newElement = true;
                    }
                    else {
                        // get element id
                        var elementId = ui.draggable.attr( "id" );
                        // get the model of the current draging element
                        var draggableModel = app.editor.selectedElements.get( elementId );
                        // get the old parentId
                        var oldParentId = window.oldParent;//draggableModel.get('parentId');

                        // if there's an old parent Id and it's not equal the new one, it means the element was moved, and reference to old parent must be removed
                        if ( oldParentId && oldParentId !== parentId ) {

                            //get parent PHP class
                            if ( oldParentId != 'ddbuilder' ) {
                                var pElement = app.editor.selectedElements.get( oldParentId );
                                phpClass = pElement.get( 'php_class' );
                            }
                            var cElement = app.editor.selectedElements.get( elementId );
                            // remove reference from old parent
                            if ( phpClass == 'TF_FullWidthContainer' && cElement.get( 'childrenId' ).length > 0 ) {
                                draggableModel = draggableModel.clone();
                                draggableModel = app.editor.selectedElements.get( draggableModel.get( 'id' ) );
                            }
                            draggableModel.unset( 'parentId' );

                            DdHelper.removeElementFromParent( elementId, oldParentId );
                            oldParentId = null;
                        }

                        // add reference to the new parent
                        if ( (!oldParentId && parentId !== "ddbuilder") || (oldParentId && oldParentId !== parentId) ) {

                            // set parent Id to the child object
                            draggableModel.set( 'parentId', parentId );

                            DdHelper.addElementToParent( elementId, parentId );
                        }
                    }

                    // move the old DOM element to the new position
                    if ( !newElement ) {
                        toEl[method]( ui.draggable );
                    }


                    // ensure that drag and drop is activated for all newly elements
                    if ( newElement ) {

                        if ( method == 'prepend' ) {
                            $( '#' + toEl.attr( 'id' ) ).prepend( $( "#" + elementId ) );
                        } else if ( method == 'after' ) {
                            $( '#' + toEl.attr( 'id' ) ).after( $( "#" + elementId ) );
                        } else {
                            $( '#' + toEl.attr( 'id' ) ).before( $( "#" + elementId ) );
                        }

                        DdHelper.activateDragging( $( ui.draggable ) );
                        DdHelper.activateDropping( $( ui.draggable ) );

                    }

                    // handle the elements width and sorting
                    DdHelper.handleElementWidthAndOrder( false );

                    //update ChidlrendIDs
                    DdHelper.updateChildrenIDs( ui.draggable.attr( "id" ) );
                    // capture editor
                    fusionHistoryManager.captureEditor();
                    //fix for 2nd element drop
                    var elementId = toEl.attr( 'id' );
                    if ( elementId != 'editor' ) {
                        elementId = elementId.replace( "editable-element-", "" );
                        var element = app.editor.selectedElements.get( elementId );
                        var parentId = element.get( 'parentId' );
                        if ( parentId ) {
                            var parent = app.editor.selectedElements.get( parentId );
                            if ( parent.get( 'php_class' ) == 'TF_FullWidthContainer' && method == 'prepend' ) {
                                $( '#editable-element-' + parentId ).droppable( 'destroy' );
                                DdHelper.activateDropping( $( '#' + parentId ) );
                            }
                        }
                    }


                    //fix for 3rd level child-parent relationship
                    if ( phpClass == 'TF_FullWidthContainer' && cElement.get( 'childrenId' ).length > 0 ) {
                        /*//turn off editor tracking first
                         fusionHistoryManager.turnOffTracking();
                         //rerender all elements for deep copy of model
                         var elements = fusionHistoryManager.getAllElementsData();
                         //remove all current editor elements first
                         Editor.deleteAllElements();
                         //reset models with new elements
                         app.editor.selectedElements.reset( JSON.parse(elements) );
                         //turn on tracking now
                         fusionHistoryManager.turnOnTracking();*/

                    }


                },
            }
        );
    }
    /**
     * Update order of children IDs for layout element
     * @returns {NULL}
     */
    DdHelper.updateChildrenIDs = function( elementID ) {
        //get element model
        var element = app.editor.selectedElements.get( elementID );
        //get parent id of element
        var parent = element.get( 'parentId' );
        //if element has parent
        if ( parent != null ) {
            //get parent element
            var parentElement = app.editor.selectedElements.get( parent );
            //get children IDs
            var childrenIDs = parentElement.get( 'childrenId' );
            var elements = [];
            //iterate through each element and get their index on page
            $.each(
                childrenIDs, function( index, value ) {

                    var cElement = [value, $( '#' + value.id ).index()];
                    elements.push( cElement );

                }
            );
            //sort elements by index
            elements.sort(
                function( a, b ) {
                    return a[1] - b[1];
                }
            );
            //remove all existing elements from array
            childrenIDs.splice( 0, childrenIDs.length );

            $.each(
                elements, function( index, value ) {
                    //add this element
                    childrenIDs.push( value[0] );

                }
            );
            //update value
            parentElement.set( 'childrenId', childrenIDs );


        }
    }
    /**
     * Check if the droppable element can accept the draggable element based on attribute "drop_level"
     * @returns {Boolean}
     */
    DdHelper.isDropingAllowed = function( draggable, droppable ) {
        if ( draggable.data( 'drop_level' ) > droppable.data( 'drop_level' ) ) {
            return true;
        }
        return false;
    }


    /**
     * Add element ID reference to a parent
     */
    DdHelper.addElementToParent = function( elementId, parentId ) {
        // get parent model
        var parentModel = app.editor.selectedElements.get( parentId );
        //check if ID already exists
        var is_available = false;
        $.each(
            parentModel.get( 'childrenId' ), function( key, value ) {
                if ( value.id == elementId ) {
                    is_available = true;
                }
            }
        );

        if ( !is_available ) { // if doesnt exist
            parentModel.get( 'childrenId' ).push( {id: elementId} );
        }
        // save parent model
        parentModel.save();


    }

    DdHelper.removeElementFromParent = function( elementId, parentId ) {
        // if there's a parent id proceed
        if ( parentId ) {
            // get the parent model
            var parentModel = app.editor.selectedElements.get( parentId );
            if ( parentModel ) {
                // get children id list .
                var childrenElements = parentModel.get( 'childrenId' );
                //remove the current element from it's parent
                for ( var i = 0; i < childrenElements.length; i++ ) {
                    if ( childrenElements[i]['id'] === elementId ) {
                        childrenElements.splice( i, 1 );
                        break;
                    }
                }
                // set the modefied childrenList
                parentModel.set( 'childrenId', childrenElements );
                // save parent model
                parentModel.save();
            }
        }
    }


    /**
     * Initialize the jquery modal dialog
     */
    DdHelper.initializeModalLightbox = function() {
        var $dialog = $( '#dialog_form' ).dialog(
            {
                autoOpen: false,
                maxHeight: 600,
                width: 900,
                resizable: false,
                draggable: false,
                modal: true,
                dialogClass: 'fusionb-dialog-form',
                buttons: {
                    "SAVE": function() {
                        tinyMCE.triggerSave();

                        $( this ).dialog( 'option', 'referencedView' ).updateElement();

                        $( this ).dialog( "close" );
                    },
                    Cancel: function() {
                        tinyMCE.triggerSave();

                        $( this ).dialog( "close" );
                    }
                },
                close: function() {
                    tinyMCE.triggerSave();
                    $( 'body' ).removeClass( 'noscroll' );
                },
                open: function( event, ui ) {
                    $( this ).parent().find( '.ui-dialog-titlebar' ).find( '.fusion-tabs-menu' ).remove();
                    var menu = $( this ).data( "appendMenu" );
                    if (menu && menu != "") {
                        $( this ).parent().find( '.ui-dialog-titlebar' ).append( menu );
                        $( this ).parent().find( '.ui-dialog-titlebar' ).css('padding-bottom', 0);
                    } else {
                        $( this ).parent().find( '.ui-dialog-titlebar' ).css('padding-bottom', 'inherit');
                    }
                }
            }
        );

        $dialog.data( "uiDialog" )._appendTitle = function( title ) {
            title.html( this.options.title );
        };
    };

    /**
     * Iterate over each element and handle it's order and number of elements in the row according to every element's width
     */
    DdHelper.handleElementWidthAndOrder = function( PublishRequest ) {
        //hide loader
        DdHelper.showHideLoader( 'hide' );

        // Loop through all elements and set them to "no"
        var editorElements = document.querySelectorAll( '#editor .item-wrapper' );
        for ( var z = 0; z < editorElements.length; z++ ) {
            var editorElement = editorElements[z];
            var elementId = editorElement.id;

            if ( elementId ) {
                var element = app.editor.selectedElements.get( elementId );

                if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && editorElement.className.indexOf( 'fusion_full_width' ) == -1 ) {
                    element.get( 'subElements' )[0].value = 'no';
                }
            }
        }

        // Loop through all elements outside of full width container
        var tl_editorElements = document.querySelectorAll( '#editor > .item-wrapper' );
        var width = 0;
        var allElements = new Array();
        var floated_width = 0;

        for ( var i = 0; i < tl_editorElements.length; i++ ) {
            var editorElement = tl_editorElements[i];
            var elementId = editorElement.id;


            if ( elementId ) {
                var element = app.editor.selectedElements.get( elementId );


                var editorElement_2 = tl_editorElements[i - 1];
                if ( typeof editorElement_2 == 'object' ) {
                    var elementId_2 = editorElement_2.id;
                    var element_2 = app.editor.selectedElements.get( elementId_2 );
                }

                var editorElement_3 = tl_editorElements[i + 1];
                if ( typeof editorElement_3 == 'object' ) {
                    var elementId_3 = editorElement_3.id;
                    var element_3 = app.editor.selectedElements.get( elementId_3 );
                }

                var element_floated_width = parseFloat( editorElement.getAttribute( 'data-floated_width' ) );

                if ( element_floated_width && floated_width < 1 ) {
                    floated_width += element_floated_width;
                }

                if ( floated_width > 1 ) {
                    if ( typeof editorElement_2 == 'object' && editorElement_2.className.indexOf( 'fusion_layout_column' ) > -1 && editorElement_2.className.indexOf( 'fusion_full_width' ) == -1 ) { // if an element behind exists, is a layout column but not a full width container
                        element_2.get( 'subElements' )[0].value = 'yes';
                        floated_width = parseFloat( editorElement.getAttribute( 'data-floated_width' ) );
                    }
                    if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && typeof editorElement_3 != 'object' && editorElement.className.indexOf( 'fusion_full_width' ) == -1 ) { // if current element is a layout column but not a full width container, forward element doesn't exist.
                        element.get( 'subElements' )[0].value = 'yes';
                        floated_width = 0;
                    }
                    if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && editorElement.className.indexOf( 'fusion_full_width' ) == -1 && typeof editorElement_3 == 'object' && editorElement_3.className.indexOf( 'fusion_full_width' ) > -1 ) { // if current element is a layout colum but not full width and not element is full width column
                        element.get( 'subElements' )[0].value = 'yes';
                        floated_width = 0;
                    }
                    if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && editorElement.className.indexOf( 'grid_one' ) > -1 ) { // if current element is a one column
                        element.get( 'subElements' )[0].value = 'yes';
                        floated_width = 0;
                    }
                } else if ( floated_width == 1 ) {
                    if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && editorElement.className.indexOf( 'fusion_full_width' ) == -1 ) { // if current element is layout column and not a full width container
                        element.get( 'subElements' )[0].value = 'yes';
                        floated_width = 0;
                    }
                    if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && editorElement.className.indexOf( 'fusion_full_width' ) == -1 && typeof editorElement_3 == 'object' && editorElement_3.className.indexOf( 'fusion_full_width' ) > -1 ) { // if current element is a layout colum but not full width and not element is full width column
                        element.get( 'subElements' )[0].value = 'yes';
                        floated_width = 0;
                    }
                } else {
                    if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && editorElement.className.indexOf( 'fusion_full_width' ) == -1 && (typeof editorElement_3 != 'object' && (typeof editorElement_3 == 'object' && editorElement_3.className.indexOf( 'fusion_layout_column' ) == -1)) ) { // if current element is a layout column and not full width and forward element element doesn't exist, but if forward element is an object, it is not a layout column
                        element.get( 'subElements' )[0].value = 'yes';
                        floated_width = 0;
                    } else if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && typeof editorElement_3 != 'object' && editorElement.className.indexOf( 'fusion_full_width' ) == -1 ) { // if current element is a layout column but not full width and forward element is not an object
                        element.get( 'subElements' )[0].value = 'yes';
                        floated_width = 0;
                    } else if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && typeof editorElement_3 == 'object' && editorElement.className.indexOf( 'fusion_full_width' ) == -1 && editorElement_3.className.indexOf( 'fusion_layout_column' ) == -1 ) { // if current element is a layout column but not full width and forward element exists but is not a layout column
                        element.get( 'subElements' )[0].value = 'yes';
                        floated_width = 0;
                    } else if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && editorElement.className.indexOf( 'fusion_full_width' ) == -1 && typeof editorElement_3 == 'object' && editorElement_3.className.indexOf( 'fusion_full_width' ) > -1 ) { // if current element is a layout colum but not full width and not element is full width column
                        element.get( 'subElements' )[0].value = 'yes';
                        floated_width = 0;
                    } else {
                        if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && editorElement.className.indexOf( 'fusion_full_width' ) == -1 ) { // if current element is a layout column and not full width
                            element.get( 'subElements' )[0].value = 'no';
                        } else {
                            floated_width = 0;
                        }
                    }
                }
            }
            //for last layout column before builder element
            if ( ( i + 1 ) != tl_editorElements.length ) { //so this is not last
                //layout column and next is builder element
                if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && editorElement.className.indexOf( 'fusion_full_width' ) == -1 && tl_editorElements[i + 1].className.indexOf( 'fusion_element_box' ) > -1 ) {
                    element.get( 'subElements' )[0].value = 'yes';
                }
            }
        }


        // Loop through all elements inside of full width container
        var full_width_elements = document.querySelectorAll( '#editor > .fusion_full_width' );

        for ( var i = 0; i < full_width_elements.length; i++ ) {
            var full_width_id = full_width_elements[i].id;
            var tl_editorElements = document.querySelectorAll( '#' + full_width_id + ' > .innerElement > .item-wrapper' );
            var width = 0;
            var allElements = new Array();
            var floated_width = 0;

            for ( var j = 0; j < tl_editorElements.length; j++ ) {
                var editorElement = tl_editorElements[j];
                var elementId = editorElement.id;

                if ( elementId ) {
                    var element = app.editor.selectedElements.get( elementId );


                    var editorElement_2 = tl_editorElements[j - 1];
                    if ( typeof editorElement_2 == 'object' ) {
                        var elementId_2 = editorElement_2.id;
                        var element_2 = app.editor.selectedElements.get( elementId_2 );
                    }

                    var editorElement_3 = tl_editorElements[j + 1];
                    if ( typeof editorElement_3 == 'object' ) {
                        var elementId_3 = editorElement_3.id;
                        var element_3 = app.editor.selectedElements.get( elementId_3 );
                    }

                    var element_floated_width = parseFloat( editorElement.getAttribute( 'data-floated_width' ) );


                    if ( element_floated_width && floated_width < 1 ) {
                        floated_width += element_floated_width;
                    }


                    if ( floated_width > 1 ) {
                        if ( typeof editorElement_2 == 'object' && editorElement_2.className.indexOf( 'fusion_layout_column' ) > -1 && editorElement_2.className.indexOf( 'fusion_full_width' ) == -1 ) { // if an element behind exists, is a layout column but not a full width container
                            element_2.get( 'subElements' )[0].value = 'yes';
                            floated_width = parseFloat( editorElement.getAttribute( 'data-floated_width' ) );
                        }
                        if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && typeof editorElement_3 != 'object' && editorElement.className.indexOf( 'fusion_full_width' ) == -1 ) { // if current element is a layout column but not a full width container, forward element doesn't exist.
                            element.get( 'subElements' )[0].value = 'yes';
                            floated_width = 0;
                        }
                        if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && editorElement.className.indexOf( 'fusion_full_width' ) == -1 && typeof editorElement_3 == 'object' && editorElement_3.className.indexOf( 'fusion_full_width' ) > -1 ) { // if current element is a layout colum but not full width and not element is full width column
                            element.get( 'subElements' )[0].value = 'yes';
                            floated_width = 0;
                        }
                        if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && editorElement.className.indexOf( 'grid_one' ) > -1 ) { // if current element is a one column
                            element.get( 'subElements' )[0].value = 'yes';
                            floated_width = 0;
                        }
                    } else if ( floated_width == 1 ) {
                        if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && editorElement.className.indexOf( 'fusion_full_width' ) == -1 ) { // if current element is layout column and not a full width container
                            element.get( 'subElements' )[0].value = 'yes';
                            floated_width = 0;
                        }
                        if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && editorElement.className.indexOf( 'fusion_full_width' ) == -1 && typeof editorElement_3 == 'object' && editorElement_3.className.indexOf( 'fusion_full_width' ) > -1 ) { // if current element is a layout colum but not full width and not element is full width column
                            element.get( 'subElements' )[0].value = 'yes';
                            floated_width = 0;
                        }
                    } else {
                        if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && editorElement.className.indexOf( 'fusion_full_width' ) == -1 && (typeof editorElement_3 != 'object' && (typeof editorElement_3 == 'object' && editorElement_3.className.indexOf( 'fusion_layout_column' ) == -1)) ) { // if current element is a layout column and not full width and forward element element doesn't exist, but if forward element is an object, it is not a layout column
                            element.get( 'subElements' )[0].value = 'yes';
                            floated_width = 0;
                        } else if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && typeof editorElement_3 != 'object' && editorElement.className.indexOf( 'fusion_full_width' ) == -1 ) { // if current element is a layout column but not full width and forward element is not an object
                            element.get( 'subElements' )[0].value = 'yes';
                            floated_width = 0;
                        } else if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && typeof editorElement_3 == 'object' && editorElement.className.indexOf( 'fusion_full_width' ) == -1 && editorElement_3.className.indexOf( 'fusion_layout_column' ) == -1 ) { // if current element is a layout column but not full width and forward element exists but is not a layout column
                            element.get( 'subElements' )[0].value = 'yes';
                            floated_width = 0;
                        } else if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && editorElement.className.indexOf( 'fusion_full_width' ) == -1 && typeof editorElement_3 == 'object' && editorElement_3.className.indexOf( 'fusion_full_width' ) > -1 ) { // if current element is a layout colum but not full width and not element is full width column
                            element.get( 'subElements' )[0].value = 'yes';
                            floated_width = 0;
                        } else {
                            if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && editorElement.className.indexOf( 'fusion_full_width' ) == -1 ) { // if current element is a layout column and not full width
                                element.get( 'subElements' )[0].value = 'no';
                            } else {
                                floated_width = 0;
                            }
                        }
                    }
                    //for last layout column before builder element
                    if ( ( j + 1 ) != tl_editorElements.length ) { //so this is not last
                        //layout column and next is builder element
                        if ( editorElement.className.indexOf( 'fusion_layout_column' ) > -1 && tl_editorElements[j + 1].className.indexOf( 'fusion_element_box' ) > -1 ) {
                            element.get( 'subElements' )[0].value = 'yes';
                        }
                    }
                }
            }
        }

        // capture editor
        fusionHistoryManager.captureEditor();

    };
    /**
     * send editor content to sever and get builder elements JSON
     * @param null
     * @returns null.
     */
    DdHelper.shortCodestoBuilderElements = function() {
        var content = null;

        if ( typeof tinyMCE.get( 'content' ) == 'object' && tinyMCE.get( 'content' ) != null ) {
            tinyMCE.get( 'content' ).focus()
        }

        tinyMCE.triggerSave();

        if ( window.fusion_builder_initial_load ) {
            content = $( '.fusion-builder-pagecontent' ).val();
            window.fusion_builder_initial_load = false;
        } else {
            content = $( '#content' ).val();
        }

        if ( content.length > 0 ) {
            //show loader
            DdHelper.showHideLoader( 'show', '' );
            var data = {
                action: 'fusion_content_to_elements',
                content: content
            };
            $.post(
                ajaxurl, data, function( response ) {
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
                    DdHelper.showHideLoader( 'hide' );

                    window.fusion_builder_fully_loaded = true;
                }
            );
        } else {
            window.fusion_builder_fully_loaded = true;
        }

    }
    /**
     * Load data for custom tabs
     * @param null
     * @returns null.
     */
    DdHelper.loadCustomTabs = function() {
        instance = jQuery( '#fusion-page-builder' ).attr( 'instance' ); //post/page ID
        var data = {
            action: 'fusion_custom_tabs',
            instance: instance,
            post_action: 'get_custom_and_prebuilt_templates'
        };

        $.post(
            ajaxurl, data, function( response ) {
                //add custom templates
                if ( response.hasOwnProperty( 'custom_templates' ) ) { //if we got data
                    $( '#custom_templates_div' ).html( response.custom_templates );
                }
                // pre built template
                if ( response.hasOwnProperty( 'prebuilt_templates' ) ) { //if we got data
                    $( '#Pre_built_templates_div' ).html( response.prebuilt_templates );
                }


            }
        );
    };

    /**
     * Show or hide loader ovelay
     * @param state whether show or hide
     * @param message ovelay content message
     * @returns null
     */
    DdHelper.showHideLoader = function( state, message ) {

        if ( state == "show" ) {
            jQuery( '.loading_img' ).html( '<p>' + message + '</p>' );
            jQuery( '#fusion_loading_section' ).show();
        } else if ( state == "hide" ) {
            jQuery( '#fusion_loading_section' ).hide();
        }
    }
    /**
     * Fix for fullwidth -> layout -> element
     * @param refView of dialof
     * @returns null
     */
    DdHelper.RemoveDuplicates = function( refView ) {

        var prentId = refView.model.attributes.parentId;
        if ( $( "#" + prentId ).hasClass( "fusion_full_width" ) ) {
            app.editor.reRender();
        }
    }
    DdHelper.cloneChildElement = function( element, parentId ) {
        var addedElement = element.clone();
        var generatedElementId = Math.guid();
        var childrenId = new Array();
        addedElement.set( "id", generatedElementId );
        if ( parentId ) {
            addedElement.set( "parentId", parentId );
        }
        app.editor.selectedElements.add( addedElement );
        return addedElement;
    }
    /**
     * Clone element, make sure that all its children are cloned as well.
     * @param element element to be cloned
     * @param parentId new parent Id for this element if exist.
     * @returns cloned element.
     */
    DdHelper.cloneElement = function( element, parentId ) {

        var addedElement = element.clone();
        var generatedElementId = Math.guid();
        var childrenId = new Array();
        addedElement.set( "id", generatedElementId );

        if ( parentId ) {
            addedElement.set( "parentId", parentId );
        } else if ( addedElement.get( 'parentId' ) ) {
            var parent = app.editor.selectedElements.get( addedElement.get( 'parentId' ) );
            var childrens = parent.get( 'childrenId' );
            childrens.push( {id: generatedElementId} );
        }

        app.editor.selectedElements.add( addedElement );
        addedElement = app.editor.selectedElements.get( generatedElementId );

        for ( var i = 0; i < addedElement.get( 'childrenId' ).length; i++ ) {
            var elementId = addedElement.get( 'childrenId' )[i];

            var childElement = app.editor.selectedElements.get( elementId );

            if ( childElement ) {
                //clone lement
                var nChildElement = childElement.clone();

                //get and update ID
                var newID = Math.guid();
                nChildElement.set( "id", newID );
                nChildElement.set( "parentId", addedElement.get( "id" ) );
                //add element to model
                app.editor.selectedElements.add( nChildElement );
                //update Children IDs
                childrenId.push( {id: newID} );

                //for nested children
                var nchildrenId = new Array();
                nestedChildren = nChildElement.get( 'childrenId' );
                $.each(
                    nestedChildren, function( index, value ) {

                        var nestedChildElement = app.editor.selectedElements.get( value.id );
                        if ( nestedChildElement ) {
                            //clone lement
                            var nNestedChildElement = nestedChildElement.clone();
                            //get and update ID
                            var newID = Math.guid();
                            nNestedChildElement.set( "id", newID );
                            nNestedChildElement.set( "parentId", nChildElement.get( "id" ) );
                            //add element to model
                            app.editor.selectedElements.add( nNestedChildElement );
                            nchildrenId.push( {id: newID} );
                        }
                    }
                );
                //update children IDs for nested element
                nChildElement.set( 'childrenId', nchildrenId );

            }

        }

        if ( childrenId.length > 0 ) {

            addedElement.set( 'childrenId', childrenId );
        }

        return generatedElementId;

    }
})( jQuery );