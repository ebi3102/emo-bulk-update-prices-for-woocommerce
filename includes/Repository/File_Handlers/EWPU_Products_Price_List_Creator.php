<?php

namespace EMO_BUPW\Repository\File_Handlers;

use EMO_BUPW\Repository\WOO_Product\EWPU_Product;
use EMO_BUPW\Repository\WOO_Product\EWPU_Product_Interface;
use EMO_BUPW\Repository\WOO_Product\EWPU_Variation;

class EWPU_Products_Price_List_Creator {

	private $file;

	public function __construct(EMO_BUPW_Write_File_Interface $file){
		$this->file = $file;
	}

	public function setHeader(array $headers)
	{
		$this->file->writeToFile(['content' =>$headers]);
	}

	private function productInit(EWPU_Product_Interface $productObj)
    {
        return $productObj;
    }



	/**
	 * @inheritDoc
	 */
	public function init( array $productIds )
	{
		foreach( $productIds as $productId){
			$product = $this->productInit(new EWPU_Product($productId));
			if($product->get_product_type() == 'variable'){
			    $variationsId = $product->get_product_children();
			    if($variationsId){
			        foreach($variationsId as $variationId){
			            $variation = $this->productInit(new EWPU_Variation($variationId));
                        $data = array(
                            $variationId,
                            $variation->get_product_sku(),
                            $variation->get_product_name(),
                            $variation->get_product_regular_price(),
                            $variation->get_product_sale_price(),
                            $variation->get_product_type()
                        );
                        $this->file->writeToFile(array('content'=>$data));
                    }
                }
			}else{
                $data = array(
                    $productId,
                    $product->get_product_sku(),
                    $product->get_product_name(),
                    $product->get_product_regular_price(),
                    $product->get_product_sale_price(),
                    $product->get_product_type()
                );
                $this->file->writeToFile(array('content'=>$data));
            }
		}
	}
}