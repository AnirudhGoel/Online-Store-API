<?php

$response = array();

if (isset($_GET['username']) && !empty($_GET['username']) && isset($_GET['password']) && !empty($_GET['password'])) {
	require 'inc/connection.inc.php';

	$username = $_GET['username'];
	$password = md5($_GET['password']);

	$sql = "SELECT PASSWORD FROM Users WHERE USERNAME = \"$username\"";
	// error_log($sql, 0);
	$result = $conn->query($sql);
	$pass = $result->fetch_assoc();

	if (empty($pass)) {
		$response['error'] = "No user with username \"$username\"";
	} else {
		$actual_password = $pass['PASSWORD'];
		if ($password == $actual_password) {
			$code = mt_rand(111111, 999999);
			$sql = "UPDATE Users SET CODE = $code WHERE USERNAME = \"$username\"";
			error_log($sql);
			$conn->query($sql);
			$response['message'] = "User Verified";
			$response['code'] = $code;
		} else {
			$response['error'] = "Wrong password entered for \"$username\"";
		}
	}
} else {
	$response['error'] = "Invalid API call. Incomplete data.";
}

echo(json_encode($response, true));