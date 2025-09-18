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
            [ $this, 'page_router' ],
            'dashicons-pets',
            59 // below first separator
        );
    }

    public function page_router() {
        if ( ! function_exists( 'wc_get_logger' ) ) {
            wp_admin_notice(
                __( 'PandamusRex Email Webhooks for WooCommerce requires the WooCommerce plugin to be active.', 'pandamusrex-memberships' ),
                [ 'type' => 'error' ]
            );
            return;
        }

        // TODO handle POSTs

        if ( isset( $_GET[ 'action' ] ) ) {
            $notification_id = 0;
            if ( isset( $_GET[ 'notification_id' ] ) ) {
                $notification_id = $_GET[ 'notification_id' ];
                $notification_id = intval( $notification_id );
            }
            if ( $_GET[ 'action' ] == 'edit' ) {
                $this->echo_pmt_notif_edit_page( $notification_id );
                return;
            }

            if ( $_GET[ 'action' ] == 'delete' ) {
                $this->echo_pmt_notif_delete_page( $notification_id );
                return;
            }
        }

        // Otherwise, show them all
        $this->echo_pmt_notif_page();
    }

    public function echo_pmt_notif_page() {
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
        echo '<th scope="col" class="manage-column">' . esc_html__( 'Assigned to Order', 'pandamusrex-payment-notifications' ) . '</th>';
        echo '<th scope="col" class="manage-column">' . esc_html__( 'Received', 'pandamusrex-payment-notifications' ) . '</th>';
        echo '</tr>';
        echo '</thead>';

        $notifications = PandamusRex_Email_Webhooks_Db::get_all_notifications();
        if ( empty( $notifications ) ) {
            echo '<tr class="no-items">';
            echo '<td class="colspanchange" colspan="4">';
            esc_html_e( 'No payment notifications found.', 'pandamusrex-payment-notifications' );
            echo '</td>';
            echo '</tr>';
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
                $edit_url = "?page=pandamusrex_pmt_notif_page&action=edit&notification_id=" . $notification[ 'id' ];
                echo '<a href="' . $edit_url . '">';
                echo esc_html__( 'Edit', 'pandamusrex-payment-notifications' );
                echo '</a>';
                echo ' | ';
                echo '<span class="delete">';
                $delete_url = "?page=pandamusrex_pmt_notif_page&action=delete&notification_id=" . $notification[ 'id' ];
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
                echo '<td>';
                $email_received = $notification[ 'email_received' ];
                echo esc_html( $email_received );
                echo '</td>';
                echo '</tr>';
            }
        }

        echo '<tfoot>';
        echo '<tr>';
        echo '<th scope="col" class="manage-column">' . esc_html__( 'Sender', 'pandamusrex-payment-notifications' ) . '</th>';
        echo '<th scope="col" class="manage-column">' . esc_html__( 'Subject', 'pandamusrex-payment-notifications' ) . '</th>';
        echo '<th scope="col" class="manage-column">' . esc_html__( 'Assigned to Order', 'pandamusrex-payment-notifications' ) . '</th>';
        echo '<th scope="col" class="manage-column">' . esc_html__( 'Received', 'pandamusrex-payment-notifications' ) . '</th>';
        echo '</tr>';
        echo '</tfoot>';
        echo '</table>';

        echo '</div>';
    }

    public function echo_pmt_notif_edit_page( $notification_id ) {
        if ( $notification_id < 1 ) {
            wp_admin_notice(
                __( 'Invalid request - bad notification ID', 'pandamusrex-email-webhooks' ),
                [ 'type' => 'error' ]
            );
            return;
        }

        $notification = PandamusRex_Email_Webhooks_Db::get_notification_by_id( $notification_id );
        if ( is_wp_error( $notification ) ) {
            wp_admin_notice(
                $notification->get_error_message(),
                [ 'type' => 'error' ]
            );
            return;
        }

        echo '<div class="wrap">';
        echo '<h1 class="wp-heading-inline">';
        esc_html_e( 'Edit Payment Notification', 'pandamusrex-email-webhooks' );
        echo '</h1>';
        echo '<hr class="wp-header-end">';

        echo '<form>';
            echo '<div id="poststuff">';
                echo '<div id="post-body" class="metabox-holder columns-2">';
                    echo '<div id="post-body-content">';
                        echo '<h2>';
                        echo esc_html( $notification[ 'email_subject' ] );
                        echo '</h2>';
                        echo '<p>';
                        echo esc_html( $notification[ 'email_received' ] );
                        echo '</p>';
                        echo '<p>';
                        echo esc_html( $notification[ 'email_sender' ] );
                        echo '</p>';
                        echo '<div>';
                            echo esc_html( nl2br( $notification[ 'email_body' ] ) );
                        echo '</div>';
                    echo '</div>';
                    echo '<div id="postbox-container-1" class="postbox-container">';
                        echo '<div id="side-sortables" class="meta-box-sortables">';
                            echo '<div id="submitdiv" class="postbox">';
                                echo '<div class="postbox-header">';
                                    echo '<h2>Save</h2>';
                                echo '</div>';
                                echo '<div class="inside">';
                                    echo '<div class="submitbox" id="submitpost">';
                                        echo '<div id="minor-publishing">';
                                        echo '</div>';
                                        echo '<div id="major-publishing-actions">';
                                            echo '<div id="delete-action">';
                                                echo '<a class="submitdelete deletion" href="#">Move to Trash</a>';
                                                echo '</div>';
                                            echo '<div id="publishing-action">';
                                                echo '<input type="submit" name="publish" id="publish" class="button button-primary button-large" value="Save">';
                                            echo '</div>';
                                            echo '<div class="clear">';
                                            echo '</div>';
                                        echo '</div>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>'; // submitdiv
                        echo '</div>';
                    echo '</div>'; // postbox-container-1
                    echo '<div id="postbox-container-2" class="postbox-container">';
                        echo '<div id="postcustom" class="postbox ">';
                            echo '<div class="postbox-header">';
                                echo '<h2 class="hndle ui-sortable-handle">History</h2>';
                            echo '<div id="postcustomstuff">';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</form>';

        echo '</div>';
    }

    public function echo_pmt_notif_delete_page( $notification_id ) {
        echo '<div class="wrap">';
        echo '<h1 class="wp-heading-inline">';
        esc_html_e( 'Delete Payment Notification', 'pandamusrex-email-webhooks' );
        echo '</h1>';
        echo '<hr class="wp-header-end">';

        echo '<p>&nbsp;</p>';
        echo '</div>';
    }

}

PandamusRex_Payment_Notifications_Admin::get_instance();