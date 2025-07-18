<?php
session_start();
include_once '../../includes/config.php';

// Fetch user data from database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

if (!$user_data) {
    header("Location: ../../loginUser.php");
    exit();
}
//test Task Scheduler Windows

$user_name = $user_data['nama_first'] . ' ' . $user_data['nama_last'];
$user_role = $user_data['bahagian'];
$user_icNo = $user_data['kp'];
$user_email = $user_data['email'];
$user_phoneNo = $user_data['phone'];

function getUlasan($application_data) {
    if (!empty($application_data['ulasan_pengesah_kewangan'])) {
        $_SESSION['wilayah_asal_ulasan'] = $application_data['ulasan_pengesah_kewangan'];
        return $application_data['ulasan_pengesah_kewangan'];
    }
    if (!empty($application_data['ulasan_penyemakBaki_kewangan'])) {
        $_SESSION['wilayah_asal_ulasan'] = $application_data['ulasan_penyemakBaki_kewangan'];
        return $application_data['ulasan_penyemakBaki_kewangan'];
    }
    if (!empty($application_data['ulasan_pelulus_HQ'])) {
        $_SESSION['wilayah_asal_ulasan'] = $application_data['ulasan_pelulus_HQ'];
        return $application_data['ulasan_pelulus_HQ'];
    }
    if (!empty($application_data['ulasan_pengesah_HQ'])) {
        $_SESSION['wilayah_asal_ulasan'] = $application_data['ulasan_pengesah_HQ'];
        return $application_data['ulasan_pengesah_HQ'];
    }
    if (!empty($application_data['ulasan_penyemak_HQ'])) {
        $_SESSION['wilayah_asal_ulasan'] = $application_data['ulasan_penyemak_HQ'];
        return $application_data['ulasan_penyemak_HQ'];
    }
    if (!empty($application_data['ulasan_pengesah_csm2'])) {
        $_SESSION['wilayah_asal_ulasan'] = $application_data['ulasan_pengesah_csm2'];
        return $application_data['ulasan_pengesah_csm2'];
    }
    if (!empty($application_data['ulasan_pengesah_csm1'])) {
        $_SESSION['wilayah_asal_ulasan'] = $application_data['ulasan_pengesah_csm1'];
        return $application_data['ulasan_pengesah_csm1'];
    }
    if (!empty($application_data['ulasan_pbr_csm1'])) {
        $_SESSION['wilayah_asal_ulasan'] = $application_data['ulasan_pbr_csm1'];
        return $application_data['ulasan_pbr_csm1'];
    }
    $_SESSION['wilayah_asal_ulasan'] = "Tiada Kuiri yang dapat dikesan";
    return "Tiada Kuiri yang dapat dikesan";
}

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
    <link rel="icon" href="../../../assets/ALLTRAS.png" type="image/x-icon">
</head>
<body>


<div class="main-container">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col p-4"> 
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
            <h4 class="page-title font-size-20 d-none d-sm-block mb-1 "><b>SELAMAT DATANG KE ALLTRAS</b></h4>
            <h6 class="mb-0 text-muted"><b>JKDM WILAYAH PERSEKUTUAN KUALA LUMPUR</b></h6>
        </div>
        

        <div class="mt-3 mt-md-0">
            <?php include 'includes/greeting.php'; ?>
        </div>
    </div>
        <!-- Status Tracking Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title mb-4">Status Permohonan</h5>
                <div class="status-tracker">
                    <?php
                    // Get the latest application status for the user
                    $status_sql = "SELECT * FROM wilayah_asal 
                                 WHERE user_kp = ? 
                                 ORDER BY id DESC LIMIT 1";
                    $status_stmt = $conn->prepare($status_sql);
                    $status_stmt->bind_param("s", $user_icNo);
                    $status_stmt->execute();
                    $status_result = $status_stmt->get_result();
                    $application_data = $status_result->fetch_assoc();

                    // Check if wilayah_asal_matang needs to be updated based on existing tarikh_pengesahan_user
                    if ($application_data && $application_data['tarikh_pengesahan_user']) {
                        $tarikh_pengesahan = new DateTime($application_data['tarikh_pengesahan_user']);
                        $current_date = new DateTime();
                        $next_year = clone $tarikh_pengesahan;
                        $next_year->modify('+1 year');
                        
                        // If current date is in or after the next year, update wilayah_asal_matang
                        if ($current_date >= $next_year && !$application_data['wilayah_asal_matang']) {
                            $update_sql = "UPDATE wilayah_asal SET wilayah_asal_matang = true WHERE id = ?";
                            $update_stmt = $conn->prepare($update_sql);
                            $update_stmt->bind_param("i", $application_data['id']);
                            $update_stmt->execute();
                            $application_data['wilayah_asal_matang'] = true;
                            
                        }
                    }

                    // Set wilayah_asal_id variable - set to null if wilayah_asal_matang is true
                    $wilayah_asal_id = ($application_data && !$application_data['wilayah_asal_matang']) ? $application_data['id'] : null;
                    $_SESSION['wilayah_asal_id'] = $wilayah_asal_id; // Store wilayah_asal_id in session

                    // Define stages
                    $stages = [
                        'Pemohon' => ['icon' => 'fa-user', 'label' => 'Pemohon'],
                        'CSM' => ['icon' => 'fa-users', 'label' => 'CSM'],
                        'HQ' => ['icon' => 'fa-building', 'label' => 'Ibu Pejabat'],
                        'CSM2' => ['icon' => 'fa-users', 'label' => 'CSM'],
                        'Kewangan' => ['icon' => 'fa-money-bill', 'label' => 'Kewangan'],
                        'Selesai' => ['icon' => 'fa-check-circle', 'label' => 'Selesai']
                    ];

                    // Determine current stage and status
                    $current_stage = 'Pemohon';
                    $show_description = true;
                    $description = "Anda belum membuat permohonan!";
                    $action_button = ['text' => 'Buat Permohonan', 'link' => 'borangWA.php'];
                    $show_action_button = false;

                    if ($wilayah_asal_id) {

                        if ($application_data['status_permohonan'] === 'Batal'){
                            $current_stage = 'Pemohon';
                            $show_description = true;
                            $description = "Maaf permohonan anda dibatalkan oleh Super Admin";
                            if (!empty($application_data['ulasan_superadmin'])) {
                                $description .= ": " . $application_data['ulasan_superadmin'];
                            }
                            $show_action_button = false;
                        }
                        
                        //Stage UI (Pemohon)
                        if ($application_data['wilayah_asal_from_stage'] !== 'Hantar') {
                            $current_stage = 'Pemohon';
                            $show_description = true;
                            
                            switch ($application_data['wilayah_asal_from_stage']) {
                                case 'BorangWA2':
                                    $description = "Borang ini belum lengkap! Sila lengkapkan untuk menghantar permohonan";
                                    $action_button = ['text' => 'Lengkapkan Borang', 'link' => 'borangWA2.php'];
                                    $show_action_button = true;
                                    break;
                                case 'BorangWA3':
                                    $description = "Borang ini belum lengkap! Sila lengkapkan untuk menghantar permohonan";
                                    $action_button = ['text' => 'Lengkapkan Borang', 'link' => 'borangWA3.php'];
                                    $show_action_button = true;
                                    break;
                                case 'BorangWA4':
                                    $description = "Borang ini belum lengkap! Sila lengkapkan untuk menghantar permohonan";
                                    $action_button = ['text' => 'Lengkapkan Borang', 'link' => 'borangWA4.php'];
                                    $show_action_button = true;
                                    break;
                                case 'BorangWA5':
                                    $description = "Borang ini belum lengkap! Sila lengkapkan untuk menghantar permohonan";
                                    $action_button = ['text' => 'Lengkapkan Borang', 'link' => 'borangWA5.php'];
                                    $show_action_button = true;
                                    break;
                            }
                        } 
                        else if ($application_data['kedudukan_permohonan'] === 'Pemohon' && $application_data['status_permohonan'] === 'Dikuiri') {
                            
                            $current_stage = 'Pemohon';
                            $show_description = true;
                            
                            
                            // Get CSM1's name from admin table
                            $csm_sql = "SELECT a.Name FROM admin a WHERE a.ID = ?";
                            $csm_stmt = $conn->prepare($csm_sql);
                            $csm_stmt->bind_param("i", $application_data['pbr_csm1_id']);
                            $csm_stmt->execute();
                            $csm_result = $csm_stmt->get_result();
                            $csm_data = $csm_result->fetch_assoc();
                            $csm_name = $csm_data['Name'] ?? 'Unknown';                            // Check which ulasan to display
                            $ulasan = getUlasan($application_data);
                            $description = "Ulasan daripada " . $csm_name . " (Cawangan Sumber Manusia): " . $ulasan;
                            $action_button = ['text' => 'Lihat Permohonan', 'link' => 'wilayahAsal.php'];
                            $show_action_button = true;

                        }

                        //status_permohonan ENUM('Belum Disemak','Selesai','Dikuiri', 'Tolak', 'Lulus') DEFAULT 'Belum Disemak',
                        //kedudukan_permohonan ENUM('Pemohon','CSM', 'HQ', 'CSM2', 'Kewangan') DEFAULT 'Pemohon',
                        //Stage UI (CSM)

                        else if ($application_data['kedudukan_permohonan'] === 'Pemohon'){

                            if($application_data['status_permohonan'] === 'Belum Disemak'){
                                $current_stage = 'CSM';
                                $description = "Sebarang pindaan tarikh penerbangan hendaklah dimaklumkan kepada Cawangan Kewangan";
                                $show_description = true;
                            }
                            else if ($application_data['status_permohonan'] === 'Selesai'){
                                $current_stage = 'CSM';
                                $description = "Sebarang pindaan tarikh penerbangan hendaklah dimaklumkan kepada Cawangan Kewangan";
                                $show_description = true;
                            }
                            else if ($application_data['status_permohonan'] === 'Tolak'){
                                $current_stage = 'CSM';
                                $show_description = true;
                                $description = "Harap Maaf, Permohonan anda ditolak.";
                            }
                            else if ($application_data['status_permohonan'] === 'Lulus'){
                                $current_stage = 'CSM';
                                $description = "Sebarang pindaan tarikh penerbangan hendaklah dimaklumkan kepada Cawangan Kewangan";
                                $show_description = true;
                            }

                        } else if ($application_data['kedudukan_permohonan'] === 'CSM') {

                            if($application_data['status_permohonan'] === 'Belum Disemak'){
                                $current_stage = 'HQ';
                                $description = "Sebarang pindaan tarikh penerbangan hendaklah dimaklumkan kepada Cawangan Kewangan";
                                $show_description = true;
                            }
                            else if ($application_data['status_permohonan'] === 'Selesai'){
                                $current_stage = 'HQ';
                                $description = "Sebarang pindaan tarikh penerbangan hendaklah dimaklumkan kepada Cawangan Kewangan";
                                $show_description = true;
                            }
                            else if ($application_data['status_permohonan'] === 'Tolak'){
                                $current_stage = 'HQ';
                                $show_description = true;
                                $description = "Harap Maaf, Permohonan anda ditolak.";
                            }
                            else if ($application_data['status_permohonan'] === 'Lulus'){
                                $current_stage = 'HQ';
                                $description = "Sebarang pindaan tarikh penerbangan hendaklah dimaklumkan kepada Cawangan Kewangan";
                                $show_description = true;
                            }
                            else if ($application_data['status_permohonan'] === 'Dikuiri'){
                                $current_stage = 'CSM';
                                $show_description = false;
                            }

                        } else if ($application_data['kedudukan_permohonan'] === 'HQ') {

                            if($application_data['status_permohonan'] === 'Belum Disemak'){
                                $current_stage = 'CSM2';
                                $description = "Sebarang pindaan tarikh penerbangan hendaklah dimaklumkan kepada Cawangan Kewangan";
                                $show_description = true;
                            }
                            else if ($application_data['status_permohonan'] === 'Selesai'){
                                $current_stage = 'CSM2';
                                $description = "Sebarang pindaan tarikh penerbangan hendaklah dimaklumkan kepada Cawangan Kewangan";
                                $show_description = true;
                            }
                            else if ($application_data['status_permohonan'] === 'Tolak'){
                                $current_stage = 'HQ';
                                $show_description = true;
                                $description = "Harap Maaf, Permohonan anda ditolak.";
                            }
                            else if ($application_data['status_permohonan'] === 'Lulus'){
                                $current_stage = 'CSM2';
                                $description = "Sebarang pindaan tarikh penerbangan hendaklah dimaklumkan kepada Cawangan Kewangan";
                                $show_description = true;
                            }
                            else if ($application_data['status_permohonan'] === 'Dikuiri'){
                                $current_stage = 'HQ';
                                $show_description = false;
                            }
                            
                        } else if ($application_data['kedudukan_permohonan'] === 'CSM2') {
                            if($application_data['status_permohonan'] === 'Belum Disemak'){
                                $current_stage = 'Kewangan';
                                $description = "Sebarang pindaan tarikh penerbangan hendaklah dimaklumkan kepada Cawangan Kewangan";
                                $show_description = true;
                            }
                            else if ($application_data['status_permohonan'] === 'Selesai'){
                                $current_stage = 'Kewangan';
                                $description = "Sebarang pindaan tarikh penerbangan hendaklah dimaklumkan kepada Cawangan Kewangan";
                                $show_description = true;
                            }
                            else if ($application_data['status_permohonan'] === 'Tolak'){
                                $current_stage = 'Kewangan';
                                $show_description = true;
                                $description = "Harap Maaf, Permohonan anda ditolak.";
                            }
                            else if ($application_data['status_permohonan'] === 'Lulus'){
                                $current_stage = 'Kewangan';
                                $description = "Sebarang pindaan tarikh penerbangan hendaklah dimaklumkan kepada Cawangan Kewangan";
                                $show_description = true;
                            }
                            else if ($application_data['status_permohonan'] === 'Dikuiri'){
                                $current_stage = 'CSM2';
                                $show_description = false;
                            }
                        } else if ($application_data['kedudukan_permohonan'] === 'Kewangan') {
                            if($application_data['status_permohonan'] === 'Selesai'){
                                $current_stage = 'Selesai';
                                $show_description = true;
                                $description = "Permohonan anda sudah selesai! Sila tekan butang dibawah untuk ke halaman Waran Udara";
                                $action_button = ['text' => 'Muat Turun Waran Udara/E-Ticket', 'link' => 'wilayahAsalSelesai.php'];
                                $show_action_button = true;
                            }
                            else if($application_data['status_permohonan'] === 'Belum Disemak'){
                                $current_stage = 'Kewangan';
                                $description = "Sebarang pindaan tarikh penerbangan hendaklah dimaklumkan kepada Cawangan Kewangan";
                                $show_description = true;
                            }
                            else if ($application_data['status_permohonan'] === 'Tolak'){
                                $current_stage = 'Kewangan';
                                $show_description = true;
                                $description = "Harap Maaf, Permohonan anda ditolak.";
                            }
                            else if ($application_data['status_permohonan'] === 'Lulus'){
                                $current_stage = 'Kewangan';
                                $description = "Sebarang pindaan tarikh penerbangan hendaklah dimaklumkan kepada Cawangan Kewangan";
                                $show_description = true;
                            }
                            else if ($application_data['status_permohonan'] === 'Dikuiri'){
                                $current_stage = 'Kewangan';
                                $show_description = false;
                            }
                        }

                        
                    } else {

                        $current_stage = 'Pemohon';
                        $show_description = true;
                        $description = "Anda belum membuat permohonan!";
                        $action_button = ['text' => 'Buat Permohonan', 'link' => 'borangWA.php'];
                        $show_action_button = true;

                    }
                    ?>

                    <div class="d-flex justify-content-between position-relative">
                        <div class="progress-line"></div>
                        <?php foreach ($stages as $key => $stage): ?>
                            <?php
                            $is_active = array_search($key, array_keys($stages)) <= array_search($current_stage, array_keys($stages));
                            $is_current = $key === $current_stage;
                            ?>
                            <div class="status-step <?= $is_active ? 'active' : '' ?> <?= $is_current ? 'current' : '' ?>">
                                <div class="status-icon">
                                    <i class="fas <?= $stage['icon'] ?>"></i>
                                </div>
                                <div class="status-label"><?= $stage['label'] ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if ($show_description): ?>
                        <div class="alert <?= 
                            ($application_data && isset($application_data['status_permohonan']) && isset($application_data['kedudukan_permohonan']) && 
                            $application_data['status_permohonan'] === 'Dikuiri' && $application_data['kedudukan_permohonan'] === 'Pemohon') 
                                ? 'alert-warning' 
                                : (($application_data && isset($application_data['status_permohonan']) && isset($application_data['kedudukan_permohonan']) && 
                                    $application_data['status_permohonan'] === 'Tolak' || $application_data['status_permohonan'] === 'Batal')
                                    ? 'alert-danger' 
                                    : 'alert-info') 
                        ?> mt-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-2x me-3"></i>
                                <div>
                                    <p class="mb-2"><?= $description ?></p>
                                    <?php if ($action_button && $show_action_button): ?>
                                        <a href="<?= $action_button['link'] ?>" class="btn btn-primary">
                                            <i class="fas fa-arrow-right me-2"></i><?= $action_button['text'] ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
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
    console.log('PBR CSM1 ID:', <?= json_encode($application_data['pbr_csm1_id'] ?? null) ?>);
</script>
</body>
</html>
