<?php
    date_default_timezone_set('America/New_York');

    require_once('database.php');
    //Openshift's php version does not include the updated password_* function
    //so use the library provided by github user ircmaxell
    require_once('password_compat-master/lib/password.php');

    /**
     *  Checks whether a password matches the hashed record in the database
     *  @param string $pass the password to check if valid
     *  @param string $auth hashed value to check password against
     *  @return true if password matches, false otherwise
     */
    function checkPass($pass, $auth){
      if(!password_verify($pass, $auth)){
        http_response_code(403);
        exit;
      }else{
        return true;
      }
    }

    //Expecting {"in" : "data", "out" : "data", "id" : "data", "auth" : "data"} from POST requests
    $input = file_get_contents('php://input');
    $data = json_decode($input, TRUE);
    if($data){
      $peoplein = mysqli_real_escape_string($db, $data['in']);
      $peopleout = mysqli_real_escape_string($db, $data['out']);
      $room_id = mysqli_real_escape_string($db, $data['id']);
      $key = mysqli_real_escape_string($db, $data['auth']);

      $time = date("h:i:sa");
      $date = date('Y-m-d');
      $query = "SELECT `room_id`, `secret_key`, `people_in`, `people_out` FROM room WHERE `room_id` = '$room_id'";

      $results = $db->query($query);
      if($results){
        $rows = $results->fetch_assoc();

        //Make sure the PI's id matches the authentication key it sent before updating records in database
        if(checkPass($key, $rows['secret_key'])){
          $peoplein += $rows['people_in'];
          $peopleout += $rows['people_out'];
          $update = "UPDATE room SET `people_in` = '$peoplein', `people_out` = '$peopleout', `date` = '$date', `time` = '$time'
                     WHERE `room_id` = '$room_id'";
          if(!$db->query($update))
            echo mysqli_error($db);
        }
      }else{
        echo mysqli_error($db);
      }
    }
 ?>
