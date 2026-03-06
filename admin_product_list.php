<?php 
include('connect.php');
include('admin_navbar.php');


// Handle search
$search = "";
$where = "";
if (isset($_GET['btnsubmit']) && !empty($_GET['txtproductname'])) {
    $search = mysqli_real_escape_string($connect, $_GET['txtproductname']);
    $where = "WHERE Product_name LIKE '%$search%' OR Product_id = '$search'";

}elseif(isset($_GET['delete'])){
     $pid = intval($_GET['delete']); // sanitize input

    $stmt = $connect->prepare("DELETE FROM product WHERE Product_id = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $stmt->close();

    // Redirect to avoid re-deleting on refresh
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


// Fetch products
$query = mysqli_query($connect, "SELECT * FROM product $where ORDER BY Product_id DESC LIMIT 0, 20");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Product List</title>
    <!-- <link rel="stylesheet" href="admin_product_list.css?v=<?= time(); ?>"> -->
    <link href="fontawesome/fontawesome-free-5.15.4-web/css/all.css" rel="stylesheet">
   
    <style>
        body { font-family: Arial; margin: 0; padding: 0px; }
         .search-class{
            margin-top: 10px;margin-bottom: 10px;margin-left:79%;
        }
        .input { padding: 8px; width: 200px; border-radius: 5px; }
        .search-btn { padding: 8px 12px; background: royalblue;color:white;border: none; cursor: pointer; border-radius: 5px; }
        .product-action{color:white}
        /* Modal */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; 
                 background: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
        .modal-content { background: white; padding: 20px; border-radius: 8px; width: 400px; position: relative; color: black; }
        .modal-content label{color:black}
        .close { position: absolute; right: 10px; top: 10px; font-size: 20px; cursor: pointer; }
        .modal-content input { width: 100%; padding: 8px; margin: 5px 0 15px 0; border-radius: 5px; border: 1px solid #ccc;color:black }
        .modal-content button { padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer; background: #28a745; color: white; }
        .modal-content button:hover { background: #218838; }
        #restockMessage { color: green; margin-top: 10px; display: none; font-weight: bold; text-align: center; }
    </style>
</head>
<body class="<? $mode ?>">

<!-- Search Form -->
<form method="GET">
    <div class="search-class">
    <input type="text" id ="txtitem" name="txtproductname" class="input" placeholder="Find product..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit" name="btnsubmit" class="search-btn"><i class="fas fa-search"></i> Search</button>
</form>
</div>
<!-- Products Table -->
<table class="table">
    <tr>
        <th>ID</th>
        <th>Code</th>
        <th>Name (ml)</th>
        <th>Stock</th>
        <th>SP Price</th>
        <th>Price</th>
        <th>Profit</th>
        <th>Action</th>
    </tr>
<?php if(mysqli_num_rows($query) > 0): ?>
    <?php while($row = mysqli_fetch_assoc($query)):
        $profit = $row['Price'] - $row['sp_price']; ?>
    <tr>
        <td><?= $row['Product_id'] ?></td>
        <td><?= $row['Product_code'] ?></td>
        <td><?= $row['Product_name'] ?> (<?= $row['amount'] ?>)</td>
        <td id="quantity_<?= $row['Product_id'] ?>"><?= $row['Quantity'] ?></td>
        <td><?= number_format($row['sp_price']) ?></td>
        <td><?= number_format($row['Price']) ?></td>
        <td><?= number_format($profit) ?></td>
        <td>
            <a class="product-action" href="#" onclick="openRestockModal(<?= $row['Product_id'] ?>)">Restock</a> | 
            <a class="product-action" href="productedit.php?PID=<?= $row['Product_id'] ?>">Edit</a> | 
           <a class="product-action" href="?delete=<?= $row['Product_id'] ?>" 
           onclick="return confirm('Are you sure you want to delete this product?');">
           Delete
        </a>

        </td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
<tr><td colspan="8">No products found.</td></tr>
<?php endif; ?>
</table>

<!-- Restock Modal -->
<div id="restockModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeRestockModal()">&times;</span>
        <h2 style="color:black">Restock Product</h2>
        <form id="restockForm">
            <input type="hidden" name="product_id" id="modal_product_id">
            <label>Product Name:</label>
            <input type="text" id="modal_product_name" readonly>
            <label>Instock:</label>
            <input type="number" id="modal_product_quantity" readonly>
            <label>Add Quantity:</label>
            <input type="number" name="add_quantity" id="modal_add_quantity" required>
            <button type="submit">Add Stock</button>
        </form>
        <div id="restockMessage">Product successfully Stock!</div>
    </div>
</div>

<script>
function openRestockModal(productId) {
    fetch('get_product.php?id=' + productId)
    .then(res => res.json())
    .then(data => {
        document.getElementById('modal_product_id').value = data.Product_id;
        document.getElementById('modal_product_name').value = data.Product_name + ' (' + data.amount + 'ml)';

        document.getElementById('modal_product_quantity').value = data.Quantity;
        document.getElementById('restockMessage').style.display = 'none';
        document.getElementById('restockModal').style.display = 'flex';
         document.getElementById('modal_add_quantity').focus();
    });
}

function closeRestockModal() {
    document.getElementById('restockModal').style.display = 'none';
}

// Close when clicking outside modal
window.addEventListener('click', function(event) {
    const modal = document.getElementById('restockModal');
    if (event.target === modal) {
        closeRestockModal();
    }
});
document.getElementById('restockForm').addEventListener('submit', function(e){
    e.preventDefault();

    let formData = new FormData(this);

    fetch('restock_ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){

            document.getElementById('restockMessage').style.display = 'block';

            document.getElementById('quantity_' + data.product_id).innerText = data.new_quantity;

            // Close modal after success
           
                closeRestockModal();

        } else {
            alert('Failed to restock.');
        }
    });
});
function confirmDelete() {
    return confirm("Are you sure you want to delete this item?");
}
 document.addEventListener("DOMContentLoaded", function() {
    var input = document.getElementById("txtitem");
    input.focus();
    input.select(); // selects the whole value
});
</script>

</body>
</html>