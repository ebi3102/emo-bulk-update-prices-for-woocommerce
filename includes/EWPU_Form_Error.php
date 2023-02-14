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
class EWPU_Form_Error {


	private function __construct(){}

//	private function error_inspection(string $conditioner, string $errorName, string $errorMsg )
//	{
//		if(EWPU_Request_Handler::get_POST($submitName)){
//			$error = false;
//		}else{
//			$error = EWPU_Pass_Error_Msg::error_object('submitError', __( "There are an error while you update", "emo_ewpu" ));
//		}
//		return $error;
//	}

	/**
	 * Inspect the status of form which is submitted or not
	 * @param string $submitName
	 *
	 * @return Repository\WP_Error|false
	 */
	public static function submit_status(string $submitName): Repository\WP_Error|false
	{
		if(EWPU_Request_Handler::get_POST($submitName)){
			$error = false;
		}else{
			$error = EWPU_Pass_Error_Msg::error_object('submitError', __( "There are an error while you update", "emo_ewpu" ));
		}
		return $error;
	}

	/**
	 * @param string $nonceName
	 * @param string|int $nonceAction
	 *
	 * @return Repository\WP_Error|false
	 */
	public static function nonce_inspection(string $nonceName, string|int $nonceAction=-1): Repository\WP_Error|false
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


	public static function requirement_inspection(string $fieldName){

	}



}