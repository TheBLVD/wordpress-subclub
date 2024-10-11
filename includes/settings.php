<?php

namespace Subclub;

class Settings {

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'subclub_settings_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'subclub_settings_init' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_scripts' ) );
	}

	static function subclub_settings_init() {
		add_options_page( 'sub.club Settings', '', 'manage_options', 'subclub', array( __CLASS__, 'subclub_options_page' ) );
		add_settings_section( 'subclub_settings_section', 'API Key', array( __CLASS__, 'subclub_settings_section_callback' ), 'subclub' );
		add_settings_field( 'subclub_api_key', 'API Key', array( __CLASS__, 'subclub_api_key_render' ), 'subclub', 'subclub_settings_section' );
		register_setting( 'subclub_settings', 'subclub_api_key' );
	}

	static function subclub_settings_section_callback() {
		echo '<p>Enter your sub.club API key below:</p>';
	}

	static function subclub_api_key_render() {
		$options = get_option( 'subclub_api_key' );
		echo "<input type='password' name='subclub_api_key' id='subclub_api_key' value='" . esc_attr( $options ) . "' />";
		echo "<button type='button' id='toggle_api_key'>Show</button>";
	}

	static function subclub_options_page() {
		?>
		<form action='options.php' method='post'>
			<h2>sub.club Settings</h2>
			<?php
			settings_fields( 'subclub_settings' );
			do_settings_sections( 'subclub' );
			submit_button();
			?>
		</form>
		<?php
	}

	static function subclub_settings_menu() {
		add_options_page( 'sub.club Settings', 'sub.club', 'manage_options', 'subclub', array( __CLASS__, 'subclub_options_page' ) );
	}

	static function enqueue_admin_scripts( $hook ) {
		if ( 'settings_page_subclub' !== $hook ) {
			return;
		}

		wp_register_script( 'subclub-admin-script', plugins_url( 'js/subclub-admin.js', __FILE__ ), array( 'jquery' ), null, true );
		wp_enqueue_script( 'subclub-admin-script' );

		$inline_script = "
			jQuery(document).ready(function($) {
				$('#toggle_api_key').on('click', function() {
					var input = $('#subclub_api_key');
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
		wp_add_inline_script( 'subclub-admin-script', $inline_script );
	}
}
