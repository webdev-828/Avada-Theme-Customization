jQuery(document).ready(function(e) {
   	// If clicked register button, process ajax request to submit data
	jQuery(".avada-register").click(function(e){
		e.preventDefault();
		var form = jQuery("#avada_product_registration");
		var loader = jQuery(".avada-loader");
		var data = form.serialize();
		loader.show();
		jQuery.ajax({
			url: ajaxurl,
			data: data,
			dataType: "HTML",
			type:"POST",
			success: function(result){
				if(result == "Updated"){
					var html = '<p class="about-description"><span class="dashicons dashicons-yes avada-icon-key"></span>Registration Complete! Thank you for registering your purchase, you can now receive automatic updates, theme support and future goodies.</p>';
					jQuery(".registration-form-container").html(html);

					jQuery( '#wp-admin-bar-product-registration' ).hide();
				} else if(result == "Empty") {
					jQuery(".registration-notice-2").attr("style","display: block !important");
				} else if(result == "Error") {
					jQuery(".registration-notice-3").attr("style","display: block !important");
				}
				loader.hide();
			}
		});
	});
});