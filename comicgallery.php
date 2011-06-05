<?php 
// ------------------------------------------------------------------------- //
// Image Gallery 1.4                                                         //
// ------------------------------------------------------------------------- //
// Modified by Brandon Dusseau                                               //
// http://www.brandonjd.net                                                  //
// ------------------------------------------------------------------------- //
// This program is free software; you can redistribute it and/or modify      //
// it under the terms of the GNU General Public License as published by      //
// the Free Software Foundation; either version 2 of the License, or         //
// (at your option) any later version.                                       //
//  A summary is available at http://creativecommons.org/licenses/GPL/2.0/   //
// ------------------------------------------------------------------------- //
// Edit the code below to configure your Comic Gallery                       //
// ------------------------------------------------------------------------- //

// Your images directory, relative to the page calling this script
$imagedir="comics";

// To start at the last image use "last"
$startimage="last";

// Copyright name to display, for none use " "
$copyright=" ";

// Creative Commons license, for none use " "
// example: "http://creativecommons.org/licenses/by/2.0/"
$creativecommons=" ";

// type of divider, for none use " "
$divider="&middot;";

// show arrows, for none use 0
$arrows=1;

// show back and next, for none use 0
$backnext=1;

// show back and next, for none use 0
$firstlast=1;

// show numbers, for none use 0
$numbers=0;

// numbers per line
$linelength=20;

// navigation position, for aboe use "above"
$navplacement="below";

// ------------------------------------------------------------------------- //
// Do not edit below this line
// ------------------------------------------------------------------------- //

//	initialize variables
$pics=array();
$count=0;

// Open the directory
$comicdir=opendir($imagedir);

//	read directory into pics array
while (($file = readdir($comicdir))!==false) {
	//	filter for jpg, gif or png files... 	
	if (substr($file,-4) == ".jpg" || substr($file,-4) == ".gif" || substr($file,-4) == ".png" || substr($file,-4) == ".JPG" || substr($file,-4) == ".GIF" || substr($file,-4) == ".PNG"){
		$pics[$count] = $file;
		$count++;
		}
}
closedir($comicdir); 

// check for the picture to view
$pic=$_GET['p'];
//	if no picture variable...
if ($pic=="") { 
	if ($startimage!="last"){ $pic=1; }
	else { $pic=$count; }
 }


//	sort the filenames alphabetically
sort($pics);
reset($pics);

//	determine which picture to get
for ($f=0;$f<=sizeof($pics)-1;$f++){if ($pic==$pics[$f]){$selected = $f+1;}}

//echo "&middot Page ".$pic." &middot";

//  check for javascript...
if ($pic && !preg_match("/javascript/",$pic)){

	//  get current image file
	$current=$pics[$pic-1];
	$next=$pic+1;
	if ($next > sizeof($pics)){ $next=sizeof($pics); }
	$back=$pic-1;
	if ($back < 1){ $back=1; }
	
    echo "<p class='date'>Comic for ".parseDate($current,1)."</p>\n";
	
	//  display image above nav
	if ($navplacement!="above"){
		if (substr($current,-4) == ".jpg" || substr($current,-4) == ".gif" || substr($current,-4) == ".png" || substr($current,-4) == ".JPG" || substr($current,-4) == ".GIF" || substr($current,-4) == ".PNG"){
				if ($pic < sizeof($pics)){ 
					echo"\n<p id=\"cg_img\"><a href=\"?p=".$next."\"><img src=\"".$imagedir."/".$current."\" alt=\"Next\" /></a></p>\n";
				} else {
				echo"\n<p id=\"cg_img\"><img src=\"".$imagedir."/".$current."\" alt=\"End\" /></p>\n";
				}
			}
	}

// echo "<p>\n";

	// display back and next
	if ($backnext != 0 || $arrows != 0){		
			if (sizeof($pics) > 1){
				echo "<p id=\"cg_nav1\">";
				if ($firstlast != 0){ 
					if ($pic > 1){	echo "<a href=\"?p=1\" id=\"cg_first\"><span>First Comic</span></a>"; }
					else { echo "<span id=\"cg_first\"><span>First Comic</span></span>"; }
					echo "<span class=\"cg_divider\"> ".$divider." </span>";
				}
				if ($pic > 1){	
					echo "<a href=\"?p=".$back."\" id=\"cg_back\"><span>";
					if ($arrows != 0) { echo "&laquo; "; }
					if ($backnext != 0) { echo "Previous Comic"; }
					echo "</span></a>";
				} else {
					echo "<span id=\"cg_back\"><span>";
					if ($arrows != 0) { echo "&laquo; "; }
					if ($backnext != 0) { echo "Previous Comic"; }
					echo "</span></span>";
				}
				echo "<span class=\"cg_divider\"> ".$divider." </span>";
echo "<a href=\"http://twokinds.keenspot.com/?pageid=3\">Archives</a>";
				echo "<span class=\"cg_divider\"> ".$divider." </span>";
				if ($pic < sizeof($pics)){	
					echo "<a href=\"?p=".$next."\" id=\"cg_next\"><span>";
					if ($backnext != 0) { echo "Next Comic"; }
					if ($arrows != 0) { echo " &raquo;"; }
					echo "</span></a>";
				} else {
					echo "<span id=\"cg_next\"><span>";
					if ($backnext != 0) { echo "Next Comic"; }
					if ($arrows != 0) { echo " &raquo;"; }
					echo "</span></span>";
				}
				if ($firstlast != 0){ 
					echo "<span class=\"cg_divider\"> ".$divider." </span>";
					if ($pic < sizeof($pics)){	echo "<a href=\"index.php\" id=\"cg_last\"><span>Today's Comic</span></a>"; }
					else { echo "<a href=\"index.php\" id=\"cg_last\"><span>Today's Comic</span></a>"; }
				}				
				echo "</p>\n";
			}
	}
	
	// display numbers	
	if ($numbers != 0){
		if (sizeof($pics) > 1){
			//	display textlinks
			echo "<p id=\"cg_nav2\">";
			// loop over images
			for ($f=1;$f<=sizeof($pics);$f++){
				// if the link to the pic is the selected one, display a bold number and no link
				if ($pic==$f){echo "<b>".$f."</b>";}
				// otherwise display the link
				else{echo "<a href=\"?p=".$f."\">".$f."</a>";}
				// add dividers and linebreaks
				if (($f % $linelength) == 0) { 
					echo "<br />";
				}
				else { 
					if ($f!=sizeof($pics)){
						echo "<span class=\"cg_divider\"> ".$divider." </span>";
					}
				}			
			}
			echo "</p>\n";
		}
	}	
	
	//  display image below nav
	if ($navplacement=="above"){
		if (substr($current,-4) == ".jpg" || substr($current,-4) == ".gif" || substr($current,-4) == ".png" || substr($current,-4) == ".JPG" || substr($current,-4) == ".GIF" || substr($current,-4) == ".PNG"){
				if ($pic < sizeof($pics)){ 
					echo"\n<p id=\"cg_img\"><a href=\"?p=".$next."\"><image src=\"".$imagedir."/".$current."\" alt=\"Next\" border=\"0\"></a></p>\n";
				} else {
				echo"\n<p id=\"cg_img\"><image src=\"".$imagedir."/".$current."\" alt=\"End\" /></p>\n";
				}
			}
	}
	
	// display copyright
	echo "<p id=\"cg_credits\">";
	if ($creativecommons != " "){ echo "<a href=\"".$creativecommons."\" title=\"Creative Commons License\">Some Rights Reserved</a> ".$divider." "; }	
	else {
		if ($copyright != " "){ echo "&copy; ".$copyright." ".$divider." "; }
	}
	echo "</p>";
}
?>
<p class="arch-disc">Powered by <a href="http://code.kyl191.net/imagegallery/">ImageGallery</a>, a derivation of ComicGallery</p>