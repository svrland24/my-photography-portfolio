<?php
require_once __DIR__ . '/admin_header.php';

// Stats queries
$total_photos = 0;
$total_categories = 0;
$total_views = 0;
$recent_photos = [];

if (isset($pdo) && $pdo !== null) {
    try {
        $total_photos = $pdo->query("SELECT COUNT(*) FROM photos")->fetchColumn();
        $total_categories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
        $total_views = $pdo->query("SELECT SUM(views_count) FROM photos")->fetchColumn() ?: 0;

        $stmt = $pdo->query("SELECT p.*, c.name as category_name FROM photos p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC LIMIT 5");
        $recent_photos = $stmt->fetchAll();
    } catch (PDOException $e) {
        $error = $e->getMessage();
    }
}
?>

<div class="admin-header">
    <div>
        <h1 style="font-family: var(--font-heading); font-size: 2.2rem;">Admin Dashboard</h1>
        <p style="color: var(--text-muted); font-size: 0.95rem;">Manage your portfolio, upload new photographs, and organize categories.</p>
    </div>
    <div>
        <a href="upload.php" class="btn-admin"><i class="fa-solid fa-plus"></i> Upload New Photo</a>
    </div>
</div>

<?php if (isset($db_error) && !empty($db_error)): ?>
    <div style="padding: 1.2rem; background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; border-radius: 12px; color: #f87171; margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <i class="fa-solid fa-triangle-exclamation fa-2x"></i>
            <div>
                <h3 style="margin-bottom: 0.2rem;">Database Connection Alert (<?php echo strtoupper($db_mode); ?>)</h3>
                <p style="font-size: 0.9rem; color: #fca5a5;"><?php echo htmlspecialchars($db_error); ?></p>
                <p style="font-size: 0.85rem; margin-top: 0.5rem;"><a href="../test_db.php" target="_blank" style="color: #fff; text-decoration: underline;">Test Connection & Guide &rarr;</a></p>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Key Stat Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
    
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.5rem; display: flex; align-items: center; gap: 1.2rem;">
        <div style="width: 54px; height: 54px; border-radius: 50%; background: rgba(99, 102, 241, 0.15); color: var(--accent-glow); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fa-solid fa-camera"></i>
        </div>
        <div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--text-main);"><?php echo $total_photos; ?></div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">Total Photographs</div>
        </div>
    </div>

    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.5rem; display: flex; align-items: center; gap: 1.2rem;">
        <div style="width: 54px; height: 54px; border-radius: 50%; background: rgba(236, 72, 153, 0.15); color: var(--accent-secondary); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fa-solid fa-folder"></i>
        </div>
        <div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--text-main);"><?php echo $total_categories; ?></div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">Active Categories</div>
        </div>
    </div>

    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.5rem; display: flex; align-items: center; gap: 1.2rem;">
        <div style="width: 54px; height: 54px; border-radius: 50%; background: rgba(16, 185, 129, 0.15); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fa-solid fa-eye"></i>
        </div>
        <div>
            <div style="font-size: 2rem; font-weight: 700; color: var(--text-main);"><?php echo number_format($total_views); ?></div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">Total Gallery Views</div>
        </div>
    </div>

</div>

<!-- Recent Uploads Table -->
<div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 style="font-size: 1.2rem; font-weight: 700;"><i class="fa-solid fa-clock-rotate-left"></i> Recently Uploaded Photos</h3>
        <a href="photos.php" style="color: var(--accent-glow); font-size: 0.9rem; font-weight: 600;">View All Photos &rarr;</a>
    </div>

    <?php if (empty($recent_photos)): ?>
        <p style="color: var(--text-muted); text-align: center; padding: 2rem;">No photos uploaded yet.</p>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color); color: var(--text-muted);">
                        <th style="padding: 0.75rem 1rem;">Image</th>
                        <th style="padding: 0.75rem 1rem;">Title</th>
                        <th style="padding: 0.75rem 1rem;">Category</th>
                        <th style="padding: 0.75rem 1rem;">Camera</th>
                        <th style="padding: 0.75rem 1rem;">Location</th>
                        <th style="padding: 0.75rem 1rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_photos as $p): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 0.75rem 1rem;">
                                <img src="<?php echo htmlspecialchars($p['image_path']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;" alt="">
                            </td>
                            <td style="padding: 0.75rem 1rem; font-weight: 600;"><?php echo htmlspecialchars($p['title']); ?></td>
                            <td style="padding: 0.75rem 1rem;"><span class="category-tag" style="position: static; font-size: 0.7rem;"><?php echo htmlspecialchars($p['category_name']); ?></span></td>
                            <td style="padding: 0.75rem 1rem; color: var(--text-muted);"><?php echo htmlspecialchars($p['camera']); ?></td>
                            <td style="padding: 0.75rem 1rem; color: var(--text-muted);"><?php echo htmlspecialchars($p['location']); ?></td>
                            <td style="padding: 0.75rem 1rem;">
                                <a href="edit_photo.php?id=<?php echo $p['id']; ?>" style="color: var(--accent-glow); margin-right: 0.8rem;" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="photos.php?action=delete&id=<?php echo $p['id']; ?>" style="color: #ef4444;" onclick="return confirm('Are you sure you want to delete this photo?');" title="Delete"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
