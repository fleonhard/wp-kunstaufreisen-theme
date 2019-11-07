<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */


add_theme_support('custom-header');
add_theme_support('custom-background');
add_theme_support('post-thumbnails');
add_theme_support('post-formats', array(/*'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'*/));
add_theme_support('html5', array('audio', 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));

//Add to front page
add_action('pre_get_posts', function (\WP_Query $query) {
    if (is_home() && $query->is_main_query())
        $query->set('post_type', array('post', 'podcast', 'travel_step'));
    return $query;
});


add_action('get_header', function ($name) {
    add_filter('current_header', function () use ($name) {
        return (string)$name;
    });
});

add_action('get_footer', function ($name) {
    add_filter('current_footer', function () use ($name) {
        return (string)$name;
    });
});


add_action('after_setup_theme', function () {
    load_theme_textdomain('kar', get_template_directory() . '/lang');
});


add_action('after_setup_theme', function () {
    register_nav_menu('primary', 'Header Navigation Menu');
});

add_action('widgets_init', function () {
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
});

function random_lipsum($amount = 1, $what = 'paras', $start = 0)
{
    $parts = array("Lorem", "ipsum", "dolor", "sit", "amet,", "consetetur", "sadipscing", "elitr,", "sed", "diam", "nonumy", "eirmod", "tempor", "invidunt", "ut", "labore", "et", "dolore", "magna", "aliquyam", "erat,", "sed", "diam", "voluptua.", "At", "vero", "eos", "et", "accusam", "et", "justo", "duo", "dolores", "et", "ea", "rebum.", "Stet", "clita", "kasd", "gubergren,", "no", "sea", "takimata", "sanctus", "est", "Lorem", "ipsum", "dolor", "sit", "amet.");
    $output = [];
    for ($i = 0; $i < $amount; $i++)
        $output[] = $parts[$i % sizeof($parts)];

    return join(" ", $output);
}

/* Function which displays your post date in time ago format */
function hs_time_ago()
{
    return human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ' . __('ago');
}

function kar_get_pagination($pages = '', $range = 2)
{
    $showitems = ($range * 2) + 1;
    global $paged;
    if (empty($paged)) $paged = 1;
    if ($pages == '') {
        global $wp_query;
        $pages = $wp_query->max_num_pages;

        if (!$pages)
            $pages = 1;
    }

    if (1 != $pages) {
        echo '<nav aria-label="Page navigation" role="navigation">';
        echo '<span class="sr-only">Page navigation</span>';
        echo '<ul class="pagination justify-content-center">';

//        echo '<li class="page-item disabled hidden-md-down d-none d-lg-block"><span class="page-link text-primary">Page '.$paged.' of '.$pages.'</span></li>';

        if ($paged > 2 && $paged > $range + 1 && $showitems < $pages)
            echo '<li class="page-item"><a class="page-link btn btn-primary" href="' . get_pagenum_link(1) . '" aria-label="First Page">&laquo;<span class="hidden-sm-down d-none d-md-block"> First</span></a></li>';

        if ($paged > 1 && $showitems < $pages)
            echo '<li class="page-item"><a class="page-link btn btn-primary" href="' . get_pagenum_link($paged - 1) . '" aria-label="Previous Page">&lsaquo;<span class="hidden-sm-down d-none d-md-block"> Previous</span></a></li>';

        for ($i = 1; $i <= $pages; $i++) {
            if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems))
                echo ($paged == $i) ? '<li class="page-item active"><span class="btn btn-outline-primary"><span class="sr-only">Current Page </span>' . $i . '</span></li>' : '<li class="page-item"><a class=" btn btn-primary" href="' . get_pagenum_link($i) . '"><span class="sr-only">Page </span>' . $i . '</a></li>';
        }

        if ($paged < $pages && $showitems < $pages)
            echo '<li class="page-item"><a class="btn btn-primary" href="' . get_pagenum_link($paged + 1) . '" aria-label="Next Page"><span class="hidden-sm-down d-none d-md-block">Next </span>&rsaquo;</a></li>';

        if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages)
            echo '<li class="page-item"><a class="btn btn-primary" href="' . get_pagenum_link($pages) . '" aria-label="Last Page"><span class="hidden-sm-down d-none d-md-block">Last </span>&raquo;</a></li>';

        echo '</ul>';
        echo '</nav>';
    }
}


function add_editor_for_taxonomy($taxonomy) {
    add_action("{$taxonomy}_edit_form_fields", function ($term){
        ?>
        <tr>
            <th scope="row">Description</th>
            <td>
                <?php wp_editor(html_entity_decode($term->description), 'description', array('media_buttons' => false)); ?>
                <script>
                    jQuery(window).ready(function(){
                        jQuery('label[for=description]').parent().parent().remove();
                    });
                </script>
            </td>
        </tr>
        <?php
    }, 10, 2);
}

function kar_get_embedded_link_url()
{
    if (!preg_match('/<a\s[^>]*?href=[\'"](.+?)[\'"]/i', get_the_content(), $links))
        return false;
    return esc_url_raw($links[1]);
}

function kar_get_embedded_img_ids($content = null)
{
    $content = $content == null ? get_the_content() : $content;
    if (!preg_match_all('/<img\s[^>]*?data\-id=[\'"](\d+?)[\'"]/i', $content, $links))
        return false;
    return $links[1];
}

function kar_get_server_url()
{
    $http = isset($_SERVER["HTTPS"]) ? 'https://' : 'http://';
    $referer = $http . $_SERVER["HTTP_HOST"];
    return $referer . $_SERVER["REQUEST_URI"];
}

function kar_get_embedded_audio_src()
{
    $content = get_the_content();
    if (preg_match_all('/<audio\s[^>]*?src=[\'"](.+?)[\'"]/i', $content, $links)) {
        return $links[1][0];
    }
    return false;
}

function kar_get_embedded_medias($type = array(), $num = 1)
{
    $content = do_shortcode(apply_filters('the_content', get_the_content()));
    $embed = get_media_embedded_in_content($content, $type);
    $output = [];
    if ($embed) {
        for ($i = 0; $i < count($embed); $i++) {
            if ($i < $num) {
                if (in_array('audio', $type)) {
                    $output[] = str_replace('?visual=true', '?visual=false', $embed[$i]);
                } else {
                    $output[] = $embed[$i];
                }
            }
        }
    }

    return $output;
}

function kar_get_embedded_media($type = array())
{
    $medias = kar_get_embedded_medias($type, 1);
    if (count($medias) > 0)
        return $medias[0];
    return null;
}

function kar_get_embedded_image_id()
{
    $ids = kar_get_image_attachment_ids(1);
    if (count($ids) > 0)
        return $ids[0];
    return null;
}

function kar_get_embedded_image_ids($num = 1)
{
    $output = [];
    if ($num > 0 && has_post_thumbnail()) {
        $output[] = get_post_thumbnail_id(get_the_ID());
        $num--;
    }

    if ($num > 0) {
        $imageIds = kar_grab_img_ids();
        if ($imageIds && count($imageIds) > 0) {
            foreach ($imageIds as $imageId) {
                if ($num > 0) {
                    $output[] = $imageId;
                    $num--;
                }
            }
        }
    }

    return $output;
}


function kar_get_shortcode( $content, $type ) {
    $regex = '/'.get_shortcode_regex( array($type) ).'/';
    if(preg_match_all( '/'. get_shortcode_regex( array($type) ) .'/s', $content, $matches )) {
        return $matches[0][0];
    }
    return null;
}

function kar_get_audio_as_shortcode($content) {
    $shortcode = kar_get_shortcode($content, 'audio');
    if(!$shortcode) {
        $src = kar_get_embedded_audio_src();
        if($src) {
            $shortcode = '[audio src="'.$src.'"]';
        }
    }
    return $shortcode;
}

function kar_get_post_meta() {
    $author = get_the_author();
    $author_link = get_author_posts_url(get_the_author_meta('ID'));
    $author_image = get_avatar(get_the_author_meta('ID'));
    $post_link = esc_url(get_permalink());
    $output = '<small class="post-meta text-muted">';
    $output .= '<a class="date kar-link" href="'.$post_link.'">' . hs_time_ago() . '</a> ';
    $output .= __('by'). ' <a class="author kar-link" href="'.$author_link.'">' . $author_image . ' '. $author . '</a>';
    $output .= '</small>';
    return $output;
}


function kar_get_post_statistic() {
    $author = get_the_author();
    $author_link = get_author_posts_url(get_the_author_meta('ID'));
    $author_image = get_avatar(get_the_author_meta('ID'));
    $post_link = esc_url(get_permalink());
    $comments_link = esc_url(get_comments_link());
    $output = '<small class="post-meta text-muted">';
//    $output .= '<a class="date kar-link" href="'.$post_link.'">'. __("Views", "kar"). ' ' . kar_get_post_views() . '</a> ';
    $output .= '<a class="date kar-link" href="'.$post_link.'"><span class="hs-icon hs-watched"></span> ' . kar_get_post_views() . '</a> ';
    $output .= '<a class="date kar-link" href="'.$comments_link.'"><span class="hs-icon hs-comment ml-2"></span> ' . get_comments_number() . '</a> ';
    $output .= '</small>';
    return $output;
}

function kar_get_category_list() {
    $categories = array_map(function ($category) {
        return '<a class="kar-link" href="' . esc_url(get_category_link($category->term_id)) . '" alt="' . $category->name . '">' . esc_html($category->name) . '</a>';
    }, get_the_category());
    return '<small class="">'.join(' &#183 ', $categories).'</small>';
}


function kar_get_tag_list() {
    $tags = get_the_tags();
    if($tags) {
        $tags = array_map(function ($tag) {
            return '<a class="kar-link" href="' . esc_url(get_tag_link($tag)) . '" alt="' . $tag->name . '">' . esc_html($tag->name) . '</a>';
        }, get_the_tags());
        return '<small class=""><span class="hs-icon hs-tag mr-1"></span>'.join(' &#183 ', $tags).'</small>';
    }
    return null;
}


function kar_get_taxonomies($name) {
    $taxonomies = array_map(function ($category) {
        return '<a class="kar-link" href="' . esc_url(get_category_link($category->term_id)) . '" alt="' . $category->name . '">' . esc_html($category->name) . '</a>';
    }, wp_get_post_terms(get_the_ID(), $name, array( 'fields' => 'all' )));
    return '<small class="">'.join(' ', $taxonomies).'</small>';
}

// Comments form.
add_filter( 'comment_form_default_fields', 'hs_bootstrap_comment_form_fields' );
if ( ! function_exists( 'hs_bootstrap_comment_form_fields' ) ) {

    function hs_bootstrap_comment_form_fields( $fields ) {
        $commenter = wp_get_current_commenter();
        $req       = get_option( 'require_name_email' );
        $aria_req  = ( $req ? " aria-required='true'" : '' );
        $html5     = current_theme_supports( 'html5', 'comment-form' ) ? 1 : 0;
        $consent  = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
        $fields    = array(
            'author'  => '<div class="form-group comment-form-author"><label for="author">' . __( 'Name',
                    'hs' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
                '<input class="form-control" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . '></div>',
            'email'   => '<div class="form-group comment-form-email"><label for="email">' . __( 'Email',
                    'hs' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
                '<input class="form-control" id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . '></div>',
            'url'     => '<div class="form-group comment-form-url"><label for="url">' . __( 'Website',
                    'hs' ) . '</label> ' .
                '<input class="form-control" id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30"></div>',
            'cookies' => '<div class="form-group form-check comment-form-cookies-consent"><input class="form-check-input" id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' /> ' .
                '<label class="form-check-label" for="wp-comment-cookies-consent">' . __( 'Save my name, email, and website in this browser for the next time I comment', 'hs' ) . '</label></div>',
        );

        return $fields;
    }
}

add_filter( 'comment_form_defaults', 'hs_bootstrap_comment_form' );
if ( ! function_exists( 'hs_bootstrap_comment_form' ) ) {

    function hs_bootstrap_comment_form( $args ) {
        $args['comment_field'] = '<div class="form-group comment-form-comment">
	    <label for="comment">' . _x( 'Comment', 'noun', 'hs' ) . ( ' <span class="required">*</span>' ) . '</label>
	    <textarea class="form-control" id="comment" name="comment" aria-required="true" cols="45" rows="8"></textarea>
	    </div>';
        $args['class_submit']  = 'btn btn-primary w-100'; // since WP 4.1.
        return $args;
    }
}
