<?php

$username = "root";
$password = "root";
$hostname = "localhost"; 

//connection to the database
$dbhandle = mysql_pconnect($hostname, $username, $password) 
 or die("Unable to connect to MySQL");
echo "Connected to MySQL<br>";

//select a database to work with
$selected = mysql_select_db("sims",$dbhandle) 
  or die("Could not select examples");

echo $selected;
