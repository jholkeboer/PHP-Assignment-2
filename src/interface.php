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
<form action="interface.php" method="post">
	<input type="hidden" name="deleteAll" value="1">
	<input type="submit" value="Delete All Videos">
</form><br>
<?php
//check for post parameters
if ($_POST) {
	
	if (isset($_POST['name'])) {
		$newName = $_POST['name'];
	}
	if (isset($_POST['category'])) {
		$newCategory = $_POST['category'];
	}
	if (isset($_POST['length'])) {
		$newLength = intval($_POST['length']);
	}
	if (isset($_POST['deleteAll'])) {
		$deleteAll = intval($_POST['deleteAll']);
	}
	
	//perform insert based on post
	if (isset($newName) && isset($newCategory) && isset($newLength)) {	
			//prepare insert statement	
		if (!($addVid = $mysqli->prepare("INSERT INTO vidstore (name, category, length) values (?,?,?)"))) {
			echo "Prepare failed on addVid";
		}
			//bind parameters
		if (!$addVid->bind_param("ssi", $newName, $newCategory, $newLength)) {
			echo "Binding failed on addVid";
		}
			//execute
		if (!($addVid->execute())) {
			echo "Execute failed for addVid";
		}
		$addVid->close();
	}
	
	//perform delete all if that button was clicked
	if (isset($deleteAll)) {
		if ($deleteAll == 1) {
				//prepare insert statement
			if (!($clearTable = $mysqli->prepare("drop table if exists vidstore"))) {
				echo "Prepare failed on clearTable";
			}
			if (!($resetTable = $mysqli->prepare("create table vidstore (
	id int(11) NOT NULL AUTO_INCREMENT,
	name varchar(255) NOT NULL,
	category varchar(255),
	length int(11) unsigned,
	rented bool default 1,
	PRIMARY KEY (id),
	UNIQUE(name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8"))) {
				echo "Prepare failed on resetTable";
			}
				//execute deletion
			if (!($clearTable->execute())) {
				echo "Execute failed for clearTable";
			}
			if (!($resetTable->execute())) {
				echo "Execute failed for resetTable";
			}
			
		}
	}
	
	//redirect
	header("Location: " . $_SERVER['REQUEST_URI']);
}

//check for get parameters
if ($_GET) {
	//perform deletion
	if (isset($_GET['idToDelete'])) {
		$idToDelete = intval($_GET['idToDelete']);
	}
	
	if (!($delVid = $mysqli->prepare("DELETE FROM vidstore WHERE id=?"))) {
		echo "Prepare failed on delVid";
	}
	if (!$delVid->bind_param("i", $idToDelete)) {
		echo "Binding failed on addVid";
	}
	if (!($delVid->execute())) {
		echo "Execute failed on delVid";
	}
	$delVid->close();
	
	//redirect
	//header("Location: " . $_SERVER['REQUEST_URI']);
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
	printf("<tr> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> </tr>", 
	$row["name"], 
	$row["category"], 
	$row["length"], 
	$row["rented"], 
	"<a href='interface.php?idToDelete=".$row["id"]."'><input type='button' value='Delete' /></a>");
}
?>
	</tbody>
</table>
<?php


$getVids->close();

$mysqli->close();
?>
</body>