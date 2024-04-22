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
class Oe_Deal_Tracker_Admin
{

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
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

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

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/oe-deal-tracker-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

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

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/oe-deal-tracker-admin.js', array('jquery'), $this->version, false);
    }
}

// Hook into the admin menu
add_action('admin_menu', 'outdoor_empire_deals_admin_menu');

function outdoor_empire_deals_admin_menu()
{
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

function outdoor_empire_deals_admin_page_content()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    echo '<h1>Outdoor Empire Deal Tracker Admin</h1>';
}

function debug_to_console($data, $context = 'Debug in Console')
{
    ob_start();

    $output = 'console.info(\'' . $context . ':\');';
    $output .= 'console.log(' . json_encode($data) . ');';
    $output = sprintf('<script>%s</script>', $output);

    echo $output;
}

function parse_avantlink_data($feed_url)
{
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

    $json = json_decode(wp_remote_retrieve_body($response));

    // echo '<pre>';
    // print_r($json);
    // echo '</pre>';

    if (!$json) {
        return 'Failed to parse Avantlink data.';
    }

    $products = array();

    foreach ($json as $product) {
        $product_data = array(
            'SKU' => isset($product->SKU) ? (string)$product->SKU : '',
            'Manufacturer_Id' => isset($product->Manufacturer_Id) ? (string)$product->Manufacturer_Id : '',
            'Product_Name' => isset($product->Product_Name) ? (string)$product->Product_Name : '',
            'Brand' => isset($product->Brand) ? (string)$product->Brand : '',
            'Retail_Price' => isset($product->Retail_Price) ? (float)$product->Retail_Price : 0.0,
            'Sale_Price' => isset($product->Sale_Price) ? (float)$product->Sale_Price : 0.0,
            'Short_Description' => isset($product->Short_Description) ? (string)$product->Short_Description : '',
            'Long_Description' => isset($product->Long_Description) ? (string)$product->Long_Description : '',
            'Small_Image_URL' => isset($product->Small_Image_URL) ? (string)$product->Small_Image_URL : '',
            'Large_Image_URL' => isset($product->Large_Image_URL) ? (string)$product->Large_Image_URL : '',
            'Buy_URL' => isset($product->Buy_URL) ? (string)$product->Buy_URL : '',
            'Percent_Off' => isset($product->Percent_Off) ? (string)$product->Percent_Off : '',
            'Merchant Name' => isset($product->{'Merchant Name'}) ? (string)$product->{'Merchant Name'} : '',
        );


        $products[] = $product_data;
    }

    return $products;
}

add_action('admin_menu', 'add_chooser_page');
function add_chooser_page()
{
    add_submenu_page(
        'oe-deal-tracker', // Parent menu slug
        'Chooser', // Page title
        'Chooser', // Menu title
        'manage_options', // Capability required
        'oe-deal-tracker-chooser', // Menu slug
        'display_chooser_page' // Callback function
    );
}


// Callback function to display Chooser page
function display_chooser_page()
{
    // Check if form is submitted and process selected products
    if (isset($_POST['save_selected_products'])) {
        // Handle form submission and save selected products
        if (isset($_POST['selected_products'])) {
            $selected_products = $_POST['selected_products'];
            // Save selected products to database or perform any other action
            // For now, let's just display the selected products
            echo '<div class="updated"><p>Selected products: ' . implode(', ', $selected_products) . '</p></div>';
        } else {
            echo '<div class="error"><p>No products selected!</p></div>';
        }
    }

    // Display Avantlink data on Chooser page
    $feed_url = 'https://www.avantlink.com/api.php?module=DotdFeed&merchant_ids=10086|13273|10086&affiliate_id=185881&website_id=236121&output=json';
    $parsed_data = parse_avantlink_data($feed_url);

    // Display selected products
    if (isset($selected_products)) {
        echo '<h2>Selected Products:</h2>';
        echo '<ul>';
        foreach ($selected_products as $selected_product) {
            echo '<li>' . $selected_product . ' <button class="delete-selected" data-product="' . $selected_product . '">Delete</button></li>';
        }
        echo '</ul>';
    }
?>
    <div class="wrap">
        <h1>Outdoor Empire Gear Tracker - Chooser</h1>
        <p>Select products from the Avantlink data:</p>
        <form method="post">
            <div class="product-cards">
                <?php if (is_array($parsed_data) && !empty($parsed_data)) : ?>
                    <?php foreach ($parsed_data as $product) : ?>
                        <div class="product-card">
                            <label>
                                <input type="checkbox" name="selected_products[]" value="<?php echo esc_attr($product['SKU']); ?>">
                                <h2><?php echo esc_html($product['Product_Name']); ?></h2>
                                <div class="product-image">
                                    <img src="<?php echo esc_url($product['Large_Image_URL']); ?>" alt="<?php echo esc_attr($product['Product_Name']); ?>">
                                </div>
                                <div class="product-details">
                                    <p><strong>Retail Price:</strong> $<?php echo esc_html($product['Retail_Price']); ?></p>
                                    <p><strong>Sale Price:</strong> $<?php echo esc_html($product['Sale_Price']); ?></p>
                                    <p><strong>Merchant:</strong> <?php echo esc_html($product['Merchant Name']); ?></p>
                                    <p><strong>Percent Off:</strong> <?php echo esc_html($product['Percent_Off']); ?>%</p>
                                    <!-- Add more product details as needed -->
                                </div>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No data available or error retrieving data.</p>
                <?php endif; ?>
            </div>
            <input type="submit" name="save_selected_products" class="button button-primary" value="Save Selected Products">
        </form>
    </div>
    <script>
        // JavaScript to handle deletion of selected products
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('delete-selected')) {
                e.preventDefault();
                var productToDelete = e.target.dataset.product;
                // Perform deletion action here, such as removing from database
                // For now, let's just remove it from the list
                e.target.parentElement.remove();
            }
        });
    </script>
<?php
}



?>