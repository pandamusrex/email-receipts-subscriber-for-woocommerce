<?php

/**
 * Plugin Name: Email Receipts Subscriber for WooCommerce
 * Version: 1.0.0
 * Plugin URI: https://github.com/pandamusrex/email-receipts-subscriber-for-woocommerce
 * Description: Integrate your Gmail inbox to WooCommerce with Google Cloud Pub/Sub to match payment receipts to orders.
 * Author: PandamusRex
 * Author URI: https://www.github.com/pandamusrex/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.4
 * Tested up to: 6.8
 *
 * Text Domain: email-receipts-subscriber-for-woocommerce
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author PandamusRex
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PandamusRex_Email_Receipts_Subscriber_for_WooCommerce {
    private static $instance;

    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __clone() {}

    public function __wakeup() {}

    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'rest_api_init' ] );
    }

    public function rest_api_init() {
        require_once( plugin_dir_path(__FILE__) . 'includes/rest-controller.php' );
        $rest_controller = new PandamusRex_Email_Receipts_Rest_Controller();
        $rest_controller->register_routes();
    }
}

PandamusRex_Email_Receipts_Subscriber_for_WooCommerce::get_instance();
