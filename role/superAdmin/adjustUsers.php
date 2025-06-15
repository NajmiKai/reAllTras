<?php
session_start();
include '../../connection.php';

// Fetch user data from database
$super_admin_id = $_SESSION['super_admin_id'];
$sql = "SELECT * FROM superAdmin WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $super_admin_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

if (!$user_data) {
    header("Location: ../../loginSuperAdmin.php");
    exit();
}

$user_name = $user_data['Name'];
$user_icNo = $user_data['ICNo'];
$user_email = $user_data['Email'];
$user_phoneNo = $user_data['PhoneNo'];

// Fetch all users
$users_sql = "SELECT * FROM user ORDER BY created_at DESC";
$users_result = $conn->query($users_sql);

// Fetch all bahagian from organisasi table
$bahagian_sql = "SELECT nama_cawangan FROM organisasi ORDER BY nama_cawangan";
$bahagian_result = $conn->query($bahagian_sql);
$bahagian_list = [];
while($bahagian = $bahagian_result->fetch_assoc()) {
    $bahagian_list[] = $bahagian['nama_cawangan'];
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Senarai Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/css/userStyle.css">
</head>
<body>

<div class="main-container">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Senarai Pengguna</h3>
            <?php include 'includes/greeting.php'; ?>
        </div>

        <div class="mb-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-plus"></i> Tambah Pengguna
            </button>
        </div>

        <!-- Users Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No. Telefon</th>
                                <th>No. KP</th>
                                <th>Bahagian</th>
                                <th>Tarikh Daftar</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($user = $users_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['nama_first'] . ' ' . $user['nama_last']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                <td><?php echo htmlspecialchars($user['kp']); ?></td>
                                <td><?php echo htmlspecialchars($user['bahagian']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm" onclick="viewUser(<?php echo $user['id']; ?>)">
                                            <i class="fas fa-eye"></i> Lihat
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteUser(<?php echo $user['id']; ?>)">
                                            <i class="fas fa-trash"></i> Padam
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewUserModalLabel">Maklumat Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="userDetails">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId" name="id">
                    <div class="mb-3">
                        <label for="editNamaFirst" class="form-label">Nama Pertama</label>
                        <input type="text" class="form-control" id="editNamaFirst" name="nama_first" required>
                    </div>
                    <div class="mb-3">
                        <label for="editNamaLast" class="form-label">Nama Akhir</label>
                        <input type="text" class="form-control" id="editNamaLast" name="nama_last" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPhone" class="form-label">No. Telefon</label>
                        <input type="text" class="form-control" id="editPhone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="editKp" class="form-label">No. KP</label>
                        <input type="text" class="form-control" id="editKp" name="kp" required>
                    </div>
                    <div class="mb-3">
                        <label for="editBahagian" class="form-label">Bahagian</label>
                        <select class="form-select" id="editBahagian" name="bahagian" required>
                            <option value="">Pilih Bahagian</option>
                            <?php foreach($bahagian_list as $bahagian): ?>
                            <option value="<?php echo htmlspecialchars($bahagian); ?>"><?php echo htmlspecialchars($bahagian); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editPassword" class="form-label">Kata Laluan Baru (Kosongkan jika tidak mahu tukar)</label>
                        <input type="password" class="form-control" id="editPassword" name="password">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="saveUserChanges()">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm">
                    <div class="mb-3">
                        <label for="nama_first" class="form-label">Nama Pertama</label>
                        <input type="text" class="form-control" id="nama_first" name="nama_first" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_last" class="form-label">Nama Akhir</label>
                        <input type="text" class="form-control" id="nama_last" name="nama_last" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">No. Telefon</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="kp" class="form-label">No. KP</label>
                        <input type="text" class="form-control" id="kp" name="kp" required>
                    </div>
                    <div class="mb-3">
                        <label for="bahagian" class="form-label">Bahagian</label>
                        <select class="form-select" id="bahagian" name="bahagian" required>
                            <option value="">Pilih Bahagian</option>
                            <?php foreach($bahagian_list as $bahagian): ?>
                            <option value="<?php echo htmlspecialchars($bahagian); ?>"><?php echo htmlspecialchars($bahagian); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Laluan</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Sahkan Kata Laluan</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="addNewUser()">Tambah Pengguna</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
// Initialize Select2 for bahagian dropdowns
$(document).ready(function() {
    $('#bahagian, #editBahagian').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Cari bahagian...',
        allowClear: true,
        language: {
            noResults: function() {
                return "Tiada bahagian dijumpai";
            },
            searching: function() {
                return "Mencari...";
            }
        }
    });
});

function viewUser(userId) {
    // Fetch user details via AJAX
    fetch(`includes/getUserDetails.php?id=${userId}`)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('editUserId').value = data.id;
            document.getElementById('editNamaFirst').value = data.nama_first;
            document.getElementById('editNamaLast').value = data.nama_last;
            document.getElementById('editEmail').value = data.email;
            document.getElementById('editPhone').value = data.phone;
            document.getElementById('editKp').value = data.kp;
            $('#editBahagian').val(data.bahagian).trigger('change'); // Update Select2
            document.getElementById('editPassword').value = ''; // Clear password field
            
            new bootstrap.Modal(document.getElementById('viewUserModal')).show();
        })
        .catch(error => console.error('Error:', error));
}

function saveUserChanges() {
    const form = document.getElementById('editUserForm');
    const formData = new FormData(form);
    
    // Convert FormData to JSON
    const jsonData = {};
    formData.forEach((value, key) => {
        if (value !== '') { // Only include non-empty values
            jsonData[key] = value;
        }
    });

    fetch('includes/updateUser.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(jsonData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Maklumat pengguna berjaya dikemaskini');
            location.reload();
        } else {
            alert('Ralat: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteUser(userId) {
    if (confirm('Adakah anda pasti mahu memadamkan pengguna ini?')) {
        fetch(`includes/deleteUser.php?id=${userId}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Pengguna berjaya dipadamkan');
                location.reload();
            } else {
                alert('Ralat: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function addNewUser() {
    const form = document.getElementById('addUserForm');
    const formData = new FormData(form);
    
    // Check if passwords match
    const password = formData.get('password');
    const confirmPassword = formData.get('confirm_password');
    
    if (password !== confirmPassword) {
        alert('Kata laluan tidak sepadan. Sila cuba lagi.');
        return;
    }
    
    // Convert FormData to JSON
    const jsonData = {};
    formData.forEach((value, key) => {
        if (key !== 'confirm_password') { // Don't send confirm_password to server
            jsonData[key] = value;
        }
    });

    fetch('includes/addUser.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(jsonData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Pengguna berjaya ditambah');
            location.reload();
        } else {
            alert('Ralat: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ralat sistem. Sila cuba lagi.');
    });
}
</script>
</body>
</html>
