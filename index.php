<?php
include 'db.php';
session_start();

// جلب المنتجات من قاعدة البيانات
$featured_products = [];
$featured_query = "SELECT * FROM products LIMIT 4";
$featured_res = $conn->query($featured_query);

if ($featured_res && $featured_res->num_rows > 0) {
    while ($p = $featured_res->fetch_assoc()) {
        $featured_products[] = $p;
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloom | Premium Indoor Plants & Plant Care</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        :root {
            --brand-green: #3B5343;
            --brand-beige: #FAF8F5;
            --brand-dark: #2C3F32;
            --card-bg: #FFFFFF;
            --text-sub: #6C757D;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--brand-beige);
            color: var(--brand-dark);
            margin: 0;
            padding: 0;
        }

        /* Green Main Navbar */
        .navbar-bloom {
            background-color: var(--brand-green) !important;
            padding: 12px 15px;
            border-bottom: 1px solid #2C3F32;
        }

        .navbar-brand-custom {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: #FAF8F5 !important;
            text-decoration: none;
            letter-spacing: -0.5px;
            line-height: 1;
        }

        .nav-link-custom {
            font-size: 0.78rem;
            font-weight: 600;
            color: #FAF8F5 !important;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 6px 8px !important;
            display: inline-flex;
            align-items: center;
            gap: 3px;
            white-space: nowrap;
            transition: opacity 0.2s;
        }

        .nav-link-custom:hover {
            opacity: 0.8;
        }

        .dropdown-menu-custom {
            border: none;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 15px;
            background-color: #FFFFFF;
        }

        .dropdown-item-custom {
            font-size: 0.88rem;
            color: var(--brand-dark);
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
        }

        .dropdown-item-custom:hover {
            background-color: var(--brand-beige);
            color: var(--brand-green);
        }

        .nav-icon {
            font-size: 1.1rem;
            color: #FAF8F5;
            margin-left: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.2s;
        }

        .nav-icon:hover {
            opacity: 0.8;
        }

        /* Welcome Banner Image Wrapper */
        .welcome-banner-wrapper {
            max-width: 1000px;
            margin: 30px auto 10px auto;
            padding: 0 20px;
            text-align: center;
        }

        .welcome-banner-img {
            width: 100%;
            border-radius: 24px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.04);
            object-fit: cover;
        }

        .welcome-desc {
            font-size: 0.95rem;
            color: var(--text-sub);
            margin-top: 15px;
            margin-bottom: 30px;
        }

        /* 4-Cards Showcase Grid */
        .showcase-container {
            max-width: 1350px;
            margin: 30px auto 80px auto;
            padding: 0 20px;
        }

        .bloomscape-card {
            background-color: #FFFFFF;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #EAE5DD;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            text-decoration: none;
        }

        .bloomscape-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
        }

        .bloomscape-img-box {
            width: 100%;
            height: 280px;
            overflow: hidden;
            background-color: #FAF8F5;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }

        .bloomscape-img-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.5s ease;
        }

        .bloomscape-card:hover .bloomscape-img-box img {
            transform: scale(1.06);
        }

        .bloomscape-card-body {
            padding: 20px;
            background-color: #FAF8F5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            border-top: 1px solid #EFEAE3;
        }

        .bloomscape-icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #EFEAE3;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3B5343;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .bloomscape-text-content {
            flex-grow: 1;
        }

        .bloomscape-title {
            font-size: 1rem;
            font-weight: 700;
            color: #2C3F32;
            margin: 0 0 2px 0;
        }

        .bloomscape-sub {
            font-size: 0.8rem;
            color: #6C757D;
            margin: 0;
        }

        .bloomscape-arrow {
            color: #A3ACA5;
            font-size: 1rem;
            transition: color 0.2s, transform 0.2s;
            flex-shrink: 0;
        }

        .bloomscape-card:hover .bloomscape-arrow {
            color: #3B5343;
            transform: translateX(3px);
        }

        /* Footer & Customer Support Section (White Text) */
        footer {
            background-color: var(--brand-dark);
            color: #FFFFFF !important;
            padding: 50px 0 30px 0;
            font-size: 0.9rem;
        }

        .footer-heading {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #FFFFFF !important;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: #FFFFFF !important;
            text-decoration: none;
            font-size: 0.88rem;
            opacity: 0.85;
            transition: opacity 0.2s;
        }

        .footer-links a:hover {
            opacity: 1;
            text-decoration: underline;
        }

        footer p, footer span, footer small {
            color: #FFFFFF !important;
            opacity: 0.85;
        }
    </style>
</head>
<body>

    <!-- Main Navigation Bar (Green Background) -->
    <nav class="navbar navbar-expand-lg navbar-bloom sticky-top">
        <div class="container-fluid px-3">
            <a class="navbar-brand navbar-brand-custom m-0" href="index.php">Bloom</a>

            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#bloomNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-between" id="bloomNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 align-items-lg-center">
                    
                    <!-- 1. Plants -->
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle" href="#" id="plantsDrop" role="button" data-bs-toggle="dropdown">
                            Plants
                        </a>
                        <ul class="dropdown-menu dropdown-menu-custom" aria-labelledby="plantsDrop">
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=indoor">Indoor plants</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=outdoor">Outdoor plants</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=cacti_succulents">Cacti and succulents</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=flowering">Flowering plants</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=aromatic">Aromatic plants</a></li>
                        </ul>
                    </li>

                    <!-- 2. Fertilizers -->
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle" href="#" id="fertilizersDrop" role="button" data-bs-toggle="dropdown">
                            Fertilizers
                        </a>
                        <ul class="dropdown-menu dropdown-menu-custom" aria-labelledby="fertilizersDrop">
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=organic_fert">Organic fertilizers</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=liquid_fert">Liquid fertilizers</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=granular_fert">Granular fertilizers</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=plant_nutrients">Plant nutrients</a></li>
                        </ul>
                    </li>

                    <!-- 3. Pots & Planters -->
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle" href="#" id="potsDrop" role="button" data-bs-toggle="dropdown">
                            Pots & Planters
                        </a>
                        <ul class="dropdown-menu dropdown-menu-custom" aria-labelledby="potsDrop">
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=indoor_pots">Indoor pots</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=outdoor_pots">Outdoor pots</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=hanging_pots">Hanging pots</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=self_watering">Self-watering pots</a></li>
                        </ul>
                    </li>

                    <!-- 4. Gardening Tools -->
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle" href="#" id="toolsDrop" role="button" data-bs-toggle="dropdown">
                            Gardening Tools
                        </a>
                        <ul class="dropdown-menu dropdown-menu-custom" aria-labelledby="toolsDrop">
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=digging_tools">Digging and planting tools</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=pruning_tools">Pruning tools</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=watering_spraying">Watering and spraying tools</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=gardening_gloves">Gardening gloves</a></li>
                        </ul>
                    </li>

                    <!-- 5. Soil & Growing Supplies -->
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle" href="#" id="soilDrop" role="button" data-bs-toggle="dropdown">
                            Soil & Supplies
                        </a>
                        <ul class="dropdown-menu dropdown-menu-custom" aria-labelledby="soilDrop">
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=potting_soil">Potting soil</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=perlite_peat">Perlite and peat moss</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=decorative_stones">Decorative stones</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=repotting_supplies">Plant repotting and planting supplies</a></li>
                        </ul>
                    </li>

                    <!-- 6. Plant Care -->
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle" href="#" id="careDrop" role="button" data-bs-toggle="dropdown">
                            Plant Care
                        </a>
                        <ul class="dropdown-menu dropdown-menu-custom" aria-labelledby="careDrop">
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=watering_monitoring">Watering monitoring tools</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=plant_sprayers">Plant sprayers</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=pest_control">Pest control supplies</a></li>
                            <li><a class="dropdown-item dropdown-item-custom" href="products.php?category=leaf_care">Leaf care products</a></li>
                        </ul>
                    </li>

                    <!-- Smart Plant Advisor -->
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom fw-bold text-warning" href="advisor.php">
                            <i class="bi bi-robot"></i> Advisor
                        </a>
                    </li>

                </ul>

                <!-- Navbar Action Icons & Admin Add Product Button -->
                <div class="d-flex align-items-center">
                    <a href="add_product.php" class="btn btn-outline-light btn-sm me-2 fw-semibold text-nowrap" title="Add New Product"><i class="bi bi-plus-lg me-1"></i> Add Product</a>
                    <a href="#" class="nav-icon" title="Search"><i class="bi bi-search"></i></a>
                    <a href="login.php" class="nav-icon" title="Login / Register"><i class="bi bi-person"></i></a>
                    <a href="cart.php" class="nav-icon position-relative" title="Cart">
                        <i class="bi bi-bag"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-light text-dark" style="font-size: 0.6rem;">0</span>
                    </a>
                </div>
            </div>

        </div>
    </nav>

    <!-- Bloom Welcome Banner Image (banner.jpeg) -->
    <div class="welcome-banner-wrapper">
        <img src="banner.jpeg" alt="Welcome to Bloom" class="welcome-banner-img">
        <p class="welcome-desc">Discover your perfect green companion. Explore our curated collection of indoor plants, gardening essentials, and smart care tools.</p>
    </div>

    <!-- 4-Cards Showcase Grid (Local Product Images) -->
    <section class="showcase-container">
        <div class="row g-4 justify-content-center">

            <!-- Card 1: Low Light -->
            <div class="col-lg-3 col-md-6">
                <a href="product.php?id=1" class="bloomscape-card">
                    <div class="bloomscape-img-box">
                        <img src="maranta.jpeg" alt="Low Light Plant" onerror="this.src='https://via.placeholder.com/300x300?text=Maranta';">
                    </div>
                    <div class="bloomscape-card-body">
                        <div class="bloomscape-icon-circle">
                            <i class="bi bi-sun"></i>
                        </div>
                        <div class="bloomscape-text-content">
                            <h3 class="bloomscape-title">Low Light</h3>
                            <p class="bloomscape-sub">Thrives in shady spots</p>
                        </div>
                        <div class="bloomscape-arrow">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 2: Pet-Friendly -->
            <div class="col-lg-3 col-md-6">
                <a href="product.php?id=2" class="bloomscape-card">
                    <div class="bloomscape-img-box">
                        <img src="hearts.jpeg" alt="Pet-Friendly Plant" onerror="this.src='https://via.placeholder.com/300x300?text=Hearts';">
                    </div>
                    <div class="bloomscape-card-body">
                        <div class="bloomscape-icon-circle">
                            <i class="bi bi-heart"></i>
                        </div>
                        <div class="bloomscape-text-content">
                            <h3 class="bloomscape-title">Pet-Friendly</h3>
                            <p class="bloomscape-sub">Safe for furry friends</p>
                        </div>
                        <div class="bloomscape-arrow">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 3: Easy Care -->
            <div class="col-lg-3 col-md-6">
                <a href="product.php?id=3" class="bloomscape-card">
                    <div class="bloomscape-img-box">
                        <img src="hoya.jpeg" alt="Easy Care Plant" onerror="this.src='https://via.placeholder.com/300x300?text=Hoya';">
                    </div>
                    <div class="bloomscape-card-body">
                        <div class="bloomscape-icon-circle">
                            <i class="bi bi-flower1"></i>
                        </div>
                        <div class="bloomscape-text-content">
                            <h3 class="bloomscape-title">Easy Care</h3>
                            <p class="bloomscape-sub">Hard to kill</p>
                        </div>
                        <div class="bloomscape-arrow">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 4: Statement Plants -->
            <div class="col-lg-3 col-md-6">
                <a href="product.php?id=4" class="bloomscape-card">
                    <div class="bloomscape-img-box">
                        <img src="spider.jpeg" alt="Statement Plant" onerror="this.src='https://via.placeholder.com/300x300?text=Spider';">
                    </div>
                    <div class="bloomscape-card-body">
                        <div class="bloomscape-icon-circle">
                            <i class="bi bi-stars"></i>
                        </div>
                        <div class="bloomscape-text-content">
                            <h3 class="bloomscape-title">Statement</h3>
                            <p class="bloomscape-sub">Bold and dramatic</p>
                        </div>
                        <div class="bloomscape-arrow">
                            <i class="bi bi-arrow-right"></i>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </section>

    <!-- Footer & Customer Support Section (White Text) -->
    <footer>
        <div class="container">
            <div class="row g-4 mb-4 text-start">
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-heading">Bloom Customer Care</h5>
                    <ul class="footer-links">
                        <li><a href="#">Help Center & Support</a></li>
                        <li><a href="#">Shipping & Delivery</a></li>
                        <li><a href="#">Returns & Guarantees</a></li>
                        <li><a href="#">Plant Care Guides</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-heading">Departments</h5>
                    <ul class="footer-links">
                        <li><a href="products.php?category=indoor">Indoor Plants</a></li>
                        <li><a href="products.php?category=fertilizers">Fertilizers & Soil</a></li>
                        <li><a href="products.php?category=pots">Pots & Planters</a></li>
                        <li><a href="advisor.php">AI Plant Advisor</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-heading">About Bloom</h5>
                    <ul class="footer-links">
                        <li><a href="#">Our Story</a></li>
                        <li><a href="#">Sustainability</a></li>
                        <li><a href="#">Careers</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-heading">Stay Connected</h5>
                    <p class="small mb-2">Subscribe to get special offers and plant care tips.</p>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control form-control-sm" placeholder="Your email">
                        <button class="btn btn-success btn-sm text-white" type="button">Join</button>
                    </div>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.2);">
            <div class="text-center pt-2">
                <p class="mb-0 small">&copy; <?php echo date('Y'); ?> Bloom Inc. All rights reserved. Designed for plant lovers.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>