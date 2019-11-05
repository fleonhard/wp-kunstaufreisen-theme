<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */


get_header("page"); ?>


        <div class="row mb-4">
            <div class="col-12 text-center">
                <h1 class="article-font"><? the_title() ?></h1>
            </div>
        </div>
        <div class="row">
            <? if (have_posts()): ?>
                <? while (have_posts()): the_post(); ?>
                    <? get_template_part('templates/page', ''); ?>
                <? endwhile; ?>
            <? endif; ?>
        </div>

<? get_footer("page");
