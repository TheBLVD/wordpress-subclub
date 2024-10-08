<?php

namespace Subclub;

use Exception;

class Publish {

	public static function init() {
		add_action( 'wp_trash_post', array( __CLASS__, 'subclub_delete_premium_post' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'subclub_add_meta_box' ), 10, 2 );
		add_action( 'save_post', array( __CLASS__, 'subclub_save_meta_box_data' ) );
	}
	// Add meta box to the post editor
	static function subclub_add_meta_box() {
		add_meta_box(
			'subclub_post_options',
			'sub.club',
			array( __CLASS__, 'subclub_meta_box_callback' ),
			'post',
			'side',
		);
	}

	// Meta box callback function
	static function subclub_meta_box_callback( $post ) {
		wp_nonce_field( 'subclub_save_meta_box_data', 'subclub_meta_box_nonce' );
		$post_type_value = get_post_meta( $post->ID, '_subclub_post_type', true );
		if ( empty( $post_type_value ) ) {
			$post_type_value = 'premium';
		}
		$delete_option_value = get_post_meta( $post->ID, '_subclub_delete_premium', true );
		?>
		<p>
			<label for="subclub_post_type">Post type:</label>
			<select name="subclub_post_type" id="subclub_post_type">
				<option value="free" <?php selected( $post_type_value, 'free' ); ?>>Free</option>
				<option value="premium" <?php selected( $post_type_value, 'premium' ); ?>>Premium</option>
			</select>
		</p>
		<p>
			<label for="subclub_delete_premium_option"><?php esc_html_e( 'On delete', 'subclub' ); ?></label>
			<select name="subclub_delete_premium_option" id="subclub_delete_premium_option">
				<option value="delete" <?php selected( $delete_option_value, 'delete' ); ?>><?php esc_html_e( 'Delete premium', 'subclub' ); ?></option>
				<option value="keep" <?php selected( $delete_option_value, 'keep' ); ?>><?php esc_html_e( 'Keep premium', 'subclub' ); ?></option>
			</select>
		</p>
		<?php
	}

	// Save meta box data
	static function subclub_save_meta_box_data( $post_id ) {
		if ( ! isset( $_POST['subclub_meta_box_nonce'] ) ) {
			return;
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['subclub_meta_box_nonce'] ) ), 'subclub_save_meta_box_data' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( ! isset( $_POST['subclub_post_type'] ) ) {
			return;
		}

		if ( array_key_exists( 'subclub_delete_premium', $_POST ) ) {
			update_post_meta(
				$post_id,
				'_subclub_delete_premium',
				sanitize_text_field( wp_unslash( $_POST['subclub_delete_premium'] ) )
			);
		} else {
			delete_post_meta( $post_id, '_subclub_delete_premium' );
		}

		$post_type = sanitize_text_field( wp_unslash( $_POST['subclub_post_type'] ) );
		update_post_meta( $post_id, '_subclub_post_type', $post_type );

		if ( ! isset( $_POST['subclub_delete_premium_option'] ) ) {
			return;
		}

		$delete_option = sanitize_text_field( wp_unslash( $_POST['subclub_delete_premium_option'] ) );
		update_post_meta( $post_id, '_subclub_delete_premium', $delete_option );

		if ( $post_type == 'premium' ) {
			$title        = get_the_title( $post_id );
			$post         = get_post( $post_id );
			$excerpt      = $post->post_excerpt;
			$post_content = '<h1>' . $title . '</h1>' . $post->post_content;

			if ( ! $excerpt ) {
				$excerpt = 'New premium post';
			}

			// Post to SubClub
			$api_key = get_option( 'subclub_api_key' );

			if ( $api_key ) {
				$subclub = new Client( $api_key );
				$post    = get_post( $post_id );

				try {
					$params   = array( 'content' => $post_content );
					$response = $subclub->createPost( $params );

					if ( isset( $response['url'] ) ) {
						// Store the URL in post meta
						update_post_meta( $post_id, '_subclub_post_url', $response['url'] );
						update_post_meta( $post_id, '_subclub_post_id', $response['postId'] );

						// Concat the excerpt with the subclub post URL
						$excerpt = $excerpt . '<br /><br />RE: <a href="' . $response['url'] . '" target="_blank">' . $response['url'] . '</a>';

						// Hacky way to prevent infinite recursion
						unset( $_POST['subclub_meta_box_nonce'] );

						// Update post body with the excerpt
						wp_update_post(
							array(
								'ID'           => $post_id,
								'post_content' => $excerpt,
								'meta_input'   => array(
									'_subclub_post_id'  => $response['postId'],
									'_subclub_post_url' => $response['url'],
								),
							)
						);

						// update the post composer content with the content of $excerpt. Use jquery to do this.
						?>
						<script>
							jQuery(document).ready(function($) {
								$('#content').val('<?php echo esc_js( $excerpt ); ?>');
							});
						</script>
						<?php
					}
				} catch ( Exception $e ) {
					// Handle any errors (e.g., log them or notify the admin)
					error_log( 'SubClub API Error: ' . $e->getMessage() );
				}
			}
		}
	}

	static function subclub_delete_premium_post( $post_id ) {
		$delete_premium  = get_post_meta( $post_id, '_subclub_delete_premium', true );
		$subclub_post_id = get_post_meta( $post_id, '_subclub_post_id', true );

		if ( $delete_premium == 'delete' && isset( $subclub_post_id ) ) {
			$api_key = get_option( 'subclub_api_key' );
			$subclub = new Client( $api_key );
			$params  = array( 'postId' => $subclub_post_id );
			try {
				$subclub->deletePost( $params );
			} catch ( Exception $e ) {
				error_log( 'SubClub API Error: ' . $e->getMessage() );
			}
		}
	}
}
