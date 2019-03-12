<?php
/**
 * Include necessary classes & files
 */
include_once dirname( __FILE__ ) . '/includes/class-avada-patcher.php';
include_once dirname( __FILE__ ) . '/includes/class-avada-patcher-client.php';
include_once dirname( __FILE__ ) . '/includes/class-avada-patcher-filesystem.php';
include_once dirname( __FILE__ ) . '/includes/class-avada-patcher-apply-patch.php';
include_once dirname( __FILE__ ) . '/includes/class-avada-patcher-admin-screen.php';
include_once dirname( __FILE__ ) . '/includes/class-avada-patcher-admin-notices.php';

/**
 * Instantiate the plugin class
 */
Avada_Patcher::get_instance();
