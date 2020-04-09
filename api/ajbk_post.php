<?php
require_once("../classes/cls.constants.php");
/*require_once('../classes/africastalking.php');*/
	
	
	

$msg = 'Failed';
$cat_types = array("transfer"=>1 );
 

if($_SERVER['REQUEST_METHOD'] == 'POST') { 
	
	$posted 		= $_POST; 
	$post 			= (is_array($posted)) ? $posted : unserialize($_POST);
	
	$curr_time 		= time();
	$acc_id  	 	= $post['param_account_key']; 
	$cat_id  	 	= $post['param_category_id']; 
	$ddata_raw  	= base64_decode($post['param_report_data']); 
	$ddata_arr 		= (array) json_decode($ddata_raw);
	
	if($cat_id === "transfer")
	{
		$new_post 		= "n";
		/*$post_session	= $ddata_arr['post_category'];	*/
		$post_session	= (!empty($ddata_arr['post_session'])) ? $ddata_arr['post_session'] : time()."000";	

		$sq_post 	 = " INSERT INTO `ort_sync_temp`(`user_email`, `post_category`, `post_session`, `post_data`, `post_data_long`, `post_date` ) VALUES ( ".q_si($acc_id).", ".q_si($cat_id).", ".q_si($post_session).", ".q_si($ddata_arr).", ".q_si($posted).", ".q_si($curr_time)."  ) ; "; 

		if($cndb->dbQuery($sq_post)) 
		{	
			$rec_id 	= $cndb->insertId();
			
			syncPostsTable($rec_id);
			/*$auth_code	= strtoupper(getSalt(intval($rec_id)));
			$voucher_no = $curr_time + intval($rec_id);
			$p_date 	= date('Y-m-d', $curr_time);
			$p_type 	= $cat_types[$cat_id]; // ($cat_id == 'transfer') ? 1 : 2;
			$dform 		= $ddata_arr;*/
			
			
			$msg = 'Success';
		} 
	}  
}

echo $msg;
exit;


function syncPostsTable($entry_id){
	$cndb = new master();
	$ddSelect = new drop_downs;
	
	$post_errors = array();

	$sq_rental = "select * from `ort_sync_temp` where `record_id` = ".q_si($entry_id)."  order by `record_id` ASC   ; ";  
	$rs_rental = $cndb->dbQueryFetch($sq_rental); 

	foreach($rs_rental as $rageff)
	{
 
		$record_id 		= $rageff['record_id'];
		$user_email 	= $rageff['user_email'];
		$post_date 		= $rageff['post_date'];

		if(isset($rageff['post_session']) && intval($rageff['post_session']) > 0)
		{
			$post_session 	= $rageff['post_session']; 			
			$post_device_date = date("Y-m-d H:i:s", substr($post_session, 0, -3));
		}  else {
			$post_session 	= $post_date.'000'; 
			$post_device_date = date("Y-m-d H:i:s", $post_date);
		}
		/*echobr('$post_session '. $post_session .' - '. intval($post_session) .' - '. gettype(intval($post_session))); */

		$dform 			= @unserialize($rageff['post_data']); 
		if(!is_array(gettype($dform))) {
			$post_errors[]  = $record_id;
			$ddata_coded 	= unserialize($rageff['post_data_long']);			 
			$ddata_raw  	= base64_decode($ddata_coded['param_report_data']); 
			$dform 			= (array) json_decode($ddata_raw); 
		}

		if(is_array($dform))
		{
			/*displayArray($dform); exit;*/
			$post_tag 		= $dform['post_tag']; 	  

			if(!empty($dform['post_id'])){
				
				$sq_post = "UPDATE `ort_posts` SET `post_project` = ".q_si($dform['post_project']).", `post_category` = ".q_si($dform['post_category']).", `post_description` = ".q_si($dform['description']).", `post_tag` = ".q_si($post_tag)."  WHERE `post_session` = ".q_si($post_session)." AND `user_id` = ".q_si($rageff['user_email'])." ;";
				
			}
			else
			{
				$sq_post = "REPLACE INTO `ort_posts`(`post_entry_id`, `user_id`, `post_project`, `post_session`, `post_category`, `post_description`, `post_tag`, `post_longitude`, `post_latitude`, `post_date_device`, `post_date_web`) VALUES (
					".q_si($record_id).", 
					".q_si($rageff['user_email']).", 
					".q_si($dform['post_project']).", 
					".q_si($post_session).", 
					".q_si($dform['post_category']).", 
					".q_si($dform['description']).", 
					".q_si($post_tag).", 
					".q_si($dform['post_longitude']).", 
					".q_si($dform['post_latitude']).", 
					".q_si($post_device_date).", 
					".q_si($post_date)."
				)  ;";
			 }
			 
			$rs_post 	= $cndb->dbQuery($sq_post); 
			
			/* === @@ Populate Tags === */  
			$post_tag 		= $dform['post_tag']; 	
			$tag_names 		= explode("|", $post_tag); 
			$tag_cat		= strtolower(trim($dform['post_category']));
			$ddSelect->tagsPopulate($tag_names, $tag_cat, $record_id );
		 } 
		else 
		{
			//$post_errors[] = $rageff['record_id'];
		}
	}
	
}

function mySmsGateway($post){
	
	$curr_time	= time();
	$log_sess   = $post['message_sess'];
	$log_msg    = $post['message'];
	$post_id    = $post['post_id'];
	
	$sendto		= $post['message_to']; 
	$recipients = implode(",", $sendto);
	$message    = $post['message'];
	
	$username   = "ragemunene";
	$apikey     = "3f258e63c58a8244a5bd63b80245fa0f023dd27b032a8833ca4675d1e61b368c";
	$gateway    = new AfricasTalkingGateway($username, $apikey);
	
	
	$sq_sms_log	= array();
	$sms_result	= array();
	$sms_cat	= 'sms_alert';
	
	try 
	{ 
	  // Thats it, hit send and we'll take care of the rest. 
	  $results = $gateway->sendMessage($recipients, $message);
	  
	  foreach($results as $result) 
	  {	
		$sms_result[] = array(
				"_to" 		=> $result->number,
				"_status" 	=> $result->status,
				"_cost" 	=> $result->cost,
				"_msg_Id" 	=> $result->messageId
				);   
		  
		/*$sq_sms_log[] = "INSERT INTO `brsf_mailing_log` 
		(`log_sess`, `log_message`, `sms_to`, `sms_status`, `sms_cost`, `msg_id` )  VALUES "
		."('".$log_sess."', ".$postb['message'].", '".$result->number."', ".quote_smart($result->status).", ".quote_smart($result->cost).",".quote_smart($result->messageId)." )";*/	 
	  }
	}
	catch ( AfricasTalkingGatewayException $e )
	{
		$sms_cat		= 'sms_error_alert';
		$error_status	= $e->getMessage();
		$sms_result[] = array(
				"_status" 	=> $error_status
				);   
		
		/*$sq_sms_log[] ="INSERT INTO `brsf_mailing_log` (`log_sess`, `log_message`, `sms_status` ) VALUES "."('".$log_sess."', ".$postb['message'].", ".quote_smart($e->getMessage())." )";*/
		
	   //echo "Encountered an error while sending: ".$e->getMessage();
	}
	
	$sq_sms_log = " INSERT INTO `aa_tester`(`account_id`, `post_category`, `post_session`, `post_data`, `post_data_long`, `post_date` ) VALUES ( ".q_si($post_id).", ".q_si($sms_cat).", ".q_si($log_sess).", ".q_si($log_msg).", ".q_si($sms_result).", ".q_si($curr_time)."  ) ; ";  	
	
	$cndb = new master();
	$cndb->dbQuery($sq_sms_log); 
	unset($sq_sms_log);
}


?>
