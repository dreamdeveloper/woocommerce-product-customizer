(function($) {
	if ( ! wp || ! wp.hooks ) return;
	var scrollStartPost;
	wp.hooks.addAction( 'PC.fe.start', 'MKL/PC/Themes/H', function( view ) {
		// duplicate the form to have a different one on mobile or desktop views
		var clone = view.footer.form.$el.clone().appendTo( view.toolbar.$el );
		view.footer.form_2 = new PC.fe.views.form( { el: clone } );

		view.$el.on( 'click', '.mkl-pc-show-form', function(e) {
			view.$el.toggleClass( 'mobile-show-form' );
		} );

		$( '.pc_configurator_form .configurator-add-to-cart' ).append( $( '.mkl_pc_toolbar .pc-total-price' ) );
		
		if ( view.$( '.pc_configurator_form input.qty' ).length ) {
			view.$( '.pc_configurator_form' ).addClass( 'has-qty' );
		}

		view.$( '.layers' ).wrap( '<div class="layers-wrapper"></div>' );

	}, 20 ); 


	wp.hooks.addAction( 'PC.fe.open', 'MKL/PC/Themes/H', function( view ) {
		view.$el.removeClass( 'mobile-show-form' );
		view.$('.choices-list').each(function ( index, element ) {
			// new SimpleBar( element, {
			// 	autoHide: false
			// } );
		});
		setTimeout( resize_layer_choices, 200 );
	} );

	// wp.hooks.addFilter( 'PC.fe.choices.where', 'MKL/PC/Themes/H', function( where ) {
	// 	return 'in';
	// } );

	wp.hooks.addAction( 'PC.fe.layer.activate', 'MKL/PC/Themes/H', function( view ) {
		if ( PC.fe.inline ) {
			view.$el.find( '.layer_choices' ).show();
			resize_layer_choices();
			$(document).scrollTop(scrollStartPost);
		} else {
			view.$el.find( '.layer_choices' ).delay(40).slideDown(200);
		}
			
	} );
	wp.hooks.addAction( 'PC.fe.layer.deactivate', 'MKL/PC/Themes/H', function( view ) {
		if ( PC.fe.inline ) {
			scrollStartPost = $(document).scrollTop();
			view.$el.find( '.layer_choices' ).hide();
		} else {
			view.$el.find( '.layer_choices' ).slideUp(200);
		}
	} );

	// Conditional logic: do not show / hide choices list visibility
	wp.hooks.addFilter( 'mkl_pc_conditionals.toggle_choices', 'MKL/PC/Themes/H', function( where ) {
		return false;
	} );

	var resize_layer_choices = function() {
		// console.log('OW',  $( '.mkl_pc.opened .mkl_pc_container' ).outerWidth() );
		$( '.layer_choices' ).css( 'width', $( '.mkl_pc.opened .mkl_pc_container' ).outerWidth() );
		$( '.choices-list > ul' ).each( function( ind, el ) {
			console.log($( el ).find( '.choice-item:visible' ).length);
			$( el ).css( 'width', 220 * $( el ).find( '.choice-item:visible' ).length );
		} );

		$( '.mkl_pc_toolbar' ).css( '--cart-form-width', $( '.mkl_pc_toolbar .form.form-cart' ).outerWidth() + 'px' );
		$( '.layers' ).css( 'width', 190 * $( '.layers > li' ).length );

	}

	$( window ).on( 'resize', function() {
		resize_layer_choices();
	} );
	

})( jQuery );