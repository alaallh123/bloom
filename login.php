<?php
include 'db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            header("Location: index.php");
            exit();
        } else {
            $error = 'Invalid password.';
        }
    } else {
        $error = 'No account found with this email.';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Bloom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #3B5343; /* خلفية الصفحة خضراء كاملة باللون المطلوب */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            width: 460px;
            max-width: 95%;
            padding: 45px;
            position: relative;
        }

        .brand-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: #3B5343;
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 5px;
        }

        .login-subtitle {
            text-align: center;
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .custom-input {
            border: none;
            border-bottom: 1px solid #CBD5E0;
            border-radius: 0;
            padding: 10px 0;
            font-size: 0.9rem;
            background: transparent;
            color: #2D3748;
            margin-bottom: 20px;
        }

        .custom-input:focus {
            box-shadow: none;
            border-color: #3B5343;
            background: transparent;
        }

        .btn-signin {
            background-color: #3B5343;
            color: #fff;
            border-radius: 10px;
            padding: 12px;
            width: 100%;
            font-weight: 600;
            border: none;
            margin-top: 10px;
            transition: background 0.2s;
        }

        .btn-signin:hover {
            background-color: #2C3F32;
            color: #fff;
        }

        .back-arrow {
            color: #718096;
            font-size: 1.1rem;
            text-decoration: none;
            position: absolute;
            top: 25px;
            left: 25px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <a href="index.php" class="back-arrow"><i class="bi bi-arrow-left"></i></a>
        
        <h2 class="brand-title">Bloom</h2>
        <p class="login-subtitle">Welcome back!</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-1 small mb-3"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <input type="email" name="email" class="form-control custom-input" placeholder="Email or mobile number" required>
            
            <div class="d-flex justify-content-between align-items-center mt-1">
                <input type="password" name="password" class="form-control custom-input w-100" placeholder="Enter password" required>
            </div>
            <div class="text-end mb-4" style="margin-top: -15px;">
                <a href="#" class="text-muted small text-decoration-none">Forgot password?</a>
            </div>

            <button type="submit" class="btn-signin">Sign In</button>

            <div class="text-center small mt-4 text-muted">
                Don't have an account? <a href="register.php" class="text-success fw-bold text-decoration-none">Sign up</a>
            </div>
        </form>
    </div>

</body>
</html>