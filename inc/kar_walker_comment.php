<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */

defined('ABSPATH') || exit;

if (!class_exists('KAR_Walker_Comment')) {
    class KAR_Walker_Comment extends Walker_Comment
    {
        protected function html5_comment($comment, $depth, $args)
        {
            $tag = ($args['style'] === 'div') ? 'div' : 'li';
            ?>
            <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class($this->has_children ? 'has-children media' : ' media'); ?>>


        <div class="mt-3 w-100" id="div-comment-<?php comment_ID(); ?>">
            <div class="card-header">
                <div class="d-flex flex-column flex-lg-row justify-content-center align-items-center text-center">
                    <?php if ($args['avatar_size'] != 0): ?>
                        <a href="<?php echo get_comment_author_url(); ?>" class="media-object">
                            <?php echo get_avatar($comment, $args['avatar_size'], 'mm', '', array('class' => "rounded-circle")); ?>
                        </a>
                    <?php endif; ?>
                    <div>
                        <h6 class="media-heading"><?php echo get_comment_author_link() ?></h6>
                        <time class="small text-muted ml-lg-4" datetime="<?php comment_time('c'); ?>">
                            <?php comment_date() ?>, <?php comment_time() ?>
                        </time>
                    </div>
                    <div class="comment-metadata d-inline-flex flex-column flex-lg-row text-left justify-content-lg-end flex-fill align-items-center">
                        <?php edit_comment_link(__('Edit')); ?>
                        <?php
                        comment_reply_link(array_merge($args, array(
                            'add_below' => 'div-comment',
                            'depth' => $depth,
                            'max_depth' => $args['max_depth']
                        )));
                        ?>
                    </div><!-- .comment-metadata -->
                </div>
            </div>
            <div class="card-block warning-color">
            <?php if ('0' == $comment->comment_approved) : ?>
            <p class="card-text comment-awaiting-moderation label label-info text-muted small"><?php _e('Your comment is awaiting moderation.'); ?></p>
        <?php endif; ?>

            <div class="comment-content card-text p-2">
                <?php comment_text(); ?>
            </div>
            <?php
        }
    }
}