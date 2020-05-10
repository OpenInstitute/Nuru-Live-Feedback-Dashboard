<?php 
require_once 'classes/cls.constants.php'; 
// Check if request for country is saved, if so pass the parameter to country
$nchi = '';

// Declare output
$out = '';


if(!empty($_GET['country'])){
	$nchi = '%'.$_GET['country'].'%';
}else{
	$nchi = '%';
}

if($nchi == 'All'){
	$nchi = '%';
}
// echo $nchi; exit;


$tags_main = array("basic food items availability","basic food items cost","discrimination","disturbance","restriction of movement","suspected covid-19 case","water availability","water cost", "social distancing", "no health care workers", "social distancing", "shortage of masks", "observance of measures to curb Covid-19 virus");


//$sq_qry = "SELECT `tag_name`, COUNT(`tag_item_id`) as `tag_items` FROM `ort_posts_tags` GROUP BY `tag_name` order by  `tag_name`  ";
$sq_qry = "SELECT `ort_posts_tags`.`tag_name` , COUNT(`ort_posts_tags`.`tag_record_id`) AS `tag_items` FROM `ort_posts_tags` INNER JOIN `ort_posts` ON (`ort_posts_tags`.`tag_item_id` = `ort_posts`.`post_entry_id`) WHERE (`ort_posts`.`published` ='1') and (`ort_posts_tags`.`tag_published` ='1') and (`ort_posts`.`post_country` LIKE '$nchi') GROUP BY `ort_posts_tags`.`tag_name` ORDER BY `ort_posts_tags`.`tag_name` ASC; "; 

// echobr($sq_qry); 

$rs_qry = $cndb->dbQuery($sq_qry);

$k 		= null;

$filta_tots = array(); 


$filta_form = array(); 
$filta_cols = array(); 
$filta_cols_dat = array(); 

if($cndb->recordCount($rs_qry) > 0) {
	$i = 0;
	while($cn_qry_a = $cndb->fetchRow($rs_qry)){

		$cn_qry  	= array_map("clean_output", $cn_qry_a);	

		$tag_name		= $cn_qry['tag_name'];
		$tag_items		= $cn_qry['tag_items'];  

		/*$tag_rec		= '<li class="linegraydotx padd5x"><span class="nom">  '.clean_title($tag_name, 1).' </span> <span class="num_box label label-info">'.$tag_items.'</span></li>';*/
		/*<input type="checkbox" class="nom_chk" id="chk_'.$i.'" >*/
		$tag_rec		= '<label class="lebo_filta"> <span class="nom"> '.clean_title($tag_name, 1).' </span> <span class="num_box labelX label-infoX">'.$tag_items.'</span></label>';

		if(in_array($tag_name, $tags_main)){
			$filta_form['main'][]	= $tag_rec;
		} else {
			$filta_form['others'][]	= $tag_rec;
		}
		/*$filta_form[]	= '<div class="row"><span class="bold">  '.$tag_name.' </span> <span class="num_box">'.$tag_items.'</span></div>';*/
		 $i++;
	}
	
}
 
// create the filters
$filter_main = '';
$filter_other = '';

if(!empty($filta_form['main'])){
    $filter_main =  implode(' ', $filta_form['main']);
}else{
    $filter_main =  "No post tags from this country";
}

if(!empty($filta_form['others'])){
    $filter_other =  implode(' ', $filta_form['others']);
}else{
    $filter_other =  "No post tags from this country";
}
 


$out .= '
<form id="frm_search" name="frm_search">
			<input type="hidden" class="gg_checks" name="data_form" value="indoor" />
		
<div class="col-md-12" id="filtaz" style="position:relative"> 
	
	<div class="accordion-wrap">
		<div class="accordion-box" id="acc_6">
			<div class="panel panel-default">
				<div class="panel-heading"><a href="#faq-one" data-toggle="collapse" class="panel-title accord-title"><h4><i class="fas fa-tagx fa-plus"></i>&nbsp; Main Tags <span class="filterhint">Click for more</span></h4>   </a></div>
				<div class="panel-collapse collapse in" id="faq-one">
					<div class="nano" style="height:250px">
                        <div class="nano-content">';
                        $out .=  $filter_main;
                        $out .= '</div>
					</div>
				</div>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading"><a href="#faq-two" data-toggle="collapse" class="panel-title accord-title"><h4> <i class="fas fa-tagsx fa-plus"></i>&nbsp; Other Tags <span class="filterhint">Click for more</span></h4> </a></div>
				<div class="panel-collapse collapse in" id="faq-two">
					<div class="nano" style="height:250px">
						<div class="nano-content">';
						 
                        $out .= $filter_other;

					$out .='</div>
					</div> 
				</div>
			</div>
			
		</div>
	</div> 
	
</div>
</form>'; 

echo $out;
      		
?>