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
