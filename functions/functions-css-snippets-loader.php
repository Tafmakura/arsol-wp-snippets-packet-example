<?php
/**
 * CSS Snippets Loader for Arsol WP Snippets
 */

// Add test.css
add_filter('arsol_wp_snippets_css_addon_files', 'add_my_test_css');
function add_my_test_css($css_options) {
    $css_options['my-test-css'] = array(
        'name' => 'My Test CSS file',
        'file' => plugin_dir_url(__FILE__) . '../snippets/css/test.css',
        'context' => 'frontend',
        'position' => 'header'
    );
    return $css_options;
}