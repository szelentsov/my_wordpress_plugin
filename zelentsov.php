<?php
/**
Plugin name: zelentsov
Description: вывод постов с доп.полями
**/

function create_films() {
    register_post_type( 'films',
        array(
            'labels' => array(
                'name' => 'Films',
                'singular_name' => 'Films',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Films',
                'edit' => 'Edit',
                'edit_item' => 'Edit Films',
                'new_item' => 'New Films',
                'view' => 'View',
                'view_item' => 'View Films',
                'search_items' => 'Search Films',
                'not_found' => 'No Films found',
                'not_found_in_trash' => 'No Films found in Trash',
                'parent' => 'Parent Films'
            ),
            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
            'taxonomies' => array( '' ),
            'menu_icon' => 'dashicons-format-video',
            'has_archive' => true
        )
    );
}

add_action( 'init', 'create_films' );

function myplugin_scripts() {
    wp_register_style( 'zelentsov-style',  plugin_dir_url( __FILE__ ) . 'zelentsov-style.css' );
    wp_enqueue_style( 'zelentsov-style' );
}
add_action( 'wp_enqueue_scripts', 'myplugin_scripts' );

function create_my_taxonomies_genry() {
    register_taxonomy(
        'genre',
        'films',
        array(
            'labels' => array(
                'name' => 'Films Genre',
                'add_new_item' => 'Add New Films Genre',
                'new_item_name' => "New Films Type Genre"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => false,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'genre', 'with_front' => false )
        )
    );
}

add_action( 'init', 'create_my_taxonomies_genry', 0 );

function create_my_taxonomies_actors() {
    register_taxonomy(
        'actors',
        'films',
        array(
            'labels' => array(
                'name' => 'Films Actors',
                'add_new_item' => 'Add New Films Actors',
                'new_item_name' => "New Films Type Actors"
            ),
            'show_ui' => true,
            'show_tagcloud' => false,
            'hierarchical' => false,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'actors', 'with_front' => false )
        )
    );
}

add_action( 'init', 'create_my_taxonomies_actors', 0 );

/*** поля img+year ***/

function my_extra_fields_content( $post ) {
    $preview = get_post_meta($post->ID, 'post_preview', 1);
    $year = get_post_meta($post->ID, 'post_year', 1);
?> 

    <label for="post_preview">
        <h4>Превью записи</h4>
        <input id="post_preview_button" type="button" class="button" value="Загрузить" /> 
    </label> 
    <label for="post_year">
        <h4>Год выпуска фильма</h4>
        <input id="post_year" type="number" size="4" name="post_year" value="<?php echo $year; ?>" />
    </label>
    <input type="hidden" name="extra_field_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>
<?php  
}

function my_add_extra_fields() {  
    add_meta_box( 'extra_fields', 'Допполнительные записи', 'my_extra_fields_content', 'films', 'normal', 'high'  );  
}

add_action('add_meta_boxes', 'my_add_extra_fields', 1);

function my_add_upload_scripts() {  
    wp_enqueue_script('media-upload');  
    wp_enqueue_script('thickbox');  
    wp_register_script(  
                'my-upload-script'   
                ,plugin_dir_url( __FILE__ ) .  'zelentsov.js'  
                ,array('jquery','media-upload','thickbox')  
    );  
    wp_enqueue_script('my-upload-script');  
}  
  
add_action('admin_print_scripts', 'my_add_upload_scripts');

function my_extra_fields_content_update( $post_id ){ 
    if ( !wp_verify_nonce($_POST['extra_field_nonce'], __FILE__) )
        return false;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE  )  
        return false; 

    $extra_fields = array(  
        'post_preview' => $_POST['post_preview'],
        'post_year' => $_POST['post_year']
    );
    $extra_fields = array_map('trim', $extra_fields);

    foreach( $extra_fields as $key=>$value ){  
            if( emptyempty($value) )  
                delete_post_meta($post_id, $key);  
            if($value)  
                update_post_meta($post_id, $key, $value);  
    }  
  
    return $post_id; 
}

add_action('save_post', 'my_extra_fields_content_update', 0);

/* шорткоды */
function get_all_films(){
?>
<?php $film = new WP_Query( array( 'post_type' => 'films', 'posts_per_page' => 10 ) ); ?>

<?php while ( $film->have_posts() ) : $film->the_post(); ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div>
            <h2><?php the_title(); ?></h2> 
        </div>
        <?php  $post_preview = get_post_meta($post->ID, 'post_preview', 1); ?>
        <?php $uploads = wp_upload_dir();?>
        <?php echo '<img src="'. esc_url( $uploads['baseurl']) . $post_preview . '">'?>
        <div>
            <ul class="film-info">
                <li>
                    <strong>Жанр: </strong>
                    <?php the_terms( $post->ID, 'genre' ,  ' ' );?>
                </li>
                <li><a href="<?php the_permalink() ?>"><strong>Читать про фильм!</strong></a></li>
            </ul>
        </div>

    </div><!-- post -->
<?php endwhile; ?>
<?php
}

add_shortcode('films', 'get_all_films');

function get_film(){
?>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class='one-film'>
        <h2><?php the_title(); ?></h2>
    <div class='one-film-info'> 
        <?php  $post_preview = get_post_meta($post->ID, 'post_preview', 1); ?>
        <?php $uploads = wp_upload_dir();?>
        <?php echo '<img src="'. esc_url( $uploads['baseurl']) . $post_preview . '">'?>
        <ul>
            <li><?php the_content(); ?></li>
            <li><strong>Актеры: </strong><?php the_terms( $post->ID, 'actors' ,  ' ' );?></li>
            <li><strong>Год: </strong><?php echo get_post_meta($post->ID, 'post_year', 1); ?></li>
            <li><strong>Жанр: </strong><?php the_terms( $post->ID, 'genre' ,  ' ' );?></li>
        </ul>        
        </div>
    <?php endwhile; ?>
    </div><!-- post -->
    <?php endif; ?>
<?php
}
add_shortcode('film', 'get_film');
?>
