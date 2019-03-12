<div class='pyre_metabox'>

	<?php

	$this->select(
		'type',
		esc_html__( 'Background Type', 'Avada' ),
		array(
			'image'             => esc_html__( 'Image', 'Avada' ),
			'self-hosted-video' => esc_html__( 'Self-Hosted Video', 'Avada' ),
			'youtube'           => esc_html__( 'Youtube', 'Avada' ),
			'vimeo'             => esc_html__( 'Vimeo', 'Avada' )
		),
		esc_html__( 'Select an image or video slide. If using an image, please select the image in the "Featured Image" box on the right hand side.', 'Avada' )
	);
	?>

	<div class="video_settings" style="display: none;">

		<h2><?php _e( 'Video Options:', 'Avada' ); ?></h2>

		<?php
		$this->text(
			'youtube_id',
			esc_html__( 'Youtube Video ID', 'Avada' ),
			__( 'For example the Video ID for http://www.youtube.com/<strong>LOfeCR7KqUs</strong> is <strong>LOfeCR7KqUs</strong>', 'Avada' )
		);
		$this->text(
			'vimeo_id',
			esc_html__( 'Vimeo Video ID', 'Avada' ),
			sprintf( esc_html__( 'For example the Video ID for %1s is %2s', 'Avada' ), 'http://vimeo.com/<strong>75230326</strong>', '<strong>75230326</strong>' )
		);
		$this->upload(
			'webm',
			esc_html__( 'Video WebM Upload', 'Avada' ),
			esc_html__( 'Video must be in a 16:9 aspect ratio. Add your WebM video file. WebM and MP4 format must be included to render your video with cross browser compatibility. OGV is optional.', 'Avada' )
		);
		$this->upload(
			'mp4',
			esc_html__( 'Video MP4 Upload', 'Avada' ),
			esc_html__( 'Video must be in a 16:9 aspect ratio. Add your MP4 video file. MP4 and WebM format must be included to render your video with cross browser compatibility. OGV is optional.', 'Avada' )
		);
		$this->upload(
			'ogv',
			esc_html__( 'Video OGV Upload', 'Avada' ),
			esc_html__( 'Add your OGV video file. This is optional.', 'Avada' )
		);
		$this->upload(
			'preview_image',
			esc_html__( 'Video Preview Image', 'Avada' ),
			esc_html__( 'IMPORTANT: This field must be used for self hosted videos. Self hosted videos do not work correctly on mobile devices. The preview image will be seen in place of your video on older browsers or mobile devices.', 'Avada' )
		);
		$this->text(
			'aspect_ratio',
			esc_html__( 'Video Aspect Ratio', 'Avada' ),
			esc_html__( 'The video will be resized to maintain this aspect ratio, this is to prevent the video from showing any black bars. Enter an aspect ratio here such as: "16:9", "4:3" or "16:10". The default is "16:9"', 'Avada' )
		);
		$this->text(
			'video_bg_color',
			esc_html__( 'Video Color Overlay', 'Avada' ),
			__( 'Select a color to show over the video as an overlay. Hex color code, <strong>ex: #fff</strong>', 'Avada' )
		);
		$this->select(
			'mute_video',
			esc_html__( 'Mute Video', 'Avada' ),
			array( 'yes' => esc_html__( 'Yes', 'Avada' ), 'no' => esc_html__( 'No', 'Avada' ) ),
			''
		);
		$this->select(
			'autoplay_video',
			esc_html__( 'Autoplay Video', 'Avada' ),
			array( 'yes' => esc_html__( 'Yes', 'Avada' ), 'no' => esc_html__( 'No', 'Avada' ) ),
			''
		);
		$this->select(
			'loop_video',
			esc_html__( 'Loop Video', 'Avada' ),
			array( 'yes' => esc_html__( 'Yes', 'Avada' ), 'no' => esc_html__( 'No', 'Avada' ) ),
			''
		);
		$this->select(
			'hide_video_controls',
			esc_html__( 'Hide Video Controls', 'Avada' ),
			array( 'yes' => esc_html__( 'Yes', 'Avada' ), 'no' => esc_html__( 'No', 'Avada' ) ),
			esc_html__( 'If this is set to yes, then autoplay must be enabled for the video to work.', 'Avada' )
		);
		?>

	</div>

	<h2><?php esc_html_e( 'Slider Content Settings:', 'Avada' ); ?></h2>

	<?php

	$this->select(
		'content_alignment',
		esc_html__( 'Content Alignment', 'Avada' ),
		array( 'left' => esc_html__( 'Left', 'Avada' ), 'center' => esc_html__( 'Center', 'Avada' ), 'right' => esc_html__( 'Right', 'Avada' ) ),
		esc_html__( 'Select how the heading, caption and buttons will be aligned.', 'Avada' )
	);
	$this->textarea(
		'heading',
		esc_html__( 'Heading Area', 'Avada' ),
		esc_html__( 'Enter the heading for your slide. This field can take HTML markup and Fusion Shortcodes.', 'Avada' )
	);
	$this->select(
		'heading_separator',
		esc_html__( 'Heading Separator', 'Avada' ),
		array(
			'none'				=> esc_html__( 'None', 'Avada' ),
			'single solid'		=> esc_html__( 'Single Solid', 'Avada' ),
			'single dashed'		=> esc_html__( 'Single Dashed', 'Avada' ),
			'single dotted'		=> esc_html__( 'Single Dotted', 'Avada' ),
			'double solid'	 	=> esc_html__( 'Double Solid', 'Avada' ),
			'double dashed'		=> esc_html__( 'Double Dashed', 'Avada' ),
			'double dotted'		=> esc_html__( 'Double Dotted', 'Avada' ),
			'underline solid'	=> esc_html__( 'Underline Solid', 'Avada' ),
			'underline dashed'	=> esc_html__( 'Underline Dashed', 'Avada' ),
			'underline dotted'	=> esc_html__( 'Underline Dotted', 'Avada' ),
		),
		esc_html__( 'Choose the heading separator you want to use.', 'Avada' )
	);
	$default = sprintf( esc_html__( 'Default: %s', 'Avada' ), '60' );
	$this->text(
		'heading_font_size',
		esc_html__( 'Heading Font Size', 'Avada' ),
		sprintf( esc_html__( 'Enter heading font size without px unit. In pixels, ex: 50 instead of 50px. %s', 'Avada' ), '<strong>' . $default . '</strong>' )
	);
	$default = sprintf( esc_html__( 'Default: %s', 'Avada' ), '#fff' );
	$this->text(
		'heading_color',
		esc_html__( 'Heading Color', 'Avada' ),
		sprintf( esc_html__( 'Select a color for the heading font. Hex color code, ex: #fff. %s', 'Avada' ), '<strong>' . $default . '</strong>' )
	);
	$this->select(
		'heading_bg',
		esc_html__( 'Heading Background', 'Avada' ),
		array( 'yes' => esc_html__( 'Yes', 'Avada' ), 'no' => esc_html__( 'No', 'Avada' ) ),
		esc_html__( 'Select this option if you would like a semi-transparent background behind your heading.', 'Avada' )
	);
	$default = sprintf( esc_html__( 'Default: %s', 'Avada' ), '#000' );
	$this->text(
		'heading_bg_color',
		esc_html__( 'Heading Background Color', 'Avada' ),
		sprintf( esc_html__( 'Select a color for the heading background. Hex color code, ex: #000. %s', 'Avada' ), '<strong>' . $default . '</strong>' )
	);
	$this->textarea(
		'caption',
		esc_html__( 'Caption Area', 'Avada' ),
		esc_html__( 'Enter the caption for your slide. This field can take HTML markup and Fusion Shortcodes.', 'Avada' )
	);
	$this->select(
		'caption_separator',
		esc_html__( 'Caption Separator', 'Avada' ),
		array(
			'none'				=> esc_html__( 'None', 'Avada' ),
			'single solid'		=> esc_html__( 'Single Solid', 'Avada' ),
			'single dashed'		=> esc_html__( 'Single Dashed', 'Avada' ),
			'single dotted'		=> esc_html__( 'Single Dotted', 'Avada' ),
			'double solid'	 	=> esc_html__( 'Double Solid', 'Avada' ),
			'double dashed'		=> esc_html__( 'Double Dashed', 'Avada' ),
			'double dotted'		=> esc_html__( 'Double Dotted', 'Avada' ),
			'underline solid'	=> esc_html__( 'Underline Solid', 'Avada' ),
			'underline dashed'	=> esc_html__( 'Underline Dashed', 'Avada' ),
			'underline dotted'	=> esc_html__( 'Underline Dotted', 'Avada' ),
		),
		esc_html__( 'Choose the caption separator you want to use.', 'Avada' )
	);
	$default = sprintf( esc_html__( 'Default: %s', 'Avada' ), '24' );
	$this->text(
		'caption_font_size',
		esc_html__( 'Caption Font Size', 'Avada' ),
		sprintf( esc_html__( 'Enter caption font size without px unit. In pixels, ex: 24 instead of 24px. %s', 'Avada' ), '<strong>' . $default . '</strong>' )
	);
	$default = sprintf( esc_html__( 'Default: %s', 'Avada' ), '#fff' );
	$this->text(
		'caption_color',
		esc_html__( 'Caption Color', 'Avada' ),
		sprintf( esc_html__( 'Select a color for the caption font. Hex color code, ex: #fff. %s', 'Avada' ), '<strong>' . $default . '</strong>' )
	);
	$this->select(
		'caption_bg',
		esc_html__( 'Caption Background', 'Avada' ),
		array( 'yes' => esc_html__( 'Yes', 'Avada' ), 'no' => esc_html__( 'No', 'Avada' ) ),
		esc_html__( 'Select this option if you would like a semi-transparent background behind your caption.', 'Avada' )
	);
	$default = sprintf( esc_html__( 'Default: %s', 'Avada' ), '#000' );
	$this->text(
		'caption_bg_color',
		esc_html__( 'Caption Background Color', 'Avada' ),
		sprintf( esc_html__( 'Select a color for the caption background. Hex color code, ex: #000. %s', 'Avada' ), '<strong>' . $default . '</strong>' )
	);
	?>

	<h2><?php esc_html_e( 'Slide Link Settings:', 'Avada' ); ?></h2>

	<?php

	$this->select(
		'link_type',
		esc_html__( 'Slide Link Type', 'Avada' ),
		array( 'button' => esc_html__( 'Button', 'Avada' ), 'full' => esc_html__( 'Full Slide', 'Avada' ) ),
		esc_html__( 'Select how the slide will link.', 'Avada' )
	);
	$this->text(
		'slide_link',
		esc_html__( 'Slide Link', 'Avada' ),
		esc_html__( 'Please enter your URL that will be used to link the full slide.', 'Avada' )
	);
	$this->select(
		'slide_target',
		esc_html__( 'Open Slide Link In New Window', 'Avada' ),
		array( 'yes' => esc_html__( 'Yes', 'Avada' ), 'no' => esc_html__( 'No', 'Avada' ) )
	);
	$this->textarea(
		'button_1',
		esc_html__( 'Button #1', 'Avada' ) . '<br/><a href="http://theme-fusion.com/knowledgebase/avada-shortcode-list/#buttons" target="_blank">' . esc_html__( 'Click here to view button option descriptions.', 'Avada' ) . '</a>',
		esc_html__( 'Adjust the button shortcode parameters for the first button.', 'Avada' ),
		'[button link="" color="default" size="" type="" shape="" target="_self" title="" gradient_colors="|" gradient_hover_colors="|" accent_color="" accent_hover_color="" bevel_color="" border_width="1px" shadow="" icon="" icon_divider="yes" icon_position="left" modal="" animation_type="0" animation_direction="down" animation_speed="0.1" class="" id=""]' . esc_html__( 'Button Text', 'Avada' ) . '[/button]'
	);
	$this->textarea(
		'button_2',
		esc_html__( 'Button #2', 'Avada' ) . '<br/><a href="http://theme-fusion.com/knowledgebase/avada-shortcode-list/#buttons" target="_blank">' . esc_html__( 'Click here to view button option descriptions.', 'Avada' ) . '</a>',
		esc_html__( 'Adjust the button shortcode parameters for the second button.', 'Avada' ),
		'[button link="" color="default" size="" type="" shape="" target="_self" title="" gradient_colors="|" gradient_hover_colors="|" accent_color="" accent_hover_color="" bevel_color="" border_width="1px" shadow="" icon="" icon_divider="yes" icon_position="left" modal="" animation_type="0" animation_direction="down" animation_speed="0.1" class="" id=""]' . esc_html__( 'Button Text', 'Avada' ) . '[/button]'
	);
	?>

</div>
<div class="clear"></div>
