<!doctype html>
<head>
</head>
<body>
<?php
include 'storedinfo.php';

//oregon state db

//$mysqli = new mysqli("oniddb.cws.oregonstate.edu","holkeboj-db",$holkebojpass,"holkeboj-db");
//if(!$mysqli || $mysqli->connect_errno) {
//	echo "Unable to connect to database.  Error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
//} else {
//	echo("connected");
//}


//local db
$mysqli = new mysqli("localhost","root",$localpass,"blockbuster","3306");
if(!$mysqli || $mysqli->connect_errno) {
	echo "Unable to connect to database.  Error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
} else {
	echo("connected");
}

//general statement for getting all videos in db
if (!($getVids = $mysqli->prepare("SELECT id, name, category, length, rented FROM vidstore ORDER BY name"))) {
	echo "Prepare failed on getVids";	
}
if (!$getVids->execute()) {
	echo "Execute failed on getVids";
}
$vidResult = $getVids->get_result();
?>
<table>
<?php
while($row = $vidResult->fetch_assoc()) {
	printf("%s %s %s %s %s<br>", $row["id"], $row["name"], $row["category"], $row["length"], $row["rented"]);
}
?>
</table>
<?php


$getVids->close();

$mysqli->close();
?>
</body>