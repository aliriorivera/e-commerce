<?php session_start();
    $userTotal=$_SESSION["identifiedUSerApp"];
    if (!isset($userTotal)){
        header("Location:/webAppCMU/index.php");
    }
    
?>

<!DOCTYPE html>
<html>
<head>
</head>

<body>


<?php include 'menucontext.php'; ?>


<div>
<br>

 <div class="alert alert-success" role="alert"><h2>Latest activity from friends</h2></div>

        <?php
    
            $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 


            $userTotal2 = $userTotal['id_user'];
            $sql = 'SELECT description, history_date FROM ( SELECT user_id_user_friend FROM  friends WHERE user_id_user = '.$userTotal2.') uno join history dos on uno.user_id_user_friend = dos.User_id_user order by history_date desc';

            $retvalr = $conn->query($sql); 
            if ($retvalr->num_rows > 0) { 

                $counter = 1;
            ?>
              

                    <table class="table table-hover">
                    <thead>
                        <tr>
                        <th>#</th>
                        <th>History Date</th>
                        <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                                       
                    <?php
                while($rowm = $retvalr->fetch_assoc())
                {
                    ?>
                            <tr>
                            <th scope="row"><?=$counter++?></th>
                            <td><?=$rowm['history_date']?></td>
                            <td><?=$rowm['description']?></td>
                            </tr>
                                       
                        <?php
                }
        ?>
                </tbody>
        </table>
        
        <?php
            }else{
                echo 'No hay nada!!';
            }

        ?>
</div>


</body>
</html>
