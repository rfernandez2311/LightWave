<?php
   require_once "dbconnect.php";
?>

<!DOCTYPE html> 
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="refresh" content="15">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Live Mesures</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet"  href="live.scss" >
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

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
        <a href="index.php" class="btn"><span class="glyphicon glyphicon-log-out"></span> Log out </a>
       </nav>
   </div>

 <!------------------- header ---------------------->
   <header>

      <div class="search-area">
          <i class="fa fa-search" aria-hidden="true"></i>
          <input type="text" name="" value="">
          </div>
          <div class="user-area">

              
      </div>
   </header>



   <!------------------- dashboard cards ---------------------->
       <div class="flex-container">
       
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
  <p class="title3">GAS</p>
  <img src="gas.png"></img> 
  <?php
    
     $query = "SELECT gas FROM Live_Info;";
    
    $result = mysqli_query($connection,$query);
    
    while ($row = mysqli_fetch_array($result)){
     echo "<p>".$row['gas']."PPM"."</p>"; 
    }
   
     ?> 
</div>

<div class="box4">
  <p class="title4">FAN</p>
  <img src="fan2.png"></img> 
  <?php
    $query = "SELECT fan_state FROM Actuators;";
    
    $result = mysqli_query($connection,$query);
    
    while ($row = mysqli_fetch_array($result)){
     if($row['fan_state'] == "1"){
       echo "<p class=\"action2\">ON</p>";
     }
      else{
          echo "<p class=\"action1\">OFF</p>";
      }
    }
     ?> 
 
  </div>

  <div class="box5">
       <p class="title5">FIRE</p>
       <img src="fire.png"></img> 
       <?php
    $query = "SELECT fire_state FROM Actuators;";
    
    $result = mysqli_query($connection,$query);
    
    while ($row = mysqli_fetch_array($result)){
     if($row['fire_state'] == "0"){
       echo "<p class=\"action4\">ON</p>";
     }
      else{
          echo "<p class=\"action3\">OFF</p>";
      }
    }
     ?> 
       
 </div>

    <div class="box6">
       <p class="title6">LIGHT</p>
       <img src="bulb.png"></img>
       <?php
    $query = "SELECT light_state FROM Actuators;";
    
    $result = mysqli_query($connection,$query);
    
    while ($row = mysqli_fetch_array($result)){
     if($row['light_state'] == "1"){
       echo "<p class=\"action6\">ON</p>";
     }
      else{
          echo "<p class=\"action5\">OFF</p>";
      }
    }
     
     ?>  
    </div>

    <div class="box7">
       <p class="title7">ALARM</p>
       <img src="alarm.png"></img> 
       <?php
    $query = "SELECT fire_state, gas_state FROM Actuators;";
    
    $result = mysqli_query($connection,$query);
    
    while ($row = mysqli_fetch_array($result)){
     if($row['fire_state'] == "0" || $row['gas_state'] == "1"){
       echo "<p class=\"action8\">ON</p>";
     }
      else{
          echo "<p class=\"action7\">OFF</p>";
      }
    }
    ?>
    </div>
    
    <?php
    $myfile =  fopen("/var/www/LightWave/system_info.txt","w") or die("Unable to open file!");

    $query = "SELECT * FROM Live_Info;";
    
    $result = mysqli_query($connection,$query);
    
    while ($row = mysqli_fetch_array($result)){
     echo "<tr><td>".$row['hum']."</td><td>".$row['temp']."</td><td>".$row['light']."</td><td>".$row['fire']."</td><td>".$row['gas']."</td></tr>";
     $table = $row['hum']." ".$row['temp']." ".$row['light']." ".$row['fire']." ".$row['gas'];
     fwrite($myfile,(string)$table);
     fwrite($myfile,"\n");  
    }
     fclose($myfile);
     mysqli_close($connection);
     ?>  
    

 <!------------------- dashboard cards#2 ---------------------->

  



 </body>
</html>
