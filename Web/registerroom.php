<?php
    require_once('includes/database.php');
    require_once('includes/password_compat-master/lib/password.php');
    //loQkRnn01Z 1000A 1
    //Xgxz5eDWIt 1000D 4
    //eumqQ06eko 45 E 51st 5
    //rn8fA0f3Gp 1000G 4
    function addCompany($db, $company, $date){
      $insert_comp = "INSERT INTO company (`company_name`, `join_date`) VALUES ('$company', '$date')";
      if($db->query($insert_comp) === TRUE)
        echo "Successfully inserted '$company' <br>";
      else {
        echo mysqli_error($db);
      }
    }

    function addBranch($db, $company, $branch, $type, $opening_hours, $closing_hours){
      $insert_branch = "INSERT INTO branch (`company_id`, `branch_address`, `type`, `opening_hours`, `closing_hours`)
                        VALUES ((SELECT `company_id` FROM company WHERE `company_name` = '$company'), '$branch', '$type',
                        '$opening_hours', '$closing_hours')";
      if($db->query($insert_branch) === TRUE)
        echo "Successfully inserted '$branch' <br>";
      else
        echo mysqli_error($db);
    }

    function addRoom($db, $company, $branch, $room, $max_cap){
      $sub_query = "SELECT c.company_id, b.branch_id, COALESCE(MAX(r.room_id), 0) AS `room_id` FROM company AS c
                    INNER JOIN branch b on b.company_id = c.company_id
                    INNER JOIN room r
                    WHERE b.branch_address = '$branch' AND c.company_name = '$company'";
      $results = $db->query($sub_query);
      $ids = $results->fetch_assoc();
      $branch_id = $ids['branch_id'];
      $room_id  = $ids['room_id'] + 1;

      $rand = genRandom();
      $hash = password_hash($rand, PASSWORD_DEFAULT);

      $insert_room = "INSERT INTO room (`room_id`, `branch_id`, `room_number`, `max_capacity`, `secret_key`)
                      VALUES ('$room_id','$branch_id', '$room', '$max_cap', '$hash')";
      if($db->query($insert_room) === TRUE){
        echo "Successfully inserted '$room'" . "<br>";
        echo "Your Pi's password is " . $rand . ". Keep it safe and secure. The Pi needs it to submit counts" . "<br>";
        echo "Your Pi's id is " . $room_id . ". Keep it safe and secure. The Pi also needs it to submit counts" . "<br>";
      }
      else {
        echo mysqli_error($db);
      }
    }

    function genRandom(){
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      $length = 10;
      for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
    }

    function getRoomOfCompany($db, $comp){
      $query = "SELECT r.room_number, r.branch_id, b.branch_id, b.company_id, b.branch_address, c.company_name, c.company_id FROM room AS r
                INNER JOIN branch b on r.branch_id = b.branch_id
                INNER JOIN company c on b.company_id = c.company_id
                WHERE c.company_name = '$comp'";
      $results = $db->query($query);
      $exists = mysqli_num_rows($results);

      if($exists){
        $data = array();
        while($rows = $results->fetch_assoc()){
          $data[] = array("company" => $rows['company_name'], "address" => $rows['branch_address'],"room" => $rows['room_number']);
        }
        echo json_encode(array("data" => $data));
      }
    }
 ?>
