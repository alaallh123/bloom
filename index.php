<?php
include 'db.php';
session_start();

// جلب جميع المنتجات من قاعدة البيانات
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
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
            padding: 10px 0;
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

        .product-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid var(--brand-divider);
            transition: transform 0.2s, box-shadow 0.2s;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(59, 83, 67, 0.08);
        }

        .product-img {
            width: 100%;
            height: 220px;
            object-fit: contain;
            background-color: #fff;
            padding: 15px;
        }

        .btn-bloom {
            background-color: var(--brand-green);
            color: #ffffff;
            border-radius: 8px;
            font-weight: 500;
            padding: 0.6rem 1.2rem;
            border: none;
            transition: background-color 0.2s;
            width: 100%;
        }

        .btn-bloom:hover {
            background-color: #2c3f32;
            color: #ffffff;
        }

        .badge-category {
            background-color: var(--brand-sage-light);
            color: var(--brand-green);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
        }
    </style>
</head>
<body>

    <!-- الهيدر المطور مع شريط التنقل الشامل -->
    <header class="main-header sticky-top">
        <nav class="navbar navbar-expand-lg navbar-dark p-0">
            <div class="container">
                <a class="navbar-brand-custom" href="index.php">Bloom</a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#bloomNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="bloomNavbar">
                    <ul class="navbar-nav ms-auto align-items-center gap-2 mt-3 mt-lg-0">
                        <li class="nav-item">
                            <a class="nav-link text-white active" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="advisor.php">
                                <i class="bi bi-robot text-warning me-1"></i> Plant Advisor
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="cart.php">
                                <i class="bi bi-bag me-1"></i> Cart
                            </a>
                        </li>
                        <li class="nav-item ms-lg-2">
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

    <!-- قسم العرض والترحيب (Hero Banner) -->
    <section class="py-5 text-center" style="background-color: var(--brand-sage-light);">
        <div class="container py-3">
            <h1 class="brand-title-font display-5 mb-3">Bring Nature Into Your Space</h1>
            <p class="text-secondary lead fs-6 mx-auto" style="max-width: 600px;">
                Explore our curated collection of indoor plants, pots, and expert care recommendations.
            </p>
        </div>
    </section>

    <!-- كتالوج المنتجات -->
    <main class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="brand-title-font m-0">Featured Plants</h3>
            <span class="text-muted small">Showing all plants</span>
        </div>

        <div class="row g-4">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                        <div class="product-card p-3">
                            <div class="text-end">
                                <span class="badge-category"><?php echo htmlspecialchars($row['category'] ?? 'Indoor Plant'); ?></span>
                            </div>
                            
                            <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="product-img">

                            <div class="d-flex flex-column flex-grow-1 mt-2">
                                <h5 class="brand-title-font fs-6 mb-1"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <p class="text-secondary small flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?php echo htmlspecialchars($row['description']); ?>
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                    <span class="fw-bold text-success fs-5"><?php echo number_format($row['price'], 2); ?> SAR</span>
                                    
                                    <form action="cart.php" method="POST" class="m-0">
                                        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
                                        <button type="submit" class="btn btn-bloom btn-sm px-3">
                                            <i class="bi bi-cart-plus me-1"></i> Add
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted fs-5">No products found in the database.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- الفوتر (Footer) -->
    <footer class="py-4 text-center border-top mt-5" style="background-color: #ffffff; border-color: var(--brand-divider) !important;">
        <div class="container text-muted small">
            <p class="mb-1">&copy; <?php echo date("Y"); ?> Bloom Store. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>