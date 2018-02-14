<div class="ui brown very padded raised segment">
    <center>
        <div class="ui centered small image" >
            <img src="static/images/app_logo.JPG">
        </div>
    </center>
    <div class="ui brown center aligned huge dividing header">Superadmin administration dashboard.</div>
    <div class="ui divided grid">
        <br>
        <?php echo isset($success)?$success:"" ?>
        <?php echo isset($fail)?$fail:"" ?>
        <div class="four wide column">
            <div class="ui vertical fluid tabular menu">
                <div class="active item" data-tab="sacco_approve_admins">
                    <a href="">
                        <i class="big icons">
                            <i class="user icon"></i>
                            <i class="bottom right corner wait icon"></i>
                        </i>
                        &nbsp;Approve pending sacco admin registration.
                    </a>
                </div>
                <div class="item" data-tab="sacco_approve_sacco_deletion">
                    <a href="">
                        <i class="big icons">
                            <i class="bus icon"></i>
                            <i class="bottom right corner remove icon"></i>
                        </i>
                        &nbsp;Approve sacco deletion requests</a>
                </div>
                <div class="item" data-tab="sacco_superuser_admins">
                    <a href=""><i class="big user icon"></i>&nbsp;Registered sacco admins</a>
                </div>
                <div class="item" data-tab="sacco_superuser_finances">
                    <a href=""><i class="big money icon"></i>&nbsp;Sacco Transactions</a>
                </div>
                <div class="item" data-tab="sacco_superuser_reports">
                    <a href=""><i class="big file icon"></i>&nbsp;Superadmin Reports</a>
                </div>
                <div class="item" data-tab="sacco_superuser_account">
                    <a href=""><i class="big settings icon"></i>&nbsp;Superuser account settings.</a>
                </div>
            </div>
        </div>
        <div class="twelve wide column">
            <div class="ui active tab basic segment" data-tab="sacco_approve_admins" style="overflow-y: auto;">
                <?php
                    if(isset($new_admins)){
                        echo "
                        <table class='ui sortable single line celled table'>
                            <thead>
                                <tr>
                                    <th>Sacco Name</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Phone Number</th>
                                    <th>Email Address</th>
                                    <th>Approve?</th>
                                </tr>
                            </thead>
                            <tbody>";

                        foreach ($new_admins as $new_admin) {
                            echo "<tr>
                                    <td><b>$new_admin->sacco_name</b></td>
                                    <td>$new_admin->first_name</td>
                                    <td>$new_admin->last_name</td>
                                    <td>+254$new_admin->phone_number</td>
                                    <td>$new_admin->email_address</td>
                                    <td>
                                        <form method='post' action='superuser_dashboard.php' enctype='multipart/form-data'>
                                            <input type='hidden' name='approve_new_admin' value='approve_new_admin'>
                                            <input type='hidden' name='new_admin_sacco' value='$new_admin->sacco_name'>
                                            <input type='hidden' name='new_admin_first_name' value='$new_admin->first_name'>
                                            <input type='hidden' name='new_admin_last_name' value='$new_admin->last_name'>
                                            <input type='hidden' name='new_admin_phone_number' value='$new_admin->phone_number'>
                                            <input type='hidden' name='new_admin_email_address' value='$new_admin->email_address'>
                                            <button type='submit' class='ui green icon button'>
                                                <i class='check icon'></i>&nbsp;Approve sacco admin
                                            </button>
                                        </form>
                                    </td>
                                  </tr>";
                        }
                        
                        echo "                           
                            </tbody>
                        </table>
                        ";
                        //var_dump($new_admins);
                    }else{
                        echo "
                        <h2 class='ui center aligned icon header'>
                            <i class='thumbs up icon'></i>
                            No pending Sacco Admins Registration Approval Requests at the moment.
                        </h2>";
                    }
                ?>
            </div>
            <div class="ui tab basic segment" data-tab="sacco_approve_sacco_deletion" style="overflow-y: auto;">
                <?php
                if(isset($deletion_saccos)){
                    echo "
                        <table class='ui sortable single line celled table'>
                            <thead>
                                <tr>
                                    <th>Sacco Name</th>
                                    <th>Sacco Description</th>
                                    <th>Request Date</th>
                                    <th>Approve?</th>
                                </tr>
                            </thead>
                            <tbody>";

                    foreach ($deletion_saccos as $deletion_sacco) {
                        echo "<tr>
                                    <td><b>$deletion_sacco->name</b></td>
                                    <td>$deletion_sacco->description</td>
                                    <td>$deletion_sacco->status_date</td>
                                    <td>
                                        <form method='post' action='superuser_dashboard.php' enctype='multipart/form-data'>
                                            <input type='hidden' name='delete_sacco'>
                                            <input type='hidden' name='delete_sacco_id' value='$deletion_sacco->id'>
                                            <button type='submit' class='ui red icon button'>
                                                <i class='trash icon'></i>&nbsp;Delete Sacco and its related data!
                                            </button>
                                        </form>
                                    </td>
                                  </tr>";
                    }

                    echo "                           
                            </tbody>
                        </table>
                        ";
                    //var_dump($new_admins);
                }else{
                    echo "
                        <h2 class='ui center aligned icon header'>
                            <i class='thumbs up icon'></i>
                            No pending Sacco Deletion Requests at the moment.
                        </h2>";
                }
                ?>
            </div>
            <div class="ui tab basic segment" data-tab="sacco_superuser_admins" style="overflow-y: auto;">
                <table class="ui sortable single line celled table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Sacco Name</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Phone Number</th>
                            <th>Email Address</th>
                            <th>Operations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach ($registered_admins as $registered_admin){
                               echo"
                               <tr>
                                    <td>$registered_admin->id</td>   
                                    <td>$registered_admin->sacco_name</td>
                                    <td>$registered_admin->first_name</td>
                                    <td>$registered_admin->last_name</td>
                                    <td>+254$registered_admin->phone_number</td>
                                    <td>$registered_admin->email_address</td>
                                    <td>
                                        <form method='post' action='superuser_dashboard.php' enctype='multipart/form-data'>
                                            <input type='hidden' name='superuser_delete_sacco_admin'>
                                            <input type='hidden' name='superuser_delete_sacco_id' value='$registered_admin->sacco_id'>
                                            <button type='submit' class='ui red button' data-tooltip='Are you sure? This deletes the sacco admin, their sacco and buses.' data-inverted=''>Delete admin</button>
                                        </form>    
                                    </td>  
                               </tr>
                               ";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="ui basic tab segment" data-tab="sacco_superuser_finances" style="overflow-y: auto;">
                <h3 class="ui green header">MPESA Transactions.</h3>
                <table class="ui sortable single line celled table">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Bus Plate</th>
                            <th>Sacco Name</th>
                            <th>Business short code</th>
                            <th>Amount</th>
                            <th>Sender Id</th>
                            <th>Receiver Id</th>
                            <th>Phone Number</th>
                            <th>Account reference</th>
                            <th>Transaction reference</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(isset($sacco_financials)){
                                foreach ($sacco_financials as $sacco_financial){
                                    echo "
                                    <tr>
                                        <td>$sacco_financial->id</td>
                                        <td>$sacco_financial->bus_plate</td>
                                        <td>$sacco_financial->sacco_name</td>
                                        <td>$sacco_financial->business_short_code</td>
                                        <td>KES $sacco_financial->amount</td>
                                        <td>$sacco_financial->sender_id</td>
                                        <td>$sacco_financial->receiver_id</td>
                                        <td>$sacco_financial->phone_number</td>
                                        <td>$sacco_financial->account_reference</td>
                                        <td>$sacco_financial->transaction_reference</td>
                                        <td>$sacco_financial->timestamp</td>
                                    </tr>";
                                }
                            }else{
                                echo '';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="ui basic tab segment" data-tab="sacco_superuser_reports">
                <div class="ui secondary pointing menu">
                    <a class="active item" data-tab="sacco_superuser_reports_buses">
                        <i class="bus icon"></i>&nbsp;All registered buses
                    </a>
                    <a class="item" data-tab="sacco_superuser_reports_saccos">
                        <i class="home icon"></i>&nbsp;All registered saccos
                    </a>
                    <a class="item" data-tab="sacco_superuser_reports_drivers">
                        <i class="user icon"></i>&nbsp;All registered drivers
                    </a>
                </div>
                <div class="ui basic active tab segment" data-tab="sacco_superuser_reports_buses" style="overflow-y: auto;">
                    <table class="ui sortable single line celled table">
                        <thead>
                            <tr>
                                <th>Bus ID</th>
                                <th>Bus Plate</th>
                                <th>Bus Capacity</th>
                                <th>Route Number</th>
                                <th>Sacco Name</th>
                                <th>Bus Status</th>
                                <th>Trips & Transactions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($all_buses)){
                                    foreach ($all_buses as $all_bus){
                                        echo "
                                        <tr>
                                            <td>$all_bus->id</td>
                                            <td>$all_bus->plate</td>
                                            <td>$all_bus->capacity</td>
                                            <td>$all_bus->route_number</td>
                                            <td>$all_bus->sacco_name</td>
                                            <td>$all_bus->status</td>
                                            <td>
                                                <form method='post' action='bus_view.php' enctype='multipart/form-data'>
                                                    <input type='hidden' name='bus_id' value='$all_bus->id'>
                                                    <button type='submit' class='ui blue button'>Trips and Transactions</button>
                                                </form>
                                            </td>
                                        </tr>
                                    ";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="ui basic tab segment" data-tab="sacco_superuser_reports_saccos" style="overflow-y: auto;">
                    <table class="ui sortable single line celled table">
                        <thead>
                        <tr>
                            <th>Sacco ID</th>
                            <th>Sacco Name</th>
                            <th>Sacco Description</th>
                            <th>Status</th>
                            <th>Status Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(isset($all_saccos)){
                            foreach ($all_saccos as $all_sacco){
                                echo "
                                        <tr>
                                            <td>$all_sacco->id</td>
                                            <td>$all_sacco->name</td>
                                            <td>$all_sacco->description</td>
                                            <td>$all_sacco->status</td>
                                            <td>$all_sacco->status_date</td>
                                        </tr>
                                    ";
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="ui basic tab segment" data-tab="sacco_superuser_reports_drivers" style="overflow-y: auto;">
                    <table class="ui sortable single line celled table">
                        <thead>
                        <tr>
                            <th>Driver ID</th>
                            <th>Sacco Name</th>
                            <th>Driver Name</th>
                            <th>Phone Number</th>
                            <th>Driver License</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(isset($all_drivers)){
                            foreach ($all_drivers as $all_driver){
                                echo "
                                        <tr>
                                            <td>$all_driver->driver_id</td>
                                            <td>$all_driver->sacco_name</td>
                                            <td>$all_driver->first_name $all_driver->last_name</td>
                                            <td>$all_driver->phone_number</td>
                                            <td>TDB$all_driver->drivers_license</td>
                                        </tr>
                                    ";
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="ui basic tab segment" data-tab="sacco_superuser_account">
                <form class="ui form" action="superuser_dashboard.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="sacco_account_admin_edit">
                        <div class="two fields">
                            <div class="required field">
                                <label for="admin_first_name">Sacco administrator first name</label>
                                <input type="text" id="admin_first_name" value="<?php echo $sacco_admin->first_name;?>" name="admin_first_name" placeholder="Enter your first name here...">
                            </div>
                            <div class="required field">
                                <label for="admin_last_name">Sacco administrator last name</label>
                                <input type="text" id="admin_last_name" value="<?php echo $sacco_admin->last_name;?>" name="admin_last_name" placeholder="Enter your last name here...">
                            </div>
                        </div>
                        <div class="two fields">
                            <div class="required field">
                                <label for="admin_phone_number">Sacco administrator phone number.</label>
                                <div class="ui labeled input">
                                    <div class="ui label">+254</div>
                                    <input type="text" maxlength="9" value="<?php echo $sacco_admin->phone_number;?>" id="admin_phone_number" name="admin_phone_number" placeholder="Enter your phone number here...">
                                </div>
                            </div>
                            <div class="required field">
                                <label for="admin_email_address">Sacco administrator email address</label>
                                <input type="email" value="<?php echo $sacco_admin->email_address;?>" id="admin_email_address" name="admin_email_address" placeholder="Enter your email address here...">
                            </div>
                        </div>
                        <div class="two fields">
                            <div class="required field">
                                <label for="admin_password">Password</label>
                                <input type="password" id="admin_password" name="admin_password" placeholder="Enter your password here...">
                            </div>
                            <div class="required field">
                                <label for="admin_password_confirm">Confirm Password</label>
                                <input type="password" id="admin_password_confirm" name="admin_password_confirm" placeholder="Confirm your password here...">
                            </div>
                        </div>
                        <div class="field">
                            <button class="ui green button" type="submit">Update your profile</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>