
<?php 

$tags_main = array("basic food items availability","basic food items cost","discrimination","disturbance","restriction of movement","suspected covid-19 case","water availability","water cost", "social distancing", "no health care workers", "social distancing", "shortage of masks", "observance of measures to curb Covid-19 virus");


$sq_qry = "SELECT `tag_name`, COUNT(`tag_item_id`) as `tag_items` FROM `ort_posts_tags` GROUP BY `tag_name` order by  `tag_name`  ";

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
	
	$tag_rec		= '<li class="linegraydot padd5"><span class="nom">  '.clean_title($tag_name, 1).' </span> <span class="num_box label label-info">'.$tag_items.'</span></li>';
	
	if(in_array($tag_name, $tags_main)){
		$filta_form['main'][]	= $tag_rec;
	} else {
		$filta_form['others'][]	= $tag_rec;
	}
	/*$filta_form[]	= '<div class="row"><span class="bold">  '.$tag_name.' </span> <span class="num_box">'.$tag_items.'</span></div>';*/
	 
	}

}
 
 
?>
<form id="frm_search" name="frm_search">
			<input type="hidden" class="gg_checks" name="data_form" value="indoor" />
		
<div class="col-md-12" style="position:relative">
	<div class="col-md-4 col-xs-12">
		<div class="card mt-3 tab-card">
			<h3>Welcome to Nuru Dashboard</h3>
			<p> Filter by tags or go directly to the map</p>
			<p>This map displays results based on feedback from the nuru app</p>
			<p>On the right side of the map, a timeline view of the feedback is displayed realtime</p>
			<p class="hide"><strong>Remember to do the 5:</strong>  <i class="fas fa-hands-wash"></i> <i class="fas fa-head-side-cough"></i> <i class="fas fa-biohazard"></i> <i class="fas fa-people-arrows"></i> <i class="fas fa-house-user"></i> </p>
			<p class="hide"><span class="rotate">  Wash hands, Cough into elbow, Don't touch your face, Keep a safe distance, , Stay home </span></p>
		</div> 
	</div>	

	<div class="col-md-4 col-xs-12">
		<div class="card mt-3 tab-card">
			&nbsp;
			<h4 class=" border_bottom_gray"><i class="fas fa-tag"></i>  FILTER: Main Tags </h4>
			<!-- <form>
				<div class="form-control custom-checkbox">
					<input type="checkbox" name="selecta" class="custom-control-input" id="selecta">
					<label class="custom-control-label" for="defaultUnchecked"><em>Click to select multiple tags</em></label>
				</div>
			</form> -->
			<div>&nbsp;</div>
			<?php echo implode(' ', $filta_form['main']); ?>
		</div> <br/>
		<!-- <span><small><e><strong>Hint:</strong> Click on checkbox to select multiple tags</e></small></span> -->
	</div>	

	<div class="col-md-4 col-xs-12">
		<div class="card mt-3 tab-card">
			&nbsp;
			<h4 class="border_bottom_gray"> <i class="fas fa-tags"></i> FILTER: Other Tags</h4>
			<?php echo implode(' ', $filta_form['others']); ?> 	
		</div> 
	</div>	

	

	
</div>
      	
      		
</form>      				
      		
