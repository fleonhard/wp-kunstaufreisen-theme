<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */


get_header("single");
kar_increase_post_views();
?>
    <!--        --><?// get_template_part('theme-test'); ?>

    <main class="container mt-4">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="article-font"><?php the_title() ?></h1>
            </div>
        </div>
        <div class="row">
            <?php if (have_posts()): ?>
                <?php while (have_posts()): the_post(); ?>
                    <?php get_template_part('templates/' . get_post_type() . '/single', get_post_format()); ?>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
        <div class="row">
            <?php if (comments_open()): comments_template(); endif; ?>
        </div>
    </main>


<?php get_footer("single");
