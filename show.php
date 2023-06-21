<?php

 if( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( ! function_exists( 'ht_register_events_show_by_id_route' ) ){
    /**
     * Rest Route to show Events based on ID.
     */
    function ht_register_events_show_by_id_route() {
        register_rest_route(
            'events/v1',
            '/show',
            array(
                'methods'  => 'GET',
                'callback' => 'show_events',
                'permission_callback' => 'permission_callback_function',
            )
        );
    }
    add_action( 'rest_api_init', 'ht_register_events_show_by_id_route' );
    
    function show_events( $request ) {
        //url : http://hardik.local/wp-json/events/v1/show?id={post_id}

        $post_id = $request->get_param( 'id' );
        if( ! $post_id ) {
            return 'ID of the Event should be passed';
        }
        $event_data = get_post($post_id); 

        if( ! $event_data ) {
            return 'No event found';
        }
        
        $response = array(
            'title'            => $event_data->post_title,
            'Start'            => get_post_meta($post_id, 'start', true),
            'End'              => get_post_meta($post_id, 'end', true),
            'description'      => $event_data->post_content,
            'Event categories' => wp_get_post_terms( $post_id, 'event_category', array( 'fields' => 'names' ) ),
        );
        
        return rest_ensure_response( $response );
    }
}


