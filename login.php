<?php
include 'db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // التحقق من كلمة المرور (أو مطابقتها مباشرة إذا لم تكن مشفرة)
        if ($password === $row['password'] || password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Incorrect password. Please try again.";
        }
    } else {
        $error = "No account found with this email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Bloom</title>

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
            --brand-text: #2f3e35;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--brand-bg);
            color: var(--brand-text);
            margin: 0;
            padding: 0;
        }

        .brand-title-font {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--brand-green);
        }

        .btn-bloom {
            background-color: var(--brand-green);
            color: #ffffff;
            border-radius: 8px;
            font-weight: 500;
            padding: 0.8rem;
            border: none;
            width: 100%;
            transition: background-color 0.2s;
        }

        .btn-bloom:hover {
            background-color: #2c3f32;
            color: #ffffff;
        }

        .card-box {
            background-color: #ffffff;
            border: 1px solid var(--brand-divider);
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 25px rgba(59, 83, 67, 0.05);
        }
    </style>
</head>
<body>

    <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100 py-5">
        <a href="index.php" class="brand-title-font fs-2 text-decoration-none mb-4">Bloom</a>
        
        <div class="card-box w-100" style="max-width: 420px;">
            <h3 class="brand-title-font text-center mb-4 fs-3">Welcome Back</h3>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-2 small text-center"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Email address</label>
                    <input type="email" name="email" class="form-control py-2" required placeholder="name@example.com">
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Password</label>
                    <input type="password" name="password" class="form-control py-2" required placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-bloom mb-3">Sign In</button>
            </form>

            <div class="text-center mt-3 small text-muted">
                Don't have an account? <a href="register.php" class="text-success text-decoration-none fw-bold">Sign Up</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>