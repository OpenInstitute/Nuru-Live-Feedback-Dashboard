<?php
require_once('../classes/cls.constants.php'); 

$sel_ops 	= array_map("clean_request", $_REQUEST);

$ccrit 		= array();
$fData 		= array();
$filta_form 		= array();

/*if(!empty($sel_ops['bw_'])){	
	foreach($sel_ops['bw_'] as $kcol => $vvals){		
		$ccrit[] = " `questionnaire`.`".$kcol."` IN (". implode(',', q_in($vvals)) .") ";			
	}
}*/
if(!empty($sel_ops['dbg'])){}
	//displayArray($sel_ops);
	//displayArray($ccrit);
	//displayArray($_SESSION['_ablt_']['indoor_fields']);



$checks_indoor 		= $_SESSION['_ablt_']['indoor_checks']['indoor'];
$checks_indoor_num = count($checks_indoor);

$mod_title = 'No data to show';


//$sq_crit = (count($ccrit) > 0) ? ' and '. implode(' and ', $ccrit) : '';


if(!empty($sel_ops['id']))
{
	
	$filta_form = $_SESSION['_ablt_']['indoor_fields'];
	
	$qry = "SELECT
		`questionnaire`.`id`
		, `questionnaire`.`type`
		, `questionnaire`.`entity_code`
		, `buildings`.`area`
		, `buildings`.`building_name`
		, `buildings`.`sub_building`
		, `streets`.`road`
		, `questionnaire`.`building_type`
		, `questionnaire`.*
	FROM
		`questionnaire`
		LEFT JOIN `buildings` ON (`questionnaire`.`entity_code` = `buildings`.`entity_code`)
		LEFT JOIN `streets` ON (`questionnaire`.`street` = `streets`.`street_code`)
	WHERE `questionnaire`.`id` = ".q_si($sel_ops['id'])."
	;";

	if(!empty($sel_ops['dbg'])){
		echobr($qry);
	}

	$res 		= $cndb->dbQueryFetch($qry);

	$k 			= null;
	$map_data 	= array();

	$fData		= $res[0];


	$mod_title  = ''. $fData['building_name'] .''; //.$fData['area']; 
	
	
	$item_check_vals = array();
	foreach($checks_indoor as $qque => $qval){
		$item_check_vals[$qque] = ( strtolower($fData[$qque]) == $qval ) ? 1 : 0 ;			
	}

	$perc_access = intval((array_sum($item_check_vals) / $checks_indoor_num) * 100);
	
	


echo modHeader($mod_title);
?>

	<div style="max-height:400px; overflow:none; overflow-x:scroll">
	<?php 


			$f_out = array();
			foreach($filta_form['indoor'] as $cat_form => $flds_form){

				//displayArray($flds_form);
				$p_title = clean_title($cat_form);
				//$p_fields = implode(' ', $val_form);

				$p_fields	= '';

				foreach($flds_form as $f_col => $f_lebo){
					$p_val 		= $fData[$f_col];
					$p_fields	.= '<div class="row"><div class="col-md-7 bold">'.$f_lebo.':</div><div class="col-md-5">'.$p_val.'</div></div>';
				}

				$f_out[] = '<div class="panel panel-default">
						<div class="panel-heading">
							<h5 class="panel-title txt18">'.$p_title.'</h5>
						</div>
						<div class="panel-body padd5">				
							<div>
							'.$p_fields.'
							</div>				
						</div>
					</div>';
			}

			echo '<div class="row"><div class="col-md-7 bold">Address:</div><div class="col-md-5">'.$fData['area'].', '.$fData['street'].'</div></div>';
			echo '<div class="row"><div class="col-md-7 bold">Entry Type:</div><div class="col-md-5">'.$fData['building_type'].'</div></div>';
			echo '<div class="row"><div class="col-md-7 bold">Entry Code:</div><div class="col-md-5">'.$fData['entity_code'].'</div></div>';
			echo '<div class="row"><div class="col-md-7 bold">Accessibility Ranking:</div><div class="col-md-5 bold">'.$perc_access.'%</div></div>';
			echo '<br>';

			echo implode(' ', $f_out);

			if($fData['comments'] <> ''){
				echo '<div class="panel panel-default">
							<div class="panel-heading"><h5 class="panel-title txt18 bold">Comments</h5></div>
							<div class="panel-body padd10"> <div>'.$fData['comments'].'</div></div>
						</div>';
			}


		//displayArray($fData); 
		?>
		<!--data hapa-->
	</div>

<?php
echo modFooter();
}
?>