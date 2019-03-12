<?php
/*-----------------------------------------------------------------------------------*/
/*	Default Options
/*-----------------------------------------------------------------------------------*/

// Number of posts array
function fusion_shortcodes_range ( $range, $all = true, $default = false, $range_start = 1 ) {
	if( $all ) {
		$number_of_posts['-1'] = 'All';
	}

	if( $default ) {
		$number_of_posts[''] = 'Default';
	}

	foreach( range( $range_start, $range ) as $number ) {
		$number_of_posts[$number] = $number;
	}

	return $number_of_posts;
}

// Taxonomies
function fusion_shortcodes_categories ( $taxonomy, $empty_choice = false, $empty_choice_label = 'Default' ) {
	$post_categories = array();
	if( $empty_choice == true ) {
		$post_categories[''] = $empty_choice_label;
	}

	$get_categories = get_categories('hide_empty=0&taxonomy=' . $taxonomy);

	if( ! is_wp_error( $get_categories ) ) {
		if( $get_categories && is_array($get_categories) ) {
			foreach ( $get_categories as $cat ) {
				if( array_key_exists('slug', $cat) &&
					array_key_exists('name', $cat)
				) {
					$post_categories[$cat->slug] = $cat->name;
				}
			}
		}

		if( isset( $post_categories ) ) {
			return $post_categories;
		}
	}
}

function get_sidebars() {
	global $wp_registered_sidebars;

	$sidebars = array();

	foreach( $wp_registered_sidebars as $sidebar_id => $sidebar ) {
		$sidebars[$sidebar_id] = $sidebar['name'];
	}

	return $sidebars;
}

$choices = array( 'yes' => __('Yes', 'fusion-core'), 'no' => __('No', 'fusion-core') );
$reverse_choices = array( 'no' => __('No', 'fusion-core'), 'yes' => __('Yes', 'fusion-core') );
$choices_with_default = array( '' => __('Default', 'fusion-core'), 'yes' => __('Yes', 'fusion-core'), 'no' => __('No', 'fusion-core') );
$reverse_choices_with_default = array( '' => __('Default', 'fusion-core'), 'no' => __('No', 'fusion-core'), 'yes' => __('Yes', 'fusion-core') );
$leftright = array( 'left' => __('Left', 'fusion-core'), 'right' => __('Right', 'fusion-core') );
$dec_numbers = array( '0.1' => '0.1', '0.2' => '0.2', '0.3' => '0.3', '0.4' => '0.4', '0.5' => '0.5', '0.6' => '0.6', '0.7' => '0.7', '0.8' => '0.8', '0.9' => '0.9', '1' => '1' );
$animation_type = array(
                    '0'             => __( 'None', 'fusion-core' ),
                    'bounce'         => __( 'Bounce', 'fusion-core' ),
                    'fade'             => __( 'Fade', 'fusion-core' ),
                    'flash'         => __( 'Flash', 'fusion-core' ),
                    'rubberBand'     => __( 'Rubberband', 'fusion-core' ),
                    'shake'            => __( 'Shake', 'fusion-core' ),
                    'slide'         => __( 'Slide', 'fusion-core' ),
                    'zoom'             => __( 'Zoom', 'fusion-core' ),
                );
$animation_direction = array(
                    'down'         => __( 'Top', 'fusion-core' ),
                    'left'         => __( 'Left', 'fusion-core' ),
                    'right'     => __( 'Right', 'fusion-core' ),
                    'up'         => __( 'Bottom', 'fusion-core' ),
                    'static'     => __( 'Static', 'fusion-core' ),
                );

// Fontawesome icons list
$pattern = '/\.(fa-(?:\w+(?:-)?)+):before\s+{\s*content:\s*"(.+)";\s+}/';
$fontawesome_path = FUSION_TINYMCE_DIR . '/css/font-awesome.css';
if( file_exists( $fontawesome_path ) ) {
	@$subject = file_get_contents( $fontawesome_path );
}


preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER);

$icons = array();

foreach($matches as $match){
	$icons[$match[1]] = $match[2];
}

$checklist_icons = array ( 'icon-check' => '\f00c', 'icon-star' => '\f006', 'icon-angle-right' => '\f105', 'icon-asterisk' => '\f069', 'icon-remove' => '\f00d', 'icon-plus' => '\f067' );

/*-----------------------------------------------------------------------------------*/
/*	Shortcode Selection Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['shortcode-generator'] = array(
	'no_preview' => true,
	'params' => array(),
	'shortcode' => '',
	'popup_title' => ''
);

/*-----------------------------------------------------------------------------------*/
/*	Alert Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['alert'] = array(
	'no_preview' => true,
	'params' => array(

		'type' => array(
			'type' => 'select',
			'label' => __( 'Alert Type', 'fusion-core' ),
			'desc' => __( 'Select the type of alert message. Choose custom for advanced color options below.', 'fusion-core' ),
			'options' => array(
				'general' => __('General', 'fusion-core'),
				'error' => __('Error', 'fusion-core'),
				'success' => __('Success', 'fusion-core'),
				'notice' => __('Notice', 'fusion-core'),
				'custom' => __('Custom', 'fusion-core'),
			)
		),
		'accentcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Accent Color', 'fusion-core' ),
			'desc' => __( 'Custom setting only. Set the border, text and icon color for custom alert boxes.', 'fusion-core')
		),
		'backgroundcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Background Color', 'fusion-core' ),
			'desc' => __( 'Custom setting only. Set the background color for custom alert boxes.', 'fusion-core')
		),
		'bordersize' => array(
			'std' => '1px',
			'type' => 'text',
			'label' => __( 'Border Width', 'fusion-core' ),
			'desc' => __('Custom setting only. For custom alert boxes. In pixels (px), ex: 1px.', 'fusion-core')
		),
		'icon' => array(
			'type' => 'iconpicker',
			'label' => __( 'Select Custom Icon', 'fusion-core' ),
			'desc' => __( 'Custom setting only. Click an icon to select, click again to deselect', 'fusion-core' ),
			'options' => $icons
		),
		'boxshadow' => array(
			'type' => 'select',
			'label' => __( 'Box Shadow', 'fusion-core' ),
			'desc' =>  __( 'Display a box shadow below the alert box.', 'fusion-core' ),
			'options' => $choices
		),
		'content' => array(
			'std' => __('Your Content Goes Here', 'fusion-core'),
			'type' => 'textarea',
			'label' => __( 'Alert Content', 'fusion-core' ),
			'desc' => __( 'Insert the alert\'s content', 'fusion-core' ),
		),
		'animation_type' => array(
			'type' => 'select',
			'label' => __( 'Animation Type', 'fusion-core' ),
			'desc' => __( 'Select the type of animation to use on the shortcode', 'fusion-core' ),
			'options' => $animation_type,
		),
		'animation_direction' => array(
			'type' => 'select',
			'label' => __( 'Direction of Animation', 'fusion-core' ),
			'desc' => __( 'Select the incoming direction for the animation', 'fusion-core' ),
			'options' => $animation_direction,
		),
		'animation_speed' => array(
			'type' => 'select',
			'std' => '',
			'label' => __( 'Speed of Animation', 'fusion-core' ),
			'desc' => __( 'Type in speed of animation in seconds (0.1 - 1)', 'fusion-core' ),
			'options' => $dec_numbers,
		),
		'animation_offset' => array(
			'type' 		=> 'select',
			'std' 		=> '',
			'label' 	=> __( 'Offset of Animation', 'fusion-core' ),
			'desc' 		=> __( 'Choose when the animation should start.', 'fusion-core' ),
			'options' 	=> array(
					  			''					=> __( 'Default', 'fusion-core' ),
								'top-into-view' 	=> __( 'Top of element hits bottom of viewport', 'fusion-core' ),
								'top-mid-of-view' 	=> __( 'Top of element hits middle of viewport', 'fusion-core' ),
								'bottom-in-view' 	=> __( 'Bottom of element enters viewport', 'fusion-core' ),
							)
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core')
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core')
		),
	),
	'shortcode' => '[alert type="{{type}}" accent_color="{{accentcolor}}" background_color="{{backgroundcolor}}" border_size="{{bordersize}}" icon="{{icon}}" box_shadow="{{boxshadow}}" animation_type="{{animation_type}}" animation_direction="{{animation_direction}}" animation_speed="{{animation_speed}}" animation_offset="{{animation_offset}}" class="{{class}}" id="{{id}}"]{{content}}[/alert]',
	'popup_title' => __( 'Alert Shortcode', 'fusion-core' )
);


/*-----------------------------------------------------------------------------------*/
/*	Blog Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['blog'] = array(
	'no_preview' => true,
	'params' => array(

		'layout' => array(
			'type' => 'select',
			'label' => __( 'Blog Layout', 'fusion-core' ),
			'desc' => __( 'Select the layout for the blog shortcode', 'fusion-core' ),
			'options' => array(
				'large' => __('Large', 'fusion-core'),
				'medium' => __('Medium', 'fusion-core'),
				'large alternate' => __('Large Alternate', 'fusion-core'),
				'medium alternate' => __('Medium Alternate', 'fusion-core'),
				'grid' => __('Grid', 'fusion-core'),
				'timeline' => __('Timeline', 'fusion-core')
			)
		),
		'posts_per_page' => array(
			'type' => 'select',
			'label' => __( 'Posts Per Page', 'fusion-core' ),
			'desc' => __( 'Select number of posts per page.', 'fusion-core' ),
			'options' => fusion_shortcodes_range( 25, true, true )
		),
		'offset' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Post Offset', 'fusion-core' ),
			'desc' => __('The number of posts to skip. ex: 1.', 'fusion-core')
		),
		'cat_slug' => array(
			'type' => 'multiple_select',
			'label' => __( 'Categories', 'fusion-core' ),
			'desc' => __( 'Select a category or leave blank for all.', 'fusion-core' ),
			'options' => fusion_shortcodes_categories( 'category' )
		),
		'exclude_cats' => array(
			'type' => 'multiple_select',
			'label' => __( 'Exclude Categories', 'fusion-core' ),
			'desc' => __( 'Select a category to exclude.', 'fusion-core' ),
			'options' => fusion_shortcodes_categories( 'category' )
		),
		'show_title' => array(
			'type' => 'select',
			'label' => __( 'Show Title', 'fusion-core' ),
			'desc' =>  __( 'Display the post title below the featured image.', 'fusion-core' ),
			'options' => $choices
		),
		'title_link' => array(
			'type' => 'select',
			'label' => __( 'Link Title To Post', 'fusion-core' ),
			'desc' =>  __( 'Choose if the title should be a link to the single post page.', 'fusion-core' ),
			'options' => $choices
		),
		'thumbnail' => array(
			'type' => 'select',
			'label' => __( 'Show Thumbnail', 'fusion-core' ),
			'desc' =>  __( 'Display the post featured image.', 'fusion-core' ),
			'options' => $choices
		),
		'excerpt' => array(
			'type' => 'select',
			'label' => __( 'Show Excerpt', 'fusion-core' ),
			'desc' =>  __( 'Show excerpt or choose "no" for full content.', 'fusion-core' ),
			'options' => $choices
		),
		'excerpt_length' => array(
			'std' => 35,
			'type' => 'text',
			'label' => __( 'Number of words/characters in Excerpt', 'fusion-core' ),
			'desc' =>  __( 'Controls the excerpt length based on words or characters that is set in Theme Options > Extra.', 'fusion-core' ),
		),
		'meta_all' => array(
			'type' => 'select',
			'label' => __( 'Show Meta Info', 'fusion-core' ),
			'desc' =>  __( 'Choose to show all meta data.', 'fusion-core' ),
			'options' => $choices
		),
		'meta_author' => array(
			'type' => 'select',
			'label' => __( 'Show Author Name', 'fusion-core' ),
			'desc' =>  __( 'Choose to show the author.', 'fusion-core' ),
			'options' => $choices
		),
		'meta_categories' => array(
			'type' => 'select',
			'label' => __( 'Show Categories', 'fusion-core' ),
			'desc' =>  __( "Choose to show the categories. Grid and timeline layout generally don't display categories.", 'fusion-core' ),
			'options' => $choices
		),
		'meta_comments' => array(
			'type' => 'select',
			'label' => __( 'Show Comment Count', 'fusion-core' ),
			'desc' =>  __( 'Choose to show the comments.', 'fusion-core' ),
			'options' => $choices
		),
		'meta_date' => array(
			'type' => 'select',
			'label' => __( 'Show Date', 'fusion-core' ),
			'desc' =>  __( 'Choose to show the date.', 'fusion-core' ),
			'options' => $choices
		),
		'meta_link' => array(
			'type' => 'select',
			'label' => __( 'Show Read More Link', 'fusion-core' ),
			'desc' =>  __( 'Choose to show the Read More link.', 'fusion-core' ),
			'options' => $choices
		),
		'meta_tags' => array(
			'type' => 'select',
			'label' => __( 'Show Tags', 'fusion-core' ),
			'desc' =>  __( "Choose to show the tags. Grid and timeline layout generally don't display tags.", 'fusion-core' ),
			'options' => $choices
		),
		'paging' => array(
			'type' => 'select',
			'label' => __( 'Show Pagination', 'fusion-core' ),
			'desc' =>  __( 'Show numerical pagination boxes.', 'fusion-core' ),
			'options' => $choices
		),
		'scrolling' => array(
			'type' => 'select',
			'label' => __( 'Pagination Type', 'fusion-core' ),
			'desc' =>  __( 'Choose the type of pagination.', 'fusion-core' ),
			'options' => array(
				'pagination' => __('Pagination', 'fusion-core'),
				'infinite' => __('Infinite Scrolling', 'fusion-core'),
				'load_more_button' => __('Load More Button', 'fusion-core')
			)
		),
		'blog_grid_columns' => array(
			'type' => 'select',
			'label' => __( 'Grid Layout # of Columns', 'fusion-core' ),
			'desc' => __( 'Select whether to display the grid layout in 2, 3 or 4 column.', 'fusion-core' ),
			'options' => array(
				'2' => '2',
				'3' => '3',
				'4' => '4',
				'5' => '5',
				'6' => '6',
			)
		),
		'blog_grid_column_spacing' => array(
			'std' => '40',
			'type' => 'text',
			'label' => __( 'Grid Layout Column Spacing', 'fusion-core' ),
			'desc' => __( 'Insert the amount of spacing between blog grid posts without "px".', 'fusion-core' )
		),
		'strip_html' => array(
			'type' => 'select',
			'label' => __( 'Strip HTML from Posts Content', 'fusion-core' ),
			'desc' =>  __( 'Strip HTML from the post excerpt.', 'fusion-core' ),
			'options' => $choices
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core')
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core')
		),
	),
	'shortcode' => '[blog number_posts="{{posts_per_page}}" offset="{{offset}}" cat_slug="{{cat_slug}}" exclude_cats="{{exclude_cats}}" show_title="{{show_title}}" title_link="{{title_link}}" thumbnail="{{thumbnail}}" excerpt="{{excerpt}}" excerpt_length="{{excerpt_length}}" strip_html="{{strip_html}}" meta_all="{{meta_all}}" meta_author="{{meta_author}}" meta_categories="{{meta_categories}}" meta_comments="{{meta_comments}}" meta_date="{{meta_date}}" meta_link="{{meta_link}}" meta_tags="{{meta_tags}}" paging="{{paging}}" scrolling="{{scrolling}}" blog_grid_columns="{{blog_grid_columns}}" blog_grid_column_spacing="{{blog_grid_column_spacing}}" layout="{{layout}}" class="{{class}}" id="{{id}}"][/blog]',
	'popup_title' => __( 'Blog Shortcode', 'fusion-core')
);

/*-----------------------------------------------------------------------------------*/
/*	Button Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['button'] = array(
	'no_preview' => true,
	'params' => array(

		'url' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Button URL', 'fusion-core' ),
			'desc' => __( 'Add the button\'s url ex: http://example.com.', 'fusion-core' )
		),
		'style' => array(
			'type' => 'select',
			'label' => __( 'Button Style', 'fusion-core' ),
			'desc' => __( 'Select the button\'s color. Select default or color name for theme options, or select custom to use advanced color options below.', 'fusion-core' ),
			'options' => array(
				'default' => __('Default', 'fusion-core'),
				'custom' => __('Custom', 'fusion-core'),
				'green' => __('Green', 'fusion-core'),
				'darkgreen' => __('Dark Green', 'fusion-core'),
				'orange' => __('Orange', 'fusion-core'),
				'blue' => __('Blue', 'fusion-core'),
				'red' => __('Red', 'fusion-core'),
				'pink' => __('Pink', 'fusion-core'),
				'darkgray' => __('Dark Gray', 'fusion-core'),
				'lightgray' => __('Light Gray', 'fusion-core'),
			)
		),
		'size' => array(
			'type' => 'select',
			'label' => __( 'Button Size', 'fusion-core' ),
			'desc' => __( 'Select the button\'s size. Choose default for theme option selection.', 'fusion-core' ),
			'options' => array(
				'' => __('Default', 'fusion-core'),
				'small' => __('Small', 'fusion-core'),
				'medium' => __('Medium', 'fusion-core'),
				'large' => __('Large', 'fusion-core'),
				'xlarge' => __('XLarge', 'fusion-core'),
			)
		),
		'stretch' => array(
			'type' => 'select',
			'label' => __( 'Button Span', 'fusion-core' ),
			'desc' => __( 'Choose to have the button span the full width of its container.', 'fusion-core' ),
			'options' => $choices_with_default
		),
		'type' => array(
			'type' => 'select',
			'label' => __( 'Button Type', 'fusion-core' ),
			'desc' => __( 'Select the button\'s type. Choose default for theme option selection.', 'fusion-core' ),
			'options' => array(
				'' => __('Default', 'fusion-core'),
				'flat' => __('Flat', 'fusion-core'),
				'3d' => '3D',
			)
		),
		'shape' => array(
			'type' => 'select',
			'label' => __( 'Button Shape', 'fusion-core' ),
			'desc' => __( 'Select the button\'s shape. Choose default for theme option selection.', 'fusion-core' ),
			'options' => array(
				'' => __('Default', 'fusion-core'),
				'square' => __('Square', 'fusion-core'),
				'pill' => __('Pill', 'fusion-core'),
				'round' => __('Round', 'fusion-core'),
			)
		),
		'target' => array(
			'type' => 'select',
			'label' => __( 'Button Target', 'fusion-core' ),
			'desc' => __( '_self = open in same window <br />_blank = open in new window.', 'fusion-core' ),
			'options' => array(
				'_self' => '_self',
				'_blank' => '_blank'
			)
		),
		'title' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Button Title Attribute', 'fusion-core' ),
			'desc' => __( 'Set a title attribute for the button link.', 'fusion-core' ),
		),
		'content' => array(
			'std' => __('Button Text', 'fusion-core'),
			'type' => 'text',
			'label' => __( 'Button\'s Text', 'fusion-core' ),
			'desc' => __( 'Add the text that will display in the button.', 'fusion-core' ),
		),
		'gradtopcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Button Gradient Top Color', 'fusion-core' ),
			'desc' => __( 'Custom setting only. Set the top color of the button background.', 'fusion-core' )
		),
		'gradbottomcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Button Gradient Bottom Color', 'fusion-core' ),
			'desc' => __( 'Custom setting only. Set the bottom color of the button background or leave empty for solid color.', 'fusion-core' )
		),
		'gradtopcolorhover' => array(
			'type' => 'colorpicker',
			'label' => __( 'Button Gradient Top Color Hover', 'fusion-core' ),
			'desc' => __( 'Custom setting only. Set the top hover color of the button background.', 'fusion-core' )
		),
		'gradbottomcolorhover' => array(
			'type' => 'colorpicker',
			'label' => __( 'Button Gradient Bottom Color Hover', 'fusion-core' ),
			'desc' => __( 'Custom setting only. Set the bottom hover color of the button background or leave empty for solid color.', 'fusion-core' )
		),
		'accentcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Accent Color', 'fusion-core' ),
			'desc' => __( 'Custom setting only. This option controls the color of the button border, divider, text and icon.', 'fusion-core' )
		),
		'accenthovercolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Accent Hover Color', 'fusion-core' ),
			'desc' => __( 'Custom setting only. This option controls the hover color of the button border, divider, text and icon.', 'fusion-core' )
		),
		'bevelcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Bevel Color (3D Mode only)', 'fusion-core' ),
			'desc' => __( 'Custom setting only. Set the bevel color of 3D buttons.', 'fusion-core' )
		),
		'borderwidth' => array(
			'std' => '1px',
			'type' => 'text',
			'label' => __( 'Border Width', 'fusion-core' ),
			'desc' => __( 'Custom setting only. In pixels (px), ex: 1px.  Leave blank for theme option selection.', 'fusion-core' )
		),
		/*
		'bordercolor' => array(
			'type' => 'colorpicker',
			'std' => '',
			'label' => __( 'Border Color', 'fusion-core' ),
			'desc' => __('Custom setting. Backside.', 'fusion-core')
		),
		'borderhovercolor' => array(
			'type' => 'colorpicker',
			'std' => '',
			'label' => __( 'Border Hover Color', 'fusion-core' ),
			'desc' => __('Custom setting. Backside.', 'fusion-core')
		),
		'textcolor' => array(
			'type' => 'colorpicker',
			'std' => '',
			'label' => __( 'Text Color', 'fusion-core' ),
			'desc' => __('Custom setting. Backside.', 'fusion-core')
		),
		'texthovercolor' => array(
			'type' => 'colorpicker',
			'std' => '',
			'label' => __( 'Text Hover Color', 'fusion-core' ),
			'desc' => __('Custom setting. Backside.', 'fusion-core')
		),
		*/
		'icon' => array(
			'type' => 'iconpicker',
			'label' => __( 'Select Custom Icon', 'fusion-core' ),
			'desc' => __( 'Click an icon to select, click again to deselect', 'fusion-core' ),
			'options' => $icons
		),
		/*
		'iconcolor' => array(
			'type' => 'colorpicker',
			'std' => '',
			'label' => __( 'Icon Color', 'fusion-core' ),
			'desc' => __('Custom setting. Leave blank for theme option selection.', 'fusion-core')
		),
		*/
		'iconposition' => array(
			'type' => 'select',
			'label' => __( 'Icon Position', 'fusion-core' ),
			'desc' => __( 'Choose the position of the icon on the button.', 'fusion-core' ),
			'options' => $leftright
		),
		'icondivider' => array(
			'type' => 'select',
			'label' => __( 'Icon Divider', 'fusion-core' ),
			'desc' => __( 'Choose to display a divider between icon and text.', 'fusion-core' ),
			'options' => $choices
		),
		'modal' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Modal Window Anchor', 'fusion-core' ),
			'desc' => __( 'Add the class name of the modal window you want to open on button click.', 'fusion-core' ),
		),
		'animation_type' => array(
			'type' => 'select',
			'label' => __( 'Animation Type', 'fusion-core' ),
			'desc' => __( 'Select the type of animation to use on the shortcode', 'fusion-core' ),
			'options' => $animation_type,
		),
		'animation_direction' => array(
			'type' => 'select',
			'label' => __( 'Direction of Animation', 'fusion-core' ),
			'desc' => __( 'Select the incoming direction for the animation', 'fusion-core' ),
			'options' => $animation_direction,
		),
		'animation_speed' => array(
			'type' => 'select',
			'std' => '',
			'label' => __( 'Speed of Animation', 'fusion-core' ),
			'desc' => __( 'Type in speed of animation in seconds (0.1 - 1)', 'fusion-core' ),
			'options' => $dec_numbers,
		),
		'animation_offset' => array(
			'type' 		=> 'select',
			'std' 		=> '',
			'label' 	=> __( 'Offset of Animation', 'fusion-core' ),
			'desc' 		=> __( 'Choose when the animation should start.', 'fusion-core' ),
			'options' 	=> array(
					  			''					=> __( 'Default', 'fusion-core' ),
								'top-into-view' 	=> __( 'Top of element hits bottom of viewport', 'fusion-core' ),
								'top-mid-of-view' 	=> __( 'Top of element hits middle of viewport', 'fusion-core' ),
								'bottom-in-view' 	=> __( 'Bottom of element enters viewport', 'fusion-core' ),
							)
		),
		'alignment' => array(
			'type' => 'select',
			'std' => '',
			'label' => __( 'Alignment', 'fusion-core' ),
			'desc' => __( 'Select the button\'s alignment.', 'fusion-core' ),
			'options' => array(
				'left' => __('Left', 'fusion-core'),
				'center' => __('Center', 'fusion-core'),
				'right' => __('Right', 'fusion-core'),
			)
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core')
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core')
		),
	),
	'shortcode' => '[button link="{{url}}" color="{{style}}" size="{{size}}" stretch="{{stretch}}" type="{{type}}" shape="{{shape}}" target="{{target}}" title="{{title}}" gradient_colors="{{gradtopcolor}}|{{gradbottomcolor}}" gradient_hover_colors="{{gradtopcolorhover}}|{{gradbottomcolorhover}}" accent_color="{{accentcolor}}" accent_hover_color="{{accenthovercolor}}" bevel_color="{{bevelcolor}}" border_width="{{borderwidth}}" icon="{{icon}}" icon_divider="{{icondivider}}" icon_position="{{iconposition}}" modal="{{modal}}" animation_type="{{animation_type}}" animation_direction="{{animation_direction}}" animation_speed="{{animation_speed}}" animation_offset="{{animation_offset}}" alignment="{{alignment}}" class="{{class}}" id="{{id}}"]{{content}}[/button]',
	'popup_title' => __( 'Button Shortcode', 'fusion-core')
);

/*-----------------------------------------------------------------------------------*/
/*	Checklist Config
/*-----------------------------------------------------------------------------------*/
$fusion_shortcodes['checklist'] = array(
	'params' => array(

		'icon' => array(
			'type' => 'iconpicker',
			'label' => __( 'Select Icon', 'fusion-core' ),
			'desc' => __( 'Global setting for all list items, this can be overridden individually below. Click an icon to select, click again to deselect.', 'fusion-core' ),
			'options' => $icons
		),
		'iconcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Icon Color', 'fusion-core' ),
			'desc' => __( 'Global setting for all list items. Leave blank for theme option selection. Defines the icon color.', 'fusion-core')
		),
		'circle' => array(
			'type' => 'select',
			'label' => __( 'Icon in Circle', 'fusion-core' ),
			'desc' => __( 'Global setting for all list items. Set to default for theme option selection. Choose to have icons in circles.', 'fusion-core' ),
			'options' => $choices_with_default
		),
		'circlecolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Circle Color', 'fusion-core' ),
			'desc' => __( 'Global setting for all list items. Leave blank for theme option selection. Defines the circle color.', 'fusion-core')
		),
		'size' => array(
			'std' => '13px',
			'type' => 'text',
			'label' => __( 'Item Size', 'fusion-core' ),
			'desc' => __( 'Select the list item\'s size. In pixels (px), ex: 13px.', 'fusion-core' ),
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core')
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core')
		),
	),

	'shortcode' => '[checklist icon="{{icon}}" iconcolor="{{iconcolor}}" circle="{{circle}}" circlecolor="{{circlecolor}}" size="{{size}}" class="{{class}}" id="{{id}}"]{{child_shortcode}}[/checklist]',
	'popup_title' => __( 'Checklist Shortcode', 'fusion-core' ),
	'no_preview' => true,

	// child shortcode is clonable & sortable
	'child_shortcode' => array(
		'params' => array(
			'icon' => array(
				'type' => 'iconpicker',
				'label' => __( 'Select Icon', 'fusion-core' ),
				'desc' => __( 'This setting will override the global setting above. Leave blank for theme option selection.', 'fusion-core' ),
				'options' => $icons
			),
			'content' => array(
				'std' => __('Your Content Goes Here', 'fusion-core'),
				'type' => 'textarea',
				'label' => __( 'List Item Content', 'fusion-core' ),
				'desc' => __( 'Add list item content', 'fusion-core' ),
			),
		),
		'shortcode' => '[li_item icon="{{icon}}"]{{content}}[/li_item]',
		'clone_button' => __( 'Add New List Item', 'fusion-core')
	)
);


/*-----------------------------------------------------------------------------------*/
/*	Client Slider Config
/*-----------------------------------------------------------------------------------*/
/*
$fusion_shortcodes['clientslider'] = array(
	'params' => array(
		'picture_size' => array(
			'type' => 'select',
			'label' => __( 'Picture Size', 'fusion-core' ),
			'desc' => __( 'fixed = width and height will be fixed <br />auto = width and height will adjust to the image.', 'fusion-core' ),
			'options' => array(
				'fixed' => __('Fixed', 'fusion-core'),
				'auto' => __('Auto', 'fusion-core')
			)
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core')
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core')
		),
	),
	'shortcode' => '[clients picture_size="{{picture_size}}" class="{{class}}" id="{{id}}"]{{child_shortcode}}[/clients]', // as there is no wrapper shortcode
	'popup_title' => __( 'Client Slider Shortcode', 'fusion-core' ),
	'no_preview' => true,

	// child shortcode is clonable & sortable
	'child_shortcode' => array(
		'params' => array(
			'url' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Client Website Link', 'fusion-core' ),
				'desc' => __( 'Add the url to client\'s website <br />ex: http://example.com', 'fusion-core')
			),
			'target' => array(
				'type' => 'select',
				'label' => __( 'Link Target', 'fusion-core' ),
				'desc' => __( '_self = open in same window <br /> _blank = open in new window', 'fusion-core' ),
				'options' => array(
					'_self' => '_self',
					'_blank' => '_blank'
				)
			),
			'image' => array(
				'type' => 'uploader',
				'label' => __( 'Client Image', 'fusion-core' ),
				'desc' => __( 'Upload the client image', 'fusion-core' ),
			),
			'alt' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Image Alt Text', 'fusion-core' ),
				'desc' => __('The alt attribute provides alternative information if an image cannot be viewed', 'fusion-core')
			),
		),
		'shortcode' => '[client link="{{url}}" linktarget="{{target}}" image="{{image}}" alt="{{alt}}"]',
		'clone_button' => __( 'Add New Client Image', 'fusion-core')
	)
);
*/
/*-----------------------------------------------------------------------------------*/
/*	Code Block Config
/*-----------------------------------------------------------------------------------*/

/*$fusion_shortcodes['code'] = array(
	'no_preview' => true,
	'params' => array(
		'content' => array(
			'std' => 'Click edit button to change this code.',
			'type' => 'textarea',
			'label' => __( 'Code', 'fusion-core' ),
			'desc' => __( 'Enter some content for this codeblock', 'fusion-core' ),
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[fusion_code class="{{class}}" id="{{id}}"]{{content}}[/fusion_code]',
	'popup_title' => __( 'Code Block Shortcode', 'fusion-core' )
);*/


/*-----------------------------------------------------------------------------------*/
/*	Columns Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['columns'] = array(
	'shortcode' => ' {{child_shortcode}} ', // as there is no wrapper shortcode
	'popup_title' => __( 'Insert Columns Shortcode', 'fusion-core' ),
	'no_preview' => true,
	'params' => array(),

	// child shortcode is clonable & sortable
	'child_shortcode' => array(
		'params' => array(
			'column' => array(
				'type' => 'select',
				'label' => __( 'Column Type', 'fusion-core' ),
				'desc' => __( 'Select the width of the column', 'fusion-core' ),
				'options' => array(
					'one_full'		=> __('One Column', 'fusion-core'),
					'one_half' 		=> __('One Half', 'fusion-core'),
					'one_third' 	=> __('One Third', 'fusion-core'),
					'two_third' 	=> __('Two Thirds', 'fusion-core'),
					'one_fourth'	=> __('One Fourth', 'fusion-core'),
					'three_fourth' 	=> __('Three Fourth', 'fusion-core'),
					'one_fifth' 	=> __('One Fifth', 'fusion-core'),
					'two_fifth' 	=> __('Two Fifth', 'fusion-core'),
					'three_fifth' 	=> __('Three Fifth', 'fusion-core'),
					'four_fifth' 	=> __('Four Fifth', 'fusion-core'),
					'one_sixth' 	=> __('One Sixth', 'fusion-core'),
					'five_sixth' 	=> __('Five Sixth', 'fusion-core'),
					'one' 	        => __('One ( Six Sixth )', 'fusion-core'),
				)
			),
			'last' => array(
				'type' => 'select',
				'label' => __( 'Last Column', 'fusion-core' ),
				'desc' => __('Choose if the column is last in a set. This has to be set to "Yes" for the last column in a set', 'fusion-core'),
				'options' => $reverse_choices
			),
			'spacing' => array(
				'std' => 'yes',
				'type' => 'select',
				'label' => __( 'Column Spacing', 'fusion-core' ),
				'desc' => __( 'Set to "No" to eliminate margin between columns.', 'fusion-core' ),
				'options' => $choices
			),
			'center_content' => array(
				'type' => 'select',
				'label' => __( 'Center Content Vertically', 'fusion-core' ),
				'desc' => __('Only works with columns inside a full width container that is set to equal heights. Set to "Yes" to center the content vertically.', 'fusion-core'),
				'options' => $reverse_choices
			),
			'hide_on_mobile' => array(
				'type' => 'select',
				'label' => __( 'Hide on Mobile', 'fusion-core' ),
				'desc' => __('Select "Yes" to hide column on mobile.', 'fusion-core'),
				'options' => $reverse_choices
			),
			'backgroundcolor' => array(
				'type' => 'colorpicker',
				'label' => __( 'Background Color', 'fusion-core' ),
				'desc' => __( 'Controls the background color.', 'fusion-core')
			),
			'backgroundimage' => array(
				'type' => 'uploader',
				'label' => __( 'Background Image', 'fusion-core' ),
				'desc' => __('Upload an image to display in the background', 'fusion-core')
			),
			'backgroundrepeat' => array(
				'type' => 'select',
				'label' => __( 'Background Repeat', 'fusion-core' ),
				'desc' => __('Choose how the background image repeats.', 'fusion-core'),
				'options' => array(
					'no-repeat' => __('No Repeat', 'fusion-core'),
					'repeat' => __('Repeat Vertically and Horizontally', 'fusion-core'),
					'repeat-x' => __('Repeat Horizontally', 'fusion-core'),
					'repeat-y' => __('Repeat Vertically', 'fusion-core')
				)
			),
			'backgroundposition' => array(
				'type' => 'select',
				'label' => __( 'Background Position', 'fusion-core' ),
				'desc' => __('Choose the postion of the background image.', 'fusion-core'),
				'options' => array(
					'left top' => __('Left Top', 'fusion-core'),
					'left center' => __('Left Center', 'fusion-core'),
					'left bottom' => __('Left Bottom', 'fusion-core'),
					'right top' => __('Right Top', 'fusion-core'),
					'right center' => __('Right Center', 'fusion-core'),
					'right bottom' => __('Right Bottom', 'fusion-core'),
					'center top' => __('Center Top', 'fusion-core'),
					'center center' => __('Center Center', 'fusion-core'),
					'center bottom' => __('Center Bottom', 'fusion-core')
				)
			),
			'hover_type' => array(
				'std' => 'none',
				'type' => 'select',
				'label' => __( 'Hover Type', 'fusion-core' ),
				'desc' => __('Select the hover effect type. This will disable links and hover effects on elements inside the column.', 'fusion-core'),
				'options' => array(
					'none' => __('None', 'fusion-core'),
					'zoomin' => __('Zoom In', 'fusion-core'),
					'zoomout' => __('Zoom Out', 'fusion-core'),
					'liftup' => __('Lift Up', 'fusion-core')
				)
			),
			'link' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Link URL', 'fusion-core' ),
				'desc' => __( 'Add the URL the column will link to, ex: http://example.com. This will disable links on elements inside the column.', 'fusion-core' ),
			),
			'borderposition' => array(
				'type' => 'select',
				'label' => __( 'Border Position', 'fusion-core' ),
				'desc' => __('Choose the postion of the border.', 'fusion-core'),
				'options' => array(
					'all' => __('All', 'fusion-core'),
					'top' => __('Top', 'fusion-core'),
					'right' => __('Right', 'fusion-core'),
					'bottom' => __('Bottom', 'fusion-core'),
					'left' => __('Left', 'fusion-core'),
				)
			),
			'bordersize' => array(
				'std' => '0px',
				'type' => 'text',
				'label' => __( 'Border Size', 'fusion-core' ),
				'desc' => __( 'In pixels (px), ex: 1px.', 'fusion-core' ),
			),
			'bordercolor' => array(
				'type' => 'colorpicker',
				'label' => __( 'Border Color', 'fusion-core' ),
				'desc' => __( 'Controls the border color.', 'fusion-core')
			),
			'borderstyle' => array(
				'type' => 'select',
				'label' => __( 'Border Style', 'fusion-core' ),
				'desc' => __( 'Controls the border style.', 'fusion-core' ),
				'options' => array(
					'solid' => __('Solid', 'fusion-core'),
					'dashed' => __('Dashed', 'fusion-core'),
					'dotted' => __('Dotted', 'fusion-core')
				)
			),
			'padding' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Padding', 'fusion-core' ),
				'desc' => __( 'In pixels (px), ex: 10px.', 'fusion-core' )
			),
			'margin_top' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Margin Top', 'fusion-core' ),
				'desc' => __( 'In pixels (px), ex: 10px.', 'fusion-core' )
			),
			'margin_bottom' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Margin Bottom', 'fusion-core' ),
				'desc' => __( 'In pixels (px), ex: 10px.', 'fusion-core' )
			),
			'content' => array(
				'std' => '',
				'type' => 'textarea',
				'label' => __( 'Column Content', 'fusion-core' ),
				'desc' => __( 'Insert the column content', 'fusion-core' ),
			),
			'animation_type' => array(
				'type' => 'select',
				'label' => __( 'Animation Type', 'fusion-core' ),
				'desc' => __( 'Select the type of animation to use on the shortcode', 'fusion-core' ),
				'options' => $animation_type,
			),
			'animation_direction' => array(
				'type' => 'select',
				'label' => __( 'Direction of Animation', 'fusion-core' ),
				'desc' => __( 'Select the incoming direction for the animation', 'fusion-core' ),
				'options' => $animation_direction,
			),
			'animation_speed' => array(
				'type' => 'select',
				'std' => '',
				'label' => __( 'Speed of Animation', 'fusion-core' ),
				'desc' => __( 'Type in speed of animation in seconds (0.1 - 1)', 'fusion-core' ),
				'options' => $dec_numbers,
			),
			'animation_offset' => array(
				'type' 		=> 'select',
				'std' 		=> '',
				'label' 	=> __( 'Offset of Animation', 'fusion-core' ),
				'desc' 		=> __( 'Choose when the animation should start.', 'fusion-core' ),
				'options' 	=> array(
					  				''					=> __( 'Default', 'fusion-core' ),
									'top-into-view' 	=> __( 'Top of element hits bottom of viewport', 'fusion-core' ),
									'top-mid-of-view' 	=> __( 'Top of element hits middle of viewport', 'fusion-core' ),
									'bottom-in-view' 	=> __( 'Bottom of element enters viewport', 'fusion-core' ),
								)
			),
			'class' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'CSS Class', 'fusion-core' ),
				'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
			),
			'id' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'CSS ID', 'fusion-core' ),
				'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
			),
		),
		'shortcode' => '[{{column}} spacing="{{spacing}}" last="{{last}}" center_content="{{center_content}}" hide_on_mobile="{{hide_on_mobile}}" background_color="{{backgroundcolor}}" background_image="{{backgroundimage}}" background_repeat="{{backgroundrepeat}}" background_position="{{backgroundposition}}" link="{{link}}" hover_type="{{hover_type}}" border_position="{{borderposition}}" border_size="{{bordersize}}" border_color="{{bordercolor}}" border_style="{{borderstyle}}" padding="{{padding}}" margin_top="{{margin_top}}" margin_bottom="{{margin_bottom}}" animation_type="{{animation_type}}" animation_direction="{{animation_direction}}" animation_speed="{{animation_speed}}" animation_offset="{{animation_offset}}" class="{{class}}" id="{{id}}"]{{content}}[/{{column}}] ',
		'clone_button' => __( 'Add Column', 'fusion-core')
	)
);

/*-----------------------------------------------------------------------------------*/
/*	Content Boxes Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['contentboxes'] = array(
	'params' => array(
		'layout' => array(
			'type' => 'select',
			'label' => __( 'Box Layout', 'fusion-core' ),
			'desc' => __( 'Select the layout for the content box', 'fusion-core' ),
			'options' => array(
				'icon-with-title' => __('Classic Icon With Title', 'fusion-core'),
				'icon-on-top' => __('Classic Icon On Top', 'fusion-core'),
				'icon-on-side' => __('Classic Icon On Side', 'fusion-core'),
				'icon-boxed' => __('Icon Boxed', 'fusion-core'),
				'clean-vertical' => __('Clean Layout Vertical', 'fusion-core'),
				'clean-horizontal' => __('Clean Layout Horizontal', 'fusion-core'),
				'timeline-vertical' => __('Timeline Vertical', 'fusion-core'),
				'timeline-horizontal' => __('Timeline Horizontal', 'fusion-core')
			)
		),
		'columns' => array(
			'std' => 4,
			'type' => 'select',
			'label' => __( 'Number of Columns', 'fusion-core' ),
			'desc' =>  __( 'Set the number of columns per row.', 'fusion-core' ),
			'options' => fusion_shortcodes_range( 6, false )
		),
		'icon_align' => array(
			'std' => 'left',
			'type' => 'select',
			'label' => __( 'Content Alignment', 'fusion-core' ),
			'desc' =>  __( 'Works with "Classic Icon With Title" and "Classic Icon On Side" layout options.' ),
			'options' => array('left'		=> 'Left',
							   'right'	 	=> 'Right')
		),
		'title_size' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Title Size', 'fusion-core' ),
			'desc' => __( 'Controls the size of the title. Leave blank for theme option selection. In pixels ex: 18px.', 'fusion-core')
		),
		'backgroundcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Content Box Background Color', 'fusion-core' ),
			'desc' => __( 'Leave blank for theme option selection.', 'fusion-core')
		),
		'icon_circle' => array(
			'type' => 'select',
			'label' => __( 'Icon Background', 'fusion-core' ),
			'desc' => __( 'Controls the background behind the icon. Select default for theme option selection.', 'fusion-core' ),
			'options' => array(
				'' => __('Default', 'fusion-core'),
				'yes' => __('Yes', 'fusion-core'),
				'no' => __('No', 'fusion-core'),
			)
		),
		'icon_circle_radius' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Icon Background Radius', 'fusion-core' ),
			'desc' => __( 'Choose the border radius of the icon background. Leave blank for theme option selection. In pixels (px), ex: 1px, or "round".', 'fusion-core')
		),
		'iconcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Icon Color', 'fusion-core' ),
			'desc' => __( 'Leave blank for theme option selection.', 'fusion-core')
		),
		'circlecolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Icon Background Color', 'fusion-core' ),
			'desc' => __( 'Leave blank for theme option selection.', 'fusion-core')
		),
		'circlebordercolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Icon Background Inner Border Color', 'fusion-core' ),
			'desc' => __( 'Leave blank for theme option selection.', 'fusion-core')
		),
		'circlebordercolorsize' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Icon Background Inner Border Size', 'fusion-core' ),
			'desc' => __( 'Leave blank for theme option selection.', 'fusion-core')
		),
		'outercirclebordercolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Icon Background Outer Border Color', 'fusion-core' ),
			'desc' => __( 'Leave blank for theme option selection.', 'fusion-core')
		),
		'outercirclebordercolorsize' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Icon Background Outer Border Size', 'fusion-core' ),
			'desc' => __( 'Leave blank for theme option selection.', 'fusion-core')
		),
		'icon_size' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Icon Size', 'fusion-core' ),
			'desc' => __( 'Controls the size of the icon.  Leave blank for theme option selection. In pixels ex: 18px.', 'fusion-core')
		),
		'link_type' => array(
			'type' => 'select',
			'label' => __( 'Link Type', 'fusion-core' ),
			'desc' => __( 'Select the type of link that should show in the content box. Select default for theme option selection.', 'fusion-core' ),
			'options' => array(
				''	=> 'Default',
				'text' => 'Text',
				'button-bar' => 'Button Bar',
				'button' => 'Button'
			)
		),
		'link_area' => array(
			'std' => '',
			'type' => 'select',
			'label' => __( 'Link Area', 'fusion-core' ),
			'desc' =>  __( 'Select which area the link will be assigned to' ),
			'options' => array('' => 'Default',
								'link-icon'		=> 'Link+Icon',
							   'box'	 		=> 'Entire Content Box')
		),
		'target' => array(
			'type' => 'select',
			'label' => __( 'Link Target', 'fusion-core' ),
			'desc' => __( '_self = open in same window <br /> _blank = open in new window', 'fusion-core' ),
			'options' => array(
				''	=> 'Default',
				'_self' => '_self',
				'_blank' => '_blank'
			)
		),
		'animation_delay' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Animation Delay', 'fusion-core' ),
			'desc' => __( 'Controls the delay of animation between each element in a set. In milliseconds.', 'fusion-core')
		),
		'animation_offset' => array(
			'type' 		=> 'select',
			'std' 		=> '',
			'label' 	=> __( 'Offset of Animation', 'fusion-core' ),
			'desc' 		=> __( 'Choose when the animation should start.', 'fusion-core' ),
			'options' 	=> array(
					  			''					=> __( 'Default', 'fusion-core' ),
								'top-into-view' 	=> __( 'Top of element hits bottom of viewport', 'fusion-core' ),
								'top-mid-of-view' 	=> __( 'Top of element hits middle of viewport', 'fusion-core' ),
								'bottom-in-view' 	=> __( 'Bottom of element enters viewport', 'fusion-core' ),
							)
		),
		'animation_type' => array(
			'type' => 'select',
			'label' => __( 'Animation Type', 'fusion-core' ),
			'desc' => __( 'Select the type on animation to use on the shortcode', 'fusion-core' ),
			'options' => $animation_type,
		),
		'animation_direction' => array(
			'type' => 'select',
			'label' => __( 'Direction of Animation', 'fusion-core' ),
			'desc' => __( 'Select the incoming direction for the animation', 'fusion-core' ),
			'options' => $animation_direction,
		),
		'animation_speed' => array(
			'type' => 'select',
			'std' => '',
			'label' => __( 'Speed of Animation', 'fusion-core' ),
			'desc' => __( 'Type in speed of animation in seconds (0.1 - 1)', 'fusion-core' ),
			'options' => $dec_numbers,
		),
		'margin_top' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Margin Top', 'fusion-core' ),
			'desc' => __( 'In pixels (px), ex: 10px.', 'fusion-core' )
		),
		'margin_bottom' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Margin Bottom', 'fusion-core' ),
			'desc' => __( 'In pixels (px), ex: 10px.', 'fusion-core' )
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core')
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core')
		),
	),
	'shortcode' => '[content_boxes layout="{{layout}}" columns="{{columns}}" icon_align="{{icon_align}}" title_size="{{title_size}}" backgroundcolor="{{backgroundcolor}}" icon_circle="{{icon_circle}}" icon_circle_radius="{{icon_circle_radius}}" iconcolor="{{iconcolor}}" circlecolor="{{circlecolor}}" circlebordercolor="{{circlebordercolor}}" circlebordercolorsize="{{circlebordercolorsize}}" outercirclebordercolor="{{circlebordercolor}}" outercirclebordercolorsize="{{outercirclebordercolorsize}}" icon_size="{{icon_size}}" link_type="{{link_type}}" link_area="{{link_area}}" animation_delay="{{animation_delay}}" animation_offset="{{animation_offset}}" animation_type="{{animation_type}}" animation_direction="{{animation_direction}}" animation_speed="{{animation_speed}}" margin_top="{{margin_top}}" margin_bottom="{{margin_top}}" class="{{class}}" id="{{id}}"]{{child_shortcode}}[/content_boxes]', // as there is no wrapper shortcode
	'popup_title' => __( 'Content Boxes Shortcode', 'fusion-core' ),
	'no_preview' => true,

	// child shortcode is clonable & sortable
	'child_shortcode' => array(
		'params' => array(
			'title' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Title', 'fusion-core'),
				'desc' => __( 'The box title.', 'fusion-core' ),
			),
			'icon' => array(
				'type' => 'iconpicker',
				'label' => __( 'Icon', 'fusion-core' ),
				'desc' => __( 'Click an icon to select, click again to deselect.', 'fusion-core' ),
				'options' => $icons
			),
			'backgroundcolor' => array(
				'type' => 'colorpicker',
				'label' => __( 'Content Box Background Color', 'fusion-core' ),
				'desc' => __( 'Leave blank for theme option selection.', 'fusion-core')
			),
			'iconcolor' => array(
				'type' => 'colorpicker',
				'label' => __( 'Icon Color', 'fusion-core' ),
				'desc' => __( 'Leave blank for theme option selection.', 'fusion-core')
			),
			'circlecolor' => array(
				'type' => 'colorpicker',
				'label' => __( 'Icon Background Color', 'fusion-core' ),
				'desc' => __( 'Leave blank for theme option selection.', 'fusion-core')
			),
			'circlebordercolor' => array(
				'type' => 'colorpicker',
				'label' => __( 'Icon Background Inner Border Color', 'fusion-core' ),
				'desc' => __( 'Leave blank for theme option selection.', 'fusion-core')
			),
			'circlebordercolorsize' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Icon Background Inner Border Size', 'fusion-core' ),
				'desc' => __( 'Leave blank for theme option selection.', 'fusion-core')
			),
			'outercirclebordercolor' => array(
				'type' => 'colorpicker',
				'label' => __( 'Icon Background Outer Border Color', 'fusion-core' ),
				'desc' => __( 'Leave blank for theme option selection.', 'fusion-core')
			),
			'outercirclebordercolorsize' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Icon Background Outer Border Size', 'fusion-core' ),
				'desc' => __( 'Leave blank for theme option selection.', 'fusion-core')
			),
			'iconrotate' => array(
				'type' => 'select',
				'label' => __( 'Rotate Icon', 'fusion-core' ),
				'desc' => __( 'Choose to rotate the icon.', 'fusion-core' ),
				'options' => array(
					''	=> __('None', 'fusion-core'),
					'90' => '90',
					'180' => '180',
					'270' => '270',
				)
			),
			'iconspin' => array(
				'type' => 'select',
				'label' => __( 'Spinning Icon', 'fusion-core' ),
				'desc' => __( 'Choose to let the icon spin.', 'fusion-core' ),
				'options' => $reverse_choices
			),
			'image' => array(
				'type' => 'uploader',
				'label' => __( 'Icon Image', 'fusion-core' ),
				'desc' => __( 'To upload your own icon image, deselect the icon above and then upload your icon image.', 'fusion-core' ),
			),
			'image_width' => array(
				'std' => 35,
				'type' => 'text',
				'label' => __( 'Icon Image Width', 'fusion-core' ),
				'desc' => __( 'If using an icon image, specify the image width in pixels but do not add px, ex: 35.', 'fusion-core' ),
			),
			'image_height' => array(
				'std' => 35,
				'type' => 'text',
				'label' => __( 'Icon Image Height', 'fusion-core' ),
				'desc' => __( 'If using an icon image, specify the image height in pixels but do not add px, ex: 35.', 'fusion-core' ),
			),
			'link' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Link Url', 'fusion-core' ),
				'desc' => __( 'Add the link\'s url ex: http://example.com', 'fusion-core' ),

			),
			'linktext' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Link Text', 'fusion-core' ),
				'desc' => __( 'Insert the text to display as the link', 'fusion-core' ),

			),
			'target' => array(
				'type' => 'select',
				'label' => __( 'Link Target', 'fusion-core' ),
				'desc' => __( '_self = open in same window <br /> _blank = open in new window', 'fusion-core' ),
				'options' => array(
					'_self' => '_self',
					'_blank' => '_blank'
				)
			),
			'content' => array(
				'std' => __('Your Content Goes Here', 'fusion-core'),
				'type' => 'textarea',
				'label' => __( 'Content Box Content', 'fusion-core' ),
				'desc' => __( 'Add content for content box', 'fusion-core' ),
			),
			'animation_type' => array(
				'type' => 'select',
				'label' => __( 'Animation Type', 'fusion-core' ),
				'desc' => __( 'Select the type on animation to use on the shortcode', 'fusion-core' ),
				'options' => $animation_type,
			),
			'animation_direction' => array(
				'type' => 'select',
				'label' => __( 'Direction of Animation', 'fusion-core' ),
				'desc' => __( 'Select the incoming direction for the animation', 'fusion-core' ),
				'options' => $animation_direction,
			),
			'animation_speed' => array(
				'type' => 'select',
				'std' => '',
				'label' => __( 'Speed of Animation', 'fusion-core' ),
				'desc' => __( 'Type in speed of animation in seconds (0.1 - 1)', 'fusion-core' ),
				'options' => $dec_numbers,
			)
		),
		'shortcode' => '[content_box title="{{title}}" icon="{{icon}}" backgroundcolor="{{backgroundcolor}}" iconcolor="{{iconcolor}}" circlecolor="{{circlecolor}}" circlebordercolor="{{circlebordercolor}}" circlebordercolorsize="{{circlebordercolorsize}}" outercirclebordercolor="{{circlebordercolor}}" outercirclebordercolorsize="{{outercirclebordercolorsize}}" iconrotate="{{iconrotate}}" iconspin="{{iconspin}}" image="{{image}}" image_width="{{image_width}}" image_height="{{image_height}}" link="{{link}}" linktarget="{{target}}" linktext="{{linktext}}" animation_type="{{animation_type}}" animation_direction="{{animation_direction}}" animation_speed="{{animation_speed}}"]{{content}}[/content_box]',
		'clone_button' => __( 'Add New Content Box', 'fusion-core')
	)
);

/*-----------------------------------------------------------------------------------*/
/*	Countdown Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['fusion_countdown'] = array(
	'params' => array(
		'countdown_end' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Countdown Timer End', 'fusion-core' ),
			'desc' =>  __( 'Set the end date and time for the countdown time. Use SQL time format: YYYY-MM-DD HH:MM:SS. E.g: 2016-05-10 12:30:00.', 'fusion-core' ),
		),
		'timezone' => array(
			'std' => '',
			'type' => 'select',
			'label' => __( 'Timezone', 'fusion-core' ),
			'desc' => __( 'Choose which timezone should be used for the countdown calculation.', 'fusion-core'),
			'options' => array(
				'' 				=> __( 'Default', 'fusion-core' ),
				'site_time' => __( 'Timezone of Site', 'fusion-core' ),
				'user_time' => __( 'Timezone of User', 'fusion-core' )
			)
		),
		'show_weeks' => array(
			'std' => '',
			'type' => 'select',
			'label' => __( 'Show Weeks', 'fusion-core' ),
			'desc' => __( 'Select "yes" to show weeks for longer countdowns.', 'fusion-core'),
			'options' => array(
				'default' 	=> __( 'Default', 'fusion-core' ),
				'no' 		=> __('No', 'fusion-core'),
				'yes' 		=> __('Yes', 'fusion-core')
			)
		),
		'background_color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Background Color', 'fusion-core' ),
			'desc' => __( 'Choose a background color for the countdown wrapping box.', 'fusion-core')
		),
		'background_image' => array(
			'type' => 'uploader',
			'label' => __( 'Background Image', 'fusion-core' ),
			'desc' => __('Upload an image to display in the background of the countdown wrapping box.', 'fusion-core')
		),
		'background_position' => array(
			'type' => 'select',
			'label' => __( 'Background Position', 'fusion-core' ),
			'desc' => __('Choose the postion of the background image.', 'fusion-core'),
			'options' => array(
				''	 			=> __('Default', 'fusion-core'),
				'left top' 		=> __('Left Top', 'fusion-core'),
				'left center' 	=> __('Left Center', 'fusion-core'),
				'left bottom' 	=> __('Left Bottom', 'fusion-core'),
				'right top' 	=> __('Right Top', 'fusion-core'),
				'right center' 	=> __('Right Center', 'fusion-core'),
				'right bottom' 	=> __('Right Bottom', 'fusion-core'),
				'center top'	=> __('Center Top', 'fusion-core'),
				'center center' => __('Center Center', 'fusion-core'),
				'center bottom' => __('Center Bottom', 'fusion-core')
			)
		),
		'background_repeat' => array(
			'type' => 'select',
			'label' => __( 'Background Repeat', 'fusion-core' ),
			'desc' => __('Choose how the background image repeats.', 'fusion-core'),
			'options' => array(
				'' 			=> __('Default', 'fusion-core'),
				'no-repeat' => __('No Repeat', 'fusion-core'),
				'repeat' 	=> __('Repeat Vertically and Horizontally', 'fusion-core'),
				'repeat-x' 	=> __('Repeat Horizontally', 'fusion-core'),
				'repeat-y' 	=> __('Repeat Vertically', 'fusion-core')
			)
		),
		'border_radius' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Border Radius', 'fusion-core' ),
			'desc' => __('Choose the radius of outer box and also the counter boxes. In pixels (px), ex: 1px.', 'fusion-core')
		),
		'counter_box_color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Counter Boxes Color', 'fusion-core' ),
			'desc' => __( 'Choose a background color for the counter boxes.', 'fusion-core')
		),
		'counter_text_color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Counter Boxes Text Color', 'fusion-core' ),
			'desc' => __( 'Choose a text color for the countdown timer.', 'fusion-core')
		),
		'heading_text' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Heading Text', 'fusion-core' ),
			'desc' => __( 'Choose a heading text for the countdown.', 'fusion-core')
		),
		'heading_text_color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Heading Text Color', 'fusion-core' ),
			'desc' => __( 'Choose a text color for the countdown heading.', 'fusion-core')
		),
		'subheading_text' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Subheading Text', 'fusion-core' ),
			'desc' => __( 'Choose a subheading text for the countdown.', 'fusion-core')
		),
		'subheading_text_color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Subheading Text Color', 'fusion-core' ),
			'desc' => __( 'Choose a text color for the countdown subheading.', 'fusion-core')
		),
		'link_text' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Link Text', 'fusion-core' ),
			'desc' => __( 'Choose a link text for the countdown.', 'fusion-core')
		),
		'link_text_color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Link Text Color', 'fusion-core' ),
			'desc' => __( 'Choose a text color for the countdown link.', 'fusion-core')
		),
		'link_url' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Link URL', 'fusion-core' ),
			'desc' => __( 'Add a url for the link. E.g: http://example.com.', 'fusion-core')
		),
		'link_target' => array(
			'type' => 'select',
			'label' => __( 'Link Target', 'fusion-core' ),
			'desc' => __( '_self = open in same window <br /> _blank = open in new window', 'fusion-core' ),
			'options' => array(
				'default'	=> 'Default',
				'_self' 	=> '_self',
				'_blank'	=> '_blank'
			)
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core')
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core')
		),
	),
	'shortcode' => '[fusion_countdown countdown_end="{{countdown_end}}" timezone="{{timezone}}" show_weeks="{{show_weeks}}" background_color="{{background_color}}" background_image="{{background_image}}" background_position="{{background_position}}" background_repeat="{{background_repeat}}" border_radius="{{border_radius}}" counter_box_color="{{counter_box_color}}" counter_text_color="{{counter_text_color}}" heading_text="{{heading_text}}" heading_text_color="{{heading_text_color}}" subheading_text="{{subheading_text}}" subheading_text_color="{{subheading_text_color}}" link_text="{{link_text}}" link_text_color="{{link_text_color}}" link_url="{{link_url}}" link_target="{{link_target}}" class="{{class}}" id="{{id}}"][/fusion_countdown]', // as there is no wrapper shortcode
	'popup_title' => __( 'Countdown Shortcode', 'fusion-core' ),
	'no_preview' => true
);


/*-----------------------------------------------------------------------------------*/
/*	Counters Box Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['countersbox'] = array(
	'params' => array(
		'columns' => array(
			'std' => 4,
			'type' => 'select',
			'label' => __( 'Number of Columns', 'fusion-core' ),
			'desc' =>  __( 'Set the number of columns per row.', 'fusion-core' ),
			'options' => fusion_shortcodes_range( 6, false )
		),
		'title_color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Counter Box Title Font Color', 'fusion-core' ),
			'desc' => __( 'Controls the color of the counter "value" and icon. Leave blank for theme option styling.', 'fusion-core')
		),
		'title_size' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Counter Box Title Font Size ', 'fusion-core' ),
			'desc' => __( 'Controls the size of the title font used for the counter value. Enter the font size without \'px\'. Default is 50. Leave blank for theme option styling.', 'fusion-core')
		),
		'icon_size' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Counter Box Icon Size', 'fusion-core' ),
			'desc' => __( 'Controls the size of the icon. Enter the font size without \'px\'. Default is 50. Leave blank for theme option styling.', 'fusion-core')
		),
		'icon_top' => array(
			'std' => '',
			'type' => 'select',
			'label' => __( 'Counter Box Icon Top', 'fusion-core' ),
			'desc' => __( 'Controls the position of the icon. Select Default for theme option styling.', 'fusion-core'),
			'options' => array(
				'' => __( 'Default', 'fusion-core' ),
				'no' => __('No', 'fusion-core'),
				'yes' => __('Yes', 'fusion-core')
			)
		),
		'body_color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Counter Box Body Font Color', 'fusion-core' ),
			'desc' => __( 'Controls the color of the counter body text. Leave blank for theme option styling.', 'fusion-core')
		),
		'body_size' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Counter Box Body Font Size', 'fusion-core' ),
			'desc' => __( 'Controls the size of the counter body text. Enter the font size without \'px\' ex: 13. Leave blank for theme option styling.', 'fusion-core')
		),
		'border_color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Counter Box Border Color', 'fusion-core' ),
			'desc' => __( 'Controls the color of the border.', 'fusion-core')
		),
		'animation_offset' => array(
			'type' 		=> 'select',
			'std' 		=> '',
			'label' 	=> __( 'Offset of Animation', 'fusion-core' ),
			'desc' 		=> __( 'Choose when the animation should start.', 'fusion-core' ),
			'options' 	=> array(
					  			''					=> __( 'Default', 'fusion-core' ),
								'top-into-view' 	=> __( 'Top of element hits bottom of viewport', 'fusion-core' ),
								'top-mid-of-view' 	=> __( 'Top of element hits middle of viewport', 'fusion-core' ),
								'bottom-in-view' 	=> __( 'Bottom of element enters viewport', 'fusion-core' ),
							)
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core')
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core')
		),
	),
	'shortcode' => '[counters_box columns="{{columns}}" color="{{title_color}}" title_size="{{title_size}}" icon_size="{{icon_size}}" body_color="{{body_color}}" body_size="{{body_size}}" border_color="{{border_color}}" animation_offset="{{animation_offset}}" class="{{class}}" id="{{id}}"]{{child_shortcode}}[/counters_box]', // as there is no wrapper shortcode
	'popup_title' => __( 'Counters Box Shortcode', 'fusion-core' ),
	'no_preview' => true,

	// child shortcode is clonable & sortable
	'child_shortcode' => array(
		'params' => array(
			'value' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Counter Value', 'fusion-core' ),
				'desc' => __( 'The number to which the counter will animate.', 'fusion-core')
			),
			'delimiter' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Delimiter Digit', 'fusion-core' ),
				'desc' => __( 'Insert a delimiter digit for better readability. ex: ,', 'fusion-core' ),
			),
			'unit' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Counter Box Unit', 'fusion-core' ),
				'desc' => __( 'Insert a unit for the counter. ex: %', 'fusion-core' ),
			),
			'unitpos' => array(
				'type' => 'select',
				'label' => __( 'Unit Position', 'fusion-core' ),
				'desc' => __( 'Choose the positioning of the unit.', 'fusion-core' ),
				'options' => array(
					'suffix' => __('After Counter', 'fusion-core'),
					'prefix' => __('Before Counter', 'fusion-core'),
				)
			),
			'icon' => array(
				'type' => 'iconpicker',
				'label' => __( 'Icon', 'fusion-core' ),
				'desc' => __( 'Click an icon to select, click again to deselect.', 'fusion-core' ),
				'options' => $icons
			),
			'direction' => array(
				'type' => 'select',
				'label' => __( 'Counter Direction', 'fusion-core' ),
				'desc' => __( 'Choose to count up or down.', 'fusion-core' ),
				'options' => array(
					'up' => __('Count Up', 'fusion-core'),
					'down' => __('Count Down', 'fusion-core'),
				)
			),
			'content' => array(
				'std' => __('Text', 'fusion-core'),
				'type' => 'text',
				'label' => __( 'Counter Box Text', 'fusion-core' ),
				'desc' => __( 'Insert text for counter box', 'fusion-core' ),
			)
		),
		'shortcode' => '[counter_box value="{{value}}" delimiter="{{delimiter}}" unit="{{unit}}" unit_pos="{{unitpos}}" icon="{{icon}}" direction="{{direction}}"]{{content}}[/counter_box]',
		'clone_button' => __( 'Add New Counter Box', 'fusion-core')
	)
);

/*-----------------------------------------------------------------------------------*/
/*	Counters Circle Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['counterscircle'] = array(
	'params' => array(
		'animation_offset' => array(
			'type' 		=> 'select',
			'std' 		=> '',
			'label' 	=> __( 'Offset of Animation', 'fusion-core' ),
			'desc' 		=> __( 'Choose when the animation should start.', 'fusion-core' ),
			'options' 	=> array(
					  			''					=> __( 'Default', 'fusion-core' ),
								'top-into-view' 	=> __( 'Top of element hits bottom of viewport', 'fusion-core' ),
								'top-mid-of-view' 	=> __( 'Top of element hits middle of viewport', 'fusion-core' ),
								'bottom-in-view' 	=> __( 'Bottom of element enters viewport', 'fusion-core' ),
							)
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[counters_circle animation_offset="{{animation_offset}}" class="{{class}}" id="{{id}}"]{{child_shortcode}}[/counters_circle]', // as there is no wrapper shortcode
	'popup_title' => __( 'Counters Circle Shortcode', 'fusion-core' ),
	'no_preview' => true,

	// child shortcode is clonable & sortable
	'child_shortcode' => array(
		'params' => array(
			'value' => array(
				'type' => 'select',
				'label' => __( 'Filled Area Percentage', 'fusion-core' ),
				'desc' => __( 'From 1% to 100%', 'fusion-core' ),
				'options' => fusion_shortcodes_range(100, false)
			),
			'filledcolor' => array(
				'type' => 'colorpicker',
				'label' => __( 'Filled Color', 'fusion-core' ),
				'desc' => __( 'Controls the color of the filled in area. Leave blank for theme option selection.', 'fusion-core')
			),
			'unfilledcolor' => array(
				'type' => 'colorpicker',
				'label' => __( 'Unfilled Color', 'fusion-core' ),
				'desc' => __( 'Controls the color of the unfilled in area. Leave blank for theme option selection.', 'fusion-core')
			),
			'size' => array(
				'std' => '220',
				'type' => 'text',
				'label' => __( 'Size of the Counter', 'fusion-core' ),
				'desc' => __( 'Insert size of the counter in px. ex: 220', 'fusion-core' ),
			),
			'scales' => array(
				'type' => 'select',
				'label' => __( 'Show Scales', 'fusion-core' ),
				'desc' => __( 'Choose to show a scale around circles.', 'fusion-core' ),
				'options' => $reverse_choices
			),
			'countdown' => array(
				'type' => 'select',
				'label' => __( 'Countdown', 'fusion-core' ),
				'desc' => __( 'Choose to let the circle filling move counter clockwise.', 'fusion-core' ),
				'options' => $reverse_choices
			),
			'speed' => array(
				'std' => '1500',
				'type' => 'text',
				'label' => __( 'Animation Speed', 'fusion-core' ),
				'desc' => __( 'Insert animation speed in milliseconds', 'fusion-core' ),
			),
			'content' => array(
				'std' => __('Text', 'fusion-core'),
				'type' => 'text',
				'label' => __( 'Counter Circle Text', 'fusion-core' ),
				'desc' => __( 'Insert text for counter circle box, keep it short', 'fusion-core' ),
			),
		),
		'shortcode' => '[counter_circle filledcolor="{{filledcolor}}" unfilledcolor="{{unfilledcolor}}" size="{{size}}" scales="{{scales}}" countdown="{{countdown}}" speed="{{speed}}" value="{{value}}"]{{content}}[/counter_circle]',
		'clone_button' => __( 'Add New Counter Circle', 'fusion-core')
	)
);

/*-----------------------------------------------------------------------------------*/
/*	Dropcap Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['dropcap'] = array(
	'no_preview' => true,
	'params' => array(
		'content' => array(
			'std' => 'A',
			'type' => 'textarea',
			'label' => __( 'Dropcap Letter', 'fusion-core' ),
			'desc' => __( 'Add the letter to be used as dropcap', 'fusion-core' ),
		),
		'color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Color', 'fusion-core' ),
			'desc' => __( 'Controls the color of the dropcap letter. Leave blank for theme option selection.', 'fusion-core ')
		),
		'boxed' => array(
			'type' => 'select',
			'label' => __( 'Boxed Dropcap', 'fusion-core' ),
			'desc' => __( 'Choose to get a boxed dropcap.', 'fusion-core' ),
			'options' => $reverse_choices
		),
		'boxedradius' => array(
			'std' => '8px',
			'type' => 'text',
			'label' => __( 'Box Radius', 'fusion-core' ),
			'desc' => __('Choose the radius of the boxed dropcap. In pixels (px), ex: 1px, or "round".', 'fusion-core')
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core')
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core')
		),
	),
	'shortcode' => '[dropcap color="{{color}}" boxed="{{boxed}}" boxed_radius="{{boxedradius}}" class="{{class}}" id="{{id}}"]{{content}}[/dropcap]',
	'popup_title' => __( 'Dropcap Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Events Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['events'] = array(
	'params' => array(
		'cat_slug' => array(
			'std' => '',
			'type' => 'select',
			'label' => __( 'Categories', 'fusion-core' ),
			'desc' =>  __( 'Select a category or leave blank for all', 'fusion-core' ),
			'options' => fusion_shortcodes_categories( 'tribe_events_cat', true, 'All' )
		),
		'number_posts' => array(
			'std' => '4',
			'type' => 'text',
			'label' => __( 'Number of Events', 'fusion-core' ),
			'desc' => __('Select the number of events to display', 'fusion-core')
		),
		'columns' => array(
			'std' => 4,
			'type' => 'select',
			'label' => __( 'Number of Columns', 'fusion-core' ),
			'desc' =>  __( 'Select the number of max columns to display.', 'fusion-core' ),
			'options' => fusion_shortcodes_range( 6, false )
		),
		'picture_size' => array(
			'std' => 'cover',
			'type' => 'select',
			'label' => __( 'Picture Size', 'fusion-core' ),
			'desc' => __( 'cover = image will scale to cover the container<br />auto = width and height will adjust to the image.', 'fusion-core'),
			'options' => array(
				'cover' 	=> __( 'Cover', 'fusion-core' ),
				'auto' 		=> __('Auto', 'fusion-core'),
			)
		),
		'picture_size' => array(
			'std' => 'cover',
			'type' => 'select',
			'label' => __( 'Picture Size', 'fusion-core' ),
			'desc' => __( 'cover = image will scale to cover the container<br />auto = width and height will adjust to the image.', 'fusion-core'),
			'options' => array(
				'cover' 	=> __( 'Cover', 'fusion-core' ),
				'auto' 		=> __('Auto', 'fusion-core'),
			)
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core')
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core')
		),
	),
	'shortcode' => '[fusion_events cat_slug="{{cat_slug}}" number_posts="{{number_posts}}" columns="{{columns}}" picture_size="{{picture_size}}" class="{{class}}" id="{{id}}"][/fusion_events]', // as there is no wrapper shortcode
	'popup_title' => __( 'Events Shortcode', 'fusion-core' ),
	'no_preview' => true
);

/*-----------------------------------------------------------------------------------*/
/*	Post Slider Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['postslider'] = array(
	'no_preview' => true,
	'params' => array(

		'type' => array(
			'type' => 'select',
			'label' => __( 'Layout', 'fusion-core' ),
			'desc' => __( 'Choose a layout style for Post Slider.', 'fusion-core' ),
			'options' => array(
				'posts' => __('Posts with Title', 'fusion-core'),
				'posts-with-excerpt' => __('Posts with Title and Excerpt', 'fusion-core'),
				'attachments' => __('Attachment Layout, Only Images Attached to Post/Page', 'fusion-core')
			)
		),
		'excerpt' => array(
			'std' => 35,
			'type' => 'text',
			'label' => __( 'Excerpt Number of Words', 'fusion-core' ),
			'desc' => __( 'Insert the number of words you want to show in the excerpt.', 'fusion-core' ),
		),
		'category' => array(
			'std' => 35,
			'type' => 'select',
			'label' => __( 'Category', 'fusion-core' ),
			'desc' => __( 'Select a category of posts to display.', 'fusion-core' ),
			'options' => fusion_shortcodes_categories( 'category', true, 'All' )
		),
		'limit' => array(
			'std' => 3,
			'type' => 'text',
			'label' => __( 'Number of Slides', 'fusion-core' ),
			'desc' => __( 'Select the number of slides to display.', 'fusion-core' )
		),
		'lightbox' => array(
			'type' => 'select',
			'label' => __( 'Lightbox on Click', 'fusion-core' ),
			'desc' => __( 'Only works on attachment layout.', 'fusion-core' ),
			'options' => $choices
		),
		'image' => array(
			'type' => 'gallery',
			'label' => __( 'Attach Images to Post/Page Gallery', 'fusion-core' ),
			'desc' => __( 'Only works for attachments layout.', 'fusion-core' ),
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core')
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core')
		),
	),
	'shortcode' => '[postslider layout="{{type}}" excerpt="{{excerpt}}" category="{{category}}" limit="{{limit}}" id="" lightbox="{{lightbox}}" class="{{class}}" id="{{id}}"][/postslider]',
	'popup_title' => __( 'Post Slider Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Flip Boxes Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['flipboxes'] = array(
	'params' => array(

		'columns' => array(
			'std' => 4,
			'type' => 'select',
			'label' => __( 'Number of Columns', 'fusion-core' ),
			'desc' =>  __( 'Set the number of columns per row.', 'fusion-core' ),
			'options' => fusion_shortcodes_range( 6, false )
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[flip_boxes columns="{{columns}}" class="{{class}}" id="{{id}}"]{{child_shortcode}}[/flip_boxes]', // as there is no wrapper shortcode
	'popup_title' => __( 'Flip Boxes Shortcode', 'fusion-core' ),
	'no_preview' => true,

	// child shortcode is clonable & sortable
	'child_shortcode' => array(
		'params' => array(
			'titlefront' => array(
				'std' => __('Your Content Goes Here', 'fusion-core'),
				'type' => 'text',
				'label' => __( 'Flip Box Frontside Heading', 'fusion-core' ),
				'desc' => __( 'Add a heading for the frontside of the flip box.', 'fusion-core' ),
			),
			'titleback' => array(
				'std' => __('Your Content Goes Here', 'fusion-core'),
				'type' => 'text',
				'label' => __( 'Flip Box Backside Heading', 'fusion-core' ),
				'desc' => __( 'Add a heading for the backside of the flip box.', 'fusion-core' ),
			),
			'textfront' => array(
				'std' => __('Your Content Goes Here', 'fusion-core'),
				'type' => 'textarea',
				'label' => __( 'Flip Box Frontside Content', 'fusion-core' ),
				'desc' => __( 'Add content for the frontside of the flip box.', 'fusion-core' ),
			),
			'content' => array(
				'std' => __('Your Content Goes Here', 'fusion-core'),
				'type' => 'textarea',
				'label' => __( 'Flip Box Backside Content', 'fusion-core' ),
				'desc' => __( 'Add content for the backside of the flip box.', 'fusion-core' ),
			),
			'backgroundcolorfront' => array(
				'type' => 'colorpicker',
				'label' => __( 'Background Color Frontside', 'fusion-core' ),
				'desc' => __( 'Controls the background color of the frontside. Leave blank for theme option selection. NOTE: flip boxes must have background colors to work correctly in all browsers.', 'fusion-core' )
			),
			'titlecolorfront' => array(
				'type' => 'colorpicker',
				'label' => __( 'Heading Color Frontside', 'fusion-core' ),
				'desc' => __( 'Controls the heading color of the frontside. Leave blank for theme option selection.', 'fusion-core' )
			),
			'textcolorfront' => array(
				'type' => 'colorpicker',
				'label' => __( 'Text Color Frontside', 'fusion-core' ),
				'desc' => __( 'Controls the text color of the frontside. Leave blank for theme option selection.', 'fusion-core' )
			),
			'backgroundcolorback' => array(
				'type' => 'colorpicker',
				'label' => __( 'Background Color Backside', 'fusion-core' ),
				'desc' => __( 'Controls the background color of the backside. Leave blank for theme option selection. NOTE: flip boxes must have background colors to work correctly in all browsers.', 'fusion-core' )
			),
			'titlecolorback' => array(
				'type' => 'colorpicker',
				'label' => __( 'Heading Color Backside', 'fusion-core' ),
				'desc' => __( 'Controls the heading color of the backside. Leave blank for theme option selection.', 'fusion-core' )
			),
			'textcolorback' => array(
				'type' => 'colorpicker',
				'label' => __( 'Text Color Backside', 'fusion-core' ),
				'desc' => __( 'Controls the text color of the backside. Leave blank for theme option selection.', 'fusion-core' )
			),
			'bordersize' => array(
				'std' => '1px',
				'type' => 'text',
				'label' => __( 'Border Size', 'fusion-core' ),
				'desc' => __( 'In pixels (px), ex: 1px. Leave blank for theme option selection.', 'fusion-core' ),
			),
			'bordercolor' => array(
				'type' => 'colorpicker',
				'label' => __( 'Border Color', 'fusion-core' ),
				'desc' => __( 'Controls the border color. Leave blank for theme option selection.', 'fusion-core' )
			),
			'borderradius' => array(
				'std' => '4px',
				'type' => 'text',
				'label' => __( 'BorderRadius', 'fusion-core' ),
				'desc' => __( 'Controls the flip box border radius. In pixels (px), ex: 1px, or "round".  Leave blank for theme option selection.', 'fusion-core' ),
			),
			'icon' => array(
				'type' => 'iconpicker',
				'label' => __( 'Icon', 'fusion-core' ),
				'desc' => __( 'Click an icon to select, click again to deselect.', 'fusion-core' ),
				'options' => $icons
			),
			'iconcolor' => array(
				'type' => 'colorpicker',
				'label' => __( 'Icon Color', 'fusion-core' ),
				'desc' => __( 'Controls the color of the icon. Leave blank for theme option selection.', 'fusion-core' )
			),
			'circle' => array(
				'std' => 0,
				'type' => 'select',
				'label' => __( 'Icon Circle', 'fusion-core' ),
				'desc' => __( 'Choose to use a circled background on the icon.', 'fusion-core' ),
				'options' => $choices
			),
			'circlecolor' => array(
				'type' => 'colorpicker',
				'label' => __( 'Icon Circle Background Color', 'fusion-core' ),
				'desc' => __( 'Controls the color of the circle. Leave blank for theme option selection.', 'fusion-core')
			),
			'circlebordercolor' => array(
				'type' => 'colorpicker',
				'label' => __( 'Icon Circle Border Color', 'fusion-core' ),
				'desc' => __( 'Controls the color of the circle border. Leave blank for theme option selection.', 'fusion-core')
			),
			'iconrotate' => array(
				'type' => 'select',
				'label' => __( 'Rotate Icon', 'fusion-core' ),
				'desc' => __( 'Choose to rotate the icon.', 'fusion-core' ),
				'options' => array(
					''	=> __('None', 'fusion-core'),
					'90' => '90',
					'180' => '180',
					'270' => '270',
				)
			),
			'iconspin' => array(
				'type' => 'select',
				'label' => __( 'Spinning Icon', 'fusion-core' ),
				'desc' => __( 'Choose to let the icon spin.', 'fusion-core' ),
				'options' => $reverse_choices
			),
			'image' => array(
				'type' => 'uploader',
				'label' => __( 'Icon Image', 'fusion-core' ),
				'desc' => __( 'To upload your own icon image, deselect the icon above and then upload your icon image.', 'fusion-core' ),
			),
			'image_width' => array(
				'std' => 35,
				'type' => 'text',
				'label' => __( 'Icon Image Width', 'fusion-core' ),
				'desc' => __( 'If using an icon image, specify the image width in pixels but do not add px, ex: 35.', 'fusion-core' ),
			),
			'image_height' => array(
				'std' => 35,
				'type' => 'text',
				'label' => __( 'Icon Image Height', 'fusion-core' ),
				'desc' => __( 'If using an icon image, specify the image height in pixels but do not add px, ex: 35.', 'fusion-core' ),
			),
			'animation_type' => array(
				'type' => 'select',
				'label' => __( 'Animation Type', 'fusion-core' ),
				'desc' => __( 'Select the type of animation to use on the shortcode', 'fusion-core' ),
				'options' => $animation_type,
			),
			'animation_direction' => array(
				'type' => 'select',
				'label' => __( 'Direction of Animation', 'fusion-core' ),
				'desc' => __( 'Select the incoming direction for the animation', 'fusion-core' ),
				'options' => $animation_direction,
			),
			'animation_speed' => array(
				'type' => 'select',
				'std' => '',
				'label' => __( 'Speed of Animation', 'fusion-core' ),
				'desc' => __( 'Type in speed of animation in seconds (0.1 - 1).', 'fusion-core' ),
				'options' => $dec_numbers,
			),
			'animation_offset' => array(
				'type' 		=> 'select',
				'std' 		=> '',
				'label' 	=> __( 'Offset of Animation', 'fusion-core' ),
				'desc' 		=> __( 'Choose when the animation should start.', 'fusion-core' ),
				'options' 	=> array(
					  				''					=> __( 'Default', 'fusion-core' ),
									'top-into-view' 	=> __( 'Top of element hits bottom of viewport', 'fusion-core' ),
									'top-mid-of-view' 	=> __( 'Top of element hits middle of viewport', 'fusion-core' ),
									'bottom-in-view' 	=> __( 'Bottom of element enters viewport', 'fusion-core' ),
								)
			),
		),
		'shortcode' => '[flip_box title_front="{{titlefront}}" title_back="{{titleback}}" text_front="{{textfront}}" border_color="{{bordercolor}}" border_radius="{{borderradius}}" border_size="{{bordersize}}" background_color_front="{{backgroundcolorfront}}" title_front_color="{{titlecolorfront}}" text_front_color="{{textcolorfront}}" background_color_back="{{backgroundcolorback}}" title_back_color="{{titlecolorback}}" text_back_color="{{textcolorback}}" icon="{{icon}}" icon_color="{{iconcolor}}" circle="{{circle}}" circle_color="{{circlecolor}}" circle_border_color="{{circlebordercolor}}" icon_rotate="{{iconrotate}}" icon_spin="{{iconspin}}" image="{{image}}" image_width="{{image_width}}" image_height="{{image_height}}" animation_type="{{animation_type}}" animation_direction="{{animation_direction}}" animation_speed="{{animation_speed}}" animation_offset="{{animation_offset}}"]{{content}}[/flip_box]',
		'clone_button' => __( 'Add New Flip Box', 'fusion-core')
	)
);


/*-----------------------------------------------------------------------------------*/
/*	FontAwesome Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['fontawesome'] = array(
	'no_preview' => true,
	'params' => array(

		'icon' => array(
			'type' => 'iconpicker',
			'label' => __( 'Select Icon', 'fusion-core' ),
			'desc' => __( 'Click an icon to select, click again to deselect.', 'fusion-core' ),
			'options' => $icons
		),
		'circle' => array(
			'type' => 'select',
			'label' => __( 'Icon in Circle', 'fusion-core' ),
			'desc' => __( 'Choose to display the icon in a circle.', 'fusion-core' ),
			'options' => $choices
		),
		'size' => array(
			'std' => '13px',
			'type' => 'text',
			'label' => __( 'Icon Size', 'fusion-core' ),
			'desc' => __( 'Set the size of the icon. In pixels (px), ex: 13px.', 'fusion-core' ),
		),
		'iconcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Icon Color', 'fusion-core' ),
			'desc' => __( 'Controls the color of the icon. Leave blank for theme option selection.', 'fusion-core')
		),
		'circlecolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Icon Circle Background Color', 'fusion-core' ),
			'desc' => __( 'Controls the color of the circle. Leave blank for theme option selection.', 'fusion-core')
		),
		'circlebordercolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Icon Circle Border Color', 'fusion-core' ),
			'desc' => __( 'Controls the color of the circle border. Leave blank for theme option selection.', 'fusion-core')
		),
		'rotate' => array(
			'type' => 'select',
			'label' => __( 'Rotate Icon', 'fusion-core' ),
			'desc' => __( 'Choose to rotate the icon.', 'fusion-core' ),
			'options' => array(
				''	=> __('None', 'fusion-core'),
				'90' => '90',
				'180' => '180',
				'270' => '270',
			)
		),
		'spin' => array(
			'type' => 'select',
			'label' => __( 'Spinning Icon', 'fusion-core' ),
			'desc' => __( 'Choose to let the icon spin.', 'fusion-core' ),
			'options' => $reverse_choices
		),
		'animation_type' => array(
			'type' => 'select',
			'label' => __( 'Animation Type', 'fusion-core' ),
			'desc' => __( 'Select the type of animation to use on the shortcode', 'fusion-core' ),
			'options' => $animation_type,
		),
		'animation_direction' => array(
			'type' => 'select',
			'label' => __( 'Direction of Animation', 'fusion-core' ),
			'desc' => __( 'Select the incoming direction for the animation', 'fusion-core' ),
			'options' => $animation_direction,
		),
		'animation_speed' => array(
			'type' => 'select',
			'std' => '',
			'label' => __( 'Speed of Animation', 'fusion-core' ),
			'desc' => __( 'Type in speed of animation in seconds (0.1 - 1).', 'fusion-core' ),
			'options' => $dec_numbers,
		),
		'animation_offset' => array(
			'type' 		=> 'select',
			'std' 		=> '',
			'label' 	=> __( 'Offset of Animation', 'fusion-core' ),
			'desc' 		=> __( 'Choose when the animation should start.', 'fusion-core' ),
			'options' 	=> array(
					  			''					=> __( 'Default', 'fusion-core' ),
								'top-into-view' 	=> __( 'Top of element hits bottom of viewport', 'fusion-core' ),
								'top-mid-of-view' 	=> __( 'Top of element hits middle of viewport', 'fusion-core' ),
								'bottom-in-view' 	=> __( 'Bottom of element enters viewport', 'fusion-core' ),
							)
		),
		'alignment' => array(
			'type' => 'select',
			'std' => '',
			'label' => __( 'Alignment', 'fusion-core' ),
			'desc' => __( 'Select the icon\'s alignment.', 'fusion-core' ),
			'options' => array(
				'left' => __('Left', 'fusion-core'),
				'center' => __('Center', 'fusion-core'),
				'right' => __('Right', 'fusion-core'),
			)
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core')
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core')
		),
	),
	'shortcode' => '[fontawesome icon="{{icon}}" circle="{{circle}}" size="{{size}}" iconcolor="{{iconcolor}}" circlecolor="{{circlecolor}}" circlebordercolor="{{circlebordercolor}}" rotate="{{rotate}}" spin="{{spin}}" animation_type="{{animation_type}}" animation_direction="{{animation_direction}}" animation_speed="{{animation_speed}}" animation_offset="{{animation_offset}}" alignment="{{alignment}}" class="{{class}}" id="{{id}}"]',
	'popup_title' => __( 'Font Awesome Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Fullwidth Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['fullwidth'] = array(
	'no_preview' => true,
	'params' => array(
		'background_color' => array(
			'type' => 'colorpicker',
			"group" => __( 'Background', 'fusion-core' ),
			'label' => __( 'Background Color', 'fusion-core' ),
			'desc' => __( 'Controls the background color.  Leave blank for theme option selection.', 'fusion-core')
		),
		'background_image' => array(
			'type' => 'uploader',
			'label' => __( 'Background Image', 'fusion-core' ),
			"group" => __( 'Background', 'fusion-core' ),
			"data"  => array(
				"replace" => "fusion-hidden-img"
			),
			'desc' => __('Upload an image to display in the background', 'fusion-core')
		),
		'background_parallax' => array(
			'type' => 'select',
			'label' => __( 'Background Parallax', 'fusion-core' ),
			'desc' => __( 'Choose how the background image scrolls and responds.', 'fusion-core' ),
			"group"         => __( 'Background', 'fusion-core' ),
			'std' => 'none',
			'options' => array(
				'none'  => __( 'No Parallax (no effects)', 'fusion-core' ),
				'fixed' => __( 'Fixed (fixed on desktop, non-fixed on mobile)', 'fusion-core' ),
				'up'    => __( 'Up (moves up on desktop & mobile)', 'fusion-core' ),
				'down'  => __( 'Down (moves down on desktop & mobile)', 'fusion-core' ),
				'left'  => __( 'Left (moves left on desktop & mobile)', 'fusion-core' ),
				'right' => __( 'Right (moves right on desktop & mobile)', 'fusion-core' ),
				//'hover' => __( 'Hover', 'fusion-core' ),
			)
		),
		'enable_mobile' => array(
			'type' => 'select',
			'label' => __( 'Enable Parallax on Mobile', 'fusion-core' ),
			'desc' => __( 'Works for up/down/left/right only. Parallax effects would most probably cause slowdowns when your site is viewed in mobile devices. If the device width is less than 980 pixels, then it is assumed that the site is being viewed in a mobile device.', 'fusion-core' ),
			"group"         => __( 'Background', 'fusion-core' ),
			'std' => 'no',
			'options' => array(
				'no'  => __( 'No', 'fusion-core' ),
				'yes' => __( 'Yes', 'fusion-core' ),
			)
		),
		'parallax_speed' => array(
			'type' => 'select',
			"group" => __( 'Background', 'fusion-core' ),
			'label' => __( 'Parallax Speed', 'fusion-core' ),
			'desc' => __( 'The movement speed, value should be between 0.1 and 1.0. A lower number means slower scrolling speed. Higher scrolling speeds will enlarge the image more.', 'fusion-core' ),
			'options' => $dec_numbers,
			'std' => '',
		),

		'background_repeat' => array(
			'type' => 'select',
			'label' => __( 'Background Repeat', 'fusion-core' ),
			'desc' => __('Choose how the background image repeats.', 'fusion-core'),
			"group"         => __( 'Background', 'fusion-core' ),
			"std"         => "no-repeat",
			'options' => array(
				'no-repeat' => __('No Repeat', 'fusion-core'),
				'repeat' => __('Repeat Vertically and Horizontally', 'fusion-core'),
				'repeat-x' => __('Repeat Horizontally', 'fusion-core'),
				'repeat-y' => __('Repeat Vertically', 'fusion-core')
			)
		),
		'background_position' => array(
			'type' => 'select',
			'label' => __( 'Background Position', 'fusion-core' ),
			"group"         => __( 'Background', 'fusion-core' ),
			'desc' => __('Choose the postion of the background image', 'fusion-core'),
			"std"         => "left top",
			'options' => array(
				'left top' => __('Left Top', 'fusion-core'),
				'left center' => __('Left Center', 'fusion-core'),
				'left bottom' => __('Left Bottom', 'fusion-core'),
				'right top' => __('Right Top', 'fusion-core'),
				'right center' => __('Right Center', 'fusion-core'),
				'right bottom' => __('Right Bottom', 'fusion-core'),
				'center top' => __('Center Top', 'fusion-core'),
				'center center' => __('Center Center', 'fusion-core'),
				'center bottom' => __('Center Bottom', 'fusion-core')
			)
		),
		'video_url' => array(
			'type' => 'text',
			'label' => __( 'YouTube/Vimeo Video URL or ID', 'fusion-core' ),
			'desc' => __( "Enter the URL to the video or the video ID of your YouTube or Vimeo video you want to use as your background. If your URL isn't showing a video, try inputting the video ID instead. <small>Ads will show up in the video if it has them.</small>", 'fusion-core' ),
			"note"  => __( "Tip: newly uploaded videos may not display right away and might show an error message.", "" ) . '<br />' . __( "Videos will not show up in mobile devices because they handle videos differently. In those cases, please provide a preview background image and that will be shown instead.", 'fusion-core' ),
			"group" => __( 'Background', 'fusion-core' ),
		),
		'video_aspect_ratio' => array(
			'type' => 'text',
			'label' => __( 'Video Aspect Ratio', 'fusion-core' ),
			'desc' => __( "The video will be resized to maintain this aspect ratio, this is to prevent the video from showing any black bars. Enter an aspect ratio here such as: &quot;16:9&quot;, &quot;4:3&quot; or &quot;16:10&quot;. The default is &quot;16:9&quot;", 'fusion-core' ),
			"group" => __( 'Background', 'fusion-core' ),
			'std' => '16:9',
		),
		'video_webm' => array(
			'type' => 'text',
			'label' => __( 'Video WebM Upload', 'fusion-core' ),
			'desc' => __('Video must be in a 16:9 aspect ratio. Add your WebM video file. WebM and MP4 format must be included to render your video with cross browser compatibility. OGV is optional.', 'fusion-core'),
		),
		'video_mp4' => array(
			'type' => 'text',
			'label' => __( 'Video MP4 Upload', 'fusion-core' ),
			'desc' => __('Video must be in a 16:9 aspect ratio. Add your MP4 video file. MP4 and WebM format must be included to render your video with cross browser compatibility. OGV is optional.', 'fusion-core'),
		),
		'video_ogv' => array(
			'type' => 'text',
			'label' => __( 'Video OGV Upload', 'fusion-core' ),
			'desc' => __('Add your OGV video file. This is optional.', 'fusion-core'),
		),
		'video_preview_image' => array(
			'type' => 'uploader',
			'label' => __( 'Video Preview Image', 'fusion-core' ),
			'desc' => __('IMPORTANT: Video backgrounds will not auto play on mobile and tablet devices or older browsers. Instead, you should insert a preview image in this field and it will be seen in place of your video.', 'fusion-core')
		),
		'overlay_color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Video Overlay Color', 'fusion-core' ),
			'desc' => __('Select a color to show over the video as an overlay. Hex color code, <strong>ex: #fff</strong>', 'fusion-core'),
		),
		'overlay_opacity' => array(
			'type' => 'text',
			'label' => __( 'Video Overlay Opacity', 'fusion-core' ),
			'desc' => __('Opacity ranges between 0 (transparent) and 1 (opaque). ex: .4', 'fusion-core'),
			'std' => '0.5'
		),
		'video_mute' => array(
			'type' => 'select',
			'label' => __( 'Mute Video', 'fusion-core' ),
			'desc' => '',
			'std' => 'yes',
			'options' => array(
				'yes' => __('Yes', 'fusion-core'),
				'no' => __('No', 'fusion-core'),
			)
		),
		'video_loop' => array(
			'std' => 'yes',
			'type' => 'select',
			'label' => __( 'Loop Video', 'fusion-core' ),
			'desc' => '',
			'options' => array(
				'yes' => __('Yes', 'fusion-core'),
				'no' => __('No', 'fusion-core'),
			)
		),
		'fade' => array(
			'type' => 'select',
			'label' => __( 'Fading Animation', 'fusion-core' ),
			'desc' => __('Choose to have the background image fade and blur on scroll. WARNING: Only works for images. This will cause video backgrounds to not display. ', 'fusion-core'),
			'options' => array(
				'no' => __('No', 'fusion-core'),
				'yes' => __('Yes', 'fusion-core')
			)
		),
		'border_size' => array(
			'std' => '0px',
			'type' => 'text',
			'label' => __( 'Border Size', 'fusion-core' ),
			'desc' => __( 'In pixels (px), ex: 1px. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'border_color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Border Color', 'fusion-core' ),
			'desc' => __( 'Controls the border color.  Leave blank for theme option selection.', 'fusion-core')
		),
		'border_style' => array(
			'type' => 'select',
			'label' => __( 'Border Style', 'fusion-core' ),
			'desc' => __( 'Controls the border style.', 'fusion-core' ),
			'std' => 'solid',
			'options' => array(
				'solid' => __('Solid', 'fusion-core'),
				'dashed' => __('Dashed', 'fusion-core'),
				'dotted' => __('Dotted', 'fusion-core')
			)
		),
		'padding_top' => array(
			'std' => 20,
			'type' => 'text',
			'label' => __( 'Padding Top', 'fusion-core' ),
			'desc' => __( 'In pixels or percentage, ex: 10px or 10%.', 'fusion-core' )
		),
		'padding_bottom' => array(
			'std' => 20,
			'type' => 'text',
			'label' => __( 'Padding Bottom', 'fusion-core' ),
			'desc' => __( 'In pixels or percentage, ex: 10px or 10%.', 'fusion-core' )
		),
		'padding_left' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Padding Left', 'fusion-core' ),
			'desc' => __( 'In pixels or percentage, ex: 10px or 10%.', 'fusion-core' )
		),
		'padding_right' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Padding Right', 'fusion-core' ),
			'desc' => __( 'In pixels or percentage, ex: 10px or 10%.', 'fusion-core' )
		),
		'menu_anchor' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Name Of Menu Anchor', 'fusion-core' ),
			'desc' => __('This name will be the id you will have to use in your one page menu.', 'fusion-core'),
		),
		'equal_height_columns' => array(
			'type' => 'select',
			'label' => __( 'Set Columns to Equal Height', 'fusion-core' ),
			'desc' => __('Select to set all column shortcodes that are used inside the container to have equal height.', 'fusion-core'),
			'options' => array(
				'no' => __('No', 'fusion-core'),
				'yes' => __('Yes', 'fusion-core'),
			)
		),
		'hundred_percent' => array(
			'type' => 'select',
			'label' => __( '100% Interior Content Width', 'fusion-core' ),
			'desc' => __('Select if the interior content is contained to site width or 100% width.', 'fusion-core'),
			'options' => array(
				'no' => __('No', 'fusion-core'),
				'yes' => __('Yes', 'fusion-core'),
			)
		),
		'content' => array(
			'std' => __('Your Content Goes Here', 'fusion-core'),
			'type' => 'textarea',
			'label' => __( 'Content', 'fusion-core' ),
			'desc' => __( 'Add content', 'fusion-core' ),
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core')
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core')
		),
	),
	'shortcode' => '[fullwidth background_color="{{background_color}}" background_image="{{background_image}}" background_parallax="{{background_parallax}}" parallax_speed="{{parallax_speed}}" enable_mobile="{{enable_mobile}}" background_repeat="{{background_repeat}}" background_position="{{background_position}}" video_url="{{video_url}}" video_aspect_ratio="{{video_aspect_ratio}}" video_webm="{{video_webm}}" video_mp4="{{video_mp4}}" video_ogv="{{video_ogv}}" video_preview_image="{{video_preview_image}}" overlay_color="{{overlay_color}}" overlay_opacity="{{overlay_opacity}}" video_mute="{{video_mute}}" video_loop="{{video_loop}}" fade="{{fade}}" border_size="{{border_size}}" border_color="{{border_color}}" border_style="{{border_style}}" padding_top="{{padding_top}}" padding_bottom="{{padding_bottom}}" padding_left="{{padding_left}}" padding_right="{{padding_right}}" hundred_percent="{{hundred_percent}}" equal_height_columns="{{equal_height_columns}}" menu_anchor="{{menu_anchor}}" class="{{class}}" id="{{id}}"]{{content}}[/fullwidth]',
	'popup_title' => __( 'Fullwidth Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Google Map Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['googlemap'] = array(
	'no_preview' => true,
	'params' => array(
		'type' => array(
			'type' => 'select',
			'label' => __( 'Map Type', 'fusion-core' ),
			'desc' => __( 'Select the type of google map to display.', 'fusion-core' ),
			'options' => array(
				'roadmap' => __('Roadmap', 'fusion-core'),
				'satellite' => __('Satellite', 'fusion-core'),
				'hybrid' => __('Hybrid', 'fusion-core'),
				'terrain' => __('Terrain', 'fusion-core')
			)
		),
		'width' => array(
			'std' => '100%',
			'type' => 'text',
			'label' => __( 'Map Width', 'fusion-core' ),
			'desc' => __( 'Map width in percentage or pixels. ex: 100%, or 940px.', 'fusion-core')
		),
		'height' => array(
			'std' => '300px',
			'type' => 'text',
			'label' => __( 'Map Height', 'fusion-core' ),
			'desc' => __( 'Map height in pixels. ex: 300px', 'fusion-core')
		),
		'zoom' => array(
			'std' => 14,
			'type' => 'select',
			'label' => __( 'Zoom Level', 'fusion-core' ),
			'desc' => __( 'Higher number will be more zoomed in.', 'fusion-core' ),
			'options' => fusion_shortcodes_range( 25, false )
		),
		'scrollwheel' => array(
			'type' => 'select',
			'label' => __( 'Scrollwheel on Map', 'fusion-core' ),
			'desc' => __( 'Enable zooming using a mouse\'s scroll wheel.', 'fusion-core' ),
			'options' => $choices
		),
		'scale' => array(
			'type' => 'select',
			'label' => __( 'Show Scale Control on Map', 'fusion-core' ),
			'desc' => __( 'Display the map scale.', 'fusion-core' ),
			'options' => $choices
		),
		'zoom_pancontrol' => array(
			'type' => 'select',
			'label' => __( 'Show Pan Control on Map', 'fusion-core' ),
			'desc' => __( 'Displays pan control button.', 'fusion-core' ),
			'options' => $choices
		),
		'animation' => array(
			'type' => 'select',
			'label' => __( 'Address Pin Animation', 'fusion-core' ),
			'desc' => __( 'Choose to animate the address pins when the map first loads.', 'fusion-core' ),
			'options' => $choices
		),
		'popup' => array(
			'type' => 'select',
			'label' => __( 'Show tooltip by default', 'fusion-core' ),
			'desc' => __( 'Display or hide the tooltip when the map first loads.', 'fusion-core' ),
			'options' => $choices
		),
		'mapstyle' => array(
			'type' => 'select',
			'label' => __( 'Select the Map Styling', 'fusion-core' ),
			'desc' => __( 'Choose default styling for classic google map styles. Choose theme styling for our custom style. Choose custom styling to make your own with the advanced options below.', 'fusion-core' ),
			'options' => array(
				'default' => __('Default Styling', 'fusion-core'),
				'theme' => __('Theme Styling', 'fusion-core'),
				'custom' => __('Custom Styling', 'fusion-core'),
			)
		),
		'overlaycolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Map Overlay Color', 'fusion-core' ),
			'desc' => __( 'Custom styling setting only. Pick an overlaying color for the map. Works best with "roadmap" type.', 'fusion-core')
		),
		'infobox' => array(
			'type' => 'select',
			'label' => __( 'Infobox Styling', 'fusion-core' ),
			'desc' => __( 'Custom styling setting only. Choose between default or custom info box.', 'fusion-core' ),
			'options' => array(
				'default' => __('Default Infobox', 'fusion-core'),
				'custom' => __('Custom Infobox', 'fusion-core'),
			)
		),
		'infoboxcontent' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __( 'Infobox Content', 'fusion-core' ),
			'desc' => __( 'Custom styling setting only. Type in custom info box content to replace address string. For multiple addresses, separate info box contents by using the | symbol. ex: InfoBox 1|InfoBox 2|InfoBox 3.', 'fusion-core' ),
		),
		'infoboxtextcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Info Box Text Color', 'fusion-core' ),
			'desc' => __( 'Custom styling setting only. Pick a color for the info box text.', 'fusion-core')
		),
		'infoboxbackgroundcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Info Box Background Color', 'fusion-core' ),
			'desc' => __( 'Custom styling setting only. Pick a color for the info box background.', 'fusion-core')
		),
		'icon' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __( 'Custom Marker Icon', 'fusion-core' ),
			'desc' => __( 'Custom styling setting only. Use full image urls for custom marker icons or input "theme" for our custom marker. For multiple addresses, separate icons by using the | symbol or use one for all. ex: Icon 1|Icon 2|Icon 3', 'fusion-core' ),
		),
		'content' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __( 'Address', 'fusion-core' ),
			'desc' => __( 'Add your address to the location you wish to show on the map. If the location is off, please try to use long/lat coordinates with latlng=. ex: latlng=12.381068,-1.492711. For multiple addresses, separate addresses by using the | symbol. ex: Address 1|Address 2|Address 3', 'fusion-core' ),
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' ),
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' ),
		)
	),
	'shortcode' => '[map address="{{content}}" type="{{type}}" map_style="{{mapstyle}}" overlay_color="{{overlaycolor}}" infobox="{{infobox}}" infobox_background_color="{{infoboxbackgroundcolor}}" infobox_text_color="{{infoboxtextcolor}}" infobox_content="{{infoboxcontent}}" icon="{{icon}}" width="{{width}}" height="{{height}}" zoom="{{zoom}}" scrollwheel="{{scrollwheel}}" scale="{{scale}}" zoom_pancontrol="{{zoom_pancontrol}}" popup="{{popup}}" animation="{{animation}}" class="{{class}}" id="{{id}}"][/map]',
	'popup_title' => __( 'Google Map Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Highlight Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['highlight'] = array(
	'no_preview' => true,
	'params' => array(

		'color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Highlight Color', 'fusion-core' ),
			'desc' => __( 'Pick a highlight color', 'fusion-core')
		),
		'rounded' => array(
			'type' => 'select',
			'label' => __( 'Highlight With Round Edges', 'fusion-core' ),
			'desc' => __( 'Choose to have rounded edges.', 'fusion-core' ),
			'options' => $reverse_choices
		),
		'content' => array(
			'std' => __('Your Content Goes Here', 'fusion-core'),
			'type' => 'textarea',
			'label' => __( 'Content to Higlight', 'fusion-core' ),
			'desc' => __( 'Add your content to be highlighted', 'fusion-core' ),
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core')
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core')
		),

	),
	'shortcode' => '[highlight color="{{color}}" rounded="{{rounded}}" class="{{class}}" id="{{id}}"]{{content}}[/highlight]',
	'popup_title' => __( 'Highlight Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Image Carousel Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['imagecarousel'] = array(
	'params' => array(
		'picture_size' => array(
			'type' => 'select',
			'label' => __( 'Picture Size', 'fusion-core' ),
			'desc' => __( 'fixed = width and height will be fixed <br />auto = width and height will adjust to the image.', 'fusion-core' ),
			'options' => array(
				'fixed' => __('Fixed', 'fusion-core'),
				'auto' => __('Auto', 'fusion-core')
			)
		),
		'hover_type' => array(
			'std' => 'none',
			'type' => 'select',
			'label' => __( 'Hover Type', 'fusion-core' ),
			'desc' => __('Select the hover effect type.', 'fusion-core'),
			'options' => array(
				'none' => __('None', 'fusion-core'),
				'zoomin' => __('Zoom In', 'fusion-core'),
				'zoomout' => __('Zoom Out', 'fusion-core'),
				'liftup' => __('Lift Up', 'fusion-core')
			)
		),
		'autoplay' => array(
			'type' => 'select',
			'label' => __( 'Autoplay', 'fusion-core' ),
			'desc' => __('Choose to autoplay the carousel.', 'fusion-core'),
			'options' => $reverse_choices
		),
		'columns' => array(
			'type' => 'select',
			'label' => __( 'Maximum Columns', 'fusion-core' ),
			'desc' => __('Select the number of max columns to display.', 'fusion-core'),
			'options' => fusion_shortcodes_range( 6, false )
		),
		'column_spacing' => array(
			'std' => '13',
			'type' => 'text',
			'label' => __( 'Column Spacing', 'fusion-core' ),
			"desc" => __("Insert the amount of spacing between items without 'px'. ex: 13.", "fusion-core"),
		),
		'scroll_items' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Scroll Items', 'fusion-core' ),
			"desc" => __("Insert the amount of items to scroll. Leave empty to scroll number of visible items.", "fusion-core"),
		),
		'show_nav' => array(
			'type' => 'select',
			'label' => __( 'Show Navigation', 'fusion-core' ),
			'desc' => __( 'Choose to show navigation buttons on the carousel.', 'fusion-core' ),
			'options' => $choices
		),
		'mouse_scroll' => array(
			'type' => 'select',
			'label' => __( 'Mouse Scroll', 'fusion-core' ),
			'desc' => __( 'Choose to enable mouse drag control on the carousel. IMPORTANT: For easy draggability, when mouse scroll is activated, links will be disabled.', 'fusion-core' ),
			'options' => $reverse_choices
		),
		'border' => array(
			'type' => 'select',
			'label' => __( 'Border', 'fusion-core' ),
			'desc' => __( 'Choose to enable a border around the images.', 'fusion-core' ),
			'options' => $choices
		),
		'lightbox' => array(
			'type' => 'select',
			'label' => __( 'Image lightbox', 'fusion-core' ),
			'desc' => __( 'Show image in lightbox.', 'fusion-core' ),
			'options' => $choices
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[images picture_size="{{picture_size}}" hover_type="{{hover_type}}" autoplay="{{autoplay}}" columns="{{columns}}" column_spacing="{{column_spacing}}" scroll_items="{{scroll_items}}" show_nav="{{show_nav}}" mouse_scroll="{{mouse_scroll}}" border="{{border}}" lightbox="{{lightbox}}" class="{{class}}" id="{{id}}"]{{child_shortcode}}[/images]', // as there is no wrapper shortcode
	'popup_title' => __( 'Image Carousel Shortcode', 'fusion-core' ),
	'no_preview' => true,

	// child shortcode is clonable & sortable
	'child_shortcode' => array(
		'params' => array(
			'link' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Image Website Link', 'fusion-core' ),
				'desc' => __( 'Add the url to image\'s website. If lightbox option is enabled, you have to add the full image link to show it in the lightbox.', 'fusion-core' )
			),
			'target' => array(
				'type' => 'select',
				'label' => __( 'Link Target', 'fusion-core' ),
				'desc' => __( '_self = open in same window <br />_blank = open in new window', 'fusion-core' ),
				'options' => array(
					'_self' => '_self',
					'_blank' => '_blank'
				)
			),
			'image' => array(
				'type' => 'uploader',
				'label' => __( 'Image', 'fusion-core' ),
				'desc' => __( 'Upload an image to display.', 'fusion-core' ),
			),
			'alt' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Image Alt Text', 'fusion-core' ),
				'desc' => __( 'The alt attribute provides alternative information if an image cannot be viewed.', 'fusion-core' ),
			)
		),
		'shortcode' => '[image link="{{link}}" linktarget="{{target}}" image="{{image}}" alt="{{alt}}"]',
		'clone_button' => __( 'Add New Image', 'fusion-core' )
	)
);

/*-----------------------------------------------------------------------------------*/
/*	Image Frame Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['imageframe'] = array(
	'no_preview' => true,
	'params' => array(
		'style_type' => array(
			'type' => 'select',
			'label' => __( 'Frame Style Type', 'fusion-core' ),
			'desc' => __( 'Select the frame style type.', 'fusion-core' ),
			'options' => array(
				'none' => __('None', 'fusion-core'),
				'glow' => __('Glow', 'fusion-core'),
				'dropshadow' => __('Drop Shadow', 'fusion-core'),
				'bottomshadow' => __('Bottom Shadow', 'fusion-core')
			)
		),
		'hover_type' => array(
			'std' => 'none',
			'type' => 'select',
			'label' => __( 'Hover Type', 'fusion-core' ),
			'desc' => __('Select the hover effect type.', 'fusion-core'),
			'options' => array(
				'none' => __('None', 'fusion-core'),
				'zoomin' => __('Zoom In', 'fusion-core'),
				'zoomout' => __('Zoom Out', 'fusion-core'),
				'liftup' => __('Lift Up', 'fusion-core')
			)
		),
		'bordercolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Border Color', 'fusion-core' ),
			'desc' => __( 'Controls the border color. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'bordersize' => array(
			'std' => '0px',
			'type' => 'text',
			'label' => __( 'Border Size', 'fusion-core' ),
			'desc' => __( 'In pixels (px), ex: 1px. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'borderradius' => array(
			'std' => '0',
			'type' => 'text',
			'label' => __( 'Border Radius', 'fusion-core' ),
			'desc' => __( 'Choose the radius of the image. In pixels (px), ex: 1px, or "round".  Leave blank for theme option selection.', 'fusion-core' ),
		),
		'stylecolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Style Color', 'fusion-core' ),
			'desc' => __( 'For all style types except border. Controls the style color. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'align' => array(
			'std' => 'none',
			'type' => 'select',
			'label' => __( 'Align', 'fusion-core' ),
			'desc' => __('Choose how to align the image.', 'fusion-core'),
			'options' => array(
				'none' => __('None', 'fusion-core'),
				'left' => __('Left', 'fusion-core'),
				'right' => __('Right', 'fusion-core'),
				'center' => __('Center', 'fusion-core')
			)
		),
		'lightbox' => array(
			'type' => 'select',
			'label' => __( 'Image lightbox', 'fusion-core' ),
			'desc' => __( 'Show image in Lightbox.', 'fusion-core' ),
			'options' => $reverse_choices
		),
		'gallery_id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Gallery ID', 'fusion-core' ),
			'desc' => __('Set a name for the lightbox gallery this image frame should belong to.', 'fusion-core')
		),
		'lightbox_image' => array(
			'type' => 'uploader',
			'label' => __( 'Lightbox Image', 'fusion-core' ),
			'desc' => __( 'Upload an image that will show up in the lightbox.', 'fusion-core' ),
		),
		'image' => array(
			'type' => 'uploader',
			'label' => __( 'Image', 'fusion-core' ),
			'desc' => __('Upload an image to display in the frame.', 'fusion-core')
		),
		'alt' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Image Alt Text', 'fusion-core' ),
			'desc' => __('The alt attribute provides alternative information if an image cannot be viewed.', 'fusion-core')
		),
		'link' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Picture Link URL', 'fusion-core' ),
			'desc' => __( 'Add the URL the picture will link to, ex: http://example.com.', 'fusion-core' ),
		),
		'target' => array(
			'type' => 'select',
			'label' => __( 'Link Target', 'fusion-core' ),
			'desc' => __( '_self = open in same window <br /> _blank = open in new window.', 'fusion-core' ),
			'options' => array(
				'_self' => '_self',
				'_blank' => '_blank'
			)
		),
		'animation_type' => array(
			'type' => 'select',
			'label' => __( 'Animation Type', 'fusion-core' ),
			'desc' => __( 'Select the type of animation to use on the shortcode.', 'fusion-core' ),
			'options' => $animation_type,
		),
		'animation_direction' => array(
			'type' => 'select',
			'label' => __( 'Direction of Animation', 'fusion-core' ),
			'desc' => __( 'Select the incoming direction for the animation.', 'fusion-core' ),
			'options' => $animation_direction,
		),
		'animation_speed' => array(
			'type' => 'select',
			'std' => '',
			'label' => __( 'Speed of Animation', 'fusion-core' ),
			'desc' => __( 'Type in speed of animation in seconds (0.1 - 1).', 'fusion-core' ),
			'options' => $dec_numbers,
		),
		'animation_offset' => array(
			'type' 		=> 'select',
			'std' 		=> '',
			'label' 	=> __( 'Offset of Animation', 'fusion-core' ),
			'desc' 		=> __( 'Choose when the animation should start.', 'fusion-core' ),
			'options' 	=> array(
					  			''					=> __( 'Default', 'fusion-core' ),
								'top-into-view' 	=> __( 'Top of element hits bottom of viewport', 'fusion-core' ),
								'top-mid-of-view' 	=> __( 'Top of element hits middle of viewport', 'fusion-core' ),
								'bottom-in-view' 	=> __( 'Bottom of element enters viewport', 'fusion-core' ),
							)
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core')
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core')
		),
	),
	'shortcode' => '[imageframe lightbox="{{lightbox}}" gallery_id="{{gallery_id}}" lightbox_image="{{lightbox_image}}" style_type="{{style_type}}" hover_type="{{hover_type}}" bordercolor="{{bordercolor}}" bordersize="{{bordersize}}" borderradius="{{borderradius}}" stylecolor="{{stylecolor}}" align="{{align}}" link="{{link}}" linktarget="{{target}}" animation_type="{{animation_type}}" animation_direction="{{animation_direction}}" animation_speed="{{animation_speed}}" animation_offset="{{animation_offset}}" class="{{class}}" id="{{id}}"]&lt;img alt="{{alt}}" src="{{image}}" /&gt;[/imageframe]',
	'popup_title' => __( 'Image Frame Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Lightbox Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['lightbox'] = array(
	'no_preview' => true,
	'params' => array(

		'full_image' => array(
			'type' => 'uploader',
			'label' => __( 'Full Image', 'fusion-core' ),
			'desc' => __( 'Upload an image that will show up in the lightbox.', 'fusion-core' ),
		),
		'thumb_image' => array(
			'type' => 'uploader',
			'label' => __( 'Thumbnail Image', 'fusion-core' ),
			'desc' => __( 'Clicking this image will show lightbox.', 'fusion-core' ),
		),
		'alt' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Alt Text', 'fusion-core' ),
			'desc' => __( 'The alt attribute provides alternative information if an image cannot be viewed.', 'fusion-core' ),
		),
		'title' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Lightbox Description', 'fusion-core' ),
			'desc' => __( 'This will show up in the lightbox as a description below the image.', 'fusion-core' ),
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[fusion_lightbox] &lt;a title="{{title}}" class="{{class}}" id="{{id}}" href="{{full_image}}" data-rel="prettyPhoto"&gt;&lt;img alt="{{alt}}" src="{{thumb_image}}" /&gt;&lt;/a&gt; [/fusion_lightbox]',
	'popup_title' => __( 'Lightbox Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Login Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['fusion_login'] = array(
	'no_preview' => true,
	'params' => array(

		'text_align' => array(
			'type' => 'select',
			'label' => __( 'Text Align', 'fusion-core' ),
			'desc' => __( 'Choose the alignment of all content parts. "Text Flow" follows the default text align of the site. "Center" will center all elements.', 'fusion-core' ),
			'options' => array(
				''				=> __( 'Default', 'fusion-core' ),
				'textflow'		=> __( 'Text Flow', 'fusion-core' ),
				'center' 		=> __( 'Center', 'fusion-core' )
			)
		),
		'heading' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Heading', 'fusion-core' ),
			'desc' => __( 'Choose a heading text.', 'fusion-core' ),
		),
		'caption' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Caption', 'fusion-core' ),
			'desc' => __( 'Choose a caption text.', 'fusion-core' ),
		),
		'button_fullwidth' => array(
			'type' => 'select',
			'label' => __( 'Button Span', 'fusion-core' ),
			'desc' => __( 'Choose to have the button span the full width.', 'fusion-core' ),
			'options' => $choices_with_default
		),
		'form_background_color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Form Backgound Color', 'fusion-core' ),
			'desc' => __( 'Choose a background color for the form wrapping box.', 'fusion-core' ),
		),
		'redirection_link' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Redirection Link', 'fusion-core' ),
			'desc' => __( 'Add the url to which a user should redirected after form submission. Leave empty to use the same page.', 'fusion-core' ),
		),
		'register_link' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Register Link', 'fusion-core' ),
			'desc' => __( 'Add the url the "Register" link should open.', 'fusion-core' ),
		),
		'lost_password_link' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Lost Password Link', 'fusion-core' ),
			'desc' => __( 'Add the url the "Lost Password" link should open.', 'fusion-core' ),
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[fusion_login text_align="{{text_align}}" heading="{{heading}}" caption="{{caption}}" button_fullwidth="{{button_fullwidth}}" form_background_color="{{form_background_color}}" redirection_link="{{redirection_link}}" register_link="{{register_link}}" lost_password_link="{{lost_password_link}}" class="{{class}}" id="{{id}}"]',
	'popup_title' => __( 'Login Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Register Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['fusion_register'] = array(
	'no_preview' => true,
	'params' => array(

		'text_align' => array(
			'type' => 'select',
			'label' => __( 'Text Align', 'fusion-core' ),
			'desc' => __( 'Choose the alignment of all content parts. "Text Flow" follows the default text align of the site. "Center" will center all elements.', 'fusion-core' ),
			'options' => array(
				''				=> __( 'Default', 'fusion-core' ),
				'textflow'		=> __( 'Text Flow', 'fusion-core' ),
				'center' 		=> __( 'Center', 'fusion-core' )
			)
		),
		'heading' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Heading', 'fusion-core' ),
			'desc' => __( 'Choose a heading text.', 'fusion-core' ),
		),
		'caption' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Caption', 'fusion-core' ),
			'desc' => __( 'Choose a caption text.', 'fusion-core' ),
		),
		'button_fullwidth' => array(
			'type' => 'select',
			'label' => __( 'Button Span', 'fusion-core' ),
			'desc' => __( 'Choose to have the button span the full width.', 'fusion-core' ),
			'options' => $choices_with_default
		),
		'form_background_color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Form Backgound Color', 'fusion-core' ),
			'desc' => __( 'Choose a background color for the form wrapping box.', 'fusion-core' ),
		),
		'redirection_link' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Redirection Link', 'fusion-core' ),
			'desc' => __( 'Add the url to which a user should redirected after form submission. Leave empty to use the same page.', 'fusion-core' ),
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[fusion_register text_align="{{text_align}}" heading="{{heading}}" caption="{{caption}}" button_fullwidth="{{button_fullwidth}}" form_background_color="{{form_background_color}}" redirection_link="{{redirection_link}}" register_link="{{register_link}}" lost_password_link="{{lost_password_link}}" class="{{class}}" id="{{id}}"]',
	'popup_title' => __( 'Register Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Lost Password Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['fusion_lost_password'] = array(
	'no_preview' => true,
	'params' => array(

		'text_align' => array(
			'type' => 'select',
			'label' => __( 'Text Align', 'fusion-core' ),
			'desc' => __( 'Choose the alignment of all content parts. "Text Flow" follows the default text align of the site. "Center" will center all elements.', 'fusion-core' ),
			'options' => array(
				''				=> __( 'Default', 'fusion-core' ),
				'textflow'		=> __( 'Text Flow', 'fusion-core' ),
				'center' 		=> __( 'Center', 'fusion-core' )
			)
		),
		'heading' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Heading', 'fusion-core' ),
			'desc' => __( 'Choose a heading text.', 'fusion-core' ),
		),
		'caption' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Caption', 'fusion-core' ),
			'desc' => __( 'Choose a caption text.', 'fusion-core' ),
		),
		'button_fullwidth' => array(
			'type' => 'select',
			'label' => __( 'Button Span', 'fusion-core' ),
			'desc' => __( 'Choose to have the button span the full width.', 'fusion-core' ),
			'options' => $choices_with_default
		),
		'form_background_color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Form Backgound Color', 'fusion-core' ),
			'desc' => __( 'Choose a background color for the form wrapping box.', 'fusion-core' ),
		),
		'redirection_link' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Redirection Link', 'fusion-core' ),
			'desc' => __( 'Add the url to which a user should redirected after form submission. Leave empty to use the same page.', 'fusion-core' ),
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[fusion_lost_password text_align="{{text_align}}" heading="{{heading}}" caption="{{caption}}" button_fullwidth="{{button_fullwidth}}" form_background_color="{{form_background_color}}" redirection_link="{{redirection_link}}" register_link="{{register_link}}" lost_password_link="{{lost_password_link}}" class="{{class}}" id="{{id}}"]',
	'popup_title' => __( 'Lost Password Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Menu Anchor Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['menuanchor'] = array(
	'no_preview' => true,
	'params' => array(

		'name' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Name Of Menu Anchor', 'fusion-core' ),
			'desc' => __('This name will be the id you will have to use in your one page menu.', 'fusion-core'),

		)
	),
	'shortcode' => '[menu_anchor name="{{name}}"]',
	'popup_title' => __( 'Menu Anchor Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Modal Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['modal'] = array(
	'no_preview' => true,
	'params' => array(

		'name' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Name Of Modal', 'fusion-core' ),
			'desc' => __( 'Needs to be a unique identifier (lowercase), used for button or modal_text_link shortcode to open the modal. ex: mymodal', 'fusion-core' ),
		),
		'title' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Modal Heading', 'fusion-core' ),
			'desc' => __( 'Heading text for the modal.', 'fusion-core' ),
		),
		'size' => array(
			'type' => 'select',
			'label' => __( 'Size Of Modal', 'fusion-core' ),
			'desc' => __( 'Select the modal window size.', 'fusion-core' ),
			'options' => array(
				'small' => __('Small', 'fusion-core'),
				'large' => __('Large', 'fusion-core')
			)
		),
		'background' => array(
			'type' => 'colorpicker',
			'label' => __( 'Background Color', 'fusion-core' ),
			'desc' => __( 'Controls the modal background color. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'bordercolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Border Color', 'fusion-core' ),
			'desc' => __( 'Controls the modal border color. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'showfooter' => array(
			'type' => 'select',
			'label' => __( 'Show Footer', 'fusion-core' ),
			'desc' => __( 'Choose to show the modal footer with close button.', 'fusion-core' ),
			'options' => $choices
		),
		'content' => array(
			'std' => __('Your Content Goes Here', 'fusion-core'),
			'type' => 'textarea',
			'label' => __( 'Contents of Modal', 'fusion-core' ),
			'desc' => __( 'Add your content to be displayed in modal.', 'fusion-core' ),
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[modal name="{{name}}" title="{{title}}" size="{{size}}" background="{{background}}" border_color="{{bordercolor}}" show_footer="{{showfooter}}" class="{{class}}" id="{{id}}"]{{content}}[/modal]',
	'popup_title' => __( 'Modal Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Modal Text Link Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['modaltextlink'] = array(
	'no_preview' => true,
	'params' => array(
		'modal' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Name Of Modal', 'fusion-core' ),
			'desc' => __('Unique identifier of the modal to open on click.', 'fusion-core'),
		),
		'content' => array(
			'std' => __('Your Content Goes Here', 'fusion-core'),
			'type' => 'textarea',
			'label' => __( 'Text or HTML code', 'fusion-core' ),
			'desc' => __( 'Insert text or HTML code here (e.g: HTML for image). This content will be used to trigger the modal popup.', 'fusion-core' ),
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[modal_text_link name="{{modal}}" class="{{class}}" id="{{id}}"]{{content}}[/modal_text_link]',
	'popup_title' => __( 'Modal Text Link Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	One Page Text Link Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['onepagetextlink'] = array(
	'no_preview' => true,
	'params' => array(
		'link' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Name Of Anchor', 'fusion-core' ),
			'desc' => __('Unique identifier of the anchor to scroll to on click.', 'fusion-core'),
		),
		'content' => array(
			'std' => __('Your Content Goes Here', 'fusion-core'),
			'type' => 'textarea',
			'label' => __( 'Text or HTML code', 'fusion-core' ),
			'desc' => __( 'Insert text or HTML code here (e.g: HTML for image). This content will be used to trigger the scrolling to the anchor.', 'fusion-core' ),
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[one_page_text_link link="{{link}}" class="{{class}}" id="{{id}}"]{{content}}[/one_page_text_link]',
	'popup_title' => __( 'One Page Text Link Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Person Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['person'] = array(
	'no_preview' => true,
	'params' => array(
		'name' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Name', 'fusion-core' ),
			'desc' => __( 'Insert the name of the person.', 'fusion-core' ),
		),
		'title' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Title', 'fusion-core' ),
			'desc' => __( 'Insert the title of the person', 'fusion-core' ),
		),
		'content' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __( 'Profile Description', 'fusion-core' ),
			'desc' => __( 'Enter the content to be displayed', 'fusion-core' )
		),
		'picture' => array(
			'type' => 'uploader',
			'label' => __( 'Picture', 'fusion-core' ),
			'desc' => __( 'Upload an image to display.', 'fusion-core' ),
		),
		'piclink' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Picture Link URL', 'fusion-core' ),
			'desc' => __( 'Add the URL the picture will link to, ex: http://example.com.', 'fusion-core' ),
		),
		'target' => array(
			'type' => 'select',
			'label' => __( 'Link Target', 'fusion-core' ),
			'desc' => __( '_self = open in same window <br /> _blank = open in new window', 'fusion-core' ),
			'options' => array(
				'_self' => '_self',
				'_blank' => '_blank'
			)
		),
		'picstyle' => array(
			'type' => 'select',
			'label' => __( 'Picture Style Type', 'fusion-core' ),
			'desc' => __( 'Selected the style type for the picture,', 'fusion-core' ),
			'options' => array(
				'none' => __('None', 'fusion-core'),
				'glow' => __('Glow', 'fusion-core'),
				'dropshadow' => __('Drop Shadow', 'fusion-core'),
				'bottomshadow' => __('Bottom Shadow', 'fusion-core')
			)
		),
		'hover_type' => array(
			'std' => 'none',
			'type' => 'select',
			'label' => __( 'Hover Type', 'fusion-core' ),
			'desc' => __('Select the hover effect type.', 'fusion-core'),
			'options' => array(
				'none' => __('None', 'fusion-core'),
				'zoomin' => __('Zoom In', 'fusion-core'),
				'zoomout' => __('Zoom Out', 'fusion-core'),
				'liftup' => __('Lift Up', 'fusion-core')
			)
		),
		'background_color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Background Color', 'fusion-core' ),
			'desc' => __( 'Controls the background color. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'content_alignment' => array(
			'type' => 'select',
			'label' => __( 'Content Alignment', 'fusion-core' ),
			'desc' => __( 'Choose the alignment of content. Choose default for theme option selection.', 'fusion-core' ),
			'options' => array(
				'' => __('Default', 'fusion-core'),
				'left' => __('Left', 'fusion-core'),
				'center' => __('Center', 'fusion-core'),
				'right' => __('Right', 'fusion-core')
			)
		),
		'pic_style_color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Picture Style color', 'fusion-core' ),
			'desc' => __( 'For all style types except border. Controls the style color. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'picborder' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Picture Border Size', 'fusion-core' ),
			'desc' => __( 'In pixels (px), ex: 1px. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'picbordercolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Picture Border Color', 'fusion-core' ),
			'desc' => __( 'Controls the picture\'s border color. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'picborderradius' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Picture Border Radius', 'fusion-core' ),
			'desc' => __( 'Choose the border radius of the person image. In pixels (px), ex: 1px, or "round".  Leave blank for theme option selection.', 'fusion-core' ),
		),
		'icon_position' => array(
			'type' => 'select',
			'label' => __( 'Icon Position', 'fusion-core' ),
			'desc' => __( 'Choose the social icon position. Choose default for theme option selection.', 'fusion-core' ),
			'options' => array(
				'' => __('Default', 'fusion-core'),
				'top' => __('Top', 'fusion-core'),
				'bottom' => __('Bottom', 'fusion-core')
			)
		),
		'iconboxed' => array(
			'type' => 'select',
			'label' => __( 'Boxed Social Icons', 'fusion-core' ),
			'desc' => __( 'Choose to get boxed icons. Choose default for theme option selection.', 'fusion-core' ),
			'options' => $reverse_choices_with_default
		),
		'iconboxedradius' => array(
			'std' => '4px',
			'type' => 'text',
			'label' => __( 'Social Icon Box Radius', 'fusion-core' ),
			'desc' => __( 'Choose the border radius of the boxed icons. In pixels (px), ex: 1px, or "round". Leave blank for theme option selection.', 'fusion-core' ),
		),
		'iconcolortype' => array(
			'type' => 'select',
			'label' => __( 'Social Icon Color Type', 'fusion-core' ),
			'desc' => __( 'Controls the color type of the social icons. Choose default for theme option selection.', 'fusion-core' ),
			'options' => array(
				''       => __('Default', 'fusion-core'),
				'custom' => __('Custom Colors', 'fusion-core'),
				'brand'  => __('Brand Colors', 'fusion-core'),
			),
		),
		'iconcolor' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __( 'Social Icon Custom Colors', 'fusion-core' ),
			'desc' => __( 'Specify the color of social icons. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'boxcolor' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __( 'Social Icon Custom Box Colors', 'fusion-core' ),
			'desc' => __( 'Specify the box color of social icons. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'icontooltip' => array(
			'type' => 'select',
			'label' => __( 'Social Icon Tooltip Position', 'fusion-core' ),
			'desc' => __( 'Choose the display position for tooltips. Choose default for theme option selection.', 'fusion-core' ),
			'options' => array(
				'' => __('Default', 'fusion-core'),
				'top' => __('Top', 'fusion-core'),
				'bottom' => __('Bottom', 'fusion-core'),
				'left' => __('Left', 'fusion-core'),
				'Right' => __('Right', 'fusion-core'),
			)
		),
		'email' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Email Address', 'fusion-core' ),
			'desc' => __( 'Insert an email address to display the email icon', 'fusion-core' ),
		),
		'facebook' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Facebook Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Facebook link', 'fusion-core' ),
		),
		'twitter' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Twitter Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Twitter link', 'fusion-core' ),
		),
		'instagram' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Instagram Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Instagram link', 'fusion-core' ),
		),
		'dribbble' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Dribbble Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Dribbble link', 'fusion-core' ),
		),
		'google' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Google+ Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Google+ link', 'fusion-core' ),
		),
		'linkedin' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'LinkedIn Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom LinkedIn link', 'fusion-core' ),
		),
		'blogger' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Blogger Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Blogger link', 'fusion-core' ),
		),
		'tumblr' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Tumblr Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Tumblr link', 'fusion-core' ),
		),
		'reddit' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Reddit Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Reddit link', 'fusion-core' ),
		),
		'yahoo' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Yahoo Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Yahoo link', 'fusion-core' ),
		),
		'deviantart' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Deviantart Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Deviantart link', 'fusion-core' ),
		),
		'vimeo' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Vimeo Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Vimeo link', 'fusion-core' ),
		),
		'youtube' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Youtube Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Youtube link', 'fusion-core' ),
		),
		'pinterest' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Pinterst Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Pinterest link', 'fusion-core' ),
		),
		'rss' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'RSS Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom RSS link', 'fusion-core' ),
		),
		'digg' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Digg Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Digg link', 'fusion-core' ),
		),
		'flickr' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Flickr Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Flickr link', 'fusion-core' ),
		),
		'forrst' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Forrst Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Forrst link', 'fusion-core' ),
		),
		'myspace' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Myspace Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Myspace link', 'fusion-core' ),
		),
		'skype' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Skype Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Skype link', 'fusion-core' ),
		),
		'paypal' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'PayPal Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom paypal link', 'fusion-core' ),
		),
		'dropbox' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Dropbox Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom dropbox link', 'fusion-core' ),
		),
		'soundcloud' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'SoundCloud Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom soundcloud link', 'fusion-core' ),
		),
		'vk' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'VK Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom vk link', 'fusion-core' ),
		),
		'xing' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Xing Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Xing link', 'fusion-core' ),
		),
		'show_custom' => array(
			'type' => 'select',
			'label' => __( 'Show Custom Social Icon', 'fusion-core' ),
			'desc' => __( 'Show the custom social icon specified in Theme Options', 'fusion-core' ),
			'options' => $reverse_choices
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[person name="{{name}}" title="{{title}}" picture="{{picture}}" pic_link="{{piclink}}" linktarget="{{target}}" pic_style="{{picstyle}}" hover_type="{{hover_type}}" background_color="{{background_color}}" content_alignment="{{content_alignment}}" icon_position="{{icon_position}}" pic_style_color="{{pic_style_color}}" pic_bordersize="{{picborder}}" pic_bordercolor="{{picbordercolor}}" pic_borderradius="{{picborderradius}}" social_icon_boxed="{{iconboxed}}" social_icon_boxed_radius="{{iconboxedradius}}" social_icon_color_type="{{iconcolortype}}" social_icon_colors="{{iconcolor}}"  social_icon_boxed_colors="{{boxcolor}}" social_icon_tooltip="{{icontooltip}}" email="{{email}}" facebook="{{facebook}}" twitter="{{twitter}}" instagram="{{instagram}}" dribbble="{{dribbble}}" google="{{google}}" linkedin="{{linkedin}}" blogger="{{blogger}}" tumblr="{{tumblr}}" reddit="{{reddit}}" yahoo="{{yahoo}}" deviantart="{{deviantart}}" vimeo="{{vimeo}}" youtube="{{youtube}}" rss="{{rss}}" pinterest="{{pinterest}}" digg="{{digg}}" flickr="{{flickr}}" forrst="{{forrst}}" myspace="{{myspace}}" skype="{{skype}}" paypal="{{paypal}}" dropbox="{{dropbox}}" soundcloud="{{soundcloud}}" vk="{{vk}}" xing="{{xing}}" show_custom="{{show_custom}}" class="{{class}}" id="{{id}}"]{{content}}[/person]',
	'popup_title' => __( 'Person Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Popover Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['popover'] = array(
	'params' => array(
		'title' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Popover Heading', 'fusion-core' ),
			'desc' => __( 'Heading text of the popover.', 'fusion-core' ),
		),
		'titlebgcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Popover Heading Background Color', 'fusion-core' ),
			'desc' => __( 'Controls the background color of the popover heading. Leave blank for theme option selection.', 'fusion-core')
		),
		'popovercontent' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __( 'Contents Inside Popover', 'fusion-core' ),
			'desc' => __( 'Text to be displayed inside the popover.', 'fusion-core' ),
		),
		'contentbgcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Popover Content Background Color', 'fusion-core' ),
			'desc' => __( 'Controls the background color of the popover content area. Leave blank for theme option selection.', 'fusion-core')
		),
		'bordercolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Popover Border Color', 'fusion-core' ),
			'desc' => __( 'Controls the border color of the of the popover box. Leave blank for theme option selection.', 'fusion-core')
		),
		'textcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Popover Text Color', 'fusion-core' ),
			'desc' => __( 'Controls all the text color inside the popover box. Leave blank for theme option selection.', 'fusion-core')
		),
		'trigger' => array(
			'type' => 'select',
			'label' => __( 'Popover Trigger Method', 'fusion-core' ),
			'desc' => __( 'Choose mouse action to trigger popover.', 'fusion-core' ),
			'options' => array(
				'click' => __('Click', 'fusion-core'),
				'hover' => __('Hover', 'fusion-core'),
			)
		),
		'placement' => array(
			'type' => 'select',
			'label' => __( 'Popover Position', 'fusion-core' ),
			'desc' => __( 'Choose the display position of the popover. Choose default for theme option selection.', 'fusion-core' ),
			'options' => array(
				'' => __('Default', 'fusion-core'),
				'top' => __('Top', 'fusion-core'),
				'bottom' => __('Bottom', 'fusion-core'),
				'left' => __('Left', 'fusion-core'),
				'Right' => __('Right', 'fusion-core'),
			)
		),
		'content' => array(
			'std' => __('Text', 'fusion-core'),
			'type' => 'text',
			'label' => __( 'Triggering Content', 'fusion-core' ),
			'desc' => __( 'Content that will trigger the popover.', 'fusion-core' ),
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[popover title="{{title}}" title_bg_color="{{titlebgcolor}}" content="{{popovercontent}}" content_bg_color="{{contentbgcolor}}" bordercolor="{{bordercolor}}" textcolor="{{textcolor}}" trigger="{{trigger}}" placement="{{placement}}" class="{{class}}" id="{{id}}"]{{content}}[/popover]', // as there is no wrapper shortcode
	'popup_title' => __( 'Popover Shortcode', 'fusion-core' ),
	'no_preview' => true,
);

/*-----------------------------------------------------------------------------------*/
/*	Pricing Table Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['pricingtable'] = array(
	'no_preview' => true,
	'params' => array(

		'type' => array(
			'type' => 'select',
			'label' => __( 'Type', 'fusion-core' ),
			'desc' => __( 'Select the type of pricing table', 'fusion-core' ),
			'options' => array(
				'1' => __('Style 1', 'fusion-core'),
				'2' => __('Style 2', 'fusion-core'),
			)
		),
		'backgroundcolor' => array(
			'type' => 'colorpicker',
			'std' => '',
			'label' => __( 'Background Color', 'fusion-core' ),
			'desc' => __('Controls the background color. Leave blank for theme option selection.', 'fusion-core')
		),
		'bordercolor' => array(
			'type' => 'colorpicker',
			'std' => '',
			'label' => __( 'Border Color', 'fusion-core' ),
			'desc' => __('Controls the border color. Leave blank for theme option selection.', 'fusion-core')
		),
		'dividercolor' => array(
			'type' => 'colorpicker',
			'std' => '',
			'label' => __( 'Divider Color', 'fusion-core' ),
			'desc' => __('Controls the divider color. Leave blank for theme option selection.', 'fusion-core')
		),
		'columns' => array(
			'type' => 'select',
			'label' => __( 'Number of Columns', 'fusion-core' ),
			'desc' => __('Select how many columns to display', 'fusion-core'),
			'options' => array(
				'&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;' => '1 Column',
				'&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;' => '2 Columns',
				'&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;' => '3 Columns',
				'&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;' => '4 Columns',
				'&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;' => '5 Columns',
				'&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;[pricing_column title=&quot;Standard&quot; standout=&quot;no&quot;][pricing_price currency=&quot;$&quot; currency_position=&quot;left&quot; price=&quot;15.55&quot; time=&quot;monthly&quot;][/pricing_price][pricing_row]Feature 1[/pricing_row][pricing_footer]Signup[/pricing_footer][/pricing_column]&lt;br /&gt;' => '6 Columns'
			)
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[pricing_table type="{{type}}" backgroundcolor="{{backgroundcolor}}" bordercolor="{{bordercolor}}" dividercolor="{{dividercolor}}" class="{{class}}" id="{{id}}"]{{columns}}[/pricing_table]',
	'popup_title' => __( 'Pricing Table Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Progress Bar Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['progressbar'] = array(
	'params' => array(
		'height' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Progress Bar Height', 'fusion-core' ),
			'desc' => __( 'Insert a height for the progress bar. Enter value including any valid CSS unit, ex: 10px. Leave blank for theme option selection.', 'fusion-core' ),
		),	
		'text_position' => array(
			'std' => 'on_bar',
			'type' => 'select',
			'label' => __( 'Text Position', 'fusion-core' ),
			'desc' => __( 'Select the position of the progress bar text. Choose "Default" for theme option selection.', 'fusion-core' ),
			'options' => array(
				'' => 'Default',
				'on_bar' => __('On Bar', 'fusion-core'),
				'above_bar' => __('Above Bar', 'fusion-core'),
				'below_bar' => __( 'Below Bar', 'fusion-core' )
			)
		),
		'percentage' => array(
			'type' => 'select',
			'label' => __( 'Filled Area Percentage', 'fusion-core' ),
			'desc' => __( 'From 1% to 100%', 'fusion-core' ),
			'options' => fusion_shortcodes_range( 100, false )
		),
		'show_percentage' => array(
			'std' => 'yes',
			'type' => 'select',
			'label' => __( 'Display Percentage Value', 'fusion-core' ),
			'desc' => __( 'Select if you want the filled area percentage value to be shown.', 'fusion-core' ),
			'options' => array(
				'yes' => __('Yes', 'fusion-core'),
				'no' => __('No', 'fusion-core')
			)
		),
		'unit' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Progress Bar Unit', 'fusion-core' ),
			'desc' => __( 'Insert a unit for the progress bar. ex %', 'fusion-core' ),
		),
		'filledcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Filled Color', 'fusion-core' ),
			'desc' => __( 'Controls the color of the filled in area. Leave blank for theme option selection.', 'fusion-core' )
		),
		'unfilledcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Unfilled Color', 'fusion-core' ),
			'desc' => __( 'Controls the color of the unfilled in area. Leave blank for theme option selection.', 'fusion-core' )
		),
		'striped' => array(
			'type' => 'select',
			'label' => __( 'Striped Filling', 'fusion-core' ),
			'desc' => __( 'Choose to get the filled area striped.', 'fusion-core' ),
			'options' => $reverse_choices
		),
		'animatedstripes' => array(
			'type' => 'select',
			'label' => __( 'Animated Stripes', 'fusion-core' ),
			'desc' => __( 'Choose to get the the stripes animated.', 'fusion-core' ),
			'options' => $reverse_choices
		),
		'textcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Text Color', 'fusion-core' ),
			'desc' => __( 'Controls the text color. Leave blank for theme option selection.', 'fusion-core ')
		),
		'content' => array(
			'std' => __('Text', 'fusion-core'),
			'type' => 'text',
			'label' => __( 'Progess Bar Text', 'fusion-core' ),
			'desc' => __( 'Text will show up on progess bar', 'fusion-core' ),
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[progress height="{{height}}" text_position="{{text_position}}" percentage="{{percentage}}" show_percentage="{{show_percentage}}" unit="{{unit}}" filledcolor="{{filledcolor}}" unfilledcolor="{{unfilledcolor}}" striped="{{striped}}" animated_stripes="{{animatedstripes}}" textcolor="{{textcolor}}" animation_offset="{{animation_offset}}" class="{{class}}" id="{{id}}"]{{content}}[/progress]',
	'popup_title' => __( 'Progress Bar Shortcode', 'fusion-core' ),
	'no_preview' => true,
);

/*-----------------------------------------------------------------------------------*/
/*	Recent Posts Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['recentposts'] = array(
	'no_preview' => true,
	'params' => array(

		'layout' => array(
			'type' => 'select',
			'label' => __( 'Layout', 'fusion-core' ),
			'desc' => __('Select the layout for the shortcode', 'fusion-core'),
			'options' => array(
				'default' => __('Default', 'fusion-core'),
				'thumbnails-on-side' => __('Thumbnails on Side', 'fusion-core'),
				'date-on-side' => __('Date on Side', 'fusion-core'),
			)
		),
		'hover_type' => array(
			'std' => 'none',
			'type' => 'select',
			'label' => __( 'Hover Type', 'fusion-core' ),
			'desc' => __('Select the hover effect type.', 'fusion-core'),
			'options' => array(
				'none' => __('None', 'fusion-core'),
				'zoomin' => __('Zoom In', 'fusion-core'),
				'zoomout' => __('Zoom Out', 'fusion-core'),
				'liftup' => __('Lift Up', 'fusion-core')
			)
		),
		'columns' => array(
			'type' => 'select',
			'label' => __( 'Columns', 'fusion-core' ),
			'desc' => __( 'Select the number of columns to display', 'fusion-core' ),
			'options' => fusion_shortcodes_range( 6, false )
		),
		'number_posts' => array(
			'std' => 4,
			'type' => 'text',
			'label' => __( 'Number of Posts', 'fusion-core' ),
			'desc' => __('Select the number of posts to display', 'fusion-core')
		),
		'offset' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Post Offset', 'fusion-core' ),
			'desc' => __('The number of posts to skip. ex: 1.', 'fusion-core')
		),
		'cat_slug' => array(
			'type' => 'multiple_select',
			'label' => __( 'Categories', 'fusion-core' ),
			'desc' => __( 'Select a category or leave blank for all', 'fusion-core' ),
			'options' => fusion_shortcodes_categories( 'category' )
		),
		'exclude_cats' => array(
			'type' => 'multiple_select',
			'label' => __( 'Exclude Categories', 'fusion-core' ),
			'desc' => __( 'Select a category to exclude', 'fusion-core' ),
			'options' => fusion_shortcodes_categories( 'category' )
		),
		'thumbnail' => array(
			'type' => 'select',
			'label' => __( 'Show Thumbnail', 'fusion-core' ),
			'desc' => __('Display the post featured image', 'fusion-core'),
			'options' => $choices
		),
		'title' => array(
			'type' => 'select',
			'label' => __( 'Show Title', 'fusion-core' ),
			'desc' => __('Display the post title below the featured image', 'fusion-core'),
			'options' => $choices
		),
		'meta' => array(
			'type' => 'select',
			'label' => __( 'Show Meta', 'fusion-core' ),
			'desc' => __('Choose to show all meta data', 'fusion-core'),
			'options' => $choices
		),
		'excerpt' => array(
			'type' => 'select',
			'label' => __( 'Show Excerpt', 'fusion-core' ),
			'desc' => __('Choose to display the post excerpt', 'fusion-core'),
			'options' => $choices
		),
		'excerpt_length' => array(
			'std' => 35,
			'type' => 'text',
			'label' => __( 'Excerpt Length', 'fusion-core' ),
			'desc' => __('Insert the number of words/characters you want to show in the excerpt', 'fusion-core'),
		),
		'strip_html' => array(
			'type' => 'select',
			'label' => __( 'Strip HTML', 'fusion-core' ),
			'desc' => __('Strip HTML from the post excerpt', 'fusion-core'),
			'options' => $choices
		),
		'animation_type' => array(
			'type' => 'select',
			'label' => __( 'Animation Type', 'fusion-core' ),
			'desc' => __( 'Select the type on animation to use on the shortcode', 'fusion-core' ),
			'options' => $animation_type,
		),
		'animation_direction' => array(
			'type' => 'select',
			'label' => __( 'Direction of Animation', 'fusion-core' ),
			'desc' => __( 'Select the incoming direction for the animation', 'fusion-core' ),
			'options' => $animation_direction,
		),
		'animation_speed' => array(
			'type' => 'select',
			'std' => '',
			'label' => __( 'Speed of Animation', 'fusion-core' ),
			'desc' => __( 'Type in speed of animation in seconds (0.1 - 1)', 'fusion-core' ),
			'options' => $dec_numbers,
		),
		'animation_offset' => array(
			'type' 		=> 'select',
			'std' 		=> '',
			'label' 	=> __( 'Offset of Animation', 'fusion-core' ),
			'desc' 		=> __( 'Choose when the animation should start.', 'fusion-core' ),
			'options' 	=> array(
					  			''					=> __( 'Default', 'fusion-core' ),
								'top-into-view' 	=> __( 'Top of element hits bottom of viewport', 'fusion-core' ),
								'top-mid-of-view' 	=> __( 'Top of element hits middle of viewport', 'fusion-core' ),
								'bottom-in-view' 	=> __( 'Bottom of element enters viewport', 'fusion-core' ),
							)
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[recent_posts layout="{{layout}}" hover_type="{{hover_type}}" columns="{{columns}}" number_posts="{{number_posts}}" offset="{{offset}}" cat_slug="{{cat_slug}}" exclude_cats="{{exclude_cats}}" thumbnail="{{thumbnail}}" title="{{title}}" meta="{{meta}}" excerpt="{{excerpt}}" excerpt_length="{{excerpt_length}}" strip_html="{{strip_html}}" animation_type="{{animation_type}}" animation_direction="{{animation_direction}}" animation_speed="{{animation_speed}}" animation_offset="{{animation_offset}}" class="{{class}}" id="{{id}}"][/recent_posts]',
	'popup_title' => __( 'Recent Posts Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Recent Works Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['recentworks'] = array(
	'no_preview' => true,
	'params' => array(
		'layout' => array(
			'type' => 'select',
			'label' => __( 'Layout', 'fusion-core' ),
			'desc' => __('Choose the layout for the shortcode', 'fusion-core'),
			'options' => array(
				'carousel' => __('Carousel', 'fusion-core'),
				'grid' => __('Grid', 'fusion-core'),
				'grid-with-excerpts' => __('Grid with Excerpts', 'fusion-core'),
			)
		),
		'picture_size' => array(
			'type' => 'select',
			'label' => __( 'Picture Size', 'fusion-core' ),
			'desc' => __( 'fixed = width and height will be fixed <br />auto = width and height will adjust to the image.', 'fusion-core' ),
			'options' => array(
				'fixed' => __('Fixed', 'fusion-core'),
				'auto' => __('Auto', 'fusion-core')
			)
		),
		'boxed_text' => array(
			'type' => 'select',
			'label' => __( 'Grid with Excerpts Layout', 'fusion-core' ),
			'desc' => __( 'Select if the grid with excerpts layouts are boxed or unboxed.', 'fusion-core' ),
			'options' => array(
				'boxed' => __('Boxed', 'fusion-core'),
				'unboxed' => __('Unboxed', 'fusion-core')
			)
		),
		'filters' => array(
			'type' => 'select',
			'label' => __( 'Show Filters', 'fusion-core' ),
			'desc' => __('Choose to show or hide the category filters', 'fusion-core'),
			'options' => array(
				'yes' => __('Yes', 'fusion-core'),
				'yes-without-all' => __('Yes without "All"', 'fusion-core'),
				'no' => __('No', 'fusion-core')
			)
		),
		'columns' => array(
			'type' => 'select',
			'label' => __( 'Columns', 'fusion-core' ),
			'desc' => __( 'Select the number of columns to display. Does not work with Carousel layout.', 'fusion-core' ),
			'options' => fusion_shortcodes_range( 6, false )
		),
		'column_spacing' => array(
			'std' => '12',
			'type' => 'text',
			'label' => __( 'Column Spacing', 'fusion-core' ),
			'desc' => __( 'Insert the amount of spacing between portfolio items without "px". ex: 7. Does not work with Carousel layout.', 'fusion-core' )
		),
		'cat_slug' => array(
			'type' => 'multiple_select',
			'label' => __( 'Categories', 'fusion-core' ),
			'desc' => __( 'Select a category or leave blank for all', 'fusion-core' ),
			'options' => fusion_shortcodes_categories( 'portfolio_category' )
		),
		'exclude_cats' => array(
			'type' => 'multiple_select',
			'label' => __( 'Exclude Categories', 'fusion-core' ),
			'desc' => __( 'Select a category to exclude', 'fusion-core' ),
			'options' => fusion_shortcodes_categories( 'portfolio_category' )
		),
		'number_posts' => array(
			'std' => 4,
			'type' => 'text',
			'label' => __( 'Number of Posts', 'fusion-core' ),
			'desc' => __('Select the number of posts to display', 'fusion-core')
		),
		'offset' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Post Offset', 'fusion-core' ),
			'desc' => __('The number of posts to skip. ex: 1.', 'fusion-core')
		),
		'excerpt_length' => array(
			'std' => 35,
			'type' => 'text',
			'label' => __( 'Excerpt Length', 'fusion-core' ),
			'desc' => __('Insert the number of words/characters you want to show in the excerpt', 'fusion-core'),
		),
		'strip_html' => array(
			'type' => 'select',
			'label' => __( 'Strip HTML from Posts Content', 'fusion-core' ),
			'desc' =>  __( 'Strip HTML from the post excerpt.', 'fusion-core' ),
			'options' => $choices
		),
		'carousel_layout' => array(
			'type' => 'select',
			'label' => __( 'Carousel Layout', 'fusion-core' ),
			'desc' => __( 'Choose to show titles on rollover image, or below image.', 'fusion-core' ),
			'options' => array(
				'title_on_rollover' => __('Title on rollover', 'fusion-core'),
				'title_below_image' => __('Title below image', 'fusion-core'),
			)
		),
		'scroll_items' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Scroll Items', 'fusion-core' ),
			"desc" => __("Insert the amount of items to scroll. Leave empty to scroll number of visible items.", "fusion-core"),
		),
		'autoplay' => array(
			'type' => 'select',
			'label' => __( 'Carousel Autoplay', 'fusion-core' ),
			'desc' => __('Choose to autoplay the carousel.', 'fusion-core'),
			'options' => $reverse_choices
		),
		'show_nav' => array(
			'type' => 'select',
			'label' => __( 'Carousel Show Navigation', 'fusion-core' ),
			'desc' => __( 'Choose to show navigation buttons on the carousel.', 'fusion-core' ),
			'options' => $choices
		),
		'mouse_scroll' => array(
			'type' => 'select',
			'label' => __( 'Carousel Mouse Scroll', 'fusion-core' ),
			'desc' => __( 'Choose to enable mouse drag control on the carousel.', 'fusion-core' ),
			'options' => $reverse_choices
		),
		'animation_type' => array(
			'type' => 'select',
			'label' => __( 'Animation Type', 'fusion-core' ),
			'desc' => __( 'Select the type on animation to use on the shortcode', 'fusion-core' ),
			'options' => $animation_type,
		),
		'animation_direction' => array(
			'type' => 'select',
			'label' => __( 'Direction of Animation', 'fusion-core' ),
			'desc' => __( 'Select the incoming direction for the animation', 'fusion-core' ),
			'options' => $animation_direction,
		),
		'animation_speed' => array(
			'type' => 'select',
			'std' => '',
			'label' => __( 'Speed of Animation', 'fusion-core' ),
			'desc' => __( 'Type in speed of animation in seconds (0.1 - 1)', 'fusion-core' ),
			'options' => $dec_numbers,
		),
		'animation_offset' => array(
			'type' 		=> 'select',
			'std' 		=> '',
			'label' 	=> __( 'Offset of Animation', 'fusion-core' ),
			'desc' 		=> __( 'Choose when the animation should start.', 'fusion-core' ),
			'options' 	=> array(
					  			''					=> __( 'Default', 'fusion-core' ),
								'top-into-view' 	=> __( 'Top of element hits bottom of viewport', 'fusion-core' ),
								'top-mid-of-view' 	=> __( 'Top of element hits middle of viewport', 'fusion-core' ),
								'bottom-in-view' 	=> __( 'Bottom of element enters viewport', 'fusion-core' ),
							)
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[recent_works picture_size="{{picture_size}}" layout="{{layout}}" boxed_text="{{boxed_text}}" filters="{{filters}}" columns="{{columns}}" column_spacing="{{column_spacing}}" cat_slug="{{cat_slug}}" exclude_cats="{{exclude_cats}}" number_posts="{{number_posts}}" offset="{{offset}}" excerpt_length="{{excerpt_length}}" strip_html="{{strip_html}}" carousel_layout="{{carousel_layout}}" scroll_items="{{scroll_items}}" autoplay="{{autoplay}}" show_nav="{{show_nav}}" mouse_scroll="{{mouse_scroll}}" animation_type="{{animation_type}}" animation_direction="{{animation_direction}}" animation_speed="{{animation_speed}}" animation_offset="{{animation_offset}}" class="{{class}}" id="{{id}}"][/recent_works]',
	'popup_title' => __( 'Recent Works Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Section Separator Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['sectionseparator'] = array(
	'no_preview' => true,
	'params' => array(
		'divider_candy' => array(
			'type' => 'select',
			'label' => __( 'Position of the Divider Candy', 'fusion-core' ),
			'desc' => __( 'Select the position of the triangle candy.', 'fusion-core' ),
			'options' => array(
				'top' => __('Top', 'fusion-core'),
				'bottom' => __('Bottom', 'fusion-core'),
				'bottom,top' => __('Top and Bottom', 'fusion-core'),
			)
		),
		'icon' => array(
			'type' => 'iconpicker',
			'label' => __( 'Select Icon', 'fusion-core' ),
			'desc' => __( 'Click an icon to select, click again to deselect', 'fusion-core' ),
			'options' => $icons
		),
		'iconcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Icon Color', 'fusion-core' ),
			'desc' => __( 'Leave blank for theme option selection.', 'fusion-core' )
		),
		'border' => array(
			'std' => '1px',
			'type' => 'text',
			'label' => __( 'Border Size', 'fusion-core' ),
			'desc' => __( 'In pixels (px), ex: 1px. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'bordercolor' => array(
			'type' => 'colorpicker',
			'std' => '',
			'label' => __( 'Border Color', 'fusion-core' ),
			'desc' => __( 'Controls the border color. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'backgroundcolor' => array(
			'type' => 'colorpicker',
			'std' => '',
			'label' => __( 'Background Color of Divider Candy', 'fusion-core' ),
			'desc' => __( 'Controls the background color of the triangle. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[section_separator divider_candy="{{divider_candy}}" icon="{{icon}}" icon_color="{{iconcolor}}" bordersize="{{border}}" bordercolor="{{bordercolor}}" backgroundcolor="{{backgroundcolor}}" class="{{class}}" id="{{id}}"]',
	'popup_title' => __( 'Section Separator Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Separator Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['separator'] = array(
	'no_preview' => true,
	'params' => array(

		'style_type' => array(
			'type' => 'select',
			'label' => __( 'Style', 'fusion-core' ),
			'desc' => __( 'Choose the separator line style', 'fusion-core' ),
			'options' => array(
				'none' => __('No Style', 'fusion-core'),
				'single' => __('Single Border Solid', 'fusion-core'),
				'double' => __('Double Border Solid', 'fusion-core'),
				'single|dashed' => __('Single Border Dashed', 'fusion-core'),
				'double|dashed' => __('Double Border Dashed', 'fusion-core'),
				'single|dotted' => __('Single Border Dotted', 'fusion-core'),
				'double|dotted' => __('Double Border Dotted', 'fusion-core'),
				'shadow' => __('Shadow', 'fusion-core')
			)
		),
		'topmargin' => array(
			'std' => 40,
			'type' => 'text',
			'label' => __( 'Margin Top', 'fusion-core' ),
			'desc' => __( 'Spacing above the separator. In pixels or percentage, ex: 10px or 10%.', 'fusion-core' ),
		),
		'bottommargin' => array(
			'std' => 40,
			'type' => 'text',
			'label' => __( 'Margin Bottom', 'fusion-core' ),
			'desc' => __( 'Spacing below the separator. In pixels or percentage, ex: 10px or 10%.', 'fusion-core' ),
		),
		'sepcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Separator Color', 'fusion-core' ),
			'desc' => __( 'Controls the separator color. Leave blank for theme option selection.', 'fusion-core' )
		),
		'border_size' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Border Size', 'fusion-core' ),
			'desc' => __( 'In pixels (px), ex: 1px. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'icon' => array(
			'type' => 'iconpicker',
			'label' => __( 'Select Icon', 'fusion-core' ),
			'desc' => __( 'Click an icon to select, click again to deselect.', 'fusion-core' ),
			'options' => $icons
		),
		'icon_circle' => array(
			'type' => 'select',
			'label' => __( 'Circled Icon', 'fusion-core' ),
			'desc' => __( 'Choose to have a circle in separator color around the icon.', 'fusion-core' ),
			'options' => $choices_with_default
		),
		'icon_circle_color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Circle Color', 'fusion-core' ),
			'desc' => __( 'Controls the background color of the circle around the icon.', 'fusion-core' )
		),
		'width' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Separator Width', 'fusion-core' ),
			'desc' => __( 'In pixels (px or %), ex: 1px, ex: 50%. Leave blank for full width.', 'fusion-core' ),
		),
		'alignment' => array(
			'std' => 'center',
			'type' => 'select',
			'label' => __( 'Alignment', 'fusion-core' ),
			'desc' => __( 'Select the separator alignment; only works when a width is specified.', 'fusion-core' ),
			'options' => array(
				'center' => __('Center', 'fusion-core'),
				'left' => __('Left', 'fusion-core'),
				'right' => __('Right', 'fusion-core'),
			)
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[separator style_type="{{style_type}}" top_margin="{{topmargin}}" bottom_margin="{{bottommargin}}"  sep_color="{{sepcolor}}" border_size="{{border_size}}" icon="{{icon}}" icon_circle="{{icon_circle}}" icon_circle_color="{{icon_circle_color}}" width="{{width}}" alignment="{{alignment}}" class="{{class}}" id="{{id}}"]',
	'popup_title' => __( 'Separator Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Sharing Box Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['sharingbox'] = array(
	'no_preview' => true,
	'params' => array(
		'tagline' => array(
			'std' => __('Share This Story, Choose Your Platform!', 'fusion-core'),
			'type' => 'text',
			'label' => __( 'Tagline', 'fusion-core' ),
			'desc' => __('The title tagline that will display', 'fusion-core')
		),
		'taglinecolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Tagline Color', 'fusion-core' ),
			'desc' => __( 'Controls the text color. Leave blank for theme option selection.', 'fusion-core')
		),
		'backgroundcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Background Color', 'fusion-core' ),
			'desc' => __( 'Controls the background color. Leave blank for theme option selection.', 'fusion-core')
		),
		'title' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Title', 'fusion-core' ),
			'desc' => __('The post title that will be shared', 'fusion-core')
		),
		'link' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Link', 'fusion-core' ),
			'desc' => __('The link that will be shared', 'fusion-core')
		),
		'description' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __( 'Description', 'fusion-core' ),
			'desc' => __('The description that will be shared', 'fusion-core')
		),
		'link' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Link to Share', 'fusion-core' ),
			'desc' => ''
		),
		'iconboxed' => array(
			'type' => 'select',
			'label' => __( 'Boxed Social Icons', 'fusion-core' ),
			'desc' => __( 'Choose to get a boxed icons. Choose default for theme option selection.', 'fusion-core' ),
			'options' => $reverse_choices_with_default
		),
		'iconboxedradius' => array(
			'std' => '4px',
			'type' => 'text',
			'label' => __( 'Social Icon Box Radius', 'fusion-core' ),
			'desc' => __( 'Choose the radius of the boxed icons. In pixels (px), ex: 1px, or "round". Leave blank for theme option selection.', 'fusion-core' ),
		),
		'iconcolortype' => array(
			'type' => 'select',
			'label' => __( 'Social Icon Color Type', 'fusion-core' ),
			'desc' => __( 'Controls the color type of the social icons. Choose default for theme option selection.', 'fusion-core' ),
			'options' => array(
				''       => __('Default', 'fusion-core'),
				'custom' => __('Custom Colors', 'fusion-core'),
				'brand'  => __('Brand Colors', 'fusion-core'),
			),
		),
		'iconcolor' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __( 'Social Icon Custom Colors', 'fusion-core' ),
			'desc' => __( 'Specify the color of social icons. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'boxcolor' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __( 'Social Icon Custom Box Colors', 'fusion-core' ),
			'desc' => __( 'Specify the box color of social icons. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'icontooltip' => array(
			'type' => 'select',
			'label' => __( 'Social Icon Tooltip Position', 'fusion-core' ),
			'desc' => __( 'Choose the display position for tooltips. Choose default for theme option selection.', 'fusion-core' ),
			'options' => array(
				'' => __('Default', 'fusion-core'),
				'top' => __('Top', 'fusion-core'),
				'bottom' => __('Bottom', 'fusion-core'),
				'left' => __('Left', 'fusion-core'),
				'right' => __('Right', 'fusion-core'),
			)
		),
		'pinterest_image' => array(
			'std' => '',
			'type' => 'uploader',
			'label' => __( 'Choose Image to Share on Pinterest', 'fusion-core' ),
			'desc' => ''
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[sharing tagline="{{tagline}}" tagline_color="{{taglinecolor}}" title="{{title}}" link="{{link}}" description="{{description}}" pinterest_image="{{pinterest_image}}" icons_boxed="{{iconboxed}}" icons_boxed_radius="{{iconboxedradius}}" color_type="{{iconcolortype}}" box_colors="{{boxcolor}}" icon_colors="{{iconcolor}}" tooltip_placement="{{icontooltip}}" backgroundcolor="{{backgroundcolor}}" class="{{class}}" id="{{id}}"][/sharing]',
	'popup_title' => __( 'Sharing Box Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Slider Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['slider'] = array(
	'params' => array(
		'hover_type' => array(
			'std' => 'none',
			'type' => 'select',
			'label' => __( 'Hover Type', 'fusion-core' ),
			'desc' => __('Select the hover effect type.', 'fusion-core'),
			'options' => array(
				'none' => __('None', 'fusion-core'),
				'zoomin' => __('Zoom In', 'fusion-core'),
				'zoomout' => __('Zoom Out', 'fusion-core'),
				'liftup' => __('Lift Up', 'fusion-core')
			)
		),
		'size' => array(
			'std' => '100%',
			'type' => 'size',
			'label' => __( 'Image Size (Width/Height)', 'fusion-core' ),
			'desc' => __( 'Width and Height in percentage (%) or pixels (px)', 'fusion-core' ),
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[slider hover_type="{{hover_type}}" width="{{size_width}}" height="{{size_height}}" class="{{class}}" id="{{id}}"]{{child_shortcode}}[/slider]', // as there is no wrapper shortcode
	'popup_title' => __( 'Slider Shortcode', 'fusion-core' ),
	'no_preview' => true,

	// child shortcode is clonable & sortable
	'child_shortcode' => array(
		'params' => array(
			'slider_type' => array(
				'type' => 'select',
				'label' => __( 'Slide Type', 'fusion-core' ),
				'desc' => __('Choose a video or image slide', 'fusion-core'),
				'options' => array(
					'image' => __('Image', 'fusion-core'),
					'video' => __('Video', 'fusion-core')
				)
			),
			'video_content' => array(
				'std' => '',
				'type' => 'textarea',
				'label' => __( 'Video Shortcode or Video Embed Code', 'fusion-core' ),
				'desc' => __('Click the Youtube or Vimeo Shortcode button below then enter your unique video ID, or copy and paste your video embed code.<a href=\'[youtube id="Enter video ID (eg. Wq4Y7ztznKc)" width="600" height="350"]\' class="fusion-shortcodes-button fusion-add-video-shortcode">Insert Youtube Shortcode</a><a href=\'[vimeo id="Enter video ID (eg. 10145153)" width="600" height="350"]\' class="fusion-shortcodes-button fusion-add-video-shortcode">Insert Vimeo Shortcode</a>', 'fusion-core')
			),
			'image_content' => array(
				'std' => '',
				'type' => 'uploader',
				'label' => __( 'Slide Image', 'fusion-core' ),
				'desc' => __('Upload an image to display in the slide', 'fusion-core')
			),
			'image_url' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Full Image Link or External Link', 'fusion-core' ),
				'desc' => __('Add the url of where the image will link to. If lightbox option is enabled,and you don\'t add the full image link, lightbox will open slide image', 'fusion-core')
			),
			'image_target' => array(
				'type' => 'select',
				'label' => __( 'Link Target', 'fusion-core' ),
				'desc' => __( '_self = open in same window <br /> _blank = open in new window', 'fusion-core' ),
				'options' => array(
					'_self' => '_self',
					'_blank' => '_blank'
				)
			),
			'image_lightbox' => array(
				'type' => 'select',
				'label' => __( 'Lighbox', 'fusion-core' ),
				'desc' => __( 'Show image in Lightbox', 'fusion-core' ),
				'options' => $choices
			),
		),
		'shortcode' => '[slide type="{{slider_type}}" link="{{image_url}}" linktarget="{{image_target}}" lightbox="{{image_lightbox}}"]{{image_content}}[/slide]',
		'clone_button' => __( 'Add New Slide', 'fusion-core')
	)
);

/*-----------------------------------------------------------------------------------*/
/*	Social Links Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['sociallinks'] = array(
	'no_preview' => true,
	'params' => array(
		'iconboxed' => array(
			'type' => 'select',
			'label' => __( 'Boxed Social Icons', 'fusion-core' ),
			'desc' => __( 'Choose to get a boxed icons. Choose default for theme option selection.', 'fusion-core' ),
			'options' => $reverse_choices_with_default
		),
		'iconboxedradius' => array(
			'std' => '4px',
			'type' => 'text',
			'label' => __( 'Social Icon Box Radius', 'fusion-core' ),
			'desc' => __( 'Choose the radius of the boxed icons. In px or %, ex: 5px or 10% or "round". Leave blank for theme option selection.', 'fusion-core' ),
		),
		'iconcolortype' => array(
			'type' => 'select',
			'label' => __( 'Social Icon Color Type', 'fusion-core' ),
			'desc' => __( 'Controls the color type of the social icons. Choose default for theme option selection.', 'fusion-core' ),
			'options' => array(
				''       => __('Default', 'fusion-core'),
				'custom' => __('Custom Colors', 'fusion-core'),
				'brand'  => __('Brand Colors', 'fusion-core'),
			),
		),
		'iconcolor' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __( 'Social Icon Custom Colors', 'fusion-core' ),
			'desc' => __( 'Specify the color of social icons. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'boxcolor' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __( 'Social Icon Custom Box Colors', 'fusion-core' ),
			'desc' => __( 'Specify the box color of social icons. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'icontooltip' => array(
			'type' => 'select',
			'label' => __( 'Social Icon Tooltip Position', 'fusion-core' ),
			'desc' => __( 'Choose the display position for tooltips. Choose default for theme option selection.', 'fusion-core' ),
			'options' => array(
				'' => __('Default', 'fusion-core'),
				'top' => __('Top', 'fusion-core'),
				'bottom' => __('Bottom', 'fusion-core'),
				'left' => __('Left', 'fusion-core'),
				'Right' => __('Right', 'fusion-core'),
			)
		),
		'facebook' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Facebook Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Facebook link', 'fusion-core' ),
		),
		'twitter' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Twitter Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Twitter link', 'fusion-core' ),
		),
		'instagram' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Instagram Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Instagram link', 'fusion-core' ),
		),
		'dribbble' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Dribbble Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Dribbble link', 'fusion-core' ),
		),
		'google' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Google+ Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Google+ link', 'fusion-core' ),
		),
		'linkedin' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'LinkedIn Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom LinkedIn link', 'fusion-core' ),
		),
		'blogger' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Blogger Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Blogger link', 'fusion-core' ),
		),
		'tumblr' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Tumblr Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Tumblr link', 'fusion-core' ),
		),
		'reddit' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Reddit Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Reddit link', 'fusion-core' ),
		),
		'yahoo' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Yahoo Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Yahoo link', 'fusion-core' ),
		),
		'deviantart' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Deviantart Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Deviantart link', 'fusion-core' ),
		),
		'vimeo' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Vimeo Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Vimeo link', 'fusion-core' ),
		),
		'youtube' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Youtube Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Youtube link', 'fusion-core' ),
		),
		'pinterest' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Pinterst Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Pinterest link', 'fusion-core' ),
		),
		'rss' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'RSS Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom RSS link', 'fusion-core' ),
		),
		'digg' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Digg Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Digg link', 'fusion-core' ),
		),
		'flickr' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Flickr Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Flickr link', 'fusion-core' ),
		),
		'forrst' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Forrst Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Forrst link', 'fusion-core' ),
		),
		'myspace' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Myspace Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Myspace link', 'fusion-core' ),
		),
		'skype' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Skype Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Skype link', 'fusion-core' ),
		),
		'paypal' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'PayPal Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom paypal link', 'fusion-core' ),
		),
		'dropbox' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Dropbox Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom dropbox link', 'fusion-core' ),
		),
		'soundcloud' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'SoundCloud Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom soundcloud link', 'fusion-core' ),
		),
		'vk' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'VK Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom vk link', 'fusion-core' ),
		),
		'xing' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Xing Link', 'fusion-core' ),
			'desc' => __( 'Insert your custom Xing link', 'fusion-core' ),
		),
		'email' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Email Address', 'fusion-core' ),
			'desc' => __( 'Insert an email address to display the email icon', 'fusion-core' ),
		),
		'show_custom' => array(
			'type' => 'select',
			'label' => __( 'Show Custom Social Icon', 'fusion-core' ),
			'desc' => __( 'Show the custom social icon specified in Theme Options', 'fusion-core' ),
			'options' => $reverse_choices
		),
		'alignment' => array(
			'type' => 'select',
			'std' => '',
			'label' => __( 'Alignment', 'fusion-core' ),
			'desc' => __( 'Select the icon\'s alignment.', 'fusion-core' ),
			'options' => array(
				'left' => __('Left', 'fusion-core'),
				'center' => __('Center', 'fusion-core'),
				'right' => __('Right', 'fusion-core'),
			)
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[social_links icons_boxed="{{iconboxed}}" icons_boxed_radius="{{iconboxedradius}}" icon_colors="{{iconcolor}}" color_type="{{iconcolortype}}" box_colors="{{boxcolor}}" tooltip_placement="{{icontooltip}}" rss="{{rss}}" facebook="{{facebook}}" twitter="{{twitter}}" instagram="{{instagram}}" dribbble="{{dribbble}}" google="{{google}}" linkedin="{{linkedin}}" blogger="{{blogger}}" tumblr="{{tumblr}}" reddit="{{reddit}}" yahoo="{{yahoo}}" deviantart="{{deviantart}}" vimeo="{{vimeo}}" youtube="{{youtube}}" pinterest="{{pinterest}}" digg="{{digg}}" flickr="{{flickr}}" forrst="{{forrst}}" myspace="{{myspace}}" skype="{{skype}}" paypal="{{paypal}}" dropbox="{{dropbox}}" soundcloud="{{soundcloud}}" vk="{{vk}}" xing="{{xing}}" email="{{email}}" show_custom="{{show_custom}}" alignment="{{alignment}}" class="{{class}}" id="{{id}}"]',
	'popup_title' => __( 'Social Links Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	SoundCloud Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['soundcloud'] = array(
	'no_preview' => true,
	'params' => array(

		'url' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'SoundCloud Url', 'fusion-core' ),
			'desc' => __('The SoundCloud url, ex: http://api.soundcloud.com/tracks/110813479', 'fusion-core')
		),
		'layout' => array(
			'type' => 'select',
			'label' => __( 'Layout', 'fusion-core' ),
			'desc' => __('Choose the layout of the soundcloud embed.', 'fusion-core'),
			'options' => array( 'classic' => 'Classic', 'visual' => 'Visual' )
		),
		'comments' => array(
			'type' => 'select',
			'label' => __( 'Show Comments', 'fusion-core' ),
			'desc' => __('Choose to display comments', 'fusion-core'),
			'options' => $choices
		),
		'show_related' => array(
			'type' => 'select',
			'label' => __( 'Show Related', 'fusion-core' ),
			'desc' => __('Choose to display related items.', 'fusion-core'),
			'options' => $choices
		),
		'show_user' => array(
			'type' => 'select',
			'label' => __( 'Show User', 'fusion-core' ),
			'desc' => __('Choose to display the user who posted the item.', 'fusion-core'),
			'options' => $choices
		),
		'autoplay' => array(
			'type' => 'select',
			'label' => __( 'Autoplay', 'fusion-core' ),
			'desc' => __('Choose to autoplay the track', 'fusion-core'),
			'options' => $reverse_choices
		),
		'color' => array(
			'type' => 'colorpicker',
			'std' => '#ff7700',
			'label' => __( 'Color', 'fusion-core' ),
			'desc' => __('Select the color of the shortcode', 'fusion-core')
		),
		'width' => array(
			'std' => '100%',
			'type' => 'text',
			'label' => __( 'Width', 'fusion-core' ),
			'desc' => __('In pixels (px) or percentage (%)', 'fusion-core')
		),
		'height' => array(
			'std' => '150px',
			'type' => 'text',
			'label' => __( 'Height', 'fusion-core' ),
			'desc' => __('In pixels (px)', 'fusion-core')
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[soundcloud url="{{url}}" layout="{{layout}}" comments="{{comments}}" show_related="{{show_related}}" show_user="{{show_user}}" auto_play="{{autoplay}}" color="{{color}}" width="{{width}}" height="{{height}}" class="{{class}}" id="{{id}}"]',
	'popup_title' => __( 'Sharing Box Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Table Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['table'] = array(
	'no_preview' => true,
	'params' => array(

		'type' => array(
			'type' => 'select',
			'label' => __( 'Type', 'fusion-core' ),
			'desc' => __( 'Select the table style', 'fusion-core' ),
			'options' => array(
				'1' => __('Style 1', 'fusion-core'),
				'2' => __('Style 2', 'fusion-core'),
			)
		),
		'columns' => array(
			'type' => 'select',
			'label' => __( 'Number of Columns', 'fusion-core' ),
			'desc' => __('Select how many columns to display', 'fusion-core'),
			'options' => array(
				'1' => '1 Column',
				'2' => '2 Columns',
				'3' => '3 Columns',
				'4' => '4 Columns',
				'5' => '5 Columns',
				'6' => '6 Columns'
			)
		)
	),
	'shortcode' => '',
	'popup_title' => __( 'Table Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Tabs Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['tabs'] = array(
	'no_preview' => true,
	'params' => array(
		'design' => array(
			'type' => 'select',
			'label' => __( 'Design', 'fusion-core' ),
			'desc' => __( 'Choose a design for the shortcode.', 'fusion-core' ),
			'options' => array(
				'classic' => __('Classic', 'fusion-core'),
				'clean' => __('Clean', 'fusion-core')
			)
		),
		'layout' => array(
			'type' => 'select',
			'label' => __( 'Layout', 'fusion-core' ),
			'desc' => __( 'Choose the layout of the shortcode', 'fusion-core' ),
			'options' => array(
				'horizontal' => __('Horizontal', 'fusion-core'),
				'vertical' => __('Vertical', 'fusion-core')
			)
		),
		'justified' => array(
			'type' => 'select',
			'label' => __( 'Justify Tabs', 'fusion-core' ),
			'desc' => __( 'Choose to get tabs stretched over full shortcode width.', 'fusion-core' ),
			'options' => $choices
		),
		'backgroundcolor' => array(
			'type' => 'colorpicker',
			'std' => '',
			'label' => __( 'Background Color', 'fusion-core' ),
			'desc' => __( 'Controls the background tab color.  Leave blank for theme option selection.', 'fusion-core' ),
		),
		'inactivecolor' => array(
			'type' => 'colorpicker',
			'std' => '',
			'label' => __( 'Inactive Color', 'fusion-core' ),
			'desc' => __( 'Controls the inactive tab color. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'bordercolor' => array(
			'type' => 'colorpicker',
			'std' => '',
			'label' => __( 'Border Color', 'fusion-core' ),
			'desc' => __( 'Controls the color of the outer tab border. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),

	'shortcode' => '[fusion_tabs design="{{design}}" layout="{{layout}}" justified="{{justified}}" backgroundcolor="{{backgroundcolor}}" inactivecolor="{{inactivecolor}}" bordercolor="{{bordercolor}}" class="{{class}}" id="{{id}}"]{{child_shortcode}}[/fusion_tabs]',
	'popup_title' => __( 'Insert Tab Shortcode', 'fusion-core' ),

	'child_shortcode' => array(
		'params' => array(
			'title' => array(
				'std' => __('Title', 'fusion-core'),
				'type' => 'text',
				'label' => __( 'Tab Title', 'fusion-core' ),
				'desc' => __( 'Title of the tab', 'fusion-core' ),
			),
			'icon' => array(
				'type' => 'iconpicker',
				'label' => __( 'Select Icon', 'fusion-core' ),
				'desc' => __( 'Display an icon next to tab title. Click an icon to select, click again to deselect.', 'fusion-core' ),
				'options' => $icons
			),
			'content' => array(
				'std' => __('Tab Content', 'fusion-core'),
				'type' => 'textarea',
				'label' => __( 'Tab Content', 'fusion-core' ),
				'desc' => __( 'Add the tabs content', 'fusion-core' )
			)
		),
		'shortcode' => '[fusion_tab title="{{title}}" icon="{{icon}}"]{{content}}[/fusion_tab]',
		'clone_button' => __( 'Add Tab', 'fusion-core' )
	)
);

/*-----------------------------------------------------------------------------------*/
/*	Tagline Box Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['taglinebox'] = array(
	'no_preview' => true,
	'params' => array(
		'backgroundcolor' => array(
			'type' => 'colorpicker',
			'std' => '',
			'label' => __( 'Background Color', 'fusion-core' ),
			'desc' => __( 'Controls the background color. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'shadow' => array(
			'type' => 'select',
			'label' => __( 'Shadow', 'fusion-core' ),
			'desc' => __( 'Show the shadow below the box', 'fusion-core' ),
			'options' => $reverse_choices
		),
		'shadowopacity' => array(
			'type' => 'select',
			'label' => __( 'Shadow Opacity', 'fusion-core' ),
			'desc' => __( 'Choose the opacity of the shadow', 'fusion-core' ),
			'options' => $dec_numbers
		),
		'border' => array(
			'std' => '1px',
			'type' => 'text',
			'label' => __( 'Border Size', 'fusion-core' ),
			'desc' => __( 'In pixels (px), ex: 1px', 'fusion-core' ),
		),
		'bordercolor' => array(
			'type' => 'colorpicker',
			'std' => '',
			'label' => __( 'Border Color', 'fusion-core' ),
			'desc' => __( 'Controls the border color. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'highlightposition' => array(
			'type' => 'select',
			'label' => __( 'Highlight Border Position', 'fusion-core' ),
			'desc' => __( 'Choose the position of the highlight. This border highlight is from theme options primary color and does not take the color from border color above', 'fusion-core' ),
			'options' => array(
				'top' => __('Top', 'fusion-core'),
				'bottom' => __('Bottom', 'fusion-core'),
				'left' => __('Left', 'fusion-core'),
				'right' => __('Right', 'fusion-core'),
				'none' => __('None', 'fusion-core'),
			)
		),
		'contentalignment' => array(
			'type' => 'select',
			'label' => __( 'Content Alignment', 'fusion-core' ),
			'desc' => __( 'Choose how the content should be displayed.', 'fusion-core' ),
			'options' => array(
				'left' => __('Left', 'fusion-core'),
				'center' => __('Center', 'fusion-core'),
				'right' => __('Right', 'fusion-core'),
			)
		),
		'button' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Button Text', 'fusion-core' ),
			'desc' => __( 'Insert the text that will display in the button', 'fusion-core' ),
		),
		'url' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Link', 'fusion-core' ),
			'desc' => __( 'The url the button will link to', 'fusion-core')
		),
		'target' => array(
			'type' => 'select',
			'label' => __( 'Link Target', 'fusion-core' ),
			'desc' => __( '_self = open in same window <br /> _blank = open in new window', 'fusion-core' ),
			'options' => array(
				'_self' => '_self',
				'_blank' => '_blank'
			)
		),
		'modal' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Modal Window Anchor', 'fusion-core' ),
			'desc' => __( 'Add the class name of the modal window you want to open on button click.', 'fusion-core' ),
		),
		'buttonsize' => array(
			'type' => 'select',
			'label' => __( 'Button Size', 'fusion-core' ),
			'desc' => __( 'Select the button\'s size.', 'fusion-core' ),
			'options' => array(
				'' => __('Default', 'fusion-core'),
				'small' => __('Small', 'fusion-core'),
				'medium' => __('Medium', 'fusion-core'),
				'large' => __('Large', 'fusion-core'),
				'xlarge' => __('XLarge', 'fusion-core'),
			)
		),
		'buttontype' => array(
			'type' => 'select',
			'label' => __( 'Button Type', 'fusion-core' ),
			'desc' => __( 'Select the button\'s type.', 'fusion-core' ),
			'options' => array(
				'' => __('Default', 'fusion-core'),
				'flat' => __('Flat', 'fusion-core'),
				'3d' => '3D',
			)
		),
		'buttonshape' => array(
			'type' => 'select',
			'label' => __( 'Button Shape', 'fusion-core' ),
			'desc' => __( 'Select the button\'s shape.', 'fusion-core' ),
			'options' => array(
				'' => __('Default', 'fusion-core'),
				'square' => __('Square', 'fusion-core'),
				'pill' => __('Pill', 'fusion-core'),
				'round' => __('Round', 'fusion-core'),
			)
		),
		'buttoncolor' => array(
			'type' => 'select',
			'label' => __( 'Button Color', 'fusion-core' ),
			'desc' => __( 'Choose the button color <br />Default uses theme option selection', 'fusion-core' ),
			'options' => array(
				'' => __('Default', 'fusion-core'),
				'green' => __('Green', 'fusion-core'),
				'darkgreen' => __('Dark Green', 'fusion-core'),
				'orange' => __('Orange', 'fusion-core'),
				'blue' => __('Blue', 'fusion-core'),
				'red' => __('Red', 'fusion-core'),
				'pink' => __('Pink', 'fusion-core'),
				'darkgray' => __('Dark Gray', 'fusion-core'),
				'lightgray' => __('Light Gray', 'fusion-core'),
			)
		),
		'title' => array(
			'type' => 'textarea',
			'label' => __( 'Tagline Title', 'fusion-core' ),
			'desc' => __( 'Insert the title text', 'fusion-core' ),
			'std' => __('Title', 'fusion-core')
		),
		'description' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __( 'Tagline Description', 'fusion-core' ),
			'desc' => __( 'Insert the description text', 'fusion-core' ),
		),
		'content' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __( 'Additional Content', 'fusion-core' ),
			'desc' => __( 'This is additional content you can add to the tagline box. This will show below the title and description if one is used.', 'fusion-core' ),
		),
		'margin_top' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Margin Top', 'fusion-core' ),
			'desc' => __( 'Add a custom top margin. In pixels.', 'fusion-core' )
		),
		'margin_bottom' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Margin Bottom', 'fusion-core' ),
			'desc' => __( 'Add a custom bottom margin. In pixels.', 'fusion-core' )
		),
		'animation_type' => array(
			'type' => 'select',
			'label' => __( 'Animation Type', 'fusion-core' ),
			'desc' => __( 'Select the type on animation to use on the shortcode', 'fusion-core' ),
			'options' => $animation_type,
		),
		'animation_direction' => array(
			'type' => 'select',
			'label' => __( 'Direction of Animation', 'fusion-core' ),
			'desc' => __( 'Select the incoming direction for the animation', 'fusion-core' ),
			'options' => $animation_direction,
		),
		'animation_speed' => array(
			'type' => 'select',
			'std' => '',
			'label' => __( 'Speed of Animation', 'fusion-core' ),
			'desc' => __( 'Type in speed of animation in seconds (0.1 - 1)', 'fusion-core' ),
			'options' => $dec_numbers,
		),
		'animation_offset' => array(
			'type' 		=> 'select',
			'std' 		=> '',
			'label' 	=> __( 'Offset of Animation', 'fusion-core' ),
			'desc' 		=> __( 'Choose when the animation should start.', 'fusion-core' ),
			'options' 	=> array(
					  			''					=> __( 'Default', 'fusion-core' ),
								'top-into-view' 	=> __( 'Top of element hits bottom of viewport', 'fusion-core' ),
								'top-mid-of-view' 	=> __( 'Top of element hits middle of viewport', 'fusion-core' ),
								'bottom-in-view' 	=> __( 'Bottom of element enters viewport', 'fusion-core' ),
							)
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[tagline_box backgroundcolor="{{backgroundcolor}}" shadow="{{shadow}}" shadowopacity="{{shadowopacity}}" border="{{border}}" bordercolor="{{bordercolor}}" highlightposition="{{highlightposition}}" content_alignment="{{contentalignment}}" link="{{url}}" linktarget="{{target}}" modal="{{modal}}" button_size="{{buttonsize}}" button_shape="{{buttonshape}}" button_type="{{buttontype}}" buttoncolor="{{buttoncolor}}" button="{{button}}" title="{{title}}" description="{{description}}" margin_top="{{margin_top}}" margin_bottom="{{margin_bottom}}" animation_type="{{animation_type}}" animation_direction="{{animation_direction}}" animation_speed="{{animation_speed}}" animation_offset="{{animation_offset}}" class="{{class}}" id="{{id}}"]{{content}}[/tagline_box]',
	'popup_title' => __( 'Insert Tagline Box Shortcode', 'fusion-core')
);

/*-----------------------------------------------------------------------------------*/
/*	Testimonials Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['testimonials'] = array(
	'no_preview' => true,
	'params' => array(
		'design' => array(
			'type' => 'select',
			'label' => __( 'Design', 'fusion-core' ),
			'desc' => __( 'Choose a design for the shortcode.', 'fusion-core' ),
			'options' => array(
				'classic' => __('Classic', 'fusion-core'),
				'clean' => __('Clean', 'fusion-core')
			)
		),
		'backgroundcolor' => array(
			'type' => 'colorpicker',
			'std' => '',
			'label' => __( 'Background Color', 'fusion-core' ),
			'desc' => __( 'Controls the background color.  Leave blank for theme option selection.', 'fusion-core' ),
		),
		'textcolor' => array(
			'type' => 'colorpicker',
			'std' => '',
			'label' => __( 'Text Color', 'fusion-core' ),
			'desc' => __( 'Controls the text color. Leave blank for theme option selection.', 'fusion-core' ),
		),
		'random' => array(
			'type' => 'select',
			'label' => __( 'Random Order', 'fusion-core' ),
			'desc' => __( 'Choose to display testimonials in random order.', 'fusion-core' ),
			'options' => array(
				'' => __( 'Default', 'fusion-core' ),
				'no' => __('No', 'fusion-core'),
				'yes' => __('Yes', 'fusion-core')
			)
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[testimonials design="{{design}}" backgroundcolor="{{backgroundcolor}}" textcolor="{{textcolor}}" random="{{random}}" class="{{class}}" id="{{id}}"]{{child_shortcode}}[/testimonials]',
	'popup_title' => __( 'Insert Testimonials Shortcode', 'fusion-core' ),

	'child_shortcode' => array(
		'params' => array(
			'name' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Name', 'fusion-core' ),
				'desc' => __( 'Insert the name of the person.', 'fusion-core' ),
			),
			'avatar' => array(
				'type' => 'select',
				'label' => __( 'Avatar', 'fusion-core' ),
				'desc' => __( 'Choose which kind of Avatar to be displayed.', 'fusion-core' ),
				'options' => array(
					'male' => __('Male', 'fusion-core'),
					'female' => __('Female', 'fusion-core'),
					'image' => __('Image', 'fusion-core'),
					'none' => __('None', 'fusion-core')
				)
			),
			'image' => array(
				'type' => 'uploader',
				'label' => __( 'Custom Avatar', 'fusion-core' ),
				'desc' => __( 'Upload a custom avatar image.', 'fusion-core' ),
			),
			'image_border_radius' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Border Radius', 'fusion-core' ),
				'desc' => __( 'Choose the radius of the testimonial image. In pixels (px), ex: 1px, or "round".  Leave blank for theme option selection.', 'fusion-core' ),
			),
			'company' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Company', 'fusion-core' ),
				'desc' => __( 'Insert the name of the company.', 'fusion-core' ),
			),
			'link' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Link', 'fusion-core' ),
				'desc' => __( 'Add the url the company name will link to.', 'fusion-core' ),
			),
			'target' => array(
				'type' => 'select',
				'label' => __( 'Target', 'fusion-core' ),
				'desc' => __( '_self = open in same window <br />_blank = open in new window.', 'fusion-core' ),
				'options' => array(
					'_self' => '_self',
					'_blank' => '_blank'
				)
			),
			'content' => array(
				'std' => '',
				'type' => 'textarea',
				'label' => __( 'Testimonial Content', 'fusion-core' ),
				'desc' => __( 'Add the testimonial content', 'fusion-core' ),
			)
		),
		'shortcode' => '[testimonial name="{{name}}" avatar="{{avatar}}" image="{{image}}" image_border_radius="{{image_border_radius}}" company="{{company}}" link="{{link}}" target="{{target}}"]{{content}}[/testimonial]',
		'clone_button' => __( 'Add Testimonial', 'fusion-core' )
	)
);

/*-----------------------------------------------------------------------------------*/
/*	Title Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['title'] = array(
	'no_preview' => true,
	'params' => array(
		'size' => array(
			'type' => 'select',
			'label' => __( 'Title Size', 'fusion-core' ),
			'desc' => __( 'Choose the title size, H1-H6', 'fusion-core' ),
			'options' => fusion_shortcodes_range( 6, false )
		),
		'contentalign' => array(
			'type' => 'select',
			'label' => __( 'Title Alignment', 'fusion-core' ),
			'desc' => __( 'Choose to align the heading left or right.', 'fusion-core' ),
			'options' => array(
				'left' => __('Left', 'fusion-core'),
				'center' => __('Center', 'fusion-core'),
				'right' => __('Right', 'fusion-core')
			)
		),
		'style_type' => array(
			'type' => 'select',
			'label' => __( 'Separator', 'fusion-core' ),
			'desc' => __( 'Choose the kind of the title separator you want to use.', 'fusion-core' ),
			'options' => array(
				'default'			=> __('Default', 'fusion-core'),
				'single'		  	=> __('Single', 'fusion-core'),
				'single solid'		=> __('Single Solid', 'fusion-core'),
				'single dashed'		=> __('Single Dashed', 'fusion-core'),
				'single dotted'		=> __('Single Dotted', 'fusion-core'),
				'double'	 		=> __('Double', 'fusion-core'),
				'double solid'	 	=> __('Double Solid', 'fusion-core'),
				'double dashed'	 	=> __('Double Dashed', 'fusion-core'),
				'double dotted'	 	=> __('Double Dotted', 'fusion-core'),
				'underline'			=> __('Underline', 'fusion-core'),
				'underline solid'	=> __('Underline Solid', 'fusion-core'),
				'underline dashed'	=> __('Underline Dashed', 'fusion-core'),
				'underline dotted'	=> __('Underline Dotted', 'fusion-core'),
				'none'				=> __('None', 'fusion-core')
			)
		),
		'sepcolor' => array(
			'type' => 'colorpicker',
			'label' => __( 'Separator Color', 'fusion-core' ),
			'desc' => __( 'Controls the separator color.  Leave blank for theme option selection.', 'fusion-core')
		),
		'margin_top' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Top Margin', 'fusion-core' ),
			'desc' => __( 'Spacing above the title. In px or em, e.g. 10px.', 'fusion-core' )
		),
		'margin_bottom' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Bottom Margin', 'fusion-core' ),
			'desc' => __( 'Spacing below the title. In px or em, e.g. 10px.', 'fusion-core' )
		),
		'content' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __( 'Title', 'fusion-core' ),
			'desc' => __( 'Insert the title text', 'fusion-core' ),
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[title size="{{size}}" content_align="{{contentalign}}" style_type="{{style_type}}" sep_color="{{sepcolor}}" margin_top="{{margin_top}}" margin_bottom="{{margin_bottom}}" class="{{class}}" id="{{id}}"]{{content}}[/title]',
	'popup_title' => __( 'Sharing Box Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Toggles Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['toggles'] = array(
	'no_preview' => true,
	'params' => array(
		'divider_line' => array(
			'std' => 'default',
			'type' => 'select',
			'label' => __( 'Divider Line', 'fusion-core' ),
			'desc' => __( 'Choose to display a divider line between each item.', 'fusion-core' ),
			'options' => array(
				'' 		=> 'Default',
				'yes'	=> 'Yes',
				'no'	=> 'No'
			)
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[accordian divider_line="{{divider_line}}" class="{{class}}" id="{{id}}"]{{child_shortcode}}[/accordian]',
	'popup_title' => __( 'Insert Toggles Shortcode', 'fusion-core' ),

	'child_shortcode' => array(
		'params' => array(
			'title' => array(
				'std' => '',
				'type' => 'text',
				'label' => __( 'Title', 'fusion-core' ),
				'desc' => __( 'Insert the toggle title', 'fusion-core' ),
			),
			'open' => array(
				'type' => 'select',
				'label' => __( 'Open by Default', 'fusion-core' ),
				'desc' => __( 'Choose to have the toggle open when page loads', 'fusion-core' ),
				'options' => $reverse_choices
			),
			'content' => array(
				'std' => '',
				'type' => 'textarea',
				'label' => __( 'Toggle Content', 'fusion-core' ),
				'desc' => __( 'Insert the toggle content', 'fusion-core' ),
			)
		),
		'shortcode' => '[toggle title="{{title}}" open="{{open}}"]{{content}}[/toggle]',
		'clone_button' => __( 'Add Toggle', 'fusion-core')
	)
);

/*-----------------------------------------------------------------------------------*/
/*	Tooltip Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['tooltip'] = array(
	'no_preview' => true,
	'params' => array(

		'title' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Tooltip Text', 'fusion-core' ),
			'desc' => __( 'Insert the text that displays in the tooltip', 'fusion-core' )
		),
		'placement' => array(
			'type' => 'select',
			'label' => __( 'Tooltip Position', 'fusion-core' ),
			'desc' => __( 'Choose the display position.', 'fusion-core' ),
			'options' => array(
				'top' => __('Top', 'fusion-core'),
				'bottom' => __('Bottom', 'fusion-core'),
				'left' => __('Left', 'fusion-core'),
				'Right' => __('Right', 'fusion-core'),
			)
		),
		'trigger' => array(
			'type' => 'select',
			'label' => __( 'Tooltip Trigger', 'fusion-core' ),
			'desc' => __( 'Choose action to trigger the tooltip.', 'fusion-core' ),
			'options' => array(
				'hover' => __('Hover', 'fusion-core'),
				'click' => __('Click', 'fusion-core'),
			)
		),
		'content' => array(
			'std' => '',
			'type' => 'textarea',
			'label' => __( 'Content', 'fusion-core' ),
			'desc' => __( 'Insert the text that will activate the tooltip hover', 'fusion-core' )
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[tooltip title="{{title}}" placement="{{placement}}" trigger="{{trigger}}" class="{{class}}" id="{{id}}"]{{content}}[/tooltip]',
	'popup_title' => __( 'Tooltip Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Vimeo Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['vimeo'] = array(
	'no_preview' => true,
	'params' => array(

		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Video ID', 'fusion-core' ),
			'desc' => __( 'For example the Video ID for <br />https://vimeo.com/75230326 is 75230326', 'fusion-core' )
		),
		'width' => array(
			'std' => '600',
			'type' => 'text',
			'label' => __( 'Width', 'fusion-core' ),
			'desc' => __( 'In pixels but only enter a number, ex: 600', 'fusion-core' )
		),
		'height' => array(
			'std' => '350',
			'type' => 'text',
			'label' => __( 'Height', 'fusion-core' ),
			'desc' => __( 'In pixels but enter a number, ex: 350', 'fusion-core' )
		),
		'autoplay' => array(
			'type' => 'select',
			'label' => __( 'Autoplay Video', 'fusion-core' ),
			'desc' =>  __( 'Set to yes to make video autoplaying', 'fusion-core' ),
			'options' => $reverse_choices
		),
		'apiparams' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'AdditionalAPI Parameter', 'fusion-core' ),
			'desc' => __( 'Use additional API parameter, for example &title=0 to disable title on video. VimeoPlus account may be required.', 'fusion-core' )
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[vimeo id="{{id}}" width="{{width}}" height="{{height}}" autoplay="{{autoplay}}" api_params="{{apiparams}}" class="{{class}}"]',
	'popup_title' => __( 'Vimeo Shortcode', 'fusion-core' )
);


/*-----------------------------------------------------------------------------------*/
/*	Widget Area Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['fusion_widget_area'] = array(
	'params' => array(
		'name' => array(
			'std' => '',
			'type' => 'select',
			'label' => __( 'Widget Area Name', 'fusion-core' ),
			'desc' => __( 'Choose a background color for the widget area.', 'fusion-core'),
			'options' => get_sidebars()
		),
		'background_color' => array(
			'type' => 'colorpicker',
			'label' => __( 'Background Color', 'fusion-core' ),
			'desc' => __( 'Choose a background color for the widget area.', 'fusion-core')
		),
		'padding' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Padding', 'fusion-core' ),
			'desc' => __( 'In pixels or percentage, ex: 10px or 10%.', 'fusion-core')
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core')
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core')
		),
	),
	'shortcode' => '[fusion_widget_area name="{{name}}" background_color="{{background_color}}" padding="{{padding}}" class="{{class}}" id="{{id}}"][/fusion_widget_area]', // as there is no wrapper shortcode
	'popup_title' => __( 'Widget Area Shortcode', 'fusion-core' ),
	'no_preview' => true
);

/*-----------------------------------------------------------------------------------*/
/*	Woo Featured Slider Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['woofeatured'] = array(
	'no_preview' => true,
	'params' => array(
		'picture_size' => array(
			'type' => 'select',
			'label' => __( 'Picture Size', 'fusion-core' ),
			'desc' => __( 'fixed = width and height will be fixed <br />auto = width and height will adjust to the image.', 'fusion-core' ),
			'options' => array(
				'auto' => __('Auto', 'fusion-core'),
				'fixed' => __('Fixed', 'fusion-core'),
			)
		),
		'carousel_layout' => array(
			'type' => 'select',
			'label' => __( 'Carousel Layout', 'fusion-core' ),
			'desc' => __( 'Choose to show titles on rollover image, or below image.', 'fusion-core' ),
			'options' => array(
				'title_on_rollover' => __('Title on rollover', 'fusion-core'),
				'title_below_image' => __('Title below image', 'fusion-core'),
			)
		),
		'autoplay' => array(
			'type' => 'select',
			'label' => __( 'Autoplay', 'fusion-core' ),
			'desc' => __('Choose to autoplay the carousel.', 'fusion-core'),
			'options' => $reverse_choices
		),
		'columns' => array(
			'std' => '5',
			'type' => 'select',
			'label' => __( 'Maximum Columns', 'fusion-core' ),
			'desc' => __('Select the number of max columns to display.', 'fusion-core'),
			'options' => fusion_shortcodes_range( 6, false )
		),
		'column_spacing' => array(
			'std' => '10',
			'type' => 'text',
			'label' => __( 'Column Spacing', 'fusion-core' ),
			"desc" => __("Insert the amount of spacing between items without 'px'. ex: 13.", "fusion-core"),
		),
		'scroll_items' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Scroll Items', 'fusion-core' ),
			"desc" => __("Insert the amount of items to scroll. Leave empty to scroll number of visible items.", "fusion-core"),
		),
		'show_nav' => array(
			'type' => 'select',
			'label' => __( 'Show Navigation', 'fusion-core' ),
			'desc' => __( 'Choose to show navigation buttons on the carousel.', 'fusion-core' ),
			'options' => $choices
		),
		'mouse_scroll' => array(
			'type' => 'select',
			'label' => __( 'Mouse Scroll', 'fusion-core' ),
			'desc' => __( 'Choose to enable mouse drag control on the carousel.', 'fusion-core' ),
			'options' => $reverse_choices
		),
		'show_cats' => array(
			'type' => 'select',
			'label' => __( 'Show Categories', 'fusion-core' ),
			'desc' => __('Choose to show or hide the categories', 'fusion-core'),
			'options' => $reverse_choices
		),
		'show_price' => array(
			'type' => 'select',
			'label' => __( 'Show Price', 'fusion-core' ),
			'desc' => __('Choose to show or hide the price', 'fusion-core'),
			'options' => $reverse_choices
		),
		'show_buttons' => array(
			'type' => 'select',
			'label' => __( 'Show Buttons', 'fusion-core' ),
			'desc' => __('Choose to show or hide the icon buttons', 'fusion-core'),
			'options' => $reverse_choices
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[featured_products_slider picture_size="{{picture_size}}" carousel_layout="{{carousel_layout}}" autoplay="{{autoplay}}" columns="{{columns}}" column_spacing="{{column_spacing}}" scroll_items="{{scroll_items}}" show_nav="{{show_nav}}" mouse_scroll="{{mouse_scroll}}" show_price="{{show_price}}" show_buttons="{{show_buttons}}" class="{{class}}" id="{{id}}"]',
	'popup_title' => __( 'Woocommerce Featured Products Slider Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Woo Products Slider Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['wooproducts'] = array(
	'params' => array(
		'picture_size' => array(
			'type' => 'select',
			'label' => __( 'Picture Size', 'fusion-core' ),
			'desc' => __( 'fixed = width and height will be fixed <br />auto = width and height will adjust to the image.', 'fusion-core' ),
			'options' => array(
				'fixed' => __('Fixed', 'fusion-core'),
				'auto' => __('Auto', 'fusion-core')
			)
		),
		'cat_slug' => array(
			'type' => 'multiple_select',
			'label' => __( 'Categories', 'fusion-core' ),
			'desc' => __( 'Select a category or leave blank for all', 'fusion-core' ),
			'options' => fusion_shortcodes_categories( 'product_cat' )
		),
		'number_posts' => array(
			'std' => 5,
			'type' => 'text',
			'label' => __( 'Number of Products', 'fusion-core' ),
			'desc' => __('Select the number of products to display', 'fusion-core')
		),
		'carousel_layout' => array(
			'type' => 'select',
			'label' => __( 'Carousel Layout', 'fusion-core' ),
			'desc' => __( 'Choose to show titles on rollover image, or below image.', 'fusion-core' ),
			'options' => array(
				'title_on_rollover' => __('Title on rollover', 'fusion-core'),
				'title_below_image' => __('Title below image', 'fusion-core'),
			)
		),
		'autoplay' => array(
			'type' => 'select',
			'label' => __( 'Autoplay', 'fusion-core' ),
			'desc' => __('Choose to autoplay the carousel.', 'fusion-core'),
			'options' => $reverse_choices
		),
		'columns' => array(
			'std' => '5',
			'type' => 'select',
			'label' => __( 'Maximum Columns', 'fusion-core' ),
			'desc' => __('Select the number of max columns to display.', 'fusion-core'),
			'options' => fusion_shortcodes_range( 6, false )
		),
		'column_spacing' => array(
			'std' => '13',
			'type' => 'text',
			'label' => __( 'Column Spacing', 'fusion-core' ),
			"desc" => __("Insert the amount of spacing between items without 'px'. ex: 13.", "fusion-core"),
		),
		'scroll_items' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Scroll Items', 'fusion-core' ),
			"desc" => __("Insert the amount of items to scroll. Leave empty to scroll number of visible items.", "fusion-core"),
		),
		'show_nav' => array(
			'type' => 'select',
			'label' => __( 'Show Navigation', 'fusion-core' ),
			'desc' => __( 'Choose to show navigation buttons on the carousel.', 'fusion-core' ),
			'options' => $choices
		),
		'mouse_scroll' => array(
			'type' => 'select',
			'label' => __( 'Mouse Scroll', 'fusion-core' ),
			'desc' => __( 'Choose to enable mouse drag control on the carousel.', 'fusion-core' ),
			'options' => $reverse_choices
		),
		'show_cats' => array(
			'type' => 'select',
			'label' => __( 'Show Categories', 'fusion-core' ),
			'desc' => __('Choose to show or hide the categories', 'fusion-core'),
			'options' => $choices
		),
		'show_price' => array(
			'type' => 'select',
			'label' => __( 'Show Price', 'fusion-core' ),
			'desc' => __('Choose to show or hide the price', 'fusion-core'),
			'options' => $choices
		),
		'show_buttons' => array(
			'type' => 'select',
			'label' => __( 'Show Buttons', 'fusion-core' ),
			'desc' => __('Choose to show or hide the icon buttons', 'fusion-core'),
			'options' => $choices
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[products_slider picture_size="{{picture_size}}" cat_slug="{{cat_slug}}" number_posts="{{number_posts}}" carousel_layout="{{carousel_layout}}" autoplay="{{autoplay}}" columns="{{columns}}" column_spacing="{{column_spacing}}" scroll_items="{{scroll_items}}" show_nav="{{show_nav}}" mouse_scroll="{{mouse_scroll}}" show_price="{{show_price}}" show_buttons="{{show_buttons}}" class="{{class}}" id="{{id}}"]',
	'popup_title' => __( 'Woocommerce Products Slider Shortcode', 'fusion-core' ),
	'no_preview' => true,
);

/*-----------------------------------------------------------------------------------*/
/*	Youtube Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['youtube'] = array(
	'no_preview' => true,
	'params' => array(

		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'Video ID', 'fusion-core' ),
			'desc' => __('For example the Video ID for <br />http://www.youtube.com/LOfeCR7KqUs is LOfeCR7KqUs', 'fusion-core')
		),
		'width' => array(
			'std' => '600',
			'type' => 'text',
			'label' => __( 'Width', 'fusion-core' ),
			'desc' => __('In pixels but only enter a number, ex: 600', 'fusion-core')
		),
		'height' => array(
			'std' => '350',
			'type' => 'text',
			'label' => __( 'Height', 'fusion-core' ),
			'desc' => __('In pixels but only enter a number, ex: 350', 'fusion-core')
		),
		'autoplay' => array(
			'type' => 'select',
			'label' => __( 'Autoplay Video', 'fusion-core' ),
			'desc' =>  __( 'Set to yes to make video autoplaying', 'fusion-core' ),
			'options' => $reverse_choices
		),
		'apiparams' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'AdditionalAPI Parameter', 'fusion-core' ),
			'desc' => __('Use additional API parameter, for example &rel=0 to disable related videos', 'fusion-core')
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[youtube id="{{id}}" width="{{width}}" height="{{height}}" autoplay="{{autoplay}}" api_params="{{apiparams}}" class="{{class}}"]',
	'popup_title' => __( 'Youtube Shortcode', 'fusion-core' )
);

/*-----------------------------------------------------------------------------------*/
/*	Fusion Slider Config
/*-----------------------------------------------------------------------------------*/

$fusion_shortcodes['fusionslider'] = array(
	'no_preview' => true,
	'params' => array(
		'name' => array(
			'type' => 'select',
			'label' => __( 'Slider Name', 'fusion-core' ),
			'desc' => __( 'This is the shortcode name that can be used in the post content area. It is usually all lowercase and contains only letters, numbers, and hyphens. ex: "fusionslider_slidernamehere"', 'fusion-core' ),
			'options' => fusion_shortcodes_categories( 'slide-page' )
		),
		'class' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS Class', 'fusion-core' ),
			'desc' => __( 'Add a class to the wrapping HTML element.', 'fusion-core' )
		),
		'id' => array(
			'std' => '',
			'type' => 'text',
			'label' => __( 'CSS ID', 'fusion-core' ),
			'desc' => __( 'Add an ID to the wrapping HTML element.', 'fusion-core' )
		),
	),
	'shortcode' => '[fusionslider id="{{id}}" class="{{class}}" name="{{name}}"][/fusionslider]',
	'popup_title' => __( 'Fusion Slider Shortcode', 'fusion-core' )
);