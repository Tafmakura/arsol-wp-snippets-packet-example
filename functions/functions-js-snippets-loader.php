<?php
/**
 * JavaScript Snippets Loader for Arsol WP Snippets
 */

// Add test.js
add_filter('arsol_wp_snippets_js_addon_files', 'add_my_test_js');
function add_my_test_js($js_options) {
    $js_options['my-test-js'] = array(
        'name' => 'My Test JavaScript Script',
        'file' => plugin_dir_url(__FILE__) . '../snippets/js/test.js',
        'context' => 'frontend',
        'position' => 'footer'
    );
    return $js_options;
}