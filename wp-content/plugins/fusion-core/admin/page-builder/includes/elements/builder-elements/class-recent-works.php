<?php
/**
 * RecentWorks implementation, it extends DDElementTemplate like all other elements
 */
	class TF_RecentWorks extends DDElementTemplate {
		public function __construct() {
			
			parent::__construct();
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'recent_works';
			// element name
			$this->config['name']	 		= __('Recent Works', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-insertpicture';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a Recent Works Blck';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_recent_works">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-insertpicture"></i><sub class="sub">'.__('Recent Works', 'fusion-core').'</sub><p>layout = <span class="recent_works_layout">icon-on-side</span><span class="columns_container" style="selector:attrib"> <br />columns = <font class="recent_works_columns">5</font></span><span class="rw_cats_container"><br>categories = <font class="recent_works_cats">All</font></span></p></span></div>';
			$innerHtml .= '</div>';
			$this->config['innerHtml'] = $innerHtml;

		}
		
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			
			$no_of_columns 				= FusionHelper::fusion_create_dropdown_data( 1 , 6 );
			$wp_categories_list  		= FusionHelper::fusion_shortcodes_categories('portfolio_category');
			$animation_speed 			= FusionHelper::get_animation_speed_data();
			$animation_direction 		= FusionHelper::get_animation_direction_data();
			$animation_type 			= FusionHelper::get_animation_type_data();
			$choices					= FusionHelper::get_shortcode_choices();
			
			$this->config['subElements'] = array(
			
			   array( "name" 			=> __('Layout', 'fusion-core'),
					  "desc" 			=> __('Choose the layout for the shortcode', 'fusion-core'),
					  "id" 				=> "fusion_layout",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "carousel",
					  "allowedValues" 	=> array('carousel' 			=> __('Carousel', 'fusion-core'),
												 'grid' 				=> __('Grid', 'fusion-core'),
												 'grid-with-excerpts' 	=> __('Grid with Excerpts', 'fusion-core'))
					  ),
					  
				array( "name" 			=> __('Picture Size', 'fusion-core'),
					  "desc" 			=> __('fixed = width and height will be fixed<br>auto = width and height will adjust to the image.', 'fusion-core'),
					  "id" 				=> "fusion_picture_size",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "fixed",
					  "allowedValues" 	=> array('fixed' 			=> __('Fixed', 'fusion-core'),
												 'auto' 			=> __('Auto', 'fusion-core'))
					  ),
					  
				array( "name" 			=> __('Grid with Excerpts Layout', 'fusion-core'),
					  "desc" 			=> __('Select if the grid with excerpts layouts are boxed or unboxed.', 'fusion-core'),
					  "id" 				=> "fusion_boxed_text",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "unboxed",
					  "allowedValues" 	=> array('boxed' 			=> __('Boxed', 'fusion-core'),
												 'unboxed' 			=> __('Unboxed', 'fusion-core'))
					  ),
					  
				array("name" 			=> __('Show Filters', 'fusion-core'),
					  "desc" 			=> __('Choose to show or hide the category filters', 'fusion-core'),
					  "id" 				=> "fusion_filters",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> 	array('yes' => __('Yes', 'fusion-core'), 
												  'yes-without-all' => __('Yes without "All"', 'fusion-core'),
												  'no' => __('No', 'fusion-core'))
					 ),
					 
				array("name" 			=> __('Columns', 'fusion-core'),
					  "desc" 			=> __('Select the number of columns to display. With Carousel layout this specifies the maximum amount of columns.', 'fusion-core'),
					  "id" 				=> "fusion_columns",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "3",
					  "allowedValues" 	=> $no_of_columns
					  ),
					  
				array("name" 			=> __('Column Spacing', 'fusion-core'),
					  "desc" 			=> __('Insert the amount of spacing between portfolio items without "px". ex: 7.', 'fusion-core'),
					  "id" 				=> "fusion_column_spacing",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "12",
					 ),					  
					  
				array("name" 			=> __('Categories', 'fusion-core'),
					  "desc" 			=> __('Select a category or leave blank for all', 'fusion-core'),
					  "id" 				=> "fusion_cat_slug",
					  "type" 			=> ElementTypeEnum::MULTI,
					  "value" 			=> array(''),
					  "allowedValues" 	=> $wp_categories_list 
					 ),
					 
				array("name" 			=> __('Exclude Categories', 'fusion-core'),
					  "desc" 			=> __('Select a category to exclude', 'fusion-core'),
					  "id" 				=> "fusion_exclude_cats",
					  "type" 			=> ElementTypeEnum::MULTI,
					  "value" 			=> array(''),
					  "allowedValues" 	=> $wp_categories_list 
					 ),
					 
				array("name" 			=> __('Number of Posts', 'fusion-core'),
					  "desc" 			=> __('Select the number of posts to display', 'fusion-core'),
					  "id" 				=> "fusion_number_posts",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "8"
					  ),
					  
				array("name" 			=> __('Post Offset', 'fusion-core'),
					  "desc" 			=> __('The number of posts to skip. ex: 1.', 'fusion-core'),
					  "id" 				=> "fusion_offset",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> ""
					  ),					  
		  
				array("name" 			=> __('Excerpt Length', 'fusion-core'),
					  "desc" 			=> __('Insert the number of words/characters you want to show in the excerpt', 'fusion-core'),
					  "id" 				=> "fusion_excerpt_words",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> 35,
					 ),
					 
                array("name"                 => __('Strip HTML from Posts Content', 'fusion-core'),
                          "desc"             => __('Strip HTML from the post excerpt.', 'fusion-core'),
                          "id"                 => "fusion_strip_html",
                          "type"             => ElementTypeEnum::SELECT,
                          "value"             => "yes",
                          "allowedValues"     => $choices 
                         ),                     					 
					 
				array("name" 			=> __('Carousel Layout', 'fusion-core'),
					  "desc" 			=> __('Choose to show titles on rollover image, or below image.', 'fusion-core'),
					  "id" 				=> "fusion_carousel_layout",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "title_on_rollover",
					  "allowedValues" 	=> array('title_on_rollover' 				=> __('Title on rollover', 'fusion-core'),
												 'title_below_image' 				=> __('Title below image', 'fusion-core')) 
					  ),
					  
				array("name" 			=> __('Carousel Scroll Items', 'fusion-core'),
					  "desc" 			=> __("Insert the amount of items to scroll. Leave empty to scroll number of visible items.", 'fusion-core'),
					  "id" 				=> "fusion_scroll_items",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "",
					 ),					  
					 
				array("name" 			=> __('Carousel Autoplay', 'fusion-core'),
					  "desc" 			=> __('Choose to autoplay the carousel.', 'fusion-core'),
					  "id" 				=> "fusion_autoplay",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "no",
					  "allowedValues" 	=> array('yes' 				=> __('Yes', 'fusion-core'),
												 'no' 				=> __('No', 'fusion-core')) 
					  ),			  				  	
			  	
				array("name" 			=> __('Carousel Show Navigation', 'fusion-core'),
					  "desc" 			=> __('Choose to show navigation buttons on the carousel.', 'fusion-core'),
					  "id" 				=> "fusion_navigation",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> array('yes' 				=> __('Yes', 'fusion-core'),
												 'no' 				=> __('No', 'fusion-core')) 
					  ),	
					  
				array("name" 			=> __('Carousel Mouse Scroll', 'fusion-core'),
					  "desc" 			=> __('Choose to enable mouse drag control on the carousel.', 'fusion-core'),
					  "id" 				=> "fusion_mouse_scroll",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "no",
					  "allowedValues" 	=> array('yes' 				=> __('Yes', 'fusion-core'),
												 'no' 				=> __('No', 'fusion-core')) 
					  ),
					  			  	 
				array("name" 			=> __('Animation Type', 'fusion-core'),
					  "desc" 			=> __('Select the type on animation to use on the shortcode', 'fusion-core'),
					  "id" 				=> "fusion_animation_type",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> $animation_type 
					 ),
				
				array("name" 			=> __('Direction of Animation', 'fusion-core'),
					  "desc" 			=> __('Select the incoming direction for the animation', 'fusion-core'),
					  "id" 				=> "fusion_animation_direction",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> $animation_direction 
					 ),
				
				array("name" 			=> __('Speed of Animation', 'fusion-core'),
					  "desc"			=> __('Type in speed of animation in seconds (0.1 - 1)', 'fusion-core'),
					  "id" 				=> "fusion_animation_speed",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "0.1",
					  "allowedValues"	=> $animation_speed
					  ),
					  
				array("name" 			=> __( 'Offset of Animation', 'fusion-core' ),
					  "desc"			=> __( 'Choose when the animation should start.', 'fusion-core' ),
					  "id" 				=> "fusion_animation_offset",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> array(
					  						''					=> __( 'Default', 'fusion-core' ),					  
											'top-into-view' 	=> __( 'Top of element hits bottom of viewport', 'fusion-core' ),
											'top-mid-of-view' 	=> __( 'Top of element hits middle of viewport', 'fusion-core' ),
											'bottom-in-view' 	=> __( 'Bottom of element enters viewport', 'fusion-core' ),
											)
					  ),					  
					  
				array("name" 			=> __('CSS Class', 'fusion-core'),
					  "desc"			=> __('Add a class to the wrapping HTML element.', 'fusion-core'),
					  "id" 				=> "fusion_class",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					  
				array("name" 			=> __('CSS ID', 'fusion-core'),
					  "desc"			=> __('Add an ID to the wrapping HTML element.', 'fusion-core'),
					  "id" 				=> "fusion_id",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					 
				);
		}
	}