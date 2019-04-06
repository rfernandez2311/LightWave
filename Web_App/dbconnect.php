<?php
 define('DB_SERVER', 'localhost');
 define('DB_USERNAME', 'pi');
 define('DB_PASSWORD', '0000');
 define('DB_DATABASE', 'LightWave');
 $connection = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE)
	 or die ('Error Connecting to DB!');
?>
