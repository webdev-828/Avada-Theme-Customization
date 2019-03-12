<?php

	if ( ! class_exists( 'AvadaRedux_Customizer_Control' ) ) {
		class AvadaRedux_Customizer_Control extends WP_Customize_Control {

			public function render() {
				$this->avadaredux_id = str_replace( 'customize-control-', '', 'customize-control-' . str_replace( '[', '-', str_replace( ']', '', $this->id ) ) );
				$class          = 'customize-control avadaredux-group-tab avadaredux-field customize-control-' . $this->type;
				?>
				<li id="<?php echo esc_attr( $this->avadaredux_id ); ?>" class="<?php echo esc_attr( $class ); ?>">
					<input type="hidden"
						   data-id="<?php echo esc_attr($this->id); ?>"
						   class="avadaredux-customizer-input"
						   id="customizer_control_id_<?php echo esc_attr($this->avadaredux_id); ?>" <?php echo esc_url($this->link()) ?>
						   value=""/>
					<?php $this->render_content(); ?>
				</li>
				<?php

			}

			public function render_content() {
				do_action( 'avadaredux/advanced_customizer/control/render/' . $this->avadaredux_id, $this );
			}

			public function label() {
				// The label has already been sanitized in the Fields class, no need to re-sanitize it.
				echo $this->label;
			}

			public function description() {
				if ( ! empty( $this->description ) ) {
					// The description has already been sanitized in the Fields class, no need to re-sanitize it.
					echo '<span class="description customize-control-description">' . $this->description . '</span>';
				}
			}

			public function title() {
				echo '<span class="customize-control-title">';
				$this->label();
				$this->description();
				echo '</span>';
			}
		}
	}
