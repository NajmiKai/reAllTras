<?php
function logSystemEvent($conn, $event_type, $user_type, $user_id, $action, $description = '', $affected_table = null, $affected_record_id = null, $old_value = null, $new_value = null) {
    try {
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        
        $stmt = $conn->prepare("INSERT INTO system_logs (
            event_type, user_type, user_id, action, description, 
            affected_table, affected_record_id, old_value, new_value,
            ip_address, user_agent
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("ssssssissss", 
            $event_type,
            $user_type,
            $user_id,
            $action,
            $description,
            $affected_table,
            $affected_record_id,
            $old_value,
            $new_value,
            $ip_address,
            $user_agent
        );
        
        $stmt->execute();
        $stmt->close();
        return true;
    } catch (Exception $e) {
        error_log("Failed to log system event: " . $e->getMessage());
        return false;
    }
}

// Helper function to log user authentication events
function logAuthEvent($conn, $event_type, $user_type, $user_id, $success = true) {
    $action = $event_type === 'login' ? 'User Login' : 'User Logout';
    $description = $event_type === 'login' 
        ? ($success ? 'Successful login attempt' : 'Failed login attempt')
        : 'User logged out';
    
    return logSystemEvent($conn, $event_type, $user_type, $user_id, $action, $description);
}

// Helper function to log document events
function logDocumentEvent($conn, $event_type, $user_type, $user_id, $document_name, $document_id = null) {
    $action = ucfirst(str_replace('_', ' ', $event_type));
    $description = "Document: " . $document_name;
    
    return logSystemEvent($conn, $event_type, $user_type, $user_id, $action, $description, 'documents', $document_id);
}

// Helper function to log data changes
function logDataChange($conn, $user_type, $user_id, $table_name, $record_id, $old_value, $new_value, $description = '') {
    $action = 'Data Update';
    if (empty($description)) {
        $description = "Updated record in {$table_name}";
    }
    
    return logSystemEvent($conn, 'data_update', $user_type, $user_id, $action, $description, $table_name, $record_id, $old_value, $new_value);
}

// Helper function to log status changes
function logStatusChange($conn, $user_type, $user_id, $table_name, $record_id, $old_status, $new_status, $description = '') {
    $action = 'Status Change';
    if (empty($description)) {
        $description = "Status changed from {$old_status} to {$new_status}";
    }
    
    return logSystemEvent($conn, 'status_change', $user_type, $user_id, $action, $description, $table_name, $record_id, $old_status, $new_status);
}

// Helper function to log errors
function logError($conn, $user_type, $user_id, $error_message, $error_details = '') {
    $action = 'System Error';
    $description = $error_message;
    if (!empty($error_details)) {
        $description .= " - Details: " . $error_details;
    }
    
    return logSystemEvent($conn, 'error', $user_type, $user_id, $action, $description);
}

// Helper function to log data creation
function logDataCreate($conn, $user_type, $user_id, $table_name, $record_id, $description = '') {
    $action = 'Data Creation';
    if (empty($description)) {
        $description = "Created new record in {$table_name}";
    }
    
    return logSystemEvent($conn, 'data_create', $user_type, $user_id, $action, $description, $table_name, $record_id);
}

// Helper function to log data deletion
function logDataDelete($conn, $user_type, $user_id, $table_name, $record_id, $description = '') {
    $action = 'Data Deletion';
    if (empty($description)) {
        $description = "Deleted record from {$table_name}";
    }
    
    return logSystemEvent($conn, 'data_delete', $user_type, $user_id, $action, $description, $table_name, $record_id);
}

// Helper function to log user profile updates
function logProfileUpdate($conn, $user_type, $user_id, $field_name, $old_value, $new_value) {
    $action = 'Profile Update';
    $description = "Updated {$field_name}";
    
    return logSystemEvent($conn, 'data_update', $user_type, $user_id, $action, $description, 'user_profile', null, $old_value, $new_value);
}

// Helper function to log application status changes
function logApplicationStatusChange($conn, $user_type, $user_id, $application_id, $old_status, $new_status, $description = '') {
    $action = 'Application Status Change';
    if (empty($description)) {
        $description = "Application status changed from {$old_status} to {$new_status}";
    }
    
    return logSystemEvent($conn, 'status_change', $user_type, $user_id, $action, $description, 'wilayah_asal', $application_id, $old_status, $new_status);
}
?> 