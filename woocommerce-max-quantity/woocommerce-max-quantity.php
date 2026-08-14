<?php
/**
 * Plugin Name:          Maximum, Minimum and Multiple Quantity for WooCommerce Shops
 * Plugin URI:
 * Description:          Set a maximum and/or minimum quantity limit, and/or force a "sell in multiples of" quantity, for the WooCommerce cart, globally or per product.
 * Version:              3.0
 * Author:               Naked Cat Plugins (by Webdados)
 * Author URI:           https://nakedcatplugins.com
 * Text Domain:          woocommerce-max-quantity
 * Requires at least:    5.9
 * Tested up to:         7.0
 * Requires PHP:         7.2
 * WC requires at least: 7.3
 * WC tested up to:      10.8
 * Requires Plugins:     woocommerce
 * License:              GPLv3
 */

namespace PTWooPlugins\MaxQuantityWC;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Load plugin
 *
 * @since 2.0
 * @return void
 */
function init() {
	// Load textdomain
	load_plugin_textdomain( 'woocommerce-max-quantity' );
	// Check for: WooCommerce (maybe later also check for required version)
	if ( class_exists( 'WooCommerce' ) ) {
		add_filter( 'woocommerce_inventory_settings', __NAMESPACE__ . '\wc_max_qty_options' );
		add_filter( 'woocommerce_quantity_input_args', __NAMESPACE__ . '\wc_max_qty_input_args', 10, 2 );
		add_filter( 'woocommerce_available_variation', __NAMESPACE__ . '\wc_max_qty_variation_input_qty_limits', 10, 3 );
		add_filter( 'woocommerce_add_to_cart_validation', __NAMESPACE__ . '\wc_max_qty_add_to_cart_validation', 1, 4 );
		add_filter( 'woocommerce_update_cart_validation', __NAMESPACE__ . '\wc_max_qty_update_cart_validation', 1, 4 );
		add_action( 'woocommerce_product_options_inventory_product_data', __NAMESPACE__ . '\wc_max_qty_add_product_field' );
		add_action( 'woocommerce_process_product_meta', __NAMESPACE__ . '\wc_max_qty_save_product_field' );
		add_filter( 'woocommerce_store_api_product_quantity_limit', __NAMESPACE__ . '\blocks_cart_max_qty', 10, 2 );
		add_filter( 'woocommerce_store_api_product_quantity_minimum', __NAMESPACE__ . '\blocks_cart_min_qty', 10, 2 );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), __NAMESPACE__ . '\wc_max_qty_action_links' );
		add_filter( 'woocommerce_loop_add_to_cart_args', __NAMESPACE__ . '\wc_max_qty_loop_add_to_cart_args', 10, 2 );
		add_filter( 'woocommerce_add_to_cart_quantity', __NAMESPACE__ . '\wc_max_qty_loop_add_to_cart_quantity', 10, 2 );
	}
}
add_action( 'init', __NAMESPACE__ . '\init' );


/**
 * Add a "Settings" link to the plugin's entry on the Plugins list page
 *
 * @param array $links Existing plugin action links.
 * @return array
 * @since 3.0
 */
function wc_max_qty_action_links( $links ) {
	$settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=products&section=inventory#isa_woocommerce_max_qty_settings-description' ) ) . '">' . __( 'Settings', 'woocommerce-max-quantity' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}


/**
 * Add the option to WooCommerce products tab
 *
 * @param array $settings The current settings.
 */
function wc_max_qty_options( $settings ) {
	$updated_settings = array();
	foreach ( $settings as $section ) {
		$updated_settings[] = $section;
		// Right after the core Inventory Options section ends, add our own subsection
		if ( isset( $section['id'] ) && 'product_inventory_options' === $section['id'] && isset( $section['type'] ) && 'sectionend' === $section['type'] ) {
				$updated_settings[] = array(
					'title' => __( 'Maximum, Minimum and Multiple Quantity for WooCommerce Shops', 'woocommerce-max-quantity' ),
					'desc'  => __( 'Store-wide defaults for this plugin. All settings below can be overridden for a specific product on that product\'s Inventory tab.', 'woocommerce-max-quantity' ),
					'id'    => 'isa_woocommerce_max_qty_settings',
					'type'  => 'title',
				);
				$updated_settings[] = array(
					'title'             => __( 'Maximum quantity per product', 'woocommerce-max-quantity' ),
					'desc'              => __( 'This is the default maximum quantity that can be added to the cart per product. To overide this for a specific product, set it on the "Max quantity per order" field on the product Inventory tab.', 'woocommerce-max-quantity' ),
					'id'                => 'isa_woocommerce_max_qty_limit',
					'css'               => 'width: 75px;',
					'type'              => 'number',
					'custom_attributes' => array(
						'min'  => 0,
						'step' => 1,
					),
					'default'           => '',
					'autoload'          => false,
					'desc_tip'          => false,
				);
				$updated_settings[] = array(
					'title'             => __( 'Minimum quantity per product', 'woocommerce-max-quantity' ),
					'desc'              => __( 'This is the default minimum quantity that must be added to the cart per product. To overide this for a specific product, set it on the "Min quantity per order" field on the product Inventory tab.', 'woocommerce-max-quantity' ),
					'id'                => 'isa_woocommerce_max_qty_min_limit',
					'css'               => 'width: 75px;',
					'type'              => 'number',
					'custom_attributes' => array(
						'min'  => 0,
						'step' => 1,
					),
					'default'           => '',
					'autoload'          => false,
					'desc_tip'          => false,
				);
				$updated_settings[] = array(
					'title'             => __( 'Sell in multiples of', 'woocommerce-max-quantity' ),
					'desc'              => __( 'This is the default multiple that quantities must be bought in (e.g. 3 allows 3, 6, 9, ...). To overide this for a specific product, set it on the "Sell in multiples of" field on the product Inventory tab. Leave empty, or use 1, for no restriction.', 'woocommerce-max-quantity' ),
					'id'                => 'isa_woocommerce_max_qty_multiple',
					'css'               => 'width: 75px;',
					'type'              => 'number',
					'custom_attributes' => array(
						'min'  => 1,
						'step' => 1,
					),
					'default'           => '',
					'autoload'          => false,
					'desc_tip'          => false,
				);
				$updated_settings[] = array(
					'type' => 'sectionend',
					'id'   => 'isa_woocommerce_max_qty_settings',
				);
		}
	}
	return $updated_settings;
}


/**
 * Display the product's "Max quantity per order" field in the Product Data metabox
 *
 * @since 1.4
 */
function wc_max_qty_add_product_field() {
	$default_max      = (int) get_option( 'isa_woocommerce_max_qty_limit' );
	$default_min      = (int) get_option( 'isa_woocommerce_max_qty_min_limit' );
	$default_multiple = (int) get_option( 'isa_woocommerce_max_qty_multiple' );
	echo '<div class="options_group">';
	woocommerce_wp_text_input(
		array(
			'id'          => '_isa_wc_max_qty_product_max',
			'label'       => __( 'Max quantity per order', 'woocommerce-max-quantity' ),
			'placeholder' => $default_max > 0 ? sprintf(
				/* translators: %d: Default maximum quantity */
				esc_attr__( 'Store-wide threshold (%d)', 'woocommerce-max-quantity' ),
				$default_max
			) : '',
			'description' => __( 'Optional. Set a maximum quantity limit allowed per order. Enter a number, 1 or greater.', 'woocommerce-max-quantity' ),
		)
	);
	woocommerce_wp_text_input(
		array(
			'id'          => '_isa_wc_max_qty_product_min',
			'label'       => __( 'Min quantity per order', 'woocommerce-max-quantity' ),
			'placeholder' => $default_min > 0 ? sprintf(
				/* translators: %d: Default minimum quantity */
				esc_attr__( 'Store-wide threshold (%d)', 'woocommerce-max-quantity' ),
				$default_min
			) : '',
			'description' => __( 'Optional. Set a minimum quantity required per order. Enter a number, 1 or greater.', 'woocommerce-max-quantity' ),
		)
	);
	woocommerce_wp_text_input(
		array(
			'id'                => '_isa_wc_max_qty_product_multiple',
			'label'             => __( 'Sell in multiples of', 'woocommerce-max-quantity' ),
			'type'              => 'number',
			'custom_attributes' => array(
				'min'  => 1,
				'step' => 1,
			),
			'placeholder'       => $default_multiple > 1 ? sprintf(
				/* translators: %d: Default multiple */
				esc_attr__( 'Store-wide threshold (%d)', 'woocommerce-max-quantity' ),
				$default_multiple
			) : '1',
			'description'       => __( 'Optional. Only allow this product to be bought in multiples of this number (e.g. 3 allows 3, 6, 9, ...). Leave empty to use the store-wide setting, or 1 for no restriction.', 'woocommerce-max-quantity' ),
		)
	);
	echo '</div>';
}


/**
 * Save product's Max Quantity field
 *
 * @param int $post_id WP post id.
 * @since 1.4
 */
function wc_max_qty_save_product_field( $post_id ) {
	$product = wc_get_product( $post_id );
	if ( ! $product ) {
		return;
	}
	$val = $product->get_meta( '_isa_wc_max_qty_product_max' );
	// phpcs:ignore WordPress.Security.NonceVerification.Missing
	$new = isset( $_POST['_isa_wc_max_qty_product_max'] ) ? sanitize_text_field( wp_unslash( $_POST['_isa_wc_max_qty_product_max'] ) ) : ''; // Nonce verification is already taken care by WooCommerce
	if ( $val !== $new ) {
		$product->update_meta_data( '_isa_wc_max_qty_product_max', $new );
		$product->save();
	}
	$val = $product->get_meta( '_isa_wc_max_qty_product_min' );
	// phpcs:ignore WordPress.Security.NonceVerification.Missing
	$new = isset( $_POST['_isa_wc_max_qty_product_min'] ) ? sanitize_text_field( wp_unslash( $_POST['_isa_wc_max_qty_product_min'] ) ) : ''; // Nonce verification is already taken care by WooCommerce
	if ( $val !== $new ) {
		$product->update_meta_data( '_isa_wc_max_qty_product_min', $new );
		$product->save();
	}
	$val = $product->get_meta( '_isa_wc_max_qty_product_multiple' );
	// phpcs:ignore WordPress.Security.NonceVerification.Missing
	$new = isset( $_POST['_isa_wc_max_qty_product_multiple'] ) ? sanitize_text_field( wp_unslash( $_POST['_isa_wc_max_qty_product_multiple'] ) ) : ''; // Nonce verification is already taken care by WooCommerce
	if ( $val !== $new ) {
		$product->update_meta_data( '_isa_wc_max_qty_product_multiple', $new );
		$product->save();
	}
}


/**
 * Get the individual product max limit
 *
 * @param int $product_id The product ID.
 * @return int|bool $limit The max limit number for this product, if set, otherwise false.
 * @since 1.4
 */
function wc_get_product_max_limit( $product_id ) {
	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		return false;
	}
	$qty = $product->get_meta( '_isa_wc_max_qty_product_max' );
	if ( empty( $qty ) ) {
		// Honor the Sold individually setting
		$limit = $product->is_sold_individually() ? 1 : false;
	} else {
		$limit = (int) $qty;
	}
	return $limit;
}


/**
 * Get the individual product "Sell in multiples of" value
 *
 * @param int $product_id The product ID.
 * @return int|bool The multiple for this product, if set (1 or greater), otherwise false.
 * @since 3.0
 */
function wc_get_product_multiple_limit( $product_id ) {
	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		return false;
	}
	$val = (int) $product->get_meta( '_isa_wc_max_qty_product_multiple' );
	return $val >= 1 ? $val : false;
}


/**
 * Get the individual product min limit
 *
 * @param int $product_id The product ID.
 * @return int|bool $limit The min limit number for this product, if set (1 or greater), otherwise false.
 * @since 3.0
 */
function wc_get_product_min_limit( $product_id ) {
	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		return false;
	}
	$val = (int) $product->get_meta( '_isa_wc_max_qty_product_min' );
	return $val >= 1 ? $val : false;
}


/**
 * Round a quantity down to the nearest valid multiple, without going below the multiple itself.
 *
 * @param int $value The value to round down.
 * @param int $multiple The multiple to round to.
 * @return int
 * @since 3.0
 */
function wc_max_qty_floor_to_multiple( $value, $multiple ) {
	$value = (int) $value;
	if ( $multiple <= 1 || $value <= 0 ) {
		return $value;
	}
	return $value < $multiple ? $multiple : $multiple * (int) floor( $value / $multiple );
}


/**
 * Round a quantity up to the nearest valid multiple.
 *
 * @param int $value The value to round up.
 * @param int $multiple The multiple to round to.
 * @return int
 * @since 3.0
 */
function wc_max_qty_ceil_to_multiple( $value, $multiple ) {
	$value = (int) $value;
	if ( $multiple <= 1 || $value <= 0 ) {
		return $value;
	}
	return $multiple * (int) ceil( $value / $multiple );
}


/**
 * Set the max attribute value for the quantity input field for Add to cart forms.
 * This applies to Simple product Add To Cart forms, and ALL (simple and variable) products on the Cart page quantity field.
 *
 * @param array  $args The current arguments.
 * @param object $product The product.
 * @return array $args
 * @since 1.1.6
 */
function wc_max_qty_input_args( $args, $product ) {
	$default_max      = (int) get_option( 'isa_woocommerce_max_qty_limit' );
	$default_min      = (int) get_option( 'isa_woocommerce_max_qty_min_limit' );
	$default_multiple = (int) get_option( 'isa_woocommerce_max_qty_multiple' );
	$product_id       = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
	$product_max      = wc_get_product_max_limit( $product_id );
	$product_min      = wc_get_product_min_limit( $product_id );
	$product_multiple = wc_get_product_multiple_limit( $product_id );
	// Set product max or default max
	if ( ! empty( $product_max ) ) {
		$args['max_value'] = $product_max;
	} elseif ( ! empty( $default_max ) ) {
		$args['max_value'] = $default_max;
	}
	// Set product min or default min
	$min = ! empty( $product_min ) ? $product_min : ( $default_min > 0 ? $default_min : 0 );
	// Set product multiple or default multiple
	$multiple = ! empty( $product_multiple ) ? $product_multiple : ( $default_multiple > 1 ? $default_multiple : 1 );
	if ( $multiple > 1 && ! $product->is_sold_individually() ) {
		$args['step'] = $multiple;
		if ( ! empty( $args['max_value'] ) ) {
			$args['max_value'] = wc_max_qty_floor_to_multiple( $args['max_value'], $multiple );
		}
	}
	// Set the min attribute from the greater of the configured min and the multiple, aligned to the multiple
	if ( ( $min > 0 || $multiple > 1 ) && ! $product->is_sold_individually() ) {
		$effective_min = max( $min, $multiple );
		if ( $multiple > 1 ) {
			$effective_min = wc_max_qty_ceil_to_multiple( $effective_min, $multiple );
		}
		$args['min_value'] = $effective_min;
	}
	// Limit our max by the available stock, if stock is lower
	if ( ! empty( $args['max_value'] ) ) {
		if ( $product->managing_stock() && ! $product->backorders_allowed() ) {
			$stock             = $product->get_stock_quantity();
			$args['max_value'] = min( $stock, $args['max_value'] );
		}
	}
	return $args;
}


/**
 * Filter the available variation to enforce the max/min on the quantity input field
 * on Add to cart forms for Variable Products.
 *
 * @param array  $args The current arguments.
 * @param object $product The product.
 * @param object $variation The product variation.
 */
function wc_max_qty_variation_input_qty_limits( $args, $product, $variation ) {
	if ( is_admin() && ! is_ajax() ) {
		return $args;
	}
	$default_max      = (int) get_option( 'isa_woocommerce_max_qty_limit' );
	$default_min      = (int) get_option( 'isa_woocommerce_max_qty_min_limit' );
	$default_multiple = (int) get_option( 'isa_woocommerce_max_qty_multiple' );
	$product_max      = wc_get_product_max_limit( $variation->get_parent_id() );
	$product_min      = wc_get_product_min_limit( $variation->get_parent_id() );
	$product_multiple = wc_get_product_multiple_limit( $variation->get_parent_id() );
	// Set product max or default max
	if ( ! empty( $product_max ) ) {
		$args['max_qty'] = $product_max;
	} elseif ( ! empty( $default_max ) ) {
		$args['max_qty'] = $default_max;
	}
	// Set product min or default min
	$min = ! empty( $product_min ) ? $product_min : ( $default_min > 0 ? $default_min : 0 );
	// Set product multiple or default multiple
	$multiple = ! empty( $product_multiple ) ? $product_multiple : ( $default_multiple > 1 ? $default_multiple : 1 );
	if ( $multiple > 1 && ! $variation->is_sold_individually() ) {
		$args['step'] = $multiple;
		if ( ! empty( $args['max_qty'] ) ) {
			$args['max_qty'] = wc_max_qty_floor_to_multiple( $args['max_qty'], $multiple );
		}
	}
	// Set the min attribute from the greater of the configured min and the multiple, aligned to the multiple
	if ( ( $min > 0 || $multiple > 1 ) && ! $variation->is_sold_individually() ) {
		$effective_min = max( $min, $multiple );
		if ( $multiple > 1 ) {
			$effective_min = wc_max_qty_ceil_to_multiple( $effective_min, $multiple );
		}
		$args['min_qty'] = $effective_min;
	}
	// Limit our max by the available stock, if stock is lower
	if ( ! empty( $args['max_qty'] ) ) {
		if ( $variation->managing_stock() && ! $variation->backorders_allowed() ) {
			$stock           = $variation->get_stock_quantity();
			$args['max_qty'] = min( $stock, $args['max_qty'] );
		}
	}
	return $args;
}


/**
 * Find out how many of this product are already in the cart
 *
 * @param mixed  $product_id ID of the product in question.
 * @param string $cart_item_key The cart key for this item in case of Updating cart.
 *
 * @return integer $running_qty The total quantity of this item, parent item in case of variations, in cart
 * @since 1.1.6
 */
function wc_max_qty_get_cart_qty( $product_id, $cart_item_key = '' ) {
	$running_qty = 0; // Keep a running total to count variations
	// Search the cart for the product in question
	foreach ( WC()->cart->get_cart() as $other_cart_item_keys => $values ) {
		if ( intval( $product_id ) === intval( $values['product_id'] ) ) {
			/*
			 * In case of updating the cart quantity, don't count this cart item key
			otherwise they won't be able to REDUCE the number of items in cart because it will think it is adding the new quantity on top of the existing quantity, when in fact it is reducing the existing quantity to the new quantity.
			 */
			if ( $cart_item_key === $other_cart_item_keys ) {
				continue;
			}
			// Add that quantity to our running total qty for this product
			$running_qty += (int) $values['quantity'];
		}
	}
	return $running_qty;
}


/**
 * Validate product quantity when Added to cart
 *
 * @param bool $passed If the validation passed.
 * @param int  $product_id The product ID.
 * @param int  $quantity The quantity added to cart.
 * @param int  $variation_id The product variation ID.
 *
 * @since 1.1.6
 */
function wc_max_qty_add_to_cart_validation( $passed, $product_id, $quantity, $variation_id = '' ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	// If it hasn't passed, we don't need to bother validating further
	if ( $passed ) {
		$default_max      = (int) get_option( 'isa_woocommerce_max_qty_limit' );
		$default_min      = (int) get_option( 'isa_woocommerce_max_qty_min_limit' );
		$default_multiple = (int) get_option( 'isa_woocommerce_max_qty_multiple' );
		$product_max      = wc_get_product_max_limit( $product_id );
		$product_min      = wc_get_product_min_limit( $product_id );
		$product_multiple = wc_get_product_multiple_limit( $product_id );
		// Set product max or default max
		if ( ! empty( $product_max ) ) {
			$new_max = $product_max;
		} elseif ( ! empty( $default_max ) ) {
			$new_max = $default_max;
		} else {
			$new_max = 0;
		}
		// Set product min or default min
		$new_min = ! empty( $product_min ) ? $product_min : ( $default_min > 0 ? $default_min : 0 );
		// Set product multiple or default multiple
		$multiple = ! empty( $product_multiple ) ? $product_multiple : ( $default_multiple > 1 ? $default_multiple : 1 );
		if ( ! $new_max && ! $new_min && $multiple <= 1 ) {
			return $passed;
		}
		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			return $passed;
		}
		if ( $product->is_sold_individually() ) {
			return $passed;
		}
		$already_in_cart = wc_max_qty_get_cart_qty( $product_id );
		$product_title   = $product->get_title();
		if ( $new_max ) {
			if ( ! empty( $already_in_cart ) ) {
				// There was already a quantity of this item in cart prior to this addition.
				// Check if the total of already_in_cart + current addition quantity is more than our max.
				if ( ( $already_in_cart + $quantity ) > $new_max ) {
					// oops. too much.
					$passed = false;
					// Add compatibility with WooCommerce Direct Checkout
					if ( class_exists( 'WooCommerce_Direct_Checkout' ) ) {
						$direct_checkout     = get_option( 'direct_checkout_enabled' );
						$direct_checkout_url = get_option( 'direct_checkout_cart_redirect_url' );
						if ( $direct_checkout && $direct_checkout_url ) {
							// Redirect to submit page
							wp_safe_redirect( esc_url_raw( $direct_checkout_url ) );
							exit;
						}
					}
					wc_add_notice(
						apply_filters(
							'isa_wc_max_qty_error_message_already_had',
							sprintf(
								/* translators: %1$s maximum items, %2$s product name, %3$s: cart link, %4$s number of items in cart */
								__( 'You can add a maximum of %1$s %2$s’s to %3$s. You already have %4$s.', 'woocommerce-max-quantity' ),
								$new_max,
								$product_title,
								'<a href="' . esc_url( wc_get_cart_url() ) . '">' . __( 'your cart', 'woocommerce-max-quantity' ) . '</a>',
								$already_in_cart
							),
							$new_max,
							$already_in_cart
						),
						'error'
					);
				}
			} else { // phpcs:ignore Universal.ControlStructures.DisallowLonelyIf.Found
				// none were in cart previously
				// just in case they manually type in an amount greater than we allow, check the input number here too
				if ( $quantity > $new_max ) {
					// oops. too much.
					wc_add_notice(
						apply_filters(
							'isa_wc_max_qty_error_message',
							sprintf(
								/* translators: %1$s maximum items, %2$s product name, %3$s: cart link */
								__( 'You can add a maximum of %1$s %2$s’s to %3$s.', 'woocommerce-max-quantity' ),
								$new_max,
								$product_title,
								'<a href="' . esc_url( wc_get_cart_url() ) . '">' . __( 'your cart', 'woocommerce-max-quantity' ) . '</a>'
							),
							$new_max
						),
						'error'
					);
					$passed = false;
				}
			}
		}
		// Just in case they manually type in a quantity that isn't a valid multiple, check it here too.
		if ( $multiple > 1 ) {
			$total_qty = $already_in_cart + $quantity;
			if ( $total_qty % $multiple !== 0 ) {
				// oops. not a valid multiple.
				$passed = false;
				wc_add_notice(
					apply_filters(
						'isa_wc_max_qty_multiple_error_message',
						sprintf(
							/* translators: %1$s: product name, %2$d: multiple */
							__( '"%1$s" can only be bought in multiples of %2$d.', 'woocommerce-max-quantity' ),
							$product_title,
							$multiple
						),
						$multiple,
						$product_title
					),
					'error'
				);
			}
		}
		// Check if the quantity being added is below the effective minimum.
		if ( $new_min > 0 || $multiple > 1 ) {
			$effective_min = max( $new_min, $multiple );
			if ( $multiple > 1 ) {
				$effective_min = wc_max_qty_ceil_to_multiple( $effective_min, $multiple );
			}
			if ( $effective_min > 0 && $quantity < $effective_min ) {
				// oops. too little.
				$passed = false;
				wc_add_notice(
					apply_filters(
						'isa_wc_max_qty_min_error_message',
						sprintf(
							/* translators: %1$s minimum items, %2$s product name, %3$s: cart link */
							__( 'You must add a minimum of %1$s %2$s’s to %3$s.', 'woocommerce-max-quantity' ),
							$effective_min,
							$product_title,
							'<a href="' . esc_url( wc_get_cart_url() ) . '">' . __( 'your cart', 'woocommerce-max-quantity' ) . '</a>'
						),
						$effective_min
					),
					'error'
				);
			}
		}
	}
	return $passed;
}

/**
 * Validate product quantity when cart is UPDATED - Not working when updating quantity inside the block-based cart
 * Just in case they manually type in an amount greater than we allow and the HTML5 Constraint validation doesn't work.
 *
 * @param bool   $passed If the validation passed.
 * @param string $cart_item_key The key of the updated cart item.
 * @param array  $values Cart item values.
 * @param int    $quantity The quantity updated to cart.
 * @since 1.1.9
 */
function wc_max_qty_update_cart_validation( $passed, $cart_item_key, $values, $quantity ) {
	// If it hasn't passed, we don't need to bother validating further
	if ( $passed ) {
		$default_max      = (int) get_option( 'isa_woocommerce_max_qty_limit' );
		$default_min      = (int) get_option( 'isa_woocommerce_max_qty_min_limit' );
		$default_multiple = (int) get_option( 'isa_woocommerce_max_qty_multiple' );
		$product_max      = wc_get_product_max_limit( $values['product_id'] );
		$product_min      = wc_get_product_min_limit( $values['product_id'] );
		$product_multiple = wc_get_product_multiple_limit( $values['product_id'] );
		// Set product max or default max
		if ( ! empty( $product_max ) ) {
			$new_max = $product_max;
		} elseif ( ! empty( $default_max ) ) {
			$new_max = $default_max;
		} else {
			$new_max = 0;
		}
		// Set product min or default min
		$new_min = ! empty( $product_min ) ? $product_min : ( $default_min > 0 ? $default_min : 0 );
		// Set product multiple or default multiple
		$multiple = ! empty( $product_multiple ) ? $product_multiple : ( $default_multiple > 1 ? $default_multiple : 1 );
		if ( ! $new_max && ! $new_min && $multiple <= 1 ) {
			return $passed;
		}
		$product = wc_get_product( $values['product_id'] );
		if ( ! $product ) {
			return $passed;
		}
		if ( $product->is_sold_individually() ) {
			return $passed;
		}
		$already_in_cart = wc_max_qty_get_cart_qty( $values['product_id'], $cart_item_key );
		if ( $new_max && ( $already_in_cart + $quantity ) > $new_max ) {
			wc_add_notice(
				apply_filters(
					'isa_wc_max_qty_error_message',
					sprintf(
						/* translators: %1$s maximum items, %2$s product name, %3$s: cart link */
						__( 'You can add a maximum of %1$s %2$s’s to %3$s.', 'woocommerce-max-quantity' ),
						$new_max,
						$product->get_name(),
						'<a href="' . esc_url( wc_get_cart_url() ) . '">' . __( 'your cart', 'woocommerce-max-quantity' ) . '</a>'
					),
					$new_max
				),
				'error'
			);
			$passed = false;
		}
		// Just in case they manually type in a quantity that isn't a valid multiple, check it here too.
		if ( $multiple > 1 ) {
			$total_qty = $already_in_cart + $quantity;
			if ( $total_qty % $multiple !== 0 ) {
				wc_add_notice(
					apply_filters(
						'isa_wc_max_qty_multiple_error_message',
						sprintf(
							/* translators: %1$s: product name, %2$d: multiple */
							__( '"%1$s" can only be bought in multiples of %2$d.', 'woocommerce-max-quantity' ),
							$product->get_name(),
							$multiple
						),
						$multiple,
						$product->get_name()
					),
					'error'
				);
				$passed = false;
			}
		}
		// Check if the new quantity is below the effective minimum.
		if ( $new_min > 0 || $multiple > 1 ) {
			$effective_min = max( $new_min, $multiple );
			if ( $multiple > 1 ) {
				$effective_min = wc_max_qty_ceil_to_multiple( $effective_min, $multiple );
			}
			if ( $effective_min > 0 && $quantity < $effective_min ) {
				wc_add_notice(
					apply_filters(
						'isa_wc_max_qty_min_error_message',
						sprintf(
							/* translators: %1$s minimum items, %2$s product name, %3$s: cart link */
							__( 'You must add a minimum of %1$s %2$s’s to %3$s.', 'woocommerce-max-quantity' ),
							$effective_min,
							$product->get_name(),
							'<a href="' . esc_url( wc_get_cart_url() ) . '">' . __( 'your cart', 'woocommerce-max-quantity' ) . '</a>'
						),
						$effective_min
					),
					'error'
				);
				$passed = false;
			}
		}
	}
	return $passed;
}


/**
 * Set maximum quantity on the block-based cart
 *
 * @since 2.0
 * @param int    $cart_max Current maximum quantity.
 * @param object $product Current product.
 * @return int
 */
function blocks_cart_max_qty( $cart_max, $product ) {
	if ( ! empty( $cart_max ) ) {
		$default_max = (int) get_option( 'isa_woocommerce_max_qty_limit' );
		$product_max = wc_get_product_max_limit( $product->get_parent_id() ? $product->get_parent_id() : $product->get_id() );
		// Set product max or default max
		if ( ! empty( $product_max ) ) {
			$new_max = $product_max;
		} elseif ( ! empty( $default_max ) ) {
			$new_max = $default_max;
		}
		if ( ! empty( $new_max ) ) {
			$cart_max = min( $cart_max, $new_max );
		}
	}
	return $cart_max;
}


/**
 * Set minimum quantity on the block-based cart
 *
 * @since 3.0
 * @param int    $cart_min Current minimum quantity.
 * @param object $product Current product.
 * @return int
 */
function blocks_cart_min_qty( $cart_min, $product ) {
	if ( $product->is_sold_individually() ) {
		return $cart_min;
	}
	$default_min = (int) get_option( 'isa_woocommerce_max_qty_min_limit' );
	$product_min = wc_get_product_min_limit( $product->get_parent_id() ? $product->get_parent_id() : $product->get_id() );
	// Set product min or default min
	$new_min = ! empty( $product_min ) ? $product_min : ( $default_min > 0 ? $default_min : 0 );
	if ( ! empty( $new_min ) ) {
		$cart_min = max( (int) $cart_min, $new_min );
	}
	return $cart_min;
}


/**
 * Set the quantity used by the "Add to cart" link on product archives/lists (classic templates,
 * shortcodes and widgets), so it adds a valid multiple and/or the minimum instead of always
 * trying to add 1.
 *
 * @since 3.0
 * @param array      $args The current arguments.
 * @param WC_Product $product The product.
 * @return array
 */
function wc_max_qty_loop_add_to_cart_args( $args, $product ) {
	$multiple = wc_max_qty_get_loop_multiple( $product );
	$quantity = max( $multiple, wc_max_qty_get_loop_min( $product ) );
	if ( $multiple > 1 ) {
		$quantity = wc_max_qty_ceil_to_multiple( $quantity, $multiple );
	}
	if ( $quantity > 1 ) {
		$args['quantity'] = $quantity;
	}
	return $args;
}


/**
 * Set the quantity used by the block-based "Add to Cart" button on product archives/lists
 * (Product Collection/Products block, FSE archive templates), so it adds a valid multiple
 * and/or the minimum instead of always trying to add 1.
 *
 * @since 3.0
 * @param int $quantity The current quantity.
 * @param int $product_id The product ID.
 * @return int
 */
function wc_max_qty_loop_add_to_cart_quantity( $quantity, $product_id ) {
	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		return $quantity;
	}
	$multiple      = wc_max_qty_get_loop_multiple( $product );
	$loop_quantity = max( $multiple, wc_max_qty_get_loop_min( $product ) );
	if ( $multiple > 1 ) {
		$loop_quantity = wc_max_qty_ceil_to_multiple( $loop_quantity, $multiple );
	}
	return $loop_quantity > 1 ? $loop_quantity : $quantity;
}


/**
 * Resolve the effective "Sell in multiples of" value to use for a product/list "Add to cart"
 * button (product override, else the store-wide default, else 1 i.e. no restriction).
 *
 * @since 3.0
 * @param WC_Product $product The product.
 * @return int
 */
function wc_max_qty_get_loop_multiple( $product ) {
	if ( $product->is_sold_individually() ) {
		return 1;
	}
	$product_id       = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
	$product_multiple = wc_get_product_multiple_limit( $product_id );
	if ( ! empty( $product_multiple ) ) {
		return $product_multiple;
	}
	$default_multiple = (int) get_option( 'isa_woocommerce_max_qty_multiple' );
	return $default_multiple > 1 ? $default_multiple : 1;
}


/**
 * Resolve the effective minimum quantity to use for a product/list "Add to cart"
 * button (product override, else the store-wide default, else 0 i.e. no restriction).
 *
 * @since 3.0
 * @param WC_Product $product The product.
 * @return int
 */
function wc_max_qty_get_loop_min( $product ) {
	if ( $product->is_sold_individually() ) {
		return 0;
	}
	$product_id  = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
	$product_min = wc_get_product_min_limit( $product_id );
	if ( ! empty( $product_min ) ) {
		return $product_min;
	}
	$default_min = (int) get_option( 'isa_woocommerce_max_qty_min_limit' );
	return $default_min > 0 ? $default_min : 0;
}


/* Declare WooCommerce HPOS Compatibility */
add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
		}
	}
);
