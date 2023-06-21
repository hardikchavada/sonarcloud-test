<?php

 if( ! defined( 'ABSPATH' ) ) {
    exit;
}

if( ! function_exists( 'ht_register_delete_event_route' ) ){
    /**
     * Rest Route to Delete Events
     */
    function ht_register_delete_event_route() {
        register_rest_route(
            'events/v1',
            '/delete/(?P<id>\d+)',
            array(
                'methods'  => 'DELETE',
                'callback' => 'delete_event',
                'permission_callback' => 'permission_callback_function',
            )
        );
    }
    add_action( 'rest_api_init', 'ht_register_delete_event_route' );
    
    function delete_event( $request ) {

        //url : http://hardik.local/wp-json/events/v1/delete/{post_id}
        
        $post_id = $request->get_param( 'id' );  
        
        $post_data = get_post($post_id); 

        if( ! $post_data ) {
            return rest_ensure_response( 
                array( 
                    'error' => 'No Event Found'
                ) 
            );
        }
    
        wp_delete_post( $post_id, true );
    
        return rest_ensure_response( 
            array( 
                'success' => 'Event Deleted successfully'
            ) 
        );
    }
}  
