<div class="ui grid">
    <div class="five wide column"></div>
    <div class="six wide column">
        <div class="ui raised very padded brown segment">
            <?php
            if(isset($registration_success)){
                echo $registration_success;
            }
            ?>
            <center>
                <div class="ui centered small image" >
                    <img src="static/images/app_logo.JPG">
                </div>
            </center>
            <div class="ui brown center aligned header">
                <div class="content">Transcomfy</div>
                <div class="sub header">Sacco Superuser Login Form</div>
            </div>
            <form class="ui form" action="superuser.php" method="POST" enctype="multipart/form-data">
                <?php
                if(isset($login_error_message)){
                    echo "
                            <div class='ui negative icon message'>
                                <i class='warning icon'></i>
                                <div class='content'>
                                    <div class='header'>Login error.</div>
                                    <p>$login_error_message</p>
                                </div>
                            </div>";
                }
                ?>
                <div class="required field">
                    <label for="admin_email_address">Superadmin email address</label>
                    <input type="email" name="admin_email_address"  id="admin_email_address" placeholder="Enter your email address here...">
                </div>
                <div class="required field">
                    <label for="admin_password">Password</label>
                    <input type="password" name="admin_password" id="admin_password" placeholder="Enter your password here...">
                </div>
                <div class="field">
                    <button class="ui green button" type="submit">Log In</button>
                </div>
            </form>
        </div>
    </div>
    <div class="five wide column"></div>
</div>