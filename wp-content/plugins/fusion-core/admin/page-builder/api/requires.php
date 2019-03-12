<?php
/*---------------------------------------------------------------------------------------------------------------------------------------------------------
| Set Directory PATHs
-----------------------------------------------------------------------------------------------------------------------------------------------------------*/

define ('FUSION_BUILDER_PHP_CORE' , dirname( __FILE__ ).'/../');

/*---------------------------------------------------------------------------------------------------------------------------------------------------------
| Include all categories, elements, libs and engine.
-----------------------------------------------------------------------------------------------------------------------------------------------------------*/
include FUSION_BUILDER_PHP_CORE.'includes/engine/class-dd-element-template.php'; // no loop for you.
// font awesome iterator class.
include FUSION_BUILDER_PHP_CORE.'classes/class-fa-iterator.php';

$directories = array ( 
				FUSION_BUILDER_PHP_CORE.'includes/categories/', //categories
				FUSION_BUILDER_PHP_CORE.'includes/elements/builder-elements/', // builder elements
				FUSION_BUILDER_PHP_CORE.'includes/elements/column-options/' // column / grid options
			);
foreach ( $directories as $directory ) {
	
	foreach( glob( $directory . "*.php" ) as $class ) {
		
		include_once $class;
	}
}