<?
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */

get_header("archive"); ?>

    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="article-font"><?php echo  get_the_archive_title() ?></h1>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12 text-center">
            <div class="article-font"><?php the_archive_description() ?></div>
        </div>
    </div>

    <div class="row">
        <?php if (have_posts()): ?>
            <?php while (have_posts()): the_post(); ?>
                <div class="col-12 mb-4">
                    <?php get_template_part('templates/' . get_post_type() . '/post', get_post_format()); ?>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <div class="mt-4 row">
        <div class="col-12">
            <?php kar_get_pagination(); ?>
        </div>
    </div>


<?php get_footer("archive");
