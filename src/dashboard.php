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



<br><br>
<div>


     <div>
     <?php
    
        $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $userME = $userTotal['id_user'];

        $sqlr = 'SELECT * FROM product WHERE private_product = 0 AND sold = 0 ORDER BY product_date DESC';
        $retvalr = $conn->query($sqlr); 
         if ($retvalr->num_rows > 0) {
             $counter = 0;

                while($rowm = $retvalr->fetch_assoc())
                {  
                    $counter++;
                }
         }

         ?>
            <div class="panel panel-primary">

                <strong>There are <?=$counter?> new Products to buy!!!!</strong>
            
            </div>
         <?php
        
        ?> 



    </div>
    <div>

    <?php
    
        $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $userME = $userTotal['id_user'];

        $sqlr = 'SELECT * FROM product WHERE sold = 1 AND user_buy = '.$userME.' ORDER BY product_date DESC';
        $retvalr = $conn->query($sqlr); 
         if ($retvalr->num_rows > 0) {
             $counter = 0; 
                while($rowm = $retvalr->fetch_assoc())
                {  
                    $counter++;
                }
         }

         ?>
            <div class="panel panel-info">

                <strong> You have bought  <?=$counter?>  Products in the platform!!!!</strong>
            
            </div>
         <?php
        
        ?> 



    </div>

    <div>


        <div class="alert alert-success" role="alert"><h2>Newest public products</h2></div>

    <?php
    
        $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 

        $userME = $userTotal['id_user'];

        $sqlr = 'SELECT * FROM product WHERE private_product = 0 AND sold = 0 ORDER BY product_date DESC';
        $retvalr = $conn->query($sqlr); 
         if ($retvalr->num_rows > 0) {
             $counter = 1;

                    ?>
                    <table class="table table-hover">
                    <thead>
                        <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Product_date</th>
                        <th>.</th>
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
                            <td>
                                <form action="/webAppCMU/buyprodfinal.php" method="post">
                                        <input type="hidden" value=<?=$rowm['idProduct']?> name = "protobuyPOST"></input>
                                        <input type ="submit" value  = "BUY NOW!!"></input>
                                    </form>
                            </td>
                            </tr>
                                       
                        <?php   


                    }
                
         }
        ?>
            </tbody>
        </table>
                        
    </div>  
</div>
    </body>
</html>