<?php
/**
 * @package EWPU
 * ========================
 * CSV CREATOR
 * ========================
 * Text Domain: emo-bulk-update-prices-for-woocommerce
 */

/**
 * Class Name: EWPU_Csv_Handler

Namespace: EmoWooPriceUpdate\Repository\File_Handlers

Parent Class: EWPU_File_Handler

Interfaces Implemented: EWPU_Read_File_Interface, EWPU_Write_File_Interface

Description: The EWPU_Csv_Handler class is responsible for handling CSV files. It extends the EWPU_File_Handler class and implements the EWPU_Read_File_Interface and EWPU_Write_File_Interface interfaces. It provides methods for reading and writing CSV files.

Methods:

readFile(array $arg): array | false

Description: This method reads a CSV file and returns an array of values.

Parameters:

$arg (array): An array of arguments that are passed to the method. The array can contain the following keys:

length (int): The maximum length of a line to be read from the file. The default value is 1000.

separator (string): The delimiter used to separate fields in the CSV file. The default value is a comma (',').

enclosure (string): The character used to enclose fields that contain the separator character. The default value is a double-quote ('"').

escape (string): The character used to escape the enclosure character. The default value is a backslash ('').

Return Value:

If the file is successfully read, an array of values is returned.

If the file cannot be read, the method returns false.

writeToFile(array $arg)

Description: This method writes an array of values to a CSV file.

Parameters:

$arg (array): An array of arguments that are passed to the method. The array can contain the following keys:

content (array): The array of values to be written to the CSV file.

separator (string): The delimiter used to separate fields in the CSV file. The default value is a comma (',').

enclosure (string): The character used to enclose fields that contain the separator character. The default value is a double-quote ('"').

escape (string): The character used to escape the enclosure character. The default value is a backslash ('').

eol (string): The end-of-line character. The default value is a newline ('\n').

Return Value:

This method does not return anything. If the file cannot be written, an error is thrown.
*/

 namespace EmoWooPriceUpdate\Repository\File_Handlers;
 use EmoWooPriceUpdate\Repository\File_Handlers\EWPU_File_Handler;
 use EmoWooPriceUpdate\Repository\File_Handlers\EWPU_Read_File_Interface;
 use EmoWooPriceUpdate\Repository\File_Handlers\EWPU_Write_File_Interface;
class EWPU_Csv_Handler extends EWPU_File_Handler implements EWPU_Read_File_Interface,EWPU_Write_File_Interface
{
	/**
	 * Write array content on a csv file that each element of $content puts on each column of csv file
	 *
	 * @param array $arg {
	 *      argument parameters for writing on csv file
	 *      @type int $fields = 1000
	 *      @type string $separator =  ","
	 *      @type string $enclosure = "\""
	 *      @type string $escape = "\\"
	 * }
	 *
	 * @return array|false Returns an indexed array containing the fields read on success, or false on failure.
	 *
	 */
	public function readFile(array $arg):array | false
	{
		$length = ($arg['length'])? $arg['length']:1000;
		$separator = ($arg['separator'])? $arg['separator']:',';
		$enclosure = ($arg['enclosure'])? $arg['enclosure']: "\"";
		$escape = ($arg['escape'])? $arg['escape']:"\\";

		return fgetcsv($this->handler, $length, $separator, $enclosure, $escape);
	}


	/**
	 * Write array content on a csv file that each element of $content puts on each column of csv file
	 *
	 * @param array $arg {
	 *      argument parameters for writing on csv file
	 *      @type array $content  the array data that should be written on csv file
	 *      @type string $separator =  ","
	 *      @type string $enclosure = "\""
	 *      @type string $escape = "\\"
	 *      @type string $eol = "\n"
	 * }
	 *
	 */
	public function writeToFile(array $arg)
    {
        $content = ($arg['content'])?$arg['content']:'' ;
    	$separator = ($arg['separator'])? $arg['separator']:',';
	    $enclosure = ($arg['enclosure'])? $arg['enclosure']: "\"";
	    $escape = ($arg['escape'])? $arg['escape']:"\\";
	    $eol = ($arg['eol'])? $arg['eol']:"\n";
	    fputcsv($this->handler, $content, $separator, $enclosure, $escape);
    }



}