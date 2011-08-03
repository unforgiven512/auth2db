<?
//connect to the database

$conf="/etc/auth2db/auth2db.conf";
 
if ($file = @file( $conf )) {
       foreach ($file as $line) {
               $temp = explode("=", $line);
               
               switch ( trim($temp[0]) ){
               case "dbuser": $user = str_replace("\n", "", str_replace("'", "", str_replace('"', "", trim($temp[1]) ))); break;
               case "dbpass": $pass = str_replace("\n", "", str_replace("'", "", str_replace('"', "", trim($temp[1]) ))); break;	       
               case "dbname": $name = str_replace("\n", "", str_replace("'", "", str_replace('"', "", trim($temp[1]) ))); break;
               case "dbserver": $server = str_replace("\n", "", str_replace("'", "", str_replace('"', "", trim($temp[1]) ))); break;
               }
       }       
}
else 
       print "Configuration file " .$conf. " couldn't be read ";

if ($server) 
       $link = mysql_connect($server, $user, $pass) or die ("Error connecting to database.");
else 
       $link = mysql_connect("localhost", $user, $pass) or die ("Error connecting to database.");

mysql_select_db($name, $link) or die ("Couldn't select the database.");


?>
