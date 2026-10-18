<?php 
include('connect.php');
include('navbar.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>
	<link rel="stylesheet" type="text/css" href="product_list.css">
	<link href="fontawesome/fontawesome-free-5.15.4-web/css/all.css" rel="stylesheet">
</head>
<body>
<style type="text/css">
</style>
	<div class="mydiv1 product-list-search" role="search">
        <label class="visually-hidden" for="txtitem">Filter products</label>
        <input type="search" id="txtitem" class="input" aria-label="Filter products" placeholder="Find a product by name, code, or ID" autocomplete="off">
	</div>
	<br>
	 <table class="table" id="productTable">
		<tr>
			<th>Product ID</th>	
			<th>Product Code</th>
			<th>Product Name(milliliter)</th>
			<th>Quantity</th>
			<th>Price</th>
			<th>Action</th>
		</tr>
		<?php
		$select_query = mysqli_query($connect, "SELECT Product_id, Product_code, Product_name, amount, Quantity, Price FROM product ORDER BY Product_id DESC LIMIT 20");
		while ($row = mysqli_fetch_assoc($select_query)): ?>
			<tr data-product-row>
				<td><?= htmlspecialchars((string)$row['Product_id'], ENT_QUOTES, 'UTF-8') ?></td>
				<td><?= htmlspecialchars((string)$row['Product_code'], ENT_QUOTES, 'UTF-8') ?></td>
				<td><?= htmlspecialchars((string)$row['Product_name'], ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars((string)$row['amount'], ENT_QUOTES, 'UTF-8') ?>)</td>
				<td id="quantity_<?= (int)$row['Product_id'] ?>"><?= max(0, (int)$row['Quantity']) ?></td>
				<td><?= number_format((float)$row['Price']) ?></td>
				<td><a class="product-action" href="#" onclick="openRestockModal(<?= (int)$row['Product_id'] ?>); return false;">Restock</a></td>
			</tr>
		<?php endwhile; ?>
	</table>
<nav class="product-pagination" id="productPagination" aria-label="Product list pages">
    <button type="button" id="productPrevious" disabled>Previous</button>
    <span id="productPageStatus" aria-live="polite">Page 1</span>
    <button type="button" id="productNext" disabled>Next</button>
</nav>
<div id="restockModal" class="restock-modal" role="dialog" aria-modal="true" aria-labelledby="restockTitle" hidden>
    <div class="restock-modal-content">
        <button type="button" class="restock-close" aria-label="Close" onclick="closeRestockModal()">&times;</button>
        <h2 id="restockTitle">Restock Product</h2>
        <form id="restockForm">
            <input type="hidden" name="product_id" id="modal_product_id">
            <label for="modal_product_name">Product Name</label>
            <input type="text" id="modal_product_name" readonly>
            <label for="modal_product_quantity">In stock</label>
            <input type="number" id="modal_product_quantity" min="0" readonly>
            <label for="modal_add_quantity">Add quantity</label>
            <input type="number" name="add_quantity" id="modal_add_quantity" min="1" step="1" required>
            <button type="submit">Add Stock</button>
            <p id="restockMessage" role="status" aria-live="polite"></p>
        </form>
    </div>
</div>
<style>
.product-action { color:#f1bd5d; }
.restock-modal[hidden] { display:none; }
.restock-modal { position:fixed; inset:0; z-index:1000; display:flex; align-items:center; justify-content:center; padding:16px; background:#0009; }
.restock-modal-content { position:relative; width:min(100%,420px); padding:24px; color:#edf3fb; background:#131e30; border:1px solid #2a3a52; border-radius:14px; box-shadow:0 18px 50px #0008; }
.restock-modal-content h2 { margin:0 36px 18px 0; }
.restock-modal-content label { display:block; margin:12px 0 5px; }
.restock-modal-content input { display:block; width:100%; }
.restock-modal-content button[type=submit] { margin-top:16px; background:#17865b !important; border-color:#17865b !important; }
.restock-close { position:absolute; top:12px; right:12px; min-width:38px; padding:6px !important; font-size:22px !important; }
#restockMessage { min-height:1.5em; margin:10px 0 0; color:#65d6a1; }
.product-pagination { display:flex; justify-content:center; align-items:center; gap:14px; margin:18px 16px 32px; }
.product-pagination span { min-width:160px; color:#d5dfed; text-align:center; }
.product-pagination button:disabled { opacity:.45; cursor:not-allowed; transform:none; }
</style>
<script>
function openRestockModal(productId) {
    fetch('get_product.php?id=' + encodeURIComponent(productId))
        .then(response => { if (!response.ok) throw new Error('Product lookup failed'); return response.json(); })
        .then(product => {
            if (!product || !product.Product_id) throw new Error('Product not found');
            document.getElementById('modal_product_id').value = product.Product_id;
            document.getElementById('modal_product_name').value = product.Product_name + ' (' + product.amount + 'ml)';
            document.getElementById('modal_product_quantity').value = Math.max(0, Number(product.Quantity) || 0);
            document.getElementById('modal_add_quantity').value = '';
            document.getElementById('restockMessage').textContent = '';
            document.getElementById('restockModal').hidden = false;
            document.getElementById('modal_add_quantity').focus();
        })
        .catch(() => alert('Unable to load product details.'));
}

function closeRestockModal() { document.getElementById('restockModal').hidden = true; }

document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("txtitem");
    const table = document.getElementById("productTable");
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
            const row = document.createElement("tr"); row.dataset.emptyRow = "true";
            const cell = document.createElement("td"); cell.colSpan = 6; cell.textContent = "No products found.";
            row.appendChild(cell); body.appendChild(row); return;
        }
        products.forEach(product => {
            const row = document.createElement("tr"); row.dataset.productRow = "true";
            [product.id, product.code, `${product.name} (${product.amount})`, Math.max(0, Number(product.quantity)), Number(product.price).toLocaleString()]
                .forEach(value => { const cell = document.createElement("td"); cell.textContent = value; row.appendChild(cell); });
            row.cells[3].id = "quantity_" + product.id;
            const action = document.createElement("td");
            const restock = document.createElement("a"); restock.className = "product-action"; restock.href = "#"; restock.textContent = "Restock";
            restock.addEventListener("click", event => { event.preventDefault(); openRestockModal(product.id); });
            action.appendChild(restock); row.appendChild(action);
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

    document.getElementById('restockForm').addEventListener('submit', function (event) {
        event.preventDefault();
        const form = this;
        const amount = document.getElementById('modal_add_quantity');
        if (!Number.isInteger(Number(amount.value)) || Number(amount.value) < 1) {
            amount.setCustomValidity('Enter a whole number greater than zero.');
            amount.reportValidity();
            return;
        }
        amount.setCustomValidity('');
        const submit = form.querySelector('button[type="submit"]');
        submit.disabled = true;
        fetch('restock_ajax.php', { method: 'POST', body: new FormData(form) })
            .then(response => response.json().then(data => { if (!response.ok || !data.success) throw new Error(data.error || 'Restock failed'); return data; }))
            .then(data => {
                const quantity = document.getElementById('quantity_' + data.product_id);
                if (quantity) quantity.textContent = Math.max(0, Number(data.new_quantity));
                document.getElementById('modal_product_quantity').value = Math.max(0, Number(data.new_quantity));
                document.getElementById('restockMessage').textContent = 'Stock updated successfully.';
            })
            .catch(error => { document.getElementById('restockMessage').textContent = error.message; })
            .finally(() => { submit.disabled = false; });
    });
    document.getElementById('modal_add_quantity').addEventListener('input', function () { this.setCustomValidity(''); });
    document.getElementById('restockModal').addEventListener('click', event => { if (event.target.id === 'restockModal') closeRestockModal(); });
    document.addEventListener('keydown', event => { if (event.key === 'Escape') closeRestockModal(); });
    filterProducts();
});
</script>
</body>
</html>
