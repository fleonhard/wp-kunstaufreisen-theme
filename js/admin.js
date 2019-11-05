/*
 * Copyright (c) 2019 Herborn Software
 *
 * @package hs
 */

$(document).ready(function ($) {
    let file_frame;
    let wp_media_post_id = wp.media.model.settings.post.id; // Store the old id

    $('#upload_media_button').on('click', function (event) {
        event.preventDefault();

        const post_id = $(this).data('post-id');
        const select_button_text = $(this).data('select-button-text');
        const media_type = $(this).data('media-type');
        const preview_id = $(this).data('preview-id');
        const preview_attr = $(this).data('preview-attr');

        if (file_frame) {
            file_frame.uploader.uploader.param('post_id', post_id);
            file_frame.open();
            return;
        } else {
            wp.media.model.settings.post.id = post_id;
        }
        file_frame = wp.media.frames.file_frame = wp.media({
            title: title,
            button: {text: select_button_text},
            library: {type: [media_type]},
            multiple: false
        });
        file_frame.on('select', function () {
            let attachment = file_frame.state().get('selection').first().toJSON();
            $(`#${preview_id}`).attr(preview_attr, attachment.url);
            wp.media.model.settings.post.id = wp_media_post_id;
        });
        file_frame.open();
    });
    $('a.add_media').on('click', function () {
        wp.media.model.settings.post.id = wp_media_post_id;
    });
});
