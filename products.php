<?php
include 'db.php';
session_start();

// معالجة الحذف بأمان تام اعتماداً على العنوان أو المعرف المتوفر
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    $category_param = isset($_GET['category']) ? trim($_GET['category']) : '';
    
    if (isset($_GET['title']) && !empty($_GET['title'])) {
        $title_to_delete = trim($_GET['title']);
        $stmt_del = $conn->prepare("DELETE FROM products WHERE title = ? LIMIT 1");
        if ($stmt_del) {
            $stmt_del->bind_param("s", $title_to_delete);
            $stmt_del->execute();
            $stmt_del->close();
        }
    }

    $redirect = "products.php" . (!empty($category_param) ? "?category=" . urlencode($category_param) : "");
    header("Location: " . $redirect);
    exit();
}

$category = isset($_GET['category']) ? trim($_GET['category']) : '';

if (!empty($category)) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE category = ?");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM products");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | Bloom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --brand-green: #3B5343; --brand-dark: #2C3F32; --brand-beige: #FAF8F5; }
        body { background-color: var(--brand-beige); color: var(--brand-dark); font-family: 'Inter', sans-serif; }
        .navbar-bloom { background-color: var(--brand-green) !important; padding: 12px 0; }
        .navbar-brand-custom { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 700; color: #FAF8F5 !important; text-decoration: none; }
        .nav-link-custom { font-size: 0.85rem; font-weight: 600; color: #FAF8F5 !important; text-transform: uppercase; padding: 8px 12px !important; text-decoration: none; }
        .product-card { background: #FFFFFF; border-radius: 20px; border: 1px solid #EAE5DD; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; height: 100%; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .product-img { width: 100%; height: 220px; object-fit: contain; background-color: var(--brand-beige); padding: 15px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-bloom mb-4">
    <div class="container d-flex align-items-center justify-content-between">
        <a class="navbar-brand navbar-brand-custom m-0" href="index.php">Bloom</a>
        <div class="d-flex align-items-center">
            <a href="index.php" class="nav-link-custom me-3"><i class="bi bi-house me-1"></i> Home</a>
            <a href="add_product.php" class="btn btn-outline-light btn-sm me-3"><i class="bi bi-plus-lg me-1"></i> Add Product</a>
        </div>
    </div>
</nav>

<div class="container my-4">
    <h2 style="font-family: 'Playfair Display', serif;" class="text-capitalize mb-4">
        <?php echo !empty($category) ? str_replace('_', ' ', htmlspecialchars($category)) : 'All Products'; ?>
    </h2>

    <div class="row g-4">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($product = $result->fetch_assoc()): 
                $p_id = isset($product['id']) ? $product['id'] : (isset($product['product_id']) ? $product['product_id'] : 1);
                $p_title = $product['title'] ?? '';
            ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product-card d-flex flex-column p-3">
                        <img src="<?php echo htmlspecialchars($product['image'] ?? 'default.jpeg'); ?>" class="product-img mb-3" alt="Product Image">
                        <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($p_title); ?></h6>
                        <p class="text-muted small flex-grow-1"><?php echo htmlspecialchars(substr($product['description'] ?? '', 0, 45)); ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <span class="fw-bold text-success">$<?php echo number_format($product['price'] ?? 0, 2); ?></span>
                            <div class="d-flex gap-2">
                                <a href="product.php?id=<?php echo $p_id; ?>" class="btn btn-sm btn-dark rounded-pill px-3">View</a>
                                <!-- زر الحذف بالاعتماد على عنوان المنتج لتجنب أخطاء الأعمدة نهائياً -->
                                <a href="products.php?action=delete&title=<?php echo urlencode($p_title); ?>&category=<?php echo urlencode($category); ?>" 
                                   class="btn btn-sm btn-outline-danger rounded-pill px-2" 
                                   onclick="return confirm('Are you sure you want to delete this product?');"
                                   title="Delete Product">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted fs-5">No products added to this category yet.</p>
                <a href="add_product.php" class="btn btn-success btn-sm rounded-pill px-3">Add Product Now</a>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>