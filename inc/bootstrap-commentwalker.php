<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */


class Bootstrap_Comment_Walker extends Walker_Comment
{
    protected function html5_comment($comment, $depth, $args)
    {
        $tag = ($args['style'] === 'div') ? 'div' : 'li';
        ?>
        <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class($this->has_children ? 'has-children media' : ' media'); ?>>


    <div class="mt-3 w-100" id="div-comment-<?php comment_ID(); ?>">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <?php if ($args['avatar_size'] != 0): ?>
                    <a href="<?php echo get_comment_author_url(); ?>" class="media-object float-left">
                        <?php echo get_avatar($comment, $args['avatar_size'], 'mm', '', array('class' => "rounded-circle mr-3")); ?>
                    </a>
                <?php endif; ?>
                <h5 class="media-heading"><?php echo get_comment_author_link() ?></h5>
                <time class="small text-muted ml-4" datetime="<?php comment_time('c'); ?>">
                    <?php comment_date() ?>, <?php comment_time() ?>
                </time>
                <div class="comment-metadata">
                    <ul class="list-inline">
                        <?php edit_comment_link(__('Edit'), '<li class="edit-link list-inline-item  btn btn-link">', '</li>'); ?>
                        <?php
                        comment_reply_link(array_merge($args, array(
                            'add_below' => 'div-comment',
                            'depth' => $depth,
                            'max_depth' => $args['max_depth'],
                            'before' => '<li class="reply-link list-inline-item btn btn-link">',
                            'after' => '</li>'
                        )));
                        ?>
                    </ul>
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
