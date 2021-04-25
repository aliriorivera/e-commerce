<?php
    session_start();
    $userTotal=$_SESSION["identifiedUSerApp"];
    if (!isset($userTotal)){
        header("Location:/webAppCMU/index.php");
    }

    $protobuy = $_POST['protobuyPOST'];
    $meID = $userTotal['id_user'];


    $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = 'UPDATE product SET user_buy = ' .$meID. ' , sold = 1 WHERE idProduct = '.$protobuy.'';

    if ($conn->query($sql) === TRUE) {

        $seluser = 'SELECT * FROM product WHERE idProduct = ' .$protobuy;

            $name = '';
            $retvalr = $conn->query($seluser); 
            if ($retvalr->num_rows > 0) {
                $counter = 0; 
                    while($rowm = $retvalr->fetch_assoc())
                    { 
                        $name  = $rowm['name'];
                    }
            }

            $desc = 'Your friend ' . $userTotal['username']. ' bought this product  '. $name;
            $sql2 = 'INSERT INTO history (description, User_id_user) VALUES ("'.$desc.'", '.$userTotal2.')';
            $conn->query($sql2); 

        $conn->close();
        ?>
            <script>
                location.href = "/webAppCMU/productsbought.php";
            </script>
        <?php
    } else {
        echo "Error updating record: " . $conn->error;
        $conn->close();
    }

?>