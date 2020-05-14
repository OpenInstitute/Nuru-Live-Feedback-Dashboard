<?php
require_once('../classes/cls.constants.php'); 

$sel_ops 	= array_map("clean_request", $_REQUEST);
$sel_cat	= (!empty($sel_ops['c'])) ? $sel_ops['c'] : 'loctn';

$ccrit 		= array();

if(!empty($sel_ops['dbg'])){
	displayArray($sel_ops);
	$nini = base64_decode($sel_ops['tag']);
	displayArray(json_decode($nini));
	
	//displayArray($ccrit);
	//displayArray($_SESSION['_ablt_']['indoor_checks']);
}


switch ($sel_cat)
{
	
		
	case "loctn":

		// Add country to the equation
		$country = '';

		if(!empty($_GET['country'])){
			$country = '%'.$_GET['country'].'%';
		}else{
			$country = '%';
		}
		
		/* ========================================================================= */
		
		/* @@Rage -- Tag search */
		$tags_crit = "WHERE `ort_posts`.`published` = 1 AND `ort_posts`.`post_country` like '$country'";
		if(!empty($sel_ops['tag'])){
			$tags_arr  = json_decode(base64_decode($sel_ops['tag'])); 
			
			if(count($tags_arr) > 0){
				foreach($tags_arr as $vvals){		
					$ccrit[] = " `ort_posts`.`post_tag` like ". q_si($vvals, 1) ."  AND `ort_posts`.`published` = '1'";			
				}
				$tags_crit = " WHERE " . implode(" OR ", $ccrit ). "  " ;
			}
		} 
		/* END @@Rage -- Tag search */
		 

		/* @@ Rage -- Image Extentions */
		$image_extenstions = array('jpg', 'jpeg', 'png', 'gif');


		/*$qry = "SELECT `ort_posts`.*, `ort_resources_table`.`res_file_url`, `ort_resources_table`.`res_file_name`, `ort_posts`.`post_entry_id` as `the_entry_id` FROM `ort_posts` left join `ort_resources_table` on  `ort_posts`.`user_id` =  `ort_resources_table`.`user_id`  and `ort_posts`.`post_session` =  `ort_resources_table`.`post_session`  ".$tags_crit."  group by `ort_posts`.`post_entry_id` order by `ort_posts`.`post_entry_id` DESC;";*/
		
		$qry = "SELECT `ort_posts`.*, `ort_posts`.`post_entry_id` as `the_entry_id` FROM `ort_posts`  ".$tags_crit."  order by `ort_posts`.`post_entry_id` DESC;";
		

		if(!empty($sel_ops['dbg'])){
			echobr($qry);
		}
		
		 /*displayArray($cndb->dbQueryFetch($qry)); exit;*/

		$res 		= $cndb->dbQuery($qry);

		$k 			= null;
		$map_data 	= array(); 
		$map_recs 	= array(); 
		
		$rec_grouped = array(); 
		
		$domain_path = 'nuru.live/dashboard';
		$domain_path_rage = 'http://localhost/oireporting_web/';
		
		$i = 0;
		while($row_a = $cndb->fetchRow($res, "assoc"))
		{
			$row  	= array_map("clean_output", $row_a);	
			/*displayArray($row); //exit;	*/
			/*$coords = explode(',', $row['LatLong']);*/
			
			$row['res_file_url'] 	= '';
			$post_photo_thumb 		= '';
			$post_photo_trim 		= '';
			$post_files 			= array();
			
			$sq_files = "SELECT `res_record_id` as `_fid`, `res_file_url` as `_url`, `res_file_name` as `_name`, `res_ext` as `_ext`, `res_type` as `_type` FROM `ort_resources_table` WHERE   `user_id` = ".q_si(trim($row['user_id']))." and `post_session` = ".q_si(trim($row['post_session']))." and `res_file_url`  not like '%[%' order by `res_record_id` ASC;";
			$rs_files = $cndb->dbQueryFetch($sq_files); /*, '_fid'*/
			
			if(!empty($sel_ops['dbg'])){
				echobr($qry);
				displayArray($rs_files);
			}
			
			if(count($rs_files) > 0){
				//displayArray($rs_files);
				$post_files = $rs_files;
				
				$photo_one = array_sub_keys_val($rs_files, '_type', 'p');
				if(count($photo_one)){
					$res_image = current($photo_one);
					$post_photo_thumb 		= $res_image['_url'];
					$post_photo_trim 		= $res_image['_url'];
				}
				 
				//$ll = 0;
				//foreach($rs_files as $fk => $fv){ 
					//TODO: CREATE THUMBNAIL
					/* if(strpos($res_url, $domain_path) === 0 or strpos($res_url, $domain_path) > 0){ }*/
						
						
					/*$ext_start 	   	= strrpos($fv , ".")+1;				
					$res_ext		= trim(substr($fv, $ext_start, 5));
					$res_image		= clean_Image($fv); 
					if($res_ext !== '3gp'){						
						//displayArray($res_image);
						if($ll == 0){
							$post_photo_thumb 		= $res_image['_thumb'];
							$post_photo_trim 		= $res_image['_name'];
						} else {
							$post_files[][$res_image['_ext']] = $res_image['_thumb'];
						}
						$ll += 1;
					} else {
						$post_files[][$res_image['_ext']] = $res_image['_name'];
					}*/
					 
				//}
				//displayArray($post_files);
			}

			$post_latitude 		= floatval($row['post_latitude']);
			$post_longitude 	= floatval($row['post_longitude']);
			$coords 			= array($post_latitude, $post_longitude); 
				
				 
			//$id					= $row['post_entry_id'];
			$id					= $row['the_entry_id'];
			$post_session		= $row['post_session'];
			$post_by_full		= trim($row['user_id']);

			/* @@ Rage - unique_post_key */
			$post_key			= $id.'_'.$post_session;
			$user_entry_key		= $post_by_full.'_'.$post_session;

			$post_description	= $row['post_description'];
			$post_tag			= $row['post_tag'];
			
			
			$post_country		= $row['post_country'];
			$post_country_code	= strtolower($row['post_country_code']);
			$post_country_flag	= ($post_country_code !== '') ? DISP_IMAGES.'flags/'.$post_country_code .'.png' : '';
 
			$post_published		= $row['published'];

			//if(!array_key_exists($user_entry_key, $rec_grouped) ) /*and $post_by_full !== ''*/
			//{	
				/* @@ Rage -- Check if image exists */
			
			/*$post_photo_trim    = str_replace($domain_path, "", $row['res_file_url']);
			$post_photo_trim    = str_replace($domain_path_rage, "", $post_photo_trim);
			
				if (file_exists(SITE_PATH.$post_photo_trim)) { 					
					$photo_ext			= getFileExtension($post_photo_trim);
					if(in_array($photo_ext, $image_extenstions)){
						$post_photo_thumb = getThumbName($post_photo_trim); 
						if(!file_exists(SITE_PATH.$post_photo_thumb)){
							$post_photo_thumb = autoThumbnail($post_photo_trim, 1);						
						}  
					}  
				} else {
					$post_photo_trim = '';
				}
			*/		
					
				$post_photo			= ($row['res_file_url'] !== "") ? $row['res_file_url'] : "";
				$post_date_device	= date("Y-F-d H:i", strtotime($row['post_date_device'])); //$row['post_date_device'];
				
				$user_em_check		= strpos($post_by_full,'@');
				$post_by			= ($user_em_check) ? ucwords(substr($post_by_full, 0, $user_em_check)) : '';
				$post_date			= date("Y-F-d", $row['post_date_web']);
				 
				$post_tag 			= $post_tag = implode(",", array_filter(explode("|", $post_tag)));;
				
				
				$post_description_trim = smartTruncateNew($post_description, 150);
				
				$map_data[$i]['type'] = 'Feature';
				$map_data[$i]['id'] = $post_key; /* $id */

				$map_data[$i]['properties'] = array(
					'id' 		=> $post_key, /*intval($id),*/
					'name' 		=> $post_date_device,
					'building' 	=> '',
					'type' 		=> $post_tag,
					'comments' 	=> $post_description,
					'comments_trim' 	=> $post_description_trim,
					'post_by' 	=> $post_by,
					'rating' 	=> '',
					'url' 		=> '',					
					'entity_code' 		=> $post_session,
					'tags' 		=> ''.$post_tag.'',
					'country' 	=> $post_country,
					'country_code' 		=> $post_country_code,
					'country_flag' 		=> $post_country_flag, 
					'lat' 		=> $post_latitude,
					'lng' 		=> $post_longitude,
					'photo' 	=> ''.$post_photo_thumb.'',
					'photo_original' 	=> ''.$post_photo_trim.'',
					'otherfiles' 		=> json_encode($post_files)
				);

				$map_recs[] = array(
					'record_id' 	=> $post_key, /*$id,*/
					'date_posted' 	=> $post_date_device, 
					'post_by' 		=> $post_by,  
					'tags' 			=> $post_tag,
					'post_detail' 	=> $post_description_trim,					
					'post_code' 	=> $post_session,
					'latitude' 		=> $post_latitude,
					'longitude' 	=> $post_longitude
				);

				 

				$map_data[$i]['properties']['perc_access'] = 50;


				$map_data[$i]['geometry']  = array(
					'type' 	=> 'Point',
					'coordinates' 	=> $coords
				);

				$rec_grouped[$post_by_full .'_'. $post_session] = $post_key;

				$i++;
			//}

		}

		 
		$markers_b = array("type" => "FeatureCollection", "features" => $map_data, "table" => $map_recs);

		/*displayArray($markers_b);*/
		echo json_encode($markers_b);
		/* ========================================================================= */
		
	break;
}

		
?>
