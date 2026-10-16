<?php
include 'db.php';
session_start();

// إذا كانت السلة فارغة، توجيه المستخدم لصفحة المنتجات
if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // استقبال بيانات العميل والدفع
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $payment_method = $_POST['payment_method'];

    // تفريغ السلة بعد نجاح الطلب
    unset($_SESSION['cart']);

    // توجيه لصفحة النجاح مع رسالة
    header("Location: checkout.php?success=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Bloom</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        :root {
            --brand-bg: #FAF8F5; 
            --brand-green: #3B5343; 
            --brand-sage-light: #F2F5F2; 
            --brand-divider: #E7E0D6; 
            --brand-text: #2f3e35;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--brand-green);
            color: var(--brand-text);
            margin: 0;
            padding: 0;
        }

        .main-header {
            background-color: var(--brand-green);
            padding: 12px 0;
            border-bottom: 1px solid #ffffff;
        }

        .navbar-brand-custom {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #FAF8F5 !important;
            text-decoration: none;
        }

        .brand-title-font {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--brand-green);
        }

        .checkout-main-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: #FAF8F5;
        }

        .btn-bloom {
            background-color: var(--brand-green);
            color: #ffffff;
            border-radius: 8px;
            font-weight: 500;
            padding: 0.8rem 1.5rem;
            border: none;
            transition: background-color 0.2s;
            width: 100%;
        }

        .btn-bloom:hover {
            background-color: #2c3f32;
            color: #ffffff;
        }

        .card-box {
            background-color: #ffffff;
            border: 1px solid var(--brand-divider);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

    <header class="main-header">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand-custom" href="index.php">Bloom</a>
            <a href="cart.php" class="text-white text-decoration-none small"><i class="bi bi-arrow-left"></i> Back to Cart</a>
        </div>
    </header>

    <div class="container my-5" style="max-width: 700px;">
        <h2 class="checkout-main-title mb-4 text-center">Checkout & Payment</h2>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="card-box text-center py-5">
                <i class="bi bi-check-circle-fill text-success display-3 mb-3"></i>
                <h3 class="brand-title-font mb-2">Order Placed Successfully!</h3>
                <p class="text-secondary mb-4">Thank you for shopping with Bloom. Your green companions are on their way to you!</p>
                <a href="index.php" class="btn btn-bloom mx-auto" style="max-width: 200px;">Back to Home</a>
            </div>
        <?php else: ?>
            <div class="card-box">
                <form action="checkout.php" method="POST">
                    
                    <!-- معلومات الشحن -->
                    <h5 class="brand-title-font mb-3">Shipping Information</h5>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Full Name</label>
                        <input type="text" name="fullname" class="form-control" required placeholder="Sara Ahmed">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Email Address</label>
                        <input type="email" name="email" class="form-control" required placeholder="sara@example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Shipping Address</label>
                        <input type="text" name="address" class="form-control" required placeholder="Street name, House/Apartment No.">
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">City</label>
                        <input type="text" name="city" class="form-control" required placeholder="Riyadh">
                    </div>

                    <hr class="my-4" style="border-color: var(--brand-divider);">

                    <!-- طريقة الدفع -->
                    <h5 class="brand-title-font mb-3">Payment Method</h5>
                    <div class="mb-3">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="card" value="Credit Card" checked>
                            <label class="form-check-label" for="card">
                                <i class="bi bi-credit-card me-2"></i> Credit Card / Mada / Apple Pay
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="Cash on Delivery">
                            <label class="form-check-label" for="cod">
                                <i class="bi bi-cash me-2"></i> Cash on Delivery (COD)
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">Card Details (Simulated)</label>
                        <input type="text" class="form-control" placeholder="4111 2222 3333 4444">
                    </div>

                    <button type="submit" class="btn btn-bloom py-3">Place Order Now</button>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>