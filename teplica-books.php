<?php
/**
 * Plugin Name:       teplica-books
 * Plugin URI:        https://www.upwork.com/freelancers/~01ea58721977099d53
 * Description:       Тестовое задание Теплица.
 * Version:           1.1
 * Author:            Evgeniy Rezanov
 * Author URI:        https://www.upwork.com/freelancers/~01ea58721977099d53
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

//Main class
class BOOKS {
    
    // The init
    public static function init(){
        require_once('inc/class-books-cpt.php');
        require_once('inc/class-display-books.php');
        add_action('wp_enqueue_scripts', [__CLASS__, 'assets']);
        register_activation_hook( __FILE__, [__CLASS__, 'my_rewrite_flush'] );
    }
    /**
     * add butstrap styles
     *
     * @return void
     */
    public static function assets(){
        wp_enqueue_style(
            'bootstrap', 
            plugin_dir_url(__FILE__).'/asset/bootstrap.min.css',
            array(),
            time()
        );
    }
    /**
     * rewrite permalink after plugin activated
     *
     * @return void
     */
    public static function my_rewrite_flush() {
        BOOKS_CPT::register_post_types();
        flush_rewrite_rules();
    }
}

BOOKS::init();