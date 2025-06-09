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

// Fetch all admins
$admins_sql = "SELECT * FROM admin ORDER BY ID DESC";
$admins_result = $conn->query($admins_sql);

// Fetch all roles
$roles_sql = "SELECT role FROM adminRole";
$roles_result = $conn->query($roles_sql);
$roles = [];
while($role = $roles_result->fetch_assoc()) {
    $roles[] = $role['role'];
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>ALLTRAS - Senarai Admin</title>
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
            <h3 class="mb-0">Senarai Admin</h3>
            <?php include 'includes/greeting.php'; ?>
        </div>

        <!-- Admins Table -->
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
                                <th>Peranan</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($admin = $admins_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($admin['Name']); ?></td>
                                <td><?php echo htmlspecialchars($admin['Email']); ?></td>
                                <td><?php echo htmlspecialchars($admin['PhoneNo']); ?></td>
                                <td><?php echo htmlspecialchars($admin['ICNo']); ?></td>
                                <td><?php echo htmlspecialchars($admin['Role']); ?></td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm" onclick="viewAdmin(<?php echo $admin['ID']; ?>)">
                                            <i class="fas fa-eye"></i> Lihat
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteAdmin(<?php echo $admin['ID']; ?>)">
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

<!-- View Admin Modal -->
<div class="modal fade" id="viewAdminModal" tabindex="-1" aria-labelledby="viewAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAdminModalLabel">Maklumat Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="adminDetails">
                <form id="editAdminForm">
                    <input type="hidden" id="editAdminId" name="id">
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
                        <label for="editRole" class="form-label">Peranan</label>
                        <select class="form-select" id="editRole" name="role" required>
                            <?php foreach($roles as $role): ?>
                            <option value="<?php echo htmlspecialchars($role); ?>"><?php echo htmlspecialchars($role); ?></option>
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
                <button type="button" class="btn btn-primary" onclick="saveAdminChanges()">Simpan Perubahan</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function viewAdmin(adminId) {
    // Fetch admin details via AJAX
    fetch(`includes/getAdminDetails.php?id=${adminId}`)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('editAdminId').value = data.ID;
            document.getElementById('editName').value = data.Name;
            document.getElementById('editEmail').value = data.Email;
            document.getElementById('editPhone').value = data.PhoneNo;
            document.getElementById('editIcNo').value = data.ICNo;
            document.getElementById('editRole').value = data.Role;
            document.getElementById('editPassword').value = ''; // Clear password field
            
            new bootstrap.Modal(document.getElementById('viewAdminModal')).show();
        })
        .catch(error => console.error('Error:', error));
}

function saveAdminChanges() {
    const form = document.getElementById('editAdminForm');
    const formData = new FormData(form);
    
    // Convert FormData to JSON
    const jsonData = {};
    formData.forEach((value, key) => {
        if (value !== '') { // Only include non-empty values
            jsonData[key] = value;
        }
    });

    fetch('includes/updateAdmin.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(jsonData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Maklumat admin berjaya dikemaskini');
            location.reload();
        } else {
            alert('Ralat: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteAdmin(adminId) {
    if (confirm('Adakah anda pasti mahu memadamkan admin ini?')) {
        fetch(`includes/deleteAdmin.php?id=${adminId}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Admin berjaya dipadamkan');
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