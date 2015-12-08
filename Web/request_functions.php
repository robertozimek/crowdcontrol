<?php
  require_once('includes/database.php');

  function request_companies($db){
    $getComps = "SELECT `company_name`, `join_date` FROM company";
    $results = $db->query($getComps);
    $exists = mysqli_num_rows($results);

    if($exists){
      $comps = array();
      while($rows = $results->fetch_assoc()){
          $rooms[] = array("company_name" => $rows['company_name'], "join_date" => $rows['join_date']);
      }
      return json_encode($comps);
    }else{
      echo "There are no companies" . "<br>";
    }
  }

  function request_branches($db, $company){
    $getBranches = "SELECT c.company_id, c.company_name, b.branch_id, b.branch_address, b.longitude, b.latitude,
                    b.opening_hours, b.closing_hours FROM company AS c
                    INNER JOIN branch AS b on c.company_id = b.company_id WHERE c.company_name = '$company'";
    $results = $db->query($getBranches);
    $exists = mysqli_num_rows($results);

    if($exists){
      $branches = array();
      while($rows = $results->fetch_assoc()){
        $branches[] = array("company_name" => $company, "address" => $rows['branch_address'], "longitude" => $rows['longitude'],
                            "latitude" => $rows['latitude'], "opening_hours" => $rows['opening_hours'], "closing_hours" => $rows['closing_hours']);
      }
      return json_encode($branches);
    }
  }

  function request_rooms($db, $company, $branch){
    $query = "SELECT c.company_name, b.branch_address, r.room_number, r.max_capacity FROM `company` AS c
              INNER JOIN `branch` AS b on c.company_id = b.company_id
              INNER JOIN `room` AS r on b.branch_id = r.branch_id
              WHERE c.company_name = '$company' AND b.branch_address = '$branch'";
    $results = $db->query($query);
    $exists = mysqli_num_rows($results);

    if($exists){
      $rooms = array();
      while($rows = $results->fetch_assoc()){
        $rooms[] = array("address" => $rows['branch_address'], "room" => $rows['room_number'],
                          "max_capacity" => $rows['max_capacity']);
      }
      return json_encode(array($company => $rooms));
    }
  }

  function request_crowd_report($db, $company, $branch, $room){
    $query = "SELECT c.company_name, b.branch_address, r.room_number, r.people_in, r.people_out,
              r.max_capacity, r.time FROM `company` AS c
              INNER JOIN `branch` AS b on c.company_id = b.company_id
              INNER JOIN `room` AS r on b.branch_id = r.branch_id
              WHERE r.room_number = '$room' AND b.branch_address = '$branch' AND c.company_name = '$company'";
    $results = $db->query($query);
    $exists = mysqli_num_rows($results);

    if($exists){
      $rooms = $results->fetch_assoc();
      $total_in = $rooms['people_in'];
      $total_out = $rooms['people_out'];
      $max = $rooms['max_capacity'];
      $time = $rooms['time'];
      $curr_number = $total_in - $total_out;

      if($curr_number >= 0)
        $crowd_percent = round(($total_in - $total_out) / $max * 100);
      else
        $crowd_percent = 0;

      $room_info = array("company" => $company, "address" => $branch, "room" => $room, "time" => $time, "crowd" => $crowd_percent);
      $crowd_report = json_encode($room_info);
      echo $crowd_report;
    }else{
      echo mysqli_error($db);
    }
  }

 ?>
