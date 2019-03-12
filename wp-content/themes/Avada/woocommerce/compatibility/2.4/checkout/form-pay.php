<?php
/**
 * Pay for order form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version    2.4.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


?>
<div class="woocommerce-content-box full-width avada-checkout checkout">
	<h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h3>

	<form id="order_review" class method="post">

		<table class="shop_table">
			<thead>
				<tr>
					<th class="product-name"><?php _e( 'Product', 'woocommerce' ); ?></th>
					<th class="product-total"><?php _e( 'Totals', 'woocommerce' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				if ( sizeof( $order->get_items() ) > 0 ) :
					foreach ( $order->get_items() as $item ) :
						$product = get_product( $item['product_id'] );
						$thumbnail = $product->get_image();
				?>
						<tr>
							<td class="product-name">
								<?php // Avada edit ?>
								<span class="product-thumbnail">
									<?php

										if ( ! $product->is_visible() ) {
											echo $thumbnail;
										} else {
											printf( '<a href="%s">%s</a>', $product->get_permalink(), $thumbnail );
										}
									?>
								</span>
								<div class="product-info">
									<?php
										echo $item['name'];
										printf( '<strong class="product-quantity">%s</strong>', $item['qty'] );
									?>
								</div>
							</td>
							<td class="product-total">
								<?php echo $order->get_formatted_line_subtotal( $item ); ?>
							</td>
						</tr>
					<?php
					endforeach;
				endif;
				?>
			</tbody>
			<tfoot>
			<?php
				if ( $totals = $order->get_order_item_totals() ) {
					$last_total = count( $totals ) - 1;
					$i = 0;
					foreach ( $totals as $total ) :
						if ( $i == $last_total ) {
							echo '<tr class="order-total">';
						} else {
							echo '<tr>';
						}
						?>
							<th><?php echo $total['label']; ?></th>
							<td class="product-total">
								<?php echo $total['value']; ?>
							</td>
						</tr>
						<?php
						$i++;
					endforeach;
				}
			?>
			</tfoot>
		</table>

		<div id="payment" class="woocommerce-checkout-payment">
			<?php if ( $order->needs_payment() ) : ?>
			<ul class="payment_methods methods">
				<?php
					if ( $available_gateways = WC()->payment_gateways->get_available_payment_gateways() ) {
						// Chosen Method
						if ( sizeof( $available_gateways ) ) {
							current( $available_gateways )->set_current();
						}
						
						foreach ( $available_gateways as $gateway ) {
							?>
							<li class="payment_method_<?php echo $gateway->id; ?>">
								<input id="payment_method_<?php echo $gateway->id; ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
								<label for="payment_method_<?php echo $gateway->id; ?>"><?php echo $gateway->get_title(); ?> <?php echo $gateway->get_icon(); ?></label>
								<?php
									if ( $gateway->has_fields() || $gateway->get_description() ) {
										echo '<div class="payment_box payment_method_' . $gateway->id . '" style="display:none;">';
										$gateway->payment_fields();
										echo '</div>';
									}
								?>
							</li>
							<?php
						}
					} else {

						echo '<p>' . __( 'Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) . '</p>';

					}
				?>
			</ul>
			<?php endif; ?>

			<div class="form-row">
				<?php wp_nonce_field( 'woocommerce-pay' ); ?>
				<?php
					$pay_order_button_text = apply_filters( 'woocommerce_pay_order_button_text', __( 'Pay for order', 'woocommerce' ) );

					echo apply_filters( 'woocommerce_pay_order_button_html', '<input type="submit" class="button alt" id="place_order" value="' . esc_attr( $pay_order_button_text ) . '" data-value="' . esc_attr( $pay_order_button_text ) . '" />' );
				?>
				<input type="hidden" name="woocommerce_pay" value="1" />
				<div class="clear"></div>
			</div>

		</div>

	</form>
</div>
