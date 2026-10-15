<?php
include 'db.php';
session_start();

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = null;

// معرفة اسم العمود الرئيسي تلقائياً في قاعدة البيانات
$check_col =$conn->query("SHOW COLUMNS FROM products");
$first_col = "id";
if ($check_col &&$check_col->num_rows > 0) {
    $row =$check_col->fetch_assoc();
    $first_col =$row['Field'];
}

if ($product_id > 0 && !empty($first_col)) {
    $stmt =$conn->prepare("SELECT * FROM products WHERE `$first_col` = ?");
    if ($stmt) {$stmt->bind_param("i", $product_id);$stmt->execute();
        $res =$stmt->get_result();
        if ($res &&$res->num_rows > 0) {
            $product =$res->fetch_assoc();
        }
        $stmt->close();
    }
}

if (!$product) {
    $res =$conn->query("SELECT * FROM products ORDER BY 1 DESC LIMIT 1");
    if ($res &&$res->num_rows > 0) {
        $product =$res->fetch_assoc();
    }
}

// معالجة إضافة المنتج إلى السلة عند الضغط على زر Add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart']) &&$product) {
    $size =$_POST['size'] ?? '4" Pot';
    $pot =$_POST['pot'] ?? 'Nursery Pot';
    $qty = intval($_POST['qty'] ?? 1);
    
    if (!isset($_SESSION['cart'])) {$_SESSION['cart'] = [];
    }
    
    $cart_key =$product_id . '_' . $size . '_' .$pot;
    if (isset($_SESSION['cart'][$cart_key])) {$_SESSION['cart'][$cart_key]['qty'] +=$qty;
    } else {
        $_SESSION['cart'][$cart_key] = [
            'id' => $product_id,
            'title' => $product['title'],
            'price' => $product['price'],
            'image' => $product['image'],
            'size' => $size,
            'pot' => $pot,
            'qty' => $qty
        ];
    }
    
    header("Location: product.php?id=" . $product_id);
    exit();
}

// حساب إجمالي المنتجات في السلة لعرضها بالعداد
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as$item) {
        $cart_count +=$item['qty'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product ? htmlspecialchars($product['title']) : 'Product Details'; ?> | Bloom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-green: #3B5343;
            --brand-dark: #2C3F32;
            --brand-beige: #FAF8F5;
        }
        body { background-color: var(--brand-beige); color: var(--brand-dark); font-family: 'Inter', sans-serif; }
        
        .navbar-bloom {
            background-color: var(--brand-green) !important;
            padding: 14px 0;
        }
        .navbar-brand-custom {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: #FAF8F5 !important;
            text-decoration: none;
        }
        .nav-link-custom {
            font-size: 0.85rem;
            font-weight: 600;
            color: #FAF8F5 !important;
            text-decoration: none;
        }
        .nav-icon {
            font-size: 1.25rem;
            color: #FAF8F5;
            margin-left: 18px;
            text-decoration: none;
            position: relative;
        }
        .nav-icon:hover { color: #D1C9BF; }

        .details-wrapper {
            max-width: 850px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .product-img-card {
            background: #FFFFFF;
            border-radius: 16px;
            border: 1px solid #EAE5DD;
            padding: 25px;
            text-align: center;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }
        .product-img-card img {
            max-width: 100%;
            max-height: 350px;
            object-fit: contain;
            border-radius: 12px;
        }

        .info-card {
            background: #FFFFFF;
            border-radius: 12px;
            border: 1px solid #EAE5DD;
            padding: 20px 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }

        .option-box {
            background: #FFFFFF;
            border: 1px solid #EAE5DD;
            border-radius: 10px;
            padding: 12px 20px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .btn-add-to-cart {
            background-color: #4A5D4E;
            color: #FFFFFF;
            border: none;
            border-radius: 8px;
            padding: 14px;
            width: 100%;
            font-weight: 600;
            font-size: 0.95rem;
            margin-top: 20px;
            transition: background 0.2s;
        }
        .btn-add-to-cart:hover { background-color: var(--brand-dark); color: #FFFFFF; }
        
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
            max-width: 140px;
        }
    </style>
</head>
<body>

<!-- Green Header Bar -->
<nav class="navbar navbar-expand-lg navbar-bloom">
    <div class="container d-flex align-items-center justify-content-between">
        <a class="navbar-brand navbar-brand-custom m-0" href="index.php">Bloom</a>
        <div class="d-flex align-items-center">
            <a href="index.php" class="nav-link-custom me-3"><i class="bi bi-house me-1"></i> Home</a>
            <a href="#" class="nav-icon" title="Search"><i class="bi bi-search"></i></a>
            <a href="login.php" class="nav-icon" title="Account"><i class="bi bi-person"></i></a>
            <!-- Cart Icon with Dynamic Badge Counter -->
            <a href="cart.php" class="nav-icon" title="Cart">
                <i class="bi bi-bag"></i>
                <?php if ($cart_count > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                        <?php echo $cart_count; ?>
                    </span>
                <?php endif; ?>
            </a>
        </div>
    </div>
</nav>

<div class="details-wrapper">
    <?php if ($product): ?>
        
        <div class="product-img-card">
            <img src="<?php echo htmlspecialchars($product['image'] ?? 'default.jpeg'); ?>" alt="Product Image">
        </div>

        <div class="info-card">
            <h6 class="fw-bold mb-2">Description</h6>
            <p class="text-muted small mb-0"><?php echo nl2br(htmlspecialchars($product['description'] ?? 'No description provided.')); ?></p>
        </div>

        <div class="info-card">
            <h3 style="font-family: 'Playfair Display', serif;" class="fw-bold mb-1"><?php echo htmlspecialchars($product['title'] ?? 'Product'); ?></h3>
            <h5 class="text-success fw-bold mb-0"><?php echo number_format($product['price'] ?? 0, 2); ?> SAR</h5>
        </div>

        <form method="POST">
            <!-- Size Options -->
            <div class="mb-4">
                <h6 class="fw-bold mb-2 text-muted small">Size</h6>
                <label class="option-box w-100"><input type="radio" name="size" value="3&quot; Pot"> 3" Pot</label>
                <label class="option-box w-100"><input type="radio" name="size" value="4&quot; Pot" checked> 4" Pot</label>
                <label class="option-box w-100"><input type="radio" name="size" value="6&quot; Pot"> 6" Pot</label>
                <label class="option-box w-100"><input type="radio" name="size" value="8&quot; Pot"> 8" Pot</label>
            </div>

            <!-- Pot Options -->
            <div class="mb-4">
                <h6 class="fw-bold mb-2 text-muted small">Pot</h6>
                <label class="option-box w-100"><input type="radio" name="pot" value="Nursery Pot" checked> Nursery Pot</label>
                <label class="option-box w-100"><input type="radio" name="pot" value="Black Cylinder"> Black Cylinder</label>
                <label class="option-box w-100"><input type="radio" name="pot" value="White Cylinder"> White Cylinder</label>
                <label class="option-box w-100"><input type="radio" name="pot" value="Terra Cotta Eco Pot"> Terra Cotta Eco Pot</label>
                <label class="option-box w-100"><input type="radio" name="pot" value="Black Eco Pot"> Black Eco Pot</label>
                <label class="option-box w-100"><input type="radio" name="pot" value="Cow Pot"> Cow Pot</label>
            </div>

            <!-- Quantity & Add to Cart -->
            <div class="mb-5">
                <h6 class="fw-bold mb-2 text-muted small">Quantity</h6>
                <div class="quantity-control mb-3">
                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="var q=document.getElementById('qty'); if(q.value>1) q.value--;">-</button>
                    <input type="number" id="qty" name="qty" value="1" min="1" class="form-control text-center form-control-sm">
                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="var q=document.getElementById('qty'); q.value++;">+</button>
                </div>

                <button type="submit" name="add_to_cart" class="btn btn-add-to-cart"><i class="bi bi-bag-plus me-2"></i> Add to cart</button>
            </div>
        </form>

    <?php else: ?>
        <div class="text-center py-5 info-card">
            <h4 class="text-danger mb-3">No Product Found</h4>
            <p class="text-muted">Please add a product first from the admin page.</p>
            <a href="add_product.php" class="btn btn-dark btn-sm rounded-pill px-4 mt-2">Add Product Now</a>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>