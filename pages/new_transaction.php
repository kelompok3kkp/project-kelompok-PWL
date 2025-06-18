<?php
session_start();
require_once '../config/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch wash types from database
$sql = "SELECT * FROM wash_types ORDER BY price";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$wash_types = $stmt->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_type = $_POST['vehicle_type'];
    $license_plate = $_POST['license_plate'];
    $wash_type_id = $_POST['wash_type_id'];
    $payment_method = $_POST['payment_method'];
    
    $sql = "INSERT INTO transactions (employee_id, vehicle_type, license_plate, wash_type_id, payment_method, transaction_date) 
            VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$_SESSION['user_id'], $vehicle_type, $license_plate, $wash_type_id, $payment_method])) {
        $transaction_id = $pdo->lastInsertId();
        header("Location: print_receipt.php?id=" . $transaction_id);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Baru - Car Wash</title>
    <link rel="stylesheet" href="../assets/css/transaction.css">
    <script src="../assets/js/transaction.js" defer></script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Transaksi Baru</h2>
        </div>

        <form method="POST" id="transactionForm">
            <div class="form-group">
                <label>Jenis Kendaraan</label>
                <select name="vehicle_type" required>
                    <option value="">Pilih Jenis Kendaraan</option>
                    <option value="motor">Motor</option>
                    <option value="mobil">Mobil</option>
                </select>
            </div>

            <div class="form-group">
                <label>Plat Nomor</label>
                <input type="text" name="license_plate" required placeholder="Contoh: B 1234 ABC">
            </div>

            <div class="form-group">
                <label>Metode Pembayaran</label>
                <select name="payment_method" required>
                    <option value="">Pilih Metode Pembayaran</option>
                    <option value="cash">Tunai</option>
                    <option value="debit">Debit Card</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>

            <div class="form-group">
                <label>Jenis Cuci</label>
                <?php foreach ($wash_types as $type): ?>
                    <div class="wash-type-card">
                        <input type="radio" name="wash_type_id" value="<?php echo $type['id']; ?>" required>
                        <span><?php echo htmlspecialchars($type['name']); ?></span>
                        <span class="wash-type-price">Rp <?php echo number_format($type['price'], 0, ',', '.'); ?></span>
                        <p style="margin: 5px 0 0; color: #666; font-size: 0.9em;">
                            <?php echo htmlspecialchars($type['description']); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="actions">
                <a href="../index.php" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn">Simpan & Cetak Struk</button>
            </div>
        </form>
    </div>
</body>
</html>
