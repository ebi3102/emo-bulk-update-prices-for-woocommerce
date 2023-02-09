<?php
/**
 * Defines the functionality required to get a http request
 *
 * When any methods are called it get request and return a string
 * or a default value.
 */
class EWPU_Request_Handler {

	private function __construct(){}

	/**
	 * get any http request and pass it through esc_sql for avoiding sql injection
	 * @param $key
	 * @param $default
	 *
	 * @return string|null
	 */
	public static function get_request($key, $default = null): string | null
	{
		return !empty($_REQUEST[$key]) ? esc_sql($_REQUEST[$key]) : $default;
	}

	/**
	 * get post http request and pass it through esc_sql for avoiding sql injection
	 * @param $key
	 * @param $default
	 *
	 * @return string|null
	 */
	public static function get_POST($key, $default = null): string | null
	{
		return !empty($_POST[$key]) ? esc_sql($_POST[$key]) : $default;
	}

	/**
	 * get http request and pass it through esc_sql for avoiding sql injection
	 * @param $key
	 * @param $default
	 *
	 * @return string|null
	 */
	public static function get_GET($key, $default = null): string | null
	{
		return !empty($_GET[$key]) ? esc_sql($_GET[$key]) : $default;
	}
}