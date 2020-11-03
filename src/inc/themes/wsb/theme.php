<?php
function mkl_pc_wsb_theme_scripts() {
	$data = "
	(function($) {
		wp.hooks.addAction( 'PC.fe.start', 'MKL/PC/Themes/wsb', function( view ) {
			view.footer.\$el.find( '.form' ).clone().appendTo( view.toolbar.\$el );
			view.\$el.on( 'click', '.mkl-pc-show-form', function(e) {
				view.\$el.toggleClass( 'mobile-show-form' );
			});
		}); 
		wp.hooks.addAction( 'PC.fe.open', 'MKL/PC/Themes/wsb', function( view ) {
			view.\$el.removeClass( 'mobile-show-form' );
		}); 

		wp.hooks.addFilter( 'PC.fe.choices.where', 'MKL/PC/Themes/wsb', function( where ) {
			return 'in';
		} );
		wp.hooks.addAction( 'PC.fe.layer.activate', 'MKL/PC/Themes/wsb', function( view ) {
			console.log(view.\$el.find( '.layer_choices' ));
			view.\$el.find( '.layer_choices' ).slideDown(200);
		} );
		wp.hooks.addAction( 'PC.fe.layer.deactivate', 'MKL/PC/Themes/wsb', function( view ) {
			view.\$el.find( '.layer_choices' ).slideUp(200);
		} );

	})( jQuery );
	";
	wp_add_inline_script( 'mkl_pc/js/views/configurator', $data, 'before' );
}
add_action( 'mkl_pc_scripts_product_page_after', 'mkl_pc_wsb_theme_scripts', 20 );

function mkl_pc_wsb_theme_choice_wrapper_open() {
	echo '<span class="choice-text">';
}
add_action( 'tmpl-pc-configurator-choice-item', 'mkl_pc_wsb_theme_choice_wrapper_open', 6 );

function mkl_pc_wsb_theme_add_mobile_form_button() {
	echo '<button class="mkl-pc-show-form">' . mkl_pc( 'frontend' )->product->get_cart_icon() .'<span class="screen-reader-text">' . __( 'Add to cart', 'woocommerce' ) . '</span></button>';
}
add_action( 'mkl_pc_frontend_configurator_footer_form_before', 'mkl_pc_wsb_theme_add_mobile_form_button', 20 );

function mkl_pc_wsb_theme_choice_wrapper_close() {
	echo '</span>';
}
add_action( 'tmpl-pc-configurator-choice-item', 'mkl_pc_wsb_theme_choice_wrapper_close', 160 );

function mkl_pc_wsb_theme_remove_title() {
	remove_action( 'mkl_pc_frontend_configurator_footer_section_left_inner', 'mkl_pc_frontend_configurator_footer_section_left_inner__product_name', 30 );
}
add_action( 'mkl_pc_frontend_templates_before', 'mkl_pc_wsb_theme_remove_title', 20 );
