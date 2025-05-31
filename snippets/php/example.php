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
    $order_id = 0;
    $current_order_related_subscription_id = 0;

    if ( function_exists( 'wcs_user_has_subscription' ) && $user_id ) {
        // Check for active or pending-cancel subscriptions
        $has_sub = wcs_user_has_subscription( $user_id, '', array( 'active', 'pending-cancel' ) );
        
        // Get on-hold subscription first
        $on_hold_subs = wcs_get_users_subscriptions( $user_id, array( 'status' => 'on-hold' ) );
        $has_sub_onhold = ! empty( $on_hold_subs );
        
        if ( $has_sub_onhold ) {
            $parent_subscription_id = key( $on_hold_subs ); // Get the first on-hold subscription ID
        }

        // Try to get the order ID from the cart (pending order)
        if ( WC()->session && WC()->session->get( 'order_awaiting_payment' ) ) {
            $order_id = WC()->session->get( 'order_awaiting_payment' );
        }

        // If we have both an on-hold subscription and an order, check if the order is for that subscription
        if ( $has_sub_onhold && $order_id ) {
            $subscriptions = wcs_get_subscriptions_for_order( $order_id );
            $order_subscription_ids = array_keys( $subscriptions );
            if ( in_array( $parent_subscription_id, $order_subscription_ids ) ) {
                $is_renewal_of_onhold_sub = true;
            }
            // Store the first related subscription ID for messaging
            $current_order_related_subscription_id = !empty($order_subscription_ids) ? $order_subscription_ids[0] : 0;
        }
    }

    // BLOCK: User has an active sub but is not switching or renewing properly
    if ( $has_sub && ( ! $is_renewal_of_onhold_sub || ( ! $subs_switch && ! is_checkout() ) ) ) {
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
                __("You have a subscription on hold (ID: %d). Please renew it instead of creating a new one. Current order related subscription: %d. Current order number: %d", "so-additions"),
                $parent_subscription_id,
                $current_order_related_subscription_id,
                $order_id
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
