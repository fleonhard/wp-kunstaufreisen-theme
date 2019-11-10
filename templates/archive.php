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
