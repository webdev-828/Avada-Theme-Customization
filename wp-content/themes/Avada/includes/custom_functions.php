<?php

add_action( 'wp_head', 'avada_set_post_views' );
if ( ! function_exists( 'avada_set_post_views' ) ) {
	function avada_set_post_views() {
		global $post;
		if ( 'post' == get_post_type() && is_single() ) {
			$postID = $post->ID;
			if ( ! empty( $postID ) ) {
				$count_key = 'avada_post_views_count';
				$count     = get_post_meta( $postID, $count_key, true );
				if ( '' == $count ) {
					$count = 0;
					delete_post_meta( $postID, $count_key );
					add_post_meta( $postID, $count_key, '0' );
				} else {
					$count++;
					update_post_meta( $postID, $count_key, $count );
				}
			}
		}
	}
}

if ( ! function_exists( 'avada_get_slider' ) ) {
	function avada_get_slider( $post_id, $type ) {
		$type = Avada_Helper::slider_name( $type );
		return ( $type ) ?get_post_meta( $post_id, 'pyre_' . $type, true ) : false;
	}
}

if ( ! function_exists( 'avada_slider' ) ) {
	function avada_slider( $post_id ) {
		$slider_type = avada_get_slider_type( $post_id );
		$slider      = avada_get_slider( $post_id, $slider_type );

		if ( $slider ) {
			$slider_name = Avada_Helper::slider_name( $slider_type );
			$slider_name = ( 'slider' == $slider_name ) ? 'layerslider' : $slider_name;

			$function = 'avada_' . $slider_name;

			$function( $slider );
		}
	}
}

if ( ! function_exists( 'avada_revslider' ) ) {
	function avada_revslider( $name ) {
		if ( function_exists('putRevSlider') ) {
			putRevSlider( $name );
		}
	}
}

if ( ! function_exists( 'avada_layerslider' ) ) {
	function avada_layerslider( $id ) {
		global $wpdb;

		// Get slider
		$ls_table_name = $wpdb->prefix . "layerslider";
		$ls_slider     = $wpdb->get_row( "SELECT * FROM $ls_table_name WHERE id = " . (int) $id . " ORDER BY date_c DESC LIMIT 1" , ARRAY_A );
		$ls_slider     = json_decode( $ls_slider['data'], true );
		?>
		<style type="text/css">
			#layerslider-container{max-width:<?php echo $ls_slider['properties']['width'] ?>;}
		</style>
		<div id="layerslider-container">
			<div id="layerslider-wrapper">
				<?php if ( 'avada' == $ls_slider['properties']['skin'] ) : ?>
					<div class="ls-shadow-top"></div>
				<?php endif; ?>
				<?php echo do_shortcode( '[layerslider id="' . $id . '"]' ); ?>
				<?php if ( 'avada' == $ls_slider['properties']['skin'] ) : ?>
					<div class="ls-shadow-bottom"></div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'avada_elasticslider' ) ) {
	function avada_elasticslider( $term ) {

		if ( Avada()->settings->get( 'status_eslider' ) ) {
			$args				= array(
				'post_type'        => 'themefusion_elastic',
				'posts_per_page'   => -1,
				'suppress_filters' => 0
			);
			$args['tax_query'][] = array(
				'taxonomy' => 'themefusion_es_groups',
				'field'    => 'slug',
				'terms'    => $term
			);
			$query = new WP_Query( $args );
			$count = 1;
			?>

			<?php if ( $query->have_posts() ) : ?>
				<div id="ei-slider" class="ei-slider">
					<div class="fusion-slider-loading"><?php _e( 'Loading...', 'Avada' ); ?></div>
					<ul class="ei-slider-large">
						<?php while ( $query->have_posts() ) : $query->the_post(); ?>
							<li style="<?php echo ( $count > 0 ) ? 'opacity: 0;' : ''; ?>">
								<?php the_post_thumbnail( 'full', array( 'title' => '', 'alt' => get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) ); ?>
								<div class="ei-title">
									<?php if ( get_post_meta( get_the_ID(), 'pyre_caption_1', true ) ): ?>
										<h2><?php echo get_post_meta( get_the_ID(), 'pyre_caption_1', true ); ?></h2>
									<?php endif; ?>
									<?php if ( get_post_meta( get_the_ID(), 'pyre_caption_2', true ) ): ?>
										<h3><?php echo get_post_meta( get_the_ID(), 'pyre_caption_2', true ); ?></h3>
									<?php endif; ?>
								</div>
							</li>
							<?php $count ++; ?>
						<?php endwhile; ?>
					</ul>
					<ul class="ei-slider-thumbs" style="display: none;">
						<li class="ei-slider-element">Current</li>
						<?php while ( $query->have_posts() ) : $query->the_post(); ?>
							<li>
								<a href="#"><?php the_title(); ?></a>
								<?php the_post_thumbnail( 'full', array( 'title' => '', 'alt' => get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) ); ?>
							</li>
						<?php endwhile; ?>
					</ul>
				</div>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
			<?php wp_reset_query();
		}
	}
}

if ( ! function_exists( 'avada_wooslider' ) ) {
	function avada_wooslider( $term ) {

		if ( Avada()->settings->get( 'status_fusion_slider' ) ) {
			$term_details = get_term_by( 'slug', $term, 'slide-page' );
			$slider_settings = array();

			if ( is_object( $term_details ) ) {
				$slider_settings = get_option( 'taxonomy_' . $term_details->term_id );
			}

			if ( ! isset( $slider_settings['typo_sensitivity'] ) ) {
				$slider_settings['typo_sensitivity'] = '0.6';
			}

			if ( ! isset( $slider_settings['typo_factor'] ) ) {
				$slider_settings['typo_factor'] = '1.5';
			}


			if ( ! isset( $slider_settings['slider_width'] ) || '' == $slider_settings['slider_width'] ) {
				$slider_settings['slider_width'] = '100%';
			}

			if ( ! isset( $slider_settings['slider_height'] ) || '' == $slider_settings['slider_height'] ) {
				$slider_settings['slider_height'] = '500px';
			}

			if ( ! isset( $slider_settings['full_screen'] ) ) {
				$slider_settings['full_screen'] = false;
			}

			if ( ! isset( $slider_settings['animation'] ) ) {
				$slider_settings['animation'] = true;
			}

			if ( ! isset( $slider_settings['nav_box_width'] ) ) {
				$slider_settings['nav_box_width'] = '63px';
			}

			if ( ! isset( $slider_settings['nav_box_height'] ) ) {
				$slider_settings['nav_box_height'] = '63px';
			}

			if ( ! isset( $slider_settings['nav_arrow_size'] ) ) {
				$slider_settings['nav_arrow_size'] = '25px';
			}

			$nav_box_height_half = '0';
			if ( $slider_settings['nav_box_height'] ) {
				$nav_box_height_half = intval( $slider_settings['nav_box_height'] ) / 2;
			}

			$slider_data = '';

			if ( $slider_settings ) {
				foreach( $slider_settings as $slider_setting => $slider_setting_value ) {
					$slider_data .= 'data-' . $slider_setting . '="' . $slider_setting_value . '" ';
				}
			}

			$slider_class = '';

			if ( '100%' == $slider_settings['slider_width'] && ! $slider_settings['full_screen'] ) {
				$slider_class .= ' full-width-slider';
			} elseif ( '100%' != $slider_settings['slider_width'] && ! $slider_settings['full_screen'] ) {
				$slider_class .= ' fixed-width-slider';
			}

			if ( isset( $slider_settings['slider_content_width'] ) && '' != $slider_settings['slider_content_width'] ) {
				$content_max_width = 'max-width:' . $slider_settings['slider_content_width'];
			} else {
				$content_max_width = '';
			}

			$args = array(
				'post_type'        => 'slide',
				'posts_per_page'   => -1,
				'suppress_filters' => 0
			);
			$args['tax_query'][] = array(
				'taxonomy' => 'slide-page',
				'field'    => 'slug',
				'terms'    => $term
			);

			$query = new WP_Query( $args );
			?>

			<?php if ( $query->have_posts() ) : ?>

				<?php $max_width = ( 'fade' == $slider_settings['animation'] ) ? 'max-width:' . $slider_settings['slider_width'] : ''; ?>

				<div class="fusion-slider-container fusion-slider-<?php the_ID(); ?> <?php echo $slider_class; ?>-container" style="height:<?php echo $slider_settings['slider_height']; ?>;max-width:<?php echo $slider_settings['slider_width']; ?>;">
					<style type="text/css" scoped="scoped">
					.fusion-slider-<?php the_ID(); ?> .flex-direction-nav a {
						<?php
						if ( $slider_settings['nav_box_width'] ) {
							echo 'width:' . $slider_settings['nav_box_width'] . ';';
						}
						if ( $slider_settings['nav_box_height'] ) {
							echo 'height:' . $slider_settings['nav_box_height'] . ';';
							echo 'line-height:' . $slider_settings['nav_box_height'] . ';';
							echo 'margin-top:-' . $nav_box_height_half . 'px;';
						}
						if ( $slider_settings['nav_arrow_size'] ) {
							echo 'font-size:' . $slider_settings['nav_arrow_size'] . ';';
						}
						?>
					}
					</style>
					<div class="fusion-slider-loading"><?php _e( 'Loading...', 'Avada' ); ?></div>
					<div class="tfs-slider flexslider main-flex<?php echo $slider_class; ?>" style="max-width:<?php echo $slider_settings['slider_width']; ?>;" <?php echo $slider_data; ?>>
						<ul class="slides" style="<?php echo $max_width ?>;">
							<?php while ( $query->have_posts() ) : $query->the_post(); ?>
								<?php
								$metadata = get_metadata( 'post', get_the_ID() );
								$background_image = '';
								$background_class = '';

								$img_width = '';
								$image_url = array( '', '' );

								if ( isset( $metadata['pyre_type'][0] ) && 'image' == $metadata['pyre_type'][0] && has_post_thumbnail() ) {
									$image_id         = get_post_thumbnail_id();
									$image_url        = wp_get_attachment_image_src( $image_id, 'full', true );
									$background_image = 'background-image: url(' . $image_url[0] . ');';
									$background_class = 'background-image';
									$img_width        = $image_url[1];
								}

								$aspect_ratio 		= '16:9';
								$video_attributes   = '';
								$youtube_attributes = '';
								$vimeo_attributes   = '';
								$data_mute          = 'no';
								$data_loop          = 'no';
								$data_autoplay      = 'no';

								if ( isset( $metadata['pyre_aspect_ratio'][0] ) && $metadata['pyre_aspect_ratio'][0] ) {
									$aspect_ratio = $metadata['pyre_aspect_ratio'][0];
								}

								if ( isset( $metadata['pyre_mute_video'][0] ) && 'yes' == $metadata['pyre_mute_video'][0] ) {
									$video_attributes = 'muted';
									$data_mute        = 'yes';
								}

								// Do not set the &auoplay=1 attributes, as this is done in js to make sure the page is fully loaded before the video begins to play
								if ( isset( $metadata['pyre_autoplay_video'][0] ) && 'yes' == $metadata['pyre_autoplay_video'][0] ) {
									$video_attributes   .= ' autoplay';
									$data_autoplay       = 'yes';
								}

								if ( isset( $metadata['pyre_loop_video'][0] ) && 'yes' == $metadata['pyre_loop_video'][0] ) {
									$video_attributes   .= ' loop';
									$youtube_attributes .= '&amp;loop=1&amp;playlist=' . $metadata['pyre_youtube_id'][0];
									$vimeo_attributes   .= '&amp;loop=1';
									$data_loop           = 'yes';
								}

								if ( isset( $metadata['pyre_hide_video_controls'][0] ) && 'no' == $metadata['pyre_hide_video_controls'][0] ) {
									$video_attributes   .= ' controls';
									$youtube_attributes .= '&amp;controls=1';
									$video_zindex        = 'z-index: 1;';
								} else {
									$youtube_attributes .= '&amp;controls=0';
									$video_zindex        = 'z-index: -99;';
								}

								$heading_color = 'color:#fff;';

								if ( isset( $metadata['pyre_heading_color'][0] ) && $metadata['pyre_heading_color'][0] ) {
									$heading_color = 'color:' . $metadata['pyre_heading_color'][0] . ';';
								}

								$heading_bg = '';

								if ( isset( $metadata['pyre_heading_bg'][0] ) && 'yes' == $metadata['pyre_heading_bg'][0] ) {
									$heading_bg = 'background-color: rgba(0,0,0, 0.4);';
									if ( isset( $metadata['pyre_heading_bg_color'][0] ) && '' != $metadata['pyre_heading_bg_color'][0] ) {
										$rgb        = fusion_hex2rgb( $metadata['pyre_heading_bg_color'][0] );
										$heading_bg = sprintf( 'background-color: rgba(%s,%s,%s,%s);', $rgb[0], $rgb[1], $rgb[2], 0.4 );
									}
								}

								$caption_color = 'color:#fff;';

								if ( isset( $metadata['pyre_caption_color'][0] ) && $metadata['pyre_caption_color'][0] ) {
									$caption_color = 'color:' . $metadata['pyre_caption_color'][0] . ';';
								}

								$caption_bg = '';

								if ( isset( $metadata['pyre_caption_bg'][0] ) && 'yes' == $metadata['pyre_caption_bg'][0] ) {
									$caption_bg = 'background-color: rgba(0, 0, 0, 0.4);';

									if ( isset( $metadata['pyre_caption_bg_color'][0] ) && '' != $metadata['pyre_caption_bg_color'][0] ) {
										$rgb        = fusion_hex2rgb( $metadata['pyre_caption_bg_color'][0] );
										$caption_bg = sprintf( 'background-color: rgba(%s,%s,%s,%s);', $rgb[0], $rgb[1], $rgb[2], 0.4 );
									}
								}

								$video_bg_color = '';

								if ( isset( $metadata['pyre_video_bg_color'][0] ) && $metadata['pyre_video_bg_color'][0] ) {
									$video_bg_color_hex = fusion_hex2rgb( $metadata['pyre_video_bg_color'][0]  );
									$video_bg_color     = 'background-color: rgba(' . $video_bg_color_hex[0] . ', ' . $video_bg_color_hex[1] . ', ' . $video_bg_color_hex[2] . ', 0.4);';
								}

								$video = false;

								if ( isset( $metadata['pyre_type'][0] ) ) {
									if ( isset( $metadata['pyre_type'][0] ) && in_array( $metadata['pyre_type'][0], array( 'self-hosted-video', 'youtube', 'vimeo' ) ) ) {
										$video = true;
									}
								}

								if ( isset( $metadata['pyre_type'][0] ) &&  $metadata['pyre_type'][0] == 'self-hosted-video' ) {
									$background_class = 'self-hosted-video-bg';
								}

								$heading_font_size = 'font-size:60px;line-height:80px;';
								if ( isset( $metadata['pyre_heading_font_size'][0] ) && $metadata['pyre_heading_font_size'][0] ) {
									$line_height       = $metadata['pyre_heading_font_size'][0] * 1.2;
									$heading_font_size = 'font-size:' . $metadata['pyre_heading_font_size'][0] . 'px;line-height:' . $line_height . 'px;';
								}

								$caption_font_size = 'font-size: 24px;line-height:38px;';
								if ( isset( $metadata['pyre_caption_font_size'][0] ) && $metadata['pyre_caption_font_size'][0] ) {
									$line_height       = $metadata['pyre_caption_font_size'][0] * 1.2;
									$caption_font_size = 'font-size:' . $metadata['pyre_caption_font_size'][0] . 'px;line-height:' . $line_height . 'px;';
								}

								$heading_styles = $heading_color . $heading_font_size;
								$caption_styles = $caption_color . $caption_font_size;
								$heading_title_sc_wrapper_class = '';
								$caption_title_sc_wrapper_class = '';

								if ( ! isset( $metadata['pyre_heading_separator'][0] ) ) {
									$metadata['pyre_heading_separator'][0] = 'none';
								}

								if ( ! isset( $metadata['pyre_caption_separator'][0] ) ) {
									$metadata['pyre_caption_separator'][0] = 'none';
								}

								if ( $metadata['pyre_content_alignment'][0] != 'center' ) {
									$metadata['pyre_heading_separator'][0] = 'none';
									$metadata['pyre_caption_separator'][0] = 'none';
								}

								if ( $metadata['pyre_content_alignment'][0] == 'center' ) {
									if ( $metadata['pyre_heading_separator'][0] != 'none' ) {
										$heading_title_sc_wrapper_class = ' fusion-block-element';
									}

									if ( $metadata['pyre_caption_separator'][0] != 'none' ) {
										$caption_title_sc_wrapper_class = ' fusion-block-element';
									}
								}
								?>
								<li data-mute="<?php echo $data_mute; ?>" data-loop="<?php echo $data_loop; ?>" data-autoplay="<?php echo $data_autoplay; ?>">
									<div class="slide-content-container slide-content-<?php if ( isset( $metadata['pyre_content_alignment'][0] ) && $metadata['pyre_content_alignment'][0] ) { echo $metadata['pyre_content_alignment'][0]; } ?>" style="display: none;">
										<div class="slide-content" style="<?php echo $content_max_width; ?>">
											<?php if ( isset( $metadata['pyre_heading'][0] ) && $metadata['pyre_heading'][0] ) : ?>
												<div class="heading <?php echo ( $heading_bg ) ? 'with-bg' : ''; ?>">
													<div class="fusion-title-sc-wrapper<?php echo $heading_title_sc_wrapper_class; ?>" style="<?php echo $heading_bg; ?>">
														<?php echo do_shortcode( sprintf( '[title size="2" content_align="%s" sep_color="%s" margin_top="0px" margin_bottom="0px" style_type="%s" style_tag="%s"]%s[/title]',  $metadata['pyre_content_alignment'][0], $metadata['pyre_heading_color'][0], $metadata['pyre_heading_separator'][0], $heading_styles, do_shortcode( $metadata['pyre_heading'][0] ) ) ); ?>
													</div>
												</div>
											<?php endif; ?>
											<?php if ( isset( $metadata['pyre_caption'][0] ) && $metadata['pyre_caption'][0] ) : ?>
												<div class="caption <?php echo ( $caption_bg ) ? 'with-bg' : ''; ?>">
													<div class="fusion-title-sc-wrapper<?php echo $caption_title_sc_wrapper_class; ?>" style="<?php echo $caption_bg; ?>">
														<?php echo do_shortcode( sprintf( '[title size="3" content_align="%s" sep_color="%s" margin_top="0px" margin_bottom="0px" style_type="%s" style_tag="%s"]%s[/title]',  $metadata['pyre_content_alignment'][0], $metadata['pyre_caption_color'][0], $metadata['pyre_caption_separator'][0], $caption_styles, do_shortcode( $metadata['pyre_caption'][0] ) ) ); ?>
													</div>
												</div>
											<?php endif; ?>
											<?php if ( isset( $metadata['pyre_link_type'][0] ) && 'button' == $metadata['pyre_link_type'][0] ) : ?>
												<div class="buttons" >
													<?php if ( isset( $metadata['pyre_button_1'][0] ) && $metadata['pyre_button_1'][0] ) : ?>
														<div class="tfs-button-1"><?php echo do_shortcode( $metadata['pyre_button_1'][0] ); ?></div>
													<?php endif; ?>
													<?php if ( isset( $metadata['pyre_button_2'][0] ) && $metadata['pyre_button_2'][0] ) : ?>
														<div class="tfs-button-2"><?php echo do_shortcode( $metadata['pyre_button_2'][0] ); ?></div>
													<?php endif; ?>
												</div>
											<?php endif; ?>
										</div>
									</div>
									<?php if ( isset( $metadata['pyre_link_type'][0] ) && 'full' == $metadata['pyre_link_type'][0] && isset( $metadata['pyre_slide_link'][0] ) && $metadata['pyre_slide_link'][0] ) : ?>
										<a href="<?php echo $metadata['pyre_slide_link'][0]; ?>" class="overlay-link" <?php echo ( isset( $metadata['pyre_slide_target'][0] ) && 'yes' == $metadata['pyre_slide_target'][0] ) ? 'target="_blank"' : ''; ?>></a>
									<?php endif; ?>
									<?php if ( isset( $metadata['pyre_preview_image'][0] ) && $metadata['pyre_preview_image'][0] && isset( $metadata['pyre_type'][0] ) && 'self-hosted-video' == $metadata['pyre_type'][0] ) : ?>
										<div class="mobile_video_image" style="background-image: url(<?php echo Avada_Sanitize::css_asset_url( $metadata['pyre_preview_image'][0] ); ?>);"></div>
									<?php elseif ( isset( $metadata['pyre_type'][0] ) && 'self-hosted-video' == $metadata['pyre_type'][0] ) : ?>
										<div class="mobile_video_image" style="background-image: url(<?php echo Avada_Sanitize::css_asset_url( get_template_directory_uri() . '/assets/images/video_preview.jpg' ); ?>);"></div>
									<?php endif; ?>
									<?php if ( $video_bg_color && true == $video ) : ?>
										<div class="overlay" style="<?php echo $video_bg_color; ?>"></div>
									<?php endif; ?>
									<div class="background <?php echo $background_class; ?>" style="<?php echo $background_image; ?>max-width:<?php echo $slider_settings['slider_width']; ?>;height:<?php echo $slider_settings['slider_height']; ?>;filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $image_url[0]; ?>', sizingMethod='scale');-ms-filter:'progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $image_url[0]; ?>', sizingMethod='scale')';" data-imgwidth="<?php echo $img_width; ?>">
										<?php if ( isset( $metadata['pyre_type'][0] ) ) : ?>
											<?php if ( 'self-hosted-video' == $metadata['pyre_type'][0] && ( $metadata['pyre_webm'][0] || $metadata['pyre_mp4'][0] || $metadata['pyre_ogg'][0] ) ) : ?>
												<video width="1800" height="700" <?php echo $video_attributes; ?> preload="auto">
													<?php if ( array_key_exists( 'pyre_mp4', $metadata ) && $metadata['pyre_mp4'][0] ) : ?>
														<source src="<?php echo $metadata['pyre_mp4'][0]; ?>" type="video/mp4">
													<?php endif; ?>
													<?php if ( array_key_exists( 'pyre_ogg', $metadata ) && $metadata['pyre_ogg'][0] ) : ?>
														<source src="<?php echo $metadata['pyre_ogg'][0]; ?>" type="video/ogg">
													<?php endif; ?>
													<?php if ( array_key_exists( 'pyre_webm', $metadata ) && $metadata['pyre_webm'][0] ) : ?>
														<source src="<?php echo $metadata['pyre_webm'][0]; ?>" type="video/webm">
													<?php endif; ?>
												</video>
											<?php endif; ?>
										<?php endif; ?>
										<?php if ( isset( $metadata['pyre_type'][0] ) && isset( $metadata['pyre_youtube_id'][0] ) && 'youtube' == $metadata['pyre_type'][0] && $metadata['pyre_youtube_id'][0] ) : ?>
											<div style="position: absolute; top: 0; left: 0; <?php echo $video_zindex; ?> width: 100%; height: 100%" data-youtube-video-id="<?php echo $metadata['pyre_youtube_id'][0]; ?>" data-video-aspect-ratio="<?php echo $aspect_ratio; ?>">
												<div id="video-<?php echo $metadata['pyre_youtube_id'][0]; ?>-inner">
													<iframe height="100%" width="100%" src="https://www.youtube.com/embed/<?php echo $metadata['pyre_youtube_id'][0]; ?>?wmode=transparent&amp;modestbranding=1&amp;showinfo=0&amp;autohide=1&amp;enablejsapi=1&amp;rel=0&amp;vq=hd720&amp;<?php echo $youtube_attributes; ?>"></iframe>
												</div>
											</div>
										<?php endif; ?>
										<?php if ( isset( $metadata['pyre_type'][0] ) && isset( $metadata['pyre_vimeo_id'][0] ) &&  'vimeo' == $metadata['pyre_type'][0] && $metadata['pyre_vimeo_id'][0] ) : ?>
											<div style="position: absolute; top: 0; left: 0; <?php echo $video_zindex; ?> width: 100%; height: 100%" data-mute="<?php echo $data_mute; ?>" data-vimeo-video-id="<?php echo $metadata['pyre_vimeo_id'][0]; ?>" data-video-aspect-ratio="<?php echo $aspect_ratio; ?>">
												<iframe src="https://player.vimeo.com/video/<?php echo $metadata['pyre_vimeo_id'][0]; ?>?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff&amp;badge=0&amp;title=0<?php echo $vimeo_attributes; ?>" height="100%" width="100%" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
											</div>
										<?php endif; ?>
									</div>
								</li>
							<?php endwhile; ?>
						</ul>
					</div>
				</div>
			<?php endif; ?>
			<?php wp_reset_query();
		}
	}
}

if ( ! function_exists( 'avada_get_page_title_bar_contents' ) ) {
	function avada_get_page_title_bar_contents( $post_id, $get_secondary_content = TRUE ) {

		if ( $get_secondary_content ) {
			ob_start();
			if ( fusion_get_option( 'page_title_bar_bs', 'page_title_breadcrumbs_search_bar', $post_id ) != 'none' ) {
				if ( ( 'Breadcrumbs' == Avada()->settings->get( 'page_title_bar_bs' ) && in_array( get_post_meta( $post_id, 'pyre_page_title_breadcrumbs_search_bar', true ), array( 'breadcrumbs', 'default', '' ) ) ) || 'breadcrumbs' == get_post_meta( $post_id, 'pyre_page_title_breadcrumbs_search_bar', true ) ) {
					fusion_breadcrumbs();
				} elseif ( ( 'Search Box' == Avada()->settings->get( 'page_title_bar_bs' ) && in_array( get_post_meta( $post_id, 'pyre_page_title_breadcrumbs_search_bar', true ), array( 'searchbar', 'default', '' ) ) ) || 'searchbar' == get_post_meta( $post_id, 'pyre_page_title_breadcrumbs_search_bar', true ) ) {
					get_search_form();
				}
			}
			$secondary_content = ob_get_contents();
			ob_get_clean();
		} else {
			$secondary_content = '';
		}

		$title    = '';
		$subtitle = '';

		if ( '' != get_post_meta( $post_id, 'pyre_page_title_custom_text', true ) ) {
			$title = get_post_meta( $post_id, 'pyre_page_title_custom_text', true );
		}

		if ( '' != get_post_meta( $post_id, 'pyre_page_title_custom_subheader', true ) ) {
			$subtitle = get_post_meta( $post_id, 'pyre_page_title_custom_subheader', true );
		}

		if ( '' == get_post_meta( $post_id, 'pyre_page_title_text', true ) || 'default' == get_post_meta( $post_id, 'pyre_page_title_text', true ) ) {
			if ( Avada()->settings->get( 'page_title_bar_text' ) ) {
				$page_title_text = 'yes';
			} else {
				$page_title_text = 'no';
			}
		} else {
			$page_title_text = get_post_meta( $post_id, 'pyre_page_title_text', true );
		}

		if ( is_search() ) {
			$title = sprintf( esc_html__( 'Search results for: %s', 'Avada' ), get_search_query() );
			$subtitle = '';
		}

		if ( ! $title ) {
			$title = get_the_title( $post_id );

			// Only assing blog title theme option to default blog page and not posts page
			if ( is_home() && get_option( 'show_on_front' ) != 'page' ) {
				$title = Avada()->settings->get( 'blog_title' );
			}

			if ( is_404() ) {
				$title = esc_html__( 'Error 404 Page', 'Avada' );
			}

			if ( class_exists( 'Tribe__Events__Main' ) && ( ( tribe_is_event() && ! is_single() && ! is_home() ) || is_events_archive() || ( is_events_archive() && is_404() ) ) ) {
				$title = tribe_get_events_title();
			} elseif ( is_archive() && ! is_bbpress() && ! is_search() ) {
				if ( is_day() ) {
					$title = sprintf( esc_html__( 'Daily Archives: %s', 'Avada' ), '<span>' . get_the_date() . '</span>' );
				} else if ( is_month() ) {
					$title = sprintf( esc_html__( 'Monthly Archives: %s', 'Avada' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );
				} elseif ( is_year() ) {
					$title = sprintf( esc_html__( 'Yearly Archives: %s', 'Avada' ), '<span> ' . get_the_date( 'Y' ) . '</span>' );
				} elseif ( is_author() ) {
					$curauth = get_user_by( 'id', get_query_var( 'author' ) );
					$title   = $curauth->nickname;
				} elseif ( is_post_type_archive() ) {
					$title = post_type_archive_title( '', false );

					$sermon_settings = get_option( 'wpfc_options' );
					if ( is_array( $sermon_settings ) ) {
						$title = $sermon_settings['archive_title'];
					}

				} else {
					$title = single_cat_title( '', false );
				}
			}

			if ( class_exists( 'WooCommerce' ) && is_woocommerce() && ( is_product() || is_shop() ) && ! is_search() ) {
				if ( ! is_product() ) {
					$title = woocommerce_page_title( false );
				}
			}
		}

		// Only assing blog subtitle theme option to default blog page and not posts page
		if ( ! $subtitle && is_home() && get_option( 'show_on_front' ) != 'page' ) {
			$subtitle = Avada()->settings->get( 'blog_subtitle' );
		}

		if ( ! is_archive() && ! is_search() && ! ( is_home() && ! is_front_page() ) ) {
			if ( 'no' == $page_title_text && ( 'yes' == get_post_meta( $post_id, 'pyre_page_title', true ) || 'yes_without_bar' == get_post_meta( $post_id, 'pyre_page_title', true ) || ( 'hide' != Avada()->settings->get( 'page_title_bar' ) && 'no' != get_post_meta( $post_id, 'pyre_page_title', true ) ) ) ) {
				$title    = '';
				$subtitle = '';
			}
		} else {
			if ( 'hide' != Avada()->settings->get( 'page_title_bar' ) && 'no' == $page_title_text ) {
				$title    = '';
				$subtitle = '';
			}
		}

		return array( $title, $subtitle, $secondary_content );
	}

}

if ( ! function_exists( 'avada_current_page_title_bar' ) ) {
	function avada_current_page_title_bar( $post_id  ) {
		$page_title_bar_contents = avada_get_page_title_bar_contents( $post_id );

		if ( ( ! is_archive() || class_exists( 'WooCommerce' ) && is_shop() ) &&
			 ! is_search()
		) {
			if ( 'yes' == get_post_meta( $post_id, 'pyre_page_title', true ) || 'yes_without_bar' == get_post_meta( $post_id, 'pyre_page_title', true ) || ( 'hide' != Avada()->settings->get( 'page_title_bar' ) && 'no' != get_post_meta( $post_id, 'pyre_page_title', true ) ) ) {
				if ( is_home() && is_front_page() && ! Avada()->settings->get( 'blog_show_page_title_bar' ) ) {
					// do nothing
				} else {
					if ( is_home() && get_post_meta( $post_id, 'pyre_page_title', true ) == 'default' && ! Avada()->settings->get( 'blog_show_page_title_bar' ) ) {
						return;
					}
					avada_page_title_bar( $page_title_bar_contents[0], $page_title_bar_contents[1], $page_title_bar_contents[2] );
				}
			}
		} else {
			if ( is_home() && Avada()->settings->get( 'blog_show_page_title_bar' ) ) {
				avada_page_title_bar( $page_title_bar_contents[0], $page_title_bar_contents[1], $page_title_bar_contents[2] );
			} else {
				if ( 'hide' != Avada()->settings->get( 'page_title_bar' ) ) {
					avada_page_title_bar( $page_title_bar_contents[0], $page_title_bar_contents[1], $page_title_bar_contents[2] );
				}
			}
		}
	}
}

if ( ! function_exists( 'avada_backend_check_new_bbpress_post' ) ) {
	function avada_backend_check_new_bbpress_post() {
		global $pagenow, $post_type;
		return ( 'post-new.php' == $pagenow && in_array( $post_type, array( 'forum', 'topic', 'reply' ) ) ) ? true : false;
	}
}

if ( ! function_exists( 'avada_featured_images_for_pages' ) ) {
	function avada_featured_images_for_pages() {

		$html = $video = $featured_images = '';

		if ( ! post_password_required( get_the_ID() ) ) {

			if ( Avada()->settings->get( 'featured_images_pages' ) ) {
				if ( 0 < avada_number_of_featured_images() || get_post_meta( get_the_ID(), 'pyre_video', true ) ) {
					if ( get_post_meta( get_the_ID(), 'pyre_video', true ) ) {
						$video = '<li><div class="full-video">' . get_post_meta( get_the_ID(), 'pyre_video', true ) . '</div></li>';
					}

					if ( has_post_thumbnail() && 'yes' != get_post_meta( get_the_ID(), 'pyre_show_first_featured_image', true ) ) {
						$attachment_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
						$full_image       = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
						$attachment_data  = wp_get_attachment_metadata( get_post_thumbnail_id() );

						$featured_images .= sprintf(
							'<li><a href="%s" rel="prettyPhoto[gallery%s]" data-title="%s" data-caption="%s"><img src="%s" alt="%s" role="presentation" /></a></li>',
							$full_image[0],
							get_the_ID(),
							get_post_field( 'post_title', get_post_thumbnail_id() ),
							get_post_field( 'post_excerpt', get_post_thumbnail_id() ),
							$attachment_image[0],
							get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true )
						);
					}

					$i = 2;
					while ( $i <= Avada()->settings->get( 'posts_slideshow_number' ) ) :

						$attachment_new_id = kd_mfi_get_featured_image_id( 'featured-image-'.$i, 'page' );

						if ( $attachment_new_id ) {

							$attachment_image = wp_get_attachment_image_src( $attachment_new_id, 'full' );
							$full_image       = wp_get_attachment_image_src( $attachment_new_id, 'full' );
							$attachment_data  = wp_get_attachment_metadata( $attachment_new_id );

							$featured_images .= sprintf(
								'<li><a href="%s" rel="iLightbox[gallery%s]" data-title="%s" data-caption="%s"><img src="%s" alt="%s" role="presentation" /></a></li>',
								$full_image[0],
								get_the_ID(),
								get_post_field( 'post_title', $attachment_new_id ),
								get_post_field( 'post_excerpt', $attachment_new_id ),
								$attachment_image[0],
								get_post_meta( $attachment_new_id, '_wp_attachment_image_alt', true )
							);
						}
						$i++;
					endwhile;

					$html .= sprintf(
						'<div class="fusion-flexslider flexslider post-slideshow"><ul class="slides">%s%s</ul></div>',
						$video,
						$featured_images
					);
				}
			}
		}
		return $html;
	}
}

if ( ! function_exists( 'avada_featured_images_lightbox' ) ) {
	function avada_featured_images_lightbox( $post_id ) {
		$html = $video = $featured_images = '';

		if ( get_post_meta( $post_id, 'pyre_video_url', true ) ) {
			$video = sprintf( '<a href="%s" class="iLightbox[gallery%s]"></a>', get_post_meta( $post_id, 'pyre_video_url', true ), $post_id );
		}

		$i = 2;

		while ( $i <= Avada()->settings->get( 'posts_slideshow_number' ) ) :

			$attachment_new_id = kd_mfi_get_featured_image_id( 'featured-image-'.$i, get_post_type( $post_id ) );
			if ( $attachment_new_id ) {
				$attachment_image = wp_get_attachment_image_src($attachment_new_id, 'full' );
				$full_image       = wp_get_attachment_image_src($attachment_new_id, 'full' );
				$attachment_data  = wp_get_attachment_metadata($attachment_new_id );
				$featured_images .= sprintf(
					'<a href="%s" data-rel="iLightbox[gallery%s]" title="%s" data-title="%s" data-caption="%s"></a>',
					$full_image[0],
					$post_id,
					get_post_field( 'post_title', $attachment_new_id ),
					get_post_field( 'post_title', $attachment_new_id ),
					get_post_field( 'post_excerpt', $attachment_new_id )
				);
			}
			$i++;

		endwhile;

		$html .= sprintf( '<div class="fusion-portfolio-gallery-hidden">%s%s</div>', $video, $featured_images );

		return $html;
	}

}

if ( ! function_exists( 'avada_display_sidenav' ) ) {
	function avada_display_sidenav( $post_id ) {

		if ( is_page_template( 'side-navigation.php' ) ) {
			$html = '<ul class="side-nav">';

			$post_ancestors = get_ancestors( $post_id, 'page' );
			$post_parent    = end( $post_ancestors );

			$html .= ( is_page( $post_parent ) ) ? '<li class="current_page_item">' : '<li>';

			if ( $post_parent ) {
				$html .= sprintf( '<a href="%s" title="%s">%s</a></li>', get_permalink( $post_parent ), esc_html__( 'Back to Parent Page', 'Avada' ), get_the_title( $post_parent ) );
				$children = wp_list_pages( sprintf( 'title_li=&child_of=%s&echo=0', $post_parent ) );
			} else {
				$html .= sprintf( '<a href="%s" title="%s">%s</a></li>', get_permalink( $post_id ), esc_html__( 'Back to Parent Page', 'Avada' ), get_the_title( $post_id ) );
				$children = wp_list_pages( sprintf( 'title_li=&child_of=%s&echo=0', $post_id ) );
			}

			if ( $children ) {
				$html .= $children;
			}

			$html .= '</ul>';

			return $html;
		}
	}
}

if ( ! function_exists( 'avada_link_pages' ) ) {
	function avada_link_pages() {
		wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'Avada' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span class="page-number">',
			'link_after'  => '</span>'
		) );
	}
}

if ( ! function_exists( 'avada_number_of_featured_images' ) ) {
	function avada_number_of_featured_images() {
		global $post;
		$number_of_images = 0;

		if ( has_post_thumbnail() && 'yes' != get_post_meta( $post->ID, 'pyre_show_first_featured_image', true ) ) {
			$number_of_images++;
		}

		for ( $i = 2; $i <= Avada()->settings->get( 'posts_slideshow_number' ); $i++ ) {
			$attachment_new_id = kd_mfi_get_featured_image_id('featured-image-'.$i, $post->post_type );

			if ( $attachment_new_id ) {
				$number_of_images++;
			}
		}
		return $number_of_images;
	}
}

// Omit closing PHP tag to avoid "Headers already sent" issues.
