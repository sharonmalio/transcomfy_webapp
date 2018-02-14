<div class="ui brown very padded segment">
    <a href="superuser.php" class="ui green right floated icon button"><i class="arrow left icon"></i>&nbsp;Go back to dashboard</a>
    <center>
        <div class="ui centered small image" >
            <img src="static/images/app_logo.JPG">
        </div>
    </center>
    <div class="ui brown center aligned header">Sacco bus trip and transactions report.</div>
    <div class="ui divider"></div>
    <div class="ui five column grid">
        <div class="column">
        </div>
        <div class="column">
        </div>
        <div class="column">
            <div class="ui tiny icon header">
                <i class="bus icon"></i>
                <p><?php echo $bus_plate->plate;?></p>
                <div class="sub header">Total Trips: <?php if(isset($bus_trips)){echo count($bus_trips);}?></div>
            </div>
        </div>
        <div class="column">
        </div>
        <div class="column">
        </div>
    </div>
        <?php

        $total_fare=0;
        if(isset($trip_commuters)){
            echo "
                <div class=\"ui divider\"></div>
                <div class='ui green label'>Trip ".$_POST['trip_id']." details</div>
                <table class='ui sortable celled center aligned table'>
                    <thead>
                        <tr>
                            <th>Commuter ID</th>
                            <th>Bus Fare</th>
                            <th>Start Coordinate</th>
                            <th>Stop Coordinate</th>
                            <th>Board Time</th>
                            <th>Alight Time</th>
                        </tr>
                    </thead>
                    <tbody>";
                    foreach ($trip_commuters as $trip_commuter){
                        $total_fare+=$trip_commuter->fare;
                        echo "<tr>
                                <td>$trip_commuter->id</td>
                                <td>KES $trip_commuter->fare</td>
                                <td>$trip_commuter->start_coordinate</td>
                                <td>$trip_commuter->stop_coordinate</td>
                                <td>$trip_commuter->board_time</td>
                                <td>$trip_commuter->alight_time</td>
                            </tr>";
                    }
                    echo "
                        <tr>
                                <td colspan='2'>
                                    <span><b>Total fare for this trip: </b></span>
                                    <div class='ui brown label'>KES $total_fare</div>
                                </td>
                                <td colspan='4'></td>
                            </tr>
                    </tbody>
                </table>                
            ";
        }
        ?>
    <div class="ui divider"></div>
    <table class="ui sortable celled center aligned table">
        <thead>
        <tr>
            <th>Trip ID</th>
            <th>Date</th>
            <th>Trip Date</th>
            <th>Trip Time</th>
            <th>Allocation ID</th>
            <th>Start Time</th>
            <th>Stop Time</th>
            <th>Start Stage</th>
            <th>End Stage</th>
            <th>Details</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if(isset($bus_trips)){
            foreach ($bus_trips as $bus_trip){
                echo "
                        <tr>
                            <td>$bus_trip->id</td>
                            <td>$bus_trip->date</td>
                            <td>$bus_trip->trip_date</td>
                            <td>$bus_trip->trip_time</td>
                            <td>$bus_trip->allocation_id</td>
                            <td>$bus_trip->start_time</td>
                            <td>$bus_trip->stop_time</td>
                            <td>$bus_trip->start_stage</td>
                            <td>$bus_trip->end_stage</td>
                            <td>
                                <form method='post' action='bus_view.php' enctype='multipart/form-data'>
                                    <input type='hidden' name='bus_id' value='".$_POST['bus_id']."'>
                                    <input type='hidden' name='trip_id' value='$bus_trip->id'>
                                    <button type='submit' class='ui blue button'>Trip details</button>
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