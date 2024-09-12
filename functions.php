<?php

function theme_style()
{
    wp_enqueue_style('index_university_font_google', "https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i");
    wp_enqueue_style('index_university_bootstrap', "https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
    wp_enqueue_style('index_university', get_theme_file_uri('build/index.css'));
    wp_enqueue_style('index_university_extra', get_theme_file_uri('build/style-index.css'));
    wp_enqueue_script('index_university_script-1', get_theme_file_uri('build/mobile.js'));
    wp_enqueue_script('index_university_script', get_theme_file_uri('build/index.js'), array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'theme_style');

if (!function_exists('mytheme_register_nav_menu')) {
    function mytheme_register_nav_menu()
    {
        register_nav_menus(array(
            'primary_menu' => __('Menu chinh', 'themeUniversity'),
            'footer_menu_1'  => __('Menu Footer_1', 'themeUniversity'),
        ));
    }
    add_action('after_setup_theme', 'mytheme_register_nav_menu', 0);
}

add_theme_support('post-thumbnails');



function custom_paginate_links()
{
    $args = array(
        'prev_text' => '<span class="pagination-previous">Previous</span>',
        'next_text' => '<span class="pagination-next">Next</span>',
    );
    echo '<div class="custom-paginate-links">';
    echo paginate_links($args);
    echo '</div>';
}

function create_event_post_type()
{
    $labels = array(
        'name' => 'Events',
        'singular_name' => 'Event',
        'menu_name' => 'Events',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'show_in_rest' => true, // Thêm dòng này để hỗ trợ REST API
    );

    register_post_type('event', $args);

    add_action('add_meta_boxes', 'add_event_date_metabox');
    add_action('save_post', 'save_event_date', 10, 2);
}
add_action('init', 'create_event_post_type');

function add_event_date_metabox()
{
    add_meta_box(
        'event_date_metabox',
        'Event Date',
        'event_date_metabox_callback',
        'event',
        'normal',
        'high'
    );
}

function event_date_metabox_callback($post)
{
    $event_date = get_post_meta($post->ID, 'event_date', true);
?>
    <label for="event_date">Event Date:</label>
    <input type="date" id="event_date" name="event_date" value="<?php echo $event_date; ?>">
<?php
}

function save_event_date($post_id, $post)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['event_date'])) {
        update_post_meta($post_id, 'event_date', sanitize_text_field($_POST['event_date']));
    }
}

// end custom type post event

// Custom post type blog
function create_blog_post_type()
{
    $labels = array(
        'name' => 'Blogs',
        'singular_name' => 'Blog',
        'menu_name' => 'Blogs',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true, // Thêm dòng này để hỗ trợ REST API
    );

    register_post_type('blog', $args);
}
add_action('init', 'create_blog_post_type');

// end custom type post blog

/// Đảm bảo hỗ trợ hình thu nhỏ, phân loại và định dạng bài viết
function my_theme_setup()
{
    add_theme_support('post-thumbnails');
    add_theme_support('post-formats', array('aside', 'gallery', 'quote', 'video'));
    add_theme_support('custom-background');
    add_theme_support('custom-header');

    register_nav_menus(array(
        'primary_menu' => __('Primary Menu', 'themeUniversity'),
    ));
}
add_action('after_setup_theme', 'my_theme_setup');


// Tạo các phân loại tùy chỉnh
function create_custom_taxonomies()
{
    // Danh mục tùy chỉnh
    register_taxonomy(
        'custom_category',
        'post',
        array(
            'label' => __('Custom Categories'),
            'rewrite' => array('slug' => 'custom-category'),
            'hierarchical' => true,
        )
    );

    // Thẻ tùy chỉnh
    register_taxonomy(
        'custom_tag',
        'post',
        array(
            'label' => __('Custom Tags'),
            'rewrite' => array('slug' => 'custom-tag'),
            'hierarchical' => false,
        )
    );
}
add_action('init', 'create_custom_taxonomies');


function add_event_meta_boxes()
{
    add_meta_box(
        'event_date_metabox',
        'Event Date',
        'event_date_metabox_callback',
        'event',
        'normal',
        'high'
    );

    add_meta_box(
        'event_location_metabox',
        'Event Location',
        'event_location_metabox_callback',
        'event',
        'normal',
        'high'
    );

    add_meta_box(
        'event_time_metabox',
        'Event Time',
        'event_time_metabox_callback',
        'event',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_event_meta_boxes');

function event_location_metabox_callback($post)
{
    $event_location = get_post_meta($post->ID, 'event_location', true);
?>
    <label for="event_location">Event Location:</label>
    <input type="text" id="event_location" name="event_location" value="<?php echo esc_attr($event_location); ?>">
<?php
}

function event_time_metabox_callback($post)
{
    $event_time = get_post_meta($post->ID, 'event_time', true);
?>
    <label for="event_time">Event Time:</label>
    <input type="time" id="event_time" name="event_time" value="<?php echo esc_attr($event_time); ?>">
<?php
}
function save_event_meta_boxes($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['event_date'])) {
        update_post_meta($post_id, 'event_date', sanitize_text_field($_POST['event_date']));
    }

    if (isset($_POST['event_location'])) {
        update_post_meta($post_id, 'event_location', sanitize_text_field($_POST['event_location']));
    }

    if (isset($_POST['event_time'])) {
        update_post_meta($post_id, 'event_time', sanitize_text_field($_POST['event_time']));
    }
}
add_action('save_post', 'save_event_meta_boxes');

// Change the login logo URL
function custom_login_logo_url() {
    return home_url(); // You can change this to any URL you'd like.
}
add_filter('login_headerurl', 'custom_login_logo_url');

// Change the login logo title
function custom_login_logo_url_title() {
    return 'Your Custom Title'; // Customize this text
}
add_filter('login_headertext', 'custom_login_logo_url_title');


function my_custom_login_logo_customizer($wp_customize) {
    $wp_customize->add_section('my_custom_login_logo_section', array(
        'title'       => __('Login Logo', 'themeUniversity'),
        'priority'    => 30,
        'description' => 'Customize the login logo.',
    ));

    $wp_customize->add_setting('my_custom_login_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    if (class_exists('WP_Customize_Image_Control')) {
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'my_custom_login_logo', array(
            'label'    => __('Upload Login Logo', 'themeUniversity'),
            'section'  => 'my_custom_login_logo_section',
            'settings' => 'my_custom_login_logo',
        )));
    }
}
add_action('customize_register', 'my_custom_login_logo_customizer');

function custom_login_logo() {
    $custom_logo_url = get_theme_mod('my_custom_login_logo');
    if ($custom_logo_url) {
        echo '<style type="text/css">
            #login h1 a {
                background-image: url(' . esc_url($custom_logo_url) . ');
                background-size: contain;
                width: 100%;
                height: 80px;
            }
        </style>';
    }
}
add_action('login_enqueue_scripts', 'custom_login_logo');

?>