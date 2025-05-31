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
 * - loading_order: Loading order (default: 10, lower = loads earlier)
 * - dependencies: Array of script handles that must load first
 * - version: Optional version number for caching (default: null for no caching)
 * 
 * Note: We use numeric arrays ($js_options[] = array()) instead of associative arrays with custom keys
 * to avoid potential conflicts when multiple packet files are created from these examples.
 * If you need to ensure unique identification of your scripts, consider using a prefix in the 'name' field.
 */

// Example 1: Versioned JavaScript file (will be cached)
add_filter('arsol_wp_snippets_js_addon_files', 'add_versioned_js');
function add_versioned_js($js_options) {
    $js_options[] = array(
        'name' => 'Example Versioned Core Script',
        'file' => plugin_dir_url(__FILE__) . '../snippets/js/core-script.js',
        'context' => 'frontend',
        'position' => 'footer',
        'loading_order' => 10,
        'version' => '1.0.0', // This file will be cached with this version
        'dependencies' => array('jquery')
    );
    return $js_options;
}
