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
		return $this->db_class->get_results($this->prepare());
	}

	private function prepare()
	{
		return $this->db_class->prepare($this->query);
	}
}