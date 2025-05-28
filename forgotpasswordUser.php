<?php
session_start();
include 'connection.php';

$showSuccess = isset($_GET['status']) && $_GET['status'] === 'sent';

?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Lupa Kata Laluan - ALLTRAS</title>
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
    </style>
</head>
<body>

    <div class="login-wrapper">
        <div class="login-title">
            <img src="assets/ALLTRAS_logo.jpg" alt="ALLTRAS" height="60"><br>
            ALL REGION TRAVELLING SYSTEM
        </div>

        <h4 class="text-center mb-3">Lupa Kata Laluan?</h4>

        <?php if ($showSuccess): ?>
            <div class="alert alert-success">
                ✅ Pautan reset telah dihantar. Sila periksa emel anda.
            </div>
        <?php endif; ?>
        
        <p class="text-center">Masukkan emel atau nombor KP anda dan kami akan hantarkan pautan untuk menetapkan semula kata laluan anda.</p>

        <form method="POST" action="send-reset-user.php">
            <div class="mb-3">
                <label for="identifier" class="form-label">Emel atau Kad Pengenalan</label>
                <input type="text" class="form-control" name="identifier" id="identifier" placeholder="Masukkan emel atau KP" required>
            </div>

            <button type="submit" class="btn btn-primary btn-login">Hantar Pautan Reset</button>
        </form>

        <div class="login-options mt-3">
            <a href="loginUser.php">← Kembali ke Log Masuk</a>
        </div>
    </div>

</body>
</html>
