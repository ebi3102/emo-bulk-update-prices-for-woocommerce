<?php
/**
 * A static class to inspect the various aspects of statuses of a form
 * that contain file to upload and handle the errors
 *
 * if any methods detect an error will return WP_Error class else false
 */

 namespace EMO_BUPW;
 use EMO_BUPW\EMO_BUPW_Form_Error;
class EMOBUPW_Form_File_Error extends EMO_BUPW_Form_Error {

	public static function extensions_inspection(){}

	public static function file_size_inspection(){}

}