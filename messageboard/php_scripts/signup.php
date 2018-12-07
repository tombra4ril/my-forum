<!DOCTYPE html>
<html>
	<head lang="en">
		<meta charset="utf-8" />
		<title>Sign up</title>
		<link rel="stylesheet" type="text/css" href="../styles/message-board.css" />
		<link rel="stylesheet" type="text/css" href="../styles/header-footer.css" />
		<link rel="stylesheet" type="text/css" href="../styles/signup.css" />
	</head>

	<body>
		<div id="main-wrapper">
			<header>
				<div id="logo">
					<img src="../images/hacker.gif" alt="Image here" />
				</div>
				<span class="site-message">Tombra Message Board</span>
			</header>

			<section>
				<?php
					if(isset($_GET["q"]) && $_GET["q"] == "fail"){
						echo "<h2 style='color: red'>Registration Failed";
						echo "<br />Please try again with a different user name.</h2><br />";
					}
				?>
				<h2>Please fill the form below</h2>
				<form action="registration.php" method="post">
					<fieldset>
						<legend>personal information</legend>
						<p>
							<label>first name:</label>
							<input onblur="lowerUpper(this)" type="text" name="firstName" placeholder="First Name" required />
						</p>
						<p>
							<label>last name:</label>
							<input onblur="lowerUpper(this)" type="text" name="lastName" placeholder="Last Name" required />
						</p>
						<p>
							<label>user name:</label>
							<input type="text" name="userName" placeholder="User Name" required />
						</p>
						<p>
							<label>password:</label>
							<input id="pword" onblur="pw(this)" type="password" name="password"  placeholder="Password" required />
							<span><img class="show-hide" onclick="showHideInputP()" src="../images/icons/eye.png" alt="eye" /></span>
							<span id="pass" style="color: red"></span>
						</p>
						<p>
							<label>confirm password:</label>
							<input id="cpword" onblur="confirm(this)" type="password" name="confirmPassword" placeholder="Confirm Password" required/>
							<span><img class="show-hide" onclick="showHideInputC()" src="../images/icons/eye.png" alt="eye" /></span>
							<span id="confirm" style="color: red"></span>
						</p>
						<p>
							<label>email:</label>
							<input onblur="em(this)" type="text" name="email" placeholder="Email" required/>
							<span id="emailSpan" style="color: red"></span>
						</p>
						<p>
							<label>phone number:</label>
							<label id="phone-number">+</label>
							<input type="text" name="countryCode" size=3" maxlength="3" value="234" />
							<input onblur="phone(this)" type="text" name="phoneNumber" maxlength="11" placeholder="Phone Number" required/>
							<span id="phoneSpan" style="color: red"></span>
						</p>
						<p>
							<label>country:</label>
							<input onblur="lowerUpper(this)" type="text" name="country" value="Nigeria" placeholder="Country" required/>
						</p>
					</fieldset>
					<p>
						<button type="submit" value="submit" name="submit">Submit</button>
					</p>
					<p>
						<button type="clear">Clear</button>
					</p>
					<p>
						<a href="../index.php">
							<button type="button">Cancel</button>
						</a>
					</p>
				</form>
			</section>

			<footer>
				<p>Copyright &copy; 2018, By Aremieye Tamaratombra.</p>
				<p>Licensed to prosper by the almighty God</p>
			</footer>
		</div>
	</body>
	<script type="text/javascript">
		function lowerUpper(obj){
			obj.value = obj.value.trim();
			obj.value = obj.value.toLowerCase();
			let fc = obj.value.charAt(0).toUpperCase();
			let rest = obj.value.substr(1);

			obj.value = fc + "" + rest;
		}

		function pw(obj){
			let length = obj.value.length;
			if(length < 8){
				document.getElementById("pass").innerHTML = "* Must be at least 8 characters long";
			}else{
				document.getElementById("pass").innerHTML = "";
			}
		}

		function confirm(obj){
			if(obj.value != document.getElementById("pword").value){
				document.getElementById("confirm").innerHTML = "* Does not match password";
			}else{
				document.getElementById("confirm").innerHTML = "";
			}
		}

		function showHideInputC(){
			let obj = document.getElementById("cpword");
			let type = obj.getAttribute("type");
			if(type == "password"){
				obj.removeAttribute("type");
				obj.setAttribute("type", "text");
			}else{
				obj.removeAttribute("type");
				obj.setAttribute("type", "password");
			}
		}

		function showHideInputP(){
			let obj = document.getElementById("pword");
			let type = obj.getAttribute("type");
			if(type == "password"){
				obj.removeAttribute("type");
				obj.setAttribute("type", "text");
			}else{
				obj.removeAttribute("type");
				obj.setAttribute("type", "password");
			}
		}

		function em(obj){
			let pattern = /(([0-9]+)|([a-z]+))((@yahoo.com)|(@gmail.com)|(hotmail.com))/gi;
			if(pattern.test(obj.value)){
				document.getElementById("emailSpan").innerHTML = "";
			}else{
				document.getElementById("emailSpan").innerHTML = "* Not a valid email address";
			}
		}

		function phone(obj){
			if(isNaN(obj.value)){
				document.getElementById("phoneSpan").innerHTML = "* Cannot be Alphabets";
			}
			else if(obj.value.length != 11){
				document.getElementById("phoneSpan").innerHTML = "* Must be 11 digits";
			}else{
				let pattern = /(07(0|1)[0-9]+)|(08(0|1)[0-9]+)|(09(0|1)[0-9]+)/gi;
				if(pattern.test(obj.value)){
					document.getElementById("phoneSpan").innerHTML = "";
				}else{
					document.getElementById("phoneSpan").innerHTML = "* Not a valid phone number";
				}
			}
			
		}
	</script>
</html>