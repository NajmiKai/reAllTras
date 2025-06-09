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

// Fetch all super admins
$super_admins_sql = "SELECT * FROM superAdmin ORDER BY ID DESC";
$super_admins_result = $conn->query($super_admins_sql);
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Senarai Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/userStyle.css">
</head>
<body>

<div class="main-container">
    <!-- Sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="col p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Senarai Super Admin</h3>
            <?php include 'includes/greeting.php'; ?>
        </div>

        <!-- Add Super Admin Button -->
        <div class="mb-4">
            <button type="button" class="btn btn-primary" onclick="showAddSuperAdminModal()">
                <i class="fas fa-plus"></i> Tambah Super Admin
            </button>
        </div>

        <!-- Super Admins Table -->
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
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($super_admin = $super_admins_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($super_admin['Name']); ?></td>
                                <td><?php echo htmlspecialchars($super_admin['Email']); ?></td>
                                <td><?php echo htmlspecialchars($super_admin['PhoneNo']); ?></td>
                                <td><?php echo htmlspecialchars($super_admin['ICNo']); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm" onclick="viewSuperAdmin(<?php echo $super_admin['ID']; ?>)">
                                            <i class="fas fa-eye"></i> Lihat
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteSuperAdmin(<?php echo $super_admin['ID']; ?>)">
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

<!-- Add Super Admin Modal -->
<div class="modal fade" id="addSuperAdminModal" tabindex="-1" aria-labelledby="addSuperAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSuperAdminModalLabel">Tambah Super Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addSuperAdminForm">
                    <div class="mb-3">
                        <label for="addName" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="addName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="addEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="addEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="addPhone" class="form-label">No. Telefon</label>
                        <input type="text" class="form-control" id="addPhone" name="phoneNo" required>
                    </div>
                    <div class="mb-3">
                        <label for="addIcNo" class="form-label">No. KP</label>
                        <input type="text" class="form-control" id="addIcNo" name="icNo" required>
                    </div>
                    <div class="mb-3">
                        <label for="addPassword" class="form-label">Kata Laluan</label>
                        <input type="password" class="form-control" id="addPassword" name="password" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="addSuperAdmin()">Tambah</button>
            </div>
        </div>
    </div>
</div>

<!-- View/Edit Super Admin Modal -->
<div class="modal fade" id="viewSuperAdminModal" tabindex="-1" aria-labelledby="viewSuperAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewSuperAdminModalLabel">Maklumat Super Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="superAdminDetails">
                <form id="editSuperAdminForm">
                    <input type="hidden" id="editSuperAdminId" name="id">
                    <div class="mb-3">
                        <label for="editName" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPhone" class="form-label">No. Telefon</label>
                        <input type="text" class="form-control" id="editPhone" name="phoneNo" required>
                    </div>
                    <div class="mb-3">
                        <label for="editIcNo" class="form-label">No. KP</label>
                        <input type="text" class="form-control" id="editIcNo" name="icNo" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPassword" class="form-label">Kata Laluan Baru (Kosongkan jika tidak mahu tukar)</label>
                        <input type="password" class="form-control" id="editPassword" name="password">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="saveSuperAdminChanges()">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function showAddSuperAdminModal() {
    document.getElementById('addSuperAdminForm').reset();
    new bootstrap.Modal(document.getElementById('addSuperAdminModal')).show();
}

function addSuperAdmin() {
    const form = document.getElementById('addSuperAdminForm');
    const formData = new FormData(form);
    
    // Convert FormData to JSON
    const jsonData = {};
    formData.forEach((value, key) => {
        jsonData[key] = value;
    });

    fetch('includes/addSuperAdmin.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(jsonData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Super Admin berjaya ditambah');
            location.reload();
        } else {
            alert('Ralat: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function viewSuperAdmin(superAdminId) {
    // Fetch super admin details via AJAX
    fetch(`includes/getSuperAdminDetails.php?id=${superAdminId}`)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('editSuperAdminId').value = data.ID;
            document.getElementById('editName').value = data.Name;
            document.getElementById('editEmail').value = data.Email;
            document.getElementById('editPhone').value = data.PhoneNo;
            document.getElementById('editIcNo').value = data.ICNo;
            document.getElementById('editPassword').value = ''; // Clear password field
            
            new bootstrap.Modal(document.getElementById('viewSuperAdminModal')).show();
        })
        .catch(error => console.error('Error:', error));
}

function saveSuperAdminChanges() {
    const form = document.getElementById('editSuperAdminForm');
    const formData = new FormData(form);
    
    // Convert FormData to JSON
    const jsonData = {};
    formData.forEach((value, key) => {
        if (value !== '') { // Only include non-empty values
            jsonData[key] = value;
        }
    });

    fetch('includes/updateSuperAdmin.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(jsonData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Maklumat Super Admin berjaya dikemaskini');
            location.reload();
        } else {
            alert('Ralat: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteSuperAdmin(superAdminId) {
    if (confirm('Adakah anda pasti mahu memadamkan Super Admin ini?')) {
        fetch(`includes/deleteSuperAdmin.php?id=${superAdminId}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Super Admin berjaya dipadamkan');
                location.reload();
            } else {
                alert('Ralat: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>
</body>
</html> 