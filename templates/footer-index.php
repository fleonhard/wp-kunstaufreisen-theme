<?php
/**
 * @author Florian Herborn
 * @copyright 2019 Herborn Software
 * @license GPL-2.0-or-later
 *
 * @package kar
 */
$sidebar_active = is_active_sidebar('main_sidebar');
?>


<footer class="page-footer container-fluid">
    <div class="row">
        <div class="container">
            <div class="row">
                <!--                <div class="copyright col-12 text-center">--><? //= hs_site_info() ?><!--</div>-->
            </div>
        </div>
    </div>
</footer>
</div>

<?php if ($sidebar_active): ?>
    <div class="row">
        <div class="col-12 mb-4 d-lg-none">
            <?php get_template_part('sidebar', 'main_sidebar'); ?>
        </div>
    </div>
<?php endif; ?>
</main>
