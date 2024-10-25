<?php

namespace SubDotClub;

use Exception;

class Publish {

	public static function init() {
		add_action( 'wp_trash_post', array( __CLASS__, 'subdotclub_delete_premium_post' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'subdotclub_add_meta_box' ), 10, 2 );
		add_action( 'save_post', array( __CLASS__, 'subdotclub_save_meta_box_data' ) );
	}
	// Add meta box to the post editor
	static function subdotclub_add_meta_box() {
		add_meta_box(
			'subdotclub_post_options',
			'sub.club',
			array( __CLASS__, 'subdotclub_meta_box_callback' ),
			'post',
			'side',
		);
	}

	// Meta box callback function
	static function subdotclub_meta_box_callback( $post ) {
		wp_nonce_field( 'subdotclub_save_meta_box_data', 'subdotclub_meta_box_nonce' );
		$post_type_value = get_post_meta( $post->ID, '_subdotclub_post_type', true );
		if ( empty( $post_type_value ) ) {
			$post_type_value = 'free';
		}
		$delete_option_value = get_post_meta( $post->ID, '_subdotclub_delete_premium', true );
		$is_editing = $post->post_status != 'auto-draft';
		?>
		<p>
			<label for="subdotclub_post_type"><?php esc_html_e( 'Post type', 'sub-club' ); ?></label>
			<select name="subdotclub_post_type" id="subdotclub_post_type" aria-describedby="subdotclub_post_type_description" <?php disabled( $is_editing ); ?>>
				<option value="free" <?php selected( $post_type_value, 'free' ); ?>><?php esc_html_e( 'Free', 'sub-club' ); ?></option>
				<option value="premium" <?php selected( $post_type_value, 'premium' ); ?>><?php esc_html_e( 'Premium', 'sub-club' ); ?></option>
			</select>
			<span id="subdotclub_post_type_description" class="screen-reader-text"><?php esc_html_e( 'Select the type of post: Free or Premium.', 'sub-club' ); ?></span>
		</p>
		<p>
			<label for="subdotclub_delete_premium_option"><?php esc_html_e( 'On delete', 'sub-club' ); ?></label>
			<select name="subdotclub_delete_premium_option" id="subdotclub_delete_premium_option" aria-describedby="subdotclub_delete_premium_option_description" <?php disabled( $is_editing ); ?>>
				<option value="delete" <?php selected( $delete_option_value, 'delete' ); ?>><?php esc_html_e( 'Delete premium', 'sub-club' ); ?></option>
				<option value="keep" <?php selected( $delete_option_value, 'keep' ); ?>><?php esc_html_e( 'Keep premium', 'sub-club' ); ?></option>
			</select>
			<span id="subdotclub_delete_premium_option_description" class="screen-reader-text"><?php esc_html_e( 'Choose whether to delete or keep the premium post when the WordPress post is deleted.', 'sub-club' ); ?></span>
		</p>
		<?php
	}

	// Save meta box data
	static function subdotclub_save_meta_box_data( $post_id ) {
		if ( ! isset( $_POST['subdotclub_meta_box_nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['subdotclub_meta_box_nonce'] ) ), 'subdotclub_save_meta_box_data' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( ! isset( $_POST['subdotclub_post_type'] ) ) {
			return;
		}

		if ( array_key_exists( 'subdotclub_delete_premium', $_POST ) ) {
			update_post_meta(
				$post_id,
				'_subdotclub_delete_premium',
				sanitize_text_field( wp_unslash( $_POST['subdotclub_delete_premium'] ) )
			);
		} else {
			delete_post_meta( $post_id, '_subdotclub_delete_premium' );
		}

		$post_type = sanitize_text_field( wp_unslash( $_POST['subdotclub_post_type'] ) );
		update_post_meta( $post_id, '_subdotclub_post_type', $post_type );

		if ( ! isset( $_POST['subdotclub_delete_premium_option'] ) ) {
			return;
		}

		$delete_option = sanitize_text_field( wp_unslash( $_POST['subdotclub_delete_premium_option'] ) );
		update_post_meta( $post_id, '_subdotclub_delete_premium', $delete_option );

		// Only execute the plugin logic when the post is being published for the first time
		if ( get_post_status( $post_id ) != 'publish' || get_post_meta( $post_id, '_subdotclub_post_published', true ) ) {
			return;
		}

		if ( $post_type == 'premium' ) {
			$title        = get_the_title( $post_id );
			$post         = get_post( $post_id );
			$excerpt      = $post->post_excerpt;
			$post_content = '<h1>' . $title . '</h1>' . $post->post_content;

			if ( ! $excerpt ) {
				$excerpt = 'New premium post';
			}

			// Post to SubClub
			$api_key = get_option( 'subdotclub_api_key' );

			if ( $api_key ) {
				$subdotclub = new Client( $api_key );
				$post    = get_post( $post_id );

				$media_attachments = self::get_post_images_and_videos( $post_id );

				// Remove all images and videos from the post content
				$post_content = preg_replace('/<img[^>]*>/i', '', $post_content);
				$post_content = preg_replace('/<video[^>]*>.*?<\/video>/i', '', $post_content);

				// Sanitize the content to regular HTML
				$post_content = wp_kses_post($post_content);
				// Remove empty tags from the $post_content HTML
				$post_content = preg_replace('/<(\w+)\b(?:\s+[^>]*)?>\s*<\/\1>/', '', $post_content);

				try {
					$params   = array( 'content' => $post_content, 'attachments' => $media_attachments );
					$response = $subdotclub->createPost( $params );

					if ( isset( $response['url'] ) ) {
						// Store the URL in post meta
						update_post_meta( $post_id, '_subdotclub_post_url', $response['url'] );
						update_post_meta( $post_id, '_subdotclub_post_id', $response['postId'] );
						update_post_meta( $post_id, '_subdotclub_post_published', true );

						// Concat the excerpt with the subdotclub post URL
						$excerpt = $excerpt . '<br /><br />RE: <a href="' . $response['url'] . '" target="_blank">' . $response['url'] . '</a>';

						// Hacky way to prevent infinite recursion
						unset( $_POST['subdotclub_meta_box_nonce'] );

						// Update post body with the excerpt
						wp_update_post(
							array(
								'ID'           => $post_id,
								'post_content' => $excerpt,
								'meta_input'   => array(
									'_subdotclub_post_id'  => $response['postId'],
									'_subdotclub_post_url' => $response['url'],
								),
							)
						);

						// Add inline script to update the post composer content
						$inline_script = sprintf(
							"jQuery(document).ready(function($) { $('#content').val('%s'); });",
							esc_js( $excerpt )
						);
						wp_add_inline_script( 'subdotclub-admin-script', $inline_script );
					}
				} catch ( Exception $e ) {
					// Handle any errors (e.g., log them or notify the admin)
					error_log( 'SubClub API Error: ' . $e->getMessage() );
				}
			}
		}
	}

	static function subdotclub_delete_premium_post( $post_id ) {
		$delete_premium  = get_post_meta( $post_id, '_subdotclub_delete_premium', true );
		$subdotclub_post_id = get_post_meta( $post_id, '_subdotclub_post_id', true );

		if ( $delete_premium == 'delete' && isset( $subdotclub_post_id ) ) {
			$api_key = get_option( 'subdotclub_api_key' );
			$subdotclub = new Client( $api_key );
			$params  = array( 'postId' => $subdotclub_post_id );
			try {
				$subdotclub->deletePost( $params );
			} catch ( Exception $e ) {
				error_log( 'SubClub API Error: ' . $e->getMessage() );
			}
		}
	}

	static function get_post_images_and_videos( $post_id ) {
		// Get the post object.
		$post = get_post( $post_id );
		if ( ! $post ) {
			return array(); // Return an empty array if the post doesn't exist.
		}
	
		// Array to hold media information (image and video objects).
		$media_objects = array();
	
		// 1. Get attached images and videos using MIME types
		// Get attached images.
		$attached_images = get_attached_media( 'image', $post_id );
		// Get attached videos.
		$attached_videos = get_attached_media( 'video', $post_id );
	
		// Helper function to get media details.
		function get_media_details( $attachment_id ) {
			// Get the attachment's metadata.
			$attachment_meta = wp_get_attachment_metadata( $attachment_id );
			$media_url = wp_get_attachment_url( $attachment_id );
			$mime_type = get_post_mime_type( $attachment_id );
	
			// Default dimensions in case metadata is missing.
			$width = isset( $attachment_meta['width'] ) ? $attachment_meta['width'] : 0;
			$height = isset( $attachment_meta['height'] ) ? $attachment_meta['height'] : 0;
	
			// For videos, try to grab the width/height from video metadata.
			if ( strpos( $mime_type, 'video' ) !== false && isset( $attachment_meta['sizes'] ) ) {
				$width = isset( $attachment_meta['sizes']['width'] ) ? $attachment_meta['sizes']['width'] : $width;
				$height = isset( $attachment_meta['sizes']['height'] ) ? $attachment_meta['sizes']['height'] : $height;
			}
	
			// Return an object with the necessary details.
			return (object) array(
				'width'     => $width,
				'height'    => $height,
				'url'       => $media_url,
				'mediaType' => $mime_type,
				'type' 		=> 'Document'
			);
		}
	
		// 2. Loop through attached images and videos, adding their details to the array.
		foreach ( $attached_images as $image ) {
			$media_objects[] = get_media_details( $image->ID );
		}
		foreach ( $attached_videos as $video ) {
			$media_objects[] = get_media_details( $video->ID );
		}
	
		// 3. Extract media (images & videos) from the post content itself.
		// Find all image IDs in post content.
		if ( preg_match_all( '/wp-image-(\d+)/', $post->post_content, $matches ) ) {
			foreach ( $matches[1] as $image_id ) {
				$media_objects[] = get_media_details( intval( $image_id ) );
			}
		}
	
		// Find all video IDs in post content (if inserted as a wp-video class).
		if ( preg_match_all( '/<video.*?wp-video-(\d+)/', $post->post_content, $matches ) ) {
			foreach ( $matches[1] as $video_id ) {
				$media_objects[] = get_media_details( intval( $video_id ) );
			}
		}
	
		// Handle [video] shortcodes in the post content (embedded videos).
		if ( has_shortcode( $post->post_content, 'video' ) ) {
			$pattern = get_shortcode_regex( array( 'video' ) );
			if ( preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches ) && isset( $matches[3] ) ) {
				foreach ( $matches[3] as $attrs ) {
					$attrs = shortcode_parse_atts( $attrs );
					if ( isset( $attrs['ids'] ) ) {
						$ids = explode( ',', $attrs['ids'] );
						foreach ( $ids as $video_id ) {
							$media_objects[] = get_media_details( intval( $video_id ) );
						}
					}
				}
			}
		}
	
		// Return the array of media objects.
		return $media_objects;
	}
}