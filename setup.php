<?php

$servername = "localhost";
$username = "root";
$password = "password";
$database = "onlineStore";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql) === TRUE) {
	echo "Database \"$database\" created"."<br>";
} else {
    echo "Error creating database: " . $conn->error;
}

$conn = new mysqli($servername, $username, $password, $database);

// 
// Create table for Store
// 
$sql = "CREATE TABLE IF NOT EXISTS Store (ID INT(6) PRIMARY KEY AUTO_INCREMENT NOT NULL, NAME VARCHAR(30) NOT NULL,	QUANTITY INT(6) NOT NULL DEFAULT 0, DESCRIPTION VARCHAR(300), CATEGORY VARCHAR(20))";

if ($conn->query($sql) === TRUE) {
    echo "Table \"Store\" created successfully"."<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

$sql = "SELECT COUNT(*) FROM Store";
$result = $conn->query($sql);
$num_of_rows = $result->fetch_assoc();
// error_log($num_of_rows["COUNT(*)"], 0);
if ($num_of_rows["COUNT(*)"] == 0) {
	// Populating with sample data
	$sql = "INSERT INTO Store (NAME, QUANTITY, DESCRIPTION, CATEGORY)
	VALUES ('Apple', 450, 'California', 'Mobile');";
	$sql .= "INSERT INTO Store (NAME, QUANTITY, DESCRIPTION, CATEGORY)
	VALUES ('Mouse', 370, 'Wireless + Bluetooth', 'Computer Peripheral');";
	$sql .= "INSERT INTO Store (NAME, QUANTITY, DESCRIPTION, CATEGORY)
	VALUES ('Bru Classic', 500, 'Classic Coffee', 'Food Items')";

	if ($conn->multi_query($sql) === TRUE) {
	    echo "New records created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	}
} else {
	echo "Table \"Store\" already exits with some records.<br>";
}

// 
// Create table for Users
// 
$sql = "CREATE TABLE IF NOT EXISTS Users (ID INT(6) PRIMARY KEY AUTO_INCREMENT NOT NULL, USERNAME VARCHAR(30) NOT NULL UNIQUE, PASSWORD VARCHAR(300) NOT NULL, CODE VARCHAR(6))";

if ($conn->query($sql) === TRUE) {
    echo "Table \"Users\" created successfully"."<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

$sql = "SELECT COUNT(*) FROM Users";
$result = $conn->query($sql);
$num_of_rows = $result->fetch_assoc();
// error_log($num_of_rows["COUNT(*)"], 0);
if ($num_of_rows["COUNT(*)"] == 0) {
	// Populating with sample data
	$sql = "INSERT INTO Users (USERNAME, PASSWORD)
	VALUES ('AnirudhGoel', md5(\"password1\"));";
	$sql .= "INSERT INTO Users (USERNAME, PASSWORD)
	VALUES ('Shubham', md5(\"password2\"));";
	$sql .= "INSERT INTO Users (USERNAME, PASSWORD)
	VALUES ('Shaurya', md5(\"password3\"))";

	if ($conn->multi_query($sql) === TRUE) {
	    echo "New records created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	}
} else {
	echo "Table \"Users\" already exits with some records.";
}
?>