<?php

include('Database.inc.php');

class User{

    protected $is_logged_in;
    protected $failed_login_message = "";
    protected $is_super_user;
    protected $is_activated;

    function __construct(){

        $this->is_logged_in=false;
        $this->is_super_user=false;
        $this->is_activated=false;

        session_start();
        if(isset($_SESSION['active_user'])){
            $this->is_logged_in=true;
        }
        if(isset($_SESSION['is_super_user'])){
            $this->is_super_user=true;
        }
        if(isset($_SESSION['is_activated'])){
            $this->is_activated=true;
        }
    }

    public function login($post_data){
        $email_address=$post_data['admin_email_address'];

        $sql="SELECT * FROM `tbl_saccos_admins` 
WHERE `email_address`='$email_address' AND `is_superuser`=0";

        $db=new Database();
        $user = $db->select($sql)[0];

        if($user==null){
            $this->is_logged_in=false;
            $this->failed_login_message = "The user does not exist.";
            return;
        }

        if(password_verify($post_data['admin_password'],$user->password)){
            $_SESSION['user_name'] = $user->first_name." ".$user->last_name;
            $_SESSION['sacco_id'] = $user->sacco_id;
            $_SESSION['active_user'] = true;
            if($user->is_activated == true){
                $_SESSION['is_activated'] = true;
                $this->is_activated = true;
            }else{
                $_SESSION['activation_code']=$user->activation_code;
            }
            $this->is_logged_in=true;
            return;
        }else{
            $this->is_logged_in=false;
            $this->failed_login_message = "Invalid password.";
            return;
        }

    }

    public function activate_user(){

        $sql = "UPDATE `tbl_saccos_admins` SET `is_activated`= 1 WHERE `sacco_id` = ".$_SESSION['sacco_id'];
        $db = new Database();
        $db->update($sql);

        $_SESSION['is_activated'] = true;
        $this->is_activated = true;
    }

    public function superuser_login($post_data){
        $email_address=$post_data['admin_email_address'];

        $sql="SELECT * FROM `tbl_saccos_admins` 
WHERE `email_address`='$email_address' AND `is_superuser`=1";

        $db=new Database();
        $user = $db->select($sql)[0];

        if($user==null){
            $this->is_logged_in=false;
            $this->failed_login_message = "The user does not exist.";
            return;
        }

        if(password_verify($post_data['admin_password'],$user->password)){
            $_SESSION['user_name'] = $user->first_name." ".$user->last_name;
            $_SESSION['sacco_id'] = $user->sacco_id;
            $_SESSION['active_user'] = true;
            $_SESSION['is_super_user'] = true;
            $this->is_logged_in=true;
            $this->is_super_user=true;
            return;
        }else{
            $this->is_logged_in=false;
            $this->failed_login_message = "Invalid password.";
            return;
        }

    }

    public function register($post_data){
        $db = new Database();

        $sql="SELECT `email_address` FROM `tbl_saccos_admins` WHERE `email_address` ='".$_POST['admin_email_address']."'";
        $existing_email=$db->select($sql);
        if($existing_email){
            $_SESSION['negative_flash_message']="The email address provided is already in use.";
            header('Location: register.php');
            return false;
        }

        $sql="SELECT `phone_number` FROM `tbl_saccos_admins` WHERE `phone_number` ='".$_POST['admin_phone_number']."'";
        $existing_phone=$db->select($sql);
        if($existing_phone){
            $_SESSION['negative_flash_message']="The phone number provided is already in use.";
            header('Location: register.php');
            return false;
        }

        $sql="SELECT tbl_saccos.name FROM tbl_saccos WHERE tbl_saccos.name='".ucwords(strtolower($post_data['sacco_name']))."';";
        $existing_sacco_name=$db->select($sql);
        if($existing_sacco_name){
            $_SESSION['negative_flash_message']="The sacco with the name <b>".ucwords(strtolower($post_data['sacco_name']))."</b> already exists.";
            header('Location: register.php');
            return false;
        }

        $sql="INSERT INTO `tbl_saccos`(`name`, `description`,`status`)
VALUES ('".ucwords(strtolower($post_data['sacco_name']))."','".$post_data['sacco_description']."','Active')";

        $SACCO_ID=$db->insert($sql,true);

        $sql="INSERT INTO `tbl_saccos_admins`(`sacco_id`, `first_name`, `last_name`, `phone_number`, `email_address`, `password`)
VALUES ('".$SACCO_ID."','".$post_data['admin_first_name']."','".$post_data['admin_last_name']."','".$post_data['admin_phone_number'].
            "','".$post_data['admin_email_address']."','".password_hash($post_data['admin_password'],PASSWORD_BCRYPT)."')";
        $db->insert($sql);

        return true;
    }

    public function logout(){
        session_unset();
        session_destroy();
    }

    public function is_logged_in(){
        return $this->is_logged_in;
    }

    public function is_super_user(){
        return $this->is_super_user;
    }

    public function is_activated(){
        return $this->is_activated;
    }

    public function get_login_error_message(){
        return $this->failed_login_message;
    }

    public function get_sacco(){
        if($this->is_logged_in()){
            $db = new Database();
            $sql = "SELECT `name`, `description` FROM `tbl_saccos` WHERE `id`= '".$_SESSION['sacco_id']."'";
            return $db->select($sql)[0];
        }else{
            return null;
        }
    }

    function __destruct()
    {
        // TODO: Implement __destruct() method.
    }
}

?>