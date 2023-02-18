<?php

namespace EmoWooPriceUpdate\Repository;

class EWPU_DB {
	protected $db_class;
	protected $query;


	protected function  __construct()
	{
		global $wpdb;
		$this->db_class = $wpdb;
	}

	public function results()
	{
		echo "<pre>";
		var_dump($this->db_class);
		echo "</pre>";
		$products = $this->db_class->get_results($this->query);
		echo "<pre>";
		var_dump($products);
		echo "</pre>";
		return $products;
	}

	private function do_prepare()
	{
		return $this->db_class->prepare($this->query[0], $this->query[1]);
	}
}