<?php
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

require_once ABSPATH . 'wp-load.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

// wp eval-file wp-content/plugins/sf-dev-helpers/import-csv.php C:\Users\...\..\base-site\app\sample-pojects.csv


// Include ACF functions
// if (function_exists('acf_add_post_meta')) {
//     require_once ABSPATH . 'wp-content/plugins/advanced-custom-fields-pro/includes/api/api-helpers.php';
// }

// Check if the script is being run via WP-CLI and if a CSV file path argument is provided
$csv_file = 'C:\Users\nateg\Local Sites\base-site-conductor-power\app\public\wp-content\plugins\sf-dev-helpers\sample-projects.csv';

if (file_exists($csv_file)) {
    // Read the CSV file
    $csv_data = array_map('str_getcsv', file($csv_file));

    // Skip the header row (if it exists)
    $header = array_shift($csv_data);

    // Loop through the CSV data and create posts
    foreach ($csv_data as $row) {
        $post_title = $row[0]; // Adjust column index as needed

        // Create a new post
        $post_id = wp_insert_post(array(
            'post_title' => $post_title,
            'post_type' => 'project',
            'post_status' => 'publish',
        ));

        // Check if ACF fields are available
        if (function_exists('update_field')) {
            // Add ACF fields here
            update_field('latitude', $row[1], $post_id); // Adjust field name and column index
            update_field('longitude', $row[2], $post_id);
        }

        if (!is_wp_error($post_id)) {
            echo "Imported post: {$post_title}\n";
            
            // Assign a taxonomy term to the post
            $term_name = $row[3]; // Adjust column index for taxonomy term
            $taxonomy = 'project-type'; // Adjust to your custom taxonomy name

            // Check if the term exists, and if not, create it
            $term = term_exists($term_name, $taxonomy);

            if (!$term) {
                $term = wp_insert_term($term_name, $taxonomy);
                if (!is_wp_error($term)) {
                    $term_id = $term['term_id'];
                }
            } else {
                $term_id = $term['term_id'];
            }

            // Assign the term to the post
            if (!is_wp_error($term_id)) {
                wp_set_post_terms($post_id, $term_id, $taxonomy);
                echo "Assigned term '{$term_name}' to post '{$post_title}'\n";
            } else {
                echo "Error assigning term '{$term_name}' to post '{$post_title}'\n";
            }
        } else {
            echo "Error importing post: {$post_title}\n";
        }
    } // Close the foreach loop here
} else {
    echo "CSV file not found.\n";
}