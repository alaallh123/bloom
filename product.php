<?php
include 'db.php';
session_start();

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 1;

$sql = "SELECT * FROM products WHERE product_id = $product_id";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

// حساب عدد المنتجات في السلة
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += isset($item['quantity']) ? intval($item['quantity']) : 1;
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['title'] ?? 'Product Details'); ?> | Bloom</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        :root {
            --brand-bg: #FAF8F5; 
            --brand-green: #3B5343; 
            --brand-divider: #E7E0D6; 
            --brand-text: #2f3e35;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--brand-bg);
            color: var(--brand-text);
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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

        .product-container {
            max-width: 750px;
            margin: 0 auto;
        }

        .product-img-main {
            width: 100%;
            max-height: 400px;
            object-fit: contain;
            background: #ffffff;
            border-radius: 16px;
            padding: 20px;
            border: 1px solid var(--brand-divider);
            display: block;
            margin: 0 auto;
        }

        .option-group-title {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--brand-text);
            margin-bottom: 8px;
            margin-top: 15px;
        }

        .option-box {
            display: block;
            background: #ffffff;
            border: 1px solid var(--brand-divider);
            border-radius: 6px;
            padding: 10px 15px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .option-box input[type="radio"] {
            margin-right: 10px;
        }

        .option-box:hover {
            border-color: var(--brand-green);
            background-color: #f7f9f7;
        }

        .btn-bloom {
            background-color: var(--brand-green);
            color: #ffffff;
            border-radius: 8px;
            font-weight: 500;
            border: none;
            padding: 12px;
        }

        .btn-bloom:hover {
            background-color: #2c3f32;
            color: #ffffff;
        }

        .site-footer {
            background-color: var(--brand-green);
            color: #FAF8F5;
            padding: 40px 0 20px 0;
            margin-top: auto;
        }
    </style>
</head>
<body>

    <!-- الهيدر مع عداد السلة -->
    <header class="main-header sticky-top">
        <nav class="navbar navbar-expand-lg navbar-dark p-0">
            <div class="container">
                <a class="navbar-brand-custom" href="index.php">Bloom</a>
                <div class="collapse navbar-collapse show">
                    <ul class="navbar-nav ms-auto align-items-center gap-3">
                        <li class="nav-item"><a class="nav-link text-white" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link text-white" href="advisor.php">Plant Advisor</a></li>
                        <li class="nav-item">
                            <a class="nav-link text-white position-relative" href="cart.php">
                                <i class="bi bi-bag"></i> Cart
                                <?php if ($cart_count > 0): ?>
                                    <span class="position-absolute top-25 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                        <?php echo $cart_count; ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- المحتوى -->
    <main class="container my-5">
        <?php if ($product): ?>
            <div class="product-container">
                
                <!-- 1. صورة النبتة في الأعلى -->
                <div class="mb-4 text-center">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Plant" class="product-img-main">
                </div>

                <!-- 2. وصف النبتة تحت الصورة مباشرة -->
                <div class="mb-4 p-4 rounded-3" style="background-color: #ffffff; border: 1px solid var(--brand-divider);">
                    <h4 class="brand-title-font fs-5 mb-2">Description</h4>
                    <p class="text-muted small m-0" style="line-height: 1.6;"><?php echo htmlspecialchars($product['description']); ?></p>
                </div>

                <!-- 3. تفاصيل المنتج والخيارات والكمية وزر الإضافة -->
                <div class="p-4 rounded-3 bg-white border">
                    <h2 class="brand-title-font fs-4 mb-1"><?php echo htmlspecialchars($product['title']); ?></h2>
                    <h3 class="text-success fw-bold mb-4 fs-5"><?php echo number_format($product['price'], 2); ?> SAR</h3>

                    <form action="cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">

                        <!-- المقاس Size -->
                        <div class="option-group-title">Size</div>
                        <label class="option-box">
                            <input type="radio" name="size" value="3"> 3" Pot
                        </label>
                        <label class="option-box">
                            <input type="radio" name="size" value="4" checked> 4" Pot
                        </label>
                        <label class="option-box">
                            <input type="radio" name="size" value="6"> 6" Pot
                        </label>
                        <label class="option-box">
                            <input type="radio" name="size" value="8"> 8" Pot
                        </label>

                        <!-- الأصيص Pot -->
                        <div class="option-group-title">Pot</div>
                        <label class="option-box">
                            <input type="radio" name="pot" value="Nursery" checked> Nursery Pot
                        </label>
                        <label class="option-box">
                            <input type="radio" name="pot" value="Black Cylinder"> Black Cylinder
                        </label>
                        <label class="option-box">
                            <input type="radio" name="pot" value="White Cylinder"> White Cylinder
                        </label>
                        <label class="option-box">
                            <input type="radio" name="pot" value="Terra Cotta"> Terra Cotta Eco Pot
                        </label>
                        <label class="option-box">
                            <input type="radio" name="pot" value="Black Eco"> Black Eco Pot
                        </label>
                        <label class="option-box">
                            <input type="radio" name="pot" value="Cow Pot"> Cow Pot
                        </label>

                        <!-- الكمية -->
                        <div class="option-group-title">Quantity</div>
                        <div class="input-group mb-4" style="width: 140px;">
                            <button class="btn btn-outline-secondary px-2" type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">-</button>
                            <input type="number" name="quantity" class="form-control text-center" value="0" min="0">
                            <button class="btn btn-outline-secondary px-2" type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">+</button>
                        </div>

                        <!-- زر الإضافة -->
                        <button type="submit" class="btn btn-bloom w-100">Add to cart</button>
                    </form>
                </div>

            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <p class="text-muted">Product not found.</p>
            </div>
        <?php endif; ?>
    </main>

    <!-- الفوتر -->
    <footer class="site-footer">
        <div class="container text-center">
            <p class="mb-0 text-light opacity-75 small">&copy; <?php echo date("Y"); ?> Bloom Store. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>