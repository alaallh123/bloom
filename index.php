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
    </style>
</head>
<body>

    <div class="page-wrapper">
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
                        <li class="nav-item"><a class="nav-link" href="#">Sign In</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container hero-section">
            <img src="<?php echo $banner_file; ?>" alt="Welcome to Bloom" class="bloom-banner-img">
            <div class="main-divider"></div>
            <p class="lead text-muted col-md-7 mx-auto fs-5">
                Discover your perfect green companion. Explore our curated collection of indoor plants, gardening essentials, and smart care tools.
            </p>
        </div>

        <div class="site-divider"></div>

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
                            <?php if (!empty($product['image']) && file_exists($product['image'])): ?>
                                <a href="product.php?id=<?php echo $product['product_id']; ?>">
                                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>" class="product-thumb rounded">
                                </a>
                            <?php else: ?>
                                <div class="product-thumb d-flex align-items-center justify-content-center text-muted small rounded">No Image</div>
                            <?php endif; ?>

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
                                    <!-- زر Shop Now عريض مثل موقع صديقتك -->
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

        <div class="site-divider mt-auto"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>