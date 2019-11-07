<?php
/**
 * Copyright (c) 2019 Herborn Software
 *
 * @package kar
 */

/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.1
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart'); ?>

    <form class="woocommerce-cart-form col-lg-12" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
        <?php do_action('woocommerce_before_cart_table'); ?>
        <div class="table-responsive mb-4">
            <table class="table table-hover col-lg-12">
                <thead>
                <tr class="d-flex">
                    <th class="col-1 product-remove">&nbsp</th>
                    <th class="col-2 product-thumbnail ">&nbsp</th>
                    <th class="col-3 product-name"><?php esc_html_e('Product', 'hs'); ?></th>
                    <th class="col-2 product-price"><?php esc_html_e('Price', 'hs'); ?></th>
                    <th class="col-2 product-quantity"><?php esc_html_e('Quantity', 'hs'); ?></th>
                    <th class="col-2 product-subtotal"><?php esc_html_e('Total', 'hs'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php do_action('woocommerce_before_cart_contents'); ?>

                <?php
                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                    $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                    $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                    if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                        ?>
                        <tr class="d-flex woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">

                            <td class="product-remove col-1 d-flex align-items-center">
                                <?php
                                // @codingStandardsIgnoreLine
                                echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
                                    '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                                    esc_url(wc_get_cart_remove_url($cart_item_key)),
                                    __('Remove this item', 'hs'),
                                    esc_attr($product_id),
                                    esc_attr($_product->get_sku())
                                ), $cart_item_key);
                                ?>
                            </td>

                            <td class="product-thumbnail col-2">
                                <?php
                                $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

                                if (!$product_permalink) {
                                    echo $thumbnail; // PHPCS: XSS ok.
                                } else {
                                    printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
                                }
                                ?>
                            </td>

                            <td class="product-name col-3" data-title="<?php esc_attr_e('Product', 'hs'); ?>">
                                <?php
                                if (!$product_permalink) {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                } else {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s" class="kar-link">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                }

                                do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                                // Meta data.
                                echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.

                                // Backorder notification.
                                if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'hs') . '</p>', $product_id));
                                }
                                ?>
                            </td>

                            <td class="product-price col-2" data-title="<?php esc_attr_e('Price', 'hs'); ?>">
                                <?php
                                echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                                ?>
                            </td>

                            <td class="product-quantity col-2" data-title="<?php esc_attr_e('Quantity', 'hs'); ?>">
                                <?php
                                if ($_product->is_sold_individually()) {
                                    $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                                } else {
                                    $product_quantity = woocommerce_quantity_input(array(
                                        'input_name' => "cart[{$cart_item_key}][qty]",
                                        'input_value' => $cart_item['quantity'],
                                        'max_value' => $_product->get_max_purchase_quantity(),
                                        'min_value' => '0',
                                        'product_name' => $_product->get_name(),
                                    ), $_product, false);
                                }

                                echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
                                ?>
                            </td>

                            <td class="product-subtotal col-2" data-title="<?php esc_attr_e('Total', 'hs'); ?>">
                                <?php
                                echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                <?php do_action('woocommerce_after_cart_contents'); ?>
                </tbody>
            </table>
        </div>
        <?php do_action('woocommerce_after_cart_table'); ?>

        <div class="row">

            <fieldset class="col-12 offset-0 col-lg-6 offset-lg-6 mb-4">
                <button type="submit" class="btn btn-outline-primary w-100" name="update_cart"
                        value="<?php esc_attr_e('Update cart', 'hs'); ?>"><?php esc_html_e('Update cart', 'hs'); ?></button>

                <?php do_action('woocommerce_cart_actions'); ?>
                <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
            </fieldset>
            <?php do_action('woocommerce_cart_contents'); ?>

            <!--            <div class="col-12" class="actions">-->

            <?php if (wc_coupons_enabled()) { ?>
                <fieldset class="col-12 offset-0 col-lg-6 offset-lg-6 mb-4">
                    <label for="coupon_code"><?php esc_html_e('Coupon:', 'hs'); ?></label>
                    <input type="text" name="coupon_code" class="form-control w-100" id="coupon_code" value=""
                           placeholder="<?php esc_attr_e('Coupon code', 'hs'); ?>"/>
                    <button type="submit" class="btn btn-outline-primary w-100" name="apply_coupon"
                            value="<?php esc_attr_e('Apply coupon', 'hs'); ?>"><?php esc_attr_e('Apply coupon', 'hs'); ?></button>
                    <?php do_action('woocommerce_cart_coupon'); ?>
                </fieldset>
            <?php } ?>

            <!--            </div>-->

        </div>
    </form>

    <div class="cart-collaterals col-12 offset-0 col-lg-6 offset-lg-6 mb-4">
        <?php
        /**
         * Cart collaterals hook.
         *
         * @hooked woocommerce_cross_sell_display
         * @hooked woocommerce_cart_totals - 10
         */
        do_action('woocommerce_cart_collaterals');
        ?>
    </div>

<?php do_action('woocommerce_after_cart');
