<?php

include "dbconnect.php";

$usuario =$_POST["usuario"];
$userpassword = $_POST["userpassword"];
$mysql_qry = 'select * from Users where username = "'.$usuario.'"
and password = "'.$userpassword.'";';
$result = mysqli_query($connection,$mysql_qry) or die ('error: '.mysql_error());

if(mysqli_num_rows($result)==1){
    echo "success";
}
else{
    echo "log-in not success";
}

?>
