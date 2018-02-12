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
if($user->is_logged_in() && $user->is_super_user()){
    include('html/Page.inc.php');
    if($_SERVER['REQUEST_METHOD']=='POST'){
        if(isset($_POST['approve_new_admin'])){

            $sql="UPDATE `tbl_saccos_admins` SET `is_activated`=1 WHERE `email_address`='".$_POST['new_admin_email_address']."'";
            $db = new Database();
            $db->update($sql);

            // Get cURL resource
            $curl = curl_init();
            // Set some options - we are passing in a useragent too here
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'http://transcomfy.com/Transcomfy/TranscomfyMail/send_mail.php',
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

            $_SESSION['flash_message'] = $response;
            //$_SESSION['flash_message'] = "Account has been successfully activated";
            header('Location: superuser.php');
        }

        if(isset($_POST['delete_sacco'])){
            $db = new Database();
            $sql = "DELETE FROM `tbl_saccos` WHERE `id`='".$_POST['delete_sacco_id']."'";
            $db->delete($sql);

            $_SESSION['flash_message'] = "Sacco has been successfully deleted.";
            header('Location: superuser.php');
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
            header('Location: superuser.php');
        }

        if(isset($_POST['superuser_delete_sacco_admin'])){
            $db = new Database();
            $sql = "DELETE FROM `tbl_saccos` WHERE `id`='".$_POST['superuser_delete_sacco_id']."'";
            $db->delete($sql);

            $_SESSION['flash_message'] = "Sacco admin has been successfully deleted.";
            header('Location: superuser.php');
        }


    }else{

        $sacco_name=$user->get_sacco()->name;
        $success = flash_message();
        $fail = negative_flash_message();

        $db = new Database();

        $sql = "SELECT tbl_saccos.name AS sacco_name,tbl_saccos_admins.first_name,tbl_saccos_admins.last_name,tbl_saccos_admins.phone_number,tbl_saccos_admins.email_address
FROM tbl_saccos,tbl_saccos_admins
WHERE tbl_saccos_admins.sacco_id = tbl_saccos.id AND tbl_saccos_admins.is_activated='0' AND tbl_saccos_admins.is_superuser='0';";
        $new_admins = $db->select($sql);

        $sql = "SELECT * FROM `tbl_saccos` WHERE `status` = 'Pending_Deletion'";
        $deletion_saccos = $db->select($sql);

        $sql = "SELECT tbl_saccos_admins.id,tbl_saccos.id as sacco_id,tbl_saccos.name AS sacco_name,tbl_saccos_admins.first_name,tbl_saccos_admins.last_name,tbl_saccos_admins.phone_number,tbl_saccos_admins.email_address
FROM tbl_saccos_admins,tbl_saccos
WHERE tbl_saccos_admins.sacco_id = tbl_saccos.id AND tbl_saccos_admins.is_activated = 1 AND tbl_saccos_admins.is_superuser = 0;";
        $registered_admins = $db->select($sql);

        $sql="SELECT  `first_name`, `last_name`, `phone_number`, `email_address` FROM `tbl_saccos_admins` WHERE `sacco_id`='".$_SESSION['sacco_id']."'";
        $sacco_admin=$db->select($sql)[0];

        $sql = "SELECT tbl_bus_mpesa_transactions.id,tbl_buses.plate AS bus_plate,tbl_saccos.name as sacco_name,tbl_bus_mpesa_transactions.business_short_code,tbl_bus_mpesa_transactions.timestamp,
tbl_bus_mpesa_transactions.transaction_type,tbl_bus_mpesa_transactions.amount,tbl_bus_mpesa_transactions.sender_id,tbl_bus_mpesa_transactions.receiver_id,
tbl_bus_mpesa_transactions.phone_number,tbl_bus_mpesa_transactions.account_reference,tbl_bus_mpesa_transactions.transaction_reference
FROM tbl_buses,tbl_bus_mpesa_transactions,tbl_saccos
WHERE tbl_bus_mpesa_transactions.bus_id = tbl_buses.id AND tbl_buses.sacco_id = tbl_saccos.id
ORDER BY tbl_bus_mpesa_transactions.id;";
        $sacco_financials=$db->select($sql);

        $sql="SELECT tbl_buses.id,tbl_buses.plate,tbl_buses.capacity,tbl_buses.route_number,tbl_saccos.name AS sacco_name,tbl_buses.status
FROM tbl_buses,tbl_saccos
WHERE tbl_buses.sacco_id = tbl_saccos.id;";
        $all_buses=$db->select($sql);

        $sql="SELECT * FROM transcomfy.tbl_saccos WHERE tbl_saccos.id !=0;";
        $all_saccos=$db->select($sql);

        $sql="SELECT tbl_drivers.driver_id,tbl_saccos.name AS sacco_name,tbl_drivers.first_name,tbl_drivers.last_name,tbl_drivers.phone_number,
tbl_drivers.drivers_license
FROM tbl_drivers,tbl_saccos
WHERE tbl_drivers.sacco_id = tbl_saccos.id;";
        $all_drivers=$db->select($sql);

        $page = new Page('superuser_dashboard_content.php');
        $page->show(compact('sacco_name','success','fail','sacco_admin','new_admins','deletion_saccos','registered_admins','sacco_financials','all_buses','all_saccos','all_drivers'));
    }

}else{
    header('Location: superuser.php');
}
?>