<?php //session_start(); 

    $connect = mysql_connect("localhost", "fenmen", "nermin"); 

    if(!$connect) 
  
 { die('Could not connect: ' . mysql_error());}
   
   $db_link= mysql_select_db("fenmen"); 
   if(!$db_link)
   {  echo "no database of that name!";}

$id=1;			//$_SESSION['id'];
$vid=$id;
?>



<html><head>

 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">

<meta http-equiv="Content-Type" content="text/html; charset=windows-1254">
	
</head><body class="bodystyle" link="#BC0000" vlink="#BC0000">




<p align="center"><img border="0" src="images/top_banner1.jpg" ></p>
              <p  align="center"><b><font face="Verdana" color="#376FA6" size="3">Search results </font></b><br></font><font color="#BC0000" face="Verdana" size = "2">Click on Volunteer Nickname for more detail</font><br><br>

<table width="65%" align="center" border="1" bordercolor="#376FA6">
<tr>
<td><font color="#376FA6" face="Verdana" size = "2"><b>Nickname</b></font></td>
<td><font color="#376FA6" face="Verdana" size = "2"><b>Languages</b></font></td>
<td><font color="#376FA6" face="Verdana" size = "2"><b>When can start</b></font></td>
<td><font color="#376FA6" face="Verdana" size = "2"><b>Duration available</b></font></td>
<td><font color="#376FA6" face="Verdana" size = "2"><b>O-Experience</b></font></td>

</tr>
<tr>
<?

// nickname
$result = mysql_query("SELECT * FROM users WHERE id='$id' ");
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$nickname = $row['nickname']; 
?><td align = "center"><font color="#BC0000" face="Verdana" size = "2"><a href="vol_details.php" target="_blank"><b><? $_SESSION['id']=$id;echo $nickname; ?></b></a></font></td>
<?
// languages
?><td><font color="#376FA6" face="Verdana" size = "2">
<?
$result = mysql_query("SELECT * FROM langs WHERE vid='$vid' ");
$nolangs = mysql_num_rows($result); 
if($nolangs>0){$l=0;
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
	$lang = $row['lang'];if($l>0){echo ", ";}
	switch($lang)
					{
					case 'E': echo "English (";break; 
					case 'F': echo "French (";break; 
					case 'S': echo "Spanish (";break; 
					case 'G': echo "German (";break;
					case 'I': echo "Italian (";break;
					case 'P': echo "Portuguese (";break;
					case 'W': echo "Scandinavian (";break;
					}
	$level = $row['level'];
	switch($level)
					{
					case '1': echo "Excellent)";break; 
					case '2': echo "OK)";break; 
					case '3': echo "Poor)";break; 
					}


$l++;}}

$result = mysql_query("SELECT * FROM timing WHERE vid='$vid' ");
$nolangs = mysql_num_rows($result); 
if($nolangs>0){
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
	$olangs = $row['olangs'];
	if($olangs !=''){if($l>0) {echo ", ";} echo $olangs;}

}}







$result = mysql_query("SELECT * FROM timing WHERE vid='$vid' ");
$nolangs = mysql_num_rows($result); 
if($nolangs>0){
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{

	$sdate = $row['sdate'];
	$duration = $row['duration'];
	$start = $row['start'];

	$loc = $row['loc'];
	$nchamp = $row['nchamp'];
	$icomp = $row['icomp'];


?>
<td><font color="#376FA6" face="Verdana" size = "2"><? if($sdate!='') echo $sdate; else echo "no date specified";?></font></td>
<td><font color="#376FA6" face="Verdana" size = "2"><? if($duration!='') echo $duration." months"; else echo "no duration specified";?></font></td>
<td><font color="#376FA6" face="Verdana" size = "2">Started orienteering in <? echo $start;?> <br>
Competed in <? switch($loc){ case '0': echo "no";break; case '1': echo "1 - 50"; break; case '2': echo "51 - 100"; break; case '3': echo "over 100";break;} ?> local events, <? switch($nchamp){ case '0': echo "no";break; case '1': echo "1 - 50"; break; case '2': echo "51 - 100"; break; case '3': echo "over 100";break;} ?> national championships, <? switch($icomp){ case '0': echo "no";break; case '1': echo "1 - 20"; break; case '2': echo "21 - 50"; break; case '3': echo "over 50";break;} ?> international competitions. 


<?}}?>
	</font></td></tr>


</table>





</body></html>