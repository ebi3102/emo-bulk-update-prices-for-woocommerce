<?php

namespace EMO_BUPW\Repository\WOO_Product;
if(! interface_exists('EMO_BUPW_Product_Interface')) {
	interface EMO_BUPW_Product_Interface {
		public function get_product_type();

		public function get_product_sku();

		public function get_product_name();

		public function get_product_regular_price();

		public function get_product_sale_price();
	}
}