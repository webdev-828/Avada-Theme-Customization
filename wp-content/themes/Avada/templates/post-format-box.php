<?php
switch ( get_post_format() ) {
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
?>
<div class="fusion-format-box">
	<i class="fusion-icon-<?php echo $format_class; ?>"></i>
</div>
