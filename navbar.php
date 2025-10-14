<?php 
    session_start();
 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="navbar.css">
 	<title></title>
 </head>
 <body>
 	      <div class="nav-bar">
           <a class="link-login" href="check_user.php"><img class="logo" src="logo.jpg"></a>
           <a class="link-a" href="product_entry.php">Product Entry</a>
           <a class="link-a" href="product_list.php">Product List</a>
          
           <a class="link-a" href="purchase.php">Sale</a>
           <a class="link-a" href="todaysalerp.php">Today Report</a>
           <a class="link-a" href="salereport.php">All Report</a>
           <a class="link-a" href="daily_report.php">Daily Report</a>
           <a href="Monthly.php" class="link-a">Monthly</a>
               <div class="link-u">
                      <?php 
                if (empty($_SESSION['Username'])) {
                    echo "<label class='mode'>User Mode</label>";
                }
                else{
                    echo "<label class='mode'>Admin Mode</label>";
                    echo "<a class='logout' href='admin_logout.php'>Logout</a>";
                }
             ?>
                </div>
        </div>
 </body>
 </html>