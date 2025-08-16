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
                'supports'            => array( 'title', 'editor', 'comments' )
            )
	    );
    }

    public function add_meta_boxes() {
        add_meta_box( 'pmts_wbhk_lstn_sectionid', __( 'Related Order' ), [ $this, 'related_order_meta_box' ], 'pandamusrex_mailnote', 'normal', 'high' );
        add_meta_box( 'pmts_wbhk_lstn_hist_sectionid', __( 'Related Order History' ), [ $this, 'related_order_history_meta_box' ], 'pandamusrex_mailnote', 'normal', 'high' );
    }

    public function related_order_meta_box( $post) {
        $linked_order_ID = get_post_meta( $post->ID, '_linked_order_id', true );
        if ( empty( $linked_order_ID ) ) {
            // Get all on-hold orders, if any
            $on_hold_orders = wc_get_orders( array(
                'status' => 'on-hold',
                'limit'  => -1, // Retrieve all on-hold orders
            ) );

            if ( $on_hold_orders ) {
                echo "<p>Choose an on-hold order to link to:";

                echo "<select>";

                foreach ( $on_hold_orders as $order ) {
                    $order_id = $order->get_id();
                    $order_user = $order->get_user();
                    $user_name = "Guest";
                    if ( $order_user ) {
                        $user_name = $order_user->user_nicename;
                    }
                    $order_total = $order->get_total();
                    echo "<option value='" . esc_attr( $order_id ) . "'>";
                    $option_text = "#" . $order_id . ' - ' . $user_name . ' - ' . $order_total;
                    echo esc_html( $option_text );
                    echo "</option>";
                }
                echo "</select>";

                echo "</p>";
            }
            else
            {
                echo "<p>There are no on-hold orders to choose from</p>";
            }
        } else {
            echo "<p>Linked order: ";
            echo get_edit_post_link( $post );
            echo "<br/>";
            echo "<a href='#'>Unlink</a>";
            echo "</p>";
        }
    }

    public function related_order_history_meta_box( $post ) {
        echo "<p>History goes here</p>";

        $comments = get_comments( $post->ID );

        echo "<ul>";

        if ( $comments ) {
            foreach ( $comments as $comment ) {
                $comment_id = $comment->comment_ID;
                $comment_datetime = get_comment_date( '', $comment ) . ' ' . get_comment_time( '', false, true, $comment_id );
                $comment_content = $comment->content;
                echo "<li>";
                echo esc_html( $comment_id . ' ' . $comment_datetime . " : " . $comment_content );
                echo "</li>";
            }
        }

        $post_creation_date = get_the_date( '', $post );
        $post_creation_time = get_the_time( '', $post );
        echo "<li>";
        echo esc_html( $post_creation_date . " " . $post_creation_time . " : Notification received" );
        echo "</li>";

        echo "</ul>";

    }
}

PandamusRex_Email_Receipts_Notification_Post_Type::get_instance();
