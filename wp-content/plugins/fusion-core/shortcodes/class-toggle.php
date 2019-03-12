<?php
class FusionSC_Toggle {

	private $accordian_counter = 1;
	private $collapse_counter = 1;
	private $collapse_id;

	public static $parent_args;
	public static $child_args;

	/**
	 * Initiate the shortcode
	 */
	public function __construct() {

		add_filter( 'fusion_attr_toggle-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_toggle-shortcode-panelgroup', array( $this, 'panelgroup_attr' ) );
		add_filter( 'fusion_attr_toggle-shortcode-panel', array( $this, 'panel_attr' ) );
		add_filter( 'fusion_attr_toggle-shortcode-fa-icon', array( $this, 'fa_icon_attr' ) );
		add_filter( 'fusion_attr_toggle-shortcode-data-toggle', array( $this, 'data_toggle_attr' ) );
		add_filter( 'fusion_attr_toggle-shortcode-collapse', array( $this, 'collapse_attr' ) );

		add_shortcode( 'accordian', array( $this, 'render_parent' ) );
		add_shortcode( 'toggle', array( $this, 'render_child' ) );

	}

	/**
	 * Render the parent shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render_parent( $args, $content = '') {
		global $smof_data;

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'divider_line'	=> $smof_data['accordion_divider_line'],
				'class' 		=> '',
				'id' 			=> '',
			), $args
		);

		extract( $defaults );

		self::$parent_args = $defaults;

		$html = sprintf( '<div %s><div %s>%s</div></div>', FusionCore_Plugin::attributes( 'toggle-shortcode' ), FusionCore_Plugin::attributes( 'toggle-shortcode-panelgroup' ),  do_shortcode( $content ) );

		$this->accordian_counter++;

		return $html;

	}

	function attr() {

		$attr = array();

		$attr['class'] = 'accordian fusion-accordian';

		if( self::$parent_args['class'] ) {
			$attr['class'] .= ' ' . self::$parent_args['class'];
		}

		if( self::$parent_args['id'] ) {
			$attr['id'] = self::$parent_args['id'];
		}

		return $attr;

	}

	function panelgroup_attr() {

		$attr = array();

		$attr['class'] = 'panel-group';
		$attr['id'] = sprintf( 'accordion-%s-%s', get_the_ID(), $this->accordian_counter );

		return $attr;

	}

	/**
	 * Render the child shortcode
	 * @param  array $args	 Shortcode paramters
	 * @param  string $content Content between shortcode
	 * @return string		  HTML output
	 */
	function render_child( $args, $content = '') {

		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'open' 		=> 'no',
				'title'		=> '&nbsp;',
			), $args
		);

		extract( $defaults );

		self::$child_args = $defaults;
		self::$child_args['toggle_class'] = '';

		if( $open == 'yes' ) {
			self::$child_args['toggle_class'] = 'in';
		}

		$this->collapse_id = substr( md5( sprintf( 'collapse-%s-%s-%s', get_the_ID(), $this->accordian_counter, $this->collapse_counter ) ), 15 );

		$html = sprintf( '<div %s><div %s><h4 %s><a %s><div %s><i %s></i></div><div %s>%s</div></a></h4></div><div %s><div %s>%s</div></div></div>', FusionCore_Plugin::attributes( 'toggle-shortcode-panel' ),
						 FusionCore_Plugin::attributes( 'panel-heading' ), FusionCore_Plugin::attributes( 'panel-title toggle' ), FusionCore_Plugin::attributes( 'toggle-shortcode-data-toggle' ),
						 FusionCore_Plugin::attributes( 'fusion-toggle-icon-wrapper' ), FusionCore_Plugin::attributes( 'toggle-shortcode-fa-icon' ), FusionCore_Plugin::attributes( 'fusion-toggle-heading' ), $title, 
						 FusionCore_Plugin::attributes( 'toggle-shortcode-collapse' ), FusionCore_Plugin::attributes( 'panel-body toggle-content' ), do_shortcode( $content ) );

		$this->collapse_counter++;

		return $html;

	}
	
	function panel_attr() {

		$attr = array();

		$attr['class'] = 'fusion-panel panel-default';
		
		if ( self::$parent_args['divider_line'] == '0' ||
			 self::$parent_args['divider_line'] == 'no'
		) {
			$attr['class'] .= ' fusion-toggle-no-divider';
		}			

		return $attr;

	}	

	function fa_icon_attr() {

		$attr = array();

		$attr['class'] = 'fa-fusion-box';

		return $attr;

	}

	function data_toggle_attr() {

		$attr = array();

		if( self::$child_args['open'] == 'yes' ) {
			$attr['class'] = 'active';
		}

		$attr['data-toggle'] = 'collapse';
		$attr['data-parent'] = sprintf( '#accordion-%s-%s', get_the_ID(), $this->accordian_counter );
		$attr['data-target'] = '#' . $this->collapse_id;
		$attr['href'] = '#' . $this->collapse_id;		

		return $attr;

	}

	function collapse_attr() {

		$attr = array();

		$attr['id'] = $this->collapse_id;
		$attr['class'] = 'panel-collapse collapse ' . self::$child_args['toggle_class'];

		return $attr;

	}

}

new FusionSC_Toggle();