<?php

class LS_Posts {

	// Stores the last query results
	public $post = null;
	public $posts = null;

	/**
	 * Returns posts that matches the query params
	 * @param  array  	$args Array of WP_Query attributes
	 * @return bool           Success of the query
	 */
	public static function find($args = array()) {

		// Crate new instance
		$instance = new self;

		if($instance->posts = get_posts($args)) {
			$instance->post = $instance->posts[0];
		}
		return $instance;
	}

	public static function getPostTypes() {

		// Get post types
		$postTypes = get_post_types();

		// Remove some defalt post types
		if(isset($postTypes['revision'])) { unset($postTypes['revision']); }
		if(isset($postTypes['nav_menu_item'])) { unset($postTypes['nav_menu_item']); }

		// Convert names to plural
		foreach($postTypes as $key => $item) {
			if(!empty($item)) {
				$postTypes[$key] = array();
				$postTypes[$key]['slug'] = $item;
				$postTypes[$key]['obj'] = get_post_type_object($item);
				$postTypes[$key]['name'] = $postTypes[$key]['obj']->labels->name;
			}
		}

		return $postTypes;
	}


	public function getParsedObject() {

		if(!$this->posts) {
			return array();
		}

		foreach($this->posts as $key => $val) {
			$ret[$key]['post-id'] = $val->ID;
			$ret[$key]['post-slug'] = $val->post_name;
			$ret[$key]['post-url'] = get_permalink($val->ID);
			$ret[$key]['date-published'] = date(get_option('date_format'), strtotime($val->post_date));
			$ret[$key]['date-modified'] = date(get_option('date_format'), strtotime($val->post_modified));
			$ret[$key]['thumbnail'] = $this->getPostThumb($val->ID);
			$ret[$key]['thumbnail'] = !empty($ret[$key]['thumbnail']) ? $ret[$key]['thumbnail'] : LS_ROOT_URL . '/static/img/blank.gif';
			$ret[$key]['image'] = '<img src="'.$ret[$key]['thumbnail'].'" alt="">';
			$ret[$key]['image-url'] = $ret[$key]['thumbnail'];
			$ret[$key]['title'] = htmlspecialchars(__($val->post_title), ENT_COMPAT | ENT_HTML401 | ENT_SUBSTITUTE);
			$ret[$key]['content'] = wp_strip_all_tags(__($val->post_content));
			$ret[$key]['excerpt'] = $this->getExcerpt();
			$ret[$key]['author'] = get_userdata($val->post_author)->user_nicename;
			$ret[$key]['author-id'] = $val->post_author;
			$ret[$key]['categories'] = $this->getCategoryList($val);
			$ret[$key]['tags'] = $this->getTagList($val);
			$ret[$key]['comments'] = $val->comment_count;
		}

		return $ret;
	}


	public function getWithFormat($str, $textlength = 0) {

		if(!is_object($this->post)) {
			return $str;
		}

		// Post ID
		if(stripos($str, '[post-id]') !== false) {
			$str = str_replace('[post-id]', $this->post->ID, $str); }

		// Post slug
		if(stripos($str, '[post-slug]') !== false) {
			$str = str_replace('[post-slug]', $this->post->post_name, $str); }

		// Post URL
		if(stripos($str, '[post-url]') !== false) {
			$str = str_replace('[post-url]', get_permalink($this->post->ID), $str);
		}

		// Date published
		if(stripos($str, '[date-published]') !== false) {
			$str = str_replace('[date-published]', date(get_option('date_format'), strtotime($this->post->post_date)), $str); }

		// Date modified
		if(stripos($str, '[date-modified]') !== false) {
			$str = str_replace('%date-modified]', date(get_option('date_format'), strtotime($this->post->post_modified)), $str); }

		// Featured image
		if(stripos($str, '[image]') !== false) {
			if(has_post_thumbnail($this->post->ID)) {
				$src = $this->getPostThumb($this->post->ID);
				if(!empty($src)){
					$str = str_replace('[image]', '<img src="'.$src.'" />', $str);
				}
			}
		}

		// Featured image URL
		if(stripos($str, '[image-url]') !== false) {
			if(has_post_thumbnail($this->post->ID)) {
				$src = $this->getPostThumb($this->post->ID);
				if(!empty($src)){
					$str = str_replace('[image-url]', $src, $str);
				}
			}
		}

		// Title
		if(stripos($str, '[title]') !== false) {
			if(!empty($textlength)) {
				$str = str_replace('[title]', substr($this->getTitle(), 0, $textlength), $str);
			} else {
				$str = str_replace('[title]', $this->getTitle(), $str);
			}
		}

		// Content
		if(stripos($str, '[content]') !== false) {
			$str = str_replace('[content]', $this->getContent($textlength), $str); }

		// Excerpt
		if(stripos($str, '[excerpt]') !== false) {
			$str = str_replace('[excerpt]', $this->getExcerpt($textlength), $str);
		}

		// Author
		if(stripos($str, '[author]') !== false) {
			$str = str_replace('[author]', $this->getAuthor(), $str); }

		// Author ID
		if(stripos($str, '[author-id]') !== false) {
			$str = str_replace('[author-id]', $this->post->post_author, $str); }

		// Category list
		if(stripos($str, '[categories]') !== false) {
			$str = str_replace('[categories]', $this->getCategoryList(), $str);
		}

		// Tags list
		if(stripos($str, '[tags]') !== false) {
			$str = str_replace('[tags]', $this->getTagList(), $str);
		}

		// Number of comments
		if(stripos($str, '[comments]') !== false) {
			$str = str_replace('[comments]', $this->post->comment_count, $str); }

		// Meta
		if(stripos($str, '[meta:') !== false) {
			$matches = array();
			preg_match_all('/\[meta:\w(?:[-\w]*\w)?]/', $str, $matches);

			foreach($matches[0] as $match) {
				$meta = str_replace('[meta:', '', $match);
				$meta = str_replace(']', '', $meta);
				$meta = get_post_meta($this->post->ID, $meta, true);
				$str = str_replace($match, $meta, $str);
			}
		}

		return $str;
	}


	/**
	 * Returns the lastly selected post's title
	 * @return string The title of the post
	 */
	public function getTitle() {
		if(is_object($this->post)) { return __($this->post->post_title); }
			else { return false; }
	}


	/**
	 * Returns the lastly selected post's excerpt
	 * @return string The excerpt of the post
	 */
	public function getExcerpt($textlength = 0) {

		global $post;
		$post = $this->post;

		setup_postdata($post);
		$excerpt = get_the_excerpt();
		wp_reset_postdata();

		if(!empty($excerpt) && !empty($textlength)) {
			$excerpt = substr($excerpt, 0, $textlength);
		}

		return $excerpt;
	}


	public function getAuthor() {
		if(is_object($this->post)) { return get_userdata($this->post->post_author)->user_nicename; }
			else { return false; }
	}

	public function getCategoryList($post = null) {

		if(!empty($post)) { $post = $this->post; }

		if(has_category(false, $this->post->ID)) {
			$cats = wp_get_post_categories($this->post->ID);
			foreach($cats as $val) {
				$cat = get_category($val);
				$list[] = '<a href="/category/'.$cat->slug.'/">'.$cat->name.'</a>';
			}
			return '<div>'.implode(', ', $list).'</div>';
		} else {
			return '';
		}
	}


	public function getTagList($post = null) {

		if(!empty($post)) { $post = $this->post; }

		if(has_tag(false, $this->post->ID)) {
			$tags = wp_get_post_tags($this->post->ID);
			foreach($tags as $val) {
				$list[] = '<a href="/tag/'.$val->slug.'/">'.$val->name.'</a>';
			}
			return '<div>'.implode(', ', $list).'</div>';
		} else {
			return '';
		}
	}

	/**
	 * Returns a subset of the post's content,
	 * or the first paragraph if isn't specified
	 * @param  integer $length The subset's length
	 * @return string          The content
	 */
	public function getContent($length = false) {

		if(!is_object($this->post)) { return false; }

		if(empty($length)) {
			return wp_strip_all_tags(__($this->post->post_content));
		} else {
			return substr(wp_strip_all_tags(__($this->post->post_content)), 0, $length);
		}
	}

	/**
	 * Returns the attachment ID of
	 * featured image in a post
	 * @param  integer $postID  The ID of the post
	 * @return string			The ID of the post, or an empty string on failure.
	 */
	public function getPostThumb($postID = 0) {
		if(function_exists('get_post_thumbnail_id') && function_exists('wp_get_attachment_url')) {
			return wp_get_attachment_url(get_post_thumbnail_id($postID));
		}
	}
}
