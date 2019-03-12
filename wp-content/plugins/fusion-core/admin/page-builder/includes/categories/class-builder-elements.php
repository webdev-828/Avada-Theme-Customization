<?php
/**
 * BuilderElements implementation
 */
class BuilderElements {
	private $value 		= array();
	private $elements 	= array();
	
	public function __construct() 
	{
		$this->value['id'] 		= "Builder_elements_div";
		$this->value['name'] 	= __('Builder Elements', 'fusion-core');
		$this->value['icon'] 	= "icon_pack/tab_icon_4.png";
		$this->value['class']	= "fusion-tab fusiona-TFicon";
		
		$this->load_elements();
	}
	
	public function to_array() 
	{
		$this->value['elements'] = $this->elements;
		return $this->value;
	}
	
	/**
	 * Load all the category's elements
	 */
	private function load_elements() 
	{
		$alert_box 		= new TF_AlertBox();
		array_push($this->elements, $alert_box->element_to_array());
		
		$wp_blog		= new TF_WpBlog();
		array_push($this->elements, $wp_blog->element_to_array());
		
		$button_block	= new TF_ButtonBlock();
		array_push($this->elements, $button_block->element_to_array());
		
		$checklist		= new TF_CheckList();
		array_push($this->elements, $checklist->element_to_array());
		
		//$clients_sldier	= new TF_ClientSlider();
		//array_push($this->elements, $clients_sldier->element_to_array());
		
		$code_block		= new TF_CodeBlock();
		array_push($this->elements, $code_block->element_to_array());
		
		$content_boxes	= new TF_ContentBoxes();
		array_push($this->elements, $content_boxes->element_to_array());

		$countdown	= new TF_CountDown();
		array_push($this->elements, $countdown->element_to_array());

		$counter_box	= new TF_CounterBox();
		array_push($this->elements, $counter_box->element_to_array());
		
		$counter_circle	= new TF_CounterCircle();
		array_push($this->elements, $counter_circle->element_to_array());
		
		/*$drop_Cap		= new TF_DropCap();
		array_push($this->elements, $drop_Cap->element_to_array());*/
		
		$events 		= new TF_FusionEvents();
		array_push($this->elements, $events->element_to_array());		
		
		$flip_boxes		= new TF_FlipBoxes();
		array_push($this->elements, $flip_boxes->element_to_array());
		
		$font_awesmoe 	= new TF_FontAwesome();
		array_push($this->elements, $font_awesmoe->element_to_array());
		
		$fusionslider	= new TF_FusionSlider();
		array_push($this->elements, $fusionslider->element_to_array());
		
		$google_map 	= new TF_GoogleMap();
		array_push($this->elements, $google_map->element_to_array());
		
		/*$high_light 	= new TF_HighLight();
		array_push($this->elements, $high_light->element_to_array());*/

		$image_carousel = new TF_ImageCarousel();
		array_push($this->elements, $image_carousel->element_to_array());
		
		$image_frame 	= new TF_ImageFrame();
		array_push($this->elements, $image_frame->element_to_array());

		$layer_slider 	= new TF_LayerSlider();
		array_push($this->elements, $layer_slider->element_to_array());
		
		/*$light_box 		= new TF_LightBox();
		array_push($this->elements, $light_box->element_to_array());*/	
		
		$menu_anchor 	= new TF_MenuAnchor();
		array_push($this->elements, $menu_anchor->element_to_array());
		
		$modal 			= new TF_Modal();
		array_push($this->elements, $modal->element_to_array());
		
		/*$modal_link 	= new TF_Modal_Link();
		array_push($this->elements, $modal_link->element_to_array());*/
		
		$person_box 	= new TF_Person();
		array_push($this->elements, $person_box->element_to_array());
		
		$post_slider	= new TF_PostSlider();
		array_push($this->elements, $post_slider->element_to_array());
		
		/*$person_box 	= new TF_Popover();
		array_push($this->elements, $person_box->element_to_array());*/
		
		$pricing_table 	= new TF_PricingTable();
		array_push($this->elements, $pricing_table->element_to_array());
		
		$progress_bar 	= new TF_ProgressBar();
		array_push($this->elements, $progress_bar->element_to_array());
		
		$recent_posts 	= new TF_RecentPosts();
		array_push($this->elements, $recent_posts->element_to_array());
		
		$recent_works 	= new TF_RecentWorks();
		array_push($this->elements, $recent_works->element_to_array());
		
		$revolution	 	= new TF_RevolutionSlider();
		array_push($this->elements, $revolution->element_to_array());
		
		$section_sep	 = new TF_SectionSeparator();
		array_push($this->elements, $section_sep->element_to_array());
		
		$separator 		= new TF_Separator();
		array_push($this->elements, $separator->element_to_array());
		
		$sharing_box 	= new TF_SharingBox();
		array_push($this->elements, $sharing_box->element_to_array());
		
		$slider 		= new TF_Slider();
		array_push($this->elements, $slider->element_to_array());

		$social_links 	= new TF_SocialLinks();
		array_push($this->elements, $social_links->element_to_array());
		
		$sound_cloud 	= new TF_SoundCloud();
		array_push($this->elements, $sound_cloud->element_to_array());

		$table 			= new TF_Table();
		array_push($this->elements, $table->element_to_array());
		
		$tabs 			= new TF_Tabs();
		array_push($this->elements, $tabs->element_to_array());
		
		$tagline_box 	= new TF_TaglineBox();
		array_push($this->elements, $tagline_box->element_to_array());
		
		$testimonial 	= new TF_Testimonial();
		array_push($this->elements, $testimonial->element_to_array());
		
		$text_block 	= new TF_FusionText();
		array_push($this->elements, $text_block->element_to_array());
		
		$title 			= new TF_Title();
		array_push($this->elements, $title->element_to_array());
		
		$toggles 		= new TF_Toggles();
		array_push($this->elements, $toggles->element_to_array());
		
		/*$tooltip 		= new TF_Tooltip();
		array_push($this->elements, $tooltip->element_to_array());*/
		
		$login 			= new TF_Login();
		array_push($this->elements, $login->element_to_array());			
		
		$vimeo 			= new TF_Vimeo();
		array_push($this->elements, $vimeo->element_to_array());

		$widget_area	= new TF_WidgetArea();
		array_push($this->elements, $widget_area->element_to_array());

		$woo_carousel 	= new TF_WooCarousel();
		array_push($this->elements, $woo_carousel->element_to_array());
		
		$woo_featured 	= new TF_WooFeatured();
		array_push($this->elements, $woo_featured->element_to_array());
		
		$woo_shortcodes = new TF_WooShortcodes();
		array_push($this->elements, $woo_shortcodes->element_to_array());
		
		$youtube 		= new TF_Youtube();
		array_push($this->elements, $youtube->element_to_array());
	}  
} 
