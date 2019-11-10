<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */


get_header("single");
do_action('kar_increase_post_views');
?>
    <article class="row mb-4">
        <?php do_action('kar_get_template', 'single') ?>
    </article>

<?php if (comments_open()): comments_template(); endif; ?>
<?php get_footer("single");
