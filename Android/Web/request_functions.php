<?php
  require_once('includes/database.php');

  function request_companies($db){
    $getComps = "SELECT `company_name`, `join_date` FROM company";
    $results = $db->query($getComps);
    $exists = mysqli_num_rows($results);

    if($exists){
      $companies = array();
      while($rows = $results->fetch_assoc()){
          $companies[] = array("company_name" => $rows['company_name'], "join_date" => $rows['join_date']);
      }
      return json_encode(array("companies" => $companies));
    }else{
      http_response_code(404);
      exit;
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
      return json_encode(array("branches" => $branches));
    }else{
      http_response_code(404);
      exit;
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
        $rooms[] = array("company" => $rows['company_name'], "address" => $rows['branch_address'], "room" => $rows['room_number'],
                          "max_capacity" => $rows['max_capacity']);
      }
      return json_encode(array("rooms" => $rooms));
    }else{
      http_response_code(404);
      exit;
    }
  }

  function request_crowd_report($db, $company, $branch, $room){
    $query = "SELECT c.company_name, b.branch_address, r.room_id, r.room_number, r.people_in, r.people_out,
              r.max_capacity, r.date, r.time FROM `company` AS c
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
      $date = $rooms['date'];
      $curr_number = $total_in - $total_out;

      if($curr_number >= 0){
        $crowd_percent = round(($total_in - $total_out) / $max * 100);
        if($crowd_percent > 100){
          $crowd_percent = 100;
        }
      }else{
        $crowd_percent = 0;
      }
      $room_info = array("company" => $company, "address" => $branch, "room" => $room, "date" => $date,"time" => $time,
                        "crowd" => $crowd_percent);
      return json_encode(array("crowd" => $room_info));
    }else{
      http_response_code(404);
      exit;
    }
  }


  function isClosed($room_id){
    $query = "SELECT r.room_id, r.branch_id, b.branch_id, b.closing_hours FROM roon AS r
              INNER JOIN branch AS b on r.branch_id = b.branch_id WHERE r.room_id = '$room_id'";
    $results = $db->query($query);
    if($results){
      $rows = $results->fetch_assoc();
      $curr_time = date("h:i:sa");
      if($rows['closing_hours'] < $curr_time){
        return true;
      }
    }
    return false;
  }
 ?>