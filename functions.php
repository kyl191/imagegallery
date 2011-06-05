<?php
function loadPage($id) {
  global $title, $contentfile, $error;
  if (isset($id) && $id != NULL) {
    $success = 0;
	
    try {
        if(!$file = @fopen("index.txt",r)) {
		    throw new Exception();
		}
    }
	
    catch (Exception $e) {
		$title="Page not found";
		$contentfile = "err.php";
		$error = 2;
		return;
	}
	
	while (!feof($file)) {
		$line = fgets($file);
		preg_match("/\[".$id."\][^\[]*\[([^\]]+)\][^\[]*\[([^\]]+)\]/i",$line,$matches);
		if ($matches != NULL) {
			$success = 1;
			break;
		}
	}
	
	fclose($file);
	
	// Make sure anything other than the filename is stripped
	preg_match("/([^.]+(?:\.[a-zA-Z0-9]+)*)/i",$matches[2],$filename);
	
	if ($success == 1 && file_exists("pages/".$filename[1])) {
	    $title = $matches[1];
	    $contentfile = $filename[1];
	}
	
	else {
	    $title = "Page not found";
		$contentfile = "err.php";
	}
  }
  
  else {
    $title = "Home";
	$contentfile = "comic.php";
  }
  
  return;
}

function parseDate($file,$dayBool) {
// Parse date from filename
	$dateSearch = '/^([0-9]{4})([0-9]{2})([0-9]{2})[^\.]*/';
	preg_match($dateSearch, $file, $dmatch);
	
	if(!$dmatch) {
	 continue;
	}
	
	$invalid = 0;
	
	switch ($dmatch[2]) {
	 case "01":
	  $month = "January";
	  break;
	 case "02":
	  $month = "February";
	  break;
	 case "03":
	  $month = "March";
	  break;
	 case "04":
	  $month = "April";
	  break;
	 case "05":
	  $month = "May";
	  break;
	 case "06":
	  $month = "June";
	  break;
	 case "07":
	  $month = "July";
	  break;
	 case "08":
	  $month = "August";
	  break;
	 case "09":
	  $month = "September";
	  break;
	 case "10":
	  $month = "October";
	  break;
	 case "11":
	  $month = "November";
	  break;
	 case "12":
	  $month = "December";
	  break;
	 default:
	  $invalid = 1;
	  break;
	}
	if (substr($dmatch[3],0,1) == "0") {
	 $day = substr($dmatch[3],1,1);
	}
	else {
	 $day = $dmatch[3];
	}
	
	switch (substr($dmatch[3],-1)) {
	 case "1":
	  $suffix = "st";
	  break;
	 case "2":
	  $suffix = "nd";
	  break;
	 case "3":
	  $suffix = "rd";
	  break;
	 default:
	  $suffix = "th";
	  break;
	}
	
	if ($day == "13" || $day == "12" || $day == "11") {
	 $suffix = "th";
	}
	
	// $suffix = "rd";
	
	$year = $dmatch[1];
	
	if ($dayBool == 1) {
	  $unixtime = @mktime(0,0,0,$dmatch[2],$dmatch[3],$dmatch[1]);
	  $dow = date("l",$unixtime);
	  $dowString = $dow.", ";
	}
	else {
	  $dow = "";
	  $dowString = "";
	}
	
	if ($day > 31 || $day < 1 || $year >= 2100) {
	 $invalid = 1;
	}
	
	if ($invalid != 1) {
	  $string = $dowString.$month ." ". $day ."<span style='font-size:xx-small; vertical-align:top;'>". $suffix ."</span>, ". $year;
	}
	else {
	  $string = "[Invalid date]";
	}
	
	return $string;
}

?>