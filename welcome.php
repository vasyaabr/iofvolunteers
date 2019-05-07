<?php //session_start(); 
$id=$_SESSION['id'];

$back=$_SESSION['back'];
$result = mysql_query("SELECT * FROM users WHERE id='$id' ");
$row = mysql_fetch_array($result, MYSQL_ASSOC);$name=$row['name'];
if(substr_count($name," ")>0) $nname = strtok($name, " ");else $nname = trim($name);
$welcome="Welcome ".$back.$nname."! Be part of the Global Orienteering Volunteer Network by clicking on any of the icons below...";


?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1254">

<title>IOF Global Orienteering Volunteer Platform</title>
</head>

<body class="bodystyle" link="#376FA6" vlink="#376FA6">

      <p align="center"><img border="0" src="images/pg_template1.jpg" > 



<table align="center" border="0" cellpadding="0" cellspacing="10" width="75%">
    <tr><td align ="center" width="30%" valign="top">

<p align="center"><img class="slides" src="images/project1s.jpg">
<img class="slides" src="images/project2s.jpg">
<img class="slides" src="images/project3s.jpg"></p>

<script>
var slideIndex = 0;
change_slides();

function change_slides() {
    var i;
    var x = document.getElementsByClassName("slides");
    for (i = 0; i < x.length; i++) {
      x[i].style.display = "none"; 
    }
    slideIndex++;
    if (slideIndex > x.length) {slideIndex = 1} 
    x[slideIndex-1].style.display = "block"; 
    setTimeout(change_slides, 2000); // Change image every 2 seconds
}
</script>      
<br><br>
<table align="center" border="0" cellpadding="0" cellspacing="10" width="75%" bgcolor="#E7FCFE">
<tr><td align = "center"> <font color="#376FA6" face="Verdana" size = "3"><b> Impressions, Experiences... </font></b></td></tr> 
<tr>	<td>


<marquee direction="up" scrollamount="1" behavior="scroll" height="200">



<font face="Verdana" size = "2">
"Orienteering has always been and remains to be my favorite activity. I started orienteering in 1978 and I am still an active orienteer, mainly in Foot-O. I regularly take a part in events at various levels, from local events to WMOC. I have prepared an MBTO map for the Albanian Orienteering Project. It is nice to get the opportunity to be involved in the development of orienteering in a country I have never been to."<br>
<b>Alexey Zuev, Volunteer, Russia</b><br><br>
"The IOF Global Orienteering Volunteer Project is great for me, as apart from some help from friends from Austria, this is the first time I am not the only expert here in Albania. It gives me the feeling of being recognised in my work and motivates me to do more."<br>
<b>Inge Bosina, Project Leader, Albanian Orienteering Project</b><br><br>
"Wow this has taken off! I knew it would. My dream has come true. I have been asking people to do something world wide at IOF level like this for many years. You have done very well."<br>
<b>David Poland, Project Leader, Orienteering Scholarship Australia</b><br><br>

"I would like to thank you for great experience we have had as volunteers for Navigation Games in the United States. Me and my husband Josivan Juan, are grateful for this opportunity given by IOF Global Orienteering Volunteer Platform."<br> 
<b>Pavla Zdrahalova, Volunteer, Czech Republic</b></font>
</marquee>
</td></tr></table>	






</td>
<td valign="top">
<table><tr><td align="center" bgcolor="#E7FCFE" width="45%">
<font size="3" face="Verdana"><b><? echo $welcome; ?></b>  </td></tr></table>
	<table cellpadding="10"><tr>

<td align="center" ><a href="new_vol.php" target="_blank" alt="new volunteer"><img src="images/vol_needed.jpg" width="100" height="77"></a><br><font color="#376FA6" face="Verdana" size = "2"><b>Register as a Volunteer</b></font><br>
<font size="2" face="Verdana">Are you skilled in some orienteering topics? Do you want to make new friends and while help others develop orienteering?<br>  </font><font size="1" color="#B30000" face="Verdana">(You need to be 18+ to volunteer!)</td><td width="%5"> </td>
<td align="center"><a href="vol_search.php" target="_blank" alt="search a volunteer"><img src="images/search_vol.jpg" width="100" height="77"></a><br><font color="#376FA6" face="Verdana" size = "2"><b>Search a Volunteer</b></font><br>
<font size="2" face="Verdana">Find volunteers with different skills who can help you develop orienteering or help you realize a project!</font></td>

</tr><tr>

<td align="center"><a href="new_request.php" target="_blank" alt="new project"><img src="images/new_request.jpg" width="100" height="77"></a><br><font color="#376FA6" face="Verdana" size = "2"><b>Register a Project</b></font><br>
<font size="2" face="Verdana">Your club or organisation wants to work in orienteering but you need extra help? Register your project and ask for volunteers!</font><br><font size="1" color="#B30000" face="Verdana">(You need to register as an organization first!)</td><td width="%5"> </td>
<td align="center"><a href="search_req.php" target="_blank" alt="find a project"><img src="images/search_requests.jpg" width="150" height="77"></a><br><font color="#376FA6" face="Verdana" size = "2"><b>Search a Project</b></font><br>
<font size="2" face="Verdana">There are many clubs and organisations in different countries in need of volunteers to help with mapping, coaching, teaching and more!</font></td>

</tr><tr>

<td align="center"><a href="new_family.php" target="_blank" alt="new family"><img src="images/family1s.jpg" width="100" height="77"></a><br><font color="#376FA6" face="Verdana" size = "2"><b>Register as a Host Family</b></font><br>
<font size="2" face="Verdana">You are an orienteering family and you would like to host a young orienteer? Help a future star go training with you! </font></td><td width="%5"> </td>
<td align="center"><a href="find_family.php" target="_blank" alt="find a family"><img src="images/find_host.jpg" width="75"></a><br><font color="#376FA6" face="Verdana" size = "2"><b>Find a host family</b></font><br>
<font face="Verdana" size = "2">There are orienteering families in different countries where you can stay and go orienteering with them! Find a host family!</font><br><font size="1" color="#B30000" face="Verdana">(You need to be 18+ to apply!)</td>


</tr><tr>

<td align="center"><a href="new_guest.php" target="_blank" alt="register as guest"><img src="images/new_orik.jpg" width="75" height="77"></a><br><font color="#376FA6" face="Verdana" size = "2"><b>Register as a Guest</b></font><br>
<font size="2" face="Verdana">You want to develop your o-skills more by training in different terrains? Apply to stay with an orienteering family and go training with them!</font><font size="1" color="#B30000" face="Verdana"><br>(You need to be 18+ to register!)</td><td width="%5"> </td>
<td align="center"><a href="find_guest.php" target="_blank" alt="search a guest to host"><img src="images/practices.jpg" width="100" height="77"></a><br><font color="#376FA6" face="Verdana" size = "2"><b>Search a guest</b></font><br>
<font face="Verdana" size = "2">There are some future stars out there waiting for a helping hand! Find a young orienteer who could stay with your family and go orienteering with you!</font></td>
</tr></table>

</td></tr>

</table>
</body>

</html>
