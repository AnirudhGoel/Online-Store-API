<?php

$response = array();
require 'inc/connection.inc.php';

// 
// LOGIN, ADD, EDIT, DELETE, SEARCH
// 
if (isset($_GET['username']) && !empty($_GET['username']) && isset($_GET['password']) && !empty($_GET['password']) && isset($_GET['method']) && $_GET['method'] == "login") {
	$username = $_GET['username'];
	$password = md5($_GET['password']);

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
} elseif (isset($_GET['code']) && !empty($_GET['code'])) {
	$code = $_GET['code'];
	$sql = "SELECT USERNAME FROM Users WHERE CODE = $code";
	$result = $conn->query($sql);
	if ($result->num_rows == 1) {
		$row = $result->fetch_assoc();
		$last_change_by = $row['USERNAME'];

		if (isset($_GET['name']) && !empty($_GET['name']) && isset($_GET['quantity']) && !empty($_GET['quantity']) && isset($_GET['product_code']) && !empty($_GET['product_code']) && isset($_GET['method']) && $_GET['method'] == "add") {
			// 
			// ADD
			// 

			error_log($last_change_by."-".$_GET['method']);

			$sql1 = "INSERT INTO Store (LAST_CHANGE_BY, NAME, QUANTITY, PRODUCT_CODE";
			$sql2 = ") VALUES (\"$last_change_by\", '".$_GET['name']."', ".$_GET['quantity'].", ".$_GET['product_code'];

			if (isset($_GET['category']) && !empty($_GET['category'])) {
				$sql1 .= ", CATEGORY";
				$sql2 .= ", '".$_GET['category']."'";
			}
			if (isset($_GET['description']) && !empty($_GET['description'])) {
				$sql1 .= ", DESCRIPTION";
				$sql2 .= ", '".$_GET['description']."'";
			}

			$sql = $sql1.$sql2.")";
			error_log($sql, 0);

			if ($conn->query($sql) === TRUE) {
				$response['result'] = "Record added successfully.";
			} else {
				$response['result'] = "Error adding record: ".$conn->error;
			}

		} elseif (isset($_GET['product_code']) && !empty($_GET['product_code']) && isset($_GET['method']) && $_GET['method'] == "delete") {
			// 
			// DELETE
			// 

			$product_code = $_GET['product_code'];

			$sql = "DELETE FROM Store WHERE PRODUCT_CODE = $product_code";
			error_log($sql, 0);
			if ($conn->query($sql)) {
				$response['result'] = "Record Deleted successfully.";
			} else {
				$response['result'] = "Error Deleting record:".$conn->error;
			}

		} elseif (isset($_GET['product_code']) && !empty($_GET['product_code']) && isset($_GET['method']) && $_GET['method'] == "modify") {
			// 
			// MODIFY
			// 

			$product_code = $_GET['product_code'];

			$sql1 = "UPDATE Store SET ";
			$sql2 = "WHERE PRODUCT_CODE = $product_code";

			if (isset($_GET['name']) && !empty($_GET['name'])) { $sql1 .= "NAME = '".$_GET['name']."', "; }
			if (isset($_GET['quantity']) && !empty($_GET['quantity'])) { $sql .= "QUANTITY = ".$_GET['quantity'].", "; }
			if (isset($_GET['category']) && !empty($_GET['category'])) { $sql .= "CATEGORY = '".$_GET['category']."', "; }
			if (isset($_GET['description']) && !empty($_GET['description'])) { $sql .= "DESCRIPTION = '".$_GET['description']."', "; }
			$sql1 .= " LAST_CHANGE_BY = \"$last_change_by\" ";

			$sql = $sql1.$sql2;

			if ($conn->query($sql)) {
				$response['result'] = "Record Modified.";
			} else {
				$response['result'] = "Error Modifying Record: ".$conn->error;
			}

		} elseif (isset($_GET['method']) && $_GET['method'] == "search") {
			// 
			// SEARCH
			// 

			$sql = "SELECT * FROM Store WHERE ";
			
			if (isset($_GET['name']) && !empty($_GET['name'])) { $sql .= "NAME = '".$_GET['name']."' AND "; }
			if (isset($_GET['product_code']) && !empty($_GET['product_code'])) { $sql .= "PRODUCT_CODE = ".$_GET['product_code']." AND "; }
			if (isset($_GET['quantity']) && !empty($_GET['quantity'])) { $sql .= "QUANTITY = ".$_GET['quantity']." AND "; }
			if (isset($_GET['category']) && !empty($_GET['category'])) { $sql .= "CATEGORY = '".$_GET['category']."' AND "; }

			$sql = rtrim($sql, " AND ");
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
					$i += 1;
				}
			} else {
				$response['result'] = "No Items found.";
			}
		} elseif (isset($_GET['method']) && $_GET['method'] == "display_all") {
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