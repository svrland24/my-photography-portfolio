<?php
require_once __DIR__ . '/auth.php';

// Get current page script name for active navigation highlight
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Aperture Vision</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<div class="admin-layout">
    <!-- Admin Sidebar -->
    <aside class="admin-sidebar">
        <a href="/index.php" class="brand-logo" target="_blank" title="View Public Website">
            <i class="fa-solid fa-camera-retro"></i>
            <div class="brand-text">Aperture<span>Admin</span></div>
        </a>

        <ul class="admin-menu">
            <li>
                <a href="/admin/index.php" class="<?php echo $current_page == 'index.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="/admin/upload.php" class="<?php echo $current_page == 'upload.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload Photo
                </a>
            </li>
            <li>
                <a href="/admin/photos.php" class="<?php echo in_array($current_page, ['photos.php', 'edit_photo.php']) ? 'active' : ''; ?>">
                    <i class="fa-solid fa-images"></i> Manage Photos
                </a>
            </li>
            <li>
                <a href="/admin/categories.php" class="<?php echo $current_page == 'categories.php' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-folder-tree"></i> Categories
                </a>
            </li>
        </ul>

        <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
            <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-user-circle"></i> Logged as: <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>
            </div>
            <a href="/index.php" target="_blank" style="display: block; font-size: 0.85rem; color: var(--accent-glow); margin-bottom: 0.8rem;">
                <i class="fa-solid fa-globe"></i> View Live Website &rarr;
            </a>
            <a href="/admin/logout.php" class="btn-admin" style="width: 100%; justify-content: center; background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #f87171; box-shadow: none;">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="admin-main">
