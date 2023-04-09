<?php

namespace EMO_BUPW\Repository;
use EMO_BUPW\Repository\EMO_BUPW_DB;

class EMOBUPW_DB_Get_All_Products_ID extends EMO_BUPW_DB
{
	public function __construct() {
		parent::__construct();
		$this->query = "SELECT ID FROM {$this->db_class->posts} WHERE post_type = 'product' AND post_status = 'publish' ORDER BY post_modified DESC;";
	}
}