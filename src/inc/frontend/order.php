<?php
namespace MKL\PC;
/**
 *	
 *	
 * @author   Marc Lacroix
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists('MKL\PC\Frontend_Order') ) {
	class Frontend_Order {
		public function __construct() {
			$this->_hooks();
		}
		private function _hooks() {
			add_action( 'woocommerce_checkout_create_order_line_item', array( $this, 'save_data' ), 20, 4 );
			add_filter( 'woocommerce_admin_order_item_thumbnail', array( $this, 'order_admin_item_thumbnail' ), 30, 3 );
			add_filter( 'woocommerce_order_item_thumbnail', array( $this, 'order_item_thumbnail' ), 30, 2 );
		}

		public function save_data( $item, $cart_item_key, $values, $order ) {
			if( isset( $values['configurator_data'] ) ) {
				$configurator_data = $values['configurator_data'];
				
				if( is_array( $configurator_data ) ) {
					// stores each couple layer name + choice as a order_item_meta, for automatic extraction
					foreach( $configurator_data as $layer ) {
						if( is_object($layer) ) {
							if( $layer->is_choice() ) :
								$choice_meta = apply_filters( 'mkl_pc/order_created/save_layer_meta', $this->set_order_item_meta( $layer, $values['data'] ), $layer, $item, $values );
								$item->add_meta_data( $choice_meta[ 'label' ], $choice_meta['value'], false );
								do_action( 'mkl_pc/order_created/after_save_layer_meta', $layer, $item, $order );
							?>
							<?php
							endif;
						} 
					}

					do_action( 'mkl_pc/order_created/after_saved_data', $item, $order, $configurator_data );
				}
				
				// stores the whole _configurator_data object
				$item->add_meta_data( '_configurator_data', $configurator_data, false );
			}		
		}

		private function set_order_item_meta( $layer, $product ) {
			$meta = array(
				'label' => $layer->get_layer('name'),
				'value' => $layer->get_choice('name'),
			);
			return apply_filters( 'mkl_pc_order_item_meta', $meta, $layer, $product ); 
		}

		/**
		 * Filter the admin order item's image
		 *
		 * @param string $image
		 * @param int    $item_id
		 * @param object $order_item
		 * @return string
		 */
		public function order_admin_item_thumbnail( $image, $item_id, $order_item ) {
			if ( ! mkl_pc( 'settings' )->get( 'show_image_in_cart' ) ) return $image;
			
			if ( $config_image = $this->_get_order_item_image( $order_item ) ) return $config_image;

			return $image;
		}

		/**
		 * Filter the order email item's image
		 *
		 * @param string $image
		 * @param object $order_item
		 * @return string
		 */
		public function order_item_thumbnail( $image, $order_item ) {
			if ( ! mkl_pc( 'settings' )->get( 'show_image_in_cart' ) ) return $image;
			
			if ( $config_image = $this->_get_order_item_image( $order_item ) ) return $config_image;

			return $image;
		}

		private function _get_order_item_image( $order_item ) {

			if ( ! mkl_pc_is_configurable( $order_item->get_product_id() ) ) return false; 

			$configurator_data = $order_item->get_meta( '_configurator_data' );

			if ( ! $configurator_data ) return false;

			$choices = array(); 
			foreach ($configurator_data as $layer) {
				$choice_images = $layer->get_choice( 'images' );
				if( $choice_images[0]["image"]['id'] ) {
					$choices[] = [ 'image' => $choice_images[0]["image"]['id'] ];
				}
			}

			$configuration = new Configuration( NULL, array( 'product_id' => $order_item['product_id'], 'content' => json_encode( $choices ) ) );
			$size = mkl_pc( 'settings' )->get( 'cart_thumbnail_size', 'woocommerce_thumbnail' );
			$img = $configuration->get_image( $size );

			if ( $img ) return $img;

			return false;
		}
	}
}