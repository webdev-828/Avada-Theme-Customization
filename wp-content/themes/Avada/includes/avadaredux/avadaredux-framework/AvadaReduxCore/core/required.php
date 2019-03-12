<?php

	if ( !defined ( 'ABSPATH' ) ) {
		exit;
	}

	if (!class_exists('avadareduxCoreRequired')){
		class avadareduxCoreRequired {
			public $parent      = null;

			public function __construct ($parent) {
				$this->parent = $parent;
				AvadaRedux_Functions::$_parent = $parent;


				/**
				 * action 'avadaredux/page/{opt_name}/'
				 */
				do_action( "avadaredux/page/{$parent->args['opt_name']}/" );

			}


		}
	}
