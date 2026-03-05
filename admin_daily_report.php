<?php 
include('connect.php');
include('admin_navbar.php');

// Default to current month & year
$selected_year = $_GET['year'] ?? date("Y");
$selected_month = $_GET['month'] ?? date("m");

// Year dropdown
$year_query = mysqli_query($connect,"SELECT DISTINCT YEAR(Date) AS year FROM purchase WHERE status='Confirmed' ORDER BY year DESC");

// Month array
$months = [
    1=>"Jan", 2=>"Feb", 3=>"Mar", 4=>"Apr", 5=>"May", 6=>"Jun",
    7=>"Jul", 8=>"Aug", 9=>"Sep", 10=>"Oct", 11=>"Nov", 12=>"Dec"
];

$query = "SELECT Date, SUM(totalprice) AS totalp, SUM(Buy_Quantity) AS qtytotal,SUM(profit) AS totalprofit
          FROM purchase 
          WHERE status='Confirmed'
          AND YEAR(Date) = '$selected_year'
          AND MONTH(Date) = '$selected_month'
          GROUP BY Date
          ORDER BY Date ASC";

$select_query = mysqli_query($connect, $query);
$count=mysqli_num_rows($select_query);
 ?>

 <!DOCTYPE html>
 <html>
 <head>
    <title></title>
 </head>
 <body>
    
 <style type="text/css">
 .container-1{
    margin: 10px;
    gap:10px;
    box-sizing: content-box;
    display: flex;
    float: right;
 }
    body{font-family: arial;
    
    }
    #sltdate {
        padding: 6px;
        font-size: 19px;
    }
    #txtdate{
        font-size: 19px;
    }
    #btnfilter{
        font-size: 19px;
        width:100px;
        background: #005F02;
        border:none;color:white;
        border-radius: 5px;
    }
    .table { width:100%; border-collapse: collapse; margin-top:20px; }
.table th, .table td { border:1px solid grey; padding:8px; text-align:center; }
.table th { background:#343a40;color:white }.table tr:nth-child(even) { background:#f2f2f2 }
.table tr:hover{background: #e6f2ff}
 </style>
        
    
    <div class="container-1">

<form method="GET">
    <select id="sltdate" name="year" onchange="this.form.submit()">
        <?php while($row = mysqli_fetch_assoc($year_query)): 
            $y = $row['year'];
            $selected = ($selected_year == $y) ? "selected" : "";
        ?>
        <option value="<?= $y ?>" <?= $selected ?>><?= $y ?></option>
        <?php endwhile; ?>
    </select>
    <select id="sltdate" name="month" onchange="this.form.submit()">
        <?php foreach($months as $num => $name):
            $selected = ($selected_month == $num) ? "selected" : "";
        ?>
        <option value="<?= $num ?>" <?= $selected ?>><?= $name ?></option>
        <?php endforeach; ?>
    </select>
</form>
</div>
<!-- 
        <select id="sltdate" name="sltdate">
            <option value="ASC">Date ASC</option>
            <option value="DESC">Date DESC</option>
        </select>
        <input id="txtdate" type="date" name="txtdate">
        <input type="submit" name="btnsearch" value="Filter" id="btnfilter">
    </div> -->
 <table class="table">
    <tr>
        
        <th>Date</th>
        <th>Total</th>
        <th>Total QTY</th>
        <th>Profit</th>
    </tr>
    <?php 

    // if (isset($_GET['btnsearch'])) {
    //  $sltdate = $_GET['sltdate'];
    //  $txtdate = $_GET['txtdate'];
    //  if (empty($txtdate)) {
    //  $select="SELECT Date,SUM(totalprice) AS totalp, SUM(Buy_Quantity) AS qtytotal FROM purchase GROUP BY Date ORDER BY Date = '$sltdate'";
    //      $select_query=mysqli_query($connect,$select);
    //      $count=mysqli_num_rows($select_query);
            
    //  }
    //  elseif (!empty($txtdate)) {
    //  $select="SELECT Date,SUM(totalprice) AS totalp, SUM(Buy_Quantity) AS qtytotal FROM purchase WHERE Date = '$txtdate' GROUP BY Date";
    //      $select_query=mysqli_query($connect,$select);
    //      $count=mysqli_num_rows($select_query);
        
    //  }
        
    // }
    // else{
    // $select="SELECT Date,SUM(totalprice) AS totalp, SUM(Buy_Quantity) AS qtytotal FROM purchase WHERE status = 'Confirmed' GROUP BY Date ORDER BY Date DESC";
    //      $select_query=mysqli_query($connect,$select);
    //      $count=mysqli_num_rows($select_query);
        
    //      }
            for ($i=0; $i < $count ; $i++) { 
                $select_array=mysqli_fetch_array($select_query);
                
                $date=$select_array['Date'];
                
                $price=$select_array['totalp'];
                $Buy_Quantity = $select_array['qtytotal'];
                $profit = $select_array['totalprofit']
                 ?>
                 <tr>
                    
                    <td><?php echo Date('(d D) (M) Y',strtotime($date)) ?></td>
                    
                    <td><?php echo number_format($price) ?> Ks</td>
                    
                    <td><?php echo $Buy_Quantity ?></td>

                    <td><?php echo number_format($profit) ?></td>
                 
                 </tr>
                 <?php 
        }
                  ?>
 </table>
 <script type="text/javascript">
  document.getElementById('sltdate').value= "<?php echo $_GET['sltdate'];?>";

</script>
 
 </body>
 </html>