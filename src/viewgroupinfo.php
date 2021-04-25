<?php 
    session_start();
    $userTotal=$_SESSION["identifiedUSerApp"];
    if (!isset($userTotal)){
        header("Location:/webAppCMU/index.php");
    }
    $groupId = $_POST['idGroup'];
?>

<html>
<head>
    <title>Info about Group</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script>
			function toggle(elemToHide) {
                var elementFinalToHide = document.getElementById(elemToHide);
                var fff = "#" +elemToHide;
                console.log(fff);
				if (elementFinalToHide.style.display == "block") {
                    $( fff ).hide(1000);
                    elementFinalToHide.style.display = "none";
				}
				else {
                    $( fff ).show(1000);
                    elementFinalToHide.style.display = "block";
				}
			}
		</script>

        <script>
        
            function updateAllCheckBoxValues(productId, likeVar, FavorVar, bookVar, userIDVar){
                var productTosend = document.getElementById(productId);
                var stringToSendPost = 'product=' + productTosend.id + '&user=' + userIDVar;

                if (likeVar.checked){
                    stringToSendPost += '&like=true'
                }else{
                    stringToSendPost += '&like=false'
                }
                if (FavorVar.checked){
                    stringToSendPost += '&favorite=true'
                }else{
                    stringToSendPost += '&favorite=false'
                }
                if (bookVar.checked){
                    stringToSendPost += '&bookmark=true'
                }else{
                    stringToSendPost += '&bookmark=false'
                }

                var whatDiv = '#saveDiv' + productTosend.id;
                console.log(whatDiv);
                    $.ajax({
                            type: "POST",
                            url: "saveTagsFavoritesBookmarks.php",
                            data: stringToSendPost,
                            cache: false,
                            success: function(html) {
                                $(whatDiv).html(html);
                        }
                    });
                return false;
            }
            
        </script>
</head>

<body>

<div style="text-align:center;">
    <div style="border:1px solid #000; display:inline-block; text-align:left;">
    <h1>Group Information</h1>

        <?php
            
            $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 

            $sql = 'SELECT * FROM groups WHERE idgroup = '.$groupId;

            $retval = $conn->query($sql);  
            if ($retval->num_rows > 0) {   
                while($row = $retval->fetch_assoc())
                {
                    echo '<span class="label label-primary">Name</span>' . $row['name'];
                    echo '<br>';
                    echo '<span class="label label-primary">Description</span>' . $row['description'];
                    echo '<br>';
                    echo '<span class="label label-primary">Created Time</span>' . $row['group_create_time'];
                }
            }
        ?>
    </div>

    <div style="border:1px solid #000; display:inline-block; text-align:left;">

        <?php

            echo '<h2>Group Admin</h2>';
            $sql = 'SELECT * FROM groups WHERE idgroup = '.$groupId;

            $retval = $conn->query($sql);  
            if ($retval->num_rows > 0) {   
                while($row = $retval->fetch_assoc())
                {
                    $sql2 = 'SELECT * FROM user WHERE id_user = '.$row['user_id_user'];
                    $retval2 = $conn->query($sql2);  
                    if ($retval2->num_rows > 0) {   
                        while($row2 = $retval2->fetch_assoc())
                        {
                            echo '<span class="label label-danger">Admin</span>';
                            echo  $row2['username'];
                            echo '<br>';
                        }
                    }
                }
            }



            echo '<h2>Group Users</h2>';
            $sql = 'SELECT * FROM group_user WHERE group_idgroup = '.$groupId;

            $retval = $conn->query($sql);  
            if ($retval->num_rows > 0) {   
                while($row = $retval->fetch_assoc())
                {
                    $sql2 = 'SELECT * FROM user WHERE id_user = '.$row['user_id_user'];
                    $retval2 = $conn->query($sql2);  
                    if ($retval2->num_rows > 0) {   
                        while($row2 = $retval2->fetch_assoc())
                        {
                            echo  $row2['username'];
                            echo '<br>';
                        }
                    }
                }
            }else{
                        echo 'Currently, this group does not have users';
                    }
        ?>
    </div>
</div>


<div style="text-align:center;">
    <div style="border:1px solid #000; display:inline-block; text-align:left;">

    <h1>Group Products</h1>
        <?php
//////////////////////////////////////////////////////////////////////////////////////////////        
        
        $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 


        $sqlr = 'SELECT * FROM group_product WHERE group_idgroup = ' . $groupId;
        $retvalr = $conn->query($sqlr); 
         if ($retvalr->num_rows > 0) {
                while($rowr = $retvalr->fetch_assoc())
                {
                        $sql = 'SELECT * FROM product WHERE idProduct = '.$rowr['product_idProduct'];

                        $retval = $conn->query($sql);                       

                    if ($retval->num_rows > 0) {
                        while($row = $retval->fetch_assoc())
                        {

                                $i = $row['idProduct'];
                                $toName = $i;
                                $toLike = "like". $i;
                                $toFavor = "favor". $i;
                                $toBook = "book". $i;
                                $divFinal = "saveDiv" .$i;
                                $userToChange = $userTotal['id_user'];

                        ?>
                            <div onclick="toggle(<?= $toName ?>);">
                                <?php
                                    echo "ID :{$row['name']}  <br> ";
                                ?>
                            </div>
                            
                            <div id=<?= $toName ?> style="display: none">
                                <?php
                                echo "name :{$row['name']}  <br> ".
                                    "description: {$row['description']} <br> ".
                                    "price: {$row['price']} <br> ".
                                    "Product Date : {$row['product_date']} <br> ".
                                    "--------------------------------<br>";


                                    //test if the product was liked or favorited or bokkmarked by the user

                                    $chechedLike = '';
                                    $sqlLike = "SELECT * FROM LikeT WHERE user_id_user='$userToChange' AND Product_idProduct='$i'";
                                    $resultLike = $conn->query($sqlLike);
                                    if ($resultLike->num_rows > 0) {
                                        $chechedLike = 'checked';
                                    }

                                    $chechedFavorite = '';
                                    $sqlFavorite = "SELECT * FROM Favorite WHERE user_id_user='$userToChange' AND Product_idProduct='$i'";
                                    $resultFavorite = $conn->query($sqlFavorite);
                                    if ($resultFavorite->num_rows > 0) {
                                        $chechedFavorite = 'checked';
                                    }

                                    $chechedBookmark = '';
                                    $sqlBookmark = "SELECT * FROM Bookmark WHERE user_id_user='$userToChange' AND Product_idProduct='$i'";
                                    $resultBookmark = $conn->query($sqlBookmark);
                                    if ($resultBookmark->num_rows > 0) {
                                        $chechedBookmark = 'checked';
                                    }
                                    ?>
                                    
                                    <form>                                
                                        Like: <input type="checkbox" name=<?= $toLike ?> value="yes" <?php echo $chechedLike; ?>>
                                        Favorite:<input type="checkbox" name=<?= $toFavor ?> value="yes" <?php echo $chechedFavorite; ?>>
                                        Bookmark:<input type="checkbox" name=<?= $toBook ?> value="yes" <?php echo $chechedBookmark; ?>>
                                        <input id="" onclick="updateAllCheckBoxValues(<?= $toName ?>,<?= $toLike ?>,
                                                                                        <?= $toFavor ?>,<?= $toBook ?>, 
                                                                                        <?= $userToChange ?>)" 
                                                                                        type="button" value="Update">                                
                                    </form>

                                    <form action="/webAppCMU/buyprodfinal.php" method="post">
                                        <input type="hidden" value=<?=$toName?> name = "protobuyPOST"></input>
                                        <input type ="submit" value  = "BUY NOW!!"></input>
                                    </form>


                                    <div id=<?=$divFinal?>></div>
                                    <?php

                                ?>
                            </div>
                        <?php
                        } 
                    }
                        echo "Fetched data successfully\n";
				}
                $conn->close();
         }else{
             echo 'This group has no products to show!!';
         }
        
        
        
        
 ///////////////////////////////////////////////////////////////////////////////////////////       
        ?>

    </div>

    <div style="border:1px solid #000; display:inline-block; text-align:center;">

        <?php
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        if(isset($_POST['savegroupPRODUCT'])) {
            
             $namePost = $_POST['nameP'];
             $descriptionPost = $_POST['descriptionP'];
             $pricePost = $_POST['priceP'];
             $tagsPost = $_POST['tagsP'];
             $privatePost = 1;
            

            $conn=mysql_connect("localhost", "root", "rootpassword") or die (mysql_error($conn)); //nos conectamos a la base de atos
            mysql_select_db("enterprisewebapp", $conn) or die (mysql_error($conn)); //cambiamos de base de datos

            if(! $conn )
            {
                die('Could not connect: ' . mysql_error());
            }

            $user_id = $userTotal["id_user"];
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


                //save product to group


                $sqlTosaveGroup = 'INSERT INTO group_product (group_idgroup, product_idProduct) VALUES ('.$groupId.','.$productIdSaved.')';
                $resultTag = $connT->query($sqlTosaveGroup);

                if($resultTag == true){
                    echo 'product saved successfully';
                }else{
                    echo mysql_error;
                }

            }
            mysql_close($conn);
        

        }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ?>

        <h1>Post a product in the group!!</h1>
        <form action="<?php $_PHP_SELF ?>" method="post">
                Name:<br>
                    <input type="text" name="nameP" >
                    <br>
                description :<br>
                    <textarea name="descriptionP"></textarea>
                    <br>
                price :<br>
                    <input type="text" name="priceP">
                    <br>
                tags :<br>
                    <input type="text" name="tagsP">
                    <br>    
                    <input type="hidden" name ="idGroup"  value = <?=$groupId?>></input>            
                <br>
                <input type="submit" name ="savegroupPRODUCT" value="Submit Product">
        </form> 
    </div>
</div>



</body>

</html>





