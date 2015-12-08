<?php
    require_once('includes/database.php');
    require_once('request_functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST')
      require_once('includes/auth.php');
    elseif($_SERVER['REQUEST_METHOD'] === 'GET'){
      if(isset($_GET["data"]) && !empty($_GET["data"])){

        if($_GET["data"] === "comp"){
          echo request_companies($db);

        }elseif($_GET["data"] === "branch" && !empty($_GET['comp'])){
          echo request_branches($db, mysqli_real_escape_string($db, $_GET['comp']));

        }elseif($_GET['data'] === "room" && !empty($_GET['comp']) && !empty($_GET['branch'])){
          echo request_rooms($db, mysqli_real_escape_string($db, $_GET['comp']), mysqli_real_escape_string($db, $_GET['branch']));

        }elseif($_GET['data'] === "crowd" && !empty($_GET['comp']) && !empty($_GET['addr']) && !empty($_GET['room'])){
          echo request_crowd_report($db, mysqli_real_escape_string($db, $_GET['comp']), mysqli_real_escape_string($db, $_GET['addr']),
                                    mysqli_real_escape_string($db, $_GET['room']));
        }
      }

      }else{
        echo "Missing arguments" . '<br>';
      }
    }

    $db->close();
?>
