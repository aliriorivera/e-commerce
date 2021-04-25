<?php
    session_start();
    $userTotal=$_SESSION["identifiedUSerApp"];
    if (!isset($userTotal)){
        header("Location:/webAppCMU/index.php");
    }

?>
<!doctype html>
<html>

<head>
    <title>My friends!!!</title>
</head>

<body>

    <?php include 'menucontext.php'; ?>

    <div>
    </div>

</body>

</html>