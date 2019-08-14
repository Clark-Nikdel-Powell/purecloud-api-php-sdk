<?php

namespace PureCloud;

class ClientCredentialsResponse {

	private $_raw_response;
	private $_response;

	public $access_token;
	public $token_type;
	public $expires_in;
	public $error_message;

	public function __construct( $json ) {

		$this->_raw_response = $json;
		$this->_response     = json_decode( $json );

		$this->access_token  = isset( $this->_response->access_token ) ? $this->_response->access_token : false;
		$this->token_type    = isset( $this->_response->token_type ) ? $this->_response->token_type : false;
		$this->expires_in    = isset( $this->_response->expires_in ) ? intval( $this->_response->expires_in ) : false;
		$this->error_message = isset( $this->_response->error ) ? $this->_response->error : false;

	}

	public function get_raw_response() {

		return $this->_raw_response;
	}

	public function get_response() {

		return $this->_response;
	}

	public function has_error() {

		return ! ( false === $this->error_message );
	}

}