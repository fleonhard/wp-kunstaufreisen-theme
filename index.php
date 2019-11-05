<?
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */
get_header("index"); ?>

    <div class="row">
        <? if (have_posts()): ?>
            <? while (have_posts()): the_post(); ?>
                <div class="col-12 mb-4">
                    <? get_template_part('templates/' . get_post_type() . '/post', get_post_format()); ?>
                </div>
            <? endwhile; ?>
        <? endif; ?>
    </div>

    <div class="mt-4 row">
        <div class="col-12">
            <? kar_get_pagination(); ?>
        </div>
    </div>

<? get_footer("index");
