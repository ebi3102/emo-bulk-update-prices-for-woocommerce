<?php

namespace EmoWooPriceUpdate\Repository;
use EmoWooPriceUpdate\Repository\EWPU_DB;

class EWPU_DB_Get_All_Products extends EWPU_DB
{
	public function __construct($term_id) {
		$this->query = "SELECT object_id FROM $this->db_class->term_relationships WHERE term_taxonomy_id = $term_id";
	}

}