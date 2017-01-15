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
$conn->close();


// 
// Create table for Store
// 
require "inc/connection.inc.php";
$sql = "CREATE TABLE IF NOT EXISTS Store (ID INT(6) PRIMARY KEY AUTO_INCREMENT NOT NULL, NAME VARCHAR(30) NOT NULL,	PRODUCT_CODE INT(6) NOT NULL UNIQUE, QUANTITY INT(6) NOT NULL DEFAULT 0, DESCRIPTION VARCHAR(300), CATEGORY VARCHAR(20), LAST_CHANGE_BY VARCHAR(30) NOT NULL)";

if ($conn->query($sql) === TRUE) {
    echo "Table \"Store\" created successfully"."<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

$sql = "SELECT * FROM Store";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
	// Populating with sample data
	$sql = "INSERT INTO Store (NAME, QUANTITY, DESCRIPTION, CATEGORY, PRODUCT_CODE, LAST_CHANGE_BY)
	VALUES ('Apple', 450, 'California', 'Mobile', 000001, 'AnirudhGoel');";
	$sql .= "INSERT INTO Store (NAME, QUANTITY, DESCRIPTION, CATEGORY, PRODUCT_CODE, LAST_CHANGE_BY)
	VALUES ('Mouse', 370, 'Wireless + Bluetooth', 'Computer Peripheral', 000002, 'AnirudhGoel');";
	$sql .= "INSERT INTO Store (NAME, QUANTITY, DESCRIPTION, CATEGORY, PRODUCT_CODE, LAST_CHANGE_BY)
	VALUES ('Bru Classic', 500, 'Classic Coffee', 'Food Items', 000003, 'AnirudhGoel')";

	if ($conn->multi_query($sql) === TRUE) {
	    echo "New records created successfully<br>";
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	}
} else {
	echo "Table \"Store\" already exits with some records.<br>";
}
$conn->close();


// 
// Create table for Users
// 
require "inc/connection.inc.php";
$sql = "CREATE TABLE IF NOT EXISTS Users (ID INT(6) PRIMARY KEY AUTO_INCREMENT NOT NULL, USERNAME VARCHAR(30) NOT NULL UNIQUE, PASSWORD VARCHAR(300) NOT NULL, CODE INT(6))";

if ($conn->query($sql) === TRUE) {
    echo "Table \"Users\" created successfully"."<br>";
} else {
    echo "Error creating table: " . $conn->error;
}

$sql = "SELECT * FROM Users";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
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