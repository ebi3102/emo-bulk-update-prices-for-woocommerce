<?php
/**
 * @package EWPU
 * ========================
 * CSV CREATOR
 * ========================
 * Text Domain: emo_ewpu
 */
class EMO_EWPU_CsvHandler
{
    private $createDirectory = EWPU_CREATED_DIR;
    private $handler = null;

    function __construct($filename, $mode)
    {
        $this->handler = fopen($this->createDirectory.$filename, $mode);
    }

    public function writeToFile($content)
    {
        fputcsv($this->handler, $content);
    }
/*
resource $stream,
?int $length = null,
string $separator = ",",
string $enclosure = "\"",
string $escape = "\\"
*/

    public function readRow()
    {

    }

    public function closeFile()
    {
	    fclose($this->handler);
    }
}