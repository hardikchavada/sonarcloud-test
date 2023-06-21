<?php

 if( ! defined( 'ABSPATH' ) ) {
    exit;
}


if( ! function_exists( 'ht_register_update_event_route' ) ){
    /**
     * Rest Route to Update Events.
     */
    function ht_register_update_event_route() {
        register_rest_route(
            'events/v1',
            '/update/(?P<id>\d+)',
            array(
                'methods'  => 'POST',
                'callback' => 'update_event',
                'permission_callback' => 'permission_callback_function',
            )
        );
    }
    add_action( 'rest_api_init', 'ht_register_update_event_route' );
            
    function update_event( $request ) {
        //url : http://hardik.local/wp-json/events/v1/update/{post_id}

        $post_id   = $request->get_param( 'id' );
        $event_data = $request->get_params();
    
        $event_args = array(
            'ID'           => $post_id,
            'post_title'   => sanitize_text_field( $event_data['title'] ),
            'post_content' => sanitize_text_field( $event_data['content'] ),
        );
    
        $updated = wp_update_post( $event_args );
    
        if ( is_wp_error( $updated ) ) {
            return rest_ensure_response( 
                array( 
                    'error' => $updated->get_error_message() 
                )
            );
        }
        
        update_post_meta($post_id, 'start', $event_data['start'] );
        update_post_meta($post_id, 'end', $event_data['end'] ); 
        
        //update category
        $event_cat_terms = get_terms(
            array(
                'taxonomy' => 'event_category',
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
        
        return rest_ensure_response( 
            array( 
                'success' => 'Event updated successfully.' 
            ) 
        );
    }
}
