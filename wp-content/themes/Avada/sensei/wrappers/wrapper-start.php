<?php
/**
 * Content wrappers
 *
 * @author 		WooThemes
 * @package 	Sensei/Templates
 * @version	 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$template = get_option('template');

ob_start();
Avada()->layout->add_class( 'content_class' );
$content_class = ob_get_clean();

ob_start();
Avada()->layout->add_style( 'content_style' );
$content_css = ob_get_clean();

?>
<div class="sensei-container">
	<div id="content"<?php echo $content_class; ?> <?php echo $content_css; ?>>
