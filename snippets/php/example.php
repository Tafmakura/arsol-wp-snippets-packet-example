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
    $has_sub_onhold = false;
    $is_renewal_of_onhold_sub = false;
    $subs_switch = false;
    $parent_subscription_id = 0;

    if ( function_exists( 'wcs_user_has_subscription' ) && $user_id ) {
        // Check for active or pending-cancel subscriptions
        $has_sub = wcs_user_has_subscription( $user_id, '', array( 'active', 'pending-cancel' ) );
        
        // Get on-hold subscription first
        $on_hold_subs = wcs_get_users_subscriptions( $user_id, array( 'status' => 'on-hold' ) );
        $has_sub_onhold = ! empty( $on_hold_subs );
        
        if ( $has_sub_onhold ) {
            $parent_subscription_id = key( $on_hold_subs ); // Get the first on-hold subscription ID
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

    // BLOCK: User has an active sub but is not switching or renewing properly
    if ( $has_sub && ( ! $is_renewal_of_onhold_sub || ( ! $subs_switch && ! is_checkout() ) ) ) {
        wc_add_notice( __("Our records show that you have an active WooPOS subscription. Please ensure that this payment is for switching or renewing your existing subscription.", "so-additions"), 'error' );
        return false;
    }

    // BLOCK: User has a sub on hold but is not renewing it
    if ( $has_sub_onhold ) {
        if ( $order_type === 'renewal order' || $order_type === 'switch order' ) {
            wc_add_notice( sprintf(
                __("(Subscription #%d)", "so-additions"),
                $parent_subscription_id
            ), 'notice' );
            return true;
        } else {
            wc_add_notice( sprintf(
                __("You have a subscription on hold (ID: %d). Please renew it instead of creating a new one.", "so-additions"),
                $parent_subscription_id,
            ), 'error' );
            return false;
        }
    }

    return true; // Default: allow adding to cart
}
add_filter( 'woocommerce_add_to_cart_validation', 'woopos_enforce_one_sub', 10, 2 );


