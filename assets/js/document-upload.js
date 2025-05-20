document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const uploadButton = document.getElementById('uploadButton');
    const fileList = document.getElementById('fileList');
    const descriptionInput = document.getElementById('description');
    let filesToUpload = [];

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    // Highlight drop zone when dragging over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    // Handle dropped files
    dropZone.addEventListener('drop', handleDrop, false);

    // Handle file input change
    fileInput.addEventListener('change', handleFileSelect, false);

    // Handle upload button click
    uploadButton.addEventListener('click', uploadFiles);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        dropZone.classList.add('highlight');
    }

    function unhighlight(e) {
        dropZone.classList.remove('highlight');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }

    function handleFileSelect(e) {
        const files = e.target.files;
        handleFiles(files);
    }

    function handleFiles(files) {
        filesToUpload = [...filesToUpload, ...files];
        updateFileList();
    }

    function updateFileList() {
        fileList.innerHTML = '';
        filesToUpload.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            fileItem.innerHTML = `
                <span>${file.name}</span>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeFile(${index})">
                    <i class="fas fa-times"></i>
                </button>
            `;
            fileList.appendChild(fileItem);
        });
    }

    function removeFile(index) {
        filesToUpload.splice(index, 1);
        updateFileList();
    }

    function uploadFiles() {
        if (filesToUpload.length === 0) {
            alert('Please select files to upload');
            return;
        }

        const formData = new FormData();
        filesToUpload.forEach(file => {
            formData.append('documents[]', file);
        });
        formData.append('wilayah_asal_id', document.getElementById('wilayah_asal_id').value);
        formData.append('description', descriptionInput.value);

        uploadButton.disabled = true;
        uploadButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';

        fetch('functions/document_upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Files uploaded successfully!');
                filesToUpload = [];
                updateFileList();
                descriptionInput.value = '';
            } else {
                alert('Error uploading files: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error uploading files: ' + error.message);
        })
        .finally(() => {
            uploadButton.disabled = false;
            uploadButton.innerHTML = 'Upload Files';
        });
    }

    // Make removeFile function globally available
    window.removeFile = removeFile;
}); 