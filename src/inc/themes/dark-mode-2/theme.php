<?php
if ( ! defined( 'ABSPATH' ) ) die();

function mkl_pc_dark_theme_scripts() {
	// $data = "
	// ";
	// wp_add_inline_script( 'mkl_pc/js/views/configurator', $data, 'before' );
	wp_enqueue_script( 'mkl_dark_theme2', plugin_dir_url( __FILE__ ) . 'darkmode2.js', [ 'flexslider' ], MKL_PC_VERSION, true );
}
add_action( 'mkl_pc_scripts_product_page_before', 'mkl_pc_dark_theme_scripts', 20 );

add_filter( 'mkl_pc_bg_image', function( $bg ) {
	return '';
}, 30 );

add_filter( 'mkl_pc/js/product_configurator/dependencies', function( $deps ) {
	$deps[] = 'mkl_dark_theme2';
	return $deps;
}, 30 );

add_action( 'mkl_pc_frontend_templates_after', function() { ?>
	<script type="text/html" id="tmpl-mkl-pc-dm2-nav">
		<# if ( data.current ) { #>
			<button class="mkl-pc-prev"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M14 5l-5 5 5 5-1 2-7-7 7-7z"/></g></svg></button>
		<# } #>
		<# if ( data.is_summary ) { #>
			<div class="summary-add-to-cart">
				<button type="button" class="<?php echo $this->button_class ?> mkl-pc-add-to-cart--trigger">
					<?php echo mkl_pc( 'frontend' )->product->get_cart_icon() ?>
					<span><?php echo apply_filters( 'mkl_pc/add_to_cart_button/label', __( 'Add to cart', 'woocommerce' ) ); ?></span>
				</button>
			</div>
		<# } else { #>
			<button class="mkl-pc-next">
				{{data.current}} - {{data.next_item_name}} 
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M6 15l5-5-5-5 1-2 7 7-7 7z"/></g></svg>
			</button>
		<# } #>
	</script>

	<script type="text/html" id="tmpl-mkl-pc-dm2-summary">
		<h3><?php _e( 'Summary', 'product-configurator-for-woocommerce' ); ?></h3>
		<# _.each( data.choices, function( choice ) { #>
			<# if ( choice.is_choice ) { #><p>{{choice.layer_name}}: {{choice.name}}</p><# } #>
		<# } ); #>
	</script>

	<script type="text/html" id="tmpl-mkl-pc-dm2-summary-layer">
		<h4>{{data.name}}</h4>
		<# if ( ! data.choices.length ) { #>
			<p><?php _e( 'Nothing selected', 'product-configurator-for-woocommerce' ); ?></p>
		<# 
			} else { 
				_.each( data.choices, function( a ) {
					console.log('choice > ', a);
		#>
			<p>{{a.get( 'name' )}}</p>
		<# 
				} );
			} 
		#>
	</script>


<?php });