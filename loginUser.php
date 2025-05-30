<?php
session_start();
include 'connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = $_POST['identifier']; // This will be either KP or email
    $password = $_POST['password'];

    // Check if the identifier is an email or KP
    $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);
    
    if ($isEmail) {
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    } else {
        $stmt = $conn->prepare("SELECT * FROM user WHERE kp = ?");
    }
    
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
            
            // Redirect to dashboard or home page
            header("Location: role/pemohon/dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid password";
            header("Location: loginUser.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "User not found";
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

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: url('background.png') repeat;
            background-size: 180px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrapper {
            background-color: rgba(255, 255, 255, 0.96);
            border-radius: 16px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 450px;
            margin: 20px;
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
            <img src="assets/ALLTRAS_logo.jpg" alt="ALLTRAS" height="60"><br>
            ALL REGION TRAVELLING SYSTEM
        </div>

       <!-- <form id="loginForm" onsubmit="return handleLogin(event)">-->
       <form id="loginForm" method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Kad Pengenalan atau Email</label>
                <input type="text" class="form-control" name="identifier" id="identifier" placeholder="Masukkan KP atau Email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Kata Laluan</label>
                <div class="input-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Kata Laluan" required>
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

       /* function handleLogin(event) {
            event.preventDefault();
            
            const identifier = document.getElementById('identifier').value;
            const password = document.getElementById('password').value;

            // Here you would typically make an API call to your backend
            console.log('Login attempt:', { identifier, password });
            
            // For now, we'll just show an alert
            alert('Login functionality will be implemented with backend integration');
            
            return false;
        }*/
    </script>
</body>
</html> 