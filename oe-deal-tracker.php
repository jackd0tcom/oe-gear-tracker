<?php
/**
 * Plugin Name: Outdoor Empire Deal Tracker
 * Plugin URI: https://yourwebsite.com/
 * Description: This plugin tracks deals for Outdoor Empire.
 * Version: 1.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com/
 */

// Hook into the admin menu
add_action('admin_menu', 'outdoor_empire_deals_admin_menu');

function outdoor_empire_deals_admin_menu() {
    add_menu_page(
        'Outdoor Empire Deal Tracker',
        'Outdoor Empire Deal Tracker',
        'manage_options',
        'oe-deal-tracker',
        'outdoor_empire_deals_admin_page_content',
        'dashicons-store',
        20
    );
}

function outdoor_empire_deals_admin_page_content() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // Fetch data
    $data = fetch_outdoor_empire_deals_data();

    // Display the admin page content
    echo '<h1>Outdoor Empire Deal Tracker Admin</h1>';
    // Here you can add HTML to display the data or a form to select items
    // For example, loop through $data to display items
}

function fetch_outdoor_empire_deals_data() {
    $response = wp_remote_get('https://datafeed.avantlink.com/download_feed.php?id=312817&auth=0c817f93c46a51e7a945310857820072');

    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    return $data;
}
