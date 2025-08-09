<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PandamusRex_Email_Receipts_Subscriber_for_WooCommerce {
    private static $instance;

    private static $rest_controller;

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
        $this->rest_controller = new PandamusRex_Email_Receipts_Rest_Controller();
    }
}

PandamusRex_Email_Receipts_Subscriber_for_WooCommerce::get_instance();
