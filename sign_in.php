<?php session_start(); 

    $connect = mysql_connect("localhost", "fenmen", "nermin"); 

    if(!$connect) 
  
 { die('Could not connect: ' . mysql_error());}
   
   $db_link= mysql_select_db("fenmen"); 
   if(!$db_link)
   {  echo "no database of that name!";}


if (isset($_POST['signin'])) 
{

               extract($_POST);

		 $uname=trim($uname);
		$email=trim($email); 
		$pword=trim($pword);
	if($uname==''&&$email=='')
	{?>
	<p align="center"><font face="Verdana" size = "4" color="#376FA6"><br><br>
	You may sign in using your username or e-mail. Go back and give at least one of these... 
	</font><p>
	<?}
	else if($pword=='')
	{?>
	<p align="center"><font face="Verdana" size = "4" color="#376FA6"><br><br>
	You need to supply your password. Go back and enter your password... 
	</font><p>
	<?}
	else {
	$result = mysql_query("SELECT * FROM users WHERE pword='$pword' AND (uname='$uname' OR email = '$email') ");
	$num=mysql_num_rows($result);
	


	if($num==0)
	{?>
	<p align="center"><font face="Verdana" size = "4" color="#376FA6"><br><br>
	Password incorrect or a record with this username or email does not exist. If you are sure you are already registered, go back to check your login details. If you are a new user, register first! 
	</font><p>
	<?}
	else{$row = mysql_fetch_array($result, MYSQL_ASSOC);$_SESSION['id']=$row['id']; $_SESSION['back']='back, ';include('welcome.php'); }
	}
}
else
{?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1254">

<title>IOF Global Orienteering Volunteer Platform</title>
</head>

<body class="bodystyle" link="#376FA6" vlink="#376FA6">

      <p align="center"><img border="0" src="images/pg_template1.jpg" > 
<script>
function but1n() {
document.getElementById("imagen").src = "button1new.jpg";
}
function but2n() {
document.getElementById("imagen").src = "button2new.jpg";
}
function but1s() {
document.getElementById("images").src = "button1signin.jpg";
}
function but2s() {
document.getElementById("images").src = "button2signin.jpg";
}
</script>



<table align="center" border="0" cellpadding="0" cellspacing="10" width="75%">
     <tr><td align ="center" width="30%" valign="top">


<form method="POST" action="sign_in.php">
<table width="300" bordercolor="#376FA6">
<tr><td colspan="2"><p align="center"><font face="Verdana" color="#376FA6" size="1">You may sign in using your e-mail or username and password...</font></p></td></tr>
<tr><td><font face="Verdana" color="#376FA6" size="2">E-mail</font></td><td>: <input type="text" name="email" size="20"> OR </td></tr>
<tr><td><font face="Verdana" color="#376FA6" size="2">Username</font></td><td>: <input type="text" name="uname" size="20"></td></tr>
<tr><td><font face="Verdana" color="#376FA6" size="2">Password</font></td><td>: <input type="password" name="pword" size="20"></td></tr>

<tr><td colspan="2"><p align="center"><input type="submit" value="Sign in!" name="signin"></p></td>

</tr></table></form>
<p align="center"><font face="Verdana" size="2"><b> If you are a new user, register first! </b></font><br><a href="new_user.php"><img src="button1new.jpg" id="imagen" border="0" onMouseOver="but2n();" onMouseOut="but1n();">          </a></p> 
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
<font size="3" face="Verdana"><b>Welcome to the Global Orienteering Volunteer Platform!<br>
	<br>Sign in to use the platform...</b>  </td></tr></table>
	<table cellpadding="10"><tr>

<td align="center" ><img src="images/vol_needed.jpg" width="100" height="77"><br><font color="#376FA6" face="Verdana" size = "2"><b>Register as a Volunteer</b></font><br>
<font size="2" face="Verdana">Are you skilled in some orienteering topics? Do you want to make new friends and while help others develop orienteering?<br>  </font><font size="1" color="#B30000" face="Verdana">(You need to be 18+ to volunteer!)</td><td width="%5"> </td>
<td align="center"><img src="images/search_vol.jpg" width="100" height="77"><br><font color="#376FA6" face="Verdana" size = "2"><b>Search a Volunteer</b></font><br>
<font size="2" face="Verdana">Find volunteers with different skills who can help you develop orienteering or help you realize a project!</font></td>

</tr><tr>

<td align="center"><img src="images/new_request.jpg" width="100" height="77"><br><font color="#376FA6" face="Verdana" size = "2"><b>Register a Project</b></font><br>
<font size="2" face="Verdana">Your club or organisation wants to work in orienteering but you need extra help? Register your project and ask for volunteers!</font></td><td width="%5"> </td>
<td align="center"><img src="images/search_requests.jpg" width="150" height="77"><br><font color="#376FA6" face="Verdana" size = "2"><b>Search a Project</b></font><br>
<font size="2" face="Verdana">There are many clubs and organisations in different countries in need of volunteers to help with mapping, coaching, teaching and more!</font></td>

</tr><tr>

<td align="center"><img src="images/family1s.jpg" width="100" height="77"><br><font color="#376FA6" face="Verdana" size = "2"><b>Register as a Host Family</b></font><br>
<font size="2" face="Verdana">You are an orienteering family and you would like to host a young orienteer? Help a future star go training with you! </font></td><td width="%5"> </td>
<td align="center"><img src="images/find_host.jpg" width="75"><br><font color="#376FA6" face="Verdana" size = "2"><b>Find a host family</b></font><br>
<font face="Verdana" size = "2">There are orienteering families in different countries where you can stay and go orienteering with them! Find a host family!</font><br><font size="1" color="#B30000" face="Verdana">(You need to be 18+ to apply!)</td>


</tr><tr>

<td align="center"><img src="images/new_orik.jpg" width="75" height="77"><br><font color="#376FA6" face="Verdana" size = "2"><b>Register as a Guest</b></font><br>
<font size="2" face="Verdana">You want to develop your o-skills more by training in different terrains? Apply to stay with an orienteering family and go training with them!</font><font size="1" color="#B30000" face="Verdana"><br>(You need to be 18+ to register!)</td><td width="%5"> </td>
<td align="center"><img src="images/practices.jpg" width="100" height="77"><br><font color="#376FA6" face="Verdana" size = "2"><b>Search a guest</b></font><br>
<font face="Verdana" size = "2">There are some future stars out there waiting for a helping hand! Find a young orienteer who could stay with your family and go orienteering with you!</font></td>
</tr></table>

</td></tr>

</table>
</body>

</html>

<?}?>
<?php
session_start();
ob_flush();?>