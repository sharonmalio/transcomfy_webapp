<?php

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

function flash_message(){
    if(isset($_SESSION['flash_message'])){
        $success="<div class='ui positive icon message'>
                        <i class='check icon'></i>
                        <div class='content'>
                            <div class='header'>Successful Account Processing.</div>
                            <p>".$_SESSION['flash_message']."</p>
                        </div>
                     </div>";
        unset($_SESSION['flash_message']);
        return $success;
    }
    return null;
}

include('php/User.inc.php');
include('html/Page.inc.php');

if($_SERVER['REQUEST_METHOD']=="POST"){
    $user = new User();
    $user->superuser_login($_POST);

    if($user->is_logged_in() && $user->is_super_user()){
        header('Location: superuser_dashboard.php');
    }else{
        $login_error_message = $user->get_login_error_message();
        $page = new Page('superuser_content.php');
        $page->show(compact('login_error_message'));
    }
}else{

    $user = new User();
    if($user->is_logged_in()){
        if(!$user->is_super_user()){
            header('Location: index.php');
        }else{
            header('Location: superuser_dashboard.php');
        }
    }else{
        $error_message=negative_flash_message();
        $registration_success=flash_message();
        $page = new Page('superuser_content.php');
        $page->show(compact('error_message','registration_success'));
    }
}
?>