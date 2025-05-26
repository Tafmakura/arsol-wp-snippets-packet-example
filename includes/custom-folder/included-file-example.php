<?php

/**
 * Example included file with shortcode functionality
 * 
 * This shortcode demonstrates a complex example of content generation and styling:
 * 1. Content is generated through PHP functions in included files
 * 2. Styling is applied through a nested CSS file added via a separate filter
 * 3. The CSS can be disabled granularly, allowing for complex combinations
 * 4. Multiple included files can work together to create rich functionality
 * 5. HTML templates can be loaded from external files for better organization
 * 
 * The shortcode loads its HTML structure from an external template file,
 * which is then processed to include dynamic content. This separation
 * allows for easier maintenance and modification of the HTML structure
 * without touching the PHP code.
 * 
 * @return string The processed HTML output
 */

// Register the shortcode
add_shortcode('arsol-wps-packet-example', 'arsol_wps_packet_example_shortcode');

function arsol_wps_packet_example_shortcode() {
    // Define the path to the HTML template file
    $template_path = __DIR__ . '/included-assets-folder/included-template-file.html';
    
    // Check if the template file exists
    if (!file_exists($template_path)) {
        return '<p>Error: Template file not found at ' . esc_html($template_path) . '</p>';
    }
    
    // Load and return the template file
    return file_get_contents($template_path);
}

/**
 * Register CSS styles for the shortcode
 * 
 * This filter demonstrates how to add CSS styles to your shortcode output.
 * The styles are loaded through the Arsol WP Snippets plugin's CSS management system,
 * which provides several benefits:
 * 
 * 1. Granular Control:
 *    - Styles can be enabled/disabled independently of the shortcode
 *    - Multiple CSS files can be managed separately
 *    - Dependencies can be specified to ensure proper loading order
 * 
 * 2. Context Awareness:
 *    - Styles can be loaded only in specific contexts (frontend/admin)
 *    - Loading order can be controlled to ensure proper cascade
 *    - Dependencies on other stylesheets can be managed
 * 
 * 3. Modularity:
 *    - CSS is separated from PHP logic
 *    - Styles can be modified without touching the shortcode
 *    - Multiple shortcodes can share the same styles
 * 
 * 4. Performance:
 *    - Styles are loaded only when needed
 *    - Proper dependency management prevents conflicts
 *    - Loading order can be optimized
 * 
 * The CSS file is located in the included-assets-folder and contains styles
 * for the HTML template loaded by the shortcode. This separation allows for
 * easier maintenance and modification of styles without affecting the shortcode
 * functionality.
 * 
 * @param array $css_options Array of CSS file options to be processed by the plugin
 * @return array Modified array of CSS file options
 */
add_filter('arsol_wp_snippets_css_addon_files', 'arsol_wps_packet_example_css');

function arsol_wps_packet_example_css($css_options) {
    $css_options[] = array(
        'name' => 'Example Nested CSS with Granular Control',
        'file' => plugin_dir_url(__FILE__) . 'included-assets-folder/included-css-folder/included-asset-file.css',
        'context' => 'frontend',
        'loading_order' => 10,
        'dependencies' => array()
    );
    return $css_options;
}
