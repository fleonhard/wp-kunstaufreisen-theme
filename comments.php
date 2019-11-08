<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */


?>
<div class="container">

    <?php if (have_comments()): ?>
        <h4 class="article-font">
            <?php
            printf(
                esc_html(_nx('One comment on &ldquo; %2$s &rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'kar')),
                number_format_i18n(get_comments_number()),
                '<span>' . get_the_title() . '</span>'
            )
            ?>
        </h4>

        <ol class="kar-comment-list">
            <?php
            wp_list_comments( array(
                'style'         => 'ol',
                'max_depth'     => 4,
                'short_ping'    => true,
                'avatar_size'   => '50',
                'walker' => new KAR_Walker_Comment(),
                'callback' => null,
                'end-callback' => null,
                'type' => 'all',
//                    'reply-text' => 'Reply Text' Default = Settings,
                'page' => '', //1... Default = Settings
                'per_page' => '',//3... Default = Settings
                'reverse_top_level' => true,
                'reverse_children' => null, // only children
                'format' => 'html5', //html5...
                'echo' => true,
            ));

            ?>
        </ol>

        <?php if (!comments_open() && get_comments_number()): ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'kar') ?></p>
        <?php endif; ?>
    <?php endif; ?>

    <?php comment_form(); ?>
</div>
