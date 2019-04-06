<?php
 require_once "dbconnect.php";

 if ($_SERVER["REQUEST_METHOD"] == "POST"){
	 
       $user = $_POST["username"];
       $pwd = $_POST["password"];

       $query = "SELECT * FROM Users WHERE username='$user' AND password='$pwd';";

       if (!$query) {
	      printf("Error: %s\n", mysqli_error($connection));
	      exit();
       }

       $result = mysqli_query($connection,$query);
       
       while($row = mysqli_fetch_array($result)){
       
        if ($user == $row['username'] && $pwd == $row['password']){
	       mysqli_close($connection);
	       header("Location: dashboard.php");
	       exit();
        }
       }
}
?>

<html>
  <head>
    <title>Log In</title>
    <link rel="stylesheet" type= "text/css" href="css/Login.css">
  </head>
    <body>

      <div class="loginbox">
      <img src="LW.png" class="avatar">

        <h1>Login Here</h1>


          <form method="post">

           <p>Username</p>
             <input type="text" name="username" placeholder="Enter Username" />
             <p>Password</p>
             <input type="password" name="password" placeholder="Enter Password" />

             <button class="submit" name="submit">Login</button>

            

          </form>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script>
	$('.message a ').click(fucntion(){
  	   $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
	});
      	</script>
      
      </div>
  </body>

</html>
