<?php

$response = array();
require 'inc/connection.inc.php';

// 
// LOGIN, ADD, EDIT, DELETE, SEARCH
// 
if (isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['method']) && $_POST['method'] == "login") {
	$username = $_POST['username'];
	$password = md5($_POST['password']);

	$sql = "SELECT PASSWORD FROM Users WHERE USERNAME = \"$username\"";
	$result = $conn->query($sql);

	if ($result->num_rows == 0) {
		$response['result'] = "No user with username \"$username\"";
	} else {
		$row = $result->fetch_assoc();
		$actual_password = $row['PASSWORD'];
		if ($password == $actual_password) {
			$code = mt_rand(111111, 999999);
			$sql = "UPDATE Users SET CODE = $code WHERE USERNAME = \"$username\"";
			// error_log($sql, 0);
			$conn->query($sql);
			$response['result'] = "User Verified";
			$response['code'] = $code;
		} else {
			$response['result'] = "Wrong password entered for \"$username\"";
		}
	}
} elseif (isset($_POST['code']) && !empty($_POST['code'])) {
	$code = $_POST['code'];
	$sql = "SELECT USERNAME FROM Users WHERE CODE = $code";
	$result = $conn->query($sql);
	if ($result->num_rows == 1) {
		$row = $result->fetch_assoc();
		$last_change_by = $row['USERNAME'];

		if (isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['quantity']) && !empty($_POST['quantity']) && isset($_POST['product_code']) && !empty($_POST['product_code']) && isset($_POST['method']) && $_POST['method'] == "add") {
			// 
			// ADD
			// 

			error_log($last_change_by."-".$_POST['method']);

			$sql1 = "INSERT INTO Store (LAST_CHANGE_BY, NAME, QUANTITY, PRODUCT_CODE";
			$sql2 = ") VALUES (\"$last_change_by\", '".$_POST['name']."', ".$_POST['quantity'].", ".$_POST['product_code'];

			if (isset($_POST['category']) && !empty($_POST['category'])) {
				$sql1 .= ", CATEGORY";
				$sql2 .= ", '".$_POST['category']."'";
			}
			if (isset($_POST['description']) && !empty($_POST['description'])) {
				$sql1 .= ", DESCRIPTION";
				$sql2 .= ", '".$_POST['description']."'";
			}

			$sql = $sql1.$sql2.")";
			error_log($sql, 0);

			if ($conn->query($sql) === TRUE) {
				$response['result'] = "Record added successfully.";
			} else {
				$response['result'] = "Error adding record: ".$conn->error;
			}

		} elseif (isset($_POST['product_code']) && !empty($_POST['product_code']) && isset($_POST['method']) && $_POST['method'] == "delete") {
			// 
			// DELETE
			// 
			error_log($last_change_by."-".$_POST['method']);

			$product_code = $_POST['product_code'];

			$sql = "SELECT * FROM Store WHERE PRODUCT_CODE = $product_code";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				$sql = "DELETE FROM Store WHERE PRODUCT_CODE = $product_code";
				error_log($sql, 0);
				if ($conn->query($sql)) {
					$response['result'] = "Record Deleted successfully.";
				} else {
					$response['result'] = "Error Deleting record:".$conn->error;
				}
			} else {
				$response['result'] = "No record exists with Product code = $product_code";
			}

		} elseif (isset($_POST['product_code']) && !empty($_POST['product_code']) && isset($_POST['method']) && $_POST['method'] == "modify") {
			// 
			// MODIFY
			// 
			error_log($last_change_by."-".$_POST['method']);

			$product_code = $_POST['product_code'];

			$sql1 = "UPDATE Store SET ";
			$sql2 = "WHERE PRODUCT_CODE = $product_code";

			if (isset($_POST['name']) && !empty($_POST['name'])) { $sql1 .= "NAME = '".$_POST['name']."', "; }
			if (isset($_POST['quantity']) && !empty($_POST['quantity'])) { $sql .= "QUANTITY = ".$_POST['quantity'].", "; }
			if (isset($_POST['category']) && !empty($_POST['category'])) { $sql .= "CATEGORY = '".$_POST['category']."', "; }
			if (isset($_POST['description']) && !empty($_POST['description'])) { $sql .= "DESCRIPTION = '".$_POST['description']."', "; }
			$sql1 .= " LAST_CHANGE_BY = \"$last_change_by\" ";

			$sql = $sql1.$sql2;

			if ($conn->query($sql)) {
				$response['result'] = "Record Modified.";
			} else {
				$response['result'] = "Error Modifying Record: ".$conn->error;
			}

		} elseif (isset($_POST['method']) && $_POST['method'] == "search") {
			// 
			// SEARCH
			// 

			$sql = "SELECT * FROM Store WHERE ";
			
			if (isset($_POST['name']) && !empty($_POST['name'])) { $sql .= "NAME = '".$_POST['name']."' AND "; }
			if (isset($_POST['product_code']) && !empty($_POST['product_code'])) { $sql .= "PRODUCT_CODE = ".$_POST['product_code']." AND "; }
			if (isset($_POST['quantity']) && !empty($_POST['quantity'])) { $sql .= "QUANTITY = ".$_POST['quantity']." AND "; }
			if (isset($_POST['category']) && !empty($_POST['category'])) { $sql .= "CATEGORY = '".$_POST['category']."' AND "; }

			$sql = rtrim($sql, " AND ");
			error_log($sql, 0);
			$result = $conn->query($sql);
			$i = 0;

			if ($result->num_rows > 0) {
				$result = $conn->query($sql);
				while ($row = $result->fetch_assoc()) {
					$response[$i]['Name'] = $row['NAME'];
					$response[$i]['Product_code'] = $row['PRODUCT_CODE'];
					$response[$i]['Quantity'] = $row['QUANTITY'];
					$response[$i]['Category'] = $row['CATEGORY'];
					$response[$i]['Description'] = $row['DESCRIPTION'];
					$response[$i]['Last Change By'] = $row['LAST_CHANGE_BY'];
					$i += 1;
				}
			} else {
				$response['result'] = "No Items found.";
			}
		} elseif (isset($_POST['method']) && $_POST['method'] == "display_all") {
			// 
			// DISPLAY ALL RECORDS
			// 

			$sql = "SELECT * FROM Store";
			$result = $conn->query($sql);
			$i = 0;
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$response[$i]['Name'] = $row['NAME'];
					$response[$i]['Product_code'] = $row['PRODUCT_CODE'];
					$response[$i]['Quantity'] = $row['QUANTITY'];
					$response[$i]['Category'] = $row['CATEGORY'];
					$response[$i]['Description'] = $row['DESCRIPTION'];
					$response[$i]['Last_Change_By'] = $row['LAST_CHANGE_BY'];
					$i += 1;
				}
			} else {
				$response['result'] = "No Records found.";
			}
		} else {
			$response['result'] = "Invalid API call. Incomplete data.";
		}
	} else {
		$response['result'] = "Invalid Code. Please enter the correct code or try logging in again.";
	}
} else {
	$response['result'] = "Invalid API call. Incomplete data.";
}

echo(json_encode($response, true));