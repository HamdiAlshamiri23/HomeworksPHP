<?php
require_once 'config/auth.php';
require_once 'config/config.php';

$error = '';
$success = '';

if (!isset($_GET['id'])) {
    redirect('products.php');
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    redirect('products.php');
}

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
        $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, quantity = ? , user_id = ? WHERE id = ?");
        if ($stmt->execute([$name, $description, $price, $quantity, $_SESSION['user_id'], $id])) {
            $success = "Product updated successfully. <a href='products.php'>View Products</a>";

            // Refresh product data
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch();
        } else {
            $error = "Failed to update product.";
        }
    }
}

include 'config/header.php';
?>


<!-- Main -->
<main class="main">
    <div class="center">
        <div class="form-card">
            <div class="page-header-simple">
                <h2>Edit Product</h2>
                <a href="products.php" class="btn-secondary">Back</a>
            </div>

            <?php if ($error): ?>
                <div class="alert error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST" class="product-form">

                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Update Product</button>
            </form>
        </div>
    </div>

</main>

<?php include 'config/footer.php'; ?>