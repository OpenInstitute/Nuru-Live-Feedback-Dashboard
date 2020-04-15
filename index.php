<?php 
require_once 'classes/cls.constants.php'; 
//include_once 'z_head.php'; 

$col_keys = array();
$col_keys['indoor'] = array(
	
);
?>


<html lang="en-US" xmlns="//www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="shortcut icon" type="image/x-icon" href="assets/image/logo_nuru.png">
	<title>Nuru.live Mapping</title>
	<!-- <link rel="manifest" href="manifest.json"> -->

	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-title" content="Nuru.live">
	<link rel="apple-touch-icon" href="assets/image/logo_nuru.png">

	<meta name="description" content="Nuru.live">
	<meta name="theme-color" content="#F5F5F5" />

	<!--<script src="https://www.gstatic.com/firebasejs/5.11.0/firebase-app.js"></script>

	<script src="https://www.gstatic.com/firebasejs/5.11.0/firebase-auth.js"></script>
	<script src="https://www.gstatic.com/firebasejs/5.11.0/firebase-database.js"></script>
	<script src="https://www.gstatic.com/firebasejs/5.11.0/firebase-messaging.js"></script>-->
	<!-- End PWA -->

	<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">-->
	<link rel="stylesheet" href="assets/js/bootstrap/css/bootstrap.3.3.7.min.css" type="text/css">
	<link rel="stylesheet" href="assets/js/bootstrap/css/bootstrap-override.css" type="text/css">
	<script src="https://code.jquery.com/jquery-3.4.0.min.js" integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg=" crossorigin="anonymous"></script>
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
	<link rel="stylesheet" type="text/css" href="assets/css/base_overrides.css?v=1.0.3">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />
	<link rel='stylesheet' id='mfn-fonts-css' href='https://fonts.googleapis.com/css?family=Roboto%3A1%2C300%2C400%2C400italic%2C500%2C700%2C700italic%7CLora%3A1%2C300%2C400%2C400italic%2C500%2C700%2C700italic&#038;ver=5.3.2' type='text/css' media='all' />

	<!-- text rotator css -->
	<link rel="stylesheet" href="assets/css/simpletextrotator.css" />

	<!-- Nano scroller css -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.nanoscroller/0.8.7/css/nanoscroller.css" integrity="sha256-7TSx6Ck89PYIn7aHChJ+u8MCr45+JcBVbKJ8ADoAQ+Y=" crossorigin="anonymous" />

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
	</style>
</head>

<body style="max-width:1600px; margin:auto;">


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
				<div class="nuru-intro">
					<div class="col-md-6">
						<h3 class="mapTitle">Nuru Map</h3>
					</div>
					<div class="col-md-offset-3 col-md-3 extras hide">
						<span class="viewTable"><i class="fas fa-table"></i> View as table &middot; </span> <span class="expandViews"><i class="fas fa-expand-arrows-alt"></i> Expand</span>
					</div>
				</div>

				<div id="gg_data_result" style=""></div>
				<div id="map" style="height: 610px; border: 1px solid #AAA;"></div>
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

	<!-- Nano script -->
	<script>
		$(".nano").nanoScroller({
			scroll: 'top'
		});
	</script>


	<script type="text/javascript">
		var map = L.map('map', {
			center: [-1.2967913, 36.8598615],
			minZoom: 0,
			zoom: 11,
			maxZoom: 80,
			// gestureHandling: true
			scrollWheelZoom: false
		});

		// Prevent map from zooming on mouse move
		map.on('focus', function() {
			if (map.scrollWheelZoom.enabled()) {
				map.scrollWheelZoom.disable();
			} else {
				map.scrollWheelZoom.enable();
			}
		});

		// Add MarkerClusters - Kevin
		function getRandomLatLng(map) {
			var bounds = map.getBounds(),
				southWest = bounds.getSouthWest(),
				northEast = bounds.getNorthEast(),
				lngSpan = northEast.lng - southWest.lng,
				latSpan = northEast.lat - southWest.lat;

			return new L.LatLng(
				southWest.lat + latSpan * Math.random(),
				southWest.lng + lngSpan * Math.random());
		}

		var markers = L.markerClusterGroup();
		// markers.addLayer(L.marker(getRandomLatLng(map)));
		// ... Add more layers ...
		// map.addLayer(markers);

		var mopt = {
			url: '//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
			options: {
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
				subdomains: ['a', 'b', 'c'],
				id: 'mapbox.light'
			}
		};

		var mq = L.tileLayer(mopt.url, mopt.options);
		mq.addTo(map);

		var layer_main = L.layerGroup();
		var layer_streets = L.layerGroup();


		// Define polyline options
		// http://leafletjs.com/reference.html#polyline
		var polyline_options = {
			color: '#9F5CCB',
			weight: 7,
			opacity: 0.8
		};


		let map_data;

		var layer_postsMarkersList = [];
		let postsMarkersObject = {};


		function getColor(d) {
			return d > 80 ? 'violet' :
				d > 60 ? 'green' :
				d > 40 ? 'yellow' :
				d > 20 ? 'orange' :
				'red';
		}


		function AddMarkerToMap(ma_point, ma_layer, ma_color = 'grey') {

			//console.log(ma_point);
			//var ma_color = (ma_type === 'indoor') ? 'blue' : 'green';


			var my_coord = '' + ma_point.lat + ', ' + ma_point.lng + '';
			var my_photo_link = (ma_point.photo !== '') ? '' + ma_point.photo_original + '' : '';
			var my_photo = (my_photo_link !== '') ? '<hr class="pop_line"><a href="' + my_photo_link + '" target="_blank" class="pop_photo"><img src="' + my_photo_link + '" style="width:50px;" /></a>' : '';
			var my_comments = (ma_point.comments !== '') ? '<b>Details:</b> ' + ma_point.comments + '' : '';
			var my_icon = '';
			var my_link = ' data-href="maps/nuru_point.php?id=' + ma_point.id + '" id="marka_' + ma_point.id + '" title="View Point Details" rel="modal:open"';

			var my_popup = '<div><b class="pop_title"> ' + ma_point.name + '</b><br /><b>Entry Tags:</b> ' + ma_point.tags + '<br /><b>From:</b> ' + ma_point.post_by + '<br />' + my_comments + '  ' + my_photo + ' </div>';


			var greenIcon = new L.Icon({
				iconUrl: 'assets/image/marker-icon-green.png',
				iconSize: [25, 41],
				iconAnchor: [12, 41],
				popupAnchor: [1, -34]
				/*,
							  shadowSize: [41, 41]*/
			});
			// Li.circleMarker 

			/* @@ Rage -- Add Title, riseOnHover, markerID, classname */
			var newMarker = new L.marker([parseFloat(ma_point.lat), parseFloat(ma_point.lng)], {
					icon: greenIcon,
					title: ma_point.name,
					riseOnHover: 1,
					markerID: ma_point.id,
					className: 'marker_' + ma_point.id
				})
				.bindPopup(my_popup).on('click', clickZoom);
			 /*.on('click', clickZoom)*/

			/* @@ Rage -- Add Markers to markersArray */
			postsMarkersObject['marker_' + ma_point.id] = newMarker;
			layer_postsMarkersList.push(newMarker);
			ma_layer.addLayer(newMarker);

			/*newMarker._popup.setLatLng(map.getBounds().getCenter());*/
			 
			/* @@ Rage -- Add layer to map */
			return map.addLayer(ma_layer);
		}


		/* @@ Rage -- POST CLICK IN-MAP POPUP */
		function clickZoom(e) {
			map.setView(e.target.getLatLng(),11);
		}
		function openPopupCustom(marker_id) {
			jQuery(document).ready(function($) {
				postsMarkersObject["" + marker_id + ""].openPopup();
				jQuery("#map").animate({
					/*body,html*/
					scrollTop: 0
				}, 800);
			});
		}

		/*map.flyTo(new L.LatLng(lat, lng), zoom, {
									duration: 1.5
								});*/



		function rageMappa(de_file, de_layer, de_color) {

			jQuery(document).ready(function($) {

				$.ajax({
					type: 'get',
					url: de_file,
					dataType: 'json',
					success: function(data) {

						var mapsData = data; /*//JSON.parse(data);*/
						var markers_a = mapsData.features;
						map_data = mapsData.table;

						for (var i = 0; i < markers_a.length; i++) {
							var ma_point = markers_a[i].properties;
							var m_color = getColor(ma_point['perc_access']);

							AddMarkerToMap(ma_point, de_layer, m_color);
						}

						rageTable(map_data, "Posts List", 1);

					}
				});
			});

		}

		rageMappa('maps/nuru_json.php', layer_main, 'indoor');


		function rageTable(tbl_data, tbl_title, $level) {

			jQuery(document).ready(function($) {
				var table_res = $.makeTable(tbl_data, tbl_title, $level);
				$("#box_res_table").html('');
				$(table_res).appendTo("#box_res_table");
				zul_DataTable();
			});
		}


		// Display the results as comments - Kevin 30th Mar 2020
		var data = '';

		function comments(data) {
			 
			let e = (typeof(data) !== 'undefined') ? data : '';
			let link = "maps/nuru_json.php?tag=" + e;

			jQuery(document).ready(function($) {
				$.ajax({
					type: 'get',
					url: link,
					dataType: 'json',

					success: function(output) {
						// console.log(output);
						let data = output.features;
						let len = data.length;
						let content = '';

						let lat = '';
						let lng = '';

						for (var c = 0; c < len; c++) {
							let record_id = data[c].properties.id;
							/*let comment = data[c].properties.comments;*/
							let comment = data[c].properties.comments_trim;
							var comment_full = data[c].properties.comments;
							let author = data[c].properties.post_by;
							let time = data[c].properties.name;

							let lat = data[c].properties.lat;
							let lng = data[c].properties.lng;
							let tags = data[c].properties.tags;

							let photo = data[c].properties.photo;
							let photo_original = data[c].properties.photo_original;

							// alert(photo.length);
							var img = '';
							var imgx = '';

							if (photo.length !== 0) {
								/*img = '<img src="'+ photo +'" alt="Image from '+ author +'" style="width: 90%; margin: auto">';*/
								img = '<a class="comment-img" href="' + photo_original + '" target="_blank"> <img src="' + photo + '" alt="Image from ' + author + '" width="50" height="50"></a>';
							}

							var findOnMap = '';

							//console.log("lat", lat.length + " - " + data[c].properties.lat + " - long: " + data[c].properties.lng);

							if (lat != "0") {
								/* href="javascript:void(0);"*/
								/* data-href="posts.php?lat=' + lat + '&lng=' + lng + '&name=latlong"*/
								findOnMap = '&middot <a class="feed_markers" lat="'+lat+'" lng="'+lng+'" data-id="marker_' + record_id + '" ><em> <strong><i class="far fa-dot-circle"></i> Find on map</strong></em></a>';
							}

							/* <hr/> <p>'+ img +'</p> */
							content += '<article class="comment">' + img + '<div class="comment-body"> <div class="text"><p>' + comment + '<br/><a class="more"> More Details</a> </p>  </div> <p class="attribution"> <i class="fas fa-user-alt"></i> <a href="#non">' + author + '</a> &middot; <span class="date"><i class="far fa-clock"></i> ' + time + '</span> ' + findOnMap + '</a></p> <p class="padd10_t txt12"> <smallx><em><strong>Tags: </strong><span class="tagisi"> ' + tags + '</span></em></smallx> </p></div> <div class="hide full_comment">'+ comment_full +'</div> </article>';
						}

						// console.log(data);
						$('section.comments').html(content + '<p>&nbsp;</p><p>&nbsp;</p>');

						// alert(data);
						// alert(data.features.0.properties.name);

					}
				});
			});

		}

		//setInterval(comments(data), 100);

		// Pan to selected coordinate




		jQuery(document).ready(function($) {
			
			comments("");
			jQuery(document).on('click', '.feed_markers', function(e) {
				var marker_id = jQuery(this).attr("data-id");
				openPopupCustom(marker_id);
			});
 
		});



		// Kevin Update - Change the way content is revealed on the map side on click
		jQuery(document).ready(function($) {
			$(document).on('click', '.comment', function(data){
				// alert($(this).('.attribution a'));
				// alert('comment clicked');
				// var this = $(this);

				let op = $(this).find('.attribution a').first().text(); //feedback from original poster
				let message = $(this).find('.full_comment').html();
				// let message = comment_full;
				let date = $(this).find('.date').html();
				let tags = $(this).find('.tagisi').text();

				let img = $(this).find('.comment-img').attr('href');
				// alert(img);
				let image = '';
				if(img !== undefined){
					image = '<div class="imageDetail"><img src="'+ img +'" /> </div>';
				};

				let lat = $(this).find('.feed_markers').attr('lat');
				let lng = $(this).find('.feed_markers').attr('lng');

				

				let content = '';
				content += '<div class="container feedback nano">';
				content += '<div class="nano-content">';
				content += '<div> <a class="back"> <i class="fas fa-long-arrow-alt-left"></i> Back to Map</a></div>';
				content += '<h4>'+op+' wrote: </h4>';
				content += '<div class="message"><p>' + message + '</p></div> <hr/>';
				content += image;
				content += '<div class="metadata">';
				content += '<span class="date"><strong>Date Posted: </strong>' + date + '</span><br/>';
				content += '<span class="tags"><strong>Tags: </strong>' + tags + '</span>';
				content += '</div> <hr/>';
				content += '<h5> Location </h5>';
				content += '<div id="custom-map"></div> <hr/>';
				content += '</div'; //close nano content
				content += '</div'; //close container

				$('.mapTitle').text('Feedback from ' + op);
				$('#map').css('background', '#ffffff');
				$('#map').html(content);

				// Add this to map
				var customMap = L.map('custom-map').setView([lat, lng], 13);

				L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
				}).addTo(customMap);

				var markerIcon = new L.Icon({
				iconUrl: 'assets/image/marker-icon-green.png',
				iconSize: [25, 41],
				iconAnchor: [12, 41],
				popupAnchor: [1, -34]
				
				});

				L.marker([lat, lng], {
					icon: markerIcon,
					title: op,
					riseOnHover: 1
				}).addTo(customMap).bindPopup(op + '\'s location').openPopup();



				// Get me out of here
				$('.back').click(function(){
					location.reload();
				});

				// Change comment background color
				$(this).find('.text').css('background-color', 'rgba(57, 181, 74, .3)');
				
			});

		});
		// End of on click to change content display



		/* @@Rage --- function to remove element from object */
		function arrayRemove(arr, value) {
			return arr.filter(function(ele) {
				return ele != value;
			});
		}

		/* @@Rage -- Tag click function */
		var tags = [""];
		var opts = [];

		$('.nom').on('click', function(x) {

			$(this).toggleClass('selFilter');

			var nom_val = $(this).text().trim();  
			if ($(this).hasClass("selFilter")) {
				//$(this).parent().addClass("selFilter");
				opts.push(nom_val);
			} else {
				//$(this).parent().removeClass("selFilter");
				var resul = arrayRemove(opts, nom_val);
				opts = resul;
			}

			/*console.log("nom_value", opts);*/

			let res = JSON.stringify(opts);
			//let results = comments(btoa(res)); /* base63_encode the string */
			//setInterval(results, 100);
			
			comments(btoa(res));
		});
	</script>



	<script language="JavaScript" type="text/javascript">
		jQuery(document).ready(function($) {

			$('.panel-heading span.clickable').each(function() {
				var $this = $(this);
				if ($this.hasClass('panel-collapsed')) {
					$this.parents('.panel').find('.panel-body').slideUp();
					$this.find('i').removeClass('glyphicon-minus').addClass('glyphicon-plus');
				}
			});

			jQuery(document).on('click', '.panel-heading span.clickable', function(e) {
				var $this = $(this);
				if (!$this.hasClass('panel-collapsed')) {
					$this.parents('.panel').find('.panel-body').slideUp();
					$this.addClass('panel-collapsed');
					$this.find('i').removeClass('glyphicon-minus').addClass('glyphicon-plus');
				} else {
					$this.parents('.panel').find('.panel-body').slideDown();
					$this.removeClass('panel-collapsed');
					$this.find('i').removeClass('glyphicon-plus').addClass('glyphicon-minus');
				}
			})

			/*$(document).on('change', '.gg_checks', function(e) {
				gg_data_search(layer_main, 'indoor');
			});*/

		});

		function gg_data_search(de_layer, de_color) {
			jQuery(document).ready(function($) {
				//alert($('#frm_search').serialize());
				//map.remove(de_layer);
				de_layer.clearLayers();
				/*map.eachLayer(function (layer) { console.log("layer", layer);
					//map.removeLayer(layer)
				}); */

				$(".gg_checks").each(function() {
					var label = $(this).parent();
					if ($(this).prop('checked')) {
						label.css('color', 'red');
					} else {
						label.css('color', '#777777');
					}
				});

				$.ajax({
					/*url: 'map_ajdata.php?tk='+Math.random(),*/
					url: 'maps/nuru_json.php?tk=' + Math.random(),
					type: 'get',
					dataType: 'json',
					data: $('#frm_search').serialize(),
					beforeSend: function() {
						$('#gg_data_result').html('loading <img src="assets/images/icons/a-loader.gif" alt="..."  />');
					},
					success: function(data) {

						$('#gg_data_result').html(data);

						var mapsData = data; //JSON.parse(data);

						var markers_a = mapsData.features;

						for (var i = 0; i < markers_a.length; i++) {
							var ma_point = markers_a[i].properties;
							var m_color = getColor(ma_point['perc_access']);
							AddMarkerToMap(ma_point, de_layer, m_color);
						}

					}
				});
			});
		}


		function kbModalLoaded() {}



		function zul_DataTable() {
			jQuery(document).ready(function($) {

				//alert("Ninii " + groupTotals);

				/*//DATA TABLE*/
				$.fn.dataTable.moment('MMM D YYYY');
				$.fn.dataTable.moment('YYYY-MMMM');
				$.fn.dataTable.moment('YYYY-MMM');
				$.fn.dataTable.moment('YYYY-MMM-DD');

				var tb_grouped = $('#gg_data_tb').length;

				if ($('table.display').length) {

					var col_filter_tag = (jQuery('#dt_example').length && jQuery('#dt_example').attr('data-col-filter') !== undefined) ? jQuery('#dt_example').attr('data-col-filter') : "";
					var col_filter = (col_filter_tag !== "") ? col_filter_tag.split(",") : "";

					var col_total_tag = (jQuery('#dt_example').length && jQuery('#dt_example').attr('data-col-total') !== undefined) ? jQuery('#dt_example').attr('data-col-total') : "";
					var col_total = (col_total_tag !== "") ? col_total_tag.split(",") : "";

					dta_table = jQuery('table.display').dataTable({
						"bProcessing": true,
						destroy: true,
						"bJQueryUI": true,
						"bInfo": true,
						"sPaginationType": "full_numbers",
						"bStateSave": false
							/*, "columnDefs": [{"targets" : 'no-sort', "orderable": false }, { "render": function ( data, type, full, meta ){ return display_decimal(data) }, "targets" : 'ddt' }]*/
							,
						"aaSorting": [],
						"iDisplayLength": 10,
						"aLengthMenu": [
							[5, 10, 25, 50, 100, -1],
							[5, 10, 25, 50, 100, "All"]
						],
						"scrollX": true,
						dom: 'Blfrtip',
						buttons: ['print', 'csvHtml5'],
						initComplete: function() {
							var num_cols = this.api().columns().nodes().length;

							if (col_filter !== "") {
								this.api().columns().every(function(tb_col) {
									var column = this;
									var col_id = col_filter.includes(tb_col.toString());

									if (col_id === true) {
										var select = $('<select><option value=""></option></select>')
											.appendTo($(column.footer()) /*.empty()*/ )
											.on('change', function() {
												var val = $.fn.dataTable.util.escapeRegex($(this).val());
												column.search(val ? '^' + val + '$' : '', true, false).draw();
											});

										column.data().unique().sort().each(function(d, j) {
											select.append('<option value="' + d + '">' + d + '</option>')
										});
									}
								});
							}


						},
						drawCallback: function() {
							var api = this.api();
							if (col_total !== "") {
								this.api().columns().every(function(tb_col) {
									var column = this;
									var col_id = col_total.includes(tb_col.toString());

									if (col_id === true) {
										var col_sum = api.column(tb_col.toString(), {
											page: 'current'
										}).data().sum();
										$(column.footer()).html(display_decimal(col_sum));
										/*$( api.table().footer() ).html(
											api.column( tb_col.toString(), {page:'current'} ).data().sum()
										  );*/
									}
								});
							}
						}
					});

				}



				if ($('#check_all').length) {
					$('#check_all').on("change", function() {
						if ($(this).is(':checked')) {
							$('.dtb_chk').each(function() {
								$(this).attr("checked", true);
							});
						} else {
							$('.dtb_chk').each(function() {
								$(this).attr("checked", false);
							});
						}
					});
				}

			});
		}
	</script>




</body>

</html>