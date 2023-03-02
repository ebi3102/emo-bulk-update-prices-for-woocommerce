<?php

namespace EmoWooPriceUpdate\Repository;

use EmoWooPriceUpdate\Repository\EWPU_Pass_Error_Msg;

class EWPU_Uploader {
	private $upload_path;
	private $upload_uri;

	private function upload_handler(array $fileChecker, string $filePath, array $file)
	{
		$extensions= ($fileChecker['extensions'])? $fileChecker['extensions']:array("png",'jpg', 'jpeg');
		$maxFileSize = ($fileChecker['max-size'])? $fileChecker['max-size']:2097152;

		//Check and create essential directories
		if (!file_exists($this->upload_path)){
			mkdir($this->upload_path, 0777, true);
		}

		//Upload and handle National ID Image
		$fileTemp =$file['tmp_name'];
		$fileSize = $file['size'];
		$fileExt =strtolower(pathinfo($filePath,PATHINFO_EXTENSION));

		if(in_array($fileExt,$extensions)=== false){
			$errors= EWPU_Pass_Error_Msg::error_object( 'file-type', __( "The extension of uploaded file is not allowed, please choose a csv file.", "emo_ewpu" ) );
			return ['error'=>$errors];
		}
		if($fileSize > $maxFileSize){
			$errors= EWPU_Pass_Error_Msg::error_object( 'file-size', __( "File size is more than allowed size.", "emo_ewpu" ) );
			return ['error'=>$errors];
		}
		if(move_uploaded_file($fileTemp,$filePath)){
			return ['error'=>false];
		}
		return ['error'=>EWPU_Pass_Error_Msg::error_object( 'unable', __( "در حال حاضر امکان بارگذاری فایل وجود ندارد.", "emo_ewpu" ) )];
	}

}