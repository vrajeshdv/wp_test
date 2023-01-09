<?php
add_action( 'after_setup_theme', 'blankslate_setup' );
function blankslate_setup() {
load_theme_textdomain( 'blankslate', get_template_directory() . '/languages' );
add_theme_support( 'title-tag' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'responsive-embeds' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'html5', array( 'search-form', 'navigation-widgets' ) );
add_theme_support( 'woocommerce' );
global $content_width;
if ( !isset( $content_width ) ) { $content_width = 1920; } 
}
 
 
add_action( 'wp_enqueue_scripts', 'blankslate_enqueue' );
function blankslate_enqueue() {
wp_enqueue_style( 'blankslate-style', get_stylesheet_uri() );
wp_enqueue_script( 'jquery' );
}
function blankslate_schema_type() {
    $schema = 'https://schema.org/';
    if ( is_single() ) {
    $type = "Article";
    } elseif ( is_author() ) {
    $type = 'ProfilePage';
    } elseif ( is_search() ) {
    $type = 'SearchResultsPage';
    } else {
    $type = 'WebPage';
    }
    echo 'itemscope itemtype="' . esc_url( $schema ) . esc_attr( $type ) . '"';
}

// Disable Gutenberg on the back end.
add_filter( 'use_block_editor_for_post', '__return_false' );
// Disable Gutenberg for widgets.
add_filter( 'use_widgets_block_editor', '__return_false' );
add_action( 'wp_enqueue_scripts', function() {
    // Remove CSS on the front end.
    wp_dequeue_style( 'wp-block-library' );
    // Remove Gutenberg theme.
    wp_dequeue_style( 'wp-block-library-theme' );
    // Remove inline global CSS on the front end.
    wp_dequeue_style( 'global-styles' );
}, 20 );
 
require_once "wp-list.php";
 
 

function smallenvelop_login_message( $message ) {
    if ( empty($message) ){
        return "<p><strong>Welcome to my Test. Please login.</strong><br />User : admin <br/> pass : admin</p>";
    } else {
        return $message;
    }
}

add_filter( 'login_message', 'smallenvelop_login_message' );