<?php

class Avada_Patcher_Admin_Screen {

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct() {

		// Call register settings function
		add_action( 'admin_init', array( $this, 'settings' ) );
		// Add the patcher to the support screen
		add_action( 'avada/admin_pages/support/after_list', array( $this, 'form' ) );

	}

	/**
	 * Register the settings.
	 *
	 * @access public
	 * @return void
	 */
	public function settings() {

		// Get the patches
		$patches = Avada_Patcher_Client::get_patches();
		if ( ! empty( $patches ) ) {

			// Register settings for the patch contents.
			foreach( $patches as $key => $value ) {
				register_setting( 'avada_patcher_' . $key, 'avada_patch_contents_' . $key );
			}
		}
	}

	/**
	 * The page contents.
	 *
	 * @access public
	 * @return void Directly echoes the form.
	 */
	public function form() {

		// Get the patches.
		$patches = Avada_Patcher_Client::get_patches();
		// Get the fusion-core plugin version.
		$fusion_core_version = ( class_exists( 'FusionCore_Plugin' ) ) ? FusionCore_Plugin::VERSION : false;
		// Get the avada theme version.
		$avada_version = Avada::get_theme_version();

		// Determine if there are available patches, and build an array of them.
		$available_patches = array();
		$context = array( 'avada' => false, 'fusion-core' => false );
		foreach ( $patches as $patch_id => $patch_args ) {
			if ( ! isset( $patch_args['patch'] ) ) {
				continue;
			}
			foreach ( $patch_args['patch'] as $key => $unique_patch_args ) {
				if ( 'avada' == $unique_patch_args['context'] && $avada_version == $unique_patch_args['version'] ) {
					$available_patches[] = $patch_id;
					$context['avada'] = true;
				} elseif ( 'fusion-core' == $unique_patch_args['context'] && $fusion_core_version == $unique_patch_args['version'] ) {
					$available_patches[] = $patch_id;
					$context['fusion-core'] = true;
				}
			}
		}
		// Make sure we have a unique array.
		$available_patches = array_unique( $available_patches );
		// Sort the array by value and re-index the keys.
		sort( $available_patches );

		// Get an array of the already applied patches.
		$applied_patches = get_site_option( 'avada_applied_patches', array() );
		?>
		<div class="avada-important-notice avada-auto-patcher">

			<p class="avada-auto-patcher description">
				<?php if ( empty( $available_patches ) ) : ?>
					<?php printf( esc_html__( 'Avada Patcher: There Are No Available Patches For Avada v%s', 'Avada' ), $avada_version ); ?>
				<?php else : ?>
					<?php printf( esc_html__( 'Avada Patcher: The following patches are available for Avada %s', 'Avada' ), $avada_version ); ?>
				<?php endif; ?>
				<span class="avada-auto-patcher learn-more"><a href="https://theme-fusion.com/avada-doc/avada-patcher/" target="_blank"><?php esc_attr_e( 'Learn More', 'Avada' ); ?></a></span>
			</p>
			<?php if ( ! empty( $available_patches ) ) : // Only display the table if we have patches to apply ?>
				<table class="avada-patcher-table">
					<tbody>
						<tr class="avada-patcher-headings">
							<th><?php esc_attr_e( 'Patch #', 'Avada' ); ?></th>
							<th><?php esc_attr_e( 'Issue Date', 'Avada' ); ?></th>
							<th><?php esc_attr_e( 'Description', 'Avada' ); ?></th>
							<th><?php esc_attr_e( 'Status', 'Avada' ); ?></th>
							<th></th>
						</tr>
						</tr>
						<?php foreach ( $available_patches as $key => $patch_id ) :

							// Do not allow applying the patch initially.
							// We'll have to check if they can later.
							$can_apply = false;

							// Make sure the patch exists
							if ( ! array_key_exists( $patch_id, $patches ) ) {
								continue;
							}

							// Get the patch arguments.
							$patch_args = $patches[ $patch_id ];

							// Has the patch been applied?
							$patch_applied = ( in_array( $patch_id, $applied_patches ) ) ? true : false;

							// If there is no previous patch, we can apply it.
							if ( ! isset( $available_patches[ $key - 1 ] ) ) {
								$can_apply = true;
							}

							// If the previous patch exists and has already been applied,
							// then we can apply this one.
							if ( isset( $available_patches[ $key - 1 ] ) ) {
								if ( in_array( $available_patches[ $key - 1 ], $applied_patches ) ) {
									$can_apply = true;
								}
							}
							?>

							<tr class="avada-patcher-table-head">
								<td class="patch-id">#<?php echo intval( $patch_id ); ?></td>
								<td class="patch-date"><?php echo $patch_args['date'][0]; ?></td>
								<td class="patch-description"><?php echo $patch_args['description'][0]; ?></td>
								<td class="patch-status">
									<?php if ( $patch_applied ) : ?>
										<span style="color:#4CAF50;" class="dashicons dashicons-yes"></span>
									<?php endif; ?>
								</td>
								<td class="patch-apply">
									<?php if ( $can_apply ) : ?>
										<form method="post" action="options.php">
											<?php settings_fields( 'avada_patcher_' . $patch_id ); ?>
											<?php do_settings_sections( 'avada_patcher_' . $patch_id ); ?>
											<input type="hidden" name="avada_patch_contents_<?php echo $patch_id; ?>" value="<?php echo self::format_patch( $patch_args ); ?>" />
											<?php submit_button( esc_attr__( 'Apply Patch', 'Avada' ) ); ?>
										</form>
									<?php else : ?>
										<span class="button disabled button-small">
											<?php if ( isset( $available_patches[ $key - 1 ] ) ) : ?>
												<?php printf( esc_html__( 'Please apply patch #%s first.', 'Avada' ), $available_patches[ $key - 1 ] ); ?>
											<?php else : ?>
												<?php esc_html_e( 'Patch cannot be currently aplied.', 'Avada' ); ?>
											<?php endif; ?>
										</span>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Format the patch.
	 * We're encoding everything here for security reasons.
	 * We're also going to check the current versions of Avada & Fusion-Core,
	 * and then build the hash for this patch using the files that are needed.
	 */
	private static function format_patch( $patch ) {
		// Get the fusion-core plugin version
		$fusion_core_version = ( class_exists( 'FusionCore_Plugin' ) ) ? FusionCore_Plugin::VERSION : false;
		// Get the avada theme version
		$avada_version = Avada::get_theme_version();

		$patches = array();
		if ( ! isset( $patch['patch'] ) ) {
			return;
		}
		foreach ( $patch['patch'] as $key => $args ) {
			if ( ! isset( $args['context'] ) || ! isset( $args['path'] ) || ! isset( $args['reference'] ) ) {
				continue;
			}
			if ( 'avada' == $args['context'] && $avada_version == $args['version'] ) {
				$patches[ $args['context'] ][ $args['path'] ] = $args['reference'];
			} elseif ( 'fusion-core' == $args['context'] && $fusion_core_version == $args['version'] ) {
				$patches[ $args['context'] ][ $args['path'] ] = $args['reference'];
			}
		}
		return base64_encode( json_encode( $patches ) );
	}

}
