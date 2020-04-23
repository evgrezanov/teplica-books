<?php
defined('ABSPATH') || exit;

class BOOKS_CPT {
    
    //Init class
    public static function init(){
        add_action( 'init', [__CLASS__, 'register_post_types'] );
        add_action( 'save_post_books', [__CLASS__, 'save_books_meta'] );
    }    

    /**
     * register book post type
     *
     * @return void
     */
    public static function register_post_types(){
	    register_post_type( 'books', [
		    'label'  => null,
		    'labels' => [
			    'name'               => 'Книги',
			    'singular_name'      => 'Книга',
			    'add_new'            => 'Добавить книгу',
			    'add_new_item'       => 'Добавление книги',
			    'edit_item'          => 'Редактирование книги',
			    'new_item'           => 'Новая книга',
			    'view_item'          => 'Смотреть книгу',
			    'search_items'       => 'Искать книгу',
			    'not_found'          => 'Не найдено',
			    'not_found_in_trash' => 'Не найдено в корзине',
			    'menu_name'          => 'Книги',
		    ],
		    'description'           => 'Библиотека книг',
		    'public'                => true,
		    'show_in_menu'          => true,
		    'show_in_rest'          => false,
		    'menu_icon'             => 'dashicons-book-alt',
		    'hierarchical'          => false,
		    'supports'              => ['title', 'editor', 'thumbnail', 'excerpt'],
		    'taxonomies'            => [],
		    'has_archive'           => true,
		    'rewrite'               => true,
            'query_var'             => true,
            'register_meta_box_cb'  => [__CLASS__, 'add_books_metaboxes'],
	    ] );
    }

    /**
    * add metabox for books
    *
    * @return void
    */
    public static function add_books_metaboxes(){
        $screens = array('books');
        add_meta_box(
            'books_fields',
            'Мета-данные книги',
            [__CLASS__, 'display_books_fields'],
            'books',
            'normal',
            'default',
            $screens
        );
    }

    /**
    * display book meta fields
    *
    * @param string $post
    * @param array $meta
    * @return void
    */
    public static function display_books_fields($post, $meta) {
	    //global $post;
	    wp_nonce_field( basename( __FILE__ ), 'book_fields' );
        $publish_date = get_post_meta( $post->ID, 'publish_date', true );
        $authors = get_post_meta( $post->ID, 'authors', true );
	    $publisher = get_post_meta( $post->ID, 'publisher', true );
        echo '<label>Дата издания</label><input type="date" name="publish_date" value="' . esc_textarea( $publish_date )  . '" class="widefat">';
        echo '<label>Список авторов через запятую</label><input type="text" name="authors" value="' . esc_textarea( $authors )  . '" class="widefat">';
        echo '<label>Название издательстваата</label><input type="text" name="publisher" value="' . esc_textarea( $publisher )  . '" class="widefat">';
    }    

    /**
    * save book meta fields
    *
    * @param string $post_id
    * @return void
    */
    public static function save_books_meta( $post_id ) {
        
        if ( wp_is_post_revision( $post_id ) ){
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ):
            return $post_id;
        endif;

	    if ( ! isset( $_POST['publish_date'] ) || ! isset( $_POST['authors'] ) || ! isset( $_POST['publisher'] ) || ! wp_verify_nonce( $_POST['book_fields'], basename(__FILE__) ) ):
            return $post_id;
        endif;
        
        $events_meta['authors'] = esc_textarea( $_POST['authors'] );
        $events_meta['publisher'] = esc_textarea( $_POST['publisher'] );
        $events_meta['publish_date'] = esc_textarea( $_POST['publish_date'] );

	    foreach ( $events_meta as $key => $value ) :

		    if ( get_post_meta( $post_id, $key, false ) ) {
			    update_post_meta( $post_id, $key, $value );
		    } else {
			    add_post_meta( $post_id, $key, $value);
		    }

		    if ( ! $value ) {
			    delete_post_meta( $post_id, $key );
		    }

	    endforeach;

    }
}

BOOKS_CPT::init();