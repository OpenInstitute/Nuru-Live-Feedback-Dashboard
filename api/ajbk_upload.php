<?php 
require_once("../classes/cls.constants.php");	
	 
	
	$curr_time = time();
	/*echobr(UPL_USERPOSTS);*/
	//this is our upload folder 
	$upload_path = UPL_USERPOSTS; //'uploads/';
	
	//Getting the server ip 
	$server_ip = gethostbyname(gethostname());
	
	//creating the upload url 
	$upload_url = DISP_USERPOSTS; //'http://'.$server_ip.'/AndroidImageUpload/'.$upload_path; #771c1c
	
	//response array 
	$response = array(); 
	
	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		
		//checking the required parameters from the request  
		//isset($_POST['name']) and 
		if(isset($_FILES['image']['name'])){
			
			//connecting to the database 
			//// $con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect...');
			
			$post_entry_id = $_POST['post_entry_id'];
			$user_id = $_POST['user_id'];  
			$res_type = "p";  
			
			//getting name from the request 
			$name = (!empty($_POST['name'])) ?  $_POST['name']: "another";
			
			$post_session   = '';
			$arr_file_name 		= explode("-", $name);
			if(is_array($arr_file_name) and count($arr_file_name) == 3)
			{
				$post_session   = $arr_file_name[1]; 
			}
			
			
			//getting file info from the request 
			$fileinfo = pathinfo($_FILES['image']['name']);
			
			//getting the file extension 
			$extension = $fileinfo['extension'];
			
			//$new_file  = getFileName() . '.' . $extension;
			$new_file  = generate_seo_title($name, "_") . '.' . $extension;
			
			//file url to store in the database 
			$file_url = $upload_url . $new_file;
			
			//file path to upload in the server 
			$file_path = $upload_path . $new_file; 
			
			//trying to save the file in the directory 
			try{
				//saving the file 
				move_uploaded_file($_FILES['image']['tmp_name'], $file_path);
				$sql = "INSERT INTO `ort_resources_table` (`post_entry_id`, `user_id`, `res_type`, `res_file_url`, `res_file_name`, `res_date`, `post_session`) VALUES 
				(".q_si($post_entry_id).", ".q_si($user_id).", ".q_si($res_type).", ".q_si($file_url).", ".q_si($name).", ".q_si($curr_time).", ".q_si($post_session)." );";
				
				//adding the path and name to database 
				if($cndb->dbQuery($sql)){
					
					//filling response array with values 
					$response['error'] = false; 
					$response['url'] = $file_url; 
					$response['name'] = $name;
				}
			//if some error occurred 
			}catch(Exception $e){
				$response['error']=true;
				$response['message']=$e->getMessage();
			}		
			//displaying the response 
			echo json_encode($response);
			
			//closing the connection 
			//mysqli_close($con);
		}else{
			$response['error']=true;
			$response['message']='Please upload a file';
			
			echo json_encode($response);
		}
	}
	
	/*
		We are generating the file name 
		so this method will return a file name for the image to be upload 
	*/
	function getFileName(){
		$con = mysqli_connect(HOST,USER,PASS,DB) or die('Unable to Connect...');
		$sql = "SELECT max(id) as id FROM images";
		$result = mysqli_fetch_array(mysqli_query($con,$sql));
		
		mysqli_close($con);
		if($result['id']==null)
			return 1; 
		else 
			return ++$result['id']; 
	}

?>
