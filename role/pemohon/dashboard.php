<?php
session_start();
include '../../connection.php';

// Set user ID in session if not set
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Set default user ID to 1
}

// Fetch user data from database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

if (!$user_data) {
    header("Location: ../../login.php");
    exit();
}

$user_name = $user_data['nama_first'] . ' ' . $user_data['nama_last'];
$user_role = $user_data['bahagian'];
$user_icNo = $user_data['kp'];
$user_email = $user_data['email'];
$user_phoneNo = $user_data['phone'];

?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/userStyle.css">
</head>
<body>

<!-- Top Navbar -->
<nav class="navbar navbar-expand navbar-light bg-light shadow-sm px-3 mb-4 w-100">
    <ul class="navbar-nav me-auto">
        <li class="nav-item">
            <a class="nav-link toggle-sidebar" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <span class="nav-link fw-semibold"><?= htmlspecialchars($user_name) ?> (<?= htmlspecialchars($user_role) ?>)</span>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="../../logoutUser.php" class="nav-link text-danger">
                <i class="fas fa-sign-out-alt me-1"></i> Log Keluar
            </a>
        </li>
    </ul>
</nav>

<div class="main-container">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col p-4">
        <h3 class="mb-3">Laman Utama</h3>

        <div class="greeting-box">
            <?php  
                $time = date('H');
                if ($time < 12) {
                    $greeting = 'Selamat Pagi';
                } elseif ($time < 15) {
                    $greeting = 'Selamat Tengah Hari';
                } elseif ($time < 19) {
                    $greeting = 'Selamat Petang';    
                } else {
                    $greeting = 'Selamat Malam';
                }
            ?>
            <strong>Hi, <?= $greeting ?>!</strong> <?= $user_name ?>
        </div>

        <!-- Status Tracking Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-4">Status Permohonan</h5>
                <div class="status-tracker">
                    <?php
                    // Get the latest application status for the user
                    $status_sql = "SELECT id, kedudukan_permohonan, status_permohonan, tarikh_keputusan_csm1, ulasan_pbr_csm1, tarikh_keputusan_pengesah_csm1, ulasan_pengesah_csm1, tarikh_keputusan_pengesah_csm2, ulasan_pengesah_csm2, ulasan_pelulus_HQ, tarikh_keputusan_pelulus_HQ, tarikh_keputusan_penyediaKemudahan_kewangan 
                                 FROM wilayah_asal 
                                 WHERE user_kp = ? 
                                 ORDER BY id DESC LIMIT 1";
                    $status_stmt = $conn->prepare($status_sql);
                    $status_stmt->bind_param("i", $user_icNo);
                    $status_stmt->execute();
                    $status_result = $status_stmt->get_result();
                    $application_data = $status_result->fetch_assoc();

                    // Set wilayah_asal_id variable
                    $wilayah_asal_id = $application_data['id'];

                    // Check if user has any application and its status
                    $has_application = !empty($application_data['id']);
                    $is_pending_review = $has_application && $application_data['status_permohonan'] === 'Belum Disemak';

                    // Extract status and ulasan data
                    $current_status = $application_data['kedudukan_permohonan'] ?? 'Pemohon';
                    $application_status = $application_data['status_permohonan'] ?? 'Belum Disemak';
                    $tarikh_keputusan_csm1 = $application_data['tarikh_keputusan_csm1'] ?? null;
                    $ulasan_csm1 = $application_data['ulasan_pbr_csm1'] ?? null;
                    $ulasan_pengesah_csm1 = $application_data['ulasan_pengesah_csm1'] ?? null;
                    $ulasan_pengesah_csm2 = $application_data['ulasan_pengesah_csm2'] ?? null;
                    $ulasan_hq = $application_data['ulasan_pelulus_HQ'] ?? null;
                    $tarikh_keputusan_hq = $application_data['tarikh_keputusan_pelulus_HQ'] ?? null;
                    $tarikh_keputusan_kewangan = $application_data['tarikh_keputusan_penyediaKemudahan_kewangan'] ?? null;

                    // Determine status color class
                    $status_color_class = '';
                    switch ($application_status) {
                        case 'Belum Disemak':
                            $status_color_class = 'status-belum-disemak';
                            break;
                        case 'Selesai':
                            $status_color_class = 'status-selesai';
                            break;
                        case 'Dikuiri':
                            $status_color_class = 'status-dikuiri';
                            break;
                        case 'Tolak':
                            $status_color_class = 'status-tolak';
                            break;
                        case 'Lulus':
                            $status_color_class = 'status-lulus';
                            break;
                        default:
                            $status_color_class = ''; // Default or no special class
                            break;
                    }

                    $stages = [
                        'Pemohon' => ['icon' => 'fa-user', 'label' => 'Pemohon'],
                        'CSM' => ['icon' => 'fa-users', 'label' => 'CSM'],
                        'HQ' => ['icon' => 'fa-building', 'label' => 'Ibu Pejabat'],
                        'CSM2' => ['icon' => 'fa-users', 'label' => 'CSM'],
                        'Kewangan' => ['icon' => 'fa-money-bill', 'label' => 'Kewangan'],
                        'Keputusan' => ['icon' => 'fa-check-circle', 'label' => 'Keputusan']
                    ];

                    $current_index = array_search($current_status, array_keys($stages));
                    if ($current_status === 'Kewangan') {
                        $current_index = 4;
                    }
                    // If status is Selesai and position is Pemohon, show Keputusan
                    if ($application_status === 'Selesai' && $current_status === 'Pemohon') {
                        $current_status = 'Keputusan';
                        $current_index = 5;
                        $show_download_button = true;
                        $ulasan_to_display = "Permohonan Selesai, E-tiket sedia untuk dimuat turun";
                    }
                    ?>

                    <div class="d-flex justify-content-between position-relative">
                        <div class="progress-line"></div>
                        <?php foreach ($stages as $key => $stage): ?>
                            <?php
                            $is_active = array_search($key, array_keys($stages)) <= $current_index;
                            $is_current = $key === $current_status;
                            ?>
                            <div class="status-step <?= $is_active ? 'active' : '' ?> <?= $is_current ? 'current' : '' ?>">
                                <div class="status-icon">
                                    <i class="fas <?= $stage['icon'] ?>"></i>
                                </div>
                                <div class="status-label"><?= $stage['label'] ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Detailed Status and Ulasan Section -->
                <div class="card-body mt-4 text-center-custom">
                    <h5 class="card-title mb-3">Status Terperinci Permohonan</h5>
                    
                    <?php if (!$has_application): ?>
                        <div class="alert alert-info">
                            <p>Anda belum membuat permohonan. Sila lengkapkan borang permohonan untuk meneruskan.</p>
                            <a href="borangWA.php" class="btn btn-primary">Buat Permohonan</a>
                        </div>
                    <?php elseif ($is_pending_review): ?>
                        <div class="alert alert-warning">
                            <p>Permohonan anda sedang menunggu untuk disemak.</p>
                        </div>
                    <?php else: ?>
                        <p class="<?= $status_color_class ?>"><strong>Status Semasa:</strong> <?= htmlspecialchars($application_status) ?></p>

                        <?php
                        $ulasan_to_display = "Tiada Kuiri / Ulasan buat masa ini";
                        $show_download_button = false;

                        switch ($current_status) {
                            case 'Pemohon':
                                if ($is_pending_review) {
                                    $ulasan_to_display = "Permohonan anda sedang menunggu untuk disemak.";
                                } else {
                                    $ulasan_to_display = "Permohonan anda sedang diproses.";
                                }
                                break;

                            case 'CSM':
                                if (!empty($ulasan_pengesah_csm1)) {
                                    $ulasan_to_display = $ulasan_pengesah_csm1;
                                } elseif (!empty($ulasan_csm1)) {
                                    $ulasan_to_display = $ulasan_csm1;
                                } else {
                                    $ulasan_to_display = "Tiada Kuiri atau Tindakan Diperlukan";
                                }
                                break;

                            case 'HQ':
                                if ($application_status === 'Lulus') {
                                    $ulasan_to_display = "Permohonan Diluluskan";
                                } elseif ($application_status === 'Tolak') {
                                    $ulasan_to_display = "Permohonan Ditolak";
                                } else {
                                    $ulasan_to_display = "Permohonan sedang diproses di Ibu Pejabat";
                                }
                                break;

                            case 'CSM2':
                                if (!empty($ulasan_pengesah_csm2)) {
                                    $ulasan_to_display = $ulasan_pengesah_csm2;
                                } else {
                                    $ulasan_to_display = "Tiada Kuiri atau Tindakan Diperlukan";
                                }
                                break;

                            case 'Kewangan':
                                if ($application_status === 'Selesai' && !empty($tarikh_keputusan_kewangan)) {
                                    $ulasan_to_display = "Permohonan Selesai, Waran Udara sedia untuk dimuat turun";
                                    $show_download_button = true;
                                } else {
                                    $ulasan_to_display = "Permohonan sedang diproses di Cawangan Kewangan";
                                }
                                break;

                            default:
                                $ulasan_to_display = "Tiada Kuiri / Ulasan buat masa ini";
                                break;
                        }
                        ?>

                        <div class="mt-3">
                            
                            <p><?= nl2br(htmlspecialchars($ulasan_to_display)) ?></p>
                            <?php if ($show_download_button): 
                                // Fetch E-ticket document
                                $eticket_sql = "SELECT * FROM documents WHERE wilayah_asal_id = ? AND description = 'E-tiket' ORDER BY upload_date DESC LIMIT 1";
                                $eticket_stmt = $conn->prepare($eticket_sql);
                                $eticket_stmt->bind_param("i", $wilayah_asal_id);
                                $eticket_stmt->execute();
                                $eticket_result = $eticket_stmt->get_result();
                                $eticket_doc = $eticket_result->fetch_assoc();
                                
                                if ($eticket_doc): ?>
                                    <a href="/reAllTras/<?= str_replace('../../../', '', htmlspecialchars($eticket_doc['file_path'])) ?>" target="_blank" class="btn btn-primary mt-2">
                                        <i class="fas fa-download me-2"></i>Muat Turun E-tiket
                                    </a>
                                <?php else: ?>
                                    <a href="wilayahAsal.php" class="btn btn-primary mt-2">Lihat Permohonan</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="container my-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Carta Aliran Permohonan</h5>
                            <h6 class="text-primary mb-3">Wilayah Asal</h6>
                            <img src="../../assets/flowchart-wilayah.jpg" alt="Carta Aliran Wilayah Asal" class="img-fluid rounded clickable-image" style="cursor:pointer;">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Carta Aliran Permohonan</h5>
                            <h6 class="text-danger mb-3">Tugas Rasmi / Kursus</h6>
                            <img src="../../assets/flowchart-tugasrasmi.jpg" alt="Carta Aliran Tugas Rasmi" class="img-fluid rounded clickable-image" style="cursor:pointer;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
            <div class="modal-body p-0">
                <img src="" id="modalImage" class="img-fluid rounded" alt="Expanded Image">
            </div>
            <div class="modal-footer p-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
        </div>


    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelector('.toggle-sidebar').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('sidebar').classList.toggle('hidden');
    });


    // Image Modal
    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
    const modalImage = document.getElementById('modalImage');

    document.querySelectorAll('.clickable-image').forEach(img => {
        img.addEventListener('click', () => {
        modalImage.src = img.src;
        imageModal.show();
        });
    });

    console.log('Wilayah Asal ID:', <?= json_encode($wilayah_asal_id) ?>);
</script>
</body>
</html>
