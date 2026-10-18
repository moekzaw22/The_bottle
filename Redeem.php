<?php
$connection = mysqli_connect('localhost','root','','the_bottle_inner_circle');

if(isset($_POST['redeem'])){
    $account_id = $_POST['account_id'];
    $redeem_points = $_POST['points'];

    // Get current points
    $check = $conn->query("SELECT Total_Points FROM account WHERE Account_ID = $account_id");
    $row = $check->fetch_assoc();
    $current = $row['Total_Points'];

    if($current >= $redeem_points){
        // Update total
        $conn->query("UPDATE account SET Total_Points = Total_Points - $redeem_points WHERE Account_ID = $account_id");

        // Record redemption
        $conn->query("INSERT INTO point_system (Account_ID, Point, Action) VALUES ($account_id, $redeem_points, 'redeem')");

        echo "✅ Redeemed $redeem_points points successfully!";
    } else {
        echo "❌ Not enough points to redeem.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Redeem Points</title>
	<link rel="stylesheet" href="bottle-ui.css">
</head>
<body>
	<form method="POST" class="utility-form">
	<h1>Redeem points</h1>
	<p class="help-text">Use your account to exchange available points.</p>
    <input type="hidden" name="account_id" value="1">
    <label for="points">Points to redeem</label>
    <input id="points" type="number" name="points" min="1" placeholder="Enter points to redeem" required>
    <button type="submit" name="redeem">Redeem points</button>
</form>

</body>
</html>
