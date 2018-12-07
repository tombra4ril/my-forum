<?php
//include all the necessary files
	require_once("../includes/connection.php");
	include_once("../functions/database_functions.php");
	include_once("../functions/processing_functions.php");

	//Get and sanitize the message
	$messagePost =  $_POST["message"];
	$status = $_POST["status"];

	if($_POST["stat"] == 1){
		$query = "INSERT INTO general_message";
		$query .= " (member_id, member_status_id, message)";
		$query .= " VALUES({$_POST["id"]}, {$status}, '{$messagePost}')";

		//query the database
		$result = mysqli_query($link, $query);

		//Handle any error
		handleError($link, $result, "Could not insert the message sent to the database using ajax.");

		//echo back a respose to work with
		echo "sent";
	}elseif($_POST["stat"] == 2){
		$query = "INSERT INTO general_reply_message";
		$query .= " (member_id, member_status_id, message)";
		$query .= " VALUES('{$_POST["id"]}', '{$status}', {$message})";

		//query the database
		$result = mysqli_query($link, $query);

		//Handle any error
		handleError($link, $result, "Could not insert the message sent to the database using ajax.");

		//echo back a respose to work with
		echo "not sent";
	}else{
		echo "retry";
	}
?>