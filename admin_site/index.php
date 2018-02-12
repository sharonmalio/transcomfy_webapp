<?php

    include('php/User.inc.php');
    include('html/Page.inc.php');

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

    if($_SERVER['REQUEST_METHOD']=="POST"){
        $user = new User();
        $user->login($_POST);

        if($user->is_logged_in()){
            header('Location: dashboard.php');
        }else{
            $login_error_message = $user->get_login_error_message();
            $page = new Page('index_content.php');
            $page->show(compact('login_error_message'));
        }

    }else{
        $user = new User();
        if($user->is_logged_in() && !$user->is_super_user()){
            header('Location: dashboard.php');
        }else{
            if($user->is_super_user()){
                header('Location: superuser.php');
            }
            $registration_success = flash_message();
            //var_dump($registration_success);
            $page = new Page('index_content.php');
            $page->show(compact('registration_success'));
        }
    }
?>