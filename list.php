<?php

 if( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( ! function_exists( 'ht_register_events_list_route' ) ){
    /**
     * Rest Route to List Events based on date.
     */
    function ht_register_events_list_route() {
        register_rest_route(
            'events/v1',
            '/list',
            array(
                'methods'  => 'GET',
                'callback' => 'get_events_list',
                'permission_callback' => 'permission_callback_function',
            )
        );
    }
    add_action( 'rest_api_init', 'ht_register_events_list_route' );
    
    function get_events_list( $request ) {
        //url : http://hardik.local/wp-json/events/v1/list?date=2023-05-31

        $date = $request->get_param( 'date' );

        $args = array(
            'post_type'      => 'events',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => 'start',
                    'value'   => $date,
                    'compare' => 'LIKE',                
                ),
            ),
        );

        $events = get_posts( $args );

        if( ! $events ) {
            return rest_ensure_response( 
                array( 
                    'error' => 'No Event Found'
                ) 
            );
        }

        $data = array();

        foreach ( $events as $event ) {
            $data[] = array(
                'id'               => $event->ID,
                'title'            => $event->post_title,
                'Start'            => get_post_meta($event->ID, 'start', true),
                'End'              => get_post_meta($event->ID, 'end', true),
                'description'      => $event->post_content,
                'Event categories' => wp_get_post_terms( $event->ID, 'event_category', array( 'fields' => 'names' ) ),
            );
        }

        return rest_ensure_response( $data );
    }
}


