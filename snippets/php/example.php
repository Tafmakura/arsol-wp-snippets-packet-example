<?php
/**
 * Example PHP file demonstrating core and dynamic functionality
 * 
 * This file shows how to structure your PHP code for the Arsol WP Snippets plugin.
 * Note: PHP files are always loaded directly from the filesystem and don't support versioning.
 */




 // (Core) WooCommerce - Only One product in the cart at a time.

 add_filter( 'woocommerce_add_cart_item_data', 'single_item_add_to_cart' );
 function single_item_add_to_cart( $cart_single_item ) {
 
     global $woocommerce;
     $woocommerce->cart->empty_cart();
 
     return $cart_single_item;
 }
 
 

 // (Core) WooCommerce Subscriptions - One subscription validation and redirect

 function woopos_enforce_one_sub( $passed, $product_id ) {
    // Check if WooCommerce Subscriptions is active
    if ( ! class_exists( 'WC_Subscriptions' ) ) {
        return $passed;
    }

    $user_id = is_user_logged_in() ? get_current_user_id() : 0;
    $has_sub = false;
    $subscription_id = 0;

    if ( function_exists( 'wcs_user_has_subscription' ) && $user_id ) {
        // Check for active, on-hold, pending, processing, or pending-cancel subscriptions
        $has_sub = wcs_user_has_subscription( $user_id, '', array( 'active', 'on-hold', 'pending', 'processing', 'pending-cancel' ) );
        
        // Get the subscription ID if one exists
        if ($has_sub) {
            $subscriptions = wcs_get_users_subscriptions($user_id);
            if (!empty($subscriptions)) {
                $subscription_id = key($subscriptions);
            }
        }
    }

    // Detect order type using URL attributes and cart functions
    $order_type = 'new order';
    if ( isset($_GET['subscription_renewal']) && $_GET['subscription_renewal'] == 'true' ) {
        $order_type = 'renewal order';
    } elseif ( isset($_GET['subscription_switch']) && $_GET['subscription_switch'] == 'true' ) {
        $order_type = 'switch order';
    } elseif ( function_exists( 'wcs_cart_contains_renewal' ) && wcs_cart_contains_renewal() ) {
        $order_type = 'renewal order';
    } elseif ( function_exists( 'wcs_cart_contains_switch' ) && wcs_cart_contains_switch() ) {
        $order_type = 'switch order';
    }

    // Add notice for renewal or switch orders
    if ( ($order_type === 'renewal order' || $order_type === 'switch order') && $subscription_id ) {
        wc_add_notice( sprintf(
            __("(subscription #%d)", "so-additions"),
            $subscription_id
        ), 'notice' );
    }

    // BLOCK: User has an active subscription but is not switching or renewing
    if ( $has_sub && $order_type === 'new order' ) {
        wc_add_notice( sprintf(
            __("You have an existing subscription (ID: %d). Please renew, upgrade or downgrade that subscription instead of creating a new one.", "so-additions"),
            $subscription_id
        ), 'error' );
        return false;
    }

    return true; // Default: allow adding to cart
}
add_filter( 'woocommerce_add_to_cart_validation', 'woopos_enforce_one_sub', 10, 2 );


