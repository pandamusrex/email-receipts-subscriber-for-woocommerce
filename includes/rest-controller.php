<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PandamusRex_Email_Receipts_Rest_Controller extends \WP_REST_Controller {

    protected $namespace = 'persfc/v1';

    protected $rest_base = 'receipts-subscriber';

    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_silence' ],
                'permission_callback' => [ $this, 'check_permission' ],
            ]
        );
    }

    public function get_silence() {
        return 'Silence is golden.';
    }

    public function check_permission() {
        return TRUE; // TODO lock this down for more interesting routes
    }
}
