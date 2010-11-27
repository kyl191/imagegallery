<?php 
// ------------------------------------------------------------------------- //
// Image Gallery v1.0b (based off Comic Gallery 1.2)                         //
// ------------------------------------------------------------------------- //
// By kyl191                                                                 //
// http://code.kyl191.net/imagegallery                                       //
// ------------------------------------------------------------------------- //
// This program is free software; you can redistribute it and/or modify      //
// it under the terms of the GNU General Public License as published by      //
// the Free Software Foundation; either version 2 of the License, or         //
// (at your option) any later version.                                       //
//  A summary is available at http://creativecommons.org/licenses/GPL/2.0/   //
// ------------------------------------------------------------------------- //

// Your images directory, relative to the page calling this script
$imagedir="comics";

// To start at the last image, use "last". To start from the first image, use "start".
$startimage="last";

// Copyright name to display, for none use " "
$copyright="";

// File to store the list of filenames
$filelist = "/tmp/imagegalleryfilelist.dat";

// Creative Commons license, for none use " "
// example: "http://creativecommons.org/licenses/by-nc-sa/3.0/deed"
$creativecommons="";

// type of divider, for none use " "
$divider="&middot;";

// show arrows, for none use 0
$arrows=1;

// show back and next, for none use 0
$backnext=1;

// show first and last, for none use 0
$firstlast=1;

// show numbers, for none use 0
$numbers=1;

// numbers per line
$linelength=20;

// navigation position, for above use "above"
$navplacement="below";

// Type of rebuild 
// - "triggered" refers to having to manually trigger a rebuild by browsing to the archive page with "rebuild" appended to the query string
// - "timer" refers to automatically rebuilding the list every x seconds, as specified in $rebuild_time

$rebuild_type = "timer";

// Rebuild index every x seconds
$rebuild_time=300;

// The folder from which all images should be served
// Use if you have a static image server somewhere else, otherwise leave it blank.
$base = "";

// ------------------------------------------------------------------------- //
// Do not edit below this line.
// ------------------------------------------------------------------------- //

// Import the filelist handling functions.
require("functions.inc.php");

// Check for a debug option. Because this doesn't expose sensitive data, a password shouldn't be needed.
$debug = isset($_GET['debug']);

// Used if the filelist needs to be rebuilt, for now we'll assume no.
$rebuild = false;

// Establish whether the filelist is present and/or writable.
$is_present = file_exists($filelist);
$is_writable = is_writable($filelist);

// Print a debug message if the filelist isn't present or writable.
if ($debug){
	
	if(!$is_present){
		echo "Warning: File list ". $filelist ." is not present. This file must be present for this script to work.";
	}
	
	if (!$is_writable){
		echo "Warning: File list ". $filelist ." is not writable. Check your permissions. (This file <b>must</b> be writable by the web server.)";
	}
	
}

// Determine the type of rebuild procedure and check if a rebuild is required
// Check that the file list is present first though. 
if($is_present){
	// If the type is timer, check if the last modified time exceeds the maximum age between rebuilds of the list. 
	if (strcasecmp($rebuild_type, "timer")==0){
			$rebuild = ((time()-filemtime($filelist))>$rebuild_time);
			if ($debug){
				echo ("Rebuild: ".(time()-filemtime($filelist))>$rebuild_time);
			}
	} else if (strcasecmp($rebuild_type, "triggered")==0){
		// If the rebuild type is "triggered", don't do anything because we're going to check for 'rebuild' in the query string later
	} else {
		// But if we've managed to get this far, something's wrong with the rebuild type option.
		if ($debug){
			echo "Warning: rebuild_type is not set to a supported option. Please check your configuration.";
		}
	}
// If the filelist isn't present, well, we're going to build it.
} else {
	$rebuild = true;
}

// Force rebuild if the user tells us to though
// Future addition: Only allow a rebuild to be forced if debug is enabled?
// Unlikely because anyone can view the debug data, so it's just obfusication
if(isset($_GET['rebuild'])){
	$rebuild = true;
}

// Once we've run through the decision tree, build the file list if necessary.
// However, no sense reading in all the filenames from the drive if we can't write the file out, so check if the file is writable before building the list
if ($rebuild && $is_writable){
	$images = readFilesFromDrive($imagedir);
	sort($images);
	writeFileList($images,$filelist);
	if ($debug){
		echo "Notice: Wrote file list to ".$filelist."\n";
	}
}

// Try reading the filelist
// It's not guarenteed that we wrote an updated file to disk anyway
try {
	$pics = readFileList($filelist);
} catch (Exception $e){
// In the event that something does screw up, tell the user, then fall back to scanning the drive manually
	echo "Oops, something went wrong with the cache. Don't worry though.";
	$pics = readFilesFromDrive($imagedir);
	// And show the specific error if debug is enabled.
	if ($debug){
		echo "Error: ".$e;
	}
}

// Get the number of files in the folder for use in the navigation
$filecount=count($pics);

// If debug is enabled, take the time to print some info.
if($debug){
	echo "Number of image files: ".$filecount."\n";
	echo "Filelist last modified: ".date(DATE_RFC2822, filemtime($filelist))."\n";
	echo "Age of filelist: ".(time()-filemtime($filelist))."\n";
}

// See what image the user is asking for
// Check that a) the user asked for something, and b) he gave a number
if (isset($_GET['p']) && is_numeric($_GET['p'])){
	// Just cast the desired image number to an int for internal storage - technically not necessary, but it will save a few string conversions later.
	$pic=(int)$_GET['p'];
	// In the event that the number specified was larger than the number of images
	// Give them a 404 error and set the number to the largest file
	if ($pic>$filecount){
		header('HTTP/1.0 404 Not Found');
		$pic = $filecount;
	}
// Otherwise, he hasn't specified an image.
// So start from the beginning or end, depending on the config
} else { 
	if ($startimage!="last"){ $pic=1; }
	else { $pic=$filecount; }
}

// Get the requested image file name
$current=$pics[$pic-1];

// Determine if we should preload the next image
// Future enhancement: Also preload the previous image - if someone is working their way *back* through the archive...
if ($pic < $filecount){
	$preload = true;
	$nextimg = $pics[$pic];
} else {
	$preload = false;
}

// Set up the next and last buttons, and make sure they're sane
$next=$pic+1;
if ($next > $filecount){ $next=$filecount; }
$back=$pic-1;
if ($back < 1){ $back=1; }

// If debug mode is enabled, make the back and forward links automatically add debug to the url
if (isset($_GET['debug'])){
	$next .= "&debug";
	$back .= "&debug";
}

// Prepare the image source and links
if ($pic < $filecount){ 
	$image="\n<p id=\"cg_img\"><a href=\"?p=".$next."\"><img src=\"".$base.$imagedir."/".$current."\" alt=\"Next\" /></a></p>\n";
} else {
	$image="\n<p id=\"cg_img\"><img src=\"".$base.$imagedir."/".$current."\" alt=\"End\" /></p>\n";
}

// Note: The navigation bar doesn't move, the image does
// Display the image before the navigation bar if configured that way
if (strcasecmp($navplacement, "above")!=0){
	echo $image;
}

// display the navigation bar
// TODO: Test it to make sure it works! :P 
if ($backnext != 0 || $arrows != 0){
	if ($filecount > 1){
		echo "<p id=\"cg_nav1\">";
		if ($firstlast != 0){ 
			if ($pic > 1){	echo "<a href=\"?p=1\" id=\"cg_first\"><span>First</span></a>"; }
			else { echo "<span id=\"cg_first\"><span>First</span></span>"; }
			echo "<span class=\"cg_divider\"> ".$divider." </span>";
		}
		if ($pic > 1){	
			echo "<a href=\"?p=".$back."\" id=\"cg_back\"><span>";
			if ($arrows != 0) { echo "&laquo; "; }
			if ($backnext != 0) { echo "Back"; }
			echo "</span></a>";
		} else {
			echo "<span id=\"cg_back\"><span>";
			if ($arrows != 0) { echo "&laquo; "; }
			if ($backnext != 0) { echo "Back"; }
			echo "</span></span>";
		}
		echo "<span class=\"cg_divider\"> ".$divider." </span>";
		if ($pic < $filecount){	
			echo "<a href=\"?p=".$next."\" id=\"cg_next\"><span>";
			if ($backnext != 0) { echo "Next"; }
			if ($arrows != 0) { echo " &raquo;"; }
			echo "</span></a>";
		} else {
			echo "<span id=\"cg_next\"><span>";
			if ($backnext != 0) { echo "Next"; }
			if ($arrows != 0) { echo " &raquo;"; }
			echo "</span></span>";
		}
		if ($firstlast != 0){ 
			echo "<span class=\"cg_divider\"> ".$divider." </span>";
			if ($pic < $filecount){	echo "<a href=\"http://2kinds.com\" id=\"cg_last\"><span>Last</span></a>"; }
			else { echo "<a href=\"http://2kinds.com\" id=\"cg_last\"><span>Last</span></a></span>"; }
		}
		echo "</p>\n";
	}
}

// display numbers
if ($numbers != 0){
	if ($filecount > 1){
		//	display textlinks
		echo "<p id=\"cg_nav2\">";
		// loop over images
		for ($f=1;$f<=$filecount;$f++){
			// if the link to the pic is the selected one, display a bold number and no link
			if ($pic==$f){echo "<b>".$f."</b>";}
			// otherwise display the link
			else{echo "<a href=\"?p=".$f."\">".$f."</a>";}
			// add dividers and linebreaks
			if (($f % $linelength) == 0) { 
				echo "<br />";
			}
			else { 
				if ($f!=$filecount){
					echo "<span class=\"cg_divider\"> ".$divider." </span>";
				}
			}
		}
		echo "</p>\n";
	}
}

//  display image below nav
if (strcasecmp($navplacement,"above")==0){
	echo $image;
}


// display copyright
echo "<p id=\"cg_credits\">";
if (strcasecmp($creativecommons, "")!=0){ echo "<a href=\"".$creativecommons."\" title=\"Creative Commons License\">Some Rights Reserved</a> ".$divider.""; }	
if (strcasecmp($copyright, "")!=0){ echo "&copy; ".$copyright." ".$divider." "; }
// Attribution optional, but requested.
echo "<br />Powered by <a href=\"http://code.kyl191.net/imagegallery/\">ImageGallery, a derivation of ComicGallery</a></p>\n";

// Preload the next comic image
if ($preload){
	echo "
<script type=\"text/javascript\">function preloader() {
	if (document.images) {
		var img = new Image();
		img.src = \"". $base.$imagedir."/".$nextimg."\";
	}
}
function addLoadEvent(func) {
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {
		window.onload = func;
	} else {
		window.onload = function() {
			if (oldonload) {
				oldonload();
			}
			func();
		}
	}
}
addLoadEvent(preloader);
</script>\n";
}
?>