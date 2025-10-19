<?php 
$connection = mysqli_connect('localhost','root','','the_bottle_inner_circle');

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['btnsubmit'])) {
    $phonenumber = $_POST['numphonenumber'];
    $amount = $_POST['numamount'];

    if (empty($phonenumber)) {
        echo "Please enter a phone number.";
        exit;
    }

    $points = !empty($amount) ? $amount / 20 : 0;
    $check_account = "SELECT * FROM account WHERE Phone_Number = '$phonenumber'";
    $check_query = mysqli_query($connection, $check_account);

    if (mysqli_num_rows($check_query) > 0) {
        $row = mysqli_fetch_assoc($check_query);
        $account_id = $row['Account_ID'];
        $current_total = $row['Total_Points'];

        if (!empty($amount)) {
            $new_total = $current_total + $points;
            $update_points = "UPDATE account SET Total_Points = '$new_total' WHERE Account_ID = '$account_id'";
            mysqli_query($connection, $update_points);
            $insert_log = "INSERT INTO point_system (Account_ID, Point, Action) VALUES ('$account_id', '$points', 'earn')";
            mysqli_query($connection, $insert_log);

            echo "<div class='message success'>
                    <h4>Points Added Successfully</h4>
                    <p><strong>Phone:</strong> $phonenumber<br>
                    <strong>Earned Points:</strong> $points<br>
                    <strong>Total Points:</strong> $new_total</p>
                  </div>";
        } else {
            echo "<div class='message info'>
                    <p>Account found for <strong>$phonenumber</strong><br>
                    Total Points: <strong>$current_total</strong></p>
                    <form method='POST' action=''>
                        <input type='hidden' name='redeem_phone' value='$phonenumber'>
                        <input type='number' class='redeem_txt' name='redeem_points' placeholder='Enter points to redeem' min='1' max='$current_total' required>
                        <button type='submit' name='btnredeem'>Redeem Points</button>
                    </form>
                  </div>";
        }
    } else {
        $insert_account = "INSERT INTO account (Phone_Number, Total_Points) VALUES ('$phonenumber', '$points')";
        if (mysqli_query($connection, $insert_account)) {
            $account_id = mysqli_insert_id($connection);
            if (!empty($amount)) {
                $insert_log = "INSERT INTO point_system (Account_ID, Point, Action) VALUES ('$account_id', '$points', 'earn')";
                mysqli_query($connection, $insert_log);
                echo "<div class='message success'>
                        <h4>New Account Created</h4>
                        <p>Earned $points points.<br>
                        Account ID: $account_id<br>
                        Phone Number: $phonenumber</p>
                      </div>";
            } else {
                echo "<div class='message success'>
                        <h4>New Account Created</h4>
                        <p>Account ID: $account_id<br>
                        Phone Number: $phonenumber<br>
                        Total Points: 0</p>
                      </div>";
            }
        } else {
            echo "<div class='message error'>Error creating account: " . mysqli_error($connection) . "</div>";
        }
    }
}

if (isset($_POST['btnredeem'])) {
    $phonenumber = $_POST['redeem_phone'];
    $points_to_redeem = $_POST['redeem_points'];
    $check = mysqli_query($connection, "SELECT * FROM account WHERE Phone_Number = '$phonenumber'");
    $acc = mysqli_fetch_assoc($check);
    $account_id = $acc['Account_ID'];
    $current_points = $acc['Total_Points'];

    if ($current_points >= $points_to_redeem) {
        $new_total = $current_points - $points_to_redeem;
        mysqli_query($connection, "UPDATE account SET Total_Points = '$new_total' WHERE Account_ID = '$account_id'");
        mysqli_query($connection, "INSERT INTO point_system (Account_ID, Point, Action) VALUES ('$account_id', '$points_to_redeem', 'redeem')");
        echo "<div class='message success'>
                <h4>Redeemed Successfully</h4>
                <p>Redeemed $points_to_redeem points from $phonenumber<br>
                Remaining Points: $new_total</p>
              </div>";
    } else {
        echo "<div class='message error'>
                Not enough points to redeem. This account has only $current_points points.
              </div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Rewards</title>
    <style>
        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f7f8fa;
            color: #333;
            padding: 20px;
        }

        h2 {
            color: #333;
            margin-bottom: 10px;
        }

        form {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            width: 380px;
            margin-bottom: 30px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
            color: #444;
        }

        input[type="number"], 
        input[type="text"] {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        button, input[type="submit"] {
            background: #0078D7;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover, input[type="submit"]:hover {
            background: #005fa3;
        }

        .message {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
            background: #f1f1f1;
        }
        .success { background: #e6f9ee; border-left: 5px solid #28a745; }
        .error { background: #fdecea; border-left: 5px solid #dc3545; }
        .info { background: #e8f1fb; border-left: 5px solid #0d6efd; }

        table {
            background: #fff;
            border-collapse: collapse;
            width: 400px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        th {
            background: #0078D7;
            color: white;
            padding: 10px;
            text-align: left;
        }

        td {
            padding: 10px;
            border-top: 1px solid #eee;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .redeem_txt {
            width: 100%;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h2>Customer Rewards</h2>

<form action="Register.php" method="POST">
    <label>Phone Number</label>
    <input type="number" name="numphonenumber" required>

    <label>Purchase Amount</label>
    <input type="number" name="numamount" placeholder="optional">

    <input type="submit" name="btnsubmit" value="Submit">
</form>

<?php
$query = "SELECT Phone_Number, Total_Points FROM account ORDER BY Total_Points DESC";
$result = mysqli_query($connection, $query);
?>

<h2>Customer Points List</h2>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Phone Number</th>
        <th>Total Points</th>
    </tr>

    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Phone_Number']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Total_Points']) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='2'>No accounts found.</td></tr>";
    }
    ?>
</table>

</body>
</html>
