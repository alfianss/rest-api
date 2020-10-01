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

        $response = wp_remote_get( get_site_url( get_option('blogid'), '/wp-json/wp/v2/posts?per_page='.$arr_atts['latest_post']));        

        if( !is_wp_error( $response ) && $response['response']['code'] == 200 ) {
            $remote_posts = json_decode($response['body']);
            foreach( $remote_posts as $remote_post ) {
                echo '<div><h2>'. $remote_post->title->rendered . '</h2><p>' . $remote_post->excerpt->rendered . '</p><div>';        
            }
        }
    }

    public function ra_save_form() {

        if(isset($_POST['submit'])) {
            $response = wp_remote_post( get_site_url( get_option('blogid'), '/wp-json/wp/v2/posts'), 
                array(                                
                    'method'      => 'POST',  
                    'headers'     => [
                        'Content-Type'=> 'application/json',
                        'X-WP-Nonce' => wp_create_nonce( 'wp_rest' ),
                    ],              
                    'body'        => array(
                        'title' => isset($_POST['title']) ? $_POST['title'] : null,
                        'content' => isset($_POST['content']) ? $_POST['content'] : null,
                        'status' => isset($_POST['status']) ? $_POST['status'] : null,
                    )
                )                
            );

            
            if( !is_wp_error( $response ) && $response['response']['code'] == 200 ) {
                echo "Success.";
            } else {
                var_dump($response);        
                echo "Sorry, can't process save form.";
            }
        }
    }


    public function ra_form_post() {
        
        include "templates/forms.php";
    }

    

}

$rest_api = new Rest_Api();

add_action('wp_enqueue_scripts', array($rest_api, 'ra_wp_enqueue'));
// add_action('wp_enqueue_scripts', array($autocomplete, 'enqueue_autocomplete'));

add_shortcode( 'wp8_get_post', array($rest_api, 'ra_latest_posts'));
add_shortcode( 'wp8_post_form', array($rest_api, 'ra_form_post'));

