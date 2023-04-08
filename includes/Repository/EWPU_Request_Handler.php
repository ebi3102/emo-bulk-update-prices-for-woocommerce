<?php
/**
 * Defines the functionality required to get a http request
 *
 * When any methods are called it get request and return a string
 * or a default value.
 */

 namespace EMO_BUPW\Repository;
class EWPU_Request_Handler {

	private function __construct(){}

	/**
	 * get any http request and pass it through esc_sql for avoiding sql injection
	 * @param $key string the name of field
	 * @param $default= null the return value if there is no request
	 *
	 * @return string|null
	 */
	public static function get_request(string $key, $default = null): string | null
	{
		return !empty($_REQUEST[$key]) ? esc_sql($_REQUEST[$key]) : $default;
	}

	/**
	 * get post http request and pass it through esc_sql for avoiding sql injection
	 * @param $key string the name of field
	 * @param $default= null the return value if there is no request
	 *
	 * @return string|null
	 */
	public static function get_POST( string $key, $default = null): string | null
	{
		return !empty($_POST[$key]) ? esc_sql($_POST[$key]) : $default;
	}

	/**
	 * get http request and pass it through esc_sql for avoiding sql injection
	 * @param $key string the name of field
	 * @param $default= null the return value if there is no request
	 *
	 * @return string|null
	 */
	public static function get_GET(string $key, $default = null): string | null
	{
		return !empty($_GET[$key]) ? esc_sql($_GET[$key]) : $default;
	}

	/**
	 * get file http request and return it as an array
	 * @param $key string       the name of field
	 * @param $default= null    the return value if there is no request
	 *
	 * @return array|null
	 *      Array(
	 *          name        the name of uploaded file
	 *          type        the type of uploaded file
	 *          tmp_name    the path of uploaded file
	 *          error       1 if there is an error else 0
	 *          size        the size of uploaded file
	 * )
	 */
	public static function get_FILE(string $key, $default = null): array |null
	{
		return !empty($_FILES[$key])? $_FILES[$key]:null;
	}
}