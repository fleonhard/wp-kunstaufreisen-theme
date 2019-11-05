<?
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */

get_header("archive"); ?>
    <!--        --><?// get_template_part('theme-test'); ?>
    <main class="container pb-4 pt-4">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="article-font"><?= get_the_archive_title() ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center">
                <div class="article-font"><? the_archive_description( )  ?></div>
            </div>
        </div>
        <div class="row">
            <? if (have_posts()): ?>
                <? while (have_posts()): the_post(); ?>
                    <? get_template_part('templates/'.get_post_type().'/post', get_post_format()); ?>
                <? endwhile; ?>
            <? endif; ?>
        </div>

        <div class="mt-4 row">
            <div class="col-12">
                <? kar_get_pagination(); ?>
            </div>
        </div>

    </main>


<? get_footer("archive");
