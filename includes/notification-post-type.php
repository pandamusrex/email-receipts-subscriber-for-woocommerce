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
        add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ] );
    }

    public function init() {
        register_post_type( 'pandamusrex_mailnote',
            array(
                'labels'       => array(
                    'name'          => __('Payments Webhook Notifications', 'textdomain'),
                    'singular_name' => __('Payments Webhook Notification', 'textdomain'),
                ),
                'public'              => false,
                'publicly_queryable'  => false,
                'exclude_from_search' => true,
                'has_archive'         => false,
                'show_ui'             => true,
                'show_in_menu'        => true,
            )
	    );
    }

    public function add_meta_boxes() {
        add_meta_box( 'pmts_wbhk_lstn_sectionid', __( 'Related Order' ), [ $this, 'meta_box' ], 'pandamusrex_mailnote', 'side', 'high' );
    }

    public function meta_box( $post) {
        $linked_order_ID = get_post_meta( $post->ID, '_linked_order_id', true );
        if ( empty( $linked_order_ID ) ) {
            echo "<p>Choose an order to link this payment to:";
            echo "</p>";
        } else {
            echo "<p>Linked order: ";
            echo get_edit_post_link( $post );
            echo "<br/>";
            echo "<a href='#'>Unlink</a>";
            echo "</p>";
        }
    }
}

PandamusRex_Email_Receipts_Notification_Post_Type::get_instance();
