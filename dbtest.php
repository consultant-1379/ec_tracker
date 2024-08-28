<?php
//mysql_connect('cifwk-oss.lmera.ericsson.se', 'ee_ectracker', 'zaiS0Sho') or die(mysql_error());
//echo "Connected to MySQL<br />";
//mysql_select_db("eniq_events_ectracker") or die(mysql_error());
//echo "Connected to Database";

$con = mysql_connect('selid1a011.lmera.ericsson.se', 'ectracker', 'UwQdtz4RPajK4F8X') or die(mysql_error());
echo "Connected to MySQL<br />";
echo $con."<br />";
$db = mysql_select_db("ectracker") or die(mysql_error());
echo "Connected to Database<br />";
echo $db."<br />";


$sql_ec_result = "SHOW TABLES;";
								
echo $sql_ec_result;
							
$result = mysql_query($sql_ec_result) or die(mysql_error());

echo $result."<br />";

while ($row = mysql_fetch_row($result)) {
    echo "Table: {$row[0]}\n";
}

?>
