<?php

namespace Subclub;

class Settings {

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'subclub_settings_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'subclub_settings_init' ) );
	}

	static function subclub_settings_init() {
		add_options_page( 'SubClub Settings', '', 'manage_options', 'subclub', array( __CLASS__, 'subclub_options_page' ) );
		add_settings_section( 'subclub_settings_section', 'API Key', array( __CLASS__, 'subclub_settings_section_callback' ), 'subclub' );
		add_settings_field( 'subclub_api_key', 'API Key', array( __CLASS__, 'subclub_api_key_render' ), 'subclub', 'subclub_settings_section' );
		register_setting( 'subclub_settings', 'subclub_api_key' );
	}

	static function subclub_settings_section_callback() {
		echo '<p>Enter your SubClub API key below:</p>';
	}

	static function subclub_api_key_render() {
		$options = get_option( 'subclub_api_key' );
		echo "<input type='password' name='subclub_api_key' id='subclub_api_key' value='" . esc_attr( $options ) . "' />";
		echo "<button type='button' id='toggle_api_key'>Show</button>";
		echo "<script>
            document.getElementById('toggle_api_key').addEventListener('click', function() {
                var input = document.getElementById('subclub_api_key');
                if (input.type === 'password') {
                    input.type = 'text';
                    this.textContent = 'Hide';
                } else {
                    input.type = 'password';
                    this.textContent = 'Show';
                }
            });
        </script>";
	}

	static function subclub_options_page() {
		?>
		<form action='options.php' method='post'>
			<h2>SubClub Settings</h2>
			<?php
			settings_fields( 'subclub_settings' );
			do_settings_sections( 'subclub' );
			submit_button();
			?>
		</form>
		<?php
	}

	static function subclub_settings_menu() {
		add_options_page( 'SubClub Settings', 'SubClub', 'manage_options', 'subclub', array( __CLASS__, 'subclub_options_page' ) );
	}
}
