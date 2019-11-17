<?php
/**
 * @author Florian Herborn
 * @copyright 2019 Herborn Software
 * @license GPL-2.0-or-later
 *
 * @package kar
 */

defined('ABSPATH') || exit;

if (!class_exists('KAR_Post_Type')) {
    abstract class KAR_Post_Type
    {
        protected $DOMAIN = 'kar';
        protected $settings_page = 'kar_settings';

        protected function __construct()
        {

            add_action('init', array($this, 'register_post_types'));
        }

        abstract function register_post_types();

        protected function get_post_type_labels($singular, $plural)
        {
            $s = _n($singular, $plural, 1, $this->DOMAIN);
            $p = _n($singular, $plural, 2, $this->DOMAIN);
            return array(
                'name' => _n($singular, $plural, 2, $this->DOMAIN),
                'singular_name' => _n($singular, $plural, 1, $this->DOMAIN),
                'menu_name' => _n($singular, $plural, 2, $this->DOMAIN),
                'name_admin_bar' => _n($singular, $plural, 2, $this->DOMAIN),

                'search_items' => sprintf(__('Search %1$s', $this->DOMAIN), $s),
                'all_items' => sprintf(__('All %1$s', $this->DOMAIN), $p),
                'parent_item' => sprintf(__('Parent %1$s', $this->DOMAIN), $s),
                'edit_item' => sprintf(__('Edit %1$s', $this->DOMAIN), $s),
                'update_item' => sprintf(__('All %1$s', $this->DOMAIN), $s),
                'add_new_item' => sprintf(__('Add New %1$s', $this->DOMAIN), $s),
                'add_new' => sprintf(__('Add New %1$s', $this->DOMAIN), $s),
                'new_item_name' => sprintf(__('New %1$s', $this->DOMAIN), $s),
            );
        }
    }
}
