<?php
include 'db.php';
session_start();

$id = isset($_GET['id']) ? intval($_GET['id']) : 4;

$product = [
    'title' => "Spider Plant",
    'price' => 69.00,
    'description' => "The Spider Plant is one of the most well-known houseplants for a good reason. They are easy to care for and adaptable to almost any spot in your home.",
    'image' => "spider.jpeg"
];
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['title']); ?> | Bloom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #3B5343; /* خلفية الصفحة خضراء متناسقة بالكامل */
            color: #2C3F32;
            margin: 0;
            padding: 0;
        }

        /* Top Bar */
        .top-bar {
            background-color: #2C3F32;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar a {
            color: #FAF8F5;
            text-decoration: none;
            font-weight: 500;
            margin-right: 20px;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .top-bar a:hover {
            opacity: 0.8;
        }

        /* Container */
        .main-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .product-card-view {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid #EAE5DD;
            margin-bottom: 30px;
        }

        .product-img-box {
            background-color: #F8F5F0;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
        }

        .product-img-box img {
            max-width: 100%;
            height: 320px;
            object-fit: contain;
        }

        .product-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #2C3F32;
            margin-bottom: 5px;
        }

        .product-price {
            font-size: 1.2rem;
            font-weight: 600;
            color: #3B5343;
            margin-bottom: 20px;
        }

        .section-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #2C3F32;
            margin-bottom: 10px;
        }

        .option-box {
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: border-color 0.2s;
        }

        .option-box:hover {
            border-color: #3B5343;
        }

        .option-box input {
            margin-right: 12px;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
        }

        .quantity-control button {
            width: 35px;
            height: 35px;
            border: 1px solid #CBD5E0;
            background: #fff;
            border-radius: 6px;
            font-weight: 600;
        }

        .quantity-control input {
            width: 50px;
            text-align: center;
            border: 1px solid #CBD5E0;
            border-radius: 6px;
            padding: 6px;
        }

        /* زر Add to cart باللون الأخضر الفاخر */
        .btn-cart {
            background-color: #3B5343 !important;
            color: #fff !important;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            border: none;
            width: 100%;
            transition: background 0.2s;
        }

        .btn-cart:hover {
            background-color: #2C3F32 !important;
            color: #fff !important;
        }
    </style>
</head>
<body>

    <!-- Top Bar with Robot Icon Next to Plant Advisor -->
    <div class="top-bar">
        <div>
            <a href="index.php">Home</a>
            <a href="advisor.php"><i class="bi bi-robot"></i> Plant Advisor</a>
        </div>
        <div>
            <a href="cart.php"><i class="bi bi-bag"></i> Cart</a>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="main-container">
        
        <!-- Top Section: Image Only -->
        <div class="product-card-view">
            <div class="product-img-box">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" onerror="this.src='spider.jpeg';">
            </div>
        </div>

        <!-- Description Section -->
        <div class="product-card-view">
            <h5 class="fw-bold mb-2">Description</h5>
            <p class="text-muted small mb-0"><?php echo htmlspecialchars($product['description']); ?></p>
        </div>

        <!-- Product Details, Options & Cart Section -->
        <div class="product-card-view">
            <h1 class="product-title"><?php echo htmlspecialchars($product['title']); ?></h1>
            <div class="product-price"><?php echo number_format($product['price'], 2); ?> SAR</div>
            
            <form action="cart.php" method="POST">
                <input type="hidden" name="product_id" value="<?php echo $id; ?>">

                <!-- Size Options -->
                <div class="mb-4">
                    <div class="section-label">Size</div>
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
                </div>

                <!-- Pot Options -->
                <div class="mb-4">
                    <div class="section-label">Pot</div>
                    <label class="option-box">
                        <input type="radio" name="pot" value="nursery" checked> Nursery Pot
                    </label>
                    <label class="option-box">
                        <input type="radio" name="pot" value="black_cylinder"> Black Cylinder
                    </label>
                    <label class="option-box">
                        <input type="radio" name="pot" value="white_cylinder"> White Cylinder
                    </label>
                    <label class="option-box">
                        <input type="radio" name="pot" value="terra_cotta"> Terra Cotta Eco Pot
                    </label>
                    <label class="option-box">
                        <input type="radio" name="pot" value="black_eco"> Black Eco Pot
                    </label>
                    <label class="option-box">
                        <input type="radio" name="pot" value="cow_pot"> Cow Pot
                    </label>
                </div>

                <!-- Quantity -->
                <div class="mb-4">
                    <div class="section-label">Quantity</div>
                    <div class="quantity-control">
                        <button type="button">-</button>
                        <input type="text" name="quantity" value="1">
                        <button type="button">+</button>
                    </div>
                </div>

                <button type="submit" class="btn-cart">Add to cart</button>
            </form>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>