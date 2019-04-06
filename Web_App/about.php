<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>About Us</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- <link rel="stylesheet"  href="main.scss" > -->
<link rel="stylesheet"  href="about.scss" >


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

   

<script src="main.js"></script>
<script async defer src= "https://buttons.github.io/buttons.js"></script>
</head>
<body>


<!-- **********************************************side menu************************* -->

 <div id="sideMenu"> 
        
    
 <a href="dashboard.php" ><img src="lightwave.png"  alt="Lightwave Logo"></a>

        <nav>

        <a href="dashboard.php" class="active"> <i class="fa fa-home" aria-hidden="true"></i> Home</a>
        <a href="about.php" > <i class="fa fa-user" aria-hidden="true"></i>About Us</a>
        <a href="liveinfo.php"><i class="fa fa-thermometer-half" aria-hidden="true"></i>Live Mesures</a>
        
        <a href="index.php" class="btn"><span class="glyphicon glyphicon-log-out"></span> Log out </a>
       </nav>
   </div>

 <!-- *************************************************** Header *************************** -->
   <header>
   
      <div class="search-area">
          <i class="fa fa-search" aria-hidden="true"></i>
          <input type="text" name="" value="">
          </div>
               
        
   </header>

    <!-- *************************************************** about us *************************** -->


   <div class="flex-container2">
     <div class="box5">
      <h2>ABOUT US</h2>
      <p> 
        The  project  consists  in  the  utilization  of  Arduino  compatible  sensors  to  
        control  Arduino  compatible  actuators (e.g.  lights,  motors)  wirelessly  over  
        a  Wi-Fi  local  network.  The  sensors  will  gather  data  from  its  surroundings, 
        like:  temperature,  humidity,  light  percentage,  fire  presence,  CO2  and  more.  
        Based  on  the  information  given  from  the  sensors, action  can  be  taken  automatically,
        without  the  need  for  interaction  between a human  and  equipment  (actuators).  For  example, 
        if  a  fire  is  detected  by one  of  the  sensors  an  alarm  must  be  activated  automatically. 
        Lastly,  the  Arduino  should  perform  its  tasks  efficiently  that  is  why  a  Real  Time   
        Operating  System  will  be  included  (FreeRTOS). Finally,  the  users  will  have  the  
        option  of  looking  at  the  all  the  information  available  from  a  LCD  screen,  
        a  Graphical  User  Interface  on  a  computer  and  an  Android application.

      </p>
      </div>


</div>


</body>
</html>
