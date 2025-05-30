<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from form
    $nama_first = $_POST['nama_first'];
    $nama_last = $_POST['nama_last'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $kp = $_POST['kp'];
    $bahagian = $_POST['bahagian'];
    $kataLaluan = $_POST['password'];
    $pengesahan = $_POST['confirmPassword'];

    // Basic validation
    if ($kataLaluan !== $pengesahan) {
        echo "<script>alert('Kata laluan dan pengesahan tidak sepadan'); window.history.back();</script>";
        exit();
    }

    // Hash the password
    $kataLaluanHash = password_hash($kataLaluan, PASSWORD_DEFAULT);

    // Insert into user table
    $stmt = $conn->prepare("INSERT INTO user (nama_first, nama_last, email, phone, kp, bahagian, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nama_first, $nama_last, $email, $phone, $kp, $bahagian, $kataLaluanHash);

    if ($stmt->execute()) {
        echo "<script>alert('Pendaftaran berjaya!'); window.location.href='loginUser.php';</script>";
    } else {
        echo "Ralat: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pengguna - ALLTRAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: url('background.png') repeat;
            background-size: 180px;
        }

        .form-wrapper {
            background-color: rgba(255, 255, 255, 0.96);
            border-radius: 16px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px 40px;
            max-width: 900px;
            margin: 40px auto;
        }

        .form-title {
            text-align: center;
            font-weight: 600;
            font-size: 22px;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 500;
        }

        .profile-img {
            display: block;
            margin: 20px auto;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #e9ecef;
            background-image: url('https://cdn-icons-png.flaticon.com/512/149/149071.png');
            background-size: 60%;
            background-repeat: no-repeat;
            background-position: center;
        }

        .form-control {
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="form-wrapper">
    <div class="form-title">
        <img src="assets/ALLTRAS_logo.jpg" alt="ALLTRAS" height="60"><br>
        ALL REGION TRAVELLING SYSTEM
    </div>

    <form action="registerUser.php" method="POST">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Pertama</label>
                <input type="text" class="form-control" name="nama_first" placeholder="Nama Pertama" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Akhir</label>
                <input type="text" class="form-control" name="nama_last" placeholder="Nama Akhir" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">No Telefon</label>
                <input type="text" class="form-control" name="phone" placeholder="No Telefon" maxlength="11" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Kad Pengenalan</label>
                <input type="text" class="form-control" name="kp" placeholder="Nombor KP" maxlength="14" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Bahagian</label>
                <select class="form-select" name="bahagian" required>
                    <option selected disabled>Pilih Bahagian</option>
                    <optgroup label="Bahagian Khidmat Pengurusan dan Sumber Manusia">
                        <option value="PENTADBIRAN AM">CAWANGAN PENTADBIRAN AM</option>
                        <option value="KEWANGAN">CAWANGAN KEWANGAN</option>
                        <option value="PEROLEHAN">CAWANGAN PEROLEHAN</option>
                        <option value="SUMBER MANUSIA">CAWANGAN SUMBER MANUSIA</option>
                        <option value="TEKNOLOGI MAKLUMAT">CAWANGAN TEKNOLOGI MAKLUMAT</option>
                        <option value="LATIHAN DAN KORPORAT">CAWANGAN LATIHAN DAN KORPORAT</option>
                    </optgroup>
                    <!-- Add more options as needed -->
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Kata Laluan</label>
                <input type="password" class="form-control" name="password" placeholder="Kata Laluan" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Pengesahan Kata Laluan</label>
                <input type="password" class="form-control" name="confirmPassword" placeholder="Pengesahan Kata Laluan" required>
            </div>

            <div class="col-md-12 text-center mt-4">
                <button type="submit" class="btn btn-primary px-4">Daftar</button>
            </div>
            <div class="col-md-12 text-center mt-4">
                <a href="loginUser.php" class="btn btn-primary px-4">Log Masuk Jika Sudah Mendaftar</a>
            </div>
        </div>
    </form>
</div>

</body>
</html>
