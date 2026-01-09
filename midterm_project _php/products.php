<?php
require_once 'config/auth.php';
require_once 'config/config.php';

$stmt = $pdo->query("
    SELECT 
        products.*,
        users.username
    FROM products
    JOIN users ON products.user_id = users.id
    ORDER BY products.created_at DESC
");
$products = $stmt->fetchAll();

?>

<?php include 'config/header.php'; ?>
<div class="main">

    <div class="page-header">
        <h2>Products List</h2>
        <a href="add_product.php" class="btn-primary">Add New Product</a>
    </div>

    <div class="table-wrapper">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Added By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo $product['quantity']; ?></td>
                        <td><?php echo htmlspecialchars($product['username']); ?></td>
                        <td class="actions">
                            <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn-edit">Edit</a>
                            <a href="delete_product.php?id=<?php echo $product['id']; ?>"
                                class="btn-delete"
                                onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="5" class="no-data">No products found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'config/footer.php'; ?>