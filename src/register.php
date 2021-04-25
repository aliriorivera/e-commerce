<?php
    $namePost = $_POST['name'];
    $usernamePost = $_POST['username'];
    $passwordPost = $_POST['password'];
    $emailPost = $_POST['email'];
    $telephonePost = $_POST['telephone'];
    $addressPost = $_POST['address'];


    $conn=mysql_connect("localhost", "root", "rootpassword") or die (mysql_error($conn)); //nos conectamos a la base de atos
    mysql_select_db("enterprisewebapp", $conn) or die (mysql_error($conn)); //cambiamos de base de datos

    if(! $conn )
    {
        die('Could not connect: ' . mysql_error());
    }

    $sql = "INSERT INTO user ".
        "(username, email, password, telephone, address, name) ".
        "VALUES ".
        "('$usernamePost','$emailPost','$passwordPost','$telephonePost','$addressPost','$namePost')";
    $retval = mysql_query( $sql, $conn );
    if(! $retval )
    {
        die('Could not enter data: ' . mysql_error());
    }
    mysql_close($conn);
    
    setcookie('registeredUSER', "User registered into the system, now please log in into the system.", time() + 100, '/');
    ?>
        <script>
            location.href = "index.php";
        </script>
    <?php
?>
