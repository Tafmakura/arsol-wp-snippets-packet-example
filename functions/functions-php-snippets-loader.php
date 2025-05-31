<?php
/**
 * PHP Snippets Loader for Arsol WP Snippets
 * 
 * This file demonstrates how to add PHP files to your WordPress site using the Arsol WP Snippets plugin.
 * 
 * Available options for PHP files:
 * - name: Display name of the PHP file
 * - file: Server path to the PHP file (not URL)
 * - loading_order: Loading order (default: 10, lower = loads earlier)
 * 
 * Note: We use numeric arrays ($php_options[] = array()) instead of associative arrays with custom keys
 * to avoid potential conflicts when multiple packet files are created from these examples.
 * If you need to ensure unique identification of your PHP files, consider using a prefix in the 'name' field.
 * 
 * PHP files are always loaded directly from the filesystem and don't support versioning.
 * They are always loaded fresh, which is why there is no version parameter.
 */

// Example 1: Basic PHP file
add_filter('arsol_wp_snippets_php_addon_files', 'add_my_example_php');
function add_my_example_php($php_options) {
    $php_options[] = array(
        'name' => 'SaaS for Woo Subscriptions',
        'file' => __DIR__ . '/../snippets/php/example.php',
        'loading_order' => 10
    );
    return $php_options;
}

// Example 6: Package with included files
add_filter('arsol_wp_snippets_php_addon_files', 'add_example_package');
function add_example_package($php_options) {
    $php_options[] = array(
        'name' => 'Example Package with Includes',
        'file' => __DIR__ . '/../snippets/php/example-include.php',
        'loading_order' => 25
    );
    return $php_options;
}