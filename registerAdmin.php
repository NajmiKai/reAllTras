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

        input[type="file"] {
            border: 1px solid #ced4da;
            padding: 10px;
            border-radius: 8px;
        }

        .form-control {
            border-radius: 8px;
        }
    </style>
</head>
<body>

<div class="form-wrapper">
    <div class="form-title">
        <img src="logo.png" alt="ALLTRAS" height="60"><br>
        ALL REGION TRAVELLING SYSTEM
    </div>

    <form>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" placeholder="Masukan Nama">
            </div>
            <div class="col-md-6">
                <label class="form-label">Kad Pengenalan / Nama Pengguna</label>
                <input type="text" class="form-control" value="sumber@kastam.my" readonly>
            </div>

            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" placeholder="Masukan Email">
            </div>
            <div class="col-md-6">
                <label class="form-label">No Telefon</label>
                <input type="text" class="form-control" placeholder="Masukan No Telefon">
            </div>

            <div class="col-md-6">
                <label class="form-label">Kata Laluan</label>
                <input type="password" class="form-control" placeholder="Kata Laluan">
            </div>
            <div class="col-md-6">
                <label class="form-label">Pengesahan Kata Laluan</label>
                <input type="password" class="form-control" placeholder="Pengesahan Kata Laluan">
            </div>

            <div class="col-md-12">
                <label class="form-label">Bahagian</label>
                <select class="form-select">
                    <option selected disabled>Pilih Bahagian</option>
                    <option>Bahagian A</option>
                    <option>Bahagian B</option>
                </select>
            </div>


            <div class="col-md-12 text-center mt-4">
                <button type="submit" class="btn btn-primary px-4">Daftar</button>
            </div>
			 <div class="col-md-12 text-center mt-4">
                <button type="submit" class="btn btn-primary px-4">Log Masuk Jika Sudah Mendaftar</button>
            </div>
        </div>
    </form>
</div>

</body>
</html>
