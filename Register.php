<?php 
$connection = mysqli_connect('localhost','root','','the_bottle_inner_circle');

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['btnsubmit'])) {
    $phonenumber = $_POST['numphonenumber'];
    $amount = $_POST['numamount'];

    if (empty($phonenumber)) {
        echo "⚠️ Please enter a phone number.";
        exit;
    }

    // Calculate points only if amount provided
    $points = !empty($amount) ? $amount / 20 : 0;

    // Check if account exists
    $check_account = "SELECT * FROM account WHERE Phone_Number = '$phonenumber'";
    $check_query = mysqli_query($connection, $check_account);

    if (mysqli_num_rows($check_query) > 0) {
        // ✅ Account exists
        $row = mysqli_fetch_assoc($check_query);
        $account_id = $row['Account_ID'];
        $current_total = $row['Total_Points'];

        if (!empty($amount)) {
            // Add new points
            $new_total = $current_total + $points;

            // Update total points
            $update_points = "UPDATE account SET Total_Points = '$new_total' WHERE Account_ID = '$account_id'";
            mysqli_query($connection, $update_points);

            // Log transaction
            $insert_log = "INSERT INTO point_system (Account_ID, Point, Action) VALUES ('$account_id', '$points', 'earn')";
            mysqli_query($connection, $insert_log);

            echo "✅ Points Added Successfully!<br>";
            echo "Phone: $phonenumber<br>";
            echo "Earned Points: $points<br>";
            echo "Total Points: $new_total<br>";
        } else {
            echo "📱 Account found for $phonenumber<br>";
            echo "Total Points: $current_total<br>";
        }

    } else {
        // ❌ Account not found → create new account
        $insert_account = "INSERT INTO account (Phone_Number, Total_Points) VALUES ('$phonenumber', '$points')";
        if (mysqli_query($connection, $insert_account)) {
            $account_id = mysqli_insert_id($connection);

            // If amount provided, record earn log
            if (!empty($amount)) {
                $insert_log = "INSERT INTO point_system (Account_ID, Point, Action) VALUES ('$account_id', '$points', 'earn')";
                mysqli_query($connection, $insert_log);
                echo "✅ New account created and earned $points points!<br>";
            } else {
                echo "✅ New account created with 0 points.<br>";
            }

            echo "Account ID: $account_id<br>";
            echo "Phone Number: $phonenumber";
        } else {
            echo "Error creating account: " . mysqli_error($connection);
        }
    }

    // Optional: Reward eligibility check
    $reward_threshold = 1000;
    $reward_check = mysqli_query($connection, "SELECT Total_Points FROM account WHERE Phone_Number = '$phonenumber'");
    $reward_row = mysqli_fetch_assoc($reward_check);
    $total_points = $reward_row['Total_Points'] ?? 0;

    if ($total_points >= $reward_threshold) {
        
    } else {
        $need = $reward_threshold - $total_points;
       
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Rewards</title>
</head>
<body>
    <form action="Register.php" method="POST">
        <label>Phone Number</label>
        <input type="number" name="numphonenumber" required><br>

        <label>Purchase Amount</label>
        <input type="number" name="numamount" placeholder="optional"><br>

        <input type="submit" name="btnsubmit" value="Submit">
    </form>
</body>
<style>
    body {
        font-family: Arial;
        padding: 20px;
    }
    label {
        display: inline-block;
        width: 150px;
        margin-bottom: 5px;
    }
</style>
</html>
<?php
$connection = mysqli_connect('localhost', 'root', '', 'the_bottle_inner_circle');

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch all accounts with their points
$query = "SELECT Phone_Number, Total_Points FROM account ORDER BY Total_Points DESC";
$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Points List</title>
</head>
<body>
    <h2>📋 Customer Points List</h2>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr style="background-color:#f0f0f0;">
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

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 30px;
    }
    h2 {
        margin-bottom: 15px;
    }
    table {
        border-collapse: collapse;
        width: 400px;
    }
    th, td {
        text-align: left;
        padding: 8px;
    }
    tr:nth-child(even) {
        background-color: #fafafa;
    }
</style>
</html>
