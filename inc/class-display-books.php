<?php
defined('ABSPATH') || exit;

class BOOKS_VIEW {
    
    //Init class
    public static function init(){
		add_shortcode( 'display_books', [__CLASS__, 'display_books'] );
		add_filter( 'the_content', [__CLASS__, 'single_book_content'] );
    }    
	/**
	 * short code for display books list
	 *
	 * @return void
	 */
    public static function display_books(){
		global $post;
	    $args = array(
			'post_type' => 'books',
			'numberposts' => -1,
		);
		$posts = get_posts($args);
		ob_start();
			?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <?php
		if ($posts):	
			foreach( $posts as $post ):
				setup_postdata($post);
		?>
            <div class="card mb-3" style="max-width: 540px;">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <img src="<?php echo get_the_post_thumbnail_url( $post->ID, 'full' ) ?>" class="card-img"
                            alt="<?php the_title(); ?>">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <a href="<?php the_permalink(); ?><h5 class=" card-title"><?php the_title(); ?></h5></a>
                            <p class="card-text"><?php the_excerpt(); ?></p>
                            <?php if ( $publisher = get_post_meta($post->ID, 'publisher', true) ): ?>
                            <p class="card-text"><small class="text-muted">Издательство:
                                    <?php echo $publisher; ?></small></p>
                            <?php endif; ?>
                            <?php if ( $publish_date = get_post_meta($post->ID, 'publish_date', true) ): ?>
                            <p class="card-text"><small class="text-muted">Дата издания:
                                    <?php echo $publish_date; ?></small></p>
                            <?php endif; ?>
                            <?php if ( $authors = get_post_meta($post->ID, 'authors', true) ): ?>
                            <p class="card-text"><small class="text-muted">Авторы: <?php echo $authors; ?></small></p>
                            <?php endif; ?>
                            <a href="<?php the_permalink(); ?>" class="btn">Подробнее</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
			endforeach;
		else:
			?>
            <div class="alert alert-primary" role="alert">
                Добавьте книгу в библиотеку!
            </div>
            <?php	
		endif;	
		?>
        </div>
    </div>
</div>
<?php
		wp_reset_postdata();
		return ob_get_clean();
	}
	/**
	 * display single book content
	 *
	 * @param string $content
	 * @return void
	 */
	public static function single_book_content($content){
		global $post;
		if (is_singular('books')):
			ob_start();
		?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <img src="<?php echo get_the_post_thumbnail_url( $post->ID, 'full' ) ?>" class="card-img-top"
                    alt="<?php echo $post->post_title; ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $post->post_title; ?></h5>
                    <?php if ( $publisher = get_post_meta($post->ID, 'publisher', true) ): ?>
                    <p class="card-text"><small class="text-muted">Издательство: <?php echo $publisher; ?></small></p>
                    <?php endif; ?>
                    <?php if ( $publish_date = get_post_meta($post->ID, 'publish_date', true) ): ?>
                    <p class="card-text"><small class="text-muted">Дата издания: <?php echo $publish_date; ?></small>
                    </p>
                    <?php endif; ?>
                    <?php if ( $authors = get_post_meta($post->ID, 'authors', true) ): ?>
                    <p class="card-text"><small class="text-muted">Авторы: <?php echo $authors; ?></small></p>
                    <?php endif; ?>
                    <p class="card-text"><?php echo $content; ?> </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
			$content =  ob_get_clean();
		endif;	
		return $content;
	}
}

BOOKS_VIEW::init();