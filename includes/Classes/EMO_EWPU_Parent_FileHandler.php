<?php
/**
 * Base class for Handling files.
 *
 */
class EMO_EWPU_Parent_FileHandler {
	protected $createDirectory = EWPU_CREATED_DIR;
	protected $handler = null;

	/**
	 * Constructor
	 * @param string $filename
	 * @param string $mode
	 * @param false $use_include_path
	 */
	public function __construct(string $filename, string $mode, bool $use_include_path = false)
	{
		$this->handler = fopen($this->createDirectory.$filename, $mode, $use_include_path);
	}

	/**
	 * Close an open file pointer
	 *
	 */
	public function closeFile()
	{
		fclose($this->handler);
	}
}