<?php

include('php/User.inc.php');

$user = new User();
if($user->is_logged_in()){
    if($user->is_super_user()){
        $user->logout();
        header('Location: superuser.php');
    }else{
        $user->logout();
        header('Location: index.php');
    }
}else{
    header('Location: index.php');
}

?>