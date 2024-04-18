<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://jackballdev.com
 * @since      1.0.0
 *
 * @package    Oe_Deal_Tracker
 * @subpackage Oe_Deal_Tracker/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Oe_Deal_Tracker
 * @subpackage Oe_Deal_Tracker/admin
 * @author     Jack Ball <jackballdev@gmail.com>
 */
class Oe_Deal_Tracker_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Oe_Deal_Tracker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Oe_Deal_Tracker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/oe-deal-tracker-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Oe_Deal_Tracker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Oe_Deal_Tracker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/oe-deal-tracker-admin.js', array( 'jquery' ), $this->version, false );

	}

}

// Hook into the admin menu
add_action('admin_menu', 'outdoor_empire_deals_admin_menu');

function outdoor_empire_deals_admin_menu() {
    add_menu_page(
        'Outdoor Empire Deal Tracker',
        'Outdoor Empire Deal Tracker',
        'manage_options',
        'oe-deal-tracker',
        'outdoor_empire_deals_admin_page_content',
        'dashicons-money-alt',
        20
    );
}

function outdoor_empire_deals_admin_page_content() {
    if (!current_user_can('manage_options')) {
        return;
    }

    echo '<h1>Outdoor Empire Deal Tracker Admin</h1>';

}

function debug_to_console($data, $context = 'Debug in Console') {
    ob_start();

    $output = 'console.info(\'' . $context . ':\');';
    $output .= 'console.log(' . json_encode($data) . ');';
    $output = sprintf('<script>%s</script>', $output);

    echo $output;
}

function parse_avantlink_data($feed_url) {
    $response = wp_remote_get($feed_url);

    if (is_wp_error($response)) {
        // Handle the error gracefully
        return 'Failed to retrieve Avantlink data: ' . $response->get_error_message();
    }

    // Check if response code is 200 (OK)
    $response_code = wp_remote_retrieve_response_code($response);
    if ($response_code !== 200) {
        return 'Failed to retrieve Avantlink data. HTTP response code: ' . $response_code;
    }

    $xml = simplexml_load_string(wp_remote_retrieve_body($response));

    if (!$xml) {
        return 'Failed to parse Avantlink data.';
    }

    $products = array();

	foreach ($xml->Product as $product) {
        $product_data = array(
            'SKU' => (string)$product->SKU,
            'Manufacturer_Id' => (string)$product->Manufacturer_Id,
            'Brand_Name' => (string)$product->Brand_Name,
            'Product_Name' => (string)$product->Product_Name,
            'Long_Description' => (string)$product->Long_Description,
            'Short_Description' => (string)$product->Short_Description,
            'Category' => (string)$product->Category,
            'SubCategory' => (string)$product->SubCategory,
            'Product_Group' => (string)$product->Product_Group,
            'Thumb_URL' => (string)$product->Thumb_URL,
            'Image_URL' => (string)$product->Image_URL,
            'Buy_Link' => (string)$product->Buy_Link,
            'Retail_Price' => (float)$product->Retail_Price,
            'Sale_Price' => (float)$product->Sale_Price,
            'UPC' => (string)$product->UPC,
            'Medium_Image_URL' => (string)$product->Medium_Image_URL,
            // Add more fields as needed
        );

        $products[] = $product_data;
    }

    return $products;
}


if (is_array($parsed_data)) {
    echo '<pre>';
    print_r($parsed_data);
    echo '</pre>';
} else {
    echo $parsed_data; // Output error message
}

function display_avantlink_data() {
    // Fetch and parse data from Avantlink datafeed
    $feed_url = 'https://datafeed.avantlink.com/download_feed.php?id=312829&auth=0c817f93c46a51e7a945310857820072';
    $parsed_data = parse_avantlink_data($feed_url);

    // Display data in the admin area
    ?>
    <div class="wrap">
        <h1>Avantlink Data</h1>
        <div class="product-cards">
            <?php if (is_array($parsed_data) && !empty($parsed_data)): ?>
                <?php foreach ($parsed_data as $product): ?>
                    <div class="product-card">
                        <h2><?php echo esc_html($product['Product_Name']); ?></h2>
                        <div class="product-image">
                            <img src="<?php echo esc_url($product['Image_URL']); ?>" alt="<?php echo esc_attr($product['Product_Name']); ?>">
                        </div>
                        <div class="product-details">
                            <p><strong>Retail Price:</strong> $<?php echo esc_html($product['Retail_Price']); ?></p>
                            <p><strong>Sale Price:</strong> $<?php echo esc_html($product['Sale_Price']); ?></p>
                            <!-- Add more product details as needed -->
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No data available or error retrieving data.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

// Hook into the admin menu to add the custom admin page
add_action('admin_menu', 'add_avantlink_admin_page');
function add_avantlink_admin_page() {
    add_menu_page(
        'Avantlink Data',
        'Avantlink Data',
        'manage_options',
        'avantlink-data',
        'display_avantlink_data'
    );
}

?>