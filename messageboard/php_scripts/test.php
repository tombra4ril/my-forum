<?php
	$phoneNumber = substr("08105912717", 1);

	//sanitize the country code and phone number
	echo htmlspecialchars(strip_tags($phoneNumber));
?>