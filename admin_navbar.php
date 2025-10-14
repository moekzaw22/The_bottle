<?php 

 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<title></title>
 </head>
 <body>
 	        <div class="nav-bar">
           <a class="link-login" href="purchase.php"><img class="logo" src="logo.jpg"></a>
           <a class="link-a" href="admin_product_list.php">Product List</a>
           <a class="link-a" href="admin_todaysalerp.php">Today Report</a>
           <a class="link-a" href="admin_salereport.php">All Report</a>
           <a class="link-a" href="admin_monthly.php">Monthly</a>

           <a class="link-a" href="admin_daily_report.php">Daily Report</a>
          <?php 
            
               echo "<a class='link-a' style='margin-left:500px' href='admin_logout.php'>Logout</a>";
            
          

           ?>
        </div>
 		<!-- <table class="navbarhaha">
 			<tr>
 				<td><img class="logo" src="logo.jpg"></td>
 				<td></td>
 				<td></td>
 				
 				<td><a href="product_entry.php">Product Entry</a></td>
 				<td><a href="product_list.php">Product List</a></td>
 				<td><a href="Supplier_entry.php">Supplier</a></td>
 				<td><a href="restock.php">Stock</a></td>

 				<td><a href="purchase.php">Sale</a></td>
 				<td><a href="todaysalerp.php">Today Report</a></td>
 				<td><a href="salereport.php">All Report</a></td>
 				<td><a href="daily_report.php">Daily Report</a></td>
                <td>Admin</td>
 			</tr>
 		</table> -->
 		
 	
 </body>
 </html>
 <style type="text/css">
 	.logo{
 		width:100px;
 		height:100px;
 		border-radius:99px;

 	}
 body{
margin: 0;
 }
 	.nav-bar{
      font-family: arial;
 		position: fixed;
    top: 0px;
    width: 100%;
        background: #101012;
 		display:flex;
 		font-size:25px;
 		padding-bottom:0px;
 		
 	}
 	.nav-bar .link-a{
       margin-top: 25px;
        padding: 10px;
 		padding-left: 10px;
 		padding-right: 10px;
 		text-decoration: none;
 		color: white;
 	}
 	.nav-bar a:hover{
        color: cyan;
    }
 </style>