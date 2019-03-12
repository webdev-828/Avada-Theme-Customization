<?php

class Avada_Patcher {

	/**
	 * The one, true instance.
	 */
	public static $instance = null;

	/**
	 * The class constructor.
	 * This is a singleton class so please use the ::get_instance() method instead.
	 */
	private function __construct() {

		if ( is_admin() ) {
			new Avada_Patcher_Apply_Patch();
			new Avada_Patcher_Admin_Screen();
		}

	}

	/**
	 * Get the one true instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new Avada_Patcher();
		}
		return self::$instance;
	}

}
