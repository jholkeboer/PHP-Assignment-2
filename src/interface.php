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

////local db
$mysqli = new mysqli("localhost","root",$localpass,"blockbuster","3306");
if(!$mysqli || $mysqli->connect_errno) {
	echo "Unable to connect to database.  Error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
}
?>
<h1>Video Inventory</h1>
<h5>Use the form below to add a video to the database.</h5>
<form action="interface.php" method="post">
	<label>Name: </label>
	<input type="text" name="name">
	<label>Category: </label>
	<input type="text" name="category">
	<label>Length: </label>
	<input type="text" name="length">
	<input type="submit">
</form><br>
<?php
//check for post parameters
$newName = "";
$newCategory = "";
$newLength = "";

if (isset($_POST['name'])) {
	$newName = $_POST['name'];
}
if (isset($_POST['category'])) {
	$newCategory = $_POST['category'];
}
if (isset($_POST['length'])) {
	$newLength = $_POST['length'];
}


	
//general statement for getting all videos in db
if (!($getVids = $mysqli->prepare("SELECT id, name, category, length, rented FROM vidstore ORDER BY name"))) {
	echo "Prepare failed on getVids";	
}
if (!$getVids->execute()) {
	echo "Execute failed on getVids";
}
$vidResult = $getVids->get_result();
//render video table ?>
<table border="1px">
	<thead>
		<tr>
			<td>Name</td>
			<td>Category</td>
			<td>Length</td>
			<td>Checked Out?</td>
		</tr>
	</thead>
	<tbody>
<?php
while($row = $vidResult->fetch_assoc()) {
	printf("<tr> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td></tr>", $row["name"], $row["category"], $row["length"], $row["rented"]);
}
?>
	</tbody>
</table>
<?php


$getVids->close();

$mysqli->close();
?>
</body>