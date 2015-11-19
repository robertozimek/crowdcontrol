<?php 
    require_once('includes/database.php');
    
    if($_GET){
        if(isset($_GET['estab']) && isset($_GET['room'])){
            $estab = $_GET['estab'];
            $room = $_GET['room'];
            
            $query = "SELECT `peoplein`, `peopleout`, `maxcap`, `time` FROM roomsinservice WHERE `establishment` = '$estab' AND `room` = '$room'";
            $results = $db->query($query);
            $exists = mysqli_num_rows($results);
            if($exists){
                $total_in = 0;
                $total_out = 0;
                $max = 0;
                $time = time();
                    
                while ($rooms = $results->fetch_assoc()){
                    $total_in += $rooms['peoplein'];
                    $total_out += $rooms['peopleout'];
                    $max = $rooms['maxcap'];
                    $time = $rooms['time'];
                }
                
                $crowd_percent = round(($total_in - $total_out) / $max * 100);
                $room_info = array("establishment" => $estab, "room" => $room, "time" => $time, "crowd" => $crowd_percent);
                $return = json_encode($room_info);
                echo $return;
            }else{
                echo "Unable to get data<br>";
            }
        $results->free();    
        }else if(isset($_GET['avail']) && ($_GET['avail'] == "rooms")){
            $query = "SELECT  DISTINCT `room`, `address`, `establishment` FROM roomsinservice";
            $results = $db->query($query);
            $exists = mysqli_num_rows($results);
            
            if($exists){
                $rooms = array();
                $index = 0;
                while($rows = $results->fetch_assoc()){
                    $room = array("establishment" => $rows['establishment'], "Room" => $rows['room'],
                                             "address" => $rows['address']);
                    $rooms[$index] = $room;
                    $index++;
                }
                $return = json_encode($rooms);
                echo $return;
            }else{
                echo "There are no rooms<br>";
            }
            $results->free();
        }else
            echo "no data";
    }
    
    $db->close();
?>