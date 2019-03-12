/*
 * This is the Canvas placeholder which contains all selected elements
 */
(function( $ ) {
    $.fn.outerHTML = function() {
        return (!this.length) ? this : (this[0].outerHTML || (function( el ) {
            var div = document.createElement( 'div' );
            div.appendChild( el.cloneNode( true ) );
            var contents = div.innerHTML;
            div = null;
            return contents;
        })( this[0] ));

    }

    var Editor = {};
    window.Editor = Editor;
    window['fusion_builder_tinymce_count'] = 1;
    // Selected element model
    Editor.ElementEntry = Backbone.Model.extend(
        {
            urlRoot: ajaxurl
        }
    );

    // Selected Element collection, it act as array for all selected elements
    Editor.SelectedElements = Backbone.Collection.extend(
        {
            model: Editor.ElementEntry,
            comparator: function( collection ) {
                // order collection by property "index"
                return (collection.get( 'index' ));
            },
            url: ajaxurl
        }
    );


    Editor.Display = Backbone.View.extend(
        {
            initialize: function() {

                this.selectedElements = new Editor.SelectedElements();

                // bind reset event to render function. i.e. when the collection is fetched this event is fired.
                this.selectedElements.bind( "reset", this.render, this );
                // bind add event to reRender function. i.e. when element is added this event is fired,
                // reRendring is needed to remove old elements as some element might be changed in the server and the view is not aware of that.
                this.selectedElements.bind( "add", this.reRender, this );

            },
            render: function() {

                this.selectedElements.each(
                    function( element ) {
                        var elementPlaceholder = new Editor.ElementPlaceholder( {model: element} );
                        var renderedElement = elementPlaceholder.render();

                        // if element contain parentId, it means it's a child and should not be rendered at this step.
                        if ( !element.get( 'parentId' ) ) {
                            this.$el.append( renderedElement.el );
                        }
                    }, this
                );

                return this;
            },
            reRender: function() {
                this.selectedElements.each(
                    function( element ) {
                        $( "#" + element.get( 'id' ) ).remove();
                    }, this
                );
                this.render();

                return this;
            }
        }
    );

    Editor.deleteAllElements = function() {
        var editorElements = $( "#editor" ).find( '.item-wrapper' );
        for ( var i = 0; i < editorElements.length; i++ ) {
            var editorElement = $( editorElements[i] );
            var elementId = editorElement.attr( 'id' );
            if ( elementId ) {
                var element = app.editor.selectedElements.get( elementId );
                //$("#"+elementId).remove();
                $( "#delete-element-" + elementId ).trigger( "click" ); // trigger click event
            }
        }
    },
        // Selected Element view object
        Editor.ElementPlaceholder = Backbone.View.extend(
            {
                // tag name for the element is div, it means each element will be surrounding by <div></div>
                tagName: 'div',
                // classes for the element div
                className: 'item-wrapper sortable-element drag-element',

                initialize: function() {
                    // use template that has name "content-child-div-template"
                    this.template = window.HandlebarsLoadTemplate( 'content-child-div' );
                    // find render function to the change event, for example when any attribute in element object is changed
                    this.model.on( 'change', this.render, this );

                },
                events: function() {
                    var _events = {};
                    // When any of the below element's id is clicked the corresponding method will be executed
                    _events["click " + "#increase-width-" + this.model.get( 'id' )] = "increaseWidth";
                    _events["click " + "#decrease-width-" + this.model.get( 'id' )] = "decreaseWidth";
                    _events["click " + "#delete-element-" + this.model.get( 'id' )] = "deleteElement";
                    _events["click " + "#edit-element-" + this.model.get( 'id' )] = "openEditPanel";
                    _events["click " + "#clone-element-" + this.model.get( 'id' )] = "cloneElement";

                    return _events;
                },
                render: function() {

                    $( this.el ).attr( 'id', this.model.get( 'id' ) );
                    $( this.el ).addClass( this.model.get( 'css_class' ) );
                    $( this.el ).html( this.template( this.model.toJSON() ) );

                    // Get model "data" array and loop over it and add it to the DOM element with prefix "data-"
                    var dataArray = this.model.get( 'data' );
                    if ( dataArray ) {
                        for ( var index in dataArray ) {
                            $( this.el ).attr( "data-" + index, dataArray[index] );
                            $( this.el ).find( '.innerElement' ).attr( "data-" + index, dataArray[index] );
                        }
                    }

                    // get all children elements and render them
                    if ( this.model.get( 'childrenId' ) ) {
                        var childrenId = this.model.get( 'childrenId' );
                        var childrenElements = new Editor.SelectedElements();
                        for ( var i = 0; i < childrenId.length; i++ ) {
                            var elementId = childrenId[i];
                            var element = app.editor.selectedElements.get( elementId );
                            if ( element ) {
                                childrenElements.add( element );
                            }
                        }

                        childrenElements.each(
                            function( element ) {
                                var elementOutput = new Editor.ElementPlaceholder( {model: element} ).render().el;

                                $( this.el ).children().closest( '.innerElement' ).append( elementOutput );
                            }, this
                        );
                    }

                    //update previews
                    fusionPreview.updatePreview( this, this.model, this.model.get( 'subElements' ) );

                    this.activateDragging( $( this.el ) );
                    this.activateDropping( $( this.el ) );

                    return this;
                },
                openEditPanel: function() {

                    // generate form html with help of currnt model data
                    DdElementParser.generateHtml( this.model );
                    var ElementName = this.model.get( 'name' );
                    this.model.attributes.newElements = [];

                    // add the element property "editPanel_innerHtml" to the jquery modal dialog
                    document.getElementById('dialog_form').innerHTML = this.model.get('editPanel_innerHtml');

                    var $dialog = $( document.getElementById( 'dialog_form' ) );

                    // change dialog title
                    $dialog.dialog( 'option', 'title', this.model.get( 'name' ) );

                    var $appendMenu = "";
                    if ( this.model.get( 'editPanel_appendtoTitle' ) && this.model.get( 'editPanel_appendtoTitle' ) != "" ) {
                        $appendMenu = this.model.get( 'editPanel_appendtoTitle' );
                    }


                    // add the current element object to the dialog as a reference for later use
                    $dialog.dialog( 'option', 'referencedView', this );
                    // add noscroll css class to prevent scrolling the page while editing any element
                    $( 'body' ).addClass( 'noscroll' );
                    // open the dialog
                    $dialog.data( 'appendMenu', $appendMenu ).dialog( "open" );
                    //if slider type element then perform relative operations to show/hide elements w.r.t image/video
                    if ( ElementName == "Slider" ) {
                        $.each(
                            $( "select[name*='fusion_slider_type']" ), function( key, value ) {
                                var selectValue = $( value ).val();
                                var parent = $( value ).parent().parent().parent();
                                if ( selectValue == "video" ) {
                                    $( parent ).find( '.funsion-element-child' ).hide();
                                    $( parent ).find( "select[name*='fusion_slider_type']" ).parent().parent().show();
                                    $( parent ).find( "[name*='video_content']" ).parent().parent().show();
                                } else {
                                    $( parent ).find( "[name*='video_content']" ).parent().parent().hide();
                                }
                            }
                        );
                    }

                    //activae color picker
                    if ( $( '.fusion-color-field' ).length > 0 ) {
                        $dialog.find( '.fusion-color-field' ).wpColorPicker();

                    }
                    //for jQuery chosen
                    if ( $( '.chosen-select' ).length > 0 ) {
                        $( '.chosen-select' ).chosen(
                            {
                                placeholder_text_multiple: 'Select Options'
                            }
                        );
                    }
                    //replace text area with wp_editor if element is text block
                    if ( typeof( tinyMCE ) == "object" && typeof( tinyMCE.execCommand ) == "function" ) {
                        $dialog.find( ".html-field" ).each(
                            function() {
                                $( this ).attr( 'id', 'fusion_content_wp_' + window['fusion_builder_tinymce_count'] );
                                window['fusion_builder_tinymce_count']++;
                                var value = $( this ).val();
                                $( this ).wp_editor( value );
                            }
                        );
                    }
                    // icons
                    $( '.icon_select_container' ).each(
                        function() {
                            var icon_name = $( this ).parents( '.element-type' ).find( 'input[type=hidden]' ).val();
                            if ( icon_name ) {
                                $( this ).find( '.' + icon_name ).addClass( 'selected-element' );
                            }
                        }
                    );

                },
                updateElement: function() {

                    // get form data
                    var subelements = $( '#element-edit-form' ).serializeArray();


                    var newElements = this.model.get( 'newElements' );
                    this.model.attributes.newElements = [];
                    var originalSubElements = this.model.get( 'subElements' );

                    //clean data before updatins elements
                    for ( var t = 0; t < originalSubElements.length; t++ ) {
                        if ( originalSubElements[t]['type'] == 'addmore' ) {
                            //add new elements
                            for ( var j = 0; j < newElements.length; j++ ) {
                                originalSubElements[t]['elements'].push( newElements[j] );
                            }

                            //for add more
                            var children = new Array();
                            var regex = '/([)/g';

                            //get children IDs from form elements
                            for ( var i = 0; i < subelements.length; i++ ) {
                                if ( subelements[i]['name'].indexOf( '[' ) !== -1 ) {
                                    var intVal = subelements[i]['name'].replace( /[^\d.]/g, '' );
                                    if ( $.inArray( intVal, children ) > -1 ) {
                                        //already in array
                                    } else {
                                        children.push( intVal );
                                    }
                                }
                            }

                            var firstElement = JSON.stringify( originalSubElements[t]['elements'][0] );
                            //empty sub elements
                            originalSubElements[t]['elements'] = [];
                            for ( var j = 0; j < children.length; j++ ) {
                                originalSubElements[t]['elements'][j] = JSON.parse( firstElement );
                                //get ID
                                var eVal = originalSubElements[t]['elements'][j][0]['id'].replace( /[^\d.]/g, '' );
                                //update IDs as per form elements
                                for ( k = 0; k < originalSubElements[t]['elements'][j].length; k++ ) {
                                    if ( originalSubElements[t]['elements'][j][k] !== undefined && originalSubElements[t]['elements'][j][k] !== undefined ) {

                                        originalSubElements[t]['elements'][j][k]['id'] =
                                            originalSubElements[t]['elements'][j][k]['id'].replace(
                                                '[' + eVal + ']', '[' + children[j] + ']'
                                            );
                                    }
                                }
                            }
                        } else if ( originalSubElements[t]['type'] == 'multiselect' ) {
                            originalSubElements[t]['value'] = [];
                        }
                    }

                    //clean data code ends here
                    for ( var i = 0; i < subelements.length; i++ ) {
                        for ( var t = 0; t < originalSubElements.length; t++ ) {
                            if ( originalSubElements[t]['type'] == 'addmore' ) {

                                for ( j = 0; j < originalSubElements[t]['elements'].length; j++ ) {
                                    for ( k = 0; k < originalSubElements[t]['elements'][j].length; k++ ) {
                                        if ( originalSubElements[t]['elements'][j][k]['id'] === subelements[i]['name'] ) {
                                            originalSubElements[t]['elements'][j][k]['value'] = subelements[i]['value'].replace( '</textarea', '&lt;/textarea' );
                                        }
                                    }
                                }
                            }
                            else if ( subelements[i]['name'] === originalSubElements[t]['id'] ) {
                                var tempPlaceholder = originalSubElements[t];
                                if ( originalSubElements[t]['type'] == 'multiselect' ) {
                                    tempPlaceholder['value'].push( subelements[i]['value'].replace( '</textarea', '&lt;/textarea' ) );
                                } else {
                                    tempPlaceholder['value'] = subelements[i]['value'].replace( '</textarea', '&lt;/textarea' );
                                }


                            }
                        }
                    }

                    //update previews
                    fusionPreview.updatePreview( this, this.model, originalSubElements );

                    // capture editor
                    fusionHistoryManager.captureEditor();

                },
                cloneElement: function() {

                    var newElementID = DdHelper.cloneElement( this.model );
                    var parent = this.model.get( 'parentId' );
                    if ( parent != null ) {
                        //get parent element
                        var parentElement = app.editor.selectedElements.get( parent );
                        if ( parentElement.get( 'css_class' ).indexOf( 'fusion_full_width' ) > -1 ) {
                            //update ChidlrendIDs
                            DdHelper.updateChildrenIDs( this.model.get( 'id' ) );
                        }
                    }


                    //turn off editor tracking first
                    fusionHistoryManager.turnOffTracking();
                    //re-render all elements for deep copy of model
                    var elements = fusionHistoryManager.getAllElementsData();
                    //remove all current editor elements first
                    Editor.deleteAllElements();
                    //reset models with new elements
                    app.editor.selectedElements.reset( JSON.parse( elements ) );
                    //turn on tracking now
                    fusionHistoryManager.turnOnTracking();
                    // capture editor
                    fusionHistoryManager.captureEditor();

                    return newElementID;
                },

                increaseWidth: function() {
                    this.changeElementSize( 1 );
                },
                decreaseWidth: function() {
                    this.changeElementSize( -1 );
                },
                changeElementSize: function( direction ) {

                    var currentModel = this.model;
                    var dataObject = currentModel.get( "data" );
                    var currentSize = dataObject['width'];
                    var currentElement;
                    var nextElement;


                    var columnSizes = [
                        // phpClass, elementName, specific cssClass, width, base
                        ['TF_GridOne', '1/1', 'grid_one', '6/6', 'one_full'],
                        ['TF_GridFiveSix', '5/6', 'grid_five_sixth', '5/6', 'five_sixth'],
                        ['TF_GridFourFifth', '4/5', 'grid_four_fifth', '4/5', 'four_fifth'],
                        ['TF_GridThreeFourth', '3/4', 'grid_three_fourth', '3/4', 'three_fourth'],
                        ['TF_GridTwoThird', '2/3', 'grid_two_third', '2/3', 'two_third'],
                        ['TF_GridThreeFifth', '3/5', 'grid_three_fifth', '3/5', 'three_fifth'],
                        ['TF_GridTwo', '1/2', 'grid_two', '2', 'one_half'],
                        ['TF_GridTwoFifth', '2/5', 'grid_two_fifth', '2/5', 'two_fifth'],
                        ['TF_GridThree', '1/3', 'grid_three', '3', 'one_third'],
                        ['TF_GridFour', '1/4', 'grid_four', '4', 'one_fourth'],
                        ['TF_GridFive', '1/5', 'grid_five', '5', 'one_fifth'],
                        ['TF_GridSix', '1/6', 'grid_six', '6', 'one_sixth'],
                    ];

                    for ( var i = 0; i < columnSizes.length; i++ ) {
                        if ( columnSizes[i][3] === currentSize ) {
                            currentElement = columnSizes[i];
                            nextElement = columnSizes[i - direction];
                            break;
                        }
                    }

                    if ( nextElement ) {
                        // update element width
                        dataObject['width'] = nextElement[3];
                        dataObject['floated_width'] = eval( nextElement[1] ).toFixed( 2 );
                        currentModel.attributes.data = dataObject;
                        $( this.el ).data( 'width', nextElement[3] ).find( '.innerElement' ).data(
                            'width', nextElement[3]
                        );

                        // update element css class
                        $( this.el ).removeClass( currentElement[2] );
                        $( this.el ).removeClass( currentElement[2] );

                        var cssClass = currentModel.get( "css_class" );
                        cssClass = cssClass.replace( currentElement[2], nextElement[2] );
                        currentModel.attributes.css_class = cssClass;

                        // update element php class
                        currentModel.attributes.php_class = nextElement[0];

                        // update element name
                        currentModel.attributes.name = nextElement[1];

                        // update base
                        currentModel.attributes.base = nextElement[4];

                        var dataArray = currentModel.get( 'data' );
                        if ( dataArray ) {
                            for ( var index in dataArray ) {
                                $( this.el ).attr( "data-" + index, dataArray[index] );
                                $( this.el ).find( '.innerElement' ).attr( "data-" + index, dataArray[index] );
                            }
                        }

                        $( this.el ).attr( 'id', currentModel.get( 'id' ) );
                        $( this.el ).addClass( currentModel.get( 'css_class' ) );
                        $( this.el ).find( '.grid_width' ).text( nextElement[1] );

                        this.model = currentModel;

                        // capture editor
                        fusionHistoryManager.captureEditor();

                    }
                },

                deleteElement: function() {

                    var elementId = this.model.get( 'id' );
                    $( "#" + elementId ).remove();
                    // Check if the element has parent, and remove the reference to the element
                    DdHelper.removeElementFromParent( elementId, this.model.get( 'parentId' ) );
                    this.model.destroy(
                        {
                            error: function( errorResponse, errorDescription ) {

                                //alert("Error during removing element "+errorDescription.responseText)
                            }
                        }
                    );
                    //patch for dropping when last item is removed
                    if ( $( '#editor div' ).length == 0 ) {
                        $( '#editor' ).droppable( 'destroy' );
                        this.activateDropping( $( '#editor' ) );
                    }
                    // capture editor
                    fusionHistoryManager.captureEditor();
                },
                activateDragging: function( element ) {
                    DdHelper.activateDragging( element );
                },
                activateDropping: function( element ) {
                    DdHelper.activateDropping( element );
                },

            }
        );
})( jQuery );