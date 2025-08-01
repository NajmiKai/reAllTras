<?php
session_start();
include_once 'includes/config.php';
include 'includes/system_logger.php';

$timeoutMessage = '';
if (isset($_GET['timeout']) && $_GET['timeout'] == 1) {
    $timeoutMessage = "Your session has expired due to inactivity. Please log in again.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $name, $icNo, $email, $phoneNo, $hashedPassword, $role, $reset_token, $token_expiry);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            // Store user info in session
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_name'] = $name;
            $_SESSION['admin_role'] = $role;
            $_SESSION['admin_icNo'] = $icNo;
            $_SESSION['admin_email'] = $email;
            $_SESSION['admin_phoneNo'] = $phoneNo;           

            // Log successful login
            logAuthEvent($conn, 'login', 'admin', $icNo, true);
            
            // Redirect based on role
            if ($role === 'PBR CSM') {
                header("Location: role/CSM/PBR/dashboard.php");
                exit();
            } else if ($role === 'Pegawai Sulit CSM') {
                header("Location: role/CSM/pegawaiSulit/dashboard.php");
                exit();
            } else if ($role === 'Pengesah CSM') {
                header("Location: role/CSM/pengesah/dashboard.php");
                exit();
            } else if ($role === 'Penyemak HQ') {
                header("Location: role/HQ/penyemak/dashboard.php");
                exit();
            } else if ($role === 'Pengesah HQ') {
                header("Location: role/HQ/pengesah/dashboard.php");
                exit();
            } else if ($role === 'Pelulus HQ') {
                header("Location: role/HQ/pelulus/dashboard.php");
                exit();
            } else if ($role === 'Penyemak Baki Kewangan') {
                header("Location: role/kewangan/penyemakBaki/dashboard.php");
                exit();
            } else if ($role === 'Pengesah Kewangan') {
                header("Location: role/kewangan/pengesah/dashboard.php");
                exit();
            } else if ($role === 'Penyedia Kemudahan Kewangan') {
                header("Location: role/kewangan/penyediaKemudahan/dashboard.php");
                exit();
            } else {
                echo "<script>alert('Akses ditolak untuk peranan: $role'); window.location.href='login.php';</script>";
                exit();
            }
        } else {
            // Log failed login attempt
            logAuthEvent($conn, 'login', 'admin', $email, false);
            echo "<script>alert('Kata laluan salah'); window.location.href='login.php';</script>";
            exit();
        }
    } else {
        // Log failed login attempt
        logAuthEvent($conn, 'login', 'admin', $email, false);
        echo "<script>alert('Emel tidak ditemui'); window.location.href='login.php';</script>";
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

        .super-admin-link {
            position: fixed;
            bottom: 60px;
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

        .super-admin-link:hover {
            background-color: #5a6268;
            color: white;
            opacity: 1;
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
    <div class="login-wrapper">
        <div class="login-title">
            <img src="assets/ALLTRAS.png" alt="ALLTRAS" height="120">
            <img src="assets/JKDMLogo.png" alt="ALLTRAS" height="110"><br>
            ALL REGION TRAVELLING SYSTEM (ADMIN PANEL)
        </div>

        <?php if (!empty($timeoutMessage)): ?>
            <div style="color: red; margin-bottom: 10px;">
                <?php echo $timeoutMessage; ?>
            </div>
        <?php endif; ?>

       <form id="loginForm" method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Emel</label>
                <input type="text" class="form-control" name="email" id="email" placeholder="Masukkan Emel" required>
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
                <!-- <a href="registerAdmin.php">Daftar Akaun Baru</a> |  -->
                <a href="forgotpassword.php">Lupa Kata Laluan?</a>
            </div>
        </form>
    </div>

    <!-- <a href="loginSuperAdmin.php" class="super-admin-link">Super Admin Login</a><br> -->
    <a href="loginUser.php" class="admin-login-btn">User Login</a>

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