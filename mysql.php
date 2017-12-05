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


/*
$query = "Select id, name from game";

$results = mysqli_query($conn, $query);


if(mysqli_num_rows($results) > 0)
{
	while($row = mysqli_fetch_assoc($results))
		echo $row["id"] .'. Name: ' .$row['name'] ." <br> ";

}

*/

?>	