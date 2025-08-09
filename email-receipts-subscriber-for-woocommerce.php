<?php

/**
 * Plugin Name: Email Receipts Subscriber for WooCommerce
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
