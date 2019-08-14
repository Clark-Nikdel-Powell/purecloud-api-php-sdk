<?php


namespace PureCloud;


class Response {

	private $_raw_response;
	private $_response;

	public function __construct( $response ) {

		$this->_raw_response = $response;
		$this->_response     = json_decode( $response );

	}

	public function get_raw_response() {

		return $this->_raw_response;
	}

	public function get_response() {

		return $this->_response;
	}

}
