<?php
require_once 'config/auth.php';
require_once 'config/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize($_POST['name']);
    $description = sanitize($_POST['description']);
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    if (empty($name) || empty($price) || empty($quantity)) {
        $error = "Name, price, and quantity are required.";
    } elseif (!is_numeric($price) || !is_numeric($quantity)) {
        $error = "Price and quantity must be numeric.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, quantity , user_id) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $description, $price, $quantity, $_SESSION['user_id']])) {
            $success = "Product added successfully. <a href='products.php'>View Products</a>";
        } else {
            $error = "Failed to add product.";
        }
    }
}
include 'config/header.php';
?>

<div class="main">
    <div class="center">
        <div class="form-card">
            <h2>Add New Product</h2>

            <?php if ($error): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" class="product-form">

                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" step="0.01" name="price" required>
                    </div>

                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" required>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Add Product</button>
            </form>
        </div>
    </div>

</div>



<?php
include 'config/footer.php';
?>