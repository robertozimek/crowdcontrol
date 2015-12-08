<?php
    date_default_timezone_set('America/New_York');

    require_once('database.php');

    function checkPass($pass, $auth){
      if(!password_verify($pass, $auth)){
        header('HTTP/1.1 403 Forbidden');
        exit;
      }else{
        return true;
      }
    }

    $input = file_get_contents('php://input');
    $data = json_decode($input, TRUE);
    if($data){
      $peoplein = mysqli_real_escape_string($db, $data['in']);
      $peopleout = mysqli_real_escape_string($db, $data['out']);
      $room_id = mysqli_real_escape_string($db, $data['id']);
      $key = mysqli_real_escape_string($db, $data['auth']);
      $time = date("h:i:sa");
      $date = date('Y-m-d');
      $query = "SELECT `room_id`, `key`, `people_in`, `people_out` FROM room WHERE `room_id` = '$room_id'";

      $results = $db->query($query);
      if($results){
        $rows = $results->fetch_assoc();
        if(checkPass($key, $rows['key'])){
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
