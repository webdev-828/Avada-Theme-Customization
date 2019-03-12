<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	if (!class_exists('avadareduxDashboardWidget')) {
		class avadareduxDashboardWidget {

			public function __construct ($parent) {
				$fname = AvadaRedux_Functions::dat( 'add_avadaredux_dashboard', $parent->args['opt_name'] );

				add_action('wp_dashboard_setup', array($this, $fname));
			}

			public function add_avadaredux_dashboard() {
				add_meta_box('avadaredux_dashboard_widget', 'AvadaRedux Framework News', array($this,'avadaredux_dashboard_widget'), 'dashboard', 'side', 'high');
			}

			public function dat() {
				return;
			}

			public function avadaredux_dashboard_widget() {
				echo '<div class="rss-widget">';
				wp_widget_rss_output(array(
					 'url'          => 'http://avadareduxframework.com/feed/',
					 'title'        => 'REDUX_NEWS',
					 'items'        => 3,
					 'show_summary' => 1,
					 'show_author'  => 0,
					 'show_date'    => 1
				));
				echo '</div>';
			}
		}
	}
