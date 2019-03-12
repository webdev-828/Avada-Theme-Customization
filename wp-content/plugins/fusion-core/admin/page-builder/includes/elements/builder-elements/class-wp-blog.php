<?php
/**
 * Blog element implementation, it extends DDElementTemplate like all other elements
 */
	class TF_WpBlog extends DDElementTemplate {
		public function __construct() {
			
			parent::__construct();
		} 
		
		// Implementation for the element structure.
		public function create_element_structure() {
			
			// Add name of the class to deserialize it again when the element is sent back to the server from the web page
			$this->config['php_class'] 		= get_class($this);
			// element id
			$this->config['id']	   		= 'wp_blog';
			// element name
			$this->config['name']	 		= __('Blog', 'fusion-core');
			// element icon
			$this->config['icon_url']  		= "icons/sc-text_block.png";
			// css class related to this element
			$this->config['css_class'] 		= "fusion_element_box";
			// element icon class
			$this->config['icon_class']		= 'fusion-icon builder-options-icon fusiona-blog';
			// tooltip that will be displyed upon mouse over the element
			//$this->config['tool_tip']  		= 'Creates a Blog';
			// any special html data attribute (i.e. data-width) needs to be passed
			// drop_level: elements with higher drop level can be dropped in elements with lower drop_level, 
			// i.e. element with drop_level = 2 can be dropped in element with drop_level = 0 or 1 only.
			$this->config['data'] 			= array("drop_level"   => "4");
		}

		// override default implemenation for this function as this element have special view
		public function create_visual_editor( $params ) {
			
			$innerHtml  = '<div class="fusion_iconbox textblock_element textblock_element_style" id="fusion_blog">';
			$innerHtml .= '<div class="bilder_icon_container"><span class="fusion_iconbox_icon"><i class="fusiona-blog"></i><sub class="sub">'.__('Blog', 'fusion-core').'</sub><p>layout = <span class="blog_layout">icon-on-side</span><font class="blog_columns">columns = 5</font></p></span></div>';
			$innerHtml .= '</div>';

			$this->config['innerHtml'] = $innerHtml;
		}
		
		
		//this function defines TextBlock sub elements or structure
		function popup_elements() {
			
			$posts_per_page 			= array('fusion_-1' => 'All' , 'fusion_' => 'Default');
			$blog_posts_per_page 		= FusionHelper::fusion_create_dropdown_data( 1, 25, $posts_per_page );
			$wp_categories_list  		= FusionHelper::get_wp_categories_list();
			$choices					= FusionHelper::get_shortcode_choices();
			
			$this->config['subElements'] = array(
			
				array("name" 			=> __('Blog Layout', 'fusion-core'),
					  "desc" 			=> __('Select the layout for the blog shortcode', 'fusion-core'),
					  "id" 				=> "fusion_layout",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "large",
					  "allowedValues" 	=> array('large' 			=> __('Large', 'fusion-core'),
												 'medium' 			=> __('Medium', 'fusion-core'),
												 'large alternate' 	=> __('Large Alternate', 'fusion-core'),
												 'medium alternate' => __('Medium Alternate', 'fusion-core'),
												 'grid'				=> __('Grid', 'fusion-core'),
												 'timeline'			=> __('Timeline', 'fusion-core'))
					  ),
											   
				array("name" 			=> __('Posts Per Page', 'fusion-core'),
					  "desc" 			=> __('Select number of posts per page.', 'fusion-core'),
					  "id" 				=> "fusion_posts_per_page",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "",
					  "allowedValues" 	=> $blog_posts_per_page
					  ),
					  
				array("name" 			=> __('Post Offset', 'fusion-core'),
					  "desc" 			=> __('The number of posts to skip. ex: 1.', 'fusion-core'),
					  "id" 				=> "fusion_offset",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> '',
					  ),					  					  
					  
				array("name" 			=> __('Categories', 'fusion-core'),
					  "desc" 			=> __('Select a category or leave blank for all.', 'fusion-core'),
					  "id" 				=> "fusion_cat_slug",
					  "type" 			=> ElementTypeEnum::MULTI,
					  "value" 			=> array(''),
					  "allowedValues" 	=> $wp_categories_list 
					 ),
					 
				array("name" 			=> __('Exclude Categories', 'fusion-core'),
					  "desc" 			=> __('Select a category to exclude.', 'fusion-core'),
					  "id" 				=> "fusion_exclude_cats",
					  "type" 			=> ElementTypeEnum::MULTI,
					  "value" 			=> array(''),
					  "allowedValues" 	=> $wp_categories_list 
					 ),
				
				array("name" 			=> __('Show Title', 'fusion-core'),
					  "desc" 			=> __('Display the post title below the featured image.', 'fusion-core'),
					  "id" 				=> "fusion_title",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> $choices 
					 ),
					 
				array("name" 			=> __('Link Title To Post', 'fusion-core'),
					  "desc" 			=> __('Choose if the title should be a link to the single post page.', 'fusion-core'),
					  "id" 				=> "fusion_title_link",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> $choices 
					 ),
				
				array("name" 			=> __('Show Thumbnail', 'fusion-core'),
					  "desc" 			=> __('Display the post featured image.', 'fusion-core'),
					  "id" 				=> "fusion_thumbnail",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> $choices 
					 ),
					 
			   array("name" 			=> __('Show Excerpt', 'fusion-core'),
					  "desc" 			=> __('Show excerpt or choose "no" for full content.', 'fusion-core'),
					  "id" 				=> "fusion_excerpt",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> $choices 
					 ),
			  
			  array("name" 				=> __('Number of Words/Characters in Excerpt', 'fusion-core'),
					  "desc" 			=> __('Control the excerpt length based on words/character setting in Theme Options > Extra.', 'fusion-core'),
					  "id" 				=> "fusion_excerpt_words",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> 35,
					 ),
					 
			 array("name" 				=> __('Show Meta Info', 'fusion-core'),
					  "desc" 			=> __('Choose to show all meta data.', 'fusion-core'),
					  "id" 				=> "fusion_meta_all",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> $choices 
					 ),
					 
			array("name" 				=> __('Show Author Name', 'fusion-core'),
					  "desc" 			=> __('Choose to show the author.', 'fusion-core'),
					  "id" 				=> "fusion_meta_author",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> $choices 
					 ),
			
			array("name" 				=> __('Show Categories', 'fusion-core'),
					  "desc" 			=> __("Choose to show the categories.", 'fusion-core'),
					  "id" 				=> "fusion_meta_categories",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> $choices 
					 ),
					 
			array("name" 				=> __('Show Comment Count', 'fusion-core'),
					  "desc" 			=> __('Choose to show the comments.', 'fusion-core'),
					  "id" 				=> "fusion_meta_comments",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> $choices 
					 ),
			
			array("name" 				=> __('Show Date', 'fusion-core'),
					  "desc" 			=> __('Choose to show the date.', 'fusion-core'),
					  "id" 				=> "fusion_meta_date",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> $choices 
					 ),
			
			array("name" 				=> __('Show Read More Link', 'fusion-core'),
					  "desc" 			=> __('Choose to show the Read More link.', 'fusion-core'),
					  "id" 				=> "fusion_meta_link",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> $choices 
					 ),
					 
			array("name" 				=> __('Show Tags', 'fusion-core'),
					  "desc" 			=> __("Choose to show the tags.", 'fusion-core'),
					  "id" 				=> "fusion_meta_tags",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> $choices 
					 ),
					 
			array("name" 				=> __('Show Pagination', 'fusion-core'),
					  "desc" 			=> __('Show numerical pagination boxes.', 'fusion-core'),
					  "id" 				=> "fusion_paging",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> $choices 
					 ),
					 
			array("name" 				=> __('Pagination Type', 'fusion-core'),
					  "desc" 			=> __('Choose the type of pagination.', 'fusion-core'),
					  "id" 				=> "fusion_scrolling",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "pagination",
					  "allowedValues" 	=> array('pagination' => __('Pagination', 'fusion-core'),
												 'infinite'   => __('Infinite Scrolling', 'fusion-core'),
												 'load_more_button' => __('Load More Button', 'fusion-core')) 
					 ),
					 
			array("name" 				=> __('Grid Layout # of Columns', 'fusion-core'),
					  "desc" 			=> __('Select whether to display the grid layout in 2, 3, 4, 5 or 6 column.', 'fusion-core'),
					  "id" 				=> "fusion_blog_grid_columns",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "3",
					  "allowedValues" 	=> array('2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6') 
					 ),
					 
			array("name" 				=> __('Grid Layout Column Spacing', 'fusion-core'),
					  "desc" 			=> __('Insert the amount of spacing between blog grid posts without "px".', 'fusion-core'),
					  "id" 				=> "fusion_blog_grid_column_spacing",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "40"
				  ),	
					 				 	 
			array("name" 				=> __('Strip HTML from Posts Content', 'fusion-core'),
					  "desc" 			=> __('Strip HTML from the post excerpt.', 'fusion-core'),
					  "id" 				=> "fusion_strip_html",
					  "type" 			=> ElementTypeEnum::SELECT,
					  "value" 			=> "yes",
					  "allowedValues" 	=> $choices 
					 ),
					 
			array("name" 				=> __('CSS Class', 'fusion-core'),
					  "desc"			=> __('Add a class to the wrapping HTML element.', 'fusion-core'),
					  "id" 				=> "fusion_class",
					  "type" 			=> ElementTypeEnum::INPUT,
					  "value" 			=> "" 
					  ),
					  
			array("name" 				=> __('CSS ID', 'fusion-core'),
				  	"desc"				=> __('Add an ID to the wrapping HTML element.', 'fusion-core'),
				  	"id" 				=> "fusion_id",
				  	"type" 				=> ElementTypeEnum::INPUT,
				  	"value" 			=> "" 
				  ),
				
				);
		}
	}