<?php
/**
 * Uninstall file for Tunisia Governorate Cities for WooCommerce
 * 
 * This file is executed when the plugin is deleted from WordPress admin.
 * It cleans up any data that the plugin has created.
 */

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('tgc_version');
delete_option('tgc_settings');

// Clean up order meta data (optional - uncomment if you want to remove all governorate/city data)
/*
global $wpdb;

// Remove governorate and city meta from orders
$wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key IN ('_billing_governorate', '_billing_city', '_shipping_governorate', '_shipping_city')");

// Remove governorate and city meta from user meta
$wpdb->query("DELETE FROM {$wpdb->usermeta} WHERE meta_key IN ('billing_governorate', 'billing_city', 'shipping_governorate', 'shipping_city')");
*/

// Clear any cached data
wp_cache_flush();

// Note: We don't delete the order meta data by default as it might be important for existing orders
// If you want to completely remove all governorate/city data, uncomment the code above 