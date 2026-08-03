<?php
require_once __DIR__ . '/admin_header.php';

$success = '';
$error = '';

if (!isset($pdo) || $pdo === null) {
    $error = "Database connection error. " . ($db_error ?? '');
} else {
    // Handle Delete Photo
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $photo_id = intval($_GET['id']);
        try {
            // Fetch image path first
            $stmt = $pdo->prepare("SELECT image_path FROM photos WHERE id = :id");
            $stmt->execute([':id' => $photo_id]);
            $photo = $stmt->fetch();

            if ($photo) {
                // Delete file if local upload
                if (strpos($photo['image_path'], UPLOAD_URL) === 0) {
                    $full_file_path = __DIR__ . '/../' . $photo['image_path'];
                    if (file_exists($full_file_path)) {
                        unlink($full_file_path);
                    }
                }

                // Delete database record
                $del_stmt = $pdo->prepare("DELETE FROM photos WHERE id = :id");
                $del_stmt->execute([':id' => $photo_id]);
                $success = "Photo deleted successfully.";
            }
        } catch (PDOException $e) {
            $error = "Error deleting photo: " . $e->getMessage();
        }
    }

    // Handle Toggle Featured Status
    if (isset($_GET['action']) && $_GET['action'] === 'toggle_featured' && isset($_GET['id'])) {
        $photo_id = intval($_GET['id']);
        try {
            $pdo->prepare("UPDATE photos SET is_featured = NOT is_featured WHERE id = :id")->execute([':id' => $photo_id]);
            $success = "Featured status updated.";
        } catch (PDOException $e) {
            $error = $e->getMessage();
        }
    }
}

// Fetch all photos
$photos = [];
if (isset($pdo) && $pdo !== null) {
    try {
        $stmt = $pdo->query("SELECT p.*, c.name as category_name FROM photos p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
        $photos = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error = $e->getMessage();
    }
}
?>

<div class="admin-header">
    <div>
        <h1 style="font-family: var(--font-heading); font-size: 2.2rem;">Manage Photographs</h1>
        <p style="color: var(--text-muted); font-size: 0.95rem;">View, edit, toggle featured showcase, or remove photos from your collection.</p>
    </div>
    <div>
        <a href="upload.php" class="btn-admin"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Photo</a>
    </div>
</div>

<?php if (!empty($success)): ?>
    <div style="padding: 0.8rem 1.2rem; background: rgba(16, 185, 129, 0.15); border: 1px solid #10b981; border-radius: 8px; color: #34d399; margin-bottom: 1.5rem;">
        <i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($success); ?>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div style="padding: 0.8rem 1.2rem; background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; border-radius: 8px; color: #f87171; margin-bottom: 1.5rem;">
        <i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.8rem;">
    <?php if (empty($photos)): ?>
        <p style="color: var(--text-muted); text-align: center; padding: 3rem;">No photographs uploaded yet. Click "Upload Photo" to get started!</p>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color); color: var(--text-muted);">
                        <th style="padding: 0.8rem 1rem;">Image</th>
                        <th style="padding: 0.8rem 1rem;">Title</th>
                        <th style="padding: 0.8rem 1rem;">Category</th>
                        <th style="padding: 0.8rem 1rem;">Camera Gear</th>
                        <th style="padding: 0.8rem 1rem;">Location</th>
                        <th style="padding: 0.8rem 1rem;">Featured</th>
                        <th style="padding: 0.8rem 1rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($photos as $p): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 0.8rem 1rem;">
                                <img src="../<?php echo htmlspecialchars($p['image_path']); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);" alt="">
                            </td>
                            <td style="padding: 0.8rem 1rem; font-weight: 700; color: var(--text-main);">
                                <?php echo htmlspecialchars($p['title']); ?>
                            </td>
                            <td style="padding: 0.8rem 1rem;">
                                <span class="category-tag" style="position: static; font-size: 0.7rem;"><?php echo htmlspecialchars($p['category_name']); ?></span>
                            </td>
                            <td style="padding: 0.8rem 1rem; color: var(--text-muted); font-size: 0.85rem;">
                                <div><i class="fa-solid fa-camera"></i> <?php echo htmlspecialchars($p['camera']); ?></div>
                                <div style="font-size: 0.8rem;"><i class="fa-solid fa-sliders"></i> <?php echo htmlspecialchars($p['iso']); ?> | <?php echo htmlspecialchars($p['shutter_speed']); ?></div>
                            </td>
                            <td style="padding: 0.8rem 1rem; color: var(--text-muted);"><?php echo htmlspecialchars($p['location']); ?></td>
                            <td style="padding: 0.8rem 1rem;">
                                <a href="photos.php?action=toggle_featured&id=<?php echo $p['id']; ?>" style="color: <?php echo $p['is_featured'] ? '#f59e0b' : 'var(--text-muted)'; ?>; font-size: 1.2rem;" title="Toggle Featured">
                                    <i class="fa-<?php echo $p['is_featured'] ? 'solid' : 'regular'; ?> fa-star"></i>
                                </a>
                            </td>
                            <td style="padding: 0.8rem 1rem;">
                                <a href="edit_photo.php?id=<?php echo $p['id']; ?>" style="color: var(--accent-glow); margin-right: 0.8rem; font-size: 1rem;" title="Edit Details"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                <a href="photos.php?action=delete&id=<?php echo $p['id']; ?>" style="color: #ef4444; font-size: 1rem;" onclick="return confirm('Are you sure you want to delete this photograph?');" title="Delete Photo"><i class="fa-solid fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
