<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PandamusRex_Email_Webhooks_Rest_Controller extends \WP_REST_Controller {

    protected $namespace = 'pandamusrex/v1';
    protected $rest_base = 'email-webhooks';

    public function register_routes() {
        // TODO - delete this test route before production
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_silence' ],
                'permission_callback' => [ $this, 'check_permission' ],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/',
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [ $this, 'post_email' ],
                'permission_callback' => [ $this, 'check_permission' ],
            ]
        );
    }

    public function get_silence() {
        return 'Silence is golden.';
    }

    public function post_email( $request ) {
        $body = wp_kses_post( $request->get_body() );

        if ( function_exists( 'wc_get_logger' ) ) {
            wc_get_logger()->debug( $body );
        }

        // $id = wp_insert_post( array(
        //     'post_title'  => 'random',
        //     'post_type'   => 'pandamusrex_mailnote',
        //     'post_content' => $body
        // ) );

        return new WP_REST_Response( true, 200 );
    }

    public function check_permission() {
        return TRUE; // TODO lock this down
    }
}
