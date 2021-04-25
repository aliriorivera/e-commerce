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
    <title>Groups</title>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <script>
        function creategroupToggle(elemToHide) {
            var elemToShowOrHide = document.getElementById(elemToHide);
            if (elemToShowOrHide.style.display == "block") {
                elemToShowOrHide.style.display = "none";
            }
            else {
                elemToShowOrHide.style.display = "block";
            }
        }
    </script>

</head>

<body>
    
    <?php include 'menucontext.php'; ?>
    <br>
    <br>
    <div style="text-align:center;">
        <div style="border:1px solid #000; display:inline-block; text-align:left;">

        <div id = "searchGroupsForm" >
            <form action="<?php $_PHP_SELF ?>" method ="POST">
                What are you looking for?:<br>
                <input type="text" name="toLookGroupSearch"  required>
                <input type="submit" name = "searchforGROUPS" value="Submit">
            </form> 
        </div>
        <?php
			
            if(isset($_COOKIE['registeredGROUPBELONG'])) {
                print $_COOKIE['registeredGROUPBELONG'];
                unset($_COOKIE['registeredGROUPBELONG']);
                // empty value and expiration one hour before
                setcookie('registeredGROUPBELONG', '', time() - 3600, "/");
            } 
        
			

            if(isset($_POST['searchforGROUPS'])) {
                $toSearchgroupPost = $_POST['toLookGroupSearch'];

                $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                } 

                $sqlgroup = 'SELECT * FROM groups WHERE (name LIKE "%'.$toSearchgroupPost.'%") OR (description LIKE "%'.$toSearchgroupPost.'%")';

                $retval = $conn->query($sqlgroup);  
                if ($retval->num_rows > 0) {
                        while($row = $retval->fetch_assoc())
                        {
                            echo $row['name'];
                            ?>
                            <form action="/webAppCMU/belongtogroup.php" method="post">
                                <input type="hidden"  name="groupId" value="<?=$row['idgroup']?>"></input>
                                <input type = "submit" value = "Belong"> </input>
                            </form>
                            <?php
                        }
                }else{
                    echo 'There are no groups matching your search text.';
                }
            }
        ?>

        </div>

        <div style="border:1px solid #000; display:inline-block; text-align:left;">

            

            <h1>Create a Group </h1> <button onclick="creategroupToggle('creategroupForm')">Create New Group</button>

            <br>

            <?php
			
				if(isset($_COOKIE['registeredGROUP'])) {
					print $_COOKIE['registeredGROUP'];
					unset($_COOKIE['registeredGROUP']);
					// empty value and expiration one hour before
					setcookie('registeredGROUP', '', time() - 3600, "/");
				} 
			
			?>

            <div id ="creategroupForm" style="border:1px solid #000; display:none; text-align:left;">
                <form action="/webAppCMU/creategroup.php" method="post">
                    Name:<br>
                        <input type="text" name="nameGroup"  required>
                        <br>
                    description :<br>
                        <textarea name="descriptionGroup"  required></textarea>
                        <br>
                    <input type="submit" value="create Group">
                </form> 
            </div>
            <br>

            <div style="border:1px solid #000; display:inline-block; text-align:left;">
                <?php
                    echo 'groups I own <br>';

                    $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    } 


                    $sqlowner = 'SELECT * FROM groups WHERE user_id_user = '.$userTotal['id_user'];

                    $retvalOwn = $conn->query($sqlowner);  
                    if ($retvalOwn->num_rows > 0) {
                        while($row = $retvalOwn->fetch_assoc())
                        {
                            $idgrouppost = $row['idgroup'];
                            echo $row['name'];
                            ?>
                            
                            <form target="_blank" action ="/webAppCMU/viewgroupinfo.php" method ="post">
                                <input type="hidden" name ="idGroup" value = <?= $idgrouppost?>></input>
                                <input type="submit" value = "view group"></input>
                            </form>
                            
                            <?php
                            
                        }
                    }else{
                        echo 'You have no created any group. ';
                    }

                ?>
            </div>

            <br>
            <div style="border:1px solid #000; display:inline-block; text-align:left;">
                <?php
                    echo 'groups I belong <br>';
                    $conn = new mysqli("localhost", "root", "rootpassword", "enterprisewebapp");
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    } 


                    $sqlowner = 'SELECT * FROM group_user WHERE user_id_user = '.$userTotal['id_user'];

                    $retvalOwn = $conn->query($sqlowner);  
                    if ($retvalOwn->num_rows > 0) {
                        while($row = $retvalOwn->fetch_assoc())
                        {
                            $sqlForGroup = 'SELECT * FROM groups WHERE idgroup = '.$row['group_idgroup'];
                            $retvalGroup = $conn->query($sqlForGroup);  
                            while($rowG = $retvalGroup->fetch_assoc())
                            {
                                $idgrouppost = $rowG['idgroup'];
                                echo $rowG['name'];
                                ?>
                                
                                <form target="_blank" action ="/webAppCMU/viewgroupinfo.php" method ="post">
                                    <input type="hidden" name ="idGroup" value = <?= $idgrouppost?>></input>
                                    <input type="submit" value = "view group"></input>
                                </form>
                                
                                <?php
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