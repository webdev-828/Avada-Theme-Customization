<?php

class Avada_ReCaptcha {

	public $recaptcha;

	public function __construct( $secret, $requestMethod = null ) {
		$this->recaptcha = new \ReCaptcha\ReCaptcha( $secret );
	}

}
