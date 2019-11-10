<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */

get_header("index"); ?>
    <div class="row">
        <?php
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                do_action('kar_get_template', 'post');
            }
        }
        ?>
    </div>

    <div class="mt-4 row">
        <div class="col-12">
            <?php kar_get_pagination(); ?>
        </div>
    </div>

<?php get_footer("index");
