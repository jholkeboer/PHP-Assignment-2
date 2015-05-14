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
if ($_POST) {
//check for post parameters	
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
	if (isset($_POST['idToDelete'])) {
		$idToDelete = intval($_POST['idToDelete']);
	}
	if (isset($_POST['idToRent'])) {
		$idToRent = intval($_POST['idToRent']);
	}	
	if (isset($_POST['idToReturn'])) {
		$idToReturn = intval($_POST['idToReturn']);
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
				//prepare delete statements
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
	
	if (isset($idToDelete)) {
			//prepare deletion statement
		if (!($delVid = $mysqli->prepare("DELETE FROM vidstore WHERE id=?"))) {
			echo "Prepare failed on delVid";
		}		
			//bind id to be deleted
		if (!$delVid->bind_param("i", $idToDelete)) {
			echo "Binding failed on delVid";
		}	
			//execute deletion
		if (!($delVid->execute())) {
			echo "Execute failed on delVid";
		}
	}
	
	if (isset($idToRent)) {
			//prepare update statement
		if(!($rentVid = $mysqli->prepare("UPDATE vidstore SET rented=1 WHERE id=?"))) {
			echo "Prepare failed on rentVid";
		}
			//bind id to be updated
		if(!($rentVid->bind_param("i",$idToRent))) {
			echo "Binding failed on rentVid";
		}
			//execute update
		if(!($rentVid->execute())) {
			echo "Execute failed on rentVid";
		}
	}
	
	if (isset($idToReturn)) {
			//prepare update statement
		if(!($returnVid = $mysqli->prepare("UPDATE vidstore SET rented=0 WHERE id=?"))) {
			echo "Prepare failed on returnVid";
		}
			//bind id to be updated
		if(!($returnVid->bind_param("i",$idToReturn))) {
			echo "Binding failed on returnVid";
		}
			//execute update
		if(!($returnVid->execute())) {
			echo "Execute failed on returnVid";
		}
	}
	
	//redirect
	header("Location: " . $_SERVER['REQUEST_URI']);
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
			<td>Status</td>
		</tr>
	</thead>
	<tbody>
<?php
while($row = $vidResult->fetch_assoc()) {
	if ($row["rented"] == 0) {
		printf("<tr> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> </tr>", 
		$row["name"], 
		$row["category"], 
		$row["length"], 
		"Available",
		"<form action='interface.php' method='post'>
			<input type='hidden' name='idToRent' value='".$row['id']."'>
			<input type='submit' value='Rent'>
		</form>", 
		"<form action='interface.php' method='post'>
			<input type='hidden' name='idToDelete' value='".$row['id']."'>
			<input type='submit' value='Delete'>
		</form>");
	}
	else if ($row["rented"] == 1) {
		printf("<tr> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> <td>%s</td> </tr>", 
		$row["name"], 
		$row["category"], 
		$row["length"], 
		"Checked Out",
		"<form action='interface.php' method='post'>
			<input type='hidden' name='idToReturn' value='".$row['id']."'>
			<input type='submit' value='Return'>
		</form>", 
		"<form action='interface.php' method='post'>
			<input type='hidden' name='idToDelete' value='".$row['id']."'>
			<input type='submit' value='Delete'>
		</form>");		
	}
}
?>
	</tbody>
</table>
<?php


$getVids->close();

$mysqli->close();
?>
</body>