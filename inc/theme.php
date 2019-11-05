<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */


add_theme_support('custom-header');
add_theme_support('custom-background');
add_theme_support('post-thumbnails');
add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));
add_theme_support('html5', array('audio', 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));

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
