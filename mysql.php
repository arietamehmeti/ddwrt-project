<?php

$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if (!$conn)
    die("Connection failed: " . mysqli_connect_error());
else{
	//echo "Connected successfully <br>";
}

$dbname = "ddwrt_routers";

mysqli_select_db($conn, $dbname);

?>	