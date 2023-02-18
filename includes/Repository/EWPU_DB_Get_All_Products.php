<?php

namespace EmoWooPriceUpdate\Repository;
use EmoWooPriceUpdate\Repository\EWPU_DB;

class EWPU_DB_Get_All_Products extends EWPU_DB
{
	public function __construct($term_id) {
		parent::__construct();
		$this->query = "SELECT ID, post_title FROM {$this->db_class->posts} WHERE post_type = 'product' AND post_status = 'publish' ORDER BY post_modified DESC;";
	}
}