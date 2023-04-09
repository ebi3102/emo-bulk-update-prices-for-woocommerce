<?php

namespace EMO_BUPW\Repository;
if (!class_exists('EMO_BUPW_Nonce_Inspection')) {
	class EMO_BUPW_Nonce_Inspection {
		private function __construct() {
		}

		/**
		 * Verifies that a correct security nonce was used with time limit.
		 *
		 * @param string $nonce
		 * @param string|int $action
		 *
		 * @return int|false 1 if the nonce is valid and generated between 0-12 hours ago, 2 if the nonce is valid and generated between 12-24 hours ago.
		 * False if the nonce is invalid.
		 */
		public static function nonce( string $nonce, string|int $action = - 1 ): int|false {
			return wp_verify_nonce( $nonce, $action );
		}
	}
}