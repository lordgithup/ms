<?php
// sidebar.php
require 'db.php';
session_start();

// For testing, set a default user ID if not logged in.
// Use user id 1 for ADMIN or 2 for USER.
$userId = $_SESSION['user_id'] ?? 1; 

// Step 1: Get the user's role
$stmt = $pdo->prepare("
    SELECT r.id, r.name 
    FROM users u 
    JOIN roles r ON u.role_id = r.id 
    WHERE u.id = ?
");
$stmt->execute([$userId]);
$role = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$role) {
    die("❌ User or role not found.");
}

// Step 2: Get permissions for the user's role
$stmt = $pdo->prepare("
    SELECT p.key_name 
    FROM role_permissions rp
    JOIN permissions p ON rp.permission_id = p.id
    WHERE rp.role_id = ?
");
$stmt->execute([$role['id']]);
$permissions = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (!$permissions) {
    die("❌ No permissions found for this role.");
}

// Prepare placeholders for permissions in SQL query
$placeholders = implode(',', array_fill(0, count($permissions), '?'));

// Step 3: Fetch allowed main menus
$stmt = $pdo->prepare("SELECT * FROM menus WHERE permission_key IN ($placeholders) ORDER BY sort_order");
$stmt->execute($permissions);
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Step 4: Fetch allowed submenus
$stmt = $pdo->prepare("SELECT * FROM submenus WHERE permission_key IN ($placeholders) ORDER BY sort_order");
$stmt->execute($permissions);
$submenus = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group submenus by their parent menu id
$groupedSubmenus = [];
foreach ($submenus as $submenu) {
    $groupedSubmenus[$submenu['menu_id']][] = $submenu;
}

// Step 5: Render the Sidebar
echo "<h3>Sidebar for {$role['name']}</h3>";
echo "<ul>";
foreach ($menus as $menu) {
    echo "<li>" . htmlspecialchars($menu['name']);
    if (isset($groupedSubmenus[$menu['id']])) {
        echo "<ul>";
        foreach ($groupedSubmenus[$menu['id']] as $submenu) {
            echo "<li><a href='" . htmlspecialchars($submenu['path']) . "'>" . htmlspecialchars($submenu['name']) . "</a></li>";
        }
        echo "</ul>";
    }
    echo "</li>";
}
echo "</ul>";
?>
