<?php
	include_once("constants.php");

	$link = mysqli_connect(HOST, USER, PASSWORD, DB);

	if(!$link){
		die($message . "<br>Error: " . mysqli_error($link));
	}
?>