<?php

class Avada_Patcher_Apply_Patch {

	/**
	 * The patch contents.
	 *
	 * @access public
	 * @var bool|array
	 */
	public $setting = false;

	/**
	 * Constructor.
	 *
	 * @access public
	 */
	public function __construct() {

		// Get patches.
		$patches = Avada_Patcher_Client::get_patches();

		// Loop our patches.
		foreach ( $patches as $key => $args ) {

			// Set the $setting property to false.
			// Then run $this->get_setting( $key ) to update the value.
			$this->setting = false;
			$this->get_setting( $key );

			// If $setting property is not false apply the patch.
			if ( false !== $this->setting && ! empty( $this->setting ) ) {
				$this->apply_patch( $key );
			}
		}
	}

	/**
	 * Get the setting from the database.
	 * If the setting exists, decode it and set the class's $setting property to an array.
	 *
	 * @access public
	 * @param  int $key The patch ID.
	 * @return void
	 */
	public function get_setting( $key ) {

		// Get the patch contents.
		// This is created when the "apply patch" button is pressed.
		$setting = get_option( 'avada_patch_contents_' . $key, false );

		// Check we have a value before proceeding.
		if ( false !== $setting && ! empty( $setting ) ) {

			// Decode and prepare tha patch.
			$setting = (array) json_decode( base64_decode( $setting ) );

			//Set the $setting property of the class to the contents of our patch.
			if ( is_array( $setting ) && ! empty( $setting ) ) {
				$this->setting = $setting;
			}
		}
	}

	/**
	 * Applies the patch.
	 * If everything is alright, return true else false.
	 *
	 * @access public
	 * @param  int $key The patch ID.
	 * @return bool
	 */
	public function apply_patch( $key ) {

		// Check that the $setting property is properly formatted as an array.
		if ( is_array( $this->setting ) ) {

			// Process the patch.
			foreach ( $this->setting as $target => $args ) {
				$args = (array) $args;
				foreach ( $args as $destination => $source ) {
					new Avada_Patcher_Filesystem( $target, $source, $destination );
				}
			}

			// Cleanup.
			$this->remove_setting( $key );
			$this->update_applied_patches( $key );
		}
	}

	/**
	 * Remove the setting from the database.
	 *
	 * @access public
	 * @param  int $key The patch ID.
	 * @return void
	 */
	public function remove_setting( $key ) {
		delete_option( 'avada_patch_contents_' . $key );
	}

	/**
	 * Update the applied patches array in the db.
	 *
	 * @access public
	 * @param  int $key The patch ID.
	 * @return void
	 */
	public function update_applied_patches( $key ) {

		// Get an array of existing patches
		$applied_patches = get_site_option( 'avada_applied_patches', array() );

		// Add the patch key to the array and save.
		if ( ! in_array( $key, $applied_patches ) ) {
			$applied_patches[] = $key;
			$applied_patches   = array_unique( $applied_patches );
			update_site_option( 'avada_applied_patches', $applied_patches );
		}
	}
}
