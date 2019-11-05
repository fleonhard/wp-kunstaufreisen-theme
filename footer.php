<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */
$sidebar_active = is_active_sidebar('main_sidebar');
?>
</div>
</main>

<? if ($sidebar_active): ?>
    <div class="col-12 mb-4 d-md-none">
        <? get_template_part( 'sidebar', 'main_sidebar' ); ?>
    </div>
<? endif; ?>

<? get_template_part('templates/footer', apply_filters('current_footer', '')); ?>
<?php wp_footer(); ?>
</body>
