<?php


//host
$host = "localhost";

//dbname
$dbname = "authentication-system";

//username/\

$username = "root";

//password

$password = "";


$conn = new PDO("mysql:host=$host;dbname=$dbname;", $username, $password);

//if($conn == true) {
 //   echo "it's connected";
//}else{
 //   echo "it's not connectex";
//}

?>