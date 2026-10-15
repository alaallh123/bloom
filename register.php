<?php
include 'db.php';
session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = 'Email is already registered.';
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            $success = 'Account created successfully! You can now login.';
        } else {
            $error = 'Something went wrong. Please try again.';
        }
        $stmt->close();
    }
    $check->close();
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | Bloom</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
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

        .auth-container {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
            width: 820px;
            max-width: 95%;
            display: flex;
            overflow: hidden;
            min-height: 460px;
        }

        .form-section {
            flex: 1.2;
            padding: 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .action-section {
            flex: 1;
            background-color: #3B5343;
            color: #FAF8F5;
            padding: 45px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .form-title {
            font-weight: 700;
            font-size: 1.5rem;
            color: #2C3F32;
            margin-bottom: 25px;
        }

        .custom-input {
            border: none;
            border-bottom: 1px solid #CBD5E0;
            border-radius: 0;
            padding: 8px 0;
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

        .btn-submit {
            background-color: #3B5343;
            color: #fff;
            border-radius: 20px;
            padding: 8px 35px;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
            transition: background 0.2s;
        }

        .btn-submit:hover {
            background-color: #2C3F32;
            color: #fff;
        }

        .btn-outline-action {
            border: 1px solid #FAF8F5;
            color: #FAF8F5;
            border-radius: 20px;
            padding: 8px 35px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            background: transparent;
            transition: all 0.2s;
        }

        .btn-outline-action:hover {
            background-color: #FAF8F5;
            color: #3B5343;
        }

        .back-arrow {
            color: #718096;
            font-size: 1.1rem;
            text-decoration: none;
            margin-bottom: 15px;
            display: inline-block;
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <!-- Left: Form -->
        <div class="form-section">
            <a href="index.php" class="back-arrow"><i class="bi bi-arrow-left"></i></a>
            <h2 class="form-title">Create Account</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-1 small mb-3"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success py-1 small mb-3"><?php echo $success; ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <input type="text" name="name" class="form-control custom-input" placeholder="Username" required>
                <input type="email" name="email" class="form-control custom-input" placeholder="E-mail" required>
                <input type="password" name="password" class="form-control custom-input mb-4" placeholder="Password" required>
                
                <button type="submit" class="btn-submit">Sign Up</button>
            </form>
        </div>

        <!-- Right: Green Panel -->
        <div class="action-section">
            <h3 class="fw-bold fs-3 mb-2">Get Started</h3>
            <p class="small text-light opacity-75 mb-4">Already have an account?</p>
            <a href="login.php" class="btn-outline-action">Log In</a>
        </div>
    </div>

</body>
</html>