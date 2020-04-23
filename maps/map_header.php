<div class="col-md-12" style="position:relative">

	<div class="col-md-4 col-xs-12">
		<div class="card mt-3 tab-card">
			<h3>Welcome to Nuru Dashboard</h3> 			
		</div> 
		<?php
		
		$countQry = "SELECT DISTINCT post_country FROM `ort_posts` where post_country is not null and post_country <> '' AND published='1' ";
		$countRes = $cndb->dbQuery($countQry);
		$i = '';

		if($cndb->recordCount($countRes) > 0) {
			
			$i .= '<div class="form-group">
			<label for="exampleFormControlSelect1">You are viewing posts from <span class="selCountry">Kenya</span>.<br/> Select a country from the dropdown to view posts from the country <br/> As more people submit posts, the countries will appear on the drop down.</label>
			<select class="form-control" id="countryFormControlSelect1">
				<option value="0">Select Country</option>' ;
			while($getCountry = $cndb->fetchRow($countRes, 'assoc')){
				
			$i .= '<option value="'.$getCountry["post_country"].'">'.$getCountry["post_country"].'</option>';
								 
				}
			$i .= '</select>
			</div>';

			echo $i;
			
			}
		
		?>
		
	</div>	

	<div class="col-md-8 col-xs-12 map-areaX">
		<div class="card mt-3 tab-card map-text">
		
			 <div class="">
			 	<p>This map displays results based on feedback from the nuru app. Filter by tags or go directly to the map</p>
				 <p>Zoom in on the map to see all the points</p>
			 </div>
			
		</div>  
	</div>	

	<!--<div class="col-md-4 col-xs-12">
		<div class="card mt-3 tab-card">
			 <p>On the right side of the map, a timeline view of the feedback is displayed realtime</p>
		</div> 
	</div>	-->

	

	
</div> 