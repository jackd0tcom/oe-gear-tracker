<?php
// db-functions.php

function create_selected_products_table()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'selected_products';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
id INT AUTO_INCREMENT PRIMARY KEY,
product_id VARCHAR(100) NOT NULL,
product_name VARCHAR(255) NOT NULL,
image_url VARCHAR(255) NOT NULL,
price DECIMAL(10,2) NOT NULL
) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
