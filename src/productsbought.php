<?php 
    session_start();
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
        <?php
    
        $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 


        $userME = $userTotal['id_user'];

        $sqlr = 'SELECT * FROM product WHERE user_buy = ' . $userME;
        $retvalr = $conn->query($sqlr); 
         if ($retvalr->num_rows > 0) {
             $counter = 1;

        $sqlr = 'SELECT * FROM product WHERE user_buy = ' . $userME;    
        $retvalr = $conn->query($sqlr); 

                    ?>
                    <table class="table table-hover">
                    <thead>
                        <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Product_date</th>
                        </tr>
                    </thead>
                    <tbody>
                                       
                    <?php
                while($rowm = $retvalr->fetch_assoc())
                {
                                       
                    
                        ?>
                            <tr>
                            <th scope="row"><?=$counter++?></th>
                            <td><?=$rowm['name']?></td>
                            <td><?=$rowm['description']?></td>
                            <td><?=$rowm['price']?></td>
                            <td><?=$rowm['product_date']?></td>
                            </tr>
                                       
                        <?php   


                    }
                
         }else{
             ?>
             <br>
             <br>
             
             <div class="alert alert-danger" role="alert">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <span class="sr-only">No products:</span>
                You have not bought any product!!!! Hurry up!! The product of your dreams is in the platform
                </div>
             
             
             <?php
         }
        ?>
            </tbody>
        </table>
                        
    </div>
</body>
</html>