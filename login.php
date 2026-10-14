<?php
include 'db.php';
session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            $error = 'Invalid password.';
        }
    } else {
        $error = 'No user found with this email.';
    }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Bloom</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        :root {
            --brand-green: #3B5343;
            --light-bg: #FAF8F5;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--brand-green);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .auth-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 40px 35px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            position: relative;
            text-align: center;
        }

        .back-link {
            position: absolute;
            top: 25px;
            left: 25px;
            color: var(--brand-green);
            font-size: 1.3rem;
            text-decoration: none;
        }

        .bloom-logo {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--brand-green);
            margin-top: 5px;
            margin-bottom: 2px;
            display: block;
        }

        .welcome-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #2f3e35;
            margin-bottom: 25px;
        }

        .field-group {
            text-align: left;
            margin-bottom: 18px;
        }

        .field-label {
            display: block;
            font-size: 0.85rem;
            color: #555;
            margin-bottom: 6px;
        }

        .field-input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.9rem;
            outline: none;
            box-sizing: border-box;
        }

        .field-input:focus {
            border-color: var(--brand-green);
        }

        .btn-signin {
            background-color: var(--brand-green);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            padding: 12px;
            width: 100%;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .forgot-pass {
            float: right;
            color: var(--brand-green);
            text-decoration: none;
            font-size: 0.8rem;
        }

        .footer-link {
            font-size: 0.85rem;
            color: #666;
        }

        .footer-link a {
            color: var(--brand-green);
            font-weight: 600;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <!-- زر العودة -->
        <a href="index.php" class="back-link" title="Back to Home">
            <i class="bi bi-arrow-left"></i>
        </a>

        <!-- شعار Bloom الخطّي والعنوان -->
        <span class="bloom-logo">Bloom</span>
        <h3 class="welcome-title">Welcome back!</h3>

        <?php if (!empty($error)): ?>
            <div style="color: #dc3545; font-size: 0.85rem; margin-bottom: 15px;"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="field-group">
                <label class="field-label">Login</label>
                <input type="email" name="email" class="field-input" placeholder="Email or mobile number" required>
            </div>

            <div class="field-group">
                <label class="field-label">
                    Password
                    <a href="#" class="forgot-pass">forgot password ?</a>
                </label>
                <input type="password" name="password" class="field-input" placeholder="Enter password" required>
            </div>

            <button type="submit" class="btn-signin">Sign In</button>
        </form>

        <p class="footer-link">
            Don't have an account? <a href="register.php">Sign up</a>
        </p>
    </div>

</body>
</html>