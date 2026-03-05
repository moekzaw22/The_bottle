<?php 
include('connect.php');
include('admin_navbar.php');
$select_date = isset($_GET['txtdate']) ? $_GET['txtdate'] : date('Y-m-d');
$where = "WHERE 1 ";
if (!empty($select_date)) {
       $where .= "AND DATE(h.date_time) = '$select_date'";
   }   
    $history = "SELECT h.Type, h.Amount, h.added_to,h.History_ID, h.date_time, p.Product_name FROM history h
			LEFT JOIN product p ON p.Product_id=h.added_to $where ORDER BY h.History_ID DESC";
$his_query = mysqli_query($connect,$history);

 ?>

 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<title></title>
 </head>
 <body>
    <form method="GET">
 	<div class="container">
  <div class="header-container">
        <h2>Product History</h2>
        <input class="datepicker"
               type="date"
               name="txtdate"
               value="<?= isset($_GET['txtdate']) ? $_GET['txtdate'] : '' ?>"
               onchange="this.form.submit()">
    </div>
    </form>
    <table>
        <tr>
            <th>Type</th>
            <th>Product</th>
            <th>Amount</th>
            <th>Date</th>
        </tr>

        <?php while($ass = mysqli_fetch_assoc($his_query)) { ?>

        <tr>
            <td>
                <?php
                    $type = $ass['Type'];
                    if($type == "Add"){
                        echo "<span class='badge-add'>Add</span>";
                    } elseif($type == "Canceled"){
                        echo "<span class='badge-delete'>Canceled</span>";
                    } else {
                        echo "<span class='badge-update'>Restock</span>";
                    }
                ?>
            </td>

            <td>
                <?= $ass['Product_name'] ?? 'N/A' ?>
            </td>

            <td>
                <?= $ass['Amount'] ?? 'N/A' ?>
            </td>

            <td>
                <?= date("d M Y, h:i A", strtotime($ass['date_time'])) ?>
            </td>
        </tr>

        <?php } ?>
    </table>
</div>
 	 <style>
 	 	
body{
    font-family: Arial, sans-serif;
    background:#f4f6f9;
  
}
.header-container {
    display: flex;
    justify-content: space-between; /* pushes left and right */
    align-items: center;            /* vertical alignment */
    margin-bottom: 20px;
}

.header-container h2 {
    margin: 0;
}

.datepicker {
    padding: 6px 10px;
    font-size: 16px;
}

.container{
    width:90%;
    max-width:1000px;
    margin:40px auto;
    background:#fff;
    padding:25px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

h2{
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
}
th{
    background:#2c3e50;
    color:white;
    padding:12px;
    text-align:left;
}
td{
    padding:10px;
    border-bottom:1px solid #ddd;
}
tr:nth-child(even){
    background:#f9f9f9;
}
tr:hover{
    background:#eaf2ff;
}

.badge-add{
    color:green;
    font-weight:bold;
}

.badge-delete{
    color:red;
    font-weight:bold;
}

.badge-update{
    color:orange;
    font-weight:bold;
}
</style>
 	
 </body>
 </html>