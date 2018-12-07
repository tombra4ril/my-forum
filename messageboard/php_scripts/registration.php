<?php
	session_start();
	//files to include
	require_once("includes/connection.php");
	include_once("functions/database_functions.php");

	$firstName = trim($_POST["firstName"]);
	$lastName = trim($_POST["lastName"]);
	//sanitize the names above
	$firstName = htmlspecialchars(ucfirst(strtolower($firstName)));
	$lastName = htmlspecialchars(ucfirst(strtolower($lastName)));

	$userName = trim($_POST["userName"]);
	//sanitize the userName
	$userName = strip_tags($userName);

	//Get and encrypt the password of the user
	$password = $_POST["password"];
	$password = sha1(htmlspecialchars($password));

	$email = trim($_POST["email"]);
	//sanitize the email
	$email = htmlspecialchars(strtolower($email));

	$countryCode = trim($_POST["countryCode"]);
	//Get the phone number 10 digits, omit the first digit
	if(strlen($_POST["phoneNumber"]) == 11){
		$phoneNumber = substr($_POST["phoneNumber"], 1);
	}

	//sanitize the country code and phone number
	$countryCode = htmlspecialchars(strip_tags($countryCode));
	$phoneNumber = htmlspecialchars(strip_tags($phoneNumber));
	$phoneNumber = "+" . $countryCode . $phoneNumber;

	$country = trim($_POST["country"]);
	//sanitize the country
	$country = htmlspecialchars(ucfirst(strtolower(trim($country))));

	$submit = $_POST["submit"];

	//Store the ip address of the user
	$ipAddress = $_SERVER["REMOTE_ADDR"];

	if($submit == "submit"){
		if(checkNoDuplicateUserNames($link, $userName)){
			registerUser($link, $firstName, $lastName, $userName, $password, $email, $phoneNumber, $country, $ipAddress, 1);
			$id = getUserIdFromUserName($link, $userName);
			$_SESSION["user_id"] = $id;
			$_SESSION["user_key"] = "authenticated";
			$_SESSION["forum_id"] = 1;

			//check if there is any member with the same ip address and log them out
			checkSetSameIpAddress($link, $ipAddress, $id);

			//Let the user join the general forum
			//Please write this code to do what you want
			joinForum($link, "general", $id, 3);

			//Redirect the user the general.php page
			header("Location: forums/general.php");
			exit;
		}else{
			header("Location: signup.php?q=fail");
			exit;
		}
	}
?>