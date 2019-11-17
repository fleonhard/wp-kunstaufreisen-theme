<?php
/**
 * @author Florian Herborn
 * @copyright 2019 Herborn Software
 * @license GPL-2.0-or-later
 *
 * @package kar
 */

?>
<div class="row mb-4">
    <div class="col-12 text-center">
        <h1 class="article-font"><?php echo get_the_archive_title() ?></h1>
    </div>
</div>
<div class="row mb-4">
    <div class="col-12 text-center">
        <div class="article-font"><?php the_archive_description() ?></div>
    </div>
</div>

<div class="row">
    <div class="col-12">

        <div class="gallery">
            <?php
            if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    ?>
                    <div class="gallery-item">
                        <img id="image-<?php echo get_the_ID() ?>"
                             src="<?php echo get_the_post_thumbnail_url() ?>"
                             alt="<?php echo get_the_post_thumbnail_caption() ?>"
                             class=" fullscreen-image-toggle"
                             data-img="#image-<?php echo get_the_ID() ?>">
                        <a class="overlay kar-link" href="<?php echo get_the_permalink() ?>">
                            <div><?php echo get_the_title() ?></div>
                        </a>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>

<div class="mt-4 row">
    <div class="col-12">
        <?php kar_get_pagination(); ?>
    </div>
</div>
