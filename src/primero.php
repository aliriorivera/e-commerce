<?php
$dbhost = 'localhost:3306';
$dbuser = 'root';
$dbpass = 'rootpassword';
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}
$sql = 'select * from tag;';

mysql_select_db('enterprisewebapp');
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not get data: ' . mysql_error());
}


while($row = mysql_fetch_array($retval, MYSQL_ASSOC))
{
    echo "ID :{$row['idTag']}  <br> ".
         "Name: {$row['name']} <br> ".
         "--------------------------------<br>";
} 
echo "Fetched data successfully\n";
mysql_close($conn);
?>