/*
 *  Copyright (c) 2019 Herborn Software
 *
 *  @package kar
 */

require('jquery');

jQuery(document).ready(function ($) {

    'use strict';
    const form = $('#podcast_episode_meta_box_content');


    function getDuration(src, cb) {
        var audio = new Audio();
        $(audio).on("loadedmetadata", function () {
            cb(audio.duration);
        });
        audio.src = src;
    }

    // Runs when the media button is clicked.
    form.find('#kar_podcast_upload_audio')
        .on('click', function (event) {
            event.preventDefault();

            const metaImageFrame = wp.media.frames.metaImageFrame = wp.media({
                title: kar_data.frame_title,
                button: {text: kar_data.button_text},
            });

            metaImageFrame.on('select', function () {
                const media_attachment = metaImageFrame.state().get('selection').first().toJSON();
                form.find('#kar_episode_audio').val(media_attachment.url).change();
            });

            // Opens the media library frame.
            metaImageFrame.open();
        });

    form.find('#kar_episode_audio')
        .on("change paste keyup", function () {
            const media_url = $(this).val();
            form.find('#kar_episode_audio_preview').attr('src', media_url);
            getDuration(media_url, function (length) {
                form.find('#kar_episode_audio_duration').val(length)
            });
        });


    form.find('#kar_episode_podcast')
        .on('change', function () {
            $.post(ajaxurl, {action: 'post_get_episode_number', podcast: $(this).val()},
                function (data) {
                    form.find('#kar_episode_number').val(data);
                }
            );
        });


});