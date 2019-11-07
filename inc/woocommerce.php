<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// https://rudrastyh.com/woocommerce/woocommerce_form_field.html
//add_filter('woocommerce_form_field_text', 'kar_remove_field_wrappers', 20, 4);
//add_filter('woocommerce_form_field_country', 'kar_remove_field_wrappers', 20, 4);
//add_filter('woocommerce_form_field_state', 'kar_remove_field_wrappers', 20, 4);
//add_filter('woocommerce_form_field_textarea', 'kar_remove_field_wrappers', 20, 4);
//add_filter('woocommerce_form_field_checkbox', 'kar_remove_field_wrappers', 20, 4);
//add_filter('woocommerce_form_field_password', 'kar_remove_field_wrappers', 20, 4);
//add_filter('woocommerce_form_field_email', 'kar_remove_field_wrappers', 20, 4);
//add_filter('woocommerce_form_field_tel', 'kar_remove_field_wrappers', 20, 4);
//add_filter('woocommerce_form_field_number', 'kar_remove_field_wrappers', 20, 4);
//add_filter('woocommerce_form_field_select', 'kar_remove_field_wrappers', 20, 4);
//add_filter('woocommerce_form_field_radio', 'kar_remove_field_wrappers', 20, 4);

//function kar_remove_field_wrappers($field, $key, $args, $value) {
/*    $field = preg_replace('/<\/?p(.|\n)*?>/', '', $field);*/
/*    $field = preg_replace('/<p(.|\n)*?>/', '<fieldset class="col-12 form-group">', $field);*/
/*    $field = preg_replace('/<\/p(.|\n)*?>/', '</fieldset>', $field);*/
/*    $field = preg_replace('/<\/?span(.|\n)*?>/', '', $field);*/
//    $field = preg_replace('/<\/?abbr(.|\n)*?abbr>/', '<span class="required">*</span>', $field);
//    return $field;
//}

/** *******************************************************************************************/
/** This is not meant to be here, but it serves as a reference
/** of what is possible to be changed. /**

$defaults = array(
'type'              => 'text',
'label'             => '',
'description'       => '',
'placeholder'       => '',
'maxlength'         => false,
'required'          => false,
'id'                => $key,
'class'             => array(),
'label_class'       => array(),
'input_class'       => array(),
'return'            => false,
'options'           => array(),
'custom_attributes' => array(),
'validate'          => array(),
'default'           => '',
);
/*********************************************************************************************/

//add_filter('woocommerce_form_field_args', 'kar_add_bootstrap_classes_to_form' );
//function kar_add_bootstrap_classes_to_form($args) {
//    switch ( $args['type'] ) {
//
//        case "select" :
//        case 'country' :
//        case "state" :
//        case "password" :
//        case "text" :
//        case "email" :
//        case "tel" :
//        case "number" :
//        case 'textarea' :
//        case 'checkbox' :
//        case 'radio' :
//        default :
//            $args['input_class'] = array('form-control', 'input-lg');
//            break;
//    }
//    return $args;
//}

//add_filter('woocommerce_form_field_text', function ($field, $key, $args, $value) {
//    $out = '<fieldset class="form-group col-lg-12">';
//    $out .= '<label for="'.$key.'" '. ($args['required'] ? 'required' : '') .'>';
//    $out .= esc_html__($args['label'], "woocommerce");
//    if($args['required']) {
//        $out .= ' <span class="required">*</span>';
//    }
//    $out .= '</label>';
//    $out .= '<input class="form-control" type="'.$args['type'].'" name="password" id="password" autocomplete="'.$args['autocomplete'].'"/>';
//    $out .= '</fieldset>';
//    return $out;
//}, 20, 4);


function rewrite_action($tag, $function, $priority) {
    remove_action( $tag, $function, $priority);
    add_action($tag, 'kar_'.$function, $priority);
}

add_action( 'after_setup_theme', 'kar_woocommerce_support' );
if ( ! function_exists( 'kar_woocommerce_support' ) ) {
    /**
     * Declares WooCommerce theme support.
     */
    function kar_woocommerce_support() {
        add_theme_support( 'woocommerce' );

        // Add New Woocommerce 3.0.0 Product Gallery support.
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-slider' );

        // hook in and customizer form fields.
        add_filter( 'woocommerce_form_field_args', 'kar_wc_form_field_args', 10, 3 );

        /**
         * First unhook the WooCommerce wrappers
         */
        remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
        remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

        rewrite_action('woocommerce_before_main_content', 'woocommerce_output_all_notices', 10);
        rewrite_action('woocommerce_before_cart', 'woocommerce_output_all_notices', 10);
        rewrite_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
        rewrite_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );

    }
}

if ( ! function_exists( 'kar_woocommerce_output_all_notices' ) ) {
    function kar_woocommerce_output_all_notices()
    {
        echo '<div class="woocommerce-notices-wrapper col-lg-12">';
        wc_print_notices();
        echo '</div>';
    }
}

if ( ! function_exists( 'kar_woocommerce_template_loop_product_title' ) ) {
    function kar_woocommerce_template_loop_product_title() {
        echo '<h4 class="' . esc_attr( apply_filters( 'woocommerce_product_loop_title_classes', 'woocommerce-loop-product__title' ) ) . ' article-font card-title">' . get_the_title() . '</h4>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}

if ( ! function_exists( 'kar_woocommerce_template_loop_product_link_open' ) ) {
    function kar_woocommerce_template_loop_product_link_open() {
        global $product;
        $link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );
        echo '<a href="' . esc_url( $link ) . '" class="kar-text-link">';    }
}


/**
 * Filter hook function monkey patching form classes
 * Author: Adriano Monecchi http://stackoverflow.com/a/36724593/307826
 */
if ( ! function_exists( 'kar_wc_form_field_args' ) ) {
    function kar_wc_form_field_args( $args ) {
        // Start field type switch case.
        switch ( $args['type'] ) {
            /* Targets all select input type elements, except the country and state select input types */
            case 'select':
                // Add a class to the field's html element wrapper - woocommerce
                // input types (fields) are often wrapped within a <p></p> tag.
                $args['class'][] = 'form-group';
                // Add a class to the form input itself.
                $args['input_class']       = array( 'form-control', 'input-lg' );
                $args['label_class']       = array( 'control-label' );
                $args['custom_attributes'] = array(
                    'data-plugin'      => 'select2',
                    'data-allow-clear' => 'true',
                    'aria-hidden'      => 'true',
                    // Add custom data attributes to the form input itself.
                );
                break;
            // By default WooCommerce will populate a select with the country names - $args
            // defined for this specific input type targets only the country select element.
            case 'country':
                $args['class'][]     = 'form-group single-country';
                $args['label_class'] = array( 'control-label' );
                break;
            // By default WooCommerce will populate a select with state names - $args defined
            // for this specific input type targets only the country select element.
            case 'state':
                // Add class to the field's html element wrapper.
                $args['class'][] = 'form-group';
                // add class to the form input itself.
                $args['input_class']       = array( '', 'input-lg' );
                $args['label_class']       = array( 'control-label' );
                $args['custom_attributes'] = array(
                    'data-plugin'      => 'select2',
                    'data-allow-clear' => 'true',
                    'aria-hidden'      => 'true',
                );
                break;
            case 'password':
            case 'text':
            case 'email':
            case 'tel':
            case 'number':
                $args['class'][]     = 'form-group';
                $args['input_class'] = array( 'form-control', 'input-lg' );
                $args['label_class'] = array( 'control-label' );
                break;
            case 'textarea':
                $args['input_class'] = array( 'form-control', 'input-lg' );
                $args['label_class'] = array( 'control-label' );
                break;
            case 'checkbox':
                $args['label_class'] = array( 'custom-control custom-checkbox' );
                $args['input_class'] = array( 'custom-control-input', 'input-lg' );
                break;
            case 'radio':
                $args['label_class'] = array( 'custom-control custom-radio' );
                $args['input_class'] = array( 'custom-control-input', 'input-lg' );
                break;
            default:
                $args['class'][]     = 'form-group';
                $args['input_class'] = array( 'form-control', 'input-lg' );
                $args['label_class'] = array( 'control-label' );
                break;
        } // end switch ($args).
        return $args;
    }
}

if ( ! is_admin() && ! function_exists( 'wc_review_ratings_enabled' ) ) {
    /**
     * Check if reviews are enabled.
     *
     * Function introduced in WooCommerce 3.6.0., include it for backward compatibility.
     *
     * @return bool
     */
    function wc_reviews_enabled() {
        return 'yes' === get_option( 'woocommerce_enable_reviews' );
    }

    /**
     * Check if reviews ratings are enabled.
     *
     * Function introduced in WooCommerce 3.6.0., include it for backward compatibility.
     *
     * @return bool
     */
    function wc_review_ratings_enabled() {
        return wc_reviews_enabled() && 'yes' === get_option( 'woocommerce_enable_review_rating' );
    }
}
