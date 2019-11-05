<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */


get_header("single"); ?>
    <!--        --><?// get_template_part('theme-test'); ?>

    <main class="container mt-4">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="article-font"><? the_title() ?></h1>
            </div>
        </div>
        <div class="row">
            <? if (have_posts()): ?>
                <? while (have_posts()): the_post(); ?>
                    <? get_template_part('templates/'.get_post_type().'/single', get_post_format()); ?>
                <? endwhile; ?>
            <? endif; ?>
        </div>
        <div class="row">
            <? if (comments_open()): comments_template(); endif; ?>
        </div>
    </main>


<? get_footer("single");
