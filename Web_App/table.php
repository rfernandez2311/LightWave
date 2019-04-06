<?php
   require_once "dbconnect.php";
?>
<html>
<head>
 <title>Table with Database</title>
 <meta http-equiv="refresh" content="15">
</head>
<body>
<table ID="myTable" BORDER="5"    WIDTH="50%"   CELLPADDING="4" CELLSPACING="3">
  <thead>   
  <tr>
      <th COLSPAN="5"><BR><h3>DB TABLE TEST</h3>
      </th>
   </tr>
   <tr>
     <th>MEASURED HUMIDITY</th>
     <th>MEASURED TEMPERATURE</th>
     <th>MEASURED LIGHT</th>
     <th>MEASURED FIRE</th>
     <th>MEASURED GAS</th>
   </tr>
  </thead>
  <tbody>
  <?php
    $myfile =  fopen("/var/www/html/system_info.txt","w") or die("Unable to open file!");

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
</tbody>
</table>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/table-to-json@0.13.0/lib/jquery.tabletojson.min.js" integrity="sha256-AqDz23QC5g2yyhRaZcEGhMMZwQnp8fC6sCZpf+e7pnw=" crossorigin="anonymous"></script>

<!--<script type="text/javascript">
    var auto_refresh = setInterval(function()
    {
    	$('#myTable').load('table.php');
    }, 10000);    
</script>-->
</body>
</html>
