<?php if ( 'Top' != Avada()->settings->get( 'header_position' ) || ( ! in_array( Avada()->settings->get( 'header_layout' ), array( 'v4', 'v5' ) ) ) ) : ?>
	<?php get_template_part( 'templates/menu-mobile-modern' ); ?>
<?php endif; ?>

<?php $mobile_menu_text_align = ( 'right' == Avada()->settings->get( 'mobile_menu_text_align' ) ) ? ' fusion-mobile-menu-text-align-right' : ''; ?>

<div class="fusion-mobile-nav-holder<?php echo $mobile_menu_text_align; ?>"></div>

<?php if ( has_nav_menu( 'sticky_navigation' ) ) : ?>
	<div class="fusion-mobile-nav-holder<?php echo $mobile_menu_text_align; ?> fusion-mobile-sticky-nav-holder"></div>
<?php endif;

// Omit closing PHP tag to avoid "Headers already sent" issues.
