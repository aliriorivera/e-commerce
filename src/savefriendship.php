<?php
    // Fetching Values From URL


    $userIDPost = $_POST['userID'];
    $friendIDPost = $_POST['friendID'];


    $conn=mysql_connect("localhost", "root", "rootpassword") or die (mysql_error($conn)); //nos conectamos a la base de atos
    mysql_select_db("enterprisewebapp", $conn) or die (mysql_error($conn)); //cambiamos de base de datos

    if(! $conn )
    {
        die('Could not connect: ' . mysql_error());
    }

    $sql = "INSERT INTO user_invitations ".
        "(user_id_user, user_id_user_invited_to) ".
        " VALUES ".
        "('$userIDPost','$friendIDPost')";

    $retval = mysql_query( $sql, $conn );
    if(! $retval )
    {
        die('Could not enter data: ' . mysql_error());
    }
    mysql_close($conn);

    echo 'FriendShip Invitation sent!!!';
?>