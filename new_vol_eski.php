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
	$start = $_POST['start'];if($start==''){$error=1;?><p align = 'center'><font color="#B30000" face="Verdana" size = "4">Please go back and enter the year you started orienteering! If it is a long time ago and you don't remember, and approximate year would be OK :)</font></p><?}
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

	$coach = $_POST['coach'];
	$nteam = $_POST['nteam'];
	$clubs = $_POST['clubs'];
	
	$si = $_POST['si'];
	$emit = $_POST['emit'];
	$gps = $_POST['gps'];
	$itex = $_POST['itex'];

	$clubev = $_POST['clubev'];
	$localev = $_POST['localev'];
	$natev = $_POST['natev'];
	$hlev = $_POST['hlev'];
	$evorg = $_POST['evorg'];
	
	$documents = $_POST['documents'];
	$children = $_POST['children'];

	$oskills = $_POST['oskills'];

	$onduts = $_POST["onduts"];
	$oiduts = $_POST["oiduts"];
	$expect = $_POST["expect"];
	$exper = $_POST["exper"];
	$training = $_POST["training"];

	$natc = $_POST['natc'];
	$intc = $_POST['intc'];}

if(	$mapper =='' && 
	$sprint ==0 && 
	$forest ==0 && 

	$coach =='' && 
	$nteam ==0 && 
	$clubs ==0 && 
	
	$si ==0 && 
	$emit ==0 && 
	$gps ==0 && 
	$itex =='' && 

	$clubev ==0 && 
	$localev ==0 && 
	$natev ==0 && 
	$hlev ==0 && 
	$evorg =='' && 
	
	$documents =='' && 
	$children =='' && 

	$oskills =='' && 

	$onduts =='' && 
	$oiduts =='' && 

	$exper =='' && 
	$training =='' && 

	$natc ==0 && 
	$intc ==0){$error=1;?><p align = 'center'><font color="#B30000" face="Verdana" size = "4">You didn't specify ANY orienteering experience! What are your skills? Please go back and give us some idea about your experience! </font></p><?}

if($expect == ''){$error=1;?><p align = 'center'><font color="#B30000" face="Verdana" size = "4">Please go back and write a few words about your expectations as a volunteer!</font></p><?}





if($error==0){
	   $insert_command = "INSERT INTO experience(natc, intc, onduts, oiduts, expect, exper, training, mapper, sprint, forest, coach, nteam, clubs, si, emit, gps, itex, clubev, localev, natev, hlev, evorg, documents, children, oskills, vid, help) 
		VALUES('$natc', '$intc','$onduts', '$oiduts', '$expect', '$exper','$mapper','$training', '$sprint', '$forest', '$coach', '$nteam', '$clubs', '$si', '$emit', '$gps', '$itex', '$clubev', '$localev', '$natev', '$hlev', '$evorg', '$documents', '$children', '$oskills', '$vid', '$help')";
   	$sent = mysql_query($insert_command, $connect);
	if(!$sent) {die("Database query failed: ". mysql_error());}}


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
 if(	($mapper !='' ||$sprint !=''|| $forest !='')&&$mp==0){$error=1;?><p align = 'center'><font color="#B30000" face="Verdana" size = "4">You stated you are experienced as a mapper but you have not uploaded any of your maps! Please go back and upload at least one of your best maps!</font></p><?}
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
}
}
 /*'<meta http-equiv="refresh" content="0" />'; */
	            
}// newvol butonu
else
{
 
$result = mysql_query("SELECT * FROM users WHERE id='$id' ");
$row = mysql_fetch_array($result, MYSQL_ASSOC);  
$name = $row['name'];
$email = $row['email'];
$country = $row['country'];
$birth = $row['birth'];
?>


    

<form method="POST" action="new_vol.php" enctype="multipart/form-data">
 
<p align="center"><img border="0" src="images/top_banner2.jpg" ></p>

              <p  align="center"><b><font face="Verdana" color="#376FA6" size="3">

<? if($birth==0){echo 'Volunteer Registration Form</font></b><br><font color="#B30000" face="Verdana" size = "2">Please note that you must be 18+ to register as a volunteer!</font>';} else 
{echo 'You may edit your details below </font></b><br><font color="#B30000" face="Verdana" size = "2">Your new details will be saved when you submit the form </font>';}
?>
</p>


    
<table width="90%" align="center">
<tr>
<td valign="top" width="25%"> <table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Contact Information</b></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Name</font></td><td><font face="Verdana" size = "2">: <? echo $name; ?></font></td></tr>	     
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Country</font></td><td><font face="Verdana" size = "2">: <? echo $country; ?></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">E-mail</font></td><td><font face="Verdana" size = "2">: <? echo $email; ?></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Phone </font><font color="#B30000" face="Verdana" size = "1">(optional)</font></td><td>: <input type="text" name="phone" size="15"></td></tr>

	    <tr><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b><br>Personal Information</b></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Gender </font></td><td>: <select size="1" name="gender"><option selected value=''>--- select ---</option><option value="M">Male</option><option value="F">Female</option></select></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Year of birth </font><font color="#B30000" face="Verdana" size = "1">required</font></td><td>: <input type="text" name="birth" size="4" value="<? echo 'yyyy';?>"></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Nickname </font><font color="#B30000" face="Verdana" size = "1">optional</font></td><td>: <input type="text" name="nickname" size="15"></td></tr>
	    <tr><td colspan="2"><font color="#B30000" face="Verdana" size = "1">if left blank, your first name will be assumed as your nickname</font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">International driving license? </font></td><td>: <select size="1" name="license"><option selected value=''>--- select ---</option><option value="1">Yes</option><option value="0">No</option></select></td></tr>
	</table>
<br>
      <table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Disciplines of experience</b></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">
        <input type="checkbox" name="dis[]" value="F" /> Foot-O  &nbsp; &nbsp; &nbsp; &nbsp;</td><td><font color="#376FA6" face="Verdana" size = "2">
        <input type="checkbox" name="dis[]" value="M" /> MTBO </td></tr><tr><td><font color="#376FA6" face="Verdana" size = "2">
        <input type="checkbox" name="dis[]" value="S" /> Ski-O  &nbsp; &nbsp; &nbsp; &nbsp;</td><td><font color="#376FA6" face="Verdana" size = "2">
        <input type="checkbox" name="dis[]" value="T" /> Trail-O 

	</font></td></tr>
 	</table>

<br>
      <table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>O-Experience</b></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Year you started orienteering<font color="#B30000" face="Verdana" size = "1">*</font> </td><td>:<font color="#376FA6" face="Verdana" size = "2"><input type="text" name="start" size="4" value="<? echo 'yyyy';?>"></td</tr>
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
        <td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="E" /> English   </td><td><font color="#376FA6" face="Verdana" size = "1"><input type="radio" name="elevel" value="1"> Excellent &nbsp; &nbsp;<input type="radio" name="elevel" value="2"> OK  &nbsp; &nbsp;<input type="radio" name="elevel" value="3"> Poor </td></tr><tr>
        <td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="F" /> French </td><td><font color="#376FA6" face="Verdana" size = "1"><input type="radio" name="flevel" value="1"> Excellent &nbsp; &nbsp;<input type="radio" name="flevel" value="2"> OK  &nbsp; &nbsp;<input type="radio" name="flevel" value="3"> Poor </td></tr><tr>
        <td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="S" /> Spanish </td><td><font color="#376FA6" face="Verdana" size = "1"><input type="radio" name="slevel" value="1"> Excellent &nbsp; &nbsp;<input type="radio" name="slevel" value="2"> OK  &nbsp; &nbsp;<input type="radio" name="slevel" value="3"> Poor </td></tr><tr>
        <td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="G" /> German </td><td><font color="#376FA6" face="Verdana" size = "1"><input type="radio" name="glevel" value="1"> Excellent &nbsp; &nbsp;<input type="radio" name="glevel" value="2"> OK  &nbsp; &nbsp;<input type="radio" name="glevel" value="3"> Poor </td></tr><tr>
        <td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="I" /> Italian </td><td><font color="#376FA6" face="Verdana" size = "1"><input type="radio" name="ilevel" value="1"> Excellent &nbsp; &nbsp;<input type="radio" name="ilevel" value="2"> OK  &nbsp; &nbsp;<input type="radio" name="ilevel" value="3"> Poor </td></tr><tr>
        <td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="P" /> Portuguese </td><td><font color="#376FA6" face="Verdana" size = "1"><input type="radio" name="plevel" value="1"> Excellent &nbsp; &nbsp;<input type="radio" name="plevel" value="2"> OK  &nbsp; &nbsp;<input type="radio" name="plevel" value="3"> Poor </td></tr><tr>
	<td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="W" /> "Scandinavian" </td><td><font color="#376FA6" face="Verdana" size = "1"><input type="radio" name="wlevel" value="1"> Excellent &nbsp; &nbsp;<input type="radio" name="wlevel" value="2"> OK  &nbsp; &nbsp;<input type="radio" name="wlevel" value="3"> Poor </td></tr><tr>
	<td colspan="2"><font color="#376FA6" face="Verdana" size = "2">Other languages? <br>State each language and level, separated by commas below...<br> <input type="text" name="olangs" size="30">
	</font></td></tr>
 	</table>
<br>
     <table><tr valign="top"><td><font color="#376FA6" face="Verdana" size = "2"><b>Where to work?</b></font></td><td> &nbsp; &nbsp; &nbsp; &nbsp;</td><td><font color="#376FA6" face="Verdana" size = "2"><b>Timing</b></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "1">Do you have a preferred destination? <br>If not, just tick "Anywhere"</font></td><td> &nbsp; &nbsp; &nbsp; &nbsp;</td><td><font color="#376FA6" face="Verdana" size = "2">When can you start? </td></tr> </td></tr>
	
        <tr><td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="continent[]" value="A" /> Anywhere </td><td> &nbsp; &nbsp; &nbsp; &nbsp;</td><td><font color="#376FA6" face="Verdana" size = "2"><input type="text" size="10" name="sdate" id="sdate"></td></tr>
        <tr><td><br> <font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="continent[]" value="N" /> North America </td><td> &nbsp; &nbsp; &nbsp; &nbsp;</td><td> </td></tr>
        <tr><td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="continent[]" value="S" /> South America </td><td> </td> &nbsp; &nbsp; &nbsp; &nbsp;</td><td><font color="#376FA6" face="Verdana" size = "2">For how long can you work? </td></tr>
        <tr><td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="continent[]" value="E" /> Europe </td><td> &nbsp; &nbsp; &nbsp; &nbsp;</td><td><input type="text" size="3" name="duration" id="duration"><font color="#376FA6" face="Verdana" size = "2"> months</td></tr>
        <tr><td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="continent[]" value="I" /> Asia </td><td> &nbsp; &nbsp; &nbsp; &nbsp;</td><td> </td></tr>
        <tr><td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="continent[]" value="F" /> Africa </td><td> &nbsp; &nbsp; &nbsp; &nbsp;</td><td> </td></tr>
	<tr><td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="continent[]" value="O" /> Oceania </td><td> &nbsp; &nbsp; &nbsp; &nbsp;</td><td> </td></tr>
	</font>
 	</table>

</td>
<td valign="top" width="31%">
     
     <table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Skills</b></font><font color="#B30000" face="Verdana" size = "1"> &nbsp;(Please tick all relevant to you and give details if necessary...)</font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">* Mapping <br><font color="#376FA6" face="Verdana" size = "1"><input type="checkbox" name="sprint" value="1"> Sprint &nbsp; &nbsp;<input type="checkbox" name="forest" value="1"> Forest </td><td><font color="#376FA6" face="Verdana" size = "1">Brief outline of your experience as a mapper <br>
		<textarea rows="2" cols="30" name="mapper" id="mapper"></textarea></td></tr><tr><td>
        <font color="#376FA6" face="Verdana" size = "2">* Coaching<br><font color="#376FA6" face="Verdana" size = "1"><input type="checkbox" name="nteam" value="1"> National Team<br><input type="checkbox" name="clubs" value="1"> Clubs </td><td><font color="#376FA6" face="Verdana" size = "1">Brief outline of your experience in coaching <br>
		<textarea rows="2" cols="30" name="coach" id="coach"></textarea>  </td></tr><tr><td>
        <font color="#376FA6" face="Verdana" size = "2">* IT & time-keeping <br><font color="#376FA6" face="Verdana" size = "1"><input type="checkbox" name="si" value="1"> SportIdent &nbsp; &nbsp;<input type="checkbox" name="emit" value="1"> Emit <br><input type="checkbox" name="othertime" value="1"> Other Timekeeping <br><input type="checkbox" name="gps" value="1"> GPS Tracking</td><td><font color="#376FA6" face="Verdana" size = "1">Brief details of your IT skills & experience <br>
		<textarea rows="2" cols="30" name="itex" id="itex"></textarea> </td></tr><tr><td>
        <font color="#376FA6" face="Verdana" size = "2">* Event Organising <br><font color="#376FA6" face="Verdana" size = "1"><input type="checkbox" name="clubev" value="1"> Club &nbsp; &nbsp;<input type="checkbox" name="localev" value="1"> Local &nbsp; &nbsp;<br><input type="checkbox" name="natev" value="1"> National &nbsp; &nbsp;<input type="checkbox" name="hlev" value="1"> High-Level </td><td><font color="#376FA6" face="Verdana" size = "1">Brief outline of your experience as organiser <br>
		<textarea rows="2" cols="30" name="evorg" id="evorg"></textarea></td></tr><tr><td>
        <font color="#376FA6" face="Verdana" size = "2">* Teaching experience 
<br><font color="#376FA6" face="Verdana" size = "1"><input type="checkbox" name="begchild" value="1"> Beginners & children <br><input type="checkbox" name="tmap" value="1"> Teaching how to map  <br><input type="checkbox" name="tcoach" value="1"> Teach coaching <br><input type="checkbox" name="tit" value="1"> Teach IT & Timekeeping <br><input type="checkbox" name="tevo" value="1"> Teach Event Organising






</td><td><font color="#376FA6" face="Verdana" size = "1">Brief outline of your experience in teaching
		<textarea rows="2" cols="30" name="documents" id="documents"></textarea></td></tr><tr><td> 
	<font color="#376FA6" face="Verdana" size = "2">* Other skills? Please explain... </td><td><textarea rows="2" cols="30" name="oskills" id="oskills"></textarea>
	</font></td></tr>
 	</table>
<br>	<table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>O-Work Experience</b></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Local / National Events   
		<select size="1" name="natc"><option selected value=''>- select -</option>
					<option value="0">none</option><option value="1">1 - 10</option>
					<option value="2">11 - 30</option>
					<option value="3">over 30</option></select><br>
		<br>Duties:<br></font><font color="#376FA6" face="Verdana" size = "1">
		
	<input type="checkbox" name="dut[]" value="NE" /> Event Director <br />
        <input type="checkbox" name="dut[]" value="NM" /> Mapper / Course Planner <br />
        <input type="checkbox" name="dut[]" value="NT" /> IT Director <br />
	<input type="checkbox" name="dut[]" value="NA" /> Event Advisor <br />
	<input type="checkbox" name="dut[]" value="NJ" /> Jury Member <br />
	Other duties? State below...<br> <input type="text" name="onduts" size="20">
</font>
        </td>
		<td><font color="#376FA6" face="Verdana" size = "2">International Events &nbsp;  
		<select size="1" name="intc"><option selected value=''>- select -</option>
					<option value="0">none</option><option value="1">1 - 10</option>
					<option value="2">11 - 20</option>
					<option value="3">over 20</option></select><br>
		<br>Duties:<br></font><font color="#376FA6" face="Verdana" size = "1">
	<input type="checkbox" name="idut[]" value="IE" /> Event Director <br />
        <input type="checkbox" name="idut[]" value="IM" /> Mapper / Course Planner <br />
        <input type="checkbox" name="idut[]" value="IT" /> IT Director <br />
	<input type="checkbox" name="idut[]" value="IA" /> Event Advisor <br />
	<input type="checkbox" name="idut[]" value="IJ" /> Jury Member <br />
	Other duties? State below...<br> <input type="text" name="oiduts" size="20">

		</font></td></tr>
 		</table>

</td>
<td valign="top" width="17%"> <table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Additional Information</b></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">
Skilled in mapping? <br>Upload your "best" maps here...<br>(At most 3 maps in PDF format)<br><br>
		<input name="image1" type="file"><br><br>
		<input name="image2" type="file"><br><br>
		<input name="image3" type="file"><br><br>

		Explain how you can help as a volunteer <br>
		<textarea rows="4" cols="30" name="help" id="help"></textarea><br><br>
		Expectations as a volunteer <br>
		<textarea rows="4" cols="30" name="expect" id="expect"></textarea><br><br>
		Experience abroad? When? Where? What?<br>
		<textarea rows="4" cols="30" name="exper" id="exper"></textarea><br><br>
		Seminars, Training Camps attended...<br>
		<textarea rows="4" cols="30" name="training" id="training"></textarea><br><br>
		
		
		
        </font></td></tr>
 	</table>
</td></tr>
<tr><td> </td><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Disclaimer:</b> I have filled in my details above as accurately as possible. By submitting this form, I state that I am a volunteer in developing orienteering. I understand that the IOF cannot be held responsible for my being or not being recruited as a volunteer. I also understand that should I choose to accept any offer requesting my assistance, the IOF cannot be held responsible for the terms under which I will work as a volunteer.
<br><br><input type="checkbox" name="ok" >  I have read and understood the above.</font> <font color="#B30000" face="Verdana" size = "1">*</font></td><td> </td></tr>	
<tr><td colspan="4" align="right"><input type="submit" name="newvol" value="Submit my details">

</td></tr></table> 
    </form>
<?


}	// newvol butonuna basilmamis	?>
    



</body></html>