<?php

/**
 * Plugin Name:       SiteFlight Mapped
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

require SFDVHLP_DIR . 'console.log.php';

/**
 * Check for plugin requirements
 *
 */


// Check for ACF
function sfdvhlp_check_for_acf()
{
	if (!function_exists('acf_get_field_groups')) {
		return false;
	}
	return true;
}

// Check for REST API
function sfdvhlp_check_for_rest_api()
{
	if (!apply_filters('rest_enabled', true)) {
		return false;
	}
	return true;
}

// Run requirement checks on activation
function sfdvhlp_plugin_activation_check()
{
	if (!sfdvhlp_check_for_acf()) {
		deactivate_plugins(plugin_basename(__FILE__));
		wp_die(__('Mapped requires Advanced Custom Fields', 'sfdvhlp'));
	}

	if (!sfdvhlp_check_for_rest_api()) {
		deactivate_plugins(plugin_basename(__FILE__));
		wp_die(__('My Plugin requires the WordPress REST API to be enabled!', 'sfdvhlp'));
	}
}

register_activation_hook(__FILE__, 'sfdvhlp_plugin_activation_check');


// Run requirement checks when plugin installed
function sfdvhlp_plugin_admin_notices()
{
	if (!sfdvhlp_check_for_acf()) {
		echo '<div class="error"><p>' . __('Mapped requires ACF, please activate!', 'sfdvhlp') . '</p></div>';
	}
	if (!sfdvhlp_check_for_rest_api()) {
		echo '<div class="error"><p>' . __('Mapped requires the WordPress REST API to be enabled!', 'sfdvhlp') . '</p></div>';
	}
}
add_action('admin_notices', 'sfdvhlp_plugin_admin_notices');

// Block Scripts




// Hooks
// REST Enpoint for delivering ACF data
add_action('rest_api_init', 'sfdvhlp_register_acf_fields_by_post_type_route');

function sfdvhlp_register_acf_fields_by_post_type_route()
{
	register_rest_route('sfdvhlp/v1', '/acf-fields-by-post-type/(?P<post_type>\w+)', array(
		'methods' => 'GET',
		'callback' => 'sfdvhlp_get_acf_fields_by_post_type',
		'permission_callback' => function () {
			return current_user_can('edit_posts');
		},
		'args' => array(
			'post_type' => array(
				'validate_callback' => function ($param, $request, $key) {
					return post_type_exists($param);
				}
			),
		),
	));
}
