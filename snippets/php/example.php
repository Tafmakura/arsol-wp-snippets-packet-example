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
    $is_renewal = false;
    $is_renewal_of_onhold_sub = false;
    $subs_switch = false;
    $parent_subscription_id = 0;
    $current_subscription_id = 0;

    if ( function_exists( 'wcs_user_has_subscription' ) && $user_id ) {
        // Check for active or pending-cancel subscriptions
        $has_sub = wcs_user_has_subscription( $user_id, '', array( 'active', 'pending-cancel' ) );
        $has_sub_onhold = wcs_user_has_subscription( $user_id, '', array( 'on-hold' ) );

        // First try: Check for renewal and switch using WooCommerce Subscriptions functions
        if ( function_exists( 'wcs_cart_contains_renewal' ) ) {
            $is_renewal = wcs_cart_contains_renewal();
        }
        
        if ( function_exists( 'wcs_cart_contains_switch' ) ) {
            $subs_switch = wcs_cart_contains_switch();
        }

        // Second try: Check URL parameter
        if ( ! $is_renewal && isset( $_GET['subscription_renewal'] ) && wcs_is_subscription( $_GET['subscription_renewal'] ) ) {
            $is_renewal = true;
            $current_subscription_id = $_GET['subscription_renewal'];
            $subscription = wcs_get_subscription( $current_subscription_id );
            if ( $subscription && $subscription->has_status( 'on-hold' ) ) {
                $is_renewal_of_onhold_sub = true;
                $parent_subscription_id = $subscription->get_parent_id();
            }
        }

        // Fallback: Thorough check if still not identified as renewal
        if ( ! $is_renewal && $has_sub_onhold ) {
            // Get all on-hold subscriptions
            $on_hold_subs = wcs_get_users_subscriptions( $user_id, array( 'status' => 'on-hold' ) );
            
            if ( WC()->cart ) {
                foreach ( WC()->cart->get_cart() as $cart_item ) {
                    // Check for renewal meta in cart item
                    if ( isset( $cart_item['subscription_renewal'] ) ) {
                        $current_subscription_id = $cart_item['subscription_renewal'];
                        $subscription = wcs_get_subscription( $current_subscription_id );
                        if ( $subscription && $subscription->has_status( 'on-hold' ) ) {
                            $is_renewal = true;
                            $is_renewal_of_onhold_sub = true;
                            $parent_subscription_id = $subscription->get_parent_id();
                            break;
                        }
                    }
                    
                    // Check for parent subscription ID
                    if ( isset( $cart_item['subscription_parent_id'] ) ) {
                        $parent_subscription_id = $cart_item['subscription_parent_id'];
                        $subscription = wcs_get_subscription( $parent_subscription_id );
                        if ( $subscription && $subscription->has_status( 'on-hold' ) ) {
                            $is_renewal = true;
                            $is_renewal_of_onhold_sub = true;
                            $current_subscription_id = $subscription->get_id();
                            break;
                        }
                    }
                }
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
            wc_add_notice( sprintf( 
                __("You have a subscription on hold (ID: %d). Please renew it instead of creating a new one. Current subscription ID: %d", "so-additions"),
                $parent_subscription_id,
                $current_subscription_id
            ), 'error' );
            return false;
        }
    }

    return true; // Default: allow adding to cart
}
add_filter( 'woocommerce_add_to_cart_validation', 'woopos_enforce_one_sub', 10, 2 );

// Add subscription actions for on-hold subscriptions
function woopos_add_subscription_actions( $actions, $subscription ) {
    if ( $subscription->has_status( 'on-hold' ) ) {
        $actions['renew'] = array(
            'url'  => wp_nonce_url( add_query_arg( array( 'subscription_renewal' => $subscription->get_id() ), wc_get_cart_url() ), 'wcs_renew_subscription' ),
            'name' => __( 'Renew Subscription', 'woocommerce-subscriptions' ),
        );
    }
    return $actions;
}
add_filter( 'wcs_view_subscription_actions', 'woopos_add_subscription_actions', 10, 2 );
