<?php

if( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( ! function_exists( 'ht_register_create_event_route' ) ){    
    /**
     * Rest Route to create Events
     */
    function ht_register_create_event_route() {
        register_rest_route(
            'events/v1',
            '/create',
            array(
                'methods'  => 'POST',
                'callback' => 'create_event',
                'permission_callback' => 'permission_callback_function',
            )
        );
    }
    add_action( 'rest_api_init', 'ht_register_create_event_route' );
    
    function create_event( $request ) {
        //url : http://hardik.local/wp-json/events/v1/create
        $event_data = $request->get_params();
    
        $event_args = array(
            'post_type'    => 'events',
            'post_title'   => sanitize_text_field( $event_data['title'] ),
            'post_content' => sanitize_text_field( $event_data['content'] ),           
            'post_status'  => 'publish',
        );
    
        $post_id = wp_insert_post( $event_args );
    
        $event_cat_terms = get_terms(
            array(
                'taxonomy'   => 'event_category',
                'hide_empty' => false,
            )
        );
    
        foreach($event_cat_terms as $term) {
            $term_id = $term->term_id;
            $term_name = $term->name;
            
            if($term_name == $event_data['category']) {
                $event_args['event_category'] = $term_id;
                wp_set_post_terms($post_id, $term_id, 'event_category');          
            }        
        }
    
        update_post_meta($post_id, 'start', $event_data['start'] );
        update_post_meta($post_id, 'end', $event_data['end'] );    
    
        if ( is_wp_error( $post_id ) ) {
            return rest_ensure_response( 
                array( 'error' => $post_id->get_error_message() ) 
            );
        }
    
        return rest_ensure_response( 
            array( 
                'success' => 'Event Created successfully'
            ) 
        );
    }
}
