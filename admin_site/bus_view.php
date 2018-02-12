<?php
/**
 * Created by PhpStorm.
 * User: CARLTON MOSETI
 * Date: 03/02/2018
 * Time: 13:25
 */

if(empty($_POST)){
    header('Location: superuser.php');
}


include('html/Page.inc.php');
include('php/Database.inc.php');


$db = new Database();
$sql="SELECT * FROM `tbl_bus_trips` WHERE `bus_id`=".$_POST['bus_id'].";";
$bus_trips = $db->select($sql);

$sql="SELECT plate FROM `tbl_buses` WHERE `id`=".$_POST['bus_id'].";";
$bus_plate = $db->select($sql)[0];

$page = new Page('bus_view_content.php');

if(isset($_POST['trip_id'])){
    $sql="SELECT * FROM `tbl_trip_commuters` WHERE `trip_id`=".$_POST['trip_id'].";";
    $trip_commuters = $db->select($sql);

    $page->show(compact('bus_trips','bus_plate','trip_commuters'));
}else{
    $page->show(compact('bus_trips','bus_plate'));
}