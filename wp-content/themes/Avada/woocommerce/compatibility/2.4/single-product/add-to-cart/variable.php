<?php
	/**
	 * Variable product add to cart
	 *
	 * @author  WooThemes
	 * @package WooCommerce/Templates
	 * @version 2.4.0
	 */
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

global $woocommerce, $product, $post;

$attribute_keys = array_keys( $attributes );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->id ); ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>
	
	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>
	<?php else : ?>
		<table class="variations" cellspacing="0">
			<tbody>
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
					<tr>
						<td class="label"><label for="<?php echo sanitize_title( $attribute_name ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?></label></td>
						<td class="value">
							<?php
								$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) : $product->get_variation_default_attribute( $attribute_name );
								wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected ) );
							?>
						</td>
					</tr>
		        <?php endforeach;?>
				  <?php //Avada edit ?>
				  <tr>
					<td class="label"></td>
					<td class="value">
						<div class="single_variation_price_reset">
						<div class="single_variation_wrap" style="display:none;">
							<div class="single_variation"></div>
						</div>
						<?php echo end( $attribute_keys ) === $attribute_name ? '<a class="reset_variations" href="#">' . __( 'Clear selection', 'woocommerce' ) . '</a>' : ''; ?>
						</div>
					</td>
				</tr>
			</tbody>
		</table>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
		<?php //Avada edit ?>


		<div class="single_variation_wrap" style="display:none;">
			<?php
			/**
			 * woocommerce_before_single_variation Hook
			 */
			do_action( 'woocommerce_before_single_variation' ); ?>
			
			<div class="variations_button">
				<?php woocommerce_quantity_input( array( 'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 ) ); ?>
				<button type="submit" class="single_add_to_cart_button fusion-button button-default button-small alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
				<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->id ); ?>" />
				<input type="hidden" name="product_id" value="<?php echo absint( $product->id ); ?>" />
				<input type="hidden" name="variation_id" class="variation_id" value="" />
			</div>			

			<?php 
			/**
			 * woocommerce_after_single_variation Hook
			 */
			do_action( 'woocommerce_after_single_variation' ); ?>
		</div>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<?php endif; ?>

</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' );

// Omit closing PHP tag to avoid "Headers already sent" issues.