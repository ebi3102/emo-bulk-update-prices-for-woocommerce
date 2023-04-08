<?php

namespace EMO_BUPW\Repository;
use EMO_BUPW\Repository\EWPU_DB;

class EWPU_DB_Get_Related_Object extends EWPU_DB
{
	public function __construct($term_id) {
		parent::__construct();
		$this->query = "SELECT object_id FROM {$this->db_class->term_relationships} WHERE term_taxonomy_id = {$term_id}";
	}
}