<?php 
    session_start();
    $userTotal=$_SESSION["identifiedUSerApp"];
    if (!isset($userTotal)){
        header("Location:/webAppCMU/index.php");
    }

    $friendTodelete = $_POST['toSaveFriend'];


    $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $toDel = $userTotal['id_user'];
    $sqlDelete = "DELETE FROM friends WHERE user_id_user='$toDel' AND user_id_user_friend='$friendTodelete'";
    $resultDelete = $conn->query($sqlDelete);
    if ($resultDelete === TRUE) {
    }else{
        die("Connection failed: " . $conn->error);
    } 

    ?>
        <script>
            location.href = "/webAppCMU/searchfriends.php";
        </script>
    <?php   
?>
