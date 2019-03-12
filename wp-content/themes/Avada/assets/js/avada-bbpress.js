jQuery(window).load(function() {
    jQuery( '.bbp-template-notice' ).each(
        function() {
            if ( jQuery( this ).hasClass( 'info' ) ) {
                jQuery( this ).attr( 'class', 'fusion-alert alert notice alert-dismissable alert-info alert-shadow' );
            } else {
                jQuery( this ).attr(
                    'class', 'fusion-alert alert notice alert-dismissable alert-warning alert-shadow'
                );
            }
            jQuery( this ).children( 'tt' ).contents().unwrap();
            jQuery( this ).children( 'p' ).contents().unwrap();
            jQuery( this ).prepend( '<button class="close toggle-alert" aria-hidden="true" data-dismiss="alert" type="button">&times;</button><span class="alert-icon"><i class="fa fa-lg fa-lg fa-cog"></i></span>' );

            jQuery( this ).children( '.close' ).click(
                function( e ) {
                    e.preventDefault();

                    jQuery( this ).parent().slideUp();
                }
            );
        }
    );

    jQuery( '.bbp-login-form' ).each(
        function() {
            jQuery( this ).children( 'tt' ).contents().unwrap();
        }
    );
});