<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PandamusRex_Email_Webhooks_History_Db {
    public static function getTableName() {
        global $wpdb;
        return $wpdb->prefix . 'pandamusrex_email_wbhks_hst';
    }

    public static function create_tables() {
        global $wpdb;
        $table_name = PandamusRex_Email_Webhooks_History_Db::getTableName();
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) NOT NULL AUTO_INCREMENT,
            webhook_id BIGINT(20) NOT NULL,
            user_id BIGINT(20),
            note VARCHAR(255),
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' ); // Include dbDelta()
        dbDelta( $sql );
    }
}