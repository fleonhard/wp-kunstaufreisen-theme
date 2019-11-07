<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */
$sidebar_active = is_active_sidebar('main_sidebar');
?>
</div>

<?php if ($sidebar_active): ?>
<div class="row">
    <div class="col-12 mb-4 d-lg-none">
        <?php get_template_part( 'sidebar', 'main_sidebar' ); ?>
    </div>
</div>
<?php endif; ?>
</main>

<?php get_template_part('templates/footer', apply_filters('current_footer', '')); ?>
<?php wp_footer(); ?>
</body>
