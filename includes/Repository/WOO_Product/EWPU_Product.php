<?php
namespace EmoWooPriceUpdate\Repository\WOO_Product;

class EWPU_Product {
	private $product;

	public function __construct($id) {
		$this->product = wc_get_product($id);
	}


}