<?php
    session_start();
    $userTotal=$_SESSION["identifiedUSerApp"];
    if (!isset($userTotal)){
        header("Location:/webAppCMU/index.php");
    }

    $friendshipPost = $_POST['newFriend'];


     $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $userTotal2 = $userTotal['id_user'];
    $sqlDelete = "DELETE FROM user_invitations WHERE user_id_user='$friendshipPost' AND user_id_user_invited_to='$userTotal2'";
    $resultDelete = $conn->query($sqlDelete);
    if ($resultDelete === TRUE) {
        $sql = 'INSERT INTO friends (user_id_user, user_id_user_friend) VALUES ('.$userTotal2.', '.$friendshipPost.')';
        $conn->query($sql);


        $seluser = 'SELECT * FROM user WHERE id_user = ' .$friendshipPost;

        $name = '';
        $retvalr = $conn->query($seluser); 
         if ($retvalr->num_rows > 0) {
             $counter = 0; 
                while($rowm = $retvalr->fetch_assoc())
                { 
                    $name  = $rowm['username'];
                }
         }

        $desc = 'Your friend ' . $userTotal['username']. ' is now friend with '. $name;
        $sql2 = 'INSERT INTO history (description, User_id_user) VALUES ("'.$desc.'", '.$userTotal2.')';

        $conn->query($sql2); 
    }else{
        echo $conn->error;
    }
    ?>
        <script>
            location.href = "/webAppCMU/searchfriends.php";
        </script>
    <?php
?>