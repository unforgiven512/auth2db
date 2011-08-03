<script type="text/javascript" src="base64.js"></script>
<?

include("security.php");


$q=$_GET["q"]; //string
$x=$_GET["x"]; //regex
$p0=$_GET["p0"]; //string
$p1=$_GET["p1"]; //string
$p2=$_GET["p2"]; //string
$p3=$_GET["p3"]; //string

// HEXA
//$encode = preg_replace("'(.)'e","dechex(ord('\\1'))",$txt); //encode
//$decode = preg_replace("'([\S,\d]{2})'e","chr(hexdec('\\1'))",$encode); //decode

// Decode HEXA
$q = preg_replace("'([\S,\d]{2})'e","chr(hexdec('\\1'))",$q);
$x = preg_replace("'([\S,\d]{2})'e","chr(hexdec('\\1'))",$x);

$p0 = preg_replace("'([\S,\d]{2})'e","chr(hexdec('\\1'))",$p0);
$p1 = preg_replace("'([\S,\d]{2})'e","chr(hexdec('\\1'))",$p1);
$p2 = preg_replace("'([\S,\d]{2})'e","chr(hexdec('\\1'))",$p2);
$p3 = preg_replace("'([\S,\d]{2})'e","chr(hexdec('\\1'))",$p3);


if ($x != "") {

    //echo $q;
    //echo "<br>";
    //echo $x;
    //echo "<br><br>";

    #echo "<br><b>string:</b> ".sec_cleanHTML($q)."<br>";
    #echo "<br><b>regex:</b> ".sec_cleanHTML($x)."<br>";
    echo "<b>regex:</b><span id='strRegex'>".sec_cleanHTML($x)."</span><br>";
    echo "<span id='strRegexHex' style='visibility: hidden;'>".preg_replace("'(.)'e","dechex(ord('\\1'))",$x)."</span>";

    if(preg_match("/".$x."/i", $q, $matches, PREG_OFFSET_CAPTURE)) {

    echo "<br><b>REGEX</b><br><br>";
    echo "p0: ".sec_cleanHTML($matches["p0"][0]);
    echo "<br>";
    echo "p1: ".sec_cleanHTML($matches["p1"][0]);
    echo "<br>";
    echo "p2: ".sec_cleanHTML($matches["p2"][0]);
    echo "<br>";
    echo "p3: ".sec_cleanHTML($matches["p3"][0]);

    } else {
    echo "<br><br>?";
    }

}


if ($p0 != "" OR $p1 != "" OR $p2 != "" OR $p3 != "") {


    $x = "";

    if ($p0 != "") {
	$x = "(?P<p0>".$p0.")";
    }

    if ($p1 != "") {
	if ($x != "") {
	    $x = $x.".+(?P<p1>".$p1.")";
	} else {
	    $x = "(?P<p1>".$p1.")";
	}
    }

    if ($p2 != "") {
	if ($x != "") {
	    $x = $x.".+(?P<p2>".$p2.")";
	} else {
	    $x = "(?P<p2>".$p2.")";
	}
    }

    if ($p3 != "") {
	if ($x != "") {
	    $x = $x.".+(?P<p3>".$p3.")";
	} else {
	    $x = "(?P<p3>".$p3.")";
	}
    }

    //$x = "(?<p0>".$p0.").+(?<p1>".$p1.").+(?<p2>".$p2.")+(?<p3>".$p3.")";


    echo "<br><b>REGEX</b><br><br>";
    #echo "<br><b>string:</b> ".sec_cleanHTML($q)."<br>";
    #echo "<br><b>regex:</b> ".sec_cleanHTML($x)."<br>";
    echo "<span id='strRegex' style='width: 100px;'>".sec_cleanHTML($x)."</span><br>";
    echo "<span id='strRegexHex' style='visibility: hidden;'>".preg_replace("'(.)'e","dechex(ord('\\1'))",$x)."</span>";

    if(preg_match("/".$x."/i", $q, $matches, PREG_OFFSET_CAPTURE)) {

    echo "<br><b>RESULT</b><br><br>";
    echo "p0: ".sec_cleanHTML($matches["p0"][0]);
    echo "<br>";
    echo "p1: ".sec_cleanHTML($matches["p1"][0]);
    echo "<br>";
    echo "p2: ".sec_cleanHTML($matches["p2"][0]);
    echo "<br>";
    echo "p3: ".sec_cleanHTML($matches["p3"][0]);

    } else {
    echo "<br><br>[ NO RESULT ]";
    }

}
?>