<?php

$this->select(
	'width',
	esc_html__( 'Width (Content Columns for Featured Image)', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'full'    => esc_html__( 'Full Width', 'Avada' ),
		'half'    => esc_html__( 'Half Width', 'Avada' )
	),
	esc_html__( 'Choose if the featured image is full or half width.', 'Avada' )
);

$this->select(
	'portfolio_width_100',
	esc_html__( 'Use 100% Width Page', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'no'      => esc_html__( 'No', 'Avada' ),
		'yes'     => esc_html__( 'Yes', 'Avada' )
	),
	esc_html__( 'Choose to set this post to 100% browser width.', 'Avada' )
);

$this->select(
	'project_desc_title',
	esc_html__( 'Show Project Description Title', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'yes'     => esc_html__( 'Yes', 'Avada' ),
		'no'      => esc_html__( 'No', 'Avada' )
	),
	esc_html__( 'Choose to show or hide the project description title.', 'Avada' )
);

$this->select(
	'project_details',
	esc_html__( 'Show Project Details', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'yes'     => esc_html__( 'Yes', 'Avada' ),
		'no'      => esc_html__( 'No', 'Avada' )
	),
	esc_html__( 'Choose to show or hide the project details text.', 'Avada' )
);

$this->select(
	'show_first_featured_image',
	esc_html__( 'Disable First Featured Image', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'no'      => esc_html__( 'No', 'Avada' ),
		'yes'     => esc_html__( 'Yes', 'Avada' )
	),
	esc_html__( 'Disable the 1st featured image on single post pages.', 'Avada' )
);

$this->textarea(
	'video',
	esc_html__( 'Video Embed Code', 'Avada' ),
	esc_html__( 'Insert Youtube or Vimeo embed code.', 'Avada' )
);

$this->text(
	'video_url',
	esc_html__( 'Youtube/Vimeo Video URL for Lightbox', 'Avada' ),
	esc_html__( 'Insert the video URL that will show in the lightbox.', 'Avada' )
);

$this->text(
	'project_url',
	esc_html__( 'Project URL', 'Avada' ),
	esc_html__( 'The URL the project text links to.', 'Avada' )
);

$this->text(
	'project_url_text',
	esc_html__( 'Project URL Text', 'Avada' ),
	esc_html__( 'The custom project text that will link.', 'Avada' )
);

$this->text(
	'copy_url',
	esc_html__( 'Copyright URL', 'Avada' ),
	esc_html__( 'The URL the copyright text links to.', 'Avada' )
);

$this->text(
	'copy_url_text',
	esc_html__( 'Copyright URL Text', 'Avada' ),
	esc_html__( 'The custom copyright text that will link.', 'Avada' )
);

$this->text(
	'fimg_width',
	esc_html__( 'Featured Image Width', 'Avada' ),
	esc_html__( 'In pixels or percentage, ex: 100% or 100px. Or Use "auto" for automatic resizing if you added either width or height.', 'Avada' )
);

$this->text(
	'fimg_height',
	esc_html__( 'Featured Image Height', 'Avada' ),
	esc_html__( 'In pixels or percentage, ex: 100% or 100px. Or Use "auto" for automatic resizing if you added either width or height.', 'Avada' )
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
	'link_icon_target',
	esc_html__( 'Open Post Links In New Window', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'no'      => esc_html__( 'No', 'Avada' ),
		'yes'     => esc_html__( 'Yes', 'Avada' )
	),
	esc_html__( 'Choose to open the single post page, project url and copyright url links in a new window.', 'Avada' )
);

$this->select(
	'related_posts',
	esc_html__( 'Show Related Projects', 'Avada' ),
	array(
		'default' => esc_html__( 'Default', 'Avada' ),
		'yes'     => esc_html__( 'Show', 'Avada' ),
		'no'      => esc_html__( 'Hide', 'Avada' )
	),
	esc_html__( 'Choose to show or hide related projects on this post.', 'Avada' )
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

// Omit closing PHP tag to avoid "Headers already sent" issues.
