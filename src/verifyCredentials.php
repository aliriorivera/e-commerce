<?php
$UserPost = $_POST['username'];
$PassPost = $_POST['password'];

if($UserPost=="" ||  $PassPost=="")
    {
        ?>
            <script>
                alert("incomplete data, please fill.......");
                location.href = "index.php";
           </script>

        <?php
    }
    else{
            $conn=mysql_connect("localhost", "root", "rootpassword") or die (mysql_error($conn)); //nos conectamos a la base de atos
            mysql_select_db("enterprisewebapp", $conn) or die (mysql_error($conn)); //cambiamos de base de datos
            $result = mysql_query("SELECT * FROM user WHERE username='$UserPost' and password='$PassPost'");
            $row = mysql_fetch_array($result);
            if($row["password"] == $PassPost & $row["username"] == $UserPost)
            {
                 session_start(); //inicio las variables de sesion...
                 $_SESSION["identifiedUSerApp"]=$row; //..  y almaceno el valor del objeto en la sesion
                 header("Location: /webAppCMU/homefeeds.php"); //y redirecciono al index de la aplicacion
                 mysql_close($conn);// cierro la conexion a la base de datos
            }
            else{
                //este es por si no es ninguno de los anteriores
                ?>
                    <script>
                        alert("Datos Errados, Intentelo de Nuevo por favor");
                        location.href = "index.php";
                    </script>
                <?php
            }
        }
?>