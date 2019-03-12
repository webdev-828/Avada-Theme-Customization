<?php
class FusionSC_RecentWorks {

	private $column;
	private $icon_permalink;
	private $image_size;

	private $recent_works_counter = 1;

	public static $args;


	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		// Actions
		add_action( 'fusion_recent_works_shortcode_content', array( $this, 'get_post_content' ) );

		// Element attributes
		add_filter( 'fusion_attr_recentworks-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_recentworks-shortcode-portfolio-wrapper', array( $this, 'portfolio_wrapper_attr' ) );
		add_filter( 'fusion_attr_recentworks-shortcode-carousel', array( $this, 'carousel_attr' ) );
		add_filter( 'fusion_attr_recentworks-shortcode-slideshow', array( $this, 'slideshow_attr' ) );
		add_filter( 'fusion_attr_recentworks-shortcode-filter-link', array( $this, 'filter_link_attr' ) );

		add_shortcode( 'recent_works', array( $this, 'render' ) );
	}

	/**
	 * Render the parent shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render( $args, $content = '') {
		global $smof_data;

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class' 				=> '',
				'id' 					=> '',
				'autoplay'				=> 'no',
				'boxed_text'			=> 'unboxed',
				'cat_slug' 				=> '',
				'carousel_layout'		=> 'title_on_rollover',
				'column_spacing'		=> '12',
				'columns' 				=> 3,
				'exclude_cats' 			=> '',
				'excerpt_length' 		=> '15',
				'excerpt_words' 		=> '',  // deprecated
				'filters'				=> 'yes',
				'layout' 				=> 'carousel',
				'mouse_scroll'			=> 'no',
				'number_posts' 			=> 8,
				'offset'				=> '',
				'picture_size'			=> 'fixed',
				'scroll_items'			=> '',
				'show_nav'				=> 'yes',
				'strip_html'		  	=> 'yes',
				'animation_direction' 	=> 'left',
				'animation_speed' 		=> '',
				'animation_type' 		=> '',
				'animation_offset'		=> Avada()->settings->get( 'animation_offset' ),
			), $args
		);

		$defaults['column_spacing'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['column_spacing'], '' );

		if ( $defaults['column_spacing'] === '0' ) {
			$defaults['column_spacing'] = '0.0';
		}

		if ( $defaults['strip_html'] == 'yes' ) {
			$defaults['strip_html'] = TRUE;
		} else {
			$defaults['strip_html'] = FALSE;
		}

		extract( $defaults );

		self::$args = $defaults;

		// Set the image size for the slideshow
		$this->set_image_size();

		// As $excerpt_words is deprecated, only use it when explicity set
		if ( $excerpt_words ||
			 $excerpt_words === '0'
		) {
			$excerpt_length = $excerpt_words;
		}

		// Transform $cat_slugs to array
		if ( self::$args['cat_slug'] ) {
			$cat_slugs = preg_replace( '/\s+/', '', self::$args['cat_slug'] );
			$cat_slugs = explode( ',', self::$args['cat_slug'] );
		} else {
			$cat_slugs = array();
		}

		// Transform $cats_to_exclude to array
		if ( self::$args['exclude_cats'] ) {
			$cats_to_exclude = preg_replace( '/\s+/', '', self::$args['cat_slug'] );
			$cats_to_exclude = explode( ',' , self::$args['exclude_cats'] );
		} else {
			$cats_to_exclude = array();
		}

		// Initialize the query array
		$args = array(
			'post_type' 		=> 'avada_portfolio',
			'paged' 			=> 1,
			'posts_per_page'	=> $number_posts,
			'has_password' 		=> false
		);

		if ( $defaults['offset'] ) {
			$args['offset'] =  $offset;
		}

		// Check if the are categories that should be excluded
		if ( ! empty ( $cats_to_exclude ) ) {

			// Exclude the correct cats from tax_query
			$args['tax_query'] = array(
				array(
					'taxonomy'	=> 'portfolio_category',
					'field'	 	=> 'slug',
					'terms'		=> $cats_to_exclude,
					'operator'	=> 'NOT IN'
				)
			);

			// Include the correct cats in tax_query
			if ( ! empty ( $cat_slugs ) ) {
				$args['tax_query']['relation'] = 'AND';
				$args['tax_query'][] = array(
					'taxonomy'	=> 'portfolio_category',
					'field'		=> 'slug',
					'terms'		=> $cat_slugs,
					'operator'	=> 'IN'
				);
			}

		} else {
			// Include the cats from $cat_slugs in tax_query
			if ( ! empty ( $cat_slugs ) ) {
				$args['tax_query'] = array(
					array(
						'taxonomy' 	=> 'portfolio_category',
						'field' 	=> 'slug',
						'terms' 	=> $cat_slugs
					)
				);
			}
		}

		// If placeholder images are disabled, add the _thumbnail_id meta key to the query to only retrieve posts with featured images
		if ( ! $smof_data['featured_image_placeholder'] ) {
			$args['meta_key'] = '_thumbnail_id';
		}

		wp_reset_query();

		$recent_works = new WP_Query( $args );

		$portfolio_posts = '';

		// Set a gallery id for the lightbox triggers on rollovers
		$gallery_id = sprintf( '-rw-%s', $this->recent_works_counter );

		// Loop through returned posts
		// Setup the inner HTML for each elements
		while ( $recent_works->have_posts() ) {
			$recent_works->the_post();

			// Reset vars
			$rich_snippets = $post_classes = $title_terms = $image = $post_title = $post_terms = $separator = $post_content = $buttons = $view_project_button = '';

			// For carousels we only need the image and a li wrapper
			if ( $layout == 'carousel' ) {
				// Title on rollover layout
				if ( $carousel_layout == 'title_on_rollover' ) {
					$show_title = 'default';
				// Title below image layout
				} else {
					$show_title = 'disable';

					// Get the post title
					$title_terms .= sprintf( '<h4 %s><a href="%s" target="%s">%s</a></h4>', FusionCore_Plugin::attributes( 'fusion-carousel-title' ), get_permalink( get_the_ID() ), '_self', get_the_title() );

					// Get the terms
					$title_terms .= get_the_term_list( get_the_ID(), 'portfolio_category', '<div class="fusion-carousel-meta">', ', ', '</div>' );
				}

				// Render the video set in page options if no featured image is present
				if ( ! has_post_thumbnail() &&
					 fusion_get_page_option( 'video', get_the_ID() )
				) {
					// For the portfolio one column layout we need a fixed max-width
					if ( $columns == '1' ) {
						$video_max_width = '540px';
					// For all other layouts get the calculated max-width from the image size
					} else {
						$featured_image_size_dimensions = avada_get_image_size_dimensions( $this->image_size );
						$video_max_width = $featured_image_size_dimensions['width'];
					}

					$image = sprintf( '<div class="fusion-image-wrapper fusion-video" style="max-width:%s;">%s</div>', $video_max_width, fusion_get_page_option( 'video', get_the_ID() ) );
				} else {

					// Get the post image
					if ( $this->image_size == 'full' && property_exists( Avada(), 'images' ) ) {
						Avada()->images->set_grid_image_meta( array( 'layout' => 'portfolio_full', 'columns' => $columns ) );
					}	
					$image = avada_render_first_featured_image_markup( get_the_ID(), $this->image_size, get_permalink( get_the_ID() ), TRUE, FALSE, FALSE, 'default', $show_title, '', $gallery_id );
					if ( property_exists( Avada(), 'images' ) ) {
						Avada()->images->set_grid_image_meta( array() );
					}
				}

				$portfolio_posts .= sprintf( '<li %s><div %s>%s%s%s</div></li>', FusionCore_Plugin::attributes( 'fusion-carousel-item'), FusionCore_Plugin::attributes( 'fusion-carousel-item-wrapper' ),
											 avada_render_rich_snippets_for_pages(), $image, $title_terms );

			} else {
				// Get the post permalink
				$permalink = get_permalink();

				// Include the post categories as css classes for later useage with filters
				$post_categories = get_the_terms( get_the_ID(), 'portfolio_category' );

				if ( $post_categories ) {
					foreach ( $post_categories as $post_category ) {
						$post_classes .= urldecode( $post_category->slug ) . ' ';
					}
				}

				// Add the col-spacing class if needed
				if ( $column_spacing ) {
					$post_classes .= 'fusion-col-spacing';
				}

				// Render the video set in page options if no featured image is present
				if ( ! has_post_thumbnail() &&
					 fusion_get_page_option( 'video', get_the_ID() )
				) {
					// For the portfolio one column layout we need a fixed max-width
					if ( $columns == '1' ) {
						$video_max_width = '540px';
					// For all other layouts get the calculated max-width from the image size
					} else {
						$featured_image_size_dimensions = avada_get_image_size_dimensions( $this->image_size );
						$video_max_width = $featured_image_size_dimensions['width'];
					}

					$image = sprintf( '<div class="fusion-image-wrapper fusion-video" style="max-width:%s;">%s</div>', $video_max_width, fusion_get_page_option( 'video', get_the_ID() ) );
				} else {
					// Get the post image
					if ( $this->image_size == 'full' && property_exists( Avada(), 'images' ) ) {
						Avada()->images->set_grid_image_meta( array( 'layout' => 'portfolio_full', 'columns' => $columns ) );
					}
					$image = avada_render_first_featured_image_markup( get_the_ID(), $this->image_size, get_permalink( get_the_ID() ), TRUE, FALSE, FALSE, 'default', 'default', '', $gallery_id );
					if ( property_exists( Avada(), 'images' ) ) {
						Avada()->images->set_grid_image_meta( array() );
					}
				}

				// Additional content for grid-with-excerpts layout
				if( $layout == 'grid-with-excerpts' ) {

					// Get the rich snippets, if enabled
					$rich_snippets = avada_render_rich_snippets_for_pages( false );

					// Get the post title
					$post_title = avada_render_post_title( get_the_ID() );

					// Get the post terms
					$post_terms = sprintf( '<h4>%s</h4>', get_the_term_list( get_the_ID(), 'portfolio_category', '', ', ', '' ) );

					// Get the post content
					ob_start();
					/**
					 * fusion_recent_works_shortcode_content hook
					 *
					 * @hooked content - 10 (outputs the post content)
					 */
					do_action( 'fusion_recent_works_shortcode_content' );

					$stripped_content = ob_get_clean();

					// For boxed layouts add a content separator if there is a post content
					if ( $boxed_text == 'boxed' &&
						 $stripped_content
					) {
						$separator = '<div class="fusion-content-sep"></div>';
					}

					// On one column layouts render the "Learn More" and "View Project" buttons
					if ( $columns == '1' ) {
						$classes = sprintf( 'fusion-button fusion-button-small fusion-button-default fusion-button-%s fusion-button-%s',
											strtolower( $smof_data['button_shape'] ), strtolower( $smof_data['button_type'] ) );

						// Add the "Learn More" button
						$learn_more_button = sprintf( '<a href="%s" %s>%s</a>', $permalink, FusionCore_Plugin::attributes( $classes ), __( 'Learn More', 'fusion-core' ) );

						// If there is a project url, add the "View Project" button
						$view_project_button = '';
						if ( fusion_get_page_option( 'project_url', get_the_ID() ) ) {
							$view_project_button = sprintf( '<a href="%s" %s>%s</a>', fusion_get_page_option( 'project_url', get_the_ID() ),
															FusionCore_Plugin::attributes( $classes ), __( 'View Project', 'fusion-core' ) );
						}

						// Wrap buttons
						$buttons = sprintf( '<div %s>%s%s</div>', FusionCore_Plugin::attributes( 'fusion-portfolio-buttons' ), $learn_more_button, $view_project_button );
					}



					// Put it all together
					$post_content = sprintf( '<div %s>%s%s%s<div %s>%s%s</div></div>', FusionCore_Plugin::attributes( 'fusion-portfolio-content' ), $post_title, $post_terms, $separator,
											 FusionCore_Plugin::attributes( 'fusion-post-content' ), $stripped_content, $buttons );
				} else {
					// Get the rich snippets for grid layout without excerpts
					$rich_snippets = avada_render_rich_snippets_for_pages();
				}

				$portfolio_posts .= sprintf( '<div %s><div %s>%s%s%s</div></div>', FusionCore_Plugin::attributes( 'fusion-portfolio-post ' . $post_classes ),
											 FusionCore_Plugin::attributes( 'fusion-portfolio-content-wrapper' ), $rich_snippets, $image, $post_content );
			}
		} // end while
		wp_reset_query();

		// Wrap all the portfolio posts with the appropriate HTML markup
		// Carousel layout
		if( $layout == 'carousel' ) {
			$main_carousel = sprintf( '<ul %s>%s</ul>', FusionCore_Plugin::attributes( 'fusion-carousel-holder' ), $portfolio_posts );

			// Check if navigation should be shown
			$navigation = '';
			if ( $show_nav == 'yes' ) {
				$navigation = sprintf( '<div %s><span %s></span><span %s></span></div>', FusionCore_Plugin::attributes( 'fusion-carousel-nav' ),
									   FusionCore_Plugin::attributes( 'fusion-nav-prev' ), FusionCore_Plugin::attributes( 'fusion-nav-next' ) );
			}

			$html = sprintf( '<div %s><div %s><div %s>%s%s</div></div></div>', FusionCore_Plugin::attributes( 'recentworks-shortcode' ), FusionCore_Plugin::attributes( 'recentworks-shortcode-carousel' ), FusionCore_Plugin::attributes( 'fusion-carousel-positioner' ), $main_carousel, $navigation );

		// Grid layouts
		} else {
			// Reset vars
			$filter_wrapper = $filter = $styles = '';

			// Setup the filters, if enabled
			$portfolio_categories = get_terms( 'portfolio_category' );

			// Check if filters should be displayed
			if( $portfolio_categories &&
				$filters != 'no'
			) {

				// Check if the "All" filter should be displayed
				if ( $filters != 'yes-without-all' ) {
					$filter = sprintf( '<li %s><a %s>%s</a></li>', FusionCore_Plugin::attributes( 'fusion-filter fusion-filter-all fusion-active' ),
									   FusionCore_Plugin::attributes( 'recentworks-shortcode-filter-link', array( 'data-filter' => '*' ) ), __( 'All', 'fusion-core' ) );
					$first_filter = FALSE;
				} else {
					$first_filter = TRUE;
				}

				// Loop through categories
				foreach ( $portfolio_categories as $portfolio_category ) {
					// Only display filters of non excluded categories
					if ( ! in_array( $portfolio_category->slug, $cats_to_exclude ) ) {
						// Check if categories have been chosen
						if ( ! empty( self::$args['cat_slug'] ) ) {

							// Only display filters for explicitly included categories
							if ( in_array( $portfolio_category->slug, $cat_slugs ) ) {
								// Set the first category filter to active, if the all filter isn't shown
								$active_class = '';
								if ( $first_filter ) {
									$active_class = ' fusion-active';
									$first_filter = FALSE;
								}

								$filter .= sprintf( '<li %s><a %s>%s</a></li>', FusionCore_Plugin::attributes( 'fusion-filter fusion-hidden' . $active_class ),
													FusionCore_Plugin::attributes( 'recentworks-shortcode-filter-link', array( 'data-filter' => '.' . $portfolio_category->slug ) ), $portfolio_category->name );
							}
						// Display all categories
						} else {
							// Set the first category filter to active, if the all filter isn't shown
							$active_class = '';
							if ( $first_filter ) {
								$active_class = ' fusion-active';
								$first_filter = FALSE;
							}

							$filter .= sprintf( '<li %s><a %s>%s</a></li>', FusionCore_Plugin::attributes( 'fusion-filter fusion-hidden' . $active_class ),
												FusionCore_Plugin::attributes( 'recentworks-shortcode-filter-link', array( 'data-filter' => '.' . $portfolio_category->slug ) ), $portfolio_category->name );
						}
					}
				} // end foreach

				// Wrap filters
				$filter_wrapper = sprintf( '<ul %s>%s</ul>', FusionCore_Plugin::attributes( 'fusion-filters' ), $filter );

			}

			// For column spacing set needed css
			if ( $column_spacing ) {
				$styles = sprintf( '<style type="text/css">.fusion-recent-works-%s .fusion-portfolio-wrapper .fusion-col-spacing{padding:%spx;}</style>', $this->recent_works_counter, $column_spacing / 2 );
			}

			// Put it all together
			$html = sprintf( '<div %s>%s%s<div %s>%s</div></div>', FusionCore_Plugin::attributes( 'recentworks-shortcode' ), $filter_wrapper, $styles,
							 FusionCore_Plugin::attributes( 'recentworks-shortcode-portfolio-wrapper' ), $portfolio_posts );

		}

		$this->recent_works_counter++;

		return $html;

	}

	function attr() {

		$attr = array();

		// Add general classes
		$attr['class'] = sprintf( 'fusion-recent-works fusion-recent-works-%s fusion-recent-works-%s', $this->recent_works_counter, self::$args['layout'] );

		$attr['data-id'] = sprintf( '-rw-%s', $this->recent_works_counter );

		// Add classes for carousel layout
		if( self::$args['layout'] == 'carousel' ) {
			$attr['class'] .= ' recent-works-carousel';
			if( self::$args['picture_size'] == 'auto' ) {
				$attr['class'] .= ' picture-size-auto';
			}
		// Add classes for grid layouts
		} else {
			$attr['class'] .= sprintf( ' fusion-portfolio fusion-portfolio-%s fusion-portfolio-%s', $this->column, self::$args['boxed_text'] );


			if ( self::$args['layout'] == 'grid-with-excerpts' ) {
				$attr['class'] .= ' fusion-portfolio-text';
			}

			$attr['data-columns'] = $this->column;
		}

		// Add class for no spacing
		if ( self::$args['column_spacing'] == '0' ||
			 self::$args['column_spacing'] == '0px'
		) {
			$attr['class'] .= ' fusion-no-col-space';
		}

		// Add custom class
		if( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		// Add custom id
		if( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		// Add animation classes
		if ( self::$args['animation_type'] ) {
			$animations = FusionCore_Plugin::animations( array(
				'type'	  	=> self::$args['animation_type'],
				'direction' => self::$args['animation_direction'],
				'speed'	 	=> self::$args['animation_speed'],
				'offset' 	=> self::$args['animation_offset'],
			) );

			$attr = array_merge( $attr, $animations );

			$attr['class'] .= ' ' . $attr['animation_class'];
			unset( $attr['animation_class'] );
		}

		return $attr;

	}

	function portfolio_wrapper_attr( $args ) {

		$attr = array();

		$attr['class'] = 'fusion-portfolio-wrapper';

		$attr['id'] = sprintf( 'fusion-recent-works-%s', $this->recent_works_counter );

		$attr['data-picturesize'] = self::$args['picture_size'];

		if( self::$args['column_spacing'] ) {
			$margin = ( -1 ) * self::$args['column_spacing'] / 2;
			$attr['style'] = sprintf( 'margin: %spx;', $margin );
		}

		return $attr;

	}

	function carousel_attr() {

		$attr['class'] = 'fusion-carousel';

		if ( self::$args['carousel_layout'] == 'title_below_image' ) {
			$attr['data-metacontent'] = 'yes';
		}

		if ( self::$args['picture_size'] == 'fixed' ) {
			$attr['class'] .= ' fusion-recent-works-carousel-fixed';
		}

		$attr['data-autoplay'] = self::$args['autoplay'];
		$attr['data-columns'] = self::$args['columns'];
		$attr['data-itemmargin'] = self::$args['column_spacing'];
		$attr['data-itemwidth'] = 180;
		$attr['data-touchscroll'] = self::$args['mouse_scroll'];
		$attr['data-imagesize'] = self::$args['picture_size'];
		$attr['data-scrollitems'] = self::$args['scroll_items'];

		return $attr;
	}

	function filter_link_attr( $args ) {

		$attr = array();

		$attr['href'] = '#';

		if( $args['data-filter'] ) {
			$attr['data-filter'] = $args['data-filter'];
		}

		return $attr;

	}

	function set_image_size() {

		// Set columns object var to correct string
		switch( self::$args['columns'] ) {
			case 1:
				$this->column = 'one';
				break;
			case 2:
				$this->column = 'two';
				break;
			case 3:
				$this->column = 'three';
				break;
			case 4:
				$this->column = 'four';
				break;
			case 5:
				$this->column = 'five';
				break;
			case 6:
				$this->column = 'six';
				break;
			default:
				$this->image_size = 'full';
				break;
		}

		// Set the image size according to picture size param and layout
		if ( self::$args['picture_size'] == 'fixed' ) {
			if ( self::$args['layout'] == 'carousel' ) {
				$this->image_size = 'portfolio-two';
			} else {
				$this->image_size = 'portfolio-' . $this->column;
			}
		} else {
			$this->image_size = 'full';
		}
	}

	function get_post_content() {
		echo fusion_get_post_content( '', 'yes', self::$args['excerpt_length'], self::$args['strip_html'] )	;
	}
}

new FusionSC_RecentWorks();