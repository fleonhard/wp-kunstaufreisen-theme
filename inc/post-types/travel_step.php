<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */


defined('ABSPATH') or die("Thanks for visting");

add_action('init', function () {
    $labels = array(
        'name' => _n('Travel Step', 'Travel Steps', 2, 'kar'),
        'singular_name' => _n('Travel Step', 'Travel Steps', 1, 'kar'),
        'menu_name' => __('Travel Step', 'kar'),
//        'parent_item_colon'   => __( 'Parent Podcast', 'kar' ),
        'all_items' => __('All Steps', 'kar'),
        'view_item' => __('View Step', 'kar'),
        'add_new_item' => __('Add New Step', 'kar'),
        'add_new' => __('Add New', 'kar'),
        'edit_item' => __('Edit Step', 'kar'),
        'update_item' => __('Update Step', 'kar'),
        'search_items' => __('Search Step', 'kar'),
        'not_found' => __('Step Not Found', 'kar'),
        'not_found_in_trash' => __('Step Not found in Trash', 'kar'),
    );

    register_post_type('travel_step', array(
        'label' => __('travel-step', 'kar'),
        'description' => '',
        'labels' => $labels,

        // Features this CPT supports in Post Editor
        'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments'),
        'show_in_rest' => true,
        // You can associate this CPT with a taxonomy or custom taxonomy.
        'taxonomies' => array('travel_trip'),
        'rewrite' => array('slug' => 'travel-step'),

        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-location-alt',
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
    ));
});


function travel_step_metabox()
{
    add_meta_box(
        'travel_step',           // Unique ID
        __('Travel Data', 'kar'),  // Box title
        'travel_step_metabox_html',  // Content callback, must be of type callable
        'travel_step',                   // Post type
        'advanced',
        'high'
    );
}

add_action('add_meta_boxes', 'travel_step_metabox');

function travel_step_metabox_html($post)
{
//    wp_nonce_field( 'travel_step_location_metabox_nonce', 'travel_step_location_nonce' );
    $location = get_post_meta($post->ID, 'kar_travel_step_location', true);
    $date = get_post_meta($post->ID, 'kar_travel_step_date', true);
    ?>
    <script>
        $(document).ready(function () {
            $('#travel-date').change(function () {
                const start = Date.parse('15 Jun 2019');
                const date = new Date($('#travel-date').val());
                const diffTime = Math.abs(start - date);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                $('#travel-day').html(diffDays);
            })
        });
    </script>
    <table style="width: 100%; text-align: left">
        <tr>
            <th><?php echo  __('Location', 'kar') ?></th>
            <th><?php echo  __('Date', 'kar') ?></th>
            <th><?php echo  __('Day', 'kar') ?></th>
        </tr>
        <tr>
            <td><input style="width: 100%;" name="travel-location" id="travel-location" type="text"
                       value="<?php echo  $location ?>"></td>
            <td><input style="width: 100%;" name="travel-date" id="travel-date" type="date" value="<?php echo  $date ?>"></td>
            <td>
                <div id="travel-day"></div>
            </td>
        </tr>

    </table>
    <?php
}

function travel_step_save_postdata($post_id)
{
    if (isset($_POST['travel-location'])) {
        update_post_meta($post_id, 'kar_travel_step_location', $_POST['travel-location']);
    }
    if (isset($_POST['travel-date'])) {
        update_post_meta($post_id, 'kar_travel_step_date', $_POST['travel-date']);
    }
}

add_action('save_post', 'travel_step_save_postdata');

add_action('init', function () {
    $labels = array(
        'name' => __('Trip', 'kar'),
        'singular_name' => _n('Trip', 'Trips', 1, 'kar'),
        'search_items' => __('Search Trip', 'kar'),
        'all_items' => __('All Trips', 'kar'),
        'parent_item' => __('Parent Trip', 'kar'),
        'edit_item' => __('Edit Trip', 'kar'),
        'update_item' => __('Update Trip', 'kar'),
        'add_new_item' => __('Add New Trip', 'kar'),
        'new_item_name' => __('New Trip', 'kar'),
        'menu_name' => _n('Trip', 'Trips', 2, 'kar'),
    );

    register_taxonomy('travel_trip', 'travel_step', array(
        'hierarchical' => true,
        'show_in_rest' => true,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'labels' => $labels,
        'publicly_queryable' => true,
        'has_archive' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'travel-trip'),
    ));

    add_editor_for_taxonomy('travel_trip');

});

function kar_get_travel_day()
{
    $date = get_post_meta(get_the_ID(), 'kar_travel_step_date', true);
    $format = 'Y-m-d';
    $start = DateTime::createFromFormat($format, '2019-06-15');
    $step = DateTime::createFromFormat($format, $date);
    return ($step->diff($start)->format("%a"))+ 1;
}

function kar_get_travel_location()
{
    return get_post_meta(get_the_ID(), 'kar_travel_step_location', true);
}

function kar_get_travel_meta()
{
    $out = '<small class="text-muted">';
    $out .= '<span>';
    $out .= '<span class="hs-icon hs-duration"></span>';
    $out .= '&nbsp';
    $out .= kar_get_travel_day();
    $out .= '</span>';
    $out .= '<span class="ml-2">';
    $out .= '<span class="hs-icon hs-location"></span>';
    $out .= kar_get_travel_location();
    $out .= '</span>';
    $out .= '</small>';
    return $out;
}
