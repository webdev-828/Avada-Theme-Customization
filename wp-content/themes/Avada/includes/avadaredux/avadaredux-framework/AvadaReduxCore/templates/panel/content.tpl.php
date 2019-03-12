<?php
	/**
	 * The template for the main content of the panel.
	 * Override this template by specifying the path where it is stored (templates_path) in your AvadaRedux config.
	 *
	 * @author      AvadaRedux Framework
	 * @package     AvadaReduxFramework/Templates
	 * @version:    3.5.4.18
	 */
?>
<!-- Header Block -->
<?php $this->get_template( 'header.tpl.php' ); ?>

<!-- Intro Text -->
<?php if ( isset( $this->parent->args['intro_text'] ) ) { ?>
	<div id="avadaredux-intro-text"><?php echo wp_kses_post( $this->parent->args['intro_text'] ); ?></div>
<?php } ?>

<?php $this->get_template( 'menu_container.tpl.php' ); ?>

<div class="avadaredux-main">
	<!-- Stickybar -->
	<?php $this->get_template( 'header_stickybar.tpl.php' ); ?>
	<div id="avadaredux_ajax_overlay">&nbsp;</div>
	<?php
		foreach ($this->parent->sections as $k => $section) {
		if ( isset( $section['customizer_only'] ) && $section['customizer_only'] == true ) {
			continue;
		}

		//$active = ( ( is_numeric($this->parent->current_tab) && $this->parent->current_tab == $k ) || ( !is_numeric($this->parent->current_tab) && $this->parent->current_tab === $k )  ) ? ' style="display: block;"' : '';
		$section['class'] = isset( $section['class'] ) ? ' ' . $section['class'] : '';
		echo '<div id="' . $k . '_section_group' . '" class="avadaredux-group-tab' . esc_attr( $section['class'] ) . '" data-rel="' . $k . '">';
		//echo '<div id="' . $k . '_nav-bar' . '"';
		/*
	if ( !empty( $section['tab'] ) ) {

		echo '<div id="' . $k . '_section_tabs' . '" class="avadaredux-section-tabs">';

		echo '<ul>';

		foreach ($section['tab'] as $subkey => $subsection) {
			//echo '-=' . $subkey . '=-';
			echo '<li style="display:inline;"><a href="#' . $k . '_section-tab-' . $subkey . '">' . $subsection['title'] . '</a></li>';
		}

		echo '</ul>';
		foreach ($section['tab'] as $subkey => $subsection) {
			echo '<div id="' . $k .'sub-'.$subkey. '_section_group' . '" class="avadaredux-group-tab" style="display:block;">';
			echo '<div id="' . $k . '_section-tab-' . $subkey . '">';
			echo "hello ".$subkey;
			do_settings_sections( $this->parent->args['opt_name'] . $k . '_tab_' . $subkey . '_section_group' );
			echo "</div>";
			echo "</div>";
		}
		echo "</div>";
	} else {
		*/

		// Don't display in the
		$display = true;
		if ( isset( $_GET['page'] ) && $_GET['page'] == $this->parent->args['page_slug'] ) {
			if ( isset( $section['panel'] ) && $section['panel'] == "false" ) {
				$display = false;
			}
		}

		if ( $display ) {
			do_action( "avadaredux/page/{$this->parent->args['opt_name']}/section/before", $section );
			$this->output_section( $k );
			do_action( "avadaredux/page/{$this->parent->args['opt_name']}/section/after", $section );
		}
		//}
	?></div><?php
	//print '</div>';
	}

	/**
	 * action 'avadaredux/page-after-sections-{opt_name}'
	 *
	 * @deprecated
	 *
	 * @param object $this AvadaReduxFramework
	 */
	do_action( "avadaredux/page-after-sections-{$this->parent->args['opt_name']}", $this ); // REMOVE LATER

	/**
	 * action 'avadaredux/page/{opt_name}/sections/after'
	 *
	 * @param object $this AvadaReduxFramework
	 */
	do_action( "avadaredux/page/{$this->parent->args['opt_name']}/sections/after", $this );
?>
<div class="clear"></div>
<!-- Footer Block -->
<?php $this->get_template( 'footer.tpl.php' ); ?>
<div id="avadaredux-sticky-padder" style="display: none;">&nbsp;</div>
</div>
<div class="clear"></div>
