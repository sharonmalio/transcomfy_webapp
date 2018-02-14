<?php

    include('php/User.inc.php');


    function flash_message(){
        if(isset($_SESSION['flash_message'])){
            $success="<div class='ui positive icon message'>
                    <i class='check icon'></i>
                    <div class='content'>
                        <div class='header'>Successful data update</div>
                        <p>".$_SESSION['flash_message']."</p>
                    </div>
                 </div>";
            unset($_SESSION['flash_message']);
            return $success;
        }
        return null;
    }

    function negative_flash_message(){
        if(isset($_SESSION['negative_flash_message'])){
            $success="<div class='ui negative icon message'>
                        <i class='warning icon'></i>
                        <div class='content'>
                            <div class='header'>Data error</div>
                            <p>".$_SESSION['negative_flash_message']."</p>
                        </div>
                     </div>";
            unset($_SESSION['negative_flash_message']);
            return $success;
        }
        return null;
    }

    function encrypt_id($id) {
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'This is my secret key';
    $secret_iv = 'This is my secret iv';
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    $output = openssl_encrypt($id, $encrypt_method, $key, 0, $iv);
    return base64_encode($output);
}

    $user = new User();
    if($user->is_logged_in() && !$user->is_super_user()){
        include('html/Page.inc.php');
        if($user->is_activated()){
            if($_SERVER['REQUEST_METHOD']=='POST'){
                if(isset($_POST['sacco_buses_add'])){
                    $db = new Database();

                    $sql="SELECT `plate` FROM `tbl_buses` WHERE `plate`='".$_POST['sacco_bus_plate']."'";
                    $existing_plate=$db->select($sql);
                    if($existing_plate){
                        $_SESSION['negative_flash_message']="A bus with the number plate <b>".$existing_plate[0]->plate."</b> exists.";
                        header('Location: index.php');
                        return;
                    }

                    $sql="INSERT INTO `tbl_buses`(`plate`, `capacity`, `sacco_id`, `route_number`)
VALUES ('".$_POST['sacco_bus_plate']."','".$_POST['sacco_bus_capacity']."','".$_SESSION['sacco_id']."','".$_POST['sacco_bus_route_number']."')";
                    
                   $id = $db->insert($sql, true);

                
                    $database = $firebase->getDatabase();

                    $newPost = $database
                    ->getReference('buses/'.$id)
                    ->set([
                        'availableSpace'=>$_POST['sacco_bus_capacity'],
                        'maxCapacity' => $_POST['sacco_bus_capacity'],
                        'numberPlate' => $_POST['sacco_bus_plate'], 
                        'route' => $_POST['sacco_bus_route_number']

                    ]);

                    $_SESSION['flash_message']="You have successfully added the bus record.";
                    header('Location: index.php');
                }

                if(isset($_POST['sacco_bus_operation'])){
                    if(isset($_POST['sacco_bus_delete'])){
                        $db = new Database();

                        $sql="SELECT `assigned_driver` FROM `tbl_buses` WHERE `id` ='".$_POST['sacco_bus_id']."'";
                        $assigned_driver = $db->select($sql);

                        if($assigned_driver!=null){
                            $assigned_driver_id=$assigned_driver[0]->assigned_driver;
                            $sql="UPDATE `tbl_drivers` SET `has_assigned_bus`= NULL WHERE `driver_id` = '".$assigned_driver_id."'";
                            $db->update($sql);
                        }

                        $sql = "DELETE FROM `tbl_buses` WHERE `id`='".$_POST['sacco_bus_id']."'";
                        $db->delete($sql);
                        $_SESSION['flash_message']="You have successfully deleted the bus record.";
                        header('Location: index.php');
                    }
                    if(isset($_POST['sacco_bus_update'])){
                        $_SESSION['sacco_bus_id'] = $_POST['sacco_bus_id'];
                        header('Location: bus_update.php');
                    }
                    if(isset($_POST['sacco_bus_assign'])){
                        $_SESSION['sacco_bus_id'] = $_POST['sacco_bus_id'];
                        header('Location: bus_assign.php');
                    }
                    if(isset($_POST['sacco_bus_enable'])){
                        $db = new Database();
                        $sql = "UPDATE `tbl_buses` SET `status`='Enabled' WHERE `id`=".$_POST['sacco_bus_id'];
                        $db->update($sql);

                        $_SESSION['flash_message'] = 'Matatu has been enabled successfully';
                        header('Location: index.php');
                    }
                    if(isset($_POST['sacco_bus_disable'])){
                        $db = new Database();
                        $sql = "UPDATE `tbl_buses` SET `status`='Disabled' WHERE `id`=".$_POST['sacco_bus_id'];
                        $db->update($sql);

                        $_SESSION['flash_message'] = 'Matatu has been disabled successfully';
                        header('Location: index.php');
                    }
                }

                if(isset($_POST['sacco_drivers_add'])){
                    $db = new Database();
                    if(isset($_POST['sacco_assigned_bus']) && !empty($_POST['sacco_assigned_bus'])){

                        $sql="SELECT `phone_number` FROM `tbl_drivers` WHERE `phone_number`='".$_POST['sacco_driver_phone_number']."'";
                        $existing_phone_number=$db->select($sql);
                        if($existing_phone_number){
                            $_SESSION['negative_flash_message']="A driver with the submitted phone number already exists.";
                            header('Location: index.php');
                            return;
                        }

                        $sql="SELECT `email_address` FROM `tbl_drivers` WHERE `email_address`='".$_POST['sacco_driver_email_address']."'";
                        $existing_email_address=$db->select($sql);
                        if($existing_email_address){
                            $_SESSION['negative_flash_message']="A driver with the submitted email address already exists.";
                            header('Location: index.php');
                            return;
                        }

                        $sql="SELECT `drivers_license` FROM `tbl_drivers` WHERE `drivers_license`='".$_POST['sacco_driver_license']."'";
                        $existing_driver_license=$db->select($sql);
                        if($existing_driver_license){
                            $_SESSION['negative_flash_message']="A driver with the submitted driver license already exists.";
                            header('Location: index.php');
                            return;
                        }

                        //create firebase account
                        $newUser = $firebase->getAuth()->createUserWithEmailAndPassword($_POST['sacco_driver_email_address'], $_POST['sacco_driver_first_name'].$_POST['sacco_driver_last_name']);
                        

                        $sql="INSERT INTO `tbl_drivers`(`driver_id`,`sacco_id`, `first_name`, `last_name`, `phone_number`,`email_address`,`password`,`drivers_license`,`has_assigned_bus`)
VALUES ('".$newUser->getUid()."','".$_SESSION['sacco_id']."','".$_POST['sacco_driver_first_name']."','".$_POST['sacco_driver_last_name']."','".$_POST['sacco_driver_phone_number']."','".$_POST['sacco_driver_email_address']."','".
                            password_hash($_POST['sacco_driver_first_name'].$_POST['sacco_driver_last_name'],PASSWORD_BCRYPT)."','".$_POST['sacco_driver_license']."',".true.")";
                        $driver_id=$db->insert($sql,true);

                
                        $database = $firebase->getDatabase();

                        $newPost = $database
                        ->getReference('drivers/'.$driver_id)
                        ->set([
                            'email'=>$_POST['sacco_driver_email_address'],
                            'name' => $_POST['sacco_driver_first_name'].' '.$_POST['sacco_driver_last_name']
        
                        ]);
                        
                        if ($_POST['sacco_assigned_bus'] != NULL) {
                            $bus = $firebase->getDatabase()->getReference('buses/'.$_POST['sacco_assigned_bus'])->getSnapShot();


                            $firebase->getDatabase()->getReference('buses/'.$bus->getKey())
                            ->update([
                                'driverId'=>$driver_id
                            ]);

                            $firebase->getDatabase()->getReference('drivers/'.$driver_id)
                            ->update([
                                'bus'=>$bus->getValue()
                            ]);
                        }

                        

                        $sql= "UPDATE `tbl_drivers` SET `public_id`='".encrypt_id($driver_id)."' WHERE `driver_id`='".$driver_id."'";
                        $db->update($sql);

                        $sql = "UPDATE `tbl_buses` SET `assigned_driver`= '".$driver_id."' WHERE `id`='".$_POST['sacco_assigned_bus']."'";
                        $db->update($sql);

                        // Get cURL resource
                        $curl = curl_init();
                        // Set some options - we are passing in a useragent too here
                        curl_setopt_array($curl, array(
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_URL => 'http://transcomfy.com/Transcomfy/TranscomfyMail/send_driver_mail.php',
                            CURLOPT_USERAGENT => 'Transcomfy Account Activation',
                            CURLOPT_POST => 1,
                            CURLOPT_POSTFIELDS => $_POST,
                            CURLOPT_TIMEOUT =>120
                        ));
                        // Send the request & save response to $resp
                        $response=curl_exec($curl);
                        // Close request to clear up some resources
                        curl_close($curl);
                        //var_dump($_POST);

                    }else{
                        $sql="SELECT `phone_number` FROM `tbl_drivers` WHERE `phone_number`='".$_POST['sacco_driver_phone_number']."'";
                        $existing_phone_number=$db->select($sql);
                        if($existing_phone_number){
                            $_SESSION['negative_flash_message']="A driver with the submitted phone number already exists.";
                            header('Location: index.php');
                            return;
                        }

                        $sql="SELECT `email_address` FROM `tbl_drivers` WHERE `email_address`='".$_POST['sacco_driver_email_address']."'";
                        $existing_email_address=$db->select($sql);
                        if($existing_email_address){
                            $_SESSION['negative_flash_message']="A driver with the submitted email address already exists.";
                            header('Location: index.php');
                            return;
                        }

                        $sql="SELECT `drivers_license` FROM `tbl_drivers` WHERE `drivers_license`='".$_POST['sacco_driver_license']."'";
                        $existing_driver_license=$db->select($sql);
                        if($existing_driver_license){
                            $_SESSION['negative_flash_message']="A driver with the submitted driver license already exists.";
                            header('Location: index.php');
                            return;
                        }

                        $sql="INSERT INTO `tbl_drivers`(`sacco_id`, `first_name`, `last_name`, `phone_number`,`email_address`,`password`,`drivers_license`,`has_assigned_bus`)
VALUES ('".$_SESSION['sacco_id']."','".$_POST['sacco_driver_first_name']."','".$_POST['sacco_driver_last_name']."','".$_POST['sacco_driver_phone_number']."','".$_POST['sacco_driver_email_address']."','".
                            password_hash($_POST['sacco_driver_first_name'].$_POST['sacco_driver_last_name'],PASSWORD_BCRYPT)."','".$_POST['sacco_driver_license']."',NULL)";
                        $driver_id=$db->insert($sql,true);

                        $sql= "UPDATE `tbl_drivers` SET `public_id`='".encrypt_id($driver_id)."' WHERE `driver_id`='".$driver_id."'";
                        $db->update($sql);

                        // Get cURL resource
                        $curl = curl_init();
                        // Set some options - we are passing in a useragent too here
                        curl_setopt_array($curl, array(
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_URL => 'http://transcomfy.com/Transcomfy/TranscomfyMail/send_driver_mail.php',
                            CURLOPT_USERAGENT => 'Transcomfy Account Activation',
                            CURLOPT_POST => 1,
                            CURLOPT_POSTFIELDS => $_POST,
                            CURLOPT_TIMEOUT =>120
                        ));
                        // Send the request & save response to $resp
                        $response=curl_exec($curl);
                        // Close request to clear up some resources
                        curl_close($curl);
                        //var_dump($_POST);
                    }
                    $_SESSION['flash_message']="You have successfully added the driver record.";
                    header('Location: index.php');
                }

                if(isset($_POST['sacco_driver_operation'])){
                    if(isset($_POST['sacco_driver_delete'])){
                        $db = new Database();
                        $sql = "DELETE FROM `tbl_drivers` WHERE `driver_id`='".$_POST['sacco_driver_id']."'";
                        $db->delete($sql);
                        $_SESSION['flash_message']="You have successfully deleted the driver record.";
                        header('Location: index.php');
                    }
                    if(isset($_POST['sacco_driver_update'])){
                        $_SESSION['sacco_driver_id'] = $_POST['sacco_driver_id'];
                        header('Location: driver_update.php');
                    }
                    if(isset($_POST['sacco_driver_deassign'])){
                        $db = new Database();
                        $sql = "UPDATE `tbl_drivers` 
SET `has_assigned_bus`=NULL WHERE `driver_id`='".$_POST['sacco_driver_id']."'";
                        $db->update($sql);

                        $sql = "UPDATE `tbl_buses` SET `assigned_driver`=NULL WHERE `assigned_driver`='".$_POST['sacco_driver_id']."'";
                        $db->update($sql);

                        $_SESSION['flash_message']="You have successfully de-assigned the driver from the bus.";
                        header('Location: index.php');
                    }
                }

                if(isset($_POST['sacco_account_sacco_edit'])){
                    $db = new Database();
                    $sql="UPDATE `tbl_saccos`
SET `name`='".$_POST['sacco_name']."',`description`='".$_POST['sacco_description']."' WHERE `id`='".$_SESSION['sacco_id']."'";
                    $db->update($sql);

                    $_SESSION['flash_message'] = "Sacco details have been updated successfully";
                    header('Location: index.php');
                }

                if(isset($_POST['sacco_account_admin_edit'])){
                    $db = new Database();
                    $sql = "UPDATE `tbl_saccos_admins`
SET `first_name`='".$_POST['admin_first_name']."',`last_name`='".$_POST['admin_last_name']."',`phone_number`='".$_POST['admin_phone_number']."',`email_address`='".$_POST['admin_email_address']."',`password`='".password_hash($_POST['admin_password'],PASSWORD_BCRYPT)."' WHERE `sacco_id`='".$_SESSION['sacco_id']."'";
                    $db->update($sql);

                    $user = new User();
                    $user->logout();

                    session_start();
                    $_SESSION['flash_message'] = "Your admin details have been updated successfully";
                    header('Location: index.php');
                }

                if(isset($_POST['sacco_account_delete'])){
//                    $db = new Database();
//                    $sql = "DELETE FROM `tbl_saccos` WHERE `id`='".$_SESSION['sacco_id']."'";
//                    $db->delete($sql);

                    $db = new Database();
                    $sql = "UPDATE `tbl_saccos` SET `status`='Pending_Deletion',`status_date`='".date('Y-m-d H:i:s')."' WHERE `id`='".$_SESSION['sacco_id']."'";
                    $db->update($sql);

                    $user->logout();
                    session_start();
                    $_SESSION['flash_message']="Your sacco account will be deleted. You can register a new account on the link below.";
                    header('Location: index.php');
                }

            }else{

                $sacco_name=$user->get_sacco()->name;
                $success = flash_message();
                $fail = negative_flash_message();

                $db = new Database();
                $sql="SELECT `id`,`plate`, `capacity`, `route_number`, `assigned_driver`, `status` FROM `tbl_buses` WHERE `sacco_id`='".$_SESSION['sacco_id']."'";
                $sacco_buses=$db->select($sql);

                // $fb_database = $firebase->getDatabase();
                // $reference = $fb_database->getReference('buses');
                // $snapshot = $reference->getSnapshot();
                // $fetched_buses = $snapshot->getValue();
                // $sacco_buses = [];
                // foreach($fetched_buses as $key => $value){
                //     $bus = [];
                //     $bus['capacity'] = $value['maxCapacity'];
                //     $bus['plate'] = $value['numberPlate'];
                //     $bus['route_number'] = $_POST['sacco_bus_route_number'];
                //     $bus['assigned_driver'] = $value['driverId'];
                //     $bus['id'] = $key;
                //     $bus['status'] = 'Enabled';
                //     array_push($sacco_buses, (object)$bus);
                // }
                // print_r($sacco_buses);
                // exit;

                $sql="SELECT `driver_id`,`first_name`, `last_name`, `phone_number`, `drivers_license`,`has_assigned_bus`,`public_id`,`email_address` FROM `tbl_drivers` WHERE `sacco_id`='".$_SESSION['sacco_id']."'";
                $sacco_drivers = $db->select($sql);

                $sql="SELECT `id`, `plate`,`route_number` FROM `tbl_buses` WHERE `assigned_driver` IS NULL AND `sacco_id` = '".$_SESSION['sacco_id']."' AND `status` = 'Enabled'";
                $free_buses = $db->select($sql);

                $sql="SELECT `name`, `description` FROM `tbl_saccos` WHERE `id` = '".$_SESSION['sacco_id']."'";
                $sacco=$db->select($sql)[0];

                $sql="SELECT  `first_name`, `last_name`, `phone_number`, `email_address` FROM `tbl_saccos_admins` WHERE `sacco_id`='".$_SESSION['sacco_id']."'";
                $sacco_admin=$db->select($sql)[0];

                $page = new Page('dashboard_content.php');
                $page->show(compact('sacco_name','success','fail','sacco_buses','sacco_drivers','free_buses','sacco','sacco_admin'));
            }
        }else{
            if($_SERVER['REQUEST_METHOD']=='POST'){
                if($_POST['admin_activation_code'] == $_SESSION['activation_code']){
                    $user->activate_user();
                    $_SESSION['flash_message'] = "Your account has been successfully activated";
                    header('Location: index.php');
                }else{
                    $_SESSION['negative_flash_message'] = "The code is incorrect";
                    header('Location: index.php');
                }
            }else{
                $page = new Page('dashboard_activate_content.php');
                $page->show();
            }
        }

    }else{
        header('Location: index.php');
    }
?>