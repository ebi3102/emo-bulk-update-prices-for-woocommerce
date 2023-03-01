<?php

namespace EmoWooPriceUpdate\Repository\WOO_Product;
use EmoWooPriceUpdate\Repository\WOO_Product\EWPU_Product_Interface;
class EWPU_Variation implements EWPU_Product_Interface
{
    private $product;

    public function __construct($id) {
        $this->product = wc_get_product($id);
    }

    public function get_product_type()
    {
        // TODO: Implement get_product_type() method.
    }

    public function get_product_sku()
    {
        // TODO: Implement get_product_sku() method.
    }

    public function get_product_name()
    {
        // TODO: Implement get_product_name() method.
    }

    public function get_product_regular_price()
    {
        // TODO: Implement get_product_regular_price() method.
    }

    public function get_product_sale_price()
    {
        // TODO: Implement get_product_sale_price() method.
    }

    public function get_product_children()
    {
        // TODO: Implement get_product_children() method.
    }
}