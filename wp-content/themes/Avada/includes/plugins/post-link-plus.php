<?php
/**
 * Retrieve adjacent post link.
 *
 * Can either be next or previous post link.
 *
 * Based on get_adjacent_post() from wp-includes/link-template.php
 *
 * @param array $r Arguments.
 * @param bool $previous Optional. Whether to retrieve previous post.
 * @return array of post objects.
 */
function fusion_get_adjacent_post_plus($r, $previous = true ) {
	global $post, $wpdb;

	extract( $r, EXTR_SKIP );

	if ( empty( $post ) ) {
		return null;
	}

	//	Sanitize $order_by, since we are going to use it in the SQL query.
	// Default to 'post_date'.
	switch ( $order_by ) {

		case 'post_date':
		case 'post_title':
		case 'post_excerpt':
		case 'post_name':
		case 'post_modified':
			$order_format = '%s';
			break;
		case 'ID':
		case 'post_author':
		case 'post_parent':
		case 'menu_order':
		case 'comment_count':
			$order_format = '%d';
			break;
		case ( 'custom' == $order_by && ! empty( $meta_key ) ):
		 	// Don't allow a custom sort if meta_key is empty.
			$order_format = '%s';
			break;
		case ( 'numeric' == $order_by && ! empty( $meta_key ) ):
			$order_format = '%d';
			break;
		default:
			$order_by     = 'post_date';
			$order_format = '%s';

	}

	//	Sanitize $order_2nd.
	// Only columns containing unique values are allowed here.
	// Default to 'post_date'.
	switch ( $order_2nd ) {

		case 'post_date':
		case 'post_title':
		case 'post_modified':
			$order_format2 = '%s';
			break;
		case 'ID':
			$order_format2 = '%d';
			break;
		default:
			$order_2nd     = 'post_date';
			$order_format2 = '%s';

	}

	//	Sanitize num_results (non-integer or negative values trigger SQL errors)
	$num_results = ( intval( $num_results ) < 2 ) ? 1 : intval( $num_results );

	$current_post = $post->$order_by;
	$order_by     = 'p.' . $order_by;
	$meta_join    = '';
	//	Queries involving custom fields require an extra table join
	if ( in_array( $order_by, array( 'custom', 'numeric' ) ) ) {
		$current_post = get_post_meta( $post->ID, $meta_key, true );
		$order_by     = ( 'numeric' === $order_by ) ? 'm.meta_value+0' : 'm.meta_value';
		$meta_join    = $wpdb->prepare( " INNER JOIN $wpdb->postmeta AS m ON p.ID = m.post_id AND m.meta_key = %s", $meta_key );
	} elseif ( $in_same_meta ) {
		$current_post = $post->$order_by;
		$order_by     = 'p.' . $order_by;
		$meta_join    = $wpdb->prepare(" INNER JOIN $wpdb->postmeta AS m ON p.ID = m.post_id AND m.meta_key = %s", $in_same_meta );
	}

	//	Get the current post value for the second sort column
	$current_post2 = $post->$order_2nd;
	$order_2nd     = 'p.' . $order_2nd;

	//	Get the list of post types. Default to current post type
	if ( empty( $post_type ) ) {
		$post_type = "'$post->post_type'";
	}

	//	Put this section in a do-while loop to enable the loop-to-first-post option
	do {
		$join                = $meta_join;
		$excluded_categories = $ex_cats;
		$included_categories = $in_cats;
		$excluded_posts      = $ex_posts;
		$included_posts      = $in_posts;

		$in_same_term_sql = $in_same_author_sql = $in_same_meta_sql = $ex_cats_sql = $in_cats_sql = $ex_posts_sql = $in_posts_sql = '';

		// Get the list of hierarchical taxonomies, including customs (don't assume taxonomy = 'category')
		$taxonomies = array_filter( get_post_taxonomies( $post->ID ), "is_taxonomy_hierarchical" );

		if ( ($in_same_cat || $in_same_tax || $in_same_format || ! empty( $excluded_categories ) || ! empty( $included_categories ) ) && ! empty( $taxonomies ) ) {
			$cat_array = $tax_array = $format_array = array();

			if ( $in_same_cat ) {
				$cat_array = wp_get_object_terms( $post->ID, $taxonomies, array( 'fields' => 'ids' ) );
			}
			if ( $in_same_tax && ! $in_same_cat ) {
				if ( true === $in_same_tax ) {
					if ( array( 'category' ) != $taxonomies ) {
						$taxonomies = array_diff( $taxonomies, array( 'category' ) );
					}
				}else {
					$taxonomies = (array) $in_same_tax;
				}
				$tax_array = wp_get_object_terms( $post->ID, $taxonomies, array( 'fields' => 'ids' ) );
			}
			if ( $in_same_format ) {
				$taxonomies[] = 'post_format';
				$format_array = wp_get_object_terms( $post->ID, 'post_format', array( 'fields' => 'ids' ) );
			}

			$join .= " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy IN (\"" . implode( '", "', $taxonomies ) . "\")";

			$term_array = array_unique( array_merge( $cat_array, $tax_array, $format_array ) );
			if ( ! empty( $term_array ) ) {
				$in_same_term_sql = "AND tt.term_id IN (" . implode( ',', $term_array ) . ")";
			}

			if ( ! empty( $excluded_categories ) ) {
				// Support for both (1 and 5 and 15) and (1, 5, 15) delimiter styles
				$delimiter = ( strpos( $excluded_categories, ',' ) !== false ) ? ',' : 'and';
				$excluded_categories = array_map( 'intval', explode( $delimiter, $excluded_categories ) );
				// Three category exclusion methods are supported: 'strong', 'diff', and 'weak'.
				// Default is 'weak'. See the plugin documentation for more information.
				if ( 'strong' === $ex_cats_method ) {
					$taxonomies = array_filter( get_post_taxonomies( $post->ID ), 'is_taxonomy_hierarchical' );
					if ( function_exists( 'get_post_format' ) ) {
						$taxonomies[] = 'post_format';
					}
					$ex_cats_posts = get_objects_in_term( $excluded_categories, $taxonomies );
					if ( ! empty( $ex_cats_posts ) ) {
						$ex_cats_sql = "AND p.ID NOT IN (" . implode( $ex_cats_posts, ',' ) . ")";
					}
				} else {
					if ( ! empty( $term_array ) && ! in_array( $ex_cats_method, array( 'diff', 'differential' ) ) ) {
						$excluded_categories = array_diff( $excluded_categories, $term_array );
					}
					if ( ! empty( $excluded_categories ) ) {
						$ex_cats_sql = "AND tt.term_id NOT IN (" . implode( $excluded_categories, ',' ) . ')';
					}
				}
			}

			if ( ! empty( $included_categories ) ) {
				$in_same_term_sql    = ''; // in_cats overrides in_same_cat
				$delimiter           = ( false !== strpos( $included_categories, ',' ) ) ? ',' : 'and';
				$included_categories = array_map( 'intval', explode( $delimiter, $included_categories ) );
				$in_cats_sql         = "AND tt.term_id IN (" . implode( ',', $included_categories ) . ")";
			}
		}

		// Optionally restrict next/previous links to same author
		if ( $in_same_author ) {
			$in_same_author_sql = $wpdb->prepare( "AND p.post_author = %d", $post->post_author );
		}
		// Optionally restrict next/previous links to same meta value
		if ( $in_same_meta && 'custom' != $r['order_by'] && 'numeric' != $r['order_by'] ) {
			$in_same_meta_sql = $wpdb->prepare( "AND m.meta_value = %s", get_post_meta( $post->ID, $in_same_meta, true ) );
		}
		// Optionally exclude individual post IDs
		if ( ! empty( $excluded_posts ) ) {
			$excluded_posts = array_map( 'intval', explode( ',', $excluded_posts ) );
			$ex_posts_sql   = " AND p.ID NOT IN (" . implode( ',', $excluded_posts ) . ")";
		}
		// Optionally include individual post IDs
		if ( ! empty( $included_posts ) ) {
			$included_posts = array_map( 'intval', explode( ',', $included_posts ) );
			$in_posts_sql = " AND p.ID IN (" . implode( ',', $included_posts ) . ")";
		}

		$adjacent = $previous ? 'previous' : 'next';
		$order    = $previous ? 'DESC' : 'ASC';
		$op       = $previous ? '<' : '>';

		// Optionally get the first/last post. Disable looping and return only one result.
		if ( $end_post ) {
			$order       = $previous ? 'ASC' : 'DESC';
			$num_results = 1;
			$loop        = false;
			// display the end post link even when it is the current post
			if ( 'fixed' === $end_post ) {
				$op = $previous ? '<=' : '>=';
			}
		}
		// If there is no next/previous post, loop back around to the first/last post.
		if ( $loop && isset( $result ) ) {
			$op   = $previous ? '>=' : '<=';
			// prevent an infinite loop if no first/last post is found
			$loop = false;
		}

		$join  = apply_filters( "get_{$adjacent}_post_plus_join", $join, $r );

		// In case the value in the $order_by column is not unique, select posts based on the $order_2nd column as well.
		// This prevents posts from being skipped when they have, for example, the same menu_order.
		$where = apply_filters( "get_{$adjacent}_post_plus_where", $wpdb->prepare( "WHERE ( $order_by $op $order_format OR $order_2nd $op $order_format2 AND $order_by = $order_format ) AND p.post_type IN ($post_type) AND p.post_status = 'publish' $in_same_term_sql $in_same_author_sql $in_same_meta_sql $ex_cats_sql $in_cats_sql $ex_posts_sql $in_posts_sql", $current_post, $current_post2, $current_post ), $r );
		$sort  = apply_filters( "get_{$adjacent}_post_plus_sort", "ORDER BY $order_by $order, $order_2nd $order LIMIT $num_results", $r );

		$query     = "SELECT DISTINCT p.* FROM $wpdb->posts AS p $join $where $sort";
		$query_key = 'adjacent_post_' . md5( $query );
		$result    = wp_cache_get( $query_key );
		if ( false !== $result ) {
			return $result;
		}

		// Use get_results instead of get_row, in order to retrieve multiple adjacent posts (when $num_results > 1)
		// Add DISTINCT keyword to prevent posts in multiple categories from appearing more than once
		$result = $wpdb->get_results( "SELECT DISTINCT p.* FROM $wpdb->posts AS p $join $where $sort" );
		$result = ( null === $result ) ? '' : $result;

	} while ( ! $result && $loop );

	wp_cache_set( $query_key, $result );
	return $result;
}

/**
 * Display previous post link that is adjacent to the current post.
 *
 * Based on previous_post_link() from wp-includes/link-template.php
 *
 * @param array|string $args Optional. Override default arguments.
 * @return bool True if previous post link is found, otherwise false.
 */
function fusion_previous_post_link_plus( $args = '' ) {
	return fusion_adjacent_post_link_plus( $args, '&laquo; %link', true );
}

/**
 * Display next post link that is adjacent to the current post.
 *
 * Based on next_post_link() from wp-includes/link-template.php
 *
 * @param array|string $args Optional. Override default arguments.
 * @return bool True if next post link is found, otherwise false.
 */
function fusion_next_post_link_plus( $args = '' ) {
	return fusion_adjacent_post_link_plus( $args, '%link &raquo;', false );
}

/**
 * Display adjacent post link.
 *
 * Can be either next post link or previous.
 *
 * Based on adjacent_post_link() from wp-includes/link-template.php
 *
 * @param array|string $args Optional. Override default arguments.
 * @param bool $previous Optional, default is true. Whether display link to previous post.
 * @return bool True if next/previous post is found, otherwise false.
 */
function fusion_adjacent_post_link_plus( $args = '', $format = '%link &raquo;', $previous = true ) {
	$defaults = array(
		'order_by'       => 'post_date',
		'order_2nd'      => 'post_date',
		'meta_key'       => '',
		'post_type'      => '',
		'loop'           => false,
		'end_post'       => false,
		'thumb'          => false,
		'max_length'     => 0,
		'format'         => '',
		'link'           => '%title',
		'date_format'    => '',
		'tooltip'        => '%title',
		'in_same_cat'    => false,
		'in_same_tax'    => false,
		'in_same_format' => false,
		'in_same_author' => false,
		'in_same_meta'   => false,
		'ex_cats'        => '',
		'ex_cats_method' => 'weak',
		'in_cats'        => '',
		'ex_posts'       => '',
		'in_posts'       => '',
		'before'         => '',
		'after'          => '',
		'num_results'    => 1,
		'return'         => false,
		'echo'           => true
	);

	//	If Post Types Order plugin is installed,
	// default to sorting on menu_order
	if ( function_exists( 'CPTOrderPosts' ) ) {
		$defaults['order_by'] = 'menu_order';
	}

	$r = wp_parse_args( $args, $defaults );
	if ( empty( $r['format'] ) ) {
		$r['format'] = $format;
	}
	if ( empty( $r['date_format'] ) ) {
		$r['date_format'] = get_option('date_format');
	}
	if ( ! function_exists( 'get_post_format' ) ) {
		$r['in_same_format'] = false;
	}

	$posts = fusion_get_adjacent_post_plus( $r, $previous );
	if ( $previous && is_attachment() ) {
		$posts   = array();
		$posts[] = & get_post( $GLOBALS['post']->post_parent );
	}

	// If there is no next/previous post,
	// return false so themes may conditionally display inactive link text.
	if ( empty( $posts ) ) {
		return false;
	}

	// if sorting by date, display posts in reverse chronological order.
	// Otherwise display in alpha/numeric order.
	if ( ( $previous && 'post_date' != $r['order_by'] ) || ( ! $previous && 'post_date' == $r['order_by'] ) ) {
		$posts = array_reverse( $posts, true );
	}

	// Option to return something other than the formatted link
	if ( $r['return'] ) {
		if ( $r['num_results'] == 1 ) {
			reset( $posts );
			$post = current( $posts );

			switch ( $r['return'] ) {

				case 'id':
					return $post->ID;
				case 'href':
					return get_permalink( $post );
				case 'object':
					return $post;
				case 'title':
					return $post->post_title;
				case 'date':
					return mysql2date( $r['date_format'], $post->post_date );

			}

		} elseif ( 'object' == $r['return'] ) {
			return $posts;
		}
	}

	$output = $r['before'];

	// When num_results > 1, multiple adjacent posts may be returned.
	// Use foreach to display each adjacent post.
	foreach ( $posts as $post ) {
		$title = $post->post_title;
		if ( empty( $post->post_title ) ) {
			$title = $previous ? esc_attr__( 'Previous Post', 'Avada' ) : esc_attr__( 'Next Post', 'Avada' );
		}

		$title  = apply_filters( 'the_title', $title, $post->ID );
		$date   = mysql2date( $r['date_format'], $post->post_date );
		$author = get_the_author_meta( 'display_name', $post->post_author );

		// Set anchor title attribute to long post title or custom tooltip text.
		// Supports variable replacement in custom tooltip.
		$tooltip = '';
		if ( $r['tooltip'] ) {
			$tooltip = str_replace( '%title', $title, $r['tooltip'] );
			$tooltip = str_replace( '%date', $date, $tooltip );
			$tooltip = str_replace( '%author', $author, $tooltip );
			$tooltip = ' title="' . esc_attr( $tooltip ) . '"';
		}

		// Truncate the link title to nearest whole word under the length specified.
		$max_length = ( 1 > intval( $r['max_length'] ) ) ? 9999 : intval( $r['max_length'] );
		if ( $max_length < strlen( $title ) ) {
			$title = substr( $title, 0, strrpos( substr( $title, 0, $max_length ), ' ' ) );
			// mod for LTR larguages.
			$title = sprintf( esc_attr__( '$s...', 'Avada' ), $title );
		}

		$rel = ( $previous ) ? 'prev' : 'next';

		$anchor = '<a href="'.get_permalink( $post ) . '" rel="' . $rel . '"' . $tooltip . '>';
		$link   = str_replace( '%title', $title, $r['link'] );
		$link   = str_replace( '%date', $date, $link );
		$link   = $anchor . $link . '</a>';

		$format = str_replace( '%link', $link, $r['format'] );
		$format = str_replace( '%title', $title, $format );
		$format = str_replace( '%date', $date, $format );
		$format = str_replace( '%author', $author, $format );
		if ( ( 'custom' == $r['order_by'] || 'numeric' == $r['order_by'] ) && ! empty( $r['meta_key'] ) ) {
			$meta   = get_post_meta( $post->ID, $r['meta_key'], true );
			$format = str_replace( '%meta', $meta, $format );
		} elseif ( $r['in_same_meta'] ) {
			$meta   = get_post_meta( $post->ID, $r['in_same_meta'], true );
			$format = str_replace( '%meta', $meta, $format );
		}

		// Get the category list, including custom taxonomies
		// (only if the %category variable has been used).
		if ( ( false !== strpos( $format, '%category' ) ) ) {
			$term_list  = '';
			$taxonomies = array_filter( get_post_taxonomies( $post->ID ), 'is_taxonomy_hierarchical' );
			if ( $r['in_same_format'] && get_post_format( $post->ID ) ) {
				$taxonomies[] = 'post_format';
			}
			foreach ( $taxonomies as &$taxonomy ) {
				// No, this is not a mistake. Yes, we are testing the result of the assignment ( = ).
				// We are doing it this way to stop it from appending a comma when there is no next term.
				if ( $next_term = get_the_term_list( $post->ID, $taxonomy, '', ', ', '' ) ) {
					$term_list .= $next_term;
					if ( current( $taxonomies ) ) {
						$term_list .= ', ';
					}
				}
			}
			$format = str_replace( '%category', $term_list, $format );
		}

		// Optionally add the post thumbnail to the link.
		// Wrap the link in a span to aid CSS styling.
		if ( $r['thumb'] && has_post_thumbnail( $post->ID ) ) {
			if ( true === $r['thumb'] ) {
				// use 'post-thumbnail' as the default size
				$r['thumb'] = 'post-thumbnail';
			}
			$thumbnail = '<a class="post-thumbnail" href="' . get_permalink( $post ) . '" rel="' . $rel . '"' . $tooltip . '>' . get_the_post_thumbnail( $post->ID, $r['thumb'] ) . '</a>';
			$format    = $thumbnail . '<span class="post-link">' . $format . '</span>';
		}

		// If more than one link is returned, wrap them in <li> tags
		if ( 1 < intval( $r['num_results'] ) ) {
			$format = '<li>' . $format . '</li>';
		}

		$output .= $format;
	}

	$output .= $r['after'];

	//	If echo is false, don't display anything. Return the link as a PHP string.
	if ( ! $r['echo'] || 'output' === $r['return'] ) {
		return $output;
	}

	$adjacent = $previous ? 'previous' : 'next';
	echo apply_filters( "{$adjacent}_post_link_plus", $output, $r );

	return true;
}

// Omit closing PHP tag to avoid "Headers already sent" issues.
