<?php 
require_once 'classes/cls.constants.php'; 
//include_once 'z_head.php'; 

$col_keys = array();
$col_keys['indoor'] = array();

?>


<html lang="en-US" xmlns="//www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="shortcut icon" type="image/x-icon" href="assets/image/logo_nuru.png">
	<title>Nuru.live Map Dashboard</title>
	<!-- <link rel="manifest" href="manifest.json"> -->

	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-title" content="Nuru.live">
	<link rel="apple-touch-icon" href="assets/image/logo_nuru.png">

	<meta name="description" content="Nuru - Kiswahili for “Light “ (nuru.live) – is a reporting platform that allows citizen community monitors around the world to make observations about social, political, economic and human rights issues around them.">
	<meta name="theme-color" content="#F5F5F5" />

	<!--<script src="https://www.gstatic.com/firebasejs/5.11.0/firebase-app.js"></script>

	<script src="https://www.gstatic.com/firebasejs/5.11.0/firebase-auth.js"></script>
	<script src="https://www.gstatic.com/firebasejs/5.11.0/firebase-database.js"></script>
	<script src="https://www.gstatic.com/firebasejs/5.11.0/firebase-messaging.js"></script>-->
	<!-- End PWA -->

	<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">-->
	<link rel="stylesheet" href="assets/js/bootstrap/css/bootstrap.3.3.7.min.css" type="text/css">
	<link rel="stylesheet" href="assets/js/bootstrap/css/bootstrap-override.css" type="text/css">
	<script src="//code.jquery.com/jquery-3.4.0.min.js" integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg=" crossorigin="anonymous"></script>
	<!-- Mapbox dependencies -->
	<script src='https://api.mapbox.com/mapbox-gl-js/v0.54.0/mapbox-gl.js'></script>
	<link href='https://api.mapbox.com/mapbox-gl-js/v0.54.0/mapbox-gl.css' rel='stylesheet' />
	<!-- Mapbox dependencies -->

	<link rel="stylesheet" type="text/css" href="assets/js/leaflet/leaflet-0.7.7.css" />

	<!-- Add leaflet markercluster -->
	<link rel="stylesheet" type="text/css" href="assets/mC/MarkerCluster.css" />

	<script type='text/javascript' src='assets/js/leaflet/leaflet-0.7.7.js'></script>
	<script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js" integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og==" crossorigin=""></script>

	<script src="assets/js/leaflet/OSM.js"></script>
	<script src="assets/js/leaflet/KML.js"></script>
	<script src="assets/js/leaflet/osmtogeojson.js"></script>

	<!-- Markercluster script -->
	<script src="assets/mC/leaflet.markercluster-src.js"></script>
	<script src="assets/mC/leaflet.markercluster.js"></script>

	<!-- Gesturehandling -->
	<link rel="stylesheet" href="//unpkg.com/leaflet-gesture-handling/dist/leaflet-gesture-handling.min.css" type="text/css">

	<!--<script src='http://tyrasd.github.io/osmtogeojson/osmtogeojson.js'></script>-->

	<link rel="stylesheet" type="text/css" href="assets/js/modal/jquery.modal.css">
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />
	<link rel='stylesheet' id='mfn-fonts-css' href='https://fonts.googleapis.com/css?family=Roboto%3A1%2C300%2C400%2C400italic%2C500%2C700%2C700italic%7CLora%3A1%2C300%2C400%2C400italic%2C500%2C700%2C700italic&#038;ver=5.3.2' type='text/css' media='all' />

	<!-- text rotator css -->
	<link rel="stylesheet" href="assets/css/simpletextrotator.css" />

	<!-- Nano scroller css -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.nanoscroller/0.8.7/css/nanoscroller.css" integrity="sha256-7TSx6Ck89PYIn7aHChJ+u8MCr45+JcBVbKJ8ADoAQ+Y=" crossorigin="anonymous" />
<link rel="stylesheet" type="text/css" href="assets/css/base_overrides.css?v=1.0.22">
	<style type="text/css">
		html,
		body {
			font-family: "Roboto", "Arial", "Sans-serif";
		}

		.pop_title {
			font-size: 15px;
			color: darkcyan;
		}

		/* @@ Rage -- Popup image styler */
		.pop_photo {
			display: inline-block;
			width: 52;
			height: auto;
			max-height: 50px;
			overflow: hidden;
			border: 1px solid #ddd;
		}

		.feed_markers {
			cursor: pointer;
		}

		.map-legend-keys img {
			width: 14px;
		}

		.map-legend-keys div {
			padding: 4px;
		}

		hr.pop_line {
			margin-top: 10px;
			margin-bottom: 10px;
			border-top-width: 2px;
		}
		
		.wrap_imageDetail { display: block !important; position: relative; clear: both !important; height: auto; }
		.wrap_imageDetail:after { content: ''; clear: both !important;}
.imageDetail_b { position: relative;width: 220px;height: 200px; display: inline-block; /*float: left !important;*/ overflow: hidden; margin: 5px; }
.imageDetail_b img { width: 100%; height: auto; background: #f1f3f4; border-radius: 5px; border:1px solid #ddd;  }
.imageDetail_b figure { width: 100%; height: 200px; background: #f1f3f4; border-radius: 5px; border:1px solid #ddd; }
.imageDetail_b audio { width: 200px; height: 120px; }
		.row.metadata { margin-top: 20px !important; }
		.row { clear: both !important; margin-bottom: 20px;}
	</style>
	
</head>

<body style="">

	<div class="">
	<!-- Intro -->
	<div class="row clearfix">
		<div class="col-md-12 nopadd top-bar">
			<!-- we add search button -->
			<div class="search-console">
				<?php include("maps/map_header.php"); ?>
			</div>


		</div>
	</div>
	<!-- Intro -->

	<div class="row clearfix">
		<div class="col-md-12 mapfeed">

			<div class="col-md-2 map_filters">
				<?php include("map_filter_side.php"); ?>
				 
			</div>
			
			<div class="col-md-3 nopadd comments">

				<div class="nuru-intro2">
					<h3>User Feedback</h3>
				</div>
				<div class="comments-container nano">
					<div class="nano-content">
						<section class="comments"></section>
						
					</div>
				</div>
			</div>

			<div class="col-md-7 map">
				<div class="nuru-intro" id="mapCol">
					<div class="col-md-6">
						<h3 class="mapTitle">Nuru Map </h3>
					</div>
					<div class="col-md-offset-3 col-md-3 extras hideX padd10_t txtright txtwhite" id="feedbackDetailBack">
						<!--<span class="viewTable"><i class="fas fa-table"></i> View as table &middot; </span> <span class="expandViews"><i class="fas fa-expand-arrows-alt"></i> Expand</span>-->
					</div>
				</div>

				<div id="gg_data_result" style=""></div>
				<div id="map" style="height: 610px; border: 1px solid #AAA;"></div>
				<div id="feedbackDetail" style="height: 610px; border: 1px solid #AAA; display:none;"></div>
			</div>

			

		</div>
	</div>

	<div class="row clearfix">
		&nbsp;
	</div>

	<div class="col-md-12 bottomline hide">
		<p><strong>Remember to do the 5:</strong> <i class="fas fa-hands-wash"></i> <i class="fas fa-head-side-cough"></i> <i class="fas fa-biohazard"></i> <i class="fas fa-people-arrows"></i> <i class="fas fa-house-user"></i> </p>
		<p><span class="rotate"> Wash hands, Cough into elbow, Don't touch your face, Keep a safe distance, , Stay home </span></p>
	</div>

	<div class="row clearfix hide">
		<div class="col-md-12">
			<div class="col-md-3"></div>
			<div class="col-md-9">
				<h3 class="bold ">Nuru.live feedback Data</h3>
			</div>
		</div>
		<div class="col-md-12">&nbsp;</div>
		<div class="col-md-12">
			<div id="box_res_table"></div>
		</div>
	</div>

	</div>

	<script type="text/javascript" src="assets/js/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/js/misc/jquery.slidetoggle.js"></script>
	<script type="text/javascript" src="assets/js/modal/jquery.modal.js" charset="utf-8"></script>
	<div class="modal fade" style="display:none;"></div>

	<script type="text/javascript" src="assets/js/oi_custom_table.js" charset="utf-8"></script>
	<!-- DataTables -->
	<link type="text/css" rel="stylesheet" href="assets/js/datatable/jquery.dataTables.css" />
	<link type="text/css" rel="stylesheet" href="assets/js/datatable/jquery.dataTables.override.css" />
	<script type="text/javascript" src="assets/js/datatable/jquery.dataTables-1.10.19.min.js"></script>
	<script type="text/javascript" src="assets/js/datatable/jquery.dataTables.colfilter.js"></script>
	<script type="text/javascript" src="assets/js/datatable/dataTables.rowGroup.min.js"></script>
	<script type="text/javascript" src="assets/js/datatable/dataTables.colReorder.min.js"></script>
	<script type="text/javascript" src="assets/js/datatable/nested.tables.min.js"></script>
	<script type='text/javascript' src="assets/js/datatable/moment.js"></script>
	<script type="text/javascript" src="assets/js/datatable/datetime-moment.js"></script>


	<link rel="stylesheet" type="text/css" href="assets/js/datatable/buttons-1.10.13/dataTables.buttons.min.css">
	<script type="text/javascript" src="assets/js/datatable/buttons-1.10.13/dataTables.buttons.min.js"></script>

	<script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
	<script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
	<script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
	<script type="text/javascript" language="javascript" src="assets/js/datatable/buttons-1.10.13/buttons.html5.min.js"></script>
	<script type="text/javascript" language="javascript" src="assets/js/datatable/buttons-1.10.13/buttons.colVis.min.js"></script>
	<script type="text/javascript" language="javascript" src="assets/js/datatable/buttons-1.10.13/buttons.print.min.js"></script>

	<!-- Nanoscroller -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nanoscroller/0.8.7/javascripts/jquery.nanoscroller.js" integrity="sha256-6As7QJOnBHo1fLCugEQD0nlUTG5LFMgo+PHtxv62GfU=" crossorigin="anonymous"></script>
	
	<!-- All scripts -->
	<script src="scripts.js"></script>



	<!-- Text rotator -->

	<script type="text/javascript" language="javascript" src="assets/js/jquery.simple-text-rotator.js"></script>

	<script>
		$(".rotate").textrotator({
			animation: "dissolve", // You can pick the way it animates when rotating through words. Options are dissolve (default), fade, flip, flipUp, flipCube, flipCubeUp and spin.
			separator: ",", // If you don't want commas to be the separator, you can define a new separator (|, &, * etc.) by yourself using this field.
			speed: 2000 // How many milliseconds until the next word show.
		});
	</script>
	<!-- Text rotator -->
</body>

</html>
