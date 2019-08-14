<?php

namespace PureCloud;

const VERSION = '0.0.1';

/**
 * Class Client
 * @package PureCloud
 */
class Client {

	const API_VERSION = 'v2';

	const BASE_URL = 'https://api.mypurecloud.com/api';

	private $client_id;

	private $client_secret;

	private $access_token;

	/**
	 * PureCloudApi constructor.
	 *
	 * @param string $client_id
	 * @param string $client_secret
	 */
	public function __construct( $client_id, $client_secret ) {

		$this->client_id     = $client_id;
		$this->client_secret = $client_secret;

	}

	/**
	 * @param $client_id
	 * @param $client_secret
	 *
	 * @return string
	 */
	private function get_token( $client_id, $client_secret ) {

		$payload = http_build_query( [ 'grant_type' => 'client_credentials' ] );

		$headers = [
			'Authorization'  => 'Basic ' . base64_encode( sprintf( '%1$s:%2$s', $client_id, $client_secret ) ),
			'Content-Length' => strlen( $payload ),
			'Content-Type'   => 'application/x-www-form-urlencoded',
			'User-Agent'     => 'EchoDelta/PureCloudClient/' . VERSION,
		];

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
		curl_setopt( $ch, CURLOPT_HEADER, false );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $this->prepare_headers( $headers ) );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_URL, 'https://login.mypurecloud.com/oauth/token' );

		$response = curl_exec( $ch );

		return new ClientCredentialsResponse( $response );
	}

	/**
	 * Prepares headers for use in HTTP request.
	 *
	 * @param array $headers
	 *
	 * @return string[]
	 */
	private function prepare_headers( array $headers ) {

		$prepared_headers = array_map( function ( $header ) {
			return sprintf( '%1$s: %2$s', $header[0], $header[1] );
		}, $headers );

		return $prepared_headers;
	}

	/**
	 * @param string $path
	 *
	 * @return string
	 */
	private function get_url( $path ) {

		return sprintf( '%1$s/%2$s/%3$s', self::BASE_URL, self::API_VERSION, $path );
	}

	/**
	 * @param string $path
	 * @param array  $data
	 *
	 * @return bool
	 */
	public function post( $path, array $data = [] ) {

		return $this->request( 'POST', $path, $data );
	}

	/**
	 * @param string $method
	 * @param string $path
	 * @param array  $data
	 *
	 * @return bool
	 */
	public function request( $method, $path, array $data = [] ) {

		$oauth = $this->get_token( $this->client_id, $this->client_secret );
		if ( ! $oauth ) {
			return false;
		}

		$headers = [
			'Accept'        => 'application/json',
			'Authorization' => 'Bearer ' . $this->access_token,
			'Content-Type'  => 'application/json',
			'User-Agent'    => 'EchoDelta/PureCloudClient/' . VERSION,
		];

		$url = $this->get_url( $path );

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $method );
		curl_setopt( $ch, CURLOPT_HEADER, false );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_URL, $url );

		if ( ! empty( $data ) ) {

			$payload = json_encode( $data );

			$headers['Content-Length'] = strlen( $payload );

			curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );

		}

		curl_setopt( $ch, CURLOPT_HTTPHEADER, $this->prepare_headers( $headers ) );

		$result = curl_exec( $ch );
		if ( false === $result ) {
			echo 'cURL ERROR: ' . curl_error( $ch );
		}

		curl_close( $ch );

		return true;
	}

}
