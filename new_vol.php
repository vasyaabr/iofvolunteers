<?php session_start(); 
$id=$_SESSION['id'];



$vid=$id;
    $connect = mysql_connect("localhost", "fenmen", "nermin"); 

    if(!$connect) 
  
 { die('Could not connect: ' . mysql_error());}
   
   $db_link= mysql_select_db("fenmen"); 
   if(!$db_link)
   {  echo "no database of that name!";}


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





if(isset($_POST['newvol'])) 
{$error=0;
$birth = $_POST["birth"];
	if($birth==''){$error=2;?><p align = 'center'><font color="#B30000" face="Verdana" size = "4">Year of birth is a required field! Please go back and enter your year of birth...</font></p><?}

$age = date('Y') - $birth; if($age<18){$error=2;?><p align = 'center'><font color="#B30000" face="Verdana" size = "4">Sorry, you need to be 18 or over to be part of this platform! Your details will not be saved...</font></p><?}
if($error<2){
	$ok = $_POST["ok"]; if($ok==''){$error=1;?><p align = 'center'><font color="#B30000" face="Verdana" size = "4">Please go back and tick the disclaimer...</font></p><?}

	$vid=$id;
	$nickname = $_POST["nickname"];
		if($nickname=='') {$result = mysql_query("SELECT * FROM users WHERE id='$id' "); $row = mysql_fetch_array($result, MYSQL_ASSOC);  $name = $row['name']; if(substr_count($name," ")>0) $nickname = strtok($name, " ");else $nickname = trim($name); }
	$gender = $_POST["gender"];

	
	$license = $_POST["license"];
	$phone = $_POST["phone"];
if($error==0){
	$update_command = "UPDATE users set phone='$phone', nickname='$nickname', gender = '$gender', birth = '$birth', license = '$license' WHERE id='$id' ";
	$sent = mysql_query($update_command, $connect);
	if(!$sent) {die("Database query failed: ". mysql_error());}
}	
	//Disciplines of experience

	$disArray = $_POST["dis"];
	if(empty($disArray) || !isset($disArray[0])){$error=1;?><p align = 'center'><font color="#B30000" face="Verdana" size = "4">Please go back and specify your discipline of expertise!</font></p><?}
	else{	foreach ($disArray as $d => $dvalue)
		{if($dvalue!='') 
			{$insert_command = "INSERT INTO discs(dis, vid) VALUES('$dvalue', '$vid')";
   			 $sent = mysql_query($insert_command, $connect);
	    			if(!$sent) {die("Database query failed: ". mysql_error());}}}	
	     }
	// O-experience
	$start = $_POST['start'];if($start=='yyyy'){$error=1;?><p align = 'center'><font color="#B30000" face="Verdana" size = "4">Please go back and enter the year you started orienteering! If it is a long time ago and you don't remember, and approximate year would be OK :)</font></p><?}
	$club = $_POST['club'];
	$loc = $_POST['loc'];
	$nchamp = $_POST['nchamp'];
	$icomp = $_POST['icomp'];
		if($loc==''&&$nchamp==''&&$icomp==''){$error=1;?><p align = 'center'><font color="#B30000" face="Verdana" size = "4">You haven't stated your experience as a competitor in orienteering! You need to be experienced in order to volunteer in the project! Please go back and enter number of competitions you took part in...</font></p><?}
	// languages
	$langArray = $_POST["lang"];
	if(empty($langArray) || !isset($langArray[0])){$error=1;?><p align = 'center'><font color="#B30000" face="Verdana" size = "4">Are you sure you don't speak any of the listed languages? Go back and specify the languages again if necessary...</font></p><?}
	else{	foreach ($langArray as $l => $lvalue)
		{if($lvalue!='') 
			{switch($lvalue)
				{
					case 'E': $level =  $_POST["elevel"];break; 
					case 'F': $level =  $_POST["flevel"];break; 
					case 'G': $level =  $_POST["glevel"];break; 
					case 'S': $level =  $_POST["slevel"];break; 
					case 'P': $level =  $_POST["plevel"];break; 
					case 'W': $level =  $_POST["wlevel"];break; 
					case 'I': $level =  $_POST["ilevel"];break;
				}
				if($level==''){$error=1;?><p align = 'center'><font color="#B30000" face="Verdana" size = "4">Please go back and tick the <b>level</b> of the languages you speak!</font></p><?}
				else{if($error==0){
			 		$insert_command = "INSERT INTO langs(lang, level, vid) VALUES('$lvalue', $level,'$vid')";
   			 		$sent = mysql_query($insert_command, $connect);
			 		if(!$sent) {die("Database query failed: ". mysql_error());}}
				    }
			}
		}
	     }
	$olangs = $_POST['olangs']; // other languages...

	// timing
	$sdate = $_POST['sdate'];
	$duration = $_POST['duration'];
if($error==0){
		$insert_command = "INSERT INTO timing(sdate, duration, olangs, start, club, loc, nchamp, icomp, vid) 
		VALUES('$sdate', '$duration', '$olangs', '$start', '$club', '$loc', '$nchamp', '$icomp', '$vid' )";
   		$sent = mysql_query($insert_command, $connect);
		if(!$sent) {die("Database query failed: ". mysql_error());}}	


	// where...

	$contArray = $_POST["continent"];
if($error==0){
	if(empty($contArray) || !isset($contArray[0])){$error=1;?><p align = 'center'><font color="#B30000" face="Verdana" size = "4">You didn't specify where you would like to work. If no preferences, please go back and tick "Anywhere"!</font></p><?}
	else{	foreach ($contArray as $c => $cvalue)
		{if($cvalue!='') 
			{$insert_command = "INSERT INTO conts(continent, vid) VALUES('$cvalue', '$vid')";
   			 $sent = mysql_query($insert_command, $connect);
			if(!$sent) {die("Database query failed: ". mysql_error());}}}
	    }}
if($error==0){ 
	// skills and experience
	$mapper = $_POST['mapper'];
	$sprint = $_POST['sprint'];
	$forest = $_POST['forest'];
	$mtbo = $_POST['mtbo'];

	$coach = $_POST['coach'];
	$nteam = $_POST['nteam'];
	$clubs = $_POST['clubs'];
	
	$si = $_POST['si'];
	$emit = $_POST['emit'];
	$othertime = $_POST['othertime'];
	$gps = $_POST['gps'];
	$itex = $_POST['itex'];

	$clubev = $_POST['clubev'];
	$localev = $_POST['localev'];
	$natev = $_POST['natev'];
	$hlev = $_POST['hlev'];
	$evorg = $_POST['evorg'];
	
	$teachex = $_POST['teachex'];
	

	$oskills = $_POST['oskills'];

	$onduts = $_POST["onduts"];
	$oiduts = $_POST["oiduts"];
	$expect = $_POST["expect"];
	$exper = $_POST["exper"];
	$help = $_POST["help"];

	$training = $_POST["training"];

	$natc = $_POST['natc'];
	$intc = $_POST['intc'];
	$teachArray = $_POST["teach"]; if(!empty($teachArray) && isset($teachArray[0])) $teachbos==2; else $teachbos=1; 

if(	$mapper =='' && 
	$sprint ==0 && 
	$forest ==0 && 
	$mtbo ==0 &&

	$coach =='' && 
	$nteam ==0 && 
	$clubs ==0 && 
	
	$si ==0 && 
	$emit ==0 &&
	$othertime == 0 && 
	$gps ==0 && 
	$itex =='' && 

	$clubev ==0 && 
	$localev ==0 && 
	$natev ==0 && 
	$hlev ==0 && 
	$evorg =='' && 
	
	$teachex =='' && 
	

	$oskills =='' && 

	$onduts =='' && 
	$oiduts =='' && 

	$exper =='' && 
	$training =='' && 

	$natc ==0 && 
	$intc ==0 &&
	$teachbos == 1


	){$error=1;?><p align = 'center'><font color="#B30000" face="Verdana" size = "4">You didn't specify ANY orienteering skills! Please go back and give us some idea about your skills! </font></p><?}

if($help == ''){$error=1;?><p align = 'center'><font color="#B30000" face="Verdana" size = "4">Please go back and explain briefly how you can help as a volunteer!</font></p><?}


}


if($error==0){
	   $insert_command = "INSERT INTO experience(natc, intc, sprint, forest,  mtbo, nteam, clubs, si, emit, othertime, gps, clubev, localev, natev, hlev, vid ) 
		VALUES('$natc', '$intc', '$sprint', '$forest', '$mtbo', '$nteam', '$clubs', '$si', '$emit', '$othertime', '$gps', '$clubev', '$localev', '$natev', '$hlev', '$vid')";
   	$sent = mysql_query($insert_command, $connect);
	if(!$sent) {die("Database query failed: ". mysql_error());}

	   $insert_command = "INSERT INTO textareas(onduts, oiduts, expect, exper, training, mapper, coach, itex, evorg, teachex, oskills, vid, help) 
		VALUES('$onduts', '$oiduts', '$expect', '$exper','$training', '$mapper', '$coach', '$itex', '$evorg', '$teachex', '$oskills', '$vid', '$help')";
   	$sent = mysql_query($insert_command, $connect);
	if(!$sent) {die("Database query failed: ". mysql_error());}

	}


	// duties
	$dutArray = $_POST["dut"];
	if(!empty($dutArray) && isset($dutArray[0])){ 
		foreach ($dutArray as $d => $dvalue)
		{if($dvalue!='') 
if($error==0){	
			{$insert_command = "INSERT INTO duties(duts, vid) VALUES('$dvalue', '$vid')";
   			 $sent = mysql_query($insert_command, $connect);
				if(!$sent) {die("Database query failed: ". mysql_error());}}}}
	}     
	$idutArray = $_POST["idut"];
	if(!empty($idutArray) && isset($idutArray[0])){ 
		foreach ($idutArray as $n => $nvalue)
		{if($nvalue!='') 
if($error==0){	
			{$insert_command = "INSERT INTO duties(duts, vid) VALUES('$nvalue', '$vid')";
   			 $sent = mysql_query($insert_command, $connect);
				if(!$sent) {die("Database query failed: ". mysql_error());}}}}
	 }  

//	$teachArray = $_POST["teach"];
	if(!empty($teachArray) && isset($teachArray[0])){ 
		foreach ($teachArray as $n => $nvalue)
		{if($nvalue!='') 

if($error==0){	
			{$insert_command = "INSERT INTO teaching(teach, vid) VALUES('$nvalue', '$vid')";
   			 $sent = mysql_query($insert_command, $connect);
				if(!$sent) {die("Database query failed: ". mysql_error());}}}}
	 }   

	//map upload
if($error==0){$mp=0;

	 
   		    if (isset($_FILES['image1']) && $_FILES['image1']['size'] > 0) { 
        		// Temporary file name stored on the server
        		$tmpName  = $_FILES['image1']['tmp_name'];  
        		// Read the file 
        		$fp      = fopen($tmpName, 'r');
        		$data = fread($fp, filesize($tmpName));
        		$data = addslashes($data);
        		fclose($fp);
        		$result = mysql_query("INSERT INTO maps (map, vid)VALUES ( '$data', '$vid')", $connect);
			        		
			if(!$result){die("Database query failed: ". mysql_error());}
       		    $mp++;}
			
   		    if (isset($_FILES['image2']) && $_FILES['image2']['size'] > 0) { 
        		// Temporary file name stored on the server
        		$tmpName  = $_FILES['image2']['tmp_name'];  
        		// Read the file 
        		$fp      = fopen($tmpName, 'r');
        		$data = fread($fp, filesize($tmpName));
        		$data = addslashes($data);
        		fclose($fp);
        		$result = mysql_query("INSERT INTO maps (map, vid)VALUES ( '$data', '$vid')", $connect);
			        		
			if(!$result){die("Database query failed: ". mysql_error());}
       		    $mp++;}
   		    if (isset($_FILES['image3']) && $_FILES['image3']['size'] > 0) { 
        		// Temporary file name stored on the server
        		$tmpName  = $_FILES['image3']['tmp_name'];  
        		// Read the file 
        		$fp      = fopen($tmpName, 'r');
        		$data = fread($fp, filesize($tmpName));
        		$data = addslashes($data);
        		fclose($fp);
        		$result = mysql_query("INSERT INTO maps (map, vid)VALUES ( '$data', '$vid')", $connect);
			        		
			if(!$result){die("Database query failed: ". mysql_error());}
       		    $mp++;}
 if(	($mapper !='' ||$sprint !=''|| $forest !=''|| $mtbo !='')&&$mp==0){$error=1;?><p align = 'center'><font color="#B30000" face="Verdana" size = "4">You stated you are experienced as a mapper but you have not uploaded any of your maps! Please go back and upload at least one of your best maps!</font></p><?}
}
if($error==0){ $_SESSION['id']=$id; include('preview_vol.php'); }
else{
$dcomn = "delete from conts where vid='$vid' ";$dlev = mysql_query($dcomn, $connect); if(!$dlev) {die("Database query failed: ". mysql_error());} 
$dcomn = "delete from discs where vid='$vid' ";$dlev = mysql_query($dcomn, $connect); if(!$dlev) {die("Database query failed: ". mysql_error());} 
$dcomn = "delete from duties where vid='$vid' ";$dlev = mysql_query($dcomn, $connect); if(!$dlev) {die("Database query failed: ". mysql_error());} 
$dcomn = "delete from experience where vid='$vid' ";$dlev = mysql_query($dcomn, $connect); if(!$dlev) {die("Database query failed: ". mysql_error());} 
$dcomn = "delete from langs where vid='$vid' ";$dlev = mysql_query($dcomn, $connect); if(!$dlev) {die("Database query failed: ". mysql_error());} 
$dcomn = "delete from maps where vid='$vid' ";$dlev = mysql_query($dcomn, $connect); if(!$dlev) {die("Database query failed: ". mysql_error());} 
$dcomn = "delete from timing where vid='$vid' ";$dlev = mysql_query($dcomn, $connect); if(!$dlev) {die("Database query failed: ". mysql_error());} 
$dcomn = "delete from teaching where vid='$vid' ";$dlev = mysql_query($dcomn, $connect); if(!$dlev) {die("Database query failed: ". mysql_error());} 
}
}
 /*'<meta http-equiv="refresh" content="0" />'; */
	            
}// newvol butonu
else
{
?>


    

<form method="POST" action="new_vol.php" enctype="multipart/form-data">
 
<p align="center"><img border="0" src="images/top_banner2.jpg" ></p>

              <p  align="center"><b><font face="Verdana" color="#376FA6" size="3">

<? 
$result = mysql_query("SELECT * FROM users WHERE id='$id' ");
$row = mysql_fetch_array($result, MYSQL_ASSOC);  
$name = $row['name'];
$email = $row['email'];
$country = $row['country'];
$birth = $row['birth'];
if($birth>0)
{
	$phone=$row['phone']; $nickname=$row['nickname']; $gender = $row['gender']; $license = $row['license'];

	$result = mysql_query("SELECT * FROM experience WHERE vid='$vid' ");
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	$natc=$row['natc']; $intc=$row['intc']; $onduts=$row['onduts']; $oiduts=$row['oiduts']; $expect=$row['expect']; $exper=$row['exper']; $training=$row['training']; $mapper=$row['mapper']; $sprint=$row['sprint']; 
	$forest=$row['forest']; 
	$mtbo = $row['mtbo'];$coach=$row['coach']; $nteam=$row['nteam']; $clubs=$row['clubs']; $si=$row['si']; $emit=$row['emit']; $gps=$row['gps']; $itex=$row['itex']; $clubev=$row['clubev']; $localev=$row['localev']; 
	$natev=$row['natev']; $hlev=$row['hlev'];$evorg=$row['evorg']; $teachex=$row['teachex'];  $oskills=$row['oskills']; $help=$row['help'];


	$result = mysql_query("SELECT * FROM discs WHERE vid='$vid' ");
	$j=0;
	while( $row = mysql_fetch_array($result, MYSQL_ASSOC))
	{$j++; $dis[$j]=$row['dis']; }

	$result = mysql_query("SELECT * FROM langs WHERE vid='$vid' ");
	$i=0;
	while( $row = mysql_fetch_array($result, MYSQL_ASSOC))
	{$i++; $lang[$i]=$row['lang']; $level[$i]=$row['level'];}	

	$result = mysql_query("SELECT * FROM timing WHERE vid='$vid' ");
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	$sdate=$row['sdate']; $duration=$row['duration']; $olangs=$row['olangs']; $start=$row['start']; $club=$row['club']; $loc=$row['loc']; $nchamp=$row['nchamp']; $icomp=$row['icomp'];

	$result = mysql_query("SELECT * FROM conts WHERE vid='$vid' ");
	$k=0;
	while( $row = mysql_fetch_array($result, MYSQL_ASSOC))
	{$k++; $continent[$k]=$row['continent'];}	

	$result = mysql_query("SELECT * FROM duties WHERE vid='$vid' ");
	$m=0;
	while( $row = mysql_fetch_array($result, MYSQL_ASSOC))
	{$m++; $duts[$m]=$row['duts'];}	

	$result = mysql_query("SELECT * FROM teaching WHERE vid='$vid' ");
	$n=0;
	while( $row = mysql_fetch_array($result, MYSQL_ASSOC))
	{$n++; $teach[$n]=$row['teach'];}	

}




if($birth==0){echo 'Volunteer Registration Form</font></b><br><font color="#B30000" face="Verdana" size = "2">Please note that you must be 18+ to register as a volunteer!</font>';} else 
{echo 'You may edit your details below </font></b><br><font color="#B30000" face="Verdana" size = "2">Your updated details will be saved when you submit the form </font>';}
?>
</p>


    
<table width="90%" align="center">
<tr>
<td valign="top" width="25%"> <table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Contact Information</b></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Name</font></td><td><font face="Verdana" size = "2">: <input type="text" name="name"  size="15" value="<? echo $name; ?>"></font></td></tr>	     
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Country</font></td><td><font face="Verdana" size = "2">: <input type="text" name="country" size="15" value="<? echo $country; ?>"></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">E-mail</font></td><td><font face="Verdana" size = "2">: <input type="text" name="email" size="15" value="<? echo $email; ?>"></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Phone </font><font color="#B30000" face="Verdana" size = "1">(optional)</font></td><td>: <input type="text" name="phone" value='<? echo $phone; ?>' size="15"></td></tr>

	    <tr><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b><br>Personal Information</b></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Gender </font></td><td>: <select size="1" name="gender"><option selected value='<? echo $gender; ?>'>--- select ---</option><option value="M">Male</option><option value="F">Female</option></select></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Year of birth </font><font color="#B30000" face="Verdana" size = "1"> required</font></td><td>: <input type="text" name="birth" size="4" value="<? if($birth>0) echo $birth; else echo 'yyyy';?>"></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Nickname </font><font color="#B30000" face="Verdana" size = "1">optional</font></td><td>: <input type="text" name="nickname" size="15" value='<? echo $nickname; ?>'></td></tr>
	    <tr><td colspan="2"><font color="#B30000" face="Verdana" size = "1">if left blank, your first name will be assumed as your nickname</font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">International driving license? </font></td><td>: <select size="1" name="license"><option selected value='<? echo $license; ?>'>--- select ---</option><option value="1">Yes</option><option value="0">No</option></select></td></tr>
	</table>
<br>
      <table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Disciplines of experience</b></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">


<?	$fvar=0; $mvar=0; $svar=0; $tvar=0; for($x=1;$x<=$j;$x++){if($dis[$x]=="F") $fvar=1;if($dis[$x]=="M") $mvar=1; if($dis[$x]=="S") $svar=1; if($dis[$x]=="T") $tvar=1;} ?>

        <input type="checkbox" name="dis[]" value="F" <? if($fvar>0) {?> checked <?}?>/> Foot-O  &nbsp; &nbsp; &nbsp; &nbsp;</td><td><font color="#376FA6" face="Verdana" size = "2">
        <input type="checkbox" name="dis[]" value="M" <? if($mvar>0) {?> checked <?}?>/> MTBO </td></tr><tr><td><font color="#376FA6" face="Verdana" size = "2">
        <input type="checkbox" name="dis[]" value="S" <? if($svar>0) {?> checked <?}?>/> Ski-O  &nbsp; &nbsp; &nbsp; &nbsp;</td><td><font color="#376FA6" face="Verdana" size = "2">
        <input type="checkbox" name="dis[]" value="T" <? if($tvar>0) {?> checked <?}?>/> Trail-O 

	</font></td></tr>
 	</table>

<br>
      <table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>O-Experience</b></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Year you started orienteering</font> </td><td>: <font color="#376FA6" face="Verdana" size = "2"><input type="text" name="start" size="4" value="<? echo 'yyyy';?>"> <font color="#B30000" face="Verdana" size = "1">(required) </td</tr>
<tr><td><font color="#376FA6" face="Verdana" size = "2">Your present club (if any) </td><td>: <font color="#376FA6" face="Verdana" size = "2"><input type="text" name="club" size="20" ></td</tr>
        <tr><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><u>Experience as Competitor</u> </td></tr>
	<tr><td><font color="#376FA6" face="Verdana" size = "2">Local events </td><td><font color="#376FA6" face="Verdana" size = "2">: <select size="1" name="loc"><option selected value=''>--- select ---</option>
					<option value="0">none</option><option value="1">1 - 50</option>
					<option value="2">51 - 100</option>
					<option value="3">over 100</option></select></td></tr>
        <tr><td><font color="#376FA6" face="Verdana" size = "2">National Championships </td><td><font color="#376FA6" face="Verdana" size = "2">: <select size="1" name="nchamp"><option selected value=''>--- select ---</option>
					<option value="0">none</option><option value="1">1 - 50</option>
					<option value="2">51 - 100</option>
					<option value="3">over 100</option></select></td></tr>
        <tr><td><font color="#376FA6" face="Verdana" size = "2">International Competitions <td><font color="#376FA6" face="Verdana" size = "2">: <select size="1" name="icomp"><option selected value=''>--- select ---</option>
					<option value="0">none</option><option value="1">1 - 20</option>
					<option value="2">21 - 50</option>
					<option value="3">over 50</option></select></td></tr> 


	
 	</table>
</td>

<td valign="top" width="27%"> <table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Languages spoken</b></font><font color="#B30000" face="Verdana" size = "1"> (required, even if only listed in "other")</font></td></tr>
	    <tr>


<?	$evar=0; $fvar=0; $svar=0; $gvar=0; $ivar=0; $pvar=0; $wvar=0; 
	for($x=1;$x<=$i;$x++){if($lang[$x]=="E") {$evar=1;$el=$level[$x];} if($lang[$x]=="F") {$fvar=1; $fl=$level[$x];} if($dis[$x]=="S") {$svar=1;$sl=$level[$x];} if($dis[$x]=="G") {$gvar=1; $gl=$level[$x];}
				if($dis[$x]=="I") {$ivar=1; $il=$level[$x];} if($lang[$x]=="P") {$pvar=1; $pl=$level[$x];} if($lang[$x]=="W") {$wvar=1;$wl=$level[$x];}}						?>

        <td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="E" <? if($evar>0) {?> checked <?}?> /> English   </td><td><font color="#376FA6" face="Verdana" size = "1"><input type="radio" name="elevel" value="1" <? if($el==1) {?> checked <?}?> > Excellent &nbsp; &nbsp;<input type="radio" name="elevel" value="2" <? if($el==2) {?> checked <?}?> > OK  &nbsp; &nbsp;<input type="radio" name="elevel" value="3" <? if($el==3) {?> checked <?}?> > Poor </td></tr><tr>
        <td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="F" <? if($fvar>0) {?> checked <?}?> /> French </td><td><font color="#376FA6" face="Verdana" size = "1"><input type="radio" name="flevel" value="1" <? if($fl==1) {?> checked <?}?> > Excellent &nbsp; &nbsp;<input type="radio" name="flevel" value="2" <? if($fl==2) {?> checked <?}?> > OK  &nbsp; &nbsp;<input type="radio" name="flevel" value="3" <? if($fl==3) {?> checked <?}?>> Poor </td></tr><tr>
        <td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="S" <? if($svar>0) {?> checked <?}?> /> Spanish </td><td><font color="#376FA6" face="Verdana" size = "1"><input type="radio" name="slevel" value="1" <? if($sl==1) {?> checked <?}?>> Excellent &nbsp; &nbsp;<input type="radio" name="slevel" value="2" <? if($sl==2) {?> checked <?}?>> OK  &nbsp; &nbsp;<input type="radio" name="slevel" value="3" <? if($sl==3) {?> checked <?}?>> Poor </td></tr><tr>
        <td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="G" <? if($gvar>0) {?> checked <?}?> /> German </td><td><font color="#376FA6" face="Verdana" size = "1"><input type="radio" name="glevel" value="1" <? if($gl==1) {?> checked <?}?>> Excellent &nbsp; &nbsp;<input type="radio" name="glevel" value="2" <? if($gl==2) {?> checked <?}?>> OK  &nbsp; &nbsp;<input type="radio" name="glevel" value="3" <? if($gl==3) {?> checked <?}?>> Poor </td></tr><tr>
        <td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="I" <? if($ivar>0) {?> checked <?}?> /> Italian </td><td><font color="#376FA6" face="Verdana" size = "1"><input type="radio" name="ilevel" value="1" <? if($il==1) {?> checked <?}?>> Excellent &nbsp; &nbsp;<input type="radio" name="ilevel" value="2" <? if($il==2) {?> checked <?}?>> OK  &nbsp; &nbsp;<input type="radio" name="ilevel" value="3" <? if($il==3) {?> checked <?}?>> Poor </td></tr><tr>
        <td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="P" <? if($pvar>0) {?> checked <?}?> /> Portuguese </td><td><font color="#376FA6" face="Verdana" size = "1"><input type="radio" name="plevel" value="1" <? if($pl==1) {?> checked <?}?>> Excellent &nbsp; &nbsp;<input type="radio" name="plevel" value="2" <? if($pl==2) {?> checked <?}?>> OK  &nbsp; &nbsp;<input type="radio" name="plevel" value="3" <? if($pl==3) {?> checked <?}?>> Poor </td></tr><tr>
	<td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="W" <? if($wvar>0) {?> checked <?}?> /> "Scandinavian" </td><td><font color="#376FA6" face="Verdana" size = "1"><input type="radio" name="wlevel" value="1" <? if($wl==1) {?> checked <?}?>> Excellent &nbsp; &nbsp;<input type="radio" name="wlevel" value="2" <? if($wl==2) {?> checked <?}?>> OK  &nbsp; &nbsp;<input type="radio" name="wlevel" value="3" <? if($wl==3) {?> checked <?}?>> Poor </td></tr><tr>
	<td colspan="2"><font color="#376FA6" face="Verdana" size = "2">Other languages? <br>State each language and level, separated by commas below...<br> <input type="text" name="olangs" value='<? echo $olangs; ?>' size="30">
	</font></td></tr>
 	</table>
<br>
     <table><tr valign="top"><td><font color="#376FA6" face="Verdana" size = "2"><b>Where to work?</b></font></td><td> &nbsp; &nbsp; &nbsp; &nbsp;</td><td><font color="#376FA6" face="Verdana" size = "2"><b>Timing</b></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "1">Do you have a preferred destination? <br>If not, just tick "Anywhere"</font></td><td> &nbsp; &nbsp; &nbsp; &nbsp;</td><td><font color="#376FA6" face="Verdana" size = "2">When can you start? </td></tr> </td></tr>

<?	$avar=0; $nvar=0; $svar=0; $evar=0; $ivar=0;$fvar=0;$ovar=0; for($x=1;$x<=$k;$x++){if($continent[$x]=="A") $avar=1;if($continent[$x]=="N") $nvar=1; if($continent[$x]=="S") $svar=1; if($continent[$x]=="E") $evar=1;if($continent[$x]=="I") $ivar=1;if($continent[$x]=="F") $fvar=1; if($continent[$x]=="O") $ovar=1;}	?> 
	
        <tr><td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="continent[]" value="A" <? if($avar>0) {?> checked <?}?> /> Anywhere </td><td> &nbsp; &nbsp; &nbsp; &nbsp;</td><td><font color="#376FA6" face="Verdana" size = "2"><input type="text" size="10" name="sdate" <? if($sdate!=''){?> value='<? echo $sdate; ?>' <?} else {?> id="sdate" <?}?> ></td></tr>


        <tr><td><br> <font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="continent[]" value="N" <? if($nvar>0) {?> checked <?}?>/> North America </td><td> &nbsp; &nbsp; &nbsp; &nbsp;</td><td> </td></tr>
        <tr><td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="continent[]" value="S" <? if($svar>0) {?> checked <?}?>/> South America </td><td> </td> &nbsp; &nbsp; &nbsp; &nbsp;</td><td><font color="#376FA6" face="Verdana" size = "2">For how long can you work? </td></tr>
        <tr><td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="continent[]" value="E" <? if($evar>0) {?> checked <?}?>/> Europe </td><td> &nbsp; &nbsp; &nbsp; &nbsp;</td><td><input type="text" size="3" name="duration" id="duration" value='<? echo $duration; ?>' ><font color="#376FA6" face="Verdana" size = "2"> months</td></tr>
        <tr><td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="continent[]" value="I" <? if($ivar>0) {?> checked <?}?> /> Asia </td><td> &nbsp; &nbsp; &nbsp; &nbsp;</td><td> </td></tr>
        <tr><td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="continent[]" value="F" <? if($fvar>0) {?> checked <?}?> /> Africa </td><td> &nbsp; &nbsp; &nbsp; &nbsp;</td><td> </td></tr>
	<tr><td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="continent[]" value="O" <? if($ovar>0) {?> checked <?}?> /> Oceania </td><td> &nbsp; &nbsp; &nbsp; &nbsp;</td><td> </td></tr>
	</font>
 	</table>

</td>
<td valign="top" width="31%">
     
     <table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Skills</b></font><font color="#B30000" face="Verdana" size = "1"> &nbsp;(Please tick all relevant to you. Details are <b>required</b> if skill is ticked)</font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">* Mapping <br><font color="#B30000" face="Verdana" size = "1">Notice that you will be required to upload map samples! </font><br><font color="#376FA6" face="Verdana" size = "1"><input type="checkbox" name="sprint" value="1"> Sprint <br><input type="checkbox" name="forest" value="1"> Forest <br><input type="checkbox" name="mtbo" value="1"> MTBO</td><td><font color="#376FA6" face="Verdana" size = "1">Brief outline of your experience as a mapper <br>
		<textarea rows="2" cols="30" name="mapper" id="mapper" value='<? echo $mapper; ?>'></textarea></td></tr><tr><td>
        <font color="#376FA6" face="Verdana" size = "2">* Coaching<br><font color="#376FA6" face="Verdana" size = "1"><input type="checkbox" name="nteam" value="1"> National Team<br><input type="checkbox" name="clubs" value="1"> Clubs </td><td><font color="#376FA6" face="Verdana" size = "1">Brief outline of your experience in coaching <br>
		<textarea rows="2" cols="30" name="coach" id="coach" value='<? echo $coach; ?>'></textarea>  </td></tr><tr><td>
        <font color="#376FA6" face="Verdana" size = "2">* IT & time-keeping <br><font color="#376FA6" face="Verdana" size = "1"><input type="checkbox" name="si" value="1"> SportIdent &nbsp; &nbsp;<input type="checkbox" name="emit" value="1"> Emit <br><input type="checkbox" name="othertime" value="1"> Other Timekeeping <br><input type="checkbox" name="gps" value="1"> GPS Tracking</td><td><font color="#376FA6" face="Verdana" size = "1">Brief details of your IT skills & experience <br>
		<textarea rows="2" cols="30" name="itex" id="itex" value='<? echo $itex; ?>'></textarea> </td></tr><tr><td>
        <font color="#376FA6" face="Verdana" size = "2">* Event Organising <br><font color="#376FA6" face="Verdana" size = "1"><input type="checkbox" name="clubev" value="1"> Club &nbsp; &nbsp;<input type="checkbox" name="localev" value="1"> Local &nbsp; &nbsp;<br><input type="checkbox" name="natev" value="1"> National &nbsp; &nbsp;<input type="checkbox" name="hlev" value="1"> High-Level </td><td><font color="#376FA6" face="Verdana" size = "1">Brief outline of your experience as organiser <br>
		<textarea rows="2" cols="30" name="evorg" id="evorg" value='<? echo $evorg; ?>'></textarea></td></tr><tr><td>
        <font color="#376FA6" face="Verdana" size = "2">* Teaching experience

<?	$cvar=0; $mvar=0; $ovar=0; $ivar=0; $evar=0; for($x=1;$x<=$n;$x++){if($teach[$x]=="C") $cvar=1;if($teach[$x]=="M") $mvar=1; if($teach[$x]=="O") $ovar=1; if($teach[$x]=="I") $ivar=1;if($teach[$x]=="E") $evar=1;}	?> 
	<br><font color="#376FA6" face="Verdana" size = "1"><input type="checkbox" name="teach[]" value="C" <? if($cvar>0) {?> checked <?}?> > Beginners & children <br><input type="checkbox" name="teach[]" value="M" <? if($mvar>0) {?> checked <?}?>> Teach how to map  <br><input type="checkbox" name="teach[]" value="O" <? if($ovar>0) {?> checked <?}?>> Teach coaching <br><input type="checkbox" name="teach[]" value="I" <? if($ivar>0) {?> checked <?}?>> Teach IT & Timekeeping <br><input type="checkbox" name="teach[]" value="E" <? if($evar>0) {?> checked <?}?> > Teach Event Organising






</td><td><font color="#376FA6" face="Verdana" size = "1">Brief outline of your experience in teaching
		<textarea rows="2" cols="30" name="teachex" id="teachex" value='<? echo $teachex; ?>'></textarea></td></tr><tr><td> 
	<font color="#376FA6" face="Verdana" size = "2">* Other skills? Please explain... </td><td><textarea rows="2" cols="30" name="oskills" id="oskills" value='<? echo $oskills; ?>' ></textarea>
	</font></td></tr>
 	</table>
<br>	<table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>O-Work Experience</b></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Local / National Events   
		<select size="1" name="natc"><option selected value=''>- select -</option>
					<option value="0">none</option><option value="1">1 - 10</option>
					<option value="2">11 - 30</option>
					<option value="3">over 30</option></select><br>
		<br>Duties:<br></font><font color="#376FA6" face="Verdana" size = "1">

<?	$evar=0; $mvar=0; $tvar=0; $avar=0; $jvar=0; for($x=1;$x<=$m;$x++){if($dut[$x]=="NE") $evar=1;if($dut[$x]=="NM") $mvar=1; if($dut[$x]=="NT") $tvar=1; if($dut[$x]=="NA") $avar=1;if($dut[$x]=="NJ") $jvar=1;}	?>
		
	<input type="checkbox" name="dut[]" value="NE" <? if($evar>0) {?> checked <?}?> /> Event Director <br />
        <input type="checkbox" name="dut[]" value="NM" <? if($mvar>0) {?> checked <?}?> /> Mapper / Course Planner <br />
        <input type="checkbox" name="dut[]" value="NT" <? if($tvar>0) {?> checked <?}?> /> IT Director <br />
	<input type="checkbox" name="dut[]" value="NA" <? if($avar>0) {?> checked <?}?> /> Event Advisor <br />
	<input type="checkbox" name="dut[]" value="NJ" <? if($jvar>0) {?> checked <?}?> /> Jury Member <br />
	Other duties? State below...<br> <input type="text" name="onduts" value='<? echo $onduts; ?>' size="20">
</font>
        </td>
		<td><font color="#376FA6" face="Verdana" size = "2">International Events &nbsp;  
		<select size="1" name="intc"><option selected value=''>- select -</option>
					<option value="0">none</option><option value="1">1 - 10</option>
					<option value="2">11 - 20</option>
					<option value="3">over 20</option></select><br>
		<br>Duties:<br></font><font color="#376FA6" face="Verdana" size = "1">

<?	$evar=0; $mvar=0; $tvar=0; $avar=0; $jvar=0; for($x=1;$x<=$m;$x++){if($dut[$x]=="IE") $evar=1;if($dut[$x]=="IM") $mvar=1; if($dut[$x]=="IT") $tvar=1; if($dut[$x]=="IA") $avar=1;if($dut[$x]=="IJ") $jvar=1;}	?>

	<input type="checkbox" name="idut[]" value="IE" <? if($evar>0) {?> checked <?}?> /> Event Director <br />
        <input type="checkbox" name="idut[]" value="IM" <? if($mvar>0) {?> checked <?}?> /> Mapper / Course Planner <br />
        <input type="checkbox" name="idut[]" value="IT" <? if($tvar>0) {?> checked <?}?> /> IT Director <br />
	<input type="checkbox" name="idut[]" value="IA" <? if($avar>0) {?> checked <?}?> /> Event Advisor <br />
	<input type="checkbox" name="idut[]" value="IJ" <? if($jvar>0) {?> checked <?}?> /> Jury Member <br />
	Other duties? State below...<br> <input type="text" name="oiduts" value='<? echo $oiduts; ?>' size="20">

		</font></td></tr>
 		</table>

</td>
<td valign="top" width="17%"> <table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Additional Information</b></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">
<? if($birth>0){?> You may choose one or more different files to upload. In this case, the previous maps you uploaded will be replaced by these ones. <?} else { ?>Skilled in mapping? <br>Upload your "best" maps here...<?}?><br><font color="#B30000" face="Verdana" size = "1">Required for mappers!</font><br>(At most 3 maps in PDF format)<br><br>
		<input name="image1" type="file"><br><br>
		<input name="image2" type="file"><br><br>
		<input name="image3" type="file"><br><br>

		Explain how you can help as a volunteer <br><font color="#B30000" face="Verdana" size = "1">(required) <br></font>
		<textarea rows="4" cols="30" name="help" id="help" value='<? echo $help; ?>' ></textarea><br><br>
		Expectations as a volunteer <br>
		<textarea rows="4" cols="30" name="expect" id="expect" value='<? echo $expect; ?>'></textarea><br><br>
		Experience abroad? When? Where? What?<br>
		<textarea rows="4" cols="30" name="exper" id="exper" value='<? echo $exper; ?>'></textarea><br><br>
		Seminars, Training Camps attended...<br>
		<textarea rows="4" cols="30" name="training" id="training" value='<? echo $training; ?>'></textarea><br><br>
		
		
		
        </font></td></tr>
 	</table>
</td></tr>
<tr><td> </td><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Disclaimer:</b> I have filled in my details above as accurately as possible. By submitting this form, I state that I am a volunteer in developing orienteering. I understand that the IOF cannot be held responsible for my being or not being recruited as a volunteer. I also understand that should I choose to accept any offer requesting my assistance, the IOF cannot be held responsible for the terms under which I will work as a volunteer.
<br><br><input type="checkbox" name="ok" >  I have read and understood the above.</font> <font color="#B30000" face="Verdana" size = "1"> Please tick the disclaimer...</font></td><td> </td></tr>	
<tr><td colspan="4" align="right"><input type="submit" name="newvol" value="Submit my details">

</td></tr></table> 
    </form>
<?


}	// newvol butonuna basilmamis	?>
    



</body></html>