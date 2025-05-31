<?php 
/* ===============================================================
 * LOGIC
 * ===============================================================
 */

// Change "Select Options" label on button 
add_filter( 'woocommerce_product_add_to_cart_text', function( $text, $product = null ) {
	// If no product is passed, try to get it from global
	if ( ! $product ) {
		global $product;
	}
	
	// If still no product, return original text
	if ( ! $product || ! is_object( $product ) ) {
		return $text;
	}
	
	if ( $product->is_type( 'variable' ) ) {
		$text = $product->is_purchasable() ? __( 'View options', 'woocommerce' ) : __( 'View options', 'woocommerce' );
	}
	return $text;
}, 10, 2 );

