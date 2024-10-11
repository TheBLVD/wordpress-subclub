<?php

namespace SubDotClub;

use Exception;

class Client {

	private $apiUrl = 'https://api.sub.club/public';
	private $apiKey;

	public function __construct( $apiKey ) {
		$this->apiKey = $apiKey;
	}

	// creates a new post on sub.club with the given parameters.
	public function createPost( $params ) {
		$response = wp_remote_request(
			"{$this->apiUrl}/post",
			array(
				'method'  => 'POST',
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => "Bearer {$this->apiKey}",
				),
				'body'    => wp_json_encode( $params ),
			)
		);

		if ( is_wp_error( $response ) ) {
			throw new Exception( 'Failed to create post: ' . esc_html( $response->get_error_message() ) );
		}

		return json_decode( $response['body'], true );
	}

	// edits the given post with the supplied PostUpdateParams.
	public function editPost( $params ) {
		$response = wp_remote_request(
			"{$this->apiUrl}/post/edit",
			array(
				'method'  => 'PUT',
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => "Bearer {$this->apiKey}",
				),
				'body'    => wp_json_encode( $params ),
			)
		);

		if ( is_wp_error( $response ) ) {
			throw new Exception( 'Failed to create post: ' . esc_html( $response->get_error_message() ) );
		}

		return json_decode( $response['body'], true );
	}

	// deletes a post with the given postID.
	public function deletePost( $params ) {
		$response = wp_remote_request(
			"{$this->apiUrl}/post/delete",
			array(
				'method'  => 'DELETE',
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => "Bearer {$this->apiKey}",
				),
				'body'    => wp_json_encode( $params ),
			)
		);

		if ( is_wp_error( $response ) ) {
			throw new Exception( 'Failed to create post: ' . esc_html( $response->get_error_message() ) );
		}

		return json_decode( $response['body'], true );
	}
}
