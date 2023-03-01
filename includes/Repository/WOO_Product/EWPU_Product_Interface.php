<?php

namespace EmoWooPriceUpdate\Repository\WOO_Product;

interface EWPU_Product_Interface
{
    public function get_product_type();

    public function get_product_sku();

    public function get_product_name();

    public function get_product_regular_price();

    public function get_product_sale_price();

    public function get_product_children();

}