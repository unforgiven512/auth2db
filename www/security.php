<?

# [Muestra los tags HTML sin ejecutarlos.] 
function sec_cleanHTML ($var) {
    return htmlentities($var);
    //return htmlspecialchars($str);
}

# [Quita todos los tags HTML y PHP de la variable.] 
function sec_cleanTAGS ($var) {
    return strip_tags($var);
}


# [Agrega escape de caracteres especiales SQL -> \' ]
function sec_addESC($var) {

    $var = mysql_real_escape_string($var);

    return $var;

}

# [Verifica characteres -> [a-z0-9] ]
function regex_standard($var) {

    $regex = "/(?i)(^[a-z0-9\-\_\.\+, \/]{1,30}$)|(^$)/";

    $referer = $_SERVER['HTTP_REFERER'];
    //echo $referer."<br>";
    if (preg_match($regex, $var) == 0) {
	header("Location: ".$referer."&error=1");
	exit;
    }

}

# [Verifica numbers -> [0-9] ]
function regex_numbers($var) {

    $regex = "/(?i)(^[0-9 ]{1,20}$)|(^$)/";

    $referer = $_SERVER['HTTP_REFERER'];

    if (preg_match($regex, $var) == 0) {
	header("Location: ".$referer."&error=1");
	exit;
    }

}

# [Verifica where -> [a-b0-9 \[\]\%] ]
function regex_where($var) {

    $regex = "/(?i)(^[a-z0-9 \[\]\(\)\%\=\.]{1,}$)/";

    $referer = $_SERVER['HTTP_REFERER'];
    //echo $var;
    if (preg_match($regex, $var) == 0) {
	header("Location: ".$referer."&error=1");
	exit;
    }

}

# [Verifica regex -> [a-b0-9 \[\]\%] ]
function regex_regex($var) {

    $regex = "/(?i)(^[a-z0-9 \[\]\(\)\%\=\|_+\.\<\>\?\:]{1,}$)|(^$)/";

    $referer = $_SERVER['HTTP_REFERER'];
    //echo $var;
    if (preg_match($regex, $var) == 0) {
	header("Location: ".$referer."&error=1");
	exit;
    }

}

# [Verifica email -> [a-b0-9\@\.\-\_] ]
function regex_email($var) {

    $regex = "/(?i)(^[a-z0-9\@\.\-\_,]{1,}$)|(^$)/";

    $referer = $_SERVER['HTTP_REFERER'];
    //echo $var;
    if (preg_match($regex, $var) == 0) {
	header("Location: ".$referer."&error=1");
	exit;
    }

}

# Muestra error de INPUT
function show_error() {

    if ($_GET["error"] == 1) {
	echo "<b>Bad Input...</b><br><br>";
    }
}

# Verifica que la tabla log_ exista
function table_exists($tabla) {

    $sql = "show tables like 'log_".$tabla."'";
    $result = mysql_query($sql);
    return mysql_num_rows($result);

}

# Verifica permiso asignado al ROLE
function rac_verify($rac) {

	if (in_array( $rac, $_SESSION["rac"] )) {
		return True;
	} else {
		return False;
	}

	}
?>
