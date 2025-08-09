<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PandamusRex_Email_Receipts_Notification_Post_Type {
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
        add_action( 'init', [ $this, 'init' ] );
    }

    public function init() {
        register_post_type( 'pandamusrex_mailnote',
            array(
                'labels'        => array(
                    'name'          => __('Email Notifications', 'textdomain'),
                    'singular_name' => __('Email Notification', 'textdomain'),
                ),
                'public'      => false,
                'has_archive' => false,
            )
	    );
    }
}

PandamusRex_Email_Receipts_Notification_Post_Type::get_instance();
