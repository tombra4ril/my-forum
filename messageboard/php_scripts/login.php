<?php
	session_start();
	$message = "";

	//check if the user wants to logout
	if(isset($_SESSION["user_key"]) && $_SESSION["user_key"] == "authenticated"){
		//Destroy the session completely
		$_SESSION["user_key"] = "";

		//This is done by using the session_name() function which will let php return the name of the session cookie variable itself
		if(isset($_COOKIE[session_name()])){
			//set the session cookie to an expired time using
			// the time() function minus a certain amount of time 
			setcookie(session_name(), "", time() - 1234, "/"); //The "/" is used to go to the root to make sure that the cookie is gotten
		}

		unset($_SESSION);
		session_destroy();

		//Create the logout display message
		$logout = "Logged out succesfully";
	}else{
		if(isset($_POST["username"]) && isset($_POST["password"])){
			//Create the message variable 
			$message = "";

			//include all the necessary files
			require_once("includes/connection.php");
			include_once("functions/database_functions.php");


			//Get username and password
			$user = $_POST["username"];
			$pass = sha1($_POST["password"]);
			$authentication = retrieveUserPassId($link, $user, $pass);

			//Check if the username and password match the ones in the database
			if(($user == $authentication['user_name']) && ($pass == $authentication['password'])){
				//the username and the password match to redirect the user to the next page
				//Create the session variable to hold the authentication
				$_SESSION["user_key"] = "authenticated";
				$_SESSION["user_id"] = $authentication["member_id"];
				$_SESSION["forum_id"] = 1;

				// redirect_to("upload.php");
				header("Location: forums/general.php");
				exit;
			}else{
				$message = "(*) Username or Password Incorrect";
			}
		}else{
			//do nothing and just proceed with the rest of the page, the user just opened the page
			//Set message to display nothing
			$message = "";
		}
	}

	
?>

<!DOCTYPE html>
<html>
	<head lang="en">
		<meta charset="utf-8" />
		<title>Sign up</title>
		<link rel="stylesheet" type="text/css" href="../styles/message-board.css" />
		<link rel="stylesheet" type="text/css" href="../styles/header-footer.css" />
		<link rel="stylesheet" type="text/css" href="../styles/login.css" />
		<link rel="stylesheet" type="text/css" href="../styles/signup.css" />
	</head>

	<body>
		<div id="main-wrapper">
			<header>
				<div id="logo">
					<img src="../images/hacker.gif" alt="Image here" />
				</div>
				<span class="site-message">Tombra Message Board</span>
				<ul>
					<li><span><a href="../index.php">Home</a></span></li>
				</ul>
			</header>

			<section>
				<?php 
					if(!empty($logout)){
						echo "<div id='hide'><p>{$logout}"; 
						echo "<span onclick='setHidden()'>&times;</span></p></div>";
					}
				?>
				
				<article>
					<img src="../images/icons/avatar1.png"/>

					<form action="" method="post">
						<span><?php echo $message; ?></span>
						<p>
							<label>username:</label>
							<input type="text" name="username" placeholder="Enter name" />
						</p>
						<p>
							<label>password:</label>
							<input type="password" name="password" placeholder="Enter password"/>
						</p>
						<button type="submit" name="sub" value="next">Next</button>
					</form>
				</article>
				<p>
					<a href="../index.html">Go Home</a>
				</p>
			</section>

			<footer>
				<p>Copyright &copy; 2018, By Aremieye Tamaratombra.</p>
				<p>Licensed to prosper by the almighty God</p>
			</footer>
		</div>	
	</body>

	<script>
		function setHidden(){
			document.getElementById("hide").style.display = "none";
		}
	</script>
</html>