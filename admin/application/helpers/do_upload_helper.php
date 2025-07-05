<?php
function doUploadImage($name = "", $module = "", $suffix = "")
{
	$BASEPATH = str_replace("system/", "", BASEPATH);
	include_once(APPPATH . "libraries/class.upload.php");

	$CI = &get_instance();
	$session_id = $CI->session->userdata("session_id");

	$return = "";
	$fileName = $name . $suffix . date("ymdHis") . $session_id;
	$fileExt = explode(".", @$_FILES[$name]["name"]);
	$fileExt = end($fileExt);

	$handle = new upload(@$_FILES[$name]);

	if ($handle->uploaded) {

		$handle->file_new_name_body   = $fileName;
		$handle->file_new_name_ext = $fileExt;
		$handle->process($BASEPATH . "upload/" . $module . "");
		$handle->processed;

		$handle->file_new_name_body   = $fileName;
		$handle->file_new_name_ext = $fileExt;
		$handle->image_resize          = true;
		$handle->image_ratio_crop      = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 150;
		$handle->process($BASEPATH . "upload/" . $module . "/thumb_edit/");
		$handle->processed;

		$handle->file_new_name_body   = $fileName;
		$handle->file_new_name_ext = $fileExt;
		$handle->image_resize          = true;
		$handle->image_ratio_crop      = true;
		$handle->image_ratio_y         = true;
		$handle->image_x               = 80;
		$handle->process($BASEPATH . "upload/" . $module . "/thumb_list/");
		$handle->processed;

		$handle->clean();
		$return = $fileName . "." . $fileExt;
	}

	return $return;
}

function doUploadFile($name = "", $module = "", $suffix = "")
{
	$BASEPATH = str_replace("system/", "", BASEPATH);
	$return = "";
	$CI = &get_instance();
	$session_id = $CI->session->userdata("session_id");
	if (@$_FILES[$name]["name"] != "") :
		$fileName = $name . $suffix . date("ymdHis") . $session_id;
		$fileExt = explode(".", @$_FILES[$name]["name"]);
		$fileExt = end($fileExt);
		$return = $fileName . "." . $fileExt;
		@move_uploaded_file($_FILES[$name]["tmp_name"], $BASEPATH . "upload/" . $module . "/" . $return);
	endif;
	return $return;
}

function doUploadQRCode($pathUpload = "", $fileName = "")
{
	$BASEPATH = str_replace("system/", "", BASEPATH);
	include_once(APPPATH . "libraries/class.upload.php");

	$fileExt = "png";
	$handle = new upload($pathUpload);

	if ($handle->uploaded) {

		$handle->file_new_name_body   = $fileName;
		$handle->file_new_name_ext = $fileExt;
		$handle->image_resize          = true;
		$handle->image_ratio_crop      = true;
		$handle->image_y               = 150;
		$handle->image_x               = 150;
		$handle->process($BASEPATH . "upload/qrcode/thumb_edit/");
		$handle->processed;

		$handle->file_new_name_body   = $fileName;
		$handle->file_new_name_ext = $fileExt;
		$handle->image_resize          = true;
		$handle->image_ratio_crop      = true;
		$handle->image_y               = 25;
		$handle->image_x               = 25;
		$handle->process($BASEPATH . "upload/qrcode/thumb_list/");
		$handle->processed;
	}
}
