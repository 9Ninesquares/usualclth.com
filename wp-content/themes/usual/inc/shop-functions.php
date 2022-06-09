<?php
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

/**
 * Удаляет из форм на странице "Оформление заказа" поля
 *
 * @param array $fields
 *
 * @return array
 */
function remove_unused_fields( $fields ) {
//    var_dump($fields);
	unset( $fields['billing']['billing_company'] );


	$fields['billing']['billing_first_name']['placeholder'] = __( 'Name', 'usual' ) . '*';
	$fields['billing']['billing_first_name']['required']    = true;
	$fields['billing']['billing_last_name']['placeholder']  = __( 'Surname', 'usual' ) . '*';
	$fields['billing']['billing_last_name']['required']     = true;
	$fields['billing']['billing_phone']['placeholder']      = __( 'Mobile Number', 'usual' ) . '*';
	$fields['billing']['billing_phone']['required']         = true;
	$fields['billing']['billing_email']['placeholder']      = __( 'Email', 'usual' ) . '*';

	foreach ( $fields as $category => $value ) {
		// loop by fields
		foreach ( $fields[ $category ] as $field => $property ) {
			// remove label property
			unset( $fields[ $category ][ $field ]['label'] );
		}
	}

//	$fields['billing']['social_to_answer'] = [
//		'type' => 'select',
//		'required' => true,
//		'options' => array(
//			'telegram' => 'Telegram',
//			'whatsapp' => 'Whatsapp',
//			'viber' => 'Viber',
//		),
//	];

	return $fields;
}

add_filter( 'woocommerce_checkout_fields', 'remove_unused_fields' );

add_filter( 'woocommerce_breadcrumb_defaults', function () {
	return array(
		'delimiter'   => '&nbsp;&#47;&nbsp;',
		'wrap_before' => '<nav class="woocommerce-breadcrumb">',
		'wrap_after'  => '</nav>',
		'before'      => '',
		'after'       => '',
		'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
	);
} );

add_filter( 'woocommerce_enqueue_styles', function ( $arr ) {
//	if ( !is_checkout() ) {
	return [];

//	}

	return $arr;
} );

add_filter( 'woocommerce_loop_add_to_cart_args', function ( $arr ) {
	$arr['class'] = preg_replace( '/^button/', 'c-button', $arr['class'] );

	return $arr;
} );

add_filter( 'woocommerce_show_page_title', '__return_false' );

remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );

add_filter( 'woocommerce_sale_flash', 'my_custom_sale_flash', 10, 3 );
function my_custom_sale_flash( $text, $post, $_product ) {
	return '<span class="onsale">sale</span>';
}

// Minimum CSS to remove +/- default buttons on input field type number
add_action( 'wp_head', 'custom_quantity_fields_css' );
function custom_quantity_fields_css() {
	?>
    <style>
        .quantity input::-webkit-outer-spin-button,
        .quantity input::-webkit-inner-spin-button {
            display: none;
            margin: 0;
        }

        .quantity input.qty {
            appearance: textfield;
            -webkit-appearance: none;
            -moz-appearance: textfield;
        }
    </style>
	<?php
}


add_action( 'wp_footer', 'custom_quantity_fields_script' );
function custom_quantity_fields_script() {
	?>
    <script type='text/javascript'>
        jQuery(function ($) {
            if (!String.prototype.getDecimals) {
                String.prototype.getDecimals = function () {
                    var num = this,
                        match = ('' + num).match(/(?:\.(\d+))?(?:[eE]([+-]?\d+))?$/);
                    if (!match) {
                        return 0;
                    }
                    return Math.max(0, (match[1] ? match[1].length : 0) - (match[2] ? +match[2] : 0));
                }
            }
            // Quantity "plus" and "minus" buttons
            $(document.body).on('click', '.plus, .minus', function () {
                var $qty = $(this).closest('.quantity').find('.qty'),
                    currentVal = parseFloat($qty.val()),
                    max = parseFloat($qty.attr('max')),
                    min = parseFloat($qty.attr('min')),
                    step = $qty.attr('step');

                // Format values
                if (!currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
                if (max === '' || max === 'NaN') max = '';
                if (min === '' || min === 'NaN') min = 0;
                if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') step = 1;

                // Change the value
                if ($(this).is('.plus')) {
                    if (max && (currentVal >= max)) {
                        $qty.val(max);
                    } else {
                        $qty.val((currentVal + parseFloat(step)).toFixed(step.getDecimals()));
                    }
                } else {
                    if (min && (currentVal <= min)) {
                        $qty.val(min);
                    } else if (currentVal > 0) {
                        $qty.val((currentVal - parseFloat(step)).toFixed(step.getDecimals()));
                    }
                }

                // Trigger change event
                $qty.trigger('change');
            });
        });
    </script>
	<?php
}

remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );
add_action( 'woocommerce_cart_is_empty', 'usual_empty_cart_message', 10 );

/**
 * Show notice if cart is empty.
 *
 * @since 3.1.0
 */
function usual_empty_cart_message() {
	echo '<div class="container"><p class="cart-empty woocommerce-info ">' . wp_kses_post( apply_filters( 'wc_empty_cart_message', __( 'Your cart is currently empty.', 'woocommerce' ) ) ) . '</p></div>';
}

function usual_tg( $message ) {
	$token   = '5351367466:AAGsmJrDuTKUKNb_nJJjCr-Ao52CvihK3y8';
	$chatids = [ 454255748, 274497421, 398757371 ];

//	if ( $_SERVER['HTTP_HOST'] == 'sve.shypelyk.com' ) {
//		return;
//	}
	if ( stristr( $message, 'http' ) === false ) {
		foreach ( $chatids as $chatid ) {
			wp_remote_get( "https://api.telegram.org/bot$token/sendMessage?chat_id=$chatid&text=$message" );
		}
	}
}

function send_msg_to_tg_new_order( $order_id ) {
	$order   = wc_get_order( $order_id );
	$message = 'Нове замовлення на сайті ' . $_SERVER['HTTP_HOST'];
	$message .= "\nId замовлення: " . $order->get_order_number();
	$message .= "\nІм'я замовника: " . $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
	$message .= "\nСума: " . $order->get_total() . ' грн';
	$message .= "\nТелефон: " . $order->get_billing_phone();
	$message .= "\nПошта: " . $order->get_billing_email();
	$message .= "\nМісто: " . $order->get_billing_city();
	$message .= "\nАдреса: " . $order->get_billing_address_1();
	$message .= "\nСпосіб оплати: " . $order->get_payment_method_title();
//	$message .= "\nКуди відповідати: " . get_post_meta( $order_id, 'social_to_answer', true );

	$message .= "\nЩо замовили: ";

	foreach ( $order->get_items() as $item ) {
		$product_name = $item->get_name();
		$quantity     = $item->get_quantity();
		$message      .= "\n   " . $product_name . ' ' . $quantity;
	}
	usual_tg( $message );

	$order = wc_get_order( $order_id );
	$lang  = pll_get_post_language( $order_id );

//	if ( $lang == 'uk' || $order->get_payment_method() !== 'wayforpay' ) {
	if ( $order->get_payment_method() !== 'wayforpay' ) {
		return;
	}

//	if ($_SERVER['HTTP_HOST'] == 'sve.shypelyk.com') return;

	WC()->mailer()->emails['WC_Email_Customer_Processing_Order']->trigger( $order_id );
	WC()->mailer()->emails['WC_Email_New_Order']->trigger( $order_id );
}

//add_action( 'woocommerce_payment_complete', 'send_msg_to_tg_new_order', 1 );
//add_action( 'woocommerce_checkout_order_processed', 'send_msg_to_tg_new_order', 1 );

add_filter( 'woocommerce_package_rates', function ( $rates, $package ) {
	$country = $package["destination"]["country"];

	if ( in_array( $country, [ 'UK', 'BY', 'PL' ] ) || ! $country ) {
		return $rates;
	}

	$weight = 0;

	// Loop over $cart items
	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		$_product  = $cart_item['data'];
		$quantity  = $cart_item['quantity'];
		$pr_weight = $_product->get_weight();
		if ( ! $pr_weight || ! is_numeric( $pr_weight * $quantity ) ) {
			$weight += 0;
		} else {
			$weight += $pr_weight * $quantity;
		}
	}

	$weight = wc_get_weight( $weight, 'kg' );

	foreach ( $rates as $rate ) {
		if ( $rate->id == 'flat_rate:7' && $weight >= 2 ) {
			$rate->cost = 600;
		}
	}

	return $rates;
}, 10, 2 );

/**
 * Change a currency symbol
 */
add_filter( 'woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2 );

function change_existing_currency_symbol( $currency_symbol, $currency ) {
	switch ( $currency ) {
		case 'UAH':
			$currency_symbol = 'UAH';
			break;
	}

	return $currency_symbol;
}

add_filter( 'woocommerce_available_payment_gateways', 'change_default_chosen' );
function change_default_chosen( $gateways ) {
	$gateways['cod']->chosen       = true;
	$gateways['wayforpay']->chosen = null;
//    if($gateways['ry_ecpay_atm']) {
//		$gateways['ry_ecpay_atm']->order_button_text = 'new label';
//	}
	return $gateways;
}

add_action( 'woocommerce_order_payment_status_changed', 'send_msg_to_tg_new_order', 10, 1 );

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 75 );

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display', 20 );

add_action('woocommerce_after_add_to_cart_quantity', function () {
   echo '<a href="#" class="size-guide-opener">'.__('Size guide', 'usual').'</a>';
}, 19);

add_action('woocommerce_before_checkout_process', function () {
	if (WC()->cart->is_empty()) echo '<div class="container">';
});

add_action('woocommerce_checkout_process', function () {
   if (WC()->cart->is_empty()) echo '</div>';
});