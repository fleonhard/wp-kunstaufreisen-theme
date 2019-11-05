<?php
/**
 * Copyright (c) 2019 Herborn Software
 */


$kar_post_views_meta_key = 'kar_post_views';
function kar_increase_post_views($post_id = false) {
    if(!$post_id) {
        $post_id = get_the_ID();
    }
    global $kar_post_views_meta_key;
    update_post_meta($post_id, $kar_post_views_meta_key, kar_get_post_views($post_id) + 1);
}
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);

function kar_get_post_views($post_id = false) {
    if(!$post_id) {
        $post_id = get_the_ID();
    }
    global $kar_post_views_meta_key;
    $views = get_post_meta($post_id, $kar_post_views_meta_key, true);
    return empty($views) ? 0 : $views;
}
