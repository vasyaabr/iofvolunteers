<?php /* session_start(); 
$id=$_SESSION['id'];



$vid=$id;*/
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





if(isset($_POST['newreq'])) 
{/*$error=0;

	$ok = $_POST["ok"]; if($ok==''){$error=1;?><p align = 'center'><font color="#B30000" face="Verdana" size = "4">Please go back and tick the disclaimer...</font></p><?}

	$vid=$id;
	$nickname = $_POST["nickname"];


	$birth = $_POST["birth"];if($birth==''){$error=1;?><p align = 'center'><font color="#B30000" face="Verdana" size = "4">Year of birth is a required field! Please go back and enter your year of birth...</font></p><?}
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
	   $insert_command = "INSERT INTO experience(natc, intc, onduts, oiduts, expect, exper, training, mapper, sprint, forest, coach, nteam, clubs, si, emit, gps, itex, clubev, localev, natev, hlev, evorg, documents, children, oskills, vid) 
		VALUES('$natc', '$intc','$onduts', '$oiduts', '$expect', '$exper','$mapper','$training', '$sprint', '$forest', '$coach', '$nteam', '$clubs', '$si', '$emit', '$gps', '$itex', '$clubev', '$localev', '$natev', '$hlev', '$evorg', '$documents', '$children', '$oskills', '$vid')";
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
if($error==0){ $_SESSION['id']=$id; include('preview_req.php'); }
else{
$dcomn = "delete from conts where vid='$vid' ";$dlev = mysql_query($dcomn, $connect); if(!$dlev) {die("Database query failed: ". mysql_error());} 
$dcomn = "delete from discs where vid='$vid' ";$dlev = mysql_query($dcomn, $connect); if(!$dlev) {die("Database query failed: ". mysql_error());} 
$dcomn = "delete from duties where vid='$vid' ";$dlev = mysql_query($dcomn, $connect); if(!$dlev) {die("Database query failed: ". mysql_error());} 
$dcomn = "delete from experience where vid='$vid' ";$dlev = mysql_query($dcomn, $connect); if(!$dlev) {die("Database query failed: ". mysql_error());} 
$dcomn = "delete from langs where vid='$vid' ";$dlev = mysql_query($dcomn, $connect); if(!$dlev) {die("Database query failed: ". mysql_error());} 
$dcomn = "delete from maps where vid='$vid' ";$dlev = mysql_query($dcomn, $connect); if(!$dlev) {die("Database query failed: ". mysql_error());} 
$dcomn = "delete from timing where vid='$vid' ";$dlev = mysql_query($dcomn, $connect); if(!$dlev) {die("Database query failed: ". mysql_error());} 
}

'<meta http-equiv="refresh" content="0" />'; */
	            
}// newvol butonu
else
{
 
$result = mysql_query("SELECT * FROM users WHERE id='$id' ");
$row = mysql_fetch_array($result, MYSQL_ASSOC);  
$name = $row['name'];
$email = $row['email'];
$country = $row['country'];
?>


    

<form method="POST" action="new_request.php" enctype="multipart/form-data">
 
<p align="center"><img border="0" src="images/top_banner1.jpg" ></p>
              <p  align="center"><b><font face="Verdana" color="#376FA6" size="3">Volunteer Request Form</font></b></p>


    
<table width="90%" align="center">
<tr>
<td valign="top" width="30%"> 
	<table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Details of the Organisation</b></font><font color="#B30000" face="Verdana" size = "1">*</font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Name of the organisation</font></td><td><font color="#376FA6" face="Verdana" size = "2">: <input type="text" name="nickname" size="20"></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Status</font></td><td><font color="#376FA6" face="Verdana" size = "2">: <select size="1" name="status"><option selected value=''>--- select ---</option><option value="F">Federation</option>
		<option value="C">Club</option><option value="I">Informal Group</option></select></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Web page (if exists) </font></td><td><font face="Verdana" size = "2">: <input type="text" name="webpage" size="20"></font> </td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Region</font></td><td><font color="#376FA6" face="Verdana" size = "2">: <select size="1" name="status"><option selected value=''>--- select ---</option>
		<option value="N" /> North America </option><option value="S" /> South America </option><option value="E" /> Europe </option><option value="I" /> Asia </option><option value="F" /> Africa </option><option value="O" /> Oceania </option></select>

		</font></td></tr>
		<tr><td><font color="#376FA6" face="Verdana" size = "2">Contact person</font></td><td><font face="Verdana" size = "2">: <input type="text" name="position" size="20" value="<? echo $name; ?>"></font></td></tr>	     
		<tr><td><font color="#376FA6" face="Verdana" size = "2">Position in the organisation</font></td><td><font face="Verdana" size = "2">: <input type="text" name="position" size="20"></font></td></tr>	     
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Country</font></td><td><font face="Verdana" size = "2">: <input type="text" name="position" size="20" value="<? echo $country; ?>"></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">E-mail</font></td><td><font face="Verdana" size = "2">: <input type="text" name="position" size="20" value="<? echo $$email; ?>"></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Phone </font></td><td><font face="Verdana" size = "2">: <input type="text" name="phone" size="10"></font></td></tr>
		<tr><td><font color="#376FA6" face="Verdana" size = "2">Native language(s)</font></td><td><font face="Verdana" size = "2">: <input type="text" name="language" size="20"></font></td></tr>

	</table>
<br>
     <table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Details of the Project</b></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Where will the volunteer be working?</font><font color="#B30000" face="Verdana" size = "1">*</font> <br> <font face="Verdana" size = "2"><input type="text" name="place" size="30"></font></td></tr>
	<tr><td><font color="#376FA6" face="Verdana" size = "2">When is the volunteer expected to start? <br> <input type="text" name="sdate" size="10"></font></td></tr>
        <tr><td><font color="#376FA6" face="Verdana" size = "2">For how long is the volunteer expected to work? <br> <input type="text" name="how_long" size="10"></td></tr>
        <tr><td><font color="#376FA6" face="Verdana" size = "2">What can you offer the volunteer?</font><font color="#B30000" face="Verdana" size = "1">*</font><br>
	<tr><td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="offer[]" value="I" />International travel expenses <br>
		<input type="checkbox" name="offer[]" value="D" />Domestic travel expenses<br>
		<input type="checkbox" name="offer[]" value="A" />Accommodation <br>
		<input type="checkbox" name="offer[]" value="M" />Meals <br>
		<input type="checkbox" name="offer[]" value="P" />Pocket money <br>
		Other (please state): <input type="text" name="ooffer" size="20"> </td><td> </td></tr>
 	</table>
<br>

      <table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Discipline of Project </b></font><font color="#B30000" face="Verdana" size = "1">*</font></td></tr>

		<tr><td><font color="#376FA6" face="Verdana" size = "2">
        	<input type="checkbox" name="dis[]" value="F" /> Foot-O  &nbsp; &nbsp; &nbsp; &nbsp;</td><td><font color="#376FA6" face="Verdana" size = "2">
        	<input type="checkbox" name="dis[]" value="M" /> MTBO </td></tr><tr><td><font color="#376FA6" face="Verdana" size = "2">
        	<input type="checkbox" name="dis[]" value="S" /> Ski-O  &nbsp; &nbsp; &nbsp; &nbsp;</td><td><font color="#376FA6" face="Verdana" size = "2">
        	<input type="checkbox" name="dis[]" value="T" /> Trail-O 
	</font></td></tr>
 	</table>

<br>
</td>
<td valign="top" width="30%">


    
   
	

     <table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Skills required</b></font><font color="#B30000" face="Verdana" size = "1"> &nbsp;(Please tick all relevant to the project)</font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">* Mapping <br><font color="#376FA6" face="Verdana" size = "1"><input type="checkbox" name="sprint" value="1"> Sprint &nbsp; &nbsp;<input type="checkbox" name="forest" value="1"> Forest </td></tr><tr><td>
        <font color="#376FA6" face="Verdana" size = "2"><br>* Coaching<br><font color="#376FA6" face="Verdana" size = "1"><input type="checkbox" name="nteam" value="1"> National Team &nbsp; &nbsp;<input type="checkbox" name="clubs" value="1"> Clubs </td></tr><tr><td>
        <font color="#376FA6" face="Verdana" size = "2"><br>* IT & time-keeping <br><font color="#376FA6" face="Verdana" size = "1"><input type="checkbox" name="si" value="1"> SportIdent &nbsp; &nbsp;<input type="checkbox" name="emit" value="1"> Emit  &nbsp; &nbsp;<input type="checkbox" name="gps" value="1"> GPS Tracking</td></tr><tr><td>
        <font color="#376FA6" face="Verdana" size = "2"><br>* Event Organising <br><font color="#376FA6" face="Verdana" size = "1"><input type="checkbox" name="clubev" value="1"> Club Events &nbsp; &nbsp;<input type="checkbox" name="localev" value="1"> Local Events  &nbsp; &nbsp;<br><input type="checkbox" name="natev" value="1"> National Events  &nbsp; &nbsp;<input type="checkbox" name="hlev" value="1"> High-Level Events </td></tr><tr><td>
        <font color="#376FA6" face="Verdana" size = "2"><br><input type="checkbox" name="material" value="1"> Development of Educational Material</td></tr><tr><td> 
        <font color="#376FA6" face="Verdana" size = "2"><br><input type="checkbox" name="child" value="1"> Teaching beginners & children 
	</font></td></tr>
 	</table>
<br>	<table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Experience required</b></font><font color="#B30000" face="Verdana" size = "1"> &nbsp;(Select if relevant to the project)</font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">National Events &nbsp;  
		<select size="1" name="natc"><option selected value=''>- select -</option>
					<option value="0">none</option><option value="1">1 - 10</option>
					<option value="2">11 - 30</option>
					<option value="3">over 30</option></select><br>
		<br>Duties:<br></font><font color="#376FA6" face="Verdana" size = "1">
		
	<input type="checkbox" name="dut[]" value="NE" /> Event Director <br />
        <input type="checkbox" name="dut[]" value="NM" /> Mapper / Course Planner <br />
        <input type="checkbox" name="dut[]" value="NT" /> IT Director <br />
	<input type="checkbox" name="dut[]" value="NA" /> Event Advisor <br />
	<input type="checkbox" name="dut[]" value="NJ" /> Jury Member 
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
	<input type="checkbox" name="idut[]" value="IJ" /> Jury Member 

		</font></td></tr>
 		</table>
<br>
<table>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">

		Details of the work to be done </font><font color="#B30000" face="Verdana" size = "1"> *</font><br>
		<textarea rows="4" cols="50" name="details" id="details"></textarea><br><br>

        </td></tr>
 	</table>

</td>


<td valign="top" width="31%">
 <table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Personal details of Volunteer</b><br></font></font><font color="#B30000" face="Verdana" size = "1"> &nbsp;(Just skip if not important)</font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Gender </td><td>: <select size="1" name="gender"><option selected value=''>--- select ---</option><option value="M">Male</option><option value="F">Female</option></select></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Age </td><td>: <select size="1" name="age"><option selected value=''>--- select ---</option>
					<option value="1">Under 25</option>
					<option value="2">25 - 35</option>
					<option value="3">36 - 50</option>
					<option value="3">Over 50</option>		</select></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">International driving license? </font></td><td>: <select size="1" name="license"><option selected value=''>--- select ---</option><option value="1">Yes</option><option value="0">No</option></select></td></tr>
		<tr><td><font color="#376FA6" face="Verdana" size = "2">Language expectations </td><td><font face="Verdana" size = "2">: <input type="text" name="lang" size="20"></td></tr>
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
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Years of experience </td><td>: <font color="#376FA6" face="Verdana" size = "2"><select size="1" name="exp"><option selected value=''>--- select ---</option>
					<option value="1">Under 5</option>
					<option value="3">5 - 9</option>
					<option value="3">10 - 20</option>
					<option value="4">Over 20</option>		</select></td></tr>

        
	<tr><td><font color="#376FA6" face="Verdana" size = "2"><br>Competed in... </td></tr>
        <tr><td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="comp[]" value="L" /> Local events </td></tr>
        <tr><td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="comp[]" value="N" /> National Championships </td></tr>
        <tr><td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="comp[]" value="I" /> International Competitions</td></tr>
        
 	</table>
<br>
		<font color="#376FA6" face="Verdana" size = "2">Anything you would like to add<br>
		<textarea rows="4" cols="50" name="add" id="add"></textarea><br><br>

<b>Disclaimer:</b> Disclaimer: The details above are as accurate as possible. We understand that the IOF cannot be held responsible for our finding or not finding a suitable volunteer. We also understand that should we choose to recruit a volunteer through this database, the IOF cannot be held responsible for the terms or quality of work produced.
<br><br><input type="checkbox" name="ok" >  I have read and understood the above.</font> <font color="#B30000" face="Verdana" size = "1">*</font></td><td> </td></tr>	
<tr><td colspan="4" align="right"><input type="submit" name="newreq" value="Submit our request">

</td></tr></table> 
    </form>
<?


}	// newvol butonuna basilmamis	?>
    



</body></html>