<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */
?>


</main>

<?php if (is_active_sidebar('sidebar_right')): ?>
    <aside id="secondary" class="col-12 py-4 col-xl-3 kar-sidebar" role="complementary">
        <div class="widget-area row px-xl-4">
            <?php dynamic_sidebar('sidebar_right'); ?>
        </div>
    </aside>
<?php endif; ?>
</div>
</div>
<?php get_template_part('templates/footer', apply_filters('current_footer', '')); ?>
<?php wp_footer(); ?>
</body>
