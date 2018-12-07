<?php
//This file is used to process any kind of data for that does not have to connect to the database

	//This function is used to return accurate timing for the message board
	function processDate($timing){
		$time = date_parse($timing);//built in associative function that contains everything about date
		$currentYear = getdate()["year"];
		$currentMonth = getdate()["mon"];
		$currentDay = getdate()["mday"];
		$currentHour = getdate()["hours"];
		$currentMinute = getdate()["minutes"];

		$yearDiff = $currentYear - $time["year"];		

		//check and return the accurate date or time
		$message = "Time";
		if($yearDiff > 0){
			$message = "Over a year ago";

			$monthDiff = (12 - $time["month"]) + $currentMonth;
			if($monthDiff < 11){
				$message = "{$monthDiff} months ago";
			};
		}else{
			$monthDiff = $currentMonth - $time["month"];
			$message = "Less than a year";
			if($monthDiff > 1){
				$message = "{$monthDiff} months ago";
			}else if($monthDiff == 1){
				$message = "{$monthDiff} month ago";

				$day = (31 - $time["day"]) + $currentDay;
				if($day >= 2){
					$message = "Over {$day} days ago";
				}else if($day == 1){
					$message = "Yesterday";
				}else{
					$hour = $currentHour - $time["hour"];
					if($hour >= 1){
						$message = "{$hour} hours ago";
					}else{
						$minute = $currentMinute - $time["minute"];
						$message = "{$minute} minutes ago";
					}
				}
			}else{
				$day = $currentDay - $time["day"];
				if($day >= 2){
					$message = "Over {$day} days ago";
				}else if($day == 1){
					$message = "Yesterday";
				}else{
					$hour = $currentHour - $time["hour"];
					if($hour >= 1){
						$message = "{$hour} hours ago";
					}else{
						$minute = $currentMinute - $time["minute"];
						$message = "{$minute} minutes ago";
					}
				}
			}
		}

		return $message;
	}
?>