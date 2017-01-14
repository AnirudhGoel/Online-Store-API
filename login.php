<?php

$response = array();

if (isset($_GET['username']) && !empty($_GET['username']) && isset($_GET['password']) && !empty($_GET['password'])) {
	require 'inc/connection.inc.php';

	$username = $_GET['username'];
	$password = md5($_GET['password']);

	$sql = "SELECT PASSWORD FROM Users WHERE USERNAME = \"$username\"";
	error_log($sql, 0);
	$result = $conn->query($sql);
	$pass = $result->fetch_assoc();

	if (empty($pass)) {
		$response['error'] = "No user with username \"$username\"";
	} else {
		$actual_password = $pass['PASSWORD'];
		if ($password == $actual_password) {
			$code = mt_rand(111111, 999999);
			
			$response['message'] = "User Verified";
		} else {
			$response['error'] = "Wrong password entered for \"$username\"";
		}
	}

	// print_r($result);
	// print_r($pass);
	// echo $actual_password;
} else {
	$response['error'] = "Invalid API call. Incomplete data.";
}

echo(json_encode($response, true));