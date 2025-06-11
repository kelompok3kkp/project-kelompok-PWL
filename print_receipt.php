<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

// Fetch transaction details
$sql = "SELECT t.*, w.name as wash_type, w.price, e.name as employee_name, t.payment_method 
        FROM transactions t 
        JOIN wash_types w ON t.wash_type_id = w.id 
        JOIN employees e ON t.employee_id = e.id 
        WHERE t.id = ? AND t.employee_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);
$transaction = $stmt->fetch();

if (!$transaction) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - Car Wash</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f0f8ff;
            margin: 0;
            padding: 20px;
        }
        .receipt {
            max-width: 300px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        .receipt-body {
            margin-bottom: 20px;
        }
        .receipt-footer {
            text-align: center;
            border-top: 1px dashed #000;
            padding-top: 10px;
            font-size: 0.9em;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .amount {
            font-weight: bold;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 5px;
            background-color: #4169e1;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #1e90ff;
        }
        @media print {
            body {
                background: none;
                padding: 0;
            }
            .receipt {
                box-shadow: none;
            }
            .btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="receipt-header">
            <h2 style="margin: 0;">Car Wash</h2>
            <p style="margin: 5px 0;">Struk Pembayaran</p>
            <p style="margin: 5px 0; font-size: 0.9em;">
                <?php echo date('d/m/Y H:i', strtotime($transaction['transaction_date'])); ?>
            </p>
        </div>

        <div class="receipt-body">
            <div class="detail-row">
                <span>No. Transaksi:</span>
                <span>#<?php echo str_pad($transaction['id'], 4, '0', STR_PAD_LEFT); ?></span>
            </div>
            <div class="detail-row">
                <span>Karyawan:</span>
                <span><?php echo htmlspecialchars($transaction['employee_name']); ?></span>
            </div>
            <div class="detail-row">
                <span>Kendaraan:</span>
                <span><?php echo ucfirst($transaction['vehicle_type']); ?></span>
            </div>
            <div class="detail-row">
                <span>Plat Nomor:</span>
                <span><?php echo htmlspecialchars($transaction['license_plate']); ?></span>
            </div>
            <div class="detail-row">
                <span>Jenis Cuci:</span>
                <span><?php echo htmlspecialchars($transaction['wash_type']); ?></span>
            </div>
            <div class="detail-row">
                <span>Pembayaran:</span>
                <span><?php 
                    $payment_methods = [
                        'cash' => 'Tunai',
                        'debit' => 'Debit Card',
                        'qris' => 'QRIS'
                    ];
                    echo $payment_methods[$transaction['payment_method']];
                ?></span>
            </div>
            <div class="detail-row" style="margin-top: 10px;">
                <span class="amount">Total:</span>
                <span class="amount">Rp <?php echo number_format($transaction['price'], 0, ',', '.'); ?></span>
            </div>
        </div>

        <div class="receipt-footer">
            <p style="margin: 5px 0;">Terima kasih atas kunjungan Anda!</p>
            <p style="margin: 5px 0;">Silahkan datang kembali</p>
        </div>

        <a href="dashboard.php" class="btn">Kembali ke Dashboard</a>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
