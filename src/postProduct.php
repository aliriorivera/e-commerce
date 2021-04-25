<?php
    session_start();
    $row=$_SESSION["identifiedUSerApp"];
    if (!isset($row)){
        header("Location:/webAppCMU/index.php");
    }


    $namePost = $_POST['name'];
    $descriptionPost = $_POST['description'];
    $pricePost = $_POST['price'];
    $tagsPost = $_POST['tags'];

    
    $privatePost =  0;
    if(isset($_POST['private'])){
        $privatePost = 1;
    }

    $conn=mysql_connect("localhost", "root", "rootpassword") or die (mysql_error($conn)); //nos conectamos a la base de atos
    mysql_select_db("enterprisewebapp", $conn) or die (mysql_error($conn)); //cambiamos de base de datos

    if(! $conn )
    {
        die('Could not connect: ' . mysql_error());
    }

    $user_id = $row["id_user"];
    $notSold = 0;
    $sql = "INSERT INTO product ".
        "(name, description, price, private_product, sold, user_owner) ".
        "VALUES ".
        "('$namePost','$descriptionPost','$pricePost', $privatePost, $notSold, $user_id)";
    $retval = mysql_query( $sql, $conn );
    if(! $retval )
    {
        die('Could not enter data: ' . mysql_error());
    }else{
        $productIdSaved = mysql_insert_id();
        // save tags as array php

         $connT = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
        // Check connection
        if ($connT->connect_error) {
            die("Connection failed: " . $connT->connect_error);
        } 

        $myArray = explode(',', $tagsPost);
        foreach($myArray as $tagToSave){

            $sqltag = 'SELECT * FROM tag WHERE name ="' . trim($tagToSave) . '"';
            $resultTag = $connT->query($sqltag);

            if ($resultTag->num_rows == 0) {
                $sqlSaveTag = 'INSERT INTO tag (name) VALUES ("'.trim($tagToSave).'")';
                $connT->query($sqlSaveTag);
                $last_id = $connT->insert_id;
                //save into tag_product table
                $sqlInsertTagProduct = 'INSERT INTO product_tags (product_idProduct, tag_idTag) VALUES ('.$productIdSaved.', '.$last_id.')';
                $connT->query($sqlInsertTagProduct);
            }else{
                //if found?
                 while($rowTag = $resultTag->fetch_assoc()) {
                    $idTagDB = $rowTag["idTag"];
                    $sqlInsertTagProduct = 'INSERT INTO product_tags (product_idProduct, tag_idTag) VALUES ('.$productIdSaved.', '.$idTagDB.')';
                    $connT->query($sqlInsertTagProduct);
                 }
            }
        }
    }
    mysql_close($conn);
    setcookie('registeredPRODUCT', "Product registered succesfully.", time() + 100, '/');
    ?>
        <script>
            location.href = "/webAppCMU/products.php";
        </script>
    <?php
    
?>