<?php
/**
 * @package EWPU
 * ========================
 * CSV CREATOR
 * ========================
 * Text Domain: emo_ewpu
 */
class EMO_EWPU_CsvCreator
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

    public function closeFile()
    {
	    fclose($this->handler);
    }
}