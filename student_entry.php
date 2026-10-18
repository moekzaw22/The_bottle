<?php 
$connect = mysqli_connect("localhost","root","","student_DB");


 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Student Entry</title>
	<link rel="stylesheet" href="bottle-ui.css">
</head>
<body>
	<main class="page-content">
		<div class="utility-form">
			<h1>Student entry</h1>
			<label for="student-name">Student name</label>
			<input id="student-name" type="text" name="student_name" autocomplete="name">
			<p class="help-text">Student entry is not connected to a save action yet.</p>
		</div>
	</main>
</body>
</html>

