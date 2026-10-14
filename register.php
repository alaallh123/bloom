<?php
include 'db.php';
session_start();

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $error = 'Email is already registered.';
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = 'Something went wrong. Please try again.';
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
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        :root {
            --brand-green: #3B5343;
            --light-green: #e2f0d9;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-green);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .split-card {
            background: #ffffff;
            border-radius: 20px;
            width: 100%;
            max-width: 800px;
            display: flex;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            color: var(--brand-green);
            font-size: 1.3rem;
            text-decoration: none;
            z-index: 10;
        }

        .form-side {
            flex: 1;
            padding: 45px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .green-side {
            flex: 1;
            background-color: var(--brand-green);
            color: #ffffff;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .card-title-text {
            color: var(--brand-green);
            font-weight: 700;
            font-size: 1.6rem;
            margin-bottom: 25px;
        }

        .input-group-custom {
            margin-bottom: 18px;
            position: relative;
        }

        .input-group-custom i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            font-size: 1.1rem;
        }

        .input-line {
            width: 100%;
            padding: 10px 10px 10px 40px;
            border: none;
            border-bottom: 2px solid #e0e0e0;
            outline: none;
            font-size: 0.95rem;
            background: transparent;
            box-sizing: border-box;
        }

        .input-line:focus {
            border-bottom-color: var(--brand-green);
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.82rem;
            color: #555;
            margin-bottom: 20px;
        }

        .btn-signup {
            background-color: var(--brand-green);
            color: #fff;
            border: none;
            border-radius: 25px;
            padding: 12px 35px;
            font-weight: 600;
            cursor: pointer;
            width: fit-content;
        }

        .green-side h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .green-side p {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 20px;
        }

        .btn-outline-login {
            border: 2px solid #ffffff;
            color: #ffffff;
            background: transparent;
            border-radius: 25px;
            padding: 10px 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
        }

        @media (max-width: 768px) {
            .split-card {
                flex-direction: column;
            }
            .green-side {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="split-card">
        <!-- زر العودة -->
        <a href="index.php" class="back-link" title="Back to Home">
            <i class="bi bi-arrow-left"></i>
        </a>

        <!-- قسم النموذج على اليسار -->
        <div class="form-side">
            <h3 class="card-title-text">Create Account</h3>

            <?php if (!empty($error)): ?>
                <div style="color: #dc3545; font-size: 0.85rem; margin-bottom: 15px;"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="register.php" method="POST">
                <div class="input-group-custom">
                    <i class="bi bi-person"></i>
                    <input type="text" name="username" class="input-line" placeholder="Username" required>
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-envelope"></i>
                    <input type="email" name="email" class="input-line" placeholder="E-mail" required>
                </div>

                <div class="input-group-custom">
                    <i class="bi bi-lock"></i>
                    <input type="password" name="password" class="input-line" placeholder="Password" required>
                </div>

                <div class="checkbox-container">
                    <input type="checkbox" id="terms" required checked>
                    <label for="terms">I accept the terms of the agreement</label>
                </div>

                <button type="submit" class="btn-signup">Sign Up</button>
            </form>
        </div>

        <!-- القسم الأخضر الداكن على اليمين -->
        <div class="green-side">
            <h2>Get Started</h2>
            <p>Already have an account?</p>
            <a href="login.php" class="btn-outline-login">Log in</a>
        </div>
    </div>

</body>
</html>