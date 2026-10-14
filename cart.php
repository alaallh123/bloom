<?php
include 'db.php';

// بداية الجلسة (Session) لحفظ المنتجات المضافة للسلة
session_start();

// 1. معالجة تعديل الكمية أو حذف المنتج
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $index = intval($_POST['cart_index']);

    if (isset($_SESSION['cart'][$index])) {
        // زيادة الكمية (+)
        if ($_POST['action'] === 'increase') {
            $_SESSION['cart'][$index]['quantity']++;
        }
        // إنقاص الكمية (-)
        elseif ($_POST['action'] === 'decrease') {
            if ($_SESSION['cart'][$index]['quantity'] > 1) {
                $_SESSION['cart'][$index]['quantity']--;
            }
        }
        // حذف المنتج فوراً بدون تأكيد المتصفح المزعج
        elseif ($_POST['action'] === 'remove') {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }

    header("Location: cart.php");
    exit();
}

// 2. إضافة المنتج للسلة إذا تم الضغط على Add to cart من صفحة المنتج
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id']) && !isset($_POST['action'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $size = isset($_POST['selected_size']) ? $_POST['selected_size'] : '';
    $pot = isset($_POST['selected_pot']) ? $_POST['selected_pot'] : '';

    $cart_item = [
        'product_id' => $product_id,
        'quantity' => $quantity,
        'size' => $size,
        'pot' => $pot
    ];
    
    $_SESSION['cart'][] = $cart_item;
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart | Bloom</title>

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
            background-color: var(--brand-bg);
            color: var(--brand-text);
            margin: 0;
            padding: 0;
        }

        .main-header {
            background-color: var(--brand-green);
            padding: 12px 0;
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

        .btn-bloom {
            background-color: var(--brand-green);
            color: #ffffff;
            border-radius: 8px;
            font-weight: 500;
            padding: 0.8rem 1.5rem;
            border: none;
            transition: background-color 0.2s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-bloom:hover {
            background-color: #2c3f32;
            color: #ffffff;
        }

        .cart-item-img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            background-color: #fff;
            border-radius: 8px;
            padding: 5px;
            border: 1px solid var(--brand-divider);
        }

        .btn-remove {
            color: #dc3545;
            background: none;
            border: none;
            font-size: 1.1rem;
            transition: opacity 0.2s;
            padding: 0;
            cursor: pointer;
        }

        .btn-remove:hover {
            color: #a71d2a;
            opacity: 0.8;
        }

        .qty-btn {
            border: 1px solid #ced4da;
            background: #fff;
            padding: 2px 10px;
            font-weight: bold;
            color: var(--brand-green);
            border-radius: 4px;
            transition: all 0.2s;
        }

        .qty-btn:hover {
            background: var(--brand-sage-light);
            border-color: var(--brand-green);
        }
    </style>
</head>
<body>

    <!-- الهيدر -->
    <header class="main-header">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand-custom" href="index.php">Bloom</a>
            <a href="index.php" class="text-white text-decoration-none small"><i class="bi bi-arrow-left"></i> Continue Shopping</a>
        </div>
    </header>

    <!-- محتوى السلة -->
    <div class="container my-5" style="max-width: 850px;">
        <h2 class="brand-title-font mb-4">Your Shopping Cart</h2>

        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="card border-0 shadow-sm p-4 mb-4" style="background-color: #ffffff; border-radius: 12px;">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr class="text-muted small border-bottom">
                                <th class="pb-3">Product</th>
                                <th class="pb-3">Details</th>
                                <th class="pb-3 text-center">Quantity</th>
                                <th class="pb-3 text-end">Price</th>
                                <th class="pb-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_price = 0;
                            foreach ($_SESSION['cart'] as $index => $item):
                                $pid = $item['product_id'];
                                $query = "SELECT * FROM products WHERE product_id = $pid";
                                $res = $conn->query($query);
                                if ($res && $res->num_rows > 0) {
                                    $prod = $res->fetch_assoc();
                                    $subtotal = $prod['price'] * $item['quantity'];
                                    $total_price += $subtotal;
                            ?>
                                <tr class="border-bottom">
                                    <td class="py-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="<?php echo htmlspecialchars($prod['image']); ?>" alt="" class="cart-item-img">
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark"><?php echo htmlspecialchars($prod['title']); ?></h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-secondary small">
                                        <div><strong>Size:</strong> <?php echo htmlspecialchars($item['size']); ?></div>
                                        <div><strong>Pot:</strong> <?php echo htmlspecialchars($item['pot']); ?></div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <!-- زيادة ونقصان الكمية -->
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <form action="cart.php" method="POST" class="d-inline">
                                                <input type="hidden" name="action" value="decrease">
                                                <input type="hidden" name="cart_index" value="<?php echo $index; ?>">
                                                <button type="submit" class="qty-btn">-</button>
                                            </form>

                                            <span class="fw-bold px-2"><?php echo $item['quantity']; ?></span>

                                            <form action="cart.php" method="POST" class="d-inline">
                                                <input type="hidden" name="action" value="increase">
                                                <input type="hidden" name="cart_index" value="<?php echo $index; ?>">
                                                <button type="submit" class="qty-btn">+</button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="py-3 text-end fw-bold text-success">
                                        <?php echo number_format($subtotal, 2); ?> SAR
                                    </td>
                                    <td class="py-3 text-center">
                                        <!-- حذف المنتج مباشرة بدون رسالة المتصفح المنبثقة -->
                                        <form action="cart.php" method="POST" class="m-0">
                                            <input type="hidden" name="action" value="remove">
                                            <input type="hidden" name="cart_index" value="<?php echo $index; ?>">
                                            <button type="submit" class="btn-remove" title="Remove Item">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php 
                                }
                            endforeach; 
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- المجموع الكلي -->
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <h5 class="m-0 brand-title-font">Total Amount:</h5>
                    <h4 class="m-0 text-success fw-bold"><?php echo number_format($total_price, 2); ?> SAR</h4>
                </div>

                <div class="mt-4 text-end">
                    <a href="checkout.php" class="btn btn-bloom px-5 py-3">Proceed to Checkout</a>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5 bg-white rounded shadow-sm border-0">
                <i class="bi bi-bag-x display-4 text-muted mb-3"></i>
                <p class="text-muted fs-5">Your cart is empty.</p>
                <a href="index.php" class="btn btn-bloom mt-2">Explore Plants</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>