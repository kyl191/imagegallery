<?php 
// Dump all filenames to an array
function readFilesFromDrive($imagedirpath, $onlynumeric = False){
	$images=array();
	// Open the directory
	$imagedir=opendir($imagedirpath);
	// Read directory into image array
	while (($file = readdir($imagedir))!==false) {
		// filter for jpg, gif or png files... 
		// However, we're also doing numeric comparisons!
		echo $file."<br>";
		if ((strcasecmp(substr($file,-4),".jpg") == 0 || strcasecmp(substr($file,-4),".gif") == 0 || strcasecmp(substr($file,-4),".png") == 0 )) {
			if ($onlynumeric && is_numeric(substr($file,0,8))) {
				array_push($images,$file);
			}
		}
	}
	closedir($imagedir); 
	// don't sort the array, let the script will handle the sorting
	reset($images);
	return $images; 
}

// Dump the array to the drive
// But use an exclusive lock so we don't have race conditions.
function writeFileList($array,$path){
	file_put_contents($path,serialize($array),LOCK_EX);
}

function readFileList($path){
	return unserialize(file_get_contents($path));
}
?>