<?php

$this->select(
	'show_first_featured_image',
	esc_html__( 'Disable First Featured Image', 'Avada' ),
	array(
		'no'  => esc_html__( 'No', 'Avada' ),
		'yes' => esc_html__( 'Yes', 'Avada' )
	),
	esc_html__( 'Disable the 1st featured image on single post pages.', 'Avada' )
);

$this->select(
	'portfolio_width_100',
	esc_html__( 'Use 100% Width Page', 'Avada' ),
	array(
		'default' 	=> esc_html__( 'Default', 'Avada' ),
		'no'  		=> esc_html__( 'No', 'Avada' ),
		'yes' 		=> esc_html__( 'Yes', 'Avada' )
	),
	esc_html__( 'Choose to set this post to 100% browser width.', 'Avada' )
);

$this->textarea(
	'video',
	esc_html__( 'Video Embed Code', 'Avada' ),
	esc_html__( 'Insert Youtube or Vimeo embed code.', 'Avada' )
);

$this->text(
	'fimg_width',
	esc_html__( 'Featured Image Width', 'Avada' ),
	esc_html__( 'In pixels or percentage, ex: 100% or 100px. Use "auto" if you set a fixed height, to make sure the image is resized respecting the aspect ratio. Value cannot exceed original image width.', 'Avada' )
);

$this->text(
	'fimg_height',
	esc_html__( 'Featured Image Height', 'Avada' ),
	esc_html__( 'In pixels or percentage, ex: 100% or 100px. Use "auto" if you set a fixed height, to make sure the image is resized respecting the aspect ratio. Value cannot exceed original image height.', 'Avada' )
);

$this->select(
	'image_rollover_icons',
	esc_html__( 'Image Rollover Icons', 'Avada' ),
	array(
		'default'  => esc_html__( 'Default', 'Avada' ),
		'linkzoom' => esc_html__( 'Link + Zoom', 'Avada' ),
		'link'     => esc_html__( 'Link', 'Avada' ),
		'zoom'     => esc_html__( 'Zoom', 'Avada' ),
		'no'       => esc_html__( 'No Icons', 'Avada' )
	),
	esc_html__( 'Choose which icons display on this post.', 'Avada' )
);

$this->text(
	'link_icon_url',
	esc_html__( 'Link Icon URL', 'Avada' ),
	esc_html__( 'Leave blank for post URL.', 'Avada' )
);

$this->select(
	'post_links_target',
	esc_html__( 'Open Post Links In New Window', 'Avada' ),
	array(
		'no'  => esc_html__( 'No', 'Avada' ),
		'yes' => esc_html__( 'Yes', 'Avada' )
	),
	esc_html__( 'Choose to open the single post page link in a new window.', 'Avada' )
);

$this->select(
	'related_posts',
	esc_html__( 'Show Related Posts', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'yes'     => esc_html__( 'Show', 'Avada' ),
		'no'      => esc_html__( 'Hide', 'Avada' )
	),
	esc_html__( 'Choose to show or hide related posts on this post.', 'Avada' )
);

$this->select(
	'share_box',
	esc_html__( 'Show Social Share Box', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'yes'     => esc_html__( 'Show', 'Avada' ),
		'no'      => esc_html__( 'Hide', 'Avada' )
	),
	esc_html__( 'Choose to show or hide the social share box', 'Avada' )
);

$this->select(
	'post_pagination',
	esc_html__( 'Show Previous/Next Pagination', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'yes'     => esc_html__( 'Show', 'Avada' ),
		'no'      => esc_html__( 'Hide', 'Avada' )
	),
	esc_html__( 'Choose to show or hide the post navigation', 'Avada' )
);

$this->select(
	'author_info',
	esc_html__( 'Show Author Info Box', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'yes'     => esc_html__( 'Show', 'Avada' ),
		'no'      => esc_html__( 'Hide', 'Avada' )
	),
	esc_html__( 'Choose to show or hide the author info box', 'Avada' )
);

$this->select(
	'post_meta',
	esc_html__( 'Show Post Meta', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'yes'     => esc_html__( 'Show', 'Avada' ),
		'no'      => esc_html__( 'Hide', 'Avada' )
	),
	esc_html__( 'Choose to show or hide the post meta', 'Avada' )
);

$this->select(
	'post_comments',
	esc_html__( 'Show Comments', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'yes'     => esc_html__( 'Show', 'Avada' ),
		'no'      => esc_html__( 'Hide', 'Avada' )
	),
	esc_html__( 'Choose to show or hide comments area', 'Avada' )
);

// Omit closing PHP tag to avoid "Headers already sent" issues.
