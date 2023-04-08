<?php

namespace EMO_BUPW\Repository;

use EMO_BUPW\Repository\EWPU_Pass_Error_Msg;

class EWPU_Uploader {
    private $file;
    private $upload_path;
	private $upload_uri;
	private $allowedExts = array("png",'jpg', 'jpeg');
	private $allowedsize = 2097152;
	private $fileName;
	private $filePath;
	private $file_url = null;
	private $error = false;


	public function __construct( array $file, array $args)
    {
        $this->file = $file;
        $this->fileName = $this->file['name'];
        $this->upload_path = $args['fileDir'];
        $this->upload_uri = $args['fileUrl'];
        $this->allowedExts = $args['extensions'];
        $this->allowedsize = $args['max-size'];
        $this->filePath = $this->upload_path.$this->fileName;

        $this->upload();
    }

    private function inspection(){
        if(in_array(strtolower(pathinfo($this->filePath,PATHINFO_EXTENSION)),$this->allowedExts)=== false){
            $this->error= EWPU_Pass_Error_Msg::error_object( 'file-type', __( "The extension of uploaded file is not allowed, please choose a csv file.", "emo-bulk-update-prices-for-woocommerce" ) );
        }
        if($this->file['size'] > $this->allowedsize){
            $this->error= EWPU_Pass_Error_Msg::error_object( 'file-size', __( "File size is more than allowed size.", "emo-bulk-update-prices-for-woocommerce" ) );
        }
    }

	private function upload()
	{
		//Check and create essential directories
		if (!file_exists($this->upload_path)){
			mkdir($this->upload_path, 0777, true);
		}

        $this->inspection();
		if($this->error){
		    return;
        }
		if(move_uploaded_file($this->file['tmp_name'],$this->filePath)){
			$this->file_url = $this->upload_uri.$this->fileName;
		}else{
            $this->error = EWPU_Pass_Error_Msg::error_object( 'unable', __( "Unable to upload file", "emo-bulk-update-prices-for-woocommerce" ) );
        }
	}

    /**
     * @return mixed
     */
    public function getFileName(): mixed
    {
        return $this->fileName;
    }

    /**
     * @return mixed
     */
    public function getFileUrl():mixed
    {
        if(!$this->error){
            return $this->file_url;
        }else{
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getFilePath(): mixed
    {
        if(!$this->error){
            return $this->filePath;
        }else{
            return false;
        }
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        if(!$this->error){
            return false;
        }else{
            return true;
        }
    }

    /**
     * @return mixed
     */
    public function getError(): mixed
    {
        return $this->error;
    }

}