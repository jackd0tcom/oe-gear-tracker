<?php

/**
 *
 * @link              https://jackballdev.com
 * @since             1.0.0
 * @package           Oe_Deal_Tracker
 *
 * @wordpress-plugin
 * Plugin Name:       Outdoor Empire Deal Tracker
 * Plugin URI:        https://outdoorempire.com/oe-deal-tracker
 * Description:       Outdoor Empire's way of tracking those sneaky deals
 * Version:           1.0.0
 * Author:            Jack Ball
 * Author URI:        https://jackballdev.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       oe-deal-tracker
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('OE_DEAL_TRACKER_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-oe-deal-tracker-activator.php
 */
function activate_oe_deal_tracker()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-oe-deal-tracker-activator.php';
	Oe_Deal_Tracker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-oe-deal-tracker-deactivator.php
 */
function deactivate_oe_deal_tracker()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-oe-deal-tracker-deactivator.php';
	Oe_Deal_Tracker_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_oe_deal_tracker');
register_deactivation_hook(__FILE__, 'deactivate_oe_deal_tracker');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-oe-deal-tracker.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_oe_deal_tracker()
{

	$plugin = new Oe_Deal_Tracker();
	$plugin->run();
}
run_oe_deal_tracker();

require_once plugin_dir_path(__FILE__) . 'db-functions.php';
