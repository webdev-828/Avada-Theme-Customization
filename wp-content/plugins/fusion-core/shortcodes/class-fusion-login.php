<?php
/**
 * Renders the login related shortcodes.
 *
 * @since 1.8.0
 * @package FusionCore
 */
class FusionSC_Login {

	/**
	 * Element counter, used for CSS
	 *
	 * @since 1.8.0
	 * @var int $args
	 */	
	private $login_counter = 0;

	/**
	 * Parameters from the shortcode.
	 *
	 * @since 1.8.0
	 * @var array $args
	 */
	public static $args;

	/**
	 * Constructor of the class
	 *
	 * @since 1.8.0	 
	 */
	public function __construct() {

		//add_action( 'login_init', array( $this, 'login_init' ) );
		add_action( 'lostpassword_post', array( $this, 'lost_password_redirect' ) );	
		add_filter( 'login_redirect', array( $this, 'login_redirect' ), 10, 3 );
		add_filter( 'registration_errors', array( $this, 'registration_error_redirect' ), 10, 3 );	

		add_filter( 'fusion_attr_login-shortcode', array( $this, 'attr' ) );
		add_filter( 'fusion_attr_login-shortcode-form', array( $this, 'form_attr' ) );
		add_filter( 'fusion_attr_login-shortcode-button', array( $this, 'button_attr' ) );

		add_shortcode( 'fusion_login', array( $this, 'render_login' ) );
		add_shortcode( 'fusion_register', array( $this, 'render_register' ) );
		add_shortcode( 'fusion_lost_password', array( $this, 'render_lost_password' ) );

	}
	
	/**
	 * Add default values to shortcode parameters.
	 *
	 * @since 1.8.0	 
	 *
	 * @param  array 	$args	 	Shortcode paramters
	 * @return array		  		Shortcode paramters with default values where necesarry
	 */	
	public function default_shortcode_parameter( $args ) {
		$defaults = FusionCore_Plugin::set_shortcode_defaults(
			array(
				'class'			   		=> '',
				'id'				 	=> '',
				'button_fullwidth'		=> Avada()->settings->get( 'button_span' ),
				'caption'				=> '',
				'caption_color'			=> '',
				'form_background_color'	=> Avada()->settings->get( 'user_login_form_background_color' ),
				'heading'				=> '',
				'heading_color'			=> '',
				'link_color'			=> '',
				'lost_password_link'	=> '',
				'redirection_link'		=> '',
				'register_link'			=> '',
				'text_align'			=> Avada()->settings->get( 'user_login_text_align' ),
				
				'disable_form'			=> '', // Only for demo usage
			), $args
		);
		
		$defaults['main_container'] = ( $defaults['disable_form'] ) ? 'div' : 'form';
		
		return $defaults;
	}

	/**
	 * Render the login shortcode.
	 *
	 * @since 1.8.0	 
	 *
	 * @param  array 	$args	 	Shortcode paramters
	 * @param  string 	$content 	Content between shortcode
	 * @return string		  		HTML output
	 */
	function render_login( $args, $content = '' ) {

		$defaults = $this->default_shortcode_parameter( $args );
		
		$defaults['action'] = 'login';

		extract( $defaults );

		self::$args = $defaults;
		
		$styles = $this->get_style_tag();
		
		$html = sprintf( '<div %s>%s', FusionCore_Plugin::attributes( 'login-shortcode' ), $styles );
		
			if ( ! is_user_logged_in() ) {
				$user_login = ( isset( $_GET['log'] ) ) ?  $_GET['log'] : '';

				$html .= sprintf( '<h3 class="fusion-login-heading">%s</h3>', $heading );
				$html .= sprintf( '<div class="fusion-login-caption">%s</div>', $caption );

				$html .= sprintf( '<%s %s>', $main_container, FusionCore_Plugin::attributes( 'login-shortcode-form' ) );

					// Get the success/error notices
					$html .= $this->render_notices( $action );

					$html .= '<div class="fusion-login-input-wrapper">';
						$html .= sprintf( '<label class="fusion-hidden-content" for="user_login">%s</label>', __( 'Username', 'fusion-core' ) );
						$html .= sprintf( '<input type="text" name="log" placeholder="%s" value="%s" size="20" class="fusion-login-username input-text" id="user_login" />', __( 'Username', 'fusion-core' ), esc_attr( $user_login ) );
					$html .= '</div>';

					$html .= '<div class="fusion-login-input-wrapper">';
						$html .= sprintf( '<label class="fusion-hidden-content" for="user_pass">%s</label>', __( 'Password', 'fusion-core' ) );
						$html .= sprintf( '<input type="password" name="pwd" placeholder="%s" value="" size="20" class="fusion-login-password input-text" id="user_pass" />', __( 'Password', 'fusion-core' ) );
					$html .= '</div>';

					$html .= '<div class="fusion-login-submit-wrapperr">';
						$html .= sprintf( '<button %s>%s</button>', FusionCore_Plugin::attributes( 'login-shortcode-button' ), __( 'Log in', 'fusion-core' ) );

						// Set the query string for successful password reset
						if ( ! $redirection_link ) {
							$redirection_link = $this->get_redirection_link();
						}						
						$html .= $this->render_hidden_login_inputs( $redirection_link ); 

					$html .= '</div>';

					$html .= '<div class="fusion-login-links">';
						$html .= sprintf( '<a class="fusion-login-lost-passowrd" target="_self" href="%s">%s</a>', $lost_password_link, __( 'Lost password?', 'fusion-core' ) );
						$html .= sprintf( '<a class="fusion-login-register" target="_self" href="%s">%s</a>', $register_link, __( 'Register', 'fusion-core' ) );
					$html .= '</div>';

				$html .= sprintf( '</%s>', $main_container );
			} else {
				$user = get_user_by( 'id', get_current_user_id() );

				$html .= sprintf( '<div class="fusion-login-caption">%s %s</div>', __( 'Welcome', 'fusion-core' ), ucwords( $user->display_name ) );
				$html .= sprintf( '<div class="fusion-login-avatar">%s</div>', get_avatar( $user->ID, apply_filters( 'fusion_login_box_avatar_size', 50 ) ) );
				$html .= '<ul class="fusion-login-loggedin-links">';
					$html .= sprintf( '<li><a href="%s">%s</a></li>', get_dashboard_url(), __( 'Dashboard', 'fusion-core' ) );
					$html .= sprintf( '<li><a href="%s">%s</a></li>', get_edit_user_link( $user->ID ), __( 'Profile', 'fusion-core' ) );
					$html .= sprintf( '<li><a href="%s">%s</a></li>', wp_logout_url( get_permalink() ), __( 'Logout', 'fusion-core' ) );
				$html .= '</ul>';

			}
		
		$html .= '</div>';
		
		return $html;
	}
	
	/**
	 * Render the register shortcode.
	 *
	 * @since 1.8.0	 
	 *
	 * @param  array 	$args	 	Shortcode paramters
	 * @param  string 	$content 	Content between shortcode
	 * @return string		  		HTML output
	 */
	function render_register( $args, $content = '' ) {

		$defaults = $this->default_shortcode_parameter( $args );
		
		$defaults['action'] = 'register';

		extract( $defaults );

		self::$args = $defaults;
		
		$styles = $this->get_style_tag();
		
		$html = '';
		
		if ( ! is_user_logged_in() ) {
			$html .= sprintf( '<div %s>%s', FusionCore_Plugin::attributes( 'login-shortcode' ), $styles );
				$html .= sprintf( '<h3 class="fusion-login-heading">%s</h3>', $heading );
				$html .= sprintf( '<div class="fusion-login-caption">%s</div>', $caption );

				$html .= sprintf( '<%s %s>', $main_container, FusionCore_Plugin::attributes( 'login-shortcode-form' ) );

					// Get the success/error notices
					$html .= $this->render_notices( $action );
				
					$html .= '<div class="fusion-login-input-wrapper">';
						$html .= sprintf( '<label class="fusion-hidden-content" for="user_login">%s</label>', __( 'Username', 'fusion-core' ) );
						$html .= sprintf( '<input type="text" name="user_login" placeholder="%s" value="" size="20" class="fusion-login-username input-text" id="user_login" />', __( 'Username', 'fusion-core' ) );
					$html .= '</div>';

					$html .= '<div class="fusion-login-input-wrapper">';
						$html .= sprintf( '<label class="fusion-hidden-content" for="user_pass">%s</label>', __( 'Email', 'fusion-core' ) );
						$html .= sprintf( '<input type="text" name="user_email" placeholder="%s" value="" size="20" class="fusion-login-email input-text" id="user_email" />', __( 'Email', 'fusion-core' ) );
					$html .= '</div>';				

				
					/* Only added as honeypot for spambots */
					$html .= '<div class="fusion-login-input-wrapper">';
						$html .= '<label class="fusion-hidden-content" for="confirm_email">Please leave this field empty</label>';
						$html .= '<input class="fusion-hidden-content" type="text" name="confirm_email" id="confirm_email" value="">';
					$html .= '</div>';
        		
        			$html .= sprintf( '<p class="fusion-login-input-wrapper">%s</p>', __( 'Registration confirmation will be e-mailed to you.', 'fusion-core' ) );

					$html .= '<div class="fusion-login-submit-wrapperr">';
						$html .= sprintf( '<button %s>%s</button>', FusionCore_Plugin::attributes( 'login-shortcode-button' ), __( 'Register', 'fusion-core' ) );

						// Set the query string for successful password reset
						if ( ! $redirection_link ) {
							$redirection_link = $this->get_redirection_link();
						}
						$html .= $this->render_hidden_login_inputs( $redirection_link,  array( 'action' => 'register', 'success' => '1' ) ); 

					$html .= '</div>';
					
				$html .= sprintf( '</%s>', $main_container );
			$html .= '</div>';
		} else {
			$html .= do_shortcode( sprintf( '[alert type="general" border_size="1px" box_shadow="yes"]%s[/alert]', __( 'You are already signed up.', 'fusion-core' ) ) );
		}
		
		return $html;
	}
	
	/**
	 * Render the lost password shortcode.
	 *
	 * @since 1.8.0	 
	 *
	 * @param  array 	$args	 	Shortcode paramters
	 * @param  string 	$content 	Content between shortcode
	 * @return string		  		HTML output
	 */
	function render_lost_password( $args, $content = '' ) {

		$defaults = $this->default_shortcode_parameter( $args );
		
		$defaults['action'] = 'lostpassword';

		extract( $defaults );

		self::$args = $defaults;
		
		$styles = $this->get_style_tag();
		
		$html = '';
		
		if ( ! is_user_logged_in() ) {

			$html .= sprintf( '<div %s>%s', FusionCore_Plugin::attributes( 'login-shortcode' ), $styles );
				$html .= sprintf( '<h3 class="fusion-login-heading">%s</h3>', $heading );
				$html .= sprintf( '<div class="fusion-login-caption">%s</div>', $caption );

				$html .= sprintf( '<%s %s>', $main_container, FusionCore_Plugin::attributes( 'login-shortcode-form' ) );

					// Get the success/error notices
					$html .= $this->render_notices( $action );
					
					$html .= sprintf( '<p class="fusion-login-input-wrapper">%s</p>', __( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'fusion-core' ) );
				
					$html .= '<div class="fusion-login-input-wrapper">';
						$html .= sprintf( '<label class="fusion-hidden-content" for="user_login">%s</label>', __( 'Username or Email', 'fusion-core' ) );
						$html .= sprintf( '<input type="text" name="user_login" placeholder="%s" value="" size="20" class="fusion-login-username input-text" id="user_login" />', __( 'Username or Email', 'fusion-core' ) );
					$html .= '</div>';
				
					$html .= '<div class="fusion-login-submit-wrapperr">';
						$html .= sprintf( '<button %s>%s</button>', FusionCore_Plugin::attributes( 'login-shortcode-button' ), __( 'Reset Password', 'fusion-core' ) );

						// Set the query string for successful password reset
						if ( ! $redirection_link ) {
							$redirection_link = $this->get_redirection_link();
						}
						$html .= $this->render_hidden_login_inputs( $redirection_link, array( 'action' => 'lostpassword', 'success' => '1' ) ); 

					$html .= '</div>';				

				$html .= sprintf( '</%s>', $main_container );
			$html .= '</div>';

		} else {
			$html .=  do_shortcode( sprintf( '[alert type="general" border_size="1px" box_shadow="yes"]%s[/alert]', __( 'You are already signed in.', 'fusion-core' ) ) );
		}
		
		return $html;
	}	
	
	/**
	 * Render the needed hidden login inputs.
	 *
	 * @since 1.8.0	 
	 *
	 * @param  string $redirecttion_link	A redirection link
	 * @param  array  $query_args			Query arguments for the redirection link
	 * @return void
	 */
	public function render_hidden_login_inputs( $redirection_link = '', $query_args = array() ) {
		$html = '';
		if ( ! self::$args['disable_form'] ) {

			$html .= '<input type="hidden" name="user-cookie" value="1" />';

			// If no redirection link is given, get ones
			if ( empty( $redirection_link ) ) {
				if ( isset( $_SERVER['REQUEST_URI'] ) ) {
					$redirection_link = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				} else {
					$redirection_link = wp_get_referer();
				}

				// Redirection and source input
				$redirection_link = remove_query_arg( 'loggedout', $redirection_link );
			}
			
			if ( ! empty( $query_args ) ) {
				$redirection_link = add_query_arg( $query_args, $redirection_link );
			}
			
			$html .= sprintf( '<input type="hidden" name="redirect_to" value="%s" />', esc_url( $redirection_link ) );
			$html .= '<input type="hidden" name="fusion_login_box" value="true" />';
			
			// Prevent hijacking of the form
			$html .= wp_nonce_field( 'fusion-login', '_wpnonce', true, false );			
		}
		
		return $html;

	}	
	
	/**
	 * Deals with the different requests.
	 *
	 * @since 1.8.0	 
	 *
	 * @return void
	 */	
	public function login_init() {
		$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : 'login';

		if ( isset( $_POST['wp-submit'] ) ) {
			$action = 'post-data';
		} else if ( isset( $_GET['reauth'] ) ) {
			$action = 'reauth';
		}
		
		$redirect_link = $this->get_redirection_link();

		// redirect to change password form
		if ( $action == 'resetpass' ) {
			wp_redirect( add_query_arg( array( 'action' => 'resetpass' ), $redirect_link ) );
			exit;
		}

		if (
			$action == 'post-data'        ||            // don't mess with POST requests
			$action == 'reauth'           ||            // need to reauthorize
			$action == 'logout'                         // user is logging out
		) {
			return;
		}

		wp_redirect( $redirect_link );
		exit;
	}

	/**
	 * Constructs a redirection link, either from the $redirect_to variable or from the referer.
	 *
	 * @since 1.8.0	 
	 *
	 * @return string The redirection link.
	 */
	public function get_redirection_link( $error = false ) {
		$redirection_link = '';

		if ( $error ) {
			$redirection_link = $_REQUEST['_wp_http_referer'];
		} elseif ( isset( $_REQUEST['redirect_to'] ) ) {
			$redirection_link = $_REQUEST['redirect_to'];
		} elseif ( isset( $_SERVER ) && isset( $_SERVER['HTTP_REFERER'] ) && $_SERVER['HTTP_REFERER'] ) {
			$referer_array = parse_url( $_SERVER['HTTP_REFERER'] );
			$referer = '//' . $referer_array['host'] . $referer_array['path'];

			// If there's a valid referrer, and it's not the default log-in screen
			if ( ! empty( $referer ) && ! strstr( $referer, 'wp-login' ) && ! strstr( $referer, 'wp-admin' ) ) {
				$redirection_link = $referer;
			}
		}

		return $redirection_link;
	}
	
	/**
	 * Redirects after the login, both on success and error.
	 *
	 * @since 1.8.0	 
	 *
	 * @param string $redirect_to			The redirect destination URL. 
	 * @param string $requested_redirect_to	The requested redirect destination URL passed as a parameter. 
	 * @param WP_User|WP_Error $user		WP_User object if login was successful, WP_Error object otherwise. 
	 * @return string The redirection link.
	 */	
	public function login_redirect( $redirect_to, $requested_redirect_to, $user ) {
		// Make sure we come from the login box
		if ( isset( $_POST['fusion_login_box'] ) ) {
			// If we have no errors, remove the action query arg
			if ( ! isset( $user->errors ) ) {
				return $redirect_to;
			}

			// Redirect to the page with the login box with error code
			wp_redirect( add_query_arg( array( 'action' => 'login', 'success' => '0' ), $this->get_redirection_link( true ) ) );
			exit;
		} else {
			return $redirect_to;
		}
	}
	
	/**
	 * Redirects after the login, both on success and error.
	 *
	 * @since 1.8.0	 
	 *
	 * @param WP_Error $errors				A WP_Error object containing any errors encountered during registration. 
	 * @param string $sanitized_user_login	User's username after it has been sanitized.
	 * @param string $user_email			User's email.
	 * @return void|WP_Error 				Error object.
	 */		
	public function registration_error_redirect( $errors, $sanitized_user_login, $user_email ) {
		// Make sure we come from the login box
		if ( isset( $_POST['fusion_login_box'] ) ) {		
			$redirection_link = $this->get_redirection_link();

			// Redirect spammers directly to success page
			if ( ! isset($_POST['confirm_email']) || $_POST['confirm_email'] !== '' ) {
				wp_redirect( add_query_arg( array( 'action' => 'register', 'success' => '1' ), $redirection_link ) );
				exit;
			}			

			// Error - prepare query strings for front end notice output
			if ( ! empty( $errors->errors ) ) {
				$redirection_link = $this->get_redirection_link( true );
				$redirection_link = add_query_arg( array( 'action' => 'register', 'success' => '0' ), $redirection_link );

				// Empty username
				if ( isset( $errors->errors['empty_username'] ) ) {
					$redirection_link = add_query_arg( array( 'empty_username' => '1' ), $redirection_link );
				}
				// Empty email
				if ( isset( $errors->errors['empty_email'] ) ) {
					$redirection_link = add_query_arg( array( 'empty_email' => '1' ), $redirection_link );
				}
				// Username exists
				if ( isset( $errors->errors['username_exists'] ) ) {
					$redirection_link = add_query_arg( array( 'username_exists' => '1' ), $redirection_link );
				}
				// Email exists
				if ( isset( $errors->errors['email_exists'] ) ) {
					$redirection_link =  add_query_arg( array( 'email_exists' => '1' ), $redirection_link );
				}
				
				wp_redirect( $redirection_link );
				exit;			
			}
		}

		return $errors;
	}
	
	/**
	 * Redirects on lost password submission error..
	 *
	 * @since 1.8.0	 
	 *
	 * @return void
	 */		
	public function lost_password_redirect() {
		// Make sure we come from the login box
		if ( isset( $_POST['fusion_login_box'] ) ) {
			$redirection_link = add_query_arg( array( 'action' => 'lostpassword', 'success' => '0' ), $this->get_redirection_link( true ) );
			$user_data = '';
			
			// Error - empty input
			if ( empty( $_POST['user_login'] ) ) {
				$redirection_link = add_query_arg( array( 'empty_login' => '1' ), $redirection_link );
			// Check email
			} elseif ( strpos( $_POST['user_login'], '@' ) ) {
				$user_data = get_user_by( 'email', trim( $_POST['user_login'] ) );
				// Error - invalid email
				if ( empty( $user_data ) ) {
					$redirection_link = add_query_arg( array( 'unregistered_mail' => '1' ), $redirection_link );
				}
			// Check username
			} else {
				$login = trim($_POST['user_login']);
				$user_data = get_user_by('login', $login);	
				
				// Error - invalid username
				if ( empty( $user_data ) ) {
					$redirection_link = add_query_arg( array( 'unregisred_user' => '1' ), $redirection_link );
				}
			}
			
			// Redirect on error
			if ( empty( $user_data ) ) {
				wp_redirect( $redirection_link );
				exit;
			}
		}
	}
	
	/**
	 * Renders the response messages after form submission.
	 *
	 * @since 1.8.0	 
	 *
	 * @param string $context The context of the calling form.
	 * @return void
	 */		
	public function render_notices( $context = '' ) {
		// Make sure we have some query string returned; if not we had a successful login
		if ( isset( $_GET['action'] ) && $_GET['action'] == $context ) {
			// Login - there is only an error message and it is always the same
			if ( $_GET['action'] == 'login' && isset( $_GET['success'] ) && $_GET['success'] == '0' ) {
				$notice_type = 'error';
				$notices = __( 'Login failed, please try again.', 'fusion-core' );
			// Registration
			} elseif ( $_GET['action'] == 'register' ) {
				// Success			
				if ( isset( $_GET['success'] ) && $_GET['success'] == '1' ) {
					$notice_type = 'success';
					$notices = __( 'Registration complete. Please check your e-mail.', 'fusion-core' );	
				// Error
				} else {
					$notice_type = 'error';
					$notices = '';
					
					// Empty username
					if ( isset( $_GET['empty_username'] ) ) {
						$notices .= __( 'Please enter a username.', 'fusion-core' ) . '<br />';
					}
					// Empty email
					if ( isset( $_GET['empty_email'] ) ) {
						$notices .= __( 'Please type your e-mail address.', 'fusion-core' ) . '<br />';
					}
					// Username exists
					if ( isset( $_GET['username_exists'] ) ) {
						$notices .= __( 'This username is already registered. Please choose another one.', 'fusion-core' ) . '<br />';
					}
					// Email exists
					if ( isset( $_GET['email_exists'] ) ) {
						$notices .= __( 'This email is already registered, please choose another one.', 'fusion-core' ) . '<br />';
					}

					// Generic Error
					if ( ! $notices ) {
						$notices .= __( 'Something went wrong during registration. Please try again.', 'fusion-core' );
					//Delete the last line break
					} else {
						$notices = substr( $notices, 0, strlen( $notices ) - 6 );
					}
				}
			// Lost password
			} elseif ( $_GET['action'] == 'lostpassword' ) {					
				// Success			
				if ( isset( $_GET['success'] ) && $_GET['success'] == '1' ) {
					$notice_type = 'success';
					$notices = __( 'Check your e-mail for the confirmation link.', 'fusion-core' );	
				// Error
				} else {
					$notice_type = 'error';
					$notices = '';
					
					// Empty login
					if ( isset( $_GET['empty_login'] ) ) {
						$notices .= __( 'Enter a username or e-mail address.', 'fusion-core' ) . '<br />';
					}
					
					// Empty login
					if ( isset( $_GET['unregisred_user'] ) ) {
						$notices .= __( 'Invalid username.', 'fusion-core' ) . '<br />';
					}
					
					// Empty login
					if ( isset( $_GET['unregistered_mail'] ) ) {
						$notices .= __( 'There is no user registered with that email address.', 'fusion-core' ) . '<br />';
					}
					
					// Generic Error
					if ( ! $notices ) {
						$notices .= __( 'Invalid username or e-mail.', 'fusion-core' );
					//Delete the last line break
					} else {
						$notices = substr( $notices, 0, strlen( $notices ) - 6 );
					}					
					
				}
			}
			
			$html = do_shortcode( sprintf( '[alert type="%s" border_size="1px" box_shadow="yes"]%s[/alert]', $notice_type, $notices ) );
		} else {
			$html =  '';
		}
		
		return $html;
	}
	
	/**
	 * Constructs the scoped style tag for the login box.
	 *
	 * @since 1.8.0	 
	 *
	 * @return string The scoped styles.
	 */		
	public function get_style_tag() {
		$this->login_counter++;
	
		$styles = '';
		
		if ( self::$args['heading_color'] ) {
			$styles .= sprintf( '.fusion-login-box-%s .fusion-login-heading{color:%s;}', $this->login_counter, self::$args['heading_color'] );
		}
		
		if ( self::$args['caption_color'] ) {
			$styles .= sprintf( '.fusion-login-box-%s .fusion-login-caption{color:%s;}', $this->login_counter, self::$args['caption_color'] );
		}
		
		if ( self::$args['link_color'] ) {
			$styles .= sprintf( '.fusion-login-box-%s a{color:%s;}', $this->login_counter, self::$args['link_color'] );
		}
		
		if ( $styles ) {
			$styles = sprintf( '<style type="text/css" scoped="scoped">%s</style>', $styles );
		}
		
		return $styles;
	}

	/**
	 * Attribtues function for the main login box container.
	 *
	 * @since 1.8.0	 
	 *
	 * @return array The attributes.
	 */	
	function attr() {

		$attr = array();

		$attr['class'] = sprintf( 'fusion-login-box fusion-login-box-%s fusion-login-box-%s fusion-login-align-%s', $this->login_counter, self::$args['action'], self::$args['text_align'] );

		if ( self::$args['class'] ) {
			$attr['class'] .= ' ' . self::$args['class'];
		}
 
		if ( self::$args['id'] ) {
			$attr['id'] = self::$args['id'];
		}

		return $attr;

	}
	
	/**
	 * Attribues function for the form container.
	 *
	 * @since 1.8.0	 
	 *
	 * @return array The attributes.
	 */		
	function form_attr() {

		$attr = array();

		$attr['class'] = 'fusion-login-form';
		
		if ( self::$args['form_background_color'] ) {
			$attr['style'] = sprintf( 'background-color:%s;', self::$args['form_background_color'] );
		}
		
		if ( self::$args['disable_form'] ) {
			return $attr;
		}
		
		$attr['name'] = self::$args['action'] .'form';
		
		$attr['id'] = self::$args['action'] .'form';
		
		$attr['method'] = 'post';
		
		if ( self::$args['action'] == 'login' ) {
			$attr['action'] = site_url( 'wp-login.php', 'login_post' );
		} else {
			$attr['action'] = site_url( add_query_arg( array( 'action' => self::$args['action'] ), 'wp-login.php' ), 'login_post' );
		}

		return $attr;

	}	
	
	/**
	 * Attribues function for the button.
	 *
	 * @since 1.8.0	 
	 *
	 * @return array The attributes.
	 */		
	function button_attr() {

		$attr = array();

		$attr['class'] = 'fusion-login-button fusion-button fusion-button-default fusion-button-medium';
		
		if ( self::$args['button_fullwidth'] == 'yes' ) {
			$attr['class'] .= ' fusion-login-button-fullwidth';
		}
		
		$attr['type'] = 'submit';
		
		$attr['name'] = 'wp-submit';

		return $attr;

	}
	
}

new FusionSC_Login();