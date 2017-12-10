<?php /* Template Name: films */ ?>
<?php get_header(); ?>
<?php $film = new WP_Query( array( 'post_type' => 'films', 'posts_per_page' => 10 ) ); ?>

<?php while ( $film->have_posts() ) : $film->the_post(); ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div>
            <h2><?php the_title(); ?></h2> 
        </div>
        <?php 

        $image = get_field('cover');

        if( !empty($image) ): ?>

	        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />

        <?php endif; ?>
        <div>
            <ul class="film-info">
                <li>
                    <strong>Жанр: </strong>
                    <?php the_terms( $post->ID, 'genre' ,  ' ' );?>
                </li>
                <li><strong>Год:</strong> <?php the_field('year'); ?></li>
                <li><a href="<?php the_permalink() ?>"><strong>Читать про фильм!</strong></a></li>
            </ul>
        </div>

    </div><!-- post -->
<?php endwhile; ?>
<?php get_footer(); ?>
