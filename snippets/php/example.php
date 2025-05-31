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

        // Get subscription related to current order
        if ( WC()->cart ) {
            foreach ( WC()->cart->get_cart() as $cart_item ) {
                // Check for subscription meta in cart item
                if ( isset( $cart_item['subscription_renewal'] ) ) {
                    $current_order_related_subscription_id = $cart_item['subscription_renewal'];
                    break;
                } elseif ( isset( $cart_item['subscription_parent_id'] ) ) {
                    $current_order_related_subscription_id = $cart_item['subscription_parent_id'];
                    break;
                } elseif ( isset( $cart_item['subscription_switch'] ) ) {
                    $current_order_related_subscription_id = $cart_item['subscription_switch'];
                    break;
                }
            }
        }

        // If we found a subscription ID, check if it's related to our on-hold subscription
        if ( $current_order_related_subscription_id ) {
            $subscription = wcs_get_subscription( $current_order_related_subscription_id );
            if ( $subscription ) {
                // Check if this subscription is related to our on-hold subscription
                if ( $subscription->get_parent_id() == $parent_subscription_id || 
                     $subscription->get_id() == $parent_subscription_id ) {
                    $is_renewal = true;
                    $is_renewal_of_onhold_sub = true;
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
                $current_order_related_subscription_id
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
