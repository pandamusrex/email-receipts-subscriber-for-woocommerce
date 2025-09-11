<?php

/**
 * Plugin Name: PandamusRex Email Webhooks for WooCommerce
 * Version: 1.1.0
 * Plugin URI: https://github.com/pandamusrex/pandamusrex-email-webhooks-for-woocommerce
 * Description: Assign payment receipt emails and complete orders with a webhook that connects your email to WooCommerce.
 * Author: PandamusRex
 * Author URI: https://www.github.com/pandamusrex/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Requires at least: 6.4
 * Tested up to: 6.8
 *
 * Text Domain: pandamusrex-email-webhooks-for-woocommerce
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author PandamusRex
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// include_once( plugin_dir_path(__FILE__) . 'includes/notification-post-type.php' );

require_once( plugin_dir_path(__FILE__) . 'includes/webhooks-db.php' );
register_activation_hook( __FILE__, [ 'PandamusRex_Email_Webhooks_Db', 'create_tables' ] );

require_once( plugin_dir_path(__FILE__) . 'includes/webhooks-history-db.php' );
register_activation_hook( __FILE__, [ 'PandamusRex_Email_Webhooks_History_Db', 'create_tables' ] );

class PandamusRex_Email_Webhooks_for_WooCommerce {
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
        $rest_controller = new PandamusRex_Email_Webhooks_Rest_Controller();
        $rest_controller->register_routes();
    }
}

PandamusRex_Email_Webhooks_for_WooCommerce::get_instance();
