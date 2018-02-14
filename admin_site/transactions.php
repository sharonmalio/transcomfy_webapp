<?php

    include('Database.inc.php');

    $db = new Database();

    /*$sacco_id = $_POST["sacco_id"];
    $amount = $_POST["amount"];
    $user_id = $_POST["user_id"];
    $user_name = $_POST["user_name"];
    $route_number = $_POST["route_number"];*/

    $sacco_id = $_GET["sacco_id"];
    $amount = $_GET["amount"];
    $user_id = $_GET["user_id"];
    $user_name = $_GET["user_name"];
    $route_number = $_GET["route_number"];

    $sql = "INSERT INTO `tbl_transactions`(`sacco_id`, `amount`, `user_id`, `user_name`,`route_number`) VALUES ('" . $sacco_id . "','" . $amount . "','" . $user_id . "','" . $user_name . "','" . $route_number . "')";
    $db->insert($sql, true);

?>