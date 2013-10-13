<?php
require 'config.php';

foreach($_FILES as $file)
{
// some information about image we need later.
$FileName 		= $file['name'];
$ImageSize 		= $file['size'];
$TempSrc	 	= $file['tmp_name'];
$FileType	 	= $file['type'];
$FileError      = $file['error'];

}

if(!empty($TempSrc))
{
	$str1 = explode("/",$FileType[0]);
//echo "the tempsrc is".$TempSrc[0]."and it is of type ". $str1[0];
if($str1[0] == "image")
{
//----------------------------------------image--------------------start-------------------------------------
 if ($FileError[0] > 0)
 {
    $error = "Return Code: " . $FileError[0] . "<br />";
 }
 elseif (file_exists(SOURCE_PATH ."images/". $FileName[0]))
  {
      $error = $FileName[0] . " already exists. ";
  }
$target_path = SOURCE_PATH."images/";
$target_path = $target_path . basename( $FileName[0]);
if(move_uploaded_file($TempSrc[0], $target_path) && !isset($error))
{
    echo $FileName[0]." ^ ".hash('crc32', time() . $FileName[0], false)." ^ successfully uploaded";
}
else
{
    echo "^^There was an error uploading the file, please try again!".'['.$error.']';
}

//--------------------------------image---------------------------end----------------------------------------------
}
else if($str1[0] == "video")
{
//-------------------video----------------start-------------------------------------------------------
 
 if ($FileError[0] > 0)
 {
    $error = "Return Code: " . $FileError[0] . "<br />";
 }
 elseif (file_exists(SOURCE_PATH . $FileName[0]))
  {
      $error = $FileName[0] . " already exists. ";
  }
$target_path = SOURCE_PATH;
$target_path = $target_path . basename( $FileName[0]);
if(move_uploaded_file($TempSrc[0], $target_path) && !isset($error))
{
    echo $FileName[0]." ^ ".hash('crc32', time() . $FileName[0], false)." ^ successfully uploaded";
}
else
{
    echo "^^There was an error uploading the file, please try again!".'['.$error.']';
}

//-------------end--------------------end----------------------------------video--------------------
}
else if($str1[0] == "audio")
{
//-------------------audio----------------start-------------------------------------------------------
 
 if ($FileError[0] > 0)
 {
    $error = "Return Code: " . $FileError[0] . "<br />";
 }
 elseif (file_exists(SOURCE_PATH ."audios/". $FileName[0]))
  {
      $error = $FileName[0] . " already exists. ";
  }
$target_path = SOURCE_PATH."audios/";
$target_path = $target_path . basename( $FileName[0]);
if(move_uploaded_file($TempSrc[0], $target_path) && !isset($error))
{
    echo $FileName[0]." ^ ".hash('crc32', time() . $FileName[0], false)." ^ successfully uploaded";
}
else
{
    echo " ^ ^ There was an error uploading the file, please try again!".'['.$error.']';
}

//-------------end--------------------end----------------------------------audio--------------------
}

}
?>