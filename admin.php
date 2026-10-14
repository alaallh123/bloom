<?php
include 'db.php';
session_start();

$message = "";

// إضافة منتج جديد
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $_POST['price'];
    $image = $conn->real_escape_string($_POST['image']);
    $category = $conn->real_escape_string($_POST['category']);

    $sql = "INSERT INTO products (title, description, price, image, category) VALUES ('$title', '$description', '$price', '$image', '$category')";
    if ($conn->query($sql)) {
        $message = "تمت إضافة النبتة بنجاح! 🌱";
    } else {
        $message = "حدث خطأ أثناء الإضافة: " . $conn->error;
    }
}

// حذف منتج
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $conn->query("DELETE FROM products WHERE product_id = $delete_id");
    header("Location: admin.php");
    exit();
}

$products = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Bloom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        :root { --brand-bg: #FAF8F5; --brand-green: #3B5343; --brand-divider: #E7E0D6; }
        body { background-color: var(--brand-bg); font-family: 'Inter', sans-serif; }
        .main-header { background-color: var(--brand-green); padding: 12px 0; }
        .btn-bloom { background-color: var(--brand-green); color: #fff; border: none; }
        .btn-bloom:hover { background-color: #2c3f32; color: #fff; }
    </style>
</head>
<body>
    <header class="main-header mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="index.php" class="text-white fw-bold fs-4 text-decoration-none">Bloom Admin</a>
            <a href="index.php" class="text-white small text-decoration-none"><i class="bi bi-shop"></i> View Store</a>
        </div>
    </header>

    <div class="container my-4">
        <?php if (!empty($message)): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- نموذج إضافة نبتة جديدة -->
            <div class="col-md-5">
                <div class="card p-4 border-0 shadow-sm rounded-3">
                    <h5 class="fw-bold text-success mb-3"><i class="bi bi-plus-circle"></i> Add New Plant</h5>
                    <form action="admin.php" method="POST">
                        <div class="mb-2">
                            <label class="form-label small">Plant Title</label>
                            <input type="text" name="title" class="form-control" required placeholder="e.g. Monstera Deliciosa">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Description</label>
                            <textarea name="description" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Price (SAR)</label>
                            <input type="number" step="0.01" name="price" class="form-control" required placeholder="45.00">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Image Filename</label>
                            <input type="text" name="image" class="form-control" required placeholder="monstera.jpeg">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small">Category</label>
                            <input type="text" name="category" class="form-control" value="Indoor Plants">
                        </div>
                        <button type="submit" name="add_product" class="btn btn-bloom w-100">Add Plant</button>
                    </form>
                </div>
            </div>

            <!-- قائمة المنتجات الحالية مع خيار الحذف -->
            <div class="col-md-7">
                <div class="card p-4 border-0 shadow-sm rounded-3">
                    <h5 class="fw-bold mb-3">Current Products</h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $products->fetch_assoc()): ?>
                                    <tr>
                                        <td><img src="<?php echo $row['image']; ?>" width="45" height="45" style="object-fit:cover;" class="rounded"></td>
                                        <td class="fw-bold"><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo number_format($row['price'], 2); ?> SAR</td>
                                        <td>
                                            <a href="admin.php?delete=<?php echo $row['product_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('هل أنت متأكد من الحذف؟');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>