<?php

/**
 * Plugin Name:       SiteFlight Dev Helpers
 * Description:       Get mapped provides a way to insert a custom map into your project. It connects to a CPT allowing you
 *                    to create a map of anything you like. It requires latitude and longitude.
 * Version:           1.0
 * Author:            Nate Gervenak
 * Text Domain:       sfdvhlp
 *
 * @package           sf-dev-helpers
 */

if (!defined('ABSPATH')) {
	exit;
}


define('SFDVHLP_VERSION', '1.0');
define('SFDVHLP_PLUGIN_FILE', __FILE__);
define('SFDVHLP_DIR', plugin_dir_path(__FILE__));
define('SFDVHLP_DIR_URL', plugin_dir_url(__FILE__));

require SFDVHLP_DIR . 'console-log.php';
// require SFDVHLP_DIR . 'import-csv.php';
