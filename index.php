<?php
include 'db.php';
session_start();

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// حساب عدد المنتجات في السلة لعرضها بجانب أيقونة Cart
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
    <title>Bloom | Plant & Gardening Store</title>

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

        .banner-container {
            width: 100%;
            max-width: 850px;
            margin: 0 auto;
            padding: 10px;
        }

        .banner-img {
            width: 100%;
            height: auto;
            border-radius: 20px;
            display: block;
            object-fit: contain;
        }

        .product-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid var(--brand-divider);
            padding: 20px;
            height: 100%;
            box-shadow: 0 4px 6px rgba(59, 83, 67, 0.03);
            display: flex;
            flex-direction: column;
            transition: transform 0.2s;
        }

        .product-card:hover {
            transform: translateY(-4px);
        }

        .product-img {
            width: 100%;
            height: 210px;
            object-fit: contain;
            margin-bottom: 15px;
            background-color: #fff;
        }

        .product-link {
            text-decoration: none;
            color: inherit;
        }

        .product-link:hover h5 {
            color: var(--brand-green);
            text-decoration: underline;
        }

        .btn-bloom {
            background-color: var(--brand-green);
            color: #ffffff;
            border-radius: 8px;
            font-weight: 500;
            border: none;
        }

        .btn-bloom:hover {
            background-color: #2c3f32;
            color: #ffffff;
        }

        .site-footer {
            background-color: var(--brand-green);
            color: #FAF8F5;
            padding: 50px 0 20px 0;
            margin-top: auto;
        }

        .site-footer h6 {
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 0.95rem;
        }

        .site-footer ul {
            padding-left: 0;
            list-style: none;
        }

        .site-footer ul li {
            margin-bottom: 8px;
        }

        .site-footer ul li a {
            color: #d1d8d3;
            text-decoration: none;
            font-size: 0.85rem;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            padding-top: 20px;
            margin-top: 30px;
            font-size: 0.8rem;
            color: #b5c2bc;
        }
    </style>
</head>
<body>

    <!-- الهيدر العلوي -->
    <header class="main-header sticky-top">
        <nav class="navbar navbar-expand-lg navbar-dark p-0">
            <div class="container">
                <a class="navbar-brand-custom" href="index.php">Bloom</a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#bloomNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="bloomNavbar">
                    <ul class="navbar-nav ms-auto align-items-center gap-3 mt-3 mt-lg-0">
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="advisor.php">Plant Advisor</a>
                        </li>
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
                        <li class="nav-item">
                            <a class="btn btn-outline-light btn-sm px-3" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-light btn-sm text-success px-3 fw-bold" href="register.php">Sign Up</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- عرض صورة البنر الأساسية -->
    <section class="py-4 text-center">
        <div class="container">
            <div class="banner-container">
                <img src="banner" alt="Welcome to Bloom" class="banner-img" onerror="this.src='banner.jpeg';">
            </div>
            
            <p class="text-secondary small mt-3 mx-auto" style="max-width: 650px;">
                Discover your perfect green companion. Explore our curated collection of indoor plants, gardening essentials, and smart care tools.
            </p>
        </div>
    </section>

    <!-- عرض المنتجات -->
    <main class="container my-4">
        <h2 class="brand-title-font mb-4">Plant Store</h2>
        <div class="row g-4">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-12 col-md-4">
                        <div class="product-card">
                            <a href="product.php?id=<?php echo $row['product_id']; ?>" class="product-link">
                                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Plant" class="product-img">
                                <h5 class="brand-title-font fs-5 mb-2 text-dark"><?php echo htmlspecialchars($row['title']); ?></h5>
                            </a>
                            
                            <p class="text-muted small mb-3 flex-grow-1" style="min-height: 40px;"><?php echo htmlspecialchars($row['description']); ?></p>
                            
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                                <span class="fw-bold text-success fs-5"><?php echo number_format($row['price'], 2); ?> SAR</span>
                                
                                <form action="cart.php" method="POST" class="m-0">
                                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                    <button type="submit" class="btn btn-bloom btn-sm px-3">Add to Cart</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No products found in the database.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- الفوتر -->
    <footer class="site-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h6>STAY IN THE LOOP</h6>
                    <p class="small text-light opacity-75 mb-3">Stay in the loop with special offers, plant-parenting tips, and more.</p>
                    <form class="input-group input-group-sm mb-3">
                        <input type="email" class="form-control bg-transparent text-white border-light" placeholder="Enter your email">
                        <button class="btn btn-light text-success fw-bold px-3" type="button">SUBSCRIBE</button>
                    </form>
                </div>

                <div class="col-lg-2 col-md-3 col-6">
                    <h6>COMPANY</h6>
                    <ul>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Reviews</a></li>
                        <li><a href="#">Press</a></li>
                        <li><a href="#">Our Guarantee</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-3 col-6">
                    <h6>SUPPORT</h6>
                    <ul>
                        <li><a href="advisor.php">Plant Advisor</a></li>
                        <li><a href="#">Shipping & Returns</a></li>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Contact Us</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-12">
                    <h6>PLANT QUESTIONS?</h6>
                    <ul>
                        <li><a href="advisor.php">Plant Care Tips</a></li>
                        <li><a href="#">Plant Life Blog</a></li>
                        <li><a href="advisor.php">Contact the Grow-How® Team</a></li>
                    </ul>
                    <div class="d-flex gap-3 mt-3 fs-5">
                        <a href="#" class="text-white"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-white"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom d-flex flex-wrap justify-content-between align-items-center">
                <p class="mb-0">&copy; <?php echo date("Y"); ?> Bloom Store. All rights reserved.</p>
                <div class="d-flex gap-3 small">
                    <a href="#" class="text-light text-decoration-none">Terms</a>
                    <a href="#" class="text-light text-decoration-none">Privacy Policy</a>
                    <a href="#" class="text-light text-decoration-none">Cookie Preferences</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>