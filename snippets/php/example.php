<?php
/**
 * Example PHP file demonstrating core and dynamic functionality
 * 
 * This file shows how to structure your PHP code for the Arsol WP Snippets plugin.
 * Note: PHP files are always loaded directly from the filesystem and don't support versioning.
 */

// Core functionality - features that don't change often
class CoreFeatures {
    /**
     * Initialize core features
     */
    public static function init() {
        add_action('init', array(__CLASS__, 'register_post_types'));
        add_action('init', array(__CLASS__, 'register_taxonomies'));
    }

    /**
     * Register custom post types
     */
    public static function register_post_types() {
        // Core post type registration that rarely changes
        register_post_type('example_type', array(
            'public' => true,
            'label' => 'Example Type',
            'supports' => array('title', 'editor', 'thumbnail'),
        ));
    }

    /**
     * Register custom taxonomies
     */
    public static function register_taxonomies() {
        // Core taxonomy registration that rarely changes
        register_taxonomy('example_taxonomy', 'example_type', array(
            'label' => 'Example Taxonomy',
            'hierarchical' => true,
        ));
    }
}

// Dynamic functionality - features that change frequently
class DynamicFeatures {
    /**
     * Initialize dynamic features
     */
    public static function init() {
        add_action('wp_enqueue_scripts', array(__CLASS__, 'enqueue_dynamic_assets'));
        add_action('wp_footer', array(__CLASS__, 'output_dynamic_content'));
    }

    /**
     * Enqueue dynamic assets
     */
    public static function enqueue_dynamic_assets() {
        // Dynamic asset loading that might change based on conditions
        $dynamic_css = self::get_dynamic_css();
        wp_add_inline_style('arsol-wps-packet-example', $dynamic_css);
    }

    /**
     * Get dynamic CSS based on current conditions
     */
    private static function get_dynamic_css() {
        // Generate dynamic CSS based on current conditions
        $bg_color = get_option('dynamic_bg_color', '#ffffff');
        $text_color = get_option('dynamic_text_color', '#333333');
        
        return "
            :root {
                --dynamic-bg-color: {$bg_color};
                --dynamic-text-color: {$text_color};
            }
        ";
    }

    /**
     * Output dynamic content
     */
    public static function output_dynamic_content() {
        // Output dynamic content that might change
        $dynamic_content = self::get_dynamic_content();
        echo $dynamic_content;
    }

    /**
     * Get dynamic content based on current conditions
     */
    private static function get_dynamic_content() {
        // Generate dynamic content based on current conditions
        $user_id = get_current_user_id();
        $user_name = $user_id ? get_userdata($user_id)->display_name : 'Guest';
        
        return "<div class='dynamic-content'>Welcome, {$user_name}!</div>";
    }
}

// Initialize both core and dynamic functionality
CoreFeatures::init();
DynamicFeatures::init();
