<?php

namespace EMO_BUPW\Repository;
use EMO_BUPW\Repository\EMO_BUPW_DB;
if (!class_exists('EMO_BUPW_DB_Get_Related_Object')) {
	class EMO_BUPW_DB_Get_Related_Object extends EMO_BUPW_DB {
		public function __construct( $term_id ) {
			parent::__construct();
			$this->query = "SELECT object_id FROM {$this->db_class->term_relationships} WHERE term_taxonomy_id = {$term_id}";
		}
	}
}