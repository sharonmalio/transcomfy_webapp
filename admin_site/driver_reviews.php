<?php

    function decrypt_id($encrypted_id){
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'This is my secret key';
        $secret_iv = 'This is my secret iv';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        return openssl_decrypt(base64_decode($encrypted_id),$encrypt_method,$key,0,$iv);
    }

    include('php/User.inc.php');

    $user = new User();
    if($user->is_logged_in()){
        $private_id=decrypt_id($_GET['id']);

        $db=new Database();
        $sql="SELECT `sacco_id`, `first_name`, `last_name`, `phone_number`, `drivers_license`, `has_assigned_bus`, `public_id` 
FROM `tbl_drivers` WHERE `driver_id`='".$private_id."'";
        $driver = $db->select($sql)[0];

        $sql="SELECT `name` FROM `tbl_saccos` WHERE `id`='".$_SESSION['sacco_id']."'";
        $sacco=$db->select($sql)[0];

        $sql="SELECT `rating`, `review`, `number_plate`, `time_of_review` FROM `tbl_driver_reviews` WHERE `driver_id`='".$private_id."'";
        $reviews=$db->select($sql);

        if(isset($reviews)){
            $sql="SELECT AVG(`rating`) as 'average_rating' FROM `tbl_driver_reviews` WHERE `driver_id`= '".$private_id."'GROUP BY `driver_id`";
            $average=round($db->select($sql)[0]->average_rating,1);
        }else{
            $average=null;
        }

        if(!isset($reviews)){
            $average_rating = "N/A";
        }elseif($average>=0 && $average<1){
            $average_rating = "$average - Poor.";
        }elseif($average>=1 && $average<2){
            $average_rating = "$average - Average.";
        }elseif($average>=2 && $average<3){
            $average_rating = "$average - Good.";
        }elseif($average>=3 && $average<4){
            $average_rating = "$average - Great.";
        }elseif($average>=4 && $average<5){
            $average_rating = "$average - Excellent.";
        }

        include('html/Page.inc.php');
        $page = new Page('driver_reviews_content.php');
        $page->show(compact('driver','sacco','reviews','average_rating'));
    }else{
        header('Location: index.php');
    }

?>