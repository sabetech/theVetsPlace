<?php
$fp = fopen('newClients.csv','r') or die("can't open file");
//$randomNum = mktime();
//$key = substr($randomNum,6,4);

$link = mysql_connect("localhost","root","sabe");
mysql_select_db("thevetsplace",$link);


while($csv_line = fgetcsv($fp,4096)) {
	//for ($i = 0, $j = count($csv_line); $i < $j; $i++) {
	$p_key = uniqid(substr($csv_line[0],0,2));
	//$p_key = "ilog".$key;	
	$queryStr = "INSERT INTO clients (client_id,clientName,folderNo) VALUES ('$p_key','$csv_line[0]','$csv_line[1]')";
	mysql_query($queryStr);	
	sleep(0.2);
	echo $p_key." ".$csv_line[0]." ".$csv_line[1]."<br \>";
	//}
}
echo "LIKE A BOSS";
fclose($fp) or die("can't close file");
?>