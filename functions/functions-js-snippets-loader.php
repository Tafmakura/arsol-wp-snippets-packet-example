<?php
/**
 * JavaScript Snippets Loader for Arsol WP Snippets
 * 
 * This file demonstrates how to add JavaScript files to your WordPress site using the Arsol WP Snippets plugin.
 * 
 * Available options for JavaScript files:
 * - name: Display name of the script
 * - file: URL path to the JS file
 * - context: 'frontend', 'admin', or 'global'
 * - position: 'header' or 'footer' (default: 'footer')
 * - priority: Loading order (default: 10, lower = loads earlier)
 * - dependencies: Array of script handles that must load first
 * 
 * Note: We use numeric arrays ($js_options[] = array()) instead of associative arrays with custom keys
 * to avoid potential conflicts when multiple packet files are created from these examples.
 * If you need to ensure unique identification of your scripts, consider using a prefix in the 'name' field.
 */

// Example 1: Basic JavaScript file
add_filter('arsol_wp_snippets_js_addon_files', 'add_my_example_js');
function add_my_example_js($js_options) {
    $js_options[] = array(
        'name' => 'My Example JS file',
        'file' => plugin_dir_url(__FILE__) . '../snippets/js/example.js',
        'context' => 'frontend',
        'position' => 'footer',
        'priority' => 10,
        'dependencies' => array('jquery')
    );
    return $js_options;
}

// Example 2: Header-loaded script
add_filter('arsol_wp_snippets_js_addon_files', 'add_header_script');
function add_header_script($js_options) {
    $js_options[] = array(
        'name' => 'Header Script',
        'file' => plugin_dir_url(__FILE__) . '../snippets/js/header-script.js',
        'context' => 'frontend',
        'position' => 'header',
        'priority' => 5,
        'dependencies' => array()
    );
    return $js_options;
}

// Example 3: Admin-only script
add_filter('arsol_wp_snippets_js_addon_files', 'add_admin_script');
function add_admin_script($js_options) {
    $js_options[] = array(
        'name' => 'Admin Script',
        'file' => plugin_dir_url(__FILE__) . '../snippets/js/admin-script.js',
        'context' => 'admin',
        'position' => 'footer',
        'priority' => 15,
        'dependencies' => array('jquery', 'wp-element')
    );
    return $js_options;
}

// Example 4: Page-specific script (loaded globally but conditionally executed)
add_filter('arsol_wp_snippets_js_addon_files', 'add_page_specific_script');
function add_page_specific_script($js_options) {
    $js_options[] = array(
        'name' => 'Page Specific Script',
        'file' => plugin_dir_url(__FILE__) . '../snippets/js/page-specific-script.js',
        'context' => 'global', // Load globally but conditionally executed
        'position' => 'footer',
        'priority' => 10,
        'dependencies' => array('jquery')
    );
    return $js_options;
}