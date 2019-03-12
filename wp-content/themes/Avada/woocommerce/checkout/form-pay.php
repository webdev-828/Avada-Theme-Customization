<?php
/**
 * Pay for order form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-pay.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author   WooThemes
 * @package  WooCommerce/Templates
 * @version  2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="woocommerce-content-box full-width avada-checkout checkout">
	<h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h3>

	<form id="order_review" method="post">

		<table class="shop_table">
			<thead>
				<tr>
					<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
					<th class="product-total"><?php _e( 'Totals', 'woocommerce' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if ( sizeof( $order->get_items() ) > 0 ) : ?>
					<?php foreach ( $order->get_items() as $item ) : ?>
						<?php $product   = get_product( $item['product_id'] ); ?>
						<?php $thumbnail = $product->get_image(); ?>
						<tr>
							<td class="product-name">
								<span class="product-thumbnail">
									<?php if ( ! $product->is_visible() ) : ?>
										<?php echo $thumbnail; ?>
									<?php else : ?>
										<?php echo '<a href="' . $product->get_permalink() . '">' . $thumbnail . '</a>'; ?>
									<?php endif; ?>
								</span>
								<div class="product-info">
									<?php echo esc_html( $item['name'] ); ?>
									<strong class="product-quantity"><?php echo esc_html( $item['qty'] ); ?></strong>
								</div>
							</td>
							<td class="product-total"><?php echo $order->get_formatted_line_subtotal( $item ); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
			<tfoot>
				<?php if ( $totals = $order->get_order_item_totals() ) : ?>
					<?php $last_total = count( $totals ) - 1; ?>
					<?php $i = 0; ?>
					<?php foreach ( $totals as $total ) : ?>
						<?php if ( $i == $last_total ) : ?>
							<tr class="order-total">
						<?php else : ?>
							<tr>
						<?php endif; ?>
							<th scope="row" colspan="2"><?php echo $total['label']; ?></th>
							<td class="product-total"><?php echo $total['value']; ?></td>
						</tr>
						<?php $i++; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</tfoot>
		</table>

		<div id="payment" class="woocommerce-checkout-payment">
			<?php if ( $order->needs_payment() ) : ?>
				<ul class="wc_payment_methods payment_methods methods">
					<?php
						if ( ! empty( $available_gateways ) ) {
							foreach ( $available_gateways as $gateway ) {
								wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
							}
						} else {
							echo '<li>' . apply_filters( 'woocommerce_no_available_payment_methods_message', __( 'Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) ) . '</li>';
						}
					?>
				</ul>
			<?php endif; ?>
			<div class="form-row">
				<input type="hidden" name="woocommerce_pay" value="1" />

				<?php echo apply_filters( 'woocommerce_pay_order_button_html', '<input type="submit" class="button alt" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '" />' ); ?>

				<?php wc_get_template( 'checkout/terms.php' ); ?>

				<?php wp_nonce_field( 'woocommerce-pay' ); ?>
				<div class="clear"></div>
			</div>
		</div>
	</form>
</div>
