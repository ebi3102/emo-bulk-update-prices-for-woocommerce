<?php

namespace EmoWooPriceUpdate\Repository;
use EmoWooPriceUpdate\Repository\EWPU_DB;

class EWPU_DB_Get_All_Products_ID extends EWPU_DB
{
	public function __construct() {
		parent::__construct();
		$this->query = "SELECT ID FROM {$this->db_class->posts} WHERE post_type = 'product' AND post_status = 'publish' ORDER BY post_modified DESC;";
	}
}