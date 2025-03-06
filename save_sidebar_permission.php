<?php
require 'db.php';
session_start();

// Get the role ID and the selected permissions from the POST data.
$role_id = $_POST['role_id'] ?? null;
$selectedPermissions = $_POST['permissions'] ?? [];

if (!$role_id) {
    die("No role specified.");
}

try {
    // Start transaction
    $pdo->beginTransaction();

    // Delete existing permissions for this role
    $stmt = $pdo->prepare("DELETE FROM role_permissions WHERE role_id = ?");
    $stmt->execute([$role_id]);

    // Insert each selected permission into role_permissions
    if (!empty($selectedPermissions)) {
        $stmt = $pdo->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
        foreach ($selectedPermissions as $permission_id) {
            $stmt->execute([$role_id, $permission_id]);
        }
    }

    // Commit the transaction
    $pdo->commit();

    echo "Permissions updated for role ID: " . htmlspecialchars($role_id) . ". <a href='admin_sidebar_permission.php?role_id=" . urlencode($role_id) . "'>Go back</a>";
} catch (PDOException $e) {
    $pdo->rollBack();
    die("Error updating permissions: " . $e->getMessage());
}
?>
