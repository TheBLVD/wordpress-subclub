<?php

namespace SubDotClub;

class Settings {

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'subdotclub_settings_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'subdotclub_settings_init' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'subdotclub_enqueue_admin_scripts' ) );
	}

	static function subdotclub_settings_init() {
		add_options_page( 'sub.club Settings', '', 'manage_options', 'subdotclub', array( __CLASS__, 'subdotclub_options_page' ) );
		add_settings_section( 'subdotclub_settings_section', 'API Key', array( __CLASS__, 'subdotclub_settings_section_callback' ), 'subdotclub' );
		add_settings_field( 'subdotclub_api_key', 'API Key', array( __CLASS__, 'subdotclub_api_key_render' ), 'subdotclub', 'subdotclub_settings_section' );
		register_setting( 'subdotclub_settings', 'subdotclub_api_key' );
	}

	static function subdotclub_settings_section_callback() {
		echo '<p>Enter your sub.club API key below:</p>';
	}

	static function subdotclub_api_key_render() {
		$options = get_option( 'subdotclub_api_key' );
		echo "<input type='password' name='subdotclub_api_key' id='subdotclub_api_key' value='" . esc_attr( $options ) . "' />";
		echo "<button type='button' id='toggle_api_key'>Show</button>";
	}

	static function subdotclub_options_page() {
		?>
		<form action='options.php' method='post'>
			<h2>sub.club Settings</h2>
			<?php
			settings_fields( 'subdotclub_settings' );
			do_settings_sections( 'subdotclub' );
			submit_button();
			?>
		</form>
		<?php
	}

	static function subdotclub_settings_menu() {
		add_options_page( 'sub.club Settings', 'sub.club', 'manage_options', 'subdotclub', array( __CLASS__, 'subdotclub_options_page' ) );
	}

	static function subdotclub_enqueue_admin_scripts( $hook ) {
		if ( 'settings_page_subdotclub' !== $hook ) {
			return;
		}

		wp_enqueue_script( 'subdotclub-admin-script' );

		$inline_script = "
			jQuery(document).ready(function($) {
				$('#toggle_api_key').on('click', function() {
					var input = $('#subdotclub_api_key');
					if (input.attr('type') === 'password') {
						input.attr('type', 'text');
						$(this).text('Hide');
					} else {
						input.attr('type', 'password');
						$(this).text('Show');
					}
				});
			});
		";
		wp_add_inline_script( 'subdotclub-admin-script', $inline_script );
	}
}
