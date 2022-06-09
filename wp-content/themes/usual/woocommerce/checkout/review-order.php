<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */
?>
<div class="shop_table woocommerce-checkout-review-order-table">
    <div class="checkout-items">
		<?php
		do_action( 'woocommerce_review_order_before_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product          = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
			$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'cart-thumb' ), $cart_item, $cart_item_key );
			$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
			$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				?>

                <div class="checkout-product">
                    <div class="product-image">
                        <a href="<?= $product_permalink ?>">
							<?= $thumbnail ?>
                        </a>
                    </div>
                    <div class="product-sidebar">
                        <div class="product-name"><a href="<?= $product_permalink ?>">
								<?= $product_name ?></a></div>
                        <div class="product-price"><?= $product_price ?></div>

                        <div class="product-info">
							<?php if ( $_product->get_attribute( 'pa_colour' ) ) : ?>
                                <div class="product-color"><?php esc_html_e( 'Colour', 'usual' ); ?>:
                                    <span><?= $_product->get_attribute( 'pa_colour' ); ?></span></div>
							<?php endif; ?>
							<?php if ( $_product->get_attribute( 'pa_size' ) ) : ?>
                                <div class="product-size"><?php esc_html_e( 'Size', 'usual' ); ?>:
                                    <span><?= $_product->get_attribute( 'pa_size' ); ?></span></div>
							<?php endif; ?>
                        </div>
                    </div>
                </div>
				<?php
			}
		}

		do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
    </div>

    <div class="checkout-finish">
		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; ?>
        <div class="order-finishing">
            <h4><?php esc_html_e( 'Total', 'woocommerce' ); ?>:</h4>
            <strong><?= WC()->cart->get_total() ?></strong>
        </div>
    </div>
</div>