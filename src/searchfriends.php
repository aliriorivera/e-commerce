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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <title>
        Search for friends!!
    </title>

    <script>
        
            function updateFriendship(divToShowMessage, userId, friendID){
                var stringToSendPost = 'userID=' + userId + '&friendID=' + friendID;
                var whatDiv = '#saveDiv' + divToShowMessage;
                var idButtonToInnactiveTotal = 'activeButton' + divToShowMessage;

                document.getElementById(idButtonToInnactiveTotal).disabled = true;
                    $.ajax({
                            type: "POST",
                            url: "savefriendship.php",
                            data: stringToSendPost,
                            cache: false,
                            success: function(html) {
                                $(whatDiv).html(html);
                        }
                    });
                return false;
            }
            
        </script>

        <script>
        
            function acceptFriend(FriendAccepted){
                var stringToSendPost = "newFriend=" + FriendAccepted;
                var whatDiv = '#saveDiv' + FriendAccepted;
                var whatDiv2 = '#' + FriendAccepted;
                    $.ajax({
                            type: "POST",
                            url: "savenewfriendship.php",
                            data: stringToSendPost,
                            cache: false,
                            success: function(html) {
                                $(whatDiv).html(html);
                                $(whatDiv2).hide(1000);
                        }
                    });
                return false;
            }
        </script>

</head>
<body>

    <?php include 'menucontext.php'; ?>

    <br>

<div style="text-align:center;">
	<div style="border:1px solid #000; display:inline-block; text-align:left;">

        <div id = "searchFriendsForm" >
            <form action="<?php $_PHP_SELF ?>" method ="POST">
                Who are you looking for?:<br>
                <input type="text" name="toLookFriendSearch"  required>
                <input type="submit" name = "searcforFRIENDS" value="Submit">
            </form> 
        </div>

        <?php
        
        if(isset($_POST['searcforFRIENDS'])) {
            $toSearchfriendPost = $_POST['toLookFriendSearch'];
            echo 'Busqueda por:  ' . $toSearchfriendPost;

            $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 
        
            $self_userNot = $userTotal['id_user'];

            $sql = 'SELECT * FROM user WHERE (username LIKE "%'.$toSearchfriendPost.'%" OR name LIKE "%'.$toSearchfriendPost.'%")'.
            ' AND id_user <> '. $self_userNot;

            $retval = $conn->query($sql);  
            if ($retval->num_rows > 0) {
                $i = 0;
                while($row = $retval->fetch_assoc())
                {
                    $divFinal  = 'saveDiv' . $i;
                    $userSelf = $userTotal['id_user'];
                    $userFriend = $row['id_user'];

                    echo '<br>';
                    echo $row['name'];

                    $sqlVerifyInvitation ='SELECT * FROM user_invitations WHERE user_id_user = '.  $userSelf  .' AND user_id_user_invited_to = '. $userFriend;
                    $sqlVerifyFriend ='SELECT * FROM friends WHERE user_id_user = '.  $userSelf  .' AND user_id_user_friend = '. $userFriend;
                    $retvalInv = $conn->query($sqlVerifyInvitation); 
                    $retvalFri = $conn->query($sqlVerifyFriend); 

                    $desactivateFriendButton = '';
                    if ($retvalInv->num_rows > 0 || $retvalFri->num_rows > 0) {
                        $desactivateFriendButton = 'disabled';
                    }

                    $idButtonInactive = 'activeButton'. $i

                    ?>
                        <div>
                            <form>  
                                <input id =<?= $idButtonInactive ?> type="button" value="Add Friend" <?=$desactivateFriendButton?>
                                onclick="updateFriendship(<?= $i ?>,
                                                            <?= $userSelf ?>,
                                                            <?= $userFriend ?> )"></input>
                            </form>
                            <div id=<?=$divFinal?>></div>
                        </div>
                        <br>
                    <?php
                    $i++;
                }
            }
        } 
        
        ?>
    </div>
    <div style="border:1px solid red; display:inline-block;">
    
        <h2>Friendship Requests</h2>

        <?php
        
            $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            } 


            $sql = 'SELECT * FROM user_invitations WHERE user_id_user_invited_to = '.$userTotal['id_user'];
            
            $retval3 = $conn->query($sql);  
            if ($retval3->num_rows > 0) {   
                while($row = $retval3->fetch_assoc())
                {
                    $sql2 = 'SELECT * FROM user WHERE id_user = ' . $row['user_id_user'];
                    $retval2 = $conn->query($sql2); 
                   while($row2 = $retval2->fetch_assoc())
                    {
                        // accept invitation.
                        $toSendFriend = $row2['id_user'];
                        $divFinal = "saveDiv" . $row2['id_user'];
                        $toName = $row2['id_user'];
                        ?>        
                            <div id=<?= $toName ?> style="display: block">
                            
                            <?php
                            echo $row2['name'] . ' wants to be your friend';
                            ?>
                                <form action ="/webAppCMU/savenewfriendship.php" method ="post">
                                <input type="hidden" name="newFriend" value="<?=$toSendFriend?>">
                                    <input type="submit" value ="Accept Friendship"></input>
                                </form>
                                <form target="_blank" action ="/webAppCMU/showallinfofromuser.php" method ="post">
                                    <input type="hidden" name="toSaveFriend" value="<?=$toSendFriend?>">
                                    <input type="submit" value ="Profile"></input>
                                </form>
                            </div>
                            <div id=<?=$divFinal?>></div>
                        <?php
                        
                    }
                }
            }else{
                echo 'Currently, You do not have Request.';
            }
        ?>
        
    </div>
    <div style="border:1px solid red; display:inline-block;">

        <h2> My Friends</h2>
        <?php
        
            $sql = 'SELECT * FROM friends WHERE user_id_user = '.$userTotal['id_user'];
            
            $retval44 = $conn->query($sql);  
            if ($retval44->num_rows > 0) {   
                while($row44 = $retval44->fetch_assoc())
                {
                    $sql445 = 'SELECT * FROM user WHERE id_user = '.$row44['user_id_user_friend'];
                    $retval445 = $conn->query($sql445);  
                    if ($retval445->num_rows > 0) {   
                        while($row445 = $retval445->fetch_assoc())
                        {    
                            echo '<span class="label label-success">Username</span><strong>' .$row445['username'].'</strong>';
                            
                            ?>
                            <form target="_blank" action ="/webAppCMU/showallinfofromuser.php" method ="post">
                                <input type ="hidden"  name="toSaveFriend" value =<?=$row445['id_user']?>></input>
                                <input type="submit" value ="View Profile"></input>
                            </form>
                            <form action ="/webAppCMU/deletefriend.php" method ="post">
                            <input type ="hidden"  name="toSaveFriend" value =<?=$row445['id_user']?>></input>
                                <input type="submit" value ="Delete Friend"></input>
                            </form>
                            
                            
                            <?php
                        }
                    }              
                }
            }else{
                echo 'Currently, you do not have friends.';
            }
        
        
        
        ?>


    </div>

</div>
</body>
</html>