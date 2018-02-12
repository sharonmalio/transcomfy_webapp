<?php

include('html/Page.inc.php');
include('php/User.inc.php');

    $user = new User();
    if($user->is_logged_in()){
        // $fb_database = $firebase->getDatabase();
        // $reference = $fb_database->getReference('buses')->set('numberPlate',$_POST['sacco_bus_plate']);
        // $snapshot = $reference->getSnapshot();
        // $fetched_buses = $snapshot->getValue();
        // print_r($fetched_buses);
        // exit;

        if($_SERVER['REQUEST_METHOD'] == "POST"){
            $db = new Database();

//            $sql="SELECT `plate` FROM `tbl_buses` WHERE `plate`='".$_POST['sacco_bus_plate']."'";
//            $existing_plate=$db->select($sql);
//            if($existing_plate){
//                $_SESSION['negative_flash_message']="A bus with the number plate <b>".$existing_plate[0]->plate."</b> exists.";
//                header('Location: index.php');
//                return;
//            }
          
            // $sacco_buses = [];
            // foreach($fetched_buses as $key => $value){
            //     $bus = [];
            //     $bus['capacity'] = $value['maxCapacity'];
            //     $bus['plate'] = $value['numberPlate'];
            //     $bus['route_number'] = '0';
            //     $bus['assigned_driver'] = $value['driverId'];
            //     $bus['status'] = 'Enabled';
            //     array_push($sacco_buses, (object)$bus);
            // }
            // $sql="UPDATE `tbl_buses` SET `plate`='".$_POST['sacco_bus_plate']."',`capacity`='".$_POST['sacco_bus_capacity']."', `route_number`='".$_POST['sacco_bus_route_number']."' WHERE `id`='".$_POST['sacco_bus_id']."'";
            // $db->update($sql);
            // $_SESSION['flash_message']="You have successfully updated the bus record.";

            header('Location: dashboard.php');
        }

        if(isset($_SESSION['sacco_bus_id'])){
            $bus_id = $_SESSION['sacco_bus_id'];
        }else{
            header('Location: dashboard.php');
        }
        unset($_SESSION['sacco_bus_id']);

        print_r($bus_id);
        exit;
        $db=new Database();
        $sql="SELECT `id`, `plate`, `capacity`, `sacco_id`, `route_number` FROM `tbl_buses` WHERE `id`='".$bus_id."'";
        $bus=$db->select($sql)[0];

        $page = new Page('bus_update_content.php');
        $page->show(compact('bus'));

    }else{
        header('Location: index.php');
    }

?>