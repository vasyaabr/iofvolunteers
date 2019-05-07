<?php  // session_start(); 

// $qid=$_SESSION['qid'];
$mid = $_GET['myNumber'];
/* echo $mid;*/

$connect = mysql_connect("localhost", "fenmen", "nermin"); 

    if(!$connect) 
  
 { die('Could not connect: ' . mysql_error());}
   
   $db_link= mysql_select_db("fenmen"); 
   if(!$db_link)
   {  echo "no database of that name!";} 


$resultt = mysql_query("SELECT map FROM maps WHERE mid = $mid  ");
$rowt = mysql_fetch_array($resultt, MYSQL_ASSOC);

	$imgData = $rowt['map'];
	header("Content-type: image/jpeg"); 
        print $imgData; 


?>
	