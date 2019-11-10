<?php
/**
 * @author Florian Herborn
 * @copyright 2019 Herborn Software
 * @license GPL-2.0-or-later
 *
 * @package kar
 */
/**
 * @wordpress-plugin
 * Plugin Name:       KAR Podcasts
 * Plugin URI:        https://www.herborn-software.com
 * Description:       Podcast support for the KAR Theme
 * Version:           0.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Herborn-Software
 * Author URI:        https://www.herborn-software.com
 * Text Domain:       kar
 * License: GNU General Public License v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Github Theme URI: fherborn/kar-podcasts
 */
defined('ABSPATH') || exit;

if (!class_exists('KARPodcastPlugin')) {
    final class KARPodcastPlugin
    {
        private $EPISODE_TYPE = 'podcast_episode';
        private $PODCAST_TYPE = 'podcast';
        private $DOMAIN = 'kar';
        private $NONCE_SUFFIX = '-nonce';

        private $PODCAST_EPISODE_PODCAST_META = 'kar_episode_podcast';
        private $PODCAST_EPISODE_AUDIO_META = 'kar_episode_audio';
        private $PODCAST_EPISODE_AUDIO_DURATION_META = 'kar_episode_audio_duration';
        private $PODCAST_EPISODE_NUMBER_META = 'kar_episode_number';


        public static function init()
        {
            new KARPodcastPlugin();
        }

        private function __construct()
        {

            add_action('wp_ajax_post_get_episode_number', array($this, 'post_get_episode_number'));

            add_action('init', array($this, 'register_podcast_episode_type'));
            add_action('init', array($this, 'register_podcast_type'));

            add_action('add_meta_boxes', array($this, 'add_episode_settings_meta_box'));
            add_action('save_post', array($this, 'save_episode_settings'));

            add_filter('kar_get_episode_count', array($this, 'get_episode_count'));
            add_filter('kar_get_podcast_duration', array($this, 'get_podcast_duration'));
        }

        public function post_get_episode_number()
        {
            if (!is_admin()) die();
            $podcast = $_POST['podcast'];
            if (!isset($podcast)) die();
            echo $this->calculate_episode_number($podcast);
            die();
        }

        private function add_nonce_field($meta_key)
        {
            wp_nonce_field(plugin_basename(__FILE__), $meta_key . $this->NONCE_SUFFIX);
        }

        private function is_nonce_error(...$meta_key)
        {
            foreach ($meta_key as $key) {
                if (!wp_verify_nonce($key . $this->NONCE_SUFFIX, plugin_basename(__FILE__)))
                    return false;
            }
            return true;
        }

        private function save_meta_box_data($id, $meta_key)
        {
            if (isset($_POST[$meta_key])) {
                update_post_meta($id, $meta_key, $_POST[$meta_key]);
            }
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

        private function get_post_type_labels($singular, $plural)
        {
            return array(
                'name' => _n($singular, $plural, 2, $this->DOMAIN),
                'singular_name' => _n($singular, $plural, 1, $this->DOMAIN),
                'menu_name' => _n($singular, $plural, 2, $this->DOMAIN),
                'name_admin_bar' => _n($singular, $plural, 2, $this->DOMAIN)
            );
        }

        public function register_podcast_episode_type()
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
        }

        public function register_podcast_type()
        {
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
        }

        public function add_episode_settings_meta_box()
        {
            add_meta_box(
                $this->PODCAST_EPISODE_PODCAST_META,
                __('Episode Settings', $this->DOMAIN),
                array($this, 'render_episode_settings_meta_box'),
                $this->EPISODE_TYPE,
                'normal',
                'high'
            );
        }

        public function render_episode_settings_meta_box($post)
        {
            $this->add_nonce_field($this->PODCAST_EPISODE_PODCAST_META);
            $podcast_id = get_post_meta($post->ID, $this->PODCAST_EPISODE_PODCAST_META, true);

            $podcast = wp_dropdown_pages(array(
                'post_type' => $this->PODCAST_TYPE,
                'selected' => $podcast_id,
                'name' => $this->PODCAST_EPISODE_PODCAST_META,
                'show_option_none' => __('No Podcast', $this->DOMAIN),
                'sort_column' => 'menu_order, post_title',
                'echo' => 0
            ));


            $audio_url = get_post_meta($post->ID, $this->PODCAST_EPISODE_AUDIO_META, true);
            $number = get_post_meta($post->ID, $this->PODCAST_EPISODE_NUMBER_META, true);
            $duration = get_post_meta($post->ID, $this->PODCAST_EPISODE_AUDIO_DURATION_META, true);

            if (!$number && $podcast) {
                $number = $this->calculate_episode_number($podcast);
            }

            ?>
            <style>
                th {
                    background-color: lightgrey;
                }

                table,
                select {
                    width: 100%;
                }
            </style>

            <table id="podcast_episode_meta_box_content">
                <tbody>
                <tr>
                    <th><?php _e('Podcast', $this->DOMAIN) ?></th>
                    <td>
                        <?php if (!empty($podcast)) {
                            echo $podcast;
                        } ?>
                    </td>
                </tr>
                <tr>
                    <th><?php _e('Episode', $this->DOMAIN) ?></th>
                    <td>
                        <?php $this->add_nonce_field($this->PODCAST_EPISODE_NUMBER_META); ?>
                        <input type="number"
                               class="large-text"
                               name="<?php echo $this->PODCAST_EPISODE_NUMBER_META ?>"
                               id="<?php echo $this->PODCAST_EPISODE_NUMBER_META ?>"
                               value="<?php echo esc_attr($number); ?>">
                    </td>
                </tr>
                <tr>
                    <th><?php _e('Audio File', $this->DOMAIN) ?></th>
                    <td>

                        <fieldset>
                            <?php $this->add_nonce_field($this->PODCAST_EPISODE_AUDIO_META); ?>
                            <?php $this->add_nonce_field($this->PODCAST_EPISODE_AUDIO_DURATION_META); ?>
                            <audio controls
                                   id="kar_episode_audio_preview"
                                   style="width: 100%"
                                   src="<?php echo esc_attr($audio_url); ?>">
                            </audio>
                            <input type="text"
                                   class="large-text"
                                   name="<?php echo $this->PODCAST_EPISODE_AUDIO_DURATION_META ?>"
                                   id="<?php echo $this->PODCAST_EPISODE_AUDIO_DURATION_META ?>"
                                   value="<?php echo esc_attr($duration); ?>"
                                   hidden>
                            <input type="url"
                                   class="large-text"
                                   name="<?php echo $this->PODCAST_EPISODE_AUDIO_META ?>"
                                   id="<?php echo $this->PODCAST_EPISODE_AUDIO_META ?>"
                                   value="<?php echo esc_attr($audio_url); ?>"
                                   hidden>
                            <button type="button"
                                    class="button"
                                    id="kar_podcast_upload_audio">
                                <?php _e('Upload Audio File', $this->DOMAIN) ?>
                            </button>
                        </fieldset>
                    </td>
                </tr>
                </tbody>
            </table>

            <?php
        }

        public function save_episode_settings($id)
        {
            if ($this->is_nonce_error(
                $this->PODCAST_EPISODE_PODCAST_META,
                $this->PODCAST_EPISODE_AUDIO_META,
                $this->PODCAST_EPISODE_NUMBER_META,
                $this->PODCAST_EPISODE_AUDIO_DURATION_META
            )) return $id;

            $this->save_meta_box_data($id, $this->PODCAST_EPISODE_PODCAST_META);
            $this->save_meta_box_data($id, $this->PODCAST_EPISODE_AUDIO_META);
            $this->save_meta_box_data($id, $this->PODCAST_EPISODE_NUMBER_META);
            $this->save_meta_box_data($id, $this->PODCAST_EPISODE_AUDIO_DURATION_META);
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

KARPodcastPlugin::init();
