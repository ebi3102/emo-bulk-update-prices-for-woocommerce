<?php
/**
 * A static class to inspect the various aspects of statuses of a form
 * and handle the errors
 *
 * if any methods detect an error will return WP_Error class else false
*/

namespace EmoWooPriceUpdate;
use EmoWooPriceUpdate\Repository\EWPU_Nonce_Inspection;
use EmoWooPriceUpdate\Repository\EWPU_Pass_Error_Msg;
use EmoWooPriceUpdate\Repository\EWPU_Request_Handler;
use EmoWooPriceUpdate\Repository\WP_Error;

class EWPU_Form_Error {


	private function __construct(){}

	/**
	 * Inspect the condition if it is true method will be returned false
	 * and if is false the method will be returned object error
	 * @param bool $conditioner
	 * @param string $errorName
	 * @param string $errorMsg
	 *
	 * @return \WP_Error|false
	 */
	private function error_inspection(bool $conditioner, string $errorName, string $errorMsg ): \WP_Error|false
	{
		if($conditioner){
			$error = false;
		}else{
			$error = EWPU_Pass_Error_Msg::error_object($errorName, $errorMsg);
		}
		return $error;
	}

	/**
	 * Inspect the status of form which is submitted or not
	 * @param string $submitName
	 *
	 * @return \WP_Error|false
	 */
	public static function submit_status(string $submitName): \WP_Error|false
	{
		$conditioner = (bool) EWPU_Request_Handler::get_POST( $submitName );
		return (new self)->error_inspection(
			$conditioner,
			'submitError',
			__( "There are an error while you update", "emo_ewpu" )
		);
	}

	/**
	 * @param string $nonceName
	 * @param string|int $nonceAction
	 *
	 * @return \WP_Error|false
	 */
	public static function nonce_inspection(string $nonceName, string|int $nonceAction=-1): \WP_Error|false
	{
		$nonce = EWPU_Request_Handler::get_POST($nonceName);
		$nonceVerification = EWPU_Nonce_Inspection::nonce($nonceName, $nonceAction);
		if($nonce || $nonceVerification){
			$error = false;
		}else{
			$error = EWPU_Pass_Error_Msg::error_object('nonce', __( "Sorry, your nonce did not verify.", "emo_ewpu" ));
		}
		return $error;
	}


	/**
	 * Inspect the requirement of field
	 * @param string $fieldName
	 *
	 * @return \WP_Error|false
	 */
	public static function requirement_inspection(string $fieldName): \WP_Error|false
	{
		$conditioner = (bool) EWPU_Request_Handler::get_POST( $fieldName );
		return (new self)->error_inspection(
			$conditioner,
			'requirements',
			__( "There are some required fields that not filled", "emo_ewpu" )
		);
	}



}