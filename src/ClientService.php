<?php

namespace PureCloud;

/**
 * Class ClientService
 * @package PureCloud
 */
class ClientService {

	/**
	 * @var Client Client
	 */
	protected $client;

	/**
	 * ClientService constructor.
	 *
	 * @param Client $client
	 */
	public function __construct( Client $client ) {

		$this->client = $client;

	}
}