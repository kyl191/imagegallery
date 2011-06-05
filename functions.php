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
	$unixtime = DateTime::createFromFormat("Ymd", substr($file,0,8));
	return $unixtime->format("l, F j")."<span style='font-size:xx-small; vertical-align:top;'>".$unixtime->format("S")."</span>".$unixtime->format(", Y");
}

?>