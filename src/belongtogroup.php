<?php
    session_start();
    $userTotal=$_SESSION["identifiedUSerApp"];
    if (!isset($userTotal)){
        header("Location:/webAppCMU/index.php");
    }

    $groupIdPost = $_POST['groupId'];


    $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $myuser = $userTotal['id_user'];
    $sql = 'INSERT INTO group_user (group_idgroup, user_id_user) VALUES ('.$groupIdPost.', '.$myuser.')';

    $result = $conn->query($sql);  

    if($result == true){
        setcookie('registeredGROUPBELONG', "registered successfully in the group.", time() + 100, '/');
    }else{
        echo mysqli_error($conn);
    }

    ?>
        <script>
            location.href = "/webAppCMU/groupspage.php";
        </script>
    <?php
?>
