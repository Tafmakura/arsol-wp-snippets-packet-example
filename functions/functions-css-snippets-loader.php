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
 * - version: Optional version number for caching (default: null for no caching)
 * 
 * Note: We use numeric arrays ($css_options[] = array()) instead of associative arrays with custom keys
 * to avoid potential conflicts when multiple packet files are created from these examples.
 * If you need to ensure unique identification of your styles, consider using a prefix in the 'name' field.
 */

// Example 1: Versioned CSS file (will be cached)
add_filter('arsol_wp_snippets_css_addon_files', 'add_versioned_css');
function add_versioned_css($css_options) {
    $css_options[] = array(
        'name' => 'Example Versioned Core Styles',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/core-styles.css',
        'context' => 'frontend',
        'loading_order' => 10,
        'version' => '1.0.0', // This file will be cached with this version
        'dependencies' => array('wp-block-library')
    );
    return $css_options;
}

// Example 2: Non-versioned CSS file (always fresh)
add_filter('arsol_wp_snippets_css_addon_files', 'add_dynamic_css');
function add_dynamic_css($css_options) {
    $css_options[] = array(
        'name' => 'Example Dynamic Styles',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/dynamic-styles.css',
        'context' => 'frontend',
        'loading_order' => 20,
        // No version specified, so it won't be cached
        'dependencies' => array('wp-block-library')
    );
    return $css_options;
}

// Example 3: Only load versioned styles for logged-in users
add_filter('arsol_wp_snippets_css_addon_files', 'add_logged_in_user_styles');
function add_logged_in_user_styles($css_options) {
    // Only add this file if user is logged in
    if (!is_user_logged_in()) {
        return $css_options;
    }

    $css_options[] = array(
        'name' => 'Example Logged In User Styles',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/logged-in-styles.css',
        'context' => 'frontend',
        'loading_order' => 15,
        'version' => '1.0.0', // Versioned because these styles rarely change
        'dependencies' => array()
    );
    return $css_options;
}

// Example 4: Only load non-versioned styles for mobile devices
add_filter('arsol_wp_snippets_css_addon_files', 'add_mobile_styles');
function add_mobile_styles($css_options) {
    // Only add this file if user is on a mobile device
    if (!wp_is_mobile()) {
        return $css_options;
    }

    $css_options[] = array(
        'name' => 'Example Mobile Styles',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/mobile-styles.css',
        'context' => 'frontend',
        'loading_order' => 10,
        // No version specified because these styles might change based on device
        'dependencies' => array()
    );
    return $css_options;
}

// Example 5: Only load versioned styles for specific user roles
add_filter('arsol_wp_snippets_css_addon_files', 'add_premium_user_styles');
function add_premium_user_styles($css_options) {
    // Only add this file if user has premium role
    if (!current_user_can('premium_member')) {
        return $css_options;
    }

    $css_options[] = array(
        'name' => 'Example Premium User Styles',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/premium-styles.css',
        'context' => 'frontend',
        'loading_order' => 25,
        'version' => '1.0.0', // Versioned because these styles are stable
        'dependencies' => array()
    );
    return $css_options;
}