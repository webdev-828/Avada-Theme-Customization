/*
* Previews of builder elements
*/
( function($) {

	var fusionPreview		= {};
	window.fusionPreview 	= fusionPreview;
	/**
	* Caller for respective element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updatePreview = function ( thisRef, model, subElements ) {
		if(typeof model.get('css_class') != 'undefined' && model.get('css_class').indexOf('fusion_layout_column') > -1) {
			return;
		}

		switch( model.get( 'php_class' ) ) { //switch case on name of element
			case 'TF_AlertBox':
				fusionPreview.updateAlertPreview( thisRef, model, subElements );
			break;

			case 'TF_WpBlog':
				fusionPreview.updateBlogPreview( thisRef, model, subElements );
			break;

			case 'TF_ButtonBlock':
				fusionPreview.updateButtonPreview( thisRef, model, subElements );
			break;

			case 'TF_CheckList':
				fusionPreview.updateChecklistPreview( thisRef, model, subElements );
			break;

			case 'TF_ClientSlider':
				fusionPreview.updateClientSliderPreview( thisRef, model, subElements );
			break;

			case 'TF_ContentBoxes':
				fusionPreview.updateContentBoxesPreview( thisRef, model, subElements );
			break;

			case 'TF_CountDown':
				fusionPreview.updateCountdownPreview( thisRef, model, subElements );
			break;

			case 'TF_CounterBox':
				fusionPreview.updateCounterBoxPreview( thisRef, model, subElements );
			break;

			case 'TF_FlipBoxes':
				fusionPreview.updateFlipBoxesPreview( thisRef, model, subElements );
			break;

			case 'TF_FontAwesome':
				fusionPreview.updateFontAwesomePreview( thisRef, model, subElements );
			break;

			case 'TF_FusionSlider':
				fusionPreview.updateFusionSliderPreview( thisRef, model, subElements );
			break;

			case 'TF_GoogleMap':
				fusionPreview.updateGoogleMapPreview( thisRef, model, subElements );
			break;

			case 'TF_ImageFrame':
				fusionPreview.updateImageFramePreview( thisRef, model, subElements );
			break;

			case 'TF_ImageCarousel':
				fusionPreview.updateImageCarouselPreview( thisRef, model, subElements );
			break;

			case 'TF_LayerSlider':
				fusionPreview.updateLayerSliderPreview( thisRef, model, subElements );
			break;

			case 'TF_LightBox':
				fusionPreview.updateLightBoxPreview( thisRef, model, subElements );
			break;

			case 'TF_Login':
				fusionPreview.updateLoginPreview( thisRef, model, subElements );
			break;

			case 'TF_MenuAnchor':
				fusionPreview.updateMenuAnchorPreview( thisRef, model, subElements );
			break;

			case 'TF_Modal':
				fusionPreview.updateModalPreview( thisRef, model, subElements );
			break;

			case 'TF_Person':
				fusionPreview.updatePersonPreview( thisRef, model, subElements );
			break;

			case 'TF_PostSlider':
				fusionPreview.updatePostSliderPreview( thisRef, model, subElements );
			break;

			case 'TF_PricingTable':
				fusionPreview.updatePricingTablePreview( thisRef, model, subElements );
			break;

			case 'TF_ProgressBar':
				fusionPreview.updateProgressBarPreview( thisRef, model, subElements );
			break;

			case 'TF_RecentPosts':
				fusionPreview.updateRecentPostsPreview( thisRef, model, subElements );
			break;

			case 'TF_RecentWorks':
				fusionPreview.updateRecentWorksPreview( thisRef, model, subElements );
			break;

			case 'TF_RevolutionSlider':
				fusionPreview.updateRevSliderPreview( thisRef, model, subElements );
			break;

			case 'TF_Separator':
				fusionPreview.updateSeparatorPreview( thisRef, model, subElements );
			break;

			case 'TF_SharingBox':
				fusionPreview.updateSharingBoxPreview( thisRef, model, subElements );
			break;

			case 'TF_Slider':
				fusionPreview.updateSliderPreview( thisRef, model, subElements );
			break;

			case 'TF_SoundCloud':
				fusionPreview.updateSoundCloudPreview( thisRef, model, subElements );
			break;

			case 'TF_Tabs':
				fusionPreview.updateTabsPreview( thisRef, model, subElements );
			break;

			case 'TF_Table':
				fusionPreview.updateTablePreview( thisRef, model, subElements );
			break;

			case 'TF_TaglineBox':
				fusionPreview.updateTaglineBoxPreview( thisRef, model, subElements );
			break;

			case 'TF_Testimonial':
				fusionPreview.updateTestimonialPreview( thisRef, model, subElements );
			break;

			case 'TF_FusionText':
				fusionPreview.updateTextBlockPreview( thisRef, model, subElements );
			break;

			case 'TF_Title':
				fusionPreview.updateTitlePreview( thisRef, model, subElements );
			break;

			case 'TF_Toggles':
				fusionPreview.updateTogglesPreview( thisRef, model, subElements );
			break;

			case 'TF_Vimeo':
				fusionPreview.updateVimeoPreview( thisRef, model, subElements );
			break;

			case 'TF_WidgetArea':
				fusionPreview.updateWidgetAreaPreview( thisRef, model, subElements );
			break;

			case 'TF_WooShortcodes':
				fusionPreview.updateWooShortcodesPreview( thisRef, model, subElements );
			break;

			case 'TF_Youtube':
				fusionPreview.updateYoutubePreview( thisRef, model, subElements );
			break;

			default:
				$(thisRef.el).find('.innerElement').html( model.get('innerHtml') );
		}
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateAlertPreview = function( thisRef, model, subElements ) {

		var innerHtml 	=  model.get('innerHtml');
		var icon		= '';
		//for icon
		switch(subElements[0].value ) {
			case 'general':
				icon = 'fa fa-lg fa-info-circle';
			break;
			case 'error':
				icon = 'fa fa-lg fa-exclamation-triangle';
			break;
			case 'success':
				icon = 'fa fa-lg fa-check-circle';
			break;
			case 'notice':
				icon = 'fa fa-lg fa-lg fa-cog';
			break;
			case 'custom':
				icon = 'fa '+subElements[4].value;
			break;
		}
		innerHtml 		= innerHtml.replace( $(innerHtml).find('sub.sub').html() , subElements[6].value );
		innerHtml 		= innerHtml.replace( $(innerHtml).find('i').attr('class') , icon );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateBlogPreview = function( thisRef, model, subElements ) {

		var innerHtml 	=  model.get('innerHtml');
		innerHtml 		= innerHtml.replace( $(innerHtml).find('span.blog_layout').html() , subElements[0].value );
		if(subElements[0].value == 'grid') {
			innerHtml 		= innerHtml.replace( $(innerHtml).find('font.blog_columns').html() , '<br /> columns = ' + subElements[19].value );
		} else {
			innerHtml 		= innerHtml.replace( $(innerHtml).find('font.blog_columns').html(), '' );
		}

		$(thisRef.el).find('.innerElement').html( innerHtml );

	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateButtonPreview = function( thisRef, model, subElements ) {

		var innerHtml 	=  model.get('innerHtml');
		var buttonStyle	= '';
		//for button color
		switch( subElements[1].value ) {
			case 'custom':
				var topC = ( subElements[9].value == 'transparent' ) ? '#ebeaea' : subElements[9].value;
				var botC = subElements[10].value;
				var acnC = subElements[11].value;
				buttonStyle = "background: "+topC+";background: -moz-linear-gradient(top,  "+topC+" 0%, "+botC+" 100%);background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,"+topC+"), color-stop(100%,"+botC+"));background: -webkit-linear-gradient(top,  "+topC+" 0%,"+botC+" 100%);background: -o-linear-gradient(top,  "+topC+" 0%,"+botC+" 100%);background: -ms-linear-gradient(top,  "+topC+" 0%,"+botC+" 100%);background: linear-gradient(to bottom,  "+topC+" 0%,"+botC+" 100%);filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='"+topC+"', endColorstr='"+botC+"',GradientType=0 );border: 1px solid #fff;color: #fff;border: 1px solid "+acnC+";color: "+acnC+";";
				innerHtml 		= innerHtml.replace( $(innerHtml).find('a.button').attr('style') , buttonStyle );
				innerHtml 		= innerHtml.replace( $(innerHtml).find('a.button').attr('class') , 'button custom' );
			break;

			default:
				buttonStyle		= "button "+subElements[1].value;
				innerHtml 		= innerHtml.replace( $(innerHtml).find('a.button').attr('class') , buttonStyle );
			break;
		}

		if ( subElements[3].value == 'yes' ) {
			innerHtml 		= innerHtml.replace( 'selector:attrib' , 'width:90%;' );
		}

		innerHtml 		= innerHtml.replace( $(innerHtml).find('span.fusion-button-text').html() , subElements[8].value );


		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateChecklistPreview = function( thisRef, model, subElements ) {

		var innerHtml 		=  model.get('innerHtml');
		var totalElements 	= subElements[7].elements.length;
		var previewData		= '';
		var counter			= 0;
		for ( i = 0; i <  totalElements; i ++) {
			element		= subElements[7].elements[i];
			value		= '';
			//fot HTML
			if( /<[a-z][\s\S]*>/i.test( element[1].value ) ) {
				value = $(element[1].value).text();
			}else {
				value = element[1].value;
			}

			if( element[1].value != '' ) {
				previewData+= '<li><i ';
				if( element[0].value != '' ) {
					previewData+= 'class="fa '+element[0].value+'"></i>';
				} else {
					previewData+= 'class="fa '+subElements[0].value+'"></i>';
				}
				previewData+=  value;
				previewData+= '</li>';
				counter++;
			}
			if( counter == 3 ) { break; }
		}

		innerHtml 			= innerHtml.replace( $(innerHtml).find('ul.checklist_elements').html() , previewData );

		$(thisRef.el).find('.innerElement').html( innerHtml );

	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateClientSliderPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');
		var totalElements 	= subElements[3].elements.length;
		var previewData		= '';

		for ( i = 0; i < totalElements; i ++) {
			element 	= subElements[3].elements[i];
			previewData+= '<li>';
			previewData+= ' <img src="'+element[2].value+'">';
			previewData+= '</li>';

			if( i == 4 ) break;
		}

		innerHtml = innerHtml.replace( $(innerHtml).find('ul.client_slider_elements').html() , previewData );

		$(thisRef.el).find('.innerElement').html( innerHtml );

	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateContentBoxesPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');

		innerHtml 			= innerHtml.replace( $(innerHtml).find('span.content_boxes_layout').html() , subElements[1].value );
		innerHtml			= innerHtml.replace( $(innerHtml).find('font.content_boxes_columns').html() , subElements[2].value );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}

	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateCountdownPreview = function( thisRef, model, subElements ) {

		var $target_time = new Date(),
			$now_time = new Date();

		if ( subElements[0].value == '' ) {
			$secs = 0;
			$mins = 0;
			$hours = 0;
			$days = 0;
			$weeks = 0;
		} else {
			var	$time = subElements[0].value.replace( ' ', '-' ).replace( new RegExp( ':', 'g' ), '-' ).split( '-' ),
				$timer = [];
				$counter = [1, 2, 3, 4, 5, 6];


			jQuery.each( $counter, function( $index, $value ) {
				if ( $index in $time ) {
					$timer.push( $time[$index] );
				} else {
					$timer.push( '0' );
				}
        	});

			$target_time = new Date( $timer[1] + '/' + $timer[2] + '/' + $timer[0] + ' ' + $timer[3] + ':' + $timer[4] + ':' + $timer[5] );
			$difference_in_secs = Math.floor( ( $target_time.valueOf() - $now_time.valueOf()) / 1000 );

			$secs = $difference_in_secs % 60;
			$mins = Math.floor( $difference_in_secs/60 )%60;
			$hours = Math.floor( $difference_in_secs/60/60 )%24;

			if ( subElements[1].value == 'no' ) {
				$days = Math.floor( $difference_in_secs/60/60/24 );
				$weeks = Math.floor( $difference_in_secs/60/60/24/7 );
			} else {
				$days = Math.floor( $difference_in_secs/60/60/24 )%7;
				$weeks = Math.floor( $difference_in_secs/60/60/24/7 );
			}
		}
		var innerHtml 		= model.get('innerHtml');

		if ( subElements[1].value == 'no' ) {
			innerHtml 		= innerHtml.replace( $( innerHtml ).find( '.fusion_dash_weeks' ).html(), '' );
			innerHtml 		= innerHtml.replace( '<span class="fusion_dash_weeks"></span>', '' );
		} else {
			innerHtml 		= innerHtml.replace( $( innerHtml ).find( '.fusion_dash_weeks .dash' ).html(), $weeks );
		}

		innerHtml 			= innerHtml.replace( $( innerHtml ).find( '.fusion_dash_days .dash' ).html(), $days );
		innerHtml 			= innerHtml.replace( $( innerHtml ).find( '.fusion_dash_hrs .dash' ).html(), $hours );
		innerHtml 			= innerHtml.replace( $( innerHtml ).find( '.fusion_dash_mins .dash' ).html(), $mins );
		innerHtml 			= innerHtml.replace( $( innerHtml ).find( '.fusion_dash_secs .dash' ).html(), $secs );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}

	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateCounterBoxPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');

		innerHtml 			= innerHtml.replace( $(innerHtml).find('font.counter_box_columns').html() , subElements[0].value );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateFlipBoxesPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');

		innerHtml 			= innerHtml.replace( $(innerHtml).find('font.flip_boxes_columns').html() , subElements[0].value );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateFontAwesomePreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');
		var previewData		= '';
		var iconElement		= '';
		//for icon
		if( !subElements[0].value.trim() ) {
			iconElement = '<i class="fusiona-flag" style="color:'+subElements[3].value+'"></i>';
		} else {
			iconElement = '<i class="fa '+subElements[0].value+'" style="color:'+subElements[3].value+'"></i>';
		}
		//for circle
		if( subElements[1].value == 'yes' ) {
			previewData = '<h3 style="background:'+subElements[4].value+'">'+iconElement+'</h3>';
		} else {
			previewData = iconElement;
		}

		innerHtml 			= innerHtml.replace( $(innerHtml).find('span.fusion_iconbox_icon').html() , previewData );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateFusionSliderPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');

		innerHtml 			= innerHtml.replace( $(innerHtml).find('span.fusion_slider_name').html() , subElements[0].value );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateGoogleMapPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');

		innerHtml 			= innerHtml.replace( $(innerHtml).find('p.google_map_address').html() , subElements[16].value );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateImageFramePreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');

		innerHtml 			= innerHtml.replace( $(innerHtml).find('div.img_frame_section').html() , '<img src="'+subElements[10].value+'">' );
		if ( subElements[8].value ) {
			innerHtml 			= innerHtml.replace( $(innerHtml).find('.img_frame_gallery').html() , 'Gallery ID: ' + subElements[8].value );
		} else {
			innerHtml 			= innerHtml.replace( 'img_frame_gallery"' , 'img_frame_gallery" style="display:none;"' );
		}

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateImageCarouselPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');
		var totalElements 	= subElements[12].elements.length;
		var previewData		= '';

		for ( i = 0; i < totalElements; i ++) {
			element 	= subElements[12].elements[i];
			previewData+= '<li>';
			previewData+= ' <img src="'+element[2].value+'">';
			previewData+= '</li>';

			if( i == 4 ) break;

		}

		innerHtml 			= innerHtml.replace( $(innerHtml).find('ul.image_carousel_elements').html() , previewData );

		$(thisRef.el).find('.innerElement').html( innerHtml );

	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateLayerSliderPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');

		innerHtml 			= innerHtml.replace( $(innerHtml).find('span.layer_slider_id').html() , subElements[0].value );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateLoginPreview = function( thisRef, model, subElements ) {

		var innerHtml 	= model.get('innerHtml');

		$(thisRef.el).find('.innerElement').html( innerHtml );
		$(thisRef.el).find('.innerElement').find( '.' + subElements[0].value ).show();
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateMenuAnchorPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');

		innerHtml 			= innerHtml.replace( $(innerHtml).find('span.anchor_name').html() , subElements[0].value );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateModalPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');

		innerHtml 			= innerHtml.replace( $(innerHtml).find('span.modal_name').html() , subElements[0].value );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updatePersonPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');

		innerHtml 			= innerHtml.replace( $(innerHtml).find('div.img_frame_section').html() , '<img src="'+subElements[3].value+'">' );
		innerHtml 			= innerHtml.replace( $(innerHtml).find('p.person_name').html() , subElements[0].value );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updatePostSliderPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');
		//for attachment layout
		if( subElements[0].value == 'attachments' ) {
			innerHtml 			= innerHtml.replace( $(innerHtml).find('span.cat_container').attr('style') , 'display:none' );
		} else {
			innerHtml 			= innerHtml.replace( $(innerHtml).find('span.cat_container').attr('style') , 'display:' );
		}
		innerHtml 			= innerHtml.replace( $(innerHtml).find('span.post_slider_layout').html() , subElements[0].value );

		var cat				= ( !subElements[2].value.trim() ) ? 'all' : subElements[2].value;
		innerHtml 			= innerHtml.replace( $(innerHtml).find('span.post_slider_cat').html() , cat );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updatePricingTablePreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');
		var columns			= subElements[4].value.match(/pricing_column/g);

		innerHtml 			= innerHtml.replace( $(innerHtml).find('span.pricing_table_style').html() , subElements[0].value );
		innerHtml 			= innerHtml.replace( $(innerHtml).find('font.pricing_table_columns').html() , columns.length / 2 );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateProgressBarPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');
		var $value = subElements[12].value;

		if ( subElements[3].value != 'no' ) {
			$value += ' ' + subElements[2].value + subElements[4].value
		}

		innerHtml 			= innerHtml.replace( $(innerHtml).find('p.progress_bar_text').html() , $value );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateRecentPostsPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');

		innerHtml 			= innerHtml.replace( $(innerHtml).find('span.recent_posts_layout').html() , subElements[0].value );
		innerHtml 			= innerHtml.replace( $(innerHtml).find('font.recent_posts_columns').html() , subElements[2].value );

		var cats 			= fusionParser.getUniqueElements( subElements[5].value ).join();
		cats 				= ( cats == '' ? 'All' : cats);
		innerHtml 			= innerHtml.replace( $(innerHtml).find('font.recent_posts_cats').html() , cats );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateRecentWorksPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');
		//for carousel layout
		if( subElements[0].value == 'carousel' ) {
			innerHtml 			= innerHtml.replace( $(innerHtml).find('span.columns_container').attr('style') , 'display:none' );
		} else {
			innerHtml 			= innerHtml.replace( $(innerHtml).find('span.columns_container').attr('style') , 'display:' );
		}

		innerHtml 			= innerHtml.replace( $(innerHtml).find('span.recent_works_layout').html() , subElements[0].value );
		innerHtml 			= innerHtml.replace( $(innerHtml).find('font.recent_works_columns').html() , subElements[4].value );

		var cats 			= fusionParser.getUniqueElements(subElements[6].value).join();
		cats 				= ( cats == '' ? 'All' : cats);
		innerHtml 			= innerHtml.replace( $(innerHtml).find('font.recent_works_cats').html() , cats );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateRevSliderPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');

		innerHtml 			= innerHtml.replace( $(innerHtml).find('span.rev_slider_name').html() , subElements[0].value );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateSeparatorPreview = function( thisRef, model, subElements ) {

		var innerHtml 			= model.get('innerHtml');
		var sep_css, icon_css	= '';
		subElements[0].value 	= ( !subElements[0].value.trim() ) ? 'none' : subElements[0].value;
		innerHtml 				= innerHtml.replace( $(innerHtml).find('section').attr('class') , 'separator ' + subElements[0].value.replace("|", "_") );

		switch( subElements[0].value ) {

			case 'none':
				//do nothing
			break;

			case 'double':
				  sep_css	= 'border-bottom: 1px solid '+subElements[3].value+';border-top: 1px solid '+subElements[3].value+';';
			break;

			case 'double|dashed':
				sep_css		= 'border-bottom: 1px dashed '+subElements[3].value+';border-top: 1px dashed '+subElements[3].value+';';
			break;

			case 'double|dotted':
				sep_css		= 'border-bottom: 1px dotted '+subElements[3].value+';border-top: 1px dotted '+subElements[3].value+';';
			break;

			case 'shadow':
				sep_css		= 'background:radial-gradient(ellipse at 50% -50% , '+subElements[3].value+' 0px, rgba(255, 255, 255, 0) 80%) repeat scroll 0 0 rgba(0, 0, 0, 0)';
			break;

			default:
				sep_css		= 'background:'+subElements[3].value+';';
			break;

		}

		// width
		if( subElements[8].value != '' ) {
			sep_css += 'width:'+subElements[7].value+';';

			// alignment
			if( subElements[9].value == 'left' ) {
				sep_css += 'margin-left:5%;margin-right:0;';
			} else if ( subElements[9].value == 'right' ) {
				sep_css += 'float:right;margin-right:5%;';
			}
		}

		if( subElements[3].value != '' || subElements[8].value != '' ) {
			innerHtml 			= innerHtml.replace( $(innerHtml).find('section').attr('style') , sep_css );
		}

		//for icon
		if( subElements[0].value != 'none' && subElements[0].value != '' && subElements[5].value != '' ) {
			innerHtml 			= innerHtml.replace( $(innerHtml).find('i:eq(1)').attr('class') , "icon fa "+subElements[5].value);
		} else {
			innerHtml 			= innerHtml.replace( $(innerHtml).find('i:eq(1)').attr('class') , "fake_class");
		}
		//color for circle border
		if ( subElements[6].value != 'no' ) {
			icon_css 			= "color:"+subElements[3].value+";border-color:"+subElements[3].value+';';
		} else {
			icon_css 			= "color:"+subElements[3].value+";border-color:transparent;";
		}

		//color for circle bg
		if ( subElements[7].value != '' ) {
			icon_css 			+= "background-color:"+subElements[7].value;
		}

		innerHtml 				= innerHtml.replace( $(innerHtml).find('i:eq(1)').attr('style') , icon_css );

		//for upper content
		if( subElements[0].value != 'none' ) {
			innerHtml 			= innerHtml.replace( $(innerHtml).find('span.upper_container').attr('style') , 'display:none' );
		} else {
			innerHtml 			= innerHtml.replace( $(innerHtml).find('span.upper_container').attr('style') , '' );
		}
		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateSharingBoxPreview  = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');

		innerHtml 			= innerHtml.replace( $(innerHtml).find('p.sharing_tagline').html() , subElements[0].value );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateSliderPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');
		var totalElements 	= subElements[5].elements.length;
		var previewData		= '';

		for ( i = 0; i < totalElements; i ++) {
			element 	= subElements[5].elements[i];

			previewData+= '<li>';
			if( element[0].value == 'video' ) {
				previewData+= '<h1 class="video_type">Video</h1>';
			} else if ( element[0].value == 'image' ) {
				previewData+= ' <img src="'+element[1].value+'">';
			}
			previewData+= '</li>';

			if( i == 4 ) break;

		}

		innerHtml 			= innerHtml.replace( $(innerHtml).find('ul.slider_elements').html() , previewData );

		$(thisRef.el).find('.innerElement').html( innerHtml );

	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateSoundCloudPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');

		innerHtml 			= innerHtml.replace( $(innerHtml).find('p.soundcloud_url').html() , subElements[0].value );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateTabsPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');
		var totalElements 	= subElements[8].elements.length;
		var previewData		= '';
		var counter			= 0;
		for ( i = 0; i < totalElements; i ++) {
			element 	= subElements[8].elements[i];
			if( element[0].value != '' ) {
				previewData+= '<li>'+element[0].value+'</li>';
				counter++;
			}

			if( counter == 3 ) break;

		}

		innerHtml 			= innerHtml.replace( $(innerHtml).find('ul.tabs_elements').html() , previewData );

		$(thisRef.el).find('.innerElement').html( innerHtml );

	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateTablePreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');

		innerHtml 			= innerHtml.replace( $(innerHtml).find('span.table_style').html() , subElements[0].value );
		innerHtml 			= innerHtml.replace( $(innerHtml).find('font.table_columns').html() , subElements[1].value );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateTaglineBoxPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');

		innerHtml 			= innerHtml.replace( $(innerHtml).find('p.tagline_title').html() , subElements[15].value );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateTestimonialPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');
		var totalElements 	= subElements[6].elements.length;
		var previewData		= '';

		for ( i = 0; i < totalElements; i ++) {
			element 	= subElements[6].elements[i];

			//if name exists
			if ( element[0].value != '' ) {
				previewData+= element[0].value ;
			}
			//if company exists
			if( element[4].value != '' ) {
				previewData+= ', '+element[4].value+'<br>';
			} else {
				previewData+= ', <br>';
			}
		}

		innerHtml 			= innerHtml.replace( $(innerHtml).find('p.testimonial_content').html() , previewData );

		$(thisRef.el).find('.innerElement').html( innerHtml );

	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateTextBlockPreview = function( thisRef, model, subElements ) {

		var text_block 		= $.parseHTML( subElements[0].value );
		var text_block_html = '';
		var insert_icon = '';

		$(text_block).each(function() {
			if($(this).get(0).tagName != 'IMG' && typeof $(this).get(0).tagName != 'undefined') {
				var childrens = $($(this).get(0)).find('*');
				var child_img = false;
				if(childrens.length >= 1) {
					$.each(childrens, function() {
						if($(this).get(0).tagName == 'IMG') {
							child_img = true;
						}
					});
				}
				if(child_img == true) {
					text_block_html += $(this).outerHTML();
				} else {
					text_block_html += $(this).text();
				}
			} else {
				text_block_html += $(this).outerHTML();
			}
		});

		if(text_block_html.indexOf('[/pricing_table]') > -1) {
			insert_icon = '<span class="text-block-icon"><i class="fusiona-icon fusiona-dollar"></i>Pricing Table</span>';
		}

		if(subElements[0].value.indexOf('<div class="table-1">') > -1 || subElements[0].value.indexOf('<div class="table-2">') > -1) {
			insert_icon = '<span class="text-block-icon"><i class="fusiona-icon fusiona-table"></i>Table</span>';
		}

		if(
			typeof wp.shortcode.next('woocommerce_order_tracking', text_block_html) == 'object' ||
			typeof wp.shortcode.next('add_to_cart', text_block_html) == 'object' ||
			typeof wp.shortcode.next('product', text_block_html) == 'object' ||
			typeof wp.shortcode.next('products', text_block_html) == 'object' ||
			typeof wp.shortcode.next('product_categories', text_block_html) == 'object' ||
			typeof wp.shortcode.next('product_category', text_block_html) == 'object' ||
			typeof wp.shortcode.next('recent_products', text_block_html) == 'object' ||
			typeof wp.shortcode.next('featured_products', text_block_html) == 'object' ||
			typeof wp.shortcode.next('woocommerce_shop_messages', text_block_html) == 'object'
			) {
			insert_icon = '<span class="text-block-icon"><i class="fusiona-icon fusiona-shopping-cart"></i>Woo Shortcodes</span>';
		}

		innerHtml   = $( '<div class="fake-wrapper">' + model.get('innerHtml') + '</div>' ).find( 'span' ).append( insert_icon + '<small>'+text_block_html+'</small>' ).parents('.fake-wrapper').html();

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateTitlePreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');
		var value			= '';
		//HTML check
		if( /<[a-z][\s\S]*>/i.test( subElements[6].value ) ) {
			value = $(subElements[6].value).text();
		}else {
			value = subElements[6].value;
		}
		//for text
		if( value != '' ) {
			innerHtml 			= innerHtml.replace( $(innerHtml).find('sub.title_text').html() , value );
		}
		//for color and style
		if ( subElements[1].value == 'center' ) {
			innerHtml 				= innerHtml.replace( $(innerHtml).find('section').attr('class') , 'title-sep-center' );
			innerHtml 				= innerHtml.replace( $(innerHtml).find('section').attr('style') , '' );

			innerHtml 				= innerHtml.replace( $(innerHtml).find('section > div').attr('class') , subElements[2].value.replace(' ','_') );
			innerHtml 				= innerHtml.replace( $(innerHtml).find('section > div').attr('style') , "border-color:"+subElements[3].value);
		} else {
			innerHtml 				= innerHtml.replace( $(innerHtml).find('section').attr('class') , ( subElements[2].value.replace( ' ','_' ) ) ? ( subElements[2].value.replace( ' ','_' ) ) : 'none' );
			innerHtml 				= innerHtml.replace( $(innerHtml).find('section').attr('style') , "border-color:"+subElements[3].value);
		}
		//for alignment
		if( subElements[1].value == 'right' ) {
			innerHtml 			= innerHtml.replace( $(innerHtml).find('sub.title_text').attr('class') , 'title_text align_right' );
		} else if( subElements[1].value == 'center' ) {
			innerHtml 			= innerHtml.replace( $(innerHtml).find('sub.title_text').attr('class') , 'title_text align_center' );
		} else {
			innerHtml 			= innerHtml.replace( $(innerHtml).find('sub.title_text').attr('class') , 'title_text align_left' );
		}
		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateTogglesPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');
		var totalElements 	= subElements[3].elements.length;
		var previewData		= '';
		var counter			= 0;

		for ( i = 0; i < totalElements; i ++) {
			element 	= subElements[3].elements[i];
			if( element[0].value != '' ) {
				previewData+= '<li>'+element[0].value+'</li>';
				counter++;
			}

			if( counter == 3 ) break;

		}

		innerHtml 			= innerHtml.replace( $(innerHtml).find('ul.toggles_content').html() , previewData );

		$(thisRef.el).find('.innerElement').html( innerHtml );

	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateVimeoPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');

		innerHtml 			= innerHtml.replace( $(innerHtml).find('p.viemo_url').html() , "https://vimeo.com/"+subElements[0].value );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}

	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateWidgetAreaPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml'),
			$array_keys		=  $( innerHtml ).find( '.array_keys' ).html().split( ',' ),
			$array_values	=  $( innerHtml ).find( '.array_values' ).html().split( ',' ),
			$key			= $.inArray( subElements[0].value, $array_keys )

		innerHtml 			= innerHtml.replace( $(innerHtml).find( '.fusion_name' ).html() , $array_values[$key] );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}

	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateWooShortcodesPreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');

		innerHtml 			= innerHtml.replace( $(innerHtml).find('p.woo_shortcode').html() , subElements[1].value );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
	/**
	* Update Preview of element
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Reference to current element
	*
	* @param		OBJECT 		Data model of element
	*
	* @param		OBJECT 		Sub elements data
	*
	* @return 		NULL
	**/
	fusionPreview.updateYoutubePreview = function( thisRef, model, subElements ) {

		var innerHtml 		= model.get('innerHtml');

		innerHtml 			= innerHtml.replace( $(innerHtml).find('p.youtube_url').html() , "http://www.youtube.com/"+subElements[0].value );

		$(thisRef.el).find('.innerElement').html( innerHtml );
	}
  })(jQuery);

