<?php /* Template Name: film */ ?>
<?php get_header(); ?>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class='one-film'>
        <h2><?php the_title(); ?></h2>
    <div class='one-film-info'>
        <?php 
        $image = get_field('cover');
        if( !empty($image) ): ?>
	        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
        <?php endif; ?>
        <ul>
            <li><?php the_content(); ?></li>
            <li><strong>Актеры: </strong><?php the_terms( $post->ID, 'actors' ,  ' ' );?></li>
            <li><strong>Год: </strong><?php the_field('year'); ?></li>
            <li><strong>Жанр: </strong><?php the_terms( $post->ID, 'genre' ,  ' ' );?></li>
        </ul>
            
            
                            
                
        </div>
    <?php endwhile; ?>
    </div><!-- post -->
    <?php endif; ?>
<?php get_footer(); ?>
