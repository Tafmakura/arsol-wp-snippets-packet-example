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
 * 
 * Note: We use numeric arrays ($js_options[] = array()) instead of associative arrays with custom keys
 * to avoid potential conflicts when multiple packet files are created from these examples.
 * If you need to ensure unique identification of your scripts, consider using a prefix in the 'name' field.
 */

// Example 1: Basic JavaScript file
add_filter('arsol_wp_snippets_js_addon_files', 'add_my_example_js');
function add_my_example_js($js_options) {
    $js_options[] = array(
        'name' => 'Example Basic JS',
        'file' => plugin_dir_url(__FILE__) . '../snippets/js/example.js',
        'context' => 'frontend',
        'position' => 'footer',
        'loading_order' => 10,
        'dependencies' => array('jquery')
    );
    return $js_options;
}

// Example 2: Header-loaded script
add_filter('arsol_wp_snippets_js_addon_files', 'add_header_script');
function add_header_script($js_options) {
    $js_options[] = array(
        'name' => 'Example Header Script',
        'file' => plugin_dir_url(__FILE__) . '../snippets/js/header-script.js',
        'context' => 'frontend',
        'position' => 'header',
        'loading_order' => 5,
        'dependencies' => array()
    );
    return $js_options;
}

// Example 3: Admin-only script
add_filter('arsol_wp_snippets_js_addon_files', 'add_admin_script');
function add_admin_script($js_options) {
    $js_options[] = array(
        'name' => 'Example Admin Script',
        'file' => plugin_dir_url(__FILE__) . '../snippets/js/admin-script.js',
        'context' => 'admin',
        'position' => 'footer',
        'loading_order' => 15,
        'dependencies' => array('jquery', 'wp-element')
    );
    return $js_options;
}

// Example 4: Page-specific script (loaded globally but conditionally executed)
add_filter('arsol_wp_snippets_js_addon_files', 'add_page_specific_script');
function add_page_specific_script($js_options) {
    $js_options[] = array(
        'name' => 'Example Page Specific Script',
        'file' => plugin_dir_url(__FILE__) . '../snippets/js/page-specific-script.js',
        'context' => 'global', // Load globally but conditionally executed
        'position' => 'footer',
        'loading_order' => 10,
        'dependencies' => array('jquery')
    );
    return $js_options;
}

// Example 5: Only load for logged-in users
add_filter('arsol_wp_snippets_js_addon_files', 'add_logged_in_user_script');
function add_logged_in_user_script($js_options) {
    // Only add this file if user is logged in
    if (!is_user_logged_in()) {
        return $js_options;
    }

    $js_options[] = array(
        'name' => 'Example Logged In User Script',
        'file' => plugin_dir_url(__FILE__) . '../snippets/js/logged-in-script.js',
        'context' => 'frontend',
        'position' => 'footer',
        'loading_order' => 15,
        'dependencies' => array('jquery')
    );
    return $js_options;
}

// Example 6: Only load on mobile devices
add_filter('arsol_wp_snippets_js_addon_files', 'add_mobile_script');
function add_mobile_script($js_options) {
    // Only add this file if user is on a mobile device
    if (!wp_is_mobile()) {
        return $js_options;
    }

    $js_options[] = array(
        'name' => 'Example Mobile Script',
        'file' => plugin_dir_url(__FILE__) . '../snippets/js/mobile-script.js',
        'context' => 'frontend',
        'position' => 'footer',
        'loading_order' => 5,
        'dependencies' => array('jquery')
    );
    return $js_options;
}

// Example 7: Only load for specific user roles
add_filter('arsol_wp_snippets_js_addon_files', 'add_premium_user_script');
function add_premium_user_script($js_options) {
    // Only add this file if user has premium role
    if (!current_user_can('premium_member')) {
        return $js_options;
    }

    $js_options[] = array(
        'name' => 'Example Premium User Script',
        'file' => plugin_dir_url(__FILE__) . '../snippets/js/premium-script.js',
        'context' => 'frontend',
        'position' => 'footer',
        'loading_order' => 20,
        'dependencies' => array('jquery')
    );
    return $js_options;
}

// Example 8: Only load during specific time period
add_filter('arsol_wp_snippets_js_addon_files', 'add_seasonal_script');
function add_seasonal_script($js_options) {
    // Only add this file during December
    if (date('m') !== '12') {
        return $js_options;
    }

    $js_options[] = array(
        'name' => 'Example Seasonal Script',
        'file' => plugin_dir_url(__FILE__) . '../snippets/js/seasonal-script.js',
        'context' => 'frontend',
        'position' => 'footer',
        'loading_order' => 25,
        'dependencies' => array('jquery')
    );
    return $js_options;
}