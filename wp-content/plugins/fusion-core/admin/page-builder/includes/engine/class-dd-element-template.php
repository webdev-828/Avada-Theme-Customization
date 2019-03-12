<?php
include 'class-element-type-enum.php';
include 'class-helper.php';

/**
 * The main class for DD Elements, each element has to extend this class.
 */
abstract class DDElementTemplate {
	// config array that contains all element configurations
	var $config = array();	
	
	// class constructor
	function __construct( $am_elements = array() ) {
		
		$this->create_element_structure();
		$this->extra_config( $am_elements );
		$this->precreate_visual_editor();
	}
	
	// reload the class again using given config array
	public function reload( $config ) {
		if(count($config)>0) {
			$this->config = $config;
			$this->precreate_visual_editor();
			if(isset($this->config['popup_editor']) && $this->config['popup_editor']) {
				$this->parse_editor_elements();
			}
		}
	}

	// the main method that will create the element structure, it has to be implemented in the element class.
	abstract function create_element_structure();
   
	/**
	 * Return configuratino array
	 */
	public function element_to_array() {
		
		return $this->config;
	}
	
	/**
	* additional config vars that are set automatically
	*/
	protected function extra_config( $am_elements = array() ) {   
		$this->config['childrenId'] = array();
		//if the element class contain method "popup_elements" it will be called.
		// if it's not then the element is not editable, i.e. no popup will be displayed when element is clicked.
		if( method_exists($this, 'popup_elements') ) {
			$this->popup_elements( $am_elements );
			if( !empty($this->config['subElements']) ) {
				$this->config['popup_editor'] = true;
				//$this->parse_editor_elements();
			}
		}
	}
	
	/**
	 * Prepare the editable popup content by reading all elements subElemenet and add it to attribute "editPanel_innerHtml".
	 */
	private function parse_editor_elements() {
		
		$output 		= "";
		$output 		.= "<form action='#' id='element-edit-form'>";
		foreach ( $this->config['subElements'] as $key => $element ) {
			
			if( $element['type'] == ElementTypeEnum::ADDMORE ) {
				
				$elementsCount = count ( $element['elements'][0]['value'] );
				$elementsCount = ($elementsCount > 0 ? $elementsCount : 1);
				$output		.= '<div id="child-element-data" style="display:none"></div>';
				$output 	.= "<table class='clearfix has-children'>";
				for ($i = 0; $i < $elementsCount; $i++) {
					$output 	.= "<tr class='child-clone-row'><td>";
					foreach ($element['elements'] as $dynamic_element ) {
						
						$output 	.= "<div class='clearfix form-element-container 
										funsion-element-child'><div class='name-description'>";
						if( !empty($dynamic_element['name'])) { $output .= "<strong>".$dynamic_element['name']."</strong>"; }
						if( !empty($dynamic_element['desc'])) { $output .= "<span>".$dynamic_element['desc']."</span>"; }
						if ( isset ($dynamic_element['value'][$i]) ) { $dynamic_element['value'] = $dynamic_element['value'][$i]; }
						else { $dynamic_element['value'] = ''; }
						if (isset ($dynamic_element['upid'])) { $dynamic_element['upid'] = $i; }
						$output 	.= "</div>";
						$output 	.="<div class='element-type'>";
						$output 	.= $this->parseElementType($dynamic_element);
						$output 	.= "</div>";
						$output 	.= "</div>";
					}
					$output 	.="<a class='child-clone-row-remove fusion-shortcodes-button' 
									href='JavaScript:void(0)'>".__('Remove', 'fusion-core')."</a>";
					$output 	.= "</td></tr>";
				}
				$output 		.= "<tr><td><a id='fusion-child-add' href='JavaScript:void(0)'>".$element['buttonText']."</a>
									</td></tr>";
				$output 		.= "</table>";
				
			} else {
				$output 	.= "<div class='clearfix form-element-container'>";
				
				$output 	.= "<div class='name-description'>";
				if( !empty($element['name'])) { $output .= "<strong>".$element['name']."</strong>"; }
				if( !empty($element['desc'])) { $output .= "<span>".$element['desc']."</span>"; }
				$output 	.= "</div>";
				
				$output 	.="<div class='element-type'>";
				$output 	.= $this->parseElementType($element);
				$output 	.= "</div>";
				
				$output 	.= "</div>";
			}
		}
		$output 	.= "</form>";
		$this->config['editPanel_innerHtml'] = $output;
	}
	
	/**
	 * Check the subelement type and render it with the correct implementation
	 * @param $element element
	 * @return string correct implementation for the element according to its type
	 */
	private function parseElementType( $element ) {
		$output = "";
		
		switch ($element['type']) {
		  
			case ElementTypeEnum::COLOR:
				$output .= '<input type="text" class="text-field fusion-color-field" value="'.nl2br($element['value']).'" id="'.$element['id'].'" name="'.$element['id'].'" size="50"/>';
			break;
			
			case ElementTypeEnum::GALLERY:
				$output .= '<a href="'.$element['id'].'" class="fusion-gallery-button fusion-shortcodes-button">'.__('Attach Images to Gallery', 'fusion-core').'</a>';
			break;
			
			case ElementTypeEnum::UPLOAD:
				$button_class = ( $element['value'] == 'fusion-hidden-img' ? '' 		: 'remove-image' ); 
				$button_text  = ( $element['value'] == 'fusion-hidden-img' ? __('Upload', 'fusion-core') 	: __('Remove', 'fusion-core') ); 
				$output .= '<div class="fusion-upload-container">';
				$output .= '<img src="' . $element['value'] . '" alt="Image" class="uploaded-image" />';
				$output .= '<input type="hidden" class="fusion-form-text fusion-form-upload fusion-input" 
							name="' . $element['id'] . '" id="' . $element['id'] . '" value="' . $element['value'] . '" />' . "\n";
				$output .= '<a href="' . $element['id'] . '" class="fusion-upload-button '.$button_class.'" 
							data-upid="' . $element['upid'] . '">
							'.$button_text.'</a>';
				$output .= '</div>';
			break;
			
			  case ElementTypeEnum::INPUT:
				$output .= '<input type="text" class="text-field" value="'.nl2br($element['value']).'" id="'.$element['id'].'" name="'.$element['id'].'" size="50"/>';
			break;
			
			case ElementTypeEnum::HIDDEN:
				$output  .= '<input type="hidden" value="'.$element['value'].'" id="'.$element['id'].'" name="'.$element['id'].'"/>';
			break;
			case ElementTypeEnum::MULTI:
			$output .= '<select id="'.$element['id'].'" name="'.$element['id'].'" class="select-field chosen-select" multiple>';
				foreach ($element['allowedValues'] as $key => $value) {
					
					$selected = "";
					if( !empty($element['value']) && $element['value'] == $key ) {
						
						$selected = "selected";
					}
					$output .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
				}
				$output .= '</select>';
			break;
			case ElementTypeEnum::SELECT:
				$output .= '<select id="'.$element['id'].'" name="'.$element['id'].'" class="select-field">';
				foreach ($element['allowedValues'] as $key => $value) {
					
					$selected = "";
					if( !empty($element['value']) && $element['value'] == $key ) {
						
						$selected = "selected";
					}
					$output .= '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
				}
				$output .= '</select>';
			break;
			case ElementTypeEnum::RADIO:
				$counter = 1;
				foreach( $element['allowedValues'] as $key => $radiobutton ) {	
					$checked = "";
					if( $element['value'] == $key ) { $checked = 'checked = "checked"'; }
					$output  .= '<span class="radio-field">';
					$output  .= '<input '.$checked.' type="radio" value="'.$key.'" 
								id="'.$element['id'].$counter.'" name="'.$element['id'].'"/>';
					$output  .= '<label for="'.$element['id'].$counter.'"><span class="labeltext">'.$radiobutton.'</span>';
					$output  .= '</label>';
					$output  .= '</span>';
					$counter++;
				}
			break;
			case ElementTypeEnum::CHECKBOX:
				$counter = 1;
				foreach( $element['allowedValues'] as $key => $checkbox ) {	
					$checked = "";
					if( $element['value'] == $key ) { $checked = 'checked = "checked"'; }
					$output  .= '<span class="checkbox-field">';
					$output  .= '<input '.$checked.' type="checkbox" value="'.$key.'" id="'.$element['id'].$counter.'" name="'.$element['id'].'"/>';
					$output  .= '<label for="'.$element['id'].$counter.'"><span class="labeltext">'.$checkbox.'</span>';
					$output  .= '</label>';
					$output  .= '</span>';
					$counter++;
				}
			break; 
			case ElementTypeEnum::TEXTAREA:
			
				$output  .= '<textarea rows="5" cols="55" class="textarea-field" id="'.$element['id'].'" name="'.$element['id'].'">'.$element['value'].'</textarea>';
				
			break;
			case ElementTypeEnum::HTML_EDITOR:
				$output  .= '<textarea rows="5" cols="55" class="textarea-field" id="'.$element['id'].'" name="'.$element['id'].'">'.$element['value'].'</textarea>';
				$output .= '<script type="text/javascript">jQuery("#'.$element['id'].'").jqte();</script>';
			break;
			case ElementTypeEnum::ICON_BOX:
				$iconsArray = FusionHelper::GET_ICONS_LIST();
				$output .= "<div class='icon_select_container'>";
				foreach ($iconsArray as $iconKey => $iconValue) {
					$selectedClass = "";
					if($element['value'] == $iconValue) {
						
						$selectedClass = "selected-element";
					}
					
					$output .= '<span class="icon_preview '.$selectedClass.'"><i class="fa '.$iconValue.'" data-name="'.$iconValue.'"></i></span>';
				}
				$output .= '</div>';
				$output  .= '<input type="hidden" value="'.$element['value'].'" id="'.$element['id'].'" name="'.$element['id'].'"/>';
			break;
			default :
				
			break;
		}
		
		return $output;
	}
	
	
	/**
	* Cet current or default sub element values and pass it to function create_visual_editor to create the element view.
	*/
	public function precreate_visual_editor() {
		
		$content 				= $this->get_content();
		//set the default arguments unless they were already passed
		$args 					= $this->get_args();
		$params['content']   	= $content;
		$params['args']	  	= $args;

		//fetch the parameter array from the child classes editor_element function which should descripe the html code
		$this->create_visual_editor( $params );
	}
	
	/**
	* function that will create the element layout in the main editor.
	 * This function can be override by the child class if the element has specific view, for example IconBox.php
	*/
	public function create_visual_editor( $params ) {
		
		$innerHtml = "";
		if(isset($this->config['icon_url'])) {
			
			$innerHtml .= "<img src='".$this->config['icon_url']."' title='".$this->config['name']."' alt='' />";
		}
		$innerHtml.= "<div class='fusion-element-label'>".$this->config['name']."</div>";
		$this->config['innerHtml'] = $innerHtml;
	}
				
	

	/**
	* helper function that gets the default value of the content element
	*
	* @param array $elements
	* @return array $args
	*/
	private function get_content() {
		$content = "";
		 if(!empty($this->config['subElements'])) {
			 $this->get_args();

			 //if there is a content element already thats the value. if not try to fetch the value
			 if(!isset($this->args['content'])) {
				 foreach($this->config['subElements'] as $element) {
					 
					 if(isset($element['value']) && isset($element['id']) && $element['id'] == "content") {
						 
						 $content = $element['value'];
					 }
				 }
			 }
			 else{
				 
				 $content = $this->args['content'];
			 }
		 }
		 return $content;
	}
	   
	   
	/**
	 * helper function executed that extracts the std values from the options array and creates a shortcode argument array
	 *
	 * @param array $elements
	 * @return array $args
	 */
	private function get_args()
	{
		$args = array();
		if(!empty($this->config['subElements'])) {
			
			foreach($this->config['subElements'] as $element) {
				
				if(isset($element['value']) && isset($element['id'])) {
					
					$args[$element['id']] = $element['value'];
				}
			}
			$this->args = $args;
		}
		return $args;
	}
}

