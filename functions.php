<?php

function get_enqueue_file_user()
{
    wp_enqueue_script(
        'ajax_script',
        get_stylesheet_directory_uri() . '/js/ajax.js',
        array('jquery')
    );
    wp_localize_script('ajax_script', 'ajax_var', array(
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ajax-nonce')
    ));
    wp_enqueue_style(
        'child-theme',
        get_template_directory_uri() . "/style.css",
    );
    wp_enqueue_style(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css'
    );
    wp_enqueue_style(
        'bootstrap-icon',
        'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css'
    );
}
add_action('wp_enqueue_scripts', 'get_enqueue_file_user');

function add_panle_customize_register($wp_customize)
{
    $wp_customize->add_setting('twitter', array(
        'default' => 'https://',
        'transport' => 'refresh',
    ));
    $wp_customize->add_section('social_media_link', array(
        'title' => 'Social Media Link',
        'priority' => 9,
    ));
    $wp_customize->add_control('twitter', array(
        'label' => 'twitter',
        'section' => 'social_media_link',
        'settings' => 'twitter',
    ));
    $wp_customize->add_setting('facebook', array(
        'default' => 'https://',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('facebook', array(
        'label' => 'FaceBook',
        'section' => 'social_media_link',
        'settings' => 'facebook',
    ));
    $wp_customize->add_setting('instagram', array(
        'default' => 'https://',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('instagram', array(
        'label' => 'Instagram',
        'section' => 'social_media_link',
        'settings' => 'instagram',
    ));
    $wp_customize->add_setting('linkedin', array(
        'default' => 'https://',
        'transport' => 'refresh',
    ));
    $wp_customize->add_control('linkedin', array(
        'label' => 'LinkedIn',
        'section' => 'social_media_link',
        'settings' => 'linkedin',
    ));
}
add_action('customize_register', 'add_panle_customize_register');

function create_testimonials_function()
{
    $supports = array(
        'title', // post title
        'editor', // post content
        'author', // post author
        'thumbnail', // featured images
        'excerpt', // post excerpt
        'custom-fields', // custom fields
        'comments', // post comments
        'revisions', // post revisions
        'post-formats', // post formats
    );
    $labels = array(
        'name' => _x('Testimonials', 'post type general name', 'your_text_domain'),
        'singular_name' => _x('Testimonials', 'post type Singular name', 'your_text_domain'),
        'add_new' => _x('Add Testimonials', '', 'your_text_domain'),
        'add_new_item' => __('Add New Testimonials', 'your_text_domain'),
        'edit_item' => __('Edit Testimonials', 'your_text_domain'),
        'new_item' => __('New Testimonials', 'your_text_domain'),
        'all_items' => __('All Testimonials', 'your_text_domain'),
        'view_item' => __('View Testimonials', 'your_text_domain'),
        'search_items' => __('Search Testimonials', 'your_text_domain'),
        'not_found' => __('No Testimonials found', 'your_text_domain'),
        'not_found_in_trash' => __('No Testimonials on trash', 'your_text_domain'),
        'parent_item_colon' => '',
        'menu_name' => __('Testimonials', 'your_text_domain')
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'testimonials'),
        'capability_type' => 'page',
        'has_archive' => true,
        'hierarchical' => true,
        'menu_position' => null,
        'menu_icon' => 'dashicons-format-gallery',
        'supports' => $supports
    );
    $labels = array(
        'name' => __('Testimonials Category'),
        'singular_name' => __('Testimonials Category'),
        'search_items' => __('Search'),
        'popular_items' => __('More Used'),
        'all_items' => __('All Testimonials Categories'),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __('Add new'),
        'update_item' => __('Update'),
        'add_new_item' => __('Add new Testimonials Category'),
        'new_item_name' => __('New')
    );
    register_taxonomy(
        'testimonials_category',
        array('testimonials'),
        array(
            'hierarchical' => true,
            'labels' => $labels,
            'singular_label' => 'testimonials_category',
            'all_items' => 'testimonials_Category',
            'query_var' => true,
            'rewrite' => array('slug' => 'cat')
        )
    );
    register_post_type('testimonials', $args);
    flush_rewrite_rules();
}
add_action('init', 'create_testimonials_function');

function testimonial_shortcode_callback($atts)
{
    ob_start();
    $cat = get_terms(['taxonomy' => 'testimonials_category']);
    foreach ($cat as $cat) {
        if ($cat->name == $atts['cat']) {
            $catId = $cat->term_id;
        }
    }
    extract(shortcode_atts(array(
        'testimonialId_name' => $catId,
    ), $atts));
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'testimonials',
        'posts_per_page' => 6,
        'paged' => $paged,
        'orderby' => 'title',
        'order' => 'ASC',
        'tax_query' => array(
            array(
                'taxonomy' => 'testimonials_category',
                'field' => 'term_id',
                'terms'    => array('cat' => $testimonialId_name)
            )
        )
    );
    $testimonial_query = new WP_Query($args);
    // run the loop based on the query
    if ($testimonial_query->have_posts()) : ?>
        <ul class="testimonial-listing">
            <?php
            while ($testimonial_query->have_posts()) : $testimonial_query->the_post();
            ?>
                <li id="testimonial-<?php the_ID(); ?>">
                    <h4><a href="<?php the_permalink(); ?>" title="Read"><?php the_title(); ?></a></h4>
                    <?php the_excerpt() ?>
                    <?php the_date() ?>
                </li>
            <?php
            endwhile;
            $big = 999999999; // need an unlikely integer
            echo paginate_links(array(
                'base' => str_replace($big, '%#%', get_pagenum_link($big)),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $testimonial_query->max_num_pages
            ));
            wp_reset_postdata();
            ?>
        </ul>
    <?php
    endif;
    //  wp_list_categories();
    $testimonial_output = ob_get_clean();
    return $testimonial_output;
}
add_shortcode('testimonial', 'testimonial_shortcode_callback');
add_action('init', 'testimonial_shortcode_callback');

function testimonial_shortcode_loadMore($atts)
{
    ob_start();
    $cat = get_terms(['taxonomy' => 'testimonials_category']);
    foreach ($cat as $cat) {
        if ($cat->name == $atts['cat']) {
            $catId = $cat->term_id;
        }
    }
    extract(shortcode_atts(array(
        'testimonialId_name' => $catId,
    ), $atts));
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'testimonials',
        'posts_per_page' => 6,
        'paged' => $paged,
        'orderby' => 'title',
        'order' => 'ASC',
        'tax_query' => array(
            array(
                'taxonomy' => 'testimonials_category',
                'field' => 'term_id',
                'terms'    => array('cat' => $testimonialId_name)
            )
        )
    );
    $testimonial_query = new WP_Query($args);
    if ($testimonial_query->have_posts()) :
    ?>
        <ul class="testimonial-listing">
            <?php
            while ($testimonial_query->have_posts()) : $testimonial_query->the_post();
            ?>
                <li id="testimonial-<?php the_ID(); ?>">
                    <h4><a href="<?php the_permalink(); ?>" title="Read"><?php the_title(); ?></a></h4>
                    <?php the_excerpt() ?>
                    <?php the_date() ?>
                </li>
            <?php
            endwhile;
            ?>
        </ul>
    <?php
        wp_reset_postdata();
    endif;
    //  wp_list_categories();
    $maxPage = $testimonial_query->max_num_pages;
    ?>
    <button id="loadId" class="mx-5" style="width:15%;" onclick='get_data("<?php echo $maxPage ?>" ,"<?php echo $testimonialId_name ?>")'>Load More</button>
    <?php
    $testimonial_output = ob_get_clean();
    return $testimonial_output;
}
add_shortcode('loadmore', 'testimonial_shortcode_loadMore');

function weichie_load_more()
{
    if (wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
        $args = array(
            'post_type' => 'testimonials',
            'posts_per_page' => 6,
            'paged' => $_POST['paged'],
            'orderby' => 'title',
            'order' => 'ASC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'testimonials_category',
                    'field' => 'term_id',
                    'terms'    => array('cat' => $_POST['testimonialId_name'])
                )
            )
        );
        $testimonial_query = new WP_Query($args);
        if ($testimonial_query->have_posts()) :
    ?>
            <ul class="testimonial-listing">
                <?php
                while ($testimonial_query->have_posts()) : $testimonial_query->the_post();
                ?>
                    <li id="testimonial-<?php the_ID(); ?>">
                        <h4><a href="<?php the_permalink(); ?>" title="Read"><?php the_title(); ?></a></h4>
                        <?php the_excerpt() ?>
                        <?php the_date() ?>
                    </li>
                <?php
                endwhile;
                ?>
            </ul>
        <?php
            wp_reset_postdata();
        endif;
    }
    exit;
}
add_action('wp_ajax_weichie_load_more_btn', 'weichie_load_more');
add_action('wp_ajax_nopriv_weichie_load_more_btn', 'weichie_load_more');

function registration_form_short_code()
{
    get_template_part('registerform');
}
add_shortcode('registration_form', 'registration_form_short_code');

function login_form_short_code()
{
    wp_login_form();
}
add_shortcode('login', 'login_form_short_code');

function register_sidebar_1()
{
    add_filter('use_widgets_block_editor', '__return_false');
    register_sidebar(array(
        'name'          => __('Main Sidebar', 'textdomain'),
        'id'            => 'main_sidebar',
        'description'   => __('Widgets in this area will be shown on all posts and pages.', 'textdomain'),
        'before_widget' => '<h3 id="%1$s" class="widget %2$s">',
        'after_widget'  => '</h3>',
        'before_title'  => '<h2 class="widgettitle">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'register_sidebar_1');

function faq_register_widget()
{
    register_widget('FAQ');
}
add_action('widgets_init', 'faq_register_widget');

class FAQ extends WP_Widget
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'get_enqueue_file_admin'));
        $widget_ops = array(
            'classname'                   => 'FAQ_Ask_Question',
            'description'                 => __('This is Custom post list.'),
            'customize_selective_refresh' => true,
        );
        parent::__construct('FAQ_Ask_Question', _x('Custom FAQ_Ask_Question', ''), $widget_ops);
    }

    function get_enqueue_file_admin()
    {
        wp_enqueue_script(
            'ajax_script',
            get_stylesheet_directory_uri() . '/js/ajax.js',
            array('jquery'),
            '',
            true
        );
        wp_localize_script('ajax_script', 'ajax_var', array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('ajax-nonce')
        ));
    }

    public function widget($args, $instance)
    {
        // $title = apply_filters('widget_title', $instance['title']);
        echo $args['before_widget'];
        // $i = 1;
        foreach ($instance as $k => $v) {
            echo $k . " : " . $v . "<br>";
        }
    }

    public function form($instance)
    {
        $i = 1;
        $title    = $instance['title'];
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </label></p>
        <div class="widget-help">
            <p><label for="Enter the Question">Enter The Question</label>
                <input class="widefat" id="<?php echo $this->get_field_id('question-1'); ?>" name="<?php echo $this->get_field_name('question-1'); ?>" type="text" value="<?php echo esc_attr($instance['question-' . $i]); ?>" />
            </p>
            <p><label for="Enter the Answer">Enter the Answer</label>
                <textarea class="widefat" id="<?php echo $this->get_field_id('answer-1'); ?>" name="<?php echo $this->get_field_name('answer-1'); ?>" type="text" cols="46" rows="10"><?php echo esc_attr($instance['answer-' . $i]); ?></textarea>
            </p>
        </div>
        <button type="button" class="btn-add" onclick='add_more_field("<?php echo $this->get_field_id("question-") ?>","<?php echo $this->get_field_name("question-") ?>","<?php echo $this->get_field_id("answer-") ?>","<?php echo $this->get_field_name("answer-") ?>")' value="Add more">Add more</button>
    <?php
    }

    public function update($new_instance, $old_instance)
    {
        return $new_instance;
    }
}

function add_more()
{
    if (wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {

        $questionId = $_POST['questionId'];
        $questionName = $_POST['questionName'];
        $answerId = $_POST['answerid'];
        $answerName = $_POST['answerName']; ?>
        <p><label for="Enter the Question">Enter The Question</label>
            <input class="widefat" id="<?php echo $questionId ?>" name="<?php echo $questionName ?>" type="text" value="" />
        </p>
        <p><label for="Enter the Answer">Enter the Answer</label>
            <textarea class="widefat" id="<?php echo $answerId ?>" name="<?php echo $answerName ?>" type="text" cols="46" rows="10"></textarea>
        </p>
<?php
    }
    die;
}
add_action('wp_ajax_add_more', 'add_more');

function user_register_form_ajax()
{
    get_template_part('db/register');
}
add_action('wp_ajax_register_form', 'user_register_form_ajax');
add_action('wp_ajax_nopriv_register_form', 'user_register_form_ajax');
