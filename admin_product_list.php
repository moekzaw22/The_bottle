<?php 
include('connect.php');
include('admin_navbar.php');


// Handle search
$search = trim((string)($_GET['txtproductname'] ?? ''));
if (isset($_GET['delete'])) {
     $pid = intval($_GET['delete']); // sanitize input

    $stmt = $connect->prepare("DELETE FROM product WHERE Product_id = ?");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $stmt->close();

    // Redirect to avoid re-deleting on refresh
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


// Match product names, IDs, and barcodes without interpolating user input into SQL.
if ($search !== '') {
    $searchStatement = $connect->prepare(
        "SELECT * FROM product
         WHERE Product_name LIKE CONCAT('%', ?, '%')
            OR Product_id = ?
            OR Product_code = ?
         ORDER BY Product_id DESC LIMIT 20"
    );
    $searchStatement->bind_param('sss', $search, $search, $search);
    $searchStatement->execute();
    $query = $searchStatement->get_result();
} else {
    $query = mysqli_query($connect, "SELECT * FROM product ORDER BY Product_id DESC LIMIT 20");
}
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
        .admin-search-form { display:flex; justify-content:flex-end; margin:24px clamp(16px, 3vw, 40px) 12px; }
        .search-class { display:flex; align-items:center; flex-wrap:wrap; gap:8px; width:min(100%, 520px); }
        .search-class .input { flex:1 1 240px; min-width:0; }
        .search-clear { display:inline-flex; align-items:center; min-height:40px; padding:8px 12px; color:#d5dfed; text-decoration:none; }
        .product-pagination { display:flex; justify-content:center; align-items:center; gap:14px; margin:18px 16px 32px; }
        .product-pagination span { min-width:160px; color:#d5dfed; text-align:center; }
        .product-pagination button:disabled { opacity:.45; cursor:not-allowed; transform:none; }
        .product-actions { display:flex; align-items:center; flex-wrap:wrap; gap:6px; }
        .product-action.action-button { display:inline-flex; align-items:center; justify-content:center; min-height:30px; padding:5px 10px; border:1px solid transparent; border-radius:7px; font-size:13px; font-weight:700; line-height:1.2; text-decoration:none; white-space:nowrap; }
        .action-restock { color:#082719 !important; background:#65d6a1; border-color:#39b77b !important; }
        .action-price { color:#17202b !important; background:#f0b74c; border-color:#d8992e !important; }
        .action-edit { color:#fff !important; background:#3982c4; border-color:#2869a5 !important; }
        .action-delete { color:#fff !important; background:#c34d58; border-color:#a83c47 !important; }
        .product-action.action-button:hover { filter:brightness(1.12); }
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
<div class="admin-search-form" role="search">
    <div class="search-class">
    <label class="visually-hidden" for="txtitem">Filter products</label>
    <input type="search" id="txtitem" class="input" aria-label="Filter products" placeholder="Name, barcode, or product ID" autocomplete="off" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
    </div>
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
    <tr data-product-row data-product-id="<?= (int)$row['Product_id'] ?>">
        <td><?= $row['Product_id'] ?></td>
        <td><?= htmlspecialchars((string)$row['Product_code'], ENT_QUOTES, 'UTF-8') ?></td>
        <td><?= htmlspecialchars($row['Product_name'], ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars((string)$row['amount'], ENT_QUOTES, 'UTF-8') ?>)</td>
        <td id="quantity_<?= $row['Product_id'] ?>"><?= max(0, (int)$row['Quantity']) ?></td>
        <td><?= number_format($row['sp_price']) ?></td>
        <td><?= number_format($row['Price']) ?></td>
        <td><?= number_format($profit) ?></td>
        <td class="product-actions">
            <a class="product-action action-button action-restock" href="#" onclick="openRestockModal(<?= $row['Product_id'] ?>); return false;">Restock</a>
            <a class="product-action action-button action-price" href="#" onclick="openPriceModal(<?= $row['Product_id'] ?>, <?= (float)$row['sp_price'] ?>, <?= (float)$row['Price'] ?>); return false;">Change Price</a>
            <a class="product-action action-button action-edit" href="productedit.php?PID=<?= $row['Product_id'] ?>">Edit</a>
           <a class="product-action action-button action-delete" href="?delete=<?= $row['Product_id'] ?>"
           onclick="return confirm('Are you sure you want to delete this product?');">
           Delete
        </a>

        </td>
    </tr>
    <?php endwhile; ?>
<?php else: ?>
<tr data-empty-row><td colspan="8">No products found.</td></tr>
<?php endif; ?>
</table>
<nav class="product-pagination" id="productPagination" aria-label="Product list pages">
    <button type="button" id="productPrevious" disabled>Previous</button>
    <span id="productPageStatus" aria-live="polite">Page 1</span>
    <button type="button" id="productNext" disabled>Next</button>
</nav>

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
            <input type="number" name="add_quantity" id="modal_add_quantity" min="1" step="1" required>
            <button type="submit">Add Stock</button>
        </form>
        <div id="restockMessage">Product successfully Stock!</div>
    </div>
</div>
<div id="priceModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="priceModalTitle">
    <div class="modal-content">
        <span class="close" onclick="closePriceModal()" aria-label="Close">&times;</span>
        <h2 id="priceModalTitle">Change Product Price</h2>
        <form id="priceForm">
            <input type="hidden" name="product_id" id="price_product_id">
            <label for="price_product_name">Product Name</label>
            <input type="text" id="price_product_name" readonly>
            <label for="price_sp_price">SP Price (cost)</label>
            <input type="number" name="sp_price" id="price_sp_price" min="0" step="0.01" required>
            <label for="price_sale_price">Price (selling)</label>
            <input type="number" name="price" id="price_sale_price" min="0" step="0.01" required>
            <button type="submit">Save Prices</button>
        </form>
        <div id="priceMessage" role="status" aria-live="polite"></div>
    </div>
</div>
<style>
#priceModal .modal-content label { display:block; margin:12px 0 5px; }
#priceModal .modal-content input { display:block; width:100%; }
#priceModal .modal-content button[type=submit] { margin-top:16px; background:#17865b !important; border-color:#17865b !important; }
#priceMessage { min-height:1.5em; margin-top:10px; color:#65d6a1; }
</style>

<script>
function openRestockModal(productId) {
    fetch('get_product.php?id=' + productId)
    .then(res => res.json())
    .then(data => {
        document.getElementById('modal_product_id').value = data.Product_id;
        document.getElementById('modal_product_name').value = data.Product_name + ' (' + data.amount + 'ml)';

        document.getElementById('modal_product_quantity').value = Math.max(0, Number(data.Quantity) || 0);
        document.getElementById('restockMessage').style.display = 'none';
        document.getElementById('restockModal').style.display = 'flex';
         document.getElementById('modal_add_quantity').focus();
    });
}

function closeRestockModal() {
    document.getElementById('restockModal').style.display = 'none';
}

function openPriceModal(productId, spPrice, price) {
    const row = document.querySelector(`[data-product-row][data-product-id="${CSS.escape(String(productId))}"]`);
    document.getElementById('price_product_id').value = productId;
    document.getElementById('price_product_name').value = row ? row.cells[2].textContent.trim() : '';
    document.getElementById('price_sp_price').value = spPrice;
    document.getElementById('price_sale_price').value = price;
    document.getElementById('priceMessage').textContent = '';
    document.getElementById('priceModal').style.display = 'flex';
    document.getElementById('price_sp_price').focus();
}

function closePriceModal() { document.getElementById('priceModal').style.display = 'none'; }

// Close when clicking outside modal
window.addEventListener('click', function(event) {
    const modal = document.getElementById('restockModal');
    if (event.target === modal) {
        closeRestockModal();
    }
    if (event.target.id === 'priceModal') closePriceModal();
});
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeRestockModal();
        closePriceModal();
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
            const quantity = document.getElementById('quantity_' + data.product_id);
            if (quantity) quantity.innerText = Math.max(0, Number(data.new_quantity));
            closeRestockModal();
        } else {
            alert('Failed to restock.');
        }
    });
});
document.getElementById('priceForm').addEventListener('submit', function (event) {
    event.preventDefault();
    const form = this;
    const submit = form.querySelector('button[type="submit"]');
    const message = document.getElementById('priceMessage');
    const spPrice = Number(document.getElementById('price_sp_price').value);
    const price = Number(document.getElementById('price_sale_price').value);
    if (!Number.isFinite(spPrice) || !Number.isFinite(price) || spPrice < 0 || price < 0) {
        message.textContent = 'Enter valid prices of zero or more.';
        return;
    }
    submit.disabled = true;
    message.textContent = 'Saving…';
    fetch('admin_update_price_ajax.php', { method: 'POST', body: new FormData(form) })
        .then(response => response.json().then(data => { if (!response.ok || !data.success) throw new Error(data.error || 'Price update failed.'); return data; }))
        .then(data => {
            const row = document.querySelector(`[data-product-row][data-product-id="${CSS.escape(String(data.product_id))}"]`);
            if (row) {
                row.cells[4].textContent = Number(data.sp_price).toLocaleString();
                row.cells[5].textContent = Number(data.price).toLocaleString();
                row.cells[6].textContent = (Number(data.price) - Number(data.sp_price)).toLocaleString();
            }
            message.textContent = 'Prices updated successfully.';
        })
        .catch(error => { message.textContent = error.message; })
        .finally(() => { submit.disabled = false; });
});
function confirmDelete() { return confirm("Are you sure you want to delete this item?"); }
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("txtitem");
    const table = document.querySelector(".table");
    const body = table.tBodies[0];
    const previousButton = document.getElementById("productPrevious");
    const nextButton = document.getElementById("productNext");
    const pageStatus = document.getElementById("productPageStatus");
    let timer;
    let currentPage = 1;
    let requestNumber = 0;

    function renderProducts(data) {
        const products = data.products;
        table.querySelectorAll("[data-product-row], [data-empty-row]").forEach(row => row.remove());
        currentPage = data.page;
        previousButton.disabled = currentPage <= 1;
        nextButton.disabled = currentPage >= data.total_pages;
        pageStatus.textContent = `Page ${data.page} of ${data.total_pages} · ${data.total} products`;
        if (!products.length) {
            const row = document.createElement("tr");
            row.dataset.emptyRow = "true";
            const cell = document.createElement("td");
            cell.colSpan = 8;
            cell.textContent = "No products found.";
            row.appendChild(cell);
            body.appendChild(row);
            return;
        }
        products.forEach(product => {
            const row = document.createElement("tr");
            row.dataset.productRow = "true";
            const values = [product.id, product.code, `${product.name} (${product.amount})`, product.quantity,
                Number(product.sp_price).toLocaleString(), Number(product.price).toLocaleString(),
                (Number(product.price) - Number(product.sp_price)).toLocaleString()];
            values.forEach(value => { const cell = document.createElement("td"); cell.textContent = value; row.appendChild(cell); });
            const action = document.createElement("td"); action.className = "product-actions";
            const restock = document.createElement("a"); restock.className = "product-action action-button action-restock"; restock.href = "#"; restock.textContent = "Restock";
            restock.addEventListener("click", event => { event.preventDefault(); openRestockModal(product.id); });
            const price = document.createElement("a"); price.className = "product-action action-button action-price"; price.href = "#"; price.textContent = "Change Price";
            price.addEventListener("click", event => { event.preventDefault(); openPriceModal(product.id, product.sp_price, product.price); });
            const edit = document.createElement("a"); edit.className = "product-action action-button action-edit"; edit.href = `productedit.php?PID=${encodeURIComponent(product.id)}`; edit.textContent = "Edit";
            const remove = document.createElement("a"); remove.className = "product-action action-button action-delete"; remove.href = `?delete=${encodeURIComponent(product.id)}`; remove.textContent = "Delete";
            remove.addEventListener("click", event => { if (!confirm("Are you sure you want to delete this product?")) event.preventDefault(); });
            [restock, price, edit, remove].forEach(link => action.appendChild(link));
            row.dataset.productId = product.id;
            row.appendChild(action);
            body.appendChild(row);
        });
    }

    function filterProducts() {
        const thisRequest = ++requestNumber;
        fetch("product_search_ajax.php?q=" + encodeURIComponent(input.value.trim()) + "&page=" + currentPage)
            .then(response => { if (!response.ok) throw new Error("Search failed"); return response.json(); })
            .then(data => { if (thisRequest === requestNumber) renderProducts(data); })
            .catch(() => {});
    }

    input.addEventListener("input", () => { currentPage = 1; requestNumber++; clearTimeout(timer); timer = setTimeout(filterProducts, 250); });
    input.addEventListener("keydown", event => { if (event.key === "Enter") event.preventDefault(); });
    previousButton.addEventListener("click", () => { if (currentPage > 1) { currentPage--; filterProducts(); } });
    nextButton.addEventListener("click", () => { currentPage++; filterProducts(); });
    filterProducts();
});
</script>

</body>
</html>
