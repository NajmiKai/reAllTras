<?php
session_start();
include_once 'includes/config.php';
date_default_timezone_set('Asia/Kuala_Lumpur');
include 'includes/system_logger.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = $_POST['identifier']; // This will be email only
    $password = $_POST['password'];

    //testing auto pull from server
    // Only allow email
    if (!filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Sila masukkan emel yang sah";
        header("Location: loginUser.php");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $nama_first, $nama_last, $email, $phone, $kp, $bahagian, $hashedPassword, $created_at, $updated_at, $reset_token, $token_expiry);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            // Store user info in session
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $nama_first . " " . $nama_last;
            $_SESSION['user_kp'] = $kp;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_phone'] = $phone;
            $_SESSION['user_bahagian'] = $bahagian;
            
            // Log successful login
            logAuthEvent($conn, 'login', 'user', $kp, true);
            
            // Redirect to dashboard or home page
            header("Location: role/pemohon/dashboard.php");
            exit();
        } else {
            // Log failed login attempt
            logAuthEvent($conn, 'login', 'user', $identifier, false);
            $_SESSION['error'] = "Kata laluan tidak sah";
            header("Location: loginUser.php");
            exit();
        }
    } else {
        // Log failed login attempt
        logAuthEvent($conn, 'login', 'user', $identifier, false);
        $_SESSION['error'] = "Emel tidak dijumpai";
        header("Location: loginUser.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Log Masuk - ALLTRAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" href="assets/ALLTRAS.png" type="image/x-icon">

    <style>
       body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('assets/background.jpeg') no-repeat center 80%;
            min-height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover;
            padding: 20px;
            transition: all 0.3s ease-in-out;
        }


        .login-wrapper {
            background-color: rgba(255, 255, 255, 0.96);
            border-radius: 16px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
            width: 100%;
            max-width: 450px;
            position: center;
        }

        .login-title {
            text-align: center;
            font-weight: 600;
            font-size: 22px;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            font-weight: 500;
            border-radius: 8px;
            margin-top: 10px;
        }

        .login-options {
            text-align: center;
            margin-top: 20px;
        }

        .login-options a {
            color: #0d6efd;
            text-decoration: none;
        }

        .login-options a:hover {
            text-decoration: underline;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }

        .admin-login-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            opacity: 0;
            transition: opacity 0.3s ease;
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .admin-login-btn:hover {
            background-color: #5a6268;
            color: white;
            opacity: 1;
        }
    </style>
</head>
<body>
    <a href="login.php" class="admin-login-btn">
        <i class="fas fa-user-shield"></i> Admin Login
    </a>
    <div class="login-wrapper">
        <div class="login-title">
            <img src="assets/ALLTRAS.png" alt="ALLTRAS" height="120">
            <img src="assets/JKDMLogo.png" alt="ALLTRAS" height="110"><br>
            ALL REGION TRAVELLING SYSTEM
        </div>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form id="loginForm" method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Emel</label>
                <input type="email" class="form-control" name="identifier" id="identifier" placeholder="Masukkan Emel" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Kata Laluan</label>
                <div class="input-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan Kata Laluan" required>
                    <span class="input-group-text p-0" style="height: 50px;">
                        <span class="d-flex align-items-center justify-content-center px-3" style="height: 100%; width: 100%; cursor: pointer;" onclick="togglePassword()">
                            <i class="fa-solid fa-eye" id="toggleIcon"></i>
                        </span>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-login">Log Masuk</button>

            <div class="login-options">
                <a href="registerUser.php">Daftar Akaun Baru</a> | 
                <a href="forgotpasswordUser.php">Lupa Kata Laluan?</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html> 