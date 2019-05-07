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





if(isset($_POST['search'])) 
{
// start filter

/*
$error=0;
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
	$teachArray = $_POST["teach"]; if(!empty($teachArray) && isset($teachArray[0])) $teachbos==2; else $teachbos=1; }

if(	$mapper =='' && 
	$sprint ==0 && 
	$forest ==0 && 

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





if($error==0){
	   $insert_command = "INSERT INTO experience(natc, intc, sprint, forest,  nteam, clubs, si, emit, othertime, gps, clubev, localev, natev, hlev, vid ) 
		VALUES('$natc', '$intc', '$sprint', '$forest', '$nteam', '$clubs', '$si', '$emit', '$othertime', '$gps', '$clubev', '$localev', '$natev', '$hlev', '$vid')";
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
$dcomn = "delete from teaching where vid='$vid' ";$dlev = mysql_query($dcomn, $connect); if(!$dlev) {die("Database query failed: ". mysql_error());} 
}
}

*/
include('list_vol.php');	            
}// search butonu
else
{
?>


    

<form method="POST" action="search_vol.php" enctype="multipart/form-data">
 
<p align="center"><img border="0" src="images/top_banner2.jpg" ></p>

              <p  align="center"><b><font face="Verdana" color="#376FA6" size="3">Volunteer Search Form</font></b><br><font color="#B30000" face="Verdana" size = "2">Please fill in your search criteria. Leave blank if not relevant / important!</font></p>


    
<table width="90%" align="center">
<tr>
<td valign="top" width="25%"> <table>
	    <tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b><br>Personal Information</b></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Gender </font></td><td>: <select size="1" name="gender"><option selected value=''>--- select ---</option><option value="M">Male</option><option value="F">Female</option></select></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Age </font></td><td>: <font color="#376FA6" face="Verdana" size = "2"> at least <input type="text" name="minage" size="2" > at most <input type="text" name="maxage" size="2" ></td></tr>
<tr><td colspan="2"><font color="#B30000" face="Verdana" size = "1">Note that volunteers under 18 are not allowed to register in the Platform</font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">International driving license? </font></td><td>: <input type="checkbox" name="license" value="1" /><font color="#376FA6" face="Verdana" size = "2"> Check if required  </td></tr>
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
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">Years in orienteering </td><td>:<font color="#376FA6" face="Verdana" size = "2"> at least <input type="text" name="oyears" size="2"> years</td</tr>
        <tr><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><u>Experience as Competitor</u> </td></tr>
	<tr><td><font color="#376FA6" face="Verdana" size = "2">Taken part in minimum <br>
	Local events </td><td><font color="#376FA6" face="Verdana" size = "2">: <select size="1" name="loc"><option selected value=''>--- select ---</option>
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

<td valign="top" width="27%"> <table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Languages required</b></font><br><font color="#B30000" face="Verdana" size = "1">Tick only the <u>most important</u> one or two to increase search results</font></td></tr>
	    <tr>

        <td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="E"  /> English   </td></tr><tr>
        <td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="F" /> French </td></tr><tr>
        <td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="S"  /> Spanish </td></tr><tr>
        <td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="G"  /> German </td></tr><tr>
        <td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="I"  /> Italian </td></tr><tr>
        <td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="P"  /> Portuguese </td></tr><tr>
	<td><font color="#376FA6" face="Verdana" size = "2"><input type="checkbox" name="lang[]" value="W"  /> "Scandinavian" </td></tr><tr>
	</table>
<br>
     <table><tr valign="top"><td><font color="#376FA6" face="Verdana" size = "2"><b>Timing</b></font></td></tr>
	    <tr><td><font color="#376FA6" face="Verdana" size = "2">When to start? <input type="text" size="2" name="sdatem" value="mm"> / <input type="text" size="10" name="sdatey" value="yyyy"></td></tr> 
        <tr><td><font color="#376FA6" face="Verdana" size = "2">Must stay for at least <input type="text" size="3" name="duration" value='' > weeks</td></tr>
        </font>
 	</table>

</td>
<td valign="top" width="31%">
     
     <table><tr valign="top"><td colspan="2"><font color="#376FA6" face="Verdana" size = "2"><b>Skills required</b></font><font color="#B30000" face="Verdana" size = "1"> &nbsp;(Please tick only the <u>most important</u> ones to increase search results...)</font></td></tr>
	    <tr><td><input type="checkbox" name="sprint" value="M"><font color="#376FA6" face="Verdana" size = "2"> Mapping </td></tr>
        <tr><td><input type="checkbox" name="sprint" value="M"><font color="#376FA6" face="Verdana" size = "2"> Coaching </td></tr>
<tr><td><input type="checkbox" name="sprint" value="M"><font color="#376FA6" face="Verdana" size = "2"> IT & timekeeping </td></tr>
<tr><td><input type="checkbox" name="sprint" value="M"><font color="#376FA6" face="Verdana" size = "2"> Event Organising </td></tr>
<tr><td><font color="#376FA6" face="Verdana" size = "2"><u>Teaching</u> (please tick)
	<br><font color="#376FA6" face="Verdana" size = "1"><input type="checkbox" name="teach[]" value="C" > Beginners & children <br><input type="checkbox" name="teach[]" value="M" > Teach how to map  <br><input type="checkbox" name="teach[]" value="O" > Teach coaching <br><input type="checkbox" name="teach[]" value="I" > Teach IT & Timekeeping <br><input type="checkbox" name="teach[]" value="E" > Teach Event Organising

</td></tr>
</table>
</td></tr>
<tr><td colspan="4" align="right"><input type="submit" name="search" value="Find volunteers">

</td></tr></table> 
    </form>
<?


}	// search butonuna basilmamis	?>
    



</body></html>