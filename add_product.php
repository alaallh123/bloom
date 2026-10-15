<?php
include 'db.php';
session_start();

$message = "";
$message_type = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $price = floatval($_POST['price']);
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    
    $image_name = "default.jpeg";
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['image']['name'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($file_ext, $allowed)) {
            $new_filename = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $filename);
            $destination = __DIR__ . '/' . $new_filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $image_name = $new_filename;
            } else {
                $message = "فشل في رفع الصورة.";
                $message_type = "danger";
            }
        } else {
            $message = "صيغة الملف غير مدعومة.";
            $message_type = "danger";
        }
    }

    if (empty($message)) {
        $stmt = $conn->prepare("INSERT INTO products (title, price, category, description, image) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sdsss", $title, $price, $category, $description, $image_name);
            if ($stmt->execute()) {
                $message = "تمت إضافة المنتج بنجاح! 🎉";
                $message_type = "success";
            } else {
                $message = "خطأ في القاعدة: " . $conn->error;
                $message_type = "danger";
            }
            $stmt->close();
        } else {
            $message = "خطأ في الاستعلام: " . $conn->error;
            $message_type = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | Bloom Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-green: #3B5343;
            --brand-dark: #2C3F32;
            --brand-beige: #FAF8F5;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--brand-green); /* خلفية الشاشة بالكامل خضراء */
            color: var(--brand-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }
        .admin-card {
            width: 100%;
            max-width: 950px;
            background: #FFFFFF;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.1);
            display: flex;
            flex-wrap: wrap;
        }
        .card-left {
            flex: 1;
            min-width: 320px;
            padding: 40px;
            background-color: #FFFFFF;
        }
        .card-right {
            flex: 1;
            min-width: 300px;
            background-color: var(--brand-dark);
            color: #FAF8F5;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .form-title {
            font-family: 'Playfair Display', serif;
            color: var(--brand-dark);
            font-weight: 700;
            margin-bottom: 20px;
        }
        .btn-bloom {
            background-color: var(--brand-green);
            color: #FFFFFF;
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
            font-weight: 600;
            width: 100%;
            transition: background 0.2s;
        }
        .btn-bloom:hover {
            background-color: var(--brand-dark);
            color: #FFFFFF;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #D1C9BF;
            padding: 10px 14px;
            font-size: 0.9rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--brand-green);
            box-shadow: 0 0 0 0.2rem rgba(59, 83, 67, 0.15);
        }
    </style>
</head>
<body>

<div class="admin-card">
    <!-- Left Section: Form -->
    <div class="card-left">
        <h3 class="form-title">Add New Product</h3>
        
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show p-2 small" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close p-2" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form action="add_product.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label small fw-semibold">Product Name</label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Snake Plant" required>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label class="form-label small fw-semibold">Price ($)</label>
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="120.00" required>
                </div>
                <div class="col-6">
                    <label class="form-label small fw-semibold">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="indoor">Indoor Plants</option>
                        <option value="outdoor">Outdoor Plants</option>
                        <option value="succulents">Succulents & Cacti</option>
                        <option value="flowering">Flowering Plants</option>
                        <option value="aromatic">Aromatic Plants</option>
                        <option value="organic_fert">Organic Fertilizers</option>
                        <option value="liquid_fert">Liquid Fertilizers</option>
                        <option value="granular_fert">Granular Fertilizers</option>
                        <option value="plant_food">Plant Food</option>
                        <option value="indoor_pots">Indoor Pots</option>
                        <option value="outdoor_pots">Outdoor Pots</option>
                        <option value="hanging_pots">Hanging Planters</option>
                        <option value="self_watering">Self-Watering Pots</option>
                        <option value="potting_soil">Potting Soil</option>
                        <option value="perlite_peat">Perlite & Peat Moss</option>
                        <option value="decorative_stones">Decorative Stones</option>
                        <option value="repotting_tools">Repotting Tools</option>
                        <option value="watering_tools">Watering Tools</option>
                        <option value="misters">Misters & Sprayers</option>
                        <option value="pest_control">Pest Control</option>
                        <option value="leaf_shine">Leaf Care</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Brief care guide and description..."></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-semibold">Product Image</label>
                <input type="file" name="image" class="form-control" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-bloom">
                <i class="bi bi-plus-circle me-1"></i> Save Product
            </button>
        </form>
    </div>

    <!-- Right Section: Info & Navigation -->
    <div class="card-right">
        <h2 style="font-family: 'Playfair Display', serif;" class="mb-3">Bloom Store</h2>
        <p class="small mb-4 opacity-75">Manage your catalog items, upload images, and keep your plant care store up to date.</p>
        <a href="index.php" class="btn btn-outline-light rounded-pill px-4 btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Shop
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>