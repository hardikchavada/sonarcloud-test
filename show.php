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


/**
 * Register a custom menu page.
 */
function register_new_menuPage_hardik(){
	add_menu_page( 
		__( 'Hardik', 'textdomain' ),
		'Hardik',
		'manage_options',
		'hardik',
		'hardik_menu_callback',
		'dashicons-welcome-widgets-menus',
		6
	); 
}
add_action( 'admin_menu', 'register_new_menuPage_hardik' );

/**
 * Display a custom menu page
 */
function hardik_menu_callback(){
  
  // require_once plugin_dir_path(__FILE__) . 'singleton.php';
  require_once plugin_dir_path(__FILE__) . 'wp-query.php';
  die;
  ?>
  <form id="hc_form">
    <table>
      <tr>
        <td> <input type="text" name="name" id="hc_name"> </td>
      </tr>
      <tr>
        <td> <input type="text" name="email" id="hc_email"> </td>
      </tr>
      <?php 
      wp_nonce_field('hc_form_nonce_action', 'hc_form_nonce');
      ?>
      <tr>
        <td> <input type="submit" id="hc_btn" value="submit"> </td>
      </tr>
    </table>    
    
    
  </form>
  <?php
  die;
	global $wpdb;
  // echo '<pre>';
  // print_r($wpdb);select * from wp_postmeta where meta_value LIKE '2023-05-25%'
  $result = $wpdb->get_results(" select * from $wpdb->postmeta where meta_key = 'start' AND meta_value LIKE '2023-05-25%' ");
  echo '<pre>';
  // print_r($result);

  // $row = $wpdb->get_row("select * from wp_postmeta where post_id = 332");  
  $row = $wpdb->get_results( $wpdb->prepare(" select * from $wpdb->postmeta where meta_key = %s AND meta_value LIKE %s ", 'start', '2023-05-25%') );
  // echo '<pre>';
  // print_r($row);

  $args = array(
    'fields' => 'ids',
    'post_type'     => 'events',
    'meta_query' => array(
      array(
        'key' => 'start',
        'value' => '2023-05-25',
        'compare' => 'LIKE',
      ),
    ),
  );

    $res = get_posts($args);
  //   echo '<pre>';
  // print_r($res); 
  
  /**
   * String functions 
   */
  $str = "Hello Hardik. It's a beautiful day.";
  echo $str;
  echo '<br>';
  //explode: splits a string into an array
  $exp = explode( " ", $str);
  echo '<pre>';
  print_r($exp);

  //implode: JOins array elements into a string
  echo '<br>';
  $arr = array('My', 'name', 'is', 'Hardik');
  $arr_to_str = implode( " ", $arr);
  echo $arr_to_str;

  //chop: removed characted from right end of the string
  echo '<br>';
  $chop = chop($str, 'day.');
  echo $chop;

  //ltrim: removed characted from left side of the string
  echo '<br>';  
  $ltrim = ltrim($str, "Hello ");  
  echo $ltrim;

  //ucfirst: Convert the first character of "hello" to uppercase:
  echo '<br>';  
  $ucfirst = ucfirst('hello world');
  echo $ucfirst;

  //ucwords: Convert the first character of each word to uppercase:
  echo '<br>';  
  $ucwords = ucwords('hello world');
  echo $ucwords;
  
}