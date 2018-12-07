<?php
	session_start();

	//include all the necessary files
	require_once("../includes/connection.php");
	include_once("../functions/database_functions.php");
	include_once("../functions/processing_functions.php");
	
	//check session variables
	if(isset($_SESSION["user_key"])){
		//Then the user just registered
		if($_SESSION["user_key"] != "authenticated"){
			echo "second if";
			//Confirm the user registered
			header("Location: index.php");
			exit;
		}else{}

		//Get also the user_id;
		$user_id = $_SESSION["user_id"];
	}else{
		//The user enters this page using the url and not the right link

		//checks the ip address of the user against those in the database, if there is any be it one
		// or many, 
		if(checkIpReturnId($link, $_SERVER["REMOTE_ADDR"])){
			$user_id = checkIpReturnId($link, $_SERVER["REMOTE_ADDR"]);
		}else{
			//Redirect the user to the log in page, the user is not logged in
			header("Location: ../login.php");
			exit;
		}
	}
	// Get the members data
	$details_set = getUserDetails($link, $user_id);//Gets all the user details in a result set
	$user_details = mysqli_fetch_assoc($details_set);//Stores all the user details into the variable
	$memberStatusId = getMemberStatusId($link, "general_forum", $user_id);

	// Get the forum data
	$forumId = $_SESSION["forum_id"];
	//Use the forum id to get the forum name and description
	$forumDetails = getForumDetails($link, $forumId);
	$forumDesc = $forumDetails["forum_description"];
	$forumName = $forumDetails["forum_name"];

	//Use the forum name to form the forum table name for messages, replies and get the data in the table
	$forumTableMessageName = strtolower($forumName) . "_message";
	$forumTableReplyName = strtolower($forumName) . "_reply_message";

	//get the messages using the forum name gotten from above
	$messageDetails = getMessageDetails($link, $forumTableMessageName);//returns a result set
?>

<!DOCTYPE html>
<html>
	<head lang="en">
		<title>Tombra Forum</title>
		<meta charset="utf-8" />
		<link rel="icon" type="image/x-icon" href="../../images/hacker.ico" />
		<link rel="stylesheet" type="text/css" href="../../styles/message-board.css" />
		<link rel="stylesheet" type="text/css" href="../../styles/header-footer.css" />
		<link rel="stylesheet" type="text/css" href="../../styles/index.css" />
		<link rel="stylesheet" type="text/css" href="../../styles/general.css" />
	</head>

	<body>
		<div id="main-wrapper">
			<header>
				<div id="logo">
					<img src="../../images/hacker.gif" alt="Image here" />
				</div>
				<span class="site-message">Tombra Message Board</span>
				<ul>
					<li><span><a href="general.php">Home</a></span></li>
					<li><span><a href="../login.php">Log Out</a></span></li>
				</ul>
			</header>

			<section id="section">
				<div id="user-name"><?php echo "WELCOME {$user_details['user_name']}"; ?></div>
				<div class="container">
					<?php 
						while($row = mysqli_fetch_assoc($messageDetails)){
							echo "<div class='message'>";
								echo "<div class='message-side'></div>";
									//Container for the avatar, name and time of message
									echo "<div class='avatar-name'>";
										echo "<img src='../../images/icons/user.jpg' alt='avater here' />";
										//get the name of the member of this post
										$userName = getUserNameFromId($link, $row["member_id"]);
										$correctTiming = processDate($row["message_time"]);
										echo "<span class='username'>{$userName}</span>";
										echo "<span class='time'>{$correctTiming}</span>";
									echo "</div>";

									//Draw horizontal rule
									echo "<hr class='rule-below-avatar' />";
									
									//Container to hold the message
									echo "<div class='message-post'>";
										echo "<p>{$row["message"]}</p>";
									echo "</div>";

									//Draw horizontal rule
									echo "<hr class='rule-below-avatar' />";
									
									//Container to hold the number of likes, reply icon
									echo "<div class='replylink-likes'>";
										//Like icon
										echo "<div class='like-icon-div'>";
											echo "<img class='like-icon' src='../../images/icons/heart.jpg' alt='likes' onclick='likePost(this)' /><span>{$row["replies"]}</span>";
										echo "</div>";
										//Reply icon
										echo "<div class='comment-icon-div'>";
											echo "<img onclick='replyToPost()' src='../../images/icons/comment.jpg' alt='reply'/><span>{$row["replies"]}</span>";
										echo "</div>";
										//Hidden div used to reply a post
										echo "<div id='comment' class='reply-box' style='display: none'><textarea rows='2' cols='30'></textarea id='replyMessage'><button type='submit' onclick='replyMessage({$user_id})'>Send</button></div>";
									echo "</div>";
							echo "</div>";

			
						
							$replyDetails = getReplyDetails($link, $forumTableReplyName, $row["member_id"]);//returns a result set
							while($rowReply = mysqli_fetch_assoc($replyDetails)){
								echo "<div class='reply reply-box'>";

									//get the name of the member of this post
									$userName = getUserNameFromId($link, $rowReply["member_id"]);
									$correctTiming = processDate($rowReply["message_time"]);
									echo "<div class='avatar-name'>
										<img src='../../images/icons/user.jpg' alt='avater here' />
										<span class='username'>{$userName}</span>
										<span class='time'>{$correctTiming}</span>
									</div>";

									echo "<hr class='rule-below-avatar' />";
								
									echo "<div class='message-post'>
										<p>{$rowReply["reply"]}</p>
									</div>";

									echo "<hr class='rule-below-avatar' />";
								
									echo "<div class='replylink-likes'>
										<img onclick='replyToPost(this)' src='../../images/icons/comment.jpg' alt='reply'/>
										<div class='like-icon-div'>
											<img class='like-icon' src='../../images/icons/heart.jpg' alt='likes' /><span>12</span>
										</div>
									</div>";
								echo "</div>";
							}
						}
					?>
				</div>
					

				<div>
					<textarea id="commentMessage" cols="50" rows="3"></textarea>
					<button type="submit" name="post" value="post" onclick="commentPost(<?php echo "{$user_id}, {$memberStatusId}"; ?>)">Post</button>
				</div>
			</section>

			<footer>
				<p>Copyright &copy; 2018, By Aremieye Tamaratombra.</p>
				<p>Licensed to prosper by the almighty God</p>
			</footer>
		</div>
	</body>

	<script type="text/javascript">
		function replyToPost(){
			const hiddenDiv = document.getElementById("comment");

			if(hiddenDiv.style.display == "none"){
				hiddenDiv.style.display = "inline-block";
			}else{
				hiddenDiv.style.display = "none";
			}
		}

		function commentPost(id, status){
			//Create a new XMLHttpRequest object
			const xttpObject = new XMLHttpRequest();

			//Get the message string
			const message = document.getElementById("commentMessage").value;

			//post parameters to send
			const postPara = "stat=1" + "&id=" + id + "&status=" + status + "&message=" + message;
			
			//Open and send the message
			xttpObject.open("POST", "postcomment.php", true);
			//Must set the request header if you want to use the post method
			xttpObject.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xttpObject.send(postPara);

			xttpObject.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200){
					displayComment(message, "tombra", "10 A.M");
					// if(this.responseText == "sent"){
					// 	//Do nothing for now
					// 	alert("Messge sent");
					// }else if(this.responseText == "reply"){
					// 	alert("Please try again");
					// }else{
					// 	alert("Error, something bad happened");
					// }
					
				}
			};
		}

		function replyMessage(id){
			//Create a new XMLHttpRequest object
			const xttpObject = new XMLHttpRequest();

			//Get the message string
			const message = document.getElementById("replyMessage");
			
			//Open and send the message
			xttpObject.open("POST", "postcomment.php", true);
			xttpObject.send("sta=2&message=" + message);

			xttpObject.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 2){
					//Do nothing for now
				}
			};
		}

		function displayComment(comment, username, time){
			//Get all section element
			const messageDiv = document.getElementsByClassName("container")[0];

			//create all the new divs to use
			const userNameDivElement = document.createElement("div");
			const userNameDivText = document.createTextNode("tombra");
			userNameDivElement.appendChild(userNameDivText);

			const timeDivElement = document.createElement("div");
			const timeDivText = document.createTextNode("10 A.M");
			timeDivElement.appendChild(timeDivText);

			const commentDivElement = document.createElement("div");
			const commentDivText = document.createTextNode(comment);
			commentDivElement.appendChild(commentDivText);
			
			//Append the necessary divs(elements) to display
			messageDiv.appendChild(userNameDivElement);
			messageDiv.appendChild(timeDivElement);
			messageDiv.appendChild(commentDivElement);
		}
	</script>
</html>