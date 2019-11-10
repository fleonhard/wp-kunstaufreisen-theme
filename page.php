<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */


get_header("page"); ?>
    <article class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="article-font"><?php the_title() ?></h1>
        </div>
        <?php if (have_posts()): ?>
            <?php while (have_posts()): the_post(); ?>
                <div class="col-12">
                    <?php the_content(); ?>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </article>
<?php get_footer("page");
