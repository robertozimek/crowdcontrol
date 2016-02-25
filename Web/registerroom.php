<?php
    require_once('includes/database.php');
    //Openshift's php version does not include the updated password_* function
    //so use the library provided by github user ircmaxell
    require_once('includes/password_compat-master/lib/password.php');

    /**
     *  Adds a company to the database
     *  @param mysqli $db the database to add information to
     *  @param string $company the company to add to
     *  @param date $date the date when the company joined
     */
    function addCompany($db, $company, $date){
      $insert_comp = "INSERT INTO company (`company_name`, `join_date`) VALUES ('$company', '$date')";
      if($db->query($insert_comp) === TRUE)
        echo "Successfully inserted '$company' <br>";
      else {
        echo "Error inserting '$company' <br>";
      }
    }

    /**
     *  Adds a branch to an existing company
     *  @param mysqli $db the database to add information to
     *  @param string $company the company the branch belongs to
     *  @param string $branch the address of the branch to add
     *  @param string $type what kind of establishment is this
     *  @param time $opening_hours what time the establishment opens
     *  @param time $closing_hours what time the establishment closes
     */
    function addBranch($db, $company, $branch, $type, $opening_hours, $closing_hours){
      $insert_branch = "INSERT INTO branch (`company_id`, `branch_address`, `type`, `opening_hours`, `closing_hours`)
                        VALUES ((SELECT `company_id` FROM company WHERE `company_name` = '$company'), '$branch', '$type',
                        '$opening_hours', '$closing_hours')";
      if($db->query($insert_branch) === TRUE)
        echo "Successfully inserted '$branch' <br>";
      else
        echo "Error inserting '$branch' <br>";
    }

    /**
     *  Adds a room to an existing branch
     *  @param mysqli $db the database to add information to
     *  @param string $company the company the branch belongs to
     *  @param string $branch the address of the branch the room belongs to
     *  @param string $room the room number of the room to add
     *  @param int $max_cap the max capacity of the room
     */
    function addRoom($db, $company, $branch, $room, $max_cap){
      $sub_query = "SELECT c.company_id, b.branch_id, COALESCE(MAX(r.room_id), 0) AS `room_id` FROM company AS c
                    INNER JOIN branch b on b.company_id = c.company_id
                    INNER JOIN room r
                    WHERE b.branch_address = '$branch' AND c.company_name = '$company'";
      $results = $db->query($sub_query);
      $ids = $results->fetch_assoc();
      $branch_id = $ids['branch_id'];
      $room_id  = $ids['room_id'] + 1;

      //Generate random string and then hash it to retrieve the authentication key for the Pi
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
        echo "Error inserting '$room' <br>";
      }
    }

    //Generate random string to be used as password
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
