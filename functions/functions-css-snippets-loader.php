<?php
/**
 * CSS Snippets Loader for Arsol WP Snippets
 * 
 * This file demonstrates how to add CSS files to your WordPress site using the Arsol WP Snippets plugin.
 * 
 * Available options for CSS files:
 * - name: Display name of the stylesheet
 * - file: URL path to the CSS file
 * - context: 'frontend', 'admin', or 'global'
 * - loading_order: Loading order (default: 10, higher = loads later)
 * - dependencies: Array of style handles that must load first
 * 
 * Note: We use numeric arrays ($css_options[] = array()) instead of associative arrays with custom keys
 * to avoid potential conflicts when multiple packet files are created from these examples.
 * If you need to ensure unique identification of your styles, consider using a prefix in the 'name' field.
 */

// Example 1: Basic CSS file
add_filter('arsol_wp_snippets_css_addon_files', 'add_my_example_css');
function add_my_example_css($css_options) {
    $css_options[] = array(
        'name' => 'My Example CSS file',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/example.css',
        'context' => 'frontend',
        'loading_order' => 20,
        'dependencies' => array(
            'wp-block-library',     // WordPress core block styles
            'my-base-styles'        // Another custom stylesheet
        )
    );
    return $css_options;
}

// Example 2: Only load for logged-in users
add_filter('arsol_wp_snippets_css_addon_files', 'add_logged_in_user_styles');
function add_logged_in_user_styles($css_options) {
    // Only add this file if user is logged in
    if (!is_user_logged_in()) {
        return $css_options;
    }

    $css_options[] = array(
        'name' => 'Logged In User Styles',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/logged-in-styles.css',
        'context' => 'frontend',
        'loading_order' => 15,
        'dependencies' => array()
    );
    return $css_options;
}

// Example 3: Only load on mobile devices
add_filter('arsol_wp_snippets_css_addon_files', 'add_mobile_styles');
function add_mobile_styles($css_options) {
    // Only add this file if user is on a mobile device
    if (!wp_is_mobile()) {
        return $css_options;
    }

    $css_options[] = array(
        'name' => 'Mobile Styles',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/mobile-styles.css',
        'context' => 'frontend',
        'loading_order' => 10,
        'dependencies' => array()
    );
    return $css_options;
}

// Example 4: Only load for specific user roles
add_filter('arsol_wp_snippets_css_addon_files', 'add_premium_user_styles');
function add_premium_user_styles($css_options) {
    // Only add this file if user has premium role
    if (!current_user_can('premium_member')) {
        return $css_options;
    }

    $css_options[] = array(
        'name' => 'Premium User Styles',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/premium-styles.css',
        'context' => 'frontend',
        'loading_order' => 25,
        'dependencies' => array()
    );
    return $css_options;
}

// Example 5: Only load during specific time period
add_filter('arsol_wp_snippets_css_addon_files', 'add_seasonal_styles');
function add_seasonal_styles($css_options) {
    // Only add this file during December
    if (date('m') !== '12') {
        return $css_options;
    }

    $css_options[] = array(
        'name' => 'Seasonal Styles',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/seasonal-styles.css',
        'context' => 'frontend',
        'loading_order' => 30,
        'dependencies' => array()
    );
    return $css_options;
}