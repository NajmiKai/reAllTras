<?php
session_start();
include_once 'includes/config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if token is valid
    $stmt = $conn->prepare("SELECT * FROM admin WHERE reset_token = ? AND token_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows !== 1) {
        die("Invalid or expired token.");
    }

    $user = $result->fetch_assoc();

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $newPassword = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword !== $confirmPassword) {
            echo "<p style='color:red'>Kata laluan tidak sepadan.</p>";
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update password and clear token
            $update = $conn->prepare("UPDATE admin SET Password = ?, reset_token = NULL, token_expiry = NULL WHERE ID = ?");
            $update->bind_param("si", $hashedPassword, $user['ID']);
            $update->execute();

            echo $user['ID'];

            if ($update->execute()) {
                echo "<script>alert('Kata laluan sudah direset'); window.location.href = 'login.php';</script>";
            } else {
                echo "<script>alert('Kata laluan tidak dapat direset. Sila cuba lagi.'); window.location.href = 'login.php';</script>";
            }

            if (!$update->execute()) {
                echo "Error: " . $update->error;
            }
            
            exit;
        }
    }
} else {
    die("No token provided.");
}
?>

<style>
          body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('assets/backgroundAdmin.jpeg') no-repeat center 80%;
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
    </style>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - ALLTRAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" href="assets/ALLTRAS.png" type="image/x-icon">

</head>
<body style="background-color: #f0f2f5;">
    <div class="login-wrapper">
    <div class="login-title">
            <img src="assets/ALLTRAS_logo.jpg" alt="ALLTRAS" height="120"><br>
            ALL REGION TRAVELLING SYSTEM
        </div>

       <form method="POST" >
            <div class="mb-3">
                <label class="form-label">Kata Laluan Baru</label>
                <div class="input-group">
                <input type="text" class="form-control" name="password" id="password" placeholder="Masukkan Kata Laluan Baru" required>
                <span class="input-group-text p-0" style="height: 40px;">
                    <span class="d-flex align-items-center justify-content-center px-3" style="height: 100%; width: 100%; cursor: pointer;" onclick="togglePassword()">
                        <i class="fa-solid fa-eye" id="toggleIcon"></i>
                    </span>
                </span>
            </div>
            </div>

            <div class="mb-3">
            <label for="password" class="form-label">Pengesahan Kata Laluan Baru</label>
            <div class="input-group">
                <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Sahkan Kata Laluan Baru" required>
            </div>
        </div>

            <button type="submit" class="btn btn-primary btn-login">RESET KATA LALUAN</button>
        </form>
    </div>


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
