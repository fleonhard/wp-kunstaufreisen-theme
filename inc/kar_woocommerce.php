<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

if (!class_exists('KAR_Woocommerce')) {
    final class KAR_Woocommerce
    {

        public static function install()
        {
            return new KAR_Woocommerce();
        }

        private function __construct()
        {
            add_theme_support('woocommerce');
            add_theme_support('wc-product-gallery-lightbox');
            add_theme_support('wc-product-gallery-zoom');
            add_theme_support('wc-product-gallery-slider');

            add_filter('woocommerce_form_field_args', array($this, 'form_field_args'), 10, 3);

            remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
            remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
            remove_action('woocommerce_before_main_content', 'woocommerce_output_all_notices', 10);
            remove_action('woocommerce_before_cart', 'woocommerce_output_all_notices', 10);
            remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
            remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);

            add_action('woocommerce_before_main_content', array($this, 'output_all_notices'), 10);
            add_action('woocommerce_before_cart', array($this, 'output_all_notices'), 10);
            add_action('woocommerce_shop_loop_item_title', array($this, 'loop_product_title'), 10);
            add_action('woocommerce_before_shop_loop_item', array($this, 'loop_product_link_open'), 10);
        }

        function output_all_notices()
        {
            echo '<div class="woocommerce-notices-wrapper col-lg-12">';
            wc_print_notices();
            echo '</div>';
        }

        function loop_product_title()
        {
            echo '<h4 class="' . esc_attr(apply_filters('woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title')) . ' article-font card-title">' . get_the_title() . '</h4>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        function loop_product_link_open()
        {
            global $product;
            $link = apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product);
            echo '<a href="' . esc_url($link) . '" class="kar-text-link">';
        }

        function form_field_args($args)
        {
            // Start field type switch case.
            switch ($args['type']) {
                /* Targets all select input type elements, except the country and state select input types */
                case 'select':
                    // Add a class to the field's html element wrapper - woocommerce
                    // input types (fields) are often wrapped within a <p></p> tag.
                    $args['class'][] = 'form-group';
                    // Add a class to the form input itself.
                    $args['input_class'] = array('form-control', 'input-lg');
                    $args['label_class'] = array('control-label');
                    $args['custom_attributes'] = array(
                        'data-plugin' => 'select2',
                        'data-allow-clear' => 'true',
                        'aria-hidden' => 'true',
                        // Add custom data attributes to the form input itself.
                    );
                    break;
                // By default WooCommerce will populate a select with the country names - $args
                // defined for this specific input type targets only the country select element.
                case 'country':
                    $args['class'][] = 'form-group single-country';
                    $args['label_class'] = array('control-label');
                    break;
                // By default WooCommerce will populate a select with state names - $args defined
                // for this specific input type targets only the country select element.
                case 'state':
                    // Add class to the field's html element wrapper.
                    $args['class'][] = 'form-group';
                    // add class to the form input itself.
                    $args['input_class'] = array('', 'input-lg');
                    $args['label_class'] = array('control-label');
                    $args['custom_attributes'] = array(
                        'data-plugin' => 'select2',
                        'data-allow-clear' => 'true',
                        'aria-hidden' => 'true',
                    );
                    break;
                case 'password':
                case 'text':
                case 'email':
                case 'tel':
                case 'number':
                    $args['class'][] = 'form-group';
                    $args['input_class'] = array('form-control', 'input-lg');
                    $args['label_class'] = array('control-label');
                    break;
                case 'textarea':
                    $args['input_class'] = array('form-control', 'input-lg');
                    $args['label_class'] = array('control-label');
                    break;
                case 'checkbox':
                    $args['label_class'] = array('custom-control custom-checkbox');
                    $args['input_class'] = array('custom-control-input', 'input-lg');
                    break;
                case 'radio':
                    $args['label_class'] = array('custom-control custom-radio');
                    $args['input_class'] = array('custom-control-input', 'input-lg');
                    break;
                default:
                    $args['class'][] = 'form-group';
                    $args['input_class'] = array('form-control', 'input-lg');
                    $args['label_class'] = array('control-label');
                    break;
            } // end switch ($args).
            return $args;
        }

    }
}