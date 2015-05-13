<?php
	/*
mysqli::__construct() (
	[string $host = ini_get("mysqli.default_host") [, string $username =
	ini_get("mysqli.default_user") [, string $passwd = 
	ini_get("mysqli.default_pw") [, string $dbname = "blockbuster"]]]]
)
*/
$mysqli = new mysqli("oniddb.cws.oregonstate.edu","holkeboj-db","VFGTEHMFAZYkn25Q","holkeboj-db");
if(!$mysqli || $mysqli->connect_errno) {
	echo "Unable to connect to database.  Error: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
} else {
	echo("connected");
}

?>