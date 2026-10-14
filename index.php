<?php
include 'db.php';

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

$banner_file = 'banner.jpeg';
$possible_banners = ['banner.jpeg', 'banner.jpg', 'banner.png', 'Banner.jpeg', 'Banner.jpg'];
foreach ($possible_banners as $bname) {
    if (file_exists(__DIR__ . '/' . $bname)) {
        $banner_file = $bname;
        break;
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
            --brand-sage-light: #F2F5F2; 
            --brand-divider: #E7E0D6; 
            --brand-card-bg: #ffffff;
            --brand-text: #2f3e35;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--brand-bg);
            color: var(--brand-text);
            margin: 0;
            padding: 0;
        }

        .page-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .brand-title-font {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--brand-green);
        }

        .site-divider {
            border-bottom: 1px solid var(--brand-divider);
        }

        .navbar {
            background-color: var(--brand-green) !important;
            padding: 0.8rem 0;
        }

        .navbar-brand-custom {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--brand-bg) !important;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link {
            font-family: 'Playfair Display', serif;
            color: var(--brand-bg) !important;
            font-weight: 600;
            font-size: 1.05rem;
            margin-left: 1.8rem;
            opacity: 0.85;
            transition: opacity 0.2s;
        }

        .nav-link:hover, .nav-link.active {
            opacity: 1;
            color: #ffffff !important;
        }

        .hero-section {
            padding: 3rem 1rem 2rem 1rem;
            text-align: center;
        }

        .bloom-banner-img {
            width: 100%;
            max-width: 950px;
            height: auto;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(59, 83, 67, 0.1);
            margin-bottom: 1.5rem;
        }

        .main-divider {
            width: 60px;
            height: 1px;
            background-color: var(--brand-green);
            margin: 1.5rem auto;
            opacity: 0.4;
        }

        .product-card {
            background-color: var(--brand-card-bg);
            border: 1px solid var(--brand-divider);
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(59, 83, 67, 0.08);
        }

        .product-thumb {
            width: 100%;
            height: 260px;
            object-fit: contain;
            background-color: #FAF8F5;
            padding: 20px;
        }

        .product-title-link {
            color: var(--brand-green);
            text-decoration: none;
            transition: color 0.2s;
        }

        .product-title-link:hover {
            color: #1b281f;
            text-decoration: underline;
        }

        .badge-category {
            background-color: var(--brand-sage-light);
            color: var(--brand-green);
            font-weight: 500;
            font-size: 0.75rem;
            padding: 0.4em 0.8em;
            border-radius: 20px;
        }

        .btn-bloom {
            background-color: var(--brand-green);
            color: #ffffff;
            border-radius: 8px;
            font-weight: 500;
            padding: 0.7rem 1.2rem;
            border: none;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.2s;
        }

        .btn-bloom:hover {
            background-color: #2c3f32;
            color: #ffffff;
        }

        .price-tag {
            color: var(--brand-green);
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.3rem;
        }

        /* --- Bloom Footer Styling --- */
        .newsletter-section {
            background-color: #F4EFEA;
            padding: 60px 20px;
            text-align: center;
        }

        .bloom-footer {
            background-color: var(--brand-green);
            color: #FAF8F5;
            padding: 60px 0 30px 0;
        }

        .bloom-footer h6 {
            font-size: 0.8rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #C2D1C5;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .bloom-footer ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .bloom-footer ul li {
            margin-bottom: 12px;
        }

        .bloom-footer ul li a {
            color: #FAF8F5;
            text-decoration: none;
            font-size: 0.95rem;
            opacity: 0.9;
            transition: opacity 0.2s;
        }

        .bloom-footer ul li a:hover {
            opacity: 1;
            text-decoration: underline;
        }

        .footer-bottom-links a {
            color: #C2D1C5;
            font-size: 0.85rem;
            text-decoration: none;
            margin-right: 15px;
        }

        .footer-bottom-links a:hover {
            color: #ffffff;
        }

        .social-icon-link {
            color: #FAF8F5;
            font-size: 1.2rem;
            margin-right: 15px;
            transition: opacity 0.2s;
        }

        .social-icon-link:hover {
            opacity: 0.7;
            color: #ffffff;
        }
    </style>
</head>
<body>

    <div class="page-wrapper">
        <!-- الشريط العلوي -->
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand-custom" href="index.php">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 21V12M12 12C12 8 8 5 4 6C4 10 7 12 12 12ZM12 12C12 8 16 5 20 6C20 10 17 12 12 12Z" stroke="#FAF8F5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Bloom
                </a>
                <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="#products">Shop Plants</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Care Guides</a></li>
                        <li class="nav-item"><a class="nav-link" href="login.php">Sign In</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- الهيرو وسلايدر الشعار -->
        <div class="container hero-section">
            <img src="<?php echo $banner_file; ?>" alt="Welcome to Bloom" class="bloom-banner-img">
            <div class="main-divider"></div>
            <p class="lead text-muted col-md-7 mx-auto fs-5">
                Discover your perfect green companion. Explore our curated collection of indoor plants, gardening essentials, and smart care tools.
            </p>
        </div>

        <div class="site-divider"></div>

        <!-- قسم المنتجات الديناميكي -->
        <div class="container my-5 pb-5" id="products">
            <div class="d-flex justify-content-between align-items-center mb-5 pt-2">
                <h3 class="brand-title-font m-0 fs-3">Featured Plants & Accessories</h3>
                <span class="text-muted small">Handpicked & Fresh</span>
            </div>

            <div class="row g-4">
                <?php
                if ($result && $result->num_rows > 0) {
                    while($product = $result->fetch_assoc()) {
                ?>
                    <div class="col-md-4">
                        <div class="card product-card h-100 p-3">
                            <a href="product.php?id=<?php echo $product['product_id']; ?>">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="product-thumb rounded">
                            </a>

                            <div class="card-body d-flex flex-column justify-content-between px-2 pt-3 pb-2">
                                <div>
                                    <div class="mb-2">
                                        <span class="badge badge-category"><?php echo htmlspecialchars($product['category']); ?></span>
                                    </div>
                                    <h5 class="card-title brand-title-font fs-4">
                                        <a href="product.php?id=<?php echo $product['product_id']; ?>" class="product-title-link">
                                            <?php echo htmlspecialchars($product['title']); ?>
                                        </a>
                                    </h5>
                                    <p class="card-text text-secondary small mt-2"><?php echo htmlspecialchars($product['description']); ?></p>
                                </div>
                                <div class="mt-4 pt-3 border-top">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="price-tag"><?php echo number_format($product['price'], 2); ?> SAR</span>
                                    </div>
                                    <a href="product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-bloom w-100 py-2">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                    }
                } else {
                    echo "<div class='col-12'><p class='text-muted text-center py-5'>No products available.</p></div>";
                }
                ?>
            </div>
        </div>

        <!-- قسم الاشتراك في النشرة البريدية (Newsletter) -->
        <section class="newsletter-section">
            <div class="container" style="max-width: 600px;">
                <h2 class="brand-title-font fs-2 mb-3">Join the plant family</h2>
                <p class="text-secondary mb-4">
                    Get 10% off your first order, plus seasonal care tips, new arrivals, and exclusive offers delivered to your inbox.
                </p>
                <form class="d-flex flex-column gap-2">
                    <input type="email" class="form-control form-control-lg text-center fs-6" placeholder="Enter your email" required style="border-radius: 30px; padding: 14px;">
                    <button type="submit" class="btn btn-bloom py-3 fs-6 mt-1" style="border-radius: 30px;">SUBSCRIBE &rarr;</button>
                </form>
            </div>
        </section>

        <!-- الفوتر الرئيسي (Footer) -->
        <footer class="bloom-footer">
            <div class="container">
                <div class="row g-4 mb-5">
                    
                    <!-- Stay in the loop -->
                    <div class="col-md-3">
                        <h6>STAY IN THE LOOP</h6>
                        <p class="small text-light opacity-75 mb-3">Stay in the loop with special offers, plant-parenting tips, and more.</p>
                        <div class="input-group">
                            <input type="email" class="form-control form-control-sm bg-transparent text-white border-secondary" placeholder="Your email">
                            <button class="btn btn-light btn-sm text-dark px-3 fw-bold" type="button">SUBSCRIBE</button>
                        </div>
                    </div>

                    <!-- Company -->
                    <div class="col-md-3">
                        <h6>COMPANY</h6>
                        <ul>
                            <li><a href="#">About</a></li>
                            <li><a href="#">Careers</a></li>
                            <li><a href="#">Reviews</a></li>
                            <li><a href="#">Press</a></li>
                            <li><a href="#">Our Guarantee</a></li>
                            <li><a href="#">Corporate Gifting</a></li>
                            <li><a href="#">Trade Program</a></li>
                        </ul>
                    </div>

                    <!-- Support -->
                    <div class="col-md-3">
                        <h6>SUPPORT</h6>
                        <ul>
                            <li><a href="#">Help + FAQs</a></li>
                            <li><a href="#">Track Your Order</a></li>
                            <li><a href="#">Shipping</a></li>
                            <li><a href="#">Returns</a></li>
                            <li><a href="#">Contact Support</a></li>
                        </ul>
                    </div>

                    <!-- Plant Questions -->
                    <div class="col-md-3">
                        <h6>PLANT QUESTIONS?</h6>
                        <ul>
                            <li><a href="#">Plant Care Tips</a></li>
                            <li><a href="#">Plant Life Blog</a></li>
                            <li><a href="#">Contact the Grow-How® Team</a></li>
                        </ul>
                    </div>

                </div>

                <hr style="border-color: rgba(255,255,255,0.15);">

                <!-- الحقوق وروابط الخصوصية والأيقونات الاجتماعية -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-3 gap-3">
                    <div class="footer-bottom-links">
                        <a href="#">Terms</a>
                        <a href="#">Privacy Policy</a>
                        <a href="#">Do Not Sell My Information</a>
                        <a href="#">Accessibility</a>
                        <a href="#">Cookie Preferences</a>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <a href="#" class="social-icon-link"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="social-icon-link"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="social-icon-link"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="social-icon-link"><i class="bi bi-pinterest"></i></a>
                        <a href="#" class="social-icon-link"><i class="bi bi-tiktok"></i></a>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>