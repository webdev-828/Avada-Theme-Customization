/*global jQuery, document, avadaredux*/

(function( $ ) {
    "use strict";

    avadaredux.field_objects = avadaredux.field_objects || {};
    avadaredux.field_objects.date = avadaredux.field_objects.date || {};

    $( document ).ready(
        function() {
            //avadaredux.field_objects.date.init();
        }
    );

    avadaredux.field_objects.date.init = function( selector ) {
        if ( !selector ) {
            selector = $( document ).find( '.avadaredux-container-date:visible' );
        }
        $( selector ).each(
            function() {
                var el = $( this );
                var parent = el;
                if ( !el.hasClass( 'avadaredux-field-container' ) ) {
                    parent = el.parents( '.avadaredux-field-container:first' );
                }
                if ( parent.is( ":hidden" ) ) { // Skip hidden fields
                    return;
                }
                if ( parent.hasClass( 'avadaredux-field-init' ) ) {
                    parent.removeClass( 'avadaredux-field-init' );
                } else {
                    return;
                }
//                        var someArr = []
//                        someArr = i;
//                        console.log(someArr);

//                var str = JSON.parse('{"avadaredux_demo[opt-multi-check]":{"avadaredux_demo[opt-multi-check][1]":"1","avadaredux_demo[opt-multi-check][2]":"","avadaredux_demo[opt-multi-check][3]":""}}');
//                console.log (str);
//
//                $.each(str, function(idx, val){
//                    var tmpArr = new Object();
//                    var count = 1;
//
//                    $.each(val, function (i, v){
//
//                        tmpArr[count] = v;
//                        count++;
//                    });
//
//                    var newArr = {};
//                    newArr[idx] = tmpArr;
//                    var newJSON = JSON.stringify(newArr)
//                    //console.log(newJSON);
//                });

                el.find( '.avadaredux-datepicker' ).each( function() {

                    $( this ).datepicker({
                        beforeShow: function(input, instance){
                            var el = $('#ui-datepicker-div');
                            //$.datepicker._pos = $.datepicker._findPos(input); //this is the default position
                            var popover = instance.dpDiv;
                            $('.avadaredux-container:first').append(el);
                            $('#ui-datepicker-div').hide();
                            setTimeout(function() {
                                popover.position({
                                    my: 'left top',
                                    at: 'left bottom',
                                    collision: 'none',
                                    of: input
                                });
                            }, 1);
                        }
                    });
                });
            }
        );


    };
})( jQuery );
