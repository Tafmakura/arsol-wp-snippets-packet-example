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
    $user_id = is_user_logged_in() ? get_current_user_id() : 0;
    $has_sub = false;
    $has_sub_onhold = false;
    $is_renewal = false;
    $is_renewal_of_onhold_sub = false;
    $subs_switch = false;

    if ( function_exists( 'wcs_user_has_subscription' ) && $user_id ) {
        // Check for active or pending-cancel subscriptions
        $has_sub = wcs_user_has_subscription( $user_id, '', array( 'active', 'pending-cancel' ) );
        $has_sub_onhold = wcs_user_has_subscription( $user_id, '', array( 'on-hold' ) );

        // Check for renewal and switch using WooCommerce Subscriptions functions
        $is_renewal = wcs_is_subscription_renewal();
        $subs_switch = wcs_is_subscription_switch();

        // Check if renewal is for an on-hold subscription
        if ( $is_renewal && isset( $_GET['subscription_renewal'] ) ) {
            $subscription = wcs_get_subscription( $_GET['subscription_renewal'] );
            if ( $subscription && $subscription->has_status( 'on-hold' ) ) {
                $is_renewal_of_onhold_sub = true;
            }
        }
    }

    // BLOCK: User has an active sub but is not switching or renewing properly
    if ( $has_sub && ( ! $is_renewal || ( ! $subs_switch && ! is_checkout() ) ) ) {
        wc_add_notice( __("Our records show that you have an active WooPOS subscription. Please ensure that this payment is for switching or renewing your existing subscription.", "so-additions"), 'error' );
        return false;
    }

    // BLOCK: User has a sub on hold but is not renewing it
    if ( $has_sub_onhold ) {
        if ( $is_renewal_of_onhold_sub ) {
            wc_add_notice( __("This payment will renew your on-hold subscription.", "so-additions"), 'notice' );
            return true;
        } else {
            wc_add_notice( __("You have a subscription on hold. Please renew it instead of creating a new one.", "so-additions"), 'error' );
            return false;
        }
    }

    return true; // Default: allow adding to cart
}
add_filter( 'woocommerce_add_to_cart_validation', 'woopos_enforce_one_sub', 10, 2 );
