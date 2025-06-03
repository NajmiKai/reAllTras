<?php
// Display existing documents
if (!empty($documents)) {
    echo '<div class="table-responsive mb-4">';
    echo '<table class="table table-striped">';
    echo '<thead><tr>';
    echo '<th>Nama Fail</th>';
    echo '<th>Penerangan</th>';
    echo '<th>Tarikh Muat Naik</th>';
    echo '<th>Tindakan</th>';
    echo '</tr></thead><tbody>';
    
    foreach ($documents as $doc) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($doc['file_name']) . '</td>';
        echo '<td>' . htmlspecialchars($doc['description']) . '</td>';
        echo '<td>' . date('d/m/Y H:i', strtotime($doc['upload_date'])) . '</td>';
        echo '<td>';
        echo '<a href="' . htmlspecialchars($doc['file_path']) . '" class="btn btn-sm btn-primary" target="_blank">Lihat</a>';
        echo '</td>';
        echo '</tr>';
    }
    
    echo '</tbody></table>';
    echo '</div>';
} else {
    echo '<div class="alert alert-info">Tiada dokumen dimuat naik.</div>';
}
?>

<!-- Upload New Document Form -->
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Muat Naik Dokumen Baru</h5>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="upload_document" value="1">
            
            <div class="mb-3">
                <label class="form-label">Dokumen</label>
                <input type="file" class="form-control" name="document" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Penerangan</label>
                <textarea class="form-control" name="description" rows="3" required></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Muat Naik Dokumen</button>
        </form>
    </div>
</div> 