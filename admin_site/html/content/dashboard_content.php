<div class="ui brown very padded raised segment">
    <center>
        <div class="ui centered small image" >
            <img src="static/images/app_logo.JPG">
        </div>
    </center>
    <div class="ui brown center aligned huge dividing header"><?php echo isset($sacco_name)?$sacco_name:"" ?> administration dashboard.</div>
    <div class="ui divided grid">
        <br>
        <?php echo isset($success)?$success:"" ?>
        <?php echo isset($fail)?$fail:"" ?>
        <div class="four wide column">
            <div class="ui vertical fluid tabular menu">
                <div class="active item" data-tab="sacco_buses">
                    <a href=""><i class="bus icon"></i>&nbsp;Sacco buses.</a>
                </div>
                <div class="item" data-tab="sacco_drivers">
                    <a href=""><i class="user icon"></i>&nbsp;Sacco drivers.</a>
                </div>
                <div class="item" data-tab="sacco_account">
                    <a href=""><i class="settings icon"></i>&nbsp;Sacco account settings.</a>
                </div>
            </div>
        </div>
        <div class="twelve wide column">
            <div class="ui active tab basic segment" data-tab="sacco_buses">
                <div class="ui secondary pointing menu">
                    <a class="active item" data-tab="sacco_buses_add">
                        <i class="plus icon"></i>&nbsp;Register bus.
                    </a>
                    <a class="item" data-tab="sacco_buses_list">
                        <i class="list icon"></i>&nbsp;List of registered sacco buses.
                    </a>
                </div>
                <div class="ui basic active tab segment" data-tab="sacco_buses_add">
                    <form class="ui form" method="POST" action="dashboard.php" enctype="multipart/form-data">
                        <input type="hidden" value="sacco_buses_add" name="sacco_buses_add">
                        <div class="three fields">
                            <div class="required field">
                                <label for="sacco_bus_plate">Bus number plate (KXX123X)</label>
                                <input type="text" maxlength="7" id="sacco_bus_plate" name="sacco_bus_plate" placeholder="Enter the bus number plate here...">
                            </div>
                            <div class="required field">
                                <label for="sacco_bus_capacity">Maximum passenger capacity</label>
                                <input type="text" id="sacco_bus_capacity" maxlength="2" name="sacco_bus_capacity" placeholder="Enter the maximum number of passengers here...">
                            </div>
                            <div class="required field">
                                <label for="sacco_bus_route_number">Route number</label>
                                <input type="text" id="sacco_bus_route_number" maxlength="4" name="sacco_bus_route_number" placeholder="Enter the bus route here...">
                            </div>
                        </div>
                        <div class="field">
                            <button type="submit" class="ui green button">Add bus.</button>
                        </div>
                    </form>
                </div>
                <div class="ui basic tab segment" data-tab="sacco_buses_list" style="overflow-y: auto;">
                    <div class="ui large brown label" >Total sacco buses :&nbsp;<?php echo isset($sacco_buses)?count($sacco_buses):0?></div>
                    <table class="ui sortable single line celled center aligned small table">
                        <thead>
                        <tr>
                            <th>Bus Route</th>
                            <th>Bus number plate</th>
                            <th>Maximum number of passengers</th>
                            <th>Status</th>
                            <th>Operations</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(isset($sacco_buses)){
                                foreach ($sacco_buses as $sacco_bus){
                                    $assign_button_text=($sacco_bus->assigned_driver)?"Change driver":"Assign driver";
                                    $disenable_button=($sacco_bus->status=="Disabled")?
                                        "<button type='submit' class='ui green mini icon button' name='sacco_bus_enable' >
                                                                <i class='bus icon'></i>&nbsp;Enable Matatu
                                                            </button>"
                                        :
                                        "<button type='submit' class='ui red mini icon button' name='sacco_bus_disable' >
                                                                <i class='bus icon'></i>&nbsp;Disable Matatu
                                                            </button>"
                                    ;
//                                    if(isset($sacco_bus->assigned_driver)){
//                                        $assign_button_text= "Change driver";
//                                    }
                                    echo "
                                         <tr>
                                            <td>$sacco_bus->route_number</td>
                                            <td>$sacco_bus->plate</td>
                                            <td>$sacco_bus->capacity</td>
                                            <td>$sacco_bus->status</td>
                                            <td>
                                                <form class='ui form' enctype='multipart/form-data' method='POST' action='dashboard.php'>
                                                    <input type='hidden' value='".$sacco_bus->id."' name='sacco_bus_id'>
                                                    <input type='hidden' value='sacco_bus_operation' name='sacco_bus_operation'>
                                                    <div class='fields'>
                                                        <div class='field'>
                                                            <button class='ui green mini icon button' type='submit' name='sacco_bus_update'>
                                                                <i class='write icon'></i>&nbsp; Edit / Update  
                                                            </button>
                                                        </div>
                                                        <div class='field'>
                                                            <button type='submit' class='ui red mini icon button' name='sacco_bus_delete' data-tooltip='Are you sure? This action cannot be undone.' data-inverted=''>
                                                                <i class='trash icon'></i>&nbsp; Delete
                                                            </button>
                                                        </div>
                                                        <div class='field'>
                                                            <button type='submit' class='ui blue mini icon button' name='sacco_bus_assign' >
                                                                <i class='user icon'></i>&nbsp;$assign_button_text
                                                            </button>
                                                        </div>
                                                        <div class='field'>
                                                            $disenable_button
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                         </tr>";
                                }
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="ui tab basic segment" data-tab="sacco_drivers">
                <div class="ui secondary pointing menu">
                    <a class="active item" data-tab="sacco_drivers_add">
                        <i class="plus icon"></i>&nbsp;Register driver.
                    </a>
                    <a class="item" data-tab="sacco_drivers_list">
                        <i class="list icon"></i>&nbsp;List of drivers.
                    </a>
                </div>
                <div class="ui active tab basic segment" data-tab="sacco_drivers_add">
                    <form method="POST" enctype="multipart/form-data" action="dashboard.php" class="ui form">
                        <input type="hidden" value="sacco_drivers_add" name="sacco_drivers_add">
                        <div class="two fields">
                            <div class="required field">
                                <label for="sacco_driver_first_name">First Name</label>
                                <input type="text" id="sacco_driver_first_name" name="sacco_driver_first_name" placeholder="Enter driver's first name here...">
                            </div>
                            <div class="required field">
                                <label for="sacco_driver_last_name">Last Name</label>
                                <input type="text" id="sacco_driver_last_name" name="sacco_driver_last_name" placeholder="Enter driver's last name here...">
                            </div>
                        </div>
                        <div class="two fields">
                            <div class="required field">
                                <label for="sacco_driver_phone_number">Phone number (9 Digits)</label>
                                <div class="ui labeled input">
                                    <div class="ui label">+254</div>
                                    <input type="text" maxlength="9" id="sacco_driver_phone_number" name="sacco_driver_phone_number" placeholder="Enter driver's phone number here...">
                                </div>
                            </div>
                            <div class="required field">
                                <label for="sacco_driver_license">Driver license number (9 Digits)</label>
                                <div class="ui labeled input">
                                    <div class="ui label">TDB</div>
                                    <input type="text" id="sacco_driver_license" maxlength="9" name="sacco_driver_license" placeholder="Enter driver's license ID here...">
                                </div>
                            </div>
                        </div>
                        <div class="required field">
                            <label for="sacco_driver_email_address">Email Address</label>
                            <div class="ui input">
                                <input type="email" id="sacco_driver_email_address" name="sacco_driver_email_address" placeholder="Enter driver's email address here...">
                            </div>
                        </div>
                        <div class="field">
                            <label for="sacco_assigned_bus">Assign bus</label>
                            <div class="ui fluid search selection dropdown">
                                <input type="hidden" id="sacco_assigned_bus" name="sacco_assigned_bus" data-validate="sacco_assigned_bus">
                                <div class="default text">
                                    <?php
                                    if(isset($free_buses)){
                                        echo "Search buses.";
                                    }else{
                                        echo "No un-assigned buses at the moment.";
                                    }
                                    ?>
                                </div>
                                <div class="menu">
                                    <?php
                                    if(isset($free_buses)){
                                        foreach ($free_buses as $free_bus){
                                            echo "<div class='item' data-value='".$free_bus->id."'>".$free_bus->plate." - Route: ".$free_bus->route_number."</div>";
                                        }
                                    }else{

                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <button class="ui green button" type="submit">Add driver.</button>
                        </div>
                    </form>
                </div>
                <div class="ui tab basic segment" data-tab="sacco_drivers_list" style="overflow-y: auto;">
                    <div class="ui large brown label" >Total sacco drivers :&nbsp;<?php echo isset($sacco_drivers)?count($sacco_drivers):0?></div>
                    <table class="ui sortable single line celled center aligned small table">
                        <thead>
                        <tr>
                            <th>Driver name</th>
                            <th>Email Address</th>
                            <th>Driver phone number</th>
                            <th>Driver license ID</th>
                            <th data-inverted="" data-tooltip="Assign bus to driver on the 'Sacco Buses' tab.">Assigned to a bus?</th>
                            <th>Operations</th>
                            <th>Reviews</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            if(isset($sacco_drivers)){
                                foreach ($sacco_drivers as $sacco_driver){
                                    $has_bus=($sacco_driver->has_assigned_bus)?"Yes":"No";
                                    $deassign_button =($sacco_driver->has_assigned_bus)?"<div class='field'>
                                                            <button type='submit' class='ui red mini icon button' name='sacco_driver_deassign' >
                                                                <i class='bus icon'></i>&nbsp; Deassign bus
                                                            </button>
                                                        </div>":"";
                                    echo "
                                         <tr>
                                            <td>$sacco_driver->first_name&nbsp;$sacco_driver->last_name</td>
                                            <td>$sacco_driver->email_address</td>
                                            <td>+254$sacco_driver->phone_number</td>
                                            <td>TDB$sacco_driver->drivers_license</td>
                                            <td>$has_bus</td>
                                            <td>
                                                <form class='ui form' enctype='multipart/form-data' method='POST' action='dashboard.php'>
                                                    <input type='hidden' value='".$sacco_driver->driver_id."' name='sacco_driver_id'>
                                                    <input type='hidden' value='sacco_driver_operation' name='sacco_driver_operation'>
                                                    <div class='fields'>
                                                        <div class='field'>
                                                            <button class='ui green mini icon button' type='submit' name='sacco_driver_update'>
                                                                <i class='write icon'></i>&nbsp; Edit / Update  
                                                            </button>
                                                        </div>
                                                        <div class='field'>
                                                            <button type='submit' class='ui red mini icon button' name='sacco_driver_delete' data-tooltip='Are you sure? This action cannot be undone.' data-inverted=''>
                                                                <i class='trash icon'></i>&nbsp; Delete
                                                            </button>
                                                        </div>
                                                        $deassign_button
                                                    </div>
                                                </form>
                                            </td>
                                            <td><a class='ui mini teal button' style='margin-bottom:13px;' href='/Transcomfy/admin_site/driver_reviews.php?id=$sacco_driver->public_id'>Reviews</a></td>
                                         </tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="ui basic tab segment" data-tab="sacco_account">
                <div class="ui secondary pointing menu">
                    <a class="active item" data-tab="sacco_account_sacco">
                        <i class="bus icon"></i>&nbsp;Sacco profile
                    </a>
                    <a class="item" data-tab="sacco_account_admin">
                        <i class="user icon"></i>&nbsp;Sacco admin profile
                    </a>
                    <a class="item" data-tab="sacco_account_delete">
                        <i class="trash icon"></i>&nbsp;Delete sacco
                    </a>
                </div>
                <div class="ui active tab basic segment" data-tab="sacco_account_sacco">
                    <form class="ui form" action="dashboard.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="sacco_account_sacco_edit" value="sacco_account_sacco_edit">
                        <div class="required field">
                            <label for="sacco_name">Sacco name</label>
                            <input type="text" name="sacco_name" value="<?php echo $sacco->name;?>" id="sacco_name" placeholder="Enter your sacco's name here...">
                        </div>
                        <div class="required field">
                            <label for="sacco_description">Sacco Description</label>
                            <textarea name="sacco_description" id="sacco_description" rows="5" ><?php echo $sacco->description;?></textarea>
                        </div>
                        <div class="field">
                            <button class="ui green button" type="submit">Update sacco information</button>
                        </div>
                    </form>
                </div>
                <div class="ui tab basic segment" data-tab="sacco_account_admin">
                    <form class="ui form" action="dashboard.php" method="POST" enctype="multipart/form-data">
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
                <div class="ui basic tab segment" data-tab="sacco_account_delete">
                    <div class="ui negative icon message">
                        <i class="warning sign icon"></i>
                        <div class="content">
                            <div class="header">Notice on sacco account termination.</div>
                            <p>The button below deletes this sacco's data from the database. All buses and drivers will be deleted too.</p>
                            <p>This action cannot be undone and therefore proceed with this process with maximum caution and due diligence.</p>
                        </div>
                    </div>
                    <form class="ui form" action="dashboard.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="sacco_account_delete" value="sacco_account_delete">
                        <div class="field">
                            <button class="ui red button" type="submit">Request for sacco deletion!</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>