<?php

	/**
	 * AvadaReduxFrameworkInstances Functions
	 *
	 * @package     AvadaRedux_Framework
	 * @subpackage  Core
	 */
	if ( ! function_exists( 'get_avadaredux_instance' ) ) {

		/**
		 * Retreive an instance of AvadaReduxFramework
		 *
		 * @param  string $opt_name the defined opt_name as passed in $args
		 *
		 * @return object                AvadaReduxFramework
		 */
		function get_avadaredux_instance( $opt_name ) {
			return AvadaReduxFrameworkInstances::get_instance( $opt_name );
		}
	}

	if ( ! function_exists( 'get_all_avadaredux_instances' ) ) {

		/**
		 * Retreive all instances of AvadaReduxFramework
		 * as an associative array.
		 *
		 * @return array        format ['opt_name' => $AvadaReduxFramework]
		 */
		function get_all_avadaredux_instances() {
			return AvadaReduxFrameworkInstances::get_all_instances();
		}
	}
