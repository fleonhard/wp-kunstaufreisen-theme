<?php
/**
 * @author Florian Herborn
 * @copyright 2019 Herborn Software
 * @license GPL-2.0-or-later
 *
 * @package kar
 */

defined('ABSPATH') || exit;

require get_template_directory() . '/inc/post-types/KAR_Post_Type.php';

if (!class_exists('KAR_Art_Plugin')) {
    final class KAR_Art_Plugin extends KAR_Post_Type
    {
        private $ART_IMAGE_TYPE = 'art_image';
        private $ART_GALLERY_TYPE = 'art_gallery';
        private $ART_TOPIC_TAXONOMY = 'art_topic';
        private $ART_STYLE_TAXONOMY = 'art_style';
        private $ART_SIZE_TAXONOMY = 'art_size';
        private $ART_SUPPORT_MEDIUM_TAXONOMY = 'art_support_medium';

        private $ART_IMAGE_GALLERY_META = 'kar_art_image_gallery';
        private $ART_SIZE_WIDTH_META = 'kar_art_size_width';
        private $ART_SIZE_HEIGHT_META = 'kar_art_size_height';


        public static function init()
        {
            new KAR_Art_Plugin();
        }

        protected function __construct()
        {
            parent::__construct();

            add_action('wp_enqueue_scripts', array($this, 'load_scripts'));
            add_action('admin_enqueue_scripts', array($this, 'load_scripts'));

            add_filter('kar_get_image_gallery_link', array($this, 'get_image_gallery_link'));
            add_filter('kar_get_image_size', array($this, 'get_image_size'));
            add_filter('kar_get_image_materials', array($this, 'get_image_materials'));
            add_filter('kar_get_image_support_medium', array($this, 'get_image_support_medium'));
            add_filter('kar_get_gallery_topics', array($this, 'get_gallery_topics'));
            add_filter('kar_get_gallery_taxonomy_images', array($this, 'get_gallery_taxonomy_images'));
            add_filter('kar_get_image_topics', array($this, 'get_image_topics'));

            $this->create_meta_boxes();
        }

        function get_gallery_images_taxonomies_query($gallery_id, ...$taxonomy_types)
        {
            global $wpdb;
            $taxonomies = join(',', array_map(function ($tax) {
                return '"' . esc_sql($tax) . '"';
            }, $taxonomy_types));

            return 'select count(p.ID) as post_count, wt.*
                    from ' . $wpdb->prefix . 'posts p
                    inner join ' . $wpdb->prefix . 'postmeta m on p.ID = m.post_id
                    inner join ' . $wpdb->prefix . 'term_relationships wtr on p.ID = wtr.object_id
                    inner join ' . $wpdb->prefix . 'term_taxonomy wtt on wtr.term_taxonomy_id = wtt.term_taxonomy_id
                    inner join ' . $wpdb->prefix . 'terms wt on wtt.term_id = wt.term_id
                    where m.meta_key = "' . $this->ART_IMAGE_GALLERY_META . '" 
                    && m.meta_value = ' . esc_sql($gallery_id) . ' 
                    && wtt.taxonomy in (' . $taxonomies . ') 
                    && p.post_status = "publish"
                    group by wt.term_id
                    order by wtt.taxonomy DESC';
        }

        function get_gallery_term_images_query($gallery_id, $term_id)
        {
            global $wpdb;
            return 'select distinct p.*
                    from ' . $wpdb->prefix . 'posts p
                    inner join ' . $wpdb->prefix . 'postmeta m on p.ID = m.post_id
                    inner join ' . $wpdb->prefix . 'term_relationships wtr on p.ID = wtr.object_id
                    where m.meta_key = "' . $this->ART_IMAGE_GALLERY_META . '" 
                    && m.meta_value = ' . esc_sql($gallery_id) . '
                    && wtr.term_taxonomy_id = ' . esc_sql($term_id) . '
                    && p.post_status = "publish"';
        }

        function get_gallery_topics($gallery_id)
        {
            global $wpdb;
            return $wpdb->get_results($this->get_gallery_images_taxonomies_query($gallery_id,
                $this->ART_TOPIC_TAXONOMY,
                $this->ART_SIZE_TAXONOMY,
                $this->ART_SUPPORT_MEDIUM_TAXONOMY,
                $this->ART_STYLE_TAXONOMY));
        }

        function get_gallery_taxonomy_images($taxonomy_id)
        {
            global $wpdb;
            return $wpdb->get_results($this->get_gallery_term_images_query(get_the_ID(), $taxonomy_id));
        }

        function get_image_gallery_link($image_id)
        {
            $gallery = get_post_meta($image_id, $this->ART_IMAGE_GALLERY_META, true);
            return ' <a href="' . get_the_permalink($gallery) . '" class="kar-link" > ' . get_the_title($gallery) . '</a> ';
        }

        function get_image_size($image_id)
        {
            $terms = get_the_terms($image_id, $this->ART_SIZE_TAXONOMY);
            if (!empty($terms)) {
                return '<a href="' . get_term_link($terms[0]->term_id) . '" class="kar-link">' . $terms[0]->name . '</a>';
                //$t_id = $terms[0]->term_id;
                //$term_meta = get_option("taxonomy_$t_id");
                //return '<a class="kar-link" href="'.get_term_link($t_id).'">'.$term_meta[$this->ART_SIZE_WIDTH_META] . ' x ' . $term_meta[$this->ART_SIZE_HEIGHT_META].'</a>';
            }
            return null;
        }

        function get_image_support_medium($image_id)
        {
            $terms = get_the_terms($image_id, $this->ART_SUPPORT_MEDIUM_TAXONOMY);
            if (!empty($terms)) {
                return '<a href="' . get_term_link($terms[0]->term_id) . '" class="kar-link">' . $terms[0]->name . '</a>';
            }
            return null;
        }

        function get_image_materials($image_id)
        {
            $terms = get_the_terms($image_id, $this->ART_STYLE_TAXONOMY);
            $termHTML = array_map(function ($term) {
                return ' <a href="' . get_term_link($term->term_id) . '" class="kar-link">' . $term->name . '</a>';
            }, $terms);

            return join(' &#183 ', $termHTML);
        }

        function get_image_topics($image_id)
        {
            $terms = get_the_terms($image_id, $this->ART_TOPIC_TAXONOMY);
            $termHTML = array_map(function ($term) {
                return ' <a href="' . get_term_link($term->term_id) . '" class="kar-link">' . $term->name . '</a>';
            }, $terms);

            return join(' &#183 ', $termHTML);
        }

        function load_scripts()
        {
            wp_enqueue_script('art', get_template_directory_uri() . '/public/js/art.js', array(), '1.0.0', true);
            wp_localize_script('art', 'data', array(
                'ajax_url' => admin_url('admin-ajax.php'),
            ));
        }

        function register_post_types()
        {
            register_post_type($this->ART_IMAGE_TYPE, array(
                'label' => __('Images', $this->DOMAIN),
                'labels' => $this->get_post_type_labels('Image', 'Images'),
                'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'author', 'comments'),
                'show_in_rest' => true,
                'hierarchical' => false,
                'taxonomy' => array($this->ART_TOPIC_TAXONOMY),
                'public' => true,
                'show_in_menu' => 'edit.php?post_type=' . $this->ART_GALLERY_TYPE,
            ));

            register_post_type($this->ART_GALLERY_TYPE, array(
                'label' => __('Galleries', $this->DOMAIN),
                'labels' => $this->get_post_type_labels('Gallery', 'Galleries'),
                'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments'),
                'show_in_rest' => true,
                'hierarchical' => true,
                'menu_position' => 5,
                'menu_icon' => 'dashicons-images-alt2',
                'public' => true,
            ));

            register_taxonomy($this->ART_TOPIC_TAXONOMY, array($this->ART_IMAGE_TYPE), array(
                'label' => __('Topics', $this->DOMAIN),
                'labels' => $this->get_post_type_labels('Topic', 'Topics'),
                'hierarchical' => true,
                'show_ui' => true,
                'show_in_rest' => true,
                'show_admin_column' => true,
                'show_tagcloud' => false,
                'query_var' => true,
                'show_in_menu' => 'edit.php?post_type=' . $this->ART_GALLERY_TYPE,
            ));

            register_taxonomy($this->ART_STYLE_TAXONOMY, array($this->ART_IMAGE_TYPE), array(
                'label' => __('Styles', $this->DOMAIN),
                'labels' => $this->get_post_type_labels('Style', 'Styles'),
                'hierarchical' => true,
                'show_ui' => true,
                'show_in_rest' => true,
                'show_admin_column' => true,
                'show_tagcloud' => false,
                'query_var' => true,
                'show_in_menu' => 'edit.php?post_type=' . $this->ART_GALLERY_TYPE,
            ));

            register_taxonomy($this->ART_SIZE_TAXONOMY, array($this->ART_IMAGE_TYPE), array(
                'label' => __('Sizes', $this->DOMAIN),
                'labels' => $this->get_post_type_labels('Size', 'Sizes'),
                'hierarchical' => true,
                'show_ui' => true,
                'show_in_rest' => true,
                'show_admin_column' => true,
                'show_tagcloud' => false,
                'query_var' => true,
                'show_in_menu' => 'edit.php?post_type=' . $this->ART_GALLERY_TYPE,
            ));

            register_taxonomy($this->ART_SUPPORT_MEDIUM_TAXONOMY, array($this->ART_IMAGE_TYPE), array(
                'label' => __('Support Mediums', $this->DOMAIN),
                'labels' => $this->get_post_type_labels('Support Medium', 'Support Mediums'),
                'hierarchical' => true,
                'show_ui' => true,
                'show_in_rest' => true,
                'show_admin_column' => true,
                'show_tagcloud' => false,
                'query_var' => true,
                'show_in_menu' => 'edit.php?post_type=' . $this->ART_GALLERY_TYPE,
            ));

            add_action('admin_init', function () {

                add_submenu_page(
                    'edit.php?post_type=' . $this->ART_GALLERY_TYPE,
                    __('Topics', $this->DOMAIN),
                    __('All Topics', $this->DOMAIN),
                    'manage_categories',
                    'edit-tags.php?taxonomy=' . $this->ART_TOPIC_TAXONOMY . '&post_type=' . $this->ART_GALLERY_TYPE
                );

                add_submenu_page(
                    'edit.php?post_type=' . $this->ART_GALLERY_TYPE,
                    __('Styles', $this->DOMAIN),
                    __('All Styles', $this->DOMAIN),
                    'manage_categories',
                    'edit-tags.php?taxonomy=' . $this->ART_STYLE_TAXONOMY . '&post_type=' . $this->ART_GALLERY_TYPE
                );

                add_submenu_page(
                    'edit.php?post_type=' . $this->ART_GALLERY_TYPE,
                    __('Sizes', $this->DOMAIN),
                    __('All Sizes', $this->DOMAIN),
                    'manage_categories',
                    'edit-tags.php?taxonomy=' . $this->ART_SIZE_TAXONOMY . '&post_type=' . $this->ART_GALLERY_TYPE
                );

                add_submenu_page(
                    'edit.php?post_type=' . $this->ART_GALLERY_TYPE,
                    __('Materials', $this->DOMAIN),
                    __('All Materials', $this->DOMAIN),
                    'manage_categories',
                    'edit-tags.php?taxonomy=' . $this->ART_SUPPORT_MEDIUM_TAXONOMY . '&post_type=' . $this->ART_GALLERY_TYPE
                );
            });

            add_action($this->ART_SIZE_TAXONOMY . '_add_form_fields', function () {
                HTML::meta_input('number', 'term_meta[' . $this->ART_SIZE_WIDTH_META . ']', '', false, __('Width (mm)', $this->DOMAIN));
                HTML::meta_input('number', 'term_meta[' . $this->ART_SIZE_HEIGHT_META . ']', '', false, __('Height (mm)', $this->DOMAIN));
            }, 10, 2);

            add_action($this->ART_SIZE_TAXONOMY . '_edit_form_fields', function ($term) {
                $t_id = $term->term_id;
                $term_meta = get_option("taxonomy_$t_id");

                HTML::start_row(__('Width (mm)', $this->DOMAIN));
                HTML::meta_input('number', 'term_meta[' . $this->ART_SIZE_WIDTH_META . ']', $term_meta[$this->ART_SIZE_WIDTH_META]);
                HTML::end_row();

                HTML::start_row(__('Height (mm)', $this->DOMAIN));
                HTML::meta_input('number', 'term_meta[' . $this->ART_SIZE_HEIGHT_META . ']', $term_meta[$this->ART_SIZE_HEIGHT_META]);
                HTML::end_row();

            }, 10, 2);

            add_action('edited_' . $this->ART_SIZE_TAXONOMY, array($this, 'save_size_meta_fields'), 10, 2);
            add_action('create_' . $this->ART_SIZE_TAXONOMY, array($this, 'save_size_meta_fields'), 10, 2);

            flush_rewrite_rules();
        }

        function save_size_meta_fields($term_id)
        {
            if (isset($_POST['term_meta'])) {
                $t_id = $term_id;
                $term_meta = get_option("taxonomy_$t_id");
                $cat_keys = array_keys($_POST['term_meta']);
                foreach ($cat_keys as $key) {
                    if (isset ($_POST['term_meta'][$key])) {
                        $term_meta[$key] = $_POST['term_meta'][$key];
                    }
                }
                update_option("taxonomy_$t_id", $term_meta);
            }

        }

        function create_meta_boxes()
        {
            add_action('add_meta_boxes', function () {
                add_meta_box('image_settings', __('Image Settings', 'kar'), function ($post) {
                    $gallery = get_post_meta($post->ID, $this->ART_IMAGE_GALLERY_META, true);
                    HTML::start_table('image_settings');

                    HTML::start_row(__('Gallery', $this->DOMAIN));
                    HTML::meta_post_type_dropdown($this->ART_IMAGE_GALLERY_META, $gallery, $this->ART_GALLERY_TYPE, __('No Gallery', $this->DOMAIN));
                    HTML::end_row();

                    HTML::end_table();
                }, $this->ART_IMAGE_TYPE, 'normal', 'high');
            });
            add_action('save_post', function ($post_id) {
                $meta_keys = array(
                    $this->ART_IMAGE_GALLERY_META
                );

                foreach ($meta_keys as $meta_key) {
                    if (HTML::verify($meta_key)) {
                        update_post_meta($post_id, $meta_key, $_POST[$meta_key]);
                    }
                }
            });
        }

    }
}