<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */


get_header("page"); ?>


        <div class="row mb-4">
            <div class="col-12 text-center">
                <h1 class="article-font"><?php the_title() ?></h1>
            </div>
        </div>
        <div class="row">
            <?php if (have_posts()): ?>
                <?php while (have_posts()): the_post(); ?>
                    <?php get_template_part('templates/page', ''); ?>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

<?php get_footer("page");
