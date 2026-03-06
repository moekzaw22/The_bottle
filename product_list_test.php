<?php
include('connect.php');

if(isset($_GET['ajax'])) {
    // This block returns JSON for live search
    $search = isset($_GET['txtsearch']) ? mysqli_real_escape_string($connect, $_GET['txtsearch']) : '';
    $where = "1";
    if ($search) $where .= " AND Product_name LIKE '$search%'";

    $query = "SELECT Product_id, Product_name, Price, Quantity, amount 
              FROM product 
              WHERE $where
              ORDER BY Product_id DESC LIMIT 0,30";
    $result = mysqli_query($connect, $query);

    $rows = [];
    if($result && mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = [
                'id' => $row['Product_id'],
                'name' => $row['Product_name'],
                'price' => $row['Price'],
                'quantity' => $row['Quantity'],
                'amount' => $row['amount']
            ];
        }
    }

    echo json_encode(['products'=>$rows]);
    exit; // important: stop rest of the page from loading
    
}
include('navbar.php');
// Normal page load below
?>
<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>
    <link rel="stylesheet" type="text/css" href="product_list.css">
</head>
<body>
<div class="mydiv1">
<input type="text" id="txtsearch" class="input" placeholder="Search Product..." autocomplete="off">
</div>
<table class="table">
    <thead>
        <tr>
            <th>Product ID</th>
            <th>Product Name (Amount)</th>
            <th>Price</th>
            <th>Quantity</th>
            
        </tr>
    </thead>
    <tbody id="productTableBody">
        <!-- Filled dynamically -->
    </tbody>
</table>

<script>
document.addEventListener("DOMContentLoaded", function(){
    const input = document.getElementById("txtsearch");
    const tbody = document.getElementById("productTableBody");

    function fetchProducts(){
        let val = input.value;

        fetch("?ajax=1&txtsearch="+encodeURIComponent(val))
        .then(res => res.json())
        .then(data => {
            tbody.innerHTML = '';
            if(data.products.length > 0){
                data.products.forEach(p => {
                    let tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${p.id}</td>
                        <td>${p.name} (${parseFloat(p.amount).toLocaleString()}ml)</td>
                        <td>${parseFloat(p.price).toLocaleString()} </td>
                       
                        <td>${parseFloat(p.quantity).toLocaleString()}</td>
                      
                    `;
                    tbody.appendChild(tr);
                });
            } else {
                let tr = document.createElement('tr');
                tr.innerHTML = `<td colspan="6">No products found</td>`;
                tbody.appendChild(tr);
            }
        });
    }

    // Initial load
    fetchProducts();

    // Live search with debounce
    let timeout = null;
    input.addEventListener("input", function(){
        clearTimeout(timeout);
        timeout = setTimeout(fetchProducts, 300);
    });

    input.focus();
    input.select();
});
</script>
</body>
</html>