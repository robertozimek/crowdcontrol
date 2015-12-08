<?php
    require_once('includes/database.php');

    function addCompany($db, $company, $date){
      $insert_comp = "INSERT INTO company (`company_name`, `join_date`) VALUES ('$company', '$date')";
      if($db->query($insert_comp) === TRUE)
        echo "Successfully inserted '$company' <br>";
      else {
        echo mysqli_error($db);
      }
    }

    function addBranch($db, $company, $branch){
      $insert_branch = "INSERT INTO branch (`company_id`, `branch_address`)
                        VALUES ((SELECT `company_id` FROM company WHERE `company_name` = '$company'), '$branch')";
      if($db->query($insert_branch) === TRUE)
        echo "Successfully inserted '$branch' <br>";
      else {
        echo mysqli_error($db);
      }
    }

    function addRoom($db, $company, $branch, $room, $max_cap){
      $sub_query = "SELECT c.company_id, b.branch_id FROM company AS c
                    INNER JOIN branch b on b.company_id = c.company_id
                    WHERE b.branch_address = '$branch' AND c.company_name = '$company'";
      $results = $db->query($sub_query);
      $ids = $results->fetch_assoc();
      $branch_id = $ids['branch_id'];

      $rand = genRandom();
      $hash = password_hash($rand, PASSWORD_DEFAULT);
      echo $rand . "<br>";

      $insert_room = "INSERT INTO room (`branch_id`, `room_number`, `max_capacity`, `key`)
                      VALUES ('$branch_id', '$room', '$max_cap', '$hash')";
      if($db->query($insert_room) === TRUE)
        echo "Successfully inserted '$room'" . "<br>";
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
 ?>
