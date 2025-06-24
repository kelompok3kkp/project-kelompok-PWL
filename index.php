<?php
session_start();
require_once 'config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: pages/login.php");
    exit();
}

// Fetch today's transactions
$today = date('Y-m-d');
$sql = "SELECT t.*, w.name as wash_type, w.price, t.payment_method 
        FROM transactions t 
        JOIN wash_types w ON t.wash_type_id = w.id 
        WHERE DATE(t.transaction_date) = ? 
        AND t.employee_id = ?
        ORDER BY t.transaction_date DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$today, $_SESSION['user_id']]);
$transactions = $stmt->fetchAll();

// Calculate total earnings for today
$sql = "SELECT SUM(w.price) as total 
        FROM transactions t 
        JOIN wash_types w ON t.wash_type_id = w.id 
        WHERE DATE(t.transaction_date) = ? 
        AND t.employee_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$today, $_SESSION['user_id']]);
$total = $stmt->fetch()['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Car Wash</title>
    <link rel="stylesheet" href="./assets/css/dashboard.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 class="welcome">Selamat Datang, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
            <div class="actions">
                <a href="pages/new_transaction.php" class="btn">Transaksi Baru</a>
                <a href="pages/logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>

        <div class="summary">
            <h3>Ringkasan Hari Ini</h3>
            <p>Total Pendapatan: Rp <?php echo number_format($total, 0, ',', '.'); ?></p>
            <p>Total Transaksi: <?php echo count($transactions); ?></p>
        </div>

        <?php if (count($transactions) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Waktu</th>
                        <th>Jenis Kendaraan</th>
                        <th>Plat Nomor</th>
                        <th>Jenis Cuci</th>
                        <th>Pembayaran</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $index => $trans): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo date('H:i', strtotime($trans['transaction_date'])); ?></td>
                            <td><?php echo ucfirst($trans['vehicle_type']); ?></td>
                            <td><?php echo htmlspecialchars($trans['license_plate']); ?></td>
                            <td><?php echo htmlspecialchars($trans['wash_type']); ?></td>
                            <td><?php 
                                $payment_methods = [
                                    'cash' => 'Tunai',
                                    'debit' => 'Debit Card',
                                    'qris' => 'QRIS'
                                ];
                                echo $payment_methods[$trans['payment_method']];
                            ?></td>
                            <td>Rp <?php echo number_format($trans['price'], 0, ',', '.'); ?></td>
                            <td>
                                <a href="pages/print_receipt.php?id=<?php echo $trans['id']; ?>" class="btn" target="_blank">Cetak Struk</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <p>Belum ada transaksi hari ini</p>
                <a href="pages/new_transaction.php" class="btn">Buat Transaksi Baru</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
