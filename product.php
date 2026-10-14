<?php
include 'db.php';

// الحصول على معرف المنتج
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 1;

// جلب بيانات المنتج من قاعدة البيانات
$sql = "SELECT * FROM products WHERE product_id = $product_id";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    $sql_default = "SELECT * FROM products LIMIT 1";
    $result_default = $conn->query($sql_default);
    $product = $result_default->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['title']); ?> | Bloom</title>

    <!-- Google Fonts & Bootstrap 5 & Bootstrap Icons -->
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
            background-color: #ffffff;
            color: var(--brand-text);
            margin: 0;
            padding: 0;
        }

        /* شريط التنبيه العلوي (Free Shipping) */
        .top-announcement-bar {
            background-color: #2D4033;
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 6px 0;
            text-align: center;
            letter-spacing: 0.5px;
        }

        /* الهيدر العلوي والإيقونات */
        .main-header {
            background-color: var(--brand-green);
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-brand-custom {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #FAF8F5 !important;
            text-decoration: none;
        }

        .header-icon-link {
            color: #FAF8F5;
            font-size: 1.3rem;
            margin-left: 1rem;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .header-icon-link:hover {
            color: #ffffff;
            opacity: 0.8;
        }

        .sub-nav {
            background-color: var(--brand-green);
            padding: 8px 0 12px 0;
            border-bottom: 1px solid #2c3f32;
        }

        .sub-nav .nav-link {
            color: #FAF8F5 !important;
            font-size: 0.95rem;
            font-weight: 500;
            padding: 0 12px;
            opacity: 0.9;
            text-decoration: none;
        }

        .sub-nav .nav-link:hover {
            opacity: 1;
        }

        .brand-title-font {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--brand-text);
        }

        .price-tag {
            color: var(--brand-text);
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            font-size: 1.5rem;
        }

        .btn-bloom {
            background-color: var(--brand-green);
            color: #ffffff;
            border-radius: 8px;
            font-weight: 500;
            padding: 0.8rem 1.5rem;
            border: none;
            transition: background-color 0.2s;
        }

        .btn-bloom:hover {
            background-color: #2c3f32;
            color: #ffffff;
        }

        /* مربعات الخيارات */
        .option-box {
            border: 1px solid #ced4da;
            padding: 12px 15px;
            text-align: left;
            background-color: #ffffff;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.95rem;
            color: #495057;
            user-select: none;
        }

        .option-box:hover {
            border-color: var(--brand-green);
            background-color: var(--brand-sage-light);
        }

        .option-box.selected {
            border-color: var(--brand-green) !important;
            background-color: var(--brand-sage-light) !important;
            color: var(--brand-green);
            font-weight: 600;
        }

        .product-main-img {
            width: 100%;
            max-height: 480px;
            object-fit: contain;
            background-color: var(--brand-bg);
            border-radius: 12px;
            padding: 20px;
        }
    </style>
</head>
<body>

    <!-- 1. شريط الشحن المجاني العلوي -->
    <div class="top-announcement-bar">
        🌿 FREE SHIPPING NATIONWIDE on orders over 100 SAR
    </div>

    <!-- 2. الهيدر الرئيسي مع إيقونات الحساب والسلة -->
    <header class="main-header">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand-custom" href="index.php">Bloom</a>

            <div class="d-flex align-items-center">
                <a href="#" class="header-icon-link" title="Search"><i class="bi bi-search"></i></a>
                <a href="account.php" class="header-icon-link" title="Account"><i class="bi bi-person"></i></a>
                <a href="cart.php" class="header-icon-link position-relative" title="Cart">
                    <i class="bi bi-bag"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                        1
                    </span>
                </a>
            </div>
        </div>
    </header>

    <!-- 3. شريط التصفح الفرعي -->
    <div class="sub-nav">
        <div class="container">
            <div class="d-flex py-1">
                <a href="index.php" class="nav-link">Home</a>
            </div>
        </div>
    </div>

    <!-- تفاصيل المنتج -->
    <div class="container my-5" style="max-width: 600px;">
        
        <form action="cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
            <input type="hidden" name="selected_size" id="selected_size" value="3' Pot">
            <input type="hidden" name="selected_pot" id="selected_pot" value="Nursery Pot">

            <!-- صورة المنتج -->
            <div class="mb-4 text-center">
                <?php if (!empty($product['image'])): ?>
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="product-main-img">
                <?php else: ?>
                    <div class="p-5 bg-light text-center border rounded text-muted">No Image Available</div>
                <?php endif; ?>
            </div>

            <!-- اسم المنتج -->
            <h1 class="brand-title-font fs-2 mb-2"><?php echo htmlspecialchars($product['title']); ?></h1>
            
            <!-- السعر -->
            <div class="price-tag mb-4"><?php echo number_format($product['price'], 2); ?> SAR</div>

            <hr class="my-3" style="border-color: var(--brand-divider);">

            <!-- خيارات Size -->
            <div class="mb-4">
                <label class="form-label fw-bold text-dark small mb-2">Size</label>
                <div class="d-flex flex-column gap-2" id="size-options">
                    <div class="option-box selected" data-value="3' Pot">3" Pot</div>
                    <div class="option-box" data-value="4' Pot">4" Pot</div>
                    <div class="option-box" data-value="6' Pot">6" Pot</div>
                    <div class="option-box" data-value="8' Pot">8" Pot</div>
                </div>
            </div>

            <!-- خيارات Pot -->
            <div class="mb-4">
                <label class="form-label fw-bold text-dark small mb-2">Pot</label>
                <div class="d-flex flex-column gap-2" id="pot-options">
                    <div class="option-box selected" data-value="Nursery Pot">Nursery Pot</div>
                    <div class="option-box" data-value="Black Cylinder">Black Cylinder</div>
                    <div class="option-box" data-value="White Cylinder">White Cylinder</div>
                    <div class="option-box" data-value="Terra Cotta Eco Pot">Terra Cotta Eco Pot</div>
                    <div class="option-box" data-value="Black Eco Pot">Black Eco Pot</div>
                    <div class="option-box" data-value="Cow Pot">Cow Pot</div>
                </div>
            </div>

            <!-- الكمية وزر الإضافة للسلة -->
            <div class="d-flex gap-3 align-items-center mb-4">
                <div class="input-group" style="width: 130px;">
                    <button class="btn btn-outline-secondary" type="button" id="minus-btn">-</button>
                    <input type="text" name="quantity" id="quantity-input" class="form-control text-center" value="1" readonly>
                    <button class="btn btn-outline-secondary" type="button" id="plus-btn">+</button>
                </div>
                <button type="submit" class="btn btn-bloom flex-grow-1 py-3">Add to cart</button>
            </div>
        </form>

        <!-- الوصف -->
        <div class="mt-5 pt-4 border-top">
            <h4 class="brand-title-font fs-4 mb-3">Description</h4>
            <p class="text-secondary" style="line-height: 1.8; font-size: 0.95rem;">
                <?php echo htmlspecialchars($product['description']); ?>
            </p>
        </div>

    </div>

    <script>
        const sizeBoxes = document.querySelectorAll('#size-options .option-box');
        const selectedSizeInput = document.getElementById('selected_size');
        sizeBoxes.forEach(box => {
            box.addEventListener('click', () => {
                sizeBoxes.forEach(b => b.classList.remove('selected'));
                box.classList.add('selected');
                selectedSizeInput.value = box.getAttribute('data-value');
            });
        });

        const potBoxes = document.querySelectorAll('#pot-options .option-box');
        const selectedPotInput = document.getElementById('selected_pot');
        potBoxes.forEach(box => {
            box.addEventListener('click', () => {
                potBoxes.forEach(b => b.classList.remove('selected'));
                box.classList.add('selected');
                selectedPotInput.value = box.getAttribute('data-value');
            });
        });

        const qtyInput = document.getElementById('quantity-input');
        document.getElementById('plus-btn').addEventListener('click', () => {
            let val = parseInt(qtyInput.value);
            qtyInput.value = val + 1;
        });
        document.getElementById('minus-btn').addEventListener('click', () => {
            let val = parseInt(qtyInput.value);
            if (val > 1) {
                qtyInput.value = val - 1;
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>