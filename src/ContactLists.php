<?php

namespace PureCloud;

/**
 * Class ContactLists
 * @package PureCloud
 */
class ContactLists extends ClientService {

	private $path = '/outbound/contactlists';

	/**
	 * @param string $list_id
	 * @param array  $data
	 * @param array  $options
	 *
	 * @return bool
	 */
	public function add_contacts( $list_id, array $data, array $options = [] ) {

		$path = sprintf( '%1$s/%2$s/contacts', $this->path, $list_id );
		if ( ! empty( $options ) ) {
			$path .= '?' . http_build_query( $options );
		}
		$response = $this->client->post( $path, $data );

		return $response;
	}

}