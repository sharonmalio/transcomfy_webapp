<?php

include('html/Page.inc.php');
include('php/User.inc.php');

$user = new User();
if($user->is_logged_in()){

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $db = new Database();

        $sql="SELECT `phone_number` FROM `tbl_drivers`
WHERE `driver_id` !='".$_POST['sacco_driver_id']."' AND `phone_number`='".$_POST['sacco_driver_phone_number']."'";
        $existing_phone_number=$db->select($sql);
        if($existing_phone_number){
            $_SESSION['negative_flash_message']="A driver with the submitted phone number already exists.";
            header('Location: index.php');
            return;
        }

//        $sql="SELECT `drivers_license` FROM `tbl_drivers` WHERE `drivers_license`='".$_POST['sacco_driver_license']."'";
//        $existing_driver_license=$db->select($sql);
//        if($existing_driver_license){
//            $_SESSION['negative_flash_message']="A driver with the submitted driver license already exists.";
//            header('Location: index.php');
//            return;
//        }

        $sql="UPDATE `tbl_drivers` 
SET `first_name`='".$_POST['sacco_driver_first_name']."',`last_name`='".$_POST['sacco_driver_last_name']."',`phone_number`='".$_POST['sacco_driver_phone_number']."',`drivers_license`='".$_POST['sacco_driver_license']."' WHERE `driver_id`='".$_POST['sacco_driver_id']."'";
        $db->update($sql);
        $_SESSION['flash_message']="You have successfully updated the driver record.";
        header('Location: dashboard.php');
    }

    if(isset($_SESSION['sacco_driver_id'])){
        $driver_id = $_SESSION['sacco_driver_id'];
    }else{
        header('Location: dashboard.php');
    }
    unset($_SESSION['sacco_driver_id']);

    $db=new Database();

    $sql="SELECT `driver_id`, `sacco_id`, `first_name`, `last_name`, `phone_number`, `drivers_license`, `has_assigned_bus` FROM `tbl_drivers` WHERE `driver_id`='".$driver_id."'";
    $driver=$db->select($sql)[0];

    $page = new Page('driver_update_content.php');
    $page->show(compact('driver'));

}else{
header('Location: index.php');
}

?>