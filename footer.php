<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */
?>


<?php if (is_active_sidebar('mobile_bottom')): ?>
    <aside class="d-xl-none widget-area row kar-sidebar pt-4" role="complementary">
        <?php dynamic_sidebar('mobile_bottom'); ?>
    </aside>
<?php endif; ?>

<?php if (is_active_sidebar('desktop_bottom')): ?>
    <aside class="d-none d-xl-block widget-area row kar-sidebar pt-4" role="complementary">
        <?php dynamic_sidebar('desktop_bottom'); ?>
    </aside>
<?php endif; ?>
</main>

<?php if (is_active_sidebar('desktop_right')): ?>
    <aside id="secondary" class="d-none d-xl-block py-4 col-3 kar-sidebar kar-sidebar-right" role="complementary">
        <div class="widget-area row px-4">
            <?php dynamic_sidebar('desktop_right'); ?>
        </div>
    </aside>
<?php endif; ?>
</div>
</div>
<?php get_template_part('templates/footer', apply_filters('current_footer', '')); ?>
<?php wp_footer(); ?>
</body>
