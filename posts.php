<?php 
require_once 'classes/cls.constants.php'; 

$lat = '';
$lng = '';

$name = '';

if ( !(empty($_GET['name'])) ){
    $name = $_GET['name'];
}

if(!(empty($_GET['lat'])) ){
    $lat = $_GET['lat'];
}

if(!(empty($_GET['lng'])) ){
    $lng = $_GET['lng'];
}

if($name == "latlong"){
    $coord = array("lat" => $lat, "lng" =>$lng );
    $dta = json_encode($coord);

    echo $dta;
}




?>