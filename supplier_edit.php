<?php 
include('navbar.php');
$error="";
$error2="";
$connect=mysqli_connect("Localhost","root","","the_bottle_database");
if (isset($_GET['SID'])) {
	$id=$_GET['SID'];
	$supplier_query="SELECT * from supplier Where id='$id'";

	$supplier_ret=mysqli_query($connect,$supplier_query);
	$supplier_array=mysqli_fetch_array($supplier_ret);
	$SCount=mysqli_num_rows($supplier_ret);
	$pid=$supplier_array['id'];
	$name=$supplier_array['name'];
	$address=$supplier_array['address'];
	$phone=$supplier_array['phone'];
}
if (isset($_POST['btnadd'])) {

	$id1=$_POST['txtid'];
	$name1=$_POST['txtname'];
	
	$address1=$_POST['txtaddress'];
	$phone1=$_POST['txtphone'];
	$update="UPDATE supplier SET name='$name1', phone='$phone1', address='$address1' WHERE id=$id1";
	$update_query=mysqli_query($connect,$update);
	if ($update_query) {
		echo "<script>alert('Supplier Updated')</script>";
		echo "<script>window.location='supplier_entry.php'</script>";
	}
	else{
		echo "<script>alert('Supplier Update Fail')</script>";
	}
}
 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<title></title>
 	<link href="fontawesome/fontawesome-free-5.15.4-web/css/all.css" rel="stylesheet">
 	<link rel="stylesheet" type="text/css" href="restock.css">
 </head>
 <body>
 	<form action="supplier_edit.php" method="POST">
 		


   
 		<h1>Supplier Edit Form</h1>
<table>
	
	 
	 <tr>
	 	<td>ID</td>
	 	<td><input type="text" value="<?php echo $id ?>" class="inputbox" name="txtid" readonly/></td>
	 </tr>
	 <tr>
	 	<td>Name</td>
	 	<td><input type="text" class="inputbox" value="<?php echo $name ?>" name="txtname"></td>
	 </tr>
	 <tr>
	 	<td>Address</td>
	 	<td><input type="text" class="inputbox" name="txtaddress" value="<?php echo $address ?>"></td>
	 </tr>
	 <tr>
	 	<td>Phone</td>
	 	<td><input type="number" class="inputbox" value="<?php echo $phone ?>" name="txtphone" required/>&nbsp;&nbsp;<span class="error"><?php echo $error2 ?></span></td>
	 </tr>
	 
	 <tr>
	 	<td></td>	
	 	<td colspan="2"><input type="submit" name="btnadd" class="add" value="Update">
	 		
	 		<button class="refresh" onclick="goback()">Back</button>
	 	</td>

	 </tr>
	
</table>

</form>
<script type="text/javascript">
	function goback(){
		history.go(-1);
	}

var ALERT_TITLE = "Oops!";
var ALERT_BUTTON_TEXT = "Ok";

if(document.getElementById) {
	window.alert = function(txt) {
		createCustomAlert(txt);
	}
}

function createCustomAlert(txt) {
	d = document;

	if(d.getElementById("modalContainer")) return;

	mObj = d.getElementsByTagName("body")[0].appendChild(d.createElement("div"));
	mObj.id = "modalContainer";
	mObj.style.height = d.documentElement.scrollHeight + "px";
	
	alertObj = mObj.appendChild(d.createElement("div"));
	alertObj.id = "alertBox";
	if(d.all && !window.opera) alertObj.style.top = document.documentElement.scrollTop + "px";
	alertObj.style.left = (d.documentElement.scrollWidth - alertObj.offsetWidth)/2 + "px";
	alertObj.style.visiblity="visible";

	h1 = alertObj.appendChild(d.createElement("h1"));
	h1.appendChild(d.createTextNode(ALERT_TITLE));

	msg = alertObj.appendChild(d.createElement("p"));
	//msg.appendChild(d.createTextNode(txt));
	msg.innerHTML = txt;

	btn = alertObj.appendChild(d.createElement("a"));
	btn.id = "closeBtn";
	btn.appendChild(d.createTextNode(ALERT_BUTTON_TEXT));
	btn.href = "#";
	btn.focus();
	btn.onclick = function() { removeCustomAlert();return false; }

	alertObj.style.display = "block";
	
}

function removeCustomAlert() {
	document.getElementsByTagName("body")[0].removeChild(document.getElementById("modalContainer"));
}
function ful(){
alert('Alert this pages');
}
</script>
<style type="text/css">

	.inputbox{
	width: 350px;
	background: black;
	color:white;
	padding:7px;
	box-sizing: content-box;
	font-size: 22px;
	border-radius: 10px;
	border: none;
}
.inputbox:focus{
	outline:none;
}
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
.add{
	background: darkcyan;
	border: none;
	width: 130px;
	color: white;
	padding: 7px;
	font-size: 22px;
	border-radius: 10px;
}
.add:hover{
	opacity: 0.7;
}
.refresh:hover{
	opacity: 0.7;
}
.refresh{
	background: #940000;
	border: none;
	width: 100px;
	color: white;
	font-size: 22px;
	padding: 7px;
	border-radius: 10px;
}
table{
	font-family: arial;
	font-size: 25px;
}
body{
	font-family: arial;
	background: #0E1818;
	color: white;
	margin-top: 110px;
}
.list_link{
	color: white;
	text-decoration: none;
}
.error{
color: red;
font-size: 15px;
}
</style>
 </body>
 </html>