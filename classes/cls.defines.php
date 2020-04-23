<?php
	//require_once('cls.locate.php');
	

	$request = array_map("clean_request", $_GET);
	//displayArray($request);
	$com_us	= '';
	if(isset($request['us'])) { 
		$com_us = substr($request['us'], 0, strpos($request['us'], '-'));
		$com_us_name = trim(substr($request['us'], strpos($request['us'], '-')+1, 100));
		$request['com_us'] = $com_us;
		$request['com_us_name'] = $com_us_name;
	}
	 
	$dir = (isset($_REQUEST['d'])) ? clean_request(strtolower($_REQUEST['d'])) : 'user_posts';  
	$ipp = (isset($_REQUEST['ipp'])) ? clean_request($_REQUEST['ipp']) : 10;  

	 
	if(isset($_REQUEST['com'])) { $com=clean_request($_REQUEST['com']);}  else {$com = NULL;}
	//if(isset($_REQUEST['com']) and is_numeric($_REQUEST['com'])) { $com=$_REQUEST['com'];} else { $com=1; }
	if(isset($_REQUEST['com2']) and is_numeric($_REQUEST['com2'])) {$com2=$_REQUEST['com2'];} else {$com2=NULL;}
	if(isset($_REQUEST['com3']) and is_numeric($_REQUEST['com3'])) {$com3=$_REQUEST['com3'];} else {$com3=NULL;}
	if(isset($_REQUEST['com4']) and is_numeric($_REQUEST['com4'])) {$com4=$_REQUEST['com4'];} else {$com4=NULL;}
	if(isset($_REQUEST['item']) and is_numeric($_REQUEST['item'])) {$item=$_REQUEST['item'];} else {$item=NULL;}
	 
	if(isset($_REQUEST['fcall'])) {$fcall=clean_request($_REQUEST['fcall']);} else {$fcall= NULL;}
	if(isset($_REQUEST['fc'])) {$fc=clean_request($_REQUEST['fc']);} else {$fc= NULL;}
	
	if(isset($_REQUEST['page']) and is_numeric($_REQUEST['page'])) {$page=$_REQUEST['page'];} else {$page=1;}	
	if(isset($_REQUEST['qst']) and is_numeric($_REQUEST['qst']))	{$qst=$_REQUEST['qst'];} else {$qst=NULL;}
	if(isset($_REQUEST['gal']) and is_numeric($_REQUEST['gal'])) {$gal=$_REQUEST['gal'];} else {$gal=NULL;}
	if(isset($_REQUEST['id'])) {$id=$_REQUEST['id'];} else {$id=NULL;}
	if(isset($_REQUEST['parent']) and is_numeric($_REQUEST['parent'])) {$parent=$_REQUEST['parent'];} else {$parent=NULL;}
	 
	if(isset($_REQUEST['call'])) {$call=clean_request($_REQUEST['call']);} else {$call='';}
	
	if(isset($_REQUEST['ptab'])) {$ptab=clean_request($_REQUEST['ptab']);} else { 
		$ptab= (!empty($_SESSION['sess_ort_front']['member']) and $_SESSION['sess_ort_front']['member']['u_organization_id']>0) ? 'dashboard' : 'dashboard';
	}
	if(isset($_REQUEST['ureg'])) {$ureg = clean_request($_GET['ureg']);} else { $ureg= NULL;}
	if(isset($_REQUEST['uac'])) {$uac = clean_request($_GET['uac']);} else { $uac= NULL;}
	if(isset($_REQUEST['op'])) { $op=clean_request($_REQUEST['op']); } else { $op='list'; }
	if(isset($_REQUEST['dc'])) { $dc=clean_request($_REQUEST['dc']); } else { $dc=''; }
	
	if(isset($_REQUEST['pay'])) { $pay=clean_request($_REQUEST['pay']); } else { $pay=NULL; }

  	
	$token = time();
	
	$com_base_arr['com'] = @$com;
	
	 if($com2)		{ $com_base_arr['com2'] = $com2; }
	 if($com3)		{ $com_base_arr['com3'] = $com3; }
	 if($com4)		{ $com_base_arr['com4'] = $com4; }
	 if($item)		{ $com_base_arr['item'] = $item; }
	 
	
	$com_base = '?'; $com_baseb = ''; 
	foreach ($com_base_arr as $key => $value) 
	{			//echo $key.' - ';
		if($key <> 'item'){
		$com_base .= $key .'='. $value .'&'; 
		}
		//echo $com_base.' - ';
	}
	
	define('RDR_REF_BASE', $com_base);
	//define('RDR_REF_BASE', "?com=".$com."&com2=".$com2."&com3=".$com3."&com4=".$com4);
	//define('RDR_REF_PAGE', $this_page);
	//define('RDR_REF_PATH', $this_page."?".$_SERVER['QUERY_STRING']);
	define('RDR_REF_SIDE', "?".$_SERVER['QUERY_STRING']);
	

			

?>