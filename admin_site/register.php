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

    include('php/User.inc.php');

    if($_SERVER['REQUEST_METHOD']=="POST"){
        $user = new User();
        if(!$user->register($_POST)){
            header('Location: register.php');
        }else{
            $_SESSION['flash_message'] = "You have successfully created a new sacco account. Log in with your credentials here.";
            header('Location: index.php');
        }
    }else{

        $user = new User();
        if($user->is_logged_in()){
            header('Location: dashboard.php');
        }else{
            include('html/Page.inc.php');
            $error_message=negative_flash_message();
            $page = new Page('register_content.php');
            $page->show(compact('error_message'));
        }
    }
?>