<?php
class FusionSC_Blog {

	private $blog_sc_counter = 1;
	private $post_count = 1;
	private $post_id = 0;
	private $post_month = null;
	private $post_year = null;
	private $meta_info_settings = array();
	private $header = array();
	private $query = '';

	public static $args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		// containers
		add_action( 'fusion_blog_shortcode_before_loop', array( $this, 'before_loop' ) );
		add_action( 'fusion_blog_shortcode_before_loop_timeline', array( $this, 'before_loop_timeline' ) );
		add_action( 'fusion_blog_shortcode_after_loop', array( $this, 'after_loop' ) );

		// post / loop basic structure
		add_action( 'fusion_blog_shortcode_loop_header', array( $this, 'loop_header' ) );
		add_action( 'fusion_blog_shortcode_loop_footer', array( $this, 'loop_footer' ) );
		add_action( 'fusion_blog_shortcode_loop_content', array( $this, 'loop_content' ) );
		add_action( 'fusion_blog_shortcode_loop_content', array( $this, 'page_links' ) );
		add_action( 'fusion_blog_shortcode_loop', array( $this, 'loop' ) );

		// special blog layout structure
		add_action( 'fusion_blog_shortcode_wrap_loop_open', array( $this, 'wrap_loop_open' ) );
		add_action( 'fusion_blog_shortcode_wrap_loop_close', array( $this, 'wrap_loop_close' ) );
		add_action( 'fusion_blog_shortcode_date_and_format', array( $this, 'add_date_box' ) );
		add_action( 'fusion_blog_shortcode_date_and_format', array( $this, 'add_format_box' ) );
		add_action( 'fusion_blog_shortcode_timeline_date', array( $this, 'timeline_date' ) );

		// element attributes
		add_filter( 'fusion_attr_blog-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_blog-shortcode-posts-container', array( $this, 'posts_container_attr' ) );
		add_filter( 'fusion_attr_blog-shortcode-loop', array( $this, 'loop_attr' ) );
		add_filter( 'fusion_attr_blog-shortcode-post-title', array( $this, 'post_title_attr' ) );

		add_shortcode( 'blog', array( $this, 'render' ) );

	}

	/**
	 * Render the shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render( $args, $content = '') {
		global $smof_data;

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'			   			=> '',
				'id'				 		=> '',
				'blog_grid_column_spacing'	=> '40',
				'blog_grid_columns'   		=> '3',
				'cat_slug'			  		=> '',
				'excerpt'			  		=> 'yes',
				'excerpt_length'	 		=> '',
				'exclude_cats'		  		=> '',
				'layout' 			  		=> 'large',
				'meta_all'			  		=> 'yes',
				'meta_author' 		  		=> 'yes',
				'meta_categories'  	  		=> 'yes',
				'meta_comments'  	  		=> 'yes',
				'meta_date' 		  		=> 'yes',
				'meta_link'  	  	  		=> 'yes',
				'meta_read'					=> 'yes',
				'meta_tags'  	  	  		=> 'no',
				'number_posts'				=> '6',
				'offset'					=> '',
				'order'			   			=> 'DESC',
				'orderby'			   		=> 'date',
				'paging'			  		=> 'yes',
				'show_title'				=> 'yes',
				'scrolling'			  		=> 'infinite',
				'strip_html'		  		=> 'yes',
				'thumbnail'			  		=> 'yes',
				'title_link'				=> 'yes',
				'posts_per_page'	  		=> '-1',
				'taxonomy'					=> 'category',

				'excerpt_words'		  		=> '50',	//deprecated
				'title'				  		=> '',	// deprecated
			), $args
		);

		$defaults['blog_grid_column_spacing'] = FusionCore_Plugin::validate_shortcode_attr_value( $defaults['blog_grid_column_spacing'], '' );

		extract( $defaults );

		// Since WP 4.4 'title' param is reserved
		if ( $defaults['title'] ) {
			$defaults['show_title'] = $defaults['title'];
		}
		unset( $defaults['title'] );

		if ( is_front_page() ||
			is_home()
		) {
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
		} else {
			$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		}

		$defaults['paged'] = $paged;

		// covert all attributes to correct values for WP query
		if ( $defaults['number_posts'] ) {
			$defaults['posts_per_page'] = $defaults['number_posts'];
		}

		if ( $defaults['posts_per_page'] == -1 ) {
			$defaults['paging'] = 'no';
		}

		// Add hyphens for alternate layout options
		if ( $defaults['layout'] == 'large alternate' ) {
			$defaults['layout'] = 'large-alternate';
		} elseif ( $defaults['layout'] == 'medium alternate' ) {
			$defaults['layout'] = 'medium-alternate';
		}

		$defaults['load_more'] = FALSE;
		if ( $defaults['scrolling'] != 'pagination' ) {
			$defaults['paging'] = TRUE;

			if ( $defaults['scrolling'] == 'load_more_button' ) {
				$defaults['load_more'] = TRUE;
			}

			$defaults['scrolling'] = 'infinite';
		}

		( $defaults['meta_all'] == "yes" ) 			? ( $defaults['meta_all'] = TRUE ) 			: ( $defaults['meta_all'] = FALSE );
		( $defaults['meta_author'] == "yes" ) 		? ( $defaults['meta_author'] = TRUE ) 		: ( $defaults['meta_author'] = FALSE );
		( $defaults['meta_categories'] == "yes" ) 	? ( $defaults['meta_categories'] = TRUE ) 	: ( $defaults['meta_categories'] = FALSE );
		( $defaults['meta_comments'] == "yes" ) 	? ( $defaults['meta_comments'] = TRUE) 		: ( $defaults['meta_comments'] = FALSE );
		( $defaults['meta_date'] == "yes" ) 		? ( $defaults['meta_date'] = TRUE ) 		: ( $defaults['meta_date'] = FALSE );
		( $defaults['meta_link'] == "yes" ) 		? ( $defaults['meta_link'] = TRUE ) 		: ( $defaults['meta_link'] = FALSE );
		( $defaults['meta_tags'] == "yes" ) 		? ( $defaults['meta_tags'] = TRUE ) 		: ( $defaults['meta_tags'] = FALSE );
		( $defaults['paging'] == "yes" ) 			? ( $defaults['paging'] = TRUE ) 			: ( $defaults['paging'] = FALSE );
		( $defaults['strip_html'] == "yes" ) 		? ( $defaults['strip_html'] = TRUE ) 		: ( $defaults['strip_html'] = FALSE );
		( $defaults['thumbnail'] == "yes" ) 		? ( $defaults['thumbnail'] = TRUE ) 		: ( $defaults['thumbnail'] = FALSE );
		( $defaults['show_title'] == "yes" ) 		? ( $defaults['show_title'] = TRUE ) 		: ( $defaults['show_title'] = FALSE );
		( $defaults['title_link'] == "yes" ) 		? ( $defaults['title_link'] = TRUE ) 		: ( $defaults['title_link'] = FALSE );

		if ( $defaults['excerpt_length'] ||
			$defaults['excerpt_length'] === '0'
		) {
			$defaults['excerpt_words'] = $defaults['excerpt_length'];
		}

		// Combine meta info into one variable
		$defaults['meta_info_combined'] = $defaults['meta_all'] * ( $defaults['meta_author'] + $defaults['meta_date'] + $defaults['meta_categories'] + $defaults['meta_tags'] + $defaults['meta_comments'] + $defaults['meta_link'] );
		// Create boolean that holds info whether content should be excerpted
		$defaults['is_zero_excerpt'] = ( $defaults['excerpt'] == 'yes' && $defaults['excerpt_words'] < 1 ) ? 1 : 0;

		//check for cats to exclude; needs to be checked via exclude_cats param and '-' prefixed cats on cats param
		//exclution via exclude_cats param
		$cats_to_exclude = explode( ',' , $defaults['exclude_cats'] );
		$cats_id_to_exclude = array();
		if ( $cats_to_exclude ) {
			foreach ( $cats_to_exclude as $cat_to_exclude ) {
				$id_obj = get_category_by_slug( $cat_to_exclude );
				if ( $id_obj ) {
					$cats_id_to_exclude[] = $id_obj->term_id;
				}
			}
			if ( $cats_id_to_exclude ) {
				$defaults['category__not_in'] = $cats_id_to_exclude;
			}
		}

		//setting up cats to be used and exclution using '-' prefix on cats param; transform slugs to ids
		$cat_ids ='';
		$categories = explode( ',' , $defaults['cat_slug'] );
		if ( isset( $categories ) &&
			 $categories
		) {
			foreach ( $categories as $category ) {

				$id_obj = get_category_by_slug( $category );

				if ( $id_obj ) {
					if ( strpos( $category, '-' ) === 0 ) {
						$cat_ids .= '-' . $id_obj->cat_ID . ',';
					} else {
						$cat_ids .= $id_obj->cat_ID . ',';
					}
				}
			}
		}
		$defaults['cat'] = substr( $cat_ids, 0, -1 );

		if ( $defaults['blog_grid_column_spacing'] === '0' ) {
			$defaults['blog_grid_column_spacing'] = '0.0';
		}

		$defaults['blog_sc_query'] = TRUE;

		self::$args = $defaults;

		// Set the meta info settings for later use
		$this->meta_info_settings['post_meta'] = $defaults['meta_all'];
		$this->meta_info_settings['post_meta_author'] = $defaults['meta_author'];
		$this->meta_info_settings['post_meta_date'] = $defaults['meta_date'];
		$this->meta_info_settings['post_meta_cats'] = $defaults['meta_categories'];
		$this->meta_info_settings['post_meta_tags'] = $defaults['meta_tags'];
		$this->meta_info_settings['post_meta_comments'] = $defaults['meta_comments'];

		$fusion_query = new WP_Query( $defaults );

		$this->query = $fusion_query;


		//prepare needed wrapping containers
		$html = '';

		$html .= sprintf( '<div %s>', FusionCore_Plugin::attributes( 'blog-shortcode' ) );

		if ( self::$args['layout'] == 'grid' &&
			 self::$args['blog_grid_column_spacing']
		) {
			$html .= sprintf( '<style type="text/css">.fusion-blog-shortcode-%s .fusion-blog-layout-grid .fusion-post-grid{padding:%spx;}.fusion-blog-shortcode-%s .fusion-posts-container{margin-left: -%spx !important; margin-right:-%spx !important;}</style>', $this->blog_sc_counter, $defaults['blog_grid_column_spacing'] / 2, $this->blog_sc_counter, $defaults['blog_grid_column_spacing'] / 2, $defaults['blog_grid_column_spacing'] / 2 );
		}

		$html .= sprintf( '<div %s>', FusionCore_Plugin::attributes( 'blog-shortcode-posts-container' ) );

		ob_start();
		do_action( 'fusion_blog_shortcode_wrap_loop_open' );
		$wrap_loop_open = ob_get_contents();
		ob_get_clean();

		$html .= $wrap_loop_open;

		// Initialize the time stamps for timeline month/year check
		if ( self::$args['layout'] == 'timeline' ) {
			$this->post_count = 1;

			$prev_post_timestamp = null;
			$prev_post_month = null;
			$prev_post_year = null;
			$first_timeline_loop = false;
		}

		//do the loop
		if ( $fusion_query->have_posts() ) : while ( $fusion_query->have_posts() ) : $fusion_query->the_post();

			$this->post_id = get_the_ID();

			if ( self::$args['layout'] == 'timeline' ) {
				// Set the time stamps for timeline month/year check
				$post_timestamp = get_the_time( 'U' );
				$this->post_month = date( 'n', $post_timestamp );
				$this->post_year = get_the_date( 'Y' );
				$current_date = get_the_date( 'Y-n' );

				$date_params['prev_post_month'] = $prev_post_month;
				$date_params['post_month'] = $this->post_month;
				$date_params['prev_post_year'] = $prev_post_year;
				$date_params['post_year'] = $this->post_year;

				// Set the timeline month label
				ob_start();
				do_action( 'fusion_blog_shortcode_timeline_date', $date_params );
				$timeline_date = ob_get_contents();
				ob_get_clean();

				$html .= $timeline_date;
			}

			ob_start();
			do_action( 'fusion_blog_shortcode_before_loop' );
			$before_loop_action = ob_get_contents();
			ob_get_clean();

			$html .= $before_loop_action;

			if ( self::$args['layout'] == 'grid' ||
				self::$args['layout'] == 'timeline'
			) {
				$html .= sprintf( '<div %s>', FusionCore_Plugin::attributes( 'fusion-post-wrapper' ) );
			}

			$this->header = array(
				'title_link' => true,
			);

			ob_start();
			do_action( 'fusion_blog_shortcode_loop_header' );

			do_action( 'fusion_blog_shortcode_loop_content' );

			do_action( 'fusion_blog_shortcode_loop_footer' );

			do_action( 'fusion_blog_shortcode_after_loop' );
			$loop_actions = ob_get_contents();
			ob_get_clean();

			$html .= $loop_actions;

			if ( self::$args['layout'] == 'timeline' ) {
				$prev_post_timestamp = $post_timestamp;
				$prev_post_month = $this->post_month;
				$prev_post_year = $this->post_year;
				$this->post_count++;
			}

		endwhile;
		else:
		endif;

		ob_start();
		do_action( 'fusion_blog_shortcode_wrap_loop_close' );

		$wrap_loop_close_action = ob_get_contents();
		ob_get_clean();

		$html .= $wrap_loop_close_action;

		$html .= '</div>';

		if ( self::$args['paging'] ) {
			ob_start();
			fusion_pagination( $this->query->max_num_pages, $range = 2, $this->query );
			$pagination = ob_get_contents();
			ob_get_clean();

			$html .= $pagination;
		}

		// If infinite scroll with "load more" button is used
		if ( self::$args['load_more'] && self::$args['posts_per_page'] != -1 ) {
			$html .= sprintf( '<div class="fusion-load-more-button fusion-blog-button fusion-clearfix">%s</div>', apply_filters( 'avada_load_more_posts_name', __( 'Load More Posts', 'fusion-core' ) ) );
		}

		$html .= '</div>';

		wp_reset_query();

		$this->blog_sc_counter++;

		return $html;

	}

	function attr() {

		$attr = array();

		// Set the correct layout class
		$blog_layout = '';
		if( self::$args['layout'] == 'timeline' ) {
			$blog_layout = 'fusion-blog-layout-timeline-wrapper';
		} elseif ( self::$args['layout'] == 'grid' ) {
			$blog_layout = 'fusion-blog-layout-grid-wrapper';
		} else {
			$blog_layout = sprintf( 'fusion-blog-layout-%s', self::$args['layout'] );
		}

		$attr['class'] = sprintf( 'fusion-blog-shortcode fusion-blog-shortcode-%s fusion-blog-archive %s fusion-blog-%s', $this->blog_sc_counter, $blog_layout, self::$args['scrolling'] );

		if ( ! self::$args['thumbnail'] ) {
			$attr['class'] .= ' fusion-blog-no-images';
		}

		if ( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}

		if ( self::$args['blog_grid_column_spacing'] == '0' ||
			 self::$args['blog_grid_column_spacing'] == '0px'
		) {
			$attr['class'] .= ' fusion-no-col-space';
		}

		if ( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		return $attr;

	}

	function posts_container_attr() {
		global $post;

		$attr = array();

		$load_more = '';
		if ( self::$args['load_more'] ) {
			$load_more = ' fusion-posts-container-load-more';
		}

		$attr['class'] = sprintf( 'fusion-posts-container fusion-posts-container-%s%s', self::$args['scrolling'], $load_more );

		$attr['data-pages'] = $this->query->max_num_pages;

		if ( self::$args['layout'] == 'grid' ) {
			 $attr['class'] .= sprintf( ' fusion-blog-layout-grid fusion-blog-layout-grid-%s isotope', self::$args['blog_grid_columns'] );

			 if ( self::$args['blog_grid_column_spacing'] ||
			 	  self::$args['blog_grid_column_spacing'] === '0'
			 ) {
			 	$attr['data-grid-col-space'] = self::$args['blog_grid_column_spacing'];
			 }

			 $negative_margin = ( -1 ) * self::$args['blog_grid_column_spacing'] / 2;

			 $attr['style'] = sprintf( 'margin: %spx %spx 0;height:500px;', $negative_margin, $negative_margin );
		}

		return $attr;

	}

	function wrap_loop_open() {
		global $post;

		$wrapper = $class_timeline_icon = '';

		if ( self::$args['layout'] == 'timeline' ) {

			$wrapper = sprintf( '<div %s><i %s></i></div>', FusionCore_Plugin::attributes( 'fusion-timeline-icon' . $class_timeline_icon ), FusionCore_Plugin::attributes( 'fusion-icon-bubbles' ) );
			$wrapper .= sprintf( '<div %s>', FusionCore_Plugin::attributes( 'fusion-blog-layout-timeline fusion-clearfix' ) );
			$wrapper .= '<div class="fusion-timeline-line"></div>';
		}

		echo $wrapper;

	} // end wrap_loop_open()

	function wrap_loop_close() {

		$wrapper = '';

		if ( self::$args['layout'] == 'timeline' ) {
			if ( $this->post_count > 1 ) {
				$wrapper = '</div>';
			}
			$wrapper .= '</div>';
		}

		if ( self::$args['layout'] == 'grid' ) {
			$wrapper .= '<div class="fusion-clearfix"></div>';
		}

		echo $wrapper;

	} // end wrap_loop_close()

	function before_loop() {

		echo sprintf( '<div %s>', FusionCore_Plugin::attributes( 'blog-shortcode-loop' ) ) . "\n";

	} // end before_loop()

	function after_loop() {

		echo '</div>' . "\n";

		if ( self::$args['layout'] == 'grid' ||
			 self::$args['layout'] == 'timeline'
		) {
			echo '</div>' . "\n";
		}

	} // end after_loop()

	function loop_attr() {
		global $smof_data;

		$defaults = array(
			'post_id' => '',
			'post_count' => '',
		);

		$attr['id'] = 'post-' . $this->post_id;

		$extra_classes = array();

		// On timeline and grid layouts, hide hide the full post item when there is no post content and no post meta data and no featured image
		if ( ( self::$args['layout'] == 'grid' || self::$args['layout'] == 'timeline' ) &&
			 self::$args['meta_info_combined'] == 0 &&
			 self::$args['is_zero_excerpt'] &&
			 ! has_post_thumbnail() &&
			 ! get_post_meta( $this->post_id, 'pyre_video', true )
		) {
			$attr['style'] = 'display:none;';
		// In any other case, add the correct post class
		} else {
			$extra_classes[] = sprintf( 'fusion-post-%s', self::$args['layout'] );
		}

		// Set the correct column class for every post
		if ( self::$args['layout'] == 'timeline' ) {

			if ( ( $this->post_count % 2 ) > 0 ) {
				$timeline_align = ' fusion-left-column';
			} else {
				$timeline_align = ' fusion-right-column';
			}

			$extra_classes[] = 'fusion-clearfix' . $timeline_align;
		}

		// Set the has-post-thumbnail if a video is used. This is needed if no featured image is present.
		if ( get_post_meta( get_the_ID(), 'pyre_video', true ) ) {
			$extra_classes[] = 'has-post-thumbnail';
		}

		$post_class = get_post_class( $extra_classes, $this->post_id );

		if ( $post_class && is_array( $post_class ) ) {
			$classes = implode( ' ', get_post_class( $extra_classes, $this->post_id ) );
			$attr['class'] = $classes;
		}

		return $attr;

	} // end loop_attr();

	function get_slideshow() {
		global $smof_data;

		$html = '';

		if ( ! post_password_required( get_the_ID() ) && class_exists( 'Avada_Init' ) ) {

			$slideshow = array(
				'images' => $this->get_post_thumbnails( get_the_ID(), $smof_data['posts_slideshow_number'] )
			);

			if ( get_post_meta( $this->post_id, 'pyre_video', true) ) {
				$slideshow['video'] = get_post_meta( $this->post_id, 'pyre_video', true );
			}

			if ( self::$args['layout'] == 'medium' ||
				 self::$args['layout'] == 'medium alternate'
			) {
				$slideshow['size'] = 'blog-medium';
			}

			ob_start();
			$atts = self::$args;
			include( locate_template( 'new-slideshow-blog-shortcode.php', false ) );
			$post_slideshow_action = ob_get_contents();
			ob_get_clean();

			$html .= $post_slideshow_action;

		}

		return $html;
	}

	function get_post_thumbnails( $post_id, $count = '' ) {
		global $smof_data;

		$attachment_ids = array();

		if ( get_post_thumbnail_id( $post_id ) ) {
			$attachment_ids[] = get_post_thumbnail_id( $post_id );
		}

		$i = 2;
		while ( $i <= $smof_data['posts_slideshow_number'] ) {

			if ( kd_mfi_get_featured_image_id( 'featured-image-' . $i, 'post' ) ) {
				$attachment_ids[] = kd_mfi_get_featured_image_id( 'featured-image-' . $i, 'post' );
			}

			$i++;
		}

		if ( isset( $count ) && $count >= 1 ) {
			$attachment_ids = array_slice( $attachment_ids, 0, $count );
		}

		return $attachment_ids;

	} // end get_post_thumbnails()


	function loop_header() {

		$defaults = array(
			'title_link' => false,
		);

		$args = wp_parse_args( $this->header, $defaults );

		$pre_title_content = $meta_data = $content_sep = $link = '';

		if ( self::$args['thumbnail'] &&
			 self::$args['layout'] != 'medium-alternate'
		) {
			$pre_title_content = $this->get_slideshow();
		}

		if ( self::$args['layout'] == 'medium-alternate' ||
			 self::$args['layout'] == 'large-alternate'
		) {
			$pre_title_content .= sprintf( '<div %s>', FusionCore_Plugin::attributes( 'fusion-date-and-formats' ) );
			ob_start();
			do_action( 'fusion_blog_shortcode_date_and_format' );
			$pre_title_content .= ob_get_contents();
			ob_get_clean();
			$pre_title_content .= '</div>';

			if ( self::$args['thumbnail'] &&
				 self::$args['layout'] == 'medium-alternate'
			) {
				$pre_title_content .= $this->get_slideshow();
			}

			if ( self::$args['meta_all'] ) {
				$meta_data .= avada_render_post_metadata( 'alternate', $this->meta_info_settings );
			}
		}

		if ( self::$args['layout'] == 'grid' ||
			 self::$args['layout'] == 'timeline'
		) {
			$content_wrapper_styles	= '';

			if ( self::$args['meta_info_combined'] > 0 &&
				 ! self::$args['is_zero_excerpt']
			) {
				$content_sep = sprintf( '<div %s></div>', FusionCore_Plugin::attributes('fusion-content-sep' ) );
			}

			if ( ! self::$args['meta_info_combined'] &&
				 self::$args['is_zero_excerpt'] &&
				 ! self::$args['show_title']
			) {
				$content_wrapper_styles = 'style="display:none;"';
			}

			if ( self::$args['meta_all'] ) {
				$meta_data .= avada_render_post_metadata( 'grid_timeline', $this->meta_info_settings );
			}

			$pre_title_content .=  sprintf( '<div %s%s>', FusionCore_Plugin::attributes( 'fusion-post-content-wrapper' ), $content_wrapper_styles );
		}

		$pre_title_content .=  sprintf( '<div %s>', FusionCore_Plugin::attributes( 'fusion-post-content post-content' ) );

		if ( self::$args['show_title'] ) {
			if ( self::$args['title_link'] ) {
				$link_target = '';
				if( fusion_get_page_option( 'link_icon_target', get_the_ID() ) == 'yes' ||
					fusion_get_page_option( 'post_links_target', get_the_ID() ) == 'yes' ) {
					$link_target = ' target="_blank"';
				}
				$link = sprintf( '<a href="%s"%s>%s</a>', get_permalink(), $link_target, get_the_title() );
			} else {
				$link = get_the_title();
			}
		}

		if ( self::$args['layout'] == 'timeline' ) {
			$pre_title_content .= sprintf( '<div %s></div><div %s></div>', FusionCore_Plugin::attributes( 'fusion-timeline-circle' ), FusionCore_Plugin::attributes( 'fusion-timeline-arrow' ) );
		}

		$html = sprintf( '%s<h2 %s>%s</h2>%s%s', $pre_title_content, FusionCore_Plugin::attributes( 'blog-shortcode-post-title' ), $link, $meta_data, $content_sep );

		echo $html;

	} // end loop_header()


	function post_title_attr() {
		global $smof_data;

		$attr = array();

		if ( $smof_data['disable_date_rich_snippet_pages'] ) {
			$attr['class'] = 'entry-title';
		}


		return $attr;

	} // end post_title_attr();

	function loop_footer() {

		if ( self::$args['layout'] == 'grid' ||
			 self::$args['layout'] == 'timeline'
		) {
			echo '</div>';

			if ( self::$args['meta_info_combined'] > 0 ) {
				$inner_content = $this->read_more();
				$inner_content .= $this->grid_timeline_comments();

				echo sprintf( '<div class="fusion-meta-info">%s</div>', $inner_content );
			}
		}

		echo '</div>';
		echo '<div class="fusion-clearfix"></div>';

		if ( self::$args['meta_info_combined'] > 0 && self::$args['layout'] == 'large' ||
			 self::$args['meta_info_combined'] > 0 && self::$args['layout'] == 'medium'
		) {
			echo sprintf( '<div class="fusion-meta-info">%s%s</div>', avada_render_post_metadata( 'standard', $this->meta_info_settings ), $this->read_more() );
		}

		if ( self::$args['meta_all'] && self::$args['layout'] == 'large-alternate' ||
			 self::$args['meta_all'] && self::$args['layout'] == 'medium-alternate'

		) {
			echo $this->read_more();
		}

	} // end loop_footer()

	function add_date_box() {
		global $smof_data;

		$inner_content = sprintf( '<div %s>', FusionCore_Plugin::attributes( 'fusion-date-box updated' ) );

		$inner_content .= sprintf( '<span %s>%s</span>', FusionCore_Plugin::attributes( 'fusion-date' ), get_the_time( $smof_data['alternate_date_format_day'] ) );
		$inner_content .= sprintf( '<span %s>%s</span>', FusionCore_Plugin::attributes( 'fusion-month-year' ), get_the_time( $smof_data['alternate_date_format_month_year'] ) );

		$inner_content .= '</div>';

		echo $inner_content;

	} // end add_date_box()

	function add_format_box() {

		switch( get_post_format() ) {
			case 'gallery':
				$format_class = 'images';
				break;
			case 'link':
				$format_class = 'link';
				break;
			case 'image':
				$format_class = 'image';
				break;
			case 'quote':
				$format_class = 'quotes-left';
				break;
			case 'video':
				$format_class = 'film';
				break;
			case 'audio':
				$format_class = 'headphones';
				break;
			case 'chat':
				$format_class = 'bubbles';
				break;
			default:
				$format_class = 'pen';
				break;
		}

		$inner_content = sprintf( '<div %s>
								  	<i %s></i>
								  </div>', FusionCore_Plugin::attributes( 'fusion-format-box' ), FusionCore_Plugin::attributes( 'fusion-icon-' . $format_class ) );

		echo $inner_content;

	} // end add_format_box()

	function timeline_date( $date_params ) {
		global $smof_data;

		$defaults = array(
			'prev_post_month' 	=> null,
			'post_month' 		=> null,
			'prev_post_year' 	=> null,
			'post_year' 		=> null
		);

		$args = wp_parse_args( $date_params, $defaults );
		$inner_content = '';

		if ( $args['prev_post_month'] != $args['post_month'] ||
			 $args['prev_post_year'] != $args['post_year']
		) {

			if ( $this->post_count > 1 ) {
				$inner_content = '</div>';
			}

			$inner_content .= sprintf( '<h3 %s>%s</h3>', FusionCore_Plugin::attributes( 'fusion-timeline-date' ), get_the_date( $smof_data['timeline_date_format'] ) );
			$inner_content .= '<div class="fusion-collapse-month">';
		}

		echo $inner_content;

	} // end timeline_date()

	function grid_timeline_comments() {

		if ( self::$args['meta_comments'] ) {

			$comments_icon = sprintf( '<i %s></i>&nbsp;', FusionCore_Plugin::attributes( 'fusion-icon-bubbles' ) );

			if ( ! post_password_required( get_the_ID() ) ) {
				ob_start();
				comments_popup_link( $comments_icon . __( '0', 'fusion-core' ), $comments_icon . __( '1', 'fusion-core' ), $comments_icon . __( '%', 'fusion-core' ) );
				$comments = ob_get_contents();
				ob_get_clean();
			} else {
				$comments = sprintf( '<i class="fusion-icon-bubbles"></i>&nbsp;%s', __( 'Protected', 'fusion-core' ) );
			}

			$inner_content = sprintf( '<div %s>%s</div>', FusionCore_Plugin::attributes( 'fusion-alignright' ), $comments );

			return $inner_content;

		}

	} // end grid_timeline_comments()

	function read_more() {

		if ( self::$args['meta_link'] ) {
			$inner_content = '';

			if ( self::$args['meta_read'] ) {

				if ( self::$args['layout'] == 'grid' ||
					 self::$args['layout'] == 'timeline'
				) {
					$read_more_wrapper_class = 'fusion-alignleft';
				} else {
					$read_more_wrapper_class = 'fusion-alignright';
				}

				$link_target = '';
				if( fusion_get_page_option( 'link_icon_target', get_the_ID() ) == 'yes' ||
					fusion_get_page_option( 'post_links_target', get_the_ID() ) == 'yes' ) {
					$link_target = ' target="_blank"';
				}

				$inner_content .= sprintf( '<div %s><a class="fusion-read-more" href="%s"%s>%s</a></div>', FusionCore_Plugin::attributes( $read_more_wrapper_class ),  get_permalink(), $link_target,  apply_filters( 'avada_read_more_name', __( 'Read More', 'fusion-core' ) ) );

				if ( self::$args['layout'] == 'large-alternate' ||
					 self::$args['layout'] == 'medium-alternate'
				) {
					$inner_content = sprintf( '<div class="fusion-meta-info">%s</div>', $inner_content );
				}

			}

			return $inner_content;
		}

	} // end read_more()

	function loop_content() {

		$content = fusion_get_post_content( '', self::$args['excerpt'], self::$args['excerpt_words'], self::$args['strip_html'] );

		$content = sprintf( '<div class="fusion-post-content-container">%s</div>', $content );

		echo $content;


	} // end loop_content()

	function page_links() {

		avada_link_pages();

	} // end page_links()

}

new FusionSC_Blog();

// Add needed action and filter to make sure queries with offset have correct pagination
add_action('pre_get_posts', 'fusion_query_offset', 1 );
function fusion_query_offset( &$query ) {
    // Check if we are in a blog shortcode query and if offset is set
	if ( isset( $query ) && is_array( $query->query ) && ! array_key_exists( 'blog_sc_query', $query->query ) ||
		 ! $query->query['offset']
	) {
		return;
    }

    // The query is paged
    if ( $query->is_paged ) {
        // Manually determine page query offset (offset + ( current page - 1 ) x posts per page )
        $page_offset = $query->query['offset'] + ( ( $query->query_vars['paged'] - 1 ) * $query->query['posts_per_page'] );

        // Apply adjusted page offset
        $query->set( 'offset', $page_offset );

	// This is the first page, so we can just use the offset
    } else {
        $query->set( 'offset', $query->query['offset'] );
    }
}

add_filter('found_posts', 'fusion_adjust_offset_pagination', 1, 2 );
function fusion_adjust_offset_pagination( $found_posts, $query ) {
    // Modification only in a blog shortcode query with set offset
    if ( array_key_exists( 'blog_sc_query', $query->query ) &&
    	$query->query['offset']
    ) {
        //Reduce found_posts count by the offset
        return $found_posts - $query->query['offset'];
    }
    return $found_posts;
}

// Make sure that the blog pagination also works on front page
add_filter( 'redirect_canonical', 'fusion_blog_redirect_canonical' );
function fusion_blog_redirect_canonical( $redirect_url ) {
	global $wp_rewrite, $wp_query;

	if ( $wp_rewrite->using_permalinks() ) {

		// Check the query var
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		// Check query paged
		} elseif ( ! empty( $wp_query->query['paged'] ) ) {
			$paged = $wp_query->query['paged'];
		} else {
			$paged = 1;
		}

		if ( 1 < $paged ) {
			return false;
		}
	}

	return $redirect_url;
}