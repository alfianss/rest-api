<?php
/*
Plugin Name: Rest API Plugin
Plugin URI: http://example.com
Description: Simple WordPress Rest API
Version: 1.0
Author: Alfian SS
Author URI: http://example.com
*/

class Rest_Api {

    public function ra_wp_enqueue() {

        wp_enqueue_script( 'script-js', plugins_url( '/rest-api.js', __FILE__ ),array('jquery'));
        wp_localize_script( 'script-js', 'wpApiSettings', array(
            'root' => esc_url_raw( rest_url() ),
            'nonce' => wp_create_nonce( 'wp_rest' )
        ) );                

    }

    public function ra_latest_posts( $atts = array() ) {

        $arr_atts = shortcode_atts(array(
            'latest_post'  => 1,                      
        ), $atts);

        $response = wp_remote_get( rest_url() . '/wp/v2/posts?per_page='.$arr_atts['latest_post']);        

        if( !is_wp_error( $response ) && $response['response']['code'] == 200 ) {
            $remote_posts = json_decode($response['body']);
            foreach( $remote_posts as $remote_post ) {
                echo '<div><h2>'. $remote_post->title->rendered . '</h2><p>' . $remote_post->excerpt->rendered . '</p><div>';        
            }
        }
    }

    public function ra_list_posts() {
        $response = wp_remote_get( rest_url() . '/wp/v2/posts');        
        
        $table = '<table border="1">
                    <thead>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Status</th>
                        <th>Action</th>
                    </thead>
                    <tbody id="table-post">';

        if( !is_wp_error( $response ) && $response['response']['code'] == 200 ) {
            $remote_posts = json_decode($response['body']);
            foreach( $remote_posts as $remote_post ) {
                $table .= '<tr>
                                <td>'. $remote_post->title->rendered . '</td>
                                <td>'. $remote_post->excerpt->rendered . '</td>
                                <td>'. $remote_post->status . '</td>
                                <td>
                                    <button data-value="'.$remote_post->id.'" id="btn-edit">Edit</button>&nbsp;
                                    <button data-value="'.$remote_post->id.'" id="btn-delete">Delete</button>
                                </td>
                           </tr>';        
            }            
        }

        $table .= '</tbody</table>';
        echo $table;
    }

    public function ra_form_post() {        
        include "templates/forms.php";        
        $this->ra_list_posts();
    }    

}

$rest_api = new Rest_Api();

add_action('wp_enqueue_scripts', array($rest_api, 'ra_wp_enqueue'));

add_shortcode( 'wp8_get_post', array($rest_api, 'ra_latest_posts'));
add_shortcode( 'wp8_post_form', array($rest_api, 'ra_form_post'));

