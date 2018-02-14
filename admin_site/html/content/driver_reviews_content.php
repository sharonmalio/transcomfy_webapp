<div class="ui brown very padded segment">
    <a href="dashboard.php" class="ui green right floated icon button"><i class="arrow left icon"></i>&nbsp;Go back to dashboard</a>
    <center>
        <div class="ui centered small image" >
            <img src="static/images/app_logo.JPG">
        </div>
    </center>
    <div class="ui brown center aligned header">Sacco driver reviews report.</div>
    <div class="ui divider"></div>
        <div class="ui five column grid">
            <div class="column">
                <div class="ui tiny icon header">
                    <i class="user icon"></i>
                    <p>Driver name</p>
                    <div class="sub header"><?php echo $driver->first_name." ".$driver->last_name?></div>
                </div>
            </div>
            <div class="column">
                <div class="ui tiny icon header">
                    <i class="call icon"></i>
                    <p>Phone number</p>
                    <div class="sub header">+254 <?php echo $driver->phone_number?></div>
                </div>
            </div>
            <div class="column">
                <div class="ui tiny icon header">
                    <i class="file icon"></i>
                    <p>Driver License</p>
                    <div class="sub header">TDB <?php echo $driver->drivers_license?></div>
                </div>
            </div>
            <div class="column">
                <div class="ui tiny icon header">
                    <i class="home icon"></i>
                    <p>Sacco</p>
                    <div class="sub header"><?php echo $sacco->name?></div>
                </div>
            </div>
            <div class="column">
                <div class="ui tiny icon header">
                    <i class="star icon"></i>
                    <p>Average rating</p>
                    <div class="sub header"><?php echo $average_rating?></div>
                </div>
            </div>
        </div>
    <div class="ui divider"></div>
    <table class="ui celled center aligned table">
        <thead>
            <tr>
                <th>Bus number plate</th>
                <th>Driver review</th>
                <th>Rating</th>
                <th>Time of review</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if(isset($reviews)){
                    foreach ($reviews as $review){
                        echo "
                        <tr>
                            <td>$review->number_plate</td>
                            <td>$review->review</td>
                            <td><div class='ui driver rating' data-max-rating='5' data-rating='$review->rating'></div></td>
                            <td>$review->time_of_review</td>
                        </tr>
                    ";
                    }
                }
            ?>
        </tbody>
    </table>
</div>