<?php 
    session_start();
    $userTotal=$_SESSION["identifiedUSerApp"];
    if (!isset($userTotal)){
        header("Location:/webAppCMU/index.php");
    }

    $namePost = $_POST['nameGroup'];
    $descriptionPost = $_POST['descriptionGroup'];

    $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sql1 = 'SELECT * FROM groups WHERE name ="'.$namePost.'"';

    $retval = $conn->query($sql1);  
    if ($retval->num_rows == 0) {
        $user = $userTotal['id_user'];
        $sql2 = 'INSERT INTO groups (name, description, user_id_user) values ("'.$namePost.'" , "'.$descriptionPost.'", '.$user.')';
        $conn->query($sql2);  
        setcookie('registeredGROUP', "Group registered successfully.", time() + 100, '/');
    }else{
        setcookie('registeredGROUP', "There is another group with the same name.", time() + 100, '/');
    }
    ?>
        <script>
            location.href = "/webAppCMU/groupspage.php";
        </script>
    <?php
?>
