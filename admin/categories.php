<?php
require_once __DIR__ . '/admin_header.php';

$success = '';
$error = '';

// Handle Category Deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $cat_id = intval($_GET['id']);
    try {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
        $stmt->execute([':id' => $cat_id]);
        $success = "Category deleted successfully.";
    } catch (PDOException $e) {
        $error = "Error deleting category: " . $e->getMessage();
    }
}

// Handle Add Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = trim($_POST['name'] ?? '');
    if (!empty($name)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        try {
            $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (:name, :slug)");
            $stmt->execute([':name' => $name, ':slug' => $slug]);
            $success = "Category '{$name}' created successfully.";
        } catch (PDOException $e) {
            $error = "Category already exists or invalid name.";
        }
    } else {
        $error = "Category name cannot be empty.";
    }
}

// Fetch all categories with photo counts
$categories = [];
try {
    $stmt = $pdo->query("SELECT c.*, COUNT(p.id) as photo_count FROM categories c LEFT JOIN photos p ON c.id = p.category_id GROUP BY c.id, c.name, c.slug, c.created_at ORDER BY c.name ASC");
    $categories = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = $e->getMessage();
}
?>

<div class="admin-header">
    <div>
        <h1 style="font-family: var(--font-heading); font-size: 2.2rem;">Manage Categories</h1>
        <p style="color: var(--text-muted); font-size: 0.95rem;">Organize your photography portfolio by custom categories.</p>
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

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem; align-items: start;">
    
    <!-- Add Category Form -->
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.8rem;">
        <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 1.2rem;"><i class="fa-solid fa-folder-plus"></i> Add New Category</h3>
        
        <form action="categories.php" method="POST">
            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="name" required class="form-control" placeholder="e.g. Wedding, Travel, Macro">
            </div>

            <button type="submit" name="add_category" class="btn-submit" style="width: 100%;">
                <i class="fa-solid fa-plus"></i> Create Category
            </button>
        </form>
    </div>

    <!-- Category List Table -->
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1.8rem;">
        <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 1.2rem;"><i class="fa-solid fa-list"></i> Existing Categories</h3>

        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
            <thead>
                <tr style="border-bottom: 1px solid var(--border-color); color: var(--text-muted);">
                    <th style="padding: 0.75rem 1rem;">Category Name</th>
                    <th style="padding: 0.75rem 1rem;">Slug</th>
                    <th style="padding: 0.75rem 1rem;">Photos Count</th>
                    <th style="padding: 0.75rem 1rem;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 0.75rem 1rem; font-weight: 600;"><?php echo htmlspecialchars($cat['name']); ?></td>
                        <td style="padding: 0.75rem 1rem; color: var(--text-muted);"><code><?php echo htmlspecialchars($cat['slug']); ?></code></td>
                        <td style="padding: 0.75rem 1rem;"><span class="pill-count" style="font-size: 0.85rem; padding: 0.2rem 0.6rem;"><?php echo $cat['photo_count']; ?> Photos</span></td>
                        <td style="padding: 0.75rem 1rem;">
                            <a href="categories.php?action=delete&id=<?php echo $cat['id']; ?>" style="color: #ef4444;" onclick="return confirm('Deleting this category will also remove photos under it! Are you sure?');" title="Delete Category"><i class="fa-solid fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
