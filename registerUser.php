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

}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pengguna - ALLTRAS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" href="assets/ALLTRAS.png" type="image/x-icon">

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

        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #ced4da;
            border-radius: 8px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }
    </style>
</head>
<body>

<div class="form-wrapper">
    <div class="form-title">
        <img src="assets/ALLTRAS_logo.jpg" alt="ALLTRAS" height="120"><br>
        ALL REGION TRAVELLING SYSTEM
    </div>

    <form action="registerUser.php" method="POST">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Pertama</label>
                <input type="text" class="form-control" name="nama_first" placeholder="cth: AHMAD ALI" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nama Akhir</label>
                <input type="text" class="form-control" name="nama_last" placeholder="cth: BIN ABU" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" placeholder="cth: aAliAbu@customs.gov.my" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nombor Telefon</label>
                <input type="text" class="form-control" name="phone" placeholder="cth: 60179813005" maxlength="11" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Kad Pengenalan</label>
                <input type="text" class="form-control" name="kp" id="kp" placeholder="cth: 010203-04-0506" maxlength="14" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Cawangan / Unit</label>
                <select class="form-select select2" name="bahagian" required>
                    <option selected disabled>Pilih Cawangan / Unit</option>
                    <?php
                    $query = "SELECT id, nama_cawangan FROM organisasi ORDER BY nama_cawangan";
                    $result = $conn->query($query);
                    while($row = $result->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['nama_cawangan']) . "'>" . htmlspecialchars($row['nama_cawangan']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Kata Laluan</label>
                <div style="position: relative;">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Kata Laluan" required>
                    <button type="button" class="btn btn-outline-secondary btn-sm" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);" onclick="togglePassword('password', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Pengesahan Kata Laluan</label>
                <div style="position: relative;">
                    <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Pengesahan Kata Laluan" required>
                    <button type="button" class="btn btn-outline-secondary btn-sm" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);" onclick="togglePassword('confirmPassword', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <div class="col-md-12 text-center mt-4">
                <a href="loginUser.php" class="btn btn-secondary px-4">Kembali</a>
                <button type="submit" class="btn btn-primary px-4">Daftar</button>
            </div>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Cari Bahagian...",
            allowClear: true,
            width: '100%'
        });
    });
    function togglePassword(fieldId, btn) {
        var input = document.getElementById(fieldId);
        var icon = btn.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }

    // Format IC number as XXXXXX-XX-XXXX while typing
    document.getElementById('kp').addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9]/g, '');
        let formatted = '';
        if (value.length > 6) {
            formatted += value.substr(0, 6) + '-';
            if (value.length > 8) {
                formatted += value.substr(6, 2) + '-';
                formatted += value.substr(8, 4);
            } else {
                formatted += value.substr(6);
            }
        } else {
            formatted = value;
        }
        e.target.value = formatted;
    });

    // Remove dashes before submitting the form
    document.querySelector('form').addEventListener('submit', function(e) {
        var kpInput = document.getElementById('kp');
        kpInput.value = kpInput.value.replace(/-/g, '');
    });
</script>

</body>
</html>
