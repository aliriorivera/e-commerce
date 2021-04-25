<?php 
    session_start();
    $userTotal=$_SESSION["identifiedUSerApp"];
    if (!isset($userTotal)){
        header("Location:/webAppCMU/index.php");
    }    

    $friendToShow = $_POST['toSaveFriend'];
    
    
    ?>
    
    <div>
        <div>  
            
            <?php
                $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }             
                
                $sqlUser = 'SELECT * FROM user WHERE id_user = '.$friendToShow;
                $resultUser = $conn->query($sqlUser);

                while($rowUser = $resultUser->fetch_assoc()) {
                    echo $rowUser['id_user'];
                    echo '<br>';
                    echo $rowUser['username'];
                    echo '<br>';
                    echo $rowUser['name'];
                    echo '<br>';
                    echo $rowUser['id_user'];
                 }
            
            ?>
        </div>

        <div>
            <div style="border:1px solid #000; display:inline-block; text-align:center;">
                aqui van los productos publicos.
            </div>

            <div style="border:1px solid #000; display:inline-block; text-align:center;">
                aqui va los grupos a los que pertenece.
            </div>
        </div>

    </div>
    
    <?php
    
    //echo $friendToShow;
?>