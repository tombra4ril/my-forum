<?php
	//check if an error occur when connecting to database
	function handleError($link, $result, $message){
		if(!$result){
			die("Tombra's message: " . $message . "<br>Error: " . mysqli_error($link));
		}
	}

	//This function checks if there is an existing user with the same username
	function checkNoDuplicateUserNames($link, $user){
		$query = "SELECT user_name FROM members";
		$query .= " WHERE user_name = '{$user}'";

		$result = mysqli_query($link, $query);

		//handle error
		handleError($link, $result, "Could not check for duplicate user names");

		$send = true;
		if(mysqli_num_rows($result) > 0){
			$send = false;
		}

		return $send;
	}

	//registers users
	function registerUser($link, $fn, $ln, $un, $pw, $em, $pn, $c, $ip, $lock){
		//prepare query
		$query = "INSERT INTO members";
		$query .= " SET first_name = '{$fn}',";
		$query .= " last_name = '{$ln}',";
		$query .= " user_name = '{$un}',";
		$query .= " password = '{$pw}',";
		$query .= " email = '{$em}',";
		$query .= " phone_number = '{$pn}',";
		$query .= " country = '{$c}',";
		$query .= " ip_address = '{$ip}',";
		$query .= " logged_in = $lock";

		//query the database
		$result = mysqli_query($link, $query);

		//handle any error if any
		handleError($link, $result, "Could not register user.");

		//return result
		return $result;
	}

	//This function is used to return the id of a particular user using the user name of the user
	function getUserIdFromUserName($link, $user){
		$query = "SELECT member_id FROM members";
		$query .= " WHERE user_name = '{$user}'";

		$result = mysqli_query($link, $query);
		//handle any error if any
		handleError($link, $result, "Could not get the user id from user name");

		$item = mysqli_fetch_assoc($result);
		return $item["member_id"];
	}

	//This function is used to return all th columns of a particular member_id from the members table
	function getUserDetails($link, $id){
		$query = "SELECT * FROM members";
		$query .= " WHERE member_id = {$id}";
		$query .= " LIMIT 1";

		$result = mysqli_query($link, $query);
		//handle any error if any
		handleError($link, $result, "Could not get the Member details using the member id");

		return $result;
	}

	//This function is used to set the logged_in column of every member with
	// the same ip address of the one passed
	function checkSetSameIpAddress($link, $ip, $id){
		$query = "UPDATE members SET";
		$query .= " logged_in = 0";
		$query .= " WHERE ip_address = '{$ip}'";
		$query .= " AND member_id !=  {$id}";

		$result = mysqli_query($link, $query);
		//handle any error if any
		handleError($link, $result, "Could not update the members table using the ip addess");
	}


	function checkIpReturnId($link, $ip){
		$query = "SELECT member_id FROM members";
		$query .= " WHERE ip_address= '{$ip}'";
		$query .= " AND logged_in = 1";
		$query .= " LIMIT 1";

		$result = mysqli_query($link, $query);
		//handle any error if any
		handleError($link, $result, "Could not get the member_id using the ip address and logged columns");

		$send = false;
		if(mysqli_num_rows($link) == 1){
			$send = mysqli_fetch_assoc($result);
			$send = $send["member_id"];
		}
		return $send;
	}

	//Called from login.php
	//This function is used check if the user authenticity from the admin table
	function retrieveUserPassId($link, $user, $pass){
		$query = "SELECT member_id, user_name, password";
		$query .= " FROM members";
		$query .= " WHERE user_name = '{$user}'";
		$query .= " AND password = '{$pass}'";
		$query .= " LIMIT 1";

		$result = mysqli_query($link, $query);

		//Handle error if any
		handleError($link, $result, "Could not check the username and password for a match.");

		$row = mysqli_fetch_assoc($result);
		return $row;
	}

	//This function is used to get the forum name using the foroum id
	function getForumDetails($link, $id){
		$query = "SELECT forum_name, forum_description FROM forum_names";
		$query .= " WHERE forum_names_id = {$id}";

		$result = mysqli_query($link, $query);
		handleError($link, $result, "Could not get the details off from the forum_names table");

		$row = mysqli_fetch_assoc($result);
		return $row;
	}

	//This function is used to get the messages result set in any message table
	function getMessageDetails($link, $name){
		$query = "SELECT * FROM $name";
		$query .= " ORDER BY message_time ASC";
		$query .= " LIMIT 10";

		$result = mysqli_query($link, $query);
		handleError($link, $result, "Could not fetch the details from the {$name} table.");

		return $result;
	}

	//This function is used to get the messages result set in any message table
	function getReplyDetails($link, $name, $id){
		$query = "SELECT * FROM $name";
		$query .= " WHERE member_id = {$id}";
		$query .= " ORDER BY reply_time ASC";
		
		$result = mysqli_query($link, $query);
		handleError($link, $result, "Could not fetch the details from the {$name} table.");

		return $result;
	}

	//This function returns the username of the member using the id
	function getUserNameFromId($link, $id){
		$query = "SELECT user_name";
		$query .= " FROM members";
		$query .= " WHERE member_id = '{$id}'";
		$query .= " LIMIT 1";

		$result = mysqli_query($link, $query);

		//Handle error if any
		handleError($link, $result, "Could not fetch the memeber username from the members table");

		$row = mysqli_fetch_assoc($result);
		return $row["user_name"];
	}

	//This function is used to populate the forum with, maybe general_forum when a user registers
	function joinForum($link, $name, $id, $status){
		$query = "INSERT INTO {$name}_forum";
		$query = " ({$id}, {$status})";

		$result = mysqli_query($link, $query);
		handleError($link, $result, "Could not join {$name}_forum.");
	}

	//This function is used to get the member status id from the specified forum table name
	function getMemberStatusId($link, $forum, $id){
		$query = "SELECT member_status_id FROM {$forum}";
		$query .= " WHERE member_id={$id}";

		$result = mysqli_query($link, $query);
		handleError($link, $result, "Could not get the user status id");

		$row = mysqli_fetch_assoc($result);
		return $row["member_status_id"];
	}
?>