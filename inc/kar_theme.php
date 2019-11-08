<?php
/**
 *  Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */

require get_template_directory() . '/inc/kar_woocommerce.php';
require get_template_directory() . '/inc/kar_walker_nav_menu.php';
require get_template_directory() . '/inc/kar_walker_comment.php';
require get_template_directory() . '/inc/post-types/podcast.php';
require get_template_directory() . '/inc/post-types/travel_step.php';
require get_template_directory() . '/inc/post-types/art.php';
require get_template_directory() . '/inc/widgets/post-views.php';

defined('ABSPATH') || exit;

if (!class_exists('KAR_Theme')) {
    final class KAR_Theme
    {

        private $theme;

        public static function install()
        {
            return new KAR_Theme();
        }

        private function __construct()
        {
            $this->theme = wp_get_theme();

            add_theme_support('custom-header');
            add_theme_support('custom-background');
            add_theme_support('post-thumbnails');
            add_theme_support('post-formats', array(/*'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'*/));
            add_theme_support('html5', array('audio', 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));


            add_action('wp_enqueue_scripts', array($this, 'add_frontend_scripts'));
            add_action('admin_enqueue_scripts', array($this, 'add_admin_scripts'));

            add_action('pre_get_posts', array($this, 'register_query_post_types'));
            add_action('get_header', array($this, 'save_header_name'));
            add_action('get_footer', array($this, 'save_footer_name'));

            add_action('after_setup_theme', array($this, 'register_lang'));
            add_action('after_setup_theme', array($this, 'register_navs'));
            add_action('after_setup_theme', array($this, 'register_sidebars'));
            add_action('after_setup_theme', array($this, 'register_plugin_support'));
        }

        function register_plugin_support()
        {
            KAR_Woocommerce::install();
        }

        function register_lang()
        {
            load_theme_textdomain('kar', get_template_directory() . '/language');
        }

        function register_navs()
        {
            register_nav_menu('primary', 'Header Navigation Menu');
        }

        function register_sidebars()
        {
            register_sidebar(
                array(
                    'name' => __('Main Sidebar', 'kar'),
                    'id' => 'main_sidebar',
                    'description' => __('Sidebar on Main Page', 'kar'),
                    'before_widget' => '<section id="%1$s" class="kar-widget col-12 %2$s">',
                    'after_widget' => '</section>',
                    'before_title' => '<h2 class="kar-widget-title">',
                    'after_title' => '</h2>'
                )
            );
        }

        function add_frontend_scripts()
        {
            wp_enqueue_script('app', get_template_directory_uri() . '/public/js/app.js', array(), $this->theme->get('Version'), true);
            wp_enqueue_style('app', get_template_directory_uri() . '/public/css/app.css', array(), $this->theme->get('Version'), 'all');
            wp_enqueue_style('raleway', 'https://fonts.googleapis.com/css?family=Raleway:200,300,500');
            wp_enqueue_style('playfair_display', 'https://fonts.googleapis.com/css?family=Playfair+Display:400,700,900&display=swap');
        }

        function add_admin_scripts()
        {
            wp_enqueue_script('app', get_template_directory_uri() . '/public/js/admin.js', array(), $this->theme->get('Version'), true);
            wp_enqueue_style('app', get_template_directory_uri() . '/public/css/admin.css', array(), $this->theme->get('Version'), 'all');
        }

        function register_query_post_types(\WP_Query $query)
        {
            if (is_home() && $query->is_main_query()) {
                $query->set('post_type', array('post', 'podcast', 'travel-step'));
            }
            return $query;
        }

        function save_header_name($name)
        {
            add_filter('current_header', function () use ($name) {
                return (string)$name;
            });
        }

        function save_footer_name($name)
        {
            add_filter('current_footer', function () use ($name) {
                return (string)$name;
            });
        }
    }
}