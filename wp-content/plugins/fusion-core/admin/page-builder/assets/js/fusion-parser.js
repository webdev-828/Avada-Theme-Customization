/*
* Parser for builder elements
*/
( function($) {
	var fusionParser 		= {};
	window.fusionParser 	= fusionParser;
	var elements			= [];

	/**
	 * get editor data and add to array
	 * @param 	NULL
	 * @return 	NULL
	 */
	fusionParser.checkBuilderElements = function( publishRequest ) {
		elements 		= JSON.parse( fusionHistoryManager.getAllElementsData() );

		if(typeof tinyMCE.get('content') == 'object' && tinyMCE.get('content') != null) {
			tinyMCE.get('content').focus()
			isActive 		= (typeof tinyMCE != "undefined") && tinyMCE.activeEditor && !tinyMCE.activeEditor.isHidden();
		} else {
			isActive = false;
		}

		if( elements.length > 0 ) {
			shortCodes 		= fusionParser.parseColumnOptions();

			if( isActive ) {
				shortCodes 		= window.switchEditors.wpautop(shortCodes);
				window.tinyMCE.get('content').setContent( shortCodes, {format: 'html'} );
			} else {
				$('#content').val( shortCodes );
			}
		} else if( publishRequest && elements.length < 1 ) {

			if( isActive ) {
				window.tinyMCE.activeEditor.setContent('');
			} else {
				$('#content').val('');
			}

		}
		//publish now
		if( publishRequest ) $( '#publish' ).trigger( "click" );
	}

	/**
	 * Construct the shortcode
	 *
	 * @since  	3.8?
	 */
	fusionParser.buildShortcodeContainer = function( element ) {
		var $shortcode = "[" + element.base;
		if ( element.subElements ) {
			$.each( element.subElements, function( index, obj ) {
				if (obj.data) {
					if (obj.data.replace) {
						obj.value.replace(obj.data.replace, '');
					}
					if (obj.data.append) {
						obj.value += obj.data.append;
					}
				}

				obj.id = obj.id.replace('fusion_', '' ).replace('[0]','');
				if ( $.isArray( obj.value ) ) {
					obj.value = fusionParser.getUniqueElements( obj.value ).join('|');
				}
				$shortcode += ' ' + obj.id +'="'+obj.value+'"';
			});
		}
		$shortcode += "]";
		return $shortcode;
	};

	/**
	* Parser for column options
	*
	* @since  	2.0.0
	*/
	fusionParser.parseColumnOptions = function() {
		elements 			= JSON.parse( fusionHistoryManager.getAllElementsData() );
		shortCodes 			= ''; // this element will have all shortcodes once processing ends
		$.each(elements, function( index, element ) { //traverse elements
			if ( !element.hasOwnProperty('parentId') || element.php_class == 'TF_FullWidthContainer' ) { //if element does not have any parent (column element)
				if ( element.base && element.base != "" ) {
					shortCodes +=  fusionParser.buildShortcodeContainer ( element );
					if ( ~element.php_class.indexOf( 'TF_Grid' ) || element.php_class == "TF_FullWidthContainer" ) {
						shortCodes +=  fusionParser.parseColumnElement( element );
					}
					shortCodes += '[/' + element.base + ']';
				} else {
					// NOT CONVERTED TO NEW JS
					//parse this element separately.
					shortCodes+= fusionParser.parseBuilderElements( element );
				}
			}

		});
		return shortCodes;

	}
	/**
	* Parses column options elements for parent and children
	*
	* @since	 	2.0.0
	*
	* @param		element				OBJECT 		Object having element data
	*
	* @return 		columnElements		String		Shortcodes of parsed elements
	**/
	fusionParser.parseColumnElement = function( element ) {
		var columnElements = '';
		var childElements = element.childrenId.length;

		if ( childElements > 0 ) {

			columnElements = fusionParser.parseChildElements( element );
		}
		return columnElements;

	}
	/**
	* Parses child elements of single column option
	*
	* @since	 	2.0.0
	*
	* @param		element						OBJECT 		Object having element data
	*
	* @return 		builderElementShortcode		String		Shortcodes of parsed elements
	**/
	fusionParser.parseChildElements = function( element ) {
		var builderElementShortcode = '';

		$.each(element['childrenId'], function( index, child ) {
			if( child != null ) {
				//get element by id
				var builderElement = $.grep( elements, function( element ){ return element.id == child.id; });
				//if element found
				if ( builderElement.length > 0 ) {
					//generate short-code for this element
					if ( builderElement[0].base && builderElement[0].base != "" ) {
						builderElementShortcode +=  fusionParser.buildShortcodeContainer ( builderElement[0] );
						if ( ~builderElement[0].php_class.indexOf( 'TF_Grid' ) || builderElement[0].php_class == "TF_FullWidthContainer" ) {
							builderElementShortcode +=  fusionParser.parseColumnElement( builderElement[0] );
						}
						builderElementShortcode += '[/' + builderElement[0].base + ']';
					} else {
						// NOT CONVERTED TO NEW JS
						builderElementShortcode+= fusionParser.parseBuilderElements( builderElement[0] );
					}
				}
			}

		});
		return builderElementShortcode;
	}
	/**
	 * parser for builder elements
	 *
	 * @since	 	2.0.0
	 *
	 * @param		element			OBJECT 		Object having element data
	 *
	 * @return 		short_code	 	String		Shortcode of parsed elements
	 */
	fusionParser.parseBuilderElements = function( element ) {
		var shortCodes = '';

		switch( element.php_class ) {
			case 'TF_GridOne' :
				shortCodes+= '[one_full '+ fusionParser.prepareColumnElement( element.subElements ) + ']';
				shortCodes+=  fusionParser.parseColumnElement( element )
				shortCodes+= '[/one_full]';
				return shortCodes;
				break;

			case 'TF_GridTwo' :
				shortCodes+= '[one_half '+ fusionParser.prepareColumnElement( element.subElements ) + ']';
				shortCodes+=  fusionParser.parseColumnElement( element )
				shortCodes+= '[/one_half]';
				return shortCodes;
				break;

			case 'TF_GridThree' :
				shortCodes+= '[one_third '+ fusionParser.prepareColumnElement( element.subElements ) + ']';
				shortCodes+=  fusionParser.parseColumnElement( element )
				shortCodes+= '[/one_third]';
				return shortCodes;
				break;

			case 'TF_GridFour' :
				shortCodes+= '[one_fourth '+ fusionParser.prepareColumnElement( element.subElements ) + ']';
				shortCodes+=  fusionParser.parseColumnElement( element )
				shortCodes+= '[/one_fourth]';
				return shortCodes;
				break;

			case 'TF_GridFive' :
				shortCodes+= '[one_fifth '+ fusionParser.prepareColumnElement( element.subElements ) + ']';
				shortCodes+=  fusionParser.parseColumnElement( element )
				shortCodes+= '[/one_fifth]';
				return shortCodes;
				break;

			case 'TF_GridTwoFifth' :
				shortCodes+= '[two_fifth '+ fusionParser.prepareColumnElement( element.subElements ) + ']';
				shortCodes+=  fusionParser.parseColumnElement( element )
				shortCodes+= '[/two_fifth]';
				return shortCodes;
				break;

			case 'TF_GridThreeFifth' :
				shortCodes+= '[three_fifth '+ fusionParser.prepareColumnElement( element.subElements ) + ']';
				shortCodes+=  fusionParser.parseColumnElement( element )
				shortCodes+= '[/three_fifth]';
				return shortCodes;
				break;

			case 'TF_GridFourFifth' :
				shortCodes+= '[four_fifth '+ fusionParser.prepareColumnElement( element.subElements ) + ']';
				shortCodes+=  fusionParser.parseColumnElement( element )
				shortCodes+= '[/four_fifth]';
				return shortCodes;
				break;

			case 'TF_GridSix' :
				shortCodes+= '[one_sixth '+ fusionParser.prepareColumnElement( element.subElements ) + ']';
				shortCodes+=  fusionParser.parseColumnElement( element )
				shortCodes+= '[/one_sixth]';
				return shortCodes;
				break;

			case 'TF_GridFiveSix' :
				shortCodes+= '[five_sixth '+ fusionParser.prepareColumnElement( element.subElements ) + ']';
				shortCodes+=  fusionParser.parseColumnElement( element )
				shortCodes+= '[/five_sixth]';
				return shortCodes;
				break;

			case 'TF_GridThreeFourth' :
				shortCodes+= '[three_fourth '+ fusionParser.prepareColumnElement( element.subElements ) + ']';
				shortCodes+=  fusionParser.parseColumnElement( element )
				shortCodes+= '[/three_fourth]';
				return shortCodes;
				break;

			case 'TF_GridTwoThird' :
				shortCodes+= '[two_third '+ fusionParser.prepareColumnElement( element.subElements ) + ']';
				shortCodes+=  fusionParser.parseColumnElement( element )
				shortCodes+= '[/two_third]';
				return shortCodes;
				break;

			case 'TF_AlertBox':
				return fusionParser.buildAlertShortocde( element.subElements );
				break;

			case 'TF_WpBlog':
				return fusionParser.buildBlogShortocde( element.subElements ) ;
				break;

			case 'TF_ButtonBlock':
				return fusionParser.buildButtonShortocde( element.subElements ) ;
				break;

			case 'TF_CheckList' :
				return fusionParser.buildChecklistShortocde( element.subElements ) ;
				break;

			case 'TF_CodeBlock':
				return fusionParser.buildCodeBlockShortocde( element.subElements ) ;
				break;

			case 'TF_ContentBoxes' :
				return fusionParser.buildContentBoxShortocde( element.subElements ) ;
				break;

			case 'TF_CountDown' :
				return fusionParser.buildCountdownShortocde( element.subElements ) ;
				break;

			case 'TF_CounterCircle' :
				return fusionParser.buildCounterCircleShortocde( element.subElements ) ;
				break;

			case 'TF_CounterBox' :
				return fusionParser.buildCounterBoxShortocde( element.subElements ) ;
				break;

			case 'TF_PostSlider' :
				return fusionParser.buildPostSliderShortocde( element.subElements ) ;
				break;
			case 'TF_FlipBoxes' :
				return fusionParser.buildFlipBoxesShortocde( element.subElements ) ;
				break;

			case 'TF_FontAwesome' :
				return fusionParser.buildFontAwesomeShortocde( element.subElements ) ;
				break;

			case 'TF_GoogleMap' :
				return fusionParser.buildGoogleMapShortocde( element.subElements ) ;
				break;

			case 'TF_ImageFrame' :
				return fusionParser.buildImageFrameShortocde( element.subElements ) ;
				break;

			case 'TF_ImageCarousel' :
				return fusionParser.buildImageCarouselShortocde( element.subElements ) ;
				break;

			case 'TF_LayerSlider' :
				return fusionParser.buildLayerSliderShortocde( element.subElements ) ;
				break;

			case 'TF_Login' :
				return fusionParser.buildLoginShortcodes( element.subElements ) ;
				break;

			case 'TF_MenuAnchor' :
				return fusionParser.buildMenuAnchorShortocde( element.subElements ) ;
				break;

			case 'TF_Modal' :
				return fusionParser.buildModalShortocde( element.subElements ) ;
				break;

			case 'TF_Person' :
				return fusionParser.buildPersonShortocde( element.subElements ) ;
				break;

			case 'TF_PricingTable' :
				return fusionParser.buildPricingTableShortocde( element.subElements ) ;
				break;

			case 'TF_ProgressBar' :
				return fusionParser.buildProgressBarShortocde( element.subElements ) ;
				break;

			case 'TF_RecentPosts' :
				return fusionParser.buildRecentPostsShortocde( element.subElements ) ;
				break;

			case 'TF_RecentWorks' :
				return fusionParser.buildRecentWorksShortocde( element.subElements ) ;
				break;

			case 'TF_RevolutionSlider' :
				return fusionParser.buildRevSliderShortocde( element.subElements ) ;
				break;

			case 'TF_SectionSeparator' :
				return fusionParser.buildSectionSeparatorShortocde( element.subElements ) ;
				break;

			case 'TF_Separator' :
				return fusionParser.buildSeparatorShortocde( element.subElements ) ;
				break;

			case 'TF_SharingBox' :
				return fusionParser.buildSharingBoxShortocde( element.subElements ) ;
				break;

			case 'TF_Slider' :
				return fusionParser.buildSliderShortocde( element.subElements ) ;
				break;

			case 'TF_SoundCloud' :
				return fusionParser.buildSoundcloudShortocde( element.subElements ) ;
				break;

			case 'TF_SocialLinks' :
				return fusionParser.buildSocialLinksShortocde( element.subElements ) ;
				break;

			case 'TF_Tabs' :
				return fusionParser.buildTabsShortocde( element.subElements ) ;
				break;

			case 'TF_Table' :
				return fusionParser.buildTableShortocde( element.subElements ) ;
				break;

			case 'TF_TaglineBox' :
				return fusionParser.buildTaglineShortocde( element.subElements ) ;
				break;

			case 'TF_Testimonial' :
				return fusionParser.buildTestimonialShortocde( element.subElements ) ;
				break;

			case 'TF_FusionText' :
				return fusionParser.buildTextBlockShortocde( element.subElements ) ;
				break;

			case 'TF_Title':
				return fusionParser.buildTitleShortocde( element.subElements ) ;
				break;

			case 'TF_Toggles' :
				return fusionParser.buildTogglesShortocde( element.subElements ) ;
				break;

			case 'TF_Vimeo':
				return fusionParser.buildVimeoShortocde( element.subElements ) ;
				break;

			case 'TF_WidgetArea':
				return fusionParser.buildWidgetAreaShortcode( element.subElements ) ;
				break;

			case 'TF_WooFeatured' :
				return fusionParser.buildWooFeaturedShortocde( element.subElements ) ;
				break;

			case 'TF_WooCarousel' :
				return fusionParser.buildWooCarouselShortocde( element.subElements ) ;
				break;

			case 'TF_WooShortcodes' :
				return fusionParser.buildWooShortcodes( element.subElements ) ;
				break;

			case 'TF_Youtube':
				return fusionParser.buildYoutubeShortocde( element.subElements ) ;
				break;

			case 'TF_FusionSlider':
				return fusionParser.buildFusionSliderShortocde( element.subElements ) ;
				break;

			case 'TF_FusionEvents':
				return fusionParser.buildFusionEventsShortcode( element.subElements ) ;
				break;
		}
	}
	/* ** ** ** ** Parser code starts here ** ** ** */

	/**
	* Returns layout shortcode attributes
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Layout shortcode attributes
	**/

	fusionParser.prepareColumnElement = function( args ) {
		shortcodeData= 'last="'+args[0].value+'"';
		shortcodeData+= ' spacing="'+args[1].value+'"';
		shortcodeData+= ' center_content="'+args[2].value+'"';
		shortcodeData+= ' hide_on_mobile="'+args[3].value+'"';
		shortcodeData+= ' background_color="'+args[4].value+'"';
		shortcodeData+= ' background_image="'+args[5].value.replace('fusion-hidden-img','')+'"';
		shortcodeData+= ' background_repeat="'+args[6].value+'"';
		shortcodeData+= ' background_position="'+args[7].value+'"';
		shortcodeData+= ' hover_type="'+args[8].value+'"';
		shortcodeData+= ' link="'+args[9].value+'"';
		shortcodeData+= ' border_position="'+args[10].value+'"';
		shortcodeData+= ' border_size="'+args[11].value+'"';
		shortcodeData+= ' border_color="'+args[12].value+'"';
		shortcodeData+= ' border_style="'+args[13].value+'"';
		shortcodeData+= ' padding="'+args[14].value+'"';
		shortcodeData+= ' margin_top="'+args[15].value+'"';
		shortcodeData+= ' margin_bottom="'+args[16].value+'"';
		shortcodeData+= ' animation_type="'+args[17].value+'"';
		shortcodeData+= ' animation_direction="'+args[18].value+'"';
		shortcodeData+= ' animation_speed="'+args[19].value+'"';
		shortcodeData+= ' animation_offset="'+args[20].value+'"';
		shortcodeData+= ' class="'+args[21].value+'"';
		shortcodeData+= ' id="'+args[22].value+'"';

		return shortcodeData;
	}
	/**
	* Returns Alert box shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Alert doable shortcode
	**/
	fusionParser.buildAlertShortocde = function( args ) {

		shortcodeData = '[alert';
		shortcodeData+= ' type="'+args[0].value+'"';
		shortcodeData+= ' accent_color="'+args[1].value+'"';
		shortcodeData+= ' background_color="'+args[2].value+'"';
		shortcodeData+= ' border_size="'+args[3].value+'"';
		shortcodeData+= ' icon="'+args[4].value+'"';
		shortcodeData+= ' box_shadow="'+args[5].value+'"';
		shortcodeData+= ' animation_type="'+args[7].value+'"';
		shortcodeData+= ' animation_direction="'+args[8].value+'"';
		shortcodeData+= ' animation_speed="'+args[9].value+'"';
		shortcodeData+= ' animation_offset="'+args[10].value+'"';
		shortcodeData+= ' class="'+args[11].value+'"';
		shortcodeData+= ' id="'+args[12].value+'"]';
		shortcodeData+=   args[6].value;
		shortcodeData+= '[/alert]';

		return shortcodeData;
	}
	/**
	* Returns Blog shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Blog doable shortcode
	**/
	fusionParser.buildBlogShortocde = function( args ) {

		shortcodeData = '[blog';
		shortcodeData+= ' number_posts="'+args[1].value+'"';
		shortcodeData+= ' offset="'+args[2].value+'"';
		shortcodeData+= ' cat_slug="'+fusionParser.getUniqueElements(args[3].value).join()+'"';
		shortcodeData+= ' exclude_cats="'+fusionParser.getUniqueElements(args[4].value).join()+'"';
		shortcodeData+= ' show_title="'+args[5].value+'"';
		shortcodeData+= ' title_link="'+args[6].value+'"';
		shortcodeData+= ' thumbnail="'+args[7].value+'"';
		shortcodeData+= ' excerpt="'+args[8].value+'"';
		shortcodeData+= ' excerpt_length="'+args[9].value+'"';
		shortcodeData+= ' meta_all="'+args[10].value+'"';
		shortcodeData+= ' meta_author="'+args[11].value+'"';
		shortcodeData+= ' meta_categories="'+args[12].value+'"';
		shortcodeData+= ' meta_comments="'+args[13].value+'"';
		shortcodeData+= ' meta_date="'+args[14].value+'"';
		shortcodeData+= ' meta_link="'+args[15].value+'"';
		shortcodeData+= ' meta_tags="'+args[16].value+'"';
		shortcodeData+= ' paging="'+args[17].value+'"';
		shortcodeData+= ' scrolling="'+args[18].value+'"';
		shortcodeData+= ' strip_html="'+args[21].value+'"';
		shortcodeData+= ' blog_grid_columns="'+args[19].value+'"';
		shortcodeData+= ' blog_grid_column_spacing="'+args[20].value+'"';
		shortcodeData+= ' layout="'+args[0].value+'"';
		shortcodeData+= ' class="'+args[22].value+'"';
		shortcodeData+= ' id="'+args[23].value+'"]';
		shortcodeData+= '[/blog]';
		return shortcodeData;
	}
	/**
	* Returns Button shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Button doable shortcode
	**/
	fusionParser.buildButtonShortocde = function( args ) {

		shortcodeData = '[button';
		shortcodeData+= ' link="'+args[0].value+'"';
		shortcodeData+= ' color="'+args[1].value+'"';
		shortcodeData+= ' size="'+args[2].value+'" ';
		shortcodeData+= ' stretch="'+args[3].value+'" ';
		shortcodeData+= ' type="'+args[4].value+'"';
		shortcodeData+= ' shape="'+args[5].value+'"';
		shortcodeData+= ' target="'+args[6].value+'"';
		shortcodeData+= ' title="'+args[7].value+'"';
		shortcodeData+= ' gradient_colors="'+args[9].value+'|'+args[10].value+'"';
		shortcodeData+= ' gradient_hover_colors="'+args[11].value+'|'+args[12].value+'"';
		shortcodeData+= ' accent_color="'+args[13].value+'"';
		shortcodeData+= ' accent_hover_color="'+args[14].value+'"';
		shortcodeData+= ' bevel_color="'+args[15].value+'"';
		shortcodeData+= ' border_width="'+args[16].value+'"';
		shortcodeData+= ' icon="'+args[17].value+'"';
		shortcodeData+= ' icon_position="'+args[18].value+'"';
		shortcodeData+= ' icon_divider="'+args[19].value+'"';
		shortcodeData+= ' modal="'+args[20].value+'"';
		shortcodeData+= ' animation_type="'+args[21].value+'"';
		shortcodeData+= ' animation_direction="'+args[22].value+'"';
		shortcodeData+= ' animation_speed="'+args[23].value+'"';
		shortcodeData+= ' animation_offset="'+args[24].value+'"';
		shortcodeData+= ' alignment="'+args[25].value+'"';
		shortcodeData+= ' class="'+args[26].value+'"';
		shortcodeData+= ' id="'+args[27].value+'"]';
		shortcodeData+=   args[8].value;
		shortcodeData+= '[/button]';

		return shortcodeData;
	}
	/**
	* Returns Checkbox shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Checkbox doable shortcode
	**/
	fusionParser.buildChecklistShortocde = function( args ) {
		shortcodeData = '[checklist';
		shortcodeData+= ' icon="'+args[0].value+'"';
		shortcodeData+= ' iconcolor="'+args[1].value+'"';
		shortcodeData+= ' circle="'+args[2].value+'"';
		shortcodeData+= ' circlecolor="'+args[3].value+'"';
		shortcodeData+= ' size="'+args[4].value+'"';
		shortcodeData+= ' class="'+args[5].value+'"';
		shortcodeData+= ' id="'+args[6].value+'"]';

		totalElements 	= args[7].elements.length;

		for ( i = 0; i <  totalElements; i ++) {
			subElemtns 		= args[7].elements[i];
			shortcodeData+= '[li_item';
			shortcodeData+= ' icon="'+subElemtns[0].value+'"]';
			shortcodeData+=   subElemtns[1].value;
			shortcodeData+= '[/li_item]';

		}
		shortcodeData+= '[/checklist]';

		return shortcodeData;
	}
	/**
	* Returns Code Block shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Client Slider doable shortcode
	**/
	fusionParser.buildCodeBlockShortocde = function( args ) {

		shortcodeData = '[fusion_code]';

		if( Number( fusion_vars.disable_encoding ) == 1 ) {
			shortcodeData+=  fusionParser.base64Encode( args[0].value );
		} else {
			shortcodeData+=  args[0].value;
		}
		shortcodeData+= '[/fusion_code]';

		return shortcodeData;
	}
	/**
	* Returns Content Box shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Content Box doable shortcode
	**/
	fusionParser.buildContentBoxShortocde = function( args ) {
		shortcodeData = '[content_boxes';
		shortcodeData+= ' settings_lvl="'+args[0].value+'"';
		shortcodeData+= ' layout="'+args[1].value+'"';
		shortcodeData+= ' columns="'+args[2].value+'"';
		shortcodeData+= ' icon_align="'+args[3].value+'"';
		shortcodeData+= ' title_size="'+args[4].value+'"';
		shortcodeData+= ' title_color="'+args[5].value+'"';
		shortcodeData+= ' body_color="'+args[6].value+'"';
		shortcodeData+= ' backgroundcolor="'+args[7].value+'"';
		shortcodeData+= ' icon_circle="'+args[8].value+'"';
		shortcodeData+= ' icon_circle_radius="'+args[9].value+'"';
		shortcodeData+= ' iconcolor="'+args[10].value+'"';
		shortcodeData+= ' circlecolor="'+args[11].value+'"';
		shortcodeData+= ' circlebordercolor="'+args[12].value+'"';
		shortcodeData+= ' circlebordersize="'+args[13].value+'"';
		shortcodeData+= ' outercirclebordercolor="'+args[14].value+'"';
		shortcodeData+= ' outercirclebordersize="'+args[15].value+'"';
		shortcodeData+= ' icon_size="'+args[16].value+'"';
		shortcodeData+= ' icon_hover_type="'+args[17].value+'"';
		shortcodeData+= ' hover_accent_color="'+args[18].value+'"';
		shortcodeData+= ' link_type="'+args[19].value+'"';
		shortcodeData+= ' link_area="'+args[20].value+'"';
		shortcodeData+= ' link_target="'+args[21].value+'"';
		shortcodeData+= ' animation_delay="'+args[22].value+'"';
		shortcodeData+= ' animation_offset="'+args[23].value+'"';
		shortcodeData+= ' animation_type="'+args[24].value+'"';
		shortcodeData+= ' animation_direction="'+args[25].value+'"';
		shortcodeData+= ' animation_speed="'+args[26].value+'"';
		shortcodeData+= ' margin_top="'+args[27].value+'"';
		shortcodeData+= ' margin_bottom="'+args[28].value+'"';
		shortcodeData+= ' class="'+args[29].value+'"';
		shortcodeData+= ' id="'+args[30].value+'"]';

		totalElements 	= args[31].elements.length;
		subElements		= args[31].elements;

		for ( i = 0; i < totalElements; i++) {
			subElements		= args[31].elements[i];
			shortcodeData+= '[content_box';
			shortcodeData+= ' title="'+subElements[0].value+'"';
			shortcodeData+= ' icon="'+subElements[1].value+'"';
			shortcodeData+= ' backgroundcolor="'+subElements[2].value+'"';
			shortcodeData+= ' iconcolor="'+subElements[3].value+'"';
			shortcodeData+= ' circlecolor="'+subElements[4].value+'"';
			shortcodeData+= ' circlebordercolor="'+subElements[5].value+'"';
			shortcodeData+= ' circlebordersize="'+subElements[6].value+'"';
			shortcodeData+= ' outercirclebordercolor="'+subElements[7].value+'"';
			shortcodeData+= ' outercirclebordersize="'+subElements[8].value+'"';
			shortcodeData+= ' iconrotate="'+subElements[9].value+'"';
			shortcodeData+= ' iconspin="'+subElements[10].value+'"';
			shortcodeData+= ' image="'+subElements[11].value+'"';
			shortcodeData+= ' image_width="'+subElements[12].value+'"';
			shortcodeData+= ' image_height="'+subElements[13].value+'"';
			shortcodeData+= ' link="'+subElements[14].value+'"';
			shortcodeData+= ' linktext="'+subElements[15].value+'"';
			shortcodeData+= ' link_target="'+subElements[16].value+'"';
			shortcodeData+= ' animation_type="'+subElements[18].value+'"';
			shortcodeData+= ' animation_direction="'+subElements[19].value+'"';
			shortcodeData+= ' animation_speed="'+subElements[20].value+'"]';
			shortcodeData+= subElements[17].value;
			shortcodeData+= '[/content_box]';
		}

		shortcodeData+= '[/content_boxes]';
		return shortcodeData;
	}

	/**
	* Returns Countdown  shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Countdown doable shortcode
	**/
	fusionParser.buildCountdownShortocde = function( args ) {
		shortcodeData = '[fusion_countdown ';
		shortcodeData+= ' countdown_end="'+args[0].value+'"';
		shortcodeData+= ' timezone="'+args[1].value+'"';
		shortcodeData+= ' show_weeks="'+args[2].value+'"';
		shortcodeData+= ' background_color="'+args[3].value+'"';
		shortcodeData+= ' background_image="'+args[4].value.replace('fusion-hidden-img','')+'"';
		shortcodeData+= ' background_repeat="'+args[5].value+'"';
		shortcodeData+= ' background_position="'+args[6].value+'"';
		shortcodeData+= ' border_radius="'+args[7].value+'"';
		shortcodeData+= ' counter_box_color="'+args[8].value+'"';
		shortcodeData+= ' counter_text_color="'+args[9].value+'"';
		shortcodeData+= ' heading_text="'+args[10].value+'"';
		shortcodeData+= ' heading_text_color="'+args[11].value+'"';
		shortcodeData+= ' subheading_text="'+args[12].value+'"';
		shortcodeData+= ' subheading_text_color="'+args[13].value+'"';
		shortcodeData+= ' link_text="'+args[14].value+'"';
		shortcodeData+= ' link_text_color="'+args[15].value+'"';
		shortcodeData+= ' link_url="'+args[16].value+'"';
		shortcodeData+= ' link_target="'+args[17].value+'"';
		shortcodeData+= ' class="'+args[18].value+'"';
		shortcodeData+= ' id="'+args[19].value+'"]';
		shortcodeData+= '[/fusion_countdown]';

		return shortcodeData;
}

	/**
	* Returns Counter Circle shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Counter Circle doable shortcode
	**/
	fusionParser.buildCounterCircleShortocde = function ( args ) {

		shortcodeData = '[counters_circle';
		shortcodeData+= ' animation_offset="'+args[0].value+'"';
		shortcodeData+= ' class="'+args[1].value+'"';
		shortcodeData+= ' id="'+args[2].value+'"]';

		totalElements 	= args[3].elements.length;

		for ( i = 0; i < totalElements; i++ ) {
			subElements 	= args[3].elements[i];
			shortcodeData+= '[counter_circle';
			shortcodeData+= ' filledcolor="'+subElements[1].value+'"';
			shortcodeData+= ' unfilledcolor="'+subElements[2].value+'"';
			shortcodeData+= ' size="'+subElements[3].value+'"';
			shortcodeData+= ' scales="'+subElements[4].value+'"';
			shortcodeData+= ' countdown="'+subElements[5].value+'"';
			shortcodeData+= ' speed="'+subElements[6].value+'"';
			shortcodeData+= ' value="'+subElements[0].value+'"]'+subElements[7].value+'';
			shortcodeData+= '[/counter_circle]';
		}

		shortcodeData+= '[/counters_circle]';
		return shortcodeData;
	}

	/**
	* Returns Counter Box shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Counter Circle doable shortcode
	**/
	fusionParser.buildCounterBoxShortocde = function( args ) {
		shortcodeData = '[counters_box ';
		shortcodeData+= ' columns="'+args[0].value+'"';
		shortcodeData+= ' color="'+args[1].value+'"';
		shortcodeData+= ' title_size="'+args[2].value+'"';
		shortcodeData+= ' icon_size="'+args[3].value+'"';
		shortcodeData+= ' icon_top="'+args[4].value+'"';
		shortcodeData+= ' body_color="'+args[5].value+'"';
		shortcodeData+= ' body_size="'+args[6].value+'"';
		shortcodeData+= ' border_color="'+args[7].value+'"';
		shortcodeData+= ' animation_offset="'+args[8].value+'"';
		shortcodeData+= ' class="'+args[9].value+'"';
		shortcodeData+= ' id="'+args[10].value+'"]';

		totalElements 	= args[11].elements.length;

		for ( i = 0; i < totalElements; i++ ){
			subElements 	= args[11].elements[i];

			shortcodeData+= '[counter_box';
			shortcodeData+= ' value="'+subElements[0]['value']+'"';
			shortcodeData+= ' delimiter="'+subElements[1]['value']+'"';
			shortcodeData+= ' unit="'+subElements[2]['value']+'"';
			shortcodeData+= ' unit_pos="'+subElements[3]['value']+'"';
			shortcodeData+= ' icon="'+subElements[4]['value']+'"';
			shortcodeData+= ' direction="'+subElements[5]['value']+'"]';
			shortcodeData+=   subElements[6].value;
			shortcodeData+= '[/counter_box]';
		}

		shortcodeData+= '[/counters_box]';
		return shortcodeData;
	}
	/**
	* Returns DropCap shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		DropCap doable shortcode
	**/
	/*fusionParser.buildDropcapShortocde = function( args ) {

		shortcodeData = '[dropcap ';
		shortcodeData+= ' color="'+args[1].value+'" ';
		shortcodeData+= ' boxed="'+args[2].value+'" ';
		shortcodeData+= ' boxed_radius="'+args[3].value+'" ';
		shortcodeData+= ' class="'+args[4].value+'" ';
		shortcodeData+= ' id="'+args[5].value+'"] ';
		shortcodeData+=   args[0].value;
		shortcodeData+= '[/dropcap]';
		shortcodeData+= ' \r';

		return shortcodeData;
	}*/

	/**
	* Returns Flex Slider shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Flex Slider doable shortcode
	**/
	fusionParser.buildPostSliderShortocde = function( args ) {

		shortcodeData = '[postslider';
		shortcodeData+= ' layout="'+args[0].value+'"';
		shortcodeData+= ' excerpt="'+args[1].value+'"';
		shortcodeData+= ' category="'+args[2].value.replace(' ','')+'"';
		shortcodeData+= ' limit="'+args[3].value+'"';
		shortcodeData+= ' lightbox="'+args[4].value+'"';
		shortcodeData+= ' class="'+args[6].value+'"';
		shortcodeData+= ' id="'+args[7].value+'"]';
		shortcodeData+= '[/postslider]';

		return shortcodeData;
	}
	/**
	* Returns Flip Box shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Flip Box doable shortcode
	**/
	fusionParser.buildFlipBoxesShortocde = function( args ) {

		shortcodeData = '[flip_boxes';
		shortcodeData+= ' columns="'+args[0].value+'"';
		shortcodeData+= ' class="'+args[1].value+'"';
		shortcodeData+= ' id="'+args[2].value+'"]';

		totalElements 		= args[3].elements.length;

		for ( i = 0;  i < totalElements; i++ ) {
			subElements 		= args[3].elements[i];
			shortcodeData+= '[flip_box';
			shortcodeData+= ' title_front="'+subElements[0].value+'"';
			shortcodeData+= ' title_back="'+subElements[1].value+'"';
			shortcodeData+= ' text_front="'+subElements[2].value+'"';
			shortcodeData+= ' background_color_front="'+subElements[4].value+'"';
			shortcodeData+= ' title_front_color="'+subElements[5].value+'"';
			shortcodeData+= ' text_front_color="'+subElements[6].value+'"';
			shortcodeData+= ' background_color_back="'+subElements[7].value+'"';
			shortcodeData+= ' title_back_color="'+subElements[8].value+'"';
			shortcodeData+= ' text_back_color="'+subElements[9].value+'"';
			shortcodeData+= ' border_size="'+subElements[10].value+'"';
			shortcodeData+= ' border_color="'+subElements[11].value+'"';
			shortcodeData+= ' border_radius="'+subElements[12].value+'"';
			shortcodeData+= ' icon="'+subElements[13].value+'"';
			shortcodeData+= ' icon_color="'+subElements[14].value+'"';
			shortcodeData+= ' circle="'+subElements[15].value+'"';
			shortcodeData+= ' circle_color="'+subElements[16].value+'"';
			shortcodeData+= ' circle_border_color="'+subElements[17].value+'"';
			shortcodeData+= ' icon_rotate="'+subElements[18].value+'"';
			shortcodeData+= ' icon_spin="'+subElements[19].value+'"';
			shortcodeData+= ' image="'+subElements[20].value+'"';
			shortcodeData+= ' image_width="'+subElements[21].value+'"';
			shortcodeData+= ' image_height="'+subElements[22].value+'"';
			shortcodeData+= ' animation_type="'+subElements[23].value+'"';
			shortcodeData+= ' animation_direction="'+subElements[24].value+'"';
			shortcodeData+= ' animation_speed="'+subElements[25].value+'"';
			shortcodeData+= ' animation_offset="'+subElements[26].value+'"]';
			shortcodeData+=   subElements[3].value;
			shortcodeData+= '[/flip_box]';
		}
		shortcodeData+= '[/flip_boxes]';

		return shortcodeData;

	}
	/**
	* Returns Font Awesome shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Font Awesome doable shortcode
	**/
	fusionParser.buildFontAwesomeShortocde = function( args ) {

		shortcodeData = '[fontawesome';
		shortcodeData+=' icon="'+args[0].value+'"';
		shortcodeData+=' circle="'+args[1].value+'"';
		shortcodeData+=' size="'+args[2].value+'"';
		shortcodeData+=' iconcolor="'+args[3].value+'"';
		shortcodeData+=' circlecolor="'+args[4].value+'"';
		shortcodeData+=' circlebordercolor="'+args[5].value+'"';
		shortcodeData+=' rotate="'+args[6].value+'"';
		shortcodeData+=' spin="'+args[7].value+'"';
		shortcodeData+=' animation_type="'+args[8].value+'"';
		shortcodeData+=' animation_direction="'+args[9].value+'"';
		shortcodeData+=' animation_speed="'+args[10].value+'"';
		shortcodeData+=' animation_offset="'+args[11].value+'"';
		shortcodeData+=' alignment="'+args[12].value+'"';
		shortcodeData+=' class="'+args[13].value+'"';
		shortcodeData+=' id="'+args[14].value+'"]';

		return shortcodeData;
	}
	/**
	* Returns Full Width Container shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Full Widht Container doable shortcode
	**/
	fusionParser.buildFullWidthContainerShortocde = function( args ) {

		shortcodeData = '[fullwidth';
		shortcodeData+= ' backgroundcolor="'+args[0].value+'"';
		shortcodeData+= ' backgroundimage="'+args[1].value.replace('fusion-hidden-img','')+'"';
		shortcodeData+= ' backgroundrepeat="'+args[2].value+'"';
		shortcodeData+= ' backgroundposition="'+args[3].value+'"';
		shortcodeData+= ' backgroundattachment="'+args[4].value+'"';
		shortcodeData+= ' video_webm="'+args[5].value+'"';
		shortcodeData+= ' video_mp4="'+args[6].value+'"';
		shortcodeData+= ' video_ogv="'+args[7].value+'"';
		shortcodeData+= ' video_preview_image="'+args[8].value+'"';
		shortcodeData+= ' overlay_color="'+args[9].value+'"';
		shortcodeData+= ' overlay_opacity="'+args[10].value+'"';
		shortcodeData+= ' video_mute="'+args[11].value+'"';
		shortcodeData+= ' video_loop="'+args[12].value+'"';
		shortcodeData+= ' fade="'+args[13].value+'"';
		shortcodeData+= ' bordersize="'+args[14].value+'"';
		shortcodeData+= ' bordercolor="'+args[15].value+'"';
		shortcodeData+= ' borderstyle="'+args[16].value+'"';
		shortcodeData+= ' paddingtop="'+args[17].value+'px"';
		shortcodeData+= ' paddingbottom="'+args[18].value+'px"';
		shortcodeData+= ' paddingleft="'+args[19].value+'px"';
		shortcodeData+= ' paddingright="'+args[20].value+'px"';
		shortcodeData+= ' menu_anchor="'+args[21].value+'"';
		shortcodeData+= ' equal_height_columns="'+args[22].value+'"';
		shortcodeData+= ' hundred_percent="'+args[23].value+'"';
		shortcodeData+= ' class="'+args[24].value+'"';
		shortcodeData+= ' id="'+args[25].value+'"]';

		return shortcodeData;
	}
	/**
	* Returns Google Map shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Google Map doable shortcode
	**/
	fusionParser.buildGoogleMapShortocde = function( args ) {

		shortcodeData = '[map';
		shortcodeData+= ' address="'+args[16].value+'"';
		shortcodeData+= ' type="'+args[0].value+'"';
		shortcodeData+= ' map_style="'+args[9].value+'"';
		shortcodeData+= ' overlay_color="'+args[10].value+'"';
		shortcodeData+= ' infobox="'+args[11].value+'"';
		shortcodeData+= ' infobox_background_color="'+args[14].value+'"';
		shortcodeData+= ' infobox_text_color="'+args[13].value+'"';
		shortcodeData+= ' infobox_content="'+_.escape(args[12].value)+'"';
		shortcodeData+= ' icon="'+args[15].value+'"';
		shortcodeData+= ' width="'+args[1].value+'"';
		shortcodeData+= ' height="'+args[2].value+'"';
		shortcodeData+= ' zoom="'+args[3].value+'"';
		shortcodeData+= ' scrollwheel="'+args[4].value+'"';
		shortcodeData+= ' scale="'+args[5].value+'"';
		shortcodeData+= ' zoom_pancontrol="'+args[6].value+'"';
		shortcodeData+= ' animation="'+args[7].value+'"';
		shortcodeData+= ' popup="'+args[8].value+'"';
		shortcodeData+= ' class="'+args[17].value+'"';
		shortcodeData+= ' id="'+args[18].value+'"]';
		shortcodeData+= '[/map]';

		return shortcodeData;
	}
	/**
	* Returns Highlight shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Highlight doable shortcode
	**/
	/*fusionParser.buildHighlightShortocde = function( args ) {

		shortcodeData = '[highlight';
		shortcodeData+=' color="'+args[0].value+'"';
		shortcodeData+=' rounded="'+args[1].value+'"';
		shortcodeData+=' class="'+args[3].value+'"';
		shortcodeData+=' id="'+args[4].value+'"]';
		shortcodeData+=args[2].value;
		shortcodeData+='[/highlight]';

		return shortcodeData;
	}*/
	/**
	* Returns Image Frame shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Image Frame doable shortcode
	**/
	fusionParser.buildImageFrameShortocde = function( args ) {
		shortcodeData = '[imageframe';
		shortcodeData+= ' lightbox="'+args[7].value+'"';
		shortcodeData+= ' gallery_id="'+args[8].value+'"';
		shortcodeData+= ' lightbox_image="'+args[9].value+'"';
		shortcodeData+= ' style_type="'+args[0].value+'"';
		shortcodeData+= ' hover_type="'+args[1].value+'"';
		shortcodeData+= ' bordercolor="'+args[2].value+'"';
		shortcodeData+= ' bordersize="'+args[3].value+'"';
		shortcodeData+= ' borderradius="'+args[4].value+'"';
		shortcodeData+= ' stylecolor="'+args[5].value+'"';
		shortcodeData+= ' align="'+args[6].value+'"';
		shortcodeData+= ' link="'+args[12].value+'"';
		shortcodeData+= ' linktarget="'+args[13].value+'"';
		shortcodeData+= ' animation_type="'+args[14].value+'"';
		shortcodeData+= ' animation_direction="'+args[15].value+'"';
		shortcodeData+= ' animation_speed="'+args[16].value+'"';
		shortcodeData+= ' animation_offset="'+args[17].value+'"';
		shortcodeData+= ' hide_on_mobile="'+args[18].value+'"';
		shortcodeData+= ' class="'+args[19].value+'"';
		shortcodeData+= ' id="'+args[20].value+'"]';
		shortcodeData+= ' <img alt="'+args[11].value+'"';
		shortcodeData+= ' src="'+args[10].value+'" />';
		shortcodeData+= '[/imageframe]';

		return shortcodeData;
	}
	/**
	* Returns Image Carousel shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Image Carousel doable shortcode
	**/
	fusionParser.buildImageCarouselShortocde = function( args ) {

		shortcodeData = '[images';
		shortcodeData+= ' picture_size="'+args[0].value+'"';
		shortcodeData+= ' hover_type="'+args[1].value+'"';
		shortcodeData+= ' autoplay="'+args[2].value+'"';
		shortcodeData+= ' columns="'+args[3].value+'"';
		shortcodeData+= ' column_spacing="'+args[4].value+'"';
		shortcodeData+= ' scroll_items="'+args[5].value+'"';
		shortcodeData+= ' show_nav="'+args[6].value+'"';
		shortcodeData+= ' mouse_scroll="'+args[7].value+'"';
		shortcodeData+= ' border="'+args[8].value+'"';
		shortcodeData+= ' lightbox="'+args[9].value+'"';
		shortcodeData+= ' class="'+args[10].value+'"';
		shortcodeData+= ' id="'+args[11].value+'"]';

		totalElements = args[12].elements.length;

		for (i = 0; i < totalElements; i++) {
			element = args[12].elements[i];
			shortcodeData+= '[image';
			shortcodeData+= ' link="'+element[0].value+'"';
			shortcodeData+= ' linktarget="'+element[1].value+'"';
			shortcodeData+= ' image="'+element[2].value+'"';
			shortcodeData+= ' alt="'+element[3].value+'"]';

		}

		shortcodeData+= '[/images]';

		return shortcodeData;
	}
	/**
	* Returns Layer Slider shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Layer Slider doable shortcode
	**/
	fusionParser.buildLayerSliderShortocde = function( args ) {

		shortcodeData = '[layerslider';
		shortcodeData+= ' id="'+args[0].value+'"]';
		return shortcodeData;
	}
	/**
	* Returns correct shortcode for login/register/lost password
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		login/register/lost password doable shortcode
	**/
	fusionParser.buildLoginShortcodes = function( args ) {
		shortcodeData = '[';
		shortcodeData+= args[0].value;
		shortcodeData+=' text_align="'+args[1].value+'"';
		shortcodeData+=' heading="'+args[2].value+'"';
		shortcodeData+=' caption="'+args[3].value+'"';
		shortcodeData+=' button_fullwidth="'+args[4].value+'"';
		shortcodeData+=' form_background_color="'+args[5].value+'"';
		shortcodeData+=' heading_color="'+args[6].value+'"';
		shortcodeData+=' caption_color="'+args[7].value+'"';
		shortcodeData+=' link_color="'+args[8].value+'"';
		shortcodeData+=' redirection_link="'+args[9].value+'"';

		if ( args[0].value == 'fusion_login' ) {
			shortcodeData+=' register_link="'+args[10].value+'"';
			shortcodeData+=' lost_password_link="'+args[11].value+'"';
		}

		shortcodeData+=' class="'+args[12].value+'"';
		shortcodeData+=' id="'+args[13].value+'"';
		shortcodeData+= ']';

		return shortcodeData;
	}
	/**
	* Returns Menu Anchor shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Menu Anchor doable shortcode
	**/
	fusionParser.buildMenuAnchorShortocde = function( args ) {

		return '[menu_anchor name="'+args[0].value+'"]';
	}
	/**
	* Returns Modal shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Modal doable shortcode
	**/
	fusionParser.buildModalShortocde = function( args ) {

		shortcodeData = '[modal';
		shortcodeData+=' name="'+args[0].value+'"';
		shortcodeData+=' title="'+args[1].value+'"';
		shortcodeData+=' size="'+args[2].value+'"';
		shortcodeData+=' background="'+args[3].value+'"';
		shortcodeData+=' border_color="'+args[4].value+'"';
		shortcodeData+=' show_footer="'+args[5].value+'"';
		shortcodeData+=' class="'+args[7].value+'"';
		shortcodeData+=' id="'+args[8].value+'"]';
		shortcodeData+=args[6].value;
		shortcodeData+='[/modal]';

		return shortcodeData;
	}
	/**
	* Returns Modal Link shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Modal Hook doable shortcode
	**/
	/*fusionParser.buildModalLinkShortocde = function( args ) {

		shortcodeData = '[modal_text_link ';
		shortcodeData+=' name="{{'+args[0].value+'}}" ';
		shortcodeData+=' class="'+args[1].value+'" ';
		shortcodeData+=' id="'+args[2].value+'"] ';
		shortcodeData+= ' \r';
		return shortcodeData;
	}*/
	/**
	* Returns Person shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Person doable shortcode
	**/
	fusionParser.buildPersonShortocde = function( args ) {

		shortcodeData = '[person';
		shortcodeData+= ' name="'+args[0].value+'"';
		shortcodeData+= ' title="'+args[1].value+'"';
		shortcodeData+= ' picture="'+args[3].value+'"';
		shortcodeData+= ' pic_link="'+args[4].value+'"';
		shortcodeData+= ' linktarget="'+args[5].value+'"';
		shortcodeData+= ' pic_style="'+args[6].value+'"';
		shortcodeData+= ' hover_type="'+args[7].value+'"';
		shortcodeData+= ' background_color="'+args[8].value+'"';
		shortcodeData+= ' content_alignment="'+args[9].value+'"';
		shortcodeData+= ' pic_style_color="'+args[10].value+'"';
		shortcodeData+= ' pic_bordersize="'+args[11].value+'"';
		shortcodeData+= ' pic_bordercolor="'+args[12].value+'"';
		shortcodeData+= ' pic_borderradius="'+args[13].value+'"';
		shortcodeData+= ' icon_position="'+args[14].value+'"';
		shortcodeData+= ' social_icon_boxed="'+args[15].value+'"';
		shortcodeData+= ' social_icon_boxed_radius="'+args[16].value+'"';
		shortcodeData+= ' social_icon_color_type="'+args[17].value+'"';
		shortcodeData+= ' social_icon_colors="'+args[18].value+'"';
		shortcodeData+= ' social_icon_boxed_colors="'+args[19].value+'"';
		shortcodeData+= ' social_icon_tooltip="'+args[20].value+'"';
		shortcodeData+= ' email="'+args[21].value+'"';
		shortcodeData+= ' facebook="'+args[22].value+'"';
		shortcodeData+= ' twitter="'+args[23].value+'"';
		shortcodeData+= ' instagram="'+args[24].value+'"';
		shortcodeData+= ' dribbble="'+args[25].value+'"';
		shortcodeData+= ' google="'+args[26].value+'"';
		shortcodeData+= ' linkedin="'+args[27].value+'"';
		shortcodeData+= ' blogger="'+args[28].value+'"';
		shortcodeData+= ' tumblr="'+args[29].value+'"';
		shortcodeData+= ' reddit="'+args[30].value+'"';
		shortcodeData+= ' yahoo="'+args[31].value+'"';
		shortcodeData+= ' deviantart="'+args[32].value+'"';
		shortcodeData+= ' vimeo="'+args[33].value+'"';
		shortcodeData+= ' youtube="'+args[34].value+'"';
		shortcodeData+= ' pinterest="'+args[35].value+'"';
		shortcodeData+= ' rss="'+args[36].value+'"';
		shortcodeData+= ' digg="'+args[37].value+'"';
		shortcodeData+= ' flickr="'+args[38].value+'"';
		shortcodeData+= ' forrst="'+args[39].value+'"';
		shortcodeData+= ' myspace="'+args[40].value+'"';
		shortcodeData+= ' skype="'+args[41].value+'"';
		shortcodeData+= ' paypal="'+args[42].value+'"';
		shortcodeData+= ' dropbox="'+args[43].value+'"';
		shortcodeData+= ' soundcloud="'+args[44].value+'"';
		shortcodeData+= ' vk="'+args[45].value+'"';
		shortcodeData+= ' xing="'+args[46].value+'"';
		shortcodeData+= ' show_custom="'+args[47].value+'"';
		shortcodeData+= ' class="'+args[48].value+'"';
		shortcodeData+= ' id="'+args[49].value+'"]';
		shortcodeData+=   args[2].value;
		shortcodeData+= '[/person]';

		return shortcodeData;
	}
	/**
	* Returns Popover shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Popover doable shortcode
	**/
	/*fusionParser.buildPopoverShortocde = function( args ) {
		shortcodeData = '[popover ';
		shortcodeData+= ' title="'+args[0].value+'" ';
		shortcodeData+= ' title_bg_color="'+args[1].value+'" ';
		shortcodeData+= ' content="'+args[2].value+'" ';
		shortcodeData+= ' content_bg_color="'+args[3].value+'" ';
		shortcodeData+= ' bordercolor="'+args[4].value+'" ';
		shortcodeData+= ' textcolor="'+args[5].value+'" ';
		shortcodeData+= ' trigger="'+args[6].value+'" ';
		shortcodeData+= ' placement="'+args[7].value+'" ';
		shortcodeData+= ' class="'+args[9].value+'" ';
		shortcodeData+= ' id="'+args[10].value+'"] ';
		shortcodeData+=   args[8].value;
		shortcodeData+= ' [/popover]';
		shortcodeData+= ' \r';

		return shortcodeData;

	}*/
	/**
	* Returns Pricing Table shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Pricing Table doable shortcode
	**/
	fusionParser.buildPricingTableShortocde = function( args ) {

		return args[7].value;
	}
	/**
	* Returns Progress Bar shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Progress Bar doable shortcode
	**/
	fusionParser.buildProgressBarShortocde = function( args ) {

		shortcodeData = '[progress';
		shortcodeData += ' height="'+args[0].value+'"';
		shortcodeData += ' text_position="'+args[1].value+'"';
		shortcodeData += ' percentage="'+args[2].value+'"';
		shortcodeData += ' show_percentage="'+args[3].value+'"';
		shortcodeData += ' unit="'+args[4].value+'"';
		shortcodeData += ' filledcolor="'+args[5].value+'"';
		shortcodeData += ' filledbordercolor="'+args[6].value+'"';
		shortcodeData += ' filledbordersize="'+args[7].value+'"';
		shortcodeData += ' unfilledcolor="'+args[8].value+'"';
		shortcodeData += ' striped="'+args[9].value+'"';
		shortcodeData += ' animated_stripes="'+args[10].value+'"';
		shortcodeData += ' textcolor="'+args[11].value+'"';
		shortcodeData += ' class="'+args[13].value+'"';
		shortcodeData += ' id="'+args[14].value+'"]';
		shortcodeData +=   args[12].value;
		shortcodeData += '[/progress]';

		return shortcodeData ;

	}
	/**
	* Returns Recent Posts shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Recent Posts doable shortcode
	**/
	fusionParser.buildRecentPostsShortocde = function( args ) {

		shortcodeData = '[recent_posts';
		shortcodeData+= ' layout="'+args[0].value+'"';
		shortcodeData+= ' hover_type="'+args[1].value+'"';
		shortcodeData+= ' columns="'+args[2].value+'"';
		shortcodeData+= ' number_posts="'+args[3].value+'"';
		shortcodeData+= ' offset="'+args[4].value+'"';
		shortcodeData+= ' cat_slug="'+fusionParser.getUniqueElements(args[5].value).join()+'"';
		shortcodeData+= ' exclude_cats="'+fusionParser.getUniqueElements(args[6].value).join()+'"';
		shortcodeData+= ' thumbnail="'+args[7].value+'"';
		shortcodeData+= ' title="'+args[8].value+'"';
		shortcodeData+= ' meta="'+args[9].value+'"';
		shortcodeData+= ' excerpt="'+args[10].value+'"';
		shortcodeData+= ' excerpt_length="'+args[11].value+'"';
		shortcodeData+= ' strip_html="'+args[12].value+'"';
		shortcodeData+= ' animation_type="'+args[13].value+'"';
		shortcodeData+= ' animation_direction="'+args[14].value+'"';
		shortcodeData+= ' animation_speed="'+args[15].value+'"';
		shortcodeData+= ' animation_offset="'+args[16].value+'"';
		shortcodeData+= ' class="'+args[17].value+'"';
		shortcodeData+= ' id="'+args[18].value+'"]';
		shortcodeData+= '[/recent_posts]';

		return shortcodeData;
	}
	/**
	* Returns Recent Works shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Recent Works doable shortcode
	**/
	fusionParser.buildRecentWorksShortocde = function( args ) {

		shortcodeData = '[recent_works';
		shortcodeData+= ' layout="'+args[0].value+'"';
		shortcodeData+= ' picture_size="'+args[1].value+'"';
		shortcodeData+= ' boxed_text="'+args[2].value+'"';
		shortcodeData+= ' filters="'+args[3].value+'"';
		shortcodeData+= ' columns="'+args[4].value+'"';
		shortcodeData+= ' column_spacing="'+args[5].value+'"';
		shortcodeData+= ' cat_slug="'+fusionParser.getUniqueElements(args[6].value).join()+'"';
		shortcodeData+= ' exclude_cats="'+fusionParser.getUniqueElements(args[7].value).join()+'"';
		shortcodeData+= ' number_posts="'+args[8].value+'"';
		shortcodeData+= ' offset="'+args[9].value+'"';
		shortcodeData+= ' excerpt_length="'+args[10].value+'"';
		shortcodeData+= ' strip_html="'+args[11].value+'"';
		shortcodeData+= ' carousel_layout="'+args[12].value+'"';
		shortcodeData+= ' scroll_items="'+args[13].value+'"';
		shortcodeData+= ' autoplay="'+args[14].value+'"';
		shortcodeData+= ' show_nav="'+args[15].value+'"';
		shortcodeData+= ' mouse_scroll="'+args[16].value+'"';
		shortcodeData+= ' animation_type="'+args[17].value+'"';
		shortcodeData+= ' animation_direction="'+args[18].value+'"';
		shortcodeData+= ' animation_speed="'+args[19].value+'"';
		shortcodeData+= ' animation_offset="'+args[20].value+'"';
		shortcodeData+= ' class="'+args[21].value+'"';
		shortcodeData+= ' id="'+args[22].value+'"]';
		shortcodeData+= '[/recent_works]';

		return shortcodeData;
	}
	/**
	* Returns Revolution Slider shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Revolution Slider doable shortcode
	**/
	fusionParser.buildRevSliderShortocde = function( args ) {
		shortcodeData = '[rev_slider';
		shortcodeData+= ' alias="'+args[0].value+'"]';
		return shortcodeData ;
	}
	/**
	* Returns Section Separator shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Section Separator doable shortcode
	**/
	fusionParser.buildSectionSeparatorShortocde = function( args ) {

		shortcodeData = '[section_separator';
		shortcodeData+= ' divider_candy="'+args[0].value+'"';
		shortcodeData+= ' icon="'+args[1].value+'"';
		shortcodeData+= ' icon_color="'+args[2].value+'"';
		shortcodeData+= ' bordersize="'+args[3].value+'"';
		shortcodeData+= ' bordercolor="'+args[4].value+'"';
		shortcodeData+= ' backgroundcolor="'+args[5].value+'"';
		shortcodeData+= ' class="'+args[6].value+'"';
		shortcodeData+= ' id="'+args[7].value+'"]';

		return shortcodeData ;
	}
	/**
	* Returns Separator shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Separator doable shortcode
	**/
	fusionParser.buildSeparatorShortocde = function( args ) {

		shortcodeData = '[separator';
		shortcodeData+= ' style_type="'+args[0].value+'"';
		shortcodeData+= ' top_margin="'+args[1].value+'"';
		shortcodeData+= ' bottom_margin="'+args[2].value+'"';
		shortcodeData+= ' sep_color="'+args[3].value+'"';
		shortcodeData+= ' border_size="'+args[4].value+'"';
		shortcodeData+= ' icon="'+args[5].value+'"';
		shortcodeData+= ' icon_circle="'+args[6].value+'"';
		shortcodeData+= ' icon_circle_color="'+args[7].value+'"';
		shortcodeData+= ' width="'+args[8].value+'"';
		shortcodeData+= ' alignment="'+args[9].value+'"';
		shortcodeData+= ' class="'+args[10].value+'"';
		shortcodeData+= ' id="'+args[11].value+'"]';

		return shortcodeData;

	}
	/**
	* Returns Sharing Box shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Sharing Box doable shortcode
	**/
	fusionParser.buildSharingBoxShortocde = function( args ) {

		shortcodeData = '[sharing';
		shortcodeData+= ' tagline="'+args[0].value+'"';
		shortcodeData+= ' tagline_color="'+args[1].value+'"';
		shortcodeData+= ' title="'+args[3].value+'"';
		shortcodeData+= ' link="'+args[4].value+'"';
		shortcodeData+= ' description="'+args[5].value+'"';
		shortcodeData+= ' pinterest_image="'+args[12].value+'"';
		shortcodeData+= ' icons_boxed="'+args[6].value+'"';
		shortcodeData+= ' icons_boxed_radius="'+args[7].value+'"';
		shortcodeData+= ' color_type="'+args[8].value+'"';
		shortcodeData+= ' box_colors="'+args[10].value+'"';
		shortcodeData+= ' icon_colors="'+args[9].value+'"';
		shortcodeData+= ' tooltip_placement="'+args[11].value+'"';
		shortcodeData+= ' backgroundcolor="'+args[2].value+'"';
		shortcodeData+= ' class="'+args[13].value+'"';
		shortcodeData+= ' id="'+args[14].value+'"]';
		shortcodeData+= '[/sharing]';

		return shortcodeData;
	}
	/**
	* Returns Slider shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Slider doable shortcode
	**/
	fusionParser.buildSliderShortocde = function( args ) {
		shortcodeData = '[slider';
			shortcodeData+= ' hover_type="'+args[0].value+'"';
			shortcodeData+= ' width="'+args[1].value+'"';
			shortcodeData+= ' height="'+args[2].value+'"';
			shortcodeData+= ' class="'+args[3].value+'"';
			shortcodeData+= ' id="'+args[4].value+'"]';

			totalElements = args[5].elements.length;
			for (i = 0; i < totalElements; i++) {
				element 		= args[5].elements[i];
				shortcodeData+= '[slide';
				if( element[0].value == "image" ) {
					shortcodeData+= ' type="'+element[0].value+'"';
					shortcodeData+= ' link="'+element[2].value+'"';
					shortcodeData+= ' linktarget="'+element[3].value+'"';
					shortcodeData+= ' lightbox="'+element[4].value+'"]';
					shortcodeData+=   element[1].value;

				} else if ( element[0].value == "video" )  {
					shortcodeData+= ' type="video"]';
					shortcodeData+= 	element[5].value;
				}

				shortcodeData+= '[/slide]';

			}

			shortcodeData+= '[/slider]';

			return shortcodeData;
	}
	/**
	* Returns Sound Cloud shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Sound Cloud doable shortcode
	**/
	fusionParser.buildSoundcloudShortocde = function( args ) {

		shortcodeData = '[soundcloud';
		shortcodeData+= ' url="'+args[0].value+'"';
		shortcodeData+= ' layout="'+args[1].value+'"';
		shortcodeData+= ' comments="'+args[2].value+'"';
		shortcodeData+= ' show_related="'+args[3].value+'"';
		shortcodeData+= ' show_user="'+args[4].value+'"';
		shortcodeData+= ' auto_play="'+args[5].value+'"';
		shortcodeData+= ' color="'+args[6].value+'"';
		shortcodeData+= ' width="'+args[7].value+'"';
		shortcodeData+= ' height="'+args[8].value+'"';
		shortcodeData+= ' class="'+args[9].value+'"';
		shortcodeData+= ' id="'+args[10].value+'"]';

		return shortcodeData;
	}
	/**
	* Returns Social Links shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Social Links doable shortcode
	**/
	fusionParser.buildSocialLinksShortocde = function( args ) {

		shortcodeData = '[social_links';
		shortcodeData+= ' icons_boxed="'+args[0].value+'"';
		shortcodeData+= ' icons_boxed_radius="'+args[1].value+'"';
		shortcodeData+= ' color_type="'+args[2].value+'"';
		shortcodeData+= ' icon_colors="'+args[3].value+'"';
		shortcodeData+= ' box_colors="'+args[4].value+'"';
		shortcodeData+= ' tooltip_placement="'+args[5].value+'"';
		shortcodeData+= ' rss="'+args[20].value+'"';
		shortcodeData+= ' facebook="'+args[6].value+'"';
		shortcodeData+= ' twitter="'+args[7].value+'"';
		shortcodeData+= ' instagram="'+args[8].value+'"';
		shortcodeData+= ' dribbble="'+args[9].value+'"';
		shortcodeData+= ' google="'+args[10].value+'"';
		shortcodeData+= ' linkedin="'+args[11].value+'"';
		shortcodeData+= ' blogger="'+args[12].value+'"';
		shortcodeData+= ' tumblr="'+args[13].value+'"';
		shortcodeData+= ' reddit="'+args[14].value+'"';
		shortcodeData+= ' yahoo="'+args[15].value+'"';
		shortcodeData+= ' deviantart="'+args[16].value+'"';
		shortcodeData+= ' vimeo="'+args[17].value+'"';
		shortcodeData+= ' youtube="'+args[18].value+'"';
		shortcodeData+= ' pinterest="'+args[19].value+'"';
		shortcodeData+= ' digg="'+args[21].value+'"';
		shortcodeData+= ' flickr="'+args[22].value+'"';
		shortcodeData+= ' forrst="'+args[23].value+'"';
		shortcodeData+= ' myspace="'+args[24].value+'"';
		shortcodeData+= ' skype="'+args[25].value+'"';
		shortcodeData+= ' paypal="'+args[26].value+'"';
		shortcodeData+= ' dropbox="'+args[27].value+'"';
		shortcodeData+= ' soundcloud="'+args[28].value+'"';
		shortcodeData+= ' vk="'+args[29].value+'"';
		shortcodeData+= ' xing="'+args[30].value+'"';
		shortcodeData+= ' email="'+args[31].value+'"';
		shortcodeData+= ' show_custom="'+args[32].value+'"';
		shortcodeData+= ' alignment="'+args[33].value+'"';
		shortcodeData+= ' class="'+args[34].value+'"';
		shortcodeData+= ' id="'+args[35].value+'"]';


		return shortcodeData;
	}
	/**
	* Returns Tabs shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Tabs doable shortcode
	**/
	fusionParser.buildTabsShortocde = function( args ) {
		shortcodeData = '[fusion_tabs';
		shortcodeData+= ' design="'+args[0].value+'"';
		shortcodeData+= ' layout="'+args[1].value+'"';
		shortcodeData+= ' justified="'+args[2].value+'"';
		shortcodeData+= ' backgroundcolor="'+args[3].value+'"';
		shortcodeData+= ' inactivecolor="'+args[4].value+'"';
		shortcodeData+= ' bordercolor="'+args[5].value+'"';
		shortcodeData+= ' class="'+args[6].value+'"';
		shortcodeData+= ' id="'+args[7].value+'"]';

		totalElements =  args[8].elements.length;

		for (i = 0; i < totalElements; i++) {
			element 		= args[8].elements[i];
			shortcodeData+= '[fusion_tab';
			shortcodeData+= ' title="'+element[0].value+'"';
			shortcodeData+= ' icon="'+element[1].value+'"]';
			shortcodeData+=   element[2].value;
			shortcodeData+= '[/fusion_tab]';

		}

		shortcodeData+= '[/fusion_tabs]';

		return shortcodeData;
	}
	/**
	* Returns Table shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Table doable shortcode
	**/
	fusionParser.buildTableShortocde = function( args ) {
		return args[2].value;
	}
	/**
	* Returns Tagline shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Tagline doable shortcode
	**/
	fusionParser.buildTaglineShortocde = function( args ) {
		shortcodeData = '[tagline_box';
		shortcodeData+= ' backgroundcolor="'+args[0].value+'"';
		shortcodeData+= ' shadow="'+args[1].value+'"';
		shortcodeData+= ' shadowopacity="'+args[2].value+'"';
		shortcodeData+= ' border="'+args[3].value+'"';
		shortcodeData+= ' bordercolor="'+args[4].value+'"';
		shortcodeData+= ' highlightposition="'+args[5].value+'"';
		shortcodeData+= ' content_alignment="'+args[6].value+'"';
		shortcodeData+= ' link="'+args[8].value+'"';
		shortcodeData+= ' linktarget="'+args[9].value+'"';
		shortcodeData+= ' modal="'+args[10].value+'"';
		shortcodeData+= ' button_size="'+args[11].value+'"';
		shortcodeData+= ' button_shape="'+args[13].value+'"';
		shortcodeData+= ' button_type="'+args[12].value+'"';
		shortcodeData+= ' buttoncolor="'+args[14].value+'"';
		shortcodeData+= ' button="'+args[7].value+'"';
		shortcodeData+= ' title="' + fusionParser.htmlEncodeQuotes( args[15].value ) + '"';
		shortcodeData+= ' description="'+fusionParser.htmlEncodeQuotes( args[16].value )+'"';
		shortcodeData+= ' margin_top="'+args[18].value+'"';
		shortcodeData+= ' margin_bottom="'+args[19].value+'"';
		shortcodeData+= ' animation_type="'+args[20].value+'"';
		shortcodeData+= ' animation_direction="'+args[21].value+'"';
		shortcodeData+= ' animation_speed="'+args[22].value+'"';
		shortcodeData+= ' animation_offset="'+args[23].value+'"';
		shortcodeData+= ' class="'+args[24].value+'"';
		shortcodeData+= ' id="'+args[25].value+'"]';
		shortcodeData+= args[17].value;
		shortcodeData+= '[/tagline_box]';

		return shortcodeData;
	}
	/**
	* Returns Testimonial shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Testimonial doable shortcode
	**/
	fusionParser.buildTestimonialShortocde = function( args ) {
		shortcodeData = '[testimonials';
		shortcodeData+= ' design="'+args[0].value+'"';
		shortcodeData+= ' backgroundcolor="'+args[1].value+'"';
		shortcodeData+= ' textcolor="'+args[2].value+'"';
		shortcodeData+= ' random="'+args[3].value+'"';
		shortcodeData+= ' class="'+args[4].value+'"';
		shortcodeData+= ' id="'+args[5].value+'"]';

		totalElements = args[6].elements.length;

		for (i = 0; i < totalElements; i++) {
			element 		= args[6].elements[i];
			shortcodeData+= '[testimonial';
			shortcodeData+= ' name="'+element[0].value+'"';
			shortcodeData+= ' avatar="'+element[1].value+'"';
			shortcodeData+= ' image="'+element[2].value+'"';
			shortcodeData+= ' image_border_radius="'+element[3].value+'"';
			shortcodeData+= ' company="'+element[4].value+'"';
			shortcodeData+= ' link="'+element[5].value+'"';
			shortcodeData+= ' target="'+element[6].value+'"]';
			shortcodeData+=   element[7].value;
			shortcodeData+= '[/testimonial]';

		}
		shortcodeData+= '[/testimonials]';

		return shortcodeData;
	}
	/**
	* Returns Text Block shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Text Block doable shortcode
	**/
	fusionParser.buildTextBlockShortocde = function( args ) {

		shortcodeData = '[fusion_text]' + args[0].value.replace( '&lt;/textarea', '</textarea' ) + '[/fusion_text]';
		return shortcodeData;
	}
	/**
	* Returns Title shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Title doable shortcode
	**/
	fusionParser.buildTitleShortocde = function( args ) {
		shortcodeData = '[title';
		shortcodeData+= ' size="'+args[0].value+'"';
		shortcodeData+= ' content_align="'+args[1].value+'"';
		shortcodeData+= ' style_type="'+args[2].value+'"';
		shortcodeData+= ' sep_color="'+args[3].value+'"';
		shortcodeData+= ' margin_top="'+args[4].value+'"';
		shortcodeData+= ' margin_bottom="'+args[5].value+'"';
		shortcodeData+= ' class="'+args[7].value+'"';
		shortcodeData+= ' id="'+args[8].value+'"]';
		shortcodeData+= args[6].value;
		shortcodeData+= '[/title]';
		return shortcodeData;
	}
	/**
	* Returns Toggles shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Toggles doable shortcode
	**/
	fusionParser.buildTogglesShortocde = function( args ) {

		shortcodeData = '[accordian';
		shortcodeData+= ' divider_line="'+args[0].value+'"';
		shortcodeData+= ' class="'+args[1].value+'"';
		shortcodeData+= ' id="'+args[2].value+'"]';

		totalElements =  args[3]['elements'].length ;

		for (i = 0; i < totalElements; i++) {
			element 		= args[3]['elements'][i];

			shortcodeData+= '[toggle';
			shortcodeData+= ' title="'+element[0].value+'"';
			shortcodeData+= ' open="'+element[1].value+'"]';
			shortcodeData+=   element[2].value;
			shortcodeData+= '[/toggle]';
		}

		shortcodeData+= '[/accordian]';

		return shortcodeData;
	}

	/**
	* Returns Widget Area shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Widget Area doable shortcode
	**/
	fusionParser.buildWidgetAreaShortcode = function( args ) {
		shortcodeData = '[fusion_widget_area';
		shortcodeData+= ' name="'+args[0].value+'"';
		shortcodeData+= ' background_color="'+args[1].value+'"';
		shortcodeData+= ' padding="'+args[2].value+'"';
		shortcodeData+= ' class="'+args[3].value+'"';
		shortcodeData+= ' id="'+args[4].value+'"]';
		shortcodeData+= '[/fusion_widget_area]';

		return shortcodeData;
	}

	/**
	* Returns Tooltip shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Tooltip doable shortcode
	**/
	/*fusionParser.buildTooltipShortocde = function( args ) {

		shortcodeData = '[tooltip ';
		shortcodeData+= ' title="'+args[0].value+'" ';
		shortcodeData+= ' placement="'+args[1].value+'" ';
		shortcodeData+= ' class="'+args[3].value+'" ';
		shortcodeData+= ' id="'+args[4].value+'"] ';
		shortcodeData+= args[2].value;
		shortcodeData+= ' [/tooltip]';
		shortcodeData+= ' \r';
		return shortcodeData;
	}*/
	/**
	* Returns Vimeo shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Vimeo doable shortcode
	**/
	//fusionParser.buildVimeoShortocde = function( args ) {
	//
	//	shortcodeData = '[vimeo';
	//	shortcodeData+= ' id="'+args[0].value+'"';
	//	shortcodeData+= ' width="'+args[1].value+'"';
	//	shortcodeData+= ' height="'+args[2].value+'"';
	//	shortcodeData+= ' autoplay="'+args[3].value+'"';
	//	shortcodeData+= ' api_params="'+args[4].value+'"';
	//	shortcodeData+= ' class="'+args[5].value+'"]';
	//
	//	return shortcodeData;
	//}
	/**
	* Returns Woo Featured shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Woo Featured doable shortcode
	**/
	//fusionParser.buildWooFeaturedShortocde = function( args ) {
	//
	//	shortcodeData = '[featured_products_slider';
	//	shortcodeData+= ' class="'+args[1].value+'"';
	//	shortcodeData+= ' id="'+args[2].value+'"]';
	//
	//	return shortcodeData;
	//}
	/**
	* Returns Woo Slider shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Woo Slider doable shortcode
	**/
	//fusionParser.buildWooCarouselShortocde = function( args ) {
	//
	//	shortcodeData = '[products_slider';
	//	shortcodeData+= ' picture_size="'+args[0].value+'"';
	//	shortcodeData+= ' cat_slug="'+fusionParser.getUniqueElements(args[1].value).join()+'"';
	//	shortcodeData+= ' number_posts="'+args[2].value+'"';
	//	shortcodeData+= ' carousel_layout="'+args[3].value+'"';
	//	shortcodeData+= ' autoplay="'+args[4].value+'"';
	//	shortcodeData+= ' columns="'+args[5].value+'"';
	//	shortcodeData+= ' column_spacing="'+args[6].value+'"';
	//	shortcodeData+= ' show_nav="'+args[7].value+'"';
	//	shortcodeData+= ' mouse_scroll="'+args[8].value+'"';
	//	shortcodeData+= ' show_cats="'+args[9].value+'"';
	//	shortcodeData+= ' show_price="'+args[10].value+'"';
	//	shortcodeData+= ' show_buttons="'+args[11].value+'"';
	//	shortcodeData+= ' class="'+args[12].value+'"';
	//	shortcodeData+= ' id="'+args[13].value+'"]';
	//
	//	return shortcodeData;
	//}
	/**
	* Returns Woo Shortcodes shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Woo Shortcodes doable shortcode
	**/
	fusionParser.buildWooShortcodes = function( args ) {
		return args[1].value;
	}
	/**
	* Returns Youtube shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Youtube doable shortcode
	**/
	//fusionParser.buildYoutubeShortocde = function( args ) {
	//
	//	shortcodeData = '[youtube';
	//	shortcodeData+= ' id="'+args[0].value+'"';
	//	shortcodeData+= ' width="'+args[1].value+'"';
	//	shortcodeData+= ' height="'+args[2].value+'"';
	//	shortcodeData+= ' autoplay="'+args[3].value+'"';
	//	shortcodeData+= ' api_params="'+args[4].value+'"';
	//	shortcodeData+= ' class="'+args[5].value+'"]';
	//
	//	return shortcodeData;
	//}
	/**
	* Returns Fusion Slider shortcode
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having element data
	*
	* @return 		String		Fusion Slider doable shortcode
	**/
	//fusionParser.buildFusionSliderShortocde = function( args ) {
	//
	//	shortcodeData = '[fusionslider';
	//	shortcodeData+= ' name="'+args[0].value+'"';
	//	shortcodeData+= ' class="'+args[1].value+'"';
	//	shortcodeData+= ' id="'+args[2].value+'"]';
	//
	//	return shortcodeData;
	//}
	/**
	* Returns Events Shortcode
	*
	* @since        2.0.0
	*
	* @param        OBJECT      Object having element data
	*
	* @return       String      Events doable shortcode
	**/
	fusionParser.buildFusionEventsShortcode = function( args ) {

		shortcodeData = '[fusion_events';
		shortcodeData+= ' cat_slug="'+args[0].value+'"';
		shortcodeData+= ' number_posts="'+args[1].value+'"';
		shortcodeData+= ' columns="'+args[2].value+'"';
		shortcodeData+= ' picture_size="'+args[3].value+'"';
		shortcodeData+= ' class="'+args[4].value+'"';
		shortcodeData+= ' id="'+args[5].value+'"]';
		shortcodeData+= '[/fusion_events]';

		return shortcodeData;
	}
	/**
	* Returns unique elements
	*
	* @since	 	2.0.0
	*
	* @param		OBJECT 		Object having duplicate elements data
	*
	* @return 		OBJECT		Object with removed duplicates
	**/
	fusionParser.getUniqueElements = function unique( list ) {
		var result = [];
		$.each(list, function(i, e) {
			if ($.inArray(e, result) == -1 && e != '')
				result.push(e);
		});

		return result;
	}
	/**
	* Encode content to base64
	*
	* @since	 	1.6.2
	*
	* @param		STRING 		String containing code and other content
	*
	* @return 		STRING		Base64 encoded content
	**/
	fusionParser.base64Encode = function(data) {

		var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
		var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
		ac = 0,
		enc = '',
		tmp_arr = [];

		if (!data) {
			return data;
		}

		data = unescape(encodeURIComponent(data));

		do {
				// pack three octets into four hexets
				o1 = data.charCodeAt(i++);
				o2 = data.charCodeAt(i++);
				o3 = data.charCodeAt(i++);

				bits = o1 << 16 | o2 << 8 | o3;

				h1 = bits >> 18 & 0x3f;
				h2 = bits >> 12 & 0x3f;
				h3 = bits >> 6 & 0x3f;
				h4 = bits & 0x3f;

				// use hexets to index into b64, and append result to encoded string
				tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
		} while (i < data.length);

		enc = tmp_arr.join('');

		var r = data.length % 3;

		return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
	}

	/**
	* Encode Quotes for HTML
	*
	* @since	 	1.8
	*
	* @param string $text		String that needs to be encoded.
	*
	* @return string			The HTML encoded string.
	**/
	fusionParser.htmlEncodeQuotes = function( $text ) {
		return $text
		.replace (/"/g, '&quot;' )
		.replace( /'/g, '&#039;' )
	}

  })(jQuery);

