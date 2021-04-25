<?php 
    session_start();
    $userTotal=$_SESSION["identifiedUSerApp"];
    if (!isset($userTotal)){
        header("Location:/webAppCMU/index.php");
    }    

    $friendToShow = $_POST['toSaveFriend'];
    ?>

<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">  
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
  
    <div>
        <div>  
            <br>
            <br>
            <?php
                $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }             
                
                $sqlUser = 'SELECT * FROM user WHERE id_user = '.$friendToShow;
                $resultUser = $conn->query($sqlUser);

                while($rowUser = $resultUser->fetch_assoc()) {
                
                    echo '<span class="label label-warning">Username:  </span>'.$rowUser['username'];
                    echo '<br>';
                    echo '<span class="label label-warning">Name:  </span>'.$rowUser['name'];
                    echo '<br>';
                    echo '<span class="label label-warning">Member Since:  </span>'.$rowUser['create_time'];
                    echo '<br>';
                 }
            
            ?>
        </div>

        <div>
            <div style="border:1px solid #000; display:inline-block; text-align:center;">
                <?php
                
                $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                } 
                       $sql = 'SELECT * FROM product WHERE user_owner = '.$friendToShow. ' AND sold = 0 AND private_product = 0';

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


                                    <div id=<?=$divFinal?>></div>
                                    <?php

                                ?>
                            </div>
                        <?php
                        } 
                    }else{
                        echo 'User has no posted any product in the platform!!!';
                    }
                ?>
            </div>

            <div style="border:1px solid #000; display:inline-block; text-align:center;">
                <?php
                    echo 'groups your friend owns <br>';

                    $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    } 


                    $sqlowner = 'SELECT * FROM groups WHERE user_id_user = '.$friendToShow;

                    $retvalOwn = $conn->query($sqlowner);  
                    if ($retvalOwn->num_rows > 0) {
                        while($row = $retvalOwn->fetch_assoc())
                        {
                            $idgrouppost = $row['idgroup'];
                            echo '<span class="label label-primary">Name</span>' .$row['name'];
                            echo '<br>';
                            echo '<span class="label label-info">Description</span>' .$row['description'];
                            echo '<br>';
                            echo '<br>';
                        }
                    }else{
                        echo 'You have no created any group. ';
                    }

echo '<br>';echo '<br>';
                    echo 'groups your friend belongs <br>';
                    $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    } 


                    $sqlowner = 'SELECT * FROM group_user WHERE user_id_user = '.$friendToShow;

                    $retvalOwn = $conn->query($sqlowner);  
                    if ($retvalOwn->num_rows > 0) {
                        while($row = $retvalOwn->fetch_assoc())
                        {
                            $sqlForGroup = 'SELECT * FROM groups WHERE idgroup = '.$row['group_idgroup'];
                            $retvalGroup = $conn->query($sqlForGroup);  
                            while($rowG = $retvalGroup->fetch_assoc())
                            {
                                $idgrouppost = $rowG['idgroup'];
                                echo '<span class="label label-primary">Name</span>' .$rowG['name'];
                                echo '<br>';
                                echo '<span class="label label-info">Description</span>' .$rowG['description'];
                                echo '<br>';
                                echo '<br>';
                            }
                        }
                    }else{
                        echo 'You do not belong to any group. ';
                    }

                ?>
            </div>
        </div>

    </div>

</body>
</html>