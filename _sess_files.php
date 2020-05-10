<?php
require_once("classes/cls.constants.php");
  
$photo = '';
/* 
displayArray(pathinfo("/storage/emulated/0/murage_openinstitute_20200508_071457928.3gp"));

$f_string = '[/storage/emulated/0/WhatsApp/Media/WhatsApp Images/IMG-20200508-WA0017.jpg, /storage/emulated/0/WhatsApp/Media/WhatsApp Images/IMG-20200508-WA0000.jpg, /storage/emulated/0/WhatsApp/Media/WhatsApp Images/IMG-20200508-WA0005.jpg, /storage/emulated/0/murage_openinstitute_20200508_071457928.3gp]';

//echobr(strpos($f_string, "]"));
$f_clean  = (strpos($f_string, "]") > 0) ? substr($f_string, 1, (strlen($f_string)-2)) : $f_string;
$f_arr	  = explode(",", $f_clean);

displayArray($f_clean);
displayArray($f_arr);
exit;*/

$post_errors = array(); 

$image_extentions = array('jpg', 'jpeg', 'png', 'gif');
$audio_extentions = array('3gp', 'wav', 'mp3', 'mp4', 'm4a');

$sq_rental = "SELECT * FROM `ort_resources_table` where `res_ext` IS NULL; ";  
$sq_rental = "SELECT * FROM `ort_resources_table` WHERE res_file_url like '%[%' ORDER BY `res_record_id` DESC; ";  
$rs_rental = $cndb->dbQuery($sq_rental); 

 $sq_res 	= array();
while($rageff = $cndb->fetchRow($rs_rental, "assoc"))	
{
	 
	$record_id 		= $rageff['res_record_id'];  
	$post_entry_id 	= $rageff['post_entry_id'];  
	$user_id 		= $rageff['user_id'];  
	$res_date 		= $rageff['res_date'];  
	$post_session 	= $rageff['post_session'];  
	
	$f_string		= $rageff['res_file_url'];  
	//$res_file_url 	= $rageff['res_file_url'];  
	$f_clean  		= (strpos($f_string, "]") > 0) ? substr($f_string, 1, (strlen($f_string)-2)) : $f_string;
	$f_arr	  		= explode(",", $f_clean);
	
	$out	= array();
	foreach($f_arr as $val){
		$f_name			= pathinfo(trim($val));
		$file_path		= DISP_USERPOSTS . $f_name['basename'];
		$file_name		= $f_name['filename'];
		$extension		= $f_name['extension'];
		$res_type		= (in_array($f_name['extension'], $image_extentions)) ? 'p' : 'd';
						if(in_array($f_name['extension'], $audio_extentions) ) { $res_type = 'a'; } 
		 
		
		$sq_res[] = "INSERT INTO `ort_resources_table` (`post_entry_id`, `user_id`, `res_type`, `res_file_url`, `res_file_name`, `res_date`, `post_session`, `res_ext`) VALUES 
				(".q_si($post_entry_id).", ".q_si($user_id).", ".q_si($res_type).", ".q_si($file_path).", ".q_si($file_name).", ".q_si($res_date).", ".q_si($post_session)." , ".q_si($extension)." );";
	}
	
	//$rv 			= iosFileExt($res_file_url); 	 
	
	/*if(is_array($rv))
	{
		 $sq_res[] = "UPDATE `ort_resources_table` SET  `res_type` = ".q_si($rv['f_type']).", `res_ext` = ".q_si($rv['f_ext'])." WHERE `res_record_id` =  ".q_si($record_id)." ;";
 	} */ 
}
/*displayArray($sq_res); exit;*/
if(count($sq_res)){
	$cndb->dbQueryMulti($sq_res);
}

exit;
?>