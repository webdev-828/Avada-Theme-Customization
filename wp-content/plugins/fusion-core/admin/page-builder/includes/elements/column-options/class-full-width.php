<?php

	/**
	 * FullWidthContainer implementation, it extends DDElementTemplate like all other elements
	 */
	class TF_FullWidthContainer extends DDElementTemplate {

		public function __construct() {

			parent::__construct();
		}

		public function deprecated_args( $args ) {

			/*
		        'backgroundattachment' => '', // deprecated
				'backgroundcolor'      => '', // deprecated
				'backgroundimage'      => '', // deprecated
				'backgroundposition'   => '', // deprecated
				'backgroundrepeat'     => '', // deprecated
				'bordercolor'          => '', // deprecated
				'bordersize'           => '', // deprecated
				'borderstyle'          => '', // deprecated
				'paddingbottom'        => '', // deprecated
				'paddingleft'          => '', // deprecated
				'paddingright'         => '', // deprecated
				'paddingtop'           => '', // deprecated
				'paddingBottom'        => '', // deprecated
				'paddingTop'           => '', // deprecated
			*/

			$toChange = array(
				'backgroundposition'    => 'background_position',
				'backgroundcolor'       => 'background_color',
				'backgroundattachment'  => 'background_parallax',
				'background_attachment' => 'background_parallax',
				'bordersize'            => 'border_size',
				'bordercolor'           => 'border_color',
				'borderstyle'           => 'border_style',
				'paddingtop'            => 'padding_top',
				'paddingbottom'         => 'padding_bottom',
				'paddingleft'           => 'padding_left',
				'paddingright'          => 'padding_right',
				'backgroundcolor'       => 'background_color',
				'backgroundimage'       => 'background_image',
				'backgroundrepeat'      => 'background_repeat',
				'paddingBottom'         => 'padding_bottom',
				'paddingTop'            => 'padding_top',
			);
			foreach ( $toChange as $old => $new ) {
				if ( isset( $args[ $old ] ) && ! empty( $args[ $old ] ) ) {
					if ( ! isset( $args[ $new ] ) || ( isset( $args[ $new ] ) && empty( $args[ $new ] ) && ! empty( $args[ $old ] ) ) ) {
						$args[ $new ] = $args[ $old ];
						unset( $args[ $old ] );
					}
				}
			}

			return $args;
		}

		// Implementation for the element structure.
		public function create_element_structure() {
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] = get_class( $this );
			// element id
			$this->config['id'] = 'full_width_container';
			// element shortcode base
			$this->config['base'] = 'fullwidth';
			// element name
			$this->config['name'] = __( 'Full Width Container', 'fusion-core' );
			// element icon
			$this->config['icon_url'] = "icons/sc-icon_box.png";
			// css class related to this element
			$this->config['css_class'] = "item-wrapper fusion_full_width sortable-element drag-element fusion_layout_column fusion_full_width item-container sort-container ui-draggable";
			// element icon class
			$this->config['icon_class'] = 'fusion-icon builder-options-icon fusiona-move-horizontal';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  	= 'Creates a Full Width Container';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] = array( "drop_level" => "2" );
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {

			$innerHtml = '<div class="fusion_iconbox textblock_element textblock_element_style">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="icon-move-horizontal"></i><sub class="sub">Full Width Container</sub></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = '';
		}

		//this function defines FullWidth sub elements or structure
		function popup_elements() {
			$this->config['layout_opt'] = true;
			$border_size                = FusionHelper::fusion_create_dropdown_data( 1, 10 );
			$padding_data               = FusionHelper::fusion_create_dropdown_data( 1, 100 );
			$parallax_speed 			= array(
								'0.1' => '0.1',
								'0.2' => '0.2',
								'0.3' => '0.3',
								'0.4' => '0.4',
								'0.5' => '0.5',
								'0.6' => '0.6',
								'0.7' => '0.7',
								'0.8' => '0.8',
								'0.9' => '0.9',
								'1'   => '1' 
							);

			$this->config['subElements'] = array(

				array(
					"name"  => __( 'Background Color', 'fusion-core' ),
					"desc"  => __( 'Controls the background color. Leave blank for theme option selection.', 'fusion-core' ),
					"id"    => "background_color",
					"group" => __( 'Background', 'fusion-core' ),
					"type"  => ElementTypeEnum::COLOR,
					"value" => ""
				),
				array(
					"name"  => __( 'Background Image', 'fusion-core' ),
					"desc"  => __( 'Upload an image to display in the background', 'fusion-core' ),
					"id"    => "background_image",
					"group" => __( 'Background', 'fusion-core' ),
					"type"  => ElementTypeEnum::UPLOAD,
					"data"  => array(
						"replace" => "fusion-hidden-img"
					),
					"upid"  => "1",
					"value" => ""
				),
				array(
					"name"          => __( 'Background Parallax', 'fusion-core' ),
					"desc"          => __( 'Choose how the background image scrolls and responds.', 'fusion-core' ),
					"id"            => "background_parallax",
					"type"          => ElementTypeEnum::SELECT,
					"group"         => __( 'Background', 'fusion-core' ),
					"value"         => "none",
					"allowedValues" => array(
						'none'  => __( 'No Parallax (no effects)', 'fusion-core' ),
						'fixed' => __( 'Fixed (fixed on desktop, non-fixed on mobile)', 'fusion-core' ),
						'up'    => __( 'Up (moves up on desktop & mobile)', 'fusion-core' ),
						'down'  => __( 'Down (moves down on desktop & mobile)', 'fusion-core' ),
						'left'  => __( 'Left (moves left on desktop & mobile)', 'fusion-core' ),
						'right' => __( 'Right (moves right on desktop & mobile)', 'fusion-core' ),
						//'hover' => __( 'Hover', 'fusion-core' ),
					)
				),
				array(
					"name"          => __( 'Enable Parallax on Mobile', 'fusion-core' ),
					"desc"          => __( 'Works for up/down/left/right only. Parallax effects would most probably cause slowdowns when your site is viewed in mobile devices. If the device width is less than 980 pixels, then it is assumed that the site is being viewed in a mobile device.', 'fusion-core' ),
					"id"            => "enable_mobile",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "no",
					"group"         => __( 'Background', 'fusion-core' ),
					"allowedValues" => array(
						'no'  => __( 'No', 'fusion-core' ),
						'yes' => __( 'Yes', 'fusion-core' ),
					)
				),				
				array(
					"name"  => __( 'Parallax Speed', 'fusion-core' ),
					"desc"  => __( 'The movement speed, value should be between 0.1 and 1.0. A lower number means slower scrolling speed. Higher scrolling speeds will enlarge the image more.', 'fusion-core' ),
					"id"    => "parallax_speed",
					"group" => __( 'Background', 'fusion-core' ),
					"type"  => ElementTypeEnum::SELECT,
					"allowedValues" => $parallax_speed,
					"value" => "0.3"
				),
				array(
					"name"          => __( 'Background Repeat', 'fusion-core' ),
					"desc"          => __( 'Choose how the background image repeats.', 'fusion-core' ),
					"id"            => "background_repeat",
					"type"          => ElementTypeEnum::SELECT,
					"group"         => __( 'Background', 'fusion-core' ),
					"value"         => "no-repeat",
					"allowedValues" => array(
						'no-repeat' => __( 'No Repeat', 'fusion-core' ),
						'repeat'    => __( 'Repeat Vertically and Horizontally', 'fusion-core' ),
						'repeat-x'  => __( 'Repeat Horizontally', 'fusion-core' ),
						'repeat-y'  => __( 'Repeat Vertically', 'fusion-core' )
					)
				),
				array(
					"name"          => __( 'Background Position', 'fusion-core' ),
					"desc"          => __( 'Choose the postion of the background image', 'fusion-core' ),
					"id"            => "background_position",
					"type"          => ElementTypeEnum::SELECT,
					"group"         => __( 'Background', 'fusion-core' ),
					"value"         => "left top",
					"allowedValues" => array(
						'left top'      => __( 'Left Top', 'fusion-core' ),
						'left center'   => __( 'Left Center', 'fusion-core' ),
						'left bottom'   => __( 'Left Bottom', 'fusion-core' ),
						'right top'     => __( 'Right Top', 'fusion-core' ),
						'right center'  => __( 'Right Center', 'fusion-core' ),
						'right bottom'  => __( 'Right Bottom', 'fusion-core' ),
						'center top'    => __( 'Center Top', 'fusion-core' ),
						'center center' => __( 'Center Center', 'fusion-core' ),
						'center bottom' => __( 'Center Bottom', 'fusion-core' )
					)
				),
				array(
					"name"  => __( 'YouTube/Vimeo Video URL or ID', 'fusion-core' ),
					"desc"  => __( "Enter the URL to the video or the video ID of your YouTube or Vimeo video you want to use as your background. If your URL isn't showing a video, try inputting the video ID instead. <small>Ads will show up in the video if it has them.</small>", 'fusion-core' ),
					"note"  => __( "Tip: newly uploaded videos may not display right away and might show an error message.", "" ) . '<br />' . __( "Videos will not show up in mobile devices because they handle videos differently. In those cases, please provide a preview background image and that will be shown instead.", 'fusion-core' ),
					"id"    => "video_url",
					"group" => __( 'Background', 'fusion-core' ),
					"type"  => ElementTypeEnum::INPUT,
					"value" => ""
				),
				array(
					"name"  => __( 'Video Aspect Ratio', 'fusion-core' ),
					"desc"  => __( "The video will be resized to maintain this aspect ratio, this is to prevent the video from showing any black bars. Enter an aspect ratio here such as: &quot;16:9&quot;, &quot;4:3&quot; or &quot;16:10&quot;. The default is &quot;16:9&quot;", 'fusion-core' ),
					"id"    => "video_aspect_ratio",
					"group" => __( 'Background', 'fusion-core' ),
					"type"  => ElementTypeEnum::INPUT,
					"value" => "16:9"
				),
				array(
					"name"  => __( 'Video WebM Upload', 'fusion-core' ),
					"desc"  => __( 'Video must be in a 16:9 aspect ratio. Add your WebM video file. WebM and MP4 format must be included to render your video with cross browser compatibility. OGV is optional.', 'fusion-core' ),
					"id"    => "video_webm",
					"group" => __( 'Background', 'fusion-core' ),
					"type"  => ElementTypeEnum::INPUT,
					"value" => ""
				),
				array(
					"name"  => __( 'Video MP4 Upload', 'fusion-core' ),
					"desc"  => __( 'Video must be in a 16:9 aspect ratio. Add your WebM video file. WebM and MP4 format must be included to render your video with cross browser compatibility. OGV is optional.', 'fusion-core' ),
					"id"    => "video_mp4",
					"group" => __( 'Background', 'fusion-core' ),
					"type"  => ElementTypeEnum::INPUT,
					"value" => ""
				),
				array(
					"name"  => __( 'Video OGV Upload', 'fusion-core' ),
					"desc"  => __( 'Add your OGV video file. This is optional.', 'fusion-core' ),
					"id"    => "video_ogv",
					"group" => __( 'Background', 'fusion-core' ),
					"type"  => ElementTypeEnum::INPUT,
					"value" => ""
				),
				array(
					"name"  => __( 'Video Preview Image', 'fusion-core' ),
					"desc"  => __( 'IMPORTANT: Video backgrounds will not auto play on mobile and tablet devices or older browsers. Instead, you should insert a preview image in this field and it will be seen in place of your video.', 'fusion-core' ),
					"id"    => "video_preview_image",
					"group" => __( 'Background', 'fusion-core' ),
					"type"  => ElementTypeEnum::UPLOAD,
					"upid"  => "2",
					"value" => ""
				),
				array(
					"name"  => __( 'Video Overlay Color', 'fusion-core' ),
					"desc"  => __( 'Select a color to show over the video as an overlay. Hex color code, <strong>ex: #fff</strong>', 'fusion-core' ),
					"id"    => "overlay_color",
					"group" => __( 'Background', 'fusion-core' ),
					"type"  => ElementTypeEnum::COLOR,
					"value" => ""
				),
				array(
					"name"  => __( 'Video Overlay Opacity', 'fusion-core' ),
					"desc"  => __( 'Opacity ranges between 0 (transparent) and 1 (opaque). ex: .4', 'fusion-core' ),
					"id"    => "overlay_opacity",
					"group" => __( 'Background', 'fusion-core' ),
					"type"  => ElementTypeEnum::INPUT,
					"value" => "0.5"
				),
				array(
					"name"          => __( 'Mute Video', 'fusion-core' ),
					"desc"          => '',
					"id"            => "video_mute",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "yes",
					"group"         => __( 'Background', 'fusion-core' ),
					"allowedValues" => array(
						'yes' => __( 'Yes', 'fusion-core' ),
						'no'  => __( 'No', 'fusion-core' )
					)
				),
				array(
					"name"          => __( 'Loop Video', 'fusion-core' ),
					"desc"          => '',
					"id"            => "video_loop",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "yes",
					"group"         => __( 'Background', 'fusion-core' ),
					"allowedValues" => array(
						'yes' => __( 'Yes', 'fusion-core' ),
						'no'  => __( 'No', 'fusion-core' )
					)
				),
				//array(
				//	"name"  => __( 'YouTube Loop Triggering Refinement', 'fusion-core' ),
				//	"desc"  => __( "Use this if you see a noticeable dark video frame before the video loops.", 'fusion-core' ),
				//	"note"  => __( "Because YouTube performs it's video looping with a huge noticeable delay, we try our best to guess when the video exactly ends and trigger a loop when we <em>just</em> reach the end. If there's a dark frame, put in a time here in milliseconds that we can use to push back the looping trigger. Try values from 5-100 milliseconds.", 'fusion-core' ),
				//	"id"    => "video_loop_refinement",
				//	"group" => __( 'Background', 'fusion-core' ),
				//	"type"  => ElementTypeEnum::INPUT,
				//	"value" => "5"
				//),
				array(
					"name"          => __( 'Fading Animation', 'fusion-core' ),
					"desc"          => __( 'Choose to have the background image fade and blur on scroll. WARNING: Only works for images. ', 'fusion-core' ),
					"id"            => "fade",
					"group"         => __( 'Background', 'fusion-core' ),
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "no",
					"allowedValues" => array(
						'no'  => __( 'No', 'fusion-core' ),
						'yes' => __( 'Yes', 'fusion-core' )
					)
				),
				array(
					"name"  => __( 'Border Size', 'fusion-core' ),
					"desc"  => __( 'In pixels (px), ex: 1px. Leave blank for theme option selection.', 'fusion-core' ),
					"id"    => "border_size",
					"group" => __( 'Design', 'fusion-core' ),
					"type"  => ElementTypeEnum::INPUT,
					"value" => "0px"
				),
				array(
					"name"  => __( 'Border Color', 'fusion-core' ),
					"desc"  => __( 'Controls the border color. Leave blank for theme option selection.', 'fusion-core' ),
					"id"    => "border_color",
					"group" => __( 'Design', 'fusion-core' ),
					"type"  => ElementTypeEnum::COLOR,
					"value" => ""
				),
				array(
					"name"          => __( 'Border Style', 'fusion-core' ),
					"desc"          => __( 'Controls the border style.', 'fusion-core' ),
					"id"            => "border_style",
					"group"         => __( 'Design', 'fusion-core' ),
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "",
					"allowedValues" => array(
						'solid'  => __( 'Solid', 'fusion-core' ),
						'dashed' => __( 'Dashed', 'fusion-core' ),
						'dotted' => __( 'Dotted', 'fusion-core' )
					)
				),
				array(
					"name"  => __( 'Padding Top', 'fusion-core' ),
					"desc"  => __( 'In pixels or percentage, ex: 10px or 10%.', 'fusion-core' ),
					"id"    => "padding_top",
					"group" => __( 'Design', 'fusion-core' ),
					"type"  => ElementTypeEnum::INPUT,
					"value" => "20",
				),
				array(
					"name"  => __( 'Padding Bottom', 'fusion-core' ),
					"desc"  => __( 'In pixels or percentage, ex: 10px or 10%.', 'fusion-core' ),
					"id"    => "padding_bottom",
					"group" => __( 'Design', 'fusion-core' ),
					"type"  => ElementTypeEnum::INPUT,
					"value" => "20",
				),
				array(
					"name"  => __( 'Padding Left', 'fusion-core' ),
					"desc"  => __( 'In pixels or percentage, ex: 10px or 10%.', 'fusion-core' ),
					"id"    => "padding_left",
					"group" => __( 'Design', 'fusion-core' ),
					"type"  => ElementTypeEnum::INPUT,
					"value" => "",
				),
				array(
					"name"  => __( 'Padding Right', 'fusion-core' ),
					"desc"  => __( 'In pixels or percentage, ex: 10px or 10%.', 'fusion-core' ),
					"id"    => "padding_right",
					"group" => __( 'Design', 'fusion-core' ),
					"type"  => ElementTypeEnum::INPUT,
					"value" => "",
				),
				array(
					"name"          => __( '100% Interior Content Width', 'fusion-core' ),
					"desc"          => __( 'Select if the interior content is contained to site width or 100% width.', 'fusion-core' ),
					"id"            => "hundred_percent",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "no",
					"allowedValues" => array(
						'no'  => __( 'No', 'fusion-core' ),
						'yes' => __( 'Yes', 'fusion-core' ),
					)
				),
				array(
					"name"          => __( 'Set Columns to Equal Height', 'fusion-core' ),
					"desc"          => __( 'Select to set all column shortcodes that are used inside the container to have equal height.', 'fusion-core' ),
					"id"            => "equal_height_columns",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "no",
					"allowedValues" => array(
						'no'  => __( 'No', 'fusion-core' ),
						'yes' => __( 'Yes', 'fusion-core' ),
					)
				),
				array(
					"name"          => __( 'Hide on Mobile', 'fusion-core' ),
					"desc"          => __( 'Select yes to hide full width container on mobile.', 'fusion-core' ),
					"id"            => "hide_on_mobile",
					"type"          => ElementTypeEnum::SELECT,
					"value"         => "no",
					"allowedValues" => array(
						'no'  => __( 'No', 'fusion-core' ),
						'yes' => __( 'Yes', 'fusion-core' ),
					)
				),
				array(
					"name"  => __( 'Name Of Menu Anchor', 'fusion-core' ),
					"desc"  => __( 'This name will be the id you will have to use in your one page menu.', 'fusion-core' ),
					"id"    => "menu_anchor",
					"type"  => ElementTypeEnum::INPUT,
					"value" => ""
				),
				array(
					"name"  => __( 'CSS Class', 'fusion-core' ),
					"desc"  => __( 'Add a class to the wrapping HTML element.', 'fusion-core' ),
					"id"    => "class",
					"type"  => ElementTypeEnum::INPUT,
					"value" => ""
				),
				array(
					"name"  => __( 'CSS ID', 'fusion-core' ),
					"desc"  => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' ),
					"id"    => "id",
					"type"  => ElementTypeEnum::INPUT,
					"value" => ""
				),
			);
		}
	}