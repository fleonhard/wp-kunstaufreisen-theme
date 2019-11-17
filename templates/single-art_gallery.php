<?php
/**
 * @author Florian Herborn
 * @copyright 2019 Herborn Software
 * @license GPL-2.0-or-later
 *
 * @package kar
 */

?>

<div class="col-12 text-center mb-4">
    <h1 class="article-font"><?php the_title() ?></h1>
</div>
<?php if (have_posts()): ?>
    <?php while (have_posts()): the_post(); ?>
        <div <?php post_class('col-12 article-font'); ?>>
            <?php the_content(); ?>
        </div>
    <?php endwhile; ?>
<?php endif; ?>

<div class="col-12">
    <?php $topics = apply_filters('kar_get_gallery_topics', get_the_ID()); ?>
    <?php foreach ($topics as $topic): ?>
        <div class="text-center my-4">
            <h1><?php echo $topic->name ?></h1>
            <div><?php printf(__("%s Images", 'kar'), $topic->post_count) ?></div>
        </div>
        <div class="gallery">
            <?php $images = apply_filters('kar_get_gallery_taxonomy_images', $topic->term_id); ?>
            <?php global $post; ?>
            <?php foreach ($images as $image): ?>
                <div class="gallery-item">
                    <img id="image-<?php echo $topic->term_id . $image->ID ?>"
                         src="<?php echo get_the_post_thumbnail_url($image->ID) ?>"
                         alt="<?php echo get_the_post_thumbnail_caption($image->ID) ?>"
                         class=" fullscreen-image-toggle"
                         data-img="#image-<?php echo $topic->term_id . $image->ID ?>">
                    <a class="overlay kar-link" href="<?php echo get_the_permalink($image->ID) ?>">
                        <div><?php echo get_the_title($image->ID) ?></div>
                    </a>
                </div>
            <?php endforeach; ?>
            <?php wp_reset_postdata(); ?>
        </div>
    <?php endforeach; ?>
</div>
