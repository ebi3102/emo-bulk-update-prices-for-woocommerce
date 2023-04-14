<?php

namespace EMO_BUPW\Repository\WOO_Product;
use EMO_BUPW\Repository\WOO_Product\EMO_BUPW_Product_Interface;
if (!class_exists('EMO_BUPW_Variation')) {
	class EMO_BUPW_Variation implements EMO_BUPW_Product_Interface {
		private $product;

		public function __construct( $id ) {
			$this->product = wc_get_product_object( 'variation', $id );
		}

		public function get_product_type() {
			return $this->product->get_type();
		}

		public function get_product_sku() {
			return $this->product->get_sku();
		}

		public function get_product_name() {
			return $this->product->get_name();
		}

		public function get_product_regular_price() {
			return $this->product->get_regular_price();
		}

		public function get_product_sale_price() {
			return $this->product->get_sale_price();
		}
	}
}