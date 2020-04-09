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
		
		/* ========================================================================= */
		
		/* @@Rage -- Tag search */
		$tags_crit = "";
		if(!empty($sel_ops['tag'])){
			$tags_arr  = json_decode(base64_decode($sel_ops['tag'])); 
			
			if(count($tags_arr) > 0){
				foreach($tags_arr as $vvals){		
					$ccrit[] = " `ort_posts`.`post_tag` like ". q_si($vvals, 1) ." ";			
				}
				$tags_crit = " WHERE " . implode(" or ", $ccrit ) ;
			}
		} 
		/* END @@Rage -- Tag search */
		
		
		
		

		$sq_crit = (count($ccrit) > 0) ? ' and '. implode(' and ', $ccrit) : ''; 

		/* @@ Rage -- Image Extentions */
		$image_extenstions = array('jpg', 'jpeg', 'png', 'gif');

		// Get tag
//		$tg = '';
//		$tag = '';
//
//		if(!empty($_GET['tag'])){
//			$tg = $_GET['tag'];
//		} else{ 
//		}


		//$strTags = (strpos($tg, ',')) ? str_replace(' , ', '","', $tg) : $tg; 
		// displayArray($strTags); exit;
		// $strTags .= '"'.$strTags.'"';
		
		// A little voodoo to enable our filters to work
		// $tag = (!empty($tg)) ? " WHERE `post_tag` = ".q_si($tg, 1)." ": ""; 
		//$tag = (!empty($strTags)) ? " WHERE `ort_posts`.`post_tag` like ".q_si($strTags, 1)." ": ""; 
		// displayArray($tag); 


		// $qry = "SELECT * FROM `ort_posts` WHERE `post_longitude` is not null and  `post_latitude` is not null  order by `post_entry_id` DESC;";
		// $qry = "SELECT * FROM `ort_posts` WHERE `post_longitude` is not null and  `post_latitude` is not null  and `post_tag` like '$tag' order by `post_entry_id` DESC;";
		//$qry = "SELECT * FROM `ort_posts`  ".$tag." order by `post_entry_id` DESC;";
		
		// and `ort_posts`.`post_session` =  `ort_resources_table`.`post_session` 
		// $qry = "SELECT `ort_posts`.*, `ort_resources_table`.* FROM `ort_posts` 
		// 	left join `ort_resources_table` on  `ort_posts`.`user_id` =  `ort_resources_table`.`user_id`  and `ort_posts`.`post_session` =  `ort_resources_table`.`post_session` 
		// 	".$tags_crit." group by `ort_posts`.`post_entry_id` order by `ort_posts`.`post_entry_id`  DESC;";

		// Kev Update to disable unpublished comments

		$qry = "SELECT `ort_posts`.*, `ort_resources_table`.* FROM `ort_posts` 
			left join `ort_resources_table` on  `ort_posts`.`user_id` =  `ort_resources_table`.`user_id`  and `ort_posts`.`post_session` =  `ort_resources_table`.`post_session`
			where `ort_posts`.`published` = 1 ".$tags_crit." group by `ort_posts`.`post_entry_id` order by `ort_posts`.`post_entry_id` DESC;";
		
		//  displayArray($qry); exit;

		if(!empty($sel_ops['dbg'])){
			echobr($qry);
		}
		
		 /*displayArray($cndb->dbQueryFetch($qry)); exit;*/

		$res 		= $cndb->dbQuery($qry);

		$k 			= null;
		$map_data 	= array(); 
		$map_recs 	= array(); 
		
		$domain_path = 'https://nuru.live/dashboard/';
		$domain_path_rage = 'http://localhost/oireporting_web/';
		
		$i = 0;
		while($row_a = $cndb->fetchRow($res, "assoc"))
		{
			$row  	= array_map("clean_output", $row_a);	
			/*displayArray($row); exit;*/	
			/*$coords = explode(',', $row['LatLong']);*/

			$post_latitude = floatval($row['post_latitude']);
			$post_longitude = floatval($row['post_longitude']);
			$coords = array($post_latitude, $post_longitude);

			//if(!empty($coords[1]))
			//{
				/*$coords[0] = floatval($coords[0]);
				$coords[1] = floatval($coords[1]);*/
				
				 
				$id					= $row['post_entry_id'];
				$post_session		= $row['post_session'];
			
				/* @@ Rage - unique_post_key */
				$post_key			= $id.'_'.$post_session;
			
				$post_description	= $row['post_description'];
				$post_tag			= $row['post_tag'];
				$post_photo_trim    = str_replace($domain_path, "", $row['res_file_url']);
				$post_photo_trim    = str_replace($domain_path_rage, "", $post_photo_trim);
			
				/*echobr(SITE_PATH.$post_photo_trim);*/			
				$post_photo_thumb 	= '';
			
				/* @@ Rage -- Check if image exists */
				if (file_exists(SITE_PATH.$post_photo_trim)) { 					
					$photo_ext			= getFileExtension($post_photo_trim);
					if(in_array($photo_ext, $image_extenstions)){
						$post_photo_thumb = getThumbName($post_photo_trim); 
						if(!file_exists(SITE_PATH.$post_photo_thumb)){
							$post_photo_thumb = autoThumbnail($post_photo_trim, 1);						
						} else { /*echobr($post_photo_thumb . " iko");*/ }
					}  
					//echobr($post_photo_trim .' ---- '. $post_photo_thumb);
				} else {
					$post_photo_trim = '';
				}
					
					
				$post_photo			= ($row['res_file_url'] !== "") ? $row['res_file_url'] : "";
				$post_date_device	= date("Y-F-d H:i", strtotime($row['post_date_device'])); //$row['post_date_device'];
				$post_by_full		= trim($row['user_id']);
				$user_em_check		= strpos($post_by_full,'@');
				$post_by			= ($user_em_check) ? ucwords(substr($post_by_full, 0, $user_em_check)) : '';
				$post_date			= date("Y-F-d", $row['post_date_web']);
				 
				$post_tag 			= str_replace('|', ", ", substr($post_tag, 0, -1) );
				
				$post_description_trim = smartTruncateNew($post_description, 75);
				
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
					'photo' 	=> ''.$post_photo_thumb.'',
					'photo_original' 	=> ''.$post_photo_trim.'',
					'entity_code' 		=> $post_session,
					'tags' 		=> ''.$post_tag.'',
					'lat' 		=> $post_latitude,
					'lng' 		=> $post_longitude
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


				$i++;
			//}

		}

		 
		$markers_b = array("type" => "FeatureCollection", "features" => $map_data, "table" => $map_recs);

		echo json_encode($markers_b);
		/* ========================================================================= */
		
	break;
}

		
?>
