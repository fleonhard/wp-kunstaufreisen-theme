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
require get_template_directory() . '/inc/post-types/HTML.php';

if (!class_exists('KAR_Podcast_Plugin')) {
    final class KAR_Podcast_Plugin extends KAR_Post_Type
    {
        private $EPISODE_TYPE = 'podcast_episode';
        private $PODCAST_TYPE = 'podcast';

        private $PODCAST_EPISODE_PODCAST_META = 'kar_episode_podcast';
        private $PODCAST_EPISODE_AUDIO_META = 'kar_episode_audio';
        private $PODCAST_EPISODE_AUDIO_DURATION_META = 'kar_episode_audio_duration';
        private $PODCAST_EPISODE_NUMBER_META = 'kar_episode_number';


        public static function init()
        {
            new KAR_Podcast_Plugin();
        }

        protected function __construct()
        {
            parent::__construct();

            add_action('wp_ajax_post_get_episode_number', array($this, 'post_get_episode_number'));

            add_filter('kar_get_episode_count', array($this, 'get_episode_count'));
            add_filter('kar_get_podcast_duration', array($this, 'get_podcast_duration'));

            add_filter('kar_get_podcast_episodes', array($this, 'get_podcast_episodes'));

            $this->create_meta_boxes();

        }

        function get_podcast_episodes($episode)
        {
            return new WP_Query(array(
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'post_type' => $this->EPISODE_TYPE,
                'meta_key' => $this->PODCAST_EPISODE_NUMBER_META,
                'order' => 'ASC',
                'orderby' => 'meta_value_num ' . $this->PODCAST_EPISODE_NUMBER_META,
                'meta_query' => array(
                    'key' => $this->PODCAST_EPISODE_PODCAST_META,
                    'value' => $episode
                )
            ));
        }

        function register_post_types()
        {
            register_post_type($this->EPISODE_TYPE, array(
                'label' => __('Episode', $this->DOMAIN),
                'labels' => $this->get_post_type_labels('Episode', 'Episodes'),
                'supports' => array('title', 'editor', 'excerpt', 'author', 'comments'),
                'show_in_rest' => true,
                'hierarchical' => false,
                'public' => true,
                'show_in_menu' => 'edit.php?post_type=' . $this->PODCAST_TYPE,
            ));

            register_post_type($this->PODCAST_TYPE, array(
                'label' => __('Podcast', $this->DOMAIN),
                'labels' => $this->get_post_type_labels('Podcast', 'Podcasts'),
                'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments'),
                'show_in_rest' => true,
                'hierarchical' => true,
                'menu_position' => 5,
                'menu_icon' => 'dashicons-microphone',
                'public' => true,
            ));
            flush_rewrite_rules();
        }

        function create_meta_boxes()
        {
            add_action('add_meta_boxes', function () {
                add_meta_box('episode_settings', __('Episode Settings', 'kar'), function ($post) {
                    $podcast_id = get_post_meta($post->ID, $this->PODCAST_EPISODE_PODCAST_META, true);
                    $audio_url = get_post_meta($post->ID, $this->PODCAST_EPISODE_AUDIO_META, true);
                    $number = get_post_meta($post->ID, $this->PODCAST_EPISODE_NUMBER_META, true);
                    $duration = get_post_meta($post->ID, $this->PODCAST_EPISODE_AUDIO_DURATION_META, true);

                    if (!$number && $podcast_id) {
                        $number = $this->calculate_episode_number($podcast_id);
                    }
                    HTML::start_table('episode_settings');

                    HTML::start_row(__('Podcast', $this->DOMAIN));
                    HTML::meta_post_type_dropdown($this->PODCAST_EPISODE_PODCAST_META, $podcast_id, $this->PODCAST_TYPE, __('No Podcast', $this->DOMAIN));
                    HTML::end_row();

                    HTML::start_row(__('Episode', $this->DOMAIN));
                    HTML::meta_input('number', $this->PODCAST_EPISODE_NUMBER_META, $number);
                    HTML::end_row();

                    HTML::start_row(__('Audio File', $this->DOMAIN));
                    HTML::audio('kar_episode_audio_preview', $audio_url);
                    HTML::meta_input('text', $this->PODCAST_EPISODE_AUDIO_DURATION_META, $duration, true);
                    HTML::meta_input('url', $this->PODCAST_EPISODE_AUDIO_META, $audio_url, true);
                    HTML::btn('kar_podcast_upload_audio', __('Upload Audio File', $this->DOMAIN));
                    HTML::end_row();

                    HTML::end_table();
                }, $this->EPISODE_TYPE, 'normal', 'high');
            });
            add_action('save_post', function ($post_id) {
                $meta_keys = array(
                    $this->PODCAST_EPISODE_PODCAST_META,
                    $this->PODCAST_EPISODE_AUDIO_META,
                    $this->PODCAST_EPISODE_NUMBER_META,
                    $this->PODCAST_EPISODE_AUDIO_DURATION_META
                );

                foreach ($meta_keys as $meta_key) {
                    if (HTML::verify($meta_key)) {
                        update_post_meta($post_id, $meta_key, $_POST[$meta_key]);
                    }
                }
            });
        }

        public function post_get_episode_number()
        {
            if (!is_admin()) die();
            $podcast = $_POST['podcast'];
            if (!isset($podcast)) die();
            echo $this->calculate_episode_number($podcast);
            die();
        }

        public function get_podcast_duration($podcast_id = 0)
        {
            if (!$podcast_id) {
                $podcast_id = get_the_ID();
            }

            $posts = get_posts(array(
                'posts_per_page' => -1,
                'post_type' => $this->EPISODE_TYPE,
                'meta_query' => array(
                    'key' => $this->PODCAST_EPISODE_PODCAST_META,
                    'value' => $podcast_id
                )
            ));

            $sum = 0;
            foreach ($posts as $post) {
                $sum += get_post_meta($post->ID, $this->PODCAST_EPISODE_AUDIO_DURATION_META, true);
            }
            return gmdate("H:i:s", $sum);

        }

        public function get_episode_count($podcast_id = 0)
        {
            if (!$podcast_id) {
                $podcast_id = get_the_ID();
            }
            $posts = get_posts(array(
                'posts_per_page' => -1,
                'post_type' => $this->EPISODE_TYPE,
                'order' => 'DESC',
                'orderby' => 'meta_value_num',
                'meta_query' => array(
                    'key' => $this->PODCAST_EPISODE_PODCAST_META,
                    'value' => $podcast_id
                )
            ));
            return count($posts);
        }

        private function calculate_episode_number($podcast)
        {

            $posts = get_posts(array(
                'posts_per_page' => 1,
                'post_type' => $this->EPISODE_TYPE,
                'meta_key' => $this->PODCAST_EPISODE_NUMBER_META,
                'order' => 'DESC',
                'orderby' => 'meta_value_num',
                'meta_query' => array(
                    'key' => $this->PODCAST_EPISODE_PODCAST_META,
                    'value' => $podcast
                )
            ));
            return (empty($posts) ? 1 : (get_post_meta($posts[0]->ID, $this->PODCAST_EPISODE_NUMBER_META, true) + 1));

        }
    }
}
