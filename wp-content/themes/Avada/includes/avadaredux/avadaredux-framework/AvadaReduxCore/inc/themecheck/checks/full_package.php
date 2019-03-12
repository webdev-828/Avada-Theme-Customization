<?php

	class AvadaRedux_Full_Package implements themecheck {
		protected $error = array();

		function check( $php_files, $css_files, $other_files ) {

			$ret = true;

			$check = AvadaRedux_ThemeCheck::get_instance();
			$avadaredux = $check::get_avadaredux_details( $php_files );

			if ( $avadaredux ) {

				$blacklist = array(
					'.tx'                    => __( 'AvadaRedux localization utilities', 'themecheck' ),
					'bin'                    => __( 'AvadaRedux Resting Diles', 'themecheck' ),
					'codestyles'             => __( 'AvadaRedux Code Styles', 'themecheck' ),
					'tests'                  => __( 'AvadaRedux Unit Testing', 'themecheck' ),
					'class.avadaredux-plugin.php' => __( 'AvadaRedux Plugin File', 'themecheck' ),
					'bootstrap_tests.php'    => __( 'AvadaRedux Boostrap Tests', 'themecheck' ),
					'.travis.yml'            => __( 'CI Testing FIle', 'themecheck' ),
					'phpunit.xml'            => __( 'PHP Unit Testing', 'themecheck' ),
				);

				$errors = array();

				foreach ( $blacklist as $file => $reason ) {
					checkcount();
					if ( file_exists( $avadaredux['parent_dir'] . $file ) ) {
						$errors[ $avadaredux['parent_dir'] . $file ] = $reason;
					}
				}

				if ( ! empty( $errors ) ) {
					$error = '<span class="tc-lead tc-required">REQUIRED</span> ' . __( 'It appears that you have embedded the full AvadaRedux package inside your theme. You need only embed the <strong>AvadaReduxCore</strong> folder. Embedding anything else will get your rejected from theme submission. Suspected AvadaRedux package file(s):', 'avadaredux-framework' );
					$error .= '<ol>';
					foreach ( $errors as $key => $e ) {
						$error .= '<li><strong>' . $e . '</strong>: ' . $key . '</li>';
					}
					$error .= '</ol>';
					$this->error[] = '<div class="avadaredux-error">' . $error . '</div>';
					$ret           = false;
				}
			}

			return $ret;
		}

		function getError() {
			return $this->error;
		}
	}

	$themechecks[] = new AvadaRedux_Full_Package();
