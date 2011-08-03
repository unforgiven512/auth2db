<?


$data = $_GET["data"];
$label = $_GET["label"];

$data = explode('*',$data);
$label = explode('*',$label);

/*
	# ------- The graph values in the form of associative array
	$values=array(
		"Jan" => 110,
		"Feb" => 130,
		"Mar" => 215,
		"Apr" => 81,
		"May" => 310,
		"Jun" => 110,
		"Jul" => 190,
		"Aug" => 175,
		"Sep" => 390,
		"Oct" => 286,
		"Nov" => 150,
		"Dec" => 198
	);

*/

//echo $label[1];

for ($i = 0; $i < count($data); $i++)
{
    //$values=array("test".$i => $data[$i]);
    $values[$label[$i]] = $data[$i];
    //$values["test".$i] = $data[$i];
    //$values[0][$i] = "test";
    //$values[1][$i] = $data[$i];
}

//echo count($values);
//echo "<br>";
//echo max($values[1]);
//exit;

	$img_width=450;
	$img_height=150; 
	$margins=20;

 
	# ---- Find the size of graph by substracting the size of borders
	$graph_width=$img_width - $margins * 2;
	$graph_height=$img_height - $margins * 2; 
	//$img=imagecreate($img_width,$img_height);
	$img=ImageCreateTrueColor($img_width,$img_height);

	if ($_GET["bar"] != "")
	    $bar_width=$_GET["bar"];
	else
	    $bar_width=14;
	$total_bars=count($values);
	$gap= ($graph_width- $total_bars * $bar_width ) / ($total_bars +1);

 
	# -------  Define Colors ----------------
	$bar_color=imagecolorallocate($img,250,140,0);
	$background_color=imagecolorallocate($img,255,255,255);
	$border_color=imagecolorallocate($img,17,17,17);
	$line_color=imagecolorallocate($img,225,225,225);
 
	# ------ Create the border around the graph ------

	imagefilledrectangle($img,1,1,$img_width-2,$img_height-2,$border_color);
	imagefilledrectangle($img,$margins,$margins,$img_width-1-$margins,$img_height-1-$margins,$background_color);

 
	# ------- Max value is required to adjust the scale	-------
	$max_value=max($values);
	//$max_value=max($values[1]);
	$ratio= $graph_height/$max_value;

 
	# -------- Create scale and draw horizontal lines  --------
	$horizontal_lines=10;
	$horizontal_gap=$graph_height/$horizontal_lines;

	for($i=1;$i<=$horizontal_lines;$i++){
		$y=$img_height - $margins - $horizontal_gap * $i ;
		imageline($img,$margins,$y,$img_width-$margins,$y,$line_color);
		$v=intval($horizontal_gap * $i /$ratio);
		imagestring($img,0,5,$y-5,$v,$bar_color);

	}
 
 
	# ----------- Draw the bars here ------
	for($i=0;$i< $total_bars; $i++){ 
		# ------ Extract key and value pair from the current pointer position
		list($key,$value)=each($values); 
		$x1= $margins + $gap + $i * ($gap+$bar_width) ;
		$x2= $x1 + $bar_width; 
		$y1=$margins +$graph_height- intval($value * $ratio) ;
		$y2=$img_height-$margins;
		imagestring($img,0,$x1+3,$y1-10,$value_,$bar_color);
		imagestring($img,0,$x1+3,$img_height-15,$key,$bar_color);		
		imagefilledrectangle($img,$x1,$y1,$x2,$y2,$bar_color);
	}
	//header("Content-type:image/png");
	//imagepng($img);


function OutputImage($img)
{
    header("Content-type:image/png");
    imagepng($img);
    //ImageJPEG($img,NULL,100);
}

OutputImage($img);
ImageDestroy($img);

?>
