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

if (!class_exists('KAR_Travel_Plugin')) {
    final class KAR_Travel_Plugin extends KAR_Post_Type
    {
        private $MILESTONE_TYPE = 'travel_milestone';
        private $TRIP_TYPE = 'travel_trip';
        private $option_name = 'kar_travel';
        private $settings_mapbox_section = 'kar_trip_section';
        private $settings_mapbox_api_key = 'kar_trip_mapbox_api_key';

        private $TRIP_START_META = 'kar_travel_trip_start';
        private $MILESTONE_DATE_META = 'kar_travel_milestone_date';
        private $MILESTONE_TRIP_META = 'kar_travel_milestone_trip';
        private $MILESTONE_LOCATION_NAME_META = 'kar_travel_milestone_location_name';
        private $MILESTONE_LOCATION_LAT_META = 'kar_travel_milestone_location_lat';
        private $MILESTONE_LOCATION_LON_META = 'kar_travel_milestone_location_lon';


        public static function init()
        {
            new KAR_Travel_Plugin();
        }

        protected function __construct()
        {
            parent::__construct();
            add_action('admin_init', array($this, 'settings_init'));

            add_action('wp_enqueue_scripts', array($this, 'load_scripts'));
            add_action('admin_enqueue_scripts', array($this, 'load_scripts'));

            add_action('kar_add_milestone_meta', array($this, 'add_milestone_meta'));

            add_filter('kar_get_milestone_day', array($this, 'get_milestone_day'));
            add_filter('kar_get_trip_milestones', array($this, 'get_trip_milestones'));
            add_filter('kar_get_milestone_location', array($this, 'get_milestone_location'));
            add_filter('kar_get_milestone_trip_link', array($this, 'get_milestone_trip_link'));
            add_filter('kar_get_milestone_link', array($this, 'get_milestone_link'));

            add_action('wp_ajax_ajax_increase_post_view', array($this, 'ajax_increase_post_view'));

            $this->create_meta_boxes();
        }

        function ajax_increase_post_view() {
            $post_id = $_POST['post_id'];
            if(!isset($post_id)) die();
            do_action('kar_increase_post_views', $post_id);
            echo 1;
            die();
        }

        function get_milestone_link($milestone_id)
        {
            return get_the_permalink(get_post_meta($milestone_id, $this->MILESTONE_TRIP_META, true)) . '?milestone=' . $milestone_id;
        }

        function get_milestone_trip_link($milestone_id)
        {
            $trip = get_post_meta($milestone_id, $this->MILESTONE_TRIP_META, true);
            return '<a class="kar-link" href="'.get_the_permalink($trip).'">'.get_the_title($trip).'</a>';
        }

        function get_milestone_location($milestone_id)
        {
            return get_post_meta($milestone_id, $this->MILESTONE_LOCATION_NAME_META, true);
        }

        function get_milestone_day($milestone_id)
        {
            $date = get_post_meta($milestone_id, $this->MILESTONE_DATE_META, true);
            $trip = get_post_meta($milestone_id, $this->MILESTONE_TRIP_META, true);
            $start_date = get_post_meta($trip, $this->TRIP_START_META, true);
            $format = 'Y-m-d';
            $start = DateTime::createFromFormat($format, $start_date);
            $step = DateTime::createFromFormat($format, $date);
            return ($step->diff($start)->format("%a"));
        }

        function add_milestone_meta()
        {
            echo '<div class="milestone-meta" 
                id="milestone-meta-' . get_the_ID() . '"
                class="milestone-meta"
                data-id="' . get_the_ID() . '" 
                data-img="' . get_the_post_thumbnail_url() . '" 
                data-day="' . __("Day", 'kar') . $this->get_milestone_day(get_the_ID()) . '" 
                data-date="' . get_post_meta(get_the_ID(), $this->MILESTONE_DATE_META, true) . '" 
                data-location-lat="' . get_post_meta(get_the_ID(), $this->MILESTONE_LOCATION_LAT_META, true) . '" 
                data-location-lon="' . get_post_meta(get_the_ID(), $this->MILESTONE_LOCATION_LON_META, true) . '" 
                data-location-name="' . get_post_meta(get_the_ID(), $this->MILESTONE_LOCATION_NAME_META, true) . '" 
                style="width: 100%; height: 100%; position: absolute;  left: 0; right: 0; top: 0; bottom: 0;"></div>';
        }

        function get_trip_milestones($trip_id)
        {
            return new WP_Query(array(
                'posts_per_page' => -1,
                'post_type' => $this->MILESTONE_TYPE,
                'meta_key' => $this->MILESTONE_DATE_META,
                'order' => 'ASC',
                'orderby' => 'meta_value_num',
                'meta_query' => array(
                    'key' => $this->MILESTONE_TRIP_META,
                    'value' => $trip_id
                )
            ));
        }

        function load_scripts()
        {
            wp_enqueue_script('trip', get_template_directory_uri() . '/public/js/trip.js', array(), '1.0.0', true);
            wp_localize_script('trip', 'framework', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'mapbox_api_key' => get_option($this->option_name)[$this->settings_mapbox_api_key]
            ));
        }

        function settings_init()
        {

            register_setting($this->settings_page, $this->option_name);

            add_settings_section($this->settings_mapbox_section, __('Trip Settings', 'kar'), null, $this->settings_page);
            add_settings_field($this->settings_mapbox_api_key, __('Mapbox API Key', 'kar'), function () {
                echo '<input class="large-text" type="text" name="' . $this->option_name . '[' . $this->settings_mapbox_api_key . ']" value="' . get_option($this->option_name)[$this->settings_mapbox_api_key] . '">';
            }, $this->settings_page, $this->settings_mapbox_section);
        }


        function register_post_types()
        {
            register_post_type($this->MILESTONE_TYPE, array(
                'label' => __('Milestones', $this->DOMAIN),
                'labels' => $this->get_post_type_labels('Milestone', 'Milestones'),
                'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'author'),
                'show_in_rest' => true,
                'hierarchical' => false,
                'public' => true,
                'show_in_menu' => 'edit.php?post_type=' . $this->TRIP_TYPE,
            ));

            register_post_type($this->TRIP_TYPE, array(
                'label' => __('Trips', $this->DOMAIN),
                'labels' => $this->get_post_type_labels('Trip', 'Trips'),
                'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments'),
                'show_in_rest' => true,
                'hierarchical' => true,
                'menu_position' => 5,
                'menu_icon' => 'dashicons-admin-site-alt',//'dashicons-palmtree',
                'public' => true,
            ));
            flush_rewrite_rules();
        }

        function create_meta_boxes()
        {
            add_action('add_meta_boxes', function () {
                add_meta_box('trip_settings', __('Trip Settings', 'kar'), function ($post) {
                    $tour_start = get_post_meta($post->ID, $this->TRIP_START_META, true);

                    HTML::start_table('trip_settings');

                    HTML::start_row(__('Start', $this->DOMAIN));
                    HTML::meta_input('date', $this->TRIP_START_META, $tour_start);
                    HTML::end_row();

                    HTML::end_table();
                }, $this->TRIP_TYPE, 'normal', 'high');

                add_meta_box('milestone_settings', __('Milestone Settings', 'kar'), function ($post) {
                    $date = get_post_meta($post->ID, $this->MILESTONE_DATE_META, true);
                    $trip = get_post_meta($post->ID, $this->MILESTONE_TRIP_META, true);
                    $location_name = get_post_meta($post->ID, $this->MILESTONE_LOCATION_NAME_META, true);
                    $location_lat = get_post_meta($post->ID, $this->MILESTONE_LOCATION_LAT_META, true);
                    $location_lon = get_post_meta($post->ID, $this->MILESTONE_LOCATION_LON_META, true);

                    HTML::start_table('milestone_settings');

                    HTML::start_row(__('Podcast', $this->DOMAIN));
                    HTML::meta_post_type_dropdown($this->MILESTONE_TRIP_META, $trip, $this->TRIP_TYPE, __('No Trip', $this->DOMAIN));
                    HTML::end_row();

                    HTML::start_row(__('Date', $this->DOMAIN));
                    HTML::meta_input('date', $this->MILESTONE_DATE_META, $date);
                    HTML::end_row();

                    HTML::start_row(__('Location', $this->DOMAIN));
                    HTML::meta_input('text', $this->MILESTONE_LOCATION_NAME_META, $location_name, true);
                    HTML::meta_input('text', $this->MILESTONE_LOCATION_LAT_META, $location_lat, true);
                    HTML::meta_input('text', $this->MILESTONE_LOCATION_LON_META, $location_lon, true);
                    echo '<div id="geocoder" class="geocoder"></div>';
                    echo '<div id="map"></div>';
                    HTML::end_row();

                    HTML::end_table();
                }, $this->MILESTONE_TYPE, 'normal', 'high');
            });
            add_action('save_post', function ($post_id) {
                $meta_keys = array(
                    $this->TRIP_START_META,
                    $this->MILESTONE_DATE_META,
                    $this->MILESTONE_TRIP_META,
                    $this->MILESTONE_LOCATION_NAME_META,
                    $this->MILESTONE_LOCATION_LAT_META,
                    $this->MILESTONE_LOCATION_LON_META,
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
