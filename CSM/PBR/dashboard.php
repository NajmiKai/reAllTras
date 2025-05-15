<?php
// hi guys
// Sample data (you can replace these with database values)
$user = "sumber manusia";
$stats = [
    "total" => ["Wilayah Asal" => 22, "Tugas Rasmi" => 12],
    "processing" => ["Wilayah Asal" => 7, "Tugas Rasmi" => 12],
    "approved" => ["Wilayah Asal" => 14, "Tugas Rasmi" => 0],
    "rejected" => ["Wilayah Asal" => 14, "Tugas Rasmi" => 0]
];
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fa;
        }
        .sidebar {
            background-color: #212529;
            color: white;
            min-height: 100vh;
            padding: 30px 20px;
        }
        .sidebar a {
            color: #d1d1d1;
            text-decoration: none;
            padding: 10px;
            display: block;
            border-radius: 6px;
            margin-bottom: 8px;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #0d6efd;
            color: white;
        }
        .card-box {
            color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card-box i {
            font-size: 1.5rem;
            float: right;
        }
        .greeting-box {
            background-color: #fefefe;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar col-md-2">
        <h4 class="mb-4"><img src="logo.png" alt="ALLTRAS" class="mb-2" width="130"><br>ALL REGION TRAVELLING SYSTEM</h4>
        <a href="#" class="active"><i class="fas fa-home me-2"></i>Laman Utama</a>
        <h6 class="text-muted mt-4">BORANG PERMOHONAN</h6>
        <a href="#"><i class="fas fa-map-marker-alt me-2"></i>Wilayah Asal</a>
        <a href="#"><i class="fas fa-tasks me-2"></i>Tugas Rasmi / Kursus</a>
    </div>

    <!-- Main Content -->
    <div class="col-md-10 p-4">
        <h3 class="mb-3">Laman Utama</h3>

        <div class="greeting-box">
            <strong>Hi, Selamat Pagi!</strong> <?= $user ?>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card-box bg-primary">
                    <i class="fas fa-user-plus"></i>
                    <h6>Jumlah Permohonan</h6>
                    <p>Wilayah Asal: <?= $stats['total']['Wilayah Asal'] ?></p>
                    <p>Tugas Rasmi: <?= $stats['total']['Tugas Rasmi'] ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-box bg-success">
                    <i class="fas fa-spinner"></i>
                    <h6>Sedang Diproses</h6>
                    <p>Wilayah Asal: <?= $stats['processing']['Wilayah Asal'] ?></p>
                    <p>Tugas Rasmi: <?= $stats['processing']['Tugas Rasmi'] ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-box bg-warning">
                    <i class="fas fa-check-circle"></i>
                    <h6>Berjaya Diproses</h6>
                    <p>Wilayah Asal: <?= $stats['approved']['Wilayah Asal'] ?></p>
                    <p>Tugas Rasmi: <?= $stats['approved']['Tugas Rasmi'] ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-box bg-danger">
                    <i class="fas fa-times-circle"></i>
                    <h6>Permohonan Gagal</h6>
                    <p>Wilayah Asal: <?= $stats['rejected']['Wilayah Asal'] ?></p>
                    <p>Tugas Rasmi: <?= $stats['rejected']['Tugas Rasmi'] ?></p>
                </div>
            </div>
        </div>
		
		
		<div class="container my-4">
  <div class="row g-4">
    <!-- Wilayah Asal Column -->
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">Carta Aliran Permohonan</h5>
          <h6 class="text-primary mb-3">Wilayah Asal <a href="#" class="float-end text-decoration-none text-muted"><i class="bi bi-zoom-in"></i> zoom</a></h6>
          <img src="flowchart-wilayah.png" alt="Carta Aliran Wilayah Asal" class="img-fluid rounded">
          <div class="mt-3">
            <p class="mb-1">Tempahan Tiket Tambang Penerbangan Bagi: <strong>Wilayah Asal</strong></p>
            <a href="#" class="btn btn-primary w-100">+ Mohon Permohonan Wilayah Asal</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Tugas Rasmi/Kursus Column -->
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">Carta Aliran Permohonan</h5>
          <h6 class="text-danger mb-3">Tugas Rasmi / Kursus <a href="#" class="float-end text-decoration-none text-muted"><i class="bi bi-zoom-in"></i> zoom</a></h6>
          <img src="flowchart-rasmi.png" alt="Carta Aliran Tugas Rasmi" class="img-fluid rounded">
          <div class="mt-3">
            <p class="mb-1">Tempahan Tiket Tambang Penerbangan Bagi: <strong>Tugas Rasmi / Kursus</strong></p>
            <a href="#" class="btn btn-primary w-100">+ Mohon Permohonan Tugas Rasmi / Kursus</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


        <h5 class="mb-3">Senarai Pemohon Wilayah Asal (Pengurusan)</h5>
        <div class="card shadow-sm">
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No ID</th>
                            <th>Nama</th>
                            <th>Jawatan</th>
                            <th>Telefon</th>
                            <th>Wilayah Asal</th>
                            <th>Tarikh Memohon</th>
                            <th>Status</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="8" class="text-center text-muted">Tiada rekod dijumpai</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
</body>
</html>
