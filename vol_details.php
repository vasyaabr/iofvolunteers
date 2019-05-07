<?php //session_start(); 

    $connect = mysql_connect("localhost", "fenmen", "nermin"); 

    if(!$connect) 
  
 { die('Could not connect: ' . mysql_error());}
   
   $db_link= mysql_select_db("fenmen"); 
   if(!$db_link)
   {  echo "no database of that name!";}

$id=1;		//$_SESSION['id'];
$vid=$id;
?>



<html><head>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<link rel="stylesheet" href="/resources/demos/style.css">
<script>
$(function() {
$( "#sdate" ).datepicker({ dateFormat: "dd-mm-yy" });
});
</script>

<meta http-equiv="Content-Type" content="text/html; charset=windows-1254">
	
</head><body class="bodystyle" link="#376FA6" vlink="#376FA6">



<?php

/*
if(isset($_POST['edit'])){ $_SESSION['id']=$id; include('edit_vol.php'); } 
else if(isset($_POST['main'])) {$_SESSION['id']=$id; echo '<meta http-equiv="refresh" content="0" />';include('welcome.php');} 
else
{
*/ 
$warning='';
$result = mysql_query("SELECT * FROM users WHERE id='$id' ");
$row = mysql_fetch_array($result, MYSQL_ASSOC);  

$country = $row['country'];
$gender = $row['gender'];
$age = date('Y') - $row['birth']; if($age>100) $warning .= "<br>* You gave forgot to give your year of birth!"; 
$nickname = $row['nickname'];
$license = $row['license'];

?>

<table width="65%" align="center">
<tr>
<td colspan = "3">
<p align="center"><img border="0" src="images/top_banner1.jpg" ></p>
              <p  align="center"><b><font face="Verdana" color="#376FA6" size="3">Details for </font><font color="#BC0000" face="Verdana" size = "3"><?echo $nickname ?> </font></b></p><br><br>
</td></tr>
<tr>
<td valign="top" width="30%"> 
	<table><tr valign="top">
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Country: <? echo $country; ?></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Gender : <? if($gender=='M') echo "Male"; else echo "Female";?></font> </td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Age : <? echo $age; ?></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">International driving license: <? if($license=='1') echo "Available"; else echo " - ";?></font></td></tr></table>
<br>	
	 <table><tr><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Languages spoken</b></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">
<?
$result = mysql_query("SELECT * FROM langs WHERE vid='$vid' ");
$nolangs = mysql_num_rows($result); 
if($nolangs>0){
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
	$lang = $row['lang'];
	switch($lang)
					{
					case 'E': echo "* English (";break; 
					case 'F': echo "* French (";break; 
					case 'S': echo "* Spanish (";break; 
					case 'G': echo "* German (";break;
					case 'I': echo "* Italian (";break;
					case 'P': echo "* Portuguese (";break;
					case 'W': echo "* Scandinavian (";break;
					}
	$level = $row['level'];
	switch($level)
					{
					case '1': echo "Excellent)";break; 
					case '2': echo "OK)";break; 
					case '3': echo "Poor)";break; 
					}

?><br><?}}

$result = mysql_query("SELECT * FROM timing WHERE vid='$vid' ");
$nolangs = mysql_num_rows($result); 
if($nolangs>0){
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{

	$olangs = $row['olangs'];
	if($olangs !=''){if($l>0) echo ", "; echo "* ".$olangs;}

}}
?>
</font></td></tr></table>

<br>
	<table><tr><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b><br>Work Preferences</b></font></td></tr>
<?
$result = mysql_query("SELECT * FROM timing WHERE vid='$vid' ");
$nolangs = mysql_num_rows($result); 
if($nolangs>0){
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{

	$sdate = $row['sdate'];?>
	<tr><td><font color="#376FA6" face="Verdana" size = "2">Available from: <? if($sdate!='') echo $sdate; else echo "no date specified";?></font></td></tr>
<?	$duration = $row['duration'];?>
	<tr><td><font color="#376FA6" face="Verdana" size = "2">Can work for: <? if($duration!='') echo $duration." months"; else echo "no duration specified";?></font></td></tr>
<?}}?>

<tr><td><font color="#376FA6" face="Verdana" size = "2">Preferred destinations:<br> 
<?
$result = mysql_query("SELECT * FROM conts WHERE vid='$vid' ");
$nolangs = mysql_num_rows($result); 
if($nolangs>0){
$c=0;
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{ 
	
	$cont = $row['continent'];
	if($cont=='A') echo "Can work anywhere. ";
	else{$c++; if($c==1) echo "Would prefer to work in "; else echo ", ";
		switch($cont)
			{
			case 'N': echo "North America";break; 
			case 'S': echo "South America";break; 
			case 'E': echo "Europe";break; 
			case 'I': echo "Asia";break;
			case 'F': echo "Africa";break;
			case 'O': echo "Oceania";break;
			} 
	     }


}}?>

	</font></td></tr></table>
	
	
</td><td valign="top" width="30%"> 
      <table><tr><td><font color="#376FA6" face="Verdana" size = "2"><b>Disciplines of experience</b></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Experienced in<br> 
<?
$result = mysql_query("SELECT * FROM discs WHERE vid='$vid' ");
$nolangs = mysql_num_rows($result); 
if($nolangs>0){
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{  
	$dis = $row['dis'];
	switch($dis)
					{
					case 'F': echo "* Foot-O   ";?><br><?break; 
					case 'M': echo "* MTBO   ";?><br><?break; 
					case 'T': echo "* Trail-O   ";?><br><?break; 
					case 'S': echo "* Ski-O   ";?><br><?break;} 
}}?>

	</font></td></tr></table>
<br>
	<table><tr><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><br><b>O-Experience</b></font></td></tr>
<?
$result = mysql_query("SELECT * FROM timing WHERE vid='$vid' ");
$nolangs = mysql_num_rows($result); 
if($nolangs>0){
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{
	$start = $row['start'];
	$club = $row['club'];
	$loc = $row['loc'];
	$nchamp = $row['nchamp'];
	$icomp = $row['icomp'];
?>
	<tr><td><font color="#376FA6" face="Verdana" size = "2">Started orienteering in <? echo $start;?> </td</tr>
	<tr><td><font color="#376FA6" face="Verdana" size = "2">Present club: <? if($club=='') echo " - "; else echo $club;?>  </td><tr>
        <tr><td colspan="2"><font color="#376FA6" face="Verdana" size = "2">Experience as Competitor: </td></tr>
	<tr><td><font color="#376FA6" face="Verdana" size = "2">* <? switch($loc){ case '0': echo "no";break; case '1': echo "1 - 50"; break; case '2': echo "51 - 100"; break; case '3': echo "over 100";break;} ?> local events<br>*  
	<? switch($nchamp){ case '0': echo "no";break; case '1': echo "1 - 50"; break; case '2': echo "51 - 100"; break; case '3': echo "over 100";break;} ?> national championships <br>*  
	<? switch($icomp){ case '0': echo "no";break; case '1': echo "1 - 20"; break; case '2': echo "21 - 50"; break; case '3': echo "over 50";break;} ?> international competitions 


<?}}?>
	</font></td></tr></table>
<br>


	<table><tr> 

<?
$result = mysql_query("SELECT * FROM experience WHERE vid='$vid' ");
$nolangs = mysql_num_rows($result); 
if($nolangs>0){
while($row = mysql_fetch_array($result, MYSQL_ASSOC))
{

	$natc = $row['natc'];
	$intc = $row['intc'];
	$onduts = $row['onduts'];
	$oiduts = $row['oiduts'];

$exper = $row['exper'];
$training = $row['training'];
$expect = $row['expect'];


$mapper	 = $row['mapper'];
$sprint	 = $row['sprint'];
$forest	 = $row['forest'];


$coach	 = $row['coach'];
$nteam	 = $row['nteam'];
$clubs	 = $row['clubs'];

$si	 = $row['si'];
$emit	 = $row['emit'];
$gps	 = $row['gps'];
$itex	 = $row['itex'];

$clubev	 = $row['clubev'];
$localev = $row['localev'];
$natev	 = $row['natev'];
$hlev	 = $row['hlev'];
$evorg	 = $row['evorg'];

$documents = $row['documents'];$children = $row['children'];$oskills = $row['oskills'];

}} // experience cekme islemi bitti


	if($natc !=0||$intc !=0){?>
	<td><font color="#376FA6" face="Verdana" size = "2">Worked as<br>  
			<?}

	if($natc!=0){
	$result1 = mysql_query("SELECT * FROM duties WHERE vid='$vid' ");
	$nol = mysql_num_rows($result1); 
	if($nol>0){
	
	while($row1 = mysql_fetch_array($result1, MYSQL_ASSOC))
	{  
	$dut = $row1['duts'];
	if($dut[0]=='N'){
	
	switch($dut[1])
		{
		case 'E': echo "* Event Director <br>"; break; 
		case 'M': echo "* Mapper / Course Planner <br>";break; 
		case 'T': echo "* IT Director <br> ";break; 
		case 'J': echo "* Jury Member <br>  ";break; 
		case 'A': echo "* Event Advisor <br>";break;
		} 
		}
	}}

	if($onduts!=''){?> 
	<br> <? echo $onduts; ?><br><?}?>
	in <? switch($natc){ case '1': echo "1 - 10"; break; case '2': echo "11 - 30"; break; case '2': echo "over 30";break;} ?> national events <br>

	<?}	//natc
	
	if($intc!=0){
	$result1 = mysql_query("SELECT * FROM duties WHERE vid='$vid' ");
	$nols = mysql_num_rows($result1); 
	if($nols>0){
	
	while($row1 = mysql_fetch_array($result1, MYSQL_ASSOC))
	{  
	$dut = $row1['duts'];
	if($dut[0]=='I'){
	
	switch($dut[1])
		{
		case 'E': echo "Event Director"; break; 
		case 'M': echo "Mapper / Course Planner";break; 
		case 'T': echo "IT Director   ";break; 
		case 'J': echo "Jury Member   ";break; 
		case 'A': echo "Event Advisor";break;
		}
		} 
	}}

	if($oiduts!=''){?> 
	<br> <? echo $oiduts; }?>
	<br>in <? switch($intc){ case '1': echo "1 - 10"; break; case '2': echo "11 - 20"; break; case '2': echo "over 20";break;} ?> international events <br>

	<?}	//intc
	
	if($exper !=''){?><br><br>
		* Other experience:  
		<br> <? echo $exper; 
				
	}


	if($training !=''){?><br><br>
		* Training camps, seminars attended:  
		<br> <? echo $training; 
				
	}?>



</font></td></tr></table>
</td>
<td valign="top"> 
      <table><tr><td><font color="#376FA6" face="Verdana" size = "2"><b>O-Skills</b></font></td></tr>
	
	    
<?


	if($sprint =='1'||$forest=='1'){?>
		<tr><td><font color="#376FA6" face="Verdana" size = "2">* Mapping experience: <?
		if($sprint =='1'&& $forest=='1') echo "Both sprint and forest maps";
			else if($sprint =='1') echo "Sprint maps";
			else echo "Forest maps"; ?>
		<br> <? echo $mapper;?>
		<br> <? 

   	 //Get image data from database
  	  $resultm = mysql_query("SELECT mid FROM maps WHERE vid = '$vid'  ");
    	$num_rowsm=mysql_num_rows($resultm);
    	if($num_rowsm > 0){$m=0; ?><br>This volunteer has uploaded <? echo $num_rowsm ?> map samples. <br>These will be displayed on a new page...<br> <?
	while($rowm = mysql_fetch_array($resultm, MYSQL_ASSOC))
	{	$m++;
		$mim[$m] = $rowm['mid'];
		if($mim[$m]>0){
		$vview = "<a href='view_map.php?myNumber=$mim[$m]' target='_blank'>view map sample". $m."</a><br>"; 
		echo $vview;}
	}	//while
	}	//map samples exist
?></font></td></tr>
	<?}	//sprint forest 
	
	
	if($nteam =='1'||$clubs=='1'){?>
		<tr><td><font color="#376FA6" face="Verdana" size = "2"><br>* Coaching experience: <?
		if($nteam =='1'&& $clubs=='1') echo "Both clubs and national team";
			else if($clubs =='1') echo "Clubs";
			else echo "National Team"; ?>
		<br> <? echo $coach; ?>
		</font></td></tr>		
	<?} // team clubs

	if($si =='1'||$emit=='1'||$gps == '1'){?>
		<tr><td><font color="#376FA6" face="Verdana" size = "2"><br>* IT experience: <?
		if($si =='1') echo "* Timekeeping with SportIdent   <br>";
		if($emit =='1') echo "* Timekeeping with Emit   <br>";
		if($gps =='1') echo "* GPS Tracking  <br> ";?>
		<br> <? echo $itex; ?>
		</font></td></tr>		
	<?} //si vb

	if($clubev =='1'||$localev=='1'||$natev == '1'||$hlev == '1'){?>
		<tr><td><font color="#376FA6" face="Verdana" size = "2"><br>* Event organising / Event controlling:<br> <?
		if($clubev =='1') echo "* Club Events   ";
		if($localev =='1') echo "* Local Events   ";
		if($natev =='1') echo "* Events on National Scale   ";
		if($hlev =='1') echo "* High-Level Events   ";?>
		<br> <? echo $evorg; ?>
		</font></td></tr>		
	<?} 	// events

	if($documents !=''){?>
		<tr><td><font color="#376FA6" face="Verdana" size = "2"><br>* Educational Documents:  
		<br> <? echo $documents; ?>
		</font></td></tr>		
	<?}	//docs


	if($children !=''){?>
		<tr><td><font color="#376FA6" face="Verdana" size = "2"><br>* Teaching beginners & children:  
		<br> <? echo $children; ?>
		</font></td></tr>		
	<?}	//begs & child

	if($oskills !=''){?>
		<tr><td><font color="#376FA6" face="Verdana" size = "2"><br>* Other skills:  
		<br> <? echo $oskills; ?>
		</font></td></tr>		
	<?}	// other skills

	if($expect !=''){?><br><br><tr><td><font color="#376FA6" face="Verdana" size = "2"><br>
		<b>Expectations as a Volunteer </b> 
		<br> <? echo $expect; 
				
	}?>

</font></td></tr>	</table>

</td></tr>
<tr><td colspan="3"><p align="right"><input type=submit value="Contact this volunteer" name="contact"></p>
</td></tr>
</table> 





</body></html>