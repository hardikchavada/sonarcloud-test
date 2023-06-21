<?php
/*
 * Plugin Name:       Hardik Test
 * Description:       This is a custom test plugin created by Hardik for basic API CRUD operations
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Hardik Chavada
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       hardik-test
 * Domain Path:       /languages
 */

 if( ! defined( 'ABSPATH' ) ) {
    exit;
 }

if( ! function_exists( 'ht_register_events_custom_post_type' ) ){
    /**
     * Register Events CPT
     */
    function ht_register_events_custom_post_type() {
        $labels = array(
            'name'                  => 'Events',
            'singular_name'         => 'Event',
            'menu_name'             => 'Events',
            'all_items'             => 'All Events',
            'add_new'               => 'Add New',
            'add_new_item'          => 'Add New Event',
            'edit_item'             => 'Edit Event',
            'new_item'              => 'New Event',
            'view_item'             => 'View Event',
            'search_items'          => 'Search Events',
            'not_found'             => 'No events found',
            'not_found_in_trash'    => 'No events found in Trash',
            'parent_item_colon'     => 'Parent Event:',
            'featured_image'        => 'Event Image',
            'set_featured_image'    => 'Set event image',
            'remove_featured_image' => 'Remove event image',
            'use_featured_image'    => 'Use as event image',
            'archives'              => 'Event archives',
            'insert_into_item'      => 'Insert into event',
            'uploaded_to_this_item' => 'Uploaded to this event',
            'filter_items_list'     => 'Filter events list',
            'items_list_navigation' => 'Events list navigation',
            'items_list'            => 'Events list',
        );
    
        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'show_in_rest'        => true,
            'has_archive'         => true,
            'publicly_queryable'  => true,
            'query_var'           => true,
            'rewrite'             => array( 'slug' => 'events' ),
            'capability_type'     => 'post',
            'supports'            => array( 'title', 'editor', 'thumbnail' ),
            'menu_icon'           => 'dashicons-calendar',
        );
    
        register_post_type( 'events', $args );
    }
    add_action( 'init', 'ht_register_events_custom_post_type' );
}



if( ! function_exists( 'ht_create_event_category_taxonomy' ) ){
    /**
     * Register Event category Taxonomy
     */
    function ht_create_event_category_taxonomy() {
        $labels = array(
            'name'                       => 'Event Categories',
            'singular_name'              => 'Event Category',
            'menu_name'                  => 'Categories',
            'all_items'                  => 'All Categories',
            'parent_item'                => 'Parent Category',
            'parent_item_colon'          => 'Parent Category:',
            'new_item_name'              => 'New Category Name',
            'add_new_item'               => 'Add New Category',
            'edit_item'                  => 'Edit Category',
            'update_item'                => 'Update Category',
            'view_item'                  => 'View Category',
            'separate_items_with_commas' => 'Separate categories with commas',
            'add_or_remove_items'        => 'Add or remove categories',
            'choose_from_most_used'      => 'Choose from the most used categories',
            'popular_items'              => 'Popular Categories',
            'search_items'               => 'Search Categories',
            'not_found'                  => 'No categories found',
            'no_terms'                   => 'No categories',
            'items_list'                 => 'Categories list',
            'items_list_navigation'      => 'Categories list navigation',
        );

        $args = array(
            'labels'            => $labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'event-category' ),
        );

        register_taxonomy( 'event_category', 'events', $args );
    }
    add_action( 'init', 'ht_create_event_category_taxonomy' );
}



// Rest route List
require_once plugin_dir_path(__FILE__) . 'list.php';

// Rest route create.
require_once plugin_dir_path(__FILE__) . 'create.php';

//Register rest route to delete event.
require plugin_dir_path(__FILE__) . 'delete.php';

//Register rest route to update event.
require plugin_dir_path(__FILE__) . 'update.php';

//Register rest route to update event.
require plugin_dir_path(__FILE__) . 'show.php';



function permission_callback_function() {
    /**
     * Permission callback function for custom routes
     * If user is not admin, will return false
     * comment added by BRANCH3
     */
    $current_user = wp_get_current_user();
    if ( ! in_array('administrator', $current_user->roles) ) {
        return false;
    }
    return true;
}

/**
 * new comment added by BRANCH 3
 */