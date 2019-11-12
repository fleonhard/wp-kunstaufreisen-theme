<?php
/**
 * @author Florian Herborn
 * @copyright 2019 Herborn Software
 * @license GPL-2.0-or-later
 *
 * @package kar
 */


defined('ABSPATH') || exit;


if (!class_exists('HTML')) {

    final class HTML
    {
        public static function start_row($title)
        {
            echo '<tr>';
            echo '<th>' . $title . '</th>';
            echo '<td>';
        }

        public static function end_row()
        {
            echo '</td>';
            echo '</tr>';
        }

        public static function input($type, $id, $value, $hidden = false)
        {
            echo '<input type="' . $type . '" class="large-text" name="' . $id . '" id="' . $id . '" value="' . esc_attr__($value) . '" ' . ($hidden ? 'hidden' : '') . '>';
        }

        public static function meta_input($type, $meta_key, $value, $hidden = false)
        {
            self::nonce($meta_key);
            self::input($type, $meta_key, $value, $hidden);
        }

        public static function audio($id, $src)
        {
            echo '<audio controls id="' . $id . '" style="width: 100%" src="' . esc_attr__($src) . '"></audio>';
        }

        public static function btn($id, $text)
        {
            echo '<button type="button" class="button" id="' . $id . '">' . $text . ' </button>';
        }

        public static function nonce($meta_key)
        {
            wp_nonce_field(plugin_basename(__FILE__), self::get_nonce($meta_key));
        }

        public static function get_nonce($meta_key)
        {
            return $meta_key . '_nonce';
        }

        public static function verify($meta_key)
        {
            return wp_verify_nonce($_POST[self::get_nonce($meta_key)], plugin_basename(__FILE__)) && isset($_POST[$meta_key]);
        }

        public static function meta_post_type_dropdown($meta_key, $selected, $post_type, $none_option)
        {
            self::nonce($meta_key);
            self::post_type_dropdown($meta_key, $selected, $post_type, $none_option);
        }

        public static function post_type_dropdown($meta_key, $selected, $post_type, $none_option)
        {
            $dropdown = wp_dropdown_pages(array(
                'post_type' => $post_type,
                'selected' => $selected,
                'name' => $meta_key,
                'show_option_none' => $none_option,
                'sort_column' => 'menu_order, post_title',
                'echo' => 0
            ));
            if (!empty($dropdown)) {
                echo $dropdown;
            }
        }

        public static function start_table($id)
        {
            echo '<table id="' . $id . '" class="kar-meta-box">';
            echo '<tbody>';
        }

        public static function end_table()
        {
            echo '</tbody>';
            echo '</table>';
        }
    }
}