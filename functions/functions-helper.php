<?php
/**
 * Helper Functions for Arsol WP Snippets
 * 
 * This file contains helper functions used across the plugin for consistent behavior.
 */

/**
 * Get default loading order for file types
 * 
 * This function provides consistent default loading orders for different file types.
 * If no loading order is specified in the file registration, this default will be used.
 * 
 * @param string $file_type The type of file (php, css, js)
 * @return int The default loading order for the file type
 */
function arsol_wps_get_default_loading_order($file_type) {
    // Default loading order is 10 for all file types
    return 10;
}

/**
 * Ensure loading order is set in file options
 * 
 * This function ensures that the loading_order parameter is always set in file options.
 * If it's not set, it will use the default loading order for that file type.
 * 
 * @param array $file_options The file options array
 * @param string $file_type The type of file (php, css, js)
 * @return array The file options with loading_order set
 */
function arsol_wps_ensure_loading_order($file_options, $file_type) {
    if (!isset($file_options['loading_order'])) {
        $file_options['loading_order'] = arsol_wps_get_default_loading_order($file_type);
    }
    return $file_options;
} 