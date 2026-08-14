=== Maximum, Minimum and Multiple Quantity for WooCommerce Shops ===
Contributors: nakedcatplugins, webdados
Donate link: https://www.paypal.me/Wonderm00n
Tags: max quantity, min quantity, cart maximum, cart minimum, order limit
Requires at least: 5.9
Tested up to: 7.0
Requires PHP: 7.2
Stable tag: 3.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Set a maximum and/or minimum quantity limit, and/or force a "sell in multiples of" quantity, for the WooCommerce cart, globally or per product.

== Description ==

Maximum, Minimum and Multiple Quantity for WooCommerce Shops is a simple extension for WooCommerce that lets you set a maximum and/or minimum limit for the number of items that can be added to the cart, and/or force customers to buy a product in multiples of a set number (e.g. 3, 6, 9, ...), for each product, per order.

It’s like one of those signs at the grocery store that says, “LIMIT 2 PER CUSTOMER!”, “MINIMUM 5 ITEMS” or “SOLD IN PACKS OF 3”. This plugin will not add a sign like that, but the quantity input field will honor the maximum, the minimum and/or the multiple.

You can set a global maximum, minimum and/or multiple to affect all products in your WooCommerce store or, if you prefer, a different one for each product, individually. Each product’s own setting will always override the global one.

This works for all products in your WooCommerce store: Simple and Variable products.

Customers do not have to be logged in for this to work. This works for guest buyers, as well as logged-in buyers.

This plugin was initially developed by [Isabel Castillo](https://profiles.wordpress.org/isabel104/) and later adopted by [Marco Almeida | Webdados](https://profiles.wordpress.org/webdados/) / [Naked Cat Plugins](https://profiles.wordpress.org/nakedcatplugins/).

Header photo by [Mick Haupt](https://unsplash.com/@rocinante_11).

== Installation ==

**Install and Activate**

1. In your WordPress dashboard, go to Plugins, Add New.
2. Search for “Maximum, Minimum and Multiple Quantity for WooCommerce Shops” to find the plugin.
3. When you see “Maximum, Minimum and Multiple Quantity for WooCommerce Shops”, click “Install Now” to install the plugin.
4. Click “Activate” to activate the plugin.

**Configuration**

The plugin has 3 global settings and the same 3 settings again per product, so you can override the global ones for a specific product. You can use these settings in a variety of ways to accomplish the unique goals of your store:

1. To set a global maximum, minimum and/or multiple to affect all products, go to WooCommerce, Settings, Products tab. Click “Inventory”. Scroll down to “Maximum quantity per product”, “Minimum quantity per product” and “Sell in multiples of”. Set your desired values there.
2. To override this for a single product, go the product’s own “Edit product” page. Scroll down to the “Product Data” box. Click on the Inventory tab. There, you’ll see the same three settings, “Max quantity per order”, “Min quantity per order” and “Sell in multiples of”, where you can set your desired values for that product.

== Other (premium) plugins ==

Already know our other WooCommerce (premium) plugins?

* [Simple Checkout Fields Manager for WooCommerce](https://nakedcatplugins.com/product/simple-custom-fields-for-woocommerce-blocks-checkout/) - Add custom fields and manage (remove, make required or optional) core fields on the new WooCommerce Block-based Checkout
* [Simple WooCommerce Order Approval](https://nakedcatplugins.com/product/simple-woocommerce-order-approval/) - The hassle-free solution for WooCommerce order approval before payment
* [Shop as Client for WooCommerce](https://nakedcatplugins.com/product/shop-as-client-for-woocommerce-pro-add-on/) - Quickly create orders on behalf of your customers
* [Advanced Coupon Restrictions for WooCommerce](https://nakedcatplugins.com/product/advanced-coupon-restrictions-for-woocommerce/) - Create coupons for any Product Taxonomy, User details, and Order destination.
* [Taxonomy/Term and Role based Discounts for WooCommerce](https://nakedcatplugins.com/product/taxonomy-term-and-role-based-discounts-for-woocommerce-pro-add-on/) - Easily create bulk discount rules for products based on any taxonomy terms (built-in or custom).
* [DPD / SEUR / Geopost Pickup and Lockers network for WooCommerce](https://nakedcatplugins.com/product/dpd-seur-geopost-pickup-and-lockers-network-for-woocommerce/) - Deliver your WooCommerce orders on the DPD and SEUR Pickup network of Parcelshops and Lockers in 21 European countries

== Frequently Asked Questions ==

= Does this plugin work with products with variations (Variable products)? =

Yes, since version 1.4. 

= Can I set a different limit for different products? =

Yes, since version 1.4.

= Can I set a minimum quantity that customers must order? =

Yes, since version 3.0, globally or per product.

= Can I force customers to buy a product in multiples of a set number (e.g. 3, 6, 9, ...)? =

Yes, since version 3.0, globally or per product.

= Is this plugin compatible with the new WooCommerce High-Performance Order Storage? =

Yes, since version 2.0.

= Is this plugin compatible with the new WooCommerce block-based Cart and Checkout? =

Yes, since version 2.0.

= I need help, can I get technical support? =

This is a free plugin. It’s our way of giving back to the wonderful WordPress community.

There’s a support tab on the top of this page, where you can ask the community for help. We’ll try to keep an eye on the forums but we cannot promise to answer support tickets.

If you reach us by email or any other direct contact method, we’ll assume you need premium, paid-for support.

= Where do I report security vulnerabilities found in this plugin? =
 
You can report any security bugs found in the source code of this plugin through the [Patchstack Vulnerability Disclosure Program](https://patchstack.com/database/vdp/woocommerce-max-quantity). The Patchstack team will assist you with verification, CVE assignment and take care of notifying the developers of this plugin.

= Can I contribute with a translation? =

Sure. Go to [GlotPress](https://translate.wordpress.org/projects/wp-plugins/woocommerce-max-quantity) and help us out.

== Screenshots ==

1. The global setting: It is labeled “Maximum quantity per product” and is found at WooCommerce, Settings, Products tab, Inventory link.

2. The individual product’s setting: It is labeled “Max quantity per order” and is found on the individual product edit screen, in the Product Data box, on the Inventory tab.

== Changelog ==

= 3.0 =
* [NEW] "Minimum quantity per product" option, globally and per product, to require a minimum quantity per order (mirrors the existing maximum quantity option)
* [NEW] "Sell in multiples of" option, globally and per product, to force quantities to be bought in multiples of a set number (e.g. 3 allows 3, 6, 9, ...)
* [NEW] Renamed to "Maximum, Minimum and Multiple Quantity for WooCommerce Shops" to reflect the new options
* [FIX] "Add to cart" buttons on product archives/lists (classic and block-based) now add a valid multiple and/or the minimum instead of always trying to add 1, when a "Sell in multiples of" and/or a minimum quantity is set
* [TWEAK] Added a "Settings" link to the plugin's entry on the Plugins list page

= 2.4 - 2026-06-06 =
* [FIX] Fatal error in edge cases (e.g. a cart item referencing a product/variation that no longer exists)
* [DEV] Move plugin repository to [GitHub](https://github.com/Naked-Cat-Plugins/Maximum-Quantity-for-WooCommerce-Shops)
* [DEV] Requires WordPress 5.9 and WooCommerce 7.3
* [DEV] Tested up to WordPress 7.1-alpha-62456 and WooCommerce 10.8.1

= 2.3 - 2025-04-14 =
* [NEW] We are now called Naked Cat Plugins 😻
* [DEV] Requires WordPress 5.8, WooCommerce 7.1, and PHP 7.2
* [DEV] Tested up to WordPress 6.8-RC3-60146 and WooCommerce 9.8.1

= 2.2.1 - 2024-10-08 =
* [FIX] Load text domain at the right time to avoid PHP notices on WordPress 6.7 and above
* [DEV] Tested up to WordPress 6.7-beta1-59184 and WooCommerce 9.4.0-beta.2

= 2.1 - 2024-06-30 =
* [NEW] Rename plugin to a more understandable title
* [TWEAK] Main plugin file header adjustments
* [TWEAK] Remove .pot file from repository
* [TWEAK] readme.txt adjustments

= 2.0 - 2024-06-27 =
* Plugin adopted by [Marco Almeida | Webdados](https://profiles.wordpress.org/webdados/)
* Rename plugin main file and textdomain to the same as the folder name to follow the plugin development guidelines (the plugin will deactivate on update, and should be manually activated again)
* Code refactor - Use namespacing, apply coding standards, better logic
* Use WooCommerce CRUD functions instead of `get_post_meta` and `update_post_meta`
* Fix maximum quantity on the WooCommerce block-based Cart
* Test and declare [WooCommerce High-Performance Order Storage](https://woocommerce.com/document/high-performance-order-storage/) compatibility
* Test and declare [WooCommerce block-based Cart and Checkout](https://woocommerce.com/checkout-blocks/) compatibility
* Requires WooCommerce 5.0 and WordPress 5.6
* Tested up to WooCommerce 8.9.0 and WordPress 6.6-alpha-58011

= 1.6 = 
* Fixed - Can no longer use WC’s name in plugin.

= 1.5.2 = 
* Fixed - Fixed a bug that was ignoring the max on some Variable Products.

= 1.5.1 =
* Fixed - Honor the “Sold individually” setting above the universal max limit.

= 1.5 =
* New - Added filters to the error message strings.
* Fixed - The max was not being enforced the input field for Variable products.
* Fixed - The max now works even when backorders are enabled.

= 1.4.3 =
* Fixed - The max limit also works on Variable Products, as long as backorders are not enabled.

= 1.4.2 =
* Fixed a fatal error regarding get_parent_data().

= 1.4.1 =
* New - For variable products, the stock quantity display has been restored. Previously, the stock quantity was hidden for products with variations. This only affected those who were displaying the stock quantity on the product page.
* Fixed several PHP notices. Thanks to @brettmhoffman.
* Internationalization - load_plugin_textdomain is now loaded on init rather than plugins_loaded, as it should be.

= 1.4 =
* New - Support for different limits for individual products. See the version 1.4 release post for details.
* New - Support for Variable products (products with variations). Now, the plugin works with all products, Simple and Variable.
* Code refactoring - Many functions were renamed:
`isa_get_qty_alread_in_cart` was changed to `isa_wc_max_qty_get_cart_qty`
`isa_max_item_quantity_validation` was changed to `isa_wc_max_qty_add_to_cart_validation`
`add_isa_max_quantity_options` was changed to `isa_wc_max_qty_options`
`isa_woo_max_qty_load_textdomain` was changed to `isa_wc_max_qty_load_textdomain`
`isa_woo_max_qty_update_cart_validation` was changed to `isa_wc_max_qty_update_cart_validation`
`isa_woocommerce_quantity_input_args` was changed to `isa_wc_max_qty_input_args`
* Code refactoring - One function was removed: `isa_woocommerce_available_variation`, which was hooked to `woocommerce_available_variation`, was removed.

= 1.3 =
* New - Added compatibility with the WooCommerce Direct Checkout plugin

= 1.2.4 =
* Fix - The setting had disappeared on last WC update.

= 1.2.3 =
* New - Added Dutch translation, thanks to Martijn Heesters.

= 1.2.2 =
* New - Added German translation, thanks @tofuSCHNITZEL.

= 1.2.1 =
* Fix - Did not calculate quantity properly if you UPDATE Quantity on Cart page. This did not let some users DECREASE or INCREASE the quantity while on the cart page.
* Maintenance - Tested and passed for WordPress 4.0 compatibility.

= 1.2.0 =
* New - Added .pot translation file.
* New - Added translations for French, Hindi, and Spanish languages.
* New - Changed textdomain to plugin slug.
* Maintenance - Tested and passed for WP 3.9 compatibility.

= 1.1.9 =
* Fix: added _update_cart_validation to avoid manual override on cart update at checkout.
* Tweak: remove passed=true in validation checks, use the passed parameter instead.
* Maintenance: replace woocommerce - add_error with wc_add_notice.

= 1.1.8 =
* Fix: now checks for manually-typed quantity because maximum limit was able to be overridden by typing in a number.
* Fix: a problem in which limit was ignored if product was previously added to cart, then added another item to cart, then re-added this item to cart.
* Maintenance: Updated description to reflect that this plugin does not yet support products with variations.

= 1.1.6 =
* Fix: maximum limit was able to be overridden by adding a new instance of the item to cart.
* Fix: maximum limit was able to be overridden by updating quantity on cart page.

= 1.1.5 =
* Tested for WP 3.8 compatibility.

= 1.1.4 =
* Tested for WP 3.7.1 compatibility.

= 1.1.3 =
* bug fix related to syncing with Git.

= 1.1.2 =
* bug fix related to syncing with Git.

= 1.1.1 =
* bugfix related to syncing with Git.

= 1.1 =
* Tested for WP 3.6.1 compatibility

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 1.5.2 = 
Fixed a bug that was ignoring the max on some Variable Products.

= 1.5.1 =
Fixed - Honor the “Sold individually” setting above the universal max limit.

= 1.5 =
Fixed variations max. New filters for error message. Now works with backorders.
