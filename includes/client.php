<?php

namespace Subclub;

use Exception;

class Client
{
    private $apiUrl = 'https://api.sub.club/public';
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    // creates a new post on sub.club with the given parameters.
    public function createPost($params)
    {
        $response = wp_remote_request("{$this->apiUrl}/post", [
            'method' => 'POST',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->apiKey}",
            ],
            'body' => json_encode($params),
        ]);

        if (is_wp_error($response)) {
            throw new Exception("Failed to create post: " . $response->get_error_message());
        }

        return json_decode($response['body'], true);
    }

    // edits the given post with the supplied PostUpdateParams.
    public function editPost($params)
    {
        $response = wp_remote_request("{$this->apiUrl}/post/edit", [
            'method' => 'PUT',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->apiKey}",
            ],
            'body' => json_encode($params),
        ]);

        if (is_wp_error($response)) {
            throw new Exception("Failed to create post: " . $response->get_error_message());
        }

        return json_decode($response['body'], true);
    }

    // deletes a post with the given postID.
    public function deletePost($params)
    {
        $response = wp_remote_request("{$this->apiUrl}/post/delete", [
            'method' => 'DELETE',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->apiKey}",
            ],
            'body' => json_encode($params),
        ]);

        if (is_wp_error($response)) {
            throw new Exception("Failed to create post: " . $response->get_error_message());
        }

        return json_decode($response['body'], true);
    }
}
