<?php /* Keenspot Lazy-php caching mechanism. Used on twokinds and flipside. */

header("Cache-Control: max-age=1800,s-maxage=1800,must-revalidate, proxy-revalidate");
$directpageid=0;
$cdncname="http://cdn.twokinds.keenspot.com";

$cachefilename="/spot/twokinds/public_html/cache/comic0.html";

if(isset($_GET["p"]) && is_numeric($_GET["p"])){
$directpageid=$_GET["p"];
}

$cachefilename="/spot/twokinds/public_html/cache/comic".$directpageid.".html";




if ( file_exists($cachefilename) && filemtime($cachefilename)>= filemtime("const.php"))
{
$lastmod = gmdate('D, d M Y H:i:s', filemtime($cachefilename)) . ' GMT';
$etag  = md5($cachefilename.'.'.$lastmod);
$retag = "BEEF";
//$lastmod = gmdate('r', filemtime($cachefilename));
$retag = "BEEF";
if(isset($_SERVER['HTTP_IF_NONE_MATCH'])){
$retag=$_SERVER['HTTP_IF_NONE_MATCH'];
}
$lastdate=@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
$thisdate=@strtotime($lastmod);
header("Last-Modified: $lastmod");
header("ETag: \"$etag\"");
if (($thisdate<=$lastdate ) ||$etag==$retag)
{
header("HTTP/1.1 304 Not Modified");
exit;
}
    $content = file_get_contents($cachefilename);
    echo $content;
echo "<!--".$lastmod."-->";

}
else

{

ob_start();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="<?php echo $cdncname ?>/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $cdncname ?>/archive.css" />
<link rel="icon" type="image/x-icon" href="favicon.ico" />
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
<meta name="description" content="A webcomic about a clueless hero, a mischievous tigress, an angsty warrior, and a gender-confused wolf." />
<link rel="alternate" type="application/rss+xml" title="RSS" href="rss.php" />

<?php
    $siteTitle = "Twokinds";
    @include_once("const.php");
?>
<title><?php echo "Archive - ".$siteTitle; ?></title>

<script type="text/javascript">
/* <![CDATA[ */
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-1156969-34']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
/* ]]> */
</script>

<!-- Quantcast Tag, part 1 -->
<script type="text/javascript">
/* <![CDATA[ */
var _qevents = _qevents || [];
(function() {
var elem = document.createElement('script');
elem.src = (document.location.protocol == "https:" ? "https://secure": "http://edge") + ".quantserve.com/quant.js";
elem.async = true;
elem.type = "text/javascript";
var scpt = document.getElementsByTagName('script')[0];
scpt.parentNode.insertBefore(elem, scpt);
})();
/* ]]> */
</script>
</head>
<body>

<script  type='text/javascript' src='http://www.keenspot.com/ks_header.js'></script>
<noscript><div><a href="http://www.keenspot.com/"><img src="http://www.keenspot.com/images/headerbar-adblockers.gif" width="519" height="32"  alt="This site is supported by advertising Revenue" /></a></div></noscript>
<!-- Header End -->

    <div class="archive-cont">
			
			<div class="arch-cont" style="position:absolute;top:140px;width:950px;">
			    <div class="comic">
    			    <?php
					    include("imagegallery.php");
				    ?>
				</div>
			</div>
		<div class="advertisement ad-top arch-topad"><script type='text/javascript'>
<!--
//<![CDATA[
var cwfl="http://twokinds.keenspot.com/cwfl.htm";
// ]]>
-->
</script><script type="text/javascript" src="http://www.keenspot.com/adp.php?&amp;type=leaderboard&amp;cat=general&amp;js=1&amp;out=twokinds">
</script></div>
		<div class="arch-header">
		    <span style="color: #00FFFF;">Two</span><span style="color: #FF991E;">kinds</span> Archive
		</div>
        <div class="advertisement">
		<div class="arch-sidebar">
		    <div class="adv adv-arch">
<script type="text/javascript" src="http://www.keenspot.com/adp.php?&amp;type=w_sky&amp;cat=general&amp;js=1&amp;out=twokinds">
</script></div>
				    <div class="keenspot">
					<script src="http://www.keenspot.com/ks_newsbox.js" type="text/javascript"></script>
			</div>
		    </div>
	    </div>

				    
		<div style="clear: both;"></div>
		<div class="advertisement"><div class="adv2 adv2-archive">
<script type="text/javascript" src="http://www.keenspot.com/adp.php?&amp;type=m_rect&amp;cat=general&amp;js=1&amp;out=twokinds">
</script></div></div>
	</div>

<!-- Start Quantcast tag, part 2 -->
<script type="text/javascript">
/* <![CDATA[ */
_qevents.push({
qacct:"p-0bpH4thh8w_tE"
});
/* ]]> */
</script>

<noscript>
<div style="display:none;">
<img src="//pixel.quantserve.com/pixel/p-0bpH4thh8w_tE.gif" height="1" width="1" alt="Quantcast"/>
</div>
</noscript>
<!-- End Quantcast tag -->

<script type="text/javascript">
/* <![CDATA[ */
 __compete_code = 'af5c57769b6abfe853931c78981c7036';
 (function () {
     var s = document.createElement('script'),
         e = document.getElementsByTagName('script')[0],
         t = document.location.protocol.toLowerCase() === 'https:' ?
             'https://c.compete.com/bootstrap/' :
             'http://c.compete.com/bootstrap/';
         s.src = t + __compete_code + '/bootstrap.js';
         s.type = 'text/javascript';
         s.async = true;
         if (e) { e.parentNode.insertBefore(s, e); }
     }());
/* ]]> */
</script>
</body>
</html>
<?php

$content = ob_get_contents();
ob_end_clean();
file_put_contents($cachefilename,$content);
$lastmod = gmdate('D, d M Y H:i:s', filemtime($cachefilename)) . ' GMT';
$etag  = md5($cachefilename.'.'.$lastmod);

header("Last-Modified: ".$lastmod);
header("ETag: \"$etag\"");
echo $content;
ob_end_flush();

}

?>