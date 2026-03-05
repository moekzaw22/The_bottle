
function openRestockModal(productId) {
    // Fetch product info via AJAX
    fetch('get_product.php?id=' + productId)
    .then(res => res.json())
    .then(data => {
        document.getElementById('modal_product_id').value = data.Product_id;
        document.getElementById('modal_product_name').value = data.Product_name + ' (' + data.amount + 'ml)';
        document.getElementById('modal_product_quantity').value = data.Quantity;
        document.getElementById('restockMessage').style.display = 'none';
        document.getElementById('restockModal').style.display = 'flex';
    });
}

function closeRestockModal() {
    document.getElementById('restockModal').style.display = 'none';
}

// Handle form submission
document.getElementById('restockForm').addEventListener('submit', function(e){
    e.preventDefault();
    let formData = new FormData(this);

    fetch('restock_ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            document.getElementById('restockMessage').style.display = 'block';
            // update quantity in the table without reloading
            document.querySelector(`#quantity_${data.product_id}`).innerText = data.new_quantity;
        } else {
            alert('Failed to restock');
        }
    });
});