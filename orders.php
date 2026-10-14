<?php
include 'db.php';
session_start();

$orders = $conn->query("SELECT * FROM orders ORDER BY order_id DESC");
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation | Bloom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root { --brand-bg: #FAF8F5; --brand-green: #3B5343; }
        body { background-color: var(--brand-bg); font-family: 'Inter', sans-serif; }
    </style>
</head>
<body>
    <div class="container my-5 text-center" style="max-width: 650px;">
        <div class="card p-5 border-0 shadow-sm rounded-4">
            <div class="text-success display-1 mb-3"><i class="bi bi-check-circle-fill"></i></div>
            <h2 class="fw-bold text-success mb-2">Thank You for Your Order!</h2>
            <p class="text-secondary mb-4">تم تسجيل طلبك بنجاح وسنقوم بتجهيز نباتاتك الجميلة وشحنها لك قريباً.</p>
            
            <a href="index.php" class="btn btn-success px-4 py-2 rounded-3">Back to Store</a>
        </div>
    </div>
</body>
</html>