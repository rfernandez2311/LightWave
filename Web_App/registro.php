<?php

include "dbconnect.php";

$user_name = $_POST["Nombre"];
$user_lastname=$_POST["LastName"];
$user_username=$_POST["usuario"];
$user_password= $_POST["userpassword"];
$mysql_query = "insert into Users (username,password) 
values ('$user_username','$user_password')";

if ($connection ->query($mysql_query)===TRUE){
    echo "Insert Successful";
}
else{
    echo "Error: ".$mysql_query."<br>".$connection->error;
}
$connection -> close();

?>
