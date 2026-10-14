<?php
include 'db.php';
session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // التحقق من عدم ترك حقول فارغة
    if (empty($username) || empty($email) || empty($password)) {
        $error = "الرجاء تعبئة جميع الحقول المطلوبة.";
    } else {
        // التحقق مما إذا كان البريد الإلكتروني مستخدماً مسبقاً
        $checkEmail = $conn->prepare("SELECT product_id FROM users WHERE email = ?"); // للتأكد من الجدول
        // ملاحظة: لو كان اسم جدولك users وعمود البريد email أو username
        // سنستخدم استعلام مباشر للتحقق السريع:
        $res = $conn->query("SELECT * FROM users WHERE email = '$email'");
        
        if ($res && $res->num_rows > 0) {
            $error = "البريد الإلكتروني مستخدم مسبقاً، يجدر بك تسجيل الدخول.";
        } else {
            // إدخال المستخدم الجديد (يفضل تشفير كلمة المرور بـ password_hash في المشاريع الحقيقية)
            $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
            if ($conn->query($sql) === TRUE) {
                $success = "تم إنشاء الحساب بنجاح! يمكنك الآن تسجيل الدخول.";
            } else {
                $error = "حدث خطأ ما، يرجى المحاولة لاحقاً.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Bloom</title>
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
        body { background-color: var(--brand-bg); color: var(--brand-text); font-family: 'Inter', sans-serif; }
        .main-header { background-color: var(--brand-green); padding: 12px 0; }
        .navbar-brand-custom { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: #FAF8F5 !important; text-decoration: none; }
        .brand-title-font { font-family: 'Playfair Display', serif; font-weight: 700; color: var(--brand-green); }
        .btn-bloom { background-color: var(--brand-green); color: #ffffff; border-radius: 8px; font-weight: 500; padding: 0.8rem 1.5rem; border: none; width: 100%; }
        .btn-bloom:hover { background-color: #2c3f32; color: #ffffff; }
        .auth-card { background: #ffffff; border-radius: 12px; border: 1px solid var(--brand-divider); padding: 35px; box-shadow: 0 10px 25px rgba(59, 83, 67, 0.05); }
    </style>
</head>
<body>

    <header class="main-header">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand-custom" href="index.php">Bloom</a>
            <a href="index.php" class="text-white text-decoration-none small"><i class="bi bi-arrow-left"></i> Back to Home</a>
        </div>
    </header>

    <div class="container my-5" style="max-width: 500px;">
        <div class="auth-card">
            <h2 class="brand-title-font mb-4 text-center">Create an Account</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-2 small"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success py-2 small"><?php echo $success; ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-secondary">Username</label>
                    <input type="text" name="username" class="form-control" required placeholder="Aseel / Meaad">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small text-secondary">Email Address</label>
                    <input type="email" name="email" class="form-control" required placeholder="name@example.com">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small text-secondary">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-bloom mb-3">Sign Up</button>
            </form>

            <div class="text-center small text-secondary">
                Already have an account? <a href="login.php" class="text-success fw-bold text-decoration-none">Login here</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>