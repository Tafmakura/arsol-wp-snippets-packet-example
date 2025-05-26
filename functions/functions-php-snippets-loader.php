<?php
/**
 * PHP Snippets Loader for Arsol WP Snippets
 * 
 * This file demonstrates how to add PHP files to your WordPress site using the Arsol WP Snippets plugin.
 * 
 * Available options for PHP files:
 * - name: Display name of the PHP file
 * - file: Server path to the PHP file (not URL)
 * 
 * Note: We use numeric arrays ($php_options[] = array()) instead of associative arrays with custom keys
 * to avoid potential conflicts when multiple packet files are created from these examples.
 * If you need to ensure unique identification of your PHP files, consider using a prefix in the 'name' field.
 * 
 * PHP files are always loaded globally, so no context or type parameters are needed.
 */

// Example 1: Basic PHP file
add_filter('arsol_wp_snippets_php_addon_files', 'add_my_example_php');
function add_my_example_php($php_options) {
    $php_options[] = array(
        'name' => 'Example Basic PHP',
        'loading_order' => 9,
        'file' => plugin_dir_url(__FILE__) . '../snippets/php/example.php',
    );
    return $php_options;
}

// Example 2: Duplicate file path example
add_filter('arsol_wp_snippets_php_addon_files', 'add_duplicate_example_php');
function add_duplicate_example_php($php_options) {
    $php_options[] = array(
        'name' => 'Example Duplicate PHP',
        'loading_order' => 12,
        'file' => __DIR__ . '/../snippets/php/example.php',

    );
    return $php_options;
}

// Example 2: Only load for logged-in users
add_filter('arsol_wp_snippets_php_addon_files', 'add_logged_in_user_functions');
function add_logged_in_user_functions($php_options) {
    // Only add this file if user is logged in
    if (!is_user_logged_in()) {
        return $php_options;
    }

    $php_options[] = array(
        'name' => 'Example Logged In User Functions',
        'file' => __DIR__ . '/../snippets/php/logged-in-functions.php'
    );
    return $php_options;
}

// Example 3: Only load on front page
add_filter('arsol_wp_snippets_php_addon_files', 'add_front_page_functions');
function add_front_page_functions($php_options) {
    // Only add this file if we're on the front page
    if (!is_front_page()) {
        return $php_options;
    }

    $php_options[] = array(
        'name' => 'Example Front Page Functions',
        'loading_order' => 5,
        'file' => __DIR__ . '/../snippets/php/front-page-functions.php'
    );
    return $php_options;
}

// Example 4: Only load for specific user roles
add_filter('arsol_wp_snippets_php_addon_files', 'add_admin_functions');
function add_admin_functions($php_options) {
    // Only add this file if user is an administrator
    if (!current_user_can('administrator')) {
        return $php_options;
    }

    $php_options[] = array(
        'name' => 'Example Admin Functions XXXX',
        'loading_order' => 66,
        'file' => __DIR__ . '/../snippets/php/example.php'
    );
    return $php_options;
}

// Example 5: Only load during specific time period
add_filter('arsol_wp_snippets_php_addon_files', 'add_seasonal_functions');
function add_seasonal_functions($php_options) {
    // Only add this file during December
    if (date('m') !== '12') {
        return $php_options;
    }

    $php_options[] = array(
        'name' => 'Example Seasonal Functions',
        'file' => __DIR__ . '/../snippets/php/example.php'
    );
    return $php_options;
}

// Example 6: Package with included files
add_filter('arsol_wp_snippets_php_addon_files', 'add_example_package');
function add_example_package($php_options) {
    $php_options[] = array(
        'name' => 'Example Package with Includes',
        'file' => __DIR__ . '/../snippets/php/example-include.php'
    );
    return $php_options;
}