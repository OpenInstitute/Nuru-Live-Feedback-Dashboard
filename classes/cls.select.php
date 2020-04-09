<?php
// GLOBAL VARIABLES AND SELECTS

class drop_downs extends master
{
	var $tbl;
	var $col1;
	var $col2;
	var $col3;
	var $query;
	var $crit;
	var $crit2;
	var $line2;
	
	
	
	function tagsPopulate($tags, $tag_cat, $item_id )
	{
		
		if(is_array($tags)) 
		{
			$sq_tags = array();
			foreach($tags as $tag_val)  {				
				if($item_id !== 0 and trim($tag_val) !== ""){	
					$tag_name = strtolower(trim($tag_val));	 	
					$sq_tags[] = "INSERT IGNORE into `ort_posts_tags` (`tag_name`, `tag_category`, `tag_item_id`) values (".q_si($tag_name).", ".q_si($tag_cat).", ".q_si($item_id)."); ";		
				}
			}
			//displayArray($sq_tags); exit;
			if (count($sq_tags) > 0 ) {
				$rs_tags = $this->dbQueryMulti($sq_tags);
			}	
			
		}
		
	}
	
	
	
	function drop_publishStatus($stat = 0){
		$out = '';
		$sys_publish_status = $GLOBALS['SYS_PUBLISH_STATUS'];
		foreach($sys_publish_status as $k => $v){
			$selected = ($stat == $k) ? "selected" : "";
			$out .= '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
		}
		return $out;
	}
	
	
	
	function getUserId($ustr){
		
		$user_id = 0;
		$u_arr 	= explode('__', $ustr);		
		$sq_qry = "select `id` from `users` where `email` = ".q_si($u_arr[0])." ; ";
		$rs_qry = $this->dbQueryFetch($sq_qry);
		if(count($rs_qry)){
			$user_id = $rs_qry[0]['id']; 
		}
		return $user_id;
	}
	
	
	
	
	function drop_checks($tbl, $col1, $col2, $title="", $crit=""){ 		
		
		$sq_more = "";
		//if($tbl == 'uawb_reg_cats') { $sq_more = " and system_cat = '0' "; }
		
		$result=$this->dbQuery("SELECT $col1, $col2 FROM $tbl where published = '1' $sq_more order by  $col2"); // //seq,
		$records = $this->recordCount($result);
		if($records >=1 )	
		{
			
			$i=0;
			
			while($qry_data = $this->fetchRow($result))
			{
				$isSelected	= "";					
				$rec_id 	= $qry_data[0];
				$labelid 	= $title."_".$qry_data[0];
				
				if(is_array($crit)){ if(in_array($rec_id, $crit)) { $isSelected = " checked"; }  }
				else
				{	if($crit <> "") {  if($fielditem == $crit) { $isSelected = " checked "; } } } 
				
				
				if($i==0) { $validate = " validate=\"required:true\" class=\"required\" "; } else { $validate = ""; }
				//
				echo "<label for=\"$labelid\" class=\"labelradio col-md-6\"><input type=\"checkbox\" name=\"".$title."[]\" id=\"$labelid\" value='$qry_data[0]'  $isSelected $validate >  $qry_data[1] &nbsp; </label>";
			$i += 1;
			}
			
		}	
	}	
	
	
	
	
	
/******************************************************************
@begin :: SELECT DROP DOWN
********************************************************************/		
	
	function dropper_sel_title($tbl, $col1, $col2, $crit = "", $crit2 = "") 
	{ 
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
				
		$line = "";
		
		$result=$this->dbQuery("SELECT $col1, $col2, `published` FROM $tbl where `published`=1 ".$crit2." order by $col2");
			
			//$line .= '<option value="" selected></option>';
			
			while($qry_data = $this->fetchRow($result))
			{
				if(strlen($qry_data[1])>=1)
				{
					$isSelected	= "";					
					$fielditem   = clean_output($qry_data[1]);
					
					if(is_array($crit)){
						if(in_array($fielditem, $crit)) { $isSelected = " selected";} 						
					}
					else
					{	if($crit <> "") { 
						 	if($fielditem == $crit) { $isSelected = " selected "; }
						 }
					} 
					
					//if($crit == $fielditem) { $isSelected = " selected "; } else { $isSelected = ""; }
					$line .= '<option value="'.$fielditem.'" '.$isSelected.'>'.$fielditem.'</option>'; // $selected
				}
			}
		return $line;
	}			
	
	
	
	function dropper_menuChildren($menu_arr, $crit = "")
	{ // dropper_select
		$out = "";
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		//if($multiple == 0) {
		$out = "<option value=''>- Select -</option>";
		//}
		
		if(is_array($menu_arr) && count($menu_arr)>0){
			
			$result = $this->dbQuery("SELECT `id`, `title` FROM `uawb_dt_menu` where `published`=1 and `id` IN (".implode(',',$menu_arr).") order by `title`;");
			
			while($qry_data = $this->fetchRow($result))
			{
				if(strlen($qry_data[1])>=1)
				{
					//$notpublished  = ($qry_data[2] == 1) ? "" : " class='op_notpublished' ";
					$selected      = "";
					if(is_array($crit)) { if(in_array($qry_data[0], $crit)) { $selected = " selected";}  }
					elseif($crit <> "") { if($qry_data[0] == $crit) { $selected=" selected "; } } 
					
                    $title_poa      = clean_output($qry_data[1]);
					$out .= "<option value='".$qry_data[0]."' ".$selected.">$title_poa</option>";
				}
			}
		}
		 
		return $out;
	}
	
	
	function dropper_select($tbl, $col1, $col2, $crit = "", $firstDefault = "Select", $multiple = 0, $ordercol = "")
	{ // dropper_select
		$out = "";
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		if($multiple == 0) {
		$out = "<option value=''>$firstDefault</option>";
		}
		if($crit == "" and $firstDefault <> "blank"){
		//$out = "<option value='' selected></option>";	//selected
		}
		
		$sqPublished = " where `published`= '1' ";
		
		$tblFilta = trim(substr($tbl,0,9)); //
		if($tblFilta == 'uawb_app_') { $sqPublished = ""; }
		
		if($ordercol == "") { $ordercol = $col2; }
		
		$result=$this->dbQuery("SELECT $col1, $col2, `published` FROM $tbl $sqPublished order by $ordercol");
			
			while($qry_data = $this->fetchRow($result))
			{
				if(strlen($qry_data[1])>=1)
				{
					$notpublished  = ($qry_data[2] == 1) ? "" : " class='op_notpublished' ";
					$selected      = "";
					if(is_array($crit)) { if(in_array($qry_data[0], $crit)) { $selected = " selected";}  }
					elseif($crit <> "") { if($qry_data[0] == $crit) { $selected=" selected "; } } 
					
                    $title_poa      = clean_output($qry_data[1]);
					$out .= "<option value='".$qry_data[0]."' ".$notpublished."  ".$selected.">$title_poa</option>";
				}
			}
			
			return $out;
	}											//dropper_select
	
	
	
	function dropper_conf($conf_type, $crit = "", $firstDefault = "Select", $show_cat = 1)
	{ 
		$out = ($firstDefault <> '') ? '<option value="">'.$firstDefault.'</option>' : '';
		
		$sq_crit = " where `published`= '1' and `choice_cat`= ".$this->quote_si($conf_type)." ";
		
		$result = $this->dbQuery("SELECT `choice_id`, `choice_item`, `choice_cat` FROM `uawb_conf_choices` $sq_crit ;");
			
			while($qry_data = $this->fetchRow($result))
			{
				if(strlen($qry_data[0])>=1)
				{
					$selected="";
					if(is_array($crit)) { if(in_array($qry_data[0], $crit)) { $selected = " selected";}  }
					elseif($crit <> "") { if($qry_data[0] == $crit) { $selected=" selected "; } } 
					
					$cat_parent  = ($show_cat == 1) ? '('.$qry_data['choice_cat'].') ' : '';
					
					$out .= '<option value="'.$qry_data['choice_id'].'" '.$selected.'>'.$cat_parent . $qry_data['choice_item'].'</option>';
				}
			}
			
			return $out;
	}
    
    
    function dropper_Tabs($cat = 11, $crit = "", $firstDefault = "Select")
	{ 
		$menuBunch = master::$menuBundle['type'][$cat];
        $out = '';
	
        foreach($menuBunch as $tag_parent)
        {
            $parent_title = master::$menuBundle['full'][$tag_parent]['title'];
            //$out .= '<option value="">'.$parent_title.'</option>';
            
			$selected="";
			if(is_array($crit)) { if(in_array($tag_parent, $crit)) { $selected = " selected";}  }
			elseif($crit <> "") { if($tag_parent == $crit) { $selected=" selected "; } } 
		
			$out .= '<option value="'.$tag_parent.'" '.$selected.'>'.$parent_title.'</option>'; 
			
            
        }
        
		return $out;
	}
	
		
    
    function dropper_confTags($menu_arr, $sel_array = "", $firstDefault = "Select")
	{ 
        $out = '';
	
        foreach($menu_arr['type'][10] as $tag_parent)
        {
            $parent_title = $menu_arr['full'][$tag_parent]['title'];
            //$out .= '<option value="">'.$parent_title.'</option>';
            
            if(array_key_exists($tag_parent, $menu_arr['child'])) 
            {
                foreach($menu_arr['child'][$tag_parent] as $tag_child) 
                {
					$isSel = "";
                    
                    if(is_array($sel_array)){
                        $optVal = $tag_child;
                        if(in_array($optVal, $sel_array)) { $isSel = " selected";} 					
                    }
                    
                    $child_title = $menu_arr['full'][$tag_child]['title'];
                    $out .= '<option value="'.$tag_child.'" '.$isSel.'>'.$child_title.'</option>'; /* ../ */
                }
            }
        }
        
        
            /*$out = "<option value=''>$firstDefault</option>";

            $sq_crit = " where `published`= '1' and `choice_cat`= ".$this->quote_si($conf_type)." ";

            $result = $this->dbQuery("SELECT `choice_item` FROM `uawb_conf_choices` $sq_crit ;");
			
			while($qry_data = $this->fetchRow($result))
			{
				if(strlen($qry_data[0])>=1)
				{
					$selected="";
					if(is_array($crit)) { if(in_array($qry_data[0], $crit)) { $selected = " selected";}  }
					elseif($crit <> "") { if($qry_data[0] == $crit) { $selected=" selected "; } } 
					
					$out .= "<option value='".$qry_data[0]."' ".$selected.">$qry_data[0]</option>";
				}
			}*/
			
			return $out;
	}
	
	
	function drop_sel($tb_key, $crit='', $label='') {
		$result = ''; $attr = array();
				
		switch($tb_key){
			case "jobgroup":   $attr = array("uawb_conf_jobgroup", "id_jobgroup", "jobgroup"); break;			
			case "bank_code":  $attr = array("uawb_conf_banks", "bank_code", "title");  break;			
			case "ward": 	   $attr = array("uawb_conf_wards", "id_ward", "ward");  break;
			case "member": 	   $attr = array("uawb_member", "id_member", "m_name");  break;
			case "committee":  $attr = array("uawb_committee", "id_committee", "title");  break;
			case "region":     $attr = array("uawb_conf_regions", "id_region", "region");  break;
		}		
		return $this->dropper_select($attr[0], $attr[1], $attr[2], $crit, $label);				
	}
	
	
	
	
	
	function dropper_type_detail($conf_type_id, $crit = "", $firstDefault = "Select")
	{ 
		$out = "<option value=''>$firstDefault</option>";
		
		$sq_crit = " where `published`= '1' and `conf_type_id`= ".$this->quote_si($conf_type_id)." ";
		
		$result = $this->dbQuery("SELECT `id_type`, `type_title` FROM `uawb_conf_types_detail` $sq_crit ;");
			
			while($qry_data = $this->fetchRow($result))
			{
				if(strlen($qry_data[1])>=1)
				{
					$selected="";
					if(is_array($crit)) { if(in_array($qry_data[0], $crit)) { $selected = " selected";}  }
					elseif($crit <> "") { if($qry_data[0] == $crit) { $selected=" selected "; } } 
					
					$out .= "<option value='".$qry_data[0]."' ".$selected.">$qry_data[1]</option>";
				}
			}
			
			return $out;
	}
	

/* ============================================================================================= */
/* DEFAULT SELECTORS
/* --------------------------------------------------------------------------------------------- */	

	
	function dropperResourceCats($crit="")
	{ 
		$out = "";
		
		/*$sq = "SELECT `content_type` FROM `uawb_dt_downloads` WHERE `published` = 1 and `content_type` <>'' GROUP by `content_type` ORDER by `content_type` ";*/
		
		$sq = "SELECT `download_type` FROM `uawb_dt_downloads_type`  WHERE `download_type` <> '';";
		
		$result = $this->dbQuery($sq);
			
			while($qry_data = $this->fetchRow($result))
			{
				
				if(strlen($qry_data[0])>=1)
				{
					$selected="";
					if(is_array($crit)){
						if(in_array($qry_data[0], $crit)) { $selected = " selected";} 						
					}
					elseif($crit <> "") { 
						if($qry_data[0] == $crit) { $selected=" selected "; }
					} 
				}
				
				$out .= "<option value='".$qry_data[0]."' ".$selected.">$qry_data[0]</option>";
			}
			
			return $out;
	}
	
	
	
	function dropperSection($crit="", $cat = "menu")
	{ 
		$out = "";
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		$sq_crit = " WHERE (`published` = 1 AND `section_cat` = 'all') or (`published` = 1 AND `section_cat` = ".quote_smart($cat).") ";
		
		$sq = "SELECT `id`, `title` FROM `uawb_dd_sections` $sq_crit ORDER BY `seq` ASC, `title` ASC ;";
		
		$result=$this->dbQuery($sq);
			
			while($qry_data = $this->fetchRow($result))
			{
				
				if(strlen($qry_data[1])>=1)
				{
					$selected="";
					if(is_array($crit)){
						if(in_array($qry_data[0], $crit)) { $selected = " selected";} 						
					}
					elseif($crit <> "") { 
						if($qry_data[0] == $crit) { $selected=" selected "; }
					} 
				}
				
				$out .= "<option value='".$qry_data[0]."' ".$selected.">$qry_data[1]</option>";
			}
			
			return $out;
	}
	
	function dropperCommittees($crit="", $cat = "menu")
	{ 
		$out = "";
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		$sq_crit = " WHERE (`published` = 1 AND `section_cat` = 'all') or (`published` = 1 AND `section_cat` = ".quote_smart($cat).") ";
		
		$sq = "SELECT `id`, `title` FROM `uawb_dd_sections` $sq_crit ORDER BY `seq` ASC, `title` ASC ;";
		
		$result=$this->dbQuery($sq);
			
			while($qry_data = $this->fetchRow($result))
			{
				
				if(strlen($qry_data[1])>=1)
				{
					$selected="";
					if(is_array($crit)){
						if(in_array($qry_data[0], $crit)) { $selected = " selected";} 						
					}
					elseif($crit <> "") { 
						if($qry_data[0] == $crit) { $selected=" selected "; }
					} 
				}
				
				$out .= "<option value='".$qry_data[0]."' ".$selected.">$qry_data[1]</option>";
			}
			
			return $out;
	}




/* ============================================================================================= */
/* POPULATORS -- PROJECT >>> LINKS
/* --------------------------------------------------------------------------------------------- */	
	
	function populateProjectLinks($link_type, $link_id, $sector_id, $project_id = '') 
	{
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		if($link_type <> '' and $link_id <> '' and $sector_id <> '' )
		{
			
			$sq_clean = " delete from `uawb_app_project_links` where `".trim($link_type)."` = ".quote_smart($link_id)."; ";
			$rs_clean = $this->dbQuery($sq_clean); 
		
			$sq_query = "INSERT INTO `uawb_app_project_links` (`sector_id`,`project_id`,`".trim($link_type)."` ) values (".quote_smart($sector_id).", ".quote_smart($project_id).", ".quote_smart($link_id)." );  ";
			$rs_query = $this->dbQuery($sq_query); 		 
		
		}
	}

	
	function getProjectLinks($link_type, $link_id) 
	{
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		$plinks = ''; 
		if($link_type <> '' and $link_id <> '')
		{
			$sq_query = "SELECT `sector_id` , `project_id`, `".trim($link_type)."` FROM `uawb_app_project_links` WHERE (`".trim($link_type)."` = ".quote_smart($link_id).")";
			$rs_query = $this->dbQuery($sq_query); 	
			if($this->recordCount($rs_query))
			{	$plinks = $this->fetchRow($rs_query, 'assoc'); }
		
		}
		return $plinks;
	}
	
	
	function getProjectParents($cat, $crit) 
	{
		$out = ''; $tb_name = ''; $tb_key = ''; $tb_col = '';
		if($cat == 'sectors') { $tb_name = 'uawb_app_sector'; $tb_key  = 'sector_id'; $tb_col = 'title'; }
		if($cat == 'pillars') { $tb_name = 'uawb_app_pillar'; $tb_key  = 'pillar_id'; $tb_col = 'title'; }
		if($cat <> '')
		{
			//$this->connect() or trigger_error('SQL', E_USER_ERROR);
			$rs_data= @$this->dbQuery("SELECT `".$tb_col."` FROM `".$tb_name."` WHERE `".$tb_key."`=".quote_smart($crit).";");
			if(@$this->recordCount($rs_data)){
				$cn_data = @$this->fetchRow($rs_data);
				$out = $cn_data[0];
			}
		}
	
		return $out;
	}
	
	
	function getProjectList($sector_id)
	{
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		$projectArray = array();
		
		if($sector_id <> '')
		{
			$sq_data = "SELECT `project_id`, `pname` as `project_name` FROM `uawb_app_project` WHERE (`published`=1 and `sector_id` = ".quote_smart($sector_id)."); ";
		//echo $sq_data;
			$rs_data = $this->dbQuery($sq_data);
			if($this->recordCount($rs_data)) 
			{
				while($cn_data = $this->fetchRow($rs_data))
				{
					$project_id  	= $cn_data[0];
					$project_name  = clean_output($cn_data[1]);
					$projectArray[$project_id] = $project_name;				
				}
			}	
		}
		return $projectArray;
	}


	function getProjectDetails($project_id)
	{
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		$projectArray = array();
		
		if($project_id <> '')
		{
			$sq_data = "SELECT
    `uawb_app_project`.*
    , `uawb_app_pillar`.`title` AS `pillar`
    , `uawb_app_sector`.`title` AS `sector`
    , `uawb_app_ministry`.`name` AS `ministry`
    , `uawb_app_location`.`name` AS `location`
FROM
    `uawb_app_project`
    LEFT JOIN `uawb_app_pillar` 
        ON (`uawb_app_project`.`pillar_id` = `uawb_app_pillar`.`pillar_id`)
    LEFT JOIN `uawb_app_sector` 
        ON (`uawb_app_project`.`sector_id` = `uawb_app_sector`.`sector_id`)
    LEFT JOIN `uawb_app_ministry` 
        ON (`uawb_app_project`.`ministry_id` = `uawb_app_ministry`.`ministry_id`)
    LEFT JOIN `uawb_app_location` 
        ON (`uawb_app_project`.`location_id` = `uawb_app_location`.`location_id`)
WHERE (`uawb_app_project`.`project_id` = ".quote_smart($project_id)." AND `uawb_app_project`.`published` =1); ";
		//echo $sq_data;
			$rs_data = $this->dbQuery($sq_data);
			if($this->recordCount($rs_data)) 
			{
				$projectArray = $this->fetchRow($rs_data, 'assoc');				
			}	
		}
		return $projectArray;
	}
	
	
	function getProjectComponents($project_id)
	{
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		$compArray = array();
		
		if($project_id <> '')
		{
			$sq_data = "SELECT
    `uawb_app_project_component`.`published`
    , `uawb_app_project_component`.*
    , `uawb_app_location`.`name` AS `location`
    , `uawb_app_location`.`lon` AS `longitude`
    , `uawb_app_location`.`lat` AS `latitude`
FROM
    `uawb_app_project_component`
    LEFT JOIN `uawb_app_location` 
        ON (`uawb_app_project_component`.`location_id` = `uawb_app_location`.`location_id`)
WHERE (`uawb_app_project_component`.`published` =1 AND `uawb_app_project_component`.`project_id` = ".quote_smart($project_id)."); ";
		//echo $sq_data;
			$rs_data = $this->dbQuery($sq_data);
			if($this->recordCount($rs_data)) 
			{
				while($cn_data = $this->fetchRow($rs_data, 'assoc'))
				{ $compArray[] = (object) array_map("clean_output",$cn_data); }				
			}	
		}
		return $compArray;
	}


	
	function getProjectGallery($project_id)
	{
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		$gallArray = array();
		
		if($project_id <> '')
		{
			//$sq_data = "SELECT `sector_id` , `project_id` , `id_gallery` FROM `uawb_app_project_links` WHERE (`project_id`  = ".quote_smart($project_id)." AND `id_gallery` <> 0); ";
			
			$sq_data = "SELECT `sector_id` , `project_id` , `id_content`, `id_gallery` FROM `uawb_app_project_links` WHERE (`project_id`  = ".quote_smart($project_id)."); ";
			$rs_data = $this->dbQuery($sq_data);
			if($this->recordCount($rs_data)) 
			{
				while($cn_data = $this->fetchRow($rs_data))
				{
					$id_content  = $cn_data['id_content']; 
					$id_gallery  = $cn_data['id_gallery']; 
					
					if($id_content <> 0) { $gallArray['cont'][] = $id_content; }
					if($id_gallery <> 0) { $gallArray['gall'][] = $id_gallery; }
					
					//$gallArray[]  = $cn_data[2];				
				}
			}	
		}
		return $gallArray;
		
	}




/* ============================================================================================= */
/* POPULATORS -- PORTALS
/* --------------------------------------------------------------------------------------------- */	
	
	function create_Portal($id_menu, $array_parent) 
	{
		$pdb_prefix = $GLOBALS['SYS_CONF']['DB_PREFIX'];
		$out = 0;
		//$sq_query = array();
		
		if(is_array($array_parent) and $id_menu <> '')
		{			
			$col_names = array(); $col_values = array();
			foreach($array_parent as $b_key => $b_val)  {
				
				$field = strtolower($b_key);				
				$col_names[] = "`$field`";	
				$col_values[] = "".q_si($b_val)."";			
			}
		
			$sq_query = "replace INTO `".$pdb_prefix."dt_portals` (".implode($col_names, ', ').") values (".implode($col_values, ', ')."); "; 
			
			//displayArray($sq_query); exit;
            $this->dbQuery($sq_query);	
			
		}
		//return $out;
	}	
	
    

/* ============================================================================================= */
/* POPULATORS -- GALLERY >>> PARENT
/* --------------------------------------------------------------------------------------------- */	
	
	function populateGalleryParent($id_photo, $array_parent, $parent_col = 'id_content') 
	{
		$record_stamp	 = time()*1000;
		$out      = 0;
		$sq_query = array();
		
		if(is_array($array_parent) and $id_photo <> '')
		{			
			foreach($array_parent as $kval) {  
                if($kval <> '') {
                    $sq_query[] = "insert IGNORE into `uawb_dt_gallery_photos_parent` (`id_photo`,`".$parent_col."`, `rec_stamp`) values (".q_si($id_photo).", ".q_si($kval).", ".q_si($record_stamp)."); ";
                }
			} 
		
            //displayArray($sq_query); exit;
            
            if(count($sq_query)) {
                $this->dbQueryMulti($sq_query);		
                unset($sq_query);
            }
			
		}
	}    

    

/* ============================================================================================= */
/* POPULATORS -- RESOURCE >>> PARENT
/* --------------------------------------------------------------------------------------------- */	
	
	function populateResourceParent($id_resource, $array_parent, $parent_col = 'id_content') 
	{
		
		$out      = 0;
		$sq_query = array();
		
		if(is_array($array_parent) and $id_resource <> '')
		{			
			foreach($array_parent as $kval) {  
                if($kval <> '') {
                    $sq_query[] = "insert IGNORE into `uawb_dt_downloads_parent` (`resource_id`,`".$parent_col."`) values (".q_si($id_resource).", ".q_si($kval)."); ";
                }
			} 
		
            //displayArray($sq_query); //exit;
            
            if(count($sq_query)) {
                $this->dbQueryMulti($sq_query);		
                unset($sq_query);
            }
			
		}
	}    


/* ============================================================================================= */
/* POPULATORS -- CONTENT >>> PARENT
/* --------------------------------------------------------------------------------------------- */	
	
	function populateContentParent($id_content, $array_parent, $parent_col = 'id_parent') 
	{
		
		$out = 0;
		$sq_query = array();
		
		if(is_array($array_parent) and $id_content <> '')
		{
			
			/*$sq_clean = " delete from `uawb_dt_content_parent` where `id_content` = ".quote_smart($id_content)."; ";
			$rs_clean = $this->dbQuery($sq_clean); */
		
			foreach($array_parent as $kval) {  
                if($kval <> '') {
                    $sq_query[] = "insert IGNORE into `uawb_dt_content_parent` (`id_content`,`".$parent_col."` ) values (".quote_smart($id_content).", ".quote_smart($kval)."); ";
                }
			} 
		
            //displayArray($sq_query); //exit;
            
            if(count($sq_query)) {
                $this->dbQueryMulti($sq_query);		
                unset($sq_query);
            }
		//$out = count($array_parent);
			
		}
		//return $out;
	}




/* ============================================================================================= */
/* POPULATORS -- KEYWORDS LOG
/* --------------------------------------------------------------------------------------------- */	
	
	function populateKeywords($parent_type, $parent_id, $array_keys) 
	{
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		$out = 0;
		$sq_query = array();
		
		if(is_array($array_keys) and $parent_type<>'' and $parent_id<>'')
		{
			
		$sq_clean = " delete from `uawb_log_keywords` where `parent_type`=".quote_smart($parent_type)." and `parent_id`=".quote_smart($parent_id)." ";
		$rs_clean = $this->dbQuery($sq_clean); 
		
			foreach($array_keys as $kval) 
			{  
				$sq_query = " insert IGNORE into `uawb_log_keywords` (`keyword`, `parent_type`, `parent_id` ) values "
				." (".quote_smart($kval).", ".quote_smart($parent_type).", ".quote_smart($parent_id).");  ";
				$rs_query = $this->dbQuery($sq_query); 
			} 
			
			$out = count($array_keys);
			//if(count($sq_query)>0){if($this->dbQuery( implode('',$sq_query) )) { $out = count($sq_query); }  }
			
		}
		return $out;
	}














/******************************************************************
@begin :: CA STATS CATS
********************************************************************/		
	
	function selectStatsCats($crit = '', $cat_parent = '', $cat_main = 1){ 
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		$out = '';
		$sq_more = "";
		
		if($cat_main == 1) { $sq_more = " and `child_cat` = '0' "; }
		if($cat_main == 0) { $sq_more = " and `child_cat` = '1' "; }
		//if($cat_parent == '_eqp') { $sq_more = " and `cat_equipment` = '1' "; }
		//if($cat_parent == '_crp') { $sq_more = " and `cat_crop` = '1' "; }
		
		$sq_qry = "SELECT `id`, `title` FROM `uawb_stats_cats` WHERE `published` = 1 " . $sq_more;
		
		$result = $this->dbQuery($sq_qry);
			
		$out .=  '<option value=""> </option>';
			while($qry_data = $this->fetchRow($result))
			{
				$selected="";
				if(is_array($crit)){
					if(in_array($qry_data[0], $crit)) { $selected = " selected";} 						
				}
				elseif($crit <> "") { 
					if($qry_data[0] == $crit) { $selected=" selected "; }
				} 
				
				$out .=  '<option value="'.$qry_data[0].'" '.$selected.'>'.$qry_data[1].'</option>';
			}
			
		return $out;
	}		
	

/******************************************************************
@begin :: CA DIRECTORY CATS DROP DOWN
********************************************************************/		
	
	function selectDirCategory($crit = '', $cat_parent = ''){ //, $cat_dir = '', $cat_equip = ''
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		$out = '';
		$sq_more = "";
		
		if($cat_parent == '_dir') { $sq_more = " and `uawb_reg_directory_category`.`cat_directory` = '1' "; }
		if($cat_parent == '_eqp') { $sq_more = " and `uawb_reg_directory_category`.`cat_equipment` = '1' "; }
		if($cat_parent == '_crp') { $sq_more = " and `uawb_reg_directory_category`.`cat_crop` = '1' "; }
		
		//$sq_qry = "SELECT `id`, `title`, `id_menu`, `cat_directory`, `cat_equipment`, `description`, `published`, `seq` FROM `uawb_reg_directory_category`  WHERE `published` = 1 " . $sq_more;
		
		$sq_qry = "SELECT `uawb_reg_directory_category`.`id`, `uawb_reg_directory_category`.`title`, `uawb_dt_menu`.`title` AS `menu` FROM `uawb_reg_directory_category` LEFT JOIN `uawb_dt_menu` ON (`uawb_reg_directory_category`.`id_menu` = `uawb_dt_menu`.`id`)  WHERE `uawb_reg_directory_category`.`published` = 1 " . $sq_more;
		
		$result=$this->dbQuery($sq_qry);
			
		$out .=  '<option value=""> </option>';
			while($qry_data = $this->fetchRow($result))
			{
				$parent="";
				if($qry_data[2] <> '') { $parent = "(". $qry_data[2] .") "; } 
				
				$selected="";
				if($qry_data[0] == $crit) { $selected=" selected "; } 
				$out .=  '<option value="'.$qry_data[0].'" '.$selected.'>'.$parent.$qry_data[1].'</option>';
			}
		return $out;
	}	
	
	
	
	
	
	
	function selectConfTypes($cat=1, $crit = "", $multiple = 0, $title = '')
	{ 
		$out = "";
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		if($multiple == 0) {
		$out = "<option value=''>$title</option>";
		}
		/*if($crit == "" and $firstDefault <> "blank"){
		//$out = "<option value='' selected></option>";	//selected
		}*/
		
		$sq = "SELECT `conf_data_id` , `conf_data_title` FROM `uawb_conf_types_data` WHERE (`published` =1 AND `conf_type_id` = ".quote_smart($cat).") ORDER BY `conf_data_title` ASC, `seq` ASC;";
		
		$result=$this->dbQuery($sq);
			
			while($qry_data = $this->fetchRow($result))
			{
				
				if(strlen($qry_data[1])>=1){
					$selected="";
					if(is_array($crit)){
						if(in_array($qry_data[0], $crit)) { $selected = " selected";} 						
					}
					elseif($crit <> "") { 
						if($qry_data[0] == $crit) { $selected=" selected "; }
					} 
				}
				
				$out .= "<option value='".$qry_data[0]."' ".$selected.">$qry_data[1]</option>";
			}
			
			return $out;
	}
	
	
	function selectDirRegion($crit = ""){ 
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		$result=$this->dbQuery("SELECT `ac_country` FROM `uawb_reg_directory` GROUP BY `ac_country`");
			
		echo "<option value=''>- Country -</option>";
			while($qry_data = $this->fetchRow($result))
			{
				$selected="";
				if($qry_data[0] == $crit) { $selected=" selected "; } 
				echo "<option value='$qry_data[0]' $selected>$qry_data[0]</option>";
			}
	}	
	
	
	
	
	
	
	/* ****************************************
	 @Automation  - GET /ADD IMAGES
	****************************************** */ 
	
	function getAddGallery($image_arr) {
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		//$ac_cat_id	= '';
			
		if(is_array($image_arr))
		{
			$ac_cat_clean = generate_seo_title($account_cat);
			$sq_check = "SELECT `id_category` FROM `uawb_reg_cats` WHERE `title` = ".quote_smart($account_cat)."; ";
			$rs_check = $this->dbQuery($sq_check);
		
			if($this->recordCount($rs_check)>=1)
			{ 
				$cn_check = $this->fetchRow($rs_check);
				$ac_cat_id = $cn_check[0];		
			}
			else
			{	
				$sqpost = "insert into `uawb_reg_cats` (`title`, `title_url`) values 
				(".quote_smart($account_cat)." ,
				".quote_smart($ac_cat_clean).") ";
			
				$result = $this->dbQuery($sqpost);
				$ac_cat_id = $this->insertId();				
			}
		
		}
		return $ac_cat_id;	
	}
	
	/* ****************************************
	 @Automation  - GET /ADD USER ACCOUNT
	****************************************** */ 
	
	function getAddUserAccount($account_email, $account_arr=array(), $mailing = 0) 
	{
		
		$auth_id	= '';
		$auth_code  = strtoupper(uniqid(time()));	
			
		if($account_email <> '')
		{
			//`uawb_reg_users`
			$sq_check = "SELECT `account_id`, `email` FROM `uawb_reg_account` WHERE (`email` = ".quote_smart($account_email).")";
			$rs_check = $this->dbQuery($sq_check);
		
			if($this->recordCount($rs_check)>=1)
			{ 
				$cn_check = $this->fetchRow($rs_check);
				$auth_id  = $cn_check[0];		
				
				if($mailing == 1) { $GLOBALS['EXISTS_MAILING_ACCOUNT'] = true; }
			}
			else
			{	
			
			
			/* ----------------------------------------- */	
				$field_title = "";
				$field_value = "";
			
				if(is_array($account_arr))
				{
					foreach($account_arr as $col=>$value)
					{	
						$field_title .= " `$col`, ";
						$field_value .= " ".quote_smart($value).", ";
					}
				}
			/* ----------------------------------------- */
			
			
				$sqpost = "insert ignore into `uawb_reg_account` ($field_title `email`, `ipaddress`, `published`) values 
				($field_value ".quote_smart($account_email).", ".quote_smart($_SERVER['REMOTE_ADDR']).", '0' ) ";				
				//echo $sqpost ; exit;
				$result = $this->dbQuery($sqpost);
				$auth_id = $this->insertId();				
			}
		
		}
		return $auth_id;	
	}
	
	
	
	
	/* ****************************************
	 @Automation  - GET /ADD USER ACTIVITIES / MODULES
	****************************************** */ 
	
	function getAddUserModule($user_id, $user_module) 
	{
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		$sqpost = "insert IGNORE INTO `afp_conf_person_modules` (`id_account`, `id_module`) VALUES (".quote_smart($user_id).", ".quote_smart($user_module)."); ";	
		$result = $this->dbQuery($sqpost); //
	}
	
	
	/* ****************************************
	 @Automation  - GET /ADD DOWNLOAD TYPES
	****************************************** */ 
	function getAddResourceType($type_name, $type_arr = array()) 
	{
		//$country_id  	   = $partner_arr['country_id'];
		$res_type_id	= '';
			
		if($type_name <> '')
		{
			$type_seo 	= $type_arr['res_type_seo']; //generate_seo_title($org_name, '-');	
			$sq_check 	= "SELECT `res_type_id` FROM `uawb_dt_downloads_type` WHERE `res_type_seo`=".q_si($type_seo).";";
			$rs_check 	= $this->dbQuery($sq_check); 
		
			if($this->recordCount($rs_check)>=1)
			{   $cn_check 	= $this->fetchRow($rs_check);
				$res_type_id 	= $cn_check['res_type_id'];		
			} 
			else 
			{	
				/* ----------------------------------------- */	
				$field_title = ""; $field_value = "";			
				if(is_array($type_arr))
				{   foreach($type_arr as $col=>$value)
					{   $field_title .= " `$col`, ";
						$field_value .= " ".q_si($value).", ";
					}
				}
				/* ----------------------------------------- */	
				
				$resource_id = 0;
				$sqpost = "insert into `uawb_dt_downloads_type` ($field_title `resource_id`) values 
				($field_value ".quote_smart($resource_id)." ) ";	//echobr($sqpost);			
				$result = $this->dbQuery($sqpost);	
				$res_type_id = $this->insertId();					
			}
		}
		
		return $res_type_id;	
	}
	
	
	/* ****************************************
	 @Automation  - GET /ADD ACCOUNT PARTNERS
	****************************************** */ 
	function getAddOrganization($org_name, $org_arr = array()) 
	{
		//$country_id  	   = $partner_arr['country_id'];
		$org_id	= '';
			
		if($org_name <> '')
		{
			$org_seo 	= $org_arr['organization_seo']; //generate_seo_title($org_name, '-');	
			$sq_check 	= "SELECT `organization_id` FROM `uawb_reg_organizations` WHERE `organization_seo`=".q_si($org_seo).";";
			$rs_check 	= $this->dbQuery($sq_check); 
		
			if($this->recordCount($rs_check)>=1)
			{   $cn_check 	= $this->fetchRow($rs_check);
				$org_id 	= $cn_check['organization_id'];		
			} 
			else 
			{	
				/* ----------------------------------------- */	
				$field_title = ""; $field_value = "";			
				if(is_array($org_arr))
				{   foreach($org_arr as $col=>$value)
					{   $field_title .= " `$col`, ";
						$field_value .= " ".q_si($value).", ";
					}
				}
				/* ----------------------------------------- */	
				
				$is_partner = 0;
				$sqpost = "insert into `uawb_reg_organizations` ($field_title `is_partner`) values 
				($field_value ".quote_smart($is_partner)." ) ";	//echobr($sqpost);			
				$result = $this->dbQuery($sqpost);	
				$org_id = $this->insertId();					
			}
		}
		
		return $org_id;	
	}
	
	
	
	
	
	
	/*function getAddUserOrganization($ac_domain, $contact_id = '') {
		
		$org_seo 	 = generate_seo_title($ac_domain, '-');	
		
		$ac_org_id	= '';
			
		if($ac_domain <> '')
		{
		$sq_check = "SELECT `organization_id` FROM `uawb_reg_organizations` WHERE `organization`=".q_si($ac_domain)." limit 1;"; 
		$rs_check = $this->dbQuery($sq_check);
		
			if($this->recordCount($rs_check) == 1 )
			{ 
				$cn_check = $this->fetchRow($rs_check);
				$ac_org_id = $cn_check['organization_id'];		
			}
			else
			{	
				$sqpost = "insert into `uawb_reg_organizations` (`organization`, `organization_seo`, `contact_id`, `published`) values 
				(".q_si($ac_domain)." , ".q_si($org_seo).", ".q_si($contact_id).", '1') ";
			
				$result = $this->dbQuery($sqpost);
				$ac_org_id = $this->insertId();				
			}
		
		}
		return $ac_org_id;	
	}*/
	
	
	
	
	/* ****************************************
	 @Automation  - GET /ADD USER CATEGORY
	****************************************** */ 
	
	function getAddUserCat($account_cat) {
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		$ac_cat_id	= '';
			
		if($account_cat <> '')
		{
			$ac_cat_clean = generate_seo_title($account_cat);
			$sq_check = "SELECT `id_category` FROM `uawb_reg_cats` WHERE `title` = ".quote_smart($account_cat)."; ";
			$rs_check = $this->dbQuery($sq_check);
		
			if($this->recordCount($rs_check)>=1)
			{ 
				$cn_check = $this->fetchRow($rs_check);
				$ac_cat_id = $cn_check[0];		
			}
			else
			{	
				$sqpost = "insert into `uawb_reg_cats` (`title`, `title_url`) values 
				(".quote_smart($account_cat)." ,
				".quote_smart($ac_cat_clean).") ";
			
				$result = $this->dbQuery($sqpost);
				$ac_cat_id = $this->insertId();				
			}
		
		}
		return $ac_cat_id;	
	}
	
	
	
	/* ****************************************
	 @Automation  - ADD USER TO CATEGORY
	****************************************** */ 
	
	function addUserToCategory($cat_id, $account_id, $pref_arr = array()) {
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		/* ----------------------------------------- */	
			$pref_title = ""; $pref_value = "";
			if(is_array($pref_arr)) {
				foreach($pref_arr as $col=>$value) 
				{ $pref_title .= " `$col`, "; $pref_value .= " ".quote_smart($value).", "; }
			}
		/* ----------------------------------------- */
		
		if($cat_id <> '' and $account_id <> '')
		{
			$sqpost = "insert into `uawb_reg_cats_links` ($pref_title `id_category`, `account_id`) values 
			($pref_value ".quote_smart($cat_id)." , ".quote_smart($account_id).") ";		
			$result = $this->dbQuery($sqpost);		
		}	
	}
	
	
	
	
	function selectUserCat($multi = "y", $crit = "") { 
		
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		
		$out = '';	
		$qry_links ="SELECT `id_category`, `title`, `published` FROM `uawb_reg_cats` WHERE  `published` =1 ORDER BY   `title` ASC ";
		
		$i = 0;
		$con_links2=$this->dbQuery($qry_links);
			
			while($res_links2 = $this->fetchRow($con_links2))
			{
				$st='';
				$link_id2	   = $res_links2['id_category'];
				$link_name2	 = html_entity_decode(stripslashes($res_links2['title']));				
				
				$selected = "";
				if(is_array($crit)){
					if(in_array($link_id2, $crit)) { $selected = " selected checked ";} 						
				}
				elseif($crit <> "") { 
					if($link_id2 == $crit) { $selected = " selected checked "; }
				} 
				
				if($multi == "y") 
				{
				$out .= '<label><input type="checkbox" name="user_cat[]" id="user_cat_'.$link_id2.'" '.$selected.' value="'.$link_id2.'" />&nbsp; '.$link_name2.' </label>';
				}
				else
				{
				$out .= '<option value="'.$link_id2.'" '.$selected.'>'.$link_name2.'</option>';
				}
				
			}
			
		return $out;
	}
	
	
	/* ****************************************
	 @Select Country
	****************************************** */ 
	
	function selectCountry($crit) {
		$country = '';
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		$rs_data= $this->dbQuery("SELECT `id`, `country` FROM `uawb_reg_countries` WHERE `id`=".quote_smart($crit)." or `iso_code_2`=".quote_smart($crit)."  or `iso_code_1`=".quote_smart($crit)." "); //
		if($this->recordCount($rs_data) ==1 ){
			$cn_data = $this->fetchRow($rs_data);
			$country = $cn_data[1];
		}
	
		return $country;
	}
	
	
	
	/* ****************************************
	 @GET MARKET PLACE ITEM OWNER
	****************************************** */ 
	
	function getMarketItemOwner($id) { 
	
		$account = "";
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
				
		$rs_data=$this->dbQuery("SELECT concat_ws(' ',`firstname`, `lastname`) as `name`, `email` FROM `afp_conf_person_list` WHERE (`id` = ".quote_smart($id).") ");
		if($this->recordCount($rs_data) ==1 ){
			$account = $this->fetchRow($rs_data, 'assoc');
		}
			
		return $account;			
	}	
	
	
	
	
	/* ****************************************
	 @Dropper Select - ProfileCats
	****************************************** */ 
	
	function select_directoryCatsMenu($sel_array = "") { 
	
		$out = '';
		
		$arr_directoryCatsMenu 		= master::$menuBundle['dircat']; 
		
		asort($arr_directoryCatsMenu);
		
		foreach ($arr_directoryCatsMenu  as $key => $value) 						
		{
			$selCrit  = trim($value);
			$selected = "";
			
			if(is_array($sel_array)){
				if(in_array($selCrit, $sel_array)) { $selected = " selected";} 						
			}
			elseif($sel_array <> "") { 
				if($selCrit == $sel_array) { $selected=" selected "; }
			}
			
			$out .= '<option value="'.$value.'" '.$selected.'>'.$value.'</option>';				
		}
		
		return $out;			
	}	
	
	
	
	
	/* ****************************************
	 @Dropper Select - PROFILES
	****************************************** */ 
	
	function selectProfiles($sel_array = ""){ 
	
		$out = "<option value=''> - Select -</option>";
		
		$arr_Profiles 		= master::$listProfiles; 		
		asort($arr_Profiles);
		
		foreach ($arr_Profiles  as $key=>$value) 						
		{	
			if(is_array($sel_array)){ if(in_array($key, $sel_array)) { $selected = " selected";} else { $selected = ""; } }
			$title = $value['title'];	
			//if($key == $crit) { $selected=" selected "; } else {$selected="";}
			$out .= "<option value='".$key."' ".$selected.">$title</option>";				
		}
		
		return $out;			
	}	
	
	
	
	
	
	/* ****************************************
	 @Dropper Select - COMMITTEES
	****************************************** */ 
	
	function selectDownloads($crit = "") { 
	
		$out = "";
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		$out .= "<option value='' selected>- Any - </option>";
		
		$sqList = "SELECT
    `uawb_dt_downloads`.`id`
    , `uawb_dt_downloads`.`title`
    , `uawb_dt_downloads`.`link`
    , `uawb_dt_downloads`.`published`
FROM
    `uawb_dt_downloads` WHERE `uawb_dt_downloads`.`published` = 1
ORDER BY `uawb_dt_downloads`.`title`;";
		
		$result=$this->dbQuery($sqList);
			
		while($qry_data = $this->fetchRow($result))
		{
			if(strlen($qry_data[1])>=1){
				if($qry_data[0] == $crit) { $selected=" selected "; } else {$selected="";}
				$out .= "<option value='".$qry_data[0]."' ".$selected.">$qry_data[1]</option>";
			}
		}
			
		return $out;			
	}	
	
	
	/* ****************************************
	 @Dropper Select - THEMATIC AREAS
	****************************************** */ 
	
	function selectThematicAreas($crit) { 
	
		$out = "";
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		$out .= "<option value='' selected>- Any - </option>";
				
		$result=$this->dbQuery("SELECT `id`, `title` FROM `uawb_dt_menu` WHERE `published`= 1 and `id_section` = 7  order by `title`; ");
			
		while($qry_data = $this->fetchRow($result))
		{
			if(strlen($qry_data[1])>=1){
				if($qry_data[0] == $crit) { $selected=" selected "; } else {$selected="";}
				$out .= "<option value='".$qry_data[0]."' ".$selected.">$qry_data[1]</option>";
			}
		}
			
		return $out;			
	}	
	
	
	/* ****************************************
	 @Dropper Select - THEMATIC TABS
	****************************************** */ 
	
	function selectThemeTabs($crit){ 
	
		$out = "";
		//$this->connect() or trigger_error('SQL', E_USER_ERROR);
		$out .= "<option value='' selected> </option>";
				
		$result=$this->dbQuery("SELECT `id`, `title` FROM `uawb_dt_menu` WHERE `published`= 1 and `id_type_menu` = 7  order by `seq`; ");
			
		while($qry_data = $this->fetchRow($result))
		{
			if(strlen($qry_data[1])>=1){
				if($qry_data[0] == $crit) { $selected=" selected "; } else {$selected="";}
				$out .= "<option value='".$qry_data[0]."' ".$selected.">$qry_data[1]</option>";
			}
		}
			
		return $out;			
	}		
	
	

/*-----------------------------------------------------------------------------------*/
/*	CONFIGS::  Directory Contacts
/*-----------------------------------------------------------------------------------*/	
	
	function getDirectoryContact($supplier_id) 
	{	
		$dirContact = array();
		
		$rs_qry = $this->dbQuery("SELECT `ac_organization`, `ac_email`,  `ac_contact_name`, `ac_contact_email` FROM `uawb_reg_directory` WHERE `id` = ".quote_smart($supplier_id)."; ");
		if($this->recordCount($rs_qry)) 
		{	
			$cn_qry = $this->fetchRow($rs_qry);
			$supplier_name  = clean_output($cn_qry['ac_organization']);
			$supplier_email  = clean_output($cn_qry['ac_email']);
			$supplier_contact_email  = clean_output($cn_qry['ac_contact_email']);
			if($supplier_email == '') { $supplier_email = $supplier_contact_email;}
			
			$dirContact['name'] = $supplier_name;
			$dirContact['email'] = $supplier_email;
			
			/*$dirContact[$cn_qry['id']] = array(
					'name'  => ''.$supplier_name.'',
					'email' => ''.$supplier_email.''
				);*/
		}
		return $dirContact;
	}	
	
	
	
/*-----------------------------------------------------------------------------------*/
/*	CONFIGS::  PORTALS
/*-----------------------------------------------------------------------------------*/	
	
	function getPortal_Select($target = "front", $port_select = "") {
		
		if($target == "single" and $port_select <> '')
		{
			
			$cn_data = $this->dbQueryFetch('SELECT `portal_id`, `portal_base`, `portal_title`, `portal_desc`, `portal_path`, `portal_logo` FROM `uawb_dt_portals` WHERE `published`=1 and `portal_id` = '.q_si($port_select).' ;');
			$rs_data = current($cn_data);
			
			return $rs_data;
		}
		elseif($target == "front" or $target == "admin")
		{
			$qry_crit = " and `portal_id` > 0 ";
			/*if($target == "admin")
			{ $qry_crit = " and `portal_link_only`= 0 "; }*/

			$out = "<option value='' selected>Select Portal</option>";

			$rs_qry = $this->dbQuery('SELECT `portal_base`, `portal_title`, `portal_link_only`, `portal_path`, `portal_id` FROM `uawb_dt_portals` WHERE `published`=1 '.$qry_crit.'  order by `portal_title` ');

			while($qry_data = $this->fetchRow($rs_qry))
			{
				$portal_id = $qry_data['portal_id'];
				$portal_title = $qry_data['portal_title'];
				$portal_path = $qry_data['portal_path'];

				if($target == "front")
				{

					if($qry_data[2]==0){
						$out .= "<option value='".$portal_id."'>$portal_title</option>";
					}
					elseif($qry_data[2]==1)
					{
						$out .= "<option value='".$portal_path."'>$portal_title</option>";
					}
				}
				elseif($target == "admin")
				{
					if($portal_id == $port_select) { $selected=" selected "; } else { $selected=""; }
					$out .= "<option value='".$portal_id."' ".$selected.">$portal_title</option>";
				}
			}

			return $out;
		}
	}
	
	
	
	
	
}

$ddSelect=new drop_downs;
?>
