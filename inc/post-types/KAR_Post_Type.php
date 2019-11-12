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
            return array(
                'name' => _n($singular, $plural, 2, $this->DOMAIN),
                'singular_name' => _n($singular, $plural, 1, $this->DOMAIN),
                'menu_name' => _n($singular, $plural, 2, $this->DOMAIN),
                'name_admin_bar' => _n($singular, $plural, 2, $this->DOMAIN)
            );
        }
    }
}
