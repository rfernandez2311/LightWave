<?php
   require_once "dbconnect.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="refresh" content="15">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LightWave</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"  href="main.scss" >
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script src="main.js"></script>
    <script async defer src= "https://buttons.github.io/buttons.js"></script>
 </head>
  <body>

     
  
    <div id="sideMenu"> 
        
    
       <a href="dashboard.php" ><img src="lightwave.png"  alt="Lightwave Logo"></a>

        <nav>

        <a href="dashboard.php" class="active"> <i class="fa fa-home" aria-hidden="true"></i> Home</a>
        <a href="about.php" > <i class="fa fa-user" aria-hidden="true"></i>About Us</a>
        <a href="liveinfo.php"><i class="fa fa-thermometer-half" aria-hidden="true"></i>Live Mesures</a>

       </nav>
   </div>


   <header>

      <div class="search-area">
          <i class="fa fa-search" aria-hidden="true"></i>
          <input type="text" name="" value="">
          </div>
          <div class="user-area">

              
      </div>
   </header>

  
      <div class="flex-container">

         <!--<div class="box0">
        <p> Today's Time and Date</p>
        <img src="clock.png"></img> 
        <time id="demo"></time>
        </div>-->




       <div class="box1">
       <p class="title">TEMPERATURE</p>
       <img src="temp.png"></img> 
       <?php
    

    $query = "SELECT temp FROM Live_Info;";
    
    $result = mysqli_query($connection,$query);
    
    while ($row = mysqli_fetch_array($result)){
     echo "<p>".$row['temp']."°C"."</p>"; 
    }
     ?> 
      </div>
  
  <div class="box2">
  <p class="title2">HUMIDITY</p>
  <img src="drop.png"></img> 
  <?php
    $query = "SELECT hum FROM Live_Info;";
    
    $result = mysqli_query($connection,$query);
    
    while ($row = mysqli_fetch_array($result)){
     echo "<p>".$row['hum']."%"."</p>"; 
    }
     ?> 
 </div>

  <div class="box3">
  <p class="title3">ALARM</p>
  <img src="alarm.png"></img> 
  <?php
   $query = "SELECT fire_state, gas_state FROM Actuators;";
    
    $result = mysqli_query($connection,$query);
    
    while ($row = mysqli_fetch_array($result)){
     if($row['fire_state'] == "0" || $row['gas_state'] == "1"){
       echo "<p class=\"action2\">ON</p>";
     }
      else{
          echo "<p class=\"action1\">OFF</p>";
      }
    }
    ?>
  </div>

  



 <div class="box5">
  <img src="stark.jpg" >
  <h1>Tony Stark</h1>
  <p class="title">CEO & Founder, Stark Industries</p>
  <p class="stu"> Interamerican University - Bayamon</p>
  <div  class="social" style="margin: 30px 0;">
    <a href="#"><i class="fa fa-dribbble"></i></a> 
    <a href="#"><i class="fa fa-twitter"></i></a>  
    <a href="#"><i class="fa fa-linkedin"></i></a>  
    <a href="#"><i class="fa fa-facebook"></i></a> 
 </div>
  
  </div>

  </body>
</html>
