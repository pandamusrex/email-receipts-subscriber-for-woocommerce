<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PandamusRex_Payment_Notifications_Admin {
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
        add_action( 'admin_menu', [ $this, 'admin_menu' ] );
    }

    public function admin_menu(){
        add_menu_page( 
            __( 'Payment Notifications', 'pandamusrex-email-webhooks' ),
            __( 'Payment Notifications', 'pandamusrex-email-webhooks' ),
            'manage_options',
            'pandamusrex_pmt_notif_page',
            [ $this, 'echo_pmt_notif_page' ],
            'dashicons-pets',
            59 // below first separator
        );
    }

    public function echo_pmt_notif_page() {
        if ( ! function_exists( 'wc_get_logger' ) ) {
            wp_admin_notice(
                __( 'PandamusRex Email Webhooks for WooCommerce requires the WooCommerce plugin to be active.', 'pandamusrex-memberships' ),
                [ 'type' => 'error' ]
            );
            return;
        }

        echo '<div class="wrap">';
        echo '<h1 class="wp-heading-inline">';
        esc_html_e( 'Payment Notifications', 'pandamusrex-email-webhooks' );
        echo '</h1>';
        echo '<hr class="wp-header-end">';

        echo '<p>&nbsp;</p>';

        echo '<table class="wp-list-table widefat fixed striped table-view-list">';
        echo '<thead>';
        echo '<tr>';
        echo '<th scope="col" class="manage-column">' . esc_html__( 'Sender', 'pandamusrex-payment-notifications' ) . '</th>';
        echo '<th scope="col" class="manage-column">' . esc_html__( 'Subject', 'pandamusrex-payment-notifications' ) . '</th>';
        echo '<th scope="col" class="manage-column">' . esc_html__( 'Order', 'pandamusrex-payment-notifications' ) . '</th>';
        echo '<th scope="col" class="manage-column">' . esc_html__( 'Received', 'pandamusrex-payment-notifications' ) . '</th>';
        echo '</tr>';
        echo '</thead>';

        wc_get_logger()->debug( "In display all" );

        $notifications = PandamusRex_Email_Webhooks_Db::get_all_notifications();

        if ( empty( $notifications ) ) {
            echo '<tr class="no-items">';
            echo '<td class="colspanchange" colspan="6">';
            esc_html_e( 'No payment notifications found.', 'pandamusrex-payment-notifications' );
            echo '</td>';
            echo '</tr>';

            $result = PandamusRex_Email_Webhooks_Db::record_webhook(
                "Test message not from Zelle 202509081130",
                "2025-09-09T00:00:32.000Z",
                "Test Sender test@example.com",
                "This is a test message to try out my webhook\r\nLa la la\r\nTest Sender\r\n"
            );
            if ( is_wp_error( $result ) ) {
                wc_get_logger()->debug( "Error adding record to database" );
                wc_get_logger()->debug( $result->get_error_message() );
            }

            $result = PandamusRex_Email_Webhooks_Db::record_webhook(
                "Test message not from Zelle 202509111730",
                "2025-09-12T00:30:32.000Z",
                "Test Sender test@example.com",
                "This is another test message to try out my webhook\r\nLa la la\r\nTest Sender\r\n"
            );
            if ( is_wp_error( $result ) ) {
                wc_get_logger()->debug( "Error adding record to database" );
                wc_get_logger()->debug( $result->get_error_message() );
            }

        } else {
            foreach ( $notifications as $notification ) {
                echo '<tr>';
                echo '<td>';
                echo esc_html( $notification[ 'email_sender' ] );
                echo '<div class="row-actions">';
                echo '<span class="id">';
                echo esc_html__( 'ID:', 'pandamusrex-payment-notifications' );
                echo ' ';
                echo esc_html( $notification[ 'id' ] );
                echo ' | ';
                echo '</span>';
                echo '<span class="edit">';
                $edit_url = "?action=edit&notification_id=" . $notification[ 'id' ];
                echo '<a href="' . $edit_url . '">';
                echo esc_html__( 'Edit', 'pandamusrex-payment-notifications' );
                echo '</a>';
                echo ' | ';
                echo '<span class="delete">';
                $delete_url = "?action=delete&notification_id=" . $notification[ 'id' ];
                echo '<a href="' . $delete_url . '">';
                echo esc_html__( 'Delete', 'pandamusrex-payment-notifications' );
                echo '</a>';
                echo '</span>';
                echo '</div>';
                echo '</td>';
                echo '<td>';
                echo esc_html( $notification[ 'email_subject' ] );
                echo '</td>';
                echo '<td>';
                $order_id = $notification[ 'order_id' ];
                if ( $order_id == 0 ) {
                    echo '-';
                } else {
                    echo esc_html( $order_id );
                }
                echo '</td>';
                echo '</tr>';
            }
        }

        echo '<tfoot>';
        echo '<tr>';
        echo '<th scope="col" class="manage-column">' . esc_html__( 'Sender', 'pandamusrex-payment-notifications' ) . '</th>';
        echo '<th scope="col" class="manage-column">' . esc_html__( 'Subject', 'pandamusrex-payment-notifications' ) . '</th>';
        echo '<th scope="col" class="manage-column">' . esc_html__( 'Order', 'pandamusrex-payment-notifications' ) . '</th>';
        echo '<th scope="col" class="manage-column">' . esc_html__( 'Received', 'pandamusrex-payment-notifications' ) . '</th>';
        echo '</tr>';
        echo '</tfoot>';
        echo '</table>';

        echo '</div>';
    }
}

PandamusRex_Payment_Notifications_Admin::get_instance();