<?php
/**
 * PHP Snippets Loader for Arsol WP Snippets
 */

// Add test.php
add_filter('arsol_wp_snippets_php_addon_files', 'add_my_test_php');
function add_my_test_php($php_options) {
    $php_options['my-test-php'] = array(
        'name' => 'My Test PHP Function',
        'file' => plugin_dir_path(__FILE__) . '../snippets/php/test.php',
    );
    return $php_options;
}