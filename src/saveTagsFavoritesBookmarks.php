<?php
    session_start();
    $userTotal=$_SESSION["identifiedUSerApp"];
    if (!isset($userTotal)){
        header("Location:/webAppCMU/index.php");
    }

    // Fetching Values From URL


    $productPost = $_POST['product'];
    $userPost = $_POST['user'];
    $likePost = $_POST['like'];
    $favoritePost = $_POST['favorite'];
    $bookmarkPost = $_POST['bookmark'];
    $userTotal2 = $userTotal['id_user'];

    $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    $sqlLike = "SELECT * FROM LikeT WHERE user_id_user='$userPost' AND Product_idProduct='$productPost'";
    $resultLike = $conn->query($sqlLike);

    if($likePost == 'true'){
        if ($resultLike->num_rows == 0) {
            $sql = "INSERT INTO LikeT (user_id_user, Product_idProduct) VALUES ($userPost, $productPost)";
            $conn->query($sql);
            

        $seluser = 'SELECT * FROM product WHERE idProduct = ' .$productPost;

        $name = '';
        $retvalr = $conn->query($seluser); 
         if ($retvalr->num_rows > 0) {
             $counter = 0; 
                while($rowm = $retvalr->fetch_assoc())
                { 
                    $name  = $rowm['name'];
                }
         }

        $desc = 'Your friend ' . $userTotal['username']. ' liked the product  '. $name;
        $sql2 = 'INSERT INTO history (description, User_id_user) VALUES ("'.$desc.'", '.$userTotal2.')';

        $conn->query($sql2); 
        }
    }else{
        if ($resultLike->num_rows > 0) {
            $sql = "DELETE FROM LikeT WHERE user_id_user='$userPost' AND Product_idProduct='$productPost'";
            $conn->query($sql);

            $seluser = 'SELECT * FROM product WHERE idProduct = ' .$productPost;

            $name = '';
            $retvalr = $conn->query($seluser); 
            if ($retvalr->num_rows > 0) {
                $counter = 0; 
                    while($rowm = $retvalr->fetch_assoc())
                    { 
                        $name  = $rowm['name'];
                    }
            }

            $desc = 'Your friend ' . $userTotal['username']. ' unliked the product  '. $name;
            $sql2 = 'INSERT INTO history (description, User_id_user) VALUES ("'.$desc.'", '.$userTotal2.')';
            $conn->query($sql2); 
        }
    }

    $sqlFavorite = "SELECT * FROM Favorite WHERE user_id_user='$userPost' AND Product_idProduct='$productPost'";
    $resultFavorite = $conn->query($sqlFavorite);

    if($favoritePost == 'true'){
        if ($resultFavorite->num_rows == 0) {
            $sql = "INSERT INTO Favorite (user_id_user, Product_idProduct) VALUES ($userPost, $productPost)";
            $conn->query($sql);

            $seluser = 'SELECT * FROM product WHERE idProduct = ' .$productPost;

        $name = '';
        $retvalr = $conn->query($seluser); 
         if ($retvalr->num_rows > 0) {
             $counter = 0; 
                while($rowm = $retvalr->fetch_assoc())
                { 
                    $name  = $rowm['name'];
                }
         }

        $desc = 'Your friend ' . $userTotal['username']. ' favorited the product  '. $name;
        $sql2 = 'INSERT INTO history (description, User_id_user) VALUES ("'.$desc.'", '.$userTotal2.')';

        $conn->query($sql2); 
        }
    }else{
        if ($resultFavorite->num_rows > 0) {
            $sql = "DELETE FROM Favorite WHERE user_id_user='$userPost' AND Product_idProduct='$productPost'";
            $conn->query($sql);

            $seluser = 'SELECT * FROM product WHERE idProduct = ' .$productPost;

        $name = '';
        $retvalr = $conn->query($seluser); 
         if ($retvalr->num_rows > 0) {
             $counter = 0; 
                while($rowm = $retvalr->fetch_assoc())
                { 
                    $name  = $rowm['name'];
                }
         }

        $desc = 'Your friend ' . $userTotal['username']. ' unfavorited the product  '. $name;
        $sql2 = 'INSERT INTO history (description, User_id_user) VALUES ("'.$desc.'", '.$userTotal2.')';

        $conn->query($sql2); 
        }
    }

    $sqlBookmark = "SELECT * FROM Bookmark WHERE user_id_user='$userPost' AND Product_idProduct='$productPost'";
    $resultBookmark = $conn->query($sqlBookmark);

    if($bookmarkPost == 'true'){
        if ($resultBookmark->num_rows == 0) {
            $sql = "INSERT INTO Bookmark (user_id_user, Product_idProduct) VALUES ($userPost, $productPost)";
            $conn->query($sql);
            $seluser = 'SELECT * FROM product WHERE idProduct = ' .$productPost;

        $name = '';
        $retvalr = $conn->query($seluser); 
         if ($retvalr->num_rows > 0) {
             $counter = 0; 
                while($rowm = $retvalr->fetch_assoc())
                { 
                    $name  = $rowm['name'];
                }
         }

        $desc = 'Your friend ' . $userTotal['username']. ' Bookmark the product  '. $name;
        $sql2 = 'INSERT INTO history (description, User_id_user) VALUES ("'.$desc.'", '.$userTotal2.')';

        $conn->query($sql2); 
        }
    }else{
        if ($resultBookmark->num_rows > 0) {
            $sql = "DELETE FROM Bookmark WHERE user_id_user='$userPost' AND Product_idProduct='$productPost'";
            $conn->query($sql);
        

            $seluser = 'SELECT * FROM product WHERE idProduct = ' .$productPost;

        $name = '';
        $retvalr = $conn->query($seluser); 
         if ($retvalr->num_rows > 0) {
             $counter = 0; 
                while($rowm = $retvalr->fetch_assoc())
                { 
                    $name  = $rowm['name'];
                }
         }

        $desc = 'Your friend ' . $userTotal['username']. ' unbookmark the product  '. $name;
        $sql2 = 'INSERT INTO history (description, User_id_user) VALUES ("'.$desc.'", '.$userTotal2.')';

        echo $sql2;

        $conn->query($sql2); 
        }
    }
    $conn->close();
    echo 'Information updated Successfully';
?>