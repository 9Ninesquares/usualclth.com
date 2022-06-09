<?php
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/*
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	function Usual_Shipping_Flat_Rate() {
		if ( ! class_exists( 'Usual_Shipping_Flat_Rate' ) ) {
			class Usual_Shipping_Flat_Rate extends WC_Shipping_Flat_Rate {

				/**
				 * Constructor for your shipping class
				 *
				 * @access public
				 * @return void
				 */
				public function __construct() {
					parent::__construct();
					$this->id                 = 'tutsplus';
					$this->method_title       = __( 'TutsPlus Shipping', 'tutsplus' );
					$this->method_description = __( 'Custom Shipping Method for TutsPlus', 'tutsplus' );

					$this->init();

					$this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';
					$this->title   = isset( $this->settings['title'] ) ? $this->settings['title'] : __( 'TutsPlus Shipping', 'tutsplus' );
				}

				/**
				 * Calculate the shipping costs.
				 *
				 * @param array $package Package of items from cart.
				 */
				public function calculate_shipping( $package = array() ) {
					$rate = array(
						'id'      => $this->get_rate_id(),
						'label'   => $this->title,
						'cost'    => 0,
						'package' => $package,
					);

					$weight  = 0;
					$cost    = 0;
					$country = $package["destination"]["country"];

					foreach ( $package['contents'] as $item_id => $values ) {
						$_product = $values['data'];
						$weight   = $weight + $_product->get_weight() * $values['quantity'];
					}

					$weight = wc_get_weight( $weight, 'kg' );

					var_dump( $weight );

					// Calculate the costs.
					$has_costs = false; // True when a cost is set. False if all costs are blank strings.
					$cost      = $this->get_option( 'cost' );

					if ( '' !== $cost ) {
						$has_costs    = true;
						$rate['cost'] = $this->evaluate_cost(
							$cost,
							array(
								'qty'  => $this->get_package_item_qty( $package ),
								'cost' => $package['contents_cost'],
							)
						);
					}

					// Add shipping class costs.
					$shipping_classes = WC()->shipping()->get_shipping_classes();

					if ( ! empty( $shipping_classes ) ) {
						$found_shipping_classes = $this->find_shipping_classes( $package );
						$highest_class_cost     = 0;

						foreach ( $found_shipping_classes as $shipping_class => $products ) {
							// Also handles BW compatibility when slugs were used instead of ids.
							$shipping_class_term = get_term_by( 'slug', $shipping_class, 'product_shipping_class' );
							$class_cost_string   = $shipping_class_term && $shipping_class_term->term_id ? $this->get_option( 'class_cost_' . $shipping_class_term->term_id, $this->get_option( 'class_cost_' . $shipping_class, '' ) ) : $this->get_option( 'no_class_cost', '' );

							if ( '' === $class_cost_string ) {
								continue;
							}

							$has_costs  = true;
							$class_cost = $this->evaluate_cost(
								$class_cost_string,
								array(
									'qty'  => array_sum( wp_list_pluck( $products, 'quantity' ) ),
									'cost' => array_sum( wp_list_pluck( $products, 'line_total' ) ),
								)
							);

							if ( 'class' === $this->type ) {
								$rate['cost'] += $class_cost;
							} else {
								$highest_class_cost = $class_cost > $highest_class_cost ? $class_cost : $highest_class_cost;
							}
						}

						if ( 'order' === $this->type && $highest_class_cost ) {
							$rate['cost'] += $highest_class_cost;
						}
					}

					if ( $has_costs ) {
						$this->add_rate( $rate );
					}

					/**
					 * Developers can add additional flat rates based on this one via this action since @version 2.4.
					 *
					 * Previously there were (overly complex) options to add additional rates however this was not user.
					 * friendly and goes against what Flat Rate Shipping was originally intended for.
					 */
					do_action( 'woocommerce_' . $this->id . '_shipping_add_rate', $this, $rate );
				}
			}
		}
	}

	add_action( 'woocommerce_shipping_init', 'Usual_Shipping_Flat_Rate' );

	function add_Usual_Shipping_Flat_Rate( $methods ) {
		$methods[] = 'Usual_Shipping_Flat_Rate';

		return $methods;
	}

	add_filter( 'woocommerce_shipping_methods', 'add_Usual_Shipping_Flat_Rate' );

	function tutsplus_validate_order( $posted ) {

		$packages = WC()->shipping->get_packages();

		$chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

		if ( is_array( $chosen_methods ) && in_array( 'tutsplus', $chosen_methods ) ) {

			foreach ( $packages as $i => $package ) {

				if ( $chosen_methods[ $i ] != "tutsplus" ) {

					continue;

				}

				$Usual_Shipping_Flat_Rate = new Usual_Shipping_Flat_Rate();
				$weightLimit              = (int) $Usual_Shipping_Flat_Rate->settings['weight'];
				$weight                   = 0;

				foreach ( $package['contents'] as $item_id => $values ) {
					$_product = $values['data'];
					$weight   = $weight + $_product->get_weight() * $values['quantity'];
				}

				$weight = wc_get_weight( $weight, 'kg' );

				if ( $weight > $weightLimit ) {

					$message = sprintf( __( 'Sorry, %d kg exceeds the maximum weight of %d kg for %s', 'tutsplus' ), $weight, $weightLimit, $Usual_Shipping_Flat_Rate->title );

					$messageType = "error";

					if ( ! wc_has_notice( $message, $messageType ) ) {

						wc_add_notice( $message, $messageType );

					}
				}
			}
		}
	}

	add_action( 'woocommerce_review_order_before_cart_contents', 'tutsplus_validate_order', 10 );
	add_action( 'woocommerce_after_checkout_validation', 'tutsplus_validate_order', 10 );
}