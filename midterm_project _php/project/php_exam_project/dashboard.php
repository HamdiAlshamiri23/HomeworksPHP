<?php
require_once 'config/auth.php';
require_once 'config/config.php';

/* عدد المستخدمين */
$totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

/* عدد المنتجات */
$totalProducts = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();

/* جدول: كل مستخدم + عدد المنتجات التي أضافها */
$stmt = $pdo->query("
    SELECT 
        u.id,
        u.username,
        u.email,
        COUNT(p.id) AS products_count
    FROM users u
    LEFT JOIN products p ON p.user_id = u.id
    GROUP BY u.id, u.username, u.email
    ORDER BY products_count DESC, u.username ASC
");
$userStats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'config/header.php'; ?>

<div class="main">

    <div class="page-header">
        <h2>Dashboard</h2>
    </div>

    <!-- Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-title">Total Users</div>
            <div class="stat-value"><?php echo $totalUsers; ?></div>
        </div>

        <div class="stat-card">
            <div class="stat-title">Total Products</div>
            <div class="stat-value"><?php echo $totalProducts; ?></div>
        </div>
    </div>

    <!-- Table -->
    <div class="table-wrapper" style="margin-top: 20px;">
        <h3 class="section-title">Users Product Activity</h3>

        <table class="styled-table">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Products Added</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userStats as $u): ?>
                    <tr>
                        <td><?php echo (int)$u['id']; ?></td>
                        <td><?php echo htmlspecialchars($u['username']); ?></td>
                        <td><?php echo htmlspecialchars($u['email']); ?></td>
                        <td><?php echo (int)$u['products_count']; ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($userStats)): ?>
                    <tr>
                        <td colspan="4" class="no-data">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'config/footer.php'; ?>


