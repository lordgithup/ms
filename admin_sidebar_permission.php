<?php
// Assume we have already fetched the current permissions for a given role
// and the list of all possible permissions from the "permissions" table.
$role_id = $_GET['role_id'] ?? 1; // e.g., 1 for ADMIN
$allPermissions = [
    ['id' => 1, 'name' => 'View Dashboard'],
    ['id' => 2, 'name' => 'Manage Users'],
    ['id' => 3, 'name' => 'Edit Profile'],
    ['id' => 4, 'name' => 'Add User'],
    ['id' => 5, 'name' => 'View Users']
];
// $assignedPermissions is an array of permission IDs currently assigned to this role.
$assignedPermissions = [1, 2, 3, 4, 5]; // Example

?>

<form method="post" action="save_sidebar_permission.php">
    <?php foreach ($allPermissions as $perm): ?>
        <label>
            <input type="checkbox" name="permissions[]" value="<?= $perm['id'] ?>"
            <?= in_array($perm['id'], $assignedPermissions) ? 'checked' : '' ?>>
            <?= htmlspecialchars($perm['name']) ?>
        </label><br>
    <?php endforeach; ?>
    <input type="hidden" name="role_id" value="<?= htmlspecialchars($role_id) ?>">
    <button type="submit">Save Permissions</button>
</form>
