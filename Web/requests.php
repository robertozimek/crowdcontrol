<?php
    require_once('includes/database.php');
    //PHP script containing the functions responsible for CRUD operations on the database
    require_once('request_functions.php');

    //Authenticate the POST request sent by a PI
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
      require_once('includes/auth.php');
    }elseif($_SERVER['REQUEST_METHOD'] === 'GET'){
      if(isset($_GET["data"]) && !empty($_GET["data"])){
        //The functions will all be returning JSON data
        header('Content-Type: application/json');

        //Handle requests accordingly and if there are missing or misspelled arguments set status code 400 for Bad Request
        if($_GET["data"] === "comp"){
          echo request_companies($db);

        }elseif($_GET["data"] === "branch" && !empty($_GET['comp'])){
          echo request_branches($db, mysqli_real_escape_string($db, $_GET['comp']));

        }elseif($_GET['data'] === "room" && !empty($_GET['comp']) && !empty($_GET['branch'])){
          echo request_rooms($db, mysqli_real_escape_string($db, $_GET['comp']), mysqli_real_escape_string($db, $_GET['branch']));

        }elseif($_GET['data'] === "crowd" && !empty($_GET['comp']) && !empty($_GET['branch']) && !empty($_GET['room'])){
          echo request_crowd_report($db, mysqli_real_escape_string($db, $_GET['comp']), mysqli_real_escape_string($db, $_GET['branch']),
                                    mysqli_real_escape_string($db, $_GET['room']));
        }else{
          http_response_code(400);
          exit;
        }
      }else{
        http_response_code(400);
        exit;
      }
    }
    $db->close();
?>
