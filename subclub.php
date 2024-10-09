<?php

/**
 * Plugin Name: sub.club
 * Plugin URI:  https://github.com/theblvd/wordpress-subclub/
 * Description: Turn your free blog into a premium blog using sub.club.
 * Version:     1.0
 * Author:      TheBLVD
 * Author URI:  https://theblvd.carrd.co/
 * License:     GPL2
 */


namespace Subclub;

// Include the Admin class file
require_once plugin_dir_path( __FILE__ ) . 'includes/settings.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/publish.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/client.php';


/**
 * Initialize plugin.
 */

// Initialize the Admin class when all plugins are loaded
add_action(
	'plugins_loaded',
	function () {
		add_action( 'init', array( __NAMESPACE__ . '\settings', 'init' ) );
		add_action( 'init', array( __NAMESPACE__ . '\publish', 'init' ) );
	}
);
