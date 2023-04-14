<?php

namespace EMO_BUPW\Repository;
if(! class_exists('EMO_BUPW_DB')){
	abstract class EMO_BUPW_DB {
		protected $db_class;
		protected $query;

		protected function  __construct()
		{
			global $wpdb;
			$this->db_class = $wpdb;
		}

		public function results()
		{
			return $this->db_class->get_results($this->do_prepare());
		}

		private function do_prepare()
		{
			return $this->db_class->prepare($this->query);
		}
	}
}